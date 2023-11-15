<?php

namespace App\Http\Livewire\Report;

use App\Models\CompaniesEconomicActivities;
use App\Models\MhCategories;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use App\Models\ViewExpensesResume;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ResumeExpensesTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return ViewExpensesResume::query()->where("Compania", Auth::user()->currentTeam->id);
        } else {
            return ViewExpensesResume::query()->where("Compania", Auth::user()->currentTeam->id)
            ->where('idSucursal', TeamUser::getUserTeam()->bo);
        }
        
    }
    public function columns()
    {
        return [
        Column::name('Tipo_Documento')
            ->label('Tipo Documento')
            ->searchable()
            ->filterable()
            ->alignCenter(),
        Column::name('Actividad_Economica')
            ->label('Actividad Economica')
            ->searchable()
            ->filterable()
            ->alignCenter(),
        Column::name('Nombre_Actividad_Economica')
            ->label('Nombre Actividad Economica')
            ->searchable()
            ->truncate()
            ->filterable($this->eas->pluck('name'))
            ->alignCenter(),

        Column::name('Categoria_MH')
            ->label('Categoria MH')
            ->searchable()
            ->filterable($this->eas->pluck('name'))
            ->alignCenter(),

            Column::callback(['Mes'], function ($mes) {
                $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                return $meses[($mes - 1)];
            })
                ->label('Mes')
                ->searchable()
                ->filterable(["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"])
                ->alignCenter(),
     
                DateColumn::name('Fecha')
                ->label('Fecha de compra')
                ->searchable()
                ->filterable()
                ->alignCenter(),
                Column::name('Consecutivo')
                ->label('Consecutivo')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('Cedula')
                ->label('Cedula')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('Proveedor')
                ->label('Proveedor')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Excento')
                ->label('Monto Excento')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Gravado')
                ->label('Monto Gravado')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('discounts')
                ->label('Descuento')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('subtotal')
                ->label('subtotal')
                ->searchable()
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
                NumberColumn::name('total_tax')
                ->label('Total de impuestos')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('total_document')
                ->label('Total de compra')
                ->searchable()
                ->filterable()
                ->alignCenter(),
                Column::name('currency')
                ->label('Moneda')
                ->searchable()
                ->filterable()
                ->alignCenter(),
                NumberColumn::name('exchange_rate')
                ->label('Tipo de Cambio')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('name')
                ->label('name')
                ->searchable()
                ->filterable()
                ->alignCenter(),
                Column::name('name_branch_office')
                ->label('Compañía')
                ->searchable()
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