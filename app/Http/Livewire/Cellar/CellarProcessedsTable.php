<?php

namespace App\Http\Livewire\Cellar;

use App\Models\Document;
use App\Models\PaymentInvoice;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CellarProcessedsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshProcessedTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Document::query()->where("documents.id_company", Auth::user()->currentTeam->id)
                ->whereIn('documents.type_doc', ['01', '04', '11'])
                ->where('documents.state_proccess', '2')
                ->leftJoin('clients', 'documents.id_client', 'clients.id');
        } else {
            return Document::query()->where("documents.id_company", Auth::user()->currentTeam->id)
                ->whereIn('documents.type_doc', ['01', '04', '11'])
                ->where('documents.state_proccess', '2')
                ->leftJoin('clients', 'documents.id_client', 'clients.id');
        }
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'path', 'type_doc', 'key', 'id_client', 'state_send', 'answer_mh', 'state_proccess'], function ($id, $path, $type, $key, $id_client, $state_send, $answer_mh, $state_proccess) {
                return view('livewire.cellar.actions', ['id' => $id, 'path' => $path, 'type_doc' => $type, 'key' => $key, 'id_client' => $id_client, 'state_send' => $state_send, 'answer_mh' => $answer_mh, 'state_proccess' => $state_proccess]);
            })->label('Acciones')
                ->alignCenter()
                ->unsortable()
                ->excludeFromExport(),
            DateColumn::name('documents.delivery_date')
                ->sortBy('documents.delivery_date')
                ->defaultSort("desc")
                ->label('Fecha de entrega')
                ->sortable()
                ->filterable(),
            DateColumn::name('documents.created_at')
                ->sortBy('documents.created_at')
                ->label('Fecha de creación')
                ->sortable()
                ->filterable(),
            Column::callback(['clients.name_client'], function ($name_client) {
                return $name_client;
            })->exportCallback(function ($name_client) {
                return $name_client;
            })
                ->label('Cliente')
                ->searchable()
                ->unwrap()
                ->sortable()
                ->filterable(),
            Column::raw('(CASE 
                WHEN documents.state_proccess = "0" THEN "Pendiente"
                WHEN documents.state_proccess = "1" THEN "Procesando"
                WHEN documents.state_proccess = "2" THEN "Procesado"
                ELSE "Procesando" END) AS state')
                ->label('Tipo')
                ->sortable()
                ->alignCenter(),
            Column::callback(['consecutive'], function ($consecutive) {
                return $consecutive;
            })->exportCallback(function ($consecutive) {
                return $consecutive;
            })
                ->label('Consecutivo')
                ->searchable()
                ->sortable()
                ->filterable()

        ];
    }
}
