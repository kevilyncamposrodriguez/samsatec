<?php

namespace App\Http\Livewire\Paybill;

use App\Models\Count;
use App\Models\InternalConsecutive;
use App\Models\Paybill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaybillComponent extends Component
{
    public $id_count, $paybill_id, $id_company, $name, $term = 1, $idcard, $date_issue, $detail, $mount = 0, $consecutive, $reference, $updateMode = false;
    public $allPaybills = [], $allCounts = [];
    protected $listeners = ['editPayBill' => 'edit', 'deletePayBill' => 'delete', 'changeCount'];
    public function mount()
    {
        $this->id_count = Count::whereIn("id_count_primary", [34,35])->where("counts.id_company", Auth::user()->currentTeam->id)->first()->id;
        $this->allCounts = Count::whereIn("id_count_primary", [34,35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
    }
    public function render()
    {
        return view('livewire.paybill.paybill-component');
    }
    public function changeCount($id)
    {
        $this->id_count = $id;
    }
    public function resetInputFields()
    {
        $this->id_count = Count::whereIn("id_count_primary", [34,35])->where("counts.id_company", Auth::user()->currentTeam->id)->first()->id;
        $this->name = '';
        $this->idcard = '';
        $this->date_issue = '';
        $this->detail = '';
        $this->mount = 0;
        $this->getConsecutive();
        $this->reference = '';
    }
    public function store()
    {
        $this->validate([
            'id_count' => 'required',
            'name' => 'required',
            'idcard' => 'required',
            'date_issue' => 'required',
            'detail' => 'required',
            'mount' => 'required',
            'term' => 'required',
            'consecutive' => 'required',
            'reference' => 'required',

        ]);
        DB::beginTransaction();
        try {
            Paybill::create([
                'id_count' => $this->id_count,
                'id_company' => Auth::user()->currentTeam->id,
                'name' => $this->name,
                'idcard' => $this->idcard,
                'date_issue' => $this->date_issue,
                'detail' => $this->detail,
                'mount' => $this->mount,
                'term' => $this->term,
                'consecutive' => $this->consecutive,
                'reference' => $this->reference,
            ]);
            $this->nextConsecutive();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Vale realizado con exito', 'refresh' => 1]);
            $this->dispatchBrowserEvent('payBill_modal_hide', []);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshPayBillTable');
    }
    private function getConsecutive()
    {
        $this->consecutive = str_pad(InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first()->c_v, 10, "0", STR_PAD_LEFT);
    }
    private function nextConsecutive()
    {
        $c = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first();
        $c->update([
            'c_v' => $c->c_v + 1,
        ]);
    }
    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $this->paybill_id = $id;
            $result = Paybill::where('id', $id)->first();
            $this->id_count = $result->id_count;
            $this->dispatchBrowserEvent('count-updated', ['newValue' => $this->id_count]);
            $this->name = $result->name;
            $this->idcard = $result->idcard;
            $this->date_issue =  date("Y-m-d", strtotime($result->date_issue));
            $this->detail = $result->detail;
            $this->mount = $result->mount;
            $this->consecutive = $result->consecutive;
            $this->reference = $result->reference;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('paybillU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('paymentU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }
    public function update()
    {
        $this->validate([
            'id_count' => 'required',
            'name' => 'required',
            'idcard' => 'required',
            'date_issue' => 'required',
            'detail' => 'required',
            'mount' => 'required',
            'consecutive' => 'required',
            'reference' => 'required',

        ]);
        DB::beginTransaction();
        try {
            if ($this->paybill_id) {
                $pay = Paybill::find($this->paybill_id);
                $pay->update([
                    'id_count' => $this->id_count,
                    'id_company' => Auth::user()->currentTeam->id,
                    'name' => $this->name,
                    'idcard' => $this->idcard,
                    'date_issue' => $this->date_issue,
                    'detail' => $this->detail,
                    'mount' => $this->mount,
                    'term' => $this->term,
                    'consecutive' => $this->consecutive,
                    'reference' => $this->reference,
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Vale actualizado con exito', 'refresh' => 1]);
                $this->dispatchBrowserEvent('payBillU_modal_hide', []);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion ' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshPayBillTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Paybill::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Vale eliminado con exito', 'refresh' => 1]);
            }
            $this->emit('refreshPayBillTable');
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
