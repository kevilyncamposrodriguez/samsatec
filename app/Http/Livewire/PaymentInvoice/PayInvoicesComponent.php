<?php

namespace App\Http\Livewire\PaymentInvoice;

use App\Models\Count;
use App\Models\Document;
use App\Models\PaymentInvoice;
use App\Models\PaymentMethods;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PayInvoicesComponent extends Component
{
    public  $balance, $code_payment_method, $id_count = '', $client = '', $keyDoc = '', $id_document = '', $mountPay = 0, $id_pay = '', $isUpdatePay = false, $mount = 0, $observations = '', $reference = '', $date = '';
    public $allCounts = [], $allPayments = [], $allAcounts = [], $allPaymentMethods = [];
    protected $listeners = ['genPay'];
    public function mount()
    {
        $this->allPaymentMethods = PaymentMethods::all()->sortBy('payment_method');
        $this->allAcounts = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
    }
    public function render()
    {

        if ($this->id_document != '') {
            $this->allPayments = PaymentInvoice::where("payment_invoices.id_document", $this->id_document)
                ->leftJoin('counts', 'payment_invoices.id_count', '=', 'counts.id')
                ->join('documents', 'payment_invoices.id_document', '=', 'documents.id')
                ->join('payment_methods', 'payment_invoices.payment_method', '=', 'payment_methods.code')
                ->select('payment_invoices.*', 'documents.consecutive', 'counts.name as count', 'payment_methods.payment_method as method')->get();
        }
        return view('livewire.payment-invoice.pay-invoices-component');
    }
    public function getAccounts($id)
    {
        $counts = Count::where("counts.id_count", $id)->get();
        if (count($counts) > 0) {
            foreach ($counts as $key => $value) {
                array_push($this->allAcounts, $this->getAccounts($value->id));
            }
        } else {
            return Count::find($id);
        }
    }
    public function genPay($id)
    {
        $this->isUpdatePay = false;
        $this->id_document = $id;
        $doc = Document::where('documents.id', $this->id_document)->join('clients', 'clients.id', '=', 'documents.id_client')
            ->select('documents.*', 'clients.name_client as name_client')->first();
        $this->keyDoc = $doc->consecutive;
        $this->balance = $doc->balance;
        $this->mountPay = $doc->balance;
        $this->client = $doc->name_client;
        $this->date =  date("Y-m-d");
    }
    public function storePay()
    {
        $this->validate([
            'date' => 'required',
            'id_document' => 'required',
            'reference' => (Auth::user()->currentTeam->plan_id == 1) ? '' : 'required',
            'id_count' => (Auth::user()->currentTeam->plan_id == 1) ? '' : 'required',
            'mountPay' => 'required|numeric|min:0',
            'code_payment_method' => 'required|exists:payment_methods,code',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $result = Document::find($this->id_document);
            if ($this->mountPay <= $result->balance) {
                PaymentInvoice::create([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_document' => $this->id_document,
                    'id_count' => ($this->id_count == "") ? null : $this->id_count,
                    'reference' => $this->reference,
                    'payment_method' => $this->code_payment_method,
                    'mount' => $this->mountPay,
                    'observations' => $this->observations
                ]);

                $result->update([
                    'balance' => $result->balance - $this->mountPay
                ]);
                $this->date =  '';
                $this->reference = '';
                $this->observations = '';
                $this->genPay($this->id_document);
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'No se puede agregar un pago mayor al monto pendiente, corrija el monto e intente de nuevo.']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshCXCClientTable');
        $this->emit('refreshDocumentTable');
        $this->cleanInputs();
    }
    public function editPay($id)
    {
        $this->date =  '';
        $this->reference = '';
        $this->observations = '';
        $this->isUpdatePay = true;
        $pay = PaymentInvoice::where("payment_invoices.id", $id)
            ->leftJoin('counts', 'payment_invoices.id_count', '=', 'counts.id')
            ->join('documents', 'payment_invoices.id_document', '=', 'documents.id')
            ->select('payment_invoices.*', 'documents.consecutive', 'counts.name as count')->first();
        $this->id_pay = $pay->id;
        $doc = Document::where('documents.id', $pay->id_document)->join('clients', 'clients.id', '=', 'documents.id_client')
            ->select('documents.*', 'clients.name_client as name_client')->first();

        $this->keyDoc = $doc->consecutive;
        $this->code_payment_method = $pay->payment_method;
        $this->mountPay = $pay->mount;
        $this->client = $doc->name_client;
        $this->id_count = $pay->id_count;
        $this->date =  date("Y-m-d", strtotime($pay->date));
        $this->reference = $pay->reference;
        $this->observations = $pay->observations;
    }
    public function updatePay()
    {
        $this->validate([
            'date' => 'required',
            'id_document' => 'required',
            'reference' => (Auth::user()->currentTeam->plan_id == 1) ? '' : 'required',
            'id_count' => (Auth::user()->currentTeam->plan_id == 1) ? '' : 'required',
            'mountPay' => 'required|numeric|min:0',
            'code_payment_method' => 'required|exists:payment_methods,code',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $pay = PaymentInvoice::find($this->id_pay);
            $doc = Document::find($this->id_document);
            if ($this->mountPay <= ($doc->balance + $pay->mount)) {
                $doc->update([
                    'balance' => $doc->balance + $pay->mount - $this->mountPay
                ]);
                $pay->update([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_document' => $this->id_document,
                    'id_count' => $this->id_count,
                    'reference' => $this->reference,
                    'payment_method' => $this->code_payment_method,
                    'mount' => $this->mountPay,
                    'observations' => $this->observations
                ]);
                $this->genPay($this->id_document);
                $this->date =  '';
                $this->reference = '';
                $this->observations = '';
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'No se puede agregar un pago mayor al monto pendiente, corrija el monto e intente de nuevo.']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshCXCClientTable');
        $this->cleanInputs();
    }
    public function deletePay($id, $mount)
    {
        try {
            if ($id) {
                PaymentInvoice::where('id', $id)->delete();
                $result = Document::find($this->id_document);
                $result->update([
                    'balance' => $result->balance + $mount
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
        $this->emit('refreshDocumentTable');
        $this->emit('refreshCXCClientTable');
        $this->genPay($this->id_document);
        $this->cleanInputs();
    }
    public function cleanInputs()
    {
        $this->date =  $this->date =  date("Y-m-d");
        $this->code_payment_method = '01';
        $this->mountPay = 0;
        $this->id_count = $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';;
        $this->reference = '';
        $this->observations = '';
    }
}
