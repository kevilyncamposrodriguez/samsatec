<?php

namespace App\Http\Livewire\Provider;

use App\Models\PaymentMethods;
use App\Models\Provider;
use App\Models\SaleConditions;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProvidersTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshProviderTable' => '$refresh'];
    public function builder()
    {
        return Provider::query()->where("providers.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('provinces', 'provinces.id', '=', 'providers.id_province')
            ->leftJoin('cantons', 'cantons.id', '=', 'providers.id_canton')
            ->leftJoin('districts', 'districts.id', '=', 'providers.id_district')
            ->leftJoin('country_codes', 'country_codes.id', '=', 'providers.id_country_code')
            ->leftJoin('sale_conditions', 'sale_conditions.id', '=', 'providers.id_sale_condition')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'providers.id_payment_method')
            ->leftJoin('type_id_cards', 'type_id_cards.id', '=', 'providers.type_id_card');
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.provider.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter()
                ->excludeFromExport(),
            DateColumn::name('providers.created_at')
                ->label('Creado')
                ->sortBy('DATE_FORMAT(providers.created_at, "%Y%m%d")')
                ->sortable()
                ->defaultSort("desc")
                ->searchable()
                ->filterable(),
            Column::name('code')
                ->label('Codigo')
                ->searchable()
                ->sortBy('code')
                ->sortable()
                ->filterable(),
            Column::name('id_card')
                ->label('Identificación')
                ->sortBy('id_card')
                ->searchable()
                ->filterable()
                ->sortable()
                ->truncate(),
            Column::name('type_id_cards.type')
                ->label('Tipo de ID')
                ->sortBy('type_id_cards.type')
                ->searchable()
                ->filterable(['Cédula física', 'Cédula jurídica', 'DIMEX', 'NITE'])
                ->sortable()
                ->alignCenter(),
            Column::callback(['name_provider'], function ($name_provider) {
                return $name_provider;
            })->exportCallback(function ($name_provider) {
                return $name_provider;
            })
                ->label('Nombre')
                ->searchable()
                ->sortBy('name_provider')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('phone')
                ->label('Telefono')
                ->searchable()
                ->sortBy('phone')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('emails')
                ->label('Correo')
                ->searchable()
                ->sortBy('emails')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('sale_conditions.sale_condition')
                ->label('Condición de venta')
                ->searchable()
                ->sortBy('sale_conditions.sale_condition')
                ->sortable()
                ->filterable($this->saleconditions->pluck('sale_condition'))
                ->alignCenter(),
            Column::name('time')
                ->label('Plazo de crédito')
                ->searchable()
                ->sortBy('time')
                ->sortable()
                ->filterable($this->saleconditions->pluck('sale_condition'))
                ->alignCenter(),
            Column::name('total_credit')
                ->label('Crédito asignado')
                ->searchable()
                ->sortBy('total_credit')
                ->filterable($this->saleconditions->pluck('sale_condition'))
                ->sortable()
                ->alignCenter(),
            Column::name('payment_methods.payment_method')
                ->label('Metodo Pago')
                ->searchable()
                ->sortBy('payment_methods.payment_method')
                ->filterable($this->paymentmethods->pluck('payment_method'))
                ->sortable()
                ->alignCenter()

        ];
    }
    public function getSaleConditionsProperty()
    {
        return SaleConditions::all();
    }
    public function getPaymentMethodsProperty()
    {
        return PaymentMethods::all();
    }
}
