<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
  include("../include/ver_aut_adm.php");       
  include("../include/db_lib.php"); 
  include("../include/tables.php"); 
 
 $nCodUser = trim($_GET["nCodUser"]);
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
	if(trim($nCodUser) != "")	 
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
  
  <form name="formIndex" action="user_emp/pro_user_emp.php" method="post"> 
  <input type="hidden" name="op" value="<?php echo $op; ?>">
 <a href="#" name="top" id="top"></a>
 <table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

 <table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td id="screenWH">
 <div class="pathbar"><a href="javascript:void(0);" onClick="location.href='<?php echo $_LINK_BASE . $url; ?>';">Usuarios/Empresas</a> &gt;</div>
 <div class="screenTitle">
  <table width="100%" cellspacing="0">
  <tr>
   <td>Relaciona Usuario con Empresas</td>
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
  <td  align="right"> <label for="fid-cname">Usuario:</label>&nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;</td>
  <td  width="90%" align="left">
      <label for="fid-cname">      
        
        <SELECT NAME="nCodUser" size="1" onChange="location.href='<?php echo $_LINK_BASE; ?>user_emp/add_user_emp.php?nCodUser=' + this.options[this.selectedIndex].value + '&op=<?php echo $op; ?>';">
<?php 
          if($nCodUser <> "")
            echo '<option value="">Selecione Usuario</option>  \n ';
          else
            echo '<option value="" selected>Selecione Usuario</option>  \n ';          
            
        $sql = "SELECT cod_usu, id_usu FROM usuario ORDER BY id_usu";       
        $result = rCursor($conn, $sql);
        
        while (!$result->EOF) {
          $nCodUsut = trim($result->fields["cod_usu"]);          
          $nIdUsut = trim($result->fields["id_usu"]);    
                          
          if($nCodUsut == $nCodUser)       
            echo '<option value="' . $nCodUsut . '" selected>' . $nIdUsut . '</option>';
          else
            echo '<option value="' . $nCodUsut . '">' . $nIdUsut . '</option>';          

          $result->MoveNext();
        } 
?>        
            </SELECT>    
</label>&nbsp;</td>
  <td  width="10" align="left">&nbsp;</td>
  <td  align="left">&nbsp;</td>
 </tr>  

  
<?php 

  if($nCodUser <> ""){

?>  
  
 <tr>
  <td colspan="3">
   <table cellspacing="0" width="30%" align="center">
        <tr>
     <td align="center">Empresas</td>
     <td align="center">&nbsp;</td>
     <td align="center">Relacionadas</td>
        </tr>                  

      
    <tr>
     <td align="center">
          <SELECT NAME="TableColumnList" multiple ="multiple" size ="10" style ="width: 60em;">
  <?php 
          $sql = "select codi_empr, rs_empr FROM empresa ORDER BY rs_empr";  
          $sql = "  SELECT codi_empr, rs_empr FROM empresa E  ";
          $sql .= " WHERE codi_empr NOT IN (SELECT codi_empr FROM empr_usu WHERE cod_usu = " . $nCodUser . ") ORDER BY rs_empr";      

                         
          $result = rCursor($conn, $sql);
          
          while (!$result->EOF) {
            $nCodEmp = trim($result->fields["codi_empr"]);          
            $sRsEmp = trim($result->fields["rs_empr"]);    
                            
            echo '<option value="' . $nCodEmp . '">' . $sRsEmp . '</option> \n';
  
            $result->MoveNext();
          } 
  ?>        
              </SELECT>    
          
          </td>
          
          <td align="center"><br><br><br>
            <button name ="remove" onclick ="buttonPressed(this);" type ="button">&lt;&lt;</button><br>
            <button name ="add" onclick ="buttonPressed(this);" type ="button">&gt;&gt;</button>
          </td>
      
          <td align="center">
            <select name ="IndexColumnList[]" multiple ="multiple" size ="10" style ="width: 60em;" id ="IndexColumnList">
<?php 
            $sql = "    SELECT ";
            $sql .= "        EU.codi_empr,  ";
            $sql .= "        E.rs_empr  ";
            $sql .= "    FROM  ";
            $sql .= "        empr_usu EU,  ";
            $sql .= "        empresa E,  ";
            $sql .= "        usuario U ";
            $sql .= "    WHERE ";
            $sql .= "        EU.codi_empr = E.codi_empr AND ";
            $sql .= "        EU.cod_usu = U.cod_usu AND ";
            $sql .= "        EU.cod_usu =  " . $nCodUser;
            $result = rCursor($conn, $sql);
          
            while (!$result->EOF) {
              $nCodEmp = trim($result->fields["codi_empr"]);          
              $sRsEmp = trim($result->fields["rs_empr"]);    
                            
              echo '<option value="' . $nCodEmp . '">' . $sRsEmp . '</option> \n';
    
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

  if($nCodUser <> ""){

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
