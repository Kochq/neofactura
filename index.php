<?php
require __DIR__ . '/vendor/autoload.php';
$request = $_SERVER['REQUEST_URI'];

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

print("HOLAAAAQAAAAAAAWQWSFQWFEQF");

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
