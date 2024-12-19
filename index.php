<?php

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="facturaC.pdf"');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

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
