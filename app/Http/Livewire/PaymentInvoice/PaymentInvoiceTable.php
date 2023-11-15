<?php

namespace App\Http\Livewire\PaymentInvoice;

use App\Models\PaymentInvoice;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PaymentInvoiceTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshPaymentInvoiceTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return PaymentInvoice::where("payment_invoices.id_company", Auth::user()->currentTeam->id)
                ->leftJoin('counts', 'payment_invoices.id_count', '=', 'counts.id')
                ->join('documents', 'payment_invoices.id_document', '=', 'documents.id')
                ->join('clients', 'documents.id_client', '=', 'clients.id');
        } else {
            return PaymentInvoice::where("payment_invoices.id_company", Auth::user()->currentTeam->id)
                ->where('documents.id_terminal', TeamUser::getUserTeam()->terminal)
                ->leftJoin('counts', 'payment_invoices.id_count', '=', 'counts.id')
                ->join('documents', 'payment_invoices.id_document', '=', 'documents.id')
                ->join('clients', 'documents.id_client', '=', 'clients.id');
        }
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'mount', 'id_document'], function ($id, $mount, $id_document) {
                return view('livewire.payment-invoice.actions', ['id' => $id, 'mount' => $mount, 'id_document' => $id_document]);
            })->unsortable()->label('Acciones')
                ->excludeFromExport()
                ->alignCenter(),
            DateColumn::name('created_at')
                ->label('Creación')
                ->searchable()
                ->defaultSort('desc')
                ->filterable(),
            Column::name('documents.consecutive')
                ->label('Consecutivo')
                ->sortBy('consecutive')
                ->searchable()
                ->filterable(),
            Column::name('clients.name_client')
                ->label('Nombre Cliente')
                ->searchable()
                ->filterable(),
            Column::name('counts.name')
                ->label('Cuenta Bancaria')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('reference')
                ->label('Referencia')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['mount'], function ($total_document) {
                return number_format($total_document, 2, '.', ',');
            })
                ->label('Monto')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('observations')
                ->label('Observaciones')
                ->searchable()
                ->filterable()
                ->alignCenter(),
        ];
    }
}
