<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');

class HTMLTicket {
    private $config = array();
    private $ticket = null;
    private $json = null;
    private $lang = array();

    function __construct($ticket, $config, $json) {
        $this->config = $config;
        $this->ticket = $ticket;
        $this->json = $json;
    }

    /**
     * Generates the complete HTML structure
     */

    private function calculateDynamicHeight($html) {
    $baseHeight = 130; 
    $lineHeight = 6.88;
    $lines = substr_count($html, '<tr>'); // Contar líneas y filas

    $contentHeight = $lines * $lineHeight;

    return $baseHeight + $contentHeight;
}
    public function generateHTML($name) {
        $html = $this->getHTMLHeader();
        $html .= $this->getBusinessInfo();
        $html .= $this->getTicketInfo();
        $html .= $this->getCustomerInfo();
        $html .= $this->getItemsTable();
        $html .= $this->getTotalSection();
        $html .= $this->getFooterInfo();
        $html .= $this->getHTMLFooter();
        $height = $this->calculateDynamicHeight($html);
        
        // Crear un nuevo documento con formato personalizado
        $pdf = new TCPDF('P', 'mm', array(79.5, $height)); // Ancho 80 mm y altura inicial de 150 mm (ajustable)

        // Configurar el documento
        $pdf->SetMargins(5, 5, 5); // Márgenes pequeños
        $pdf->SetAutoPageBreak(TRUE, 10); // Salto automático de página con margen inferior

        // Añadir una página
        $pdf->AddPage();

        // Añadir contenido HTML
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output("/home/koch/$name", 'S');
    }

    /**
     * Returns HTML header with styles
     */
    private function getHTMLHeader() {
        return '<!DOCTYPE html>
        <html>
        <head>
            <title>Ticket</title>
            <style type="text/css">
                * {
                    box-sizing: border-box;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                }
                .bill-container {
                    border-collapse: collapse;
                    max-width: 8cm;
                    position: absolute;
                    left: 0;
                    right: 0;
                    margin: auto;
                    border-collapse: collapse;
                    font-size: 7px;
                }
                .text-lg {
                    font-size: 15px;
                }
                .text-center {
                    text-align: center;
                }
                #qrcode {
                    width: 75%
                }
                table table {
                    width: 100%;
                }
                table table tr td:last-child {
                    text-align: right;
                }
                .border-top {
                    border-top: 1px dotted #000;
                }
                .padding-b-3 {
                    padding-bottom: 3px;
                }
                .padding-t-3 {
                    padding-top: 3px;
                }
            </style>
        </head>
        <body>
            <table class="bill-container">';
    }

    /**
     * Generates business information section
     */
    private function getBusinessInfo() {
        return '<tr>
            <td class="padding-b-3">
                <p>Razón social: ' . htmlspecialchars($this->config['TRADE_SOCIAL_REASON']) . '</p>
                <p>Direccion: ' . htmlspecialchars($this->config['TRADE_ADDRESS']) . '</p>
                <p>C.U.I.T.: ' . htmlspecialchars($this->config['TRADE_CUIT']) . '</p>
                <p>' . htmlspecialchars($this->config['TRADE_TAX_CONDITION']) . '</p>
                <p>IIBB: ' . htmlspecialchars($this->config['TRADE_IIBB']) . '</p>
                <p>Inicio de actividad: ' . htmlspecialchars($this->config['TRADE_INIT_ACTIVITY']) . '</p>
            </td>
        </tr>';
    }

    /**
     * Generates ticket information section
     */
    private function getTicketInfo() {
        $date = date('d/m/Y', strtotime($this->ticket['fechaComprobante']));
        
        return '<tr>
            <td class="border-top padding-t-3 padding-b-3">
                <p class="text-center text-lg">FACTURA ' . htmlspecialchars($this->ticket['letra']) . '</p>
                <p class="text-center">Codigo ' . htmlspecialchars($this->ticket['codigoTipoComprobante']) . '</p>
                <p>P.V: ' . str_pad($this->ticket['numeroPuntoVenta'], 4, "0", STR_PAD_LEFT) . '</p>
                <p>Nro: ' . str_pad($this->ticket['numeroComprobante'], 8, "0", STR_PAD_LEFT) . '</p>
                <p>Fecha: ' . $date . '</p>
                <p>Concepto: ' . htmlspecialchars($this->ticket['concepto']) . '</p>
            </td>
        </tr>';
    }

    /**
     * Generates customer information section
     */
    private function getCustomerInfo() {
        return '<tr>
            <td class="border-top padding-t-3 padding-b-3">
                <p>' . htmlspecialchars($this->ticket['tipoResponsable']) . '</p>
            </td>
        </tr>';
    }

    /**
     * Generates items table
     */
    private function getItemsTable() {
        
        $html = '<tr>
            <td class="border-top padding-t-3 padding-b-3">
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>P. Unitario</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>';
        
        foreach ($this->ticket['items'] as $index => $item) {
            $precioSinDescuento = $item['importeItem'] + $item['impBonif'];
            $html .= '<tr>
                <td>' . htmlspecialchars($item['descripcion']) . '</td>
                <td>' . $item['cantidad'] . '</td>
                <td>' . number_format($item['precioUnitario'], 2, ',', '.') . '</td>
                <td>' . number_format($precioSinDescuento, 2, ',', '.') . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
                    </table>
                </div>
            </td>
        </tr>';
        
        return $html;
    }

    /**
     * Generates total section
     */
    private function getTotalSection() {
        // Calcula el descuento total sumando todos los descuentos de los items
        $descuentoTotal = array_reduce($this->ticket['items'], function($carry, $item) {
            return $carry + $item['impBonif'];
        }, 0);
    
        // Calcula el SubTotal como la suma del importe total y el descuento total
        $subTotal = $this->ticket['importeTotal'] + $descuentoTotal;
    
        return '<tr>
            <td class="border-top padding-t-3 padding-b-3">
                <div>
                    <table style="width: 95%;">
                        <tr>
                            <td style="text-align: left;">SubTotal</td>
                            <td style="text-align: right;">' . number_format($subTotal, 2, ',', '.') . '</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Descuento</td>
                            <td style="text-align: right;">' . number_format($descuentoTotal, 2, ',', '.') . '</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">TOTAL</td>
                            <td style="text-align: right;">' . number_format($this->ticket['importeTotal'], 2, ',', '.') . '</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>';
    }
    /**
     * Generates footer information
     */
    private function getFooterInfo() {
        $caeDate = date('d/m/Y', strtotime($this->ticket['fechaVencimientoCAE']));

        $link = "https://www.afip.gob.ar/fe/qr/?p=";
        $json64 = base64_encode($this->json);
        include('phpqrcode/qrlib.php');
        QRcode::png($link.$json64, "qrs/". "storeID" .".png", QR_ECLEVEL_L, 1, 2);
        
        return '<tr>
            <td class="border-top padding-t-3">
                <p>CAE: ' . htmlspecialchars($this->ticket['cae']) . '</p>
                <p>Vto: ' . $caeDate . '</p>
            </td>
        </tr>
        <tr class="text-center">
            <td>
                <img id="qrcode" src="qrs/storeID.png">
            </td>
        </tr>';
    }

    /**
     * Returns HTML footer
     */
    private function getHTMLFooter() {
        return '</table>
        </body>
        </html>';
    }
}
