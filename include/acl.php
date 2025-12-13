<?php
//TODO cambiar por los datos del config
$conn = pg_connect("host=10.30.1.194 port=5432 dbname=opendte user=opendte password=root8831");
	//obtenemos los recursos asociados a un rol
	function obtener_recursos($conn,$cod_rol){
		$recursos = array();
	
		$sql=" SELECT  * FROM permiso INNER JOIN".
			" recurso ON (recurso.cod_recurso = permiso.recurso_cod_recurso) AND".
			" (permiso.rol_cod_rol = ".$cod_rol.")";


		$result=pg_query($conn,$sql);
		while ($row=pg_fetch_array($result)){
			$recursos[]=$row;
		}
		return $recursos;
	}

	function valida_permisos($recursos){
		//verificamos si el rol, tiene acceso al recurso
		//obtenemos el recurso o url actal
		$validacion = false;
		$permisos_recursos = $recursos;

		echo "<pre>";print_r($permisos_recursos);echo "</pre>";
		//url actual
		$url = $_SERVER["REQUEST_URI"];
		for ($i=0;$i<count($permisos_recursos);$i++){
			if ($url == $permisos_recursos[$i]["url"]){
				$validacion = true;
			}
		}
		//retornamos si tiene acceso
		return $validacion;
	}

	if ( valida_permisos( obtener_recursos($conn,5) ) ){
		echo "tienes acceso";
	}else{
		echo "no tienes acceso";
		die();
	}
?>
