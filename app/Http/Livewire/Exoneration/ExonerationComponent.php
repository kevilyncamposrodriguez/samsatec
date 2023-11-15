<?php

namespace App\Http\Livewire\Exoneration;

use Livewire\Component;
use App\Models\Type_document_exoneration;
use Illuminate\Support\Facades\Redirect;
use App\Models\Exoneration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExonerationComponent extends Component
{

    public $typeDocument, $allExonerations, $exoneration_id, $id_type_document_exoneration, $description, $document_number, $updateMode = false, $allTypeDocuments, $institutional_name, $date, $exemption_percentage;
    public $message, $error;
    protected $listeners = ['editExoneration' => 'edit', 'deleteExoneration' => 'delete'];
    public function render()
    {
        $this->allTypeDocuments = type_document_exoneration::all();
        $this->allExonerations = Exoneration::where("exonerations.id_company", "=", Auth::user()->currentTeam->id)
        ->where("exonerations.active",'1')
        ->join('type_document_exonerations', 'type_document_exonerations.id', '=', 'exonerations.id_type_document_exoneration')
            ->select('exonerations.*', 'type_document_exonerations.document as typeDocument')->get();
        return view('livewire.exoneration.exoneration-component');
    }

    private function resetInputFields()
    {
        $this->id_type_document_exoneration = 0;
        $this->description = '';
        $this->document_number = '';
        $this->institutional_name = '';
        $this->date = '';
        $this->exemption_percentage = 0;
    }

    public function store()
    {
        $this->validate([
            'id_type_document_exoneration' => 'required|exists:type_document_exonerations,id',
            'description' => 'required|min:0|max:200',
            'document_number' => 'required',
            'institutional_name' => 'required',
            'date' => 'required',
            'exemption_percentage' => 'required|numeric|min:0|max:100'
        ]);
        DB::beginTransaction();
        try {
            Exoneration::create([
                'id_type_document_exoneration' => $this->id_type_document_exoneration,
                'id_company' => Auth::user()->currentTeam->id,
                'description' => $this->description,
                'document_number' => $this->document_number,
                'institutional_name' => $this->institutional_name,
                'date' => $this->date,
                'exemption_percentage' => $this->exemption_percentage
            ]);
            $this->resetInputFields();
            $this->dispatchBrowserEvent('exoneration_modal_hide', []);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Exoneracion creada con exito']);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshExonerationsTable');
    }

    public function edit($id)
    {
        try {
            $this->updateMode = true;
            $result = \App\Models\Exoneration::where('id', $id)->first();
            $this->exoneration_id = $id;
            $this->id_type_document_exoneration = $result->id_type_document_exoneration;
            $this->description = $result->description;
            $this->document_number = $result->document_number;
            $this->institutional_name = $result->institutional_name;
            $this->date = str_replace(" ", "T", $result->date);
            $this->exemption_percentage = $result->exemption_percentage;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('discountU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update()
    {
        $this->validate([
            'id_type_document_exoneration' => 'required|exists:type_document_exonerations,id',
            'description' => 'required',
            'document_number' => 'required',
            'institutional_name' => 'required',
            'date' => 'required',
            'exemption_percentage' => 'required|numeric|min:0|max:100'
        ]);
        DB::beginTransaction();
        try {
            if ($this->exoneration_id) {
                $result = Exoneration::find($this->exoneration_id);
                $result->update([
                    'id_type_document_exoneration' => $this->id_type_document_exoneration,
                    'id_company' => Auth::user()->currentTeam->id,
                    'description' => $this->description,
                    'document_number' => $this->document_number,
                    'institutional_name' => $this->institutional_name,
                    'date' => $this->date,
                    'exemption_percentage' => $this->exemption_percentage
                ]);
                $this->updateMode = false;
                $this->resetInputFields();
                $this->dispatchBrowserEvent('exonerationU_modal_hide', []);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Exoneracion actualizada con exito']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion']);
        }
        DB::commit();
        $this->emit('refreshExonerationsTable');
    }

    public function delete($id)
    {
        try {
            if ($id) {
                $result = Exoneration::find($id);
                $result->update([
                    'active' => '0'
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
        $this->emit('refreshExonerationsTable');
    }
}
