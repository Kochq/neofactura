# Neofactura

Pasos:

1. Clonar repositorio de github
2. Crear una carpeta dentro del mismo con el [cuit] de la persona autorizada en AFIP 
3. Crear tres carpetas dentro de la anterior: ./[cuit]/wsfe , ./[cuit]/wsfex y ./[cuit]/ws_sr_padron_a5
4. Dentro de dichas carpetas crear dos carpetas más: ./[cuit]./[serviceName]/tmp y ./[cuit]./[serviceName]/token
5. Crear las carpetas "./key/homologacion" y "./key/produccion"
6. En ./key/homologacion y ./key/produccion colocar los certificados generados en afip junto con las claves privadas.
7. Composer install

Test:

1. Editar el archivo test.php y modificar el valor de la variable $CUIT por el de la persona autorizada en AFIP.
2. Probar la libreria desde consola con "php test.php" -> Debería imprimir OK por cada web service.
3. Opcional: Podrás probar los distintos tipos de comprobante desde los ejemplos nombrados como testFacturaX.php

Rutas:
/test -> Run test.php
/factura/A -> Run testFacturaA.php
/factura/B -> Run testFacturaB.php
/factura/C -> Run testFacturaB.php

¿Más info? -> https://github.com/neocomplexx/neofactura/wiki
