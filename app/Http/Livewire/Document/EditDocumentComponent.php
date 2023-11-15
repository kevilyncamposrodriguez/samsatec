<?php

namespace App\Http\Livewire\Document;

use App\Models\BranchOffice;
use App\Models\Client;
use App\Models\CompaniesEconomicActivities;
use App\Models\Consecutive;
use App\Models\Count;
use App\Models\Currencies;
use App\Models\Discount;
use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\DocumentOtherCharge;
use App\Models\DocumentReference;
use App\Models\Exoneration;
use App\Models\MhCategories;
use App\Models\PaymentInvoice;
use App\Models\PaymentMethods;
use App\Models\PriceList;
use App\Models\PriceListsLists;
use App\Models\Product;
use App\Models\Reference;
use App\Models\ReferenceTypeDocument;
use App\Models\SaleConditions;
use App\Models\Seller;
use App\Models\Skuse;
use App\Models\Tax;
use App\Models\TeamUser;
use App\Models\Terminal;
use App\Models\TypeDocumentOtherCharge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDF;

class EditDocumentComponent extends Component
{
    public $path, $id_ea, $keyDoc, $total_taxed_services, $patn, $dateDoc, $total_exempt_merchandise, $id_document, $updateMode = false, $allDocuments, $index, $id_discount, $code_product, $type_document, $percent_discount, $percent_tax, $percent_exo, $id_branch_office, $id_expense, $key, $consecutive, $date,
        $sale_condition, $id_client, $payment_method, $currency, $exchange_rate, $price_list_id,
        $type_doc, $id_terminal, $delivery_date, $subtotal, $discount = 0, $total, $totalDiscount,
        $totalTax, $totalExoneration = 0, $sku, $id_tax, $cabys, $detail, $number_ea, $otherCharge,
        $id_type_reference, $name_client, $id_card, $name, $code_payment_method, $id_type_document_other,
        $code_sale_condition, $id_mh_category, $priority, $idcard_other, $name_other, $phone,
        $id_exoneration, $mail, $id_seller, $price = 0, $qty = 1, $cost = 0, $name_product, $qty_dispatch,
        $detail_other_charge, $id_product, $details = [], $tax, $exoneration, $tariff_heading = 0,
        $total_exonerated_merchandise = 0, $total_other_charges = 0, $state_send = 'Sin enviar',
        $iva_returned = 0, $reason, $id_reference, $amount_other, $date_reference, $number_reference,
        $code_currency, $term, $answer_mh = 'Ninguna', $detail_mh = 'Ninguno', $note = '',
        $is_orden = false, $key_send, $mail_send, $cc_mail, $total_exonerated_services = 0, $total_exempt_services = 0, $total_taxed_merchandise = 0;
    public $allDiscounts = [], $allPayments = [], $allLines = [], $allPriceList = [], $allProducts, $cash,
        $allCurrencies, $allMHCategories, $allSaleConditions, $allSellers, $allEconomicActivities,
        $allBranchOffices, $allTerminals, $allSkuses, $allTaxes, $allExonerations = [], $allTypeReferences, $allOtherCharges = [], $allTypeDocumentOtherCharges, $allClients, $allPaymentMethods,
        $allReferences, $total_cost, $pre, $id_count, $allAcounts, $reference, $observations, $total_pagar;

    protected $listeners = ['editDocument' => 'edit', 'cleanInputs'];
    public function mount()
    {
        $this->percent_discount = 0;
        $this->allAcounts = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
        $this->delivery_date = date('Y-m-d');
        $this->priority = 1;
        $this->allMHCategories = MhCategories::all();
        $this->allTypeReferences = ReferenceTypeDocument::all();
        $this->allReferences = Reference::all();
        $this->allTaxes = Tax::where("taxes.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allSkuses = Skuse::all();
        $this->allPaymentMethods = PaymentMethods::all()->sortBy('payment_method');
        $this->allSaleConditions = SaleConditions::all();
        $this->allBranchOffices = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->id_branch_office = $this->allBranchOffices[0]->id;
        $this->allTerminals = Terminal::where("terminals.id_branch_office", "=", $this->id_branch_office)
            ->where("terminals.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allSellers = Seller::where("sellers.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allCurrencies = Currencies::all();
        $this->allPriceList = PriceList::where("price_lists.id_company", "=", Auth::user()->currentTeam->id)->get();
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where("products.active", '1')->get();
        } else {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count)
                ->where("products.active", '1')->get();
        }
        $this->allDocuments = Document::where('documents.id_company', '=', Auth::user()->currentTeam->id)
            ->leftJoin('clients', 'clients.id', '=', 'documents.id_client')
            ->leftJoin('mh_categories', 'mh_categories.id', '=', 'documents.id_mh_categories')
            ->select('documents.*', 'clients.name_client', 'mh_categories.name as category_mh')->orderBy('id', 'desc')->get();
        $this->allEconomicActivities = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select('economic_activities.*', 'companies_economic_activities.*')->get();
        $this->number_ea = $this->allEconomicActivities->first()->number;
    }
    public function render()
    {
        $this->allClients = Client::where("clients.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allEconomicActivities = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select('economic_activities.*', 'companies_economic_activities.*')->get();
        return view('livewire.document.edit-document-component');
    }
    public function edit($id)
    {
        $this->cleanInputs();
        try {
            $this->updateMode = true;
            $doc = Document::where('id', $id)->first();
            $this->id_client = $doc->id_client;
            $this->changeClient(Client::where('id', $this->id_client)->first()->name_client);
            $this->allTypeDocumentOtherCharges = TypeDocumentOtherCharge::all();
            $this->keyDoc = $doc->key;
            $this->note = $doc->note;
            $this->id_document = $doc->id;
            $this->date_reference = $doc->date_issue;
            $this->delivery_date = substr($doc->delivery_date, 0, 10);
            $this->priority = $doc->priority;
            $this->code_currency = $doc->currency;
            $this->type_doc = str_pad($doc->type_doc, 2, "0", STR_PAD_LEFT);
            $this->code_payment_method = $doc->payment_method;
            $this->id_mh_category = $doc->id_mh_categories;
            $this->code_sale_condition = $doc->sale_condition;
            $this->term = $doc->term;
            $this->exchange_rate = $doc->exchange_rate;
            $this->consecutive = $doc->consecutive;
            $this->id_ea = $doc->e_a;
            $this->id_seller = $doc->id_seller;
            $this->id_branch_office = $doc->id_branch_office;
            $this->dateDoc = $doc->date_issue;
            $this->subtotal = $doc->total_net_sale;
            $this->totalDiscount = $doc->total_discount;
            $this->totalTax = $doc->total_tax;
            $this->total_other_charges = $doc->total_other_charges;
            $this->total = ($doc->total_document - $doc->total_other_charges);

            $details = DocumentDetail::where('document_details.id_document', $doc->id)->get();
            foreach ($details as $detail) {
                $product = Product::find($detail->id_product);
                $line = [
                    "id_product" => $detail->id_product,
                    "internal_code" => $product->internal_code,
                    "cabys" => $detail->code,
                    'tariff_heading' => $detail->tariff_heading,
                    "description" => $detail->detail,
                    "sku" => $detail->sku,
                    "qty" => $detail->qty,
                    "qty_dispatch" => $detail->qty_dispatch,
                    "price" => $detail->price_unid,
                    "cost" => $detail->cost_unid,
                    "discountUnid" => $detail->discounts,
                    "discount" => $detail->discounts,
                    "tax" => json_decode($detail->taxes, true),
                    "totalLine" => $detail->total_amount_line,
                ];
                array_push($this->allLines, $line);
            }
            $otherCharges = DocumentOtherCharge::leftJoin('type_document_other_charges', 'type_document_other_charges.id', '=', 'document_other_charges.type_document')
                ->where('id_document', $doc->id)
                ->select(
                    'document_other_charges.*',
                    'type_document_other_charges.type_document as typeDocument'
                )->get();
            if (count($otherCharges) > 0) {
                foreach ($otherCharges as $charge) {
                    $chargeLine = [
                        "id_type_document_other" => $charge->type_document,
                        "type_document_other" => $charge->typeDocument,
                        'idcard_other' => $charge->idcard,
                        "name_other" => $charge->name,
                        "detail_other_charge" => $charge->detail,
                        "amount_other" => $charge->amount
                    ];
                    array_push($this->allOtherCharges, $chargeLine);
                }
                $this->otherCharge = 1;
            }
            if ($reference = DocumentReference::where("id_document", $doc->id)->first()) {
                $this->id_type_reference =  $reference->code_type_doc;
                $this->number_reference = $reference->number;
                $this->date_reference = str_replace(' ', 'T', $reference->date_issue);
                $this->id_reference =  str_pad($reference->code, 2, "0", STR_PAD_LEFT);
                $this->reason =  $reference->reason;
            }
            $this->type_document = $this->typeDoc2($this->type_doc);
            $this->subtotal = $doc->total_net_sale;
            $this->totalDiscount = $doc->total_discount;
            $this->totalTax = $doc->total_tax;
            $this->totalExoneration = $doc->total_exoneration;
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback and then redirect
            // back to form with errors
            $this->dispatchBrowserEvent('documentU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('documentU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados' . $e->getMessage()]);
        }
    }
    public function editTax($index)
    {
        $this->index = $index;
        $this->id_exoneration = '';
        $this->exoneration = 0;
        $this->percent_exo = 0;
        $this->tax = 0;
        $this->percent_exo = 0;
        $exoneration = '';
        if (count($this->allLines[$index]['tax']) > 0) {
            $this->id_tax =  $this->allLines[$index]['tax']['id'];
            $tax = Tax::where('taxes.id', $this->id_tax)
                ->leftJoin('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
                ->select('taxes.*', 'taxes_codes.code as taxCode', 'rate_codes.code as rateCode')->first();
            $this->tax = bcdiv((($this->allLines[$index]['price']) - bcdiv($this->allLines[$index]['discountUnid'], '1', 5)) * $tax->rate / 100, '1', 5);
            $this->percent_tax = $tax->rate;
            if ($this->allLines[$index]['tax']['exoneration'] != '') {
                $exoneration = $exoneration = Exoneration::where('exonerations.id', $tax->id_exoneration)
                    ->leftJoin('type_document_exonerations', 'type_document_exonerations.id', 'exonerations.id_type_document_exoneration')
                    ->select('exonerations.*', 'type_document_exonerations.code', 'type_document_exonerations.document')->first();
                $this->id_exoneration = $exoneration->id;
                $this->percent_exo = $exoneration->exemption_percentage;
                $this->exoneration = bcdiv((($this->allLines[$this->index]['price']) - bcdiv($this->allLines[$this->index]['discountUnid'], '1', 5)) * $exoneration->exemption_percentage / 100, '1', 5);
            } else {
                $this->percent_exo = 0;
                $this->exoneration = 0;
            }
        } else {
            $this->id_tax = '';
            $this->id_exoneration = '';
            $this->exoneration = 0;
            $this->percent_exo = 0;
            $this->tax = 0;
            $this->percent_tax = 0;
        }
    }
    public function cleanInputs()
    {
        $this->percent_discount = 0;
        $this->otherCharge = 0;
        $this->allOtherCharges = [];
        $this->allLines = [];
        $this->id_client = '';
        $this->name_client = '';
        $this->note = '';
        $this->type_doc = '';
        $this->code_currency = 'CRC';
        $this->code_payment_method = '01';
        $this->id_mh_category = '01';
        $this->code_sale_condition = '01';
        $this->priority = 1;
        $this->delivery_date = date('Y-m-d');
        $this->price = 0;
        $this->cost = 0;
        $this->qty = 1;
        $this->qty_dispatch = 1;
        $this->term = 1;
        $this->exchange_rate = 1;
        $this->consecutive = '';
        $this->price_list_id = '';
        $this->subtotal = 0;
        $this->totalDiscount = 0;
        $this->totalTax = 0;
        $this->totalExoneration = 0;
        $this->total = 0;
        $this->id_type_reference = '';
        $this->number_reference = '';
        $this->date_reference = '';
        $this->id_reference = 1;
        $this->id_product = '';
        $this->name_product = '';
        $this->id_tax = $this->allTaxes->first()->id;
        $this->reason = '';
        $this->cc_mail = '';
        $this->discount = 0;
        $this->allAcounts = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
    }
    public function typeDoc2($type)
    {
        switch ($type) {
            case '01':
                return 'Factura Electronica';
                break;
            case '02':
                return 'Nota Electronica de Debito';
                break;
            case '03':
                return 'Nota Electronica de Credito';
                break;
            case '04':
                return 'Tiquete Electronico';
                break;
            case '09':
                return 'Factura Electronica de Exportacion';
                break;
            case '00':
                return 'Orden de Venta';
                break;
            case '11':
                return 'Factura Contingencia';
                break;
            case '99':
                return 'Proforma';
                break;
            default:
                return 'Orden de Venta';
        }
    }
    public function otherChargeChange()
    {
        //limpia los campos
        $this->id_type_document_other = '';
        $this->idcard_other = '';
        $this->name_other = '';
        $this->detail_other_charge = '';
        $this->amount_other = 0;
        $this->allOtherCharges = [];
        $this->total_other_charges = 0;
        $this->otherCharge = ($this->otherCharge) ? 0 : 1;
        if ($this->otherCharge) {
            $otherCharges = DocumentOtherCharge::leftJoin('type_document_other_charges', 'type_document_other_charges.id', '=', 'document_other_charges.type_document')
                ->where('id_document', $this->id_document)
                ->select(
                    'document_other_charges.*',
                    'type_document_other_charges.type_document as typeDocument'
                )->get();
            if (count($otherCharges) > 0) {
                foreach ($otherCharges as $charge) {
                    $chargeLine = [
                        "id_type_document_other" => $charge->type_document,
                        "type_document_other" => $charge->typeDocument,
                        'idcard_other' => $charge->idcard,
                        "name_other" => $charge->name,
                        "detail_other_charge" => $charge->detail,
                        "amount_other" => $charge->amount
                    ];
                    $this->total_other_charges += $charge->amount;
                    array_push($this->allOtherCharges, $chargeLine);
                }
                $this->otherCharge = 1;
            }
        }
    }
    public function changeSku($id)
    {
        $this->sku = $id;
    }
    public function changeProduct()
    {
        if ($this->name_product != '') {
            $product = $this->allProducts->where('description', $this->name_product)->first();
            if ($product) {
                $this->id_product = $product->id;
                $this->code_product = $product->internal_code;
                if ($product->id_discount != '') {
                    $discount = Discount::find($product->id_discount);
                    $this->discount = $discount->amount;
                } else {
                    $this->discount = 0;
                }
                $this->cabys = $product->cabys;
                $this->detail = $product->description;
                $list = PriceListsLists::where("price_lists_lists.id_price_list", $this->price_list_id)->where("price_lists_lists.id_product", $product->id)->first();

                $this->price = ($this->price_list_id != '' && $list != null) ? $list->price : $product->price_unid;
                $this->cost = $product->cost_unid;
                $this->sku = Skuse::where('skuses.id', '=', $product->id_sku)->first()->symbol;
                $this->id_tax = ($product->ids_taxes == "[]") ? '' : json_decode($product->ids_taxes, true)[0];
                $this->addLine();
            }
        } else {

            $this->cabys = '';
            $this->detail = '';
            $this->price = 0;
            $this->cost = 0;
            $this->sku = Skuse::first()->symbol;
            $this->id_tax = '';
            $this->qty = 1;
            $this->discount = 0;
        }
    }
    public function addLine()
    {
        $taxLine = [];
        $tax = [];
        $exonerationAmount = 0;
        $taxAmount = 0;
        $agregar = true;
        foreach ($this->allLines as $key => $value) {
            if ($value['internal_code'] == $this->code_product) {
                $agregar = false;
                $this->allLines[$key]['qty_dispatch'] = $this->allLines[$key]['qty_dispatch'] + 1;
            }
        }
        if ($agregar) {
            if ($this->id_tax != '') {
                $tax = Tax::where('taxes.id', $this->id_tax)
                    ->leftJoin('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                    ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
                    ->select('taxes.*', 'taxes_codes.code as taxCode', 'rate_codes.code as rateCode')->first();

                if ($tax->id_exoneration != null) {
                    $exoneration = Exoneration::where('exonerations.id', $tax->id_exoneration)
                        ->leftJoin('type_document_exonerations', 'type_document_exonerations.id', 'exonerations.id_type_document_exoneration')
                        ->select('exonerations.*', 'type_document_exonerations.code', 'type_document_exonerations.document')->first();
                }
                $taxLine = array(
                    "id" => (string)$tax->id,
                    "code" => (string)$tax->taxCode,
                    "rate_code" => (string)$tax->rateCode,
                    "rate" => (string)$tax->rate,
                    "mount" => $taxAmount = bcdiv(((1 * bcdiv($this->price, '1', 5)) - bcdiv($this->discount, '1', 5)) * $tax->rate / 100, '1', 5),
                    "exoneration" => (isset($exoneration)) ? array(
                        "id" => (string)$exoneration->id,
                        "DocumentType" => (string)$exoneration->code,
                        "DocumentNumber" => (string)$exoneration->document_number,
                        "InstitucionalName" => (string)$exoneration->institutional_name,
                        "EmisionDate" => (string)$exoneration->date,
                        "PercentageExoneration" => (string)$exoneration->exemption_percentage,
                        "AmountExoneration" => $exonerationAmount = ((1 * $this->price) - $this->discount) * $exoneration->exemption_percentage / 100
                    ) : "",
                );
            }
            $line = [
                "id_product" => $this->id_product,
                "internal_code" => $this->code_product,
                "cabys" => str_pad($this->cabys, 13, "0", STR_PAD_LEFT),
                'tariff_heading' => $this->tariff_heading,
                "description" => $this->detail,
                "sku" => $this->sku,
                "qty" => 1,
                "qty_dispatch" => 1,
                "cost" => $this->cost,
                "price" =>  bcdiv($this->price, '1', 5),
                "discountUnid" => bcdiv($this->discount, '1', 5),
                "discount" => bcdiv($this->discount * 1, '1', 5),
                "tax" => $taxLine,
                "totalLine" => bcdiv((1 * $this->price) - $this->discount + $taxAmount - $exonerationAmount, '1', 5)
            ];
            array_push($this->allLines, $line);
        }
        $this->calculateLines();

        $this->dispatchBrowserEvent('product-updated', [
            'newValue' => '',
        ]);
        //limpia los campos
        $this->qty = 1;
        $this->price = 0;
        $this->cost = 0;
        $this->discount = 0;
        $this->id_tax = $this->allTaxes->first()->id;
        $this->id_product = '';
        $this->name_product = '';
        $this->code_product = '';
        $this->tariff_heading = 0;
        $this->sku = $this->allSkuses->first()->symbol;
    }
    public function changeList()
    {
        if ($this->price_list_id && $this->id_product) {
            $this->price = PriceListsLists::where('id_price_list', $this->price_list_id)
                ->where('id_product', $this->id_product)->first()->price;
        } else {
            if ($this->id_product) {
                $product = Product::where('products.id', '=', $this->id_product)->first();
                $this->price =  ($this->price_list_id == '') ? $product->price_unid : PriceListsLists::where("price_lists_lists.id_price_list", $this->price_list_id)->where("price_lists_lists.id_product", $product->id)->first()->price;
            }
        }
    }
    public function changeT()
    {
        $this->getConsecutive();
    }
    public function changeClient($name)
    {
        if ($name != "" && $client = $this->allClients->where('name_client', $name)->first()) {
            $this->id_client = $client->id;
            $this->name_client = $client->name_client;
        }

        if ($this->id_client != '') {
            $client = Client::where('clients.id', '=', $this->id_client)
                ->leftJoin('sale_conditions', 'sale_conditions.id', '=', 'clients.id_sale_condition')
                ->leftJoin('currencies', 'currencies.id', '=', 'clients.id_currency')
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'clients.id_payment_method')
                ->select('clients.*', 'sale_conditions.code as code_sale_condition', 'currencies.code as code_currency', 'payment_methods.code as code_payment_method')->first();
            $this->changeCurrency(Currencies::find($client->id_currency)->code);
            $this->code_sale_condition = $client->code_sale_condition;
            $this->code_payment_method = $client->code_payment_method;
            $this->term = $client->time;
            $this->price_list_id = ($client->id_price_list) ? $client->id_price_list : '';
        }
    }
    public function changeCurrency($code)
    {
        $this->code_currency = $code;
        if ($this->code_currency == 'USD') {
            $this->exchange_rate = session('sale');
        } else {
            $this->exchange_rate = 1;
        }
    }
    public function changeBO()
    {
        $this->allTerminals = Terminal::where("terminals.id_branch_office", "=", $this->id_branch_office)
            ->where("terminals.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->id_terminal = $this->allTerminals[0]->id;
        $this->getConsecutive();
    }
    public function editLine($index)
    {
        $this->id_product = $this->allLines[$index]['id_product'];
        $this->name_product = $this->allLines[$index]['description'];
        $this->changeProduct();
        $this->qty = $this->allLines[$index]['qty'];
        $this->cost = $this->allLines[$index]['cost'];
        $this->price = $this->allLines[$index]['price'];
        $this->tariff_heading = $this->allLines[$index]['tariff_heading'];
        $this->sku = $this->allLines[$index]['sku'];
        $this->discount = $this->allLines[$index]['discount'];
        $this->deleteLine($index);
    }
    public function deleteLine($index)
    {
        $mountL = $this->allLines[$index]['qty_dispatch'] * $this->allLines[$index]['price'];
        $this->subtotal -= $mountL;
        $this->total_cost -= ($this->allLines[$index]['qty_dispatch'] * $this->allLines[$index]['cost']);
        $this->totalDiscount -= ($this->allLines[$index]['discount']);
        $this->totalTax -= (isset($this->allLines[$index]['tax']['mount'])) ? $this->allLines[$index]['tax']['mount'] : 0;
        $this->totalExoneration -= (isset($this->allLines[$index]['tax']['exoneration']['AmountExoneration'])) ? $this->allLines[$index]['tax']['exoneration']['AmountExoneration'] : 0;
        $this->total =  $this->subtotal - $this->totalDiscount + $this->totalTax - $this->totalExoneration;
        $this->total_pagar =  ($this->total + $this->total_other_charges);
        unset($this->allLines[$index]);
        $this->allLines = array_values($this->allLines);
    }
    public function getConsecutive()
    {

        $bo = BranchOffice::where('branch_offices.id', $this->id_branch_office)->first();
        $te = Terminal::where("terminals.id", "=", $this->id_terminal)->first();
        $cons = Consecutive::where('consecutives.id_branch_offices', $this->id_branch_office)
            ->where('consecutives.id_terminal', $this->id_terminal)->first();

        switch ($this->type_doc) {
            case '01':
                $this->type_document = 'Factura Electronica';
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($te->number, 5, "0", STR_PAD_LEFT) . $this->type_doc . str_pad($cons->c_fe, 10, "0", STR_PAD_LEFT);
                break;
            case '02':
                $this->type_document = 'Nota Electronica de Debito';
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($te->number, 5, "0", STR_PAD_LEFT) . $this->type_doc  . str_pad($cons->c_nd, 10, "0", STR_PAD_LEFT);
                break;
            case '03':
                $this->type_document = 'Nota Electronica de Credito';
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($te->number, 5, "0", STR_PAD_LEFT) . $this->type_doc  . str_pad($cons->c_nc, 10, "0", STR_PAD_LEFT);
                break;
            case '04':
                $this->type_document = 'Tiquete Electronico';
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($te->number, 5, "0", STR_PAD_LEFT) . $this->type_doc  . str_pad($cons->c_te, 10, "0", STR_PAD_LEFT);
                break;
            case '09':
                $this->type_document = 'Factura Electronica de Exportacion';
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . str_pad($te->number, 5, "0", STR_PAD_LEFT) . $this->type_doc  . str_pad($cons->c_fex, 10, "0", STR_PAD_LEFT);
                break;
            case '00':
                $this->type_document = 'Orden de Venta';
                $this->consecutive = 'OV-' . str_pad($cons->c_ov, 10, "0", STR_PAD_LEFT);
                break;
            case '11':
                $this->type_document = 'Factura Contingencia';
                $this->consecutive = 'FC-' . str_pad($cons->c_fi, 10, "0", STR_PAD_LEFT);
                break;
            case '99':
                $this->type_document = 'Proforma';
                $this->consecutive = 'PO-' . str_pad($cons->c_co, 10, "0", STR_PAD_LEFT);
                break;
            default:
                $this->type_document = 'Orden de Venta';
                $this->consecutive = 'OV-' . str_pad($cons->c_ov, 10, "0", STR_PAD_LEFT);
        }
    }
    public function update()
    {
        $this->validate([
            'id_client' => 'required',
            'code_currency' => 'required',
            'code_payment_method' => 'required',
            'id_mh_category' => 'required',
            'code_sale_condition' => 'required',
            'term' => 'required',
            'exchange_rate' => 'required',
            'consecutive' => 'required',
            'id_ea' => 'required',
            'id_branch_office' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if ($this->id_document) {
                $this->calculate();
                $result = Document::find($this->id_document);
                $this->path = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $this->keyDoc;
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_client' => $this->id_client,
                    'key' => $this->keyDoc,
                    'e_a' => $this->id_ea,
                    'consecutive' => $this->consecutive,
                    'date_issue' => $this->dateDoc,
                    'sale_condition' => $this->code_sale_condition,
                    'term' => $this->term,
                    'payment_method' => $this->code_payment_method,
                    'currency' => $this->code_currency,
                    'exchange_rate' => $this->exchange_rate,
                    'id_mh_categories' => $this->id_mh_category,
                    'total_taxed_services' => $this->total_taxed_services,
                    'total_exempt_services' => $this->total_exempt_services,
                    'total_exonerated_services' => $this->total_exonerated_services,
                    'total_taxed_merchandise' => $this->total_taxed_merchandise,
                    'total_exempt_merchandise' => $this->total_exempt_merchandise,
                    'total_exonerated_merchandise' => $this->total_exonerated_merchandise,
                    'total_taxed' => $this->total_taxed_services + $this->total_taxed_merchandise,
                    'total_exempt' => $this->total_exempt_services + $this->total_exempt_merchandise,
                    'total_exonerated' => $this->total_exonerated_services + $this->total_exonerated_merchandise,
                    'total_discount' => $this->totalDiscount,
                    'total_net_sale' => $this->subtotal,
                    'total_tax' => $this->totalTax,
                    'total_other_charges' => (($this->otherCharge) ? $this->total_other_charges : 0),
                    'iva_returned' => $this->iva_returned,
                    'total_document' => $this->total + (($this->otherCharge) ? $this->total_other_charges : 0),
                    'balance' => $this->total - PaymentInvoice::where("id_company", Auth::user()->currentTeam->id)->where("id_document", $this->id_document)->sum("mount"),
                    'path' => $this->path,
                    'state_send' => $this->state_send,
                    'answer_mh' => $this->answer_mh,
                    'detail_mh' => $this->detail_mh,
                    'type_doc' => $this->type_doc,
                    'id_branch_office' => $this->id_branch_office,
                    'note' => ($this->note) ? $this->note : null,
                    'id_seller' => ($this->id_seller) ? $this->id_seller : null
                ]);
                $dd = DocumentDetail::where('id_document', $result->id)->get();
                DocumentDetail::where('id_document', $result->id)->delete();
                foreach ($this->allLines as $line) {
                    //dd( $dd );
                    if ($this->type_doc == '11') {
                        $product = Product::find($line['id_product']);
                        $d = $dd->where('id_product', $product->id)->first();
                        if ($d) {
                            $product->update([
                                'stock' => $product->stock + $d->qty_dispatch - $line['qty_dispatch']
                            ]);
                        }
                    }
                    DocumentDetail::create([
                        'id_document' => $result->id,
                        'id_product' => $line['id_product'],
                        'tariff_heading' => $line['tariff_heading'],
                        'code' => $line['cabys'],
                        'qty_dispatch' => $line['qty_dispatch'],
                        'qty' => $line['qty'],
                        'sku' => $line['sku'],
                        'detail' => $line['description'],
                        'price_unid' => $line['price'],
                        'cost_unid' => $line['cost'],
                        'total_amount' => $line['price'] * $line['qty'],
                        'discounts' => $line['discount'],
                        'subtotal' => ($line['price'] * $line['qty']) - $line['discount'],
                        'taxes' => json_encode($line['tax']),
                        'tax_net' => (isset($line['tax']['exoneration']['AmountExoneration'])) ? $line['tax']['mount'] - $line['tax']['exoneration']['AmountExoneration'] : 0,
                        'total_amount_line' => $line['totalLine'],
                    ]);
                }
                DocumentReference::where('id_document', $result->id)->delete();
                if ($this->id_type_reference != '') {
                    DocumentReference::create([
                        'id_document' => $result->id,
                        'code_type_doc' => $this->id_type_reference,
                        'number' => $this->number_reference,
                        'date_issue' => $this->date_reference,
                        'code' => $this->id_reference,
                        'reason' => $this->reason
                    ]);
                }
                if ($this->otherCharge) {
                    DocumentOtherCharge::where('id_document', $result->id)->delete();
                    foreach ($this->allOtherCharges as $other) {
                        DocumentOtherCharge::create([
                            'id_document' => $result->id,
                            'type_document' => $other['id_type_document_other'],
                            'idcard' => $other['idcard_other'],
                            'name' => $other['name_other'],
                            'detail' => $other['detail_other_charge'],
                            'amount' => $other['amount_other']
                        ]);
                    }
                } else {
                    DocumentOtherCharge::where('id_document', $result->id)->delete();
                }
                $this->generatePDF($result->id);
                $this->updateMode = false;
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Documento actualizado con exito', 'refresh' => 0]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. ' . $e->getMessage()]);
        }
        DB::commit();
        $this->dispatchBrowserEvent('documentU_modal_hide', []);
        $this->emit('refreshDocumentTable');
    }
    public function calculate()
    {
        $this->total_exonerated_services = 0;
        $this->total_taxed_services = 0;
        $this->total_exempt_services = 0;

        $this->total_exonerated_merchandise = 0;
        $this->total_taxed_merchandise = 0;
        $this->total_exempt_merchandise = 0;

        foreach ($this->allLines as $line) {
            if ($line['sku'] == 'Spe' || $line['sku'] == 'Sp') {
                if (count($line['tax']) > 0) {
                    if (isset($line['tax']['exoneration']['AmountExoneration'])) {
                        $this->total_exonerated_services += ($line['price'] * $line['qty']);
                    } else {
                        $this->total_taxed_services += ($line['price'] * $line['qty']);
                    }
                } else {
                    $this->total_exempt_services += ($line['price'] * $line['qty']);
                }
            } else {
                if (count($line['tax']) > 0) {
                    if (isset($line['tax']['exoneration']['AmountExoneration'])) {
                        $this->total_exonerated_merchandise += ($line['price'] * $line['qty']);
                    } else {
                        $this->total_taxed_merchandise += ($line['price'] * $line['qty']);
                    }
                } else {
                    $this->total_exempt_merchandise += ($line['price'] * $line['qty']);
                }
            }
        }
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
        $otherCharges = DocumentOtherCharge::leftJoin('type_document_other_charges', 'type_document_other_charges.id', '=', 'document_other_charges.type_document')
            ->where('id_document', $doc->id)
            ->select(
                'document_other_charges.*',
                'type_document_other_charges.type_document as typeDocument'
            )->get();
        $seller = ($doc->id_seller) ? Seller::where('id', $doc->id_seller)->first() : '';
        $data = [
            'title' => $this->typeDoc2($doc->type_doc),
            'document' => $doc,
            'company' => Auth::user()->currentTeam,
            'company_bo' => $bo->name_branch_office,
            'bo' => $bo,
            'client' => $client,
            'reference' => $reference,
            'details' => $details,
            'otherCharges' => $otherCharges,
            'otherCharge' => $this->otherCharge,
            'seller' => $seller
        ];
        $pdf = PDF::loadView('livewire.document.invoicePDF', $data);
        $path = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $doc->key;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $pdf->save($path . '/' . $clave . '.pdf');
    }
    public function addLineOther()
    {
        $this->validate([
            'id_type_document_other' => 'required',
            'idcard_other' => 'required|min:9|max:12',
            'name_other' => 'required',
            'detail_other_charge' => 'required',
            'amount_other' => 'required'
        ]);

        $type_document_other = TypeDocumentOtherCharge::where('id', $this->id_type_document_other)->first();
        $charge = [
            "id_type_document_other" => $this->id_type_document_other,
            "type_document_other" => $type_document_other->type_document,
            'idcard_other' => $this->idcard_other,
            "name_other" => $this->name_other,
            "detail_other_charge" => $this->detail_other_charge,
            "amount_other" => $this->amount_other
        ];
        array_push($this->allOtherCharges, $charge);
        $this->total_other_charges += $this->amount_other;
        //limpia los campos
        $this->id_type_document_other = '';
        $this->idcard_other = '';
        $this->name_other = '';
        $this->detail_other_charge = '';
        $this->amount_other = 0;
    }
    public function deleteLineOther($index)
    {
        $this->total_other_charges -= $this->allOtherCharges[$index]['amount_other'];
        unset($this->allOtherCharges[$index]);
        $this->allOtherCharges = array_values($this->allOtherCharges);
    }
    public function editDiscount($index)
    {
        $this->index = $index;
        $this->price =  $this->allLines[$this->index]['price'];
        $this->qty =  $this->allLines[$this->index]['qty_dispatch'];
        $this->discount = $this->allLines[$this->index]['discountUnid'];
        $this->percent_discount = ($this->discount != 0) ? number_format($this->discount / $this->price * 100, 2) : 0;
    }

    public function changeTax()
    {
        $this->id_exoneration = '';
        $this->exoneration = 0;
        $this->percent_exo = 0;
        $this->tax = 0;
        $this->percent_exo = 0;
        if ($this->id_tax  != '') {
            $tax = Tax::where('taxes.id', $this->id_tax)
                ->leftJoin('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
                ->select('taxes.*', 'taxes_codes.code as taxCode', 'rate_codes.code as rateCode')->first();
            $this->tax = bcdiv((($this->allLines[$this->index]['qty_dispatch'] * bcdiv($this->allLines[$this->index]['price'], '1', 5)) - bcdiv($this->allLines[$this->index]['discount'], '1', 5)) * $tax->rate / 100, '1', 5);
            $this->percent_tax = $tax->rate;
            if ($tax->id_exoneration != null && $tax->id_exoneration != []) {
                $exoneration = Exoneration::where('exonerations.id', $tax->id_exoneration)
                    ->leftJoin('type_document_exonerations', 'type_document_exonerations.id', 'exonerations.id_type_document_exoneration')
                    ->select('exonerations.*', 'type_document_exonerations.code', 'type_document_exonerations.document')->first();
                $this->id_exoneration = $exoneration->id;
                $this->percent_exo = $exoneration->exemption_percentage;
                $this->exoneration = bcdiv((($this->allLines[$this->index]['qty_dispatch'] * bcdiv($this->allLines[$this->index]['price'], '1', 5)) - bcdiv($this->allLines[$this->index]['discount'], '1', 5)) * $exoneration->exemption_percentage / 100, '1', 5);
            } else {
                $this->id_exoneration = '';
                $this->exoneration = 0;
                $this->percent_exo = 0;
            }
        } else {
            $this->id_tax = '';
            $this->id_exoneration = '';
            $this->exoneration = 0;
            $this->percent_exo = 0;
            $this->tax = 0;
            $this->percent_tax = 0;
        }
    }
    public function changeExo()
    {
        if ($this->id_exoneration != '') {
            $exoneration = Exoneration::where('exonerations.id', $this->tax->id_exoneration)
                ->leftJoin('type_document_exonerations', 'type_document_exonerations.id', 'exonerations.id_type_document_exoneration')
                ->select('exonerations.*', 'type_document_exonerations.code', 'type_document_exonerations.document')->first();
            $this->percent_exo = $exoneration->exemption_percentage;
            $this->exoneration = bcdiv((($this->allLines[$this->index]['qty_dispatch'] * bcdiv($this->allLines[$this->index]['price'], '1', 5)) - bcdiv($this->allLines[$this->index]['discount'], '1', 5)) * $exoneration->exemption_percentage / 100, '1', 5);
        } else {
            $this->id_exoneration = '';
            $this->percent_exo = 0;
            $this->exoneration = 0;
        }
    }
    public function selectDiscount()
    {
        if ($this->id_discount == '') {
            $this->allLines[$this->index]['discount'] = 0;
        } else {
            $this->discount = Discount::find($this->id_discount)->first()->amount;
        }
    }
    public function changeDiscount()
    {
        if ($this->price != '' && $this->qty != '' && $this->discount != 0 && $this->discount != '' && ($this->qty * $this->price) != 0) {
            $this->percent_discount = number_format($this->discount / $this->price * 100, 2);
        }
        if ($this->discount == 0) {
            $this->percent_discount = 0;
        }
    }
    public function changeDiscountP()
    {
        $this->discount = number_format($this->percent_discount * $this->price / 100, 5);
    }
    public function saveDiscount()
    {
        $this->validate([
            'discount' => 'required',
            'percent_discount' => 'required',
        ]);
        $this->allLines[$this->index]['discountUnid'] = $this->discount;
        $this->dispatchBrowserEvent('change_discount_modal_hide', []);
        $this->calculateLines();
        $this->discount = 0;
        $this->price = 0;
        $this->qty = 0;
        $this->calculateLines();
    }
    public function saveTax()
    {
        $taxLine = [];
        if ($this->id_tax != '') {
            $tax = Tax::where('taxes.id', $this->id_tax)
                ->leftJoin('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
                ->select('taxes.*', 'taxes_codes.code as taxCode', 'rate_codes.code as rateCode')->first();
            if ($this->id_exoneration != '') {
                $exoneration = Exoneration::where('exonerations.id', $tax->id_exoneration)
                    ->leftJoin('type_document_exonerations', 'type_document_exonerations.id', 'exonerations.id_type_document_exoneration')
                    ->select('exonerations.*', 'type_document_exonerations.code', 'type_document_exonerations.document')->first();
            }
            $taxLine = array(
                "id" => (string)$tax->id,
                "code" => (string)$tax->taxCode,
                "rate_code" => (string)$tax->rateCode,
                "rate" => (string)$tax->rate,
                "mount" => $taxAmount =  bcdiv((($this->allLines[$this->index]['qty_dispatch'] * bcdiv($this->allLines[$this->index]['price'], '1', 5)) - bcdiv($this->allLines[$this->index]['discount'], '1', 5)) * $tax->rate / 100, '1', 5),
                "exoneration" => (isset($exoneration)) ? array(
                    "id" => (string)$exoneration->id,
                    "DocumentType" => (string)$exoneration->code,
                    "DocumentNumber" => (string)$exoneration->document_number,
                    "InstitucionalName" => (string)$exoneration->institutional_name,
                    "EmisionDate" => (string)$exoneration->date,
                    "PercentageExoneration" => (string)$exoneration->exemption_percentage,
                    "AmountExoneration" => $exonerationAmount = bcdiv((($this->allLines[$this->index]['qty_dispatch'] * bcdiv($this->allLines[$this->index]['price'], '1', 5)) - bcdiv($this->allLines[$this->index]['discount'], '1', 5)) * $exoneration->exemption_percentage / 100, '1', 5)
                ) : "",
            );
        }

        $this->allLines[$this->index]['tax'] = $taxLine;
        $this->dispatchBrowserEvent('change_tax_modal_hide', []);
        $this->calculateLines();
    }
    public function changeQty($index, $qty)
    {
        $this->allLines[$index]['qty_dispatch'] = $qty;
        $this->allLines[$index]['qty'] = $qty;
        $this->calculateLines();
    }
    public function changePrice($index, $price)
    {
        $this->allLines[$index]['price'] = ($price == '') ? 0 : $price;
        if ($this->allLines[$index]['price'] == 0) {
            $this->allLines[$index]['discountUnid'] = 0;
        }
        $this->calculateLines();
    }
    public function changeCost($index, $cost)
    {
        $this->allLines[$index]['cost'] = ($cost == '') ? 0 : $cost;
        if ($this->allLines[$index]['cost'] == 0) {
            $this->allLines[$index]['discountUnid'] = 0;
        }
        $this->calculateLines();
    }
    public function calculateLines()
    {
        $this->subtotal = 0;
        $this->total_cost = 0;
        $this->totalDiscount = 0;
        $this->totalTax = 0;
        $this->totalExoneration = 0;
        $this->total = 0;
        $this->total_pagar =  0;
        foreach ($this->allLines as $index => $line) {
            $this->allLines[$index]['discount'] = $this->allLines[$index]['discountUnid'] * $this->allLines[$index]['qty_dispatch'];
            if (isset($this->allLines[$index]['tax']['id'])) {
                $tax = Tax::where('taxes.id', $this->allLines[$index]['tax']['id'])
                    ->leftJoin('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                    ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
                    ->select('taxes.*', 'taxes_codes.code as taxCode', 'rate_codes.code as rateCode')->first();
                $this->allLines[$index]['tax']['mount'] =  bcdiv((($this->allLines[$index]['qty_dispatch'] * $this->allLines[$index]['price']) - $this->allLines[$index]['discount']) * $this->allLines[$index]['tax']['rate'] / 100, '1', 5);
                if ($this->allLines[$index]['tax']['exoneration'] != "") {
                    $exoneration = Exoneration::where('exonerations.id', $this->allLines[$index]['tax']['exoneration']['id'])
                        ->leftJoin('type_document_exonerations', 'type_document_exonerations.id', 'exonerations.id_type_document_exoneration')
                        ->select('exonerations.*', 'type_document_exonerations.code', 'type_document_exonerations.document')->first();
                    $this->allLines[$index]['tax']['exoneration']['AmountExoneration'] =  bcdiv((($this->allLines[$index]['qty_dispatch'] * bcdiv($this->allLines[$index]['price'], '1', 5)) - bcdiv($this->allLines[$index]['discount'], '1', 5)) * $this->allLines[$index]['tax']['exoneration']['PercentageExoneration'] / 100, '1', 5);
                }
            }

            $taxAmount = ((isset($this->allLines[$index]['tax']) && $this->allLines[$index]['tax'] != []) ? $this->allLines[$index]['tax']['mount'] : 0);
            $exonerationAmount = ((isset($this->allLines[$index]['tax']['exoneration']) && $this->allLines[$index]['tax']['exoneration'] != '') ? $this->allLines[$index]['tax']['exoneration']['AmountExoneration'] : 0);
            $this->allLines[$index]['totalLine'] = bcdiv(($this->allLines[$index]['qty_dispatch'] * $this->allLines[$index]['price']) - $this->allLines[$index]['discount'] + $taxAmount - $exonerationAmount, '1', 5);
            $this->subtotal += bcdiv(($this->allLines[$index]['qty_dispatch'] * $this->allLines[$index]['price']), '1', 5);
            $this->total_cost += ($this->allLines[$index]['qty_dispatch'] * $this->allLines[$index]['cost']);
            $this->totalDiscount += $this->allLines[$index]['discount'];

            $this->totalTax += ((isset($this->allLines[$index]) && ($this->allLines[$index]['tax'] != '' && count($this->allLines[$index]['tax']) > 0)) ? $this->allLines[$index]['tax']['mount'] : 0);
            $this->totalExoneration += ((isset($this->allLines[$index]['tax']['exoneration']) && $this->allLines[$index]['tax']['exoneration'] != '') ? $this->allLines[$index]['tax']['exoneration']['AmountExoneration'] : 0);
            $this->total =  $this->subtotal - $this->totalDiscount + $this->totalTax - $this->totalExoneration;
            $this->total_pagar =  ($this->total + $this->total_other_charges);
        }
    }
}
