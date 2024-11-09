<?php
$request = $_SERVER['REQUEST_URI'];

$json = json_decode(file_get_contents('php://input'), true);

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
