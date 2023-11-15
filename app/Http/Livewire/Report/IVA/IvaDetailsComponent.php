<?php

namespace App\Http\Livewire\Report\IVA;

use App\Exports\ExportIvaYear;
use App\Models\CompaniesEconomicActivities;
use App\Models\MhCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class IvaDetailsComponent extends Component
{
    public $year2, $year, $allAE, $ae, $nombre;
    //bienes
    public $Ventas, $taxes = [], $months = [],  $categories = [];
    public function mount()
    {
        $this->year2 = date("Y");
        $this->categories = MhCategories::all();
        $this->taxes = [0, 1, 2, 4, 8, 13];
        $this->months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $this->allAE = CompaniesEconomicActivities::join("economic_activities", "economic_activities.id", "=", "companies_economic_activities.id_economic_activity")
            ->where("id_company",  Auth::user()->currentTeam->id)->get();
        $this->ae = $this->allAE->first()->number;
    }
    public function render()
    {
        $this->year = $this->year2;
        //dd($this->year);
        $this->allAE = CompaniesEconomicActivities::join("economic_activities", "economic_activities.id", "=", "companies_economic_activities.id_economic_activity")
            ->where("id_company",  Auth::user()->currentTeam->id)->get();
        //bienes
        $this->Ventas =DB::table("view_summary_ivas")->where("Compania", Auth::user()->currentTeam->id)
            ->where("ano", $this->year)
            ->where("Actividad_Economica", $this->ae)->get();

        return view('livewire.report.i-v-a.iva-details-component');
    }
    public function exportExcel()
    {
        $datos['Ventas'] = $this->Ventas;
        $datos['categories'] = $this->categories;
        $datos['year'] = $this->year;
        $datos['taxes'] = $this->taxes;
        $datos['months'] = $this->months;
        return Excel::download(new ExportIvaYear($datos), 'Control Anual de IVA.xlsx');
    }
}
