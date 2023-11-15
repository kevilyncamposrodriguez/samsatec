<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportIvaYear implements FromView{
    private $Ventas;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($datos) {
        $this->Ventas = $datos;
    }
    public function view(): View {
      return view('livewire.report.i-v-a.iva-details-export-component',$this->Ventas);        
    }
}
