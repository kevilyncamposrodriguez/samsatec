<?php

namespace App\Http\Livewire\PayMethodInformation;

use App\Models\PayMethodInformation;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PayMethodsInformationTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshPayMethodInformationTable' => '$refresh'];
    public function builder()
    {
     return PayMethodInformation::query();
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.pay-method-information.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            DateColumn::name('created_at')
                ->label('Creado')
                ->searchable()
                ->filterable(),
            Column::name('id')
                ->label('Codigo')
                ->defaultSort('asc')
                ->searchable()
                ->filterable(),
            Column::name('count_number')
                ->label('Cuenta')
                ->searchable()
                ->filterable(),
            Column::name('count_owner')
                ->label('A nombre de')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('email_notification')
                ->label('Correo de notificación')
                ->searchable()
                ->filterable()
                ->truncate(),
            BooleanColumn::name('use_by_default')
                ->label('Es por defecto')
                ->searchable()
                ->filterable()
                ->truncate(),
            BooleanColumn::name('active')
                ->label('Activo')
                ->searchable()
                ->filterable()
                ->truncate()
        ];
    }
}
