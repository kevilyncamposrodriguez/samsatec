<?php

namespace App\Http\Livewire\Transfer;

use App\Models\Transfer;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TransfersTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshTransferTable' => '$refresh'];
    public function builder()
    {
        return Transfer::where("transfers.id_company", Auth::user()->currentTeam->id)
            ->join('counts as countsC', 'countsC.id', '=', 'transfers.id_count_c')
            ->join('counts as countsD', 'countsD.id', '=', 'transfers.id_count_d');
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.transfer.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            DateColumn::name('date_issue')
                ->label('Fecha')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::name('consecutive')
                ->label('Consecutivo')
                ->searchable()
                ->filterable(),
            Column::name('user')
                ->label('Usuario')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('detail')
                ->label('Detalle')
                ->searchable()
                ->filterable(),
            Column::name('countsD.name')
                ->label('Debitado')
                ->searchable()
                ->filterable(),
                Column::name('countsC.name')
                ->label('Acreditado')
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
