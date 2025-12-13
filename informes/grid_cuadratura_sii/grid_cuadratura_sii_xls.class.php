<?php

class grid_cuadratura_sii_xls
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
   function grid_cuadratura_sii_xls()
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
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_cuadratura_sii.php"; 
      set_include_path(get_include_path() . PATH_SEPARATOR . $this->Ini->path_third . '/phpexcel/');
      require_once $this->Ini->path_third . '/phpexcel/PHPExcel.php';
      require_once $this->Ini->path_third . '/phpexcel/PHPExcel/IOFactory.php';
      $this->xls_col    = 0;
      $this->nm_data    = new nm_data("es");
      $this->arquivo    = "sc_xls";
      $this->arquivo   .= "_" . date("YmdHis") . "_" . rand(0, 1000);
      $this->arquivo   .= "_grid_cuadratura_sii";
      $this->arquivo   .= ".xls";
      $this->tit_doc    = "grid_cuadratura_sii.xls";
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
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xls_name']))
      {
          $this->arquivo = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xls_name'];
          $this->tit_doc = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xls_name'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xls_name']);
          $this->xls_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->arquivo;
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

      foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['field_order'] as $Cada_col)
      { 
          $SC_Label = (isset($this->New_label['tipo_dte'])) ? $this->New_label['tipo_dte'] : "Tipo DTE "; 
          if ($Cada_col == "tipo_dte" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['folio_dte'])) ? $this->New_label['folio_dte'] : "Folio DTE"; 
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
          $SC_Label = (isset($this->New_label['rut_emisor'])) ? $this->New_label['rut_emisor'] : "RUT Emisor"; 
          if ($Cada_col == "rut_emisor" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['razon_social'])) ? $this->New_label['razon_social'] : "Razon Social"; 
          if ($Cada_col == "razon_social" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
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
          $SC_Label = (isset($this->New_label['monto_total'])) ? $this->New_label['monto_total'] : "Mnt. Total"; 
          if ($Cada_col == "monto_total" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
             if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = mb_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
              $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $SC_Label);
              $this->xls_col++;
          }
          $SC_Label = (isset($this->New_label['fecha_hora_recepcion'])) ? $this->New_label['fecha_hora_recepcion'] : "Fec. Recep. SII"; 
          if ($Cada_col == "fecha_hora_recepcion" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
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
         $rs->MoveNext();
      }
      $rs->Close();
      $objWriter = PHPExcel_IOFactory::createWriter($this->xls_dados, 'Excel5');
      $objWriter->save($this->xls_f);
   }
   //----- tipo_dte
   function NM_export_tipo_dte()
   {
         if (!NM_is_utf8($this->look_tipo_dte))
         {
             $this->look_tipo_dte = mb_convert_encoding($this->look_tipo_dte, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->look_tipo_dte))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->look_tipo_dte);
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
   //----- rut_emisor
   function NM_export_rut_emisor()
   {
         if (!NM_is_utf8($this->rut_emisor))
         {
             $this->rut_emisor = mb_convert_encoding($this->rut_emisor, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->rut_emisor);
         $this->xls_col++;
   }
   //----- razon_social
   function NM_export_razon_social()
   {
         if (!NM_is_utf8($this->razon_social))
         {
             $this->razon_social = mb_convert_encoding($this->razon_social, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->razon_social);
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
   //----- monto_total
   function NM_export_monto_total()
   {
         if (!NM_is_utf8($this->monto_total))
         {
             $this->monto_total = mb_convert_encoding($this->monto_total, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->monto_total))
         {
             $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getNumberFormat()->setFormatCode('#,##0');
         }
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->monto_total);
         $this->xls_col++;
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
         if (!NM_is_utf8($this->fecha_hora_recepcion))
         {
             $this->fecha_hora_recepcion = mb_convert_encoding($this->fecha_hora_recepcion, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->xls_dados->getActiveSheet()->getStyle($this->calc_cell($this->xls_col) . $this->xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->xls_dados->getActiveSheet()->setCellValue($this->calc_cell($this->xls_col) . $this->xls_row, $this->fecha_hora_recepcion);
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
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xls_file']);
      if (is_file($this->xls_f))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['xls_file'] = $this->xls_f;
      }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>DTE no Recepcionados en OpenDTE :: Excel</TITLE>
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
<form name="Fdown" method="get" action="grid_cuadratura_sii_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="nm_tit_doc" value="<?php echo NM_encode_input($this->tit_doc); ?>"> 
<input type="hidden" name="nm_name_doc" value="<?php echo NM_encode_input($this->Ini->path_imag_temp . "/" . $this->arquivo) ?>"> 
</form>
<FORM name="F0" method=post action="grid_cuadratura_sii.php"> 
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
