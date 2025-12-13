<?php 

function firmarDTE($xml, $rutEmisor, $dvEmisor, $tipoArchivo, $apiKey) {
    try {
        // Configuración del cliente SOAP
        $options = [
            'trace' => true,
            'exceptions' => true,
            'connection_timeout' => 180,
            'location' => 'http://cloud-ws.opendte.cl:8080/OpenDTEWS/services/FirmaDTE.FirmaDTEHttpSoap11Endpoint/',
            'uri' => 'http://ws.opendte.cl',
        ];

        // Crear instancia del cliente SOAP
        $soapClient = new SoapClient(null, $options);

        // Parámetros para la llamada al método firmaDTE
        $parametros = [
            "RUTEmisor" => $rutEmisor,
            "DVEmisor" => $dvEmisor,
            "tipoArchivo" => $tipoArchivo,
            "archivo" => $xml,
            "apikey" => $apiKey,
        ];

        // Llamar al método firmaDTE del servicio web
        $response = $soapClient->__soapCall('firmaDTE', [$parametros]);

        // Procesar la respuesta del servicio web
        return $response;
    } catch (SoapFault $e) {
print_r($e);
        return "Error SOAP: " . $e->getMessage();
    } catch (Exception $e) {
print_r($e); 
        return "Error: " . $e->getMessage();
    }
}

// Ejemplo de llamada a la función firmarDTE
$xmlData = '<xml>aquí va tu XML</xml>'; // XML a firmar
$rutEmisor = '99999999'; // Rut del emisor
$dvEmisor = '9'; // DV del emisor
$tipoArchivo = 'XML'; // Tipo de archivo (puede variar según el servicio)
$apiKey = ''; // Clave API

// Llamar a la función firmarDTE con los parámetros necesarios
$resultado = firmarDTE($xmlData, $rutEmisor, $dvEmisor, $tipoArchivo, $apiKey);

// Verificar el resultado
if (is_string($resultado)) {
    echo "Error al firmar DTE: " . $resultado;
} else {
    var_dump($resultado); // Imprimir detalles de la respuesta
    // Procesar la respuesta según sea necesario
}


?>
