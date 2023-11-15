<?php

namespace App\Http\Livewire\Credits;

use App\Models\Credit;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CreditsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshCreditTable' => '$refresh'];
    public function builder()
    {
        return Credit::where("credits.id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.credits.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            DateColumn::name('date_issue')
                ->label('Fecha')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::name('financial_entity')
                ->label('Financiera')
                ->searchable()
                ->filterable(),
            Column::name('credit_number')
                ->label('Numero')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('period')
                ->label('Periodo')
                ->searchable()
                ->filterable(),
            Column::name('currency')
                ->label('Moneda')
                ->searchable()
                ->filterable(),
            Column::name('taxed')
                ->label('Tributado')
                ->searchable()
                ->filterable(),
            Column::name('credit_rate')
                ->label('Tasa %')
                ->searchable()
                ->filterable(),
            NumberColumn::callback(['credit_mount'], function ($credit_mount) {
                return number_format($credit_mount, 2, '.', ',');
            })
                ->label('Monto')
                ->searchable()
                ->filterable(),
            NumberColumn::callback(['formalization_expenses'], function ($formalization_expenses) {
                return number_format($formalization_expenses, 2, '.', ',');
            })
                ->label('Gasto de Formalización')
                ->searchable()
                ->filterable(),

        ];
    }
}
