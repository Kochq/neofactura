<?php

include_once (__DIR__ . '/wsfev1.php');
include_once (__DIR__ . '/wsaa.php');

/**
* Este script sirve para probar el webservice WSFEV1 con Factura B
* Hay que indicar el CUIT con el cual vamos a realizar las pruebas
* Hay que indicar el número de comprobante correcto
* Hay que indicar un DNI válido para el receptor del comprobante
* Recordar tener todos los servicios de homologación habilitados en AFIP
* Ejecutar desde consola con "php testFacturaB.php"
*/
$CUIT = "20463482056"; // CUIT del emisor
$MODO = Wsaa::MODO_HOMOLOGACION;

echo "----------Script de prueba de AFIP WSFEV1----------\n";

$voucher = Array
(
    "idVoucher" => 1,
    "numeroComprobante" => 1,
    "numeroPuntoVenta" => 1,
    "cae" => 0,
    "letra" => "B",
    "fechaVencimientoCAE" => "20241107",
    "tipoResponsable" => "Consumidor Final",
    "nombreCliente" =>  "JUAN PEREZ",
    "domicilioCliente" => "CALLE FALSA 123",
    "fechaComprobante" => "20241107",
    "codigoTipoComprobante" => 6,
    "TipoComprobante" => "Factura",
    "codigoConcepto" => 1,
    "codigoMoneda" => "PES",
    "cotizacionMoneda" => 1.000,
    "fechaDesde" => 20241107,
    "fechaHasta" => 20241107,
    "fechaVtoPago" => 20241107,
    "codigoTipoDocumento" => 96,
    "TipoDocumento" => "DNI",
    "numeroDocumento" => 45189450, // Debe ser diferente al DNI del emisor
    "importeTotal" => 121.000,
    "importeOtrosTributos" => 0.000,
    "importeGravado" => 100.000,
    "importeNoGravado" => 0.000,
    "importeExento" => 0.000,
    "importeIVA" => 21.000,
    "codigoPais" => 200,
    "idiomaComprobante" => 1,
    "NroRemito" => 0,
    "CondicionVenta" => "Efectivo",
    "items" => Array
        (
            0 => Array
                (
                    "codigo" => 112233445566,
                    "scanner" => 112233445566,
                    "descripcion" => "Producto de prueba",
                    "codigoUnidadMedida" => 7,
                    "UnidadMedida" => "Unidades",
                    "codigoCondicionIVA" => 5,
                    "Alic" => 21,
                    "cantidad" => 1.00,
                    "porcBonif" => 0.000,
                    "impBonif" => 0.000,
                    "precioUnitario" => 121.00,
                    "importeIVA" => 21.000,
                    "importeItem" => 121.00,
                )
        ),
    "subtotivas" => Array
        (
            0 => Array
                (
                    "codigo" => 5,
                    "Alic" => 21,
                    "importe" => 21.00,
                    "BaseImp" => 100.00,
                )
        ),
    "Tributos" => Array(),
    "CbtesAsoc" => Array()
);

try {
    $afip = new Wsfev1($CUIT,$MODO);
    $result = $afip->emitirComprobante($voucher);
    print_r($result);
} catch (Exception $e) {
    echo 'Falló la ejecución: ' . $e->getMessage();
}

echo "--------------Ejecución WSFEV1 finalizada-----------------\n";
