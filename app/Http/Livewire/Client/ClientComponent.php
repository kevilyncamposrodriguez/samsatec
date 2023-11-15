<?php

namespace App\Http\Livewire\Client;

use App\Imports\ClientImport;
use App\Models\Cantons;
use App\Models\CountryCodes;
use App\Models\Currencies;
use App\Models\Districts;
use App\Models\PaymentMethods;
use App\Models\Client;
use App\Models\PriceList;
use App\Models\Province;
use App\Models\SaleConditions;
use App\Models\TypeIdCards;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ClientComponent extends Component
{
    use WithFileUploads;
    //datos para manejo de Clientees
    public $code, $total_credit = 0, $client_id, $allClients = [], $allPriceList, $id_price_list, $name_client, $provinces = [], $cantons = [], $districts = [], $id_currency = 55, $id_province = 0, $id_canton = 0, $id_district = 0, $id_country_code = 52, $number = 1, $country_codes, $other_signs, $emails, $phone;
    public $id_card, $type_id_cards = 1, $sale_conditions = 1, $payment_methods, $currencies, $time = 1, $type_id_card = 1, $id_sale_condition = 1, $id_payment_method = 1;
    //datos generales
    public $isOpen = 0, $message, $updateMode = false, $ante, $file_import;
    protected $listeners = ['editClient' => 'edit', 'deleteClient' => 'delete','showClient'=>'show'];
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
        $this->allPriceList = PriceList::where("price_lists.id_company", "=", Auth::user()->currentTeam->id)->get();;
        $this->type_id_cards = TypeIdCards::all();
        $this->provinces = Province::all();
        $this->country_codes = CountryCodes::all();
        $this->sale_conditions = SaleConditions::all();
        $this->payment_methods = PaymentMethods::all();
        $this->currencies = Currencies::all();
        $this->country_codes = DB::table('country_codes')->get();

        return view('livewire.client.client-component');
    }
    public function show($id){
        return view('livewire.client.show');
    }
    public function importClients()
    {
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls', // 5MB Max
        ]);
        if ($this->file_import) {
            DB::beginTransaction();
            try {
                try {

                    $import = new ClientImport();
                    $import->import($this->file_import);
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Archivo cargado']);
                    $this->dispatchBrowserEvent('productImport_modal_hide', []);
                } catch (ValidationException $e) {
                    $failures = $e->failures();
                    $error = 'Errores al cargar el archivo: ';
                    foreach ($failures as $failure) {
                        $error .= 'linea: ' . $failure->row() . ', Columna: ' . $failure->attribute() . ', error : ' . $failure->errors()[0] . ' --> ';
                    }
                    $this->dispatchBrowserEvent('errorData', ['errorData' => $error]);
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // back to form with errors
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . substr($e->getMessage(), 0, 70)]);
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
            }
            DB::commit();
        } else {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Documento vacio']);
        }

        $this->emit('refreshClientTable');
    }
    public function resetInputFields()
    {
        $this->id_price_list = "";
        $this->name_client = "";
        $this->code = "";
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



    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = Client::where('id', $id)->first();
            $this->client_id = $id;
            $this->code = $result->code;
            $this->id_price_list = $result->id_price_list;
            $this->name_client = $result->name_client;
            $this->id_card = $result->id_card;
            $this->type_id_card = $result->type_id_card;
            $this->id_province = $result->id_province;
            $this->id_canton = $result->id_canton;
            $this->id_district = $result->id_district;
            $this->other_signs = $result->other_signs;
            $this->id_country_code = $result->id_country_code;
            $this->phone = $result->phone;
            $this->emails = $result->emails;
            $this->id_sale_condition = $result->id_sale_condition;
            $this->time = $result->time;
            $this->id_currency = $result->id_currency;
            $this->id_payment_method = $result->id_payment_method;
            $this->total_credit = $result->total_credit;
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

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
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
            if ($this->client_id) {
                $bo = Client::find($this->client_id);
                $bo->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'code' => $this->code,
                    'id_price_list' => $this->id_price_list,
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
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('clientU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cliente actualizado con exito', 'refresh' => 0]);
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
        $this->emit('refreshClientTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                if (Client::where('id', $id)->delete()) {
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cliente eliminado con exito', 'refresh' => 0]);
                    $this->emit('refreshClientTable');
                } else {
                    $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
