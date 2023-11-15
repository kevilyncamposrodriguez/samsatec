<?php

namespace App\Http\Livewire\Product;

use App\Models\BranchOffice;
use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\Tax;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProductsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshProductTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Product::query()->where("products.id_company", "=", Auth::user()->currentTeam->id)
                ->where('products.active', '1')
                ->leftJoin('skuses', 'skuses.id', '=', 'products.id_sku')
                ->leftJoin('families', 'families.id', '=', 'products.id_family')
                ->leftJoin('categories', 'categories.id', '=', 'products.id_category')
                ->leftJoin('class_products', 'class_products.id', '=', 'products.id_class')
                ->leftJoin('discounts', 'discounts.id', '=', 'products.id_discount')
                ->leftJoin('counts', 'counts.id', '=', 'products.id_count_income');
        } else {
            return Product::query()->where("products.id_company", "=", Auth::user()->currentTeam->id)
                ->where('products.active', '1')
                ->whereIn('id_count_inventory', [BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count, ''])
                ->leftJoin('skuses', 'skuses.id', '=', 'products.id_sku')
                ->leftJoin('families', 'families.id', '=', 'products.id_family')
                ->leftJoin('categories', 'categories.id', '=', 'products.id_category')
                ->leftJoin('class_products', 'class_products.id', '=', 'products.id_class')
                ->leftJoin('discounts', 'discounts.id', '=', 'products.id_discount')
                ->leftJoin('counts', 'counts.id', '=', 'products.id_count_income');
        }
    }

    public function columns()
    {
        if (Auth::user()->currentTeam->plan_id < 3) {
            return [
                Column::callback(['id', 'type'], function ($id, $type) {
                    return view('livewire.product.actions', ['id' => $id, 'type' => $type]);
                })->unsortable()->label('Acciones')
                    ->excludeFromExport()
                    ->alignCenter(),
                Column::name('description')
                    ->exportCallback(function ($description) {
                        return $description;
                    })
                    ->label('Descripción')
                    ->searchable()
                    ->defaultSort("desc")
                    ->sortable()
                    ->filterable()
                    ->alignCenter(),
                Column::name('internal_code')
                    ->label('Codigo Interno')
                    ->searchable()
                    ->sortable()
                    ->filterable(),
                NumberColumn::name('cabys')
                    ->label('Codigo Cabys')
                    ->exportCallback(function ($cabys) {
                        return 'c' . $cabys;
                    })
                    ->searchable()
                    ->sortable()
                    ->filterable()
                    ->alignCenter(),
                Column::name('skuses.symbol')
                    ->label('Unidad')
                    ->searchable()
                    ->sortable()
                    ->sortable()
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::name('cost_unid')
                    ->label('Costo')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('price_unid')
                    ->label('Precio sin IVA')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::callback(['ids_taxes'], function ($ids_taxes) {
                    $tax = ($ids_taxes != "[]") ? json_decode($ids_taxes)[0] : null;
                    $tax = Tax::find($tax);
                    return ($tax) ? $tax->rate : 0;
                })
                    ->label('% IVA')
                    ->searchable()
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::callback(['ids_taxes', 'price_unid'], function ($ids_taxes, $price_unid) {
                    $tax = ($ids_taxes != "[]") ? json_decode($ids_taxes)[0] : 0;
                    $tax = Tax::find($tax);
                    $tax = ($tax) ? $tax->rate : 0;
                    $result = $tax * $price_unid / 100;
                    return number_format($result, 5, '.', ',');
                })
                    ->label('Monto IVA')
                    ->searchable()
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::name('total_price')
                    ->label('Precio con IVA')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),

            ];
        } else {
            return [
                Column::callback(['id', 'type'], function ($id, $type) {
                    return view('livewire.product.actions', ['id' => $id, 'type' => $type]);
                })->unsortable()->label('Acciones')
                    ->excludeFromExport()
                    ->alignCenter(),
                Column::name('description')
                    ->exportCallback(function ($description) {
                        return $description;
                    })
                    ->label('Descripción')
                    ->searchable()
                    ->defaultSort("desc")
                    ->sortable()
                    ->filterable()
                    ->alignCenter(),
                Column::name('internal_code')
                    ->label('Codigo Interno')
                    ->searchable()
                    ->sortable()
                    ->filterable(),
                NumberColumn::name('cabys')
                    ->label('Codigo Cabys')
                    ->exportCallback(function ($cabys) {
                        return 'c' . $cabys;
                    })
                    ->searchable()
                    ->sortable()
                    ->filterable()
                    ->alignCenter(),
                Column::name('skuses.symbol')
                    ->label('Unidad')
                    ->searchable()
                    ->sortable()
                    ->sortable()
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::name('cost_unid')
                    ->label('Costo')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('price_unid')
                    ->label('Precio sin IVA')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::callback(['ids_taxes'], function ($ids_taxes) {
                    $tax = ($ids_taxes != "[]") ? json_decode($ids_taxes)[0] : null;
                    $tax = Tax::find($tax);
                    return ($tax) ? $tax->rate : 0;
                })
                    ->label('% IVA')
                    ->searchable()
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::callback(['ids_taxes', 'price_unid'], function ($ids_taxes, $price_unid) {
                    $tax = ($ids_taxes != "[]") ? json_decode($ids_taxes)[0] : 0;
                    $tax = Tax::find($tax);
                    $tax = ($tax) ? $tax->rate : 0;
                    $result = $tax * $price_unid / 100;
                    return number_format($result, 5, '.', ',');
                })
                    ->label('Monto IVA')
                    ->searchable()
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::name('total_price')
                    ->label('Precio con IVA')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('stock')
                    ->label('Stock')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('stock_base')
                    ->label('Stock Base')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('alert_min')
                    ->label('Alerta Stock')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('families.name')
                    ->label('Familia')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('categories.name')
                    ->label('Categoria')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),
                NumberColumn::name('class_products.name')
                    ->label('Clase')
                    ->searchable()
                    ->filterable()
                    ->editable()
                    ->alignCenter(),

            ];
        }
    }
}
