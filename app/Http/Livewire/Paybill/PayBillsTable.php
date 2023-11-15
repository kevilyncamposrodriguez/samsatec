<?php

namespace App\Http\Livewire\Paybill;

use App\Models\Paybill;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PayBillsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshPayBillTable' => '$refresh'];
    public function builder()
    {
        return Paybill::where("paybills.id_company", Auth::user()->currentTeam->id)
            ->join('counts', 'counts.id', '=', 'paybills.id_count');
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.paybill.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            DateColumn::name('date_issue')
                ->label('Fecha')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::name('term')
                ->label('Vencimiento')
                ->searchable()
                ->filterable(),
            Column::name('consecutive')
                ->label('Consecutivo')
                ->searchable()
                ->filterable(),
            Column::name('name')
                ->label('Deudor')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('idcard')
                ->label('Cedula')
                ->searchable()
                ->filterable(),
            Column::name('detail')
                ->label('Detalle')
                ->searchable()
                ->filterable(),
            Column::name('counts.name')
                ->label('Cuenta Bancaria')
                ->searchable()
                ->filterable(),
            Column::name('reference')
                ->label('Referencia')
                ->searchable()
                ->filterable(),
            Column::name('mount')
                ->label('Monto')
                ->searchable()
                ->filterable()

        ];
    }
}
