<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpensesDetailExport implements FromView{
    private $allExpenses;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($datos) {
        $this->allExpenses = $datos;
    }
    public function view(): View {
      return view('livewire.report.detail-report-expenses-export-component',$this->allExpenses);        
    }
}