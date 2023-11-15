<?php

namespace App\Http\Livewire\Provider;

use App\Models\Cantons;
use App\Models\CountryCodes;
use App\Models\Currencies;
use App\Models\Districts;
use App\Models\PaymentMethods;
use App\Models\Provider;
use App\Models\ProviderAccount;
use App\Models\Province;
use App\Models\SaleConditions;
use App\Models\TypeIdCards;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateProviderComponent extends Component
{
    //datos para manejo de proveedores
    public $account_sinpe, $total_credit = 0, $refresh = 0, $provider_id, $allProviders, $code, $name_provider, $provinces = [], $cantons = [], $districts = [], $id_currency = 55, $name_currency, $id_province = 0, $id_canton = 0, $id_district = 0, $id_country_code = 52, $name_country_code, $number = 1, $country_codes, $other_signs, $emails, $phone;
    public $id_card, $type_id_cards = 1, $sale_conditions = 1, $payment_methods, $currencies, $time = 1, $type_id_card = 1, $id_sale_condition = 1, $id_payment_method = 1;
    //datos generales
    public $isOpen = 0, $message, $updateMode = false, $ante, $allProviderAccounts = [], $allProviderAccountsU = [], $account_provider_account = '', $description_provider_account = '';


    public function mount()
    {
        $this->type_id_cards = TypeIdCards::all();
        $this->provinces = Province::all();
        $this->country_codes = CountryCodes::all();
        $this->sale_conditions = SaleConditions::all();
        $this->payment_methods = PaymentMethods::all();
        $this->currencies = Currencies::all();
        $this->country_codes = CountryCodes::all();
        $this->resetInputFields();
    }
    public function render()
    {
        $this->code = Provider::getCode();
        if ($this->updateMode) {
            $this->cantons = Cantons::where("id_province", $this->id_province)->get();
            $this->districts = Districts::where("id_canton", $this->id_canton)->get();
        } else {
            if (!empty($this->id_province)) {
                if ($this->ante != $this->id_province) {
                    $this->ante = $this->id_province;
                    $this->cantons = Cantons::where("id_province", $this->id_province)->get();
                    $this->districts = array();
                } else {
                    if (!empty($this->id_canton)) {
                        $this->districts = Districts::where("id_canton", $this->id_canton)->get();
                    } else {
                        $this->districts = array();
                    }
                }
            } else {
                $this->cantons = array();
                $this->districts = array();
            }
        }
        if ($country = $this->country_codes->where('phone_code', $this->name_country_code)->first()) {
            $this->id_country_code = $country->id;
        } else {
            $this->id_country_code = '';
        }
        if ($currency = $this->currencies->where('code', $this->name_currency)->first()) {
            $this->id_currency = $currency->id;
        } else {
            $this->id_currency = '';
        }
        return view('livewire.provider.create-provider-component');
    }
    public function deleteAccounts($id)
    {

        try {
            unset($this->allProviderAccounts[$id]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados.']);
        }
    }
    public function resetInputFields()
    {
        $this->code = "";
        $this->name_provider = "";
        $this->id_card = "";
        $this->type_id_card = 1;
        $this->id_province = "";
        $this->id_canton = "";
        $this->id_district = "";
        $this->other_signs = "";
        $this->id_country_code = 52;
        $this->phone = "";
        $this->emails = "";
        $this->id_sale_condition = 1;
        $this->time = 1;
        $this->id_currency = 55;
        $this->id_payment_method = 1;
        $this->account_provider_account = '';
        $this->description_provider_account = '';
        $this->allProviderAccounts = [];
        $this->total_credit = 0;
        $this->name_country_code = '506';
        $this->name_currency = 'CRC';
    }

    public function store()
    {
        $this->validate([
            'code' => 'required|min:4|max:15',
            'name_provider' => 'required|min:4|max:80',
            'id_card' => 'required|min:9|max:12',
            'type_id_card' => 'required|exists:type_id_cards,id',
            'id_province' => 'required|exists:provinces,id',
            'id_canton' => 'required|exists:cantons,id',
            'id_district' => 'required|exists:districts,id',
            'other_signs' => 'required',
            'emails' => 'required|email',
            'phone' => 'required|numeric|min:8|max:8',
            'id_country_code' => 'required|exists:country_codes,id',
            'id_sale_condition' => 'required|exists:country_codes,id',
            'id_payment_method' => 'required|exists:payment_methods,id',
            'phone' => 'required|numeric|digits_between:8,8',
            'time' => 'required|numeric'
        ]);
        DB::beginTransaction();
        try {
            $result = Provider::create([
                'id_company' => Auth::user()->currentTeam->id,
                'code' => $this->code,
                'name_provider' => $this->name_provider,
                'id_card' => $this->id_card,
                'type_id_card' => $this->type_id_card,
                'id_province' => $this->id_province,
                'id_canton' => $this->id_canton,
                'id_district' => $this->id_district,
                'other_signs' => $this->other_signs,
                'id_country_code' => $this->id_country_code,
                'phone' => $this->phone,
                'emails' => $this->emails,
                'id_sale_condition' => $this->id_sale_condition,
                'time' => $this->time,
                'total_credit' => ($this->id_sale_condition != 2) ? 0 : $this->total_credit,
                'id_currency' => $this->id_currency,
                'id_payment_method' => $this->id_payment_method,
            ]);

            if (count($this->allProviderAccounts) > 0) {
                foreach ($this->allProviderAccounts as $account) {
                    ProviderAccount::create([
                        'id_provider' => $result->id,
                        'description' => $account['description'],
                        'account' => $account['account'],
                        'sinpe' => $account['sinpe']
                    ]);
                }
            }
            $this->resetInputFields();
            $this->dispatchBrowserEvent('provider_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Proveedor creado con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        Provider::nextCode();
        $this->emit('refreshProviderTable');
    }
    public function addAccounts()
    {
        $this->validate([
            'account_provider_account' =>  ($this->account_provider_account)?'':'required',
            'description_provider_account' => 'required',
            'account_sinpe' => ($this->account_provider_account)?'':'required|min:8|max:8'
        ]);
        try {
            array_push($this->allProviderAccounts, ["description" => $this->description_provider_account, "account" => $this->account_provider_account, "sinpe" => $this->account_sinpe]);
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede agregar.']);
        }

        $this->description_provider_account = '';
        $this->account_provider_account = '';
        $this->account_sinpe = '';
    }
}
