<?php

namespace App\Http\Livewire\Payment;

use App\Models\Payment;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PaymentsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshPaymentTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Payment::where("payments.id_company", "=", Auth::user()->currentTeam->id)
                ->join('counts', 'payments.id_count', '=', 'counts.id')
                ->join('expenses', 'payments.id_expense', '=', 'expenses.id')
                ->join('providers', 'expenses.id_provider', '=', 'providers.id');
        } else {
            return Payment::where("payments.id_company", "=", Auth::user()->currentTeam->id)
                ->where('expenses.id_terminal', TeamUser::getUserTeam()->terminal)
                ->join('counts', 'payments.id_count', '=', 'counts.id')
                ->join('expenses', 'payments.id_expense', '=', 'expenses.id')
                ->join('providers', 'expenses.id_provider', '=', 'providers.id');
        }
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'mount', 'id_expense'], function ($id, $mount, $id_expense) {
                return view('livewire.payment.actions', ['id' => $id, 'mount' => $mount, 'id_expense' => $id_expense]);
            })->unsortable()->label('Acciones')
                ->alignCenter()
                ->excludeFromExport(),
            DateColumn::name('created_at')
                ->label('Creación')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::callback(['expenses.key', 'expenses.consecutive'], function ($key, $consecutive) {
                if (substr($consecutive, 0, 2) == 'FI' || substr($consecutive, 0, 2) == 'OC') {
                    return $consecutive;
                } else {
                    return substr($key, 21, 20);
                }
            })
                ->label('Consecutivo')
                ->searchable()
                ->filterable(),
            Column::name('providers.name_provider')
                ->label('Proveedor')
                ->searchable()
                ->filterable(),
            Column::name('counts.name')
                ->label('Cuenta Bancaria')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('reference')
                ->label('Referencia')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['mount'], function ($total_document) {
                return number_format($total_document, 2, ',', '.');
            })
                ->label('Monto')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('observations')
                ->label('Observaciones')
                ->searchable()
                ->filterable()
                ->alignCenter()
        ];
    }
}
