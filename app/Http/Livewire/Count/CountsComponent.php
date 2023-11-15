<?php

namespace App\Http\Livewire\Count;

use Livewire\Component;
use App\Models\Count;
use App\Models\CountCategory;
use App\Models\CountPrimary;
use App\Models\CountPrincipal;
use App\Models\CountType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CountsComponent extends Component
{

    public $id_count, $secundary = 0, $count_id, $description, $name, $updateMode = false,   $type_counts, $type_detail_counts;
    public $allCounts, $allCountsS = [], $allCountTypes = [], $allPrimaryCounts, $allCountCategories, $allCountPrincipals = [];
    public $id_type_count = 1, $id_count_primary = 1, $name_count_primary, $name_count;
    public $contador = 1, $initial_balance = 0;
    protected $listeners = ['editCount' => 'edit', 'deleteCount' => 'delete'];

    public function render()
    {
        $this->allCountTypes = CountType::all();
        $this->allCountCategories = CountCategory::all();
        $this->allPrimaryCounts = CountPrimary::all();
        $this->allCounts = Count::where("id_company", "=", Auth::user()->currentTeam->id)->get();
        return view('livewire.count.counts-component');
    }
    public function changeCountType()
    {
        $this->id_count_primary = CountCategory::where('count_categories.id_count_type', '=', $this->id_type_count)->first()->id;
    }
    public function changeCountCategory()
    {
        $this->id_count = Count::where('counts.id_count_category', '=', $this->id_count_primary)->first()->id;
    }
    private function resetInputFields()
    {
        $this->description = '';
        $this->name = '';
        $this->id_type_count = '';
        $this->id_type_detail_count = '';
    }
    public function newAccount($count, $primary)
    {
        $this->id_count = $count;
        $this->name_count = ($count)?$this->allCounts->find($count):null;
        $this->id_count_primary = $primary;
        $this->name_count_primary = ($primary)?$this->allPrimaryCounts->find($primary):null;
    }
    public function store()
    {
        if ($this->secundary) {
            $this->validate([
                'name' => 'required',
                'description' => 'required',
                'initial_balance' => 'required'
            ]);
        }
        DB::beginTransaction();
        try {
            Count::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name,
                'description' => $this->description,
                'id_count_primary' => $this->id_count_primary,
                'id_count' => $this->id_count,
                'initial_balance' => $this->initial_balance
            ]);


            $this->resetInputFields();
            $this->dispatchBrowserEvent('count_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cuenta creada con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshCountTable');
    }

    public function edit($id)
    {

        try {
            $this->updateMode = true;
            $result = Count::where('counts.id', $id)
                ->join('count_categories', 'count_categories.id', '=', 'counts.id_count_category')
                ->join('count_types', 'count_types.id', '=', 'count_categories.id_count_type')
                ->select('counts.*', 'count_categories.id as idCC', 'count_types.id as idCT')->first();
            $this->count_id = $id;
            $this->name = $result->name;
            $this->description = $result->description;
            $this->id_count_primary = $result->idCC;
            $this->id_type_count = $result->idCT;
            $this->id_count = $result->id_count;
            $this->initial_balance = $result->initial_balance;
            if (isset($this->id_count)) {
                $this->secundary = 1;
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        if ($this->secundary) {
            $this->validate([
                'name' => 'required',
                'description' => 'required',
                'id_count_primary' => 'required|exists:count_categories,id',
                'id_count' => 'required|exists:counts,id',
                'initial_balance' => 'required'

            ]);
        } else {
            $this->validate([
                'name' => 'required',
                'description' => 'required',
                'id_count_primary' => 'required|exists:count_categories,id',
                'initial_balance' => 'required'
            ]);
        }
        DB::beginTransaction();
        try {
            if ($this->count_id) {
                $result = Count::find($this->count_id);
                if ($this->secundary) {
                    $result->update([
                        'id_company' => Auth::user()->currentTeam->id,
                        'name' => $this->name,
                        'description' => $this->description,
                        'id_count_category' => $this->id_count_primary,
                        'id_count' => $this->id_count,
                        'initial_balance' => $this->initial_balance
                    ]);
                } else {
                    $result->update([
                        'id_company' => Auth::user()->currentTeam->id,
                        'name' => $this->name,
                        'description' => $this->description,
                        'id_count_category' => $this->id_count_primary,
                        'id_count' => null,
                        'initial_balance' => $this->initial_balance
                    ]);
                }
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('countU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cuenta actualizada con exito', 'refresh' => 0]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshCountTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Count::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Cuenta eliminada con exito', 'refresh' => 0]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.' . $e->getMessage()]);
        }
        $this->emit('refreshCountTable');
    }
}
