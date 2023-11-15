<?php

namespace App\Http\Livewire\Lot;

use App\Models\Lot;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class LotsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshLotTable' => '$refresh'];
    public function builder()
    {
        return Lot::query()->where("lots.id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.lot.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->excludeFromExport()
                ->alignCenter(),
            Column::name('code')
                ->label('Codigo')
                ->searchable()
                ->filterable(),
            Column::name('date_purchase')
                ->label('Descripción')
                ->searchable()
                ->filterable()
                ->truncate()

        ];
    }
}
