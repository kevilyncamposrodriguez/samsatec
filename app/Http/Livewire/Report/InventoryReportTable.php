<?php

namespace App\Http\Livewire\Report;

use App\Models\ViewInventoryReport;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InventoryReportTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public function builder()
    
    
    {
        return ViewInventoryReport :: Query();
    }

    public function columns()
    {
        return [

            Column::name('Categoria')
                ->label('Categoria')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('Familia')
                ->label('Familia')
                ->searchable()
                ->filterable()
                ->alignCenter()

        ];
    }
}