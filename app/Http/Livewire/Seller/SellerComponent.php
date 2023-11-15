<?php

namespace App\Http\Livewire\Seller;

use App\Models\Cantons;
use App\Models\Client;
use App\Models\CountryCodes;
use App\Models\Districts;
use App\Models\Province;
use App\Models\Seller;
use App\Models\TypeIdCards;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SellerComponent extends Component
{
    //datos para manejo de Clientees
    public $seller_id, $allSellers = [], $name_seller, $provinces = [], $cantons = [], $districts = [], $id_province = 0, $id_canton = 0, $id_district = 0, $id_country_code = 52, $number = 1, $country_codes, $other_signs, $emails, $phone;
    public $id_card, $type_id_cards = 1, $type_id_card = 1, $commission = 0;
    //datos generales
    public $isOpen = 0, $message, $updateMode = false, $ante, $file_import;
    public function render()
    {
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
        $this->type_id_cards = TypeIdCards::all();
        $this->provinces = Province::all();
        $this->country_codes = CountryCodes::all();
        $this->country_codes = DB::table('country_codes')->get();
        $this->allSellers = Seller::where("sellers.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('provinces', 'provinces.id', '=', 'sellers.id_province')
            ->leftJoin('cantons', 'cantons.id', '=', 'sellers.id_canton')
            ->leftJoin('districts', 'districts.id', '=', 'sellers.id_district')
            ->leftJoin('country_codes', 'country_codes.id', '=', 'sellers.id_country_code')
            ->leftJoin('type_id_cards', 'type_id_cards.id', '=', 'sellers.type_id_card')
            ->select(
                'sellers.*',
                'provinces.code as province_code',
                'provinces.province as nameProvince',
                'cantons.canton as nameCanton',
                'districts.district as nameDistrict',
                'type_id_cards.type as typeIdCard',
                'cantons.code as canton_code',
                'districts.code as district_code',
                'country_codes.phone_code as phone_code'
            )->get();
        return view('livewire.seller.seller-component');
    }
    public function resetInputFields()
    {

        $this->name_seller = "";
        $this->id_card = "";
        $this->type_id_card = 1;
        $this->id_province = "";
        $this->id_canton = "";
        $this->id_district = "";
        $this->other_signs = "";
        $this->id_country_code = 52;
        $this->phone = "";
        $this->emails = "";
        $this->commission = "";
    }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function store()
    {
        $this->validate([
            'name_seller' => 'required|min:4|max:80',
            'id_card' => 'required|min:9|max:12',
            'type_id_card' => 'required|exists:type_id_cards,id',
            'id_province' => 'required|exists:provinces,id',
            'id_canton' => 'required|exists:cantons,id',
            'id_district' => 'required|exists:districts,id',
            'other_signs' => 'required',
            'commission' => 'required',
            'emails' => 'required|email',
            'phone' => 'required|numeric|min:8|max:8',
            'id_country_code' => 'required|exists:country_codes,id',
            'phone' => 'required|numeric|digits_between:8,8'
        ]);
        DB::beginTransaction();
        try {
            $result = Seller::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name_seller,
                'id_card' => $this->id_card,
                'type_id_card' => $this->type_id_card,
                'id_province' => $this->id_province,
                'id_canton' => $this->id_canton,
                'id_district' => $this->id_district,
                'other_signs' => $this->other_signs,
                'id_country_code' => $this->id_country_code,
                'phone' => $this->phone,
                'emails' => $this->emails,
                'commission' => $this->commission
            ]);
            $this->resetInputFields();
            $this->dispatchBrowserEvent('seller_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Vendedor creado con exito','refresh' => 1]);
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
    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = Seller::where('id', $id)->first();
            $this->seller_id = $id;
            $this->name_seller = $result->name;
            $this->id_card = $result->id_card;
            $this->type_id_card = $result->type_id_card;
            $this->id_province = $result->id_province;
            $this->id_canton = $result->id_canton;
            $this->id_district = $result->id_district;
            $this->other_signs = $result->other_signs;
            $this->id_country_code = $result->id_country_code;
            $this->phone = $result->phone;
            $this->emails = $result->emails;
            $this->commission = $result->commission;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('sellerU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }

    public function update()
    {
        $this->validate([

            'name_seller' => 'required|min:4|max:80',
            'id_card' => 'required|min:9|max:12',
            'type_id_card' => 'required|exists:type_id_cards,id',
            'id_province' => 'required|exists:provinces,id',
            'id_canton' => 'required|exists:cantons,id',
            'id_district' => 'required|exists:districts,id',
            'other_signs' => 'required',
            'emails' => 'required|email',
            'phone' => 'required|numeric|min:8|max:8',
            'phone' => 'required|numeric|digits_between:8,8',
            'commission' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->seller_id) {
                $bo = Seller::find($this->seller_id);
                $bo->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'name' => $this->name_seller,
                    'id_card' => $this->id_card,
                    'type_id_card' => $this->type_id_card,
                    'id_province' => $this->id_province,
                    'id_canton' => $this->id_canton,
                    'id_district' => $this->id_district,
                    'other_signs' => $this->other_signs,
                    'id_country_code' => $this->id_country_code,
                    'phone' => $this->phone,
                    'emails' => $this->emails,
                    'commission' => $this->commission,
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('sellerU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Vendedor actualizado con exito','refresh' => 1]);
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
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            if ($id) {
                $bo = Seller::find($id);
                $bo->update([
                    'active' => '0',
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('sellerU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Vendedor inhabilitado con exito','refresh' => 0]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.'. $e->getMessage()]);
        }
        DB::commit();
    }
    public function enable($id)
    {
        DB::beginTransaction();
        try {
            if ($id) {
                $bo = Seller::find($id);
                $bo->update([
                    'active' => '1',
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('sellerU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Vendedor habilitado con exito']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
        DB::commit();
    }
}
