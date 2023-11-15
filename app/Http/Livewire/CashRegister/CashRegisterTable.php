<?php

namespace App\Http\Livewire\CashRegister;

use App\Models\CashRegister;
use App\Models\TeamUser;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\TimeColumn;

class CashRegisterTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshCashRegisterTable' => '$refresh'];
    public function builder()
    {
        $tu = TeamUser::where('team_id', Auth::user()->currentTeam->id)
            ->where('user_id', Auth::user()->id)->first();
        if ($tu && $tu->terminal) {
            return CashRegister::query()->where("cash_registers.id_company", Auth::user()->currentTeam->id)
                ->where("cash_registers.id_terminal", $tu->terminal)
                ->join('terminals', 'terminals.id', '=', 'cash_registers.id_terminal')
                ->join('users', 'users.id', '=', 'cash_registers.id_user');
        } else {
            return CashRegister::query()->where("cash_registers.id_company", Auth::user()->currentTeam->id)
                ->join('terminals', 'terminals.id', '=', 'cash_registers.id_terminal')
                ->join('users', 'users.id', '=', 'cash_registers.id_user');
        }
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'state'], function ($id, $state) {
                return view('livewire.cash-register.actions', ['id' => $id, 'state' => $state, 'path' => "files/cierres/" . Auth::user()->currentTeam->id_card . "/" . $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter()
                ->excludeFromExport(),
            Column::name('terminals.number')
                ->label('Terminal')
                ->filterable($this->terminals->pluck('number'))
                ->sortable()
                ->alignCenter(),

            Column::name('created_at')
                ->label('Fecha Apertura')
                ->defaultSort('desc')
                ->sortable()
                ->alignCenter()
                ->filterable(),
            Column::name('finish')
                ->label('Fecha Cierre')
                ->sortable()
                ->filterable(),
            NumberColumn::name('start_balance')
                ->label('Saldo Inicial')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('sales')
                ->label('Entradas')
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('bills')
                ->label('Salidas')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('end_balance')
                ->label('Saldo Final')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::callback(['state'], function ($state) {
                return ($state)
                    ? '<span class="text-green-500">Abierta</span>'
                    : '<span class="text-red-500">Cerrada</span>';
            })
                ->exportCallback(function ($state) {
                    return ($state) ? 'Abierto' : 'Cerradp';
                })
                ->label('Estado')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('users.name')
                ->label('Usuario')
                ->searchable()
                ->hide()
                ->filterable()
                ->alignCenter()
        ];
    }
    public function getTerminalsProperty()
    {
        return Terminal::where("id_company", Auth::user()->currentTeam->id)->get();
    }
}
