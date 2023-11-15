<?php

namespace App\Http\Livewire\Count;

use App\Models\Count;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CountsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshCountTable' => '$refresh'];
    public function builder()
    {
        return Count::query()->where("counts.id_company", "=", Auth::user()->currentTeam->id)           
            ->join('count_primaries', 'count_primaries.id', '=', 'counts.id_count_primary')
            ->join('count_categories', 'count_categories.id', '=', 'count_primaries.id_count_category')
            ->join('count_types', 'count_types.id', '=', 'count_categories.id_count_type');
    }

    public function columns()
    {
        return [
            Column::callback(['id','id_count'], function ($id,$id_count) {
                return view('livewire.count.actions', ['id' => $id, 'id_count' => $id_count]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            DateColumn::name('created_at')
                ->label('Creada')
                ->defaultSort('desc')
                ->searchable()
                ->filterable(),
            Column::name('count_types.name')
                ->label('Tipo')
                ->searchable()
                ->filterable(),
            Column::name('count_categories.name')
                ->label('Categoria')
                ->searchable()
                ->filterable(),
                Column::name('count_primaries.name')
                ->label('Cuenta Primaria')
                ->searchable()
                ->filterable(),
            Column::callback(['id_count'], function ($id_count) {
                return ($id_count!='')?'Secundaria':'Primaria';
            })
                ->label('Nivel')
                ->searchable()
                ->filterable(),
            Column::name('name')
                ->label('Nombre')
                ->searchable()
                ->filterable(),
            Column::name('description')
                ->label('Descripción')
                ->searchable()
                ->filterable()

        ];
    }
}
