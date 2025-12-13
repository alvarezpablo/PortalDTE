<?php

class grid_public_v_dte_xls
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
   function grid_public_v_dte_xls()
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
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_public_v_dte.php"; 
      set_include_path(get_include_path() . PATH_SEPARATOR . $this->Ini->path_third . '/phpexcel/');
      require_once $this->Ini->path_third . '/phpexcel/PHPExcel.php';
      require_once $this->Ini->path_third . '/phpexcel/PHPExcel/IOFactory.php';
      $this->xls_col    = 0;
      $this->nm_data    = new nm_data("es");
      $this->arquivo    = "sc_xls";
      $this->arquivo   .= "_" . date("YmdHis") . "_" . rand(0, 1000);
      $this->arquivo   .= "_grid_public_v_dte";
      $this->arquivo   .= ".xls";
      $this->tit_doc    = "grid_public_v_dte.xls";
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
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['field_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['field_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['field_display'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['usr_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['usr_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['usr_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['php_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['php_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['php_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']))
      { 
          $this->tipo_docu = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['tipo_docu']; 
          $tmp_pos = strpos($this->tipo_docu, "##@@");
          if ($tmp_pos !== false)
          {
              $this->tipo_docu = substr($this->tipo_docu, 0, $tmp_pos);
          }
          $this->folio_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['folio_dte']; 
          $tmp_pos = strpos($this->folio_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->folio_dte = substr($this->folio_dte, 0, $tmp_pos);
          }
          $this->fec_emi_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte']; 
          $tmp_pos = strpos($this->fec_emi_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fec_emi_dte = substr($this->fec_emi_dte, 0, $tmp_pos);
          }
          $this->fec_emi_dte_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2']; 
          $this->fech_carg = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg']; 
          $tmp_pos = strpos($this->fech_carg, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fech_carg = substr($this->fech_carg, 0, $tmp_pos);
          }
          $this->fech_carg_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2']; 
          $this->est_xdte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['est_xdte']; 
          $tmp_pos = strpos($this->est_xdte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->est_xdte = substr($this->est_xdte, 0, $tmp_pos);
          }
          $this->rut_rec_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['rut_rec_dte']; 
          $tmp_pos = strpos($this->rut_rec_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->rut_rec_dte = substr($this->rut_rec_dte, 0, $tmp_pos);
          }
      } 
      $this->nm_field_dinamico = array();
      $this->nm_order_dinamico = array();
      $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];
      $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'];
      $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'];
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['xls_name']))
      {
          $this->arquivo = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['xls_name'];
          $this->tit_doc = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['xls_name'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['xls_name']);
          $this->xls_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo;
      }
      $nmgp_select = "SELECT tipo_docu, folio_dte, est_xdte, fec_emi_dte, fech_carg, rut_rec_dte, nom_rec_dte, dir_rec_dte, com_rec_dte, mntneto_dte, mnt_exen_dte, iva_dte, mont_tot_dte, track_id, path_pdf_cedible, msg_xdte, est_rec_doc, est_res_rev, est_recibo_mercaderias from " . $this->Ini->nm_tabela; 
      $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'];
      $nmgp_order_by = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['order_grid'];
      $nmgp_select .= $nmgp_order_by; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select;
      $rs = $this->Db->Execute($nmgp_select);
      if ($rs === false && !$rs->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1)
      {
         $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
         exit;
      }

      foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['field_order'] as $Cada_col)
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
          $SC_Label = (isset($this->New_label['folio_dte'])) ? $this->New_label['folio_dte'] : "Folio Dte"; 
          if ($Cada_col == "folio_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['est_xdte'])) ? $this->New_label['est_xdte'] : "Estado DTE"; 
          if ($Cada_col == "est_xdte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['fec_emi_dte'])) ? $this->New_label['fec_emi_dte'] : "Fecha Emisión"; 
          if ($Cada_col == "fec_emi_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['fech_carg'])) ? $this->New_label['fech_carg'] : "Fecha Carga"; 
          if ($Cada_col == "fech_carg" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['rut_rec_dte'])) ? $this->New_label['rut_rec_dte'] : "Rut Recep."; 
          if ($Cada_col == "rut_rec_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['nom_rec_dte'])) ? $this->New_label['nom_rec_dte'] : "Nombre Recep."; 
          if ($Cada_col == "nom_rec_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['dir_rec_dte'])) ? $this->New_label['dir_rec_dte'] : "Dir."; 
          if ($Cada_col == "dir_rec_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['com_rec_dte'])) ? $this->New_label['com_rec_dte'] : "Comuna"; 
          if ($Cada_col == "com_rec_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['mntneto_dte'])) ? $this->New_label['mntneto_dte'] : "Monto Neto"; 
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
          $SC_Label = (isset($this->New_label['mnt_exen_dte'])) ? $this->New_label['mnt_exen_dte'] : "Monto Exento"; 
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
          $SC_Label = (isset($this->New_label['iva_dte'])) ? $this->New_label['iva_dte'] : "IVA  "; 
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
          $SC_Label = (isset($this->New_label['track_id'])) ? $this->New_label['track_id'] : "Track Id"; 
          if ($Cada_col == "track_id" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
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
          $SC_Label = (isset($this->New_label['cedible'])) ? $this->New_label['cedible'] : "Cedible"; 
          if ($Cada_col == "cedible" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['xml'])) ? $this->New_label['xml'] : "XML"; 
          if ($Cada_col == "xml" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
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
          $SC_Label = (isset($this->New_label['aprob_comercial'])) ? $this->New_label['aprob_comercial'] : "Aprob."; 
          if ($Cada_col == "aprob_comercial" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['rec_mercaderia'])) ? $this->New_label['rec_mercaderia'] : "RM "; 
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
      } 
      while (!$rs->EOF)
      {
         $this->xls_col = 0;
         $this->xls_row++;
         $this->tipo_docu = $rs->fields[0] ;  
         $this->tipo_docu = (string)$this->tipo_docu;
         $this->folio_dte = $rs->fields[1] ;  
         $this->folio_dte =  str_replace(",", ".", $this->folio_dte);
         $this->folio_dte = (string)$this->folio_dte;
         $this->est_xdte = $rs->fields[2] ;  
         $this->est_xdte = (string)$this->est_xdte;
         $this->fec_emi_dte = $rs->fields[3] ;  
         $this->fech_carg = $rs->fields[4] ;  
         $this->rut_rec_dte = $rs->fields[5] ;  
         $this->rut_rec_dte = (string)$this->rut_rec_dte;
         $this->nom_rec_dte = $rs->fields[6] ;  
         $this->dir_rec_dte = $rs->fields[7] ;  
         $this->com_rec_dte = $rs->fields[8] ;  
         $this->mntneto_dte = $rs->fields[9] ;  
         $this->mntneto_dte =  str_replace(",", ".", $this->mntneto_dte);
         $this->mntneto_dte = (string)$this->mntneto_dte;
         $this->mnt_exen_dte = $rs->fields[10] ;  
         $this->mnt_exen_dte =  str_replace(",", ".", $this->mnt_exen_dte);
         $this->mnt_exen_dte = (string)$this->mnt_exen_dte;
         $this->iva_dte = $rs->fields[11] ;  
         $this->iva_dte =  str_replace(",", ".", $this->iva_dte);
         $this->iva_dte = (string)$this->iva_dte;
         $this->mont_tot_dte = $rs->fields[12] ;  
         $this->mont_tot_dte =  str_replace(",", ".", $this->mont_tot_dte);
         $this->mont_tot_dte = (string)$this->mont_tot_dte;
         $this->track_id = $rs->fields[13] ;  
         $this->track_id = (string)$this->track_id;
         $this->path_pdf_cedible = $rs->fields[14] ;  
         $this->msg_xdte = $rs->fields[15] ;  
         $this->est_rec_doc = $rs->fields[16] ;  
         $this->est_res_rev = $rs->fields[17] ;  
         $this->est_recibo_mercaderias = $rs->fields[18] ;  
         //----- lookup - tipo_docu
         $this->look_tipo_docu = $this->tipo_docu; 
         $this->Lookup->lookup_tipo_docu($this->look_tipo_docu, $this->tipo_docu) ; 
         $this->look_tipo_docu = ($this->look_tipo_docu == "&nbsp;") ? "" : $this->look_tipo_docu; 
         //----- lookup - est_xdte
         $this->look_est_xdte = $this->est_xdte; 
         $this->Lookup->lookup_est_xdte($this->look_est_xdte); 
         $this->look_est_xdte = ($this->look_est_xdte == "&nbsp;") ? "" : $this->look_est_xdte; 
         $this->sc_proc_grid = true; 
         $_SESSION['scriptcase']['grid_public_v_dte']['contr_erro'] = 'on';
if (!isset($_SESSION['v_codi_empr'])) {$_SESSION['v_codi_empr'] = "";}
if (!isset($this->sc_temp_v_codi_empr)) {$this->sc_temp_v_codi_empr = (isset($_SESSION['v_codi_empr'])) ? $_SESSION['v_codi_empr'] : "";}
 $this->Ini->link_pdf_apl = "http://portaldte.opendte.cl/dte/view_pdf_rem.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu ";
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

if ($this->path_pdf_cedible  <> "") {
	$this->Ini->link_cedible_apl = "http://portaldte.opendte.cl/dte/view_pdf_rem.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &cd=true";
$this->Ini->link_cedible_apl = str_replace("'", "?&?'", $this->Ini->link_cedible_apl);
$this->Ini->link_cedible_parms = "";
$this->Ini->link_cedible_parms = str_replace("'", "?&?'", $this->Ini->link_cedible_parms);
$this->Ini->link_cedible_hint = "Ver PDF Cedible";
$this->Ini->link_cedible_hint = str_replace("'", "?&?'", $this->Ini->link_cedible_hint);
$this->Ini->link_cedible_target = "_blank";
$this->Ini->link_cedible_pos = "";
$this->Ini->link_cedible_alt = "440";
$this->Ini->link_cedible_larg = "630";
;
}		
		
$this->Ini->link_xml_apl = "http://portaldte.opendte.cl/dte/view_xml_rem.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu ";
$this->Ini->link_xml_apl = str_replace("'", "?&?'", $this->Ini->link_xml_apl);
$this->Ini->link_xml_parms = "";
$this->Ini->link_xml_parms = str_replace("'", "?&?'", $this->Ini->link_xml_parms);
$this->Ini->link_xml_hint = "Ver XML";
$this->Ini->link_xml_hint = str_replace("'", "?&?'", $this->Ini->link_xml_hint);
$this->Ini->link_xml_target = "_blank";
$this->Ini->link_xml_pos = "";
$this->Ini->link_xml_alt = "440";
$this->Ini->link_xml_larg = "630";
;		
		
if ($this->est_xdte >1000) {
	$this->Ini->link_est_xdte_apl = $this->Ini->path_link . "" . SC_dir_app_name('#') . "/#.php";
$this->Ini->link_est_xdte_apl = str_replace("'", "?&?'", $this->Ini->link_est_xdte_apl);
$this->Ini->link_est_xdte_parms = "";
$this->Ini->link_est_xdte_parms = str_replace("'", "?&?'", $this->Ini->link_est_xdte_parms);
$this->Ini->link_est_xdte_hint = "" . $this->msg_xdte  . "";
$this->Ini->link_est_xdte_hint = str_replace("'", "?&?'", $this->Ini->link_est_xdte_hint);
$this->Ini->link_est_xdte_target = "_blank";
$this->Ini->link_est_xdte_pos = "";
$this->Ini->link_est_xdte_alt = "440";
$this->Ini->link_est_xdte_larg = "630";
;		
}	
		

if ($this->est_rec_doc =='R'){
	$this->acuse_recibo ="<img src='../_lib/img/sys__NM__success.png'>";
	$this->Ini->link_acuse_recibo_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=AR";
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
}	
else if ($this->est_rec_doc =='X'){
	$this->Ini->link_acuse_recibo_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=AR";
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
	$this->acuse_recibo ="<img src='../_lib/img/sys__NM__rejected.png'>";
}	
else {
	$this->acuse_recibo ="<img src='../_lib/img/sys__NM__info.png'>";	
	$this->Ini->link_acuse_recibo_apl = $this->Ini->path_link . "" . SC_dir_app_name('#') . "/#.php";
$this->Ini->link_acuse_recibo_apl = str_replace("'", "?&?'", $this->Ini->link_acuse_recibo_apl);
$this->Ini->link_acuse_recibo_parms = "";
$this->Ini->link_acuse_recibo_parms = str_replace("'", "?&?'", $this->Ini->link_acuse_recibo_parms);
$this->Ini->link_acuse_recibo_hint = "Acuse de Recibido no Recepcionado";
$this->Ini->link_acuse_recibo_hint = str_replace("'", "?&?'", $this->Ini->link_acuse_recibo_hint);
$this->Ini->link_acuse_recibo_target = "_blank";
$this->Ini->link_acuse_recibo_pos = "";
$this->Ini->link_acuse_recibo_alt = "440";
$this->Ini->link_acuse_recibo_larg = "630";
;	
}	
			
			
if ($this->est_res_rev =='A'){
	$this->Ini->link_aprob_comercial_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=ARC";
$this->Ini->link_aprob_comercial_apl = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_hint);
$this->Ini->link_aprob_comercial_target = "_blank";
$this->Ini->link_aprob_comercial_pos = "";
$this->Ini->link_aprob_comercial_alt = "440";
$this->Ini->link_aprob_comercial_larg = "630";
;			
	$this->aprob_comercial ="<img src='../_lib/img/sys__NM__success.png'>";
}	
else if ($this->est_res_rev =='R'){
	$this->Ini->link_aprob_comercial_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=ARC";
$this->Ini->link_aprob_comercial_apl = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_hint);
$this->Ini->link_aprob_comercial_target = "_blank";
$this->Ini->link_aprob_comercial_pos = "";
$this->Ini->link_aprob_comercial_alt = "440";
$this->Ini->link_aprob_comercial_larg = "630";
;			
	$this->aprob_comercial ="<img src='../_lib/img/sys__NM__warning.png'>";
}				
else if ($this->est_res_rev =='X'){
	$this->Ini->link_aprob_comercial_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=ARC";
$this->Ini->link_aprob_comercial_apl = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_hint);
$this->Ini->link_aprob_comercial_target = "_blank";
$this->Ini->link_aprob_comercial_pos = "";
$this->Ini->link_aprob_comercial_alt = "440";
$this->Ini->link_aprob_comercial_larg = "630";
;			
	$this->aprob_comercial ="<img src='../_lib/img/sys__NM__rejected.png'>";
}	
else
	$this->aprob_comercial ="";			

if ($this->est_recibo_mercaderias =='R'){
	$this->Ini->link_aprob_comercial_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=RM";
$this->Ini->link_aprob_comercial_apl = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "?&?'", $this->Ini->link_aprob_comercial_hint);
$this->Ini->link_aprob_comercial_target = "_blank";
$this->Ini->link_aprob_comercial_pos = "";
$this->Ini->link_aprob_comercial_alt = "440";
$this->Ini->link_aprob_comercial_larg = "630";
;			
	$this->rec_mercaderia ="<img src='../_lib/img/sys__NM__success.png'>";
}	
else
	$this->rec_mercaderia ="";
if (isset($this->sc_temp_v_codi_empr)) {$_SESSION['v_codi_empr'] = $this->sc_temp_v_codi_empr;}
$_SESSION['scriptcase']['grid_public_v_dte']['contr_erro'] = 'off'; 
         foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['field_order'] as $Cada_col)
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
   //----- folio_dte
   function NM_export_folio_dte()
   {
         if (!NM_is_utf8($this->folio_dte))
         {
             $this->folio_dte = mb_convert_encoding($this->folio_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->folio_dte))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->folio_dte);
         $this->xls_col++;
   }
   //----- est_xdte
   function NM_export_est_xdte()
   {
         if (!NM_is_utf8($this->look_est_xdte))
         {
             $this->look_est_xdte = mb_convert_encoding($this->look_est_xdte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->look_est_xdte);
         $this->xls_col++;
   }
   //----- fec_emi_dte
   function NM_export_fec_emi_dte()
   {
         $conteudo_x =  $this->fec_emi_dte;
         nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
         if (is_numeric($conteudo_x) && $conteudo_x > 0) 
         { 
             $this->nm_data->SetaData($this->fec_emi_dte, "YYYY-MM-DD");
             $this->fec_emi_dte = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
         } 
         if (!NM_is_utf8($this->fec_emi_dte))
         {
             $this->fec_emi_dte = mb_convert_encoding($this->fec_emi_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->fec_emi_dte);
         $this->xls_col++;
   }
   //----- fech_carg
   function NM_export_fech_carg()
   {
         $conteudo_x =  $this->fech_carg;
         nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
         if (is_numeric($conteudo_x) && $conteudo_x > 0) 
         { 
             $this->nm_data->SetaData($this->fech_carg, "YYYY-MM-DD");
             $this->fech_carg = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
         } 
         if (!NM_is_utf8($this->fech_carg))
         {
             $this->fech_carg = mb_convert_encoding($this->fech_carg, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->fech_carg);
         $this->xls_col++;
   }
   //----- rut_rec_dte
   function NM_export_rut_rec_dte()
   {
         if (!NM_is_utf8($this->rut_rec_dte))
         {
             $this->rut_rec_dte = mb_convert_encoding($this->rut_rec_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->rut_rec_dte))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->rut_rec_dte);
         $this->xls_col++;
   }
   //----- nom_rec_dte
   function NM_export_nom_rec_dte()
   {
         if (!NM_is_utf8($this->nom_rec_dte))
         {
             $this->nom_rec_dte = mb_convert_encoding($this->nom_rec_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->nom_rec_dte);
         $this->xls_col++;
   }
   //----- dir_rec_dte
   function NM_export_dir_rec_dte()
   {
         if (!NM_is_utf8($this->dir_rec_dte))
         {
             $this->dir_rec_dte = mb_convert_encoding($this->dir_rec_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->dir_rec_dte);
         $this->xls_col++;
   }
   //----- com_rec_dte
   function NM_export_com_rec_dte()
   {
         if (!NM_is_utf8($this->com_rec_dte))
         {
             $this->com_rec_dte = mb_convert_encoding($this->com_rec_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->com_rec_dte);
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
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,###');
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
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,###');
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
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,###');
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
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,###');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->mont_tot_dte);
         $this->xls_col++;
   }
   //----- track_id
   function NM_export_track_id()
   {
         if (!NM_is_utf8($this->track_id))
         {
             $this->track_id = mb_convert_encoding($this->track_id, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->track_id))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->track_id);
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
   //----- cedible
   function NM_export_cedible()
   {
         if (!NM_is_utf8($this->cedible))
         {
             $this->cedible = mb_convert_encoding($this->cedible, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->cedible);
         $this->xls_col++;
   }
   //----- xml
   function NM_export_xml()
   {
         if (!NM_is_utf8($this->xml))
         {
             $this->xml = mb_convert_encoding($this->xml, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->xml);
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
   //----- aprob_comercial
   function NM_export_aprob_comercial()
   {
         if (!NM_is_utf8($this->aprob_comercial))
         {
             $this->aprob_comercial = mb_convert_encoding($this->aprob_comercial, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->aprob_comercial);
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
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['xls_file']);
      if (is_file($this->xls_f))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['xls_file'] = $this->xls_f;
      }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>Consulta DTE Emitidos :: Excel</TITLE>
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
<form name="Fdown" method="get" action="grid_public_v_dte_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="nm_tit_doc" value="<?php echo NM_encode_input($this->tit_doc); ?>"> 
<input type="hidden" name="nm_name_doc" value="<?php echo NM_encode_input($this->Ini->path_imag_temp . "/" . $this->arquivo) ?>"> 
</form>
<FORM name="F0" method=post action="grid_public_v_dte.php"> 
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
