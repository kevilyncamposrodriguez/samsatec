<?php

namespace App\Http\Livewire\Buyer;

use App\Models\Buyer;
use App\Models\Cantons;
use App\Models\CountryCodes;
use App\Models\Districts;
use App\Models\Provider;
use App\Models\Province;
use App\Models\TypeIdCards;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BuyerComponent extends Component
{
    public $type_id_cards = [], $provinces = [], $cantons = [], $districts = [], $country_codes, $allProviders;
    public $id_province, $id_canton, $id_district, $type_id_card, $id_card, $name_buyer, $other_signs,
        $emails, $phone, $name_country, $id_provider, $name_provider, $id_buyer;
    public $updateMode = 0, $ante;
    protected $listeners = ['editBuyer' => 'edit', 'deleteBuyer' => 'delete'];
    public function mount()
    {
        $this->type_id_cards = TypeIdCards::all();
        $this->country_codes = CountryCodes::all();
        $this->type_id_card = 1;
        $this->provinces = Province::all();
        $this->allProviders = Provider::where("providers.id_company", "=", Auth::user()->currentTeam->id)->get();
    }
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
        if ($this->allProviders->where('name_provider', $this->name_provider)->first()) {
            $this->id_provider = $this->allProviders->where('name_provider', $this->name_provider)->first()->id;
        }
        if ($code = $this->country_codes->where('phone_code', strtok($this->name_country, '- '))->first()) {
            $this->id_country_code = $code->id;
        }
        return view('livewire.buyer.buyer-component');
    }
    public function delete($id)
    {
        try {
            if ($id) {
                $buyer = Buyer::where('id', $id)->first();
                if (Buyer::where('id', $id)->update(
                    [
                        'active' => '0',
                    ]
                )) {
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Comprador eliminado con exito', 'refresh' => 1]);
                    $this->emit('refreshBuyerTable');
                } else {
                    $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function edit($id)
    {
        $this->resetInputFields();
        try {
            $this->updateMode = true;
            $result = Buyer::where('id', $id)->first();
            $provider = Provider::find($result->id_provider);
            $this->id_provider = $provider->id;
            $this->name_provider = $provider->name_provider;
            $this->id_buyer = $id;
            $this->name_buyer = $result->name;
            $this->id_card = $result->id_card;
            $this->type_id_card = $result->type_id_card;
            $this->id_province = $result->id_province;
            $this->id_canton = $result->id_canton;
            $this->id_district = $result->id_district;
            $this->other_signs = $result->other_signs;
            $code = CountryCodes::find($result->id_country_code);
            $this->name_country = $code->phone_code . ' - ' . $code->name;
            $this->id_country_code = $code->id;
            $this->phone = $result->phone;
            $this->emails = $result->emails;
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
    public function update()
    {
        $this->validate([
            'id_provider' => 'required|exists:providers,id',
            'name_buyer' => 'required|min:4|max:80',
            'id_card' => 'required|min:9|max:12',
            'type_id_card' => 'required|exists:type_id_cards,id',
            'id_province' => 'required|exists:provinces,id',
            'id_canton' => 'required|exists:cantons,id',
            'id_district' => 'required|exists:districts,id',
            'other_signs' => 'required',
            'emails' => 'required|email',
            'phone' => 'required|numeric|min:8|max:8',
            'id_country_code' => 'required|exists:country_codes,id',
            'phone' => 'required|numeric|digits_between:8,8'
        ]);
        DB::beginTransaction();
        try {
            if ($this->id_buyer) {
                $result = Buyer::find($this->id_buyer);
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                'id_provider' => $this->id_provider,
                'id_card' => $this->id_card,
                'type_id_card' => $this->type_id_card,
                'name' => $this->name_buyer,
                'id_province' => $this->id_province,
                'id_canton' => $this->id_canton,
                'id_district' => $this->id_district,
                'other_signs' => $this->other_signs,
                'id_country_code' => $this->id_country_code,
                'phone' => $this->phone,
                'emails' => $this->emails
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('buyerU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Comprador actualizado con exito', 'refresh' => 0]);
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
        $this->emit('refreshBuyerTable');
    }
    public function resetInputFields()
    {
        $this->id_provider = '';
        $this->name_provider = '';
        $this->name_buyer = "";
        $this->id_card = "";
        $this->type_id_card = 1;
        $this->id_province = "";
        $this->id_canton = "";
        $this->id_district = "";
        $this->other_signs = "";
        $this->name_country = '506 - COSTA RICA';
        $this->id_country_code = 52;
        $this->phone = "";
        $this->emails = "";
    }
}
