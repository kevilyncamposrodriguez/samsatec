<?php

namespace App\Http\Livewire\Voucher;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\TeamUser;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;

class VouchersTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshVoucherTable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return Expense::query()
                ->where("expenses.id_company", "=", Auth::user()->currentTeam->id)
                ->leftJoin('providers', 'providers.id', '=', 'expenses.id_provider')
                ->leftJoin('mh_categories', 'mh_categories.id', '=', 'expenses.id_mh_categories')
                ->select(
                    'expenses.*',
                    'providers.id_card as idcardProvider',
                    'providers.name_provider as nameProvider',
                    'mh_categories.name as nameMHCategoty'
                );
        } else {
            return Expense::query()
                ->where("expenses.id_company", "=", Auth::user()->currentTeam->id)
                ->where('id_branch_office', TeamUser::getUserTeam()->bo)
                ->leftJoin('providers', 'providers.id', '=', 'expenses.id_provider')
                ->leftJoin('mh_categories', 'mh_categories.id', '=', 'expenses.id_mh_categories')
                ->select(
                    'expenses.*',
                    'providers.id_card as idcardProvider',
                    'providers.name_provider as nameProvider',
                    'mh_categories.name as nameMHCategoty'
                );
        }
    }
    public function columns()
    {
        return [
            Column::callback(['id', 'ruta', 'key', 'consecutive'], function ($id, $ruta, $key, $consecutive) {
                return view('livewire.voucher.actions', ['id' => $id, 'ruta' => $ruta, 'key' => $key, 'type' => (substr($consecutive, 0, 2) == 'OC') ? 3 : ((substr($consecutive, 0, 3) == 'FCI') ? 2 : 1)]);
            })->unsortable()
                ->label('Acciones')
                ->excludeFromExport()
                ->alignCenter(),
            DateColumn::name('date_issue')
                ->sortBy('DATE_FORMAT(date_issue, "%Y%m%d")')
                ->defaultSort("desc")
                ->label('Emisión')
                ->searchable()
                ->sortable()
                ->filterable(),
            Column::name('consecutive_real')
                ->label('Consecutivo Real')
                ->sortBy('consecutive_real')
                ->searchable()
                ->sortable()
                ->filterable(),
            Column::name('consecutive')
                ->label('Consecutivo Aceptación')
                ->sortBy('consecutive')
                ->searchable()
                ->sortable()
                ->filterable(),
            Column::name('providers.id_card')
                ->label('Cedula')
                ->searchable()
                ->filterable()
                ->sortable()
                ->unwrap(),
            Column::callback(['providers.name_provider'], function ($name_provider) {
                return $name_provider;
            })->exportCallback(function ($name_provider) {
                return $name_provider;
            })
                ->label('Proveedor')
                ->searchable()
                ->filterable()
                ->sortable()
                ->unwrap(),
            Column::name('currency')
                ->label('Moneda')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            NumberColumn::callback(['total_document'], function ($total_document) {
                return number_format($total_document, 2, '.', ',');
            })
                ->label('Total')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            NumberColumn::callback(['pending_amount'], function ($balance) {
                return number_format($balance, 2, '.', ',');
            })
                ->label('Saldo')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            NumberColumn::callback(['id'], function ($id) {
                $docs = Payment::where('id_expense', $id)->get();
                return view('livewire.voucher.payActions', ['qty' => count($docs), 'id' => $id]);
            })->exportCallback(function ($id) {
                $docs = Payment::where('id_expense', $id)->get();
                return count($docs);
            })
                ->label('Pagos')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            DateColumn::name('expiration_date')
                ->label('Expira')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('state')
                ->label('Estado MH')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('mh_categories.name')
                ->label('Categoria MH')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('key')
                ->label('Clave')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('e_a')
                ->label('Act. Econom')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('condition')
                ->label('Condición')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('mh_detail')
                ->label('Detalle MH')
                ->searchable()
                ->filterable()
                ->sortable()
                ->alignCenter()

        ];
    }
}
