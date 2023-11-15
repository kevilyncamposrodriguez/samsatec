<?php

namespace App\Http\Livewire\Payment;

use App\Models\Count;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaymentComponent extends Component
{
    public $date, $id_count, $observations, $reference, $mount = '', $payment_id, $id_expense = '', $updateMode = false;
    public $allPayments = [], $allCounts = [], $allExpenses = [], $allAcounts = [];
    protected $listeners = ['deletePayment', 'editPayment' => 'edit', 'updatePayment' => 'update'];
    public function mount()
    {
        $this->allAcounts = Count::whereIn("id_count_primary", [34,35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
    }
    public function render()
    {
        if ($this->id_expense != '' && !$this->updateMode && $this->mount == '') {
            $this->mount = Expense::find($this->id_expense)->pending_amount;
        }
        $this->allCounts = Count::where("counts.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allExpenses = Expense::where("expenses.id_company", "=", Auth::user()->currentTeam->id)
            ->join('providers', 'providers.id', '=', 'expenses.id_provider')
            ->select('expenses.*', 'providers.name_provider')
            ->where('expenses.pending_amount', '>', 0)->get();
        return view('livewire.payment.payment-component');
    }
    public function getAccounts($id){
        $counts = Count::where("counts.id_count", $id)->get();
        if (count($counts) > 0) {
            foreach ($counts as $key => $value) {
                array_push($this->allAcounts, $this->getAccounts($value->id));
            }
        } else {
            return Count::find($id);
        }
    }
    private function resetInputFields()
    {
        $this->id_count = '';
        $this->date = '';
        $this->observations = '';
        $this->reference = '';
        $this->mount = '';
        $this->payment_id = '';
        $this->id_expense = '';
    }
    public function store()
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
            Payment::create([
                'date' => $this->date,
                'id_company' => Auth::user()->currentTeam->id,
                'id_expense' => $this->id_expense,
                'id_count' => $this->id_count,
                'reference' => $this->reference,
                'mount' => $this->mount,
                'observations' => $this->observations
            ]);
            $result = Expense::find($this->id_expense);
            $result->update([
                'pending_amount' => $result->pending_amount - $this->mount
            ]);
            $this->resetInputFields();
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago realizado con exito']);
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
        $this->emit('refreshPaymentTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = Payment::where('id', $id)->first();
            $this->payment_id = $id;
            $this->mount = $result->mount;
            $this->id_expense = $result->id_expense;
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
            'id_expense' => 'required',
            'id_count' => 'required',
            'reference' => 'required',
            'mount' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->payment_id) {
                $pay = Payment::find($this->payment_id);
                $result = Expense::find($this->id_expense);
                $result->update([
                    'pending_amount' => $result->pending_amount + $pay->mount - $this->mount
                ]);
                $pay->update([
                    'date' => $this->date,
                    'id_expense' => $this->id_expense,
                    'id_count' => $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->mount,
                    'observations' => $this->observations
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago actualizado con exito']);
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
        $this->emit('refreshPaymentTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Payment::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago eliminado con exito']);
                $this->emit('refreshPaymentInvoiceTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function deletePayment($id, $mount, $id_expense)
    {
        try {
            if ($id) {
                Payment::where('id', $id)->delete();
                $result = Expense::find($id_expense);
                $result->update([
                    'pending_amount' => $result->pending_amount + $mount
                ]);
                $this->emit('refreshPaymentTable');
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago eliminado con exito']);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
