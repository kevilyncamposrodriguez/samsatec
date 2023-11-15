<?php

namespace App\Http\Livewire\Document;

use App\Mail\InvoiceMail;
use App\Models\Client;
use App\Models\Document;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SendDocumentComponent extends Component
{
    public $mail_send, $key_send, $cc_mail;
    protected $listeners = ['chargeSendInvoice', 'imprimir_tiquete'];
    public function render()
    {
        return view('livewire.document.send-document-component');
    }
    public function chargeSendInvoice($id_client, $id_doc)
    {
        $this->mail_send = Client::find($id_client)->emails;
        $this->key_send = Document::find($id_doc)->key;
    }
    public function sendInvoice()
    {
        $data = array();
        $data["key"] = $this->key_send;
        $data["xml"] = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $data["key"] . '/' . $data["key"] . '-Firmado.xml';
        $data["xmlR"] = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $data["key"] . '/' . $data["key"] . '-R.xml';
        $data["pdf"] = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $data["key"] . '/' . $data["key"] . '.pdf';
        try {
            if ($this->cc_mail != '') {
                Mail::to($this->mail_send)
                    ->cc($this->cc_mail)
                    ->queue(new InvoiceMail($data));
            } else {
                Mail::to($this->mail_send)->queue(new InvoiceMail($data));
            }
            Document::where('key', '=', $data["key"])->first()->update([
                'state_send' => 'enviado',
            ]);
            $this->dispatchBrowserEvent('sendInvoice_modal_hide', []);
            $this->emit('refreshDocumentTable');
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Documentos enviados con exito', 'refresh' => 0]);
        } catch (Exception $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al enviar los documentos.' . $ex->getMessage()]);
        }
        $this->cleanInputs();
    }
    public function cleanInputs()
    {
        $this->mail_send = '';
        $this->cc_mail = '';
        $this->key_send = '';
    }
}
