<?php

namespace App\Http\Livewire\Plan;

use App\Models\Plan;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PlansTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshPlanTable' => '$refresh'];
    public function builder()
    {
     return Plan::query();
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.plan.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            DateColumn::name('created_at')
                ->label('Creado')
                ->searchable()
                ->filterable(),
            Column::name('id')
                ->label('Codigo')
                ->defaultSort('asc')
                ->searchable()
                ->filterable(),
            Column::name('name')
                ->label('Plan')
                ->searchable()
                ->filterable(),
            Column::name('description')
                ->label('Detalle del plan')
                ->searchable()
                ->filterable()
                ->truncate(),
            BooleanColumn::name('active')
                ->label('Activo')
                ->searchable()
                ->filterable()
                ->truncate()
        ];
    }
}
