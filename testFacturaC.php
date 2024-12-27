<?php

include_once (__DIR__ . '/wsfev1.php');
include_once (__DIR__ . '/wsaa.php');
require "pdf_voucher.php";
require "pdf_ticket.php";

/**
* Este script sirve para probar el webservice WSFEV1 con Factura C
* Hay que indicar el CUIT con el cual vamos a realizar las pruebas
* Hay que indicar el número de comprobante correcto
* Hay que indicar un DNI válido para el receptor del comprobante
* Recordar tener todos los servicios de homologación habilitados en AFIP
* Ejecutar desde consola con "php testFacturaC.php"
*/
$CUIT = "20463482056"; // CUIT del emisor
$MODO = Wsaa::MODO_HOMOLOGACION;

$afip = new Wsfev1($CUIT,$MODO);

// Consulto el ultimo comprobante autorizado, se le pasa (punton de venta - tipo comprobante)
$ultimocomp = $afip->consultarUltimoComprobanteAutorizado(1,11);

// le sumo uno, para que quede el siguiente comprobante a autorizar
++$ultimocomp;

$fecha = date('Ymd');
$fechaCAE = date('Y-m-d');

$json["numeroComprobante"] = $ultimocomp;
$json["fechaVencimientoCAE"] = $fechaCAE;
$json["fechaComprobante"] = $fecha;
$json["fechaDesde"] = $fecha;
$json["fechaHasta"] = $fecha;
$json["fechaVtoPago"] = $fecha;

$config = [
    "TRADE_SOCIAL_REASON" => "CUARTA GENERACION",
    "TRADE_CUIT" => "20463482056",
    "TRADE_ADDRESS" => "P. Aparicio 125",
    "TRADE_TAX_CONDITION" => "MONOTRIBUTISTA",
    "TRADE_INIT_ACTIVITY" => "05/11/2024",
    "VOUCHER_OBSERVATION" => ""
];

$ptoVta = $json['numeroPuntoVenta'];
$tipoCmp = $json['codigoTipoComprobante'];
$nroCmp = $json['numeroComprobante'];
$importe = $json['importeTotal'];
$moneda = $json['codigoMoneda'];
$ctz = $json['cotizacionMoneda'];
$tipoDocRec = $json['codigoTipoDocumento'];
$nroDocRec = $json['numeroDocumento'];

$ticket = [
    'letra' => 'C',
    'codigoTipoComprobante' => $json['codigoTipoComprobante'],
    'numeroPuntoVenta' => $json['numeroPuntoVenta'],
    'numeroComprobante' => $json['numeroComprobante'],
    'fechaComprobante' => $json['fechaComprobante'],
    'concepto' => 'Productos',
    'tipoResponsable' => 'A CONSUMIDOR FINAL',
    'items' => $json['items'],
    'importeTotal' => $json['importeTotal'],
    'fechaVencimientoCAE' => $json["fechaVencimientoCAE"]
];

try {
    $result = $afip->emitirComprobante($json);
    $json["cae"] = $result["cae"];
    $ticket['cae'] = $result["cae"];
    $config['TRADE_IIBB'] = "20463482056";

    $qrJson = '{
        "ver": 1,
        "fecha": "' . $fechaCAE . '",
        "cuit": ' . $CUIT . ',
        "ptoVta": ' . $ptoVta . ',
        "tipoCmp": ' . $tipoCmp . ',
        "nroCmp": ' . $nroCmp . ',
        "importe": ' . $importe . ',
        "moneda": "' . $moneda . '",
        "ctz": ' . $ctz . ',
        "tipoDocRec": ' . $tipoDocRec . ',
        "nroDocRec": ' . $nroDocRec . ',
        "tipoCodAut": "E",
        "codAut": ' . $result['cae'] . '
    }';

    $fecha = date('d-m-Y-H-i-s');
    $logo_path = "/home/koch/Workspace/fusionDevs/neofactura/assets/cuadrado.png";

    
    $nombreDelArchivo = "factura_$fecha.pdf";
    $generator = new PDFVoucher($json, $config);
    $pdfFactura = $generator->emitirPDF($nombreDelArchivo, $logo_path);

    $nombreDelArchivo = "ticket_$fecha.pdf";
    $generator = new HTMLTicket($ticket, $config, $qrJson);
    $pdfTicket = $generator->generateHTML($nombreDelArchivo);

    

    $pdf = $pdfFactura . $pdfTicket;


    http_response_code(200);
    
    header('Content-Length: ' . strlen($pdf));

} catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}
