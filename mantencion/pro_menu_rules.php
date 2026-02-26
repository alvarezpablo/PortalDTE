<?php 
/**
 * Procesamiento CRUD de Reglas de Men&uacute;
 * PortalDTE
 */
include("../include/config.php");  
include("../include/ver_aut.php");      
include("../include/ver_aut_adm.php");        
include("../include/db_lib.php"); 

// Solo administradores (rol 1)
if($_SESSION["_COD_ROL_SESS"] != "1") {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

header("Content-Type: application/json; charset=ISO-8859-1");

$conn = conn();
$sAccion = isset($_POST["accion"]) ? trim($_POST["accion"]) : '';

// Funci&oacute;n para escapar valores
function escape($conn, $val) {
    return str_replace("'", "''", trim($val));
}

// INSERTAR
function insertar($conn) {
    $menu = escape($conn, $_POST["menu"]);
    $opcion = escape($conn, $_POST["opcion"]);
    $link = escape($conn, $_POST["link"]);
    $icono = escape($conn, $_POST["icono"]);
    $tipo_regla = escape($conn, $_POST["tipo_regla"]);
    $variable = escape($conn, $_POST["variable"]);
    $operador = escape($conn, $_POST["operador"]);
    $valor = escape($conn, $_POST["valor"]);
    $descripcion = escape($conn, $_POST["descripcion"]);
    $activo = isset($_POST["activo"]) ? 'S' : 'N';
    $orden = intval($_POST["orden"]);
    
    $sql = "INSERT INTO menu_reglas (menu, opcion, link, icono, tipo_regla, variable, operador, valor, descripcion, activo, orden) VALUES (";
    $sql .= "'" . $menu . "',";
    $sql .= "'" . $opcion . "',";
    $sql .= "'" . $link . "',";
    $sql .= "'" . $icono . "',";
    $sql .= "'" . $tipo_regla . "',";
    $sql .= "'" . $variable . "',";
    $sql .= "'" . $operador . "',";
    $sql .= "'" . $valor . "',";
    $sql .= "'" . $descripcion . "',";
    $sql .= "'" . $activo . "',";
    $sql .= $orden . ")";
    
    $result = $conn->Execute($sql);
    
    if ($result) {
        return ['success' => true, 'message' => 'Regla creada correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al crear regla: ' . $conn->ErrorMsg()];
    }
}

// MODIFICAR
function modificar($conn) {
    $id = intval($_POST["id"]);
    $menu = escape($conn, $_POST["menu"]);
    $opcion = escape($conn, $_POST["opcion"]);
    $link = escape($conn, $_POST["link"]);
    $icono = escape($conn, $_POST["icono"]);
    $tipo_regla = escape($conn, $_POST["tipo_regla"]);
    $variable = escape($conn, $_POST["variable"]);
    $operador = escape($conn, $_POST["operador"]);
    $valor = escape($conn, $_POST["valor"]);
    $descripcion = escape($conn, $_POST["descripcion"]);
    $activo = isset($_POST["activo"]) ? 'S' : 'N';
    $orden = intval($_POST["orden"]);
    
    $sql = "UPDATE menu_reglas SET ";
    $sql .= "menu = '" . $menu . "',";
    $sql .= "opcion = '" . $opcion . "',";
    $sql .= "link = '" . $link . "',";
    $sql .= "icono = '" . $icono . "',";
    $sql .= "tipo_regla = '" . $tipo_regla . "',";
    $sql .= "variable = '" . $variable . "',";
    $sql .= "operador = '" . $operador . "',";
    $sql .= "valor = '" . $valor . "',";
    $sql .= "descripcion = '" . $descripcion . "',";
    $sql .= "activo = '" . $activo . "',";
    $sql .= "orden = " . $orden . ",";
    $sql .= "fecha_modificacion = CURRENT_TIMESTAMP ";
    $sql .= "WHERE id = " . $id;
    
    $result = $conn->Execute($sql);
    
    if ($result) {
        return ['success' => true, 'message' => 'Regla modificada correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al modificar regla: ' . $conn->ErrorMsg()];
    }
}

// ELIMINAR
function eliminar($conn) {
    $id = intval($_POST["id"]);
    
    $sql = "DELETE FROM menu_reglas WHERE id = " . $id;
    $result = $conn->Execute($sql);
    
    if ($result) {
        return ['success' => true, 'message' => 'Regla eliminada correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al eliminar regla: ' . $conn->ErrorMsg()];
    }
}

// TOGGLE ACTIVO
function toggleActivo($conn) {
    $id = intval($_POST["id"]);
    
    $sql = "UPDATE menu_reglas SET activo = CASE WHEN activo = 'S' THEN 'N' ELSE 'S' END, fecha_modificacion = CURRENT_TIMESTAMP WHERE id = " . $id;
    $result = $conn->Execute($sql);
    
    if ($result) {
        return ['success' => true, 'message' => 'Estado actualizado'];
    } else {
        return ['success' => false, 'message' => 'Error: ' . $conn->ErrorMsg()];
    }
}

// Procesar acci&oacute;n
$response = ['success' => false, 'message' => 'Acci&oacute;n no v&aacute;lida'];

switch ($sAccion) {
    case "I": 
        $response = insertar($conn);
        break;
    case "M": 
        $response = modificar($conn);
        break;
    case "E": 
        $response = eliminar($conn);
        break;
    case "T": 
        $response = toggleActivo($conn);
        break;
}

echo json_encode($response);
exit;
?>

