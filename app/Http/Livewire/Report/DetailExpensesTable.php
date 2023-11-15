<?php

namespace App\Http\Livewire\Report;

use App\Models\CompaniesEconomicActivities;
use App\Models\Count;
use App\Models\MhCategories;
use App\Models\Product;
use App\Models\TeamUser;
use App\Models\ViewExpensesDetail;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use PhpParser\Node\Stmt\Return_;

class DetailExpensesTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return ViewExpensesDetail::query()->where("Compania", Auth::user()->currentTeam->id);
        } else {
            return ViewExpensesDetail::query()->where("Compania", Auth::user()->currentTeam->id)
                ->where('idSucursal', TeamUser::getUserTeam()->bo);
        }
    }

    public function columns()
    {
        return [

            Column::name('Tipo_Documento')
                ->label('Tipo Documento')
                ->sortBy('Tipo_Documento')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('Actividad_Economica')
                ->label('Actividad Economica')
                ->sortBy('Actividad_Economica')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('Nombre_Actividad_Economica')
                ->label('Nombre Actividad Economica')
                ->truncate()
                ->sortBy('Nombre_Actividad_Economica')
                ->sortable()
                ->filterable($this->eas->pluck('name'))
                ->alignCenter(),

            Column::name('Categoria_MH')
                ->label('Categoria MH')
                ->sortBy('Categoria_MH')
                ->sortable()
                ->filterable($this->eas->pluck('name'))
                ->alignCenter(),

            Column::callback(['Mes'], function ($mes) {
                $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                return $meses[($mes - 1)];
            })
                ->sortable()
                ->sortBy('Mes')
                ->label('Mes')
                ->filterable(["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"])
                ->alignCenter(),

            DateColumn::name('Fecha')
                ->label('Fecha de compra')
                ->sortBy('DATE_FORMAT(Fecha, "%Y%m%d")')
                ->defaultSort("desc")
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('Consecutivo')
                ->label('Consecutivo')
                ->sortBy('Consecutivo')
                ->filterable()
                ->alignCenter(),
            Column::name('Cedula')
                ->label('Cedula')

                ->sortBy('Cedula')
                ->filterable()
                ->alignCenter(),
            Column::name('Proveedor')
                ->label('Proveedor')

                ->sortBy('Proveedor')
                ->filterable()
                ->alignCenter(),
            Column::name('Producto')
                ->label('Producto')
                ->sortBy('Producto')

                ->truncate()
                ->filterable()
                ->alignCenter(),
            Column::name('Unidad')
                ->label('Unidad')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Cantidad')
                ->label('Cantidad')

                ->filterable()
                ->alignCenter(),

            NumberColumn::name('Costo')
                ->label('Costo')

                ->filterable()
                ->alignCenter(),

            NumberColumn::name('Excento')
                ->label('Excento')

                ->filterable()
                ->alignCenter(),

            NumberColumn::name('Gravado')
                ->label('Gravado')

                ->filterable()
                ->alignCenter(),

            NumberColumn::callback(['discounts'], function ($discounts) {
               return (isset(json_decode($discounts)->MontoDescuento)) ? json_decode($discounts)->MontoDescuento : 0;
            })
                ->label('Descuentos')
                ->filterable()
                ->alignCenter(),

            NumberColumn::name('subtotal')
                ->label('subtotal')

                ->filterable()
                ->alignCenter(),

            Column::name('Tarifa')
                ->label('Tarifa')

                ->filterable()
                ->alignCenter(),

            NumberColumn::name('Impuesto_0')
                ->label('Impuesto 0%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_1')
                ->label('Impuesto 1%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_2')
                ->label('Impuesto 2%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_4')
                ->label('Impuesto 4%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_8')
                ->label('Impuesto 8%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('Impuesto_13')
                ->label('Impuesto 13%')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('total_amount')
                ->label('total_amount')

                ->filterable()
                ->alignCenter(),
            Column::name('currency')
                ->label('Moneda')

                ->filterable()
                ->alignCenter(),
            NumberColumn::name('exchange_rate')
                ->label('Tipo de Cambio')

                ->filterable()
                ->alignCenter(),
            Column::callback(['Id_producto', 'name'], function ($id_producto, $cuenta) {
                return ($cuenta != "") ? $cuenta : ((isset(Product::find($id_producto)->id_count_expense)) ? Count::find(Product::find($id_producto)->id_count_expense)->name : 'Ninguna');
            })
                ->label('Cuenta')

                ->filterable()
                ->alignCenter(),
            Column::name('name_branch_office')
                ->label('Compañía')

                ->truncate()
                ->filterable()
                ->alignCenter()




        ];
    }

    public function getMhCategoriesProperty()
    {
        return MhCategories::all();
    }
    public function getEASProperty()
    {
        return CompaniesEconomicActivities::join('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->where('id_company', '=', Auth::user()->currentTeam->id)->get();
    }
}
