<?php

namespace App\Models;

use App\Hacienda\Firmador;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConexionMH extends Model
{
    use HasFactory;

    public static function tokenMH($company)
    {
        $url = '';
        $client_secret = "";
        $grant_type = "password";
        //selecccion e acceso a DB
        if ($company->proof == '1') {
            $client_id = "api-stag";
            $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token";
        } else {
            $client_id = "api-prod";
            $url = "https://idp.comprobanteselectronicos.go.cr/auth/realms/rut/protocol/openid-connect/token";
        }
        //Solicitud de un nuevo token
        if ($grant_type == "password") {
            $username = $company->user_mh;
            $password = $company->pass_mh;

            //Validation de los datos necesarios
            if ($client_id == '') {
                $result = array("status" => "400", "message" => "El parametro Client ID es requerido");
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
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, 'Content-Type: application/x-www-form-urlencoded');
        $data = http_build_query($data);
        //$data = rtrim($data, '&');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //("JSON: ".$data);

        $respuesta  = curl_exec($curl);
        $status     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err        = json_decode(curl_error($curl));
        curl_close($curl);
        //consulta y resultado
        if ($err) {
            $arrayResp = array(
                "Status"    => $status,
                "text"      => $err
            );
            return $arrayResp;
        } else
            return $respuesta;
    }

    public static function sendMessage($company, $consecutive, $xml, $XMLFirmado, $token)
    {
        $fecha = date('Y-m-d\TH:i:s');
        if ($company->proof == '1') {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion");
        } else {
            $curl = curl_init("https://api.comprobanteselectronicos.go.cr/recepcion/v1/recepcion");
        }
        $datos = array(
            'clave' => (string) $xml->Clave,
            'fecha' => $fecha,
            'emisor' => array(
                'tipoIdentificacion' => (string) $xml->Emisor->Identificacion->Tipo,
                'numeroIdentificacion' => (string) $xml->Emisor->Identificacion->Numero
            ),
            'receptor' => array(
                'tipoIdentificacion' => (string) $xml->Receptor->Identificacion->Tipo,
                'numeroIdentificacion' => (string) $xml->Receptor->Identificacion->Numero
            ),
            'consecutivoReceptor' => $consecutive,
            'comprobanteXml' => base64_encode($XMLFirmado)
        );
        //$datosJ= http_build_query($datos);
        $mensaje = json_encode($datos);
        $header = array(
            'Authorization: bearer ' . $token,
            'Content-Type: application/json'
        );
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $respuesta = curl_exec($curl);

        list($headers, $response) = explode("\r\n\r\n", $respuesta, 2);
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

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result = array("status" => $status, "message" => $respuesta, "result" => $respuesta);
        } else {
            $result = array("status" => $status, "message" => $respuesta, "result" => "Ninguno");
        }

        return $result;
    }
    public static function createXML($type_doc, $company, $bo, $te, $client, $doc, $allLines, $allOtherCharges, $number_ea)
    {
        $keyDoc = $bo->phone_code . date("d") . date("m") . date("y") . str_pad($company->id_card, 12, "0", STR_PAD_LEFT) . $doc->consecutive . '119890717';
        $xml = '';
        $xml = header($type_doc) . '
        <Clave>' . $keyDoc . '</Clave>
        <CodigoActividad>' . $number_ea . '</CodigoActividad>
        <NumeroConsecutivo>' . $doc->consecutive . '</NumeroConsecutivo>
        <FechaEmision>' . date('Y-m-d\TH:i:s', strtotime($doc->date_issue)) . '</FechaEmision>
        <Emisor>
        <Nombre>' . $company->name . '</Nombre>
        <Identificacion>
        <Tipo>' . '0' . $company->type_id_card . '</Tipo>
        <Numero>' . $company->id_card . '</Numero>
        </Identificacion>
        <NombreComercial>' . $company->name . '</NombreComercial>
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
        </Emisor>';

        if ($client) {
            if ($type_doc == '04' && $client->id_card == '000000000') {
            } else {
                $xml .=  '<Receptor>
            <Nombre>' . $client->name_client . '</Nombre>
            <Identificacion>
            <Tipo>' . '0' . $client->type_id_card . '</Tipo>
            <Numero>' . $client->id_card . '</Numero>
            </Identificacion>
            <NombreComercial>' . $client->name_client . '</NombreComercial>
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
            </Receptor>';
            }
        }
        $xml .= '<CondicionVenta>' . $doc->sale_condition . '</CondicionVenta>
        <PlazoCredito>' . $doc->term . '</PlazoCredito>
        <MedioPago>' . (($doc->payment_method != '06') ? $doc->payment_method : '03') . '</MedioPago>
        <DetalleServicio>';
        foreach ($allLines as $index => $line) {
            $xml .= '<LineaDetalle>
            <NumeroLinea>' . ($index + 1) . '</NumeroLinea>';
            if ($type_doc == '09') {
                $xml .= '<PartidaArancelaria>' . $line['tariff_heading'] . '</PartidaArancelaria>';
            }
            $xml .= '<Codigo>' . str_pad($line['cabys'], 13, '0', STR_PAD_LEFT) . '</Codigo>
            <Cantidad>' . $line['qty'] . '</Cantidad>
            <UnidadMedida>' . $line['sku'] . '</UnidadMedida>
            <Detalle>' . $line['description'] . '</Detalle>
            <PrecioUnitario>' . $line['price'] . '</PrecioUnitario>
            <MontoTotal>' . bcdiv($line['price'] * $line['qty'], '1', 5) . '</MontoTotal>';
            if ($line['discount'] != 0) {
                $xml .= '<Descuento>
                <MontoDescuento>' . $line['discount'] . '</MontoDescuento>
                <NaturalezaDescuento>Descuento General</NaturalezaDescuento>
                </Descuento>';
            }
            $xml .= '<SubTotal>' . bcdiv((($line['price'] * $line['qty']) - $line['discount']), '1', 5) . '</SubTotal>';
            if ($line['tax']) {
                $xml .= '<Impuesto>
                <Codigo>' . str_pad($line['tax']['code'], 2, "0", STR_PAD_LEFT) . '</Codigo>
                <CodigoTarifa>' . str_pad($line['tax']['rate_code'], 2, "0", STR_PAD_LEFT) . '</CodigoTarifa>
                <Tarifa>' . $line['tax']['rate'] . '</Tarifa>
                <Monto>' . $line['tax']['mount'] . '</Monto>';
                if (isset($line['tax']['exoneration']['AmountExoneration'])) {
                    $xml .= '<Exoneracion>
                    <TipoDocumento>' . str_pad($line['tax']['exoneration']['DocumentType'], 2, "0", STR_PAD_LEFT)  . '</TipoDocumento>
                    <NumeroDocumento>' . $line['tax']['exoneration']['DocumentNumber'] . '</NumeroDocumento>
                    <NombreInstitucion>' . $line['tax']['exoneration']['InstitucionalName'] . '</NombreInstitucion>
                    <FechaEmision>' . date('Y-m-d\TH:i:s', strtotime($line['tax']['exoneration']['EmisionDate'])) . '</FechaEmision>
                    <PorcentajeExoneracion>' . $line['tax']['exoneration']['PercentageExoneration'] . '</PorcentajeExoneracion>
                    <MontoExoneracion>' . $line['tax']['exoneration']['AmountExoneration'] . '</MontoExoneracion>
                    </Exoneracion>';
                }

                $xml .= '</Impuesto>';
            }
            $xml .= '<ImpuestoNeto>' . $in = ((isset($line['tax']['exoneration']['AmountExoneration'])) ? $line['tax']['mount'] - $line['tax']['exoneration']['AmountExoneration'] : $line['tax']['mount']) . '</ImpuestoNeto>
            <MontoTotalLinea>' . $line['totalLine'] . '</MontoTotalLinea>
            </LineaDetalle>';
        }
        $xml .= '</DetalleServicio>';
        $xml .= '<ResumenFactura>
        <CodigoTipoMoneda>
        <CodigoMoneda>' . $doc->currency . '</CodigoMoneda>
        <TipoCambio>' . $doc->exchange_rate . '</TipoCambio>
        </CodigoTipoMoneda>
        <TotalServGravados>' . $doc->total_taxed_services . '</TotalServGravados>
        <TotalServExentos>' . $doc->total_exempt_services . '</TotalServExentos>
        <TotalServExonerado>' . $doc->total_exonerated_services . '</TotalServExonerado>
        <TotalMercanciasGravadas>' . $doc->total_taxed_merchandise . '</TotalMercanciasGravadas>
        <TotalMercanciasExentas>' . $doc->total_exempt_merchandise . '</TotalMercanciasExentas>
        <TotalMercExonerada>' . $doc->total_exonerated_merchandise . '</TotalMercExonerada>
        <TotalGravado>' . ($doc->total_taxed_services + $doc->total_taxed_merchandise) . '</TotalGravado>
        <TotalExento>' . ($doc->total_exempt_services + $doc->total_exempt_merchandise) . '</TotalExento>
        <TotalExonerado>' . ($doc->total_exonerated_services + $doc->total_exonerated_merchandise) . '</TotalExonerado>
        <TotalVenta>' . ($doc->total_taxed_services + $doc->total_taxed_merchandise + $doc->total_exempt_services + $doc->total_exempt_merchandise + $doc->total_exonerated_services + $doc->total_exonerated_merchandise) . '</TotalVenta>
        <TotalDescuentos>' . $doc->total_discount . '</TotalDescuentos>
        <TotalVentaNeta>' . ($doc->total_taxed_services + $doc->total_taxed_merchandise + $doc->total_exempt_services + $doc->total_exempt_merchandise + $doc->total_exonerated_services + $doc->total_exonerated_merchandise - $doc->total_discount) . '</TotalVentaNeta>
        <TotalImpuesto>' . ($doc->total_taxed - $doc->total_exonerated) . '</TotalImpuesto>
        <TotalIVADevuelto>' . $doc->iva_returned . '</TotalIVADevuelto>
        <TotalOtrosCargos>' . $doc->total_other_charges . '</TotalOtrosCargos>
        <TotalComprobante>' . $doc->total_document . '</TotalComprobante>
        </ResumenFactura>';
        $xml .= footer($doc->type_doc);
        $xmlt = simplexml_load_string(trim($xml));
        $path = 'files/creados/' . $company->id_card . '/' . $keyDoc;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $xmlt->asXML($path . '/' . $keyDoc . '.xml');
        //firmar xml de mensaje
        $firmador = new Firmador();

        $xmlF = $firmador->firmarXml(Storage::path($company->cryptographic_key), $company->pin, trim($xml), $firmador::TO_XML_STRING);
        //almacenamos el XML firmado
        $xmlSave = simplexml_load_string(trim($xmlF));
        $xmlSave->asXML($path . '/' . $keyDoc . '-Firmado.xml');
        $doc->update([
            'path' => $path
        ]);
        return $xml;
    }
    public static function footer($type)
    {
        $header = "";
        switch ($type) {
            case '01':
                $header = '</FacturaElectronica>';
                break;
            case '02':
                $header = '</NotaDebitoElectronica>';
                break;
            case '03':
                $header = '</NotaCreditoElectronica>';
                break;
            case '04':
                $header = '</TiqueteElectronico>';
                break;
            case '08':
                $header = '</FacturaElectronicaCompra>';
                break;
            case '09':
                $header = '</FacturaElectronicaExportacion>';
                break;
            default:
                $header = '</FacturaElectronica>';
        }
        return $header;
    }

    public static function header($type)
    {
        $header = "";
        switch ($type) {
            case '01':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '02':
                $header = '<?xml version="1.0" encoding="utf-8"?><NotaDebitoElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/notaDebitoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '03':
                $header = '<?xml version="1.0" encoding="utf-8"?><NotaCreditoElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/notaCreditoElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '04':
                $header = '<?xml version="1.0" encoding="utf-8"?><TiqueteElectronico xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/tiqueteElectronico" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '08':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronicaCompra xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronicaCompra" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            case '09':
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronicaExportacion xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronicaExportacion" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
                break;
            default:
                $header = '<?xml version="1.0" encoding="utf-8"?><FacturaElectronica xmlns="https://cdn.comprobanteselectronicos.go.cr/xml-schemas/v4.3/facturaElectronica" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        }
        return $header;
    }
    
}
