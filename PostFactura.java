import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class PostFactura {
    public static void main(String[] args) {
        String jsonInputString = """
            {
              "idVoucher": 1,
              "numeroComprobante": "<ULTIMO_COMP>",
              "numeroPuntoVenta": 1,
              "cae": 0,
              "letra": "A",
              "fechaVencimientoCAE": "<FECHA>",
              "tipoResponsable": "Responsable Incripto",
              "nombreCliente": "koch",
              "domicilioCliente": "republica 12",
              "fechaComprobante": "<FECHA>",
              "codigoTipoComprobante": 1,
              "codigoConcepto": 1,
              "codigoMoneda": "PES",
              "cotizacionMoneda": 1.0,
              "fechaDesde": "<FECHA>",
              "fechaHasta": "<FECHA>",
              "fechaVtoPago": "<FECHA>",
              "codigoTipoDocumento": 80,
              "numeroDocumento": "20456068732",
              "importeTotal": 27830.0,
              "importeOtrosTributos": 0.0,
              "importeGravado": 23000.0,
              "importeNoGravado": 0.0,
              "importeExento": 0.0,
              "importeIVA": 4830.0,
              "codigoPais": 200,
              "idiomaComprobante": 1,
              "nroRemito": 0,
              "items": [
                {
                  "codigo": 6,
                  "scanner": 6,
                  "descripcion": "alamos",
                  "codigoUnidadMedida": 7,
                  "codigoCondicionIVA": 5,
                  "cantidad": 2.0,
                  "porcBonif": 0.0,
                  "impBonif": 0.0,
                  "precioUnitario": 10000.0,
                  "importeIVA": 2100.0,
                  "importeItem": 12100.0,
                  "unidadMedida": "Unidades",
                  "alic": 21.0
                },
                {
                  "codigo": 4,
                  "scanner": 4,
                  "descripcion": "cheeseburger",
                  "codigoUnidadMedida": 7,
                  "codigoCondicionIVA": 5,
                  "cantidad": 2.0,
                  "porcBonif": 0.0,
                  "impBonif": 0.0,
                  "precioUnitario": 1500.0,
                  "importeIVA": 315.0,
                  "importeItem": 1815.0,
                  "unidadMedida": "Unidades",
                  "alic": 21.0
                }
              ],
              "subtotivas": [
                {
                  "codigo": 5,
                  "alic": 21.0,
                  "importe": 4830.0,
                  "baseImp": 23000.0
                }
              ],
              "condicionVenta": "efectivo",
              "tipoComprobante": "Factura",
              "tipoDocumento": "DNI",
              "tributos": [],
              "cbtesAsoc": []
            }
        """;

        try {
            System.out.println("ashsdhfas");
            HttpClient client = HttpClient.newHttpClient();
            HttpRequest request = HttpRequest.newBuilder()
                    .uri(URI.create("http://0.0.0.0:8000/factura/A"))
                    .header("Content-Type", "application/json")
                    .POST(HttpRequest.BodyPublishers.ofString(jsonInputString))
                    .build();

            System.out.println("ashsdhfas1");
            // Enviar la solicitud y obtener la respuesta
            try {
                HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());
                System.out.println("Response Code: " + response.statusCode());
                System.out.println("Response Body: " + response.body());
            } catch (java.net.ConnectException e) {
                System.err.println("Connection error: " + e.getMessage());
                e.printStackTrace();
            } catch (Exception e) {
                e.printStackTrace();
            }
            System.out.println("ashsdhfas2");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
