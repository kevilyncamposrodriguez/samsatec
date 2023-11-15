<?php

namespace App\Http\Livewire\Document;

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

class DocumentsTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshDocumentTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Document::query()->where("documents.id_company", Auth::user()->currentTeam->id)
                ->leftJoin('clients', 'documents.id_client', 'clients.id')
                ->leftJoin('branch_offices', 'documents.id_branch_office', 'branch_offices.id')
                ->leftJoin('terminals', 'documents.id_terminal', 'terminals.id')
                ->leftJoin('mh_categories', 'documents.id_mh_categories', '=', 'mh_categories.id');
        } else {
            return Document::query()->where("documents.id_company", Auth::user()->currentTeam->id)
                ->where('id_terminal', TeamUser::getUserTeam()->terminal)
                ->leftJoin('clients', 'documents.id_client', 'clients.id')
                ->leftJoin('branch_offices', 'documents.id_branch_office', 'branch_offices.id')
                ->leftJoin('terminals', 'documents.id_terminal', 'terminals.id')
                ->leftJoin('mh_categories', 'documents.id_mh_categories', '=', 'mh_categories.id');
        }
    }

    public function columns()
    {
        if (Auth::user()->currentTeam->plan_id > 2) {
            return [
                Column::callback(['id', 'path', 'type_doc', 'key', 'id_client', 'state_send', 'answer_mh'], function ($id, $path, $type, $key, $id_client, $state_send, $answer_mh) {
                    return view('livewire.document.actions', ['id' => $id, 'path' => $path, 'type_doc' => $type, 'key' => $key, 'id_client' => $id_client, 'state_send' => $state_send, 'answer_mh' => $answer_mh]);
                })->label('Acciones')
                    ->alignCenter()
                    ->unsortable()
                    ->excludeFromExport(),
                DateColumn::name('documents.created_at')
                    ->defaultSort("desc")
                    ->label('Fecha')
                    ->sortable()
                    ->filterable(),
                Column::callback(['consecutive'], function ($consecutive) {
                    return $consecutive;
                })->exportCallback(function ($consecutive) {
                    return $consecutive;
                })
                    ->label('Consecutivo')
                    ->searchable()
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

                Column::name('answer_mh')
                    ->label('Estado MH')
                    ->filterable(["Aceptado", "Rechazado", "Procesando", "Ninguna"])
                    ->alignCenter(),
                Column::raw('(CASE 
                WHEN documents.type_doc = "00" THEN "Orden de Venta"
                WHEN documents.type_doc = "11" THEN "Factura Contingencia"
                WHEN documents.type_doc = "01" THEN "Factura Electronica"
                WHEN documents.type_doc = "02" THEN "Nota Electronica Debito"
                WHEN documents.type_doc = "03" THEN "Nota Electronica Credito"
                WHEN documents.type_doc = "04" THEN "Tiquete Electronico"
                WHEN documents.type_doc = "09" THEN "Factura Electronica Exportacion"
                WHEN documents.type_doc = "99" THEN "Proforma"
                ELSE "Factura Electronica" END) AS type_document')
                    ->label('Tipo')
                    ->filterable(["Proforma", "Orden de Venta", "Factura Contingencia", "Factura Electronica", "Tiquete Electronico", "Nota Electronica Debito", "Nota Electronica Credito", 'Factura Electronica Exportacion'])
                    ->unwrap()
                    ->sortable()
                    ->alignCenter(),
                Column::name('currency')
                    ->label('Moneda')
                    ->filterable()
                    ->sortable()
                    ->alignCenter(),
                NumberColumn::callback(['total_document'], function ($total_document) {
                    return number_format($total_document, 2, '.', ',');
                })
                    ->label('Total')
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::callback(['balance'], function ($balance) {
                    return number_format($balance, 2, '.', ',');
                })
                    ->label('Saldo')
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::callback(['id'], function ($id) {
                    $docs = PaymentInvoice::where('id_document', $id)->get();
                    return view('livewire.document.payActions', ['qty' => count($docs), 'id' => $id]);
                })->exportCallback(function ($id) {
                    $docs = PaymentInvoice::where('id_document', $id)->get();
                    return count($docs);
                })
                    ->label('Pagos')
                    ->filterable()
                    ->alignCenter(),
                Column::name('state_send')
                    ->label('Envio')
                    ->sortable()
                    ->filterable(["enviado", "Sin enviar"]),
                Column::name('e_a')
                    ->label('Act Econ')
                    ->filterable()
                    ->sortable()
                    ->alignCenter(),
                Column::name('mh_categories.name')
                    ->label('Categoria MH')
                    ->filterable($this->mhcategories)
                    ->sortable()
                    ->alignCenter(),
                Column::name('branch_offices.name_branch_office')
                    ->label('Sucursal')
                    ->filterable($this->sucursales)
                    ->unwrap()
                    ->sortable(),
                Column::name('terminals.number')
                    ->label('Terminals')
                    ->filterable($this->terminals)
                    ->sortable(),
                Column::callback(['key'], function ($key) {
                    return $key;
                })->exportCallback(function ($key) {
                    return $key;
                })
                    ->label('Clave')
                    ->unwrap()
                    ->searchable()
                    ->sortable()
                    ->filterable(),
                Column::name('detail_mh')
                    ->label('Detalle MH')
                    ->filterable()
                    ->truncate(50)
                    ->alignCenter()

            ];
        } else {
            return [
                Column::callback(['id', 'path', 'type_doc', 'key', 'id_client', 'state_send', 'answer_mh'], function ($id, $path, $type, $key, $id_client, $state_send, $answer_mh) {
                    return view('livewire.document.actions', ['id' => $id, 'path' => $path, 'type_doc' => $type, 'key' => $key, 'id_client' => $id_client, 'state_send' => $state_send, 'answer_mh' => $answer_mh]);
                })->label('Acciones')
                    ->alignCenter()
                    ->unsortable()
                    ->excludeFromExport(),
                DateColumn::name('documents.created_at')
                    ->sortBy('documents.created_at')
                    ->defaultSort("desc")
                    ->label('Fecha')
                    ->searchable()
                    ->sortable()
                    ->filterable(),
                Column::callback(['consecutive'], function ($consecutive) {
                    return $consecutive;
                })->exportCallback(function ($consecutive) {
                    return $consecutive;
                })
                    ->label('Consecutivo')
                    ->searchable()
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

                Column::name('answer_mh')
                    ->label('Estado MH')
                    ->filterable(["Aceptado", "Rechazado", "Procesando", "Ninguna"])
                    ->alignCenter(),
                Column::raw('(CASE 
                WHEN documents.type_doc = "00" THEN "Orden de Venta"
                WHEN documents.type_doc = "11" THEN "Factura de Contingencia"
                WHEN documents.type_doc = "01" THEN "Factura Electronica"
                WHEN documents.type_doc = "02" THEN "Nota Electronica Debito"
                WHEN documents.type_doc = "03" THEN "Nota Electronica Credito"
                WHEN documents.type_doc = "04" THEN "Tiquete Electronico"
                WHEN documents.type_doc = "09" THEN "Factura Electronica Exportacion"
                WHEN documents.type_doc = "99" THEN "Proforma"
                ELSE "Factura Electronica" END) AS type_document')
                    ->label('Tipo')
                    ->unwrap()
                    ->sortable()
                    ->filterable(["Proforma", "Orden de Venta", "Factura Contingencia", "Factura Electronica", "Tiquete Electronico", "Nota Electronica Debito", "Nota Electronica Credito", 'Factura Electronica Exportacion'])
                    ->alignCenter(),
                Column::name('currency')
                    ->label('Moneda')
                    ->filterable()
                    ->sortable()
                    ->alignCenter(),
                NumberColumn::callback(['total_document'], function ($total_document) {
                    return number_format($total_document, 2, '.', ',');
                })
                    ->label('Total')
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::callback(['balance'], function ($balance) {
                    return number_format($balance, 2, '.', ',');
                })
                    ->label('Saldo')
                    ->filterable()
                    ->alignCenter(),
                NumberColumn::callback(['id'], function ($id) {
                    $docs = PaymentInvoice::where('id_document', $id)->get();
                    return view('livewire.document.payActions', ['qty' => count($docs), 'id' => $id]);
                })
                    ->label('Pagos')
                    ->excludeFromExport()
                    ->filterable()
                    ->alignCenter(),
                Column::name('state_send')
                    ->label('Envio')
                    ->filterable(["enviado", "Sin enviar"])
                    ->sortable(),
                Column::name('e_a')
                    ->label('Act Econ')
                    ->filterable()
                    ->sortable()
                    ->alignCenter(),
                Column::name('mh_categories.name')
                    ->label('Categoria MH')
                    ->filterable($this->mhcategories)
                    ->sortable()
                    ->alignCenter(),
                Column::name('branch_offices.name_branch_office')
                    ->label('Sucursal')
                    ->filterable($this->sucursales)
                    ->unwrap()
                    ->sortable(),
                Column::name('terminals.number')
                    ->label('Terminals')
                    ->filterable($this->terminals)
                    ->sortable(),
                Column::callback(['key'], function ($key) {
                    return $key;
                })->exportCallback(function ($key) {
                    return $key;
                })
                    ->label('Clave')
                    ->unwrap()
                    ->searchable()

                    ->filterable(),
                Column::name('detail_mh')
                    ->label('Detalle MH')
                    ->filterable()
                    ->truncate(50)
                    ->alignCenter()

            ];
        }
    }
    public function getClientsProperty()
    {
        return Client::where("id_company", Auth::user()->currentTeam->id)->pluck('name_client');
    }
    public function getMhCategoriesProperty()
    {
        return MhCategories::pluck('name');
    }
    public function getTerminalsProperty()
    {
        return Terminal::where("id_company", Auth::user()->currentTeam->id)->pluck('number');
    }
    public function getSucursalesProperty()
    {
        return BranchOffice::where("id_company", Auth::user()->currentTeam->id)->pluck('name_branch_office');
    }
}
