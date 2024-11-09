<?php

include_once (__DIR__ . '/wsfev1.php');
include_once (__DIR__ . '/wsaa.php');

$CUIT = "20463482056"; // CUIT del emisor
$MODO = 0;

$afip = new Wsfev1($CUIT,$MODO);

// Consulto el ultimo comprobante autorizado, se le pasa (punton de venta - tipo comprobante)
$ultimocomp = $afip->consultarUltimoComprobanteAutorizado(1,1);

// le sumo uno, para que quede el siguiente comprobante a autorizar
++$ultimocomp;

$fecha = date('Ymd');

$json["numeroComprobante"] = $ultimocomp;
$json["fechaVencimientoCAE"] = $fecha;
$json["fechaComprobante"] = $fecha;
$json["fechaDesde"] = $fecha;
$json["fechaHasta"] = $fecha;
$json["fechaVtoPago"] = $fecha;

try {
    $result = $afip->emitirComprobante($json);
    print_r($result);
} catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}
