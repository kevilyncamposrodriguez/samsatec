<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CategoriesComponent extends Component
{

    public $allCategories, $category_id, $code, $name, $updateMode = false, $allBO;
    protected $listeners = ['editCategory' => 'edit', 'deleteCategory' => 'delete'];
    public function render()
    {
        return view('livewire.category.categories-component');
    }

    private function resetInputFields()
    {
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Category::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name
            ]);

            $this->resetInputFields();
            $this->dispatchBrowserEvent('category_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Categoria creada con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshCategoryTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = \App\Models\Category::where('id', $id)->first();
            $this->category_id = $id;
            $this->name = $result->name;
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
        $this->validate([
            'name' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->category_id) {
                $result = Category::find($this->category_id);
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'name' => $this->name
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('categoryU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Categoria actualizada con exito', 'refresh' => 1]);
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
        $this->emit('refreshCategoryTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Category::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Categoria eliminada con exito', 'refresh' => 1]);
                $this->emit('refreshCategoryTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
