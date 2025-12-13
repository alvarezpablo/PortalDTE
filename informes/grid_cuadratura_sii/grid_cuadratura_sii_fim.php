<?php
   include_once('grid_cuadratura_sii_session.php');
   if (!function_exists("NM_is_utf8"))
   {
       include_once("grid_cuadratura_sii_nmutf8.php");
   }
   session_start();

//----- 
   if (!empty($_POST))
   {
       foreach ($_POST as $nmgp_var => $nmgp_val)
       {
            $$nmgp_var = $nmgp_val;
       }
   }
   if (!empty($_GET))
   {
       foreach ($_GET as $nmgp_var => $nmgp_val)
       {
            $$nmgp_var = $nmgp_val;
       }
   }
   if (isset($_SESSION['session_sec_aplicacao']["WebDTE_____grid_cuadratura_sii"]))
   {
      unset($_SESSION['session_sec_aplicacao']["WebDTE_____grid_cuadratura_sii"]);
   }

   if (isset($_SESSION['session_sec_aplicacao']) && empty($_SESSION['session_sec_aplicacao']))
   {
      unset($_SESSION['session_sec_aplicacao']);
      unset($_SESSION['session_sec_usuario']);
   }
   $fecha_janela = false;
   if (isset($_SESSION['sc_session'][$script_case_init]['grid_cuadratura_sii']['sc_outra_jan']) && $_SESSION['sc_session'][$script_case_init]['grid_cuadratura_sii']['sc_outra_jan'])
   {
       $fecha_janela = true;
   }
   if ((isset($script_case_init) && isset($_SESSION['sc_session'][$script_case_init]['grid_cuadratura_sii']['opc_psq']) && $_SESSION['sc_session'][$script_case_init]['grid_cuadratura_sii']['opc_psq']) || $fecha_janela)
   {
       if (isset($script_case_init) && isset($_SESSION['sc_session'][$script_case_init]['grid_cuadratura_sii']['sc_modal']) && $_SESSION['sc_session'][$script_case_init]['grid_cuadratura_sii']['sc_modal'])
       {
           unset($_SESSION['sc_session'][$script_case_init]['grid_cuadratura_sii']['sc_modal']);
           $saida_final = "self.parent.tb_remove()";
       }
       else
       {
           $saida_final = "parent.window.close()";
       }
       nm_limpa_arr_session();
?>
<HTML>
<HEAD>
 <TITLE>grid_cuadratura_sii</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
</HEAD>
<BODY>
<SCRIPT LANGUAGE="Javascript">
 <?php echo $saida_final; ?>;
</SCRIPT>
</BODY>
</HTML>
<?php
   }
   elseif (!isset($_SESSION['scriptcase']['sc_url_saida'][$script_case_init]) || empty($_SESSION['scriptcase']['sc_url_saida'][$script_case_init]))
   {
           nm_limpa_arr_session();
?>
<HTML>
<HEAD>
 <TITLE>grid_cuadratura_sii</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
</HEAD>
<BODY>
<SCRIPT LANGUAGE="Javascript">
  history.back();
</SCRIPT>
</BODY>
</HTML>
<?php
   }
   else
   {
       nm_limpa_arr_session();
?>
<HTML>
<HEAD>
 <TITLE>grid_cuadratura_sii</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
</HEAD>
<BODY>
<form name="fsai" method="post" action="<?php echo $_SESSION['scriptcase']['sc_url_saida'][$script_case_init]; ?>">
<input type=hidden name="script_case_init" value="<?php  echo NM_encode_input($script_case_init); ?>"> 
<input type=hidden name="script_case_session" value="<?php  echo NM_encode_input(session_id()); ?>"> 
</form>
<SCRIPT LANGUAGE="Javascript">
   nm_ver_saida = "<?php echo $_SESSION['scriptcase']['sc_url_saida'][$script_case_init]; ?>";
   nm_ver_saida = nm_ver_saida.toLowerCase();
   if (nm_ver_saida.substr(0, 4) != ".php" && (nm_ver_saida.substr(0, 7) == "http://" || nm_ver_saida.substr(0, 8) == "https://" || nm_ver_saida.substr(0, 3) == "../")) 
   { 
       window.location = ("<?php echo $_SESSION['scriptcase']['sc_url_saida'][$script_case_init]; ?>"); 
   } 
   else 
   { 
       document.fsai.submit();
   } 
</SCRIPT>
</BODY>
</HTML>
<?php
   }
   function nm_limpa_arr_session()
   {
      global $script_case_init;
      $achou = false;
      if (!isset($_SESSION['sc_session'][$script_case_init]) || !isset($script_case_init))
      {
          return;
      }
      foreach ($_SESSION['sc_session'][$script_case_init] as $nome_apl => $resto)
      {
          if ($nome_apl == 'grid_cuadratura_sii' || $achou)
          {
              unset($_SESSION['sc_session'][$script_case_init][$nome_apl]);
              $achou = true;
          }
      }
      if (empty($_SESSION['sc_session'][$script_case_init]))
      {
          unset($_SESSION['sc_session'][$script_case_init]);
      }
   }
?>
