<?php

namespace App\Http\Livewire\AccountsReceivable;

use App\Models\CXC;
use App\Models\Document;
use App\Models\PaymentInvoice;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CXCClientTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    public $client = '';
    protected $listeners = ['obtenerSelecionados', 'refreshCXCClientTable' => '$refresh', 'clearSelectsCXCClientTable'];

    public function builder()
    {
        if ($this->selected > 0) {
            $this->emit('changeSelected', $this->selected);
        }
        return CXC::query()->where("company", Auth::user()->currentTeam->id)
            ->where("cliente", $this->client);
    }
    public function clearSelectsCXCClientTable()
    {
        $this->selected = [];
    }
    public function columns()
    {
        return [

            Column::checkbox(),
            Column::callback(['id_documento','consecutivo'], function ($id_documento,$consecutive) {
                $doc = Document::find($id_documento);
                return view('livewire.accounts-receivable.actions2', ['id' => $doc->id, 'consecutivo' => $consecutive, 'path' => $doc->path, 'key' => $doc->key]);
            })->exportCallback(function ($consecutive) {
                return $consecutive;
            })
                ->label('Consecutivo')
                ->alignCenter()
                ->filterable(),
            NumberColumn::callback(['id'], function ($id) {
                $docs = PaymentInvoice::where('id_document', $id)->get();
                return view('livewire.accounts-receivable.payActions', ['qty' => count($docs), 'id' => $id]);
            })->exportCallback(function ($id) {
                $docs = PaymentInvoice::where('id_document', $id)->get();
                return count($docs);
            })
            
                ->label('Pagos')
                ->filterable()
                ->alignCenter(),
            DateColumn::name('fecha_de_venta')
                ->label('Fecha de Venta')
                ->defaultSort('desc')
                ->filterable()
                ->alignCenter(),
            DateColumn::name('fecha_de_vencimiento')
                ->label('Fecha de vencimiento')
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['dias_vencidos'], function ($dias) {
                return $dias > 0
                    ? '<span class="text-red-500">' . $dias . '</span>'
                    : '<span class="text-blue-500">' . $dias . '</span>';
            })
                ->exportCallback(function ($monto) {
                    return number_format($monto, 2, '.', ',');
                })
                ->label('Dias Vencidos')
                ->filterable()
                ->alignCenter(),
            NumberColumn::callback(['saldo_pendiente'], function ($monto) {
                return number_format($monto, 2, '.', ',');
            })

                ->label('Monto total')
                ->filterable()
                ->alignRight(),
            NumberColumn::callback(['saldo_pendiente', 'dias_de_atraso', 'fecha_de_vencimiento'], function ($monto, $atraso, $fecha) {
                return ($atraso == 'Al Dia') ? number_format($monto, 2, '.', ',') : '0';
            })
                ->label('Al Día')
                ->alignRight(),
            NumberColumn::callback(['saldo_pendiente', 'dias_de_atraso', 'Dias_de_credito'], function ($monto, $atraso, $fecha) {
                return ($atraso == '0 a 15 dias de atraso') ? number_format($monto, 2, '.', ',') : '0';
            })
                ->label('0 a 15 días de atraso')
                ->alignRight(),
            NumberColumn::callback(['saldo_pendiente', 'dias_de_atraso', 'impuesto'], function ($monto, $atraso, $n) {
                return ($atraso == '15 a 30 dias de atraso') ? number_format($monto, 2, '.', ',') : '0';
            })
                ->label('15 a 30 días de atraso')
                ->alignRight(),
            NumberColumn::callback(['saldo_pendiente', 'dias_de_atraso', 'tipo_documento'], function ($monto, $atraso, $r) {
                return ($atraso == '30 a 60 dias de atraso') ? number_format($monto, 2, '.', ',') : '0';
            })
                ->label('30 a 60 días de atraso')
                ->alignRight(),
            NumberColumn::callback(['saldo_pendiente', 'dias_de_atraso', 'consecutivo'], function ($monto, $atraso, $f) {
                return ($atraso == '60 a 90 dias de atraso') ? number_format($monto, 2, '.', ',') : '0';
            })
                ->label('60 a 90 dias de atraso')
                ->alignRight(),
            NumberColumn::callback(['saldo_pendiente', 'dias_de_atraso', 'Cliente'], function ($monto, $atraso, $r) {
                return ($atraso == 'Mas de 90 dias de atraso') ? number_format($monto, 2, '.', ',') : '0';
            })
                ->label('Mas de 90 dias de atraso')
                ->sortBy('saldo_pendiente')
                ->alignRight(),


        ];
    }
}
