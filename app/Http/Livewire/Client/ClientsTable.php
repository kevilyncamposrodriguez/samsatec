<?php

namespace App\Http\Livewire\Client;

use App\Models\Client;
use App\Models\PaymentMethods;
use App\Models\SaleConditions;
use App\Models\TypeIdCards;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshClientTable' => '$refresh'];
    public function builder()
    {
        return Client::query()->where("clients.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('provinces', 'provinces.id', '=', 'clients.id_province')
            ->leftJoin('cantons', 'cantons.id', '=', 'clients.id_canton')
            ->leftJoin('districts', 'districts.id', '=', 'clients.id_district')
            ->leftJoin('country_codes', 'country_codes.id', '=', 'clients.id_country_code')
            ->leftJoin('sale_conditions', 'sale_conditions.id', '=', 'clients.id_sale_condition')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'clients.id_payment_method')
            ->leftJoin('price_lists', 'price_lists.id', '=', 'clients.id_price_list')
            ->leftJoin('type_id_cards', 'type_id_cards.id', '=', 'clients.type_id_card');
    }

    public function columns()
    {
        if(Auth::user()->currentTeam->plan_id > 3){
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.client.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter()
                ->excludeFromExport(),
            Column::callback(['name_client', 'id'], function ($client, $id_client) {
                return view('livewire.client.link', ['client' => $client, 'id_client' => $id_client]);
            })->exportCallback(function ($client) {
                return $client;
            })
            ->label('Nombre')
                ->unwrap()
                ->searchable()
                ->defaultSort('asc')
                ->filterable()
                ->alignCenter(),
            Column::name('id_card')
                ->label('Identificación')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::callback(["id", "type_id_cards.id", "type_id_cards.type"], function ($modelId, $valueId, $valueName) {
                return view('livewire.client.select', [
                    'rowId' => $modelId,
                    'modelName' => "client",
                    'nullable' => false,
                    'valueId' => $valueId, // myModel.user_id
                    'options' => TypeIdCards::All(), // [["id" => , "name" => , ....], ...]
                ]);
            })->exportCallback(function ($valueName) {
                return $valueName;
            })
                ->label('Tipo de ID')
                ->filterable()
                ->alignCenter(),
            Column::name('phone')
                ->label('Telefono')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('emails')
                ->label('Correo')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('sale_conditions.sale_condition')
                ->label('Condición de venta')
                ->searchable()
                ->filterable($this->saleconditions->pluck('sale_condition'))
                ->alignCenter(),
            Column::name('time')
                ->label('Plazo de crédito')
                ->searchable()
                ->filterable($this->saleconditions->pluck('sale_condition'))
                ->alignCenter(),
            Column::name('total_credit')
                ->label('Crédito asignado')
                ->searchable()
                ->alignCenter(),
            Column::name('payment_methods.payment_method')
                ->label('Metodo Pago')
                ->searchable()
                ->filterable($this->paymentmethods->pluck('payment_method'))
                ->alignCenter(),
            Column::name('price_lists.name')
                ->label('Lista Precio')
                ->filterable()
                ->alignCenter()

        ];
    }else{
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.client.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter()
                ->excludeFromExport(),
            Column::callback(['name_client', 'id'], function ($client, $id_client) {
                return view('livewire.client.link', ['client' => $client, 'id_client' => $id_client]);
            })->exportCallback(function ($client) {
                return $client;
            })
                ->unwrap()
                ->searchable()
                ->defaultSort('asc')
                ->filterable()
                ->alignCenter(),
            Column::name('id_card')
                ->label('Identificación')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::callback(["id", "type_id_cards.id", "type_id_cards.type"], function ($modelId, $valueId, $valueName) {
                return view('livewire.client.select', [
                    'rowId' => $modelId,
                    'modelName' => "client",
                    'nullable' => false,
                    'valueId' => $valueId, // myModel.user_id
                    'options' => TypeIdCards::All(), // [["id" => , "name" => , ....], ...]
                ]);
            })->exportCallback(function ($valueName) {
                return $valueName;
            })
                ->label('Tipo de ID')
                ->filterable()
                ->alignCenter(),
            Column::name('phone')
                ->label('Telefono')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('emails')
                ->label('Correo')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('sale_conditions.sale_condition')
                ->label('Condición de venta')
                ->searchable()
                ->filterable($this->saleconditions->pluck('sale_condition'))
                ->alignCenter(),
            Column::name('time')
                ->label('Plazo de crédito')
                ->searchable()
                ->filterable($this->saleconditions->pluck('sale_condition'))
                ->alignCenter(),
            Column::name('total_credit')
                ->label('Crédito asignado')
                ->searchable()
                ->alignCenter(),
            Column::name('payment_methods.payment_method')
                ->label('Metodo Pago')
                ->searchable()
                ->filterable($this->paymentmethods->pluck('payment_method'))
                ->alignCenter()
        ];
    }
    }
    public function getSaleConditionsProperty()
    {
        return SaleConditions::all();
    }
    public function getPaymentMethodsProperty()
    {
        return PaymentMethods::all();
    }
    public function saveData($rowId, $value)
    {
        if ($value === "")
            $value = null;
        Client::where('id', $rowId)->update(["type_id_card" => $value]);
    }
}
