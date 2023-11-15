<?php

namespace App\Http\Livewire\SystemPayAdmin;

use App\Models\PaymentSystem;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class SystemPaysAdminTable extends LivewireDatatable
{
    protected $listeners = ['refreshSystemPaysAdminTable' => '$refresh'];
    public function builder()
    {
        return PaymentSystem::query()
            ->leftJoin('teams', 'id_company', 'teams.id')
            ->where('teams.npay', 0);
    }

    public function columns()
    {
        return [
            Column::callback(['id_pay', 'id'], function ($id_pay, $id) {
                return view('livewire.system-pay-admin.actions', ['id_pay' => $id_pay, 'id' => $id]);
            })->label('Acciones')
                ->alignCenter(),
            Column::name('type_pay')
                ->label('Tipo Pago')
                ->sortable()
                ->filterable(['Ridivi','Otro']),
            Column::name('teams.id_card')
                ->label("Cedula")
                ->filterable()
                ->unwrap()
                ->sortable(),
            Column::name('teams.name')
                ->label('Nombre')
                ->filterable()
                ->unwrap()
                ->sortable(),
            Column::callback(['id'], function ($id) {
                return 'FS-' . str_pad($id, 10, "0", STR_PAD_LEFT);
            })

                ->defaultSort('desc')
                ->label('Id Factura')
                ->filterable(),
            Column::raw('(CASE 
            WHEN id_pay is null THEN "Pendiente"
            ELSE "Pagado" END) AS state_pay')
                ->label('Estado')
                ->filterable(["Pendiente", "Pagado"]),
            DateColumn::name('start_pay')
                ->label('Generada')
                ->sortable()
                ->filterable(),
            DateColumn::name('payment_due')
                ->label('Vence')
                ->sortable()
                ->filterable(),
            DateColumn::name('next_pay')
                ->label('Proximo pago')
                ->sortable()
                ->filterable(),
            Column::callback(['pay_amount'], function ($pay_amount) {
                return number_format($pay_amount, 2, '.', ',');
            })
                ->label('Precio total')
                ->filterable(),

        ];
    }
}
