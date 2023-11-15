<?php

namespace App\Http\Livewire\PriceList;

use App\Models\PriceListsLists;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PriceLitsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public $ids = '';
    protected $listeners = ['refreshPriceListTable' => '$refresh', 'setId'];
    public function builder()
    {
        return PriceListsLists::query()->where("price_lists_lists.id_price_list", "=", $this->ids)
            ->where('products.active',1)
        ->join('products', 'products.id', '=', 'price_lists_lists.id_product');
    }
    public function setId($id)
    {
        $this->ids = $id;
    }
    public function columns()
    {
        return [
            Column::name('products.internal_code')
                ->label('Codigo Interno')
                ->searchable()
                ->filterable(),
            Column::name('products.cabys')
                ->label('Cabys')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('products.description')
                ->label('Nombre')
                ->searchable()
                ->filterable(),
            NumberColumn::name('price')
                ->label('Price')
                ->searchable()
                ->filterable()
                ->editable()
                ->alignCenter()
                

        ];
    }
}
