<?php

namespace App\Http\Livewire\SystemPay;

use App\Mail\InvoiceMail;
use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\CompaniesEconomicActivities;
use App\Models\ConexionMH;
use App\Models\Consecutive;
use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\DocumentReference;
use App\Models\PaymentInvoice;
use App\Models\PaymentSystem;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Ridivi;
use App\Models\Tax;
use App\Models\Team;
use App\Models\Terminal;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use PDF;

class ListSystemPaysComponent extends Component
{
    public $fact, $company, $plan, $name, $email, $allPlans = [], $qty, $iva, $total, $monto, $idcard_count, $name_count, $iban, $lastname_count,
        $referredby, $password_confirmation, $password, $terms, $phone, $fe, $detail, $id_fact = '', $price, $discount, $date, $id_invoice;
    public $type_idcard, $e_id_card, $e_name, $e_mail, $e_phone, $c_id_card, $c_name, $c_mail, $c_phone, $plan_name, $plan_id;
    protected $listeners = ['viewFactSystem', 'payFactSystem'];
    public function mount()
    {
        $this->allPlans = Plan::all();
    }
    public function render()
    {
        return view('livewire.system-pay.list-system-pays-component');
    }
    public function chargeSystem()
    {
    }
    public function viewFactSystem($id)
    {
        $company = Team::find(1);
        $this->e_name = $company->name;
        $this->e_id_card = $company->id_card;
        $this->e_mail = $company->email_company;
        $this->e_phone = $company->phone_company;
        $this->id_fact =  $id;
        $this->fact = PaymentSystem::find($id);
        $this->plan = Plan::find($this->fact->plan_id);
        $this->id_invoice = $this->fact->id_invoice;
        $this->plan_id = $this->fact->plan_id;
        $this->plan_name = $this->fact->pay_detail;
        $this->qty = $this->fact->months;
        $this->discount = 0;
        $this->price = $this->plan->price_for_month;
        $this->monto = $this->qty * $this->price;
        $this->iva = $this->monto * 0.13;
        $this->total = $this->monto + $this->iva - $this->discount;
        $this->date = date('d-m-Y');
    }
    public function payFactSystem($id)
    {
        $this->company = Team::find(1);
        $this->e_name = $this->company->name;
        $this->e_id_card = $this->company->id_card;
        $this->e_mail = $this->company->email_company;
        $this->e_phone = $this->company->phone_company;
        $this->id_fact =  $id;
        $this->fact = PaymentSystem::find($id);
        $this->plan = Plan::find($this->fact->plan_id);
        $this->plan_id = $this->fact->plan_id;
        $this->detail = $this->fact->pay_detail;
        $this->id_invoice = $this->fact->id_invoice;
        $this->qty = $this->fact->months;
        $this->discount = 0;
        $this->price = $this->plan->price_for_month;
        if ($this->qty > 11) {
            $this->discount = $this->price * 2;
        } else if ($this->qty > 5) {
            $this->discount = $this->price;
        } else {
            $this->discount = 0;
        }
        $this->monto = $this->qty * $this->price;
        $this->iva = $this->monto * 0.13;
        $this->total = $this->monto + $this->iva - $this->discount;
        $this->date = date('d-m-Y');
        $this->type_idcard = 0;
    }
    public function cleanInputs()
    {
        $this->e_name = '';
        $this->e_id_card = '';
        $this->e_mail = '';
        $this->e_phone = '';
        $this->id_fact =  '';
        $this->id_invoice = '';
        $this->plan = 1;
        $this->plan = '';
        $this->plan_name = '';
        $this->qty = 1;
        $this->discount = 0;
        $this->price = 0;
        $this->monto = $this->qty * $this->price;
        $this->iva = $this->monto * 0.13;
        $this->total = $this->monto + $this->iva - $this->discount;
        $this->date = date('d-m-Y');
    }
    public function pay($id)
    {
        $this->validate([
            'idcard_count' => ['required', 'string', 'max:255'],
            'name_count' => ['required', 'string', 'max:255'],
            'lastname_count' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', 'max:255']
        ]);
        $token = Ridivi::getToken($this->company->ridivi_key, $this->company->ridivi_secret);
        if (isset($token['title'])) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $token['title']]);
        } else {
            $link = Ridivi::getLinkPay($token["token"], $this->name_count, $this->lastname_count, $this->idcard_count, $this->type_idcard, Auth::user()->currentTeam->phone_company, Auth::user()->currentTeam->email_company, $this->total, $this->detail);

            if (isset($link['title'])) {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $link['title'] . ': ' . $link['detail']]);
            } else {
                $token = Ridivi::getToken($this->company->ridivi_key, $this->company->ridivi_secret);
                if (isset($token['title'])) {
                    $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $token['title']]);
                } else {
                    $pay = Ridivi::proccessPay($token["token"], $link["payKey"], $this->iban, $this->idcard_count);
                    if (($pay['title'])) {
                        $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $pay['title'] . ': ' . $pay['detail']]);
                    } else { //register
                        DB::beginTransaction();
                        try {
                            $payS = PaymentSystem::find($id);
                            $payS->update([
                                'id_pay' => $pay['id'],
                                'pay_key' => $pay['payKey'],
                            ]);

                            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Pago realizado, id de comprobante ' . $pay['id'] . ', estado ' . $pay['statePay'], 'refresh' => 0]);
                            $this->emit('refreshSystemPayTable');
                        } catch (QueryException $e) {
                            // back to form with errors
                            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
                            DB::rollback();
                        } catch (\Exception $e) {
                            DB::rollback();
                            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
                        }
                        DB::commit();
                        if ($payS->plan_id != $this->company->plan_id) {
                            $this->company->update([
                                'plan_id' => $payS->plan_id
                            ]);
                        }
                        $this->invoiceGenerate($payS, $payS->id_company, '01');
                        $this->dispatchBrowserEvent('paySytemPay_modal_hide', []);                        //endRegister

                    }
                }
            }
        }
    }
    public function invoiceGenerate($payS, $client_id, $type_doc)
    {
        DB::beginTransaction();
        try {
            $bo = BranchOffice::where('branch_offices.id_company', $this->company->id)
                ->leftJoin('country_codes', 'country_codes.id', '=', 'branch_offices.id_country_code')
                ->leftJoin('provinces', 'provinces.id', '=', 'branch_offices.id_province')
                ->leftJoin('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
                ->leftJoin('districts', 'districts.id', '=', 'branch_offices.id_district')
                ->select(
                    'branch_offices.*',
                    'country_codes.phone_code',
                    'provinces.code as code_province',
                    'cantons.code as code_canton',
                    'districts.code as code_district'
                )->first();
            $terminal = Terminal::where('id_company', $this->company->id)
                ->where('id_branch_office', $bo->id)->first();
            $number_ea = CompaniesEconomicActivities::where('id_company', $this->company->id)
                ->leftJoin('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
                ->select(
                    'economic_activities.id',
                    'economic_activities.number'
                )
                ->first()->number;
            $consecutive = $this->getConsecutive($bo, $terminal, '01');
            $keyDoc = $bo->phone_code . date("d") . date("m") . date("y") . str_pad($this->company->id_card, 12, "0", STR_PAD_LEFT) . $consecutive . '119890717';
            $path = 'files/creados/' . $this->company->id_card . '/' . $keyDoc;
            $dateDoc = date('Y-m-d H:i:s');
            $doc = Document::create([
                'id_company' => $this->company->id,
                'id_terminal' => $terminal->id,
                'id_client' => $client_id,
                'key' => $keyDoc,
                'e_a' => $number_ea,
                'consecutive' => $consecutive,
                'date_issue' => $dateDoc,
                'sale_condition' => '01',
                'term' => 1,
                'priority' => 1,
                'delivery_date' => $dateDoc,
                'payment_method' => '04',
                'currency' => "CRC",
                'exchange_rate' => '1',
                'id_mh_categories' => 3,
                'total_taxed_services' => $this->total_taxed_services,
                'total_exempt_services' => 0,
                'total_exonerated_services' => 0,
                'total_taxed_merchandise' => 0,
                'total_exempt_merchandise' => 0,
                'total_exonerated_merchandise' => 0,
                'total_taxed' => $this->monto,
                'total_exempt' => 0,
                'total_exonerated' => 0,
                'total_discount' => $this->discount,
                'total_net_sale' => $this->monto,
                'total_exoneration' => 0,
                'total_tax' => $this->iva,
                'total_other_charges' => 0,
                'iva_returned' => 0,
                'total_cost' => 0,
                'total_document' => $this->total,
                'balance' => $this->total,
                'path' => $path,
                'state_send' => "Sin enviar",
                'answer_mh' => "Ninguna",
                'detail_mh' => "Ninguno",
                'type_doc' => $type_doc,
                'id_branch_office' => $bo->id,
                'note' =>  null,
                'id_seller' =>  null
            ]);
            $product = Product::where('descriptuion', 'Plan ' . $this->plan_name)->first();
            $id_tax = ($product->ids_taxes == "" || $product->ids_taxes == "[]") ? '' : json_decode($product->ids_taxes, true)[0];
            $tax = Tax::where('taxes.id', $id_tax)
                ->leftJoin('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
                ->select('taxes.*', 'taxes_codes.code as taxCode', 'rate_codes.code as rateCode')->first();
            $taxLine = array(
                "id" => (string)$tax->id,
                "code" => (string)$tax->taxCode,
                "rate_code" => (string)$tax->rateCode,
                "rate" => (string)$tax->rate,
                "mount" => $taxAmount = bcdiv(((1 * bcdiv($this->price, '1', 5)) - bcdiv($this->discount, '1', 5)) * $tax->rate / 100, '1', 5),
                "exoneration" => "",
            );
            DocumentDetail::create([
                'id_document' => $doc->id,
                'id_product' => $product->id,
                'tariff_heading' => 0,
                'code' => str_pad($product->cabys, 13, '0', STR_PAD_LEFT),
                'qty' => $this->qty,
                'qty_dispatch' => $this->qty,
                'sku' => 'Sp',
                'detail' => 'Plan ' . $this->plan_name,
                'cost_unid' => $product->cost_unid,
                'price_unid' => $product->price_unid,
                'total_amount' => $this->qty *  $product->price_unid,
                'discounts' => $this->discount,
                'subtotal' => ($this->qty *  $product->price_unid) - $this->discount,
                'taxes' => json_encode($taxLine),
                'tax_net' => $product->tax_net,
                'total_amount_line' => ($this->qty *  $product->price_unid) - $this->discount + $taxAmount,
            ]);
            DocumentReference::create([
                'id_document' => $doc->id,
                'code_type_doc' => '99',
                'number' => 'FS-' . str_pad($this->id_invoice, 10, "0", STR_PAD_LEFT),
                'date_issue' =>  str_replace(' ', 'T', $this->fact->created_at),
                'code' => 4,
                'reason' => 'Factura de Sistema'
            ]);
            PaymentInvoice::create([
                'date' => now(),
                'id_company' => Auth::user()->currentTeam->id,
                'id_document' => $doc->id,
                'payment_method' => '04',
                'id_count' => ($this->id_count == "") ? null : $this->id_count,
                'reference' => $payS->id_pay,
                'mount' => $doc->total_document,
                'observations' => 'Pago de contado'
            ]);
            $doc->update([
                'balance' => $doc->balance - $doc->total_document
            ]);

            $this->generatePDF($doc->id);
            $this->nextConsecutive($type_doc,$bo->id,$terminal->id);
            $client = Client::where('clients.id', $client_id)
                ->leftJoin('country_codes', 'country_codes.id', '=', 'clients.id_country_code')
                ->leftJoin('provinces', 'provinces.id', '=', 'clients.id_province')
                ->leftJoin('cantons', 'cantons.id', '=', 'clients.id_canton')
                ->leftJoin('districts', 'districts.id', '=', 'clients.id_district')
                ->select(
                    'clients.*',
                    'country_codes.phone_code',
                    'provinces.code as code_province',
                    'cantons.code as code_canton',
                    'districts.code as code_district'
                )->first();

            $xmlF = ConexionMH::createXML($type_doc,$this->company,$bo,$terminal,$client,$doc,$number_ea);
            $token = $this->tokenMH();
            $this->send($client, $xmlF, json_decode($token)->access_token);
            $path = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $this->keyDoc;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            while ($respMH == '' || $respMH == 'procesando') {
                $respMH = $this->consultState($this->keyDoc);
            }
            if ($this->type_doc == '03' && $respMH == "aceptado") {
                $this->payWithNC($doc);
            }

            $this->dispatchBrowserEvent('messageData', ['messageData' => "El documento clave: " . $this->keyDoc . ' tiene como estado: ' . $respMH . ' en el Ministerio de Hacienda']);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        if (($this->type_doc != '00' && $this->type_doc != '11' && $this->type_doc != '99') && $respMH == "aceptado") {
            $mail_send = Client::find($this->id_client)->emails;
            $key_send = Document::find($doc->id)->key;
            $this->sendInvoice();
        }
    }
    public function nextConsecutive($type_doc, $id_branch_office, $id_terminal)
    {
        $cons = Consecutive::where('consecutives.id_branch_offices', $id_branch_office)
            ->where('consecutives.id_terminal', $id_terminal)->first();
        $consecutive = array();
        switch ($type_doc) {
            case '01':
                $consecutive["c_fe"] = ($cons->c_fe + 1);
                break;
            case '02':
                $consecutive["c_nd"] = ($cons->c_nd + 1);
                break;
            case '03':
                $consecutive["c_nc"] = ($cons->c_nc + 1);
                break;
            case '04':
                $consecutive["c_te"] = ($cons->c_te + 1);
                break;
            case '09':
                $consecutive["c_fex"] = ($cons->c_fex + 1);
                break;
            case '00':
                $consecutive["c_ov"] = ($cons->c_ov + 1);
                break;
            case '11':
                $consecutive["c_fi"] = ($cons->c_fi + 1);
                break;
            case '99':
                $consecutive["c_co"] = ($cons->c_co + 1);
                break;
            default:
                $consecutive["c_ov"] = ($cons->c_ov + 1);
        }
        Consecutive::where('id_branch_offices', '=', $id_branch_office)->where('consecutives.id_terminal', $id_terminal)->update($consecutive);
    }
    public function generatePDF($id)
    {
        $doc = Document::where('documents.id', '=', $id)
            ->leftJoin('sale_conditions', 'sale_conditions.code', '=', 'documents.sale_condition')
            ->leftJoin('payment_methods', 'payment_methods.code', '=', 'documents.payment_method')
            ->select(
                'documents.*',
                'sale_conditions.sale_condition as saleConditions',
                'payment_methods.payment_method as paymentMethod',
            )->first();
        $clave = $doc->key;
        $bo = BranchOffice::where('branch_offices.id_company', Auth::user()->currentTeam->id)
            ->where('branch_offices.id', $doc->id_branch_office)
            ->leftJoin('country_codes', 'country_codes.id', '=', 'branch_offices.id_country_code')
            ->leftJoin('provinces', 'provinces.id', '=', 'branch_offices.id_province')
            ->leftJoin('cantons', 'cantons.id', '=', 'branch_offices.id_canton')
            ->leftJoin('districts', 'districts.id', '=', 'branch_offices.id_district')
            ->select(
                'branch_offices.*',
                'country_codes.phone_code',
                'provinces.province as name_province',
                'cantons.canton as name_canton',
                'districts.district as name_district'
            )->first();
        $client = Client::where('clients.id', $doc->id_client)
            ->leftJoin('country_codes', 'country_codes.id', '=', 'clients.id_country_code')
            ->leftJoin('provinces', 'provinces.id', '=', 'clients.id_province')
            ->leftJoin('cantons', 'cantons.id', '=', 'clients.id_canton')
            ->leftJoin('districts', 'districts.id', '=', 'clients.id_district')
            ->select(
                'clients.*',
                'country_codes.phone_code',
                'provinces.province as name_province',
                'cantons.canton as name_canton',
                'districts.district as name_district'
            )->first();
        $reference = DocumentReference::where('document_references.id_document', $doc->id)->first();
        $details = DocumentDetail::where('document_details.id_document', $doc->id)->get();

        $data = [
            'title' => $this->typeDoc2($doc->type_doc),
            'document' => $doc,
            'company' => Auth::user()->currentTeam,
            'company_bo' => $bo->name_branch_office,
            'bo' => $bo,
            'client' => $client,
            'reference' => $reference,
            'details' => $details,
            'otherCharges' => '',
            'otherCharge' => $this->otherCharge,
            'seller' => ''
        ];
        $pdf = PDF::loadView('livewire.document.invoicePDF', $data);
        $path = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $doc->key;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $pdf->save($path . '/' . $clave . '.pdf');
    }
    public function sendInvoice($company)
    {
        $data = array();
        $data["key"] = $this->key_send;
        $data["xml"] = 'files/creados/' . $company->id_card . '/' . $data["key"] . '/' . $data["key"] . '-Firmado.xml';
        $data["xmlR"] = 'files/creados/' . $company->id_card . '/' . $data["key"] . '/' . $data["key"] . '-R.xml';
        $data["pdf"] = 'files/creados/' . $company->id_card . '/' . $data["key"] . '/' . $data["key"] . '.pdf';
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
    public function getConsecutive($bo, $terminal, $type_doc)
    {
        $type_document = "";
        $pre = "";
        $consecutive = "";
        $cons = Consecutive::where('consecutives.id_branch_offices', $bo->id)
            ->where('consecutives.id_terminal', $terminal->id)->first();
        switch ($type_doc) {
            case '01':
                $type_document = 'Factura Electronica';
                $pre = 'FE-';
                $consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($terminal->number, 5, "0", STR_PAD_LEFT) . $type_doc . str_pad($cons->c_fe, 10, "0", STR_PAD_LEFT);
                break;
            case '02':
                $type_document = 'Nota Electronica de Debito';
                $pre = 'ND-';
                $consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($terminal->number, 5, "0", STR_PAD_LEFT) . $type_doc  . str_pad($cons->c_nd, 10, "0", STR_PAD_LEFT);
                break;
            case '03':
                $type_document = 'Nota Electronica de Credito';
                $pre = 'NC-';
                $consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($terminal->number, 5, "0", STR_PAD_LEFT) . $type_doc  . str_pad($cons->c_nc, 10, "0", STR_PAD_LEFT);
                break;
            case '04':
                $type_document = 'Tiquete Electronico';
                $pre = 'TE-';
                $consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($terminal->number, 5, "0", STR_PAD_LEFT) . $type_doc  . str_pad($cons->c_te, 10, "0", STR_PAD_LEFT);
                break;
            case '09':
                $type_document = 'Factura Electronica de Exportacion';
                $pre = 'FEX-';
                $consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($terminal->number, 5, "0", STR_PAD_LEFT) . $type_doc  . str_pad($cons->c_fex, 10, "0", STR_PAD_LEFT);
                break;
            case '00':
                $type_document = 'Orden de Venta';
                $pre = 'OV-';
                $consecutive = 'OV-' . str_pad($cons->c_ov, 10, "0", STR_PAD_LEFT);
                break;
            case '11':
                $type_document = 'Factura Contingencia';
                $pre = 'FC-';
                $consecutive = 'FC-' . str_pad($cons->c_fi, 10, "0", STR_PAD_LEFT);
                break;
            case '99':
                $type_document = 'Proforma';
                $pre = 'PO-';
                $consecutive = 'PO-' . str_pad($cons->c_co, 10, "0", STR_PAD_LEFT);
                break;
            default:
                $type_document = 'Orden de Venta';
                $pre = 'OV-';
                $consecutive = 'OV-' . str_pad($cons->c_ov, 10, "0", STR_PAD_LEFT);
        }
        return $consecutive;
    }
}
