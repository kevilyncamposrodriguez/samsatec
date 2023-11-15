<?php

namespace App\Http\Livewire\Terminal;

use App\Models\BranchOffice;
use App\Models\Consecutive;
use Livewire\Component;
use App\Models\Terminal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TerminalsComponent extends Component
{

    public $allTerminals, $allBO, $id_branch_office = 0, $number = 0;
    //datos para manejo de consecutivos
    public $c_fe, $c_nc, $c_nd, $c_fc, $c_fex, $c_te, $c_mra, $c_mrr, $id_consecutive;
    protected $listeners = ['refreshTerminal' => '$refresh'];
    public function render()
    {
        if ($this->id_branch_office) {
            $this->number = Terminal::where("terminals.id_company", "=", Auth::user()->currentTeam->id)
                ->where("terminals.active", '1')
                ->where("terminals.id_branch_office", "=", $this->id_branch_office)->count() + 1;
        }
        $this->allTerminals = Terminal::where("terminals.id_company", "=", Auth::user()->currentTeam->id)
            ->where("terminals.active", '1')
            ->leftJoin('branch_offices', 'branch_offices.id', '=', 'terminals.id_branch_office')
            ->select('terminals.*', 'branch_offices.name_branch_office')->get();
        $this->allBO = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->get();
        return view('livewire.terminal.terminals-component');
    }

    private function resetInputFields()
    {
        $this->number = '0';
        $this->id_branch_office = '0';
    }

    public function store()
    {
        $this->validate([
            'number' => 'required',
            'id_branch_office' => 'required|exists:branch_offices,id'
        ]);

        Terminal::create([
            'number' => $this->number,
            'id_company' => Auth::user()->currentTeam->id,
            'id_branch_office' => $this->id_branch_office
        ]);

        $this->resetInputFields();
        $this->dispatchBrowserEvent('terminal_modal_hide', []);
        $this->dispatchBrowserEvent('messageData', ['messageData' => 'Terminal creada con exito']);
    }
    public function editConsecutives($id)
    {
        $this->updateMode = true;
        $result = Consecutive::where('consecutives.id_terminal', $id)->first();
        $this->id_consecutive = $result->id;
        $this->c_fe = $result->c_fe;
        $this->c_nc = $result->c_nc;
        $this->c_nd = $result->c_nd;
        $this->c_fc = $result->c_fc;
        $this->c_fex = $result->c_fex;
        $this->c_te = $result->c_te;
        $this->c_mra = $result->c_mra;
        $this->c_mrr = $result->c_mrr;
    }

    public function storeConsecutives()
    {
        $this->validate([
            'c_fe' => 'required|numeric',
            'c_nc' => 'required|numeric',
            'c_nd' => 'required|numeric',
            'c_fc' => 'required|numeric',
            'c_fex' => 'required|numeric',
            'c_te' => 'required|numeric',
            'c_mra' => 'required|numeric',
            'c_mrr' => 'required|numeric'
        ]);
        DB::beginTransaction();
        try {
            if ($this->id_consecutive) {
                $result = Consecutive::find($this->id_consecutive);

                $result->update([
                    'c_fe' => $this->c_fe,
                    'c_nc' => $this->c_nc,
                    'c_nd' => $this->c_nd,
                    'c_fc' => $this->c_fc,
                    'c_fex' => $this->c_fex,
                    'c_te' => $this->c_te,
                    'c_mra' => $this->c_mra,
                    'c_mrr' => $this->c_mrr
                ]);
                $this->updateMode = false;
                $this->resetInputFieldsConsecutives();
                $this->dispatchBrowserEvent('consecutives_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Consecutivos actualizados con exito']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'Error al ingrasar la informacion']);
            DB::rollback();
        }
        DB::commit();
    }

    private function resetInputFieldsConsecutives()
    {
        $this->c_fe = '';
        $this->c_nc = '';
        $this->c_nd = '';
        $this->c_fc = '';
        $this->c_fex = '';
        $this->c_te = '';
        $this->c_mra = '';
        $this->c_mrr = '';
    }
    public function delete($id)
    {
        try {
            if ($id) {
                Terminal::where('id', $id)->update([
                    'active' => 0
                ]);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Terminal eliminada con exito']);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
}
