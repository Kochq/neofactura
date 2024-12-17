<?php

include('phpqrcode/qrlib.php');
QRcode::png("afip.com.ar", "qrs/". "storeID" .".png", QR_ECLEVEL_L, 10, 2);
require "pdf_ticket.php";


// Create and use the ticket generator
$generator = new HTMLTicket($ticket, $config);
$html = $generator->generateHTML();

// Output the HTML
echo $html;
