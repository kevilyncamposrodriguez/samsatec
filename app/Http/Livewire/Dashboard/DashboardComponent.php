<?php

namespace App\Http\Livewire\Dashboard;

use App\ExchangeRate\Indicador;
use App\Models\User;
use App\Models\ViewCurmonthExp;
use App\Models\ViewCurmonthSale;
use App\Models\ViewTotalExp;
use App\Models\ViewTotalSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $interno = true, $curMonth_sales = 0, $curMonth_sales_int = 0, $curMonth_sales_trib = 0,
        $gastos = 0, $compras = 0, $compras_trib = 0, $gastos_trib = 0, $compras_no_trib = 0, $gastos_no_trib = 0,
        $t_sales = 0, $t_sales_trib = 0, $t_sales_no_trib = 0, $t_gastos = 0, $t_compras = 0, $t_compras_trib = 0, $t_compras_no_trib = 0,
        $t_gastos_trib = 0, $t_gastos_no_trib = 0, $ano = '', $anos = [];
    public function mount()
    {
        $this->anos = ViewTotalSale::where('COMPANIA',  Auth::user()->currentTeam->id)->get()->toArray();
        $sales = ViewCurmonthSale::where('COMPANIA',  Auth::user()->currentTeam->id)->first();
        $purchases = ViewCurmonthExp::where('COMPANIA',  Auth::user()->currentTeam->id)->first();
        $this->curMonth_sales = (isset($sales->VENTAS)) ? $sales->VENTAS : 0;
        $this->curMonth_sales_int = (isset($sales->INTERNAS)) ? $sales->INTERNAS : 0;
        $this->curMonth_sales_trib = (isset($sales->TRIBUTADAS)) ? $sales->TRIBUTADAS : 0;
        //compras
        $this->compras = (isset($purchases->COMPRAS)) ? $purchases->COMPRAS : 0;
        $this->compras_trib = (isset($purchases->COMPRAS_TRIBUTADAS)) ? $purchases->COMPRAS_TRIBUTADAS : 0;
        $this->compras_no_trib = (isset($purchases->COMPRAS_NO_TRIBUTADAS)) ? $purchases->COMPRAS_NO_TRIBUTADAS : 0;
        //gastos
        $this->gastos = (isset($purchases->GASTOS)) ? $purchases->GASTOS : 0;
        $this->gastos_trib = (isset($purchases->GASTOS_TRIBUTADOS)) ? $purchases->GASTOS_TRIBUTADOS : 0;
        $this->gastos_no_trib = (isset($purchases->GASTOS_NO_TRIBUTADOS)) ? $purchases->GASTOS_NO_TRIBUTADOS : 0;
    }
    public function render()
    {
        if ($this->ano != '') {
            $t_ventas = ViewTotalSale::where('ANO', $this->ano)->where('COMPANIA',  Auth::user()->currentTeam->id)->first();
            $t_exps = ViewTotalExp::where('ANO', $this->ano)->where('COMPANIA',  Auth::user()->currentTeam->id)->first();
            $this->t_sales = 0;
            $this->t_sales_trib = 0;
            $this->t_sales_no_trib = 0;
            //totales de ventas       
            $this->t_sales = (isset($t_ventas->VENTAS)) ? $t_ventas->VENTAS : 0;
            $this->t_sales_trib = (isset($t_ventas->TRIBUTADAS)) ? $t_ventas->TRIBUTADAS : 0;
            $this->t_sales_no_trib = (isset($t_ventas->INTERNAS)) ? $t_ventas->INTERNAS : 0;
            //totales gastos-compras
            //compras
            $this->t_compras = (isset($t_exps->COMPRAS)) ? $t_exps->COMPRAS : 0;
            $this->t_compras_trib = (isset($t_exps->COMPRAS_TRIBUTADAS)) ? $t_exps->COMPRAS_TRIBUTADAS : 0;
            $this->t_compras_no_trib = (isset($t_exps->COMPRAS_NO_TRIBUTADAS)) ? $t_exps->COMPRAS_NO_TRIBUTADAS : 0;
            //gastos
            $this->t_gastos = (isset($t_exps->GASTOS)) ? $t_exps->GASTOS : 0;
            $this->t_gastos_trib = (isset($t_exps->GASTOS_TRIBUTADOS)) ? $t_exps->GASTOS_TRIBUTADOS : 0;
            $this->t_gastos_no_trib = (isset($t_exps->GASTOS_NO_TRIBUTADOS)) ? $t_exps->GASTOS_NO_TRIBUTADOS : 0;
        } else {
            $t_ventas = ViewTotalSale::where('COMPANIA',  Auth::user()->currentTeam->id)->get();
            $this->t_sales = 0;
            $this->t_sales_trib = 0;
            $this->t_sales_no_trib = 0;
            foreach ($t_ventas as $key => $t_venta) {
                //totales de ventas       
                $this->t_sales += (isset($t_venta->VENTAS)) ? $t_venta->VENTAS : 0;
                $this->t_sales_trib += (isset($t_venta->TRIBUTADAS)) ? $t_venta->TRIBUTADAS : 0;
                $this->t_sales_no_trib += (isset($t_venta->INTERNAS)) ? $t_venta->INTERNAS : 0;
            }
            $t_exps = ViewTotalExp::where('COMPANIA',  Auth::user()->currentTeam->id)->get();
            foreach ($t_exps as $key => $t_exp) {
                //totales gastos-compras
                //compras
                $this->t_compras += (isset($t_exp->COMPRAS)) ? $t_exp->COMPRAS : 0;
                $this->t_compras_trib += (isset($t_exp->COMPRAS_TRIBUTADAS)) ? $t_exp->COMPRAS_TRIBUTADAS : 0;
                $this->t_compras_no_trib += (isset($t_exp->COMPRAS_NO_TRIBUTADAS)) ? $t_exp->COMPRAS_NO_TRIBUTADAS : 0;
                //gastos
                $this->t_gastos += (isset($t_exp->GASTOS)) ? $t_exp->GASTOS : 0;
                $this->t_gastos_trib += (isset($t_exp->GASTOS_TRIBUTADOS)) ? $t_exp->GASTOS_TRIBUTADOS : 0;
                $this->t_gastos_no_trib += (isset($t_exp->GASTOS_NO_TRIBUTADOS)) ? $t_exp->GASTOS_NO_TRIBUTADOS : 0;
            }
        }
        $i = new Indicador(false);
        // Metodo recibe el tipo de cambio Indicador::VENTA o Indicador::COMPRA
        $sale = $i->obtenerIndicadorEconomico("317", "");
        $purchase = $i->obtenerIndicadorEconomico("318", "");
        session(['sale' => $sale, 'purchase' => $purchase]);
        return view('livewire.dashboard.dashboard-component');
    }
}
