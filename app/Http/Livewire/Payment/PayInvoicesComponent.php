<?php

namespace App\Http\Livewire\Payment;

use App\Models\Count;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PayInvoicesComponent extends Component
{
    public  $id_count = '', $client = '', $keyDoc = '', $id_expense = '', $mount = 0, $id_pay = '', $isUpdatePay = false, $observations = '', $reference = '', $date = '', $provider;
    public $allCounts = [], $allPayments = [], $allAcounts = [];
    protected $listeners = ['genPayP'];
    public function mount()
    {
        $this->allAcounts = Count::where("counts.id_company", Auth::user()->currentTeam->id)
            ->whereIn('counts.id_count_primary', ['34', '35'])->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
    }
    public function render()
    {

        if ($this->id_expense != '') {
            $this->allPayments = Payment::where("payments.id_expense", $this->id_expense)
                ->leftJoin('counts', 'payments.id_count', '=', 'counts.id')
                ->join('expenses', 'payments.id_expense', '=', 'expenses.id')
                ->select('payments.*', 'expenses.consecutive', 'counts.name as count')->get();
        }
        return view('livewire.payment.pay-invoices-component');
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
    public function genPayP($id)
    {
        $this->cleanInputs();
        $this->isUpdatePay = false;
        $this->id_expense = $id;
        $doc = Expense::where('expenses.id', $this->id_expense)->join('providers', 'providers.id', '=', 'expenses.id_provider')
            ->select('expenses.*', 'providers.name_provider')->first();
        if (substr($doc->consecutive, 0, 3) == 'FCI' || substr($doc->consecutive, 0, 2) == 'OC') {
            $this->keyDoc = $doc->consecutive;
        } else {
            $this->keyDoc = substr($doc->key, 21, 20);
        }
        $this->mount = $doc->pending_amount;
        $this->provider = $doc->name_provider;
    }
    public function cleanInputs()
    {
        $this->id_count = '';
        $this->mount = 0;
        $this->date = date("Y-m-d", strtotime(now()));
        $this->reference = '';
        $this->observations = '';
        $this->allPayments = [];
    }
    public function storePay()
    {
        $this->validate([
            'date' => 'required',
            'id_expense' => 'required',
            'reference' => 'required',
            'mount' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $result = Expense::find($this->id_expense);
            if ($this->mount <= $result->pending_amount) {
                Payment::create([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_expense' => $this->id_expense,
                    'id_count' => ($this->id_count == "") ? null : $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->mount,
                    'observations' => $this->observations
                ]);

                $result->update([
                    'pending_amount' => $result->pending_amount - $this->mount
                ]);
                $this->date =  '';
                $this->reference = '';
                $this->observations = '';
                $this->genPayP($this->id_expense);
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
        $this->emit('refreshVoucherTable');
        $this->emit('refreshCXPProviderTable');
    }
    public function editPay($id)
    {
        $this->date =  '';
        $this->reference = '';
        $this->observations = '';
        $this->isUpdatePay = true;
        $pay = Payment::where("payments.id", $id)
            ->leftJoin('counts', 'payments.id_count', '=', 'counts.id')
            ->join('expenses', 'payments.id_expense', '=', 'expenses.id')
            ->select('payments.*', 'expenses.consecutive', 'counts.name as count')->first();
        $this->id_pay = $pay->id;
        $doc = Expense::where('expenses.id', $pay->id_expense)->join('providers', 'providers.id', '=', 'expenses.id_provider')
            ->select('expenses.*', 'providers.name_provider')->first();

        $this->keyDoc = $doc->consecutive;
        $this->mount = $pay->mount;
        $this->provider = $doc->name_provider;
        $this->id_count = $pay->id_count;
        $this->date =  date("Y-m-d", strtotime($pay->date));
        $this->reference = $pay->reference;
        $this->observations = $pay->observations;
    }
    public function updatePay()
    {
        $this->validate([
            'date' => 'required',
            'id_expense' => 'required',
            'id_count' => 'required',
            'reference' => 'required',
            'mount' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $pay = Payment::find($this->id_pay);
            $doc = Expense::find($this->id_expense);
            if ($this->mount <= ($doc->pending_amount + $pay->mount)) {
                $doc->update([
                    'pending_amount' => $doc->pending_amount + $pay->mount - $this->mount
                ]);
                $pay->update([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_expense' => $this->id_expense,
                    'id_count' => $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->mount,
                    'observations' => $this->observations
                ]);
                $this->genPayP($this->id_expense);
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
        $this->emit('refreshCXPProviderTable');
    }
    public function deletePay($id, $mount)
    {
        try {
            if ($id) {
                Payment::where('id', $id)->delete();
                $result = Expense::find($this->id_expense);
                $result->update([
                    'pending_amount' => $result->pending_amount + $mount
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
        $this->emit('refreshCXPProviderTable');
        $this->genPayP($this->id_expense);
    }
}
