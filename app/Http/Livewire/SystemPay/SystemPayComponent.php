<?php

namespace App\Http\Livewire\SystemPay;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use App\Models\PaymentSystem;
use App\Models\Plan;
use App\Models\Ridivi;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;

class SystemPayComponent extends Component
{
    use PasswordValidationRules;
    public $plan, $name, $email, $allPlans = [], $qty, $iva, $total, $monto, $idcard_count, $name_count, $iban, $lastname_count,
        $referredby, $password_confirmation, $password, $terms, $phone, $fe, $detail, $id_fact = '', $price, $discount;
    public $type_idcard, $e_id_card, $e_name, $e_mail, $e_phone, $c_id_card, $c_name, $c_mail, $c_phone, $id_pay;
    public function mount($plan = 1, $referred = null)
    {
        $this->fe = 0;
        $this->discount = 0;
        $this->plan = $plan;
        $this->qty = 1;
        $this->price = 5000;
        $this->monto = $this->qty * Plan::find($this->plan)->price_for_month;
        $this->referredby = $referred;
        $this->allPlans = Plan::all();
        $this->detail = 'Plan ' . Plan::find($this->plan)->name;
        $this->type_idcard = 0;
    }
    public function render()
    {
        $this->qty = ($this->qty == "") ? 1 : $this->qty;
        $this->monto = $this->qty * Plan::find($this->plan)->price_for_month;
        $this->iva = $this->monto * 0.13;
        if ($this->qty > 11) {
            $this->discount = $this->price * 2;
        } else if ($this->qty > 5) {
            $this->discount = $this->price;
        } else {
            $this->discount = 0;
        }
        $this->total = $this->monto + $this->iva-$this->discount;
        $this->detail = Plan::find($this->plan)->name . ' ' . $this->qty . ' mes(es)';
        return view('livewire.system-pay.system-pay-component')->layout("layouts.guest");
    }
    public function register()
    {

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'phone' => ['required', 'numeric', 'digits:8'],
            'idcard_count' => ['required', 'string', 'max:255'],
            'name_count' => ['required', 'string', 'max:255'],
            'lastname_count' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', 'max:255'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ]);
        $company = Team::find(1);
        $token = Ridivi::getToken($company->ridivi_key, $company->ridivi_secret);
        if (isset($token['title'])) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $token['title']]);
        } else {
            $link = Ridivi::getLinkPay($token["token"], $this->name_count, $this->lastname_count, $this->idcard_count, $this->type_idcard, $this->phone, $this->email, $this->total, $this->detail);
            if (isset($link['title'])) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $link['title'].': '.$link['detail']]);
            } else {
                $token = Ridivi::getToken($company->ridivi_key, $company->ridivi_secret);
                if (isset($token['title'])) {
                    $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $token['title']]);
                } else {
                    $pay = Ridivi::proccessPay($token["token"], $link["payKey"], $this->iban, $this->idcard_count);
                    if (isset($pay['title'])) {
                        $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $pay['title'].': '.$pay['detail']]);
                    } else {

                        //register
                        DB::beginTransaction();
                        try {

                            $user = User::create([
                                'name' => $this->name,
                                'email' => $this->email,
                                'password' => Hash::make($this->password),
                            ]);
                            $company = $user->ownedTeams()->save(Team::forceCreate([
                                'user_id' => $user->id,
                                'name' => $user->name,
                                'phone_company' => $this->phone,
                                'personal_team' => true,
                                'plan_id' => $this->plan,
                                'referred_by' => ($this->referredby) ? Team::where('referral_code', $this->referredby)->first()->id : 1
                            ]));
                            $payS = PaymentSystem::create([
                                'id_company' => $company->id,
                                'id_pay' => $pay['id'],
                                'pay_key' => $pay['payKey'],
                                'pay_amount' => $this->total,
                                'pay_detail' => $this->detail,
                                'user' => $this->email,
                                'fe' => $this->fe,
                                'months' => $this->qty,
                                'plan_id' => $this->plan,
                                'type_pay' => 'Ridivi',
                                'start_pay' => date("Y-m-d H:i:s")
                            ]);
                            $user->sendEmailVerificationNotification();
                            $this->dispatchBrowserEvent('successRegister', ['messageData' => 'Pago y registro realizado con éxito, comprobante #' . $pay['id'] . ', estado ' . $pay['statePay'], 'refresh' => 1]);
                        } catch (QueryException $e) {
                            // back to form with errors
                            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacions.' . $e->getMessage()]);
                            DB::rollback();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
                        }
                        DB::commit();
                        //endRegister

                    }
                }
            }
        }
    }
    public function registerFree()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'phone' => ['required', 'numeric', 'digits:8'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ]);
        $company = Team::find(1);
        $this->qty = 2;
        //register
        DB::beginTransaction();
        try {

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $company = $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => $user->name,
                'phone_company' => $this->phone,
                'personal_team' => true,
                'plan_id' => $this->plan,
                'referred_by' => ($this->referredby) ? Team::where('referral_code', $this->referredby)->first()->id : 1
            ]));
            $payS = PaymentSystem::create([
                'id_company' => $company->id,
                'pay_amount' => $this->total,
                'pay_detail' => $this->detail,
                'user' => $this->email,
                'fe' => $this->fe,
                'months' => 1,
                'plan_id' => $this->plan,
                'start_pay' => date("Y-m-d H:i:s", strtotime(date("d-m-Y") . "+ " . 1 . " month"))
            ]);
            $user->sendEmailVerificationNotification();
            $this->dispatchBrowserEvent('successRegister', ['messageData' => 'Registro realizado con éxito, la fecha del proximo pago será el ' . date("Y-m-d", strtotime(date("d-m-Y") . "+ " . 1 . " month")), 'refresh' => 1]);
        } catch (QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacions.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
    }
    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => $user->name,
            'phone_company' => $this->phone,
            'plan_id' => $this->plan,
            'next_pay' => date("Y-m-d H:i:s", strtotime(date("d-m-Y") . "+ " . $this->qty . " month")),
            'personal_team' => true
        ]));
    }
}
