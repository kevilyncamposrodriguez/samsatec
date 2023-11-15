<?php

namespace App\Http\Livewire\Inventory;

use App\Models\Count;
use App\Models\InventoryAjustment;
use App\Models\TeamUser;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class InventoryAjustmentTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshAITable' => '$refresh'];
    public function builder()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            return InventoryAjustment::query()->where("inventory_ajustments.id_company", Auth::user()->currentTeam->id)
                ->leftjoin('counts', 'counts.id', '=', 'inventory_ajustments.id_count');
        } else {
            return InventoryAjustment::query()->where("inventory_ajustments.id_company", Auth::user()->currentTeam->id)
                ->where('id_terminal', TeamUser::getUserTeam()->terminal)
                ->leftjoin('counts', 'counts.id', '=', 'inventory_ajustments.id_count');
        }
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'type', 'consecutive'], function ($id, $type, $consecutive) {
                return view('livewire.inventory.actionsAI', ['id' => $id, 'type' => $type, 'consecutive' => $consecutive]);
            })->unsortable()->label('Acciones')
                ->excludeFromExport()
                ->alignCenter(),
            NumberColumn::name('consecutive')
                ->label('Numero')
                ->searchable()
                ->sortable()
                ->defaultSort("desc")
                ->filterable()
                ->alignCenter(),
            Column::name('counts.name')
                ->label('Cuenta de Inventario')
                ->searchable()
                ->sortable()
                ->filterable($this->counts->pluck('name')),
            Column::name('type')
                ->label('Tipo de Ajuste')
                ->searchable()
                ->sortable()
                ->filterable()
                ->alignCenter(),
            NumberColumn::name('total')
                ->label('Ajuste')
                ->filterable()
                ->sortable()
                ->alignCenter(),
            Column::name('observation')
                ->label('Observacion')
                ->searchable()
                ->sortable()
                ->filterable()
                ->alignCenter(),
            Column::name('nameuser')
                ->label('Usuario')
                ->searchable()
                ->sortable()
                ->filterable()
                ->alignCenter()
        ];
    }
    public function getCountsProperty()
    {
        return Count::where("id_company", "=", Auth::user()->currentTeam->id)->where("id_count_primary", 4);
    }
}
