<?php
include_once('grid_public_v_dte_session.php');
@session_start() ;

$oExportPdf = new grid_public_v_dte_export_pdf;
$oExportPdf->exportPdf();

class grid_public_v_dte_export_pdf {

function exportPdf() {

$aParams = isset($_POST['str_schema']) ? $_POST : $_GET;

$parms_pdf = (isset($aParams['sc_parms_pdf'])) ? $aParams['sc_parms_pdf'] : "";
$graf_pdf  = (isset($aParams['sc_graf_pdf']))  ? $aParams['sc_graf_pdf']  : "";
if (!function_exists("NM_is_utf8"))
{
    include_once("grid_public_v_dte_nmutf8.php");
}
if (!function_exists("nmButtonOutput"))
{
    include_once("../_lib/lib/php/nm_gp_config_btn.php");
}
$STR_schema  = $aParams['str_schema'];
$STR_tmp     = $aParams['str_tmp'];
$STR_prod    = $aParams['str_prod'];
$STR_btn     = $aParams['str_btn'];
$STR_lang    = $aParams['str_lang'];
$NM_arq_lang = "../_lib/lang/" . $STR_lang . ".lang.php";
$Nm_lang     = array();
$this->path_botoes = '../_lib/img';
if (is_file($NM_arq_lang))
{
    $Lixo = file($NM_arq_lang);
    foreach ($Lixo as $Cada_lin) 
    {
        if (strpos($Cada_lin, "array()") === false && (trim($Cada_lin) != "<?php")  && (trim($Cada_lin) != "?" . ">"))
        {
            eval (str_replace("\$this->", "\$", $Cada_lin));
        }
    }
}
$_SESSION['scriptcase']['charset'] = (isset($Nm_lang['Nm_charset']) && !empty($Nm_lang['Nm_charset'])) ? $Nm_lang['Nm_charset'] : "ISO-8859-15";
foreach ($Nm_lang as $ind => $dados)
{
   if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($dados))
   {
       $Nm_lang[$ind] = mb_convert_encoding($dados, $_SESSION['scriptcase']['charset'], "UTF-8");
   }
}
$this->Nm_lang = $Nm_lang;
include_once('../_lib/buttons/' . str_replace('.css', '.php', $STR_btn));
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<head>
 <title>Consulta DTE Emitidos :: PDF</title>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
 <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $STR_schema; ?>_export.css" /> 
 <link rel="stylesheet" type="text/css" href="../_lib/buttons/<?php echo $STR_btn; ?>" /> 
 <script type="text/javascript" src="<?php echo $STR_prod; ?>/third/jquery/js/jquery.js"></script>
 <script type="text/javascript">
 $(function(){
  document.getElementById("idBtnView").disabled = true;
  checkPDF();
 });
 function checkPDF() {
  $.get(nm_url_rand("grid_public_v_dte_gauge_ctrl.php"), {
   pbfile    : "<?php echo $aParams['pbfile'] ?>",
   sc_apbgcol: "<?php echo urlencode($aParams['sc_apbgcol']) ?>",
   str_lang  : "<?php echo $aParams['str_lang']; ?>",
   str_schema: "<?php echo $STR_schema ?>"
  },
  function (data){
   var aInfo = data.split("!#!");
   if (4 == aInfo.length) {
    var iSize = aInfo[0],
        iStep = aInfo[1],
        iEnd  = aInfo[2],
        sMsg  = aInfo[3],
        iPerc = Math.floor((iStep * 95) / iSize);
    if (1 == iEnd) {
     iPerc = 100;
    }
    if (1 <= iPerc) {
     $("#idPbarDone").css("display", "");
    }
    $("#idMessage").html(sMsg);
    $("#idPbarDone").css("width", iPerc + "%");
    $("#idPbarRest").css("width", (100 - iPerc) + "%");
    if (1 == iEnd) {
     document.getElementById("idBtnView").disabled = false;
     $("#idPbarRest").css("display", "none");
     return;
    }
   }
   setTimeout("checkPDF()", 1000);
  });
 }
 function nm_url_rand(v_str_url)
 {
  str_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  str_rand  = v_str_url;
  str_rand += (-1 == v_str_url.indexOf('?')) ? '?' : '&';
  str_rand += 'r=';
  for (i = 0; i < 8; i++)
  {
   str_rand += str_chars.charAt(Math.round(str_chars.length * Math.random()));
  }
  return str_rand;
 }
 </script>
</head>
<body class="scExportPage">
<?php
$NM_pdfbase = (isset($_SESSION['sc_session'][$aParams['script_case_init']]['grid_public_v_dte']['pdf_name'])) ? $_SESSION['sc_session'][$aParams['script_case_init']]['grid_public_v_dte']['pdf_name'] : 'sc_pdf_' . date('YmdHis') . '_' . rand(0, 1000) . '_grid_public_v_dte.pdf';
$NM_tit_doc = (isset($_SESSION['sc_session'][$aParams['script_case_init']]['grid_public_v_dte']['pdf_name'])) ? $_SESSION['sc_session'][$aParams['script_case_init']]['grid_public_v_dte']['pdf_name'] : "grid_public_v_dte.pdf";
$NM_pdfurl  = $STR_tmp;
$NM_target  = "_self";
$NM_volta   = "volta_grid";
?>
<form name="F0" method="post" action="grid_public_v_dte.php" target="<?php echo $NM_target; ?>" style="display: none"> 
<input type="hidden" name="nmgp_opcao" value="<?php echo NM_encode_input($NM_volta); ?>"> 
<input type="hidden" name="script_case_init" value="<?php echo NM_encode_input($aParams['script_case_init']); ?>"> 
<input type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id()); ?>"> 
</form>
<form name="Fdown" method="get" action="grid_public_v_dte_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="nm_tit_doc" value="<?php echo NM_encode_input($NM_tit_doc); ?>"> 
<input type="hidden" name="nm_name_doc" value="<?php echo NM_encode_input($NM_pdfurl . "/" . $NM_pdfbase);?>"> 
</form>
<form name="Fview" method="get" action="<?php echo $NM_pdfurl . "/" . $NM_pdfbase;?>" target="_blank" style="display: none"> 
</form>
<table style="border-collapse: collapse; border-width: 0; height: 98%; width: 100%"><tr><td style="padding: 0; text-align: center; vertical-align: middle">
 <table class="scExportTable" align="center">
  <tr>
   <td class="scExportTitle" style="height: 25px">PDF</td>
  </tr>
  <tr>
   <td class="scExportLine" style="width: 100%">
    <table style="border-collapse: collapse; border-width: 0; width: 100%"><tr><td style="padding: 0; text-align: center" colspan="2">
     <table class="scExportBar" style="width: 100%" align="center">
      <tr>
       <td class="scExportBarDone" style="width: 0; display: none" id="idPbarDone"></td>
       <td class="scExportBarRest" style="width: 100%" id="idPbarRest"></td>
      </tr>
     </table>
    </td></tr><tr><td class="scExportLineFont" style="padding: 3px 0 0 0" id="idMessage">
    &nbsp;
    </td><td class="scExportLineFont" style="text-align:right; padding: 3px 0 0 0">
     <?php echo nmButtonOutput($this->arr_buttons, "bexportview", "document.Fview.submit()", "document.Fview.submit()", "idBtnView", "", "", "", "", "", "", $this->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bdownload", "document.Fdown.submit()", "document.Fdown.submit()", "idBtnDown", "", "", "", "", "", "", $this->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bvoltar", "document.F0.submit()", "document.F0.submit()", "idBtnBack", "", "", "", "", "", "", $this->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
 ?>
    </td></tr></table>
<?php
if (!isset($aParams['nmgp_opcao']) || 'pdf_res' != $aParams['nmgp_opcao'])
{
?>
<iframe name="nmIfrPdf" height="1px" width="1px" frameborder="0" scrolling="no" allowtransparency="true" src="grid_public_v_dte.php?nmgp_opcao=pdf&script_case_init=<?php echo $aParams['script_case_init'] ?>&script_case_session=<?php echo session_id() ?>&pbfile=<?php echo $aParams['pbfile'] ?>&pdf_base=<?php echo str_replace("+", "_NMPLUS_",$NM_pdfbase) ?>&pdf_url=<?php echo $NM_pdfurl ?>&sc_apbgcol=<?php echo urlencode($aParams['sc_apbgcol']) ?>&nmgp_tipo_pdf=<?php echo $aParams['sc_tp_pdf'] ?>&nmgp_parms_pdf=<?php echo $parms_pdf ?>&nmgp_graf_pdf=<?php echo $graf_pdf ?>"></iframe>
<?php
}
else
{
?>
<iframe name="nmIfrPdf" height="1px" width="1px" frameborder="0" scrolling="no" allowtransparency="true" src="grid_public_v_dte.php?nmgp_opcao=pdf_res&script_case_init=<?php echo $aParams['script_case_init'] ?>&script_case_session=<?php echo session_id() ?>&pbfile=<?php echo $aParams['pbfile'] ?>&pdf_base=<?php echo str_replace("+", "_NMPLUS_",$NM_pdfbase) ?>&pdf_url=<?php echo $NM_pdfurl ?>&sc_apbgcol=<?php echo urlencode($aParams['sc_apbgcol']) ?>&nmgp_tipo_pdf=<?php echo $aParams['sc_tp_pdf'] ?>&nmgp_parms_pdf=<?php echo $parms_pdf ?>&nmgp_graf_pdf=<?php echo $graf_pdf ?>"></iframe>
<?php
}
?>
   </td>
  </tr>
 </table>
</td></tr></table>
</body>
</html>
<?php
}

}

?>
