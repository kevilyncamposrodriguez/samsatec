<?php

namespace App\Http\Livewire\Product;

use App\Models\BranchOffice;
use App\Models\Cabys;
use App\Models\Category;
use App\Models\ClassProduct;
use App\Models\Count;
use App\Models\DiaryBook;
use App\Models\Discount;
use App\Models\Family;
use App\Models\InternalConsecutive;
use App\Models\Product;
use App\Models\Skuse;
use App\Models\Tax;
use App\Models\TeamUser;
use App\Models\TypeCodeProduct;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;




class CreateProductComponent extends Component
{
    public  $id_family,  $name_family, $id_discount = '', $discount = 0, $id_category, $name_category, $id_count_inventory, $id_count_expense,  $total_tax = 0,  $description, $cabys, $type, $need_rate_iva, $id_count_income, $id_sku, $name_sku, $id_class, $internal_code;
    public $other_code = '', $alert_min = 0, $stock_base = 0, $stock = 0, $tax_base = 0, $total_price = 0, $export_tax = 0, $id_tax, $need_tax_base, $price_unid = 0, $cost_unid = 0;
    public $allCabys, $allClasses = [], $allCategories, $allSkuses, $allFamilies, $allCountInventary = [], $id_bo, $ivai = 0,
        $allBranchOffices, $allDiscounts = [], $allCountIncomes = [], $allCountExpenses = [], $allTaxes = [], $type_code_product = '', $allTypeCodes = [];

    public function mount()
    {
        $this->allCabys = Cabys::all()->take(50);
        $this->allBranchOffices = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allDiscounts = Discount::where("discounts.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allTypeCodes = TypeCodeProduct::all();
        $this->allFamilies = Family::where("families.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allClasses = ClassProduct::where("class_products.id_company", "=", Auth::user()->currentTeam->id)
            ->where('symbol', '!=', 'SERV')->get();
        $this->allCountInventary = Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allCountIncomes = Count::where("id_count_primary", 20)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allCountExpenses = Count::where("id_count_primary", 23)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allCategories = Category::where("categories.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allTaxes = Tax::where("taxes.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->id_tax = Tax::where("taxes.id_company", "=", Auth::user()->currentTeam->id)->first()->id;
        $this->allSkuses = Skuse::where('symbol', '!=', 'Sp')->where('symbol', '!=', 'Spe')->get();
        $teamUser = TeamUser::getUserTeam();
        $this->id_bo = (isset($teamUser->bo)) ? $teamUser->bo : $this->allBranchOffices[0]->id;
        $this->resetInputFields();
    }
    public function render()
    {
        if ($this->type_code_product == '') {
            $this->other_code = '';
        }
        $this->calculator();
        if (!$this->need_tax_base) {
            $this->tax_base = 0;
        }
        if ($category = $this->allCategories->where('name', $this->name_category)->first()) {
            $this->id_category = $category->id;
        }
        if ($family = $this->allFamilies->where('name', $this->name_family)->first()) {
            $this->id_family = $family->id;
        }
        if ($sku = $this->allSkuses->where('symbol', strtok($this->name_sku, '-'))->first()) {
            $this->id_sku = $sku->id;
        }
        if ($this->cabys != null && $this->cabys != '') {
            $this->allCabys = Cabys::where('codigo', 'like', '%' . $this->cabys . '%')->orwhere('descripcion', 'like', '%' . $this->cabys . '%')->get()->take(100);
        }
        $teamUser = DB::table("team_user")->select("*")->where("team_id", Auth::user()->currentTeam->id)->where("user_id", Auth::user()->id)->first();
        return view('livewire.product.create-product-component', ['teamUser' => $teamUser]);
    }
    public function changeClass()
    {
        $result = ClassProduct::where('id', $this->id_class)->first();
        $this->internal_code = $result->symbol . '-' . str_pad($result->consecutive, 6, "0", STR_PAD_LEFT);
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
    public function store()
    {
        $this->validate([
            'internal_code' => 'required|max:20',
            'cabys' => 'required|exists:cabys,codigo',
            'id_count_income' => 'required',
            'id_count_expense' => 'required',
            'id_count_inventory' => 'required',
            'id_sku' => 'required|exists:skuses,id',
            'id_class' => 'required|exists:class_products,id',
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
            'id_category' => 'required|exists:categories,id',
            'id_family' => 'required|exists:families,id',
            'id_discount' => ($this->id_discount != '') ? 'required|exists:discounts,id' : '',
            'type_code_product' => ($this->type_code_product != '') ? 'required|exists:type_code_products,id' : '',
            'other_code' => ($this->type_code_product != '') ? 'required' : '',
        ]);

        DB::beginTransaction();
        try {
            $class = ClassProduct::where('class_products.id', $this->id_class)->first();
            $result = Product::create([
                'id_company' => Auth::user()->currentTeam->id,
                'internal_code' => $this->internal_code,
                'cabys' => trim($this->cabys),
                'id_count_income' => $this->id_count_income,
                'id_count_expense' => $this->id_count_expense,
                'id_count_inventory' => $this->id_count_inventory,
                'id_sku' => $this->id_sku,
                'id_class' => $this->id_class,
                'description' => $this->description,
                'tax_base' => $this->tax_base,
                'export_tax' => $this->export_tax,
                'stock_start' => $this->stock,
                'stock' => $this->stock,
                'price_unid' => $this->price_unid,
                'cost_unid' => $this->cost_unid,
                'id_discount' => ($this->id_discount != '') ? $this->id_discount : null,
                'total_tax' => $this->total_tax,
                'stock_base' => $this->stock_base,
                'alert_min' => $this->alert_min,
                'total_price' => $this->total_price,
                'id_category' => $this->id_category,
                'id_family' => $this->id_family,
                'ids_taxes' => '["' . $this->id_tax . '"]',
                'type' => 1,
                'id_bo' => ($this->id_bo != '') ? $this->id_bo : null,
                'id_type_code_product' => ($this->type_code_product != '') ? $this->type_code_product : NULL,
                'other_code' => ($this->type_code_product != '') ? $this->other_code : NULL,
            ]);
            Product::nextConseutive($this->id_class);
            $ic = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first();
            DiaryBook::create([
                'entry' => 'II-' . $ic->ii,
                'document' => $result->id,
                'id_count' => $this->id_count_inventory,
                'debit' => $this->stock * $this->cost_unid,
                'credit' => 0,
                'id_company' => Auth::user()->currentTeam->id,
                'id_bo' => ($this->id_bo != '') ? $this->id_bo : BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->first()->id,
                'id_user' => Auth::user()->id,
                'name_user' => Auth::user()->name,
            ]);
            DiaryBook::create([
                'entry' => 'II-' . $ic->ii,
                'document' => $result->id,
                'id_count' => Count::where("name", 'AJUSTES INICIALES EN INVENTARIOS')->where("id_company", Auth::user()->currentTeam->id)->first()->id,
                'debit' => 0,
                'credit' =>  $this->stock * $this->cost_unid,
                'id_company' => Auth::user()->currentTeam->id,
                'id_bo' => ($this->id_bo != '') ? $this->id_bo : BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->first()->id,
                'id_user' => Auth::user()->id,
                'name_user' => Auth::user()->name,
            ]);
            $ic->update([
                'ii' => $ic->ii + 1
            ]);
            $this->dispatchBrowserEvent('products-updated', [
                'newValue' => $result->id,
                'products' => Product::where('id_company', '=', Auth::user()->currentTeam->id)
                    ->where('active', '=', 1)->select('description as text', 'id')->get()->toArray()
            ]);
            $this->dispatchBrowserEvent('product_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Producto creado con exito', 'refresh' => 0]);
            $this->emit('refreshProducts');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion ' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion ' . $e->getMessage()]);
            DB::rollback();
        }
        DB::commit();
        $this->resetInputFields();
        $this->emit('refreshProductTable');
    }
    public function resetInputFields()
    {
        $this->id_class = $this->allClasses[0]->id;
        $result = ClassProduct::where('id', $this->id_class)->first();
        $this->internal_code = $result->symbol . '-' . str_pad($result->consecutive, 6, "0", STR_PAD_LEFT);
        $this->cabys = "";
        $this->id_family = $this->allFamilies[0]->id;
        $this->id_class = $this->allClasses[0]->id;
        $this->id_category = $this->allCategories[0]->id;
        $this->id_count_income = $this->allCountIncomes[0]->id;
        $this->id_count_expense = $this->allCountExpenses[0]->id;
        $this->id_count_inventory = (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) ? $this->allCountInventary[0]->id : BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count;
        $this->description = "";
        $this->tax_base = 0;
        $this->stock = 0;
        $this->stock_base = 0;
        $this->alert_min = 0;
        $this->export_tax = 0;
        $this->price_unid = 0;
        $this->cost_unid = 0;
        $this->total_tax = 0;
        $this->ivai = 0;
        $this->total_price = 0;
        $this->type_code_product = '';
        $this->other_code = '';
        $this->id_discount = '';
        $this->name_family = $this->allFamilies[0]->name;
        $this->name_category = $this->allCategories[0]->name;
        $this->name_sku = $this->allSkuses[0]->symbol . '-' . $this->allSkuses[0]->description;
    }
}
