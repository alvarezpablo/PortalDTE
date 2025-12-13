<?php 

$server = new SoapServer(null, array('uri' => 'urn:webservices'));
 
// Asignamos la Clase
$server->setClass('UserService');
 
// Atendemos las peticiones
$server->handle();


class UserService
{
    private $_EMAIL;
    private $_PASSWORD;
    
    public function login($email, $password)
    {
        $this->_EMAIL = addslashes($email);    // También puede servir addslashes
        $this->_PASSWORD = addslashes($password);    // También puede servir addslashes

		if($this->_EMAIL == "pepe" and $this->_PASSWORD = "perez")
			return true;
		else
			return false;
    }
}



?>
