<?php 
  include("../include/config.php");
//  include("../include/ver_aut.php");
//  include("../include/ver_aut_adm.php");

        include("../include/ver_aut.php");
    include("../include/ver_emp_adm.php");

  include("../include/db_lib.php");

	$nFolio	= $_GET["nFolio"];
	$nTipoDTE	= $_GET["nTipoDTE"];
	

	if(trim($nFolio) == "" || $nTipoDTE == "" ){
		echo "
			<SCRIPT>
				alert(\"Faltan folio o tipo de documento \");
				window.close();
			</SCRIPT>";
	
	} 

                    $conn = conn();
		      
			$sql = "SELECT email_envio FROM clientes WHERE rut_cli IN (SELECT rut_rec_dte FROM dte_enc WHERE folio_dte=$nFolio
 			AND tipo_docu= $nTipoDTE AND codi_empr=" . $_SESSION["_COD_EMP_USU_SESS"] . ")";
			
					$result = rCursor($conn, $sql);
					if(!$result->EOF) {
						$emailenvio = trim($result->fields["email_envio"]);
		
}
			$sql = "SELECT email_contr FROM contrib_elec WHERE rut_contr IN (SELECT rut_rec_dte FROM dte_enc WHERE folio_dte=$nFolio 
			AND tipo_docu=$nTipoDTE AND codi_empr=" . $_SESSION["_COD_EMP_USU_SESS"] . ")";
			
					$result = rCursor($conn, $sql);
					if(!$result->EOF) {
					$emailcontribuyente = trim($result->fields["email_contr"]);	
			
}

		 	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
 <HEAD>
  <TITLE> Reenviar DTE </TITLE>
  <META NAME="Generator" CONTENT="EditPlus">
  <META NAME="Author" CONTENT="">
  <META NAME="Keywords" CONTENT="">
  <META NAME="Description" CONTENT="">

<base href="<?php echo $_LINK_BASE; ?>" />

<script language="javascript" type="text/javascript" src="javascript/funciones.js"></script>
<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/msg.js"></script>

<link rel="stylesheet" type="text/css" href="skins/aqua/css/general.css">
<link rel="stylesheet" type="text/css" href="skins/aqua/css/main/custom.css">
<link rel="stylesheet" type="text/css" href="skins/aqua/css/main/layout.css">
<link rel="stylesheet" type="text/nonsense" href="skins/aqua/css/misc.css">

  <SCRIPT LANGUAGE="JavaScript">
  <!--

	function valida(){
		if(confirm("Confirma el Reenvio del documento ??")){
			if(email(document._F.sDestinatario.value,"Ingrese un Email Valido") == false){
				document._F.sDestinatario.focus();
				document._F.sDestinatario.select();
				return false;
			}
			return true;
		}
		return false;
	}

   	function IngresarEmail(opcion){
		if(opcion==1)
			document._F.sDestinatario.value=document._F.emailcliente.value;

		if(opcion==2)
			document._F.sDestinatario.value=document._F.emailcontribuyente.value;
}



  //-->
  </SCRIPT>
 </HEAD>
 
			

 <BODY>
 <FORM name="_F" METHOD=POST ACTION="<?php echo $_LINK_BASE; ?>dte/pro_resend.php" onSubmit="return valida();">	
	<INPUT TYPE="hidden" NAME="nFolio" value="<?php echo $nFolio ?>">
	<INPUT TYPE="hidden" NAME="nTipoDTE" value="<?php echo $nTipoDTE ?>">

	<INPUT TYPE="hidden" NAME="emailcliente" value="<?php echo $emailenvio ?>">
	<INPUT TYPE="hidden" NAME="emailcontribuyente" value="<?php echo $emailcontribuyente ?>">
  <TABLE width="100%" cellspacing="0" class="list">
  <TR>
	<Th colspan="2"><H3>Enviar Folio <?php echo $nFolio ?> (tipo <?php echo $nTipoDTE ?>)</H3></Th>
  </TR>
  <TR  class="evenrowbg">
	<TD>Tipo Envio: </TD>
	<TD><INPUT TYPE="radio" NAME="nTipoEnvio" value="PDF" checked onClick="IngresarEmail(1)">PDF &nbsp; <INPUT TYPE="radio" NAME="nTipoEnvio"  value="XML"  onclick="IngresarEmail(2)" >XML</TD>
	
  </TR>
  <TR  class="oddrowbg">
	<TD>Email Destino: </TD>
	<TD><INPUT TYPE="text" NAME="sDestinatario" value="<?php echo $emailenvio ?>" maxlength="200"></TD>
  </TR>
  <TR  class="evenrowbg">
	<TD colspan="2"><BR><INPUT TYPE="submit" value="Re-Enviar"></TD>
  </TR>


			

  </TABLE>
 </FORM>
 </BODY>
</HTML>
