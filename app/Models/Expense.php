<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;

class Expense extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_company',
        'id_branch_office',
        'id_terminal',
        'id_provider',
        'id_buyer',
        'key',
        'consecutive',
        'consecutive_real',
        'e_a',
        'date_issue',
        'expiration_date',
        'possible_deliver_date',
        'deliver_date',
        'sale_condition',
        'term',
        'payment_method',        
        'currency',
        'exchange_rate',
        'total_taxed_services',
        'total_exempt_services',
        'total_exonerated_services',
        'total_taxed_merchandise',
        'total_exempt_merchandise',
        'total_exonerated_merchandise',
        'total_taxed',        
        'total_exempt',
        'total_exonerated',
        'total_discount',
        'total_net_sale',
        'total_tax',
        'total_other_charges',
        'total_document',
        'pending_amount',
        'condition',
        'id_count',
        'id_product',
        'type_purchase',
        'state',
        'ruta',        
        'state_pay',
        'note',
        'id_mh_categories',
        'gasper',
        'id_buyer',
        'pendingCE',
        'type_doc',
        'reference_ce'
        
    ];
    public static function chargeDocs()
    {
        $result = array();
        $path = 'files/recibidos/sin procesar/' . Auth::user()->currentTeam->id_card . '/';
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                $count = 0;
                while (($file = readdir($dir)) !== false) {
                    if (is_dir($path . $file) && $file != '.' && $file != '..') {
                        if (file_exists($path . $file . '/' . basename($file . '.xml'))) {
                            $xml = file_get_contents($path . $file . '/' . basename($file . '.xml'));
                            $xml = simplexml_load_string($xml);
                            $clave = (string) $xml->Clave;
                            $consecutivo = (string) $xml->NumeroConsecutivo;
                            if (substr($consecutivo, 8, 2) != 04) {
                                $emisor = (isset($xml->Emisor)) ? $xml->Emisor : null;
                                $receptor = $xml->Receptor;
                                $condicionVenta = $xml->CondicionVenta;
                                $medioPago = $xml->MedioPago;
                                $detalle = $xml->DetalleServicio;
                                $neto = (string) $xml->ResumenFactura->TotalVentaNeta;
                                $moneda = "CRC";
                                if (isset($xml->ResumenFactura->CodigoTipoMoneda) && (string) $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda != null) {
                                    $moneda = (string) $xml->ResumenFactura->CodigoTipoMoneda->CodigoMoneda;
                                }
                                $tipoCambio = (isset($xml->ResumenFactura->CodigoTipoMoneda->TipoCambio)) ? (string)$xml->ResumenFactura->CodigoTipoMoneda->TipoCambio : 1;
                                $descuentos = 0;
                                if ((string) $xml->ResumenFactura->TotalDescuentos != null) {
                                    $descuentos = (string) $xml->ResumenFactura->TotalDescuentos;
                                }
                                $exoneracion = 0;
                                if (isset($xml->ResumenFactura->TotalExonerado)) {
                                    $exoneracion = (string) $xml->ResumenFactura->TotalExonerado;
                                }
                                $subTotal = (string) $xml->ResumenFactura->TotalVenta;
                                $impuestos = (isset($xml->ResumenFactura->TotalImpuesto)) ? (string) $xml->ResumenFactura->TotalImpuesto : 0;
                                $total = ($xml->ResumenFactura->TotalComprobante)?(string)$xml->ResumenFactura->TotalComprobante:0;
                                $fecha = (string) $xml->FechaEmision;
                                $line = array(
                                    "clave" => $clave,
                                    "consecutivo" => $consecutivo,
                                    "fecha" => $fecha,
                                    "emisor" => $emisor,
                                    "receptor" => $receptor,
                                    "condicionVenta" => $condicionVenta,
                                    "medioPago" => $medioPago,
                                    "detalle" => $detalle,
                                    "neto" => $neto,
                                    "moneda" => $moneda,
                                    "subTotal" => $subTotal,
                                    "tipoCambio" => $tipoCambio,
                                    "descuento" => $descuentos,
                                    "exoneracion" => $exoneracion,
                                    "impuesto" => $impuestos,
                                    "total" => number_format((Integer)$total,2,'.',','),
                                    "ruta" => $path
                                );
                                array_push($result,$line);
                            }
                        }
                    }
                }
                closedir($dir);
            }
        }
        return $result;
    }

    public static function createXMLMenssage($xml, $condition, $consecutive, $ae)
    {

        $xmlString = '<?xml version="1.0" encoding="utf-8"?>
            <MensajeReceptor xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/mensajeReceptor">
            <Clave>' . $xml->Clave . '</Clave>
            <NumeroCedulaEmisor>' . $xml->Emisor->Identificacion[0]->Numero . '</NumeroCedulaEmisor>
            <FechaEmisionDoc>' . $xml->FechaEmision . '</FechaEmisionDoc>
            <Mensaje>' . $condition . '</Mensaje>
            ';
        if ($xml->ResumenFactura->TotalImpuesto == '' || $xml->ResumenFactura->TotalImpuesto == 0) {
            $xmlString .= '<MontoTotalImpuesto>0.00</MontoTotalImpuesto>';
        } else {
            $xmlString .= '<MontoTotalImpuesto>' . $xml->ResumenFactura->TotalImpuesto . '</MontoTotalImpuesto>';
        }
        $xmlString .= '<CodigoActividad>' . $ae . '</CodigoActividad>
            <CondicionImpuesto>04</CondicionImpuesto>
            <MontoTotalDeGastoAplicable>' . $xml->ResumenFactura->TotalComprobante . '</MontoTotalDeGastoAplicable>
            <TotalFactura>' . $xml->ResumenFactura->TotalComprobante . '</TotalFactura>
            <NumeroCedulaReceptor>' . $xml->Receptor->Identificacion[0]->Numero . '</NumeroCedulaReceptor>
            <NumeroConsecutivoReceptor>' . $consecutive . '</NumeroConsecutivoReceptor>
            </MensajeReceptor>';
        return $xmlString;
    }
    public static function nextConseutive($type, $bo)
    {
        $cons = Consecutive::where('consecutives.id_branch_offices', $bo)->first();
        $consecutive = array();
        switch ($type) {
            case 'aceptado':
                $consecutive["c_mra"] = ($cons->c_mra + 1);
                break;
            case 'rechazado':
                $consecutive["c_mrr"] = ($cons->c_mrr + 1);
                break;
            default:
                $consecutive["c_mra"] = ($cons->c_mra + 1);
        }
        Consecutive::where('id_branch_offices', '=', $bo)->update($consecutive);
    }
}
