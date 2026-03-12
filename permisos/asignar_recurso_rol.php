<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");       
  include("../include/db_lib.php"); 
  include("../include/tables.php"); 
 
 $nCodEmp = isset($_GET["nCodEmp"]) ? trim($_GET["nCodEmp"]) : "";
 $op = isset($_GET["op"]) ? trim($_GET["op"]) : ""; 
 $conn = conn();

 if (!function_exists('h')) {
   function h($value) {
     return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
   }
 }

 
  if($op == "0")
    $url = "empresa/listempre.php";
  else
    $url = "usuario/list_user.php"; 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
  
  <head>
    <link rel="shortcut icon" href="/favicon.ico">
    <title>OpenB</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <base href="<?php echo $_LINK_BASE; ?>" />
    <script language="javascript" type="text/javascript" src="javascript/common.js"></script>
    <script language="javascript" type="text/javascript" src="javascript/msg.js"></script>
    
    <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
    <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
    <link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
	    <link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

	    <style type="text/css">
	      body{background:#f3f6fb;color:#1f2937;}
	      .page-shell{max-width:1200px;margin:0 auto;padding:1.25rem;}
	      .topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;background:linear-gradient(135deg,#001f3f 0%,#0b5ed7 100%);color:#fff;border-radius:20px;padding:1.15rem 1.35rem;box-shadow:0 18px 40px rgba(15,23,42,.18);margin-bottom:1rem;}
	      .topbar-eyebrow{font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;opacity:.82;margin-bottom:.25rem;}
	      .topbar-title{font-size:1.45rem;font-weight:700;line-height:1.15;margin:0;}
	      .topbar-meta{font-size:.92rem;opacity:.9;margin-top:.35rem;max-width:760px;}
	      .topbar-chip{display:inline-flex;align-items:center;padding:.35rem .7rem;border-radius:999px;background:rgba(255,255,255,.16);font-size:.8rem;font-weight:600;}
	      .panel{background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:20px;box-shadow:0 18px 40px rgba(15,23,42,.08);overflow:hidden;}
	      .panel-header{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;padding:1.05rem 1.25rem;border-bottom:1px solid #e5e7eb;background:#f8fafc;}
	      .panel-title{font-size:1.02rem;font-weight:700;margin:0;color:#0f172a;}
	      .panel-subtitle{font-size:.88rem;color:#64748b;margin-top:.25rem;}
	      .panel-body{padding:1.25rem;}
	      .panel-footer{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem 1.25rem;border-top:1px solid #e5e7eb;background:#f8fafc;}
	      .panel-note{font-size:.88rem;color:#64748b;background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;padding:.8rem .95rem;}
	      .form-actions{display:flex;flex-wrap:wrap;gap:.65rem;align-items:center;}
	      .selection-layout{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;align-items:stretch;}
	      .selection-card{border:1px solid #dbe3ee;border-radius:16px;padding:1rem;background:#fff;}
	      .selection-title{font-size:.92rem;font-weight:700;color:#0f172a;margin-bottom:.35rem;}
	      .selection-help{font-size:.85rem;color:#64748b;margin-bottom:.75rem;}
	      .selection-select{min-height:18rem;}
	      .selection-select[multiple]{padding:.5rem;}
	      .empty-state{border:1px dashed #cbd5e1;border-radius:18px;padding:2rem 1.25rem;text-align:center;background:#f8fafc;color:#64748b;}
	      .helper-text{font-size:.85rem;color:#64748b;}
	      @media (max-width: 991px){
	        .topbar,.panel-header,.panel-footer{flex-direction:column;align-items:stretch;}
	        .selection-layout{grid-template-columns:1fr;}
	      }
	    </style>


<script type="text/javascript">
<!--


function _body_onload()
{
  loff();
 <?php 
  if(trim($nCodEmp) != "")   
    echo "init(); \n ";
 ?>

  SetContext('cl_ed');
    
}

function _body_onunload()
{
  lon();
  
}

//-->
    </script>
    
    

<script>

    function init() {

	             tableColumnList = document.getElementById("TableColumnList");

             indexColumnList = document.getElementById("IndexColumnList");
             indexColumns = indexColumnList.options;
             tableColumns = tableColumnList.options;
    }

    function buttonPressed(object) {



             if (object.name == "add") {

                 from = tableColumnList;

                 to = indexColumnList;

             }

             else {

                 to = tableColumnList;

                 from = indexColumnList;

             }



             var selectedOptions = getSelectedOptions(from);


             for (i = 0; i < selectedOptions.length; i++) {

                  option = new Option(selectedOptions[i].text, selectedOptions[i].value);

                  addToArray(to, option);

                  removeFromArray(from, selectedOptions[i].index);

             }

    }

    function getSelectedOptionsTest() {
        obj = indexColumnList;

        var selectedOptions = new Array();


        for (i = 0; i < obj.options.length; i++) {

            alert(obj.options[i].value);
        }

    }
    
    function getSelectedOptions(obj) {

             var selectedOptions = new Array();



             for (i = 0; i < obj.options.length; i++) {

                  if (obj.options[i].selected) {
                      
                      selectedOptions.push(obj.options[i]);

                  }

             }



             return selectedOptions;

    }
    
    function addToArray(obj, item) {

             obj.options[obj.options.length] = item;

    }

    function removeFromArray(obj, index) {

             obj.remove(index);

    }

    function enviar(){
      
      for(i=0; i < indexColumnList.length; i++)
        indexColumnList.options[i].selected = true;
        
      document.formIndex.submit();
    }

</script>
    
  </head>

	  <body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	    <form name="formIndex" action="permisos/pro_recurso_rol.php" method="post">
	      <input type="hidden" name="op" value="<?php echo h($op); ?>">
	      <input type="hidden" name="start" value="">
	      <a href="#" name="top" id="top"></a>
	      <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	      <div class="page-shell">
	        <div class="topbar">
	          <div>
	            <div class="topbar-eyebrow">Permisos / Recursos</div>
	            <h1 class="topbar-title">Relacionar roles con recursos</h1>
	            <div class="topbar-meta">Se conserva el selector de empresa, la selecci&oacute;n de recursos y roles, y el env&iacute;o hacia <strong>permisos/pro_recurso_rol.php</strong>.</div>
	          </div>
	          <div class="form-actions">
	            <span class="topbar-chip"><?php echo ($op == "0") ? 'Desde empresa' : 'Desde usuario'; ?></span>
	            <a href="<?php echo h($_LINK_BASE . $url); ?>" class="btn btn-outline-light btn-sm">Volver</a>
	          </div>
	        </div>

	        <div class="panel">
	          <div class="panel-header">
	            <div>
	              <div class="panel-title">Selecci&oacute;n principal</div>
	              <div class="panel-subtitle">Cambiar la empresa recarga esta pantalla manteniendo el flujo legacy de administraci&oacute;n.</div>
	            </div>
	          </div>

	          <div class="panel-body">
	            <div class="row g-3 align-items-end">
	              <div class="col-lg-8">
	                <label class="form-label fw-semibold" for="nCodEmp">Empresa</label>
	                <select name="nCodEmp" id="nCodEmp" class="form-select" onchange="location.href='<?php echo $_LINK_BASE; ?>permisos/asignar_recurso_rol.php?nCodEmp=' + this.options[this.selectedIndex].value + '&op=<?php echo rawurlencode($op); ?>';">
	<?php 
	          $sql = "select codi_empr, rs_empr FROM empresa ORDER BY rs_empr";  
	          $result = rCursor($conn, $sql);
	          
	          if($nCodEmp <> "")
	            echo '<option value="">Selecione Empresa</option>\n';
	          else
	            echo '<option value="" selected>Selecione Empresa</option>\n';
	          
	          while (!$result->EOF) {
	          
	            $nCodEmpt = trim($result->fields["codi_empr"]);          
	            $sRsEmpt = trim($result->fields["rs_empr"]);    

	            if($nCodEmpt == $nCodEmp)
	              echo '<option value="' . $nCodEmpt . '" selected>' . h($sRsEmpt) . '</option> \n';
	            else
	              echo '<option value="' . $nCodEmpt . '">' . h($sRsEmpt) . '</option> \n';
	  
	            $result->MoveNext();
	          } 
	?>
	                </select>
	              </div>
	              <div class="col-lg-4">
	                <div class="panel-note">Seleccione una empresa para relacionar recursos con un rol dentro del flujo actual.</div>
	              </div>
	            </div>

	<?php if($nCodEmp <> ""){ ?>
	            <div class="selection-layout mt-4">
	              <div class="selection-card">
	                <div class="selection-title">Recursos</div>
	                <div class="selection-help">Seleccione uno o varios recursos para asociarlos al rol elegido.</div>
	                <select name="TableColumnList[]" id="TableColumnList" multiple="multiple" size="10" class="form-select selection-select">
	<?php  

	        $sql = "  SELECT cod_recurso, desc_recurso FROM recurso ";                            
	        
	        $result = rCursor($conn, $sql);
	        
	        while (!$result->EOF) {
	          $cod_recurso = trim($result->fields["cod_recurso"]);          
	          $desc_recurso = trim($result->fields["desc_recurso"]);    
	                          
	          echo '<option value="' . $cod_recurso . '">' . h($desc_recurso) . '</option>';          

	          $result->MoveNext();
	        } 
	?>
	                </select>
	              </div>

	              <div class="selection-card">
	                <div class="selection-title">Roles disponibles</div>
	                <div class="selection-help">Seleccione el rol que recibir&aacute; los recursos elegidos.</div>
	                <select name="IndexColumnList" size="10" class="form-select selection-select" id="IndexColumnList">
	<?php 
	            $sql = "    SELECT ";
	            $sql .= "        R.cod_rol,  ";
	            $sql .= "        R.desc_rol  ";
	            $sql .= "    FROM  ";
	            $sql .= "        rol R ";
	            $result = rCursor($conn, $sql);
	          
	            while (!$result->EOF) {
	              $nCodRol = trim($result->fields["cod_rol"]);          
	              $sDescRol = trim($result->fields["desc_rol"]);    
	                            
	              echo '<option value="' . $nCodRol . '">' . h($sDescRol) . '</option> \n';
	    
	              $result->MoveNext();
	            } 
	?>
	                </select>
	              </div>
	            </div>
	<?php } else { ?>
	            <div class="empty-state mt-4">
	              <div class="fw-semibold mb-2">Aun no hay empresa seleccionada</div>
	              <div>Primero seleccione una empresa para elegir recursos y rol dentro de este flujo de permisos.</div>
	            </div>
	<?php } ?>
	          </div>

	          <div class="panel-footer">
	            <div class="helper-text"><span class="required">*</span> Campos requeridos.</div>
	            <div class="form-actions">
	<?php if($nCodEmp <> ""){ ?>
	              <button type="button" class="btn btn-primary" onclick="enviar();">Aceptar</button>
	<?php } ?>
	              <a href="<?php echo h($_LINK_BASE . $url); ?>" class="btn btn-outline-secondary">Cancelar</a>
	            </div>
	          </div>
	        </div>
	      </div>
	    </form>
	  </body>

	  <script type="text/javascript">
	    try {
	      lsetup();
	    } catch (e) {
	    }
	  </script>

	</html>