<?php

namespace App\Http\Livewire\Family;

use App\Models\Family;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class FamiliesTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshFamilyTable' => '$refresh'];
    public function builder()
    {
     return Family::query()->where("families.id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.family.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter()
                ->excludeFromExport(),
            DateColumn::name('created_at')
                ->label('Creado')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::name('id')
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