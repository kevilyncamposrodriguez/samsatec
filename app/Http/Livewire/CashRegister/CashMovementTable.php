<?php

namespace App\Http\Livewire\CashRegister;

use App\Models\ViewCashMovementReport;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CashMovementTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshCashMovementTable' => '$refresh'];
    public function builder()
    {
        return ViewCashMovementReport::query()->where("compania", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            DateColumn::name('fecha_movimiento')
                ->label('Facha de Movimiento')
                ->filterable()
                ->defaultSort('desc')
                ->sortable()
                ->alignCenter(),
            Column::name('persona_empresa')
                ->label('Persona o Empresa')
                ->filterable()
                ->sortable()
                ->alignCenter(),

            Column::name('nombre_cuenta')
                ->label('Tipo')
                ->sortable()
                ->alignCenter()
                ->filterable($this->types),
            DateColumn::name('fecha_cancelacion')
                ->label('Fecha Cancelación')
                ->sortable()
                ->alignCenter()
                ->filterable(),
            Column::name('observacion')
                ->label('Observación')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::callback(['referencia'], function ($referencia) {
                return ($referencia == '')?'Ninguna':$referencia;
            })
                ->label('Referencia')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('consecutivo')
                ->label('Consecutivo')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['entrada'], function ($entrada) {
                return number_format($entrada, 2, '.', ',');
            })
                ->label('Entrada')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['Salida'], function ($salida) {
                return number_format($salida, 2, '.', ',');
            })
                ->label('Salida')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('cuenta')
                ->label('CTA Bancaria')
                ->filterable()
                ->alignCenter()
        ];
    }
    public function getTypesProperty()
    {
        return ViewCashMovementReport::where("compania", Auth::user()->currentTeam->id)->groupBy('nombre_cuenta')->pluck('nombre_cuenta');
    }
}
