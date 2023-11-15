<?php

namespace App\Http\Livewire\PaymentInvoice;

use App\Imports\PayImport;
use App\Models\Count;
use App\Models\Document;
use App\Models\PaymentInvoice;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class PaymentInvoiceComponent extends Component
{
    use WithFileUploads;
    public $balance, $file_import, $name, $consecutive, $date, $id_count, $observations, $reference, $mount = '', $payment_id, $id_document = '', $updateMode = false;
    public $allPayments = [], $allCounts = [], $allDocuments = [], $allAcounts = [];

    protected $listeners = ['deletePaymentInvoice' => 'deletePay', 'editPaymentInvoice' => 'edit', 'updatePaymentInvoice' => 'update'];
    public function mount()
    {
        $this->allAcounts = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
    }
    public function render()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allDocuments = Document::where("documents.id_company", "=", Auth::user()->currentTeam->id)
                ->join('clients', 'clients.id', '=', 'documents.id_client')
                ->where('documents.balance', '>', 0)
                ->whereIn('documents.type_doc', ['11', '04', '01', '09'])
                ->select('documents.*', 'clients.name_client as name_client')
                ->get();
        } else {
            $this->allDocuments = Document::where("documents.id_company", "=", Auth::user()->currentTeam->id)
                ->join('clients', 'clients.id', '=', 'documents.id_client')
                ->where('documents.id_terminal', TeamUser::getUserTeam()->terminal)
                ->where('documents.balance', '>', 0)
                ->whereIn('documents.type_doc', ['11', '04', '01', '09'])
                ->select('documents.*', 'clients.name_client as name_client')
                ->get();
        }
        return view('livewire.payment-invoice.payment-invoice-component');
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
    public function changeDoc()
    {
        $this->mount = Document::find($this->id_document)->balance;
        $this->date =  date("Y-m-d");
    }
    private function resetInputFields()
    {
        $this->id_count = '';
        $this->balance = 0;
        $this->date = '';
        $this->observations = '';
        $this->reference = '';
        $this->mount = '';
        $this->payment_id = '';
        $this->id_document = '';
    }
    public function store()
    {
        $this->validate([
            'date' => 'required',
            'id_count' => (Auth::user()->currentTeam->plan_id != 1) ? '' : 'required',
            'reference' => (Auth::user()->currentTeam->plan_id != 1) ? '' : 'required',
            'mount' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->id_document) {
                PaymentInvoice::create([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_document' => $this->id_document,
                    'id_count' => ($this->id_count == "") ? null : $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->mount,
                    'observations' => $this->observations
                ]);
                $result = Document::find($this->id_document);
                $result->update([
                    'balance' => $result->balance - $this->mount
                ]);
            } else {

                while ($this->mount > 0) {
                    if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
                        $doc = Document::where("documents.id_company", "=", Auth::user()->currentTeam->id)
                            ->join('clients', 'clients.id', '=', 'documents.id_client')
                            ->where('documents.balance', '>', 0)
                            ->whereIn('documents.type_doc', ['11', '04', '01', '09'])
                            ->select('documents.*', 'clients.name_client as name_client')
                            ->oldest()->first();
                    } else {
                        $doc = Document::where("documents.id_company", "=", Auth::user()->currentTeam->id)
                            ->join('clients', 'clients.id', '=', 'documents.id_client')
                            ->where('documents.id_terminal', TeamUser::getUserTeam()->terminal)
                            ->where('documents.balance', '>', 0)
                            ->whereIn('documents.type_doc', ['11', '04', '01', '09'])
                            ->select('documents.*', 'clients.name_client as name_client')
                            ->oldest()->first();
                    }
                }
            }
            $this->resetInputFields();
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago(s) realizado con exito', 'refresh' => 0]);
            $this->dispatchBrowserEvent('payment_modal_hide', []);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshPaymentInvoiceTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = PaymentInvoice::where('id', $id)->first();
            $this->payment_id = $id;
            $this->id_count = $result->id_count;
            $this->mount = $result->mount;
            $this->id_document = $result->id_document;
            $doc = Document::where("documents.id", $this->id_document)
                ->join('clients', 'clients.id', '=', 'documents.id_client')
                ->select('documents.*', 'clients.name_client as name_client')
                ->first();
            $this->name = $doc->name_client;
            $this->consecutive = $doc->consecutive;
            $this->reference = $result->reference;
            $this->observations = $result->observations;
            $this->date = str_replace(" ", "T", $result->date);
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('paymentU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('paymentU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate([
            'date' => 'required',
            'id_document' => 'required',
            'id_count' => (Auth::user()->currentTeam->plan_id != 1) ? '' : 'required',
            'reference' => (Auth::user()->currentTeam->plan_id != 1) ? '' : 'required',
            'mount' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->payment_id) {
                $pay = PaymentInvoice::find($this->payment_id);
                $result = Document::find($this->id_document);
                $result->update([
                    'balance' => $result->balance + $pay->mount - $this->mount
                ]);
                $pay->update([
                    'date' => $this->date,
                    'id_document' => $this->id_document,
                    'id_count' => ($this->id_count == "") ? null : $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->mount,
                    'observations' => $this->observations
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago actualizado con exito', 'refresh' => 0]);
                $this->dispatchBrowserEvent('paymentU_modal_hide', []);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshPaymentInvoiceTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                PaymentInvoice::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago eliminado con exito', 'refresh' => 0]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function deletePay($id, $mount, $id_document)
    {
        try {
            if ($id) {
                PaymentInvoice::where('id', $id)->delete();
                $result = Document::find($id_document);
                $result->update([
                    'pending_amount' => $result->balance + $mount
                ]);
                $this->emit('refreshPaymentInvoiceTable');
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago eliminado con exito', 'refresh' => 0]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function importPays()
    {

        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls', // 5MB Max
        ]);
        try {
            if ($this->file_import) {
                Excel::import(new PayImport, $this->file_import);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Se han cargado los pagos con exito']);
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Documento vacio']);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al cargar los archivos' . $e->getMessage()]);
        }
    }
}
