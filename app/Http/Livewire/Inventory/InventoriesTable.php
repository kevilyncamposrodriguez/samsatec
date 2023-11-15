<?php

namespace App\Http\Livewire\Inventory;

use App\Models\BranchOffice;
use App\Models\Category;
use App\Models\Count;
use App\Models\DiaryBook;
use App\Models\DocumentDetail;
use App\Models\ExpenseDetail;
use App\Models\Family;
use App\Models\Product;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class InventoriesTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $selectable = false;
    public $exportable = true;
    protected $listeners = ['refreshInventoryTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Product::query()->where("products.id_company", "=", Auth::user()->currentTeam->id)
                ->where('products.active', '1')
                ->join('skuses', 'skuses.id', '=', 'products.id_sku')
                ->leftJoin('families', 'families.id', '=', 'products.id_family')
                ->leftJoin('categories', 'categories.id', '=', 'products.id_category')
                ->leftJoin('discounts', 'discounts.id', '=', 'products.id_discount')
                ->join('counts', 'counts.id', '=', 'products.id_count_income');
        } else {
            return Product::query()->where("products.id_company", "=", Auth::user()->currentTeam->id)
                ->where('products.active', '1')
                ->where('id_count_inventory', BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count)
                ->join('skuses', 'skuses.id', '=', 'products.id_sku')
                ->leftJoin('families', 'families.id', '=', 'products.id_family')
                ->leftJoin('categories', 'categories.id', '=', 'products.id_category')
                ->leftJoin('discounts', 'discounts.id', '=', 'products.id_discount')
                ->join('counts', 'counts.id', '=', 'products.id_count_income');
        }
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'type'], function ($id, $type) {
                return view('livewire.inventory.actionsInventory', ['id' => $id, 'type' => $type]);
            })->unsortable()->label('Acciones')
                ->excludeFromExport()
                ->alignCenter(),
            Column::callback(['description'], function ($description) {
                return $description;
            })->exportCallback(function ($description) {
                return $description;
            })
                ->label('Descripción')
                ->searchable()
                ->sortable()
                ->defaultSort("desc")
                ->filterable()
                ->alignCenter(),
            Column::callback(['internal_code'], function ($internal_code) {
                return $internal_code;
            })->exportCallback(function ($internal_code) {
                return $internal_code;
            })
                ->label('Codigo Interno')
                ->searchable()
                ->sortable()
                ->filterable(),
            Column::callback(['cabys'], function ($cabys) {
                return $cabys;
            })->exportCallback(function ($cabys) {
                return 'C' . $cabys;
            })
                ->label('Codigo Cabys')
                ->searchable()
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('skuses.symbol')
                ->label('Unidad')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('counts.name')
                ->label('Inventario')
                ->unwrap()
                ->sortable()
                ->filterable($this->counts->pluck('name'))
                ->alignCenter(),
            Column::name('families.name')
                ->label('Familia')
                ->sortable()
                ->unwrap()
                ->filterable($this->families->pluck('name'))
                ->alignCenter(),
            Column::name('categories.name')
                ->label('Categoria')
                ->unwrap()
                ->sortable()
                ->filterable($this->categories->pluck('name'))
                ->alignCenter(),
            NumberColumn::name('products.stock')
                ->label('Cantidad')
                ->filterable()
                ->editable((Auth::user()->currentTeam->plan_id > 1) ? true : false)
                ->sortable()
                ->alignCenter(),
            NumberColumn::callback(['products.id'], function ($id) {
                $product = Product::find($id);
                $ed = ExpenseDetail::where('id_product', $id);
                $qty = $product->stock_start + $ed->sum('qty');
                $cost = ($product->cost_unid * $product->stock_start) + $ed->sum('total_amount');
                $qty = ($qty == 0) ? 1 : $qty;
                return number_format(($cost / $qty), 2, '.', ',');
            })
                ->label('Costo Promedio')
                ->sortable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['products.id', 'products.stock'], function ($id, $stock) {
                $product = Product::find($id);
                $ed = ExpenseDetail::where('id_product', $id);
                $qty = $product->stock_start + $ed->sum('qty');
                $cost = ($product->cost_unid * $product->stock_start) + $ed->sum('total_amount');
                $qty = ($qty == 0) ? 1 : $qty;
                return number_format(($cost / $qty) * $stock, 2, '.', ',');
            })
                ->label('Total')
                ->filterable()
                ->alignCenter(),
        ];
    }
    public function getCategoriesProperty()
    {
        return Category::where("categories.id_company", "=", Auth::user()->currentTeam->id);
    }
    public function getFamiliesProperty()
    {
        return Family::where("families.id_company", "=", Auth::user()->currentTeam->id);
    }
    public function getCountsProperty()
    {
        return Count::where("id_company", "=", Auth::user()->currentTeam->id)->where("id_count_primary", 4);
    }
}
