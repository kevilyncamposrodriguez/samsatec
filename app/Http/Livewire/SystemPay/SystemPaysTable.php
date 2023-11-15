<?php

namespace App\Http\Livewire\SystemPay;

use App\Models\PaymentSystem;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class SystemPaysTable extends LivewireDatatable
{
    protected $listeners = ['refreshSystemPayTable' => '$refresh'];
    public function builder()
    {
        return PaymentSystem::query()->where("id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::callback(['id_pay', 'id'], function ($id_pay, $id) {
                return view('livewire.system-pay.actions', ['id_pay' => $id_pay, 'id' => $id]);
            })->label('Acciones')
                ->alignCenter(),
            Column::callback(['id'], function ($id) {
                return 'FS-' . str_pad($id, 10, "0", STR_PAD_LEFT);
            })
                ->label('Id Factura')
                ->filterable(),
                Column::raw('(CASE 
                WHEN id_pay = "" THEN "Pendiente"
                ELSE "Pagado" END) AS state_pay')
                    ->label('Estado')
                    ->filterable(["Pendiente", "Pagado"]),
            DateColumn::name('start_pay')
                ->label('Facturado')
                ->defaultSort('desc')
                ->filterable(),
            DateColumn::callback(['start_pay', 'pay_amount'], function ($start_pay, $no) {
                return date("d/m/Y", strtotime($start_pay . "+ " . 5 . " days"));
            })
                ->label('Vence')
                ->filterable(),
            DateColumn::callback(['start_pay', 'months'], function ($start_pay, $months) {
                return date("d/m/Y", strtotime($start_pay . "+ " . $months . " month"));
            })
                ->label('Proximo pago')
                ->filterable(),
            Column::callback(['pay_amount'], function ($pay_amount) {
                return number_format($pay_amount, 2, '.', ',');
            })
                ->label('Precio total')
                ->filterable(),

        ];
    }
}
