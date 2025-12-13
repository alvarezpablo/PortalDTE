<?php

class laudusAPI_Ejemplos {
    
    public function __construct()
    {
        //host
        $this->hostAPI = "https://api.laudus.cl";
        //objeto para preservar los credenciales de acceso a la API
        $this->credential = array("token" => "", "expiration" => "");  
        //objeto para representar la entidad Account (Cuenta del plan de cuentas contable)
        $this->account = array();  
        //objeto para representar la entidad Customer (clientes)
        $this->customer = array();
        //objeto para representar la entidad factura (clientes)
        $this->invoice = array();
        //objeto para representar la lista de clientes
		$this->listCli = array();
		$this->mensaje = "";
		$this->bResult = false;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function obtenerToken($userName, $passName, $rutEmpr) {
        //obtiene un token realizando un request a la API

        $vReturn = false;
		$this->mensaje = "";
        $this->credential = array();

        //esquema de request Login
/*        $requestLoginSchema = array("userName" => "facturacion", "password" => "facapi2022", "companyVATId" => "76361105-1");
        #se aplican los valores finales al esquema de request Login
        $requestLoginSchema["userName"] = "facturacion";
        $requestLoginSchema["password"] = "facapi2022";
        $requestLoginSchema["companyVATId"] = "76361105-1";
*/
        //esquema de request Login
        $requestLoginSchema = array("userName" => $userName, "password" => $passName, "companyVATId" => $rutEmpr);
        #se aplican los valores finales al esquema de request Login
        $requestLoginSchema["userName"] = $userName;
        $requestLoginSchema["password"] = $passName;
        $requestLoginSchema["companyVATId"] = $rutEmpr;


		
		//se contruye el request body en json
        $requestBodyJson = json_encode($requestLoginSchema); 
        
        try {
            $request = curl_init($this->hostAPI."/auth/login");
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($request, CURLOPT_POSTFIELDS, $requestBodyJson);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                "Content-type: application/json", 
                "Content-Length: ".strlen($requestBodyJson))
            );
            //make post
            $respond = curl_exec($request);    
            //respond status code
            $respondStatusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);  
            curl_close($request); 

            if ($respondStatusCode == 200) {
                $vReturn = true;
                $this->credential = json_decode($respond);
//                echo "token = " . $this->credential->{"token"}."\n\n";
//                echo "expiration = " . $this->credential->{"expiration"}."\n\n";
            }
            else {
                $vReturn = false;
                $requestError = json_decode($respond);     
                $requestErrorMessage = "";   
                if (isset($requestError->{"message"})) {    
                    $requestErrorMessage = $requestError->{"message"};
                }
                //echo "error login ".$requestErrorMessage."\n\n";
				$this->mensaje = "error login ".$requestErrorMessage;
            }
        }
        catch (Exception $error) {
            $vReturn = false;
//            echo "Unexpected error: ",  $error->getMessage(), "\n";
			$this->mensaje = "Unexpected error: ".  $error->getMessage();
        }
        
        return $vReturn;
    }  

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function getCliente($customerId) {
        //se obtiene la información del cliente

        echo "-----------------------<< Obtener Customer >>-----------------------\n\n";        

        $vReturn = false;

        #se verifica la validez del token almacenado y si no lo fuera se obtiene uno nuevo
        if (!$this->isValidToken()) {
            echo "No se pudo obtener un token válido";
            return $vReturn;
        }

        $this->customer = array();

        try {
            $request = curl_init($this->hostAPI."/sales/customers/".$customerId); 
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                "Authorization: Bearer ".$this->credential->{"token"})
            );

            //make GET
            $respond = curl_exec($request);    
            //respond status code
            $respondStatusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);  
            curl_close($request); 

            if ($respondStatusCode == 200) {
                $vReturn = true;
                $this->customer = json_decode($respond);
                echo "customerId = " . $this->customer->{"customerId"}."\n\n";
                echo "name = " . $this->customer->{"name"}."\n\n";
                echo "legalName = " . $this->customer->{"legalName"}."\n\n";
            }
            else {
                $vReturn = false;
                $requestError = json_decode($respond);     
                $requestErrorMessage = "";   
                if (isset($requestError->{"message"})) {    
                    $requestErrorMessage = $requestError->{"message"};
                }
                echo "error get customer ".$requestErrorMessage."\n\n";
            }
        }
        catch (Exception $error) {
            $vReturn = false;
            echo "Unexpected error: ",  $error->getMessage(), "\n";
        }        
        
        return $vReturn;            
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function  putCliente($customerId){
        //se realiza el guardado de datos del cliente

        echo "-----------------------<< Guardar Customer >>-----------------------\n\n";
        
        $vReturn = false;

        #se verifica la validez del token almacenado y si no lo fuera se obtiene uno nuevo
        if (!$this->isValidToken()) {
            echo "No se pudo obtener un token válido";
            return $vReturn;
        }     

        try {
            #se contruye el request body en json
            $requestBodyJson = json_encode($this->customer);
            $request = curl_init($this->hostAPI."/sales/customers/".$customerId); 
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($request, CURLOPT_POSTFIELDS, $requestBodyJson);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                "Authorization: Bearer ".$this->credential->{"token"},
                "Content-type: application/json", 
                "Content-Length: ".strlen($requestBodyJson))
            );

            //make POST
            $respond = curl_exec($request);    
            //respond status code
            $respondStatusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);  
            curl_close($request); 

            if ($respondStatusCode == 200) {
                $vReturn = true;
                $this->customer = json_decode($respond);
                echo "customerId = " . $this->customer->{"customerId"}."\n\n";
                echo "name = " . $this->customer->{"name"}."\n\n";
                echo "legalName = " . $this->customer->{"legalName"}."\n\n";
            }
            else {
                $vReturn = false;
                $requestError = json_decode($respond);     
                $requestErrorMessage = "";   
                if (isset($requestError->{"message"})) {    
                    $requestErrorMessage = $requestError->{"message"};
                }
                echo "error get customer ".$requestErrorMessage."\n\n";
            }
        }
        catch (Exception $error) {
            $vReturn = false;
            echo "Unexpected error: ",  $error->getMessage(), "\n";
        }        
        
        return $vReturn;

    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function getListCliente($campoFiltro, $valorFiltro) {
        //se obtiene la lista de cliente

        $vReturn = false;

        #se verifica la validez del token almacenado y si no lo fuera se obtiene uno nuevo
        if (!$this->isValidToken()) {
            $this->mensaje = "No se pudo obtener un token válido";
            return $vReturn;
        }

        $this->listCli = array();

        try {
			$requestBodyJson = "{	\"options\": {\"offset\": 0, \"limit\": 10},
									\"fields\": [\"customerId\", \"legalName\", \"name\", \"VATId\" ],
									\"filterBy\": [
													{
														\"field\": \"$campoFiltro\",
														\"operator\": \"=\",
														\"value\": \"$valorFiltro\"
													}
												 ],
									\"orderBy\": [
													{
														\"field\": \"legalName\",
														\"direction\": \"ASC\"
													}
												]
								}	";

			//$requestBodyJson = json_encode($requestBodyJson);
			//echo $requestBodyJson;

			$request = curl_init($this->hostAPI."/sales/customers/list"); 
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($request, CURLOPT_POSTFIELDS, $requestBodyJson);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                "Authorization: Bearer ".$this->credential->{"token"}, 
				"Content-type: application/json", 
                "Content-Length: ".strlen($requestBodyJson))
            );

            //make GET
            $respond = curl_exec($request);    
            //respond status code
            $respondStatusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);  
            curl_close($request); 
//echo "<h2>" . $valorFiltro . "</h2>";
			
			if ($respondStatusCode == 200) {
                $vReturn = true;
                $this->listCli = json_decode($respond);
				if(trim($this->listCli[0]->customerId) == ""){
					$vReturn = false;
					$this->mensaje = "Cliente RUT ".$valorFiltro." no existe";
				}

//				print_r($this->listCli);
//                echo "customerId = " . $this->listCliete->{"customerId"}."\n\n";
//                echo "name = " . $this->listCliete->{"name"}."\n\n";
//                echo "legalName = " . $this->listCliete->{"legalName"}."\n\n";
            }
            else {
                $vReturn = false;
                $requestError = json_decode($respond);     
                $requestErrorMessage = "";   
                if (isset($requestError->{"message"})) {    
                    $requestErrorMessage = $requestError->{"message"};
                }
                $this->mensaje = "error get customer ".$requestErrorMessage;
            }
        }
        catch (Exception $error) {
            $vReturn = false;
//            echo "Unexpected error: ",  $error->getMessage(), "\n";
			$this->mensaje = "Unexpected error: ".  $error->getMessage();
        }        
        return $vReturn;            
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function  postFactura($jsonFactura){
        //se realiza el guardado de datos del cliente

 //       echo "-----------------------<< Guardar Customer >>-----------------------\n\n";
        
        $vReturn = false;

        #se verifica la validez del token almacenado y si no lo fuera se obtiene uno nuevo
        if (!$this->isValidToken()) {
//            echo "No se pudo obtener un token válido";
			$this->mensaje = "No se pudo obtener un token válido";
            return $vReturn;
        }     

        try {
            #se contruye el request body en json
            $requestBodyJson = $jsonFactura; //json_encode($jsonFactura);
            $request = curl_init($this->hostAPI."/sales/invoices"); 
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($request, CURLOPT_POSTFIELDS, $requestBodyJson);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                "Authorization: Bearer ".$this->credential->{"token"},
                "Content-type: application/json", 
                "Content-Length: ".strlen($requestBodyJson))
            );

            //make POST
            $respond = curl_exec($request);    
            //respond status code
            $respondStatusCode = curl_getinfo($request, CURLINFO_HTTP_CODE);  
            curl_close($request); 
//echo "<br><br><br>" . $jsonFactura . "<br>";
//print_r($respond);
//echo "<br><br>";
            if ($respondStatusCode == 200) {
                $vReturn = true;
                $this->invoice = json_decode($respond);
//				print_r($this->invoice);
//                echo "customerId = " . $this->customer->{"customerId"}."\n\n";
//                echo "name = " . $this->customer->{"name"}."\n\n";
//                echo "legalName = " . $this->customer->{"legalName"}."\n\n";
            }
            else {
                $vReturn = false;
				$this->invoice = json_decode($respond);
                $requestError = json_decode($respond);     
                $requestErrorMessage = "";   
                if (isset($requestError->{"message"})) {    
                    $requestErrorMessage = $requestError->{"message"};
                }
                //echo "error get customer ".$requestErrorMessage."\n\n";
				$this->mensaje = "error post invoice ".$requestErrorMessage;
            }
        }
        catch (Exception $error) {
            $vReturn = false;
            // echo "Unexpected error: ",  $error->getMessage(), "\n";
			$this->mensaje = "Unexpected error: ".  $error->getMessage();
        }        
        
        return $vReturn;

    }

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function isValidToken() {
        #indica si el token almacenado en el objeto credential es válido
        #si el token almacenado no es válido obtiene un nuevo token e igualmente indica su validez
		date_default_timezone_set('America/Santiago');

        $vReturn = true;

        if (isset($this->credential->{"expiration"})) {
            $ltNow = new DateTime("NOW");    
            $ltNow = $ltNow->format('c');
            if ($this->credential->{"expiration"} < $ltNow) {
                return $this->obtenerToken();
            }
            else {
                return $vReturn;
            }            

        }
        else {
            return $this->obtenerToken();
        }

    }

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
$jsonString = "{
  \"salesInvoiceId\": \"\",
  \"docNumber\": 1000,
  \"customer\": {
    \"customerId\": 222222222,
    \"name\": \"Mauricio Escobar\",
    \"legalName\": \"Mauricio Escobar\",
    \"email\": \"\"
  },
  \"issuedDate\": \"2022-02-28T20:52:35.124Z\",
  \"dueDate\": \"2022-02-28T20:52:35.124Z\",
  \"nullDoc\": true,
  \"totals\": {
    \"net\": 0,
    \"vat\": 0,
    \"vatWithheld\": 0,
    \"notInvoiceable_income\": 0,
    \"notInvoiceable_total\": 0,
    \"total\": 0,
    \"parity\": 0
  },
  \"totalsOriginalCurrency\": {
    \"net\": 0,
    \"vat\": 0,
    \"vatWithheld\": 0,
    \"notInvoiceable_income\": 0,
    \"notInvoiceable_total\": 0,
    \"total\": 0,
    \"parity\": 0
  },
  \"payInOriginalCurrency\": true,
  \"deliveryCost\": 0,
  \"bypassCreditLimit\": true,
  \"createAccounting\": true,
  \"items\": [
    {
      \"itemId\": 0,
      \"itemOrder\": 0,
      \"itemDescription\": \"producto n1\",
      \"quantity\": 1,
      \"originalUnitPrice\": 100,
      \"parityToMainCurrency\": 100,
      \"unitPrice\": 100,
      \"VAT\": 10,
      \"VATRate\": 10,
      \"discountPercentage\": 0,
      \"notInvoiceable\": true,
      \"notInvoiceable_isIncome\": true,
    }
  ]
} ";




$ejemplo = new laudusAPI_Ejemplos();

//echo $ejemplo->obtenerToken();

if ($ejemplo->obtenerToken("facturacion","facapi2022","76361105-1")) {

	if ($ejemplo->postFactura($jsonString)) {

	}

    //en este ejemplo se usa el customerId 18
//    $customerId = 15;
//	sleep(1);
///      if ($ejemplo->getCliente($customerId)) {
//		print_r($ejemplo);
        //se cambia el valor de la propiedad 'legalName' del cliente
/*        $ejemplo->customer->{"legalName"} = "new legal Name";
        if ($ejemplo->putCliente($customerId)) {
            //guardado correctamente
        }	 * /
//    } 
}
*/

?>