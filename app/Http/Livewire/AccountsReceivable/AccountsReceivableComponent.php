<?php

namespace App\Http\Livewire\AccountsReceivable;

use App\Mail\CXCMail;
use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\Count;
use App\Models\CXC;
use App\Models\Document;
use App\Models\PaymentInvoice;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use PDF;

class AccountsReceivableComponent extends Component
{
    public $total = 0, $rango0 = 0, $rango0a15 = 0, $rango15a30 = 0, $rango30a60 = 0, $rango60a90 = 0, $rangoMas90 = 0,
        $notes, $client = "", $id_document = "", $allCounts = [], $cxcs = [], $isUpdatePay = false, $id_client = '', $selects = [], $facts = [], $pay, $allAcounts = [], $totalPay = 0, $id_count = '', $mountPay = 0,  $allPayments = [],
        $reference = '', $date = '', $observations = '', $title, $company, $logo_url, $bo, $accounts;
    public $mail_send, $cc_mail, $email_client, $phone_client, $pdfReport;
    protected $listeners = ['changeSelected', 'setClient', 'quitClient', 'genPay'];
    public function mount($id_client = null)
    {
        $this->company = Auth::user()->currentTeam;
        $this->bo = BranchOffice::where('branch_offices.id_company', Auth::user()->currentTeam->id)->first();
        $this->date = date("Y-m-d");
        $this->id_client = $id_client;
        if ($id_client) {
            $client = Client::find($this->id_client);
            $this->client = $client->name_client;
            $this->mail_send = $client->emails;
            $this->client = $client->name_client;
        }
        $this->allAcounts = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
        $this->allCounts = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
    }
    public function render()
    {
        if ($this->id_document != '') {
            $this->allPayments = PaymentInvoice::where("payment_invoices.id_document", $this->id_document)
                ->join('counts', 'payment_invoices.id_count', '=', 'counts.id')
                ->join('documents', 'payment_invoices.id_document', '=', 'documents.id')
                ->select('payment_invoices.*', 'documents.consecutive', 'counts.name as count')->get();
        }
        $this->facts = [];
        $this->totalPay = 0;
        foreach ($this->selects as $index => $value) {
            $this->totalPay += ((isset($this->pay[$index]) && is_numeric($this->pay[$index])) ? $this->pay[$index] : 0);
            $d = Document::find($value);
            array_push($this->facts, $d);
        }
        if ($this->id_client) {
            $this->rango0 = collect(CXC::all())
                ->where('dias_de_atraso', 'Al Dia')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('cliente', $this->client)->sum('saldo_pendiente');
            $this->rango0a15 = collect(CXC::all())
                ->where('dias_de_atraso', '0 a 15 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('cliente', $this->client)->sum('saldo_pendiente');
            $this->rango15a30 = collect(CXC::all())
                ->where('dias_de_atraso', '15 a 30 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('cliente', $this->client)->sum('saldo_pendiente');
            $this->rango30a60 = collect(CXC::all())
                ->where('dias_de_atraso', '30 a 60 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('cliente', $this->client)->sum('saldo_pendiente');
            $this->rango60a90 = collect(CXC::all())
                ->where('dias_de_atraso', '60 a 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('cliente', $this->client)->sum('saldo_pendiente');
            $this->rangoMas90 = collect(CXC::all())->where('dias_de_atraso', 'Mas de 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->where('cliente', $this->client)->sum('saldo_pendiente');
            $this->total = $this->rango0 + $this->rango0a15 + $this->rango15a30 + $this->rango30a60 + $this->rango60a90 + $this->rangoMas90;
        } else {
            $this->rango0 = collect(CXC::all())
                ->where('dias_de_atraso', 'Al Dia')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango0a15 = collect(CXC::all())
                ->where('dias_de_atraso', '0 a 15 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango15a30 = collect(CXC::all())
                ->where('dias_de_atraso', '15 a 30 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango30a60 = collect(CXC::all())
                ->where('dias_de_atraso', '30 a 60 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rango60a90 = collect(CXC::all())
                ->where('dias_de_atraso', '60 a 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->rangoMas90 = collect(CXC::all())
                ->where('dias_de_atraso', 'Mas de 90 dias de atraso')
                ->where('company', Auth::user()->currentTeam->id)
                ->sum('saldo_pendiente');
            $this->total = $this->rango0 + $this->rango0a15 + $this->rango15a30 + $this->rango30a60 + $this->rango60a90 + $this->rangoMas90;
        }
        return view(
            'livewire.accounts-receivable.accounts-receivable-component',
            ["clientfind" => ($this->id_client) ? Client::where('clients.id', $this->id_client)
                ->join('provinces', 'provinces.id', '=', 'clients.id_province')
                ->join('cantons', 'cantons.id', '=', 'clients.id_canton')
                ->join('districts', 'districts.id', '=', 'clients.id_district')
                ->join('country_codes', 'country_codes.id', '=', 'clients.id_country_code')
                ->join('sale_conditions', 'sale_conditions.id', '=', 'clients.id_sale_condition')
                ->join('payment_methods', 'payment_methods.id', '=', 'clients.id_payment_method')
                ->join('type_id_cards', 'type_id_cards.id', '=', 'clients.type_id_card')->first() : null]
        );
    }
    public function proccess()
    {
        $this->pay = [];
        foreach ($this->selects as $index => $value) {
            $d = Document::find($value);
            $this->pay[$index] = $d->balance;
        }
    }
    public function saveNotes()
    {
        Client::find($this->id_client)->update([
            "notes" => $this->notes
        ]);
    }
    public function chargeNotes()
    {
        $this->notes =  "";
        $this->notes =  Client::find($this->id_client)->notes;
    }
    public function downloadReportCXC($client)
    {
        $cxcs = CXC::where("Company", Auth::user()->currentTeam->id)
            ->where("cliente", $client)->get();
        $data = [
            'title' => "REPORTE DE FACTURAS PENDIENTES DE PAGO",
            'company' => Auth::user()->currentTeam,
            'bo' => BranchOffice::where('branch_offices.id_company', Auth::user()->currentTeam->id)->first(),
            'date' => date("d-m-Y | h:i:sa"),
            'client' => $client,
            'cxcs' => $cxcs,
            'logo_url' => (Auth::user()->currentTeam->logo_url != '') ? Auth::user()->currentTeam->logo_url : ''
        ];
        $pdf = PDF::loadView('livewire.accounts-receivable.reportCXCPDF', $data);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Reporte.pdf');
    }
    public function preSendReport()
    {
        $this->title = "REPORTE DE FACTURAS PENDIENTES DE PAGO";
        $this->logo_url = asset(Auth::user()->currentTeam->logo_url);
        if ($this->id_client) {
            $client = Client::find($this->id_client);
            $this->mail_send = $client->emails;
            $this->client = $client->name_client;
            $this->accounts =  Auth::user()->currentTeam->accounts;
            $this->email_client = $client->name_client;
            $this->phone_client = $client->name_client;
            $this->cxcs = CXC::where("Company", Auth::user()->currentTeam->id)
                ->where("cliente", $this->client)->get();
        }
    }
    public function getAccounts($id)
    {
        $counts = Count::where("counts.id_count", $id)->get();
        if (count($counts) > 0) {
            foreach ($counts as $key => $value) {
                array_push($this->allAcounts, $this->getAccounts($value->id));
            }
        } else {
            return Count::find($id);
        }
    }
    public function sendReportCXC()
    {
        $data = array();
        $data["company"] = Auth::user()->currentTeam;
        $data["accounts"] = $this->accounts;
        $data["client"] = $this->client;
        $data["cxcs"] = CXC::where("Company", Auth::user()->currentTeam->id)
            ->where("cliente", $this->client)->get();
        try {
            if ($this->cc_mail != '') {
                Mail::to($this->mail_send)
                    ->cc($this->cc_mail)
                    ->queue(new CXCMail($data));
            } else {
                Mail::to($this->mail_send)->queue(new CXCMail($data));
            }
            $this->dispatchBrowserEvent('sendReport_modal_hide', []);
            $this->emit('refreshCXCTable');
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Reporte enviado con exito', 'refresh' => 0]);
        } catch (Exception $ex) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al enviar el reporte.' . $ex->getMessage()]);
        }
        $this->cleanInputs();
    }
    public function cleanInputs()
    {
    }
    public function changeSelected($value)
    {
        $this->selects = $value;
    }
    public function storePays()
    {
        $this->validate([
            'date' => 'required',
            'id_count' => 'required',
            'reference' => 'required'
        ]);
        DB::beginTransaction();
        try {
            foreach ($this->selects as $index => $value) {
                if (isset($this->pay[$index])) {
                    $doc = Document::find($value);
                    PaymentInvoice::create([
                        'date' => $this->date,
                        'id_company' => Auth::user()->currentTeam->id,
                        'id_document' => $doc->id,
                        'id_count' => $this->id_count,
                        'reference' => $this->reference,
                        'mount' => $this->pay[$index],
                        'observations' => "Ninguna"
                    ]);
                    $doc->update([
                        'balance' => $doc->balance - $this->pay[$index]
                    ]);
                }
            }
            $this->pay = '';
            $this->reference = '';
            $this->id_count =  $this->allAcounts[0]['id'];
            $this->date = date("Y-m-d");
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pagos realizados con exito']);
            $this->dispatchBrowserEvent('pays_modal_hide', []);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
        }
        DB::commit();
        $this->facts = [];
        $this->emit('refreshCXCClientTable');
        $this->emit('clearSelectsCXCClientTable');
    }
    public function genPay($id)
    {
        $this->isUpdatePay = false;
        $this->id_document = $id;
        $doc = Document::where('documents.id', $this->id_document)->join('clients', 'clients.id', '=', 'documents.id_client')
            ->select('documents.*', 'clients.name_client as name_client')->first();
        $this->keyDoc = $doc->consecutive;
        $this->mountPay = $doc->balance;
        $this->client = $doc->name_client;
    }
    public function storePay()
    {
        $this->validate([
            'date' => 'required',
            'id_document' => 'required',
            'id_count' => 'required',
            'reference' => 'required',
            'mountPay' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $result = Document::find($this->id_document);
            if ($this->mountPay <= $result->balance) {
                PaymentInvoice::create([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_document' => $this->id_document,
                    'id_count' => $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->mountPay,
                    'observations' => $this->observations
                ]);

                $result->update([
                    'balance' => $result->balance - $this->mountPay
                ]);
                $this->date =  '';
                $this->reference = '';
                $this->observations = '';
                $this->genPay($this->id_document);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pagos realizados con exito']);
                $this->dispatchBrowserEvent('pays_modal_hide', []);
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'No se puede agregar un pago mayor al monto pendiente, corrija el monto e intente de nuevo.']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshCXCClientTable');
    }
    public function updatePay()
    {
        $this->validate([
            'date' => 'required',
            'id_document' => 'required',
            'id_count' => 'required',
            'reference' => 'required',
            'mountPay' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $pay = PaymentInvoice::find($this->id_pay);
            $doc = Document::find($this->id_document);
            if ($this->mountPay <= ($doc->balance + $pay->mount)) {
                $doc->update([
                    'balance' => $doc->balance + $pay->mount - $this->mountPay
                ]);
                $pay->update([
                    'date' => $this->date,
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_document' => $this->id_document,
                    'id_count' => $this->id_count,
                    'reference' => $this->reference,
                    'mount' => $this->mountPay,
                    'observations' => $this->observations
                ]);
                $this->genPay($this->id_document);
                $this->date =  '';
                $this->reference = '';
                $this->observations = '';
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'No se puede agregar un pago mayor al monto pendiente, corrija el monto e intente de nuevo.']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->emit('refreshCXCClientTable');
    }
    public function deletePay($id, $mount)
    {
        try {
            if ($id) {
                PaymentInvoice::where('id', $id)->delete();
                $result = Document::find($this->id_document);
                $result->update([
                    'balance' => $result->balance + $mount
                ]);
            }
            $this->genPay($this->id_document);
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
        $this->emit('refreshCXCClientTable');
    }
}
