<?php

namespace App\Http\Livewire\Report;

use App\Models\CompaniesEconomicActivities;
use App\Models\Document;
use App\Models\MhCategories;
use App\Models\TeamUser;
use App\Models\ViewSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ResumeDocumentsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return ViewSale::query()->where("Compania", Auth::user()->currentTeam->id);
        } else {
            return ViewSale::query()->where("Compania", Auth::user()->currentTeam->id)
            ->where('idSucursal', TeamUser::getUserTeam()->bo);
        }
    }

    public function columns()
    {
        return [
            Column::name('Tipo_de_documento')
                ->label('Tipo de documento')
                ->searchable()
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('Actividad_Economica')
                ->label('Actividad Economica')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('Nombre_Actividad')
                ->label('Nombre de actividad')
                ->unwrap()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('Categoria_MH')
                ->label('Categoria MH')
                ->filterable($this->mhcategories) //el pluck trae todos los elementos diferentes de la columna//
                ->alignCenter(),
            Column::raw('(CASE 
            WHEN Mes = "1" THEN "Enero"
            WHEN Mes = "2" THEN "Febrero"
            WHEN Mes = "3" THEN "Marzo"
            WHEN Mes = "4" THEN "Abril"
            WHEN Mes = "5" THEN "Mayo"
            WHEN Mes = "6" THEN "Junio"
            WHEN Mes = "7" THEN "Julio"
            WHEN Mes = "8" THEN "Agosto"
            WHEN Mes = "9" THEN "Setiembre"
            WHEN Mes = "10" THEN "Octubre"
            WHEN Mes = "11" THEN "Noviembre"
            WHEN Mes = "12" THEN "Diciembre"
            ELSE "Enero" END) AS "Mes"')
                ->label('Mes')
                ->filterable(["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"])
                ->sortable()
                ->alignCenter(),
            DateColumn::name('fecha_de_venta')
                ->label('Fecha de venta')
                ->defaultSort("desc")
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('Consecutivo')
                ->label('Consecutivo')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('Cedula')
                ->label('Cedula')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('Cliente')
                ->label('Cliente')
                ->searchable()
                ->unwrap()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            NumberColumn::name('total_net_sale')
                ->label('Total de venta')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Excento')
                ->label('Monto Excento')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Gravado')
                ->label('Monto Gravado')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Descuento')
                ->label('Descuento')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_0')
                ->label('Impuesto 0%')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_1')
                ->label('Impuesto 1%')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_2')
                ->label('Impuesto 2%')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_4')
                ->label('Impuesto 4%')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_8')
                ->label('Impuesto 8%')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_13')
                ->label('Impuesto 13%')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Total_Impuestos')
                ->label('Total Impuestos')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Total_de_venta')
                ->label('Total de venta')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('currency')
                ->label('Moneda')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            NumberColumn::name('exchange_rate')
                ->label('Tipo de Cambio')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('name_branch_office')
                ->label('Empresa')
                ->searchable()
                ->truncate()
                ->unwrap()
                ->filterable()
                ->alignCenter(),
            Column::name('number')
                ->label('Terminal')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('name_seller')
                ->label('Vendedor')
                ->searchable()
                ->filterable()
                ->unwrap()
                ->alignCenter(),
            Column::name('Referencia')
                ->label('Referencia')
                ->searchable()
                ->filterable()
                ->alignCenter()

        ];
    }
    public function getMhCategoriesProperty()
    {
        return MhCategories::pluck('name');
    }
    public function getEASProperty()
    {
        return CompaniesEconomicActivities::join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->where('id_company', '=', Auth::user()->currentTeam->id)->get();
    }
}
