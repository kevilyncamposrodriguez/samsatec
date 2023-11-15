<?php

namespace App\Http\Livewire\Report;

use App\Http\Livewire\Expense\ExpensesTable;
use App\Models\CompaniesEconomicActivities;
use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\MhCategories;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ResumeExpensesTableq extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public function builder()
    {
        return Expense::where('expenses.id_company', Auth::user()->currentTeam->id)
            ->join('providers', 'providers.id', '=', 'expenses.id_provider')
            ->join('mh_categories', 'mh_categories.id', '=', 'expenses.id_mh_categories');
    }

    public function columns()
    {
        return [

            Column::raw('(CASE 
                WHEN SUBSTRING(expenses.key, 30, 2) = "01" THEN "Factura Electronica"
                WHEN SUBSTRING(expenses.key, 30, 2) = "03" THEN "Nota Electronica Credito"
                WHEN SUBSTRING(expenses.key, 30, 2) = "08" THEN "Factura Electronica Compra"
                ELSE "Compra Interna" END) AS type_document')
                ->label('Tipo')
                ->searchable()
                ->filterable(["Factura Electronica", "Nota Electronica Credito", 'Factura Electronica Compra', 'Compra Interna'])
                ->alignCenter(),
            Column::name('expenses.e_a')
                ->label('Act Econ')
                ->searchable()
                ->filterable($this->eas->pluck('number'))
                ->alignCenter(),
            DateColumn::name('expenses.date_issue')
                ->label('Fecha')
                ->searchable()
                ->defaultSort('desc')
                ->filterable()
                ->alignCenter(),
            Column::callback(['expenses.key'], function ($key) {
                return substr($key, 21, 20);
            })
                ->label('Consecutivo')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('providers.id_card')
                ->label('Cedula')
                ->searchable()
                ->filterable()
                ->truncate()
                ->alignCenter(),
            Column::name('providers.name_provider')
                ->label('Proveedor')
                ->searchable()
                ->filterable()
                ->truncate()
                ->alignCenter(),
            Column::name('mh_categories.name')
                ->label('Categoria MH')
                ->searchable()
                ->filterable($this->mhcategories->pluck('name')),
            NumberColumn::callback(['total_taxed', 'total_exempt', 'total_exonerated'], function ($tax, $exempt, $exonerate) {
                return number_format(($tax + $exempt + $exonerate), 2, '.', ',');
            })
                ->label('Subtotal')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['total_taxed'], function ($total_amount) {
                return number_format($total_amount, 2, '.', ',');
            })
                ->label('Gravado')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['total_discount'], function ($discounts) {
                return ($discounts != null) ? number_format($discounts, 2, '.', ',') : 0;
            })
                ->label('Descuento')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['total_exempt'], function ($total_amount) {
                return number_format($total_amount, 2, '.', ',');
            })
                ->label('Exento')
                ->searchable()
                ->filterable()
                ->alignCenter(),

            NumberColumn::callback(['total_exonerated'], function ($total_amount) {
                return number_format($total_amount, 2, '.', ',');
            })
                ->label('Exonerado')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['total_other_charges'], function ($total_amount) {
                return number_format($total_amount, 2, '.', ',');
            })
                ->label('Otros Cargos')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['total_tax'], function ($total_amount) {
                return number_format($total_amount, 2, '.', ',');
            })
                ->label('Impuestos')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['total_document'], function ($total_amount) {
                return number_format($total_amount, 2, '.', ',');
            })
                ->label('Total')
                ->searchable()
                ->filterable()
                ->alignCenter(),

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
