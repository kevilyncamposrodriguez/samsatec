<?php

namespace App\Http\Livewire\Report\IVA;

use App\Models\CompaniesEconomicActivities;
use App\Models\MhCategories;
use App\Models\VieWIvaDetails;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class IvaDetailsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public function builder()
    {
        return VieWIvaDetails::query()->where("Compania", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [

            Column::name('Tipo_de_Documento')
                ->label('Tipo Documento')
                ->alignCenter(),
            Column::name('Actividad_Economica')
                ->label('Nombre Actividad Economica')
                ->truncate()
                ->alignCenter(),

            Column::name('Categoria_MH')
                ->label('Categoria MH')
                ->filterable($this->eas->pluck('name'))
                ->alignCenter(),

            Column::callback(['Mes'], function ($mes) {
                $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                return $meses[($mes - 1)];
            })
                ->label('Mes')
                ->filterable(["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"])
                ->alignCenter(),

            Column::name('fecha_de_venta')
                ->label('Fecha de venta')
                ->filterable()
                ->alignCenter(),
            Column::name('Consecutivo')
                ->label('Consecutivo')

                ->filterable()
                ->alignCenter(),
            Column::name('Cedula')
                ->label('Cedula')

                ->filterable()
                ->alignCenter(),
            Column::name('Cliente')
                ->label('Cliente')

                ->filterable()
                ->alignCenter(),
            Column::name('Producto')
                ->label('Producto')

                ->truncate()
                ->filterable()
                ->alignCenter(),
            Column::name('Unidad')
                ->label('Unidad')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Cantidad')
                ->label('Cantidad')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Precio')
                ->label('Precio')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Monto')
                ->label('Monto')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Excento')
                ->label('Excento')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Gravado')
                ->label('Gravado')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Monto_Exonerado')
                ->label('Monto Exonerado')

                ->filterable()
                ->alignCenter(),

            NumberColumn::name('Descuento')
                ->label('Descuento')

                ->filterable()
                ->alignCenter(),

            NumberColumn::name('subtotal')
                ->label('subtotal')

                ->filterable()
                ->alignCenter(),

            Column::name('Tarifa')
                ->label('Tarifa')

                ->filterable()
                ->alignCenter(),

            NumberColumn::name('Impuesto_0')
                ->label('Impuesto 0%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_1')
                ->label('Impuesto 1%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_2')
                ->label('Impuesto 2%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_4')
                ->label('Impuesto 4%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_8')
                ->label('Impuesto 8%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_13')
                ->label('Impuesto 13%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Tarifa_Exonerada')
                ->label('Tarifa Exonerada')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_exonerado')
                ->label('Impuesto exonerado')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('total_impuesto')
                ->label('Total Impuesto')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('total_venta')
                ->label('Total Venta')

                ->filterable()
                ->alignCenter(),
            Column::name('Moneda')
                ->label('Moneda')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Tipo_de_cambio')
                ->label('Tipo de Cambio')

                ->filterable()
                ->alignCenter(),
            Column::name('Cuenta_Contable')
                ->label('Cuenta Contable')

                ->filterable()
                ->alignCenter(),
            Column::name('Sucursal')
                ->label('Sucursal')

                ->truncate()
                ->filterable()
                ->alignCenter(),
            Column::name('Terminal')
                ->label('Terminal')

                ->truncate()
                ->filterable()
                ->alignCenter(),
            Column::name('Vendedor')
                ->label('Vendedor')

                ->truncate()
                ->filterable()
                ->alignCenter(),
            Column::name('costo_unitario')
                ->label('costo_unitario')

                ->truncate()
                ->filterable()
                ->alignCenter(),
            Column::name('costo_total')
                ->label('costo_total')

                ->truncate()
                ->filterable()
                ->alignCenter(),
            Column::name('utilidad')
                ->label('utilidad')

                ->truncate()
                ->filterable()
                ->alignCenter()
        ];
    }
    public function getMhCategoriesProperty()
    {
        return MhCategories::all();
    }
    public function getEASProperty()
    {
        return CompaniesEconomicActivities::join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->where('id_company', '=', Auth::user()->currentTeam->id)->get();
    }
}
