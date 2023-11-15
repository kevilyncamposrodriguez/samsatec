<?php

namespace App\Http\Livewire\Product;

use App\Imports\ProductImport;
use App\Models\Count;
use Livewire\Component;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ProductsComponent extends Component
{
    use WithFileUploads;
    public $type = '', $file_import = '', $allCountInventary, $id_count_inventory, $id_sku;
    public function mount()
    {
        $this->allCountInventary = Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count_inventory = $this->allCountInventary[0]->id;
    }
    public function render()
    {
        return view('livewire.product.products-component');
    }
    public function changeSku($id)
    {
        $this->id_sku = $id;
    }
    public function importProducts()
    {
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls', // 5MB Max
        ]);
        if ($this->file_import) {
            try {
                $import = new ProductImport($this->id_count_inventory);
                $import->import($this->file_import);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Archivo cargado']);
                $this->dispatchBrowserEvent('productImport_modal_hide', []);
                $this->emit('refreshProductTable');
            } catch (ValidationException $error) {
                $failures = $error->failures();
                $error = 'Errores al cargar el archivo: ';
                foreach ($failures as $failure) {
                    $error .= 'linea: ' . $failure->row() . ' Columna: ' . $failure->attribute() . ' error : ' . $failure->errors()[0] . ' --> ';
                }
                $this->dispatchBrowserEvent('errorData', ['errorData' => $error]);
            } catch (QueryException $e) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => $e->errorInfo[2]]);
            }
        } else {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Documento vacio']);
        }
    }

    public function delete($id)
    {
        try {
            if ($id) {
                if (Product::where('id', $id)->update(
                    [
                        'active' => 0
                    ]
                )) {
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Producto o servicio eliminado con exito', 'refresh' => 0]);
                    $this->emit('refreshProductTable');
                } else {
                    $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function newProduct($type)
    {
        $this->type = $type;
        $this->emit('edit_type', $type);
    }
}
