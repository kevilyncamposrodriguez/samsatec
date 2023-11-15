<?php

namespace App\Http\Livewire\Cellar;

use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\Document;
use App\Models\MhCategories;
use App\Models\PaymentInvoice;
use App\Models\TeamUser;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CellarUnprocessedsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshUnprocessedTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Document::query()->where("documents.id_company", Auth::user()->currentTeam->id)
                ->whereIn('documents.type_doc', ['01', '04', '11'])
                ->where('documents.state_proccess', '<>', '2')
                ->leftJoin('clients', 'documents.id_client', 'clients.id');
        } else {
            return Document::query()->where("documents.id_company", Auth::user()->currentTeam->id)
                ->whereIn('documents.type_doc', ['01', '04', '11'])
                ->where('documents.state_proccess', '<>', '2')
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
            Column::callback(["documents.id", "documents.state_proccess"], function ($modelId, $valueId) {
                return view('livewire.cellar.select', [
                    'rowId' => $modelId,
                    'modelName' => "documents",
                    'nullable' => false,
                    'valueId' => $valueId, // myModel.user_id
                    'options' => [["id" => 0, "name" => 'Sin procesar'], ["id" => 1, "name" => 'Procesando']]
                ]);
            })->exportCallback(function ($valueId) {
                return ($valueId == 0) ? "Pendiente" : "Procesando";
            })
                ->label('Estado')
                ->filterable()
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
    public function saveData($rowId, $value)
    {
        if ($value === "")
            $value = null;
        Document::where('id', $rowId)->update(["state_proccess" => $value]);
    }
}
