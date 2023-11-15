<?php

namespace App\Http\Livewire\Product;

use App\Models\Count;
use App\Models\DiaryBook;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class DiaryBooksTable extends LivewireDatatable
{
    public $hideable = 'select';
    public $exportable = true;
    protected $listeners = ['refreshProductTable' => '$refresh'];
    public function builder()
    {
        return DiaryBook::query()->where("id_company", "=", Auth::user()->currentTeam->id);
    }

    public function columns()
    {
        return [
            Column::name('entry')
                ->label('Asiento')
                ->filterable(),
            DateColumn::name('updated_at')
                ->label('Fecha')
                ->sortBy('updated_at')
                ->defaultSort("desc")
                ->sortable()
                ->filterable(),
            Column::name('document')
                ->label('Documento'),
            Column::callback(['id_count'], function ($id_count) {
                return Count::find($id_count)->name;
            })
                ->label('Cuenta Contable'),
            NumberColumn::callback(['debit'], function ($debit) {
                return number_format($debit, 2, '.', ',');
            })
                ->label('Debito'),
            NumberColumn::callback(['credit'], function ($credit) {
                return number_format($credit, 2, '.', ',');
            })
                ->label('Credito')

        ];
    }
    public function getAccountsProperty()
    {
        return Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id);
    }
}
