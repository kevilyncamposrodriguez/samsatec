<?php

namespace App\Http\Livewire\Charts;

use App\Models\DocumentDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChartsComponent extends Component
{
    //Inicializa la variable de datos 
    public $cxc_data = [], $business_data = [], $months = [], $allMonths = [];
    public $chartDataCosts, $chartDataSales, $product = null, $ano = null, $families = null, $clients = null, $categories = null;
    protected $listeners = ['updatedMonths'];
    //secarga desde un inicio
    public function mount()
    {
        $this->months = ['1', '2', '3','4', '5', '6','7', '8', '9','10', '11', '12'];
    }
    //se ejecuta constantemente
    public function render()
    {
        $months = "'" . implode("','", $this->months) . "'";
        $this->allMonths = collect(DB::select("call businessdata(?,?,?,?,?,?,?,?)", [null, null, $months, null, null, null, null, null]))->pluck("month");
        return view('livewire.charts.charts-component');
    }
    // se ejecuta cada vez que hay una seleccion de mes
    public function updatedMonths($value)
    {
        $this->months = $value;
        $this->loadChartData();
    }
    public function loadChartData()
    {
        $this->dispatchBrowserEvent('renderChart', [
            'data' => $this->getCollection()
        ]);
    }
    //trae todos los datos de la consulta
    private function getCollection()
    {
        $months = "'" . implode("','", $this->months) . "'";
        $data = DB::select("call businessdata(?,?,?,?,?,?,?,?)", [null, null, $months, null, null, null, null, null]);

        return $data;
    }
}
