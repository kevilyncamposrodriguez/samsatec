<?php

namespace App\Http\Livewire\Inventory;

use App\Models\BranchOffice;
use App\Models\Count;
use App\Models\DiaryBook;
use App\Models\InternalConsecutive;
use App\Models\InventoryAjustment;
use App\Models\InventoryAjustmentsDetail;
use App\Models\Product;
use App\Models\TeamUser;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDF;

class InventoryAjustmentComponent extends Component
{
    public $date, $consecutive, $allInventaries, $allInventariesE, $allInventariesS, $inventary, $observation, $allProducts, $id_product,
        $name_product, $qty_start, $cost, $costS, $costE, $qty, $total_line, $qty_end, $allLines = [], $allLinesT = [], $total,
        $terminal, $id_user, $username, $id_bo, $id_terminal, $allBranchOffices, $allTerminals, $tu;
    public $qtyAS, $qtyNS, $qtyAE, $type, $qtyNE, $inventaryS,  $inventaryE, $allProductsE, $allProductsS, $productE, $productS, $qtyTransfer, $name_productS, $name_productE;
    protected $listeners = ['cleanInputs', 'cleanInputsT', "editT"];
    public function mount()
    {
        $this->allInventariesE = [];
        $this->allProductsE = [];
        $this->allProductsS = [];
        $this->allProducts = [];
        $this->qty_start = 0;
        $this->cost = 0;
        $this->costS = 0;
        $this->costE = 0;
        $this->qty = 0;
        $this->qty_end = 0;
        $this->total_line = 0;
        $this->total = 0;
        $this->id_user = Auth::user()->id;
        $this->username = Auth::user()->name;
        $this->allInventariesE = $this->allInventariesS =  $this->allInventaries = Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allBranchOffices = BranchOffice::where("id_company", Auth::user()->currentTeam->id)->get();
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->id_bo = $this->allBranchOffices[0]->id;
            $this->inventary = BranchOffice::find($this->allBranchOffices[0]->id)->id_count;
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventary)
                ->where("products.active", '1')->get();
        } else {
            $this->id_bo = TeamUser::getUserTeam()->bo;
            $this->inventary = BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count;
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventary)
                ->where("products.active", '1')->get();
        }
        $this->allTerminals = Terminal::where("id_company", Auth::user()->currentTeam->id)->where("id_branch_office", $this->id_bo)->get();
        $this->id_terminal = (isset($this->tu->terminal) && $this->tu->terminal) ? $this->tu->terminal : $this->allTerminals[0]->id;
    }
    public function cleanInputsT()
    {
        $this->allLinesT = [];
        $this->allProductsS = [];
        $this->inventaryS = '';
        $this->allProductsE = [];
        $this->productS = '';
        $this->productE = '';
        $this->name_productE = '';
        $this->name_productS = '';
        $this->inventaryE = '';
        $this->qtyTransfer = 0;
        $this->qtyAE = 0;
        $this->qtyNE = 0;
        $this->qtyNS = 0;
        $this->qtyAS = 0;
        $this->cost = 0;
        $this->total = 0;
        $this->observation = '';
        $this->qtyTransfer = 0;
        $this->updateInventaryS();
        $this->updateInventaryE();
    }
    public function render()
    {
        return view('livewire.inventory.inventory-ajustment-component');
    }
    public function updateBO()
    {
        if ((Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))) {
            $this->inventary = BranchOffice::find($this->id_bo)->id_count;
        } else {
            $this->inventary = BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count;
        }
        if ($this->inventary) {
            $this->id_terminal = (isset($this->tu->terminal) && $this->tu->terminal) ? $this->tu->terminal : Terminal::where('id_branch_office', $this->id_bo)->first()->id;
        } else {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error, no hay cuenta de inventario asiganda a la sucursal, favor verificar en Configuraciones->Sucursales ']);
        }
        $this->updateInventory();
    }
    public function updateInventory()
    {
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventary)
                ->where("products.active", '1')->get();
        } else {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventary)
                ->where("products.active", '1')->get();
        }
    }
    public function updateInventaryE()
    {
        $this->allProductsE = ($this->inventaryE != '') ? Product::where('id_company', Auth::user()->currentTeam->id)
            ->where('id_count_inventory', $this->inventaryE)
            ->where('active', '1')->get() : [];
        $this->qtyAE = 0;
        $this->productE = '';
        if ($this->productS != '') {
            $product = Product::find($this->productS);
            $p = Product::where('id_company', Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventaryE)
                ->where('internal_code', $product->internal_code)->first();
            if ($p) {
                $this->productE = $p->id;
                $this->qtyAE = $p->stock;
                $this->qtyNE = ($this->qtyTransfer != '') ? $this->qtyAE + $this->qtyTransfer : $this->qtyAE;
            } else {
                $this->productE = '';
                $this->name_productE = '';
                $this->qtyAE = 0;;
                $this->qtyNE = 0;
            }
        }
    }
    public function updateInventaryS()
    {
        $this->allProductsS = ($this->inventaryS != '') ? Product::where('id_company', Auth::user()->currentTeam->id)
            ->where('id_count_inventory', $this->inventaryS)
            ->where('active', '1')->get() : [];
        $this->qtyAS = 0;
        $this->qtyNS = 0;
        $this->qtyAE = 0;
        $this->qtyNE = 0;
        $this->productS = '';
        $this->name_productS = '';
        $this->name_productE = '';
        $this->inventaryE = '';
        $this->allProductsE = [];
        $this->allInventariesE = Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->where('counts.id', '!=', $this->inventaryS)->get();
    }
    public function updateProductS()
    {
        $product = Product::find($this->productS);
        if ($product) {
            $this->qtyAS = $product->stock;
            $this->qtyNS = ($this->qtyTransfer != '') ? $this->qtyAS - $this->qtyTransfer : $this->qtyAS;
            $this->cost = $product->cost_unid;
        }
        if ($this->inventaryE != '') {
            $p = Product::where('id_company', Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventaryE)
                ->where('internal_code', $product->internal_code)->first();
            if ($p) {
                $this->productE = $p->id;
                $this->qtyAE = $p->stock;
                $this->qtyNE = ($this->qtyTransfer != '') ? $this->qtyAE + $this->qtyTransfer : $this->qtyAE;
            } else {
                $this->productE = '';
                $this->qtyAE = 0;;
                $this->qtyNE = 0;
            }
        }
    }
    public function updateQty()
    {
        $this->qty = ($this->qty == '') ? 0 : $this->qty;
        $this->qty_end = $this->qty_start + (($this->qty != '') ? $this->qty : 0);
        $this->total_line = $this->qty * (($this->cost != '') ? $this->cost : 0);
    }
    public function updateProductE()
    {
        $product = Product::find($this->productE);
        if ($product) {
            $this->qtyAE = $product->stock;
            $this->qtyNE = ($this->qtyTransfer != '') ? $this->qtyAE + $this->qtyTransfer : $this->qtyAE;
        } else {
            $this->qtyAE = 0;
            $this->qtyNE = 0;
        }
    }
    public function updateQtyTransfer()
    {
        $this->qtyTransfer = ($this->qtyTransfer > $this->qtyAS) ? $this->qtyAS : $this->qtyTransfer;
        $this->qtyNS = ($this->qtyTransfer != '') ? $this->qtyAS - $this->qtyTransfer : $this->qtyAS;
        $this->qtyNE = ($this->qtyTransfer != '') ? $this->qtyAE + $this->qtyTransfer : $this->qtyAE;
    }
    public function newAI()
    {
        $this->inventary = (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) ? BranchOffice::find($this->id_bo)->id_count : BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count;
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventary)
                ->where("products.active", '1')->get();
        } else {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', $this->inventary)
                ->where("products.active", '1')->get();
        }
        $this->consecutive = InternalConsecutive::where("id_company", Auth::user()->currentTeam->id)->first()->ai;
        $this->type = 'Ajuste';
    }
    public function newTransfer()
    {
        $this->consecutive = InternalConsecutive::where("id_company", Auth::user()->currentTeam->id)->first()->ai;
        $this->type = 'Traslado';
    }
    public function changeProduct()
    {
        if ($this->name_product != '') {
            $product = $this->allProducts->where('description', $this->name_product)->first();
            $this->id_product = $product->id;
            $this->qty_start = $product->stock;
            $this->cost = $product->cost_unid;
        }
    }
    public function addLine()
    {
        $this->validate([
            'id_product' => 'required',
            'qty_start' => 'required',
            'qty' => 'required',
            'qty_end' => 'required',
            'cost' => 'required',
            'total_line' => 'required'
        ]);
        $line = [
            "id_product" => $this->id_product,
            "name_product" => $this->name_product,
            "qty_start" => $this->qty_start,
            'qty' => $this->qty,
            "qty_end" => $this->qty_end,
            "cost" => $this->cost,
            "total_line" => $this->total_line
        ];
        array_push($this->allLines, $line);
        $this->total += $this->total_line;
        $this->name_product = '';
        $this->qty_start = 0;
        $this->qty_end = 0;
        $this->cost = 0;
        $this->qtyTransfer = 0;
        $this->total_line = 0;
        $this->qty = 0;
    }
    public function addLineT()
    {
        $this->validate([
            'inventaryS' => 'required',
            'productS' => 'required',
            'qtyAS' => 'required',
            'qtyNS' => 'required',
            'qtyTransfer' => 'required',
            'productE' => 'required',
            'qtyAE' => 'required',
            'qtyNE' => 'required',
            'cost' => 'required',
            'total_line' => 'required'
        ]);
        $line = [
            "inventaryS" => $this->inventaryS,
            "productS" => $this->productS,
            "name_productS" => Product::find($this->productS)->description,
            "qtyAS" => $this->qtyAS,
            'qtyNS' => $this->qtyNS,
            "qtyTransfer" => $this->qtyTransfer,
            "inventaryE" => $this->inventaryE,
            "productE" => $this->productE,
            "name_productE" => Product::find($this->productE)->description,
            "qtyAE" => $this->qtyAE,
            'qtyNE' => $this->qtyNE,
            "cost" => $this->cost,
            "total_line" => $this->total_line = $this->qtyTransfer * $this->cost
        ];
        array_push($this->allLinesT, $line);
        $this->total += $this->total_line;
        $this->allProductsS = [];
        $this->inventaryS = '';
        $this->allProductsE = [];
        $this->productS = '';
        $this->productE = '';
        $this->inventaryE = '';
        $this->qtyTransfer = 0;
        $this->qtyAE = 0;
        $this->qtyNE = 0;
        $this->qtyNS = 0;
        $this->qtyAS = 0;
        $this->observation = '';
        $this->qtyTransfer = 0;
        $this->updateInventaryS();
        $this->updateInventaryE();
    }
    public function deleteLineT($index)
    {
        $this->total -= $this->allLinesT[$index]['total_line'];
        unset($this->allLinesT[$index]);
        $this->allLinesT = array_values($this->allLinesT);
    }
    public function deleteLine($index)
    {
        $this->total -= $this->allLines[$index]['total_line'];
        unset($this->allLines[$index]);
        $this->allLines = array_values($this->allLines);
    }
    public function editLine($index)
    {
        $this->id_product = $this->allLines[$index]['id_product'];
        $this->name_product = $this->allLines[$index]['name_product'];
        $this->qty_start = $this->allLines[$index]['qty_start'];
        $this->qty = $this->allLines[$index]['qty'];
        $this->qty_end = $this->allLines[$index]['qty_end'];
        $this->cost = $this->allLines[$index]['cost'];
        $this->total_line = $this->allLines[$index]['total_line'];
        $this->deleteLine($index);
    }
    public function editLineT($index)
    {
        $this->inventaryS = $this->allLinesT[$index]['inventaryS'];
        $this->updateInventaryS();
        $this->productS = $this->allLinesT[$index]['productS'];
        $this->updateProductS();
        $this->inventaryE = $this->allLinesT[$index]['inventaryE'];
        $this->updateInventaryE();
        $this->productE = $this->allLinesT[$index]['productE'];
        $this->updateProductE();
        $this->qtyAS = $this->allLinesT[$index]['qtyAS'];
        $this->qtyNS = $this->allLinesT[$index]['qtyNS'];
        $this->qtyTransfer = $this->allLinesT[$index]['qtyTransfer'];

        $this->qtyAE = $this->allLinesT[$index]['qtyAE'];
        $this->qtyNE = $this->allLinesT[$index]['qtyNE'];
        $this->cost = $this->allLinesT[$index]['cost'];
        $this->total_line = $this->allLinesT[$index]['total_line'];


        $this->deleteLineT($index);
    }
    public function cleanInputs()
    {
        //limpia los campos
        $this->name_product = '';
        $this->qty_start = 0;
        $this->qty_end = 0;
        $this->cost = 0;
        $this->qtyTransfer = 0;
        $this->total_line = 0;
        $this->qty = 0;
        $this->allLines = [];
        $this->total = 0;
    }
    public function store()
    {
        $this->validate([
            'consecutive' => 'required',
            'inventary' => 'required',
            'observation' => 'required',
            'allLines' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $this->consecutive = InternalConsecutive::where("id_company", Auth::user()->currentTeam->id)->first()->ai;
            $ia = InventoryAjustment::create([
                'id_company' => Auth::user()->currentTeam->id,
                'consecutive' => $this->consecutive,
                'id_count' => $this->inventary,
                'observation' => $this->observation,
                'total' => $this->total,
                'type' => $this->type,
                'id_terminal' => $this->id_terminal,
                'nameuser' => $this->username
            ]);
            foreach ($this->allLines as $line) {
                if ($line['qty'] != 0) {
                    InventoryAjustmentsDetail::create([
                        'id_ai' => $ia->id,
                        'id_product' => $line['id_product'],
                        'qty_start' => $line['qty_start'],
                        'qty' => $line['qty'],
                        'qty_end' => $line['qty_end'],
                        'cost' => $line['cost'],
                        'total_line' => $line['total_line'],
                    ]);
                    Product::find($line['id_product'])->update([
                        'stock' => $line['qty_end']
                    ]);
                }
            }
            $ic = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first();
            DiaryBook::create([
                'entry' => 'AI-' . $ic->ai,
                'document' => $ia->id,
                'id_count' => $this->inventary,
                'debit' => ($this->total > 0) ? $this->total : 0,
                'credit' => ($this->total < 0) ? $this->total : 0,
                'id_company' => Auth::user()->currentTeam->id,
                'id_bo' => $this->id_bo,
                'terminal' => $this->id_terminal,
                'id_user' => $this->id_user,
                'name_user' => $this->username
            ]);
            DiaryBook::create([
                'entry' => 'AI-' . $ic->ai,
                'document' => $ia->id,
                'id_count' => Count::where("name", 'AJUSTES DE INVENTARIO')->where("id_company", Auth::user()->currentTeam->id)->first()->id,
                'debit' => ($this->total < 0) ? $this->total : 0,
                'credit' => ($this->total > 0) ? $this->total : 0,
                'id_company' => Auth::user()->currentTeam->id,
                'id_bo' => $this->id_bo,
                'terminal' => $this->id_terminal,
                'id_user' => $this->id_user,
                'name_user' => $this->username
            ]);
            $ic->update([
                'ai' => $ic->ai + 1
            ]);

            $this->dispatchBrowserEvent('messageData', ['messageData' => "Ajuste creado con exito"]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->dispatchBrowserEvent('AI_modal_hide', []);
        $this->emit('refreshAITable');
    }
    public function storeT()
    {
        $this->validate([
            'allLinesT' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $this->consecutive = InternalConsecutive::where("id_company", Auth::user()->currentTeam->id)->first()->ai;
            $ia = InventoryAjustment::create([
                'id_company' => Auth::user()->currentTeam->id,
                'consecutive' => $this->consecutive,
                'id_count' =>  null,
                'observation' => $this->observation,
                'total' => $this->total,
                'type' => $this->type,
                'id_terminal' => $this->id_terminal,
                'nameuser' => $this->username
            ]);
            foreach ($this->allLinesT as $key => $line) {            //registra salida

                $pe = Product::find($line['productS']);
                InventoryAjustmentsDetail::create([
                    'id_ai' => $ia->id,
                    'id_product' => $line['productS'],
                    'qty_start' => $line['qtyAS'],
                    'qty' => $line['qtyTransfer'],
                    'qty_end' => $line['qtyNS'],
                    'cost' => $line['cost'],
                    'total_line' => $line['cost'] * $line['qtyTransfer'],
                ]);
                $pe->update([
                    'stock' => $pe->stock - $line['qtyTransfer']
                ]);
                $ic = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first();
                DiaryBook::create([
                    'entry' => 'AI-' . $ic->ai,
                    'document' => $ia->id,
                    'id_count' => $line['inventaryS'],
                    'debit' => $line['cost'] * $line['qtyTransfer'],
                    'credit' => 0,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_bo' => $this->id_bo,
                    'terminal' => $this->id_terminal,
                    'id_user' => $this->id_user,
                    'name_user' => $this->username
                ]);

                //registra entrada
                $p = Product::find($line['productE']);
                InventoryAjustmentsDetail::create([
                    'id_ai' => $ia->id,
                    'id_product' => $line['productE'],
                    'qty_start' => $line['qtyAE'],
                    'qty' => $line['qtyTransfer'],
                    'qty_end' => $line['qtyNE'],
                    'cost' => $line['cost'],
                    'total_line' => $line['cost'] * $line['qtyTransfer'],
                ]);
                $p->update([
                    'stock' => $pe->stock + $line['qtyTransfer']
                ]);
                $ic = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first();
                DiaryBook::create([
                    'entry' => 'AI-' . $ic->ai,
                    'document' => $ia->id,
                    'id_count' => $line['inventaryE'],
                    'debit' => 0,
                    'credit' => $line['cost'] * $line['qtyTransfer'],
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_bo' => $this->id_bo,
                    'terminal' => $this->id_terminal,
                    'id_user' => $this->id_user,
                    'name_user' => $this->username
                ]);

                $ic->update([
                    'ai' => $ic->ai + 1
                ]);
            }
            $this->dispatchBrowserEvent('messageData', ['messageData' => "Ajuste creado con exito"]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();

        $this->dispatchBrowserEvent('transferI_modal_hide', []);
        $this->emit('refreshAITable');
        $this->cleanInputsT();
    }
    public function editT($consecutive)
    {

        $ai = InventoryAjustment::where('consecutive', $consecutive)->first();
        $aids = InventoryAjustmentsDetail::where('id_ai', $ai->id)->get();

        for ($i = 0; $i < count($aids); $i = $i + 2) {
            $ps = Product::find($aids[$i]->id_product);
            $pe = Product::find($aids[$i + 1]->id_product);
            $line = [
                "inventaryS" => $ps->id_count_inventory,
                "productS" => $ps->id,
                "name_productS" => $ps->description,
                "qtyAS" => $aids[$i]->qty_start,
                'qtyNS' => $aids[$i]->qty_end,
                "qtyTransferS" => $aids[$i]->qty,
                "qtyTransferE" => $aids[$i + 1]->qty,
                "inventaryE" => $pe->id_count_inventory,
                "productE" => $pe->id,
                "name_productE" => $pe->description,
                "qtyAE" => $aids[$i + 1]->qty_start,
                'qtyNE' => $aids[$i + 1]->qty_end,
                "cost" => $aids[$i]->cost,
                "total_line" => $aids[$i + 1]->total_line
            ];
            array_push($this->allLinesT, $line);
        }
        $this->type = $ai->type;
        $this->date = $ai->created_at;
        $this->observation = $ai->observation;
        $this->total = $ai->total;
        $this->consecutive = $ai->consecutive;
        $path = 'files/traslados/' . Auth::user()->currentTeam->id_card;
        if (!file_exists($path . '/' . $this->consecutive . '.pdf') || !file_exists($path . '/I' . $this->consecutive . '.pdf')) {
            $this->createPdfT();
            $this->informePdfT();
        }
    }
    public function createPdfT()
    {
        $data = [
            'date' => $this->date,
            'type' => $this->type,
            'title' => (($this->type == 'Traslado') ? 'Traslado #' : 'Transformación #') . $this->consecutive,
            'consecutive' => $this->consecutive,
            'company' => Auth::user()->currentTeam,
            'total' => $this->total,
            'observation' => $this->observation,
            'allLinesT' => $this->allLinesT
        ];
        $pdf = PDF::loadView('livewire.inventory.PDFTraslateInventory', $data);
        $path = 'files/traslados/' . Auth::user()->currentTeam->id_card;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $pdf->save($path . '/' . $this->consecutive . '.pdf');
    }
    public function informePdfT()
    {
        $data = [
            'date' => $this->date,
            'type' => $this->type,
            'title' => (($this->type == 'Traslado') ? 'Traslado #' : 'Transformación #') . $this->consecutive,
            'consecutive' => $this->consecutive,
            'company' => Auth::user()->currentTeam,
            'total' => $this->total,
            'observation' => $this->observation,
            'allLinesT' => $this->allLinesT
        ];
        $pdf = PDF::loadView('livewire.inventory.InformeTraslateInventory', $data);
        $path = 'files/traslados/' . Auth::user()->currentTeam->id_card;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $pdf->save($path . '/I' . $this->consecutive . '.pdf');
    }
}
