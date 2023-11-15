<?php

namespace App\Http\Livewire\Cellar;

use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CellarUnprocessedComponent extends Component
{
    public $notes, $delivery_date, $client, $date_issue, $priority, $type_document, $id_branch_office, $allLines = [], $id_document, $qtys;
    protected $listeners = ['editCellar', 'processedCellar'];
    public function render()
    {
        return view('livewire.cellar.cellar-unprocessed-component');
    }

    public function editCellar($id)
    {
        $doc = Document::find($id);
        $this->delivery_date = $doc->delivery_date;
        $this->date_issue = $doc->date_issue;
        $this->priority = $doc->priority;
        $this->client = Client::find($doc->id_client)->name_client;
        $this->id_document = $id;
        $this->allLines = [];
        $this->allLines = DocumentDetail::where('id_document', $id)->get();
        foreach ($this->allLines as $key => $line) {
            $this->qtys[$key] = $line->qty_dispatch;
            $this->notes[$key] = $line->note;
        }
    }
    public function processed()
    {

        $this->validate([
            'qtys.*' => 'required'
        ]);
        DB::beginTransaction();
        try {
            foreach ($this->allLines as $key => $line) {
                $dl = DocumentDetail::find($line->id);
                $dl->update([
                    'qty_dispatch' => $this->qtys[$key],
                    'note' => $this->notes[$key]
                ]);
            }
            $doc = Document::find($this->id_document);
            $doc->update([
                'state_proccess' => 2
            ]);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Procesado con exito', 'refresh' => 0]);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshUnprocessedTable');
        $this->dispatchBrowserEvent('cellar_modal_hide');
    }
}
