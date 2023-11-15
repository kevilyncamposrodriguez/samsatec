<?php

namespace App\Http\Livewire\Credits;

use App\Models\Count;
use App\Models\Credit;
use App\Models\CreditPayment;
use App\Models\Currencies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreditComponent extends Component
{
    public $allCurrencies = [], $allCredits = [], $credit_id, $financial_entity, $credit_number, $date_issue,
        $pay_day = 1, $formalization_expenses, $credit_rate, $period, $currency = 'CRC', $taxed, $other_expenses, $savings, $pay_id;
    //pay
    public  $month_payment = 1, $date_pay = '', $credit_mount = 1, $capital_contribution = 0, $loan_interest = 0,
        $other_interest = 0, $interest_late_payment = 0, $safe = 0, $endorsement = 0, $policy = 0, $saving = 0,
        $months = [], $other_saving = 0, $total_fee = 0, $allFees = [], $allCounts = [], $id_count = '', $isUpdatePay = false;
    protected $listeners = ['editCredit' => 'edit', 'deleteCredit' => 'delete', 'genPayCredit' => 'genPay'];
    public function mount()
    {
        $this->months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $this->allCounts = Count::where("counts.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allCurrencies = Currencies::all();
    }
    public function render()
    {
        if ($this->credit_id != '') {
            $this->allFees = CreditPayment::where("credit_payments.id_credit", $this->credit_id)
                ->join('counts', 'counts.id', '=', 'credit_payments.id_count')
                ->select(
                    'credit_payments.*',
                    'counts.name as count',
                )->get();
        }
        $this->feeCalculate();
        return view('livewire.credits.credit-component');
    }
    public function feeCalculate()
    {
        try {
            $this->total_fee = $this->capital_contribution + $this->loan_interest + $this->other_interest + $this->interest_late_payment +
                $this->endorsement + $this->policy + $this->saving + $this->safe + $this->other_saving;
        } catch (\Exception $e) {
            $this->total_fee = 0;
        }
    }
    public function storePay()
    {
        $this->validate([
            'month_payment' => 'required',
            'id_count' => 'required',
            'date_pay' => 'required',
            'capital_contribution' => 'required',
            'loan_interest' => 'required',
            'other_interest' => 'required',
            'interest_late_payment' => 'required',
            'safe' => 'required',
            'endorsement' => 'required',
            'policy' => 'required',
            'saving' => 'required',
            'other_saving' => 'required',
            'total_fee' => 'required'
        ]);
        DB::beginTransaction();
        try {
            CreditPayment::create([
                'id_credit' => $this->credit_id,
                'id_count' => $this->id_count,
                'month_payment' => $this->month_payment,
                'capital_contribution' => $this->capital_contribution,
                'loan_interest' => $this->loan_interest,
                'other_interest' => $this->other_interest,
                'interest_late_payment' => $this->interest_late_payment,
                'safe' => $this->safe,
                'endorsement' => $this->endorsement,
                'policy' => $this->policy,
                'saving' => $this->saving,
                'other_saving' => $this->other_saving,
                'total_fee' => $this->total_fee,
                'date_pay' => $this->date_pay
            ]);

            $this->resetInputFieldsPays();
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cuota agregada con exito']);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
    }
    public function resetInputFieldsPays()
    {
        $this->month_payment = 1;
        $this->capital_contribution = 0;
        $this->loan_interest = 0;
        $this->other_interest = 0;
        $this->interest_late_payment = 0;
        $this->safe = 0;
        $this->endorsement = 0;
        $this->policy = 0;
        $this->saving = 0;
        $this->other_saving = 0;
        $this->total_fee = 0;
        $this->date_pay = '';
    }
    public function editPay($id)
    {
        try {
            $this->isUpdatePay = true;
            $this->pay_id = $id;
            $result = CreditPayment::where('id', $id)->first();
            $this->month_payment = $result->month_payment;
            $this->capital_contribution = $result->capital_contribution;
            $this->loan_interest = $result->loan_interest;
            $this->other_interest = $result->other_interest;
            $this->interest_late_payment = $result->interest_late_payment;
            $this->safe = $result->safe;
            $this->id_count = $result->id_count;
            $this->endorsement = $result->endorsement;
            $this->policy = $result->policy;
            $this->saving = $result->saving;
            $this->other_saving = $result->other_saving;
            $this->total_fee = $result->total_fee;
            $this->date_pay = date("Y-m-d", strtotime($result->date_pay));
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }
    public function resetInputFields()
    {
        $this->allCredits = [];
        $this->credit_id = '';
        $this->financial_entity = '';
        $this->credit_number = '';
        $this->date_issue = '';
        $this->formalization_expenses = 0;
        $this->credit_rate = 0;
        $this->period = 0;
        $this->currency = 'CRC';
        $this->taxed = 0;
        $this->other_expenses = '';
        $this->savings = '';
    }
    public function genPay($id)
    {
        $this->resetInputFieldsPays();
        $this->credit_id = $id;
        $result = Credit::where('id', $id)->first();
        $this->credit_number =  $result->credit_number;
        $this->financial_entity =  $result->financial_entity;
    }
    public function store()
    {
        $this->validate([
            'financial_entity' => 'required',
            'credit_number' => 'required',
            'credit_mount' => 'required',
            'date_issue' => 'required',
            'formalization_expenses' => 'required',
            'credit_rate' => 'required',
            'period' => 'required',
            'currency' => 'required',
            'taxed' => 'required',
            'pay_day' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Credit::create([
                'id_company' => Auth::user()->currentTeam->id,
                'financial_entity' => $this->financial_entity,
                'credit_number' => $this->credit_number,
                'credit_mount' => $this->credit_mount,
                'date_issue' => $this->date_issue,
                'formalization_expenses' => $this->formalization_expenses,
                'credit_rate' => $this->credit_rate,
                'period' => $this->period,
                'currency' => $this->currency,
                'taxed' => $this->taxed,
                'pay_day' => $this->pay_day
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('credit_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Credito creado con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshCreditTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = Credit::where('id', $id)->first();
            $this->credit_id = $id;
            $this->financial_entity = $result->financial_entity;
            $this->credit_number = $result->credit_number;
            $this->date_issue = date("Y-m-d", strtotime($result->date_issue));
            $this->formalization_expenses = $result->formalization_expenses;
            $this->credit_rate = $result->credit_rate;
            $this->period = $result->period;
            $this->currency = $result->currency;
            $this->taxed = $result->taxed;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }
    public function updatePay()
    {
        $this->validate([
            'month_payment' => 'required',
            'id_count' => 'required',
            'date_pay' => 'required',
            'capital_contribution' => 'required',
            'loan_interest' => 'required',
            'other_interest' => 'required',
            'interest_late_payment' => 'required',
            'safe' => 'required',
            'endorsement' => 'required',
            'policy' => 'required',
            'saving' => 'required',
            'other_saving' => 'required',
            'total_fee' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->pay_id) {
                $result = CreditPayment::find($this->pay_id);
                $result->update([
                    'id_credit' => $this->credit_id,
                    'id_count' => $this->id_count,
                    'month_payment' => $this->month_payment,
                    'capital_contribution' => $this->capital_contribution,
                    'loan_interest' => $this->loan_interest,
                    'other_interest' => $this->other_interest,
                    'interest_late_payment' => $this->interest_late_payment,
                    'safe' => $this->safe,
                    'endorsement' => $this->endorsement,
                    'policy' => $this->policy,
                    'saving' => $this->saving,
                    'other_saving' => $this->other_saving,
                    'total_fee' => $this->total_fee,
                    'date_pay' => $this->date_pay
                ]);
                $this->updateMode = false;
                $this->resetInputFieldsPays();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cuota actualizada con exito']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
        }
        DB::commit();
    }
    public function update()
    {
        $this->validate([
            'financial_entity' => 'required',
            'credit_number' => 'required',
            'credit_mount' => 'required',
            'date_issue' => 'required',
            'formalization_expenses' => 'required',
            'credit_rate' => 'required',
            'period' => 'required',
            'currency' => 'required',
            'taxed' => 'required',
            'pay_day' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->credit_id) {
                $result = Credit::find($this->credit_id);
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'financial_entity' => $this->financial_entity,
                    'credit_number' => $this->credit_number,
                    'credit_mount' => $this->credit_mount,
                    'date_issue' => $this->date_issue,
                    'formalization_expenses' => $this->formalization_expenses,
                    'credit_rate' => $this->credit_rate,
                    'period' => $this->period,
                    'currency' => $this->currency,
                    'taxed' => $this->taxed,
                    'pay_day' => $this->pay_day
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('creditU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Credito actualizado con exito', 'refresh' => 1]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshCreditTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Credit::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Credito eliminado con exito', 'refresh' => 1]);
                $this->emit('refreshCreditTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function deletePay($id)
    {
        try {
            if ($id) {
                CreditPayment::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cuota eliminado con exito']);
                $this->emit('refreshCreditTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
