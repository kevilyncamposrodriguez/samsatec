<?php

namespace App\Http\Livewire\Voucher;

use App\Hacienda\Firmador;
use App\Models\BranchOffice;
use App\Models\Buyer;
use App\Models\Cabys;
use App\Models\CompaniesEconomicActivities;
use App\Models\Consecutive;
use App\Models\Count;
use App\Models\Currencies;
use App\Models\Discount;
use App\Models\Exoneration;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentMethods;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Reference;
use App\Models\ReferenceTypeDocument;
use App\Models\SaleConditions;
use App\Models\Skuse;
use App\Models\Tax;
use App\Models\ExpenseDetail;
use App\Models\ExpenseOtherCharge;
use App\Models\ExpenseReference;
use App\Models\MhCategories;
use App\Models\PriceListsLists;
use App\Models\TeamUser;
use App\Models\Terminal;
use App\Models\TypeDocumentOtherCharge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use LDAP\Result;
use PDF;

class VouchersComponent extends Component
{
    public $path, $total_taxed_services, $total_exempt_services, $total_taxed_merchandise, $total_exempt_merchandise;
    public $id_document, $detail, $cc_mail, $qty_dispatch, $dateDoc, $id_ea, $keyDoc, $payment_id, $updateMode, $other_view, $total_exonerated_services, $address, $credit_term, $type_purchase, $name_buyer, $name_currency, $priority, $type_document, $id_branch_office, $id_expense, $key, $consecutive, $date, $sale_condition, $payment_method, $currency, $exchange_rate, $delivery_date, $sku, $id_tax, $id_type_document_other, $idcard_other,
        $pendingCE = 0, $subtotal, $discount = 0, $total, $totalDiscount, $totalTax, $totalExoneration, $code_currency, $id_mh_category, $term, $type_doc, $name_other, $detail_other_charge, $amount_other,
        $number_ea, $otherCharge, $id_terminal, $id_type_reference, $name_provider, $id_card, $name, $type_line, $reference, $mount = 0, $observations,
        $phone, $mail, $id_buyer, $price = 0, $qty = 1, $cost = 0, $name_product, $code_payment_method, $code_sale_condition, $pending_amount = 0,
        $id_product, $details = [], $tax, $exoneration, $tariff_heading = 0, $total_exonerated_merchandise = 0,
        $total_other_charges = 0, $state_send = 'Sin enviar', $iva_returned = 0, $reason, $id_reference, $date_reference, $number_reference,
        $answer_mh = 'Ninguna', $detail_mh = 'Ninguno', $note = '', $is_orden = false, $id_count = '', $id_countP = '', $count, $countP, $isUpdatePay = false, $possible_deliver_date;
    public $allPayments = [], $allLines = [], $allPriceList = [], $allProducts, $gasper = false,
        $allCurrencies, $allMHCategories, $allSaleConditions, $allBuyers, $allEconomicActivities,
        $allBranchOffices, $allTerminals, $allSkuses, $allTaxes, $allTypeReferences, $allOtherCharges = [], $allTypeDocumentOtherCharges = [],
        $allProviders, $allPaymentMethods, $allReferences, $allCounts, $allCountsP = [], $allCabys, $cabys, $description, $id_provider;

    protected $listeners = ['viewVoucher', 'editVoucher', 'generateFEC', 'generateFIC'];
    public $name_e, $phone_e, $mail_e, $address_e, $date_view, $consecutive_view, $countsUpdate, $productsUpdate;
    public function mount()
    {
        $this->gasper = false;
        $this->allCountsP = Count::where("counts.id_company", Auth::user()->currentTeam->id)
            ->whereIn('counts.id_count_primary', ['34', '35'])->get();
        $this->allCounts = Count::whereIn("id_count_primary", [25, 26])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->code_payment_method = '01';
        $this->code_sale_condition = '01';
        $this->allCabys = Cabys::all()->take(50);
        $this->possible_deliver_date = date('Y-m-d');
        $this->priority = 1;
        $this->allMHCategories = MhCategories::all();
        $this->allTypeReferences = ReferenceTypeDocument::all();
        $this->allReferences = Reference::all();
        $this->allTaxes = Tax::where("taxes.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allSkuses = Skuse::all();
        $this->allPaymentMethods = PaymentMethods::all();
        $this->allSaleConditions = SaleConditions::all();
        $this->allBranchOffices = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->id_branch_office = (isset(TeamUser::getUserTeam()->bo)) ? TeamUser::getUserTeam()->bo : $this->allBranchOffices[0]->id;
        $this->allTerminals = Terminal::where('id_branch_office', $this->id_branch_office)->get();
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->id_terminal = (Terminal::where("terminals.id_branch_office", $this->id_branch_office)->first())->id;
        } else {
            $this->id_terminal = (isset(TeamUser::getUserTeam()->terminal)) ? TeamUser::getUserTeam()->terminal : ((Terminal::where("terminals.id_branch_office", $this->id_branch_office)->first())->id);
        }
        $this->allBuyers = Buyer::where("buyers.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allCurrencies = Currencies::all();
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where("products.active", '1')->get();
        } else {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count)
                ->where("products.active", '1')->get();
        }
        $this->allEconomicActivities = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select('economic_activities.*', 'companies_economic_activities.*')->get();
        $this->number_ea = $this->allEconomicActivities->first()->number;
    }

    public function render()
    {
        $this->allProviders = Provider::where("providers.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->allEconomicActivities = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select('economic_activities.*', 'companies_economic_activities.*')->get();
        if ($count = $this->allCounts->where('name', $this->count)->first()) {
            $this->id_count = $count->id;
        } else {
            $this->id_count = '';
        }
        if ($this->cabys != null && $this->cabys != '') {
            $this->allCabys = Cabys::where('codigo', 'like', '%' . $this->cabys . '%')->orwhere('descripcion', 'like', '%' . $this->cabys . '%')->get()->take(100);
        }
        return view('livewire.voucher.vouchers-component');
    }

    public function cambio()
    {
        $expenses = Expense::all();
        foreach ($expenses as $key => $exp) {
            if (substr($exp->consecutive, 0, 2) == "OC" || substr($exp->consecutive, 0, 3) == "FCI") {
                $exp->update([
                    "consecutive_real" => $exp->consecutive
                ]);
            } else {
                $exp->update([
                    "consecutive_real" => substr($exp->key, 21, 20)
                ]);
            }
        }
    }
    public function changeT()
    {
        $this->getConsecutive();
    }
    public function changeTe()
    {
        $this->getConsecutive();
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
    }
    public function changeBO()
    {
        $this->getConsecutive($this->type_doc);
    }
    public function changeProvider($name)
    {

        if ($name != "" && $provider = $this->allProviders->where('name_provider', $name)->first()) {
            $this->id_provider = $provider->id;
            $this->name_provider = $provider->name_provider;

            if ($this->id_provider != '') {
                $provider = Provider::where('name_provider', $this->name_provider)
                    ->leftJoin('sale_conditions', 'sale_conditions.id', '=', 'providers.id_sale_condition')
                    ->leftJoin('currencies', 'currencies.id', '=', 'providers.id_currency')
                    ->leftJoin('payment_methods', 'payment_methods.id', '=', 'providers.id_payment_method')
                    ->select('providers.*', 'sale_conditions.code as code_sale_condition', 'currencies.code as code_currency', 'payment_methods.code as code_payment_method')->first();

                $this->code_sale_condition = $provider->code_sale_condition;
                $currency = Currencies::find($provider->id_currency);
                $this->code_currency = $currency->code;
                $this->name_currency = $currency->code . ' - ' . $currency->currency;
                $this->code_payment_method = $provider->code_payment_method;
                $this->term = $provider->time;
                $this->allBuyers = Buyer::where('id_company', Auth::user()->currentTeam->id)->where('id_provider', $this->id_provider)->get();
                $this->name_buyer = '';
            }
        }
    }
    public function changeCurrency()
    {
        if ($this->code_currency == 'USD') {
            $this->exchange_rate = session('sale');
        } else {
            $this->exchange_rate = 1;
        }
    }
    public function editExpense($id, $categoryMH, $typePurchase, $idCount)
    {
        $this->id_expense = $id;
        $this->id_mh_category = $categoryMH;
        $this->type_purchase = $typePurchase;
        $this->id_count = $idCount;
    }
    public function changeCount($value)
    {
        $this->id_count = $value;
    }


    public function changeCountC($id, $value)
    {
        DB::beginTransaction();
        try {
            $result = ExpenseDetail::find($id);
            $result->update([
                'id_count' => ($value == '') ? null : $value
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        }
        DB::commit();
    }
    public function changeCodeD($value, $id)
    {
        DB::beginTransaction();
        try {
            $result = ExpenseDetail::find($id);
            $this->id_product = $result->id_product;
            if ($result->id_product != null) {
                $product = Product::find($result->id_product);
                $product->update([
                    'stock' => ($product->stock - $result->qty)
                ]);
            }
            if ($value != "") {
                $product = Product::find($value);
                $product->update([
                    'stock' => ($product->stock + $result->qty)
                ]);
            }
            $result->update([
                'id_product' => ($value == '') ? null : $value
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
        }
        DB::commit();
    }
    public function viewVoucher($key, $id)
    {
        $this->cleanInputs();
        $this->id_expense = $id;
        $expense = Expense::where('key', '=', $key)->first();
        $path = 'files/recibidos/procesados/' . Auth::user()->currentTeam->id_card . '/' . $key . '/' . $key . '.xml';
        $provider = Provider::getProvider($expense->id_provider);
        $this->number_ea = $expense->e_a;
        $this->key = $key;
        $this->id_buyer = $expense->id_buyer;
        $this->consecutive = $expense->consecutive_real;
        $this->type_doc = (substr($this->consecutive, 0, 2) == 'OC') ? 3 : ((substr($this->consecutive, 0, 2) == 'FC') ? 2 : 1);
        $this->type_document = ($this->type_doc == 1) ? 'Factura Electronica de Compra' : (($this->type_doc == 2) ? 'Factura Interna de Compra' : 'Orden de Compra');
        $this->id_card = $provider->id_card;
        $this->name = $provider->name_provider;
        $this->phone = $provider->phone;
        $this->mail = $provider->emails;
        $this->address = $provider->province . ', ' . $provider->canton . ', ' . $provider->district . ', ' . $provider->other_signs;
        $this->date  = $expense->date_issue;
        $this->sale_condition = SaleConditions::where("sale_conditions.code", "=", $expense->sale_condition)->first();
        $this->sale_condition = $this->sale_condition->sale_condition;
        $this->payment_method = PaymentMethods::where("payment_methods.code", "=", $expense->payment_method)->first();
        $this->payment_method = $this->payment_method->payment_method;
        $this->credit_term = $expense->credit;
        $this->currency = $expense->currency;
        $this->number_ea = $expense->e_a;
        $this->exchange_rate = $expense->exchange_rate;
        $this->note = $expense->note;
        $this->gasper = $expense->gasper;
        $this->allTypeDocumentOtherCharges = TypeDocumentOtherCharge::all();
        $this->details = ExpenseDetail::where('id_expense', '=', $expense->id)->get();
        if ($reference = ExpenseReference::where("id_expense", $expense->id)->first()) {
            $this->id_type_reference =  $reference->code_type_doc;
            $this->number_reference = $reference->number;
            $this->date_reference = str_replace(' ', 'T', $reference->date_issue);
            $this->id_reference =  str_pad($reference->code, 2, "0", STR_PAD_LEFT);
            $this->reason =  $reference->reason;
        }
        $otherCharges = ExpenseOtherCharge::join('type_document_other_charges', 'type_document_other_charges.id', '=', 'expense_other_charges.type_document')
            ->where('id_expense', $expense->id)
            ->select(
                'expense_other_charges.*',
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
        $this->otherCharge = (count($this->allOtherCharges) > 0) ? 1 : 0;
        $this->id_count = $expense->id_count;
        $this->id_mh_category = $expense->id_mh_categories;
        //resumen de facturacion
        $this->subtotal = $expense->total_net_sale;
        $this->discount = $expense->total_discount;
        $this->tax = $expense->total_tax;
        $this->exoneration = $expense->total_exonerated;
        $this->total  = $expense->total_document;
        $this->pending_amount = $expense->pending_amount;
        $this->other_view  = $expense->total_other_charges;
        $this->allPayments = Payment::where("payments.id_expense", $this->id_expense)
            ->leftJoin('counts', 'payments.id_count', '=', 'counts.id')
            ->leftJoin('expenses', 'payments.id_expense', '=', 'expenses.id')
            ->select('payments.*', 'expenses.consecutive', 'counts.name as count')->get();

        $this->type_line = null;
        foreach ($this->details as $key => $detail) {
            $product = Product::find($detail->id_product);
            $count = Count::find($detail->id_count);
            if ($detail->id_product) {
                $this->productsUpdate[$key] = $product->description;
            }
            if ($detail->id_count) {
                $this->countsUpdate[$key] = (isset($count->name)) ? ($count->name) : '';
            }
            $this->type_line[$key] = ($detail->type) ? "Gasto" : "Compra";
        }
    }
    public function getIdExpense()
    {
        return '' . $this->id_expense;
    }
    public function saveChangeExpense()
    {
        DB::beginTransaction();
        try {
            if ($this->id_expense) {
                $result = Expense::find($this->id_expense);
                $result->update([
                    'id_count' => $this->id_count,
                    'id_mh_category' => $this->id_mh_category,
                    'type_purchase' => $this->type_purchase
                ]);
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Datos actualizados con exito']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al actualizar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al actualizar la informacion']);
        }
        DB::commit();
    }
    public function storePay()
    {
        $this->validate([
            'date' => 'required',
            'id_expense' => 'required',
            'id_countP' => 'required',
            'reference' => 'required',
            'mount' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            Payment::create([
                'date' => $this->date,
                'id_company' => Auth::user()->currentTeam->id,
                'id_expense' => $this->id_expense,
                'id_count' => $this->id_countP,
                'reference' => $this->reference,
                'mount' => $this->mount,
                'observations' => $this->observations
            ]);
            $result = Expense::find($this->id_expense);
            $result->update([
                'pending_amount' => $result->pending_amount - $this->mount
            ]);
            $this->dispatchBrowserEvent('payment_modal_hide', []);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
    }
    public function editPay($id)
    {
        try {
            $this->updateMode = true;
            $result = Payment::where('id', $id)->first();
            $this->payment_id = $id;
            $this->id_countP = $result->id_count;
            $this->id_expense = $result->id_expense;
            $this->reference = $result->reference;
            $this->mount = $result->mount;
            $this->observations = $result->observations;
            $this->date = str_replace(" ", "T", $result->date);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->resetInputFields();
            $this->dispatchBrowserEvent('paymentU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->resetInputFields();
            $this->dispatchBrowserEvent('paymentU_modal_hide', []);
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Datos no encontrados']);
        }
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function resetInputFields()
    {
    }

    public function updatePay()
    {
        $this->validate([
            'date' => 'required',
            'id_expense' => 'required',
            'id_count' => 'required',
            'reference' => 'required',
            'mount' => 'required',
            'observations' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($this->payment_id) {
                $pay = Payment::find($this->payment_id);
                $result = Expense::find($this->id_expense);
                $result->update([
                    'pending_amount' => $result->pending_amount + $pay->mount - $this->mount
                ]);
                $pay->update([
                    'date' => $this->date,
                    'id_expense' => $this->id_expense,
                    'id_count' => $this->id_countP,
                    'reference' => $this->reference,
                    'mount' => $this->mount,
                    'observations' => $this->observations
                ]);

                $this->updateMode = false;
                $this->dispatchBrowserEvent('paymentU_modal_hide', []);
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
    }

    public function deletePay($id, $mount)
    {
        try {
            if ($id) {
                Payment::where('id', $id)->delete();
                $result = Expense::find($this->id_expense);
                $result->update([
                    'pending_amount' => $result->pending_amount + $mount
                ]);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $this->dispatchBrowserEvent('errorData', ['messageData' => 'El Objeto no se puede eliminar porque hay datos ligados. AL eliminar podría generar errores y perdida de infomación.']);
        }
    }
    public function generateFEC($id)
    {
        $this->newPurchase('1');
        $this->chargeData($id);
        $this->getConsecutive();
        $this->id_type_reference = '99';
        $this->reason = 'Orden de compra';
        $this->id_reference = '4';
    }
    public function generateFIC($id)
    {
        $this->newPurchase('2');
        $this->chargeData($id);
        $this->getConsecutive();
        $this->id_type_reference = '99';
        $this->reason = 'Orden de compra';
        $this->id_reference = '4';
        $this->pendingCE = 0;
    }
    public function chargeData($id)
    {
        try {
            $this->updateMode = true;
            $doc = Expense::where('id', $id)->first();
            $this->id_expense = $doc->id;
            $this->date_reference = str_replace(' ', 'T', $doc->created_at);
            $this->number_reference = (substr($doc->consecutive, 0, 2) == 'OC') ? $doc->consecutive : ((substr($doc->consecutive, 0, 2) == 'FC') ? $doc->consecutive : substr($doc->key, 21, 20));
            $this->id_provider = $doc->id_provider;
            $this->changeProvider(Provider::where('id', $this->id_provider)->first()->name_provider);
            $this->keyDoc = $doc->key;
            $this->note = $doc->note;
            $this->id_expense = $doc->id;
            $this->possible_deliver_date = substr($doc->possible_deliver_date, 0, 10);
            $this->priority = $doc->priority;
            $this->code_currency = $doc->currency;
            $this->code_payment_method = $doc->payment_method;
            $this->id_mh_category = $doc->id_mh_categories;
            $this->code_sale_condition = $doc->sale_condition;
            $this->id_buyer = $doc->id_buyer;
            $this->term = $doc->term;
            $this->exchange_rate = $doc->exchange_rate;
            $this->id_ea = $doc->e_a;
            $this->gasper = $doc->gasper;
            $this->id_buyer = $doc->id_buyer;
            $this->id_branch_office = $doc->id_branch_office;
            $this->dateDoc = $doc->date_issue;
            $this->subtotal = $doc->total_net_sale;
            $this->totalDiscount = $doc->total_discount;
            $this->totalTax = $doc->total_tax;
            $this->totalExoneration = $doc->total_exonerated;
            $this->total_other_charges = $doc->total_other_charges;
            $this->total = ($doc->total_document - $doc->total_other_charges);

            $details = ExpenseDetail::where('id_expense', $doc->id)->get();
            foreach ($details as $detail) {
                $line = [
                    "cabys" => $detail->cabys,
                    'tariff_heading' => $detail->tariff_heading,
                    "description" => $detail->detail,
                    "sku" => $detail->sku,
                    "qty" => $detail->qty,
                    "qty_dispatch" => $detail->qty_dispatch,
                    "cost" => $detail->cost_unid,
                    "price" => $detail->price,
                    "discount" => $detail->discounts,
                    "tax" => json_decode($detail->taxes, true),
                    "totalLine" => $detail->total_amount_line,
                    "id_product" => $detail->id_product,
                    "id_count" => $detail->id_count,
                    "type_line" => ($detail->type) ? "Gasto" : "Compra"
                ];
                array_push($this->allLines, $line);
            }
            $otherCharges = ExpenseOtherCharge::join('type_document_other_charges', 'type_document_other_charges.id', '=', 'expense_other_charges.type_document')
                ->where('id_expense', $doc->id)
                ->select(
                    'expense_other_charges.*',
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
            if ($reference = ExpenseReference::where("id_expense", $doc->id)->first()) {
                $this->id_type_reference =  $reference->code_type_doc;
                $this->number_reference = $reference->number;
                $this->date_reference = str_replace(' ', 'T', $reference->date_issue);
                $this->id_reference =  str_pad($reference->code, 2, "0", STR_PAD_LEFT);
                $this->reason =  $reference->reason;
            }
            $this->subtotal = $doc->total_net_sale;
            $this->totalDiscount = $doc->total_discount;
            $this->totalTax = $doc->total_tax;
            $this->totalExoneration = $doc->total_exonerated;
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
    public function newPurchase($type)
    {
        $this->cleanInputs();
        $this->allEconomicActivities = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select('economic_activities.*', 'companies_economic_activities.*')->get();
        $this->number_ea = $this->allEconomicActivities->first()->number;
        $this->type_doc = $type;
        if ($type == '3') {
            $this->is_orden = true;
        } else {
            $this->is_orden = false;
        }
        $this->sku = 'Spe';
        $this->id_terminal = Terminal::where("terminals.id_branch_office", "=", $this->id_branch_office)->first()->id;
        $this->allTypeDocumentOtherCharges = TypeDocumentOtherCharge::all();
        $this->getConsecutive();
        $this->type_document = ($type == 1) ? 'Factura Electronica de Compra' : (($type == 2) ? 'Factura Interna de Compra' : 'Orden de Compra');
    }
    public function cleanInputs()
    {
        $this->allLines = [];
        $this->allEconomicActivities = CompaniesEconomicActivities::where("companies_economic_activities.id_company", "=", Auth::user()->currentTeam->id)
            ->leftJoin('economic_activities', 'economic_activities.id', '=', 'companies_economic_activities.id_economic_activity')
            ->select('economic_activities.*', 'companies_economic_activities.*')->get();
        $this->number_ea = $this->allEconomicActivities->first()->number;
        $this->id_mh_category = $this->allMHCategories->first()->id;
        $this->note = '';
        $this->gasper = false;
        $this->countsUpdate = [];
        $this->type_line = 'Compra';
        $this->otherCharge = 0;
        $this->allOtherCharges = [];
        $this->id_provider = '';
        $this->name_provider = '';
        $this->total_other_charges = 0;
        $this->count = '';
        $this->type_doc = '';
        $this->code_currency = 'CRC';
        $this->code_payment_method = '01';
        $this->id_mh_category = '01';
        $this->code_sale_condition = '01';
        $this->priority = 1;
        $this->possible_deliver_date = date('Y-m-d');
        $this->price = 0;
        $this->cost = 0;
        $this->qty = 1;
        $this->qty_dispatch = 1;
        $this->term = 1;
        $this->exchange_rate = 1;
        $this->consecutive = '';
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
        $this->pendingCE = 0;
    }
    public function getConsecutive()
    {
        $cons = Consecutive::where('consecutives.id_branch_offices', $this->id_branch_office)->first();
        $bo = BranchOffice::where('branch_offices.id', $this->id_branch_office)->first();
        switch ($this->type_doc) {
            case '1':
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . "0000108" . str_pad($cons->c_fc, 10, "0", STR_PAD_LEFT);
                break;
            case '2':
                $this->consecutive = 'FCI-' . str_pad($cons->c_fci, 10, "0", STR_PAD_LEFT);
                break;
            case '3':
                $this->consecutive = 'OC-' . str_pad($cons->c_oc, 10, "0", STR_PAD_LEFT);
                break;
            default:
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . "0000108" . str_pad($cons->c_fc, 10, "0", STR_PAD_LEFT);
        }
    }
    public function nextConsecutive($type, $bo)
    {
        $cons = Consecutive::where('consecutives.id_branch_offices', $this->id_branch_office)->first();
        $consecutive = array();
        switch ($type) {
            case '1':
                $consecutive["c_fc"] = ($cons->c_fc + 1);
                break;
            case '2':
                $consecutive["c_fci"] = ($cons->c_fci + 1);
                break;
            case '3':
                $consecutive["c_oc"] = ($cons->c_oc + 1);
                break;
            default:
                $consecutive["c_fc"] = ($cons->c_fc + 1);
        }
        Consecutive::where('id_branch_offices', '=', $bo)->update($consecutive);
    }
    public function changeProduct()
    {
        if ($this->name_product != '') {
            $product = $this->allProducts->where('description', $this->name_product)->first();
            if ($product) {
                $this->id_product = $product->id;
                if ($product->id_discount != '') {
                    $discount = Discount::find($product->id_discount);
                    $this->discount = $discount->amount;
                } else {
                    $this->discount = 0;
                }
                $this->cabys = $product->cabys;
                $this->detail = $product->description;
                $this->price =  $product->price_unid;
                $this->cost = $product->cost_unid;
                $this->sku = Skuse::where('skuses.id', '=', $product->id_sku)->first()->symbol;
                $this->id_tax = ($product->ids_taxes == "" || $product->ids_taxes == []) ? '' : json_decode($product->ids_taxes, true)[0];
                if ($this->type_doc == '11') {
                    $this->id_tax = '';
                }
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
    public function editVoucher($id)
    {
        try {
            $this->updateMode = true;
            $doc = Expense::where('id', $id)->first();
            $this->id_expense = $doc->id;
            $this->id_provider = $doc->id_provider;
            $this->changeProvider(Provider::where('id', $this->id_provider)->first()->name_provider);
            $this->keyDoc = $doc->key;
            $this->note = $doc->note;
            $this->id_document = $doc->id;
            $this->date_reference = $doc->date_issue;
            $this->possible_deliver_date = substr($doc->possible_deliver_date, 0, 10);
            $this->priority = $doc->priority;
            $this->code_currency = $doc->currency;
            $this->type_doc = str_pad($doc->type_doc, 2, "0", STR_PAD_LEFT);
            $this->code_payment_method = $doc->payment_method;
            $this->id_mh_category = $doc->id_mh_categories;
            $this->code_sale_condition = $doc->sale_condition;
            $this->id_buyer = $doc->id_buyer;
            $this->term = $doc->term;
            $this->exchange_rate = $doc->exchange_rate;
            $this->consecutive = $doc->consecutive;
            $this->id_ea = $doc->e_a;
            $this->gasper = $doc->gasper;
            $this->id_buyer = $doc->id_buyer;
            $this->id_branch_office = $doc->id_branch_office;
            $this->dateDoc = $doc->date_issue;
            $this->subtotal = $doc->total_net_sale;
            $this->totalDiscount = $doc->total_discount;
            $this->totalTax = $doc->total_tax;
            $this->totalExoneration = $doc->total_exonerated;
            $this->pendingCE = $doc->pendingCE;
            $this->total_other_charges = $doc->total_other_charges;
            $this->total = ($doc->total_document - $doc->total_other_charges);
            $details = ExpenseDetail::where('id_expense', $doc->id)->get();
            foreach ($details as $index => $detail) {
                $line = [
                    "cabys" => $detail->cabys,
                    'tariff_heading' => $detail->tariff_heading,
                    "description" => $detail->detail,
                    "sku" => $detail->sku,
                    "qty" => $detail->qty,
                    "qty_dispatch" => $detail->qty_dispatch,
                    "cost" => $detail->cost_unid,
                    "price" => $detail->price,
                    "discount" => $detail->discounts,
                    "tax" => json_decode($detail->taxes, true),
                    "totalLine" => $detail->total_amount_line,
                    "id_product" => $detail->id_product,
                    "id_count" => $detail->id_count,
                    "type_line" => ($detail->type) ? "Gasto" : "Compra"
                ];
                array_push($this->allLines, $line);
            }

            $otherCharges = ExpenseOtherCharge::join('type_document_other_charges', 'type_document_other_charges.id', '=', 'expense_other_charges.type_document')
                ->where('id_expense', $doc->id)
                ->select(
                    'expense_other_charges.*',
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
            if ($reference = ExpenseReference::where("id_expense", $doc->id)->first()) {
                $this->id_type_reference =  $reference->code_type_doc;
                $this->number_reference = $reference->number;
                $this->date_reference = str_replace(' ', 'T', $reference->date_issue);
                $this->id_reference =  str_pad($reference->code, 2, "0", STR_PAD_LEFT);
                $this->reason =  $reference->reason;
            }
            $this->type_doc = (substr($this->consecutive, 0, 2) == 'OC') ? 3 : ((substr($this->consecutive, 0, 2) == 'FC') ? 2 : 1);
            $this->type_document = ($this->type_doc == 1) ? 'Factura Electronica de Compra' : (($this->type_doc == 2) ? 'Factura Interna de Compra' : 'Orden de Compra');
            $this->subtotal = $doc->total_net_sale;
            $this->totalDiscount = $doc->total_discount;
            $this->totalTax = $doc->total_tax;
            $this->totalExoneration = $doc->total_exonerated;
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
    public function addLine()
    {
        $this->validate([
            'id_product' => ($this->type_line == 'Compra') ? 'required' : '',
            'description' => ($this->type_line != 'Compra') ? 'required' : '',
            'id_count' => ($this->type_line != 'Compra' && Auth::user()->currentTeam->plan_id != '1') ? 'required' : '',
            'cabys' => ($this->type_line == 'Compra' || $this->type_doc == '2') ? '' : 'required',
            'tariff_heading' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'sku' => 'required',
            'discount' => 'required'
        ]);
        $taxLine = [];
        $tax = [];
        $exonerationAmount = 0;
        $taxAmount = 0;
        if ($this->id_tax != '') {
            $tax = Tax::where('taxes.id', $this->id_tax)
                ->leftJoin('taxes_codes', 'taxes_codes.id', '=', 'taxes.id_taxes_code')
                ->leftJoin('rate_codes', 'rate_codes.id', '=', 'taxes.id_rate_code')
                ->select('taxes.*', 'taxes_codes.code as taxCode', 'rate_codes.code as rateCode')->first();

            if ($tax->id_exoneration != null) {
                $exoneration = Exoneration::where('exonerations.id', $tax->id_exoneration)
                    ->leftJoin('type_document_exonerations', 'type_document_exonerations.id', '=', 'exonerations.id_type_document_exoneration')->first();
            }
            $taxLine = array(
                "id" => $this->id_tax,
                "code" => (string)$tax->taxCode,
                "rate_code" => (string)$tax->rateCode,
                "rate" => (string)$tax->rate,
                "mount" => $taxAmount = (($this->qty * $this->price) - $this->discount) * $tax->rate / 100,
                "exoneration" => (isset($exoneration)) ? array(
                    "DocumentType" => (string)$exoneration->code,
                    "DocumentNumber" => (string)$exoneration->document_number,
                    "InstitucionalName" => (string)$exoneration->institutional_name,
                    "EmisionDate" => (string)$exoneration->date,
                    "PercentageExoneration" => (string)$exoneration->exemption_percentage,
                    "AmountExoneration" => $exonerationAmount = (($this->qty * $this->price) - $this->discount) * $exoneration->exemption_percentage / 100
                ) : "",
            );
        }
        if ($count = $this->allCounts->where('name', $this->count)->first()) {
            $this->id_count = $count->id;
        }
        $line = [
            "id_product" => ($this->type_line == "Compra") ? $this->id_product : null,
            "cabys" => ($this->cabys == "") ? '0000000000000' : $this->cabys,
            'tariff_heading' => $this->tariff_heading,
            "description" => ($this->type_line == 'Compra') ? $this->name_product : $this->description,
            "id_count" => ($this->type_line != 'Compra') ? $this->id_count : null,
            "sku" => $this->sku,
            "qty" => $this->qty,
            "cost" => $this->cost,
            "price" => $this->price,
            "discount" => $this->discount,
            "tax" => $taxLine,
            "type_line" => $this->type_line,
            "totalLine" => ($this->qty * $this->price) - $this->discount + $taxAmount - $exonerationAmount
        ];

        array_push($this->allLines, $line);
        $this->subtotal += ($this->qty * $this->price);
        $this->totalDiscount += $this->discount;
        $this->totalTax += $taxAmount;
        $this->totalExoneration += $exonerationAmount;
        $this->total =  $this->subtotal - $this->totalDiscount + $this->totalTax - $this->totalExoneration;

        //limpia los campos
        $this->detail = '';
        $this->cabys = '';
        $this->description = '';
        $this->id_count = '';
        $this->count = '';
        $this->qty = 1;
        $this->price = 0;
        $this->cost = 0;
        $this->discount = 0;
        $this->id_tax = $this->allTaxes->first()->id;
        $this->id_product = '';
        $this->name_product = '';
        $this->tariff_heading = 0;
        $this->sku = $this->allSkuses->first()->symbol;
    }
    public function deleteLineOther($index)
    {
        $this->total_other_charges -= $this->allOtherCharges[$index]['amount_other'];
        unset($this->allOtherCharges[$index]);
        $this->allOtherCharges = array_values($this->allOtherCharges);
    }
    public function deleteLine($index)
    {
        $line = $this->allLines[$index];
        $mountL = $line['qty'] * $line['price'];
        $this->subtotal -= $mountL;
        $this->totalDiscount -= ($line['discount']);
        $this->totalTax -= (isset($line['tax']['mount'])) ? $line['tax']['mount'] : 0;
        $this->totalExoneration -= (isset($line['tax']['exoneration']['AmountExoneration'])) ? $line['tax']['exoneration']['AmountExoneration'] : 0;

        $this->total =  $this->subtotal - $this->totalDiscount + $this->totalTax - $this->totalExoneration;
        unset($this->allLines[$index]);
        $this->allLines = array_values($this->allLines);
    }
    public function editLine($index)
    {
        $this->id_product = $this->allLines[$index]['id_product'];
        $this->cabys = $this->allLines[$index]['cabys'];
        $this->name_product = $this->allLines[$index]['description'];
        $this->description = $this->allLines[$index]['description'];
        $this->qty = $this->allLines[$index]['qty'];
        $this->cost = $this->allLines[$index]['cost'];
        $this->price = $this->allLines[$index]['price'];
        $this->tariff_heading = $this->allLines[$index]['tariff_heading'];
        $this->sku = $this->allLines[$index]['sku'];
        $this->discount = $this->allLines[$index]['discount'];
        $this->type_line = $this->allLines[$index]['type_line'];
        $this->id_tax = $this->allLines[$index]['tax']['id'];
        $this->id_count = ($this->type_line != 'Compra') ? $this->allLines[$index]['id_count'] : null;
        $this->count = ($this->id_count) ? Count::find($this->id_count)->name : "";
        $this->deleteLine($index);
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
    public function store()
    {
        $this->validate([
            'id_provider' => 'required',
            'code_currency' => 'required',
            'code_payment_method' => 'required',
            'id_mh_category' => 'required',
            'code_sale_condition' => 'required',
            'term' => 'required',
            'exchange_rate' => 'required',
            'consecutive' => 'required',
            'number_ea' => 'required',
            'id_branch_office' => 'required',
            'allLines' => 'required|array'
        ]);
        DB::beginTransaction();
        try {
            $bo = BranchOffice::where('branch_offices.id', $this->id_branch_office)
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

            $this->keyDoc = $bo->phone_code . date("d") . date("m") . date("y") . str_pad(Auth::user()->currentTeam->id_card, 12, "0", STR_PAD_LEFT) . $this->consecutive . '119890717';
            $this->path = 'files/Facturas de Compra/' . Auth::user()->currentTeam->id_card . '/' . $this->keyDoc;
            $this->getConsecutive();
            $this->dateDoc = now();
            $this->calculate();
            $doc = Expense::create([
                'id_company' => Auth::user()->currentTeam->id,
                'id_terminal' => $this->id_terminal,
                'id_provider' => $this->id_provider,
                'key' => $this->keyDoc,
                'e_a' => $this->number_ea,
                'consecutive' => $this->consecutive,
                'consecutive_real' => $this->consecutive,
                'date_issue' => $this->dateDoc,
                'expiration_date' => $this->dateDoc, //modificar mas term
                'sale_condition' => $this->code_sale_condition,
                'term' => $this->term,
                'note' => $this->note,
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
                'total_other_charges' => $this->total_other_charges,
                'iva_returned' => $this->iva_returned,
                'total_document' => $this->total,
                'pending_amount' =>  $this->total,
                'ruta' => $this->path,
                'state' => 'Ninguno',
                'condition' => 'Ninguna',
                'state_pay' => 'Pendiente',
                'mh_detail' => $this->detail_mh,
                'possible_deliver_date' => $this->possible_deliver_date,
                'id_branch_office' => $this->id_branch_office,
                'id_buyer' => $this->id_buyer,
                'gasper' => $this->gasper,
                'pendingCE' => $this->pendingCE,
                'type_doc' => ($this->type_doc == '1') ? '08' : (($this->type_doc == '2') ? '11' : '00')
            ]);
            foreach ($this->allLines as $index => $line) {
                ExpenseDetail::create([
                    'id_expense' => $doc->id,
                    'id_product' => $line['id_product'],
                    'tariff_heading' => $line['tariff_heading'],
                    'qty' => $line['qty'],
                    'sku' => $line['sku'],
                    'line' => $index + 1,
                    'cabys' =>  $line['cabys'],
                    'detail' => $line['description'],
                    'id_count' => ($line['id_count']) ? $line['id_count'] : null,
                    'price' => $line['price'],
                    'total_amount' => $line['price'] * $line['qty'],
                    'discounts' => $line['discount'],
                    'type' => ($line['type_line'] == "Compra") ? 0 : 1,
                    'subtotal' => ($line['price'] * $line['qty']) - $line['discount'],
                    'taxes' => json_encode($line['tax']),
                    'tax_net' => (isset($line['tax']['exoneration']['AmountExoneration'])) ? $line['tax']['mount'] - $line['tax']['exoneration']['AmountExoneration'] : 0,
                    'total_amount_line' => $line['totalLine'],
                ]);
            }
            if ($this->otherCharge) {
                foreach ($this->allOtherCharges as $other) {
                    ExpenseOtherCharge::create([
                        'id_expense' => $doc->id,
                        'type_document' => $other['id_type_document_other'],
                        'idcard' => $other['idcard_other'],
                        'name' => $other['name_other'],
                        'detail' => $other['detail_other_charge'],
                        'amount' => $other['amount_other']
                    ]);
                }
            }
            if ($this->id_type_reference != '') {
                ExpenseReference::create([
                    'id_expense' => $doc->id,
                    'code_type_doc' => $this->id_type_reference,
                    'number' => $this->number_reference,
                    'date_issue' => $this->date_reference,
                    'code' => $this->id_reference,
                    'reason' => $this->reason
                ]);
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
        $this->generatePDF($doc->id);
        $this->nextConsecutive($this->type_doc, $bo->id);
        try {
            if ($this->type_doc == '1') {
                $client = Provider::where('providers.id', $this->id_provider)
                    ->leftJoin('country_codes', 'country_codes.id', '=', 'providers.id_country_code')
                    ->leftJoin('provinces', 'provinces.id', '=', 'providers.id_province')
                    ->leftJoin('cantons', 'cantons.id', '=', 'providers.id_canton')
                    ->leftJoin('districts', 'districts.id', '=', 'providers.id_district')
                    ->select(
                        'providers.*',
                        'country_codes.phone_code',
                        'provinces.code as code_province',
                        'cantons.code as code_canton',
                        'districts.code as code_district'
                    )->first();
                $xmlF = $this->createXML($bo, $doc, $client);
                $token = $this->tokenMH();
                $this->send($client, $xmlF, json_decode($token)->access_token);
                do {
                    $state = $this->consultState($this->keyDoc);
                } while ($state == 'procesando');

                $path = 'files/Facturas de compra/' . Auth::user()->currentTeam->id_card . '/' . $this->keyDoc;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $this->dispatchBrowserEvent('messageData', ['messageData' => "El documento clave: " . $this->keyDoc . ' fue ' . $state . ' por el Ministerio de Hacienda', 'refresh' => 0]);
            } else {
                $this->dispatchBrowserEvent('messageData', ['messageData' => 'Documento creado con exito', 'refresh' => 0]);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        $this->emit('refreshVoucherTable');
        $this->dispatchBrowserEvent('electronicPurchase_modal_hide', []);
    }
    public function createXML($bo, $doc, $client)
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronicaCompra xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronicaCompra" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <Clave>' . $this->keyDoc . '</Clave>
        <CodigoActividad>' . $this->number_ea . '</CodigoActividad>
        <NumeroConsecutivo>' . $this->consecutive . '</NumeroConsecutivo>
        <FechaEmision>' . date('Y-m-d\TH:i:s', strtotime($this->dateDoc)) . '</FechaEmision>
        <Emisor>
        <Nombre>' . $client->name_provider . '</Nombre>
            <Identificacion>
            <Tipo>' . '0' . $client->type_id_card . '</Tipo>
            <Numero>' . $client->id_card . '</Numero>
            </Identificacion>
            <NombreComercial>' . $client->name_provider . '</NombreComercial>
            <Ubicacion>
            <Provincia>' . $client->code_province . '</Provincia>
            <Canton>' . $client->code_canton . '</Canton>
            <Distrito>' . $client->code_district . '</Distrito>
            <OtrasSenas>' . $client->other_signs . '</OtrasSenas>
            </Ubicacion>
            <Telefono>
            <CodigoPais>' . $client->phone_code . '</CodigoPais>
            <NumTelefono>' . $client->phone . '</NumTelefono>
            </Telefono>
            <CorreoElectronico>' . $client->emails . '</CorreoElectronico>
        </Emisor>';

        $xml .=  '<Receptor>
        <Nombre>' . Auth::user()->currentTeam->name . '</Nombre>
        <Identificacion>
        <Tipo>' . '0' . Auth::user()->currentTeam->type_id_card . '</Tipo>
        <Numero>' . Auth::user()->currentTeam->id_card . '</Numero>
        </Identificacion>
        <NombreComercial>' . Auth::user()->currentTeam->name . '</NombreComercial>
        <Ubicacion>
        <Provincia>' . $bo->code_province . '</Provincia>
        <Canton>' . $bo->code_canton . '</Canton>
        <Distrito>' . $bo->code_district . '</Distrito>
        <OtrasSenas>' . $bo->other_signs . '</OtrasSenas>
        </Ubicacion>
        <Telefono>
        <CodigoPais>' . $bo->phone_code . '</CodigoPais>
        <NumTelefono>' . $bo->phone . '</NumTelefono>
        </Telefono>
        <CorreoElectronico>' . $bo->emails . '</CorreoElectronico>            
            </Receptor>';
        $xml .= '<CondicionVenta>' . $this->code_sale_condition . '</CondicionVenta>
        <PlazoCredito>' . $this->term . '</PlazoCredito>
        <MedioPago>' . (($this->code_payment_method != '06') ? $this->code_payment_method : '03') . '</MedioPago>
        <DetalleServicio>';
        foreach ($this->allLines as $index => $line) {
            $xml .= '<LineaDetalle>
            <NumeroLinea>' . ($index + 1) . '</NumeroLinea>';
            if ($this->type_doc == '09') {
                $xml .= '<PartidaArancelaria>' . $line['tariff_heading'] . '</PartidaArancelaria>';
            }
            if ($line['cabys'] != '0000000000000' && $line['cabys'] != '') {
                $xml .= '<Codigo>' . $line['cabys'] . '</Codigo>';
            }
            $xml .= '<Cantidad>' . $line['qty'] . '</Cantidad>
            <UnidadMedida>' . $line['sku'] . '</UnidadMedida>
            <Detalle>' . $line['description'] . '</Detalle>
            <PrecioUnitario>' . $line['price'] . '</PrecioUnitario>
            <MontoTotal>' . $line['price'] * $line['qty'] . '</MontoTotal>';
            if ($line['discount'] != 0) {
                $xml .= '<Descuento>
                <MontoDescuento>' . $line['discount'] . '</MontoDescuento>
                <NaturalezaDescuento>Descuento General</NaturalezaDescuento>
                </Descuento>';
            }
            $xml .= '<SubTotal>' . (($line['price'] * $line['qty']) - $line['discount']) . '</SubTotal>';
            if ($line['tax']) {
                $xml .= '<Impuesto>
                <Codigo>' . str_pad($line['tax']['code'], 2, "0", STR_PAD_LEFT) . '</Codigo>
                <CodigoTarifa>' . str_pad($line['tax']['rate_code'], 2, "0", STR_PAD_LEFT) . '</CodigoTarifa>
                <Tarifa>' . $line['tax']['rate'] . '</Tarifa>
                <Monto>' . $line['tax']['mount'] . '</Monto>';
                if (isset($line['tax']['exoneration']['AmountExoneration'])) {
                    $xml .= '<Exoneracion>
                    <TipoDocumento>' . $line['tax']['exoneration']['DocumentType'] . '</TipoDocumento>
                    <NumeroDocumento>' . $line['tax']['exoneration']['DocumentNumber'] . '</NumeroDocumento>
                    <NombreInstitucion>' . $line['tax']['exoneration']['InstitucionalName'] . '</NombreInstitucion>
                    <FechaEmision>' . $line['tax']['exoneration']['EmisionDate'] . '</FechaEmision>
                    <PorcentajeExoneracion>' . $line['tax']['exoneration']['PercentageExoneration'] . '</PorcentajeExoneracion>
                    <MontoExoneracion>' . $line['tax']['exoneration']['AmountExoneration'] . '</MontoExoneracion>
                    </Exoneracion>';
                }

                $xml .= '</Impuesto>';
            }
            $xml .= '<ImpuestoNeto>' . $in = ((isset($line['tax']['exoneration']['AmountExoneration'])) ? $line['tax']['mount'] - $line['tax']['exoneration']['AmountExoneration'] : 0) . '</ImpuestoNeto>
            <MontoTotalLinea>' . $line['totalLine'] . '</MontoTotalLinea>
            </LineaDetalle>';
        }
        $xml .= '</DetalleServicio>
        <ResumenFactura>
        <TotalServGravados>' . $this->total_taxed_services . '</TotalServGravados>
        <TotalServExentos>' . $this->total_exempt_services . '</TotalServExentos>
        <TotalServExonerado>' . $this->total_exonerated_services . '</TotalServExonerado>
        <TotalMercanciasGravadas>' . $this->total_taxed_merchandise . '</TotalMercanciasGravadas>
        <TotalMercanciasExentas>' . $this->total_exempt_merchandise . '</TotalMercanciasExentas>
        <TotalMercExonerada>' . $this->total_exonerated_merchandise . '</TotalMercExonerada>
        <TotalGravado>' . ($this->total_taxed_services + $this->total_taxed_merchandise) . '</TotalGravado>
        <TotalExento>' . ($this->total_exempt_services + $this->total_exempt_merchandise) . '</TotalExento>
        <TotalExonerado>' . ($this->total_exonerated_services + $this->total_exonerated_merchandise) . '</TotalExonerado>
        <TotalVenta>' . ($this->total_taxed_services + $this->total_taxed_merchandise + $this->total_exempt_services + $this->total_exempt_merchandise + $this->total_exonerated_services + $this->total_exonerated_merchandise) . '</TotalVenta>
        <TotalDescuentos>' . $this->totalDiscount . '</TotalDescuentos>
        <TotalVentaNeta>' . ($this->total_taxed_services + $this->total_taxed_merchandise + $this->total_exempt_services + $this->total_exempt_merchandise + $this->total_exonerated_services + $this->total_exonerated_merchandise - $this->totalDiscount) . '</TotalVentaNeta>
        <TotalImpuesto>' . $this->totalTax . '</TotalImpuesto>       
        <TotalComprobante>' . $this->total . '</TotalComprobante>
        </ResumenFactura>';
        if ($this->id_type_reference != '') {
            $xml .= '<InformacionReferencia>
            <TipoDoc>' . str_pad($this->id_type_reference, 2, "0", STR_PAD_LEFT) . '</TipoDoc>
            <Numero>' . $this->number_reference . '</Numero>
            <FechaEmision>' . $this->date_reference . ':00</FechaEmision>
            <Codigo>' . str_pad($this->id_reference, 2, "0", STR_PAD_LEFT) . '</Codigo>
            <Razon>' . $this->reason . '</Razon>
            </InformacionReferencia>';
        }
        $xml .= '</FacturaElectronicaCompra>';
        $xmlt = simplexml_load_string(trim($xml));
        $path = 'files/Facturas de Compra/' . Auth::user()->currentTeam->id_card . '/' . $this->keyDoc;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $xmlt->asXML($path . '/' . $this->keyDoc . '.xml');
        //firmar xml de mensaje
        $firmador = new Firmador();
        $xmlF = $firmador->firmarXml(Storage::path(Auth::user()->currentTeam->cryptographic_key), Auth::user()->currentTeam->pin, trim($xml), $firmador::TO_XML_STRING);
        //almacenamos el XML firmado
        $xmlSave = simplexml_load_string(trim($xmlF));
        $xmlSave->asXML($path . '/' . $this->keyDoc . '-Firmado.xml');
        $doc->update([
            'path' => $path
        ]);
        return  $xmlF;
    }
    public static function tokenMH()
    {
        $url = '';
        $client_secret = "";
        $grant_type = "password";
        //selecccion e acceso a DB
        if (Auth::user()->currentTeam->proof == '1') {
            $client_id = "api-stag";
            $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token";
        } else {
            $client_id = "api-prod";
            $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut/protocol/openid-connect/token";
        }
        //Solicitud de un nuevo token
        if ($grant_type == "password") {
            $username = Auth::user()->currentTeam->user_mh;
            $password = Auth::user()->currentTeam->pass_mh;

            //Validation de los datos necesarios
            if ($client_id == '') {
                $result = array("status" => "400", "message" => "El parametro Provider ID es requerido");
                return $result;
            } else if ($grant_type == '') {
                $result = array("status" => "400", "message" => "El parametro Grant Type es requerido");
                return $result;
            } else if ($username == '') {
                $result = array("status" => "400", "message" => "El parametro Username es requerido");
                return $result;
            } else if ($password == '') {
                $result = array("status" => "400", "message" => "El parametro Password es requerido");
                return $result;
            }

            //creadcion del array de acceso 
            $data = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => $grant_type,
                'username' => $username,
                'password' => $password
            );
            //refrescand el token
        }

        //creacion del header para la consulta
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        //consulta y resultado
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    function send($client, $xmlFirmado, $token)
    {
        $xml64 = base64_encode($xmlFirmado);
        $d = '';
        $d = date('Y-m-d\TH:i:s');

        $datos = array(
            'clave' => trim($this->keyDoc),
            'fecha' => $d,
            'emisor' => array(
                'tipoIdentificacion' => '0' . $client->type_id_card,
                'numeroIdentificacion' => $client->id_card
            ),
            'receptor' => array(
                'tipoIdentificacion' => '0' . Auth::user()->currentTeam->type_id_card,
                'numeroIdentificacion' => Auth::user()->currentTeam->id_card
            ),
            'comprobanteXml' => $xml64
        );
        $mensaje = json_encode($datos);

        $header = array(
            'Authorization: bearer ' . $token,
            'Content-Type: application/json'
        );
        if (Auth::user()->currentTeam->proof == 1) {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion");
        } else {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion");
        }
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);


        $respuesta = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);
        list($headers, $respuesta) = explode("\r\n\r\n", $respuesta, 2);
        // $headers now has a string of the HTTP headers
        // $response is the body of the HTTP response
        $h = "";
        $headers = explode("\n", $headers);
        foreach ($headers as $header) {
            if (stripos($header, 'x-error-cause:') !== false) {
                $h = str_replace('x-error-cause: ', "", $header);
                $h = str_replace('\r', "", $h);
            }
        }
        if ($err) {
            $arrayResp = array(
                "status"    => $status,
                "message"      => $err
            );
            return $arrayResp;
        } else {
            $arrayResp = array(
                "status"    => $status,
                "message"      => explode("\n", $respuesta),
                "result" => $h
            );
            return $arrayResp;
        }
    }
    public function generatePDF($id)
    {
        $doc = Expense::where('expenses.id', $id)
            ->leftJoin('sale_conditions', 'sale_conditions.code', '=', 'expenses.sale_condition')
            ->leftJoin('payment_methods', 'payment_methods.code', '=', 'expenses.payment_method')
            ->select(
                'expenses.*',
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

        $client = Provider::where('providers.id', $doc->id_provider)
            ->leftJoin('country_codes', 'country_codes.id', '=', 'providers.id_country_code')
            ->leftJoin('provinces', 'provinces.id', '=', 'providers.id_province')
            ->leftJoin('cantons', 'cantons.id', '=', 'providers.id_canton')
            ->leftJoin('districts', 'districts.id', '=', 'providers.id_district')
            ->select(
                'providers.*',
                'country_codes.phone_code',
                'provinces.province as name_province',
                'cantons.canton as name_canton',
                'districts.district as name_district'
            )->first();
        $reference = ExpenseReference::where('expense_references.id_expense', $doc->id)->first();
        $details = ExpenseDetail::where('expense_details.id_expense', $doc->id)->get();
        $otherCharges = ExpenseOtherCharge::join('type_document_other_charges', 'type_document_other_charges.id', '=', 'expense_other_charges.type_document')
            ->where('id_expense', $doc->id)
            ->select(
                'expense_other_charges.*',
                'type_document_other_charges.type_document as typeDocument'
            )->get();
        $buyer = ($doc->id_buyer) ? Buyer::where('id', $doc->id_buyer)->first() : '';
        $data = [
            'title' => $this->type_document,
            'document' => $doc,
            'company' => Auth::user()->currentTeam,
            'bo' => $bo,
            'client' => $client,
            'reference' => $reference,
            'details' => $details,
            'otherCharges' => $otherCharges,
            'otherCharge' => $this->otherCharge,
            'buyer' => $buyer
        ];
        $pdf = PDF::loadView('livewire.voucher.invoicePDF', $data);
        $path = 'files/Facturas de Compra/' . Auth::user()->currentTeam->id_card . '/' . $doc->key;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $pdf->save($path . '/' . $clave . '.pdf');
    }

    public function consultState($clave)
    {
        $doc = Expense::where('expenses.key', $clave)->first();
        $token = json_decode($this->tokenMH())->access_token;
        $indEstado = 'ninguno';
        $curl = curl_init();
        if ($clave == "") {
            echo "El valor de la clave no debe ser vacio";
        }
        $url = null;
        if (Auth::user()->currentTeam->proof == '1') {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/";
        } else {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion/";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $clave,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
                "Postman-Token: bf8dc171-5bb7-fa54-7416-56c5cda9bf5c"
            ),
        ));

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => "Error " . $status . ': ' . $err]);
        } else {
            $xml = json_decode($response, true);
            $indEstado = $xml["ind-estado"];
            if (isset($xml["respuesta-xml"])) {
                $xml = $xml["respuesta-xml"];
                $xml = base64_decode($xml);
            } else {
                $xml = "";
            }
        }

        $doc = Expense::where('expenses.key', $clave)->first();
        $doc->update([
            "state" => $indEstado
        ]);
        if ($xml != "") {
            $xml = simplexml_load_string(trim($xml));
            if ($indEstado == "rechazado") {
                $doc->update([
                    "detail_mh" => $xml->DetalleMensaje
                ]);
            }
            $carpeta = 'files/Facturas de Compra/' . Auth::user()->currentTeam->id_card . '/' . $clave;
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            //agregar envio aqui
            $nombre_fichero = $carpeta . '/' . $clave . '-R.xml';
            $xml->asXML($nombre_fichero);
        }
        return $indEstado;
    }
    public function consultStateP($clave)
    {
        $doc = Expense::where('expenses.key', $clave)->first();
        $token = json_decode($this->tokenMH())->access_token;
        $indEstado = 'ninguno';
        $curl = curl_init();
        if ($clave == "") {
            echo "El valor de la clave no debe ser vacio";
        }
        $url = null;
        if (Auth::user()->currentTeam->proof == '1') {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/";
        } else {
            $url = "https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion/";
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $clave,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded",
                "Postman-Token: bf8dc171-5bb7-fa54-7416-56c5cda9bf5c"
            ),
        ));

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => "Error " . $status . ': ' . $err]);
        } else {
            $xml = json_decode($response, true);
            $indEstado = $xml["ind-estado"];
            if (isset($xml["respuesta-xml"])) {
                $xml = $xml["respuesta-xml"];
                $xml = base64_decode($xml);
            } else {
                $xml = "";
            }
        }

        $doc = Expense::where('expenses.key', $clave)->first();
        $doc->update([
            "state" => $indEstado
        ]);
        if ($xml != "") {
            $xml = simplexml_load_string(trim($xml));
            if ($indEstado == "rechazado") {
                $doc->update([
                    "detail_mh" => $xml->DetalleMensaje
                ]);
            }
            $carpeta = 'files/Facturas de Compra/' . Auth::user()->currentTeam->id_card . '/' . $clave;
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            //agregar envio aqui
            $nombre_fichero = $carpeta . '/' . $clave . '-R.xml';
            $xml->asXML($nombre_fichero);
        }
        $this->dispatchBrowserEvent('messageData', ['messageData' => "El documento clave: " . $clave . ' fue ' . $indEstado . ' por el Ministerio de Hacienda', 'refresh' => 0]);
    }
    public function change_bo()
    {
        $this->allTerminals = Terminal::where('id_branch_office', $this->id_branch_office)->get();
        $this->id_terminal = Terminal::where('id_branch_office', $this->id_branch_office)->first()->id;
    }
    public function updateVoucher()
    {
        DB::beginTransaction();
        try {
            $expense = Expense::find($this->id_expense);
            $expense->update([
                'note' => $this->note,
                'id_mh_categories' => $this->id_mh_category,
                'e_a' => $this->number_ea,
                'gasper' => $this->gasper,
                'id_branch_office' => $this->id_branch_office,
                'id_terminal' => $this->id_terminal
            ]);
            foreach ($this->details as $key => $detail) {
                if (Auth::user()->currentTeam->plan_id != 1) {
                    if (isset($this->productsUpdate[$key])) {
                        if ($this->productsUpdate[$key] != '') {
                            $detail->update([
                                'id_count' => null,
                                'id_product' => $this->allProducts->where('description', $this->productsUpdate[$key])->first()->id,
                                'type' => '0'
                            ]);
                        } else {
                            $detail->update([
                                'id_product' => null,
                            ]);
                        }
                    } else {
                        $detail->update([
                            'id_product' => null,
                        ]);
                    }
                    if (isset($this->countsUpdate[$key])) {
                        if ($this->countsUpdate[$key] != '') {
                            $detail->update([
                                'id_product' => null,
                                'id_count' => $this->allCounts->where('name', $this->countsUpdate[$key])->first()->id,
                                'type' => '1'
                            ]);
                        } else {
                            $detail->update([
                                'id_count' => null,
                            ]);
                        }
                    } else {
                        $detail->update([
                            'id_count' => null,
                        ]);
                    }
                } else {
                    $detail->update([
                        'type' => ($this->type_line == 'Compra') ? '0' : '1'
                    ]);
                }
            }
            $this->generatePDF($this->id_expense);
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Datos actualizados con exito']);
            $this->dispatchBrowserEvent('viewVoucher_modal_hide', []);
        } catch (\Illuminate\Database\QueryException $e) {
            // back to form with errors
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion.' . $e->getMessage()]);
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion' . $e->getMessage()]);
        }
        DB::commit();
        $this->cleanInputs();
    }
    public function update()
    {
        $this->validate([
            'id_provider' => 'required',
            'code_currency' => 'required',
            'code_payment_method' => 'required',
            'id_mh_category' => 'required',
            'code_sale_condition' => 'required',
            'term' => 'required',
            'exchange_rate' => 'required',
            'consecutive' => 'required',
            'number_ea' => 'required',
            'id_branch_office' => 'required',
            'allLines' => 'required|array'
        ]);
        DB::beginTransaction();
        try {
            if ($this->id_expense) {
                $this->calculate();
                $result = Expense::find($this->id_expense);
                $this->path = 'files/Facturas de Compra/' . Auth::user()->currentTeam->id_card . '/' . $this->keyDoc;
                $result->update([
                    'id_company' => Auth::user()->currentTeam->id,
                    'id_provider' => $this->id_provider,
                    'key' => $this->keyDoc,
                    'e_a' => $this->number_ea,
                    'consecutive_real' => $this->consecutive,
                    'date_issue' => $this->dateDoc,
                    'expiration_date' => $this->dateDoc, //modificar mas term
                    'sale_condition' => $this->code_sale_condition,
                    'term' => $this->term,
                    'note' => $this->note,
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
                    'total_other_charges' => $this->total_other_charges,
                    'iva_returned' => $this->iva_returned,
                    'total_document' => $this->total + $this->total_other_charges,
                    'pending_amount' =>  $this->total + $this->total_other_charges,
                    'ruta' => $this->path,
                    'state' => 'Ninguno',
                    'condition' => 'Ninguna',
                    'state_pay' => 'Pendiente',
                    'mh_detail' => $this->detail_mh,
                    'possible_deliver_date' => $this->possible_deliver_date,
                    'id_branch_office' => $this->id_branch_office,
                    'id_buyer' => $this->id_buyer,
                    'gasper' => $this->gasper,
                    'pendingCE' => $this->pendingCE,
                    'type_doc' => ($this->type_doc == '1') ? '08' : (($this->type_doc == '2') ? '11' : '00')
                ]);
                ExpenseDetail::where('id_expense', $result->id)->delete();
                foreach ($this->allLines as $index => $line) {
                    ExpenseDetail::create([
                        'id_expense' => $result->id,
                        'id_product' => $line['id_product'],
                        'tariff_heading' => $line['tariff_heading'],
                        'qty' => $line['qty'],
                        'sku' => $line['sku'],
                        'line' => $index + 1,
                        'cabys' =>  $line['cabys'],
                        'detail' => $line['description'],
                        'id_count' => (isset($line['id_count'])) ? $line['id_count'] : null,
                        'price' => $line['price'],
                        'total_amount' => $line['price'] * $line['qty'],
                        'discounts' => $line['discount'],
                        'type' => ($line['type_line'] == "Compra") ? 0 : 1,
                        'subtotal' => ($line['price'] * $line['qty']) - $line['discount'],
                        'taxes' => json_encode($line['tax']),
                        'tax_net' => (isset($line['tax']['exoneration']['AmountExoneration'])) ? $line['tax']['mount'] - $line['tax']['exoneration']['AmountExoneration'] : 0,
                        'total_amount_line' => $line['totalLine'],
                    ]);
                }
                ExpenseReference::where('id_expense', $result->id)->delete();
                if ($this->id_type_reference != '') {
                    ExpenseReference::create([
                        'id_expense' => $result->id,
                        'code_type_doc' => $this->id_type_reference,
                        'number' => $this->number_reference,
                        'date_issue' => $this->date_reference,
                        'code' => $this->id_reference,
                        'reason' => $this->reason
                    ]);
                }
                if ($this->otherCharge) {
                    ExpenseOtherCharge::where('id_expense', $result->id)->delete();
                    foreach ($this->allOtherCharges as $other) {
                        ExpenseOtherCharge::create([
                            'id_expense' => $result->id,
                            'type_document' => $other['id_type_document_other'],
                            'idcard' => $other['idcard_other'],
                            'name' => $other['name_other'],
                            'detail' => $other['detail_other_charge'],
                            'amount' => $other['amount_other']
                        ]);
                    }
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
        $this->dispatchBrowserEvent('electronicPurchaseU_modal_hide', []);
        $this->emit('refreshVoucherTable');
        $this->cleanInputs();
    }
}
