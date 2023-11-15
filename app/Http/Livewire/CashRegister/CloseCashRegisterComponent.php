<?php

namespace App\Http\Livewire\CashRegister;

use App\Models\BranchOffice;
use App\Models\CashRegister;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\Terminal;
use App\Models\ViewCash;
use App\Models\ViewCashBill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDF;

class CloseCashRegisterComponent extends Component
{

    protected $listeners = ['closeCR' => 'edit'];
    public $observation, $id_cr, $allBOs, $allTerminals, $start_balance, $start_date, $end_date, $end_balance, $number_terminal,
        $name_bo, $id_user, $id_terminal, $sales, $bills, $id_bo, $linesCheques, $chequeN, $chequeR, $chequeT;
    public $cant_5, $cant_10, $cant_25, $cant_50, $cant_100, $cant_500, $cant_1000, $cant_2000,
        $cant_5000, $cant_10000, $cant_20000, $cant_50000;
    public $total_monedas, $total_billetes, $total_5, $total_10, $total_25, $total_50, $total_100,
        $total_500, $total_1000, $total_2000, $total_5000, $total_10000, $total_20000, $total_50000;
    public $totalEfectivo, $totalCaja, $totalCheques, $otrosEfectivos, $totalEntradas, $totalSalidas;
    public $ventas_contado, $ventas_credito, $ventas_otros, $total_ventas, $cxcs, $cxps, $compras, $compras_otras = 0, $total_compras, $efectivo_estimado, $sobrante;
    public $transferencia, $sinpe, $tarjeta, $efectivo, $cheque, $transferenciaR, $sinpeR, $tarjetaR, $total_banco, $total_efectivo;
    public $total_otros_movimientos, $otras_entradas_cxc, $otras_entradas_otras, $otras_salidas_cxp, $otras_salidas_otras;
    public function mount()
    {
        $this->chequeN = [];
        $this->chequeR = [];
        $this->chequeT = [];
        $this->observation = '';
        $this->linesCheques = 6;
        $this->allBOs = BranchOffice::where('id_company', Auth::user()->currentTeam->id)->get();
        $this->id_bo = $this->allBOs[0]->id;
        $this->allTerminals = Terminal::where('id_branch_office', $this->id_bo)->get();
        $this->end_date = date("Y-m-d\TH:i:s", strtotime(now()));
        $this->start_balance = 0;
        $this->cant_5 = 0;
        $this->cant_10 = 0;
        $this->cant_25 = 0;
        $this->cant_50 = 0;
        $this->cant_100 = 0;
        $this->cant_500 = 0;
        $this->cant_1000 = 0;
        $this->cant_2000 = 0;
        $this->cant_5000 = 0;
        $this->cant_10000 = 0;
        $this->cant_20000 = 0;
        $this->cant_50000 = 0;
        $this->total_5 = 0;
        $this->total_10 = 0;
        $this->total_25 = 0;
        $this->total_50 = 0;
        $this->total_100 = 0;
        $this->total_500 = 0;
        $this->total_1000 = 0;
        $this->total_2000 = 0;
        $this->total_5000 = 0;
        $this->total_10000 = 0;
        $this->total_20000 = 0;
        $this->total_50000 = 0;
        $this->totalCheques = 0;
        $this->totalEfectivo = 0;
        $this->totalCaja = 0;
        $this->otrosEfectivos = 0;
        $this->totalEntradas = 0;
        $this->totalSalidas = 0;
    }
    public function render()
    {
        $this->total_billetes = 0;
        $this->totalCheques = 0;
        $this->total_5 = ($this->cant_5 != '') ? $this->cant_5 * 5 : 0;
        $this->total_10 = ($this->cant_10 != '') ? $this->cant_10 * 10 : 0;
        $this->total_25 = ($this->cant_25 != '') ? $this->cant_25 * 25 : 0;
        $this->total_50 = ($this->cant_50 != '') ? $this->cant_50 * 50 : 0;
        $this->total_100 = ($this->cant_100 != '') ? $this->cant_100 * 100 : 0;
        $this->total_500 = ($this->cant_500 != '') ? $this->cant_500 * 500 : 0;
        $this->total_1000 = ($this->cant_1000 != '') ? $this->cant_1000 * 1000 : 0;
        $this->total_2000 = ($this->cant_2000 != '') ? $this->cant_2000 * 2000 : 0;
        $this->total_5000 = ($this->cant_5000 != '') ? $this->cant_5000 * 5000 : 0;
        $this->total_10000 = ($this->cant_10000 != '') ? $this->cant_10000 * 10000 : 0;
        $this->total_20000 = ($this->cant_20000 != '') ? $this->cant_20000 * 20000 : 0;
        $this->total_50000 = ($this->cant_50000 != '') ? $this->cant_50000 * 50000 : 0;
        $this->total_billetes = $this->total_1000 + $this->total_2000 + $this->total_5000 +
            $this->total_10000 + $this->total_20000 + $this->total_50000;
        $this->total_monedas = $this->total_5 + $this->total_10 + $this->total_25 +
            $this->total_50 + $this->total_100 + $this->total_500;
        if ($this->chequeT) {
            foreach ($this->chequeT as $key => $chq) {
                $this->totalCheques += ($chq != '') ? $chq : 0;
            }
        }
        $this->totalCaja = $this->total_billetes + $this->total_monedas + $this->totalCheques + (($this->otrosEfectivos != '') ? $this->otrosEfectivos : 0);
        $this->total_otros_movimientos = (($this->otras_entradas_cxc) ? $this->otras_entradas_cxc : 0) + (($this->otras_entradas_otras) ? $this->otras_entradas_otras : 0) - (($this->otras_salidas_cxp) ? $this->otras_salidas_cxp : 0) - (($this->otras_salidas_otras) ? $this->otras_salidas_otras : 0);
        return view('livewire.cash-register.close-cash-register-component');
    }
    public function reporte()
    {
        $this->ventas_contado = ViewCash::where('compania', Auth::user()->currentTeam->id)
            ->where('condicion_venta', '01')
            ->where('terminal', $this->id_terminal)
            ->whereBetween('fecha_creado', [$this->start_date, $this->end_date])
            ->sum('total_documento');
        $this->ventas_credito = ViewCash::where('compania', Auth::user()->currentTeam->id)
            ->where('condicion_venta', '02')
            ->where('terminal', $this->id_terminal)
            ->whereBetween('fecha_creado', [$this->start_date, $this->end_date])
            ->sum('total_documento');
        $this->ventas_otros = ViewCash::where('compania', Auth::user()->currentTeam->id)
            ->whereNotIn('condicion_venta', ['01', '02'])
            ->where('terminal', $this->id_terminal)
            ->whereBetween('fecha_creado', [$this->start_date, $this->end_date])
            ->sum('total_documento');
        $this->total_ventas = $this->ventas_contado + $this->ventas_credito + $this->ventas_otros;
        $this->cxcs = PaymentInvoice::join('documents', 'documents.id', '=', 'payment_invoices.id_document')
            ->where('payment_invoices.id_company', Auth::user()->currentTeam->id)
            ->where('documents.id_terminal', $this->id_terminal)
            ->whereNotIn('documents.sale_condition', ['01'])
            ->whereBetween('payment_invoices.created_at', [$this->start_date, $this->end_date])->sum('mount');

        $this->compras = ViewCashBill::where('terminal', $this->id_terminal)
            ->where('view_cash_bills.compania', Auth::user()->currentTeam->id)
            ->whereBetween('fecha_creado', [$this->start_date, $this->end_date])
            ->where('condicion_venta', '01')->sum('total_documento');
        $this->cxps = Payment::join('expenses', 'expenses.id', '=', 'payments.id_expense')
            ->where('payments.id_company', Auth::user()->currentTeam->id)
            ->whereNotIn('expenses.sale_condition', ['01'])
            ->whereBetween('payments.created_at', [$this->start_date, $this->end_date])->sum('mount');

        $this->transferencia = PaymentInvoice::join('documents', 'documents.id', '=', 'payment_invoices.id_document')
            ->where('payment_invoices.id_company', Auth::user()->currentTeam->id)
            ->whereBetween('payment_invoices.created_at', [$this->start_date, $this->end_date])
            ->where('documents.id_terminal', $this->id_terminal)
            ->where('documents.payment_method', '04')->sum('mount');
        $this->sinpe = PaymentInvoice::join('documents', 'documents.id', '=', 'payment_invoices.id_document')
            ->where('payment_invoices.id_company', Auth::user()->currentTeam->id)
            ->whereBetween('payment_invoices.created_at', [$this->start_date, $this->end_date])
            ->where('documents.id_terminal', $this->id_terminal)
            ->where('documents.payment_method', '06')->sum('mount');
        $this->tarjeta = PaymentInvoice::join('documents', 'documents.id', '=', 'payment_invoices.id_document')
            ->where('payment_invoices.id_company', Auth::user()->currentTeam->id)
            ->whereBetween('payment_invoices.created_at', [$this->start_date, $this->end_date])
            ->where('documents.id_terminal', $this->id_terminal)
            ->where('documents.payment_method', '02')->sum('mount');
        $this->total_banco = $this->transferencia + $this->sinpe + $this->tarjeta;
        $this->efectivo = PaymentInvoice::join('documents', 'documents.id', '=', 'payment_invoices.id_document')
            ->where('payment_invoices.id_company', Auth::user()->currentTeam->id)
            ->whereBetween('payment_invoices.created_at', [$this->start_date, $this->end_date])
            ->where('documents.id_terminal', $this->id_terminal)
            ->where('documents.payment_method', '01')->sum('mount') + $this->start_balance;
        $this->cheque =  PaymentInvoice::join('documents', 'documents.id', '=', 'payment_invoices.id_document')
            ->where('payment_invoices.id_company', Auth::user()->currentTeam->id)
            ->whereBetween('payment_invoices.created_at', [$this->start_date, $this->end_date])
            ->where('documents.id_terminal', $this->id_terminal)
            ->where('documents.payment_method', '03')->sum('mount');
        $this->total_efectivo = $this->efectivo + $this->cheque;
        $this->totalEntradas = $this->start_balance + $this->ventas_contado + $this->cxcs + $this->ventas_otros;
        $this->totalSalidas = $this->compras + $this->cxps + $this->compras_otras;
        $this->efectivo_estimado = $this->totalEntradas - $this->totalSalidas;
    }
    public function edit($id)
    {
        $cr = CashRegister::find($id);
        $this->id_cr = $cr->id;
        $this->start_date = date("Y-m-d\TH:i:s", strtotime($cr->created_at));
        $this->start_balance = $cr->start_balance;
        $this->id_terminal = $cr->id_terminal;
        $this->number_terminal = Terminal::find($this->id_terminal)->number;
        $this->id_bo = BranchOffice::find(Terminal::find($this->id_terminal)->id_branch_office)->id;
        $this->name_bo = BranchOffice::find(Terminal::find($this->id_terminal)->id_branch_office)->name_branch_office;
        $this->reporte();
    }
    public function pdf()
    {
        $data = [
            "bo" => $this->name_bo,
            "terminal" => $this->number_terminal,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            "start_balance" => $this->start_balance,
            "ventas_contado" => $this->ventas_contado,
            "cxcs" => $this->cxcs,
            "ventas_otros" => $this->ventas_otros,
            "totalEntradas" => $this->totalEntradas,
            "compras" => $this->compras,
            "cxps" => $this->cxps,
            "totalSalidas" => $this->totalSalidas,
            "total_compras" => $this->total_compras,
            "totalCaja" => $this->totalCaja,
            "total_banco" => $this->total_banco,
            "ventas_credito" => $this->ventas_credito,
            "total_ventas" => $this->total_ventas,
            "efectivo_estimado" => $this->efectivo_estimado,
            "transferencia" => $this->transferencia,
            "transferenciaR" => $this->transferenciaR,
            "sinpe" => $this->sinpe,
            "sinpeR" => $this->sinpeR,
            "tarjeta" => $this->tarjeta,
            "tarjetaR" => $this->tarjetaR,
            "efectivo" => $this->efectivo,
            "cheque" => $this->cheque,
            "total_efectivo" => $this->total_efectivo,
            "total_otros_movimientos" => $this->total_otros_movimientos,
            "totalCheques" => $this->totalCheques,
            "total_monedas" => $this->total_monedas,
            "total_billetes" => $this->total_billetes,
            "cant_5" => $this->cant_5,
            "total_5" => $this->total_5,
            "cant_10" => $this->cant_10,
            "total_10" => $this->total_10,
            "cant_25" => $this->cant_25,
            "total_25" => $this->total_25,
            "cant_50" => $this->cant_50,
            "total_50" => $this->total_50,
            "cant_100" => $this->cant_100,
            "total_100" => $this->total_100,
            "cant_500" => $this->cant_500,
            "total_500" => $this->total_500,
            "cant_1000" => $this->cant_1000,
            "total_1000" => $this->total_1000,
            "cant_2000" => $this->cant_2000,
            "total_2000" => $this->total_2000,
            "cant_5000" => $this->cant_5000,
            "total_5000" => $this->total_5000,
            "cant_10000" => $this->cant_10000,
            "total_10000" => $this->total_10000,
            "cant_20000" => $this->cant_20000,
            "total_20000" => $this->total_20000,
            "cant_50000" => $this->cant_50000,
            "total_50000" => $this->total_50000,
            "linesCheques" => $this->linesCheques,
            "chequeN" => $this->chequeN,
            "chequeR" => $this->chequeR,
            "chequeT" => $this->chequeT,
            "number_terminal" => $this->number_terminal,
            "totalCaja" => $this->totalCaja

        ];
        $pdf = PDF::loadView('livewire.cash-register.cierrePDF', $data);
        $path = 'files/cierres/' . Auth::user()->currentTeam->id_card . '/' . $this->id_cr;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $pdf->save($path . '/' . $this->id_cr . '.pdf');
    }
    public function update()
    {
        DB::beginTransaction();
        try {
            if ($this->id_cr) {
                $result = CashRegister::find($this->id_cr);
                $result->update([
                    'sales' => $this->totalEntradas,
                    'sales' => $this->totalSalidas,
                    'end_balance' => $this->totalCaja,
                    'state' => '0',
                    'finish' => now(),
                    'observation' => $this->observation,
                ]);
                $this->updateMode = false;
                $this->pdf();
                $this->dispatchBrowserEvent('ccr_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cierre realizado con éxito', 'refresh' => 0]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshCashRegisterTable');
    }
}
