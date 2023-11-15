<?php

namespace App\Http\Livewire\PriceList;

use App\Models\PriceList;
use App\Models\PriceListsLists;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PriceListsComponent extends Component
{
    public $re, $allPriceList = [], $allPriceListList = [], $description, $price = 0, $name, $productName, $updateMode = false, $price_list_id = '', $price_list_list_id = '';
    protected $listeners = ['changeList'];

    public function render()
    {
        $this->emit('setId', $this->price_list_id);
        $this->allPriceList = PriceList::where("price_lists.id_company", "=", Auth::user()->currentTeam->id)->get();
        return view('livewire.price-list.price-lists-component');
    }
    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        DB::beginTransaction();
        try {
            PriceList::create([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name,
                'description' => $this->description
            ]);


            $this->resetInputFields();            
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Lista de precios creada con exito', 'refresh' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . '<br>' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . '<br>' . $e->getMessage()]);
        }
        DB::commit();
        $this->dispatchBrowserEvent('priceList_modal_hide', []);
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $result = PriceList::where('id', $id)->first();
        $this->name = $result->name;
        $this->description = $result->description;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        if ($this->price_list_id) {
            $result = PriceList::find($this->price_list_id);
            $result->update([
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name,
                'description' => $this->description
            ]);
            $this->updateMode = false;
            $this->resetInputFields();
            $this->dispatchBrowserEvent('priceListU_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Lista de precios actualizada con exito']);
        }
    }
    public function updatePrice()
    {
        $this->validate([
            'price' => 'required'
        ]);
        if ($this->price_list_list_id) {
            $result = PriceListsLists::find($this->price_list_list_id);
            $result->update([
                'price' => $this->price
            ]);
            $this->updateMode = false;
            $this->resetInputFields();
            $this->dispatchBrowserEvent('priceListU_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Lista de precios actualizada con exito']);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            if ($id) {
                PriceListsLists::where('id_price_list', $id)->delete();
                PriceList::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Lista de precios eliminada con exito']);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
        DB::commit();
        $this->price_list_id = '';
        $this->resetInputFields();
    }
}
