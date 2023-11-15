<?php

namespace App\Http\Livewire\SystemPayAdmin;

use App\Models\PaymentSystem;
use App\Models\Plan;
use App\Models\Ridivi;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class SystemPayAdminComponent extends Component
{
    public $allCompanies, $allPlans, $ridivi, $ridivi_date;
    public $price, $monto, $discount, $iva, $total, $plan, $qty, $id_plan, $name_company, $company, $state, $ps_id, $start_pay, $id_pay;
    protected $listeners = ['editPaySystemAdmin' => 'edit', 'deletePaySystemAdmin'];
    public function mount()
    {
        $this->start_pay = date("Y-m-d");
        $this->allCompanies = Team::where('teams.npay', 0)->get();
        $this->resetInputFields();
    }
    public function render()
    {
        return view('livewire.system-pay-admin.system-pay-admin-component');
    }
    public function changeCompany($nameCompany)
    {
        $this->company = Team::where('name', $nameCompany)->first();
        if ($this->company) {
            $this->plan = Plan::find($this->company->plan_id);
        } else {
            $this->allPlans = Plan::all();
            $this->plan = $this->allPlans[0];
        }
        $this->qty = 1;
        $this->id_plan = $this->plan->id;
        $this->calculate();
    }
    public function changePlan()
    {
        $this->plan = Plan::find($this->id_plan);
        $this->calculate();
    }
    public function changeQTY()
    {
        $this->calculate();
    }
    public function calculate()
    {
        $this->price = $this->plan->price_for_month;
        $this->monto = $this->qty * $this->price;
        if ($this->qty > 11) {
            $this->discount = $this->price * 2;
        } else if ($this->qty > 5) {
            $this->discount = $this->price;
        } else {
            $this->discount = 0;
        }
        $this->iva = ($this->monto - $this->discount) * 0.13;
        $this->total = $this->monto - $this->discount + $this->iva;
    }
    public function create()
    {
    }
    public function store()
    {
        $this->validate([
            'name_company' => 'required|exists:teams,name',
            'qty' => 'required',
            'id_plan' => 'required',
            'id_pay' => ($this->state) ? 'required' : ''
        ]);
        DB::beginTransaction();
        try {
            $ps = PaymentSystem::create([
                'id_company' => $this->company->id,
                'pay_amount' => $this->total,
                'pay_detail' => $this->plan->name . ' ' . $this->qty . ' mes(es)',
                'user' => Auth::user()->email,
                'months' => $this->qty,
                'plan_id' => $this->id_plan,
                'start_pay' => date("Y/m/d", strtotime($this->start_pay)),
                'next_pay' => date("Y/m/d", strtotime($this->start_pay . "+ " . $this->qty . " month")),
                'id_pay' => ($this->state) ? $this->id_pay : null
            ]);
            $company = Team::find($ps->id_company);
            if ($ps->id_plan != $company->plan_id) {
                $company->update([
                    'plan_id' => $this->id_plan
                ]);
            }
            $this->dispatchBrowserEvent('newCharge_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cobro creado con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion ' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion ' . $e->getMessage()]);
            DB::rollback();
        }
        DB::commit();
        $this->resetInputFields();
        $this->emit('refreshSystemPaysAdminTable');
    }
    public function edit($id)
    {
        $ps = PaymentSystem::find($id);
        $this->ps_id = $ps->id;
        $this->qty = $ps->months;
        $this->company =  Team::find($ps->id_company);
        $this->name_company = $this->company->name;
        $this->plan = Plan::find($ps->plan_id);
        $this->id_plan = $this->plan->id;
        $this->state = ($ps->id_pay != '') ? true : false;
        $this->start_pay = $ps->start_pay;
        $this->id_pay = $ps->id_pay;
        $this->start_pay = date("Y-m-d", strtotime($ps->start_pay));
        $this->ridivi = '';
        $this->ridivi_date = '';
        if ($ps->pay_key) {
            $data = $this->getRidiviPay($ps->pay_key);
            $this->ridivi = $data["invoiceNumber"];
            $this->ridivi_date = date("Y-m-d", strtotime($data["createAt"]));
        }
        $this->calculate();
    }
    public function update()
    {
        $this->validate([
            'name_company' => 'required|exists:teams,name',
            'qty' => 'required',
            'id_plan' => 'required',
            'id_pay' => ($this->state) ? 'required' : ''
        ]);

        DB::beginTransaction();
        try {
            $ps = PaymentSystem::find($this->ps_id);
            $id = $ps->id_company;
            $ps->update([
                'id_company' => $id,
                'pay_amount' => $this->total,
                'pay_detail' => $this->plan->name . ' ' . $this->qty . ' mes(es)',
                'user' => Auth::user()->email,
                'months' => $this->qty,
                'plan_id' => $this->id_plan,
                'start_pay' => $this->start_pay,
                'start_pay' => date("Y/m/d", strtotime($this->start_pay)),
                'next_pay' => date("Y/m/d", strtotime($this->start_pay . "+ " . $this->qty . " month")),
                'id_pay' => ($this->state) ? $this->id_pay : null
            ]);
            $company = Team::find($ps->id_company);
            if ($this->id_plan != $company->plan_id) {
                $company->update([
                    'plan_id' => $this->id_plan
                ]);
            }
            $this->dispatchBrowserEvent('editCharge_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cobro creado con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion ' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion ' . $e->getMessage()]);
            DB::rollback();
        }
        DB::commit();
        $this->resetInputFields();
        $this->emit('refreshSystemPaysAdminTable');
    }
    public function deletePaySystemAdmin($id)
    {
        dd('delete');
    }
    public function resetInputFields()
    {
        $this->allPlans = Plan::all();
        $this->name_company = '';
        $this->plan = $this->allPlans[0];
        $this->id_plan = $this->plan->id;
        $this->qty = 1;
        $this->price = $this->plan->price_for_month;
        $this->monto = $this->qty * $this->price;
        $this->discount = 0;
        $this->iva = ($this->monto - $this->discount) * 0.13;
        $this->total = $this->monto - $this->discount + $this->iva;
        $this->start_pay = date("Y-m-d");
        $this->id_pay = '';
        $this->state = 0;
    }
    public function getRidiviPay($payKey)
    {
        $company = Team::find(1);
        $token = Ridivi::getToken($company->ridivi_key, $company->ridivi_secret);
        if (isset($token['title'])) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $token['title']]);
        } else {
            $link = Ridivi::getDataLinkPay($token["token"], $payKey);
            if (isset($link['title'])) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $link['title'] . ': ' . $link['detail']]);
            } else {
                return $link;
            }
        }
    }
}
