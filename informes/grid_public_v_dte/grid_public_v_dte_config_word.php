<?php
/**
 * $Id: nm_gp_config_word.php,v 1.2 2012-01-27 13:02:59 sergio Exp $
 */
    include_once('grid_public_v_dte_session.php');
    session_start();
    $SC_apl_proc = "grid_public_v_dte";
    $language   = (isset($_GET['language']))   ? $_GET['language'] : "port";

    if (!function_exists("NM_is_utf8"))
    {
        include_once($SC_apl_proc . "_nmutf8.php");
    }

    $tradutor = array();
    if (isset($_SESSION['scriptcase']['sc_idioma_prt']))
    {
        $tradutor = $_SESSION['scriptcase']['sc_idioma_prt'];
    }
    if (!isset($tradutor[$language]))
    {
        foreach ($tradutor as $language => $resto)
        {
            break;
        }
    }
    if (!isset($tradutor[$language]))
    {
		exit;
    }

?>
<html<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<head>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html']; ?>" />
 <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $_SESSION['scriptcase']['css_popup'] ?>" />
 <link rel="stylesheet" type="text/css" href="../_lib/buttons/<?php echo $_SESSION['scriptcase']['css_btn_popup'] ?>" />
</head>
<body class="scGridPage" style="margin: 0px; overflow-x: hidden">

<script language="javascript" type="text/javascript" src="<?php echo $_SESSION['sc_session']['path_third'] ?>/jquery/js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $_SESSION['sc_session']['path_third'] ?>/tigra_color_picker/picker.js"></script>

<form name="config_prt" method="post">
<?php
if ($_SESSION['scriptcase']['reg_conf']['html_dir'] == " DIR='RTL'")
{
    echo "<table class=\"scGridBorder\" id=\"main_table\" style=\"position: relative; top: 20px; right: 20px\">";
}
else
{
    echo "<table class=\"scGridBorder\" id=\"main_table\" style=\"position: relative; top: 20px; left: 20px\">";
}
?>
<tr>
 <td class="scGridTabelaTd">
  <table class="scGridTabela">
   <tr class="scGridLabelVert">
    <td align="middle" nowrap>
     <b><?php echo $tradutor[$language]['titulo']; ?></b>
    </td>
   </tr>

 <tr><td>
 <table style="border-collapse: collapse; border-width: 0px">
 <tr class="scGridFieldOddVert">
   <td  align="left">
       <?php echo $tradutor[$language]['cor']; ?>
   </td>
   <td align="left">
     <input type=radio name="cor" value="pb" checked><?php echo $tradutor[$language]['pb']; ?>
   </td>
</tr>
 <tr class="scGridFieldOddVert">
   <td>&nbsp;</td>
   <td align="left">
     <input type=radio name="cor" value="co"><?php echo $tradutor[$language]['color']; ?>
   </td>
</tr>
 <tr class="scGridFieldOddVert">
   <td align="center" colspan=2>&nbsp;</td>
 </tr>
</table></td></tr>
 <tr class="scGridToolbar">
   <td align="center" colspan=2>
<?php
echo  $_SESSION['scriptcase']['bg_btn_popup']['bok'] . "\r\n";
echo  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\r\n";
echo  $_SESSION['scriptcase']['bg_btn_popup']['btbremove'] . "\r\n";

?>
   </td>
 </tr>
</table>
 </td>
 </tr>
</table>

</form>


<script language="javascript">
  var bFixed = false;
  $(function() {
    ajusta_window();
  });
  function ajusta_window()
  {
    var mt = $(document.getElementById("main_table"));
    if (0 == mt.width() || 0 == mt.height())
    {
      setTimeout("ajusta_window()", 50);
      return;
    }
    else if(!bFixed)
    {
      bFixed = true;
      if (navigator.userAgent.indexOf("Chrome/") > 0)
      {
        self.parent.tb_resize(mt.height() + 40, mt.width() + 80);
        setTimeout("ajusta_window()", 50);
        return;
      }
    }
    self.parent.tb_resize(mt.height() + 40, mt.width() + 40);
  }

  function processa()
  {
     self.parent.tb_remove();
     cor = (document.config_prt.cor[0].checked) ? "pb" : "co";
     parent.nm_gp_word_conf(cor);return false;
  }
</script>
</body>
</html>