<?php

namespace App\Http\Livewire\Client;

use App\Models\Cantons;
use App\Models\Client;
use App\Models\CountryCodes;
use App\Models\Currencies;
use App\Models\Districts;
use App\Models\PaymentMethods;
use App\Models\PriceList;
use App\Models\Province;
use App\Models\SaleConditions;
use App\Models\TypeIdCards;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateClientComponent extends Component
{
    public $code, $total_credit = 0, $refresh, $id_price_list, $id_sale_condition = 1, $id_payment_method = 1, $time = 1, $id_card, $type_id_card = 1, $name_client, $id_currency = 55, $id_province = 0, $id_canton = 0, $id_district = 0, $id_country_code = 52, $number = 1, $other_signs, $emails, $phone;
    public $provinces = [], $cantons = [], $districts = [], $type_id_cards = [], $country_codes = [], $sale_conditions = [], $payment_methods = [], $currencies = [], $allPriceList = [];
    protected $listeners = ['refresh'];
    public function mount()
    {
        $this->type_id_cards = TypeIdCards::all();
        $this->country_codes = CountryCodes::all();
        $this->sale_conditions = SaleConditions::all();
        $this->payment_methods = PaymentMethods::all();
        $this->currencies = Currencies::all();
        $this->allPriceList = PriceList::where('id_company', Auth::user()->currentTeam->id)->get();
        $this->provinces = Province::all();
    }
    public function render()
    {
        $this->code = Client::getCode();
        return view('livewire.client.create-client-component');
    }
    public function refresh($refresh)
    {
        $this->refresh = $refresh;
    }
    public function changeProvince()
    {
        if (!empty($this->id_province)) {
            $this->cantons = Cantons::where("id_province", $this->id_province)->get();
            $this->districts = array();
        } else {
            $this->cantons = array();
            $this->districts = array();
        }
    }
    public function changeCanton()
    {
        if (!empty($this->id_canton)) {
            $this->districts = Districts::where("id_canton", $this->id_canton)->get();
        } else {
            $this->districts = array();
        }
    }
    public function store()
    {
        $this->validate([
            'name_client' => 'required|min:4|max:80',
            'code' => 'required|min:4|max:15',
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
            $result = Client::create([
                'id_company' => Auth::user()->currentTeam->id,
                'code' => $this->code,
                'id_price_list' => ($this->id_price_list) ? $this->id_price_list : null,
                'name_client' => $this->name_client,
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
                'total_credit' => $this->total_credit,
                'id_currency' => $this->id_currency,
                'id_payment_method' => $this->id_payment_method,
            ]);
            $this->resetInputFields();
            $this->dispatchBrowserEvent('clients-updated', [
                'newValue' => $result->id,
                'clients' => Client::where('id_company', '=', Auth::user()->currentTeam->id)->select('name_client as text', 'id')->get()->toArray()
            ]);
            $this->dispatchBrowserEvent('clientsU-updated', [
                'newValue' => $result->id,
                'clients' => Client::where('id_company', '=', Auth::user()->currentTeam->id)->select('name_client as text', 'id')->get()->toArray()
            ]);
            $this->dispatchBrowserEvent('client_modal_hide', []);

            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cliente creado con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        Client::nextCode();
        $this->emit('refreshClientTable');
    }
    public function cancel()
    {
        $this->resetInputFields();
    }
    public function resetInputFields()
    {
        $this->id_price_list = "";
        $this->name_client = "";
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
        $this->total_credit = 0;
    }
}
