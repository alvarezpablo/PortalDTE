<?php
// Forzamos la cabecera HTTP a ISO-8859-1
header('Content-Type: text/html; charset=ISO-8859-1');

/*********************************************************************
 * EJEMPLO COMPLETO - cotizacion.php
 *
 * DEMO:
 *  - Formulario con RUT, Nombre Contacto, Correo, Cantidad Documentos.
 *  - Requiere Certificación (Sí/No). Si Sí, habilita selección múltiple
 *    para tipos de documentos a certificar (se pueden elegir varios).
 *  - Cálculo de cotización en UF: Setup fijo (5 UF) + Mensualidad
 *    (según tabla de rangos) + Costo(s) de Certificación (si aplica).
 *  - Validaciones JavaScript (RUT, Nombre != blanco, Correo válido).
 *  - Validación y cálculo en PHP.
 *********************************************************************/

/**
 * Retorna el valor mensual en UF de acuerdo al rango de documentos.
 * Ejemplo de tabla:
 * (0 - 500) => 2.00 | (501 - 750) => 2.40 | (751 - 1000) => 3.16 | etc.
 */
function getValorMensualUF($cantidad) {
    if ($cantidad >= 0   && $cantidad <= 500)   return 2.00;
    if ($cantidad >= 501 && $cantidad <= 750)   return 2.40;
    if ($cantidad >= 751 && $cantidad <= 1000)  return 3.16;
    if ($cantidad >= 1001 && $cantidad <= 1500) return 4.22;
    if ($cantidad >= 1501 && $cantidad <= 2500) return 6.17;
    if ($cantidad >= 2501 && $cantidad <= 3000) return 7.99;
    if ($cantidad >= 3001 && $cantidad <= 3500) return 9.15;
    if ($cantidad >= 3501 && $cantidad <= 4000) return 10.28;
    return null; // Fuera de rango
}

/**
 * Retorna el costo de certificación en UF, según el tipo de documento.
 * Se asume que sumaremos los costos si el usuario selecciona varios.
 */
function getCostosCertificacionUF($tiposSeleccionados) {
    // Precios por cada tipo (en UF)
    $costos = array(
        'Set Boleta'         => 3.0,
        'Set Básico'         => 4.0,
        'Set Exento'         => 2.0,
        'Set Exportación'    => 4.0,
        'Guía de Despacho'   => 3.0,
        'Factura de Compra'  => 2.0
    );

    $suma = 0.0;
    foreach ($tiposSeleccionados as $tipo) {
        if (isset($costos[$tipo])) {
            $suma += $costos[$tipo];
        }
    }
    return $suma;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <title>Formulario de Cotizaci&oacute;n</title>
  <script type="text/javascript">
    /**
     * Valida un RUT chileno (con dígito verificador).
     * Retorna true si es válido, false en caso contrario.
     */
    function validarRutChileno(rutCompleto) {
      rutCompleto = rutCompleto.toUpperCase().trim();
      rutCompleto = rutCompleto.replace(/\./g, '').replace(/-/g, '');

      if (rutCompleto.length < 2) {
        return false;
      }

      var dv = rutCompleto.charAt(rutCompleto.length - 1);
      var cuerpo = rutCompleto.slice(0, -1);

      if (!/^\d+$/.test(cuerpo)) {
        return false;
      }

      var suma = 0;
      var multiplo = 2;
      for (var i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo.charAt(i)) * multiplo;
        multiplo = multiplo < 7 ? multiplo + 1 : 2;
      }
      var resto = 11 - (suma % 11);
      var dvEsperado = '';
      if (resto === 11) {
        dvEsperado = '0';
      } else if (resto === 10) {
        dvEsperado = 'K';
      } else {
        dvEsperado = String(resto);
      }

      return (dv === dvEsperado);
    }

    /**
     * Valida el formato de correo.
     * Retorna true si es válido, false en caso contrario.
     */
    function validarEmail(email) {
      // Expresión regular básica (puedes ajustarla según requieras)
      var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(email);
    }

    /**
     * Habilita o deshabilita el select multiple según "requiere_certificacion".
     */
    function toggleTipoDocumento() {
      var requiereCertSelect = document.getElementById('requiere_certificacion');
      var tipoDocSelect = document.getElementById('tipo_documento_certificar');

      if (requiereCertSelect.value === '1') {
        // Sí requiere certificación => habilitamos el multiple select
        tipoDocSelect.disabled = false;
      } else {
        // No requiere => deshabilitamos el multiple select y limpiamos selección
        tipoDocSelect.disabled = true;
        // Limpiamos selección
        for (var i = 0; i < tipoDocSelect.options.length; i++) {
          tipoDocSelect.options[i].selected = false;
        }
      }
    }

    /**
     * Se ejecuta al enviar el formulario.
     * Validaciones: RUT, Nombre (no vacío), Correo (formato) y RUT chileno válido.
     */
    function validarFormulario() {
      // 1. RUT
      var rutInput = document.getElementById('rut');
      var rutValor = rutInput.value.trim();
      if (!validarRutChileno(rutValor)) {
        alert('El RUT ingresado no es válido. Por favor, verifíquelo.');
        rutInput.focus();
        return false;
      }

      // 2. Nombre Contacto (no puede estar en blanco)
      var nombreInput = document.getElementById('nombre_contacto');
      var nombreValor = nombreInput.value.trim();
      if (nombreValor === '') {
        alert('El Nombre de Contacto no puede estar vacío.');
        nombreInput.focus();
        return false;
      }

      // 3. Correo electrónico (validar formato)
      var correoInput = document.getElementById('correo_contacto');
      var correoValor = correoInput.value.trim();
      if (!validarEmail(correoValor)) {
        alert('El Correo Electrónico no es válido.');
        correoInput.focus();
        return false;
      }

      // Si llega aquí, pasó las validaciones de JS
      return true;
    }

    // Cuando cargue la página, configurar el estado inicial del select
    window.onload = function() {
      toggleTipoDocumento();
    };
  </script>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se ha enviado el formulario, procesamos la cotización

    // 1. Obtener datos del POST (y sanitizar mínimamente)
    $rut            = isset($_POST['rut'])               ? trim($_POST['rut']) : '';
    $nombreContacto = isset($_POST['nombre_contacto'])    ? trim($_POST['nombre_contacto']) : '';
    $correoContacto = isset($_POST['correo_contacto'])    ? trim($_POST['correo_contacto']) : '';
    $cantidad       = isset($_POST['cantidad_documentos']) ? (int) $_POST['cantidad_documentos'] : 0;
    
    // "requiere_certificacion" => "0" (No) o "1" (Sí)
    $requiereCert = (isset($_POST['requiere_certificacion']) && $_POST['requiere_certificacion'] === '1');

    // "tipo_documento_certificar" es un array si multiple, sino vendrá como array vacía
    $tiposSeleccionados = isset($_POST['tipo_documento_certificar']) ? $_POST['tipo_documento_certificar'] : array();
    if (!is_array($tiposSeleccionados)) {
        // Forzamos a array para evitar errores
        $tiposSeleccionados = array();
    }

    // 2. Validaciones mínimas en PHP (por seguridad)
    if ($nombreContacto === '') {
        echo '<h2>Error: el Nombre de Contacto no puede estar vacío.</h2>';
        echo '<p><a href="?'.mt_rand().'">Volver</a></p>';
        exit;
    }
    if (!filter_var($correoContacto, FILTER_VALIDATE_EMAIL)) {
        echo '<h2>Error: El Correo Electrónico no tiene un formato válido.</h2>';
        echo '<p><a href="?'.mt_rand().'">Volver</a></p>';
        exit;
    }

    // 3. Determinar valor mensual según tabla de rangos
    $valorMensualUF = getValorMensualUF($cantidad);
    if (is_null($valorMensualUF)) {
        // Cantidad fuera de los rangos permitidos
        echo '<h2>La cantidad de documentos ('.$cantidad.') excede los rangos definidos.</h2>';
        echo '<p><a href="?'.mt_rand().'">Volver</a></p>';
        exit;
    }

    // 4. Setup fijo de 5 UF
    $setupUF = 5.0;

    // 5. Costo de certificación (se suman costos de cada tipo si $requiereCert = true)
    $costoCertUF = 0.0;
    if ($requiereCert) {
        $costoCertUF = getCostosCertificacionUF($tiposSeleccionados);
    }

    // 6. Cálculo total:
    //    - Costo inicial (setup + certificación)
    //    - Valor mensual según volumen
    //    - Total primer mes = costo inicial + valor mensual
    $costoInicialUF        = $setupUF + $costoCertUF;
    $costoMensualUF        = $valorMensualUF;
    $costoTotalPrimerMesUF = $costoInicialUF + $costoMensualUF;

    // 7. Mostrar resumen
    echo '<h1>Cotizaci&oacute;n Generada</h1>';

    echo '<p><strong>RUT:</strong> '.htmlspecialchars($rut, ENT_QUOTES, 'ISO-8859-1').'</p>';
    echo '<p><strong>Nombre Contacto:</strong> '.htmlspecialchars($nombreContacto, ENT_QUOTES, 'ISO-8859-1').'</p>';
    echo '<p><strong>Correo Electr&oacute;nico:</strong> '.htmlspecialchars($correoContacto, ENT_QUOTES, 'ISO-8859-1').'</p>';
    echo '<p><strong>Cantidad de Documentos:</strong> '.$cantidad.'</p>';

    echo '<h3>Costos en UF</h3>';
    echo '<ul>';
    echo '<li><strong>Setup:</strong> '.number_format($setupUF, 2).' UF</li>';

    if ($requiereCert) {
        // Mostramos los tipos seleccionados
        $tiposStr = implode(', ', array_map(function($t) {
            // Filtro rápido
            return htmlspecialchars($t, ENT_QUOTES, 'ISO-8859-1');
        }, $tiposSeleccionados));
        
        echo '<li><strong>Certificaci&oacute;n ('.$tiposStr.'):</strong> '
           . number_format($costoCertUF,2).' UF</li>';
    } else {
        echo '<li><strong>Certificaci&oacute;n:</strong> No requerida</li>';
    }

    echo '<li><strong>Valor Mensual (por volumen):</strong> '
       . number_format($costoMensualUF, 2).' UF</li>';
    echo '</ul>';

    echo '<p><strong>Total Primer Mes:</strong> '
       . number_format($costoTotalPrimerMesUF, 2).' UF</p>';

    echo '<p><a href="?'.mt_rand().'">Volver</a></p>';

} else {
    // Formulario inicial
    ?>
    <h1>Formulario de Cotizaci&oacute;n</h1>
    <form action="" method="POST" onsubmit="return validarFormulario();">

      <!-- RUT -->
      <label for="rut">RUT Empresa (ej: 12.345.678-9):</label><br>
      <input type="text" id="rut" name="rut" required><br><br>

      <!-- Nombre Contacto -->
      <label for="nombre_contacto">Nombre Contacto:</label><br>
      <input type="text" id="nombre_contacto" name="nombre_contacto" required><br><br>

      <!-- Correo Electrónico -->
      <label for="correo_contacto">Correo Electr&oacute;nico:</label><br>
      <input type="email" id="correo_contacto" name="correo_contacto" required><br><br>

      <!-- Cantidad de Documentos -->
      <label for="cantidad_documentos">Cantidad de Documentos (emitidos + recibidos):</label><br>
      <input type="number" id="cantidad_documentos" name="cantidad_documentos" min="0" required><br><br>

      <!-- Requiere Certificación -->
      <label for="requiere_certificacion">&iquest;Requiere Certificaci&oacute;n?</label><br>
      <select id="requiere_certificacion" name="requiere_certificacion" onchange="toggleTipoDocumento();">
        <option value="0">No</option>
        <option value="1">S&iacute;</option>
      </select><br><br>

      <!-- Tipos de documento a certificar (MÚLTIPLE) -->
      <label for="tipo_documento_certificar">Tipo(s) de Documento a Certificar (selecci&oacute;n m&uacute;ltiple):</label><br>
      <select id="tipo_documento_certificar" name="tipo_documento_certificar[]" multiple size="5">
        <option value="Set Boleta">Set Boleta</option>
        <option value="Set Básico">Set Básico</option>
        <option value="Set Exento">Set Exento</option>
        <option value="Set Exportación">Set Exportación</option>
        <option value="Guía de Despacho">Guía de Despacho</option>
        <option value="Factura de Compra">Factura de Compra</option>
      </select>
      <br><br>

      <button type="submit">Generar Cotizaci&oacute;n</button>
    </form>

    <?php
}
?>

</body>
</html>
