<?php

namespace App\Http\Livewire\Cellar;

use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentDetail;
use Livewire\Component;

class CellarProcessedComponent extends Component
{
    public $delivery_date, $client, $date_issue, $priority, $type_document, $id_branch_office, $allLines = [], $id_document;
    protected $listeners = ['viewCellar', 're_processed'];
    public function render()
    {
        return view('livewire.cellar.cellar-processed-component');
    }
    public function viewCellar($id)
    {
        $doc = Document::find($id);
        $this->delivery_date = $doc->delivery_date;
        $this->date_issue = $doc->date_issue;
        $this->priority = $doc->priority;
        $this->client = Client::find($doc->id_client)->name_client;
        $this->id_document = $id;
        $this->allLines = [];
        $this->allLines = DocumentDetail::where('id_document', $id)->get();
    }
    public function re_processed()
    {

        $doc = Document::find($this->id_document);
        $doc->update([
            'state_proccess' => 0
        ]);
        $this->emit('refreshProcessedTable');
        $this->dispatchBrowserEvent('cellarP_modal_hide');
    }
}
