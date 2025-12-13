<?php
$url = "http://10.0.0.66/ws/server.php?wsdl";
try {
 $client = new SoapClient($url, array( "trace" => "1" ) );
 $result = $client->InicializarXMLVenta( array( "rutEmpr" => "99999999" ) );
 print_r($result);
} catch ( SoapFault $e ) {
 echo $e->getMessage();
}
//echo PHP_EOL;

?>