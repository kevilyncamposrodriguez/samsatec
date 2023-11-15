<?php

namespace App\Http\Livewire\Report\IVA;

use App\Exports\ExportIva;
use App\Models\CompaniesEconomicActivities;
use App\Models\MhCategories;
use App\Models\ViewIvaDetails;
use App\Models\ViewSalesDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class IvaComponent extends Component
{
    public $year, $month, $allEconomicActivities, $number_ea, $nombre;
    //bienes
    public $Ventas,$Gastos, $taxes = [], $months = [],  $categories = [];
    public function mount()
    {
        $this->categories = MhCategories::all();
        $this->taxes = [0, 1, 2, 4, 8, 13];
        $this->months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $this->year = date('Y');
        $this->month = date('m') -2;
        $this->allEconomicActivities = CompaniesEconomicActivities::join("economic_activities", "economic_activities.id", "=", "companies_economic_activities.id_economic_activity")
        ->where("id_company",  Auth::user()->currentTeam->id)->get();
        $this->number_ea = '';
    }
    public function render()
    {
        $this->allEconomicActivities = CompaniesEconomicActivities::join("economic_activities", "economic_activities.id", "=", "companies_economic_activities.id_economic_activity")
        ->where("id_company",  Auth::user()->currentTeam->id)->get();
        //bienes
        $this->Ventas = DB::table("view_iva_details")->where("Compania", Auth::user()->currentTeam->id)
            ->where("ano", $this->year)
            ->where("Mes", $this->month)
            ->where("Actividad_Economica",'like', '%'.$this->number_ea.'%')
            ->selectRaw('Categoria_MH,sum(Gravado) as Gravado, sum(Impuesto_0) as Impuesto_0, sum(Impuesto_1) as Impuesto_1, sum(Impuesto_2) as Impuesto_2, sum(Impuesto_4) as Impuesto_4, sum(Impuesto_8) as Impuesto_8, sum(Impuesto_13) as Impuesto_13,
            sum(Monto_0) as Monto_0, sum(Monto_1) as Monto_1, sum(Monto_2) as Monto_2, sum(Monto_4) as Monto_4, sum(Monto_8) as Monto_8, sum(Monto_13) as Monto_13, sum(Impuesto_exonerado) as Impuesto_exonerado, sum(total_impuesto) as total_impuesto, sum(total_venta) as total_venta')
            ->groupBy(['Categoria_MH'])->get();
            $this->Gastos = DB::table("view_expenses_details")->where("Compania", Auth::user()->currentTeam->id)
            ->where("ano", $this->year)
            ->where("Mes", $this->month)
            ->where("Actividad_Economica",'like', '%'.$this->number_ea.'%')
            ->selectRaw('Categoria_MH,sum(Gravado) as Gravado, sum(Impuesto_0) as Impuesto_0, sum(Impuesto_1) as Impuesto_1, sum(Impuesto_2) as Impuesto_2, sum(Impuesto_4) as Impuesto_4, sum(Impuesto_8) as Impuesto_8, sum(Impuesto_13) as Impuesto_13,
            sum(Monto_0) as Monto_0, sum(Monto_1) as Monto_1, sum(Monto_2) as Monto_2, sum(Monto_4) as Monto_4, sum(Monto_8) as Monto_8, sum(Monto_13) as Monto_13, sum(Excento) as Impuesto_exonerado, sum(total_impuesto) as total_impuesto, sum(total_amount) as total_venta')
            ->groupBy(['Categoria_MH'])->get();
        return view('livewire.report.i-v-a.iva-component');
    }
    public function exportExcel()
    {
        $datos['Ventas'] = $this->Ventas;
        $datos['Gastos'] = $this->Gastos;
        $datos['categories'] = $this->categories;
        $datos['year'] = $this->year;
        $datos['taxes'] = $this->taxes;
        $datos['months'] = $this->months;
        return Excel::download(new ExportIva($datos), 'Reporte de IVA.xlsx');
    }
}
