<?php

namespace App\Http\Livewire\Document;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Mail\InvoiceMail;
use App\Models\BranchOffice;
use App\Models\Cantons;
use App\Models\Category;
use App\Models\ClassProduct;
use App\Models\Count;
use App\Models\CountryCodes;
use App\Models\Currencies;
use App\Models\Discount;
use App\Models\Districts;
use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\Family;
use App\Models\PaymentInvoice;
use App\Models\PaymentMethods;
use App\Models\Product;
use App\Models\Province;
use App\Models\SaleConditions;
use App\Models\Sku;
use App\Models\Skuse;
use App\Models\Team;
use App\Models\Terminal;
use PDF;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\WithFileUploads;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use ZipArchive;

class DocumentsComponent extends Component
{
    use WithFileUploads;
    public $file_import, $type_document, $id_branch_office, $id_expense, $key, $consecutive, $date,
        $sale_condition, $id_client, $payment_method, $currency, $exchange_rate, $price_list_id,
        $type_doc, $id_terminal, $delivery_date, $subtotal, $discount = 0, $total, $totalDiscount,
        $totalTax, $totalExoneration = 0, $sku, $id_tax, $cabys, $detail, $number_ea, $otherCharge,
        $id_type_reference, $name_client, $id_card, $name, $code_payment_method, $id_type_document_other,
        $code_sale_condition, $id_mh_category, $priority, $idcard_other, $name_other, $phone,
        $mail, $id_seller, $price = 0, $qty = 1, $cost = 0, $name_product, $qty_dispatch,
        $detail_other_charge, $id_product, $details = [], $tax, $exoneration, $tariff_heading = 0,
        $total_exonerated_merchandise = 0, $total_other_charges = 0, $state_send = 'Sin enviar',
        $iva_returned = 0, $reason, $id_reference, $amount_other, $date_reference, $number_reference,
        $code_currency, $term, $answer_mh = 'Ninguna', $detail_mh = 'Ninguno', $note = '',
        $is_orden = false, $key_send, $mail_send, $cc_mail, $total_exonerated_services = 0, $total_exempt_services = 0, $total_taxed_merchandise = 0;
    public $allDiscounts, $allPayments = [], $allLines = [], $allPriceList = [], $allProducts,
        $allCurrencies, $allMHCategories, $allSaleConditions, $allSellers, $allEconomicActivities,
        $allBranchOffices, $allTerminals, $allSkuses, $allTaxes, $allTypeReferences, $allOtherCharges = [], $allTypeDocumentOtherCharges, $allClients, $allPaymentMethods,
        $allReferences, $total_cost, $pre, $id_count, $allAcounts, $reference, $observations;
    protected $listeners = ['imprimir_tiquete', 'ticketPDF'];
    public function mount()
    {
        
        $this->allDiscounts = Discount::where("id_company", Auth::user()->currentTeam->id)->get();
    }
    public function render()
    {
        // $this->listarArchivos();
        return view('livewire.document.documents-component');
    }


    public function cleanInputs()
    {
        $this->allAcounts = Count::whereIn("id_count_primary", [34, 35])->where("counts.id_company", Auth::user()->currentTeam->id)->get();
        $this->id_count = isset($this->allAcounts[0]->id) ? $this->allAcounts[0]->id : '';
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
        $this->reason = '';
        $this->cc_mail = '';
        $this->discount = 0;
        $this->total_cost = 0;
    }
    public function importDocuments()
    {
        $this->validate([
            'file_import' => 'required|mimes:zip|max:2048', // 5MB Max
        ]);
        try {
            $rute = $this->file_import->getRealPath();
            //creamos un array para guardar el nombre de los archivos que contiene el ZIP
            $nombresFichZIP = array();
            $zip = new ZipArchive;
            if ($zip->open($rute) === TRUE) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    //obtenemos ruta que tendrán los documentos cuando los descomprimamos
                    $nombresFichZIP['tmp_name'][$i] = 'files/importar/' . $zip->getNameIndex($i);
                    //obtenemos nombre del fichero
                    $nombresFichZIP['name'][$i] = $zip->getNameIndex($i);
                }
                //descomprimimos zip
                $zip->extractTo('files/importar/');
                $zip->close();
                $this->saveDocImport();
            } else {
                $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al abrir el archivo .zip']);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al cargar los archivos' . $e->getMessage()]);
        }
    }
    public function saveDocImport()
    {
        $result = array();
        $path = 'files/importar';
        if (is_dir($path)) {

            if ($dir = opendir($path)) {
                while (($file = readdir($dir)) !== false) {
                    if ($file != '.' && $file != '..' && strpos($file, '.xml') !== false) {
                        $this->storeDoc($path, $file);
                    }
                }
                closedir($dir);
            }
        }
        return $result;
    }
    public function storeDoc($path, $file)
    {

        if (file_exists($path . '/' . $file)) {
            $xml = file_get_contents($path . '/' . $file);
            $xml = simplexml_load_string($xml);
            if (isset($xml->NumeroConsecutivo)) {
                $this->consecutive =  (string)$xml->NumeroConsecutivo;
                DB::beginTransaction();
                try {
                    $province = Province::serachByCode($xml->Receptor->Ubicacion->Provincia);
                    $canton = Cantons::serachByCode($province->id, $xml->Receptor->Ubicacion->Canton);
                    $district = Districts::serachByCode($canton->id, $xml->Receptor->Ubicacion->Distrito);
                    $country_code = (isset($xml->Receptor->Telefono->CodigoPais)) ? CountryCodes::serachByCode($xml->Receptor->Telefono->CodigoPais) : CountryCodes::serachByCode('506');
                    $sale_condition = (isset($xml->CondicionVenta)) ? SaleConditions::serachByCode($xml->CondicionVenta) : SaleConditions::serachByCode('01');
                    $currency = (isset($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda)) ? Currencies::serachByCode($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda) : Currencies::serachByCode('CRC');
                    $payment = (isset($xml->MedioPago)) ? PaymentMethods::serachByCode($xml->MedioPago) : PaymentMethods::serachByCode('01');
                    $client = Client::where('id_company', Auth::user()->currentTeam->id)
                        ->where('id_card', $xml->Receptor->Identificacion->Numero)->first();
                    if ($client === null) {
                        $client = Client::updateOrCreate(
                            [
                                'id_company' => Auth::user()->currentTeam->id,
                                'id_card' => (string)$xml->Receptor->Identificacion->Numero,
                            ],
                            [
                                'code' => '00000',
                                'id_company' => Auth::user()->currentTeam->id,
                                'id_card' => $xml->Receptor->Identificacion->Numero,
                                'type_id_card' => $xml->Receptor->Identificacion->Tipo,
                                'name_client' => $xml->Receptor->Nombre,
                                'id_province' => $province->id,
                                'id_canton' => $canton->id,
                                'id_district' => $district->id,
                                'other_signs' => (isset($xml->Receptor->Ubicacion->OtrasSenas)) ? $xml->Receptor->Ubicacion->OtrasSenas : 'Ninguna',
                                'id_country_code' => $country_code->id,
                                'phone' => (isset($xml->Receptor->Telefono->NumeroTelefono)) ? $xml->Receptor->Telefono->NumeroTelefono : '22222222',
                                'emails' => (isset($xml->Receptor->CorreoElectronico)) ? $xml->Receptor->CorreoElectronico : '1@1.com',
                                'id_sale_condition' => $sale_condition->id,
                                'time' => $time = (isset($xml->Receptor->PlazoCredito)) ? $xml->Receptor->PlazoCredito : '1',
                                'id_currency' => $currency->id,
                                'id_payment_method' => $payment->id
                            ]
                        );
                    }
                    $bo = BranchOffice::where('id_company', Auth::user()->currentTeam->id)->where('number', substr($xml->NumeroConsecutivo, 0, 3))->first();
                    if (!$bo) {
                        $bo = BranchOffice::where('id_company', Auth::user()->currentTeam->id)->first();
                    }
                    $terminal = Terminal::where('id_company', Auth::user()->currentTeam->id)->where('id_branch_office', $bo->id)->first();
                    $document = Document::updateOrCreate(
                        [
                            'id_company' => Auth::user()->currentTeam->id,
                            'key' => $xml->Clave
                        ],
                        [
                            'id_company' => Auth::user()->currentTeam->id,
                            'id_branch_office' => $bo->id,
                            'id_terminal' => $terminal->id,
                            'id_client' => $client->id,
                            'key' => $xml->Clave,
                            'consecutive' => $xml->NumeroConsecutivo,
                            'e_a' => $xml->CodigoActividad,
                            'date_issue' => $emision = date('Y-m-d H:i:s', strtotime((string)$xml->FechaEmision)),
                            'delivery_date' => $emision,
                            'sale_condition' => $xml->CondicionVenta,
                            'term' => (isset($xml->PlazoCredito)) ? $xml->PlazoCredito : 1,
                            'payment_method' => $xml->MedioPago,
                            'currency' => (isset($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda)) ? $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda : 'CRC',
                            'exchange_rate' => (isset($xml->ResumenFactura->CodigoTipoMoneda->TipoCambio)) ? (($xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda == 'CRC') ? 1 : $xml->ResumenFactura->CodigoTipoMoneda->TipoCambio) : '1',
                            'id_mh_categories' => (isset($xml->ResumenFactura->TotalServGravados)) ? (($xml->ResumenFactura->TotalServGravados > 0) ? 3 : 1) : 1,
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
                            'balance' => $xml->ResumenFactura->TotalComprobante,
                            'state_send' => 'enviado',
                            'answer_mh' => 'aceptado',
                            'detail_mh' => 'Ninguno',
                            'type_doc' => substr($xml->NumeroConsecutivo, 8, 2),
                            'note' => 'Documento importado',
                            'path' => 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $xml->Clave

                        ]
                    );

                    if (isset($xml->DetalleServicio->LineaDetalle)) {

                        $cont = 0;
                        foreach ($xml->DetalleServicio->LineaDetalle as $index => $l) {
                            $class = ClassProduct::where('id_company', Auth::user()->currentTeam->id)->first();
                            $type = ($l->UnidadMedida == 'Sp' || $l->UnidadMedida == 'Spe') ? 24 : 23;

                            $product =  Product::firstOrCreate(
                                [
                                    'id_company' => Auth::user()->currentTeam->id,
                                    'description' => $l->Detalle
                                ],
                                [
                                    'id_company' => Auth::user()->currentTeam->id,
                                    'internal_code' => $class->symbol . '-' . str_pad($class->consecutive, 6, "0", STR_PAD_LEFT),
                                    'cabys' => $l->Codigo,
                                    'id_count_income' => Count::where("id_count_primary", 20)->where("counts.id_company", Auth::user()->currentTeam->id)->first()->id,
                                    'id_count_expense' => Count::where("id_count_primary", $type)->where("counts.id_company", Auth::user()->currentTeam->id)->first()->id,
                                    'id_count_inventory' => Count::where("id_count_primary", 4)->where("counts.id_company", Auth::user()->currentTeam->id)->first()->id,
                                    'id_sku' => (Skuse::where('symbol', (string)$l->UnidadMedida)->first()->id) ? Skuse::where('symbol', (string)$l->UnidadMedida)->first()->id : 'Sp',
                                    'id_class' => $class->id,
                                    'description' => (string)$l->Detalle,
                                    'tax_base' => 0,
                                    'export_tax' => 0,
                                    'stock_start' => 1,
                                    'stock' => 1,
                                    'price_unid' => 0,
                                    'cost_unid' => 0,
                                    'id_discount' => null,
                                    'total_tax' => 0,
                                    'stock_base' => 0,
                                    'alert_min' => 0,
                                    'total_price' => 0,
                                    'id_category' => Category::where('id_company', Auth::user()->currentTeam->id)->first()->id,
                                    'id_family' => Family::where('id_company', Auth::user()->currentTeam->id)->first()->id,
                                    'ids_taxes' => '[]',
                                    'type' => 1,
                                    'id_bo' => null,
                                    'id_type_code_product' => NULL,
                                    'other_code' => NULL,
                                ]
                            );
                            $count = '';
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

                            DocumentDetail::updateOrCreate(
                                [
                                    'id_document' => $document->id,
                                    'detail' => $l->Detalle
                                ],
                                [
                                    'id_document' => $document->id,
                                    'tariff_heading' => (isset($l->PartidaArancelaria)) ? $l->PartidaArancelaria : 0,
                                    'code' => $l->Codigo,
                                    'qty' => $l->Cantidad,
                                    'qty_dispatch' => $l->Cantidad,
                                    'sku' => $l->UnidadMedida,
                                    'detail' => $l->Detalle,
                                    'price_unid' => '' . $l->PrecioUnitario,
                                    'cost_unid' => 0,
                                    'total_amount' => $l->MontoTotal,
                                    'discounts' => json_encode($discounts),
                                    'subtotal' => $l->SubTotal,
                                    'taxes' => json_encode($taxes),
                                    'tax_net' => (isset($l->ImpuestoNeto)) ? $l->ImpuestoNeto : 0,
                                    'total_amount_line' => $l->MontoTotalLinea,
                                    'id_product' => $product->id,
                                    'id_count' => null
                                ]
                            );
                        }
                    }
                    if (isset($xml->InformacionReferencia) && $document->type_doc == '03') {
                        $result = (strlen($xml->InformacionReferencia->Numero) > 20) ? Document::where('key', $xml->InformacionReferencia->Numero)->first() :
                            Document::where('consecutive', $xml->InformacionReferencia->Numero)->where('id_client', $client->id)->first();
                        PaymentInvoice::create([
                            'date' => date('Y-m-d H:i:s', strtotime((string)$xml->FechaEmision)),
                            'id_company' => Auth::user()->currentTeam->id,
                            'id_expense' => $result->id,
                            'reference' => $document->consecutive,
                            'mount' => $document->total_document,
                            'observations' => 'Nota de crédito aplicada'
                        ]);
                        $result->update([
                            'pending_amount' => $result->pending_amount - $document->total_document
                        ]);
                        $document->update([
                            'pending_amount' => 0
                        ]);
                    }
                    $this->moveFiles($path, $file, $xml->Clave);
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
            }
        }
    }
    public function moveFiles($path, $file, $clave)
    {
        $path2 = 'files/creados/' . Auth::user()->currentTeam->id_card . '/' . $clave;
        if (!file_exists($path2)) {
            mkdir($path2, 0777, true);
        }
        rename($path . '/' . $file, $path2 . '/' . $clave . '-Firmado.xml');
        if ($dir = opendir($path)) {
            while (($file = readdir($dir)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (strpos($file, $clave) !== false) {
                        if (strpos($file, '.pdf') !== false) {
                            rename($path . '/' . $file, $path2 . '/' . $clave . '.pdf');
                        }
                        if (strpos($file, '.xml') !== false) {
                            rename($path . '/' . $file, $path2 . '/' . $clave . '-R.xml');
                        }
                    }
                }
            }
            closedir($dir);
        }
    }
    public function imprimir_tiquete()
    {
        try {
            $nombreImpresora = "casa";
            $connector = new WindowsPrintConnector($nombreImpresora);
            $impresora = new Printer($connector);
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('errorData', ['errorData' => 'Error al ingresar la informacion <br>' . $e->getMessage()]);
        }
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(2, 2);
        $impresora->text("Imprimiendo\n");
        $impresora->text("ticket\n");
        $impresora->text("desde\n");
        $impresora->text("Laravel\n");
        $impresora->setTextSize(1, 1);
        $impresora->text("https://parzibyte.me");
        $impresora->feed(5);
        $impresora->close();
    }

    function listarArchivos()
    {
        // Abrimos la carpeta que nos pasan como parámetro
        $listado = [];
        $path = 'files/creados/';
        $dir = opendir($path);
        // Leo todos los ficheros de la carpeta
        while ($elemento = readdir($dir)) {
            // Tratamos los elementos . y .. que tienen todas las carpetas
            if ($elemento != "." && $elemento != "..") {
                $path2 = $path . $elemento . '/';
                $dir2 = opendir($path2);
                while ($elemento2 = readdir($dir2)) {
                    // Tratamos los elementos . y .. que tienen todas las carpetas
                    if ($elemento2 != "." && $elemento2 != "..") {
                        $path3 = $path2 . $elemento2 . '/';
                        $dir3 = opendir($path3);
                        while ($elemento3 = readdir($dir3)) {
                            // Tratamos los elementos . y .. que tienen todas las carpetas
                            if ($elemento3 != "." && $elemento3 != "..") {
                                // Si es una carpeta
                                if (!is_dir($path3 . $elemento3)) {
                                    // Muestro el fichero
                                    // $ruta =  $path3 . $elemento3;
                                    //  $ruta =  str_replace('FI', 'FC', $ruta);
                                    //rename($path3 . $elemento3, $ruta);
                                    array_push($listado, $path3 . $elemento3);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function ticketPDF($id)
    {
        $result = array();
        $doc = Document::where("id", $id)->first();
        if ($doc) {
            $result["key"] = $doc->key;
            $result["consecutive"] = (string) $doc->consecutive;
            $result["ced_emisor"] = Auth::user()->currentTeam->id_card;
            $result["name_compania"] = Auth::user()->currentTeam->name;
            $result["emisor"] = BranchOffice::find($doc->id_branch_office);
            $result["terminal"] = Terminal::find($doc->id_terminal)->number;
            $result["receptor"] = Client::where('id', $doc->id_client)->first();
            $result["detail"] = DocumentDetail::where("id_document", $doc->id)->get();
            $result["type_doc"] = $doc->type_doc;
            $result["typeDoc"] = $this->typeDoc($doc->type_doc);
            $result["subTotal"]  = $doc->total_net_sale;
            $result["tax"]  = $doc->total_tax;
            $result["desc"]  = $doc->total_discount;
            $result["total"]  = $doc->total_document;
            $result["date"]  = (string) $doc->date_issue;
            $result["otrosCargos"] = $doc->total_other_charges;
        } else {
            echo "Documento no encontrado";
            return 0;
        }
        $pdf = PDF::loadView('livewire.document.generar_ticket', $result);
        $pdf->setPaper('letther', 'portrait');
        return $pdf->stream($doc->key . '.pdf');
    }
    public function typeDoc($type)
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
                return 'Factura';
                break;
            case '99':
                return 'Cotización';
                break;
            default:
                return 'Orden de Venta';
        }
    }
}
