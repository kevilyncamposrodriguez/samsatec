<?php

namespace App\Http\Livewire\Zone;

use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ZonesTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshZoneTable' => '$refresh'];
    public function builder()
    {
        return Zone::query()->where("zones.id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.zone.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->excludeFromExport()
                ->alignCenter(),
            Column::name('code')
                ->label('Codigo')
                ->searchable()
                ->filterable(),
            Column::name('name')
                ->label('Descripción')
                ->searchable()
                ->filterable()
                ->truncate()

        ];
    }
}
