<?php

namespace App\Http\Livewire\Product;

use App\Imports\ProductImport;
use App\Models\BranchOffice;
use Livewire\Component;
use App\Models\Product;
use App\Models\Skuse;
use App\Models\Family;
use App\Models\Category;
use App\Models\Cabys;
use App\Models\ClassProduct;
use App\Models\Count;
use App\Models\Discount;
use App\Models\PriceListsLists;
use App\Models\Tax;
use App\Models\TypeCodeProduct;
use Facade\FlareClient\View;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\View\View as IlluminateViewView;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class UpdateServiceComponent extends Component
{
    use WithPagination;
    use WithFileUploads;
    //datos para manejo de proveedores
    public  $id_family,  $name_family, $id_discount = '', $discount = 0, $id_category, $name_category, $id_count_inventory, $id_count_expense,  $total_tax = 0,  $description, $cabys, $name_cabys, $type, $need_rate_iva, $id_count_income, $id_sku, $name_sku, $id_class, $internal_code;
    public $other_code = '', $alert_min = 0, $stock_base = 0, $stock = 0, $tax_base = 0, $total_price = 0, $export_tax = 0, $id_tax, $ivai, $need_tax_base, $price_unid = 0, $cost_unid = 0;
    public $allCabys, $allClasses = [], $allCategories, $allSkuses, $allFamilies, $allCountInventary = [], $id_bo,
        $allBranchOffices, $allDiscounts = [], $allCountIncomes = [], $allCountExpenses = [], $allTaxes = [], $type_code_product = '', $allTypeCodes = [];
    protected $listeners = ['refreshProducts' => '$refresh', 'changeSkuUP', 'editService' => 'edit', 'deleteProduct' => 'delete'];
    public function mount()
    {
        $this->allBranchOffices = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allCabys = Cabys::all()->take(100);
        $this->allDiscounts = Discount::where("discounts.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allTypeCodes = TypeCodeProduct::all();
        $this->allCountInventary = Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allCountIncomes = Count::where("id_count_primary", 20)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allCountExpenses = Count::where("id_count_primary", 24)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allTaxes = Tax::where("taxes.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allSkuses = Skuse::where('symbol', '!=', 'Sp')->where('symbol', '!=', 'Spe')->get();
        $teamUser = DB::table("team_user")->select("*")->where("team_id", Auth::user()->currentTeam->id)->where("user_id", Auth::user()->id)->first();
        $this->id_bo = (isset($teamUser->bo)) ? $teamUser->bo : '';
        $this->resetInputFields();
    }
    public function newProduct($type)
    {
        $this->type = $type;
        $this->emit('edit_type', $type);
    }
    public function render()
    {
        $this->calculator();
        if (!$this->need_tax_base) {
            $this->tax_base = 0;
        }
        if ($sku = $this->allSkuses->where('symbol', strtok($this->name_sku, '-'))->first()) {
            $this->id_sku = $sku->id;
        }
        if ($this->cabys != null && $this->cabys != '') {
            $this->allCabys = Cabys::where('codigo', 'like', '%' . $this->cabys . '%')->orwhere('descripcion', 'like', '%' . $this->cabys . '%')->get()->take(100);
        }
        $teamUser = DB::table("team_user")->select("*")->where("team_id", Auth::user()->currentTeam->id)->where("user_id", Auth::user()->id)->first();
        return view('livewire.product.update-service-component', ['teamUser' => $teamUser]);
    }
    public function changeSkuUS($id)
    {
        $this->id_sku = $id;
    }
    public function importProducts()
    {
        $this->validate([
            'file_import' => 'required|mimes:xlsx,xls', // 5MB Max
        ]);
        try {
            if ($this->file_import) {
                $mensaje = Excel::import(new ProductImport, $this->file_import);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Se han cargado ']);
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Documento vacio']);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al cargar los archivos' . $e->getMessage()]);
        }
    }
    public function resetInputFields()
    {
        $this->id_class = 1;
        $this->internal_code = "";
        $this->cabys = "";
        $this->id_category = 1;
        $this->id_family = 1;
        $this->id_count_inventory = 1;
        $this->id_count_income = 1;
        $this->id_count_expense = 1;
        $this->description = "";
        $this->tax_base = 0;
        $this->stock = 0;
        $this->stock_base = 0;
        $this->alert_min = 0;
        $this->id_sku = 1;
        $this->export_tax = 0;
        $this->price_unid = 0;
        $this->cost_unid = 0;
        $this->total_tax = 0;
        $this->total_price = 0;
        $this->type_code_product = '';
        $this->other_code = '';
        $this->id_discount = null;
        $this->discount = 0;
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $result = Product::where('id', $id)->first();
        $this->product_id = $id;
        $this->internal_code = $result->internal_code;
        $this->cabys = str_pad($result->cabys, 13, "0", STR_PAD_LEFT);
        $this->id_count_income = $result->id_count_income;
        $this->id_count_expense = $result->id_count_expense;
        $this->id_count_inventory = $result->id_count_inventory;
        $this->id_sku = $result->id_sku;
        $this->id_family = $result->id_family;
        $this->id_category = $result->id_category;
        $this->description = $result->description;
        $this->id_tax = ($result->ids_taxes != '[]')?json_decode($result->ids_taxes, true)[0]:'';
        $this->tax_base = $result->tax_base;
        $this->export_tax = $result->export_tax;
        $this->id_discount = $result->id_discount;
        $this->stock = $result->stock;
        $this->cost_unid = $result->cost_unid;
        $this->total_tax = $result->total_tax;
        $this->id_class = $result->id_class;
        $this->stock_base = $result->stock_base;
        $this->alert_min = $result->alert_min;
        $this->total_price = $result->total_price;
        $result = Tax::join('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
            ->select('taxes_codes.code as code', 'taxes.rate as rate')->where('taxes.id', $this->id_tax)->first();
        $rate = ($result) ? $result->rate : 0;
        $this->price_unid = $this->total_price / (1 + ($rate / 100));
        $this->type = $result->type;
        $this->type_code_product = $result->id_type_code_product;
        $this->other_code = $result->other_code;
        $sku = Skuse::find($this->id_sku);
        $this->name_sku = $sku->symbol . '-' . $sku->description;
    }
    public function changeClass()
    {
        $result = ClassProduct::where('id', $this->id_class)->first();
        $this->internal_code = $result->symbol . '-' . str_pad($result->consecutive, 6, "0", STR_PAD_LEFT);
    }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        if ($this->type) {
            $this->validate([
                'internal_code' => 'required|min:11|max:11',
                'cabys' => 'required|min:13|max:13',
                'id_count_income' => 'required',
                'id_count_expense' => 'required',
                'id_count_inventory' => 'required',
                'id_sku' => 'required|exists:skuses,id',
                'id_class' => 'required',
                'description' => 'required|unique',
                'tax_base' => 'required',
                'export_tax' => 'required',
                'stock' => 'required',
                'price_unid' => 'required',
                'cost_unid' => 'required',
                'total_tax' => 'required',
                'stock_base' => 'required',
                'alert_min' => 'required',
                'total_price' => 'required',
                'id_category' => 'required|exists:categories,id',
                'id_family' => 'required|exists:families,id',
                'type_code_product' => ($this->type_code_product != '') ? 'required|exists:type_code_products,id' : '',
                'other_code' => ($this->type_code_product != '') ? 'required' : '',
            ]);
        } else {
            $this->validate([
                'internal_code' => 'required|min:11|max:11',
                'cabys' => 'required|min:13|max:13',
                'id_count_income' => 'required',
                'id_count_expense' => 'required',
                'id_sku' => 'required|exists:skuses,id',
                'description' => 'required',
                'tax_base' => 'required',
                'export_tax' => 'required',
                'stock' => 'required',
                'price_unid' => 'required',
                'cost_unid' => 'required',
                'total_tax' => 'required',
                'stock_base' => 'required',
                'alert_min' => 'required',
                'total_price' => 'required',
                'type_code_product' => ($this->type_code_product != '') ? 'required|exists:type_code_products,id' : '',
                'other_code' => ($this->type_code_product != '') ? 'required' : '',
            ]);
        }
        DB::beginTransaction();
        try {
            if ($this->product_id) {
                $bo = Product::find($this->product_id);
                if ($this->type) {
                    $bo->update([
                        'id_company' => Auth::user()->currentTeam->id,
                        'internal_code' => trim($this->internal_code),
                        'cabys' => trim($this->cabys),
                        'id_count_income' => $this->id_count_income,
                        'id_count_expense' => $this->id_count_expense,
                        'id_count_inventory' => $this->id_count_inventory,
                        'id_sku' => $this->id_sku,
                        'description' => $this->description,
                        'tax_base' => $this->tax_base,
                        'export_tax' => $this->export_tax,
                        'stock' => $this->stock,
                        'price_unid' => $this->price_unid,
                        'cost_unid' => $this->cost_unid,
                        'total_tax' => $this->total_tax,
                        'alert_min' => $this->alert_min,
                        'stock_base' => $this->stock_base,
                        'id_class' => $this->id_class,
                        'total_price' => $this->total_price,
                        'id_discount' => ($this->id_discount != '') ? $this->id_discount : null,
                        'id_category' => $this->id_category,
                        'id_family' => $this->id_family,
                        'ids_taxes' => '["' . $this->id_tax . '"]',
                        'id_type_code_product' => ($this->type_code_product != '') ? $this->type_code_product : NULL,
                        'other_code' => ($this->type_code_product != '') ? $this->other_code : NULL,
                    ]);
                } else {
                    $bo->update([
                        'id_company' => Auth::user()->currentTeam->id,
                        'internal_code' => trim($this->internal_code),
                        'cabys' => trim($this->cabys),
                        'id_count_income' => $this->id_count_income,
                        'id_count_expense' => $this->id_count_expense,
                        'id_count_inventory' => $this->id_count_inventory,
                        'id_sku' => $this->id_sku,
                        'description' => $this->description,
                        'tax_base' => $this->tax_base,
                        'export_tax' => $this->export_tax,
                        'stock' => $this->stock,
                        'price_unid' => $this->price_unid,
                        'cost_unid' => $this->cost_unid,
                        'total_tax' => $this->total_tax,
                        'alert_min' => $this->alert_min,
                        'stock_base' => $this->stock_base,
                        'total_price' => $this->total_price,
                        'ids_taxes' => '["' . $this->id_tax . '"]',
                        'id_type_code_product' => ($this->type_code_product != '') ? $this->type_code_product : NULL,
                        'other_code' => ($this->type_code_product != '') ? $this->other_code : NULL,
                    ]);
                }
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('serviceU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Servicio actualizado con exito', 'refresh' => 1]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshProductTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                PriceListsLists::where('id_product', $id)->delete();
                if (Product::where('id', $id)->delete()) {
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Producto o servicio eliminado con exito', 'refresh' => 1]);
                    $this->emit('refreshProductTable');
                } else {
                    $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function edit_type($type = 0)
    {
        $this->type = $type;
    }
    public function changeIvai()
    {
        $this->price_unid = 0;
        $this->total_price = 0;
        $this->total_tax = 0;
    }
    public function calculator()
    {
        $this->discount = ($this->id_discount == '') ? 0 : Discount::find($this->id_discount)->amount;
        $this->total_tax = 0;
        if ($this->id_tax != '' && $this->price_unid != '' && $this->total_price != '') {
            $ntb = false;
            $nri = false;
            $iva = '';
            $result = Tax::join('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                ->select('taxes_codes.code as code', 'taxes.rate as rate')->where('taxes.id', $this->id_tax)->first();
            if ($this->ivai) {
                $this->price_unid = $this->total_price / (1 + ($result->rate / 100));
            }
            if ($result->code == 7) {
                $ntb = true;
            }
            if ($result->code == 8) {
                $nri = true;
            }
            $this->need_rate_iva = $nri;
            $this->need_tax_base = $ntb;
            $result = Tax::join('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                ->select('taxes_codes.code', 'taxes.rate', 'taxes.exoneration_amount')->where('taxes.id', $this->id_tax)->first();
            if ($result->code == 1) {
                if ($this->need_tax_base) {
                    if ($this->tax_base != '') {
                        $this->total_tax += ($this->tax_base * ($result->rate - $result->exoneration_amount)) / 100;
                    }
                } else {
                    if ($this->price_unid != '') {
                        $this->total_tax += (($this->price_unid - $this->discount) * ($result->rate - $result->exoneration_amount)) / 100;
                    }
                }
            } else if ($result->code == 7) {
                if ($this->tax_base != '') {
                    $this->total_tax += ($this->tax_base * ($result->rate - $result->exoneration_amount)) / 100;
                }
            } else if ($result->code == 8) {
                if ($this->rate_iva != '') {
                    if ($this->need_tax_base) {
                        $this->total_tax += $this->tax_base * ($this->rate_iva);
                    } else {
                        $this->total_tax += ($this->price_unid - $this->discount) * ($this->rate_iva - 1);
                    }
                }
            } else {
                if ($this->tax_base != '') {
                    if ($this->need_tax_base) {
                        $this->total_tax += ($this->tax_base * ($result->rate - $result->exoneration_amount) * 0.01);
                    } else {
                        $this->total_tax += (($this->price_unid - $this->discount) * ($result->rate - $result->exoneration_amount) * 0.01);
                    }
                }
            }
            if ($this->need_tax_base) {
                $this->total_price = ($this->tax_base != '') ? round($this->tax_base + $this->total_tax, 5) : 0;
            } else {
                $this->total_price = ($this->price_unid != '') ? round(($this->price_unid - $this->discount) + $this->total_tax, 5) : 0;
            }
            $this->total_tax = round($this->total_tax, 5);
            $this->price_unid = round($this->price_unid, 5);
        }
    }
}
