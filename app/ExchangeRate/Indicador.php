<?php

/*
 * Descripcion: Clase para obtener el tipo de cambio actual en Dolares del Banco Central de Costa Rica.
 * Autor: Ariel Orozco <bassplayer85@gmail.com>
 * Web: http://arielorozco.com/
 * Fecha: 29/12/2010
 */

namespace App\ExchangeRate;

class Indicador {

    // Constantes de tipo de cambio
    const COMPRA = 317;
    const VENTA = 318;

    // URL del WebService
    private $ind_econom_ws = "https://gee.bccr.fi.cr/Indicadores/Suscripciones/WS/wsindicadoreseconomicos.asmx";
    // Funcion que se va a utilizar del WebService
    private $ind_econom_func = "ObtenerIndicadoresEconomicos";
    // Bandera que indica si se va a utilizar SOAP para obtener los datos (falso por defecto)
    private $soap = false;
    // Tipo de cambio que se quiere obtener (COMPRA por defecto)
    private $tipo = 317;
    // Fecha actual
    private $fecha = "";

    function __construct($soap = false) {
        $this->soap = $soap;
        $this->fecha = date("d/m/Y");
    }

    public function obtenerIndicadorEconomico($tipo) {
        $this->tipo = $tipo;
        $valor = ($this->soap) ? $this->obtenerPorSoap() : $this->obtenerPorGet();
        return $valor;
    }

    private function obtenerPorGet() {
        $urlWS = $this->ind_econom_ws . "/" . $this->ind_econom_func . "?Indicador=" . $this->tipo . "&FechaInicio=" . $this->fecha . "&FechaFinal=" . $this->fecha . "&Nombre=CF&SubNiveles=string&CorreoElectronico=k.campos@contafast.net&Token=5OEPKOITVE";
        $tipo_cambio = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWS);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        if ($res != false) {
            $tipo_cambio = strip_tags(substr($res, strpos($res, "<NUM_VALOR>"), strripos($res, "</NUM_VALOR>")));
        }
        curl_close($ch);

        return substr($tipo_cambio, 0, 6);
    }

    private function obtenerPorSoap() {
        require_once("soap/nusoap.php");
        $tipoCambio = "";
        $parametros = array(
            "tcIndicador" => $this->tipo,
            "tcFechaInicio" => $this->fecha,
            "tcFechaFinal" => $this->fecha,
            "tcNombre" => "TQ",
            "tnSubNiveles" => "N");
        $oSoapClient = new nusoap_client("http://gee.bccr.fi.cr//Indicadores/Suscripciones/WS/wsindicadoreseconomicos.asmx",true);
        $aRespuesta = $oSoapClient->call($this->ind_econom_func, $parametros);
        $xml = simplexml_load_string($aRespuesta['ObtenerIndicadoresEconomicosXMLResult']);
        $tipoCambio = $xml->INGC011_CAT_INDICADORECONOMIC[0]->NUM_VALOR;
        return $tipoCambio;
    }

}

?>