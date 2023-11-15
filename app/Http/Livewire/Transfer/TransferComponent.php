<?php

namespace App\Http\Livewire\Transfer;

use App\Models\Count;
use App\Models\InternalConsecutive;
use App\Models\Tranfer;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransferComponent extends Component
{
    public $id_count_c, $id_count_d, $transfer_id, $id_company, $user, $term = 1, $idcard, $date_issue, $detail, $mount = 0, $consecutive, $reference, $updateMode = false;
    public $allTransfers = [], $allCounts = [];
    protected $listeners = ['editTransfer' => 'edit', 'deleteTransfer' => 'delete', 'changeCountD', 'changeCountC'];
    public function render()
    {
        return view('livewire.transfer.transfer-component');
    }

    public function mount()
    {
        $this->id_count_d = Count::whereIn("id_count_primary", [34,35])->where("counts.id_company", Auth::user()->currentTeam->id)->first()->id;
        $this->id_count_c = Count::whereIn("id_count_primary", [34,35])->where("counts.id_company", Auth::user()->currentTeam->id)->first()->id;
        $this->allCounts =Count::whereIn("id_count_primary", [34,35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
    }
    public function changeCountC($id)
    {
        $this->id_count_c = $id;
    }
    public function changeCountD($id)
    {
        $this->id_count_d = $id;
    }
    public function resetInputFields()
    {
        $this->id_count_d = Count::where('counts.name', 'EFECTIVO Y EQUIVALENTES')
            ->where('counts.id_company', Auth::user()->currentTeam->id)->first()->id;
        $this->id_count_c = Count::where('counts.name', 'EFECTIVO Y EQUIVALENTES')
            ->where('counts.id_company', Auth::user()->currentTeam->id)->first()->id;
        $this->user = $this->user = Auth::user()->name;
        $this->date_issue = '';
        $this->detail = '';
        $this->mount = 0;
        $this->getConsecutive();
        $this->reference = '';
    }
    public function store()
    {
        $this->validate([
            'id_count_c' => 'required',
            'id_count_d' => 'required',
            'user' => 'required',
            'date_issue' => 'required',
            'detail' => 'required',
            'mount' => 'required',
            'consecutive' => 'required',
            'reference' => 'required',

        ]);
        DB::beginTransaction();
        try {
            Transfer::create([
                'id_count_c' => $this->id_count_c,
                'id_count_d' => $this->id_count_d,
                'id_company' => Auth::user()->currentTeam->id,
                'user' => $this->user,
                'date_issue' => $this->date_issue,
                'detail' => $this->detail,
                'mount' => $this->mount,
                'consecutive' => $this->consecutive,
                'reference' => $this->reference,
            ]);
            $this->nextConsecutive();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Transferencia realizada con exito', 'refresh' => 1]);
            $this->dispatchBrowserEvent('transfer_modal_hide', []);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshTransferTable');
    }
    private function getConsecutive()
    {
        $this->consecutive = str_pad(InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first()->c_t, 10, "0", STR_PAD_LEFT);
    }
    private function nextConsecutive()
    {
        $c = InternalConsecutive::where('id_company', Auth::user()->currentTeam->id)->first();
        $c->update([
            'c_t' => $c->c_t + 1,
        ]);
    }
    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $this->transfer_id = $id;
            $result = Transfer::where('id', $id)->first();
            $this->id_count_c = $result->id_count_c;
            $this->dispatchBrowserEvent('countC-updated', ['newValue' => $this->id_count_c]);
            $this->id_count_d = $result->id_count_d;
            $this->dispatchBrowserEvent('countD-updated', ['newValue' => $this->id_count_d]);
            $this->user = $result->user;
            $this->date_issue =  date("Y-m-d", strtotime($result->date_issue));
            $this->detail = $result->detail;
            $this->mount = $result->mount;
            $this->consecutive = $result->consecutive;
            $this->reference = $result->reference;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('transferU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('transferU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }
    public function update()
    {
        $this->validate([
            'id_count_c' => 'required',
            'id_count_d' => 'required',
            'user' => 'required',
            'date_issue' => 'required',
            'detail' => 'required',
            'mount' => 'required',
            'consecutive' => 'required',
            'reference' => 'required',
        ]);
        DB::beginTransaction();
        try {
            if ($this->transfer_id) {
                $pay = Transfer::find($this->transfer_id);
                $pay->update([
                    'id_count_c' => $this->id_count_c,
                    'id_count_d' => $this->id_count_d,
                    'id_company' => Auth::user()->currentTeam->id,
                    'user' => $this->user,
                    'date_issue' => $this->date_issue,
                    'detail' => $this->detail,
                    'mount' => $this->mount,
                    'consecutive' => $this->consecutive,
                    'reference' => $this->reference,
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Transferencia actualizada con exito', 'refresh' => 1]);
                $this->dispatchBrowserEvent('transferU_modal_hide', []);
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
        $this->emit('refreshTransferTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                Transfer::where('id', $id)->delete();
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Transferencia eliminada con exito', 'refresh' => 1]);
                $this->emit('refreshTransferTable');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
