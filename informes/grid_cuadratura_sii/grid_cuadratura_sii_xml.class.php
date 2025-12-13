<?php

class grid_cuadratura_sii_xml
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;
   var $nm_data;

   var $arquivo;
   var $arquivo_view;
   var $tit_doc;
   var $sc_proc_grid; 
   var $NM_cmp_hidden = array();

   //---- 
   function grid_cuadratura_sii_xml()
   {
      $this->nm_data   = new nm_data("es");
   }

   //---- 
   function monta_xml()
   {
      $this->inicializa_vars();
      $this->grava_arquivo();
      $this->monta_html();
   }

   //----- 
   function inicializa_vars()
   {
      global $nm_lang;
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_cuadratura_sii.php"; 
      $this->nm_data    = new nm_data("es");
      $this->arquivo      = "sc_xml";
      $this->arquivo     .= "_" . date("YmdHis") . "_" . rand(0, 1000);
      $this->arquivo     .= "_grid_cuadratura_sii";
      $this->arquivo_view = $this->arquivo . "_view.xml";
      $this->arquivo     .= ".xml";
      $this->tit_doc      = "grid_cuadratura_sii.xml";
      $this->Grava_view   = false;
      if (strtolower($_SESSION['scriptcase']['charset']) != strtolower($_SESSION['scriptcase']['charset_html']))
      {
          $this->Grava_view = true;
      }
   }

   //----- 
   function grava_arquivo()
   {
      global $nm_lang;
      global
             $nm_nada, $nm_lang;

      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->sc_proc_grid = false; 
      $nm_raiz_img  = ""; 
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_cuadratura_sii']['field_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_cuadratura_sii']['field_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_cuadratura_sii']['field_display'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['usr_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['usr_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['usr_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['php_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['php_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['php_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']))
      { 
          $this->tipo_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['tipo_dte']; 
          $tmp_pos = strpos($this->tipo_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->tipo_dte = substr($this->tipo_dte, 0, $tmp_pos);
          }
          $this->folio_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['folio_dte']; 
          $tmp_pos = strpos($this->folio_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->folio_dte = substr($this->folio_dte, 0, $tmp_pos);
          }
          $this->rut_emisor = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['rut_emisor']; 
          $tmp_pos = strpos($this->rut_emisor, "##@@");
          if ($tmp_pos !== false)
          {
              $this->rut_emisor = substr($this->rut_emisor, 0, $tmp_pos);
          }
          $this->fec_emision = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision']; 
          $tmp_pos = strpos($this->fec_emision, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fec_emision = substr($this->fec_emision, 0, $tmp_pos);
          }
          $this->fec_emision_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2']; 
          $this->fec_recep = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep']; 
          $tmp_pos = strpos($this->fec_recep, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fec_recep = substr($this->fec_recep, 0, $tmp_pos);
          }
          $this->fec_recep_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2']; 
      } 
      $this->nm_field_dinamico = array();
      $this->nm_order_dinamico = array();
      $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_orig'];
      $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq'];
      $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq_filtro'];
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xml_name']))
      {
          $this->arquivo = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xml_name'];
          $this->tit_doc = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xml_name'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xml_name']);
      }
      if (!$this->Grava_view)
      {
          $this->arquivo_view = $this->arquivo;
      }
      $nmgp_select = "SELECT tipo_dte, folio_dte, rut_emisor, razon_social, fec_emision, fec_recep, monto_total, fecha_hora_recepcion from " . $this->Ini->nm_tabela; 
      $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq'];
      $nmgp_order_by = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['order_grid'];
      $nmgp_select .= $nmgp_order_by; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select;
      $rs = $this->Db->Execute($nmgp_select);
      if ($rs === false && !$rs->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1)
      {
         $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
         exit;
      }

      $xml_charset = $_SESSION['scriptcase']['charset'];
      $xml_f = fopen($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo, "w");
      fwrite($xml_f, "<?xml version=\"1.0\" encoding=\"$xml_charset\" ?>\r\n");
      fwrite($xml_f, "<root>\r\n");
      if ($this->Grava_view)
      {
          $xml_charset_v = $_SESSION['scriptcase']['charset_html'];
          $xml_v         = fopen($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo_view, "w");
          fwrite($xml_v, "<?xml version=\"1.0\" encoding=\"$xml_charset_v\" ?>\r\n");
          fwrite($xml_v, "<root>\r\n");
      }
      while (!$rs->EOF)
      {
         $this->xml_registro = "<grid_cuadratura_sii";
         $this->tipo_dte = $rs->fields[0] ;  
         $this->tipo_dte = (string)$this->tipo_dte;
         $this->folio_dte = $rs->fields[1] ;  
         $this->folio_dte =  str_replace(",", ".", $this->folio_dte);
         $this->folio_dte = (string)$this->folio_dte;
         $this->rut_emisor = $rs->fields[2] ;  
         $this->razon_social = $rs->fields[3] ;  
         $this->fec_emision = $rs->fields[4] ;  
         $this->fec_recep = $rs->fields[5] ;  
         $this->monto_total = $rs->fields[6] ;  
         $this->monto_total =  str_replace(",", ".", $this->monto_total);
         $this->monto_total = (string)$this->monto_total;
         $this->fecha_hora_recepcion = $rs->fields[7] ;  
         //----- lookup - tipo_dte
         $this->look_tipo_dte = $this->tipo_dte; 
         $this->Lookup->lookup_tipo_dte($this->look_tipo_dte, $this->tipo_dte) ; 
         $this->look_tipo_dte = ($this->look_tipo_dte == "&nbsp;") ? "" : $this->look_tipo_dte; 
         $this->sc_proc_grid = true; 
         foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['field_order'] as $Cada_col)
         { 
            if (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off")
            { 
                $NM_func_exp = "NM_export_" . $Cada_col;
                $this->$NM_func_exp();
            } 
         } 
         $this->xml_registro .= " />\r\n";
         fwrite($xml_f, $this->xml_registro);
         if ($this->Grava_view)
         {
            fwrite($xml_v, $this->xml_registro);
         }
         $rs->MoveNext();
      }
      fwrite($xml_f, "</root>");
      fclose($xml_f);
      if ($this->Grava_view)
      {
         fwrite($xml_v, "</root>");
         fclose($xml_v);
      }

      $rs->Close();
   }
   //----- tipo_dte
   function NM_export_tipo_dte()
   {
         nmgp_Form_Num_Val($this->look_tipo_dte, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->look_tipo_dte))
         {
             $this->look_tipo_dte = mb_convert_encoding($this->look_tipo_dte, "UTF-8");
         }
         $this->xml_registro .= " tipo_dte =\"" . $this->trata_dados($this->look_tipo_dte) . "\"";
   }
   //----- folio_dte
   function NM_export_folio_dte()
   {
         nmgp_Form_Num_Val($this->folio_dte, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "0", "S", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->folio_dte))
         {
             $this->folio_dte = mb_convert_encoding($this->folio_dte, "UTF-8");
         }
         $this->xml_registro .= " folio_dte =\"" . $this->trata_dados($this->folio_dte) . "\"";
   }
   //----- rut_emisor
   function NM_export_rut_emisor()
   {
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->rut_emisor))
         {
             $this->rut_emisor = mb_convert_encoding($this->rut_emisor, "UTF-8");
         }
         $this->xml_registro .= " rut_emisor =\"" . $this->trata_dados($this->rut_emisor) . "\"";
   }
   //----- razon_social
   function NM_export_razon_social()
   {
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->razon_social))
         {
             $this->razon_social = mb_convert_encoding($this->razon_social, "UTF-8");
         }
         $this->xml_registro .= " razon_social =\"" . $this->trata_dados($this->razon_social) . "\"";
   }
   //----- fec_emision
   function NM_export_fec_emision()
   {
         $conteudo_x =  $this->fec_emision;
         nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
         if (is_numeric($conteudo_x) && $conteudo_x > 0) 
         { 
             $this->nm_data->SetaData($this->fec_emision, "YYYY-MM-DD");
             $this->fec_emision = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
         } 
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->fec_emision))
         {
             $this->fec_emision = mb_convert_encoding($this->fec_emision, "UTF-8");
         }
         $this->xml_registro .= " fec_emision =\"" . $this->trata_dados($this->fec_emision) . "\"";
   }
   //----- fec_recep
   function NM_export_fec_recep()
   {
         $conteudo_x =  $this->fec_recep;
         nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
         if (is_numeric($conteudo_x) && $conteudo_x > 0) 
         { 
             $this->nm_data->SetaData($this->fec_recep, "YYYY-MM-DD");
             $this->fec_recep = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
         } 
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->fec_recep))
         {
             $this->fec_recep = mb_convert_encoding($this->fec_recep, "UTF-8");
         }
         $this->xml_registro .= " fec_recep =\"" . $this->trata_dados($this->fec_recep) . "\"";
   }
   //----- monto_total
   function NM_export_monto_total()
   {
         nmgp_Form_Num_Val($this->monto_total, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "0", "S", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->monto_total))
         {
             $this->monto_total = mb_convert_encoding($this->monto_total, "UTF-8");
         }
         $this->xml_registro .= " monto_total =\"" . $this->trata_dados($this->monto_total) . "\"";
   }
   //----- fecha_hora_recepcion
   function NM_export_fecha_hora_recepcion()
   {
         if (substr($this->fecha_hora_recepcion, 10, 1) == "-") 
         { 
             $this->fecha_hora_recepcion = substr($this->fecha_hora_recepcion, 0, 10) . " " . substr($this->fecha_hora_recepcion, 11);
         } 
         if (substr($this->fecha_hora_recepcion, 13, 1) == ".") 
         { 
            $this->fecha_hora_recepcion = substr($this->fecha_hora_recepcion, 0, 13) . ":" . substr($this->fecha_hora_recepcion, 14, 2) . ":" . substr($this->fecha_hora_recepcion, 17);
         } 
         $this->nm_data->SetaData($this->fecha_hora_recepcion, "YYYY-MM-DD HH:II:SS");
         $this->fecha_hora_recepcion = $this->nm_data->FormataSaida($this->Nm_date_format("DH", "ddmmaaaa;hhiiss"));
         if ($_SESSION['scriptcase']['charset'] == "UTF-8" && !NM_is_utf8($this->fecha_hora_recepcion))
         {
             $this->fecha_hora_recepcion = mb_convert_encoding($this->fecha_hora_recepcion, "UTF-8");
         }
         $this->xml_registro .= " fecha_hora_recepcion =\"" . $this->trata_dados($this->fecha_hora_recepcion) . "\"";
   }

   //----- 
   function trata_dados($conteudo)
   {
      $str_temp =  $conteudo;
      $str_temp =  str_replace("<br />", "",  $str_temp);
      $str_temp =  str_replace("&", "&amp;",  $str_temp);
      $str_temp =  str_replace("<", "&lt;",   $str_temp);
      $str_temp =  str_replace(">", "&gt;",   $str_temp);
      $str_temp =  str_replace("'", "&apos;", $str_temp);
      $str_temp =  str_replace('"', "&quot;",  $str_temp);
      $str_temp =  str_replace('(', "_",  $str_temp);
      $str_temp =  str_replace(')', "",  $str_temp);
      return ($str_temp);
   }

   function nm_conv_data_db($dt_in, $form_in, $form_out)
   {
       $dt_out = $dt_in;
       if (strtoupper($form_in) == "DB_FORMAT")
       {
           if ($dt_out == "null" || $dt_out == "")
           {
               $dt_out = "";
               return $dt_out;
           }
           $form_in = "AAAA-MM-DD";
       }
       if (strtoupper($form_out) == "DB_FORMAT")
       {
           if (empty($dt_out))
           {
               $dt_out = "null";
               return $dt_out;
           }
           $form_out = "AAAA-MM-DD";
       }
       nm_conv_form_data($dt_out, $form_in, $form_out);
       return $dt_out;
   }
   //---- 
   function monta_html()
   {
      global $nm_url_saida, $nm_lang;
      include($this->Ini->path_btn . $this->Ini->Str_btn_grid);
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xml_file']);
      if (is_file($this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xml_file'] = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo;
      }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>DTE no Recepcionados en OpenDTE :: XML</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
 <META http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT"/>
 <META http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT"/>
 <META http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate"/>
 <META http-equiv="Cache-Control" content="post-check=0, pre-check=0"/>
 <META http-equiv="Pragma" content="no-cache"/>
  <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_all ?>_export.css" /> 
  <link rel="stylesheet" type="text/css" href="../_lib/buttons/<?php echo $this->Ini->Str_btn_css ?>" /> 
</HEAD>
<BODY class="scExportPage">
<?php echo $this->Ini->Ajax_result_set ?>
<table style="border-collapse: collapse; border-width: 0; height: 100%; width: 100%"><tr><td style="padding: 0; text-align: center; vertical-align: middle">
 <table class="scExportTable" align="center">
  <tr>
   <td class="scExportTitle" style="height: 25px">XML</td>
  </tr>
  <tr>
   <td class="scExportLine" style="width: 100%">
    <table style="border-collapse: collapse; border-width: 0; width: 100%"><tr><td class="scExportLineFont" style="padding: 3px 0 0 0" id="idMessage">
    <?php echo $this->Ini->Nm_lang['lang_othr_file_msge'] ?>
    </td><td class="scExportLineFont" style="text-align:right; padding: 3px 0 0 0">
     <?php echo nmButtonOutput($this->arr_buttons, "bexportview", "document.Fview.submit()", "document.Fview.submit()", "idBtnView", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bdownload", "document.Fdown.submit()", "document.Fdown.submit()", "idBtnDown", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bvoltar", "document.F0.submit()", "document.F0.submit()", "idBtnBack", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
 ?>
    </td></tr></table>
   </td>
  </tr>
 </table>
</td></tr></table>
<form name="Fview" method="get" action="<?php echo $this->Ini->path_imag_temp . "/" . $this->arquivo_view ?>" target="_blank" style="display: none"> 
</form>
<form name="Fdown" method="get" action="grid_cuadratura_sii_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="nm_tit_doc" value="<?php echo NM_encode_input($this->tit_doc); ?>"> 
<input type="hidden" name="nm_name_doc" value="<?php echo NM_encode_input($this->Ini->path_imag_temp . "/" . $this->arquivo) ?>"> 
</form>
<FORM name="F0" method=post action="grid_cuadratura_sii.php" style="display: none"> 
<INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<INPUT type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id()); ?>"> 
<INPUT type="hidden" name="nmgp_opcao" value="volta_grid"> 
</FORM> 
</BODY>
</HTML>
<?php
   }

 function Nm_date_format($Type, $Format)
 {
     $Form_base = str_replace("/", "", $Format);
     $Form_base = str_replace("-", "", $Form_base);
     $Form_base = str_replace(":", "", $Form_base);
     $Form_base = str_replace(";", "", $Form_base);
     $Form_base = str_replace(" ", "", $Form_base);
     $Form_base = str_replace("a", "Y", $Form_base);
     $Form_base = str_replace("y", "Y", $Form_base);
     $Form_base = str_replace("h", "H", $Form_base);
     $date_format_show = "";
     if ($Type == "DT" || $Type == "DH")
     {
         $Str_date = str_replace("a", "y", strtolower($_SESSION['scriptcase']['reg_conf']['date_format']));
         $Str_date = str_replace("y", "Y", $Str_date);
         $Str_date = str_replace("h", "H", $Str_date);
         $Lim   = strlen($Str_date);
         $Ult   = "";
         $Arr_D = array();
         for ($I = 0; $I < $Lim; $I++)
         {
              $Char = substr($Str_date, $I, 1);
              if ($Char != $Ult)
              {
                  $Arr_D[] = $Char;
              }
              $Ult = $Char;
         }
         $Prim = true;
         foreach ($Arr_D as $Cada_d)
         {
             if (strpos($Form_base, $Cada_d) !== false)
             {
                 $date_format_show .= (!$Prim) ? $_SESSION['scriptcase']['reg_conf']['date_sep'] : "";
                 $date_format_show .= $Cada_d;
                 $Prim = false;
             }
         }
     }
     if ($Type == "DH" || $Type == "HH")
     {
         if ($Type == "DH")
         {
             $date_format_show .= " ";
         }
         $Str_time = strtolower($_SESSION['scriptcase']['reg_conf']['time_format']);
         $Str_time = str_replace("h", "H", $Str_time);
         $Lim   = strlen($Str_time);
         $Ult   = "";
         $Arr_T = array();
         for ($I = 0; $I < $Lim; $I++)
         {
              $Char = substr($Str_time, $I, 1);
              if ($Char != $Ult)
              {
                  $Arr_T[] = $Char;
              }
              $Ult = $Char;
         }
         $Prim = true;
         foreach ($Arr_T as $Cada_t)
         {
             if (strpos($Form_base, $Cada_t) !== false)
             {
                 $date_format_show .= (!$Prim) ? $_SESSION['scriptcase']['reg_conf']['time_sep'] : "";
                 $date_format_show .= $Cada_t;
                 $Prim = false;
             }
         }
     }
     return $date_format_show;
 }

   function nm_gera_mask(&$nm_campo, $nm_mask)
   { 
      $trab_campo = $nm_campo;
      $trab_mask  = $nm_mask;
      $tam_campo  = strlen($nm_campo);
      $trab_saida = "";
      $mask_num = false;
      for ($x=0; $x < strlen($trab_mask); $x++)
      {
          if (substr($trab_mask, $x, 1) == "#")
          {
              $mask_num = true;
              break;
          }
      }
      if ($mask_num )
      {
          $ver_duas = explode(";", $trab_mask);
          if (isset($ver_duas[1]) && !empty($ver_duas[1]))
          {
              $cont1 = count(explode("#", $ver_duas[0])) - 1;
              $cont2 = count(explode("#", $ver_duas[1])) - 1;
              if ($cont2 >= $tam_campo)
              {
                  $trab_mask = $ver_duas[1];
              }
              else
              {
                  $trab_mask = $ver_duas[0];
              }
          }
          $tam_mask = strlen($trab_mask);
          $xdados = 0;
          for ($x=0; $x < $tam_mask; $x++)
          {
              if (substr($trab_mask, $x, 1) == "#" && $xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_campo, $xdados, 1);
                  $xdados++;
              }
              elseif ($xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_mask, $x, 1);
              }
          }
          if ($xdados < $tam_campo)
          {
              $trab_saida .= substr($trab_campo, $xdados);
          }
          $nm_campo = $trab_saida;
          return;
      }
      for ($ix = strlen($trab_mask); $ix > 0; $ix--)
      {
           $char_mask = substr($trab_mask, $ix - 1, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               $trab_saida = $char_mask . $trab_saida;
           }
           else
           {
               if ($tam_campo != 0)
               {
                   $trab_saida = substr($trab_campo, $tam_campo - 1, 1) . $trab_saida;
                   $tam_campo--;
               }
               else
               {
                   $trab_saida = "0" . $trab_saida;
               }
           }
      }
      if ($tam_campo != 0)
      {
          $trab_saida = substr($trab_campo, 0, $tam_campo) . $trab_saida;
          $trab_mask  = str_repeat("z", $tam_campo) . $trab_mask;
      }
   
      $iz = 0; 
      for ($ix = 0; $ix < strlen($trab_mask); $ix++)
      {
           $char_mask = substr($trab_mask, $ix, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               if ($char_mask == "." || $char_mask == ",")
               {
                   $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
               }
               else
               {
                   $iz++;
               }
           }
           elseif ($char_mask == "x" || substr($trab_saida, $iz, 1) != "0")
           {
               $ix = strlen($trab_mask) + 1;
           }
           else
           {
               $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
           }
      }
      $nm_campo = $trab_saida;
   } 
}

?>
