<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");       
  include("../include/db_lib.php"); 
  include("../include/tables.php"); 
 
 $nCodEmp = trim($_GET["nCodEmp"]);
 $op = trim($_GET["op"]); 
 $conn = conn();
 
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

             tableColumnList = document.formIndex.TableColumnList;

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
  <input type="hidden" name="op" value="<?php echo $op; ?>">
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
	<div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE . $url; ?>';">Empresa/Usuarios</a> &gt;</div>
	<div class="screenTitle">
		<table width="100%" cellspacing="0">
		<tr>
			<td>Agregar Recurso</td>
			<td class="uplevel">
      <a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE . $url; ?>';">
      <div class="commonButton" id="bid-up-level" title="Subir nivel"><button name="bname_up_level">Subir nivel</button><span>Subir nivel</span></div></a></td>
		</tr>
		</table>
	</div>
	<div id="screenSubTitle"></div>
	<div id="screenTabs">
		<div id="tabs">
			
		</div>

	</div>
	<div class="screenBody" id="">
		
	<div class="formArea">
		<fieldset>

<legend>Usuarios </legend>
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>

<table class="formFields" cellspacing="0" width="30%" align="center">

	<tr>
		<td  align="right"> <label for="fid-cname">Empresa:</label>&nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;</td>
    
					
      <td  width="90%" align="left">          
          <SELECT NAME="nCodEmp" size="1" onChange="location.href='<?php echo $_LINK_BASE; ?>permisos/add_recurso.php?nCodEmp=' + this.options[this.selectedIndex].value + '&op=<?php echo $op; ?>';">
  <?php 
          $sql = "select codi_empr, rs_empr FROM empresa ORDER BY rs_empr";  
          $result = rCursor($conn, $sql);
          
          if($nCodEmp <> "")
            echo '<option value="">Selecione Empresa</option>		\n	';
          else
            echo '<option value="" selected>Selecione Empresa</option>		\n	';           
          
          while (!$result->EOF) {
          
            $nCodEmpt = trim($result->fields["codi_empr"]);          
            $sRsEmpt = trim($result->fields["rs_empr"]);    

            if($nCodEmpt == $nCodEmp)	                            
              echo '<option value="' . $nCodEmpt . '" selected>' . $sRsEmpt . '</option> \n';
            else
              echo '<option value="' . $nCodEmpt . '">' . $sRsEmpt . '</option> \n';
  
            $result->MoveNext();
          } 
  ?>        
              </SELECT>    
          
          </td>

		<td  width="10" align="left">&nbsp;</td>
		<td  align="left">&nbsp;</td>
	</tr>		

  
<?php 

  if($nCodEmp <> ""){
        
?>  
  
	<tr>
		<td colspan="3">
			<table cellspacing="0" width="30%" align="center">
        <tr>
					<td align="center">Usuarios</td>
					<td align="center">&nbsp;</td>
					<td align="center">Relacionadas</td>
        </tr>                  

      
				<tr>

		<td align="center">
      <label for="fid-cname">						
        
        
        <SELECT NAME="TableColumnList" multiple ="multiple" size ="10" style ="width: 10em;">
<?php         
        $sql = "  SELECT cod_recurso,des_recurso,url FROM recurso ";                            
        
        $result = rCursor($conn, $sql);
        
        while (!$result->EOF) {
          $cod_recurso = trim($result->fields["cod_recurso"]);          
          $des_recurso = trim($result->fields["des_recurso"]);    
                          
          echo '<option value="' . $cod_recurso . '">' . $des_recurso . '</option>';          

          $result->MoveNext();
        } 
?>        
            </SELECT>    
</label>&nbsp;</td>        
        
                  
          <td align="center"><br><br><br>
            <!--
            <button name ="remove" onclick ="buttonPressed(this);" type ="button">&lt;&lt;</button><br>
            <button name ="add" onclick ="buttonPressed(this);" type ="button">&gt;&gt;</button>
          -->
          </td>
      
          <td align="center">
            <select name ="IndexColumnList" size ="10" style ="width: 10em;" id ="IndexColumnList">
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
                            
              echo '<option value="' . $nCodRol . '">' . $sDescRol . '</option> \n';
    
              $result->MoveNext();
            } 
?>                   
            
            </select>
          </td>
                
      </table>
		</td>
  </tr>  
  
<?php 
  } // fin if 2
?>
  
  
</table>

<input type="hidden" name="start" value="">

</td></tr></table></fieldset>

	</div>
	
	<div class="formArea">
		<table width="100%" class="buttons" cellspacing="0" cellpadding="0"><tr>
			<td class="main" width="0"></td>
			<td class="footnote"><span class="required">*</span> Campos requeridos.</td>
			<td class="misc" width="0">
				
<?php 

  if($nCodEmp <> ""){

?>  
        
        <div class="commonButton" id="bid-ok" title="Aceptar" onClick="enviar();" onMouseOver="" onMouseOut="">
        
        <button name="bname_ok">Aceptar</button><span>Aceptar</span></div>
<?php } ?>          
        <a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE . $url; ?>';">
				<div class="commonButton" id="bid-cancel" title="Cancelar" onMouseOver="" onMouseOut=""><button name="bname_cancel">Cancelar</button><span>Cancelar</span></div></a>

        
        
			</td>
		</tr></table>

	</div>

</form>

	</div>
	</td></tr></table>
	</body>

	<script type="text/javascript">
		try {
			lsetup();
		} catch (e) {
		}
	</script>

</html>