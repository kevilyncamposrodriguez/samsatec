<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DocumentsDetailExport implements FromView{
    private $allDocuments;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($datos) {
        $this->allDocuments = $datos;
    }
    public function view(): View {
      return view('livewire.report.detail-report-documents-export-component',$this->allDocuments);        
    }
}