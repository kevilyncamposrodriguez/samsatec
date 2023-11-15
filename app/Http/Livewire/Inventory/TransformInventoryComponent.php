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

class TransformInventoryComponent extends Component
{
    public $allInventaries, $allBranchOffices, $allProductsS, $allProductsE, $allTerminals;
    public $id_user, $id_bo, $consecutive, $type, $inventaryE, $inventaryS, $productE, $productS, $username, $qtyAS, $qtyNS, $qtyAE, $qtyNE, $observation, $qtyTransferE, $qtyTransferS, $tu, $id_terminal, $name_productS, $name_productE;
    protected $listeners = ['newTransform', 'editTransform'];
    public function mount()
    {
        $this->cleanInputs();
        $this->id_user = Auth::user()->id;
        $this->username = Auth::user()->name;
        $this->allInventaries = Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allBranchOffices = BranchOffice::where("id_company", Auth::user()->currentTeam->id)->get();
        $this->tu = TeamUser::where('team_id', Auth::user()->currentTeam->id)->where('user_id', $this->id_user)->first();
        $this->id_bo = (isset($this->tu->bo) && $this->tu->bo) ? $this->tu->bo : $this->allBranchOffices[0]->id;
        $this->allTerminals = Terminal::where("id_company", Auth::user()->currentTeam->id)->where("id_branch_office", $this->id_bo)->get();
        $this->id_terminal = (isset($this->tu->terminal) && $this->tu->terminal) ? $this->tu->terminal : $this->allTerminals[0]->id;
    }
    public function render()
    {
        return view('livewire.inventory.transform-inventory-component');
    }
    public function editTransform($consecutive)
    {
        $ais = InventoryAjustment::where('consecutive', $consecutive)->get();
        $bandera = 1;
        foreach ($ais as $key => $ai) {
            $aid = InventoryAjustmentsDetail::where('id_ai', $ai->id)->first();
            if ($bandera) {
                $this->inventaryS = $ai->id_count;
                $this->productS = $aid->id_product;
                $this->qtyAS = $aid->qty_start;
                $this->qtyNS = $aid->qty_end;
                $this->qtyTransferS = $aid->qty;
                $bandera = 0;
            } else {
                $this->inventaryE = $ai->id_count;
                $this->productE = $aid->id_product;
                $this->qtyAE = $aid->qty_start;
                $this->qtyNE = $aid->qty_end;
                $this->qtyTransferE = $aid->qty;
            }
        }

        $this->date = $ai->created_at;
        $this->observation = $ai->observation;
        $this->total = $ai->total;
        $this->consecutive = $ai->consecutive;
    }
    public function newTransform()
    {
        $this->cleanInputs();
        $this->consecutive = InternalConsecutive::where("id_company", Auth::user()->currentTeam->id)->first()->ai;
        $this->type = 'Transformacion';
    }
    public function cleanInputs()
    {
        $this->allBranchOffices = [];
        $this->allInventaries = [];
        $this->allProductsE = [];
        $this->allProductsS = [];
        $this->name_productE = '';
        $this->name_productS = '';
        $this->productE = '';
        $this->productS = '';
        $this->inventaryE = '';
        $this->inventaryS = '';
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
                $this->name_productE = $p->description;
                $this->qtyAE = $p->stock;
                $this->qtyNE = ($this->qtyTransferE != '') ? $this->qtyAE + $this->qtyTransferE : $this->qtyAE;
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
        $this->productS = '';
        $this->name_productS = '';
    }
    public function updateProductS()
    {
        $product = Product::find($this->productS);
        if ($product) {
            $this->qtyAS = $product->stock;
            $this->qtyNS = ($this->qtyTransferS != '') ? $this->qtyAS - $this->qtyTransferS : $this->qtyAS;
        } else {
            $this->qtyAS = 0;
            $this->qtyNS = 0;
        }
    }
    public function updateProductE()
    {
        $product = Product::find($this->productE);
        if ($product) {
            $this->qtyAE = $product->stock;
            $this->qtyNE = ($this->qtyTransferE != '') ? $this->qtyAE + $this->qtyTransferE : $this->qtyAE;
        } else {
            $this->qtyAE = 0;
            $this->qtyNE = 0;
        }
    }
    public function updateQtyTransferS()
    {
        $this->qtyTransferS = ($this->qtyTransferS > $this->qtyAS) ? $this->qtyAS : $this->qtyTransferS;
        $this->qtyNS = ($this->qtyTransferS != '') ? $this->qtyAS - $this->qtyTransferS : $this->qtyAS;
    }
    public function updateQtyTransferE()
    {
        $this->qtyNE = ($this->qtyTransferE != '') ? $this->qtyAE + $this->qtyTransferE : $this->qtyAE;
    }
    public function storeTranform()
    {
        $this->validate([
            'productE' => 'required',
            'productS' => 'required',
            'qtyTransferS' => 'required|numeric|min:0|',
            'qtyTransferE' => 'required|numeric|min:0|',
            'observation' => 'required'
        ]);

        DB::beginTransaction();
        try {
            //registra salida
            $this->consecutive = InternalConsecutive::where("id_company", Auth::user()->currentTeam->id)->first()->ai;
            $pe = Product::find($this->productS);
            $ia = InventoryAjustment::create([
                'id_company' => Auth::user()->currentTeam->id,
                'consecutive' => $this->consecutive,
                'observation' => $this->observation,
                'total' => $pe->cost_unid * $this->qtyTransferS * -1,
                'type' => $this->type,
                'id_terminal' => $this->id_terminal,
                'nameuser' => $this->username
            ]);
            InventoryAjustmentsDetail::create([
                'id_ai' => $ia->id,
                'id_product' => $this->productS,
                'qty_start' => $this->qtyAS,
                'qty' => $this->qtyTransferS,
                'qty_end' => $this->qtyNS,
                'cost' => $pe->cost_unid,
                'total_line' => $pe->cost_unid * $this->qtyTransferS,
            ]);
            $pe->update([
                'stock' => $this->qtyNS
            ]);
            $ic = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first();
            DiaryBook::create([
                'entry' => 'AI-' . $ic->aqtyTransferi,
                'document' => $ia->id,
                'id_count' => $this->inventaryS,
                'debit' => $pe->cost_unid * $this->qtyTransferS,
                'credit' => 0,
                'id_company' => Auth::user()->currentTeam->id,
                'id_bo' => $this->id_bo,
                'terminal' => $this->id_terminal,
                'id_user' => $this->id_user,
                'name_user' => $this->username
            ]);

            //registra entrada
            $p = Product::find($this->productE);
            InventoryAjustmentsDetail::create([
                'id_ai' => $ia->id,
                'id_product' => $this->productE,
                'qty_start' => $this->qtyAE,
                'qty' => $this->qtyTransferE,
                'qty_end' => $this->qtyNE,
                'cost' => $pe->cost_unid,
                'total_line' => $pe->cost_unid * $this->qtyTransferE,
            ]);
            $p->update([
                'stock' => $this->qtyNE
            ]);
            DiaryBook::create([
                'entry' => 'AI-' . $ic->ai,
                'document' => $ia->id,
                'id_count' => $this->inventaryE,
                'debit' => 0,
                'credit' => $pe->cost_unid * $this->qtyTransferE,
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
        $this->dispatchBrowserEvent('transferI_modal_hide', []);
        $this->emit('refreshAITable');
        $this->cleanInputs();
    }
}
