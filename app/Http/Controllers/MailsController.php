<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class MailsController extends Controller
{
    public function chargeMails()
    {
        header('Content-Type: text/html; charset=UTF-8');
        // Connect to gmail
        $hostname = '{imap.hostinger.es:993/imap/ssl/novalidate-cert}INBOX';
        $username = "facturas@samsatec.com";
        $password = "Samsatec.2021";
        echo "conectandos.... ";
        /* try to connect */
        if ($username != null) {
            $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
            echo "conectado <br>";
            /* grab emails */
            $emails = imap_search($inbox, 'FROM ' . $username);
            $emails = imap_search($inbox, 'UNSEEN');

            /* if emails are returned, cycle through each... */
            if ($emails) {
                /* begin output var */
                $output = '';

                /* put the newest emails on top */
                rsort($emails);
                $cont = 1;
                foreach ($emails as $email_number) { //recorre emails
                    echo "<br>Correo #" . $cont++ . "<br>";
                    /* get information specific to this email */
                    $structure = imap_fetchstructure($inbox, $email_number);
                    $claveXML = "";
                    $archXMLF = "";
                    $archXMLM = "";
                    $archPDFF = "";
                    $bandera = "";

                    $attachments = array();
                    if (isset($structure->parts) && (count($structure->parts) - 1) > 0) { //if partes email
                        echo "Adjuntos: " . (count($structure->parts) - 1) . "<br>";
                        for ($i = 1; $i < count($structure->parts); $i++) { // for recorre partes

                            if (strcasecmp($structure->parts[$i]->subtype, "XML") === 0 || strcasecmp($structure->parts[$i]->subtype, "PDF") === 0 || strcasecmp($structure->parts[$i]->subtype, "OCTET-STREAM") === 0 || strcasecmp($structure->parts[$i]->subtype, "zip") === 0) { //if tipo de estructuras
                                echo "Adjunto: " . $i . $structure->parts[$i]->subtype . "<br>";
                                $attachments[$i] = array(
                                    'is_attachment' => false,
                                    'filename' => '',
                                    'name' => '',
                                    'attachment' => ''
                                );
                                if ($structure->parts[$i]->ifdparameters) {
                                    foreach ($structure->parts[$i]->dparameters as $object) {
                                        if (strtolower($object->attribute) == 'filename') {
                                            $attachments[$i]['is_attachment'] = true;
                                            $attachments[$i]['filename'] = imap_utf8($object->value);
                                        }
                                    }
                                }
                                if ($structure->parts[$i]->ifparameters) {
                                    foreach ($structure->parts[$i]->parameters as $object) {
                                        if (strtolower($object->attribute) == 'name') {
                                            $attachments[$i]['is_attachment'] = true;
                                            $attachments[$i]['filename'] = imap_utf8($object->value);
                                        }
                                    }
                                }
                                if ($attachments[$i]['is_attachment']) { // if adjunto
                                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);
                                    if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                                    } //fin encode 3 
                                    else { // 4 = QUOTED-PRINTABLE
                                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                                    } // fin encode 4
                                } //fin if adjunto
                            } // fin tipos de archivo
                            if (strcasecmp($structure->parts[$i]->subtype, "MIXED") === 0) { //if MIXED
                                echo "Adjuntos MIXEDs: " . $i . $structure->parts[$i]->subtype . "<br>";
                                for ($j = 1; $j < count($structure->parts[$i]->parts); $j++) { // for recorre partes                            
                                    $attachments2[$j] = array(
                                        'is_attachment' => false,
                                        'filename' => '',
                                        'name' => '',
                                        'attachment' => ''
                                    );
                                    if ($structure->parts[$i]->parts[$j]->ifdparameters) {
                                        foreach ($structure->parts[$i]->parts[$j]->dparameters as $object) {
                                            if (strtolower($object->attribute) == 'filename') {
                                                $attachments2[$j]['is_attachment'] = true;
                                                $attachments2[$j]['filename'] = $object->value;
                                            }
                                        }
                                    }
                                    if ($structure->parts[$i]->parts[$j]->ifparameters) {
                                        foreach ($structure->parts[$i]->parts[$j]->parameters as $object) {
                                            if (strtolower($object->attribute) == 'name') {
                                                $attachments2[$j]['is_attachment'] = true;
                                                $attachments2[$j]['filename'] = $object->value;
                                            }
                                        }
                                    }
                                    if ($attachments2[$j]['is_attachment']) { // if adjunto
                                        $attachments2[$j]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);
                                        if ($structure->parts[$i]->parts[$j]->encoding == 3) { // 3 = BASE64
                                            $attachments2[$j]['attachment'] = base64_decode($attachments2[$j]['attachment']);
                                        } //fin encode 3 
                                        else { // 4 = QUOTED-PRINTABLE
                                            $attachments2[$j]['attachment'] = quoted_printable_decode($attachments2[$j]['attachment']);
                                        } // fin encode 4
                                    } //fin if adjunto
                                } // end if MIXED
                            }
                        } //fin for partes


                        for ($h = 1; $h <= count($attachments); $h++) {
                           
                            if (count($attachments) > 1 && (strpos(strtolower($attachments[1]['filename']), ".pdf"))) {
                               
                                $temp = $attachments[1];
                                $attachments[1] = $attachments[2];
                                $attachments[2] = $temp;
                            }
                            if ($attachments[$h]['is_attachment']) {
                                if ((strpos(strtolower($attachments[$h]['filename']), ".xml"))) {
                                    libxml_use_internal_errors(true);
                                    if ($xml = simplexml_load_string($attachments[$h]['attachment'])) {
                                        echo $xml;
                                        $claveXML = $xml->Clave;

                                        if (strrpos($attachments[$h]['attachment'], 'mensajeHacienda') || strrpos($attachments[$h]['attachment'], 'MensajeHacienda')) {
                                            $carpeta = 'files/recibidos/sin procesar/' . $xml->NumeroCedulaReceptor . '/' . $xml->Clave . '/';
                                            $cedula = $xml->NumeroCedulaReceptor;
                                            if (!file_exists($carpeta)) {
                                                mkdir($carpeta, 0777, true);
                                            }
                                            $nombre_fichero = $carpeta . $xml->Clave . '-R.xml';
                                            $xml->asXML($nombre_fichero);
                                            echo "Nombre archivo : " . $nombreFichero = $attachments[$h]['filename'] . "<br>";
                                            echo "Guardado xml respuesta <br>";
                                        }
                                        if (
                                            strrpos($attachments[$h]['attachment'], 'facturaElectronica') || strrpos($attachments[$h]['attachment'], 'FacturaElectronica') || strrpos($attachments[$h]['attachment'], 'tiqueteElectronico') || strrpos($attachments[$h]['attachment'], 'TiqueteElectronico') ||
                                            strrpos($attachments[$h]['attachment'], 'NotaCreditoElectronica') || strrpos($attachments[$h]['attachment'], 'notaCreditoElectronica')
                                        ) {
                                            $claveXML = $xml->Clave;
                                            $cedula = $xml->Receptor->Identificacion->Numero;
                                            $carpeta = 'files/recibidos/sin procesar/' . $xml->Receptor->Identificacion->Numero . '/' . $xml->Clave . '/';
                                            if (!file_exists($carpeta)) {
                                                mkdir($carpeta, 0777, true);
                                            }
                                            $nombre_fichero = $carpeta . $xml->Clave . '.xml';
                                            $xml->asXML($nombre_fichero);
                                            echo "Nombre archivo : " . $nombreFichero = $attachments[$h]['filename'] . "<br>";
                                            echo "Guardado xml factura <br>";
                                        }
                                    } else {
                                        echo "Nombre archivo : " . $nombreFichero = $attachments[$h]['filename'] . "<br>";
                                        echo "error al abrir <br>";
                                    }
                                }
                                if ((strpos(strtolower($attachments[$h]['filename']), ".pdf")) && $claveXML != "") {

                                    $carpeta = 'files/recibidos/sin procesar/' . $cedula . '/' . $xml->Clave . '/';
                                    if (!file_exists($carpeta)) {
                                        mkdir($carpeta, 0777, true);
                                    }
                                    $nombre_fichero = $carpeta .  $claveXML . '.pdf';
                                    if (file_put_contents($nombre_fichero, $attachments[$h]['attachment'])) {
                                        echo "Nombre archivo : " . $nombreFichero = $attachments[$h]['filename'] . "<br>";
                                        echo "Guardado  pdf de factura <br>";
                                    }
                                }

                                if ((strpos(strtolower($attachments[$h]['filename']), ".zip"))) {

                                    echo "ZIP " . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . " <br>";
                                    $zip = new ZipArchive;
                                    $carpeta = 'files/recibidos/sin procesar/zips/' . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']);
                                    if (!file_exists($carpeta)) {
                                        mkdir($carpeta, 0777, true);
                                    }
                                    $nombre_fichero = $carpeta . '/' . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . '.zip';
                                    file_put_contents($nombre_fichero, $attachments[$h]['attachment']);
                                    if ($zip->open($nombre_fichero) === TRUE) {
                                        $zip->extractTo($carpeta . '/');
                                        $zip->close();
                                        if ($xml = simplexml_load_file($carpeta . "/" . str_replace(".zip", "", $attachments[$h]['filename']) . ".xml")) {
                                            $cedula = $xml->Receptor->Identificacion->Numero;
                                            $carpeta2 = 'files/recibidos/sin procesar/'. $cedula . '/' . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']);
                                            if (!file_exists($carpeta2)) {
                                                mkdir($carpeta2, 0777, true);
                                            }
                                            
                                            rename($carpeta . "/ATV_eFAC_" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".xml", $carpeta2 . "/" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".xml");
                                            rename($carpeta . "/ATV_eFAC_Respuesta_" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".xml", $carpeta2 . "/" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . "-R.xml");
                                            rename($carpeta . "/ATV_eFAC_" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".pdf", $carpeta2 . "/" . str_replace(array("ATV_eFAC_", ".zip"), "", $attachments[$h]['filename']) . ".pdf");
                                            echo 'OK';
                                        }
                                    } else {
                                        echo 'failed';
                                    }
                                } //fin if pdf


                            } // Fin de es adjunto
                        }
                        if (isset($attachments2)) {
                            for ($m = 1; $m <= count($attachments2); $m++) {

                                if (count($attachments2) > 1 && (strpos(strtolower($attachments2[1]['filename']), ".pdf"))) {
                                    $temp = $attachments2[1];
                                    $attachments2[1] = $attachments2[2];
                                    $attachments2[2] = $temp;
                                }
                                if ($attachments2[$m]['is_attachment']) {

                                    if ((strpos(strtolower($attachments2[$m]['filename']), ".xml"))) {
                                        libxml_use_internal_errors(true);
                                        echo $attachments2[$m]['attachment'] = base64_decode($attachments2[$m]['attachment']);
                                        if ($xml = simplexml_load_string($attachments2[$m]['attachment'])) {

                                            $claveXML = $xml->Clave;
                                            if (strrpos($attachments2[$m]['attachment'], 'mensajeHacienda') || strrpos($attachments2[$m]['attachment'], 'MensajeHacienda')) {
                                                $carpeta = 'files/recibidos/sin procesar/' . $xml->NumeroCedulaReceptor . '/' . $xml->Clave . '/';
                                                $cedula = $xml->NumeroCedulaReceptor;
                                                if (!file_exists($carpeta)) {
                                                    mkdir($carpeta, 0777, true);
                                                }
                                                $nombre_fichero = $carpeta . $xml->Clave . '-R.xml';
                                                $xml->asXML($nombre_fichero);
                                                echo "Nombre archivo : " . $nombreFichero = $attachments2[$m]['filename'] . "<br>";
                                                echo "Guardado xml respuesta <br>";
                                            }
                                            if (
                                                strrpos($attachments2[$m]['attachment'], 'facturaElectronica') || strrpos($attachments2[$m]['attachment'], 'FacturaElectronica') || strrpos($attachments2[$m]['attachment'], 'tiqueteElectronico') || strrpos($attachments2[$m]['attachment'], 'TiqueteElectronico') ||
                                                strrpos($attachments2[$m]['attachment'], 'NotaCreditoElectronica') || strrpos($attachments2[$m]['attachment'], 'notaCreditoElectronica')
                                            ) {
                                                $claveXML = $xml->Clave;
                                                
                                                $cedula = $xml->Receptor->Identificacion->Numero;
                                                echo $cedula;
                                                $carpeta = 'files/recibidos/sin procesar/' . $xml->Receptor->Identificacion->Numero . '/' . $xml->Clave . '/';
                                                if (!file_exists($carpeta)) {
                                                    mkdir($carpeta, 0777, true);
                                                }
                                                $nombre_fichero = $carpeta . $xml->Clave . '.xml';
                                                $xml->asXML($nombre_fichero);
                                                echo "Nombre archivo : " . $nombreFichero = $attachments2[$m]['filename'] . "<br>";
                                                echo "Guardado xml factura <br>";
                                            }
                                        } else {
                                            echo "Nombre archivo : " . $nombreFichero = $attachments2[$m]['filename'] . "<br>";
                                            echo "error al abrir <br>";
                                        }
                                    }
                                    if ((strpos(strtolower($attachments2[$m]['filename']), ".pdf")) && $claveXML != "") {

                                        $carpeta = 'files/recibidos/sin procesar/' . $cedula . '/' . $xml->Clave . '/';
                                        if (!file_exists($carpeta)) {
                                            mkdir($carpeta, 0777, true);
                                        }
                                        $nombre_fichero = $carpeta . $claveXML . '.pdf';
                                        if (file_put_contents($nombre_fichero, $attachments2[$m]['attachment'])) {
                                            echo "Nombre archivo : " . $nombreFichero = $attachments2[$m]['filename'] . "<br>";
                                            echo "Guardado  pdf de factura <br>";
                                        }
                                    }
                                } // Fin de es adjunto
                            }
                        }
                        $claveXML = "";
                    } //fin if partes email
                } //fin recorre emails
                // echo $output;
            }

            /* close the connection */
            imap_close($inbox);
        }
    }
}
