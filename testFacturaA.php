<?php

include_once (__DIR__ . '/wsfev1.php');
include_once (__DIR__ . '/wsaa.php');
require "pdf_voucher.php";

$CUIT = "20463482056"; // CUIT del emisor
$MODO = 0;

$afip = new Wsfev1($CUIT,$MODO);

// Consulto el ultimo comprobante autorizado, se le pasa (punton de venta - tipo comprobante)
$ultimocomp = $afip->consultarUltimoComprobanteAutorizado(1,1);

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
    "TRADE_SOCIAL_REASON" => "EMPRESA SRL",
    "TRADE_CUIT" => "20463482056",
    "TRADE_ADDRESS" => "Calle 123",
    "TRADE_TAX_CONDITION" => "No pago iva",
    "TRADE_INIT_ACTIVITY" => "05/11/2024",
    "VOUCHER_OBSERVATION" => ""
];


try {
    $result = $afip->emitirComprobante($json);
    $json["cae"] = $result["cae"];
    $pdf = new PDFVoucher($json, $config);
    $logo_path = "/home/koch/Workspace/fusionDevs/neofactura/assets/cuadrado.png";
    $pdf->emitirPDF($logo_path);
    $pdf->output("/home/koch/docu.pdf", "F"); // ("path/to/pdf", "F") para que lo cree en un archivo en el server
    print_r($result);
} catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}


