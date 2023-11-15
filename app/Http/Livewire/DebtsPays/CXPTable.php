<?php

namespace App\Http\Livewire\DebtsPays;

use App\Models\CXCDetail;
use App\Models\CXPDetail;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CXPTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public $perPage = 5;


    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return CXPDetail::query()->where("company", Auth::user()->currentTeam->id);
        } else {
            return CXPDetail::query()->where("company", Auth::user()->currentTeam->id)
            ->where('sucursal', TeamUser::getUserTeam()->bo);
        }
    }
    public function columns()
    {
        return [
            Column::callback(['provider', 'idProvider'], function ($provider, $id_provider) {
                return view('livewire.debts-pays.actions', ['provider' => $provider, 'id_provider' => $id_provider]);
            })->exportCallback(function ($provider) {
                return $provider;
            })
                ->sortBy('provider')
                ->defaultSort('asc')
                ->sortable()
                ->label('Proveedor')
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
