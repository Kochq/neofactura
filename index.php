<?php
require __DIR__ . '/vendor/autoload.php';
$request = $_SERVER['REQUEST_URI'];

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$headers = getallheaders();
$json = json_decode(file_get_contents('php://input'), true);

$jwtCookie = $headers['Authorization'];
$token = explode(" ", $jwtCookie)[1];
$key = $_ENV['KEY'];
$key = base64_decode($key);
print("TOKEN: ". $token);

$decoded = JWT::decode($token, new Key($key, 'HS512'));
$decoded_array = (array) $decoded;

$config = [
    'TRADE_SOCIAL_REASON' => 'Empresa imaginaria S.A.',
    'TRADE_ADDRESS' => 'Calle falsa 123',
    'TRADE_CUIT' => '12345678912',
    'TRADE_IIBB' => '12345432',
    'TRADE_TAX_CONDITION' => 'RESPONSABLE INSCRIPTO',
    'TRADE_INIT_ACTIVITY' => '25/10/2023',
    'STORE_ID' => 'storeID'
];

// Example ticket data
$ticket = [
    'letra' => 'C',
    'codigoTipoComprobante' => '11',
    'numeroPuntoVenta' => 4,
    'numeroComprobante' => 1484,
    'fechaComprobante' => '2023-11-21',
    'concepto' => 'Productos',
    'tipoResponsable' => 'A CONSUMIDOR FINAL',
    'items' => [
        [
            'descripcion' => 'Cafe Americano',
            'alic' => 21,
            'importeItem' => 1500.00
        ]
    ],
    'importeTotal' => 1500.00,
    'cae' => '12345678912345',
    'fechaVencimientoCAE' => '2023-11-05'
];

print_r($decoded_array);

switch ($request) {
    case '/factura/A':
        require 'testFacturaA.php';
        break;

    case '/factura/B':
        require 'testFacturaB.php';
        break;

    case '/factura/C':
        require 'testFacturaC.php';
        break;

    case '/test':
        require 'test.php';
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>
