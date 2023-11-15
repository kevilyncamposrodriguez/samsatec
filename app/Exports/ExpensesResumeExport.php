<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpensesResumeExport implements FromView{
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
      return view('livewire.report.resume-report-expenses-export-component',$this->allExpenses);        
    }
}