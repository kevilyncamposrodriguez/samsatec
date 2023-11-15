<?php

namespace App\Http\Livewire\Voucher;

use App\Models\Expense;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PendingElectronicReceiptTable extends LivewireDatatable
{
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Expense::query()
                ->where("expenses.id_company", Auth::user()->currentTeam->id)
                ->leftJoin('providers', 'providers.id', '=', 'expenses.id_provider')
                ->where('pendingCE', '1')->where('reference_ce', null);
        } else {
            return Expense::query()
                ->where("expenses.id_company", Auth::user()->currentTeam->id)
                ->where('expenses.id_branch_office', TeamUser::getUserTeam()->bo)
                ->leftJoin('providers', 'providers.id', 'expenses.id_provider')
                ->where('pendingCE', '1')->where('reference_ce', null);
        }
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'ruta', 'key', 'consecutive'], function ($id, $ruta, $key, $consecutive) {
                return view('livewire.voucher.actionsPCE', ['id' => $id, 'ruta' => $ruta, 'key' => $key, 'type' => (substr($consecutive, 0, 2) == 'OC') ? 3 : ((substr($consecutive, 0, 3) == 'FCI') ? 2 : 1)]);
            })->unsortable()
                ->label('Acciones')
                ->alignCenter(),
            Column::name('providers.name_provider')
                ->label('Proveedor')
                ->searchable()
                ->filterable()
                ->defaultSort("asc")
                ->sortable()
                ->unwrap(),
            Column::name('providers.phone')
                ->label('Telefono')
                ->searchable()
                ->filterable()
                ->sortable(),
            Column::name('consecutive_real')
                ->label('Consecutivo Real')
                ->sortBy('consecutive_real')
                ->searchable()
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['total_document'], function ($total_document) {
                return number_format($total_document, 2, '.', ',');
            })
                ->label('Total')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            DateColumn::name('date_issue')
                ->sortBy('DATE_FORMAT(date_issue, "%Y%m%d")')
                ->label('Emisión')
                ->searchable()
                ->sortable()
                ->filterable(),


        ];
    }
}
