<?php

namespace App\Http\Livewire\ClassProduct;

use App\Models\ClassProduct;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClassProductsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshClassTable' => '$refresh'];
    public function builder()
    {
        return ClassProduct::query()->where("class_products.id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'removable'], function ($id, $removable) {
                return view('livewire.class-product.actions', ['id' => $id, 'removable' => $removable]);
            })->unsortable()->label('Acciones')
                ->alignCenter()
                ->excludeFromExport(),
            Column::name('id')
                ->label('Codigo')
                ->searchable()
                ->filterable(),
            Column::name('name')
                ->label('Descripción')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('symbol')
                ->label('Simbolo')
                ->searchable()
                ->filterable()
                ->truncate()

        ];
    }
}
