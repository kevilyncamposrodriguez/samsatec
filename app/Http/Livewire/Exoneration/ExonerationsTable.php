<?php

namespace App\Http\Livewire\Exoneration;

use App\Models\Exoneration;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ExonerationsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshExonerationsTable' => '$refresh'];
    public function builder()
    {
        return Exoneration::query()->where("exonerations.id_company", "=", Auth::user()->currentTeam->id)
        ->where("exonerations.active",'1');
    }
    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.exoneration.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            Column::name('description')
                ->label('Descripción')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::name('id_type_document_exoneration')
                ->label('Tipo de documento')
                ->searchable()
                ->filterable(),
            Column::name('document_number')
                ->label('Numero de Documento')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('institutional_name')
                ->label('Institución')
                ->searchable()
                ->filterable(),
            Column::name('exemption_percentage')
                ->label('Monto Exoneración')
                ->searchable()
                ->filterable()
        ];
    }
}
