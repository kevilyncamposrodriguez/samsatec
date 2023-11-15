<?php

namespace App\Http\Livewire\Buyer;

use App\Models\Buyer;
use App\Models\TypeIdCards;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class BuyersTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshBuyerTable' => '$refresh'];
    public function builder()
    {
        return Buyer::query()->where('buyers.id_company', Auth::user()->currentTeam->id)
            ->where('buyers.active', '1')
            ->join('providers', 'providers.id', '=', 'buyers.id_provider')
            ->join('provinces', 'provinces.id', '=', 'buyers.id_province')
            ->join('cantons', 'cantons.id', '=', 'buyers.id_canton')
            ->join('districts', 'districts.id', '=', 'buyers.id_district')
            ->join('country_codes', 'country_codes.id', '=', 'buyers.id_country_code')
            ->join('type_id_cards', 'type_id_cards.id', '=', 'buyers.type_id_card');;
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('livewire.buyer.actions', ['id' => $id]);
            })->unsortable()->label('Acciones')
                ->alignCenter(),
            Column::name('id_card')
                ->label('Identificación')
                ->searchable()
                ->filterable()
                ->truncate(),
            Column::name('name')
                ->label('Nombre')
                ->searchable()
                ->filterable()
                ->alignCenter(),
            Column::name('phone')
                ->label('Telefono')
                ->filterable()
                ->alignCenter(),
            Column::name('emails')
                ->label('Correo')
                ->filterable()
                ->alignCenter()
        ];
    }
}
