<?php

namespace App\Http\Livewire\SystemPayAdmin;

use App\Models\Team;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientsTable extends LivewireDatatable
{
    public function builder()
    {
        return Team::query();
    }

    public function columns()
    {
        return [

            Column::name('name')
                ->label('Nombre')
                ->unwrap()
                ->searchable()
                ->defaultSort('asc')
                ->filterable()
                ->alignCenter(),
            Column::name('id_card')
                ->label('Identificación')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('phone_company')
                ->label('Telefono')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('email_company')
                ->label('Correo')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::callback(['npay'], function ($npay) {
                return !$npay;
            })->exportCallback(function ($npay) {
                return !$npay;
            })
                ->label('Paga')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('active')
                ->label('Activo')
                ->searchable()
                ->filterable()
                ->alignCenter()
        ];
    }
}
