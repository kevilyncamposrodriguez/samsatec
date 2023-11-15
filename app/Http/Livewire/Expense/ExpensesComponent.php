<?php

namespace App\Http\Livewire\Expense;

use App\Models\BranchOffice;
use App\Models\Cantons;
use App\Models\CompaniesEconomicActivities;
use App\Models\ConexionMH;
use App\Models\Consecutive;
use App\Models\Count;
use App\Models\CountryCodes;
use App\Models\Currencies;
use App\Models\Districts;
use App\Models\Expense;
use App\Models\PaymentMethods;
use App\Models\Provider;
use App\Models\Province;
use App\Models\SaleConditions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Hacienda\Firmador;
use App\Models\DetailExpenses;
use App\Models\DiaryBook;
use App\Models\ExpenseDetail;
use App\Models\MhCategories;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TeamUser;
use App\Models\Terminal;
use Illuminate\Support\Facades\Storage;

class ExpensesComponent extends Component
{
    use WithFileUploads;
    public $allTerminals = [], $type_document,$allPayments = [],$debit, $allPendings = [], $id_terminal, $allBranchsOffices = [], $allBO = [], $allCounts, $all_eas = [];
    //upload files
    public $type_doc, $type_line, $pre, $pendingCE, $xmlDoc, $xmlrDoc, $pdfDoc, $key, $category_mh = 'Sin clasificar', $type_purchase = "Compra", $id_count, $id_branch_office, $id_mh_category = 1;
    //view voucher
    public $ids_product, $ids_count, $number_ea, $id_expense, $id_card_e, $name_e, $phone_e, $mail_e, $address_e, $id_card_r, $name_r, $phone_r, $mail_r, $address_r, $consecutive_view,
        $gasper, $date_view, $sale_condition_view, $payment_method_view, $currency_view, $credit_term, $exchange_rate, $detail_view = [], $subtotal_view, $discount_view, $tax_view, $exoneration_view, $total_view, $other_view, $code_ea;
    public $allCountsD = [], $allProducts, $allMHCategories = [], $date_emition, $expiration_date, $detail_mh = 'Ninguno', $state = 'Ninguno', $type_proccess, $consecutive;
    protected $listeners = ['change_product', 'change_count'];
    public $countsUpdate, $productsUpdate;
    public  $allOtherCharges = [], $allTypeDocumentOtherCharges = [], $id_type_document_other, $idcard_other, $name_other, $detail_other_charge, $amount_other, $otherCharge = 0, $totalDoc = 0;
    public function mount()
    {
        $this->gasper = false;
        $this->allMHCategories = MhCategories::all();
        if ($this->id_expense != '') {
            $this->allPayments = Payment::where("payments.id_expense", "=", $this->id_expense)
                ->join('counts', 'counts.id', '=', 'payments.id_count')
                ->select('payments.*', 'counts.name as count')->get();
            $this->debit = $this->total_view - Payment::where("payments.id_expense", "=", $this->id_expense)
                ->sum('mount');
        }

        $this->allCounts = Count::whereIn("id_count_primary", [25, 26])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->allBO = BranchOffice::where("branch_offices.id_company", "=", Auth::user()->currentTeam->id)->get();
        $this->id_branch_office = (isset(TeamUser::getUserTeam()->bo)) ? TeamUser::getUserTeam()->bo : $this->allBO[0]->id;
        $this->allTerminals = Terminal::where('id_branch_office', $this->id_branch_office)->get();
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->id_terminal = (Terminal::where("terminals.id_branch_office", $this->id_branch_office)->first())->id;
        } else {
            $this->id_terminal = (isset(TeamUser::getUserTeam()->terminal)) ? TeamUser::getUserTeam()->terminal : ((Terminal::where("terminals.id_branch_office", $this->id_branch_office)->first())->id);
        }
        $this->pendingCE = [];
        $this->allCountsD = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where("products.active", '1')->get();
        } else {
            $this->allProducts = Product::where("products.id_company", Auth::user()->currentTeam->id)
                ->where('id_count_inventory', BranchOffice::find(TeamUser::getUserTeam()->bo)->id_count)
                ->where("products.active", '1')->get();
        }
        $this->all_eas = CompaniesEconomicActivities::where("companies_economic_activities.id_company", Auth::user()->currentTeam->id)
            ->join('economic_activities', 'economic_activities.id', 'companies_economic_activities.id_economic_activity')
            ->select(
                'companies_economic_activities.*',
                'economic_activities.number as number',
                'economic_activities.name_ea as name_ea'
            )->get();
        $this->number_ea = $this->all_eas[0]->number;
    }
    public function render()
    { //->where("providers.id_card", 'like', '%' . $this->id_card_e . '%')
        if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')) {
            $this->allPendings = Expense::where("expenses.consecutive", 'like', 'FCI%')->where("expenses.id_company", Auth::user()->currentTeam->id)->where("pendingCE", 1)
                ->where("providers.id_card", 'like', '%' . $this->id_card_e . '%')
                ->join('providers', 'providers.id', '=', 'expenses.id_provider')->get();
        } else {
            $this->allPendings = Expense::where("expenses.consecutive", 'like', 'FCI%')->where("expenses.id_company", Auth::user()->currentTeam->id)->where("pendingCE", 1)
                ->where('expenses.id_branch_office', TeamUser::getUserTeam()->bo)
                ->where("providers.id_card", 'like', '%' . $this->id_card_e . '%')
                ->join('providers', 'providers.id', '=', 'expenses.id_provider')->get();
        }
        $this->all_eas = CompaniesEconomicActivities::where("companies_economic_activities.id_company", Auth::user()->currentTeam->id)
            ->join('economic_activities', 'economic_activities.id', 'companies_economic_activities.id_economic_activity')
            ->select(
                'companies_economic_activities.*',
                'economic_activities.number as number',
                'economic_activities.name_ea as name_ea'
            )->get();
        return view('livewire.expense.expenses-component', [
            'allVouchers' => Expense::chargeDocs()
        ]);
    }
    public function change_bo()
    {
        $this->allTerminals = Terminal::where('id_branch_office', $this->id_branch_office)->get();
        $this->id_terminal = Terminal::where('id_branch_office', $this->id_branch_office)->first()->id;
    }
    public function change_product($index, $value)
    {
        $this->detail_view[$index]['id_product'] = $value;
    }
    public function change_count($index, $value)
    {
        $this->detail_view[$index]['id_count'] = $value;
    }
    public function delete($key)
    {
        $path = 'files/recibidos/sin procesar/' . Auth::user()->currentTeam->id_card . '/' . $key;
        foreach (glob($path . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        rmdir($path);
        $this->dispatchBrowserEvent('messageData', ['messageData' => 'Comprobante eliminado con exito']);
    }
    public function importDocs()
    {
        $this->validate([
            'key' => 'required', // 5MB Max
            'xmlDoc' => 'required', // 5MB Max
            'xmlrDoc' => 'required', // 5MB Max
        ]);
        try {
            $path = 'files/recibidos/sin procesar/' . Auth::user()->currentTeam->id_card . '/' . $this->key . '/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $this->xmlDoc->storeAs($path, $this->key . '.xml', 'public_files');
            $this->xmlrDoc->storeAs($path, $this->key . '-R.xml', 'public_files');
            if ($this->pdfDoc != '') {
                $this->pdfDoc->storeAs($path, $this->key . '.pdf', 'public_files');
            }
            $this->dispatchBrowserEvent('messageData', ['messageData' => 'Archivos Cargados']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al cargar los archivos' . $e->getMessage()]);
        }
    }
    public function viewVoucher($key, $accept)
    {
        $this->cleanInputs();
        $this->all_eas = CompaniesEconomicActivities::where("companies_economic_activities.id_company", Auth::user()->currentTeam->id)
            ->join('economic_activities', 'economic_activities.id', 'companies_economic_activities.id_economic_activity')
            ->select(
                'companies_economic_activities.*',
                'economic_activities.number as number',
                'economic_activities.name_ea as name_ea'
            )->get();
        $this->type_proccess = ($accept) ? 'aceptado' : 'guardado';
        $this->number_ea = $this->all_eas[0]->number;
        $this->exchange_rate = $path = 'files/recibidos/sin procesar/' . Auth::user()->currentTeam->id_card . '/' . $key . '/' . $key . '.xml';
        if (file_exists($path)) {
            $xml = file_get_contents($path);
            $xml = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
            $xml = simplexml_load_string($xml);
            $this->key = (string) $key;
            $this->date_emition = (string)$xml->FechaEmision;
            $this->consecutive_view = (string) $xml->NumeroConsecutivo;
            $this->id_card_e = (string)$xml->Emisor->Identificacion->Numero;
            $this->name_e = (string) $xml->Emisor->Nombre;
            $this->phone_e = ((string) $xml->Emisor->Telefono->NumTelefono) ? (string) $xml->Emisor->Telefono->NumTelefono : '0000-0000';
            $this->mail_e = (string)$xml->Emisor->CorreoElectronico;
            $this->address_e = (string)$xml->Ubicacion->OtrasSenas;
            $this->date_view  = (string) $xml->FechaEmision;
            $this->sale_condition_view = SaleConditions::where("sale_conditions.code", "=", (string) $xml->CondicionVenta)->first();
            $this->sale_condition_view = $this->sale_condition_view->sale_condition;
            $this->payment_method_view = PaymentMethods::where("payment_methods.code", "=", (string) $xml->CondicionVenta)->first();
            $this->payment_method_view = $this->payment_method_view->payment_method;
            $this->credit_term = (isset($xml->PlazoCredito)) ? (string)$xml->PlazoCredito : '1';
            $this->expiration_date  = strtotime((string)$xml->FechaEmision . "+ " . $this->credit_term . " days");
            $this->currency_view = (isset($xml->ResumenFactura->CodigoTipoMoneda)) ? (string)$xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda : 'CRC';
            $this->exchange_rate = (isset($xml->ResumenFactura->CodigoTipoMoneda)) ? (((string)$xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda == 'CRC') ? 1 : (string)$xml->ResumenFactura->CodigoTipoMoneda->TipoCambio) : '1';
            //resumen de facturacion
            $this->subtotal_view = (isset($xml->ResumenFactura->TotalVentaNeta)) ? (string)$xml->ResumenFactura->TotalVentaNeta : '0';
            $this->discount_view = (isset($xml->ResumenFactura->TotalDescuentos)) ? (string)$xml->ResumenFactura->TotalDescuentos : '0';
            $this->tax_view = (isset($xml->ResumenFactura->TotalImpuesto)) ? (string)$xml->ResumenFactura->TotalImpuesto : '0';
            $this->exoneration_view = (isset($xml->ResumenFactura->TotalExonerado)) ? (string)$xml->ResumenFactura->TotalExonerado : '0';
            $this->total_view  = (isset($xml->ResumenFactura->TotalComprobante)) ? (string)$xml->ResumenFactura->TotalComprobante : '0';
            $this->other_view  = (isset($xml->ResumenFactura->TotalOtrosCargos)) ? (string)$xml->ResumenFactura->TotalOtrosCargos : '0';
            $this->detail_view = [];
            $this->type_line = null;
            foreach (json_decode(json_encode($xml->DetalleServicio), true) as $key => $detail) {
                if (isset($detail['NumeroLinea'])) {
                    array_push($this->detail_view, [
                        "line" => $detail['NumeroLinea'],
                        "id_count" => '',
                        "id_product" => '',
                        "code" => $detail["Codigo"],
                        "description" => $detail["Detalle"],
                        "sku" =>  $detail["UnidadMedida"],
                        "qty" =>  $detail["Cantidad"],
                        "price" =>  $detail["PrecioUnitario"],
                        "discount" => (isset($detail["Descuento"]["Monto"])) ? $detail["Descuento"]["Monto"] : 0,
                        "tax" => (isset($detail["Impuesto"])) ? $detail["Impuesto"]["Monto"] : 0,
                        "exoneration" => (isset($detail["Impuesto"]["Exoneracion"])) ? $detail["Impuesto"]["Exoneracion"]["MontoExoneracion"] : 0,
                        "total" => $detail["MontoTotalLinea"]
                    ]);
                    $this->type_line[$key] = "0";
                    if ($key == 'LineaDetalle') {
                        $this->countsUpdate[0] = '';
                        $this->productsUpdate[0] = '';
                    } else {
                        $this->countsUpdate[$key] = '';
                        $this->productsUpdate[$key] = '';
                    }
                } else {
                   
                    foreach ($detail as $index => $d) {
                        array_push($this->detail_view, [
                            "line" => $d['NumeroLinea'],
                            "id_count" => '',
                            "id_product" => '',
                            "code" => $d["Codigo"],
                            "description" => $d["Detalle"],
                            "sku" =>  $d["UnidadMedida"],
                            "qty" =>  $d["Cantidad"],
                            "price" =>  $d["PrecioUnitario"],
                            "discount" => (isset($d["Descuento"]["Monto"])) ? $d["Descuento"]["Monto"] : 0,
                            "tax" => (isset($d["Impuesto"])) ? $d["Impuesto"]["Monto"] : 0,
                            "exoneration" => (isset($d["Impuesto"]["Exoneracion"])) ? $d["Impuesto"]["Exoneracion"]["MontoExoneracion"] : 0,
                            "total" => $d["MontoTotalLinea"],
                        ]);
                        $this->type_line[$index] = "0";
                        $this->countsUpdate[$index] = '';
                        $this->productsUpdate[$index] = '';
                    }
                }
            }
        }
    }

    public function proccessModal($key, $type)
    {
        $this->all_eas = CompaniesEconomicActivities::where("companies_economic_activities.id_company", Auth::user()->currentTeam->id)
            ->join('economic_activities', 'economic_activities.id', 'companies_economic_activities.id_economic_activity')
            ->select(
                'companies_economic_activities.*',
                'economic_activities.number as number',
                'economic_activities.name_ea as name_ea'
            )->get();
        $this->number_ea = $this->all_eas[0]->number;
        $this->type_proccess = $type;
        $this->key = $key;
    }
    public function getConsecutive($type_doc)
    {
        switch ($type_doc) {
            case '01':
                $this->type_document = 'Factura Electronica';
                $this->pre = 'FE-';
                break;
            case '02':
                $this->type_document = 'Nota Electronica de Debito';
                $this->pre = 'ND-';
                break;
            case '03':
                $this->type_document = 'Nota Electronica de Credito';
                $this->pre = 'NC-';
                break;
            case '04':
                $this->type_document = 'Tiquete Electronico';
                $this->pre = 'TE-';
                break;
            default:
                $this->type_document = 'Factura Electronica';
                $this->pre = 'FE-';
        }
    }
    public function saveVoucher()
    {


        $key = $this->key;
        $provider = '';
        $path = 'files/recibidos/sin procesar/' . Auth::user()->currentTeam->id_card . '/' . $key . '/' . $key . '.xml';
        if (file_exists($path)) {
            $xml = file_get_contents($path);
            $xml = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $xml);
            $xml = simplexml_load_string($xml);
            $this->consecutive =  (string)$xml->NumeroConsecutivo;
            $bandera = true;
            if (Auth::user()->currentTeam->plan_id > 2 && $this->type_proccess != 'rechazado') {
                //vacias
                $vacias = 0;
                foreach ($this->productsUpdate as $index => $value) {
                    if ($this->countsUpdate[$index] == '' && $this->productsUpdate[$index] == '') {
                        $vacias++;
                        $bandera = false;
                    }
                }
            }

            if ($bandera) {
                DB::beginTransaction();
                try {
                    $province = Province::serachByCode($xml->Emisor->Ubicacion->Provincia);
                    $canton = Cantons::serachByCode($province->id, $xml->Emisor->Ubicacion->Canton);
                    $district = Districts::serachByCode($canton->id, $xml->Emisor->Ubicacion->Distrito);
                    $country_code = (isset($xml->Emisor->Telefono->CodigoPais)) ? CountryCodes::serachByCode($xml->Emisor->Telefono->CodigoPais) : CountryCodes::serachByCode('506');
                    $sale_condition = (isset($xml->CondicionVenta)) ? SaleConditions::serachByCode($xml->CondicionVenta) : SaleConditions::serachByCode('01');
                    $currency = (isset($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda)) ? Currencies::serachByCode($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda) : Currencies::serachByCode('CRC');
                    $payment = (isset($xml->MedioPago)) ? PaymentMethods::serachByCode($xml->MedioPago) : PaymentMethods::serachByCode('01');
                    $provider = Provider::firstOrCreate(
                        [
                            'id_company' => Auth::user()->currentTeam->id,
                            'id_card' => $xml->Emisor->Identificacion->Numero,
                        ],
                        [
                            'code' => '00000',
                            'id_company' => Auth::user()->currentTeam->id,
                            'id_card' => $xml->Emisor->Identificacion->Numero,
                            'type_id_card' => $xml->Emisor->Identificacion->Tipo,
                            'name_provider' => $xml->Emisor->Nombre,
                            'id_province' => $province->id,
                            'id_canton' => $canton->id,
                            'id_district' => $district->id,
                            'other_signs' => (isset($xml->Emisor->Ubicacion->OtrasSenas)) ? $xml->Emisor->Ubicacion->OtrasSenas : 'Ninguna',
                            'id_country_code' => $country_code->id,
                            'phone' => (isset($xml->Emisor->Telefono->NumeroTelefono)) ? $xml->Emisor->Telefono->NumeroTelefono : '22222222',
                            'emails' => (isset($xml->Emisor->CorreoElectronico)) ? $xml->Emisor->CorreoElectronico : '1@1.com',
                            'id_sale_condition' => $sale_condition->id,
                            'time' => $time = (isset($xml->Emisor->PlazoCredito)) ? $xml->Emisor->PlazoCredito : '1',
                            'id_currency' => $currency->id,
                            'id_payment_method' => $payment->id
                        ]
                    );
                    $path = 'files/recibidos/sin procesar/' . Auth::user()->currentTeam->id_card . '/' . $xml->Clave;
                    $path2 = 'files/recibidos/procesados/' . Auth::user()->currentTeam->id_card . '/' . $xml->Clave;

                    $expense = Expense::updateOrCreate(
                        [
                            'id_company' => Auth::user()->currentTeam->id,
                            'key' => $xml->Clave
                        ],
                        [
                            'id_company' => Auth::user()->currentTeam->id,
                            'id_branch_office' => $this->id_branch_office,
                            'id_terminal' => $this->id_terminal,
                            'id_provider' => $provider->id,
                            'key' => $xml->Clave,
                            'consecutive' => $this->consecutive,
                            'consecutive_real' => $xml->NumeroConsecutivo,
                            'e_a' => $this->number_ea,
                            'date_issue' => $emision = date('Y-m-d H:i:s', strtotime((string)$xml->FechaEmision)),
                            'expiration_date' => date('Y-m-d H:i:s', strtotime($emision . "+ " . $time . " days")),
                            'sale_condition' => $xml->CondicionVenta,
                            'term' => (isset($xml->PlazoCredito)) ? $xml->PlazoCredito : 1,
                            'payment_method' => $xml->MedioPago,
                            'currency' => (isset($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda)) ? $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda : 'CRC',
                            'exchange_rate' => (isset($xml->ResumenFactura->CodigoTipoMoneda->TipoCambio)) ? (($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda == 'CRC') ? 1 : $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio) : '1',
                            'id_mh_categories' => $this->id_mh_category,
                            'total_taxed_services' => (isset($xml->ResumenFactura->TotalServGravados)) ? $xml->ResumenFactura->TotalServGravados : 0,
                            'total_exempt_services' => (isset($xml->ResumenFactura->TotalServExentos)) ? $xml->ResumenFactura->TotalServExentos : 0,
                            'total_exonerated_services' => (isset($xml->ResumenFactura->TotalServExonerado)) ? $xml->ResumenFactura->TotalServExonerado : 0,
                            'total_taxed_merchandise' => (isset($xml->ResumenFactura->TotalMercanciasGravadas)) ? $xml->ResumenFactura->TotalMercanciasGravadas : 0,
                            'total_exempt_merchandise' => (isset($xml->ResumenFactura->TotalMercanciasExentas)) ? $xml->ResumenFactura->TotalMercanciasExentas : 0,
                            'total_exonerated_merchandise' => (isset($xml->ResumenFactura->TotalMercExonerada)) ? $xml->ResumenFactura->TotalMercExonerada : 0,
                            'total_taxed' => (isset($xml->ResumenFactura->TotalGravado)) ? $xml->ResumenFactura->TotalGravado : 0,
                            'total_exempt' => (isset($xml->ResumenFactura->TotalExento)) ? $xml->ResumenFactura->TotalExento : 0,
                            'total_exonerated' => (isset($xml->ResumenFactura->TotalExonerado)) ? $xml->ResumenFactura->TotalExonerado : 0,
                            'total_discount' => (isset($xml->ResumenFactura->TotalDescuentos)) ? $xml->ResumenFactura->TotalDescuentos : 0,
                            'total_net_sale' => (isset($xml->ResumenFactura->TotalVentaNeta)) ? $xml->ResumenFactura->TotalVentaNeta : 0,
                            'total_tax' => (isset($xml->ResumenFactura->TotalImpuesto)) ? $xml->ResumenFactura->TotalImpuesto : 0,
                            'total_other_charges' => (isset($xml->ResumenFactura->TotalOtrosCargos)) ? $xml->ResumenFactura->TotalOtrosCargos : 0,
                            'total_document' => $xml->ResumenFactura->TotalComprobante,
                            'pending_amount' => ($this->gasper) ? 0 : $xml->ResumenFactura->TotalComprobante,
                            'condition' => $this->type_proccess,
                            'type_purchase' => $this->type_purchase,
                            'state' => $this->state,
                            'ruta' => $path2,
                            'gasper' => $this->gasper,
                            'type_doc' => substr($xml->NumeroConsecutivo, 8, 2),
                        ]
                    );
                    if (isset($xml->DetalleServicio->LineaDetalle)) {

                        $cont = 0;
                        foreach ($xml->DetalleServicio->LineaDetalle as $index => $l) {

                            $taxes = array();
                            foreach ($l->Impuesto as $tax) {
                                $taxes += array(
                                    "code" => (string)$tax->Codigo,
                                    "rate_code" => (string)$tax->CodigoTarifa,
                                    "rate" => (string)$tax->Tarifa,
                                    "mount" => (string)$tax->Monto,
                                    "exoneration" => (isset($tax->Exoneracion)) ? array(
                                        "DocumentType" => (string)$tax->Exoneracion->TipoDocumento,
                                        "DocumentNumber" => (string)$tax->Exoneracion->NumeroDocumento,
                                        "InstitucionalName" => (string)$tax->Exoneracion->NombreInstitucion,
                                        "EmisionDate" => (string)$tax->Exoneracion->FechaEmision,
                                        "PercentageExoneration" => (string)$tax->Exoneracion->PorcentajeExoneracion,
                                        "AmountExoneration" => (string)$tax->Exoneracion->MontoExoneracion
                                    ) : "",
                                );
                            }
                            $discounts = array();
                            foreach ($l->Descuento as $discount) {
                                $discounts += array(
                                    "MontoDescuento" => (string)$discount->MontoDescuento,
                                    "NaturalezaDescuento" => (string)$discount->NaturalezaDescuento,
                                );
                            }
                            ExpenseDetail::updateOrCreate(
                                [
                                    'id_expense' => $expense->id,
                                    'line' => $l->NumeroLinea
                                ],
                                [
                                    'tariff_heading' => (isset($l->PartidaArancelaria)) ? $l->PartidaArancelaria : 0,
                                    'cabys' => $l->Codigo,
                                    'qty' => $l->Cantidad,
                                    'sku' => $l->UnidadMedida,
                                    'detail' => $l->Detalle,
                                    'price' => '' . $l->PrecioUnitario,
                                    'total_amount' => $l->MontoTotal,
                                    'discounts' => json_encode($discounts),
                                    'subtotal' => $l->SubTotal,
                                    'taxes' => json_encode($taxes),
                                    'tax_net' => (isset($l->ImpuestoNeto)) ? $l->ImpuestoNeto : 0,
                                    'total_amount_line' => $l->MontoTotalLinea,
                                    'id_expense' => $expense->id,
                                    'type' => (Auth::user()->currentTeam->plan_id != 1) ? ((isset($this->productsUpdate[$cont])) ? 0 : 1) : (isset($this->type_line[$cont]) ? $this->type_line[$cont] : 1),
                                    'id_product' => ($this->productsUpdate[$cont] != '') ? Product::where('description', $this->productsUpdate[$cont])->where("products.id_company", "=", Auth::user()->currentTeam->id)->where('active', '1')->first()->id : null,
                                    'id_count' => ($this->countsUpdate[$cont] != '') ?  Count::where('name', $this->countsUpdate[$cont])->where("counts.id_company", "=", Auth::user()->currentTeam->id)->first()->id : null
                                ]
                            );
                            if ($this->productsUpdate[$cont] != '' && count($this->pendingCE) > 0) {
                                $p = Product::find($this->productsUpdate[$cont]);
                                if (!$this->gasper && $p) {
                                    $p->update([
                                        'stock' => $p->stock + $l->Cantidad
                                    ]);
                                }
                            }
                            $cont++;
                        }
                    }
                    if (isset($xml->InformacionReferencia) && $expense->type_doc == '03') {
                        $result = (strlen($xml->InformacionReferencia->Numero) > 20) ? Expense::where('key', $xml->InformacionReferencia->Numero)->first() :
                            Expense::where('consecutive_real', $xml->InformacionReferencia->Numero)->where('id_provider', $provider->id)->first();
                        Payment::create([
                            'date' => date('Y-m-d H:i:s', strtotime((string)$xml->FechaEmision)),
                            'id_company' => Auth::user()->currentTeam->id,
                            'id_expense' => $result->id,
                            'reference' => $expense->consecutive_real,
                            'mount' => $expense->total_document,
                            'observations' => 'Nota de crédito aplicada'
                        ]);
                        $result->update([
                            'pending_amount' => $result->pending_amount - $expense->total_document
                        ]);
                        $expense->update([
                            'pending_amount' => 0
                        ]);
                    }
                    if (count($this->pendingCE) > 0) {
                        foreach ($this->pendingCE as $key => $pending) {
                            Expense::where('key',$pending)->first()->update([
                                'reference_ce' => $expense->id,
                                'pending_amount' => 0
                            ]);
                        }
                    }


                    if ($this->type_proccess != 'guardado') {
                        //Realiza todo el proceso de envio y recepcion al MH
                        $this->processMH($xml, $path);
                        $expense->update([
                            'mh_detail' => $this->detail_mh,
                            'state' => $this->state,
                            'consecutive' => $this->consecutive
                        ]);
                    }


                    $this->moveFiles($path, $path2);
                    $this->dispatchBrowserEvent('messageData', ['messageData' => 'Comprobante guardado con exito', 'refresh' => 1]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // back to form with errors
                    $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion. <br>' . $e->getMessage()]);
                    DB::rollback();
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion <br>' . $e->getMessage()]);
                }
                DB::commit();

                $this->cleanInputs();
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error, Hay ' . $vacias . ' linea(s) sin clasificar.']);
            }
        } else {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error, archivos no encontrados']);
        }
    }
    public function cleanInputs()
    {
        $this->countsUpdate = [];
        $this->productsUpdate = [];
    }
    public function moveFiles($path, $path2)
    {
        $dir = opendir($path);
        if (!file_exists($path2)) {
            mkdir($path2, 0777, true);
        }
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($path . '/' . $file)) {
                    copy($path . '/' . $file, $path2 . '/' . $file);
                } else {
                    copy($path . '/' . $file, $path2 . '/' . $file);
                }
            }
        }
        closedir($dir);
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$path/$file")) ? delTree("$path/$file") : unlink("$path/$file");
        }
        rmdir($path);
    }
    public function processMH($xml, $path)
    {
        $consecutives = Consecutive::where("consecutives.id_branch_offices", "=", $this->id_branch_office)->first();
        $bo = BranchOffice::where('id', $this->id_branch_office)->first();
        switch ($this->type_proccess) {
            case 'aceptado':
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . "0000105" . str_pad($consecutives->c_mra, 10, "0", STR_PAD_LEFT);
                break;
            case 'rechazado':
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . "0000107" . str_pad($consecutives->c_mra, 10, "0", STR_PAD_LEFT);
                break;
            default:
                $this->consecutive = str_pad($bo->number, 3, "0", STR_PAD_LEFT) . "0000105" . str_pad($consecutives->c_mra, 10, "0", STR_PAD_LEFT);
                break;
        }
        $xmlM = Expense::createXMLMenssage($xml, $this->type_proccess, $this->consecutive, $this->number_ea);
        //firmar xml de mensaje
        $firmador = new Firmador();
        $xmlMF = $firmador->firmarXml(Storage::path(Auth::user()->currentTeam->cryptographic_key), Auth::user()->currentTeam->pin, $xmlM, $firmador::TO_XML_STRING);
        //almacenamos el XML firmado
        $xmlMF = simplexml_load_string(trim($xmlMF));
        $xmlMF->asXML($path . '/' . $xml->Clave . '-C.xml');

        //token de acceso al Ministerio de Hacienda
        $token = ConexionMH::tokenMH();
        $respMH = '';
        while ($respMH == '' || $respMH == 'procesando') {
            $result = ConexionMH::sendMessage($this->consecutive, $xml, $xmlMF, json_decode($token)->access_token);
            $respMH = $result["result"];
        }


        if ($result["status"] == "202" || $result["status"] == "200") {
            $this->state = "aceptado";
            $this->detail_mh = $result["result"];
        }
        if ($result["status"] == "401" || $result["status"] == "400") {
            $this->state = "rechazado";
            $this->detail_mh = $result["result"];
        }
        Expense::nextConseutive($this->type_proccess, $this->id_branch_office);
    }
    public function changeAccount($index)
    {
        if (!Count::where('name', $this->countsUpdate[$index])->where("counts.id_company", "=", Auth::user()->currentTeam->id)->first()) {
            $this->countsUpdate[$index] = '';
        }
    }
    public function changeProduct($index)
    {
        if (!Product::where('description', $this->productsUpdate[$index])->where("products.id_company", "=", Auth::user()->currentTeam->id)->where('active', '1')->first()) {
            $this->productsUpdate[$index] = '';
        }
    }
}
