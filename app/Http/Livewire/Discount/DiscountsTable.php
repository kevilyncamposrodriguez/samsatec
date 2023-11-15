<?php

namespace App\Http\Livewire\Discount;

use App\Models\Discount;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class DiscountsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshDiscountTable' => '$refresh'];
    public function builder()
    {
        return Discount::query()->where("discounts.id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.discount.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            DateColumn::name('created_at')
                ->label('Creado')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::name('nature')
                ->label('Descripción')
                ->searchable()
                ->filterable(),
            Column::name('amount')
                ->label('Monto')
                ->searchable()
                ->filterable()
                ->truncate()

        ];
    }
}
