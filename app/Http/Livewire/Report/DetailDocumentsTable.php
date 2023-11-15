<?php

namespace App\Http\Livewire\Report;

use App\Models\CompaniesEconomicActivities;
use App\Models\Document;
use App\Models\MhCategories;
use App\Models\TeamUser;
use App\Models\ViewSalesDetail;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class DetailDocumentsTable extends LivewireDatatable
{
    public $searchEnabled = false;
    public $hideable = 'select';
    public $exportable = true;
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return ViewSalesDetail::query()->where("compania", Auth::user()->currentTeam->id);
        } else {
            return ViewSalesDetail::query()->where("compania", Auth::user()->currentTeam->id)
                ->where('idSucursal', TeamUser::getUserTeam()->bo);
        }
    }

    public function columns()
    {
        return [
            Column::name('tipo_de_documento')
                ->label('Tipo de documento')
                ->sortBy('tipo_de_documento')
                ->sortable()
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('actividad_economica')
                ->label('Actividad_Economica')
                ->unwrap()
                ->sortBy('actividad_economica')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('nombre_actividad')
                ->label('Nombre de Actividad')
                ->sortBy('nombre_actividad')
                ->unwrap()
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('categoria_mh')
                ->label('Categoria MH')
                ->sortBy('categoria_mh')
                ->sortable()
                ->filterable($this->mhcategories) //el pluck trae todos los elementos diferentes de la columna//
                ->alignCenter(),
            Column::callback(['mes'], function ($mes) {
                $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                return $meses[($mes - 1)];
            })
                ->label('mes')
                ->sortBy('categoria_mh')
                ->sortable()
                ->filterable(["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"])
                ->alignCenter(),
            DateColumn::name('fecha_de_venta')
                ->label('Fecha de Venta')
                ->sortBy('fecha_de_venta')
                ->defaultSort('desc')
                ->sortable()
                ->filterable(),
            Column::name('consecutivo')
                ->label('Consecutivo')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('cedula')
                ->label('Cedula')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('cliente')
                ->label('Cliente')
                ->searchable()
                ->unwrap()
                ->filterable()
                ->alignCenter(),
            Column::name('producto')
                ->label('Producto')
                ->searchable()
                ->unwrap()
                ->filterable()
                ->alignCenter(),
            Column::name('unidad')
                ->label('Unidad')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('cantidad')
                ->label('Cantidad')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('precio')
                ->label('Precio')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('monto')
                ->label('Monto')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('excento')
                ->label('Excento')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('gravado')
                ->label('Gravado')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('monto_exonerado')
                ->label('Monto Exonerado')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('descuento')
                ->label('Descuento')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('subtotal')
                ->label('Subtotal')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('tarifa')
                ->label('Tarifa')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('impuesto_0')
                ->label('Impuesto 0%')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('impuesto_1')
                ->label('Impuesto 1%')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('impuesto_2')
                ->label('Impuesto 2%')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('impuesto_4')
                ->label('Impuesto 4%')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('impuesto_8')
                ->label('Impuesto 8%')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('impuesto_13')
                ->label('Impuesto 13%')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('tarifa_exonerada')
                ->label('Tarifa Exonerada')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('impuesto_exonerado')
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
            Column::name('moneda')
                ->label('Moneda')
                ->filterable()
                ->alignCenter(),
            Column::name('tipo_de_cambio')
                ->label('Tipo de cambio')
                ->filterable()
                ->alignCenter(),
            Column::name('sucursal')
                ->label('Sucursal')
                ->unwrap()
                ->filterable()
                ->alignCenter(),
            Column::name('terminal')
                ->label('Terminal')
                ->filterable()
                ->alignCenter(),
            Column::name('vendedor')
                ->label('Vendedor')
                ->unwrap()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('costo_unitario')
                ->label('Costo Unitario')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('costo_total')
                ->label('Costo Total')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('utilidad')
                ->label('Utilidad')
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
