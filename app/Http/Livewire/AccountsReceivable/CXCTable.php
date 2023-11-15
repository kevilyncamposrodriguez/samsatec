<?php

namespace App\Http\Livewire\AccountsReceivable;

use App\Models\CXCDetail;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CXCTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public $perPage = 5;

    protected $listeners = ['refreshCXCTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return CXCDetail::query()->where("Compania", Auth::user()->currentTeam->id);
        } else {
            return CXCDetail::query()->where("Compania", Auth::user()->currentTeam->id)
                ->where('sucursal', TeamUser::getUserTeam()->bo);
        }        
    }
    public function columns()
    {
        return [
            Column::callback(['cliente', 'idCliente'], function ($client, $id_client) {
                return view('livewire.accounts-receivable.actions', ['client' => $client, 'id_client' => $id_client]);
            })->exportCallback(function ($client) {
                return $client;
            })
                ->sortBy('cliente')
                ->defaultSort('asc')
                ->sortable()
                ->label('Cliente')
                ->filterable(),
            NumberColumn::callback(['cantAlDia'], function ($monto) {
                return number_format($monto, 2, '.', ',');
            })
                ->label('Facturas al dia')
                ->alignCenter(),
            NumberColumn::callback(['montoAlDia'], function ($monto) {
                return number_format($monto, 2, '.', ',');
            })
                ->label('Monto al dia')
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['cantAtrasadas'], function ($monto) {
                return '<div class="text-red">' . number_format($monto, 2, '.', ',') . '</div>';
            })
                ->exportCallback(function ($monto) {
                    return number_format($monto, 2, '.', ',');
                })
                ->label('Facturas Vencidas')
                ->alignCenter(),
            NumberColumn::callback(['montoAtrasadas'], function ($monto) {
                return '<div class="text-red">' . number_format($monto, 2, '.', ',') . '</div>';
            })
                ->exportCallback(function ($monto) {
                    return number_format($monto, 2, '.', ',');
                })
                ->label('Monto vencido')
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['cantTotal'], function ($total) {
                return number_format($total, 2, '.', ',');
            })
                ->label('Facturas Pendientes')
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['montoTotal'], function ($total) {
                return number_format($total, 2, '.', ',');
            })
                ->label('Monto Total')
                ->filterable()
                ->alignCenter()

        ];
    }
}
