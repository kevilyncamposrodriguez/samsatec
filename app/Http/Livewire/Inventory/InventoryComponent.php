<?php

namespace App\Http\Livewire\Inventory;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InventoryComponent extends Component
{
    public $description, $stock, $product;
    protected $listeners = ["editInventory"];
    public function render()
    {
        return view('livewire.inventory.inventory-component');
    }
    public function changeStock()
    {
        $this->validate([
            'description' => 'required',
            'stock' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $this->product->update([
                'stock' => $this->stock
            ]);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Inventario actualizado', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
        }
        DB::commit();
        $this->dispatchBrowserEvent('changeStock_modal_hide', []);
        $this->emit('refreshInventoryTable');
        $this->cleanInputs();
    }
    public function editInventory($id)
    {
        $this->cleanInputs();
        $this->product = Product::find($id);
        $this->description = $this->product->description;
        $this->stock = $this->product->stock;
    }
    public function cleanInputs()
    {
        $this->product = null;
        $this->description = '';
        $this->stock = 0;
    }
}
