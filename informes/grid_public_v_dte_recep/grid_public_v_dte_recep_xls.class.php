<?php

class grid_public_v_dte_recep_xls
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;
   var $nm_data;
   var $xls_dados;
   var $xls_workbook;
   var $xls_col;
   var $xls_row;
   var $sc_proc_grid; 
   var $NM_cmp_hidden = array();
   var $arquivo;
   var $tit_doc;
   //---- 
   function grid_public_v_dte_recep_xls()
   {
   }

   //---- 
   function monta_xls()
   {
      $this->inicializa_vars();
      $this->grava_arquivo();
      $this->monta_html();
   }

   //----- 
   function inicializa_vars()
   {
      global $nm_lang;
      $this->xls_row = 1;
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_public_v_dte_recep.php"; 
      set_include_path(get_include_path() . PATH_SEPARATOR . $this->Ini->path_third . '/phpexcel/');
      require_once $this->Ini->path_third . '/phpexcel/PHPExcel.php';
      require_once $this->Ini->path_third . '/phpexcel/PHPExcel/IOFactory.php';
      $this->xls_col    = 0;
      $this->nm_data    = new nm_data("es");
      $this->arquivo    = "sc_xls";
      $this->arquivo   .= "_" . date("YmdHis") . "_" . rand(0, 1000);
      $this->arquivo   .= "_grid_public_v_dte_recep";
      $this->arquivo   .= ".xls";
      $this->tit_doc    = "grid_public_v_dte_recep.xls";
      $this->xls_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo;
      $this->xls_dados = new PHPExcel();
      $this->xls_dados->setActiveSheetIndex(0);
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
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte_recep']['field_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte_recep']['field_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte_recep']['field_display'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['usr_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['usr_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['usr_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['php_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['php_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['php_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']))
      { 
          $this->tipo_docu = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']['tipo_docu']; 
          $tmp_pos = strpos($this->tipo_docu, "##@@");
          if ($tmp_pos !== false)
          {
              $this->tipo_docu = substr($this->tipo_docu, 0, $tmp_pos);
          }
          $this->fact_ref = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']['fact_ref']; 
          $tmp_pos = strpos($this->fact_ref, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fact_ref = substr($this->fact_ref, 0, $tmp_pos);
          }
          $this->rut_emite = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']['rut_emite']; 
          $tmp_pos = strpos($this->rut_emite, "##@@");
          if ($tmp_pos !== false)
          {
              $this->rut_emite = substr($this->rut_emite, 0, $tmp_pos);
          }
          $this->fec_emision = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']['fec_emision']; 
          $tmp_pos = strpos($this->fec_emision, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fec_emision = substr($this->fec_emision, 0, $tmp_pos);
          }
          $this->fec_emision_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']['fec_emision_input_2']; 
          $this->fec_recep = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']['fec_recep']; 
          $tmp_pos = strpos($this->fec_recep, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fec_recep = substr($this->fec_recep, 0, $tmp_pos);
          }
          $this->fec_recep_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['campos_busca']['fec_recep_input_2']; 
      } 
      $this->nm_field_dinamico = array();
      $this->nm_order_dinamico = array();
      $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['where_orig'];
      $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['where_pesq'];
      $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['where_pesq_filtro'];
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['xls_name']))
      {
          $this->arquivo = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['xls_name'];
          $this->tit_doc = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['xls_name'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['xls_name']);
          $this->xls_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo;
      }
      $nmgp_select = "SELECT tipo_docu, fact_ref, fec_emision, fec_recep, rut_emite, nom_emite, mntneto_dte, mnt_exen_dte, iva_dte, mont_tot_dte, xml_respuesta, xml_recibo_mercaderia, xml_est_res_rev from " . $this->Ini->nm_tabela; 
      $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['where_pesq'];
      $nmgp_order_by = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['order_grid'];
      $nmgp_select .= $nmgp_order_by; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select;
      $rs = $this->Db->Execute($nmgp_select);
      if ($rs === false && !$rs->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1)
      {
         $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
         exit;
      }

      foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['field_order'] as $Cada_col)
      { 
          $SC_Label = (isset($this->New_label['tipo_docu'])) ? $this->New_label['tipo_docu'] : "Tipo DTE"; 
          if ($Cada_col == "tipo_docu" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['fact_ref'])) ? $this->New_label['fact_ref'] : "Folio"; 
          if ($Cada_col == "fact_ref" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['fec_emision'])) ? $this->New_label['fec_emision'] : "Fec. Emision"; 
          if ($Cada_col == "fec_emision" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['fec_recep'])) ? $this->New_label['fec_recep'] : "Fec. Recep"; 
          if ($Cada_col == "fec_recep" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['rut_emite'])) ? $this->New_label['rut_emite'] : "Rut Emisor"; 
          if ($Cada_col == "rut_emite" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['nom_emite'])) ? $this->New_label['nom_emite'] : "Razón Social"; 
          if ($Cada_col == "nom_emite" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['mntneto_dte'])) ? $this->New_label['mntneto_dte'] : "Neto"; 
          if ($Cada_col == "mntneto_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['mnt_exen_dte'])) ? $this->New_label['mnt_exen_dte'] : "Exento"; 
          if ($Cada_col == "mnt_exen_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['iva_dte'])) ? $this->New_label['iva_dte'] : "IVA"; 
          if ($Cada_col == "iva_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['mont_tot_dte'])) ? $this->New_label['mont_tot_dte'] : "Total"; 
          if ($Cada_col == "mont_tot_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['pdf'])) ? $this->New_label['pdf'] : "PDF"; 
          if ($Cada_col == "pdf" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['acuse_recibo'])) ? $this->New_label['acuse_recibo'] : "Acuse"; 
          if ($Cada_col == "acuse_recibo" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['rec_mercaderia'])) ? $this->New_label['rec_mercaderia'] : "RM"; 
          if ($Cada_col == "rec_mercaderia" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['res_rev'])) ? $this->New_label['res_rev'] : "AR.Com."; 
          if ($Cada_col == "res_rev" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
      } 
      while (!$rs->EOF)
      {
         $this->xls_col = 0;
         $this->xls_row++;
         $this->tipo_docu = $rs->fields[0] ;  
         $this->tipo_docu = (string)$this->tipo_docu;
         $this->fact_ref = $rs->fields[1] ;  
         $this->fact_ref = (string)$this->fact_ref;
         $this->fec_emision = $rs->fields[2] ;  
         $this->fec_recep = $rs->fields[3] ;  
         $this->rut_emite = $rs->fields[4] ;  
         $this->nom_emite = $rs->fields[5] ;  
         $this->mntneto_dte = $rs->fields[6] ;  
         $this->mntneto_dte = (string)$this->mntneto_dte;
         $this->mnt_exen_dte = $rs->fields[7] ;  
         $this->mnt_exen_dte = (string)$this->mnt_exen_dte;
         $this->iva_dte = $rs->fields[8] ;  
         $this->iva_dte = (string)$this->iva_dte;
         $this->mont_tot_dte = $rs->fields[9] ;  
         $this->mont_tot_dte = (string)$this->mont_tot_dte;
         $this->xml_respuesta = $rs->fields[10] ;  
         $this->xml_recibo_mercaderia = $rs->fields[11] ;  
         $this->xml_est_res_rev = $rs->fields[12] ;  
         //----- lookup - tipo_docu
         $this->look_tipo_docu = $this->tipo_docu; 
         $this->Lookup->lookup_tipo_docu($this->look_tipo_docu, $this->tipo_docu) ; 
         $this->look_tipo_docu = ($this->look_tipo_docu == "&nbsp;") ? "" : $this->look_tipo_docu; 
         $this->sc_proc_grid = true; 
         $_SESSION['scriptcase']['grid_public_v_dte_recep']['contr_erro'] = 'on';
if (!isset($_SESSION['v_codi_empr'])) {$_SESSION['v_codi_empr'] = "";}
if (!isset($this->sc_temp_v_codi_empr)) {$this->sc_temp_v_codi_empr = (isset($_SESSION['v_codi_empr'])) ? $_SESSION['v_codi_empr'] : "";}
 $this->Ini->link_pdf_apl = "/dte/view_pdf_compras.php?c=$this->sc_temp_v_codi_empr&f=$this->fact_ref &t=$this->tipo_docu &r=$this->rut_emite ";
$this->Ini->link_pdf_apl = str_replace("'", "?&?'", $this->Ini->link_pdf_apl);
$this->Ini->link_pdf_parms = "";
$this->Ini->link_pdf_parms = str_replace("'", "?&?'", $this->Ini->link_pdf_parms);
$this->Ini->link_pdf_hint = "Ver PDF";
$this->Ini->link_pdf_hint = str_replace("'", "?&?'", $this->Ini->link_pdf_hint);
$this->Ini->link_pdf_target = "_blank";
$this->Ini->link_pdf_pos = "";
$this->Ini->link_pdf_alt = "440";
$this->Ini->link_pdf_larg = "630";
;

if ($this->xml_respuesta !=''){
	$this->Ini->link_acuse_recibo_apl = "/dte/view_xml_compras.php?c=$this->sc_temp_v_codi_empr&f=$this->fact_ref &t=$this->tipo_docu &r=$this->rut_emite &x=AR";
$this->Ini->link_acuse_recibo_apl = str_replace("'", "?&?'", $this->Ini->link_acuse_recibo_apl);
$this->Ini->link_acuse_recibo_parms = "";
$this->Ini->link_acuse_recibo_parms = str_replace("'", "?&?'", $this->Ini->link_acuse_recibo_parms);
$this->Ini->link_acuse_recibo_hint = "Ver XML";
$this->Ini->link_acuse_recibo_hint = str_replace("'", "?&?'", $this->Ini->link_acuse_recibo_hint);
$this->Ini->link_acuse_recibo_target = "_blank";
$this->Ini->link_acuse_recibo_pos = "";
$this->Ini->link_acuse_recibo_alt = "440";
$this->Ini->link_acuse_recibo_larg = "630";
;
	$this->acuse_recibo ="<img src='../_lib/img/sys__NM__success.png'>";	
}	
else
	$this->acuse_recibo ="";

if ($this->xml_recibo_mercaderia !=''){
	$this->Ini->link_rec_mercaderia_apl = "/dte/view_xml_compras.php?c=$this->sc_temp_v_codi_empr&f=$this->fact_ref &t=$this->tipo_docu &r=$this->rut_emite &x=RM";
$this->Ini->link_rec_mercaderia_apl = str_replace("'", "?&?'", $this->Ini->link_rec_mercaderia_apl);
$this->Ini->link_rec_mercaderia_parms = "";
$this->Ini->link_rec_mercaderia_parms = str_replace("'", "?&?'", $this->Ini->link_rec_mercaderia_parms);
$this->Ini->link_rec_mercaderia_hint = "Ver XML";
$this->Ini->link_rec_mercaderia_hint = str_replace("'", "?&?'", $this->Ini->link_rec_mercaderia_hint);
$this->Ini->link_rec_mercaderia_target = "_blank";
$this->Ini->link_rec_mercaderia_pos = "";
$this->Ini->link_rec_mercaderia_alt = "440";
$this->Ini->link_rec_mercaderia_larg = "630";
;	
	$this->rec_mercaderia ="<img src='../_lib/img/sys__NM__success.png'>";	
}	
else 
	$this->rec_mercaderia ="";	

if ($this->xml_est_res_rev !=''){
	$this->Ini->link_res_rev_apl = "/dte/view_xml_compras.php?c=$this->sc_temp_v_codi_empr&f=$this->fact_ref &t=$this->tipo_docu &r=$this->rut_emite &x=ARC";
$this->Ini->link_res_rev_apl = str_replace("'", "?&?'", $this->Ini->link_res_rev_apl);
$this->Ini->link_res_rev_parms = "";
$this->Ini->link_res_rev_parms = str_replace("'", "?&?'", $this->Ini->link_res_rev_parms);
$this->Ini->link_res_rev_hint = "Ver XML";
$this->Ini->link_res_rev_hint = str_replace("'", "?&?'", $this->Ini->link_res_rev_hint);
$this->Ini->link_res_rev_target = "_blank";
$this->Ini->link_res_rev_pos = "";
$this->Ini->link_res_rev_alt = "440";
$this->Ini->link_res_rev_larg = "630";
;		
	$this->res_rev ="<img src='../_lib/img/sys__NM__success.png'>";	
}
else
	$this->res_rev ="";
if (isset($this->sc_temp_v_codi_empr)) {$_SESSION['v_codi_empr'] = $this->sc_temp_v_codi_empr;}
$_SESSION['scriptcase']['grid_public_v_dte_recep']['contr_erro'] = 'off'; 
         foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['field_order'] as $Cada_col)
         { 
            if (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off")
            { 
                $NM_func_exp = "NM_export_" . $Cada_col;
                $this->$NM_func_exp();
            } 
         } 
         $rs->MoveNext();
      }
      $rs->Close();
      $objWriter = PHPExcel_IOFactory::createWriter($this->xls_dados, 'Excel5');
      $objWriter->save($this->xls_f);
   }
   //----- tipo_docu
   function NM_export_tipo_docu()
   {
         if (!NM_is_utf8($this->look_tipo_docu))
         {
             $this->look_tipo_docu = mb_convert_encoding($this->look_tipo_docu, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->look_tipo_docu))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->look_tipo_docu);
         $this->xls_col++;
   }
   //----- fact_ref
   function NM_export_fact_ref()
   {
         if (!NM_is_utf8($this->fact_ref))
         {
             $this->fact_ref = mb_convert_encoding($this->fact_ref, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->fact_ref))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->fact_ref);
         $this->xls_col++;
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
         if (!NM_is_utf8($this->fec_emision))
         {
             $this->fec_emision = mb_convert_encoding($this->fec_emision, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->fec_emision);
         $this->xls_col++;
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
         if (!NM_is_utf8($this->fec_recep))
         {
             $this->fec_recep = mb_convert_encoding($this->fec_recep, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->fec_recep);
         $this->xls_col++;
   }
   //----- rut_emite
   function NM_export_rut_emite()
   {
         if (!NM_is_utf8($this->rut_emite))
         {
             $this->rut_emite = mb_convert_encoding($this->rut_emite, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->rut_emite);
         $this->xls_col++;
   }
   //----- nom_emite
   function NM_export_nom_emite()
   {
         if (!NM_is_utf8($this->nom_emite))
         {
             $this->nom_emite = mb_convert_encoding($this->nom_emite, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->nom_emite);
         $this->xls_col++;
   }
   //----- mntneto_dte
   function NM_export_mntneto_dte()
   {
         if (!NM_is_utf8($this->mntneto_dte))
         {
             $this->mntneto_dte = mb_convert_encoding($this->mntneto_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->mntneto_dte))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->mntneto_dte);
         $this->xls_col++;
   }
   //----- mnt_exen_dte
   function NM_export_mnt_exen_dte()
   {
         if (!NM_is_utf8($this->mnt_exen_dte))
         {
             $this->mnt_exen_dte = mb_convert_encoding($this->mnt_exen_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->mnt_exen_dte))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->mnt_exen_dte);
         $this->xls_col++;
   }
   //----- iva_dte
   function NM_export_iva_dte()
   {
         if (!NM_is_utf8($this->iva_dte))
         {
             $this->iva_dte = mb_convert_encoding($this->iva_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->iva_dte))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->iva_dte);
         $this->xls_col++;
   }
   //----- mont_tot_dte
   function NM_export_mont_tot_dte()
   {
         if (!NM_is_utf8($this->mont_tot_dte))
         {
             $this->mont_tot_dte = mb_convert_encoding($this->mont_tot_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->mont_tot_dte))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->mont_tot_dte);
         $this->xls_col++;
   }
   //----- pdf
   function NM_export_pdf()
   {
         if (!NM_is_utf8($this->pdf))
         {
             $this->pdf = mb_convert_encoding($this->pdf, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->pdf);
         $this->xls_col++;
   }
   //----- acuse_recibo
   function NM_export_acuse_recibo()
   {
         if (!NM_is_utf8($this->acuse_recibo))
         {
             $this->acuse_recibo = mb_convert_encoding($this->acuse_recibo, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->acuse_recibo);
         $this->xls_col++;
   }
   //----- rec_mercaderia
   function NM_export_rec_mercaderia()
   {
         if (!NM_is_utf8($this->rec_mercaderia))
         {
             $this->rec_mercaderia = mb_convert_encoding($this->rec_mercaderia, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->rec_mercaderia);
         $this->xls_col++;
   }
   //----- res_rev
   function NM_export_res_rev()
   {
         if (!NM_is_utf8($this->res_rev))
         {
             $this->res_rev = mb_convert_encoding($this->res_rev, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->res_rev);
         $this->xls_col++;
   }

   function calc_cell($col)
   {
       $arr_alfa = array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
       $val_ret = "";
       $result = $col + 1;
       while ($result > 26)
       {
           $cel      = $result % 26;
           $result   = $result / 26;
           if ($cel == 0)
           {
               $cel    = 26;
               $result--;
           }
           $val_ret = $arr_alfa[$cel] . $val_ret;
       }
       $val_ret = $arr_alfa[$result] . $val_ret;
       return $val_ret;
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
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['xls_file']);
      if (is_file($this->xls_f))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte_recep']['xls_file'] = $this->xls_f;
      }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>Consulta DTE Recibidos :: Excel</TITLE>
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
   <td class="scExportTitle" style="height: 25px">XLS</td>
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
<form name="Fview" method="get" action="<?php echo $this->Ini->path_imag_temp . "/" . $this->arquivo ?>" target="_blank" style="display: none"> 
</form>
<form name="Fdown" method="get" action="grid_public_v_dte_recep_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="nm_tit_doc" value="<?php echo NM_encode_input($this->tit_doc); ?>"> 
<input type="hidden" name="nm_name_doc" value="<?php echo NM_encode_input($this->Ini->path_imag_temp . "/" . $this->arquivo) ?>"> 
</form>
<FORM name="F0" method=post action="grid_public_v_dte_recep.php"> 
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
