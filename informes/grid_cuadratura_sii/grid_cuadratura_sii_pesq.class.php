<?php

class grid_cuadratura_sii_pesq
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;
   var $cmp_formatado;
   var $nm_data;
   var $Campos_Mens_erro;

   var $comando;
   var $comando_sum;
   var $comando_filtro;
   var $comando_ini;
   var $comando_fim;
   var $NM_operador;
   var $NM_data_qp;
   var $NM_path_filter;
   var $NM_curr_fil;
   var $nm_location;
   var $nmgp_botoes = array();
   var $NM_fil_ant = array();
   var $ajax_return_fields = array();

   /**
    * @access  public
    */
   function grid_cuadratura_sii_pesq()
   {
   }

   /**
    * @access  public
    * @global  string  $bprocessa  
    */
   function monta_busca()
   {
      global $bprocessa;
      include("../_lib/css/" . $this->Ini->str_schema_filter . "_filter.php");
      $this->Ini->Str_btn_filter = trim($str_button) . "/" . trim($str_button) . ".php";
      $this->Str_btn_filter_css  = trim($str_button) . "/" . trim($str_button) . ".css";
      include($this->Ini->path_btn . $this->Ini->Str_btn_filter);
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['path_libs_php'] = $this->Ini->path_lib_php;
      $this->Img_sep_filter = "/" . trim($str_toolbar_separator);
      $this->Block_img_col  = trim($str_block_col);
      $this->Block_img_exp  = trim($str_block_exp);
      $this->Bubble_tail    = trim($str_bubble_tail);
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_gp_config_btn.php", "F", "nmButtonOutput"); 
      if ($this->NM_ajax_flag)
      {
          $this->NM_ajax_info['param']['buffer_output'] = true;
          ob_start();
          $this->processa_ajax();
          $this->Db->Close(); 
          exit;
      }
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['prim_vez']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['prim_vez'] = "S";
      }
      if (isset($bprocessa) && "pesq" == $bprocessa)
      {
         $this->processa_busca();
      }
      else
      {
         $this->monta_formulario();
      }
   }

   /**
    * @access  public
    */
   function monta_formulario()
   {
      $this->init();
      $this->monta_html_ini();
      $this->monta_form();
      $this->monta_html_fim();
   }

   /**
    * @access  public
    */
   function init()
   {
      $_SESSION['scriptcase']['sc_tab_meses']['int'] = array(
                                  $this->Ini->Nm_lang['lang_mnth_janu'],
                                  $this->Ini->Nm_lang['lang_mnth_febr'],
                                  $this->Ini->Nm_lang['lang_mnth_marc'],
                                  $this->Ini->Nm_lang['lang_mnth_apri'],
                                  $this->Ini->Nm_lang['lang_mnth_mayy'],
                                  $this->Ini->Nm_lang['lang_mnth_june'],
                                  $this->Ini->Nm_lang['lang_mnth_july'],
                                  $this->Ini->Nm_lang['lang_mnth_augu'],
                                  $this->Ini->Nm_lang['lang_mnth_sept'],
                                  $this->Ini->Nm_lang['lang_mnth_octo'],
                                  $this->Ini->Nm_lang['lang_mnth_nove'],
                                  $this->Ini->Nm_lang['lang_mnth_dece']);
      $_SESSION['scriptcase']['sc_tab_meses']['abr'] = array(
                                  $this->Ini->Nm_lang['lang_shrt_mnth_janu'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_febr'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_marc'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_apri'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_mayy'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_june'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_july'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_augu'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_sept'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_octo'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_nove'],
                                  $this->Ini->Nm_lang['lang_shrt_mnth_dece']);
      $_SESSION['scriptcase']['sc_tab_dias']['int'] = array(
                                  $this->Ini->Nm_lang['lang_days_sund'],
                                  $this->Ini->Nm_lang['lang_days_mond'],
                                  $this->Ini->Nm_lang['lang_days_tued'],
                                  $this->Ini->Nm_lang['lang_days_wend'],
                                  $this->Ini->Nm_lang['lang_days_thud'],
                                  $this->Ini->Nm_lang['lang_days_frid'],
                                  $this->Ini->Nm_lang['lang_days_satd']);
      $_SESSION['scriptcase']['sc_tab_dias']['abr'] = array(
                                  $this->Ini->Nm_lang['lang_shrt_days_sund'],
                                  $this->Ini->Nm_lang['lang_shrt_days_mond'],
                                  $this->Ini->Nm_lang['lang_shrt_days_tued'],
                                  $this->Ini->Nm_lang['lang_shrt_days_wend'],
                                  $this->Ini->Nm_lang['lang_shrt_days_thud'],
                                  $this->Ini->Nm_lang['lang_shrt_days_frid'],
                                  $this->Ini->Nm_lang['lang_shrt_days_satd']);
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_data.class.php", "C", "nm_data") ; 
      $this->nm_data = new nm_data("es");
      $pos_path = strrpos($this->Ini->path_prod, "/");
      $this->NM_path_filter = $this->Ini->root . substr($this->Ini->path_prod, 0, $pos_path) . "/conf/filters/";
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['opcao'] = "igual";
   }

   function processa_ajax()
   {
      global 
      $tipo_dte_cond, $tipo_dte,
             $folio_dte_cond, $folio_dte,
             $rut_emisor_cond, $rut_emisor,
             $fec_emision_cond, $fec_emision, $fec_emision_dia, $fec_emision_mes, $fec_emision_ano, $fec_emision_input_2_dia, $fec_emision_input_2_mes, $fec_emision_input_2_ano,
             $fec_recep_cond, $fec_recep, $fec_recep_dia, $fec_recep_mes, $fec_recep_ano, $fec_recep_input_2_dia, $fec_recep_input_2_mes, $fec_recep_input_2_ano,
      $NM_filters, $NM_filters_del, $nmgp_save_name, $NM_operador, $nmgp_save_option, $bprocessa, $Ajax_label, $Ajax_val, $Campo_bi, $Opc_bi;
      $this->init();
      if (isset($this->NM_ajax_info['param']['tipo_dte_cond']))
      {
          $tipo_dte_cond = $this->NM_ajax_info['param']['tipo_dte_cond'];
      }
      if (isset($this->NM_ajax_info['param']['tipo_dte']))
      {
          $tipo_dte = $this->NM_ajax_info['param']['tipo_dte'];
      }
      if (isset($this->NM_ajax_info['param']['folio_dte_cond']))
      {
          $folio_dte_cond = $this->NM_ajax_info['param']['folio_dte_cond'];
      }
      if (isset($this->NM_ajax_info['param']['folio_dte']))
      {
          $folio_dte = $this->NM_ajax_info['param']['folio_dte'];
      }
      if (isset($this->NM_ajax_info['param']['rut_emisor_cond']))
      {
          $rut_emisor_cond = $this->NM_ajax_info['param']['rut_emisor_cond'];
      }
      if (isset($this->NM_ajax_info['param']['rut_emisor']))
      {
          $rut_emisor = $this->NM_ajax_info['param']['rut_emisor'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision_cond']))
      {
          $fec_emision_cond = $this->NM_ajax_info['param']['fec_emision_cond'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision']))
      {
          $fec_emision = $this->NM_ajax_info['param']['fec_emision'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision_dia']))
      {
          $fec_emision_dia = $this->NM_ajax_info['param']['fec_emision_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision_mes']))
      {
          $fec_emision_mes = $this->NM_ajax_info['param']['fec_emision_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision_ano']))
      {
          $fec_emision_ano = $this->NM_ajax_info['param']['fec_emision_ano'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision_input_2_dia']))
      {
          $fec_emision_input_2_dia = $this->NM_ajax_info['param']['fec_emision_input_2_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision_input_2_mes']))
      {
          $fec_emision_input_2_mes = $this->NM_ajax_info['param']['fec_emision_input_2_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emision_input_2_ano']))
      {
          $fec_emision_input_2_ano = $this->NM_ajax_info['param']['fec_emision_input_2_ano'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep_cond']))
      {
          $fec_recep_cond = $this->NM_ajax_info['param']['fec_recep_cond'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep']))
      {
          $fec_recep = $this->NM_ajax_info['param']['fec_recep'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep_dia']))
      {
          $fec_recep_dia = $this->NM_ajax_info['param']['fec_recep_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep_mes']))
      {
          $fec_recep_mes = $this->NM_ajax_info['param']['fec_recep_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep_ano']))
      {
          $fec_recep_ano = $this->NM_ajax_info['param']['fec_recep_ano'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep_input_2_dia']))
      {
          $fec_recep_input_2_dia = $this->NM_ajax_info['param']['fec_recep_input_2_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep_input_2_mes']))
      {
          $fec_recep_input_2_mes = $this->NM_ajax_info['param']['fec_recep_input_2_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fec_recep_input_2_ano']))
      {
          $fec_recep_input_2_ano = $this->NM_ajax_info['param']['fec_recep_input_2_ano'];
      }
      if (isset($this->NM_ajax_info['param']['NM_filters']))
      {
          $NM_filters = $this->NM_ajax_info['param']['NM_filters'];
      }
      if (isset($this->NM_ajax_info['param']['NM_filters_del']))
      {
          $NM_filters_del = $this->NM_ajax_info['param']['NM_filters_del'];
      }
      if (isset($this->NM_ajax_info['param']['nmgp_save_name']))
      {
          $nmgp_save_name = $this->NM_ajax_info['param']['nmgp_save_name'];
      }
      if (isset($this->NM_ajax_info['param']['NM_operador']))
      {
          $NM_operador = $this->NM_ajax_info['param']['NM_operador'];
      }
      if (isset($this->NM_ajax_info['param']['nmgp_save_option']))
      {
          $nmgp_save_option = $this->NM_ajax_info['param']['nmgp_save_option'];
      }
      if (isset($this->NM_ajax_info['param']['nmgp_refresh_fields']))
      {
          $nmgp_refresh_fields = $this->NM_ajax_info['param']['nmgp_refresh_fields'];
      }
      if (isset($this->NM_ajax_info['param']['bprocessa']))
      {
          $bprocessa = $this->NM_ajax_info['param']['bprocessa'];
      }
      if (isset($nmgp_refresh_fields))
      {
          $nmgp_refresh_fields = explode('_#fld#_', $nmgp_refresh_fields);
      }
      else
      {
          $nmgp_refresh_fields = array();
      }
//-- ajax metodos ---
      if (isset($bprocessa) && $bprocessa == "save_form")
      {
          $this->salva_filtro();
          $this->NM_fil_ant = $this->gera_array_filtros();
          $Nome_filter = "";
          $Opt_filter  = "<option value=\"\"></option>\r\n";
          foreach ($this->NM_fil_ant as $Cada_filter => $Tipo_filter)
          {
              if ($_SESSION['scriptcase']['charset'] != "UTF-8")
              {
                  $Tipo_filter[1] = mb_convert_encoding($Tipo_filter[1], "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($Tipo_filter[1] != $Nome_filter)
              {
                  $Nome_filter = $Tipo_filter[1];
                  $Opt_filter .= "<option value=\"\">" . grid_cuadratura_sii_pack_protect_string($Nome_filter) . "</option>\r\n";
              }
              $Opt_filter .= "<option value=\"" . grid_cuadratura_sii_pack_protect_string($Tipo_filter[0]) . "\">.." . grid_cuadratura_sii_pack_protect_string($Cada_filter) .  "</option>\r\n";
          }
      }

      if (isset($bprocessa) && $bprocessa == "filter_delete")
      {
          $this->apaga_filtro();
          $this->NM_fil_ant = $this->gera_array_filtros();
          $Nome_filter = "";
          $Opt_filter  = "<option value=\"\"></option>\r\n";
          foreach ($this->NM_fil_ant as $Cada_filter => $Tipo_filter)
          {
              if ($_SESSION['scriptcase']['charset'] != "UTF-8")
              {
                  $Tipo_filter[1] = mb_convert_encoding($Tipo_filter[1], "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($Tipo_filter[1] != $Nome_filter)
              {
                  $Nome_filter  = $Tipo_filter[1];
                  $Opt_filter .= "<option value=\"\">" .  grid_cuadratura_sii_pack_protect_string($Nome_filter) . "</option>\r\n";
              }
              $Opt_filter .= "<option value=\"" . grid_cuadratura_sii_pack_protect_string($Tipo_filter[0]) . "\">.." . grid_cuadratura_sii_pack_protect_string($Cada_filter) .  "</option>\r\n";
          }
      }

      if (isset($bprocessa) && $bprocessa == "filter_save")
      {
          $this->recupera_filtro();
          foreach ($this->ajax_return_fields as $cada_cmp => $cada_opt)
          {
              $this->NM_ajax_info['fldList'][$cada_cmp] = array(
                         'type'    => $cada_opt['obj'],
                         'valList' => $cada_opt['vallist'],
                         );
          }
      }

      if (isset($bprocessa) && $bprocessa == "proc_bi")
      {
          $this->process_cond_bi($Opc_bi, $BI_data1, $BI_data2);
          $this->NM_ajax_info['fldList'][$Campo_bi . "_dia"] = array('type' => 'text', 'valList' => array(substr($BI_data1, 0, 2)));
          $this->NM_ajax_info['fldList'][$Campo_bi . "_mes"] = array('type' => 'text', 'valList' => array(substr($BI_data1, 2, 2)));
          $this->NM_ajax_info['fldList'][$Campo_bi . "_ano"] = array('type' => 'text', 'valList' => array(substr($BI_data1, 4)));
          $this->NM_ajax_info['fldList'][$Campo_bi . "_input_2_dia"] = array('type' => 'text', 'valList' => array(substr($BI_data2, 0, 2)));
          $this->NM_ajax_info['fldList'][$Campo_bi . "_input_2_mes"] = array('type' => 'text', 'valList' => array(substr($BI_data2, 2, 2)));
          $this->NM_ajax_info['fldList'][$Campo_bi . "_input_2_ano"] = array('type' => 'text', 'valList' => array(substr($BI_data2, 4)));
      }
      if (in_array("tipo_dte", $nmgp_refresh_fields) || $bprocessa == "filter_save")
      {
          $nmgp_def_dados = $this->lookup_ajax_tipo_dte();
          $this->NM_ajax_info['fldList']['tipo_dte'] = array(
                     'type'    => 'select',
                     'optList' => $nmgp_def_dados,
                     'valList' => $Ajax_val,
                     );
      }
   }
   function lookup_ajax_tipo_dte()
   {
      global $tipo_dte, $Ajax_label, $Ajax_val;
      $tmp_pos = strpos($tipo_dte, "##@@");
      if ($tmp_pos !== false)
      {
          $tipo_dte = substr($tipo_dte, 0, $tmp_pos);
      }
            $Ajax_val     = array(); 
      $Ajax_label   = array(); 
      $nmgp_def_dados = array(); 
      $nm_comando = "SELECT tipo_docu, desc_tipo_docu  FROM \"public\".dte_tipo  ORDER BY desc_tipo_docu"; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      if ($rs = $this->Db->Execute($nm_comando)) 
      { 
         while (!$rs->EOF) 
         { 
            $cmp1 = NM_charset_to_utf8(trim($rs->fields[0]));
            $cmp2 = NM_charset_to_utf8(trim($rs->fields[1]));
            if (trim($rs->fields[0]) == $tipo_dte)
            {
                $Ajax_val[]   = $cmp1 . "##@@" . $cmp2;
                $Ajax_label[] = $cmp2;
            }
            $cmp1 = grid_cuadratura_sii_pack_protect_string($cmp1);
            $cmp2 = grid_cuadratura_sii_pack_protect_string($cmp2);
            $nmgp_def_dados[] = array($cmp1 . "##@@" . $cmp2 => $cmp2); 
            $rs->MoveNext() ; 
         } 
         $rs->Close() ; 
      } 
      else  
      {  
         $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
         exit; 
      } 

      $sel_ret_ajax  = "      <SELECT " . $this->css_obj_select_ajax('tipo_dte') . " name=\"tipo_dte\"  size=\"1\">\r\n";
      $sel_ret_ajax .= "       <OPTION value=\"\">" . grid_cuadratura_sii_pack_protect_string('Seleccione Tipo DTE') . "</OPTION>\r\n";
      foreach ($nmgp_def_dados as $NM_ind => $NM_opcoes)
      {
         foreach ($NM_opcoes as $NM_val => $NM_label)
         {
            $sel_ret_ajax .= "       <OPTION value=\"" .  $NM_val . "\">" . $NM_label . "</OPTION>\r\n";
         }
      }
      $sel_ret_ajax .= "      </SELECT>\r\n";
      
      return $sel_ret_ajax;
   }
   

   /**
    * @access  public
    */
   function processa_busca()
   {
      $this->inicializa_vars();
      $this->trata_campos();
      if (!empty($this->Campos_Mens_erro)) 
      {
          echo "<script type=\"text/javascript\">"; 
          echo "parent.NM_exibe_erro('" . $this->Campos_Mens_erro . "')";
          echo "</script>"; 
      }
      else
      {
          $this->finaliza_resultado();
      }
   }

   /**
    * @access  public
    */
   function testa_browser()
   {
      $valido = FALSE;
      if (FALSE !== strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 5.5"))
      {
         $valido = TRUE;
      }
      elseif (FALSE !== strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 6"))
      {
         $valido = TRUE;
      }
      elseif (FALSE !== strpos($_SERVER['HTTP_USER_AGENT'], "Mozilla"))
      {
         $valido = TRUE;
      }
      return ($valido);
   }

   /**
    * @access  public
    */
   function and_or()
   {
      $posWhere = strpos(strtolower($this->comando), "where");
      if (FALSE === $posWhere)
      {
         $this->comando     .= " where ";
         $this->comando_sum .= " and ";
      }
      if ($this->comando_ini == "ini")
      {
          if (FALSE !== $posWhere)
          {
              $this->comando     .= " and ( ";
              $this->comando_sum .= " and ( ";
              $this->comando_fim  = " ) ";
          }
         $this->comando_ini  = "";
      }
      elseif ("or" == $this->NM_operador)
      {
         $this->comando        .= " or ";
         $this->comando_sum    .= " or ";
         $this->comando_filtro .= " or ";
      }
      else
      {
         $this->comando        .= " and ";
         $this->comando_sum    .= " and ";
         $this->comando_filtro .= " and ";
      }
   }

   /**
    * @access  public
    * @param  string  $nome  
    * @param  string  $condicao  
    * @param  mixed  $campo  
    * @param  mixed  $campo2  
    * @param  string  $nome_campo  
    * @param  string  $tp_campo  
    * @global  array  $nmgp_tab_label  
    */
   function monta_condicao($nome, $condicao, $campo, $campo2 = "", $nome_campo="", $tp_campo="")
   {
      global $nmgp_tab_label;
      $nm_aspas   = "'";
      $Nm_numeric = array();
      $nm_ini_lower = "";
      $nm_fim_lower = "";
      $Nm_numeric[] = "tipo_dte";$Nm_numeric[] = "folio_dte";$Nm_numeric[] = "monto_total";$Nm_numeric[] = "mntneto_dte";$Nm_numeric[] = "mnt_exen_dte";$Nm_numeric[] = "iva_dte";$Nm_numeric[] = "mont_tot_dte";
      $campo_join = strtolower(str_replace(".", "_", $nome));
      if (in_array($campo_join, $Nm_numeric))
      {
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['decimal_db'] == ".")
         {
            $nm_aspas = "";
         }
         if ($condicao != "in")
         {
            if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['decimal_db'] == ".")
            {
               $campo  = str_replace(",", ".", $campo);
               $campo2 = str_replace(",", ".", $campo2);
            }
            if ($campo == "")
            {
               $campo = 0;
            }
            if ($campo2 == "")
            {
               $campo2 = 0;
            }
         }
      }
      $Nm_datas[] = "fecha_hora_recepcion";$Nm_datas[] = "fec_emision";$Nm_datas[] = "fec_recep";
      $campo_join = strtolower(str_replace(".", "_", $nome));
      if ($campo == "" && $condicao != "nu" && $condicao != "nn")
      {
         return;
      }
      else
      {
         $tmp_pos = strpos($campo, "##@@");
         if ($tmp_pos === false)
         {
             $res_lookup = $campo;
         }
         else
         {
             $res_lookup = substr($campo, $tmp_pos + 4);
             $campo = substr($campo, 0, $tmp_pos);
             if ($campo === "" && $condicao != "nu" && $condicao != "nn")
             {
                 return;
             }
         }
         $tmp_pos = strpos($this->cmp_formatado[$nome_campo], "##@@");
         if ($tmp_pos !== false)
         {
             $this->cmp_formatado[$nome_campo] = substr($this->cmp_formatado[$nome_campo], $tmp_pos + 4);
         }
         $this->and_or();
         $campo  = substr($this->Db->qstr($campo), 1, -1);
         $campo2 = substr($this->Db->qstr($campo2), 1, -1);
         $nome_sum = ".$nome";
         if (in_array($campo_join, $Nm_numeric) && in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres) && (strtoupper($condicao) == "II" || strtoupper($condicao) == "QP" || strtoupper($condicao) == "NP"))
         {
             $nome     = "CAST ($nome AS TEXT)";
             $nome_sum = "CAST ($nome_sum AS TEXT)";
         }
         if (substr($tp_campo, 0, 8) == "DATETIME" && in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres) && strtoupper($condicao) != "QP" && strtoupper($condicao) != "NP")
         {
             $nome     = "to_char ($nome, 'YYYY-MM-DD hh24:mi:ss')";
             $nome_sum = "to_char ($nome_sum, 'YYYY-MM-DD hh24:mi:ss')";
         }
         elseif (substr($tp_campo, 0, 4) == "DATE" && in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres) && strtoupper($condicao) != "QP" && strtoupper($condicao) != "NP")
         {
             $nome     = "to_char ($nome, 'YYYY-MM-DD')";
             $nome_sum = "to_char ($nome_sum, 'YYYY-MM-DD' )";
         }
         switch (strtoupper($condicao))
         {
            case "EQ":     // 
               $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " = " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " = " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower. " = " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "II":     // 
               $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " like '" . $campo . "%'";
               $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " like '" . $campo . "%'";
               $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " like '" . $campo . "%'";
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_strt'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "QP":     // 
               if (substr($tp_campo, 0, 4) == "DATE")
               {
                   $NM_cond    = "";
                   $NM_cmd     = "";
                   $NM_cmd_sum = "";
                   if ($this->NM_data_qp['ano'] != "____")
                   {
                       $NM_cond    .= (empty($NM_cmd)) ? "" : " " . $this->Ini->Nm_lang['lang_srch_andd'] . " ";
                       $NM_cond    .= $this->Ini->Nm_lang['lang_srch_year'] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->NM_data_qp['ano'];
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       $NM_cmd_sum .= (empty($NM_cmd_sum)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sqlite))
                       {
                           $NM_cmd     .= "strftime('%Y', $nome) = '" . $this->NM_data_qp['ano'] . "'";
                           $NM_cmd_sum .= "strftime('%Y', $nome_sum) = '" . $this->NM_data_qp['ano'] . "'";
                       }
                       elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('year' from $nome) = " . $this->NM_data_qp['ano'];
                           $NM_cmd_sum .= "extract('year' from $nome_sum) = " . $this->NM_data_qp['ano'];
                       }
                       else
                       {
                           $NM_cmd     .= "year($nome) = " . $this->NM_data_qp['ano'];
                           $NM_cmd_sum .= "year($nome_sum) = " . $this->NM_data_qp['ano'];
                       }
                   }
                   if ($this->NM_data_qp['mes'] != "__")
                   {
                       $NM_cond    .= (empty($NM_cmd)) ? "" : " " . $this->Ini->Nm_lang['lang_srch_andd'] . " ";
                       $NM_cond    .= $this->Ini->Nm_lang['lang_srch_mnth'] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->NM_data_qp['mes'];
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       $NM_cmd_sum .= (empty($NM_cmd_sum)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sqlite))
                       {
                           $NM_cmd     .= "strftime('%m', $nome) = '" . $this->NM_data_qp['mes'] . "'";
                           $NM_cmd_sum .= "strftime('%m', $nome_sum) = '" . $this->NM_data_qp['mes'] . "'";
                       }
                       elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('month' from $nome) = " . $this->NM_data_qp['mes'];
                           $NM_cmd_sum .= "extract('month' from $nome_sum) = " . $this->NM_data_qp['mes'];
                       }
                       else
                       {
                           $NM_cmd     .= "month($nome) = " . $this->NM_data_qp['mes'];
                           $NM_cmd_sum .= "month($nome_sum) = " . $this->NM_data_qp['mes'];
                       }
                   }
                   if ($this->NM_data_qp['dia'] != "__")
                   {
                       $NM_cond    .= (empty($NM_cmd)) ? "" : " " . $this->Ini->Nm_lang['lang_srch_andd'] . " ";
                       $NM_cond    .= $this->Ini->Nm_lang['lang_srch_days'] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->NM_data_qp['dia'];
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       $NM_cmd_sum .= (empty($NM_cmd_sum)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sqlite))
                       {
                           $NM_cmd     .= "strftime('%d', $nome) = '" . $this->NM_data_qp['dia'] . "'";
                           $NM_cmd_sum .= "strftime('%d', $nome_sum) = '" . $this->NM_data_qp['dia'] . "'";
                       }
                       elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('day' from $nome) = " . $this->NM_data_qp['dia'];
                           $NM_cmd_sum .= "extract('day' from $nome_sum) = " . $this->NM_data_qp['dia'];
                       }
                       else
                       {
                           $NM_cmd     .= "day($nome) = " . $this->NM_data_qp['dia'];
                           $NM_cmd_sum .= "day($nome_sum) = " . $this->NM_data_qp['dia'];
                       }
                   }
                   if (!empty($NM_cmd))
                   {
                       $NM_cmd     = " (" . $NM_cmd . ")";
                       $NM_cmd_sum = " (" . $NM_cmd_sum . ")";
                       $this->comando        .= $NM_cmd;
                       $this->comando_sum    .= $NM_cmd_sum;
                       $this->comando_filtro .= $NM_cmd;
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_like'] . " " . $NM_cond . "##*@@";
                   }
               }
               else
               {
                   $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower ." like '%" . $campo . "%'";
                   $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " like '%" . $campo . "%'";
                   $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " like '%" . $campo . "%'";
                   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_like'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
               }
            break;
            case "NP":     // 
               if (substr($tp_campo, 0, 4) == "DATE")
               {
                   $NM_cond    = "";
                   $NM_cmd     = "";
                   $NM_cmd_sum = "";
                   if ($this->NM_data_qp['ano'] != "____")
                   {
                       $NM_cond    .= (empty($NM_cmd)) ? "" : " " . $this->Ini->Nm_lang['lang_srch_andd'] . " ";
                       $NM_cond    .= $this->Ini->Nm_lang['lang_srch_year'] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->NM_data_qp['ano'];
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " or ";
                       $NM_cmd_sum .= (empty($NM_cmd_sum)) ? "" : " or ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sqlite))
                       {
                           $NM_cmd     .= "strftime('%Y', $nome) <> '" . $this->NM_data_qp['ano'] . "'";
                           $NM_cmd_sum .= "strftime('%Y', $nome_sum) <> '" . $this->NM_data_qp['ano'] . "'";
                       }
                       elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('year' from $nome) <> " . $this->NM_data_qp['ano'];
                           $NM_cmd_sum .= "extract('year' from $nome_sum) <> " . $this->NM_data_qp['ano'];
                       }
                       else
                       {
                           $NM_cmd     .= "year($nome) <> " . $this->NM_data_qp['ano'];
                           $NM_cmd_sum .= "year($nome_sum) <> " . $this->NM_data_qp['ano'];
                       }
                   }
                   if ($this->NM_data_qp['mes'] != "__")
                   {
                       $NM_cond    .= (empty($NM_cmd)) ? "" : " " . $this->Ini->Nm_lang['lang_srch_andd'] . " ";
                       $NM_cond    .= $this->Ini->Nm_lang['lang_srch_mnth'] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->NM_data_qp['mes'];
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " or ";
                       $NM_cmd_sum .= (empty($NM_cmd_sum)) ? "" : " or ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sqlite))
                       {
                           $NM_cmd     .= "strftime('%m', $nome) <> '" . $this->NM_data_qp['mes'] . "'";
                           $NM_cmd_sum .= "strftime('%m', $nome_sum) <> '" . $this->NM_data_qp['mes'] . "'";
                       }
                       elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('month' from $nome) <> " . $this->NM_data_qp['mes'];
                           $NM_cmd_sum .= "extract('month' from $nome_sum) <> " . $this->NM_data_qp['mes'];
                       }
                       else
                       {
                           $NM_cmd     .= "month($nome) <> " . $this->NM_data_qp['mes'];
                           $NM_cmd_sum .= "month($nome_sum) <> " . $this->NM_data_qp['mes'];
                       }
                   }
                   if ($this->NM_data_qp['dia'] != "__")
                   {
                       $NM_cond    .= (empty($NM_cmd)) ? "" : " " . $this->Ini->Nm_lang['lang_srch_andd'] . " ";
                       $NM_cond    .= $this->Ini->Nm_lang['lang_srch_days'] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->NM_data_qp['dia'];
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " or ";
                       $NM_cmd_sum .= (empty($NM_cmd_sum)) ? "" : " or ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sqlite))
                       {
                           $NM_cmd     .= "strftime('%d', $nome) <> '" . $this->NM_data_qp['dia'] . "'";
                           $NM_cmd_sum .= "strftime('%d', $nome_sum) <> '" . $this->NM_data_qp['dia'] . "'";
                       }
                       elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('day' from $nome) <> " . $this->NM_data_qp['dia'];
                           $NM_cmd_sum .= "extract('day' from $nome_sum) <> " . $this->NM_data_qp['dia'];
                       }
                       else
                       {
                           $NM_cmd     .= "day($nome) <> " . $this->NM_data_qp['dia'];
                           $NM_cmd_sum .= "day($nome_sum) <> " . $this->NM_data_qp['dia'];
                       }
                   }
                   if (!empty($NM_cmd))
                   {
                       $NM_cmd     = " (" . $NM_cmd . ")";
                       $NM_cmd_sum = " (" . $NM_cmd_sum . ")";
                       $this->comando        .= $NM_cmd;
                       $this->comando_sum    .= $NM_cmd_sum;
                       $this->comando_filtro .= $NM_cmd;
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_not_like'] . " " . $NM_cond . "##*@@";
                   }
               }
               else
               {
                   $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower ." not like '%" . $campo . "%'";
                   $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " not like '%" . $campo . "%'";
                   $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " not like '%" . $campo . "%'";
                   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_not_like'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
               }
            break;
            case "DF":     // 
               if ($tp_campo == "DTDF" || $tp_campo == "DATEDF" || $tp_campo == "DATETIMEDF")
               {
                   $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " not like '%" . $campo . "%'";
                   $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " not like '%" . $campo . "%'";
                   $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " not like '%" . $campo . "%'";
               }
               else
               {
                   $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " <> " . $nm_aspas . $campo . $nm_aspas;
                   $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " <> " . $nm_aspas . $campo . $nm_aspas;
                   $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " <> " . $nm_aspas . $campo . $nm_aspas;
               }
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_diff'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "GT":     // 
               $this->comando        .= " $nome > " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum > " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome > " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $nmgp_lang['pesq_cond_maior'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "GE":     // 
               $this->comando        .= " $nome >= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum >= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome >= " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_grtr_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "LT":     // 
               $this->comando        .= " $nome < " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum < " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome < " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_less'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "LE":     // 
               $this->comando        .= " $nome <= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum <= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome <= " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_less_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "BW":     // 
               if ($tp_campo == "DTDF" || $tp_campo == "DATEDF" || $tp_campo == "DATETIMEDF")
               {
                   $this->comando        .= " $nome not between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_sum    .= " $nome_sum not between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_filtro .= " $nome not between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_diff'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
               }
               else
               {
                   $this->comando        .= " $nome between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_sum    .= " $nome_sum between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_filtro .= " $nome between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   if ($tp_campo == "DTEQ" || $tp_campo == "DATEEQ" || $tp_campo == "DATETIMEEQ")
                   {
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
                   }
                   else
                   {
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_betw'] . " " . $this->cmp_formatado[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_andd'] . " " . $this->cmp_formatado[$nome_campo . "_Input_2"] . "##*@@";
                   }
               }
            break;
            case "IN":     // 
               $nm_sc_valores = explode(",", $campo);
               $cond_str = "";
               $nm_cond  = "";
               if (!empty($nm_sc_valores))
               {
                   foreach ($nm_sc_valores as $nm_sc_valor)
                   {
                      if (in_array($campo_join, $Nm_numeric) && substr_count($nm_sc_valor, ".") > 1)
                      {
                         $nm_sc_valor = str_replace(".", "", $nm_sc_valor);
                      }
                      if ("" != $cond_str)
                      {
                         $cond_str .= ",";
                         $nm_cond  .= " " . $this->Ini->Nm_lang['lang_srch_orrr'] . " ";
                      }
                      $cond_str .= $nm_aspas . $nm_sc_valor . $nm_aspas;
                      $nm_cond  .= $nm_aspas . $nm_sc_valor . $nm_aspas;
                   }
               }
               $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " in (" . $cond_str . ")";
               $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " in (" . $cond_str . ")";
               $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " in (" . $cond_str . ")";
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_like'] . " " . $nm_cond . "##*@@";
            break;
            case "NU":     // 
               $this->comando        .= " $nome IS NULL ";
               $this->comando_sum    .= " $nome_sum IS NULL ";
               $this->comando_filtro .= " $nome IS NULL ";
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_null'] ." " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "NN":     // 
               $this->comando        .= " $nome IS NOT NULL ";
               $this->comando_sum    .= " $nome_sum IS NOT NULL ";
               $this->comando_filtro .= " $nome IS NOT NULL ";
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_nnul'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
         }
      }
   }

   /**
    * @access  public
    * @param  array  $data_arr  
    */
   function data_menor(&$data_arr)
   {
      $data_arr["ano"] = ("____" == $data_arr["ano"]) ? "0001" : $data_arr["ano"];
      $data_arr["mes"] = ("__" == $data_arr["mes"])   ? "01" : $data_arr["mes"];
      $data_arr["dia"] = ("__" == $data_arr["dia"])   ? "01" : $data_arr["dia"];
      $data_arr["hor"] = ("__" == $data_arr["hor"])   ? "00" : $data_arr["hor"];
      $data_arr["min"] = ("__" == $data_arr["min"])   ? "00" : $data_arr["min"];
      $data_arr["seg"] = ("__" == $data_arr["seg"])   ? "00" : $data_arr["seg"];
   }

   /**
    * @access  public
    * @param  array  $data_arr  
    */
   function data_maior(&$data_arr)
   {
      $data_arr["ano"] = ("____" == $data_arr["ano"]) ? "9999" : $data_arr["ano"];
      $data_arr["mes"] = ("__" == $data_arr["mes"])   ? "12" : $data_arr["mes"];
      $data_arr["hor"] = ("__" == $data_arr["hor"])   ? "23" : $data_arr["hor"];
      $data_arr["min"] = ("__" == $data_arr["min"])   ? "59" : $data_arr["min"];
      $data_arr["seg"] = ("__" == $data_arr["seg"])   ? "59" : $data_arr["seg"];
      if ("__" == $data_arr["dia"])
      {
          $data_arr["dia"] = "31";
          if ($data_arr["mes"] == "04" || $data_arr["mes"] == "06" || $data_arr["mes"] == "09" || $data_arr["mes"] == "11")
          {
              $data_arr["dia"] = 30;
          }
          elseif ($data_arr["mes"] == "02")
          { 
                  if  ($data_arr["ano"] % 4 == 0)
                  {
                       $data_arr["dia"] = 29;
                  }
                  else 
                  {
                       $data_arr["dia"] = 28;
                  }
          }
      }
   }

   /**
    * @access  public
    * @param  string  $nm_data_hora  
    */
   function limpa_dt_hor_pesq(&$nm_data_hora)
   {
      $nm_data_hora = str_replace("Y", "", $nm_data_hora); 
      $nm_data_hora = str_replace("M", "", $nm_data_hora); 
      $nm_data_hora = str_replace("D", "", $nm_data_hora); 
      $nm_data_hora = str_replace("H", "", $nm_data_hora); 
      $nm_data_hora = str_replace("I", "", $nm_data_hora); 
      $nm_data_hora = str_replace("S", "", $nm_data_hora); 
      $tmp_pos = strpos($nm_data_hora, "--");
      if ($tmp_pos !== FALSE)
      {
          $nm_data_hora = str_replace("--", "-", $nm_data_hora); 
      }
      $tmp_pos = strpos($nm_data_hora, "::");
      if ($tmp_pos !== FALSE)
      {
          $nm_data_hora = str_replace("::", ":", $nm_data_hora); 
      }
   }

   /**
    * @access  public
    */
   function retorna_pesq()
   {
      global $nm_apl_dependente;
   $NM_retorno = "grid_cuadratura_sii.php";
    if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['proc_res'])) 
    {
        $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['proc_res'] = false; 
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
<BODY class="scGridPage">
<SCRIPT type="text/javascript">
<?php
    if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['prim_vez'] == "N" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['proc_res']) 
    {
?>
   parent.document.getElementById('nmsc_iframe_grid_cuadratura_sii').contentWindow.nm_gp_submit_ajax('inicio', '');
<?php
    }
    else
    {
?>
   parent.document.getElementById('nmsc_iframe_grid_cuadratura_sii').src = 'grid_cuadratura_sii.php?nmgp_opcao=pesq&script_case_init=<?php echo NM_encode_input($this->Ini->sc_page) ?>&script_case_session=<?php echo session_id() ?>';
<?php
    }
    $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['prim_vez'] = "N";
?>
</SCRIPT>
</BODY>
</HTML>
<?php
}

   /**
    * @access  public
    */
   function monta_html_ini()
   {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>DTE no Recepcionados en OpenDTE</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
 <META http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT"/>
 <META http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT"/>
 <META http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate"/>
 <META http-equiv="Cache-Control" content="post-check=0, pre-check=0"/>
 <META http-equiv="Pragma" content="no-cache"/>
 <link rel="stylesheet" href="<?php echo $this->Ini->path_prod ?>/third/jquery_plugin/thickbox/thickbox.css" type="text/css" media="screen" />
 <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_filter ?>_filter.css" /> 
 <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_filter ?>_error.css" /> 
 <link rel="stylesheet" type="text/css" href="../_lib/buttons/<?php echo $this->Str_btn_filter_css ?>" /> 
 <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_filter ?>_form.css" /> 
</HEAD>
<BODY class="scFilterPage">
<?php echo $this->Ini->Ajax_result_set ?>
<SCRIPT type="text/javascript" src="<?php echo $this->Ini->path_js . "/browserSniffer.js" ?>"></SCRIPT>
 <script type="text/javascript" src="<?php echo $this->Ini->path_prod ?>/third/jquery/js/jquery.js"></script>
 <script type="text/javascript" src="<?php echo $this->Ini->path_prod ?>/third/jquery_plugin/malsup-blockui/jquery.blockUI.js"></script>
 <script type="text/javascript" src="../_lib/lib/js/jquery.scInput.js"></script>
 <script type="text/javascript">var sc_pathToTB = '<?php echo $this->Ini->path_prod ?>/third/jquery_plugin/thickbox/';</script>
 <script type="text/javascript" src="<?php echo $this->Ini->path_prod ?>/third/jquery_plugin/thickbox/thickbox-compressed.js"></script>
 <script type="text/javascript">
   var sc_ajaxBg = '<?php echo $this->Ini->Color_bg_ajax ?>';
   var sc_ajaxBordC = '<?php echo $this->Ini->Border_c_ajax ?>';
   var sc_ajaxBordS = '<?php echo $this->Ini->Border_s_ajax ?>';
   var sc_ajaxBordW = '<?php echo $this->Ini->Border_w_ajax ?>';
 </script>
 <script type="text/javascript" src="<?php echo $this->Ini->path_prod; ?>/third/jquery/js/jquery-ui.js"></script>
 <link rel="stylesheet" href="<?php echo $this->Ini->path_prod ?>/third/jquery/css/smoothness/jquery-ui.css" type="text/css" media="screen" />
 <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_filter ?>_calendar.css" />
<?php
$Cod_Btn = nmButtonOutput($this->arr_buttons, "berrm_clse", "scAjaxHideDebug()", "scAjaxHideDebug()", "", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
?>
<div id="id_debug_window" style="display: none; position: absolute; left: 50px; top: 50px"><table class="scFormMessageTable">
<tr><td class="scFormMessageTitle"><?php echo $Cod_Btn ?>&nbsp;&nbsp;Output</td></tr>
<tr><td class="scFormMessageMessage" style="padding: 0px; vertical-align: top"><div style="padding: 2px; height: 200px; width: 350px; overflow: auto" id="id_debug_text"></div></td></tr>
</table></div>
 <SCRIPT type="text/javascript">

<?php
if (is_file($this->Ini->root . $this->Ini->path_link . "_lib/js/tab_erro_" . $this->Ini->str_lang . ".js"))
{
    $Tb_err_js = file($this->Ini->root . $this->Ini->path_link . "_lib/js/tab_erro_" . $this->Ini->str_lang . ".js");
    foreach ($Tb_err_js as $Lines)
    {
        if (NM_is_utf8($Lines) && $_SESSION['scriptcase']['charset'] != "UTF-8")
        {
            $Lines = mb_convert_encoding($Lines, $_SESSION['scriptcase']['charset'], "UTF-8");
        }
        echo $Lines;
    }
}
$Msg_Inval = mb_convert_encoding("Inválido", $_SESSION['scriptcase']['charset']);
?>
var SC_crit_inv = "<?php echo $Msg_Inval ?>";
var nmdg_Form = "F1";

function scJQCalendarAdd() {
  $("#sc_fec_emision_jq").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fec_emision_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emision_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emision_ano').value;
          document.getElementById('sc_fec_emision_jq').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fec_emision_dia').value = aParts[0];
          document.getElementById('SC_fec_emision_mes').value = aParts[1];
          document.getElementById('SC_fec_emision_ano').value = aParts[2];
    },
    showWeek: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
    yearRange: 'c-5:c+5',
    dayNames: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_satd']); ?>'],
    dayNamesMin: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_satd']); ?>'],
    monthNamesShort: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_janu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_febr']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_marc']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_apri']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_mayy']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_june']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_july']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_augu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_sept']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_octo']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_nove']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_dece']); ?>'],
    weekHeader: "<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_days_sem']); ?>",
    firstDay: <?php echo $this->jqueryCalendarWeekInit("" . $_SESSION['scriptcase']['reg_conf']['date_week_ini'] . ""); ?>,
    dateFormat: "<?php echo $this->jqueryCalendarDtFormat("ddmmyyyy", "/"); ?>",
    showOtherMonths: true,
    showOn: "button",
    buttonImage: "<?php echo $this->Ini->path_botoes . "/" . $this->arr_buttons['bcalendario']['image']; ?>",
    buttonImageOnly: true
  });

  $("#sc_fec_emision_jq2").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fec_emision_input_2_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emision_input_2_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emision_input_2_ano').value;
          document.getElementById('sc_fec_emision_jq2').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fec_emision_input_2_dia').value = aParts[0];
          document.getElementById('SC_fec_emision_input_2_mes').value = aParts[1];
          document.getElementById('SC_fec_emision_input_2_ano').value = aParts[2];
    },
    showWeek: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
    yearRange: 'c-5:c+5',
    dayNames: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_satd']); ?>'],
    dayNamesMin: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_satd']); ?>'],
    monthNamesShort: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_janu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_febr']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_marc']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_apri']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_mayy']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_june']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_july']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_augu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_sept']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_octo']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_nove']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_dece']); ?>'],
    weekHeader: "<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_days_sem']); ?>",
    firstDay: <?php echo $this->jqueryCalendarWeekInit("" . $_SESSION['scriptcase']['reg_conf']['date_week_ini'] . ""); ?>,
    dateFormat: "<?php echo $this->jqueryCalendarDtFormat("ddmmyyyy", "/"); ?>",
    showOtherMonths: true,
    showOn: "button",
    buttonImage: "<?php echo $this->Ini->path_botoes . "/" . $this->arr_buttons['bcalendario']['image']; ?>",
    buttonImageOnly: true
  });

  $("#sc_fec_recep_jq").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fec_recep_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fec_recep_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fec_recep_ano').value;
          document.getElementById('sc_fec_recep_jq').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fec_recep_dia').value = aParts[0];
          document.getElementById('SC_fec_recep_mes').value = aParts[1];
          document.getElementById('SC_fec_recep_ano').value = aParts[2];
    },
    showWeek: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
    yearRange: 'c-5:c+5',
    dayNames: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_satd']); ?>'],
    dayNamesMin: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_satd']); ?>'],
    monthNamesShort: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_janu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_febr']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_marc']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_apri']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_mayy']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_june']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_july']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_augu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_sept']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_octo']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_nove']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_dece']); ?>'],
    weekHeader: "<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_days_sem']); ?>",
    firstDay: <?php echo $this->jqueryCalendarWeekInit("" . $_SESSION['scriptcase']['reg_conf']['date_week_ini'] . ""); ?>,
    dateFormat: "<?php echo $this->jqueryCalendarDtFormat("ddmmyyyy", "/"); ?>",
    showOtherMonths: true,
    showOn: "button",
    buttonImage: "<?php echo $this->Ini->path_botoes . "/" . $this->arr_buttons['bcalendario']['image']; ?>",
    buttonImageOnly: true
  });

  $("#sc_fec_recep_jq2").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fec_recep_input_2_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fec_recep_input_2_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fec_recep_input_2_ano').value;
          document.getElementById('sc_fec_recep_jq2').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fec_recep_input_2_dia').value = aParts[0];
          document.getElementById('SC_fec_recep_input_2_mes').value = aParts[1];
          document.getElementById('SC_fec_recep_input_2_ano').value = aParts[2];
    },
    showWeek: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
    yearRange: 'c-5:c+5',
    dayNames: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_satd']); ?>'],
    dayNamesMin: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_sund']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_mond']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_tued']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_wend']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_thud']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_frid']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_satd']); ?>'],
    monthNamesShort: ['<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_janu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_febr']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_marc']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_apri']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_mayy']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_june']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_july']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_augu']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_sept']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_octo']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_nove']); ?>','<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_dece']); ?>'],
    weekHeader: "<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_days_sem']); ?>",
    firstDay: <?php echo $this->jqueryCalendarWeekInit("" . $_SESSION['scriptcase']['reg_conf']['date_week_ini'] . ""); ?>,
    dateFormat: "<?php echo $this->jqueryCalendarDtFormat("ddmmyyyy", "/"); ?>",
    showOtherMonths: true,
    showOn: "button",
    buttonImage: "<?php echo $this->Ini->path_botoes . "/" . $this->arr_buttons['bcalendario']['image']; ?>",
    buttonImageOnly: true
  });

} // scJQCalendarAdd


 $(function() {

   SC_carga_evt_jquery();
   $('input:text.sc-js-input').listen();
   scJQCalendarAdd('');
 });
var NM_index = 0;
var NM_hidden = new Array();
var NM_IE = (navigator.userAgent.indexOf('MSIE') > -1) ? 1 : 0;
function NM_hitTest(o, l)
{
    function getOffset(o){
        for(var r = {l: o.offsetLeft, t: o.offsetTop, r: o.offsetWidth, b: o.offsetHeight};
            o = o.offsetParent; r.l += o.offsetLeft, r.t += o.offsetTop);
        return r.r += r.l, r.b += r.t, r;
    }
    for(var b, s, r = [], a = getOffset(o), j = isNaN(l.length), i = (j ? l = [l] : l).length; i;
        b = getOffset(l[--i]), (a.l == b.l || (a.l > b.l ? a.l <= b.r : b.l <= a.r))
        && (a.t == b.t || (a.t > b.t ? a.t <= b.b : b.t <= a.b)) && (r[r.length] = l[i]));
    return j ? !!r.length : r;
}
var tem_obj = false;
function NM_show_menu(nn)
{
    if (!NM_IE)
    {
         return;
    }
    x = document.getElementById(nn);
    x.style.display = "block";
    obj_sel = document.body;
    tem_obj = true;
    x.ieFix = NM_hitTest(x, obj_sel.getElementsByTagName("select"));
    for (i = 0; i <  x.ieFix.length; i++)
    {
      if (x.ieFix[i].style.visibility != "hidden")
      {
          x.ieFix[i].style.visibility = "hidden";
          NM_hidden[NM_index] = x.ieFix[i];
          NM_index++;
      }
    }
}
function NM_hide_menu()
{
    if (!NM_IE)
    {
         return;
    }
    obj_del = document.body;
    if (tem_obj && obj_del == obj_sel)
    {
        for(var i = NM_hidden.length; i; NM_hidden[--i].style.visibility = "visible");
    }
    NM_index = 0;
    NM_hidden = new Array();
}
 function nm_campos_between(nm_campo, nm_cond, nm_nome_obj)
 {
  if (nm_cond.value == "bw")
  {
   nm_campo.style.display = "";
  }
  else
  {
    if (nm_campo)
    {
      nm_campo.style.display = "none";
    }
  }
 }
function nm_open_popup(parms)
{
    NovaJanela = window.open (parms, '', 'resizable, scrollbars');
}
 </SCRIPT>
<?php
include_once("grid_cuadratura_sii_sajax_js.php");
?>
<script type="text/javascript">
 $(function() {
 });
</script>
 <FORM name="F1" action="grid_cuadratura_sii.php" method="post" target="_self"> 
 <INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
 <INPUT type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id()); ?>"> 
 <INPUT type="hidden" name="nmgp_opcao" value="busca"> 
 <div id="idJSSpecChar" style="display:none;"></div>
 <div id="id_div_process" style="display: none; position: absolute"><table class="scFilterTable"><tr><td class="scFilterLabelOdd"><?php echo $this->Ini->Nm_lang['lang_othr_prcs']; ?>...</td></tr></table></div>
 <div id="id_fatal_error" class="scFilterFieldOdd" style="display:none; position: absolute"></div>
<table width="100%" style="border-collapse: collapse"><tr><td style="padding: 0px">
 <div id="NM_mostra_erro" style="display:none;">
<TABLE class="scErrorTable" cellspacing="0" cellpadding="0" align="center">
 <TR>
  <TD class="scErrorTitle" align="left"><?php echo $this->Ini->Nm_lang["gr_erro"]; ?></TD>
 </TR>
 <TR>
  <TD class="scErrorMessage" align="center"><span id="NM_erro_crit"></span></TD>
 </TR>
</TABLE> </div>
  </td></tr><tr><td style="padding: 0px">
<script type="text/javascript">
function NM_exibe_erro(mens)
{
 document.getElementById('NM_mostra_erro').style.display='';
 document.getElementById('NM_erro_crit').innerHTML = mens;
}
function NM_apaga_erro()
{
 document.getElementById('NM_mostra_erro').style.display='none';
 document.getElementById('NM_erro_crit').innerHTML = '';
}
</script>
<table style="padding: 0px; spacing: 0px; border-width: 0px;" align="center" valign="top" width="100%"><tr><td style="padding: 0px">
<TABLE class="scFilterBorder" align="center" valign="top" >
  <div id="id_div_process_block" style="display: none; margin: 10px; whitespace: nowrap"><span class="scFormProcess"><img border="0" src="<?php echo $this->Ini->path_icones ?>/scriptcase__NM__ajax_load.gif" align="absmiddle" />&nbsp;<?php echo $this->Ini->Nm_lang['lang_othr_prcs'] ?>...</span></div>
<?php
   }

   /**
    * @access  public
    * @global  string  $bprocessa  
    */
   /**
    * @access  public
    * @global  string  $nm_url_saida  $this->Ini->Nm_lang['pesq_global_nm_url_saida']
    * @global  integer  $nm_apl_dependente  $this->Ini->Nm_lang['pesq_global_nm_apl_dependente']
    * @global  string  $nmgp_parms  
    * @global  string  $bprocessa  $this->Ini->Nm_lang['pesq_global_bprocessa']
    */
   function monta_form()
   {
      global 
             $tipo_dte_cond, $tipo_dte,
             $folio_dte_cond, $folio_dte,
             $rut_emisor_cond, $rut_emisor,
             $fec_emision_cond, $fec_emision, $fec_emision_dia, $fec_emision_mes, $fec_emision_ano, $fec_emision_input_2_dia, $fec_emision_input_2_mes, $fec_emision_input_2_ano,
             $fec_recep_cond, $fec_recep, $fec_recep_dia, $fec_recep_mes, $fec_recep_ano, $fec_recep_input_2_dia, $fec_recep_input_2_mes, $fec_recep_input_2_ano,
             $nm_url_saida, $nm_apl_dependente, $nmgp_parms, $bprocessa, $nmgp_save_name, $NM_operador, $NM_filters, $nmgp_save_option, $NM_filters_del, $Script_BI;
      $Script_BI = "";
      $this->nmgp_botoes['clear'] = "on";
      $this->nmgp_botoes['save'] = "on";
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_cuadratura_sii']['btn_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_cuadratura_sii']['btn_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_cuadratura_sii']['btn_display'] as $NM_cada_btn => $NM_cada_opc)
          {
              $this->nmgp_botoes[$NM_cada_btn] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['scriptcase']['sc_aba_iframe']))
      {
          foreach ($_SESSION['scriptcase']['sc_aba_iframe'] as $aba => $apls_aba)
          {
              if (in_array("grid_cuadratura_sii", $apls_aba))
              {
                  $this->aba_iframe = true;
                  break;
              }
          }
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['iframe_menu'] && (!isset($_SESSION['scriptcase']['menu_mobile']) || empty($_SESSION['scriptcase']['menu_mobile'])))
      {
          $this->aba_iframe = true;
      }
      $nmgp_tab_label = "";
      $delimitador = "##@@";
      if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']) && $bprocessa != "recarga" && $bprocessa != "save_form" && $bprocessa != "filter_save" && $bprocessa != "filter_delete")
      { 
          if ($_SESSION['scriptcase']['charset'] != "UTF-8")
          {
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca'] = NM_conv_charset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca'], $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          $tipo_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['tipo_dte']; 
          $tipo_dte_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['tipo_dte_cond']; 
          $folio_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['folio_dte']; 
          $folio_dte_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['folio_dte_cond']; 
          $rut_emisor = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['rut_emisor']; 
          $rut_emisor_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['rut_emisor_cond']; 
          $fec_emision_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_dia']; 
          $fec_emision_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_mes']; 
          $fec_emision_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_ano']; 
          $fec_emision_input_2_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2_dia']; 
          $fec_emision_input_2_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2_mes']; 
          $fec_emision_input_2_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2_ano']; 
          $fec_emision_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_cond']; 
          $fec_recep_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_dia']; 
          $fec_recep_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_mes']; 
          $fec_recep_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_ano']; 
          $fec_recep_input_2_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2_dia']; 
          $fec_recep_input_2_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2_mes']; 
          $fec_recep_input_2_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2_ano']; 
          $fec_recep_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_cond']; 
          $this->NM_operador = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['NM_operador']; 
          if (strtoupper($folio_dte_cond) != "II" && strtoupper($folio_dte_cond) != "QP" && strtoupper($folio_dte_cond) != "NP" && strtoupper($folio_dte_cond) != "IN") 
          { 
              nmgp_Form_Num_Val($folio_dte, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "1", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
          } 
      } 
      if (!isset($tipo_dte_cond) || empty($tipo_dte_cond))
      {
         $tipo_dte_cond = "eq";
      }
      if (!isset($folio_dte_cond) || empty($folio_dte_cond))
      {
         $folio_dte_cond = "eq";
      }
      if (!isset($rut_emisor_cond) || empty($rut_emisor_cond))
      {
         $rut_emisor_cond = "eq";
      }
      if (!isset($fec_emision_cond) || empty($fec_emision_cond))
      {
         $fec_emision_cond = "bw";
      }
      if (!isset($fec_recep_cond) || empty($fec_recep_cond))
      {
         $fec_recep_cond = "bw";
      }
      $browser_ok = $this->testa_browser();
      if ($browser_ok)
      {
         $display_aberto  = "style=display:";
         $display_fechado = "style=display:none";
      }
      else
      {
         $display_aberto  = "";
         $display_fechado = "";
      }

      $str_display_fec_emision = ('bw' == $fec_emision_cond) ? $display_aberto : $display_fechado;
      $str_display_fec_recep = ('bw' == $fec_recep_cond) ? $display_aberto : $display_fechado;

      if (!isset($tipo_dte) || $tipo_dte == "")
      {
          $tipo_dte = "";
      }
      if (isset($tipo_dte) && !empty($tipo_dte))
      {
         $tmp_pos = strpos($tipo_dte, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $tipo_dte = substr($tipo_dte, 0, $tmp_pos);
         }
      }
      if (!isset($folio_dte) || $folio_dte == "")
      {
          $folio_dte = "";
      }
      if (isset($folio_dte) && !empty($folio_dte))
      {
         $tmp_pos = strpos($folio_dte, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $folio_dte = substr($folio_dte, 0, $tmp_pos);
         }
      }
      if (!isset($rut_emisor) || $rut_emisor == "")
      {
          $rut_emisor = "";
      }
      if (isset($rut_emisor) && !empty($rut_emisor))
      {
         $tmp_pos = strpos($rut_emisor, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $rut_emisor = substr($rut_emisor, 0, $tmp_pos);
         }
      }
      if (!isset($fec_emision) || $fec_emision == "")
      {
          $fec_emision = "";
      }
      if (isset($fec_emision) && !empty($fec_emision))
      {
         $tmp_pos = strpos($fec_emision, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $fec_emision = substr($fec_emision, 0, $tmp_pos);
         }
      }
      if (!isset($fec_recep) || $fec_recep == "")
      {
          $fec_recep = "";
      }
      if (isset($fec_recep) && !empty($fec_recep))
      {
         $tmp_pos = strpos($fec_recep, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $fec_recep = substr($fec_recep, 0, $tmp_pos);
         }
      }
?>
 <TR align="center">
  <TD class="scFilterTableTd">
   <table width="100%" class="scFilterToolbar"><tr>
    <td class="scFilterToolbarPadding" align="left" width="33%" nowrap>
    </td>
    <td class="scFilterToolbarPadding" align="center" width="33%" nowrap>
    </td>
    <td class="scFilterToolbarPadding" align="right" width="33%" nowrap>
<?php
      if ( isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['sc_modal']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['sc_modal'])
   {
?>
       <?php echo nmButtonOutput($this->arr_buttons, "bsair", "document.form_cancel.submit()", "document.form_cancel.submit()", "sc_b_cancel_", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
?>
<?php
   }
?>
<?php
   if (is_file("grid_cuadratura_sii_help.txt"))
   {
      $Arq_WebHelp = file("grid_cuadratura_sii_help.txt"); 
      if (isset($Arq_WebHelp[0]) && !empty($Arq_WebHelp[0]))
      {
          $Arq_WebHelp[0] = str_replace("\r\n" , "", trim($Arq_WebHelp[0]));
          $Tmp = explode(";", $Arq_WebHelp[0]); 
          foreach ($Tmp as $Cada_help)
          {
              $Tmp1 = explode(":", $Cada_help); 
              if (!empty($Tmp1[0]) && isset($Tmp1[1]) && !empty($Tmp1[1]) && $Tmp1[0] == "fil" && is_file($this->Ini->root . $this->Ini->path_help . $Tmp1[1]))
              {
?>
          <?php echo nmButtonOutput($this->arr_buttons, "bhelp", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "sc_b_help_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
?>
<?php
              }
          }
      }
   }
?>
    </td>
   </tr></table>
  </TD>
 </TR>
 <TR align="center">
  <TD class="scFilterTableTd">
   <TABLE style="padding: 0px; spacing: 0px; border-width: 0px;" width="100%" height="100%">
   <TR valign="top" >
  <TD width="100%" height="">
   <TABLE class="scFilterTable" id="hidden_bloco_0" valign="top" width="100%" style="height: 100%;">
   <tr>





      <TD class="scFilterLabelOdd"><?php echo (isset($this->New_label['tipo_dte'])) ? $this->New_label['tipo_dte'] : "Tipo DTE "; ?></TD>
     <TD class="scFilterFieldOdd"> 
      <INPUT type="hidden" name="tipo_dte_cond" value="eq"><?php echo $this->Ini->Nm_lang['lang_srch_exac'] ?>
 </TD>
     <TD  class="scFilterFieldOdd">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontOdd">
           <?php
 $SC_Label = (isset($this->New_label['tipo_dte'])) ? $this->New_label['tipo_dte'] : "Tipo DTE ";
 $nmgp_tab_label .= "tipo_dte?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>

<?php
      $nmgp_def_dados = "" ; 
      $nm_comando = "SELECT tipo_docu, desc_tipo_docu  FROM \"public\".dte_tipo  ORDER BY desc_tipo_docu"; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      if ($rs = $this->Db->Execute($nm_comando)) 
      { 
         while (!$rs->EOF) 
         { 
            $nmgp_def_dados .= trim($rs->fields[1]) . "?#?" ; 
            $nmgp_def_dados .= trim($rs->fields[0]) . "?#?N?@?" ; 
            $rs->MoveNext() ; 
         } 
         $rs->Close() ; 
      } 
      else  
      {  
         $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
         exit; 
      } 
?>
   <span id="idAjaxSelect_tipo_dte">
      <SELECT class="scFilterObjectOdd" name="tipo_dte"  size="1">
       <OPTION value="">Seleccione Tipo DTE</OPTION>
<?php
      $nm_opcoes = explode("?@?", $nmgp_def_dados);
      foreach ($nm_opcoes as $nm_opcao)
      {
         if (!empty($nm_opcao))
         {
            $temp_bug_list                                = explode("?#?", $nm_opcao);
            list($nm_opc_val, $nm_opc_cod, $nm_opc_sel) = $temp_bug_list;
            if ("" != $tipo_dte)
            {
                    $tipo_dte_sel = ($nm_opc_cod === $tipo_dte) ? "selected" : "";
            }
            else
            {
               $tipo_dte_sel = ("S" == $nm_opc_sel) ? "selected" : "";
            }
            $nm_sc_valor = $nm_opc_val;
            $nm_opc_val = $nm_sc_valor;
?>
       <OPTION value="<?php echo NM_encode_input($nm_opc_cod . $delimitador . $nm_opc_val); ?>" <?php echo $tipo_dte_sel; ?>><?php echo $nm_opc_val; ?></OPTION>
<?php
         }
      }
?>
      </SELECT>
   </span>
<?php
?>
        
        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelEven"><?php echo (isset($this->New_label['folio_dte'])) ? $this->New_label['folio_dte'] : "Folio DTE"; ?></TD>
     <TD class="scFilterFieldEven"> 
      <INPUT type="hidden" name="folio_dte_cond" value="eq"><?php echo $this->Ini->Nm_lang['lang_srch_exac'] ?>
 </TD>
     <TD  class="scFilterFieldEven">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontEven">
           <?php
 $SC_Label = (isset($this->New_label['folio_dte'])) ? $this->New_label['folio_dte'] : "Folio DTE";
 $nmgp_tab_label .= "folio_dte?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>
<INPUT  type="text" id="SC_folio_dte" name="folio_dte" value="<?php echo NM_encode_input($folio_dte) ?>" size=10 alt="{datatype: 'decimal', maxLength: 10, precision: 0, decimalSep: '<?php echo $_SESSION['scriptcase']['reg_conf']['dec_num'] ?>', thousandsSep: '<?php echo $_SESSION['scriptcase']['reg_conf']['grup_num'] ?>', allowNegative: false, onlyNegative: false, enterTab: false}" class="sc-js-input scFilterObjectEven">

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelOdd"><?php echo (isset($this->New_label['rut_emisor'])) ? $this->New_label['rut_emisor'] : "RUT Emisor"; ?></TD>
     <TD class="scFilterFieldOdd"> 
      <INPUT type="hidden" name="rut_emisor_cond" value="eq"><?php echo $this->Ini->Nm_lang['lang_srch_exac'] ?>
 </TD>
     <TD  class="scFilterFieldOdd">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontOdd">
           <?php
 $SC_Label = (isset($this->New_label['rut_emisor'])) ? $this->New_label['rut_emisor'] : "RUT Emisor";
 $nmgp_tab_label .= "rut_emisor?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>
<INPUT  type="text" id="SC_rut_emisor" name="rut_emisor" value="<?php echo NM_encode_input($rut_emisor) ?>" size=10 alt="{datatype: 'text', maxLength: 10, allowedChars: '', lettersCase: '', autoTab: false, enterTab: false}" class="sc-js-input scFilterObjectOdd">

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelEven"><?php echo (isset($this->New_label['fec_emision'])) ? $this->New_label['fec_emision'] : "Fec. Emision"; ?></TD>
     <TD class="scFilterFieldEven"> 
      <INPUT type="hidden" name="fec_emision_cond" value="bw"><?php echo $this->Ini->Nm_lang['lang_srch_betw'] ?>
 </TD>
     <TD  class="scFilterFieldEven">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontEven">
           <?php
 $SC_Label = (isset($this->New_label['fec_emision'])) ? $this->New_label['fec_emision'] : "Fec. Emision";
 $nmgp_tab_label .= "fec_emision?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>

<?php
  $Form_base = "ddmmyyyy";
  $date_format_show = "";
  $Str_date = str_replace("a", "y", strtolower($_SESSION['scriptcase']['reg_conf']['date_format']));
  $Lim   = strlen($Str_date);
  $Str   = "";
  $Ult   = "";
  $Arr_D = array();
  for ($I = 0; $I < $Lim; $I++)
  {
      $Char = substr($Str_date, $I, 1);
      if ($Char != $Ult && "" != $Str)
      {
          $Arr_D[] = $Str;
          $Str     = $Char;
      }
      else
      {
          $Str    .= $Char;
      }
      $Ult = $Char;
  }
  $Arr_D[] = $Str;
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
  $Arr_format = $Arr_D;
  $date_format_show = str_replace("dd",   $this->Ini->Nm_lang['lang_othr_date_days'], $date_format_show);
  $date_format_show = str_replace("mm",   $this->Ini->Nm_lang['lang_othr_date_mnth'], $date_format_show);
  $date_format_show = str_replace("yyyy", $this->Ini->Nm_lang['lang_othr_date_year'], $date_format_show);
  $date_format_show = str_replace("aaaa", $this->Ini->Nm_lang['lang_othr_date_year'], $date_format_show);
  $date_format_show = str_replace("hh",   $this->Ini->Nm_lang['lang_othr_date_hour'], $date_format_show);
  $date_format_show = str_replace("ii",   $this->Ini->Nm_lang['lang_othr_date_mint'], $date_format_show);
  $date_format_show = str_replace("ss",   $this->Ini->Nm_lang['lang_othr_date_scnd'], $date_format_show);
  $date_format_show = "(" . $date_format_show .  ")";

?>

         <?php

foreach ($Arr_format as $Part_date)
{
?>
<?php
  if (substr($Part_date, 0,1) == "d")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fec_emision_dia" name="fec_emision_dia" value="<?php echo NM_encode_input($fec_emision_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emision_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fec_emision_mes" name="fec_emision_mes" value="<?php echo NM_encode_input($fec_emision_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emision_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fec_emision_ano" name="fec_emision_ano" value="<?php echo NM_encode_input($fec_emision_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
<INPUT type="hidden" id="sc_fec_emision_jq">
        <SPAN id="id_css_fec_emision"  class="scFilterFieldFontEven">
 <?php echo $date_format_show ?>         </SPAN>
                 </TD>
       </TR>
       <TR valign="top">
        <TD id="id_vis_fec_emision"  <?php echo $str_display_fec_emision; ?> class="scFilterFieldFontEven">
         <?php echo $date_sep_bw ?>
         <BR>
         
         <?php

foreach ($Arr_format as $Part_date)
{
?>
<?php
  if (substr($Part_date, 0,1) == "d")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fec_emision_input_2_dia" name="fec_emision_input_2_dia" value="<?php echo NM_encode_input($fec_emision_input_2_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emision_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fec_emision_input_2_mes" name="fec_emision_input_2_mes" value="<?php echo NM_encode_input($fec_emision_input_2_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emision_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fec_emision_input_2_ano" name="fec_emision_input_2_ano" value="<?php echo NM_encode_input($fec_emision_input_2_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
         <INPUT type="hidden" id="sc_fec_emision_jq2">

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelOdd"><?php echo (isset($this->New_label['fec_recep'])) ? $this->New_label['fec_recep'] : "Fec. Recep"; ?></TD>
     <TD class="scFilterFieldOdd"> 
      <INPUT type="hidden" name="fec_recep_cond" value="bw"><?php echo $this->Ini->Nm_lang['lang_srch_betw'] ?>
 </TD>
     <TD  class="scFilterFieldOdd">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontOdd">
           <?php
 $SC_Label = (isset($this->New_label['fec_recep'])) ? $this->New_label['fec_recep'] : "Fec. Recep";
 $nmgp_tab_label .= "fec_recep?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>

<?php
  $Form_base = "ddmmyyyy";
  $date_format_show = "";
  $Str_date = str_replace("a", "y", strtolower($_SESSION['scriptcase']['reg_conf']['date_format']));
  $Lim   = strlen($Str_date);
  $Str   = "";
  $Ult   = "";
  $Arr_D = array();
  for ($I = 0; $I < $Lim; $I++)
  {
      $Char = substr($Str_date, $I, 1);
      if ($Char != $Ult && "" != $Str)
      {
          $Arr_D[] = $Str;
          $Str     = $Char;
      }
      else
      {
          $Str    .= $Char;
      }
      $Ult = $Char;
  }
  $Arr_D[] = $Str;
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
  $Arr_format = $Arr_D;
  $date_format_show = str_replace("dd",   $this->Ini->Nm_lang['lang_othr_date_days'], $date_format_show);
  $date_format_show = str_replace("mm",   $this->Ini->Nm_lang['lang_othr_date_mnth'], $date_format_show);
  $date_format_show = str_replace("yyyy", $this->Ini->Nm_lang['lang_othr_date_year'], $date_format_show);
  $date_format_show = str_replace("aaaa", $this->Ini->Nm_lang['lang_othr_date_year'], $date_format_show);
  $date_format_show = str_replace("hh",   $this->Ini->Nm_lang['lang_othr_date_hour'], $date_format_show);
  $date_format_show = str_replace("ii",   $this->Ini->Nm_lang['lang_othr_date_mint'], $date_format_show);
  $date_format_show = str_replace("ss",   $this->Ini->Nm_lang['lang_othr_date_scnd'], $date_format_show);
  $date_format_show = "(" . $date_format_show .  ")";

?>

         <?php

foreach ($Arr_format as $Part_date)
{
?>
<?php
  if (substr($Part_date, 0,1) == "d")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_recep_dia" name="fec_recep_dia" value="<?php echo NM_encode_input($fec_recep_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_recep_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_recep_mes" name="fec_recep_mes" value="<?php echo NM_encode_input($fec_recep_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_recep_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_recep_ano" name="fec_recep_ano" value="<?php echo NM_encode_input($fec_recep_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
<INPUT type="hidden" id="sc_fec_recep_jq">
        <SPAN id="id_css_fec_recep"  class="scFilterFieldFontOdd">
 <?php echo $date_format_show ?>         </SPAN>
                 </TD>
       </TR>
       <TR valign="top">
        <TD id="id_vis_fec_recep"  <?php echo $str_display_fec_recep; ?> class="scFilterFieldFontOdd">
         <?php echo $date_sep_bw ?>
         <BR>
         
         <?php

foreach ($Arr_format as $Part_date)
{
?>
<?php
  if (substr($Part_date, 0,1) == "d")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_recep_input_2_dia" name="fec_recep_input_2_dia" value="<?php echo NM_encode_input($fec_recep_input_2_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_recep_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_recep_input_2_mes" name="fec_recep_input_2_mes" value="<?php echo NM_encode_input($fec_recep_input_2_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_recep_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_recep_input_2_ano" name="fec_recep_input_2_ano" value="<?php echo NM_encode_input($fec_recep_input_2_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
         <INPUT type="hidden" id="sc_fec_recep_jq2">

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr>
   </TABLE>
  </TD>
 </TR>
 </TABLE>
 </TD>
 </TR>
 <TR align="center">
  <TD class="scFilterTableTd">
<INPUT type="hidden" name="NM_operador" value="and">   <INPUT type="hidden" name="nmgp_tab_label" value="<?php echo NM_encode_input($nmgp_tab_label); ?>"> 
   <INPUT type="hidden" name="bprocessa" value="pesq"> 
  </TD>
 </TR>
 <TR align="center">
  <TD class="scFilterTableTd">
   <table width="100%" class="scFilterToolbar"><tr>
    <td class="scFilterToolbarPadding" align="left" width="33%" nowrap>
    </td>
    <td class="scFilterToolbarPadding" align="center" width="33%" nowrap>
   <?php echo nmButtonOutput($this->arr_buttons, "bpesquisa", "document.F1.bprocessa.value='pesq'; nm_submit_form()", "document.F1.bprocessa.value='pesq'; nm_submit_form()", "sc_b_pesq_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
?>
<?php
   if ($this->nmgp_botoes['clear'] == "on")
   {
?>
          <?php echo nmButtonOutput($this->arr_buttons, "blimpar", "limpa_form()", "limpa_form()", "limpa_frm_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
?>
<?php
   }
?>
    </td>
    <td class="scFilterToolbarPadding" align="right" width="33%" nowrap>
<?php
   if (is_file("grid_cuadratura_sii_help.txt"))
   {
      $Arq_WebHelp = file("grid_cuadratura_sii_help.txt"); 
      if (isset($Arq_WebHelp[0]) && !empty($Arq_WebHelp[0]))
      {
          $Arq_WebHelp[0] = str_replace("\r\n" , "", trim($Arq_WebHelp[0]));
          $Tmp = explode(";", $Arq_WebHelp[0]); 
          foreach ($Tmp as $Cada_help)
          {
              $Tmp1 = explode(":", $Cada_help); 
              if (!empty($Tmp1[0]) && isset($Tmp1[1]) && !empty($Tmp1[1]) && $Tmp1[0] == "fil" && is_file($this->Ini->root . $this->Ini->path_help . $Tmp1[1]))
              {
?>
          <?php echo nmButtonOutput($this->arr_buttons, "bhelp", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "sc_b_help_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
?>
<?php
              }
          }
      }
   }
?>
    </td>
   </tr></table>
  </TD>
 </TR>
<?php
   }

   function monta_html_fim()
   {
       global $bprocessa, $nm_url_saida, $Script_BI;
?>

</td></tr></table><tr><td>
<table align="center" valign="top" width="100%"><tr><td>
<?php
    if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['prim_vez'] == "N") 
    {
?>
       <iframe id="nmsc_iframe_grid_cuadratura_sii" frameborder="0" marginwidth="0px" marginheight="0px" topmargin="0px" leftmargin="0px" align="center" height="1500px" name="nm_iframe_grid_cuadratura_sii" scrolling="auto" src="grid_cuadratura_sii.php?nmgp_opcao=pesq&script_case_init=<?php echo NM_encode_input($this->Ini->sc_page) ?>&script_case_session=<?php echo session_id() ?>" width="100%"></iframe>
<?php
    }
    else
    {
?>
       <iframe id="nmsc_iframe_grid_cuadratura_sii" frameborder="0" marginwidth="0px" marginheight="0px" topmargin="0px" leftmargin="0px" align="center" height="1500px" name="nm_iframe_grid_cuadratura_sii" scrolling="auto" src="" width="100%"></iframe>
<?php
    }
?>
<iframe id="nmsc_iframe_grid_cuadratura_sii_pesq"  name="nm_iframe_grid_cuadratura_sii_pesq" style= "display: none;" frameborder="0" marginwidth="0px" marginheight="0px" topmargin="0px" leftmargin="0px" src=""></iframe>
</td></tr></table></td></tr>
</TABLE>
   <INPUT type="hidden" name="form_condicao" value="3">
</FORM> 
   <FORM style="display:none;" name="form_cancel"  method="POST" action="<?php echo $nm_url_saida; ?>" target="_self"> 
   <INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
   <INPUT type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id()); ?>"> 
<?php
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['orig_pesq'] == "grid")
   {
       $Ret_cancel_pesq = "volta_grid";
   }
   else
   {
       $Ret_cancel_pesq = "resumo";
   }
?>
   <INPUT type="hidden" name="nmgp_opcao" value="<?php echo $Ret_cancel_pesq; ?>"> 
   </FORM> 
<SCRIPT type="text/javascript">
 function nm_submit_form()
 {
    document.F1.target = "nm_iframe_grid_cuadratura_sii_pesq";
    document.F1.submit();
 }
 function limpa_form()
 {
   document.F1.reset();
   NM_apaga_erro();
   document.F1.tipo_dte.value = "";
   document.F1.folio_dte.value = "";
   document.F1.rut_emisor.value = "";
   document.F1.fec_emision_dia.value = "";
   document.F1.fec_emision_mes.value = "";
   document.F1.fec_emision_ano.value = "";
   document.F1.fec_emision_input_2_dia.value = "";
   document.F1.fec_emision_input_2_mes.value = "";
   document.F1.fec_emision_input_2_ano.value = "";
   document.F1.fec_recep_dia.value = "";
   document.F1.fec_recep_mes.value = "";
   document.F1.fec_recep_ano.value = "";
   document.F1.fec_recep_input_2_dia.value = "";
   document.F1.fec_recep_input_2_mes.value = "";
   document.F1.fec_recep_input_2_ano.value = "";
   nm_campos_between(document.getElementById('id_vis_fec_emision'), document.F1.fec_emision_cond, 'fec_emision');
   nm_campos_between(document.getElementById('id_vis_fec_recep'), document.F1.fec_recep_cond, 'fec_recep');
 }
function nm_tabula(obj, tam, cond)
{
   if (obj.value.length == tam)
   {
       for (i=0; i < document.F1.elements.length;i++)
       {
            if (document.F1.elements[i].name == obj.name)
            {
                i++;
                campo = document.F1.elements[i].name;
                campo2 = campo.lastIndexOf('_input_2');
                if (document.F1.elements[i].type == 'text' && (campo2 == -1 || cond == 'bw'))
                {
                    eval('document.F1.' + campo + '.focus()');
                }
                break;
            }
       }
   }
}
 function SC_carga_evt_jquery()
 {
    $('#SC_fec_emision_dia').bind('change', function() {sc_grid_cuadratura_sii_valida_dia(this)});
    $('#SC_fec_emision_input_2_dia').bind('change', function() {sc_grid_cuadratura_sii_valida_dia(this)});
    $('#SC_fec_emision_input_2_mes').bind('change', function() {sc_grid_cuadratura_sii_valida_mes(this)});
    $('#SC_fec_emision_mes').bind('change', function() {sc_grid_cuadratura_sii_valida_mes(this)});
    $('#SC_fec_recep_dia').bind('change', function() {sc_grid_cuadratura_sii_valida_dia(this)});
    $('#SC_fec_recep_input_2_dia').bind('change', function() {sc_grid_cuadratura_sii_valida_dia(this)});
    $('#SC_fec_recep_input_2_mes').bind('change', function() {sc_grid_cuadratura_sii_valida_mes(this)});
    $('#SC_fec_recep_mes').bind('change', function() {sc_grid_cuadratura_sii_valida_mes(this)});
 }
 function sc_grid_cuadratura_sii_valida_dia(obj)
 {
     if (obj.value < 1 || obj.value > 31)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_iday'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_cuadratura_sii_valida_mes(obj)
 {
     if (obj.value < 1 || obj.value > 12)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_mnth'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_cuadratura_sii_valida_hora(obj)
 {
     if (obj.value < 0 || obj.value > 23)
     {
         if (confirm (Nm_erro['lang_jscr_ivtm'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_cuadratura_sii_valida_min(obj)
 {
     if (obj.value < 0 || obj.value > 59)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_mint'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_cuadratura_sii_valida_seg(obj)
 {
     if (obj.value < 0 || obj.value > 59)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_secd'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_cuadratura_sii_valida_cic(obj)
 {
     var x        = 0;
     var y        = 0;
     var soma     = 0;
     var resto    = 0;
     var CicIn    = obj.value;
     var Cic_calc = 0;
     CicIn = CicIn.replace(/[.]/g, '');
     CicIn = CicIn.replace(/[-]/g, '');
     if (CicIn.length == 0)
     {
         return true;
     }
     Cic_calc = CicIn.substring(0, 9);
     x = CicIn.substring(0, 1);
     for (y = 1; y < 11; y++)
     {
         if (CicIn.substr(y , 1) != x)
         {
             break;
         }
         else
         {
              soma++;
         }
     }
     if (soma == 10) 
     {
         Cic_calc = "1";
     }
     soma = 0;
     y = 10;
     for (x = 0 ; x < 9 ; x++)
     {
         soma = soma + (parseInt(Cic_calc.substr(x , 1)) * y );
         y--;
     }
     soma = soma * 10;
     resto = soma % 11;
     if (resto == 10)
     {
         resto = 0;
     }
     Cic_calc = Cic_calc + resto.toString();
     x = 0;
     y = 11;
     soma = 0;
     for (x = 0 ; x < 10 ; x++)
     {
         soma = soma + (parseInt(Cic_calc.substr(x , 1)) * y );
         y--;
     }
     soma = soma * 10;
     resto = soma % 11;
     if (resto == 10)
     {
          resto = 0;
     }
     Cic_calc = Cic_calc + resto.toString();
     if (Cic_calc != CicIn)
     {
         if (confirm ("CIC " + SC_crit_inv + " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
            return false;
         }
     }
     return true;
 }
 function sc_grid_cuadratura_sii_valida_cnpj(obj)
 {
     var x         = 0;
     var y         = 5;
     var soma      = 0;
     var resto     = 0;
     var Cnpj_calc = 0;
     var CnpjIn    = obj.value;
     CnpjIn = CnpjIn.replace(/[.]/g, '');
     CnpjIn = CnpjIn.replace(/[-]/g, '');
     CnpjIn = CnpjIn.replace(/[/]/g, '');
     if (CnpjIn.length == 0)
     {
         return true;
     }
     Cnpj_calc = CnpjIn.substring(0, 12);
     for (x = 0 ; x < 12 ; x++)
     {
         soma = soma + (parseInt(Cnpj_calc.substr(x , 1)) * y );
         y--;
         if (y == 1)
         {
             y = 9;
         }
     }
     soma = soma * 10;
     resto = soma % 11;
     if (resto == 10)
     {
         resto = 0;
     }
     Cnpj_calc = Cnpj_calc + resto.toString();
     x = 0;
     y = 6;
     soma = 0;
     for (x = 0 ; x < 13 ; x++)
     {
         soma = soma + (parseInt(Cnpj_calc.substr(x , 1)) * y );
         y--;
         if (y == 1)
         {
             y = 9;
         }
     }
     soma = soma * 10;
     resto = soma % 11;
     if (resto == 10)
     {
          resto = 0;
     }
     Cnpj_calc = Cnpj_calc + resto.toString();
     if (Cnpj_calc != CnpjIn || CnpjIn == "00000000000000")
     {
         if (confirm ("CNPJ " + SC_crit_inv + " "  +  Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
            return false;
         }
     }
     return true;
 }
 function sc_grid_cuadratura_sii_valida_ciccnpj(obj)
 {
     var CnpjIn = obj.value;
     CnpjIn = CnpjIn.replace(/[.]/g, '');
     CnpjIn = CnpjIn.replace(/[-]/g, '');
     CnpjIn = CnpjIn.replace(/[/]/g, '');
     if (CnpjIn.length <= 11)
     {
         return sc_grid_cuadratura_sii_valida_cic(obj);
     }
     else
     {
         return sc_grid_cuadratura_sii_valida_cnpj(obj);
     }
 }
 function sc_grid_cuadratura_sii_valida_cep(obj)
 {
     var CepIn = obj.value;
     CepIn = CepIn.replace(/[-]/g, '');
     if (CepIn.length != 0 && (CepIn.length < 8 || CepIn == '00000000'))
     {
         if (confirm ("CEP " + SC_crit_inv + " "  +  Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
            return false;
         }
     }
     return true;
 }
</SCRIPT>
</BODY>
</HTML>
<?php
   }

   /**
    * @access  public
    * @param  string  $NM_operador  $this->Ini->Nm_lang['pesq_global_NM_operador']
    * @param  array  $nmgp_tab_label  
    */
   function inicializa_vars()
   {
      global $NM_operador, $nmgp_tab_label;

      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/");  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1);  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_cuadratura_sii.php";
      $this->Campos_Mens_erro = ""; 
      $this->nm_data = new nm_data("es");
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] = "";
      if (!empty($nmgp_tab_label))
      {
         $nm_tab_campos = explode("?@?", $nmgp_tab_label);
         $nmgp_tab_label = array();
         foreach ($nm_tab_campos as $cada_campo)
         {
             $parte_campo = explode("?#?", $cada_campo);
             $nmgp_tab_label[$parte_campo[0]] = $parte_campo[1];
         }
      }
      $this->comando        = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_orig'];
      $this->comando_sum    = "";
      $this->comando_filtro = "";
      $this->comando_ini    = "ini";
      $this->comando_fim    = "";
      $this->NM_operador    = (isset($NM_operador) && ("and" == strtolower($NM_operador) || "or" == strtolower($NM_operador))) ? $NM_operador : "and";
   }

   /**
    * @access  public
    */
   function trata_campos()
   {
      global $tipo_dte_cond, $tipo_dte,
             $folio_dte_cond, $folio_dte,
             $rut_emisor_cond, $rut_emisor,
             $fec_emision_cond, $fec_emision, $fec_emision_dia, $fec_emision_mes, $fec_emision_ano, $fec_emision_input_2_dia, $fec_emision_input_2_mes, $fec_emision_input_2_ano,
             $fec_recep_cond, $fec_recep, $fec_recep_dia, $fec_recep_mes, $fec_recep_ano, $fec_recep_input_2_dia, $fec_recep_input_2_mes, $fec_recep_input_2_ano, $nmgp_tab_label;

      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_gp_limpa.php", "F", "nm_limpa_valor") ; 
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_conv_dados.php", "F", "nm_conv_limpa_dado") ; 
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_edit.php", "F", "nmgp_Form_Num_Val") ; 
      $tipo_dte_cond_salva = $tipo_dte_cond; 
      if (!isset($tipo_dte_input_2) || $tipo_dte_input_2 == "")
      {
          $tipo_dte_input_2 = $tipo_dte;
      }
      $folio_dte_cond_salva = $folio_dte_cond; 
      if (!isset($folio_dte_input_2) || $folio_dte_input_2 == "")
      {
          $folio_dte_input_2 = $folio_dte;
      }
      $rut_emisor_cond_salva = $rut_emisor_cond; 
      if (!isset($rut_emisor_input_2) || $rut_emisor_input_2 == "")
      {
          $rut_emisor_input_2 = $rut_emisor;
      }
      $fec_emision_cond_salva = $fec_emision_cond; 
      if (!isset($fec_emision_input_2_dia) || $fec_emision_input_2_dia == "")
      {
          $fec_emision_input_2_dia = $fec_emision_dia;
      }
      if (!isset($fec_emision_input_2_mes) || $fec_emision_input_2_mes == "")
      {
          $fec_emision_input_2_mes = $fec_emision_mes;
      }
      if (!isset($fec_emision_input_2_ano) || $fec_emision_input_2_ano == "")
      {
          $fec_emision_input_2_ano = $fec_emision_ano;
      }
      if (!isset($fec_emision_input_2) || $fec_emision_input_2 == "")
      {
          $fec_emision_input_2 = $fec_emision;
      }
      $fec_recep_cond_salva = $fec_recep_cond; 
      if (!isset($fec_recep_input_2_dia) || $fec_recep_input_2_dia == "")
      {
          $fec_recep_input_2_dia = $fec_recep_dia;
      }
      if (!isset($fec_recep_input_2_mes) || $fec_recep_input_2_mes == "")
      {
          $fec_recep_input_2_mes = $fec_recep_mes;
      }
      if (!isset($fec_recep_input_2_ano) || $fec_recep_input_2_ano == "")
      {
          $fec_recep_input_2_ano = $fec_recep_ano;
      }
      if (!isset($fec_recep_input_2) || $fec_recep_input_2 == "")
      {
          $fec_recep_input_2 = $fec_recep;
      }
      if ($folio_dte_cond != "in")
      {
          nm_limpa_numero($folio_dte, $_SESSION['scriptcase']['reg_conf']['grup_num']) ; 
      }
      else
      {
          $Nm_sc_valores = explode(",", $folio_dte);
          foreach ($Nm_sc_valores as $II => $Nm_sc_valor)
          {
              $Nm_sc_valor = trim($Nm_sc_valor);
              nm_limpa_numero($Nm_sc_valor, $_SESSION['scriptcase']['reg_conf']['grup_num']); 
              $Nm_sc_valores[$II] = $Nm_sc_valor;
          }
          $folio_dte = implode(",", $Nm_sc_valores);
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']  = array(); 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['tipo_dte'] = $tipo_dte; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['tipo_dte_cond'] = $tipo_dte_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['folio_dte'] = $folio_dte; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['folio_dte_cond'] = $folio_dte_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['rut_emisor'] = $rut_emisor; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['rut_emisor_cond'] = $rut_emisor_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_dia'] = $fec_emision_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_mes'] = $fec_emision_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_ano'] = $fec_emision_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2_dia'] = $fec_emision_input_2_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2_mes'] = $fec_emision_input_2_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2_ano'] = $fec_emision_input_2_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_cond'] = $fec_emision_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_dia'] = $fec_recep_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_mes'] = $fec_recep_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_ano'] = $fec_recep_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2_dia'] = $fec_recep_input_2_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2_mes'] = $fec_recep_input_2_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2_ano'] = $fec_recep_input_2_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_cond'] = $fec_recep_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['NM_operador'] = $this->NM_operador; 
      $Conteudo = $tipo_dte;
      if (strpos($Conteudo, "##@@") !== false)
      {
          $Conteudo = substr($Conteudo, strpos($Conteudo, "##@@") + 4);
      }
      $this->cmp_formatado['tipo_dte'] = $Conteudo;
      $Conteudo = $folio_dte;
      if (strtoupper($folio_dte_cond) != "II" && strtoupper($folio_dte_cond) != "QP" && strtoupper($folio_dte_cond) != "NP" && strtoupper($folio_dte_cond) != "IN") 
      { 
          nmgp_Form_Num_Val($Conteudo, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "1", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
      } 
      $this->cmp_formatado['folio_dte'] = $Conteudo;
      $Conteudo = $rut_emisor;
      $this->cmp_formatado['rut_emisor'] = $Conteudo;
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fec_emision_dia))
      {
          $Conteudo .= (strlen($fec_emision_dia) == 2) ? $fec_emision_dia : "0" . $fec_emision_dia;
          $Formato  .= "DD";
      }
      if (!empty($fec_emision_mes))
      {
          $Conteudo .= (strlen($fec_emision_mes) == 2) ? $fec_emision_mes : "0" . $fec_emision_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fec_emision_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fec_emision'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision'] = $Conteudo; 
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fec_emision_input_2_dia))
      {
          $Conteudo .= (strlen($fec_emision_input_2_dia) == 2) ? $fec_emision_input_2_dia : "0" . $fec_emision_input_2_dia;
          $Formato  .= "DD";
      }
      if (!empty($fec_emision_input_2_mes))
      {
          $Conteudo .= (strlen($fec_emision_input_2_mes) == 2) ? $fec_emision_input_2_mes : "0" . $fec_emision_input_2_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fec_emision_input_2_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fec_emision_Input_2'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2'] = $Conteudo; 
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fec_recep_dia))
      {
          $Conteudo .= (strlen($fec_recep_dia) == 2) ? $fec_recep_dia : "0" . $fec_recep_dia;
          $Formato  .= "DD";
      }
      if (!empty($fec_recep_mes))
      {
          $Conteudo .= (strlen($fec_recep_mes) == 2) ? $fec_recep_mes : "0" . $fec_recep_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fec_recep_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fec_recep'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep'] = $Conteudo; 
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fec_recep_input_2_dia))
      {
          $Conteudo .= (strlen($fec_recep_input_2_dia) == 2) ? $fec_recep_input_2_dia : "0" . $fec_recep_input_2_dia;
          $Formato  .= "DD";
      }
      if (!empty($fec_recep_input_2_mes))
      {
          $Conteudo .= (strlen($fec_recep_input_2_mes) == 2) ? $fec_recep_input_2_mes : "0" . $fec_recep_input_2_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fec_recep_input_2_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fec_recep_Input_2'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2'] = $Conteudo; 

      //----- $tipo_dte
      if (isset($tipo_dte))
      {
         $this->monta_condicao("tipo_dte", $tipo_dte_cond, $tipo_dte, "", "tipo_dte");
      }

      //----- $folio_dte
      if (isset($folio_dte) || $folio_dte_cond == "nu" || $folio_dte_cond == "nn")
      {
         $this->monta_condicao("folio_dte", $folio_dte_cond, $folio_dte, "", "folio_dte");
      }

      //----- $rut_emisor
      if (isset($rut_emisor) || $rut_emisor_cond == "nu" || $rut_emisor_cond == "nn")
      {
         $this->monta_condicao("rut_emisor", $rut_emisor_cond, $rut_emisor, "", "rut_emisor");
      }

      //----- $fec_emision
      $nm_tp_dado = "DATETIME";
      $nm_format_db = "YYYY-MM-DD HH:II:SS";
      $condicao = strtoupper($fec_emision_cond);
      $array_fec_emision = array();
      $array_fec_emision2 = array();
      $nm_psq_dt1 = $fec_emision_dia . $fec_emision_mes . $fec_emision_ano . $fec_emision_hor . $fec_emision_min . $fec_emision_seg;
      $nm_psq_dt_inf  = ("" == $fec_emision_ano) ? "YYYY" : "$fec_emision_ano";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fec_emision_mes) ? "MM" : "$fec_emision_mes";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fec_emision_dia) ? "DD"   : "$fec_emision_dia";
      $nm_psq_dt_inf .= " ";
      $nm_psq_dt_inf .= ("" == $fec_emision_hor) ? "HH" : "$fec_emision_hor";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fec_emision_min) ? "II" : "$fec_emision_min";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fec_emision_seg) ? "SS" : "$fec_emision_seg";
      nm_conv_form_data_hora($nm_psq_dt_inf, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      $array_fec_emision["dia"] = ("" == $fec_emision_dia) ? "__"   : "$fec_emision_dia";
      $array_fec_emision["mes"] = ("" == $fec_emision_mes) ? "__"   : "$fec_emision_mes";
      $array_fec_emision["ano"] = ("" == $fec_emision_ano) ? "____" : "$fec_emision_ano";
      $array_fec_emision["hor"] = ("" == $fec_emision_hor) ? "__" : "$fec_emision_hor";
      $array_fec_emision["min"] = ("" == $fec_emision_min) ? "__" : "$fec_emision_min";
      $array_fec_emision["seg"] = ("" == $fec_emision_seg) ? "__" : "$fec_emision_seg";
      $this->NM_data_qp = $array_fec_emision;
      $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
      nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
//
      if ($condicao == "BW")
      {
          $array_fec_emision2["dia"] = ("" == $fec_emision_input_2_dia) ? "__"   : "$fec_emision_input_2_dia";
          $array_fec_emision2["mes"] = ("" == $fec_emision_input_2_mes) ? "__"   : "$fec_emision_input_2_mes";
          $array_fec_emision2["ano"] = ("" == $fec_emision_input_2_ano) ? "____" : "$fec_emision_input_2_ano";
          $array_fec_emision2["hor"] = ("" == $fec_emision_input_2_hor) ? "__" : "$fec_emision_input_2_hor";
          $array_fec_emision2["min"] = ("" == $fec_emision_input_2_min) ? "__" : "$fec_emision_input_2_min";
          $array_fec_emision2["seg"] = ("" == $fec_emision_input_2_seg) ? "__" : "$fec_emision_input_2_seg";
          $this->data_menor($array_fec_emision);
          $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
          nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
          $this->data_maior($array_fec_emision2);
          $nm_dt_compl_2 = $array_fec_emision2["ano"] . "-" . $array_fec_emision2["mes"] . "-" . $array_fec_emision2["dia"] . " " . $array_fec_emision2["hor"] . ":" . $array_fec_emision2["min"] . ":" . $array_fec_emision2["seg"];
          nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      }
      else
      {
          $array_fec_emision2 = $array_fec_emision;
      }
      if (FALSE !== strpos($nm_dt_compl, "__"))
      {
         if ($condicao == "II")
         {
             $condicao = "QP";
         }
         elseif ($condicao == "DF")
         {
             $this->data_menor($array_fec_emision);
             $this->data_maior($array_fec_emision2);
             $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fec_emision2["ano"] . "-" . $array_fec_emision2["mes"] . "-" . $array_fec_emision2["dia"] . " " . $array_fec_emision2["hor"] . ":" . $array_fec_emision2["min"] . ":" . $array_fec_emision2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "DF";
         }
         elseif ($condicao == "EQ")
         {
             $this->data_menor($array_fec_emision);
             $this->data_maior($array_fec_emision2);
             $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fec_emision2["ano"] . "-" . $array_fec_emision2["mes"] . "-" . $array_fec_emision2["dia"] . " " . $array_fec_emision2["hor"] . ":" . $array_fec_emision2["min"] . ":" . $array_fec_emision2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "EQ";
         }
         elseif ($condicao == "GT")
         {
             $this->data_maior($array_fec_emision);
             $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "GE")
         {
             $this->data_menor($array_fec_emision);
             $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LT")
         {
             $this->data_menor($array_fec_emision);
             $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LE")
         {
             $this->data_maior($array_fec_emision);
             $nm_dt_compl = $array_fec_emision["ano"] . "-" . $array_fec_emision["mes"] . "-" . $array_fec_emision["dia"] . " " . $array_fec_emision["hor"] . ":" . $array_fec_emision["min"] . ":" . $array_fec_emision["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
      }
      if ($condicao == "QP")
      {
          $nm_dt_compl = $nm_psq_dt_inf;
          $nm_dt_compl_2 = "";
      }
      if (!empty($nm_dt_compl))
      {
          $this->limpa_dt_hor_pesq($nm_dt_compl);
      }
      if (!empty($nm_dt_compl_2))
      {
          $this->limpa_dt_hor_pesq($nm_dt_compl_2);
      }
      if (!empty($nm_psq_dt1) || $condicao == "NU" || $condicao == "NN")
      {
          $this->monta_condicao("fec_emision", $condicao, trim($nm_dt_compl), trim($nm_dt_compl_2), "fec_emision", $nm_tp_dado);
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision']   = trim($nm_dt_compl); 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_emision_input_2'] = trim($nm_dt_compl_2); 
      }
      $nm_tp_dado = "";

      //----- $fec_recep
      $nm_tp_dado = "DATETIME";
      $nm_format_db = "YYYY-MM-DD HH:II:SS";
      $condicao = strtoupper($fec_recep_cond);
      $array_fec_recep = array();
      $array_fec_recep2 = array();
      $nm_psq_dt1 = $fec_recep_dia . $fec_recep_mes . $fec_recep_ano . $fec_recep_hor . $fec_recep_min . $fec_recep_seg;
      $nm_psq_dt_inf  = ("" == $fec_recep_ano) ? "YYYY" : "$fec_recep_ano";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fec_recep_mes) ? "MM" : "$fec_recep_mes";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fec_recep_dia) ? "DD"   : "$fec_recep_dia";
      $nm_psq_dt_inf .= " ";
      $nm_psq_dt_inf .= ("" == $fec_recep_hor) ? "HH" : "$fec_recep_hor";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fec_recep_min) ? "II" : "$fec_recep_min";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fec_recep_seg) ? "SS" : "$fec_recep_seg";
      nm_conv_form_data_hora($nm_psq_dt_inf, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      $array_fec_recep["dia"] = ("" == $fec_recep_dia) ? "__"   : "$fec_recep_dia";
      $array_fec_recep["mes"] = ("" == $fec_recep_mes) ? "__"   : "$fec_recep_mes";
      $array_fec_recep["ano"] = ("" == $fec_recep_ano) ? "____" : "$fec_recep_ano";
      $array_fec_recep["hor"] = ("" == $fec_recep_hor) ? "__" : "$fec_recep_hor";
      $array_fec_recep["min"] = ("" == $fec_recep_min) ? "__" : "$fec_recep_min";
      $array_fec_recep["seg"] = ("" == $fec_recep_seg) ? "__" : "$fec_recep_seg";
      $this->NM_data_qp = $array_fec_recep;
      $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
      nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
//
      if ($condicao == "BW")
      {
          $array_fec_recep2["dia"] = ("" == $fec_recep_input_2_dia) ? "__"   : "$fec_recep_input_2_dia";
          $array_fec_recep2["mes"] = ("" == $fec_recep_input_2_mes) ? "__"   : "$fec_recep_input_2_mes";
          $array_fec_recep2["ano"] = ("" == $fec_recep_input_2_ano) ? "____" : "$fec_recep_input_2_ano";
          $array_fec_recep2["hor"] = ("" == $fec_recep_input_2_hor) ? "__" : "$fec_recep_input_2_hor";
          $array_fec_recep2["min"] = ("" == $fec_recep_input_2_min) ? "__" : "$fec_recep_input_2_min";
          $array_fec_recep2["seg"] = ("" == $fec_recep_input_2_seg) ? "__" : "$fec_recep_input_2_seg";
          $this->data_menor($array_fec_recep);
          $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
          nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
          $this->data_maior($array_fec_recep2);
          $nm_dt_compl_2 = $array_fec_recep2["ano"] . "-" . $array_fec_recep2["mes"] . "-" . $array_fec_recep2["dia"] . " " . $array_fec_recep2["hor"] . ":" . $array_fec_recep2["min"] . ":" . $array_fec_recep2["seg"];
          nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      }
      else
      {
          $array_fec_recep2 = $array_fec_recep;
      }
      if (FALSE !== strpos($nm_dt_compl, "__"))
      {
         if ($condicao == "II")
         {
             $condicao = "QP";
         }
         elseif ($condicao == "DF")
         {
             $this->data_menor($array_fec_recep);
             $this->data_maior($array_fec_recep2);
             $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fec_recep2["ano"] . "-" . $array_fec_recep2["mes"] . "-" . $array_fec_recep2["dia"] . " " . $array_fec_recep2["hor"] . ":" . $array_fec_recep2["min"] . ":" . $array_fec_recep2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "DF";
         }
         elseif ($condicao == "EQ")
         {
             $this->data_menor($array_fec_recep);
             $this->data_maior($array_fec_recep2);
             $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fec_recep2["ano"] . "-" . $array_fec_recep2["mes"] . "-" . $array_fec_recep2["dia"] . " " . $array_fec_recep2["hor"] . ":" . $array_fec_recep2["min"] . ":" . $array_fec_recep2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "EQ";
         }
         elseif ($condicao == "GT")
         {
             $this->data_maior($array_fec_recep);
             $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "GE")
         {
             $this->data_menor($array_fec_recep);
             $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LT")
         {
             $this->data_menor($array_fec_recep);
             $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LE")
         {
             $this->data_maior($array_fec_recep);
             $nm_dt_compl = $array_fec_recep["ano"] . "-" . $array_fec_recep["mes"] . "-" . $array_fec_recep["dia"] . " " . $array_fec_recep["hor"] . ":" . $array_fec_recep["min"] . ":" . $array_fec_recep["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
      }
      if ($condicao == "QP")
      {
          $nm_dt_compl = $nm_psq_dt_inf;
          $nm_dt_compl_2 = "";
      }
      if (!empty($nm_dt_compl))
      {
          $this->limpa_dt_hor_pesq($nm_dt_compl);
      }
      if (!empty($nm_dt_compl_2))
      {
          $this->limpa_dt_hor_pesq($nm_dt_compl_2);
      }
      if (!empty($nm_psq_dt1) || $condicao == "NU" || $condicao == "NN")
      {
          $this->monta_condicao("fec_recep", $condicao, trim($nm_dt_compl), trim($nm_dt_compl_2), "fec_recep", $nm_tp_dado);
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep']   = trim($nm_dt_compl); 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']['fec_recep_input_2'] = trim($nm_dt_compl_2); 
      }
      $nm_tp_dado = "";
   }

   /**
    * @access  public
    */
   function finaliza_resultado()
   {
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca']) && $_SESSION['scriptcase']['charset'] != "UTF-8")
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca'] = NM_conv_charset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['campos_busca'], "UTF-8", $_SESSION['scriptcase']['charset']);
      }

      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq_lookup']  = $this->comando_sum . $this->comando_fim;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq']         = $this->comando . $this->comando_fim;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['opcao']              = "pesq";
      if ("" == $this->comando_filtro)
      {
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq_filtro'] = "";
      }
      else
      {
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq_filtro'] = " (" . $this->comando_filtro . ")";
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq'] != $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq_ant'])
      {
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['cond_pesq'] .= $this->NM_operador;
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['contr_array_resumo'] = "NAO";
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['contr_total_geral']  = "NAO";
         unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['tot_geral']);
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['where_pesq_ant'] = $this->comando . $this->comando_fim;
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_cuadratura_sii']['fast_search']);

      echo "<script type=\"text/javascript\">"; 
      echo "parent.NM_apaga_erro()";
      echo "</script>"; 
      $this->retorna_pesq();
   }
   function jqueryCalendarDtFormat($sFormat, $sSep)
   {
       $sFormat = chunk_split(str_replace('yyyy', 'yy', $sFormat), 2, $sSep);

       if ($sSep == substr($sFormat, -1))
       {
           $sFormat = substr($sFormat, 0, -1);
       }

       return $sFormat;
   } // jqueryCalendarDtFormat

   function jqueryCalendarWeekInit($sDay)
   {
       switch ($sDay) {
           case 'MO': return 1; break;
           case 'TU': return 2; break;
           case 'WE': return 3; break;
           case 'TH': return 4; break;
           case 'FR': return 5; break;
           case 'SA': return 6; break;
           default  : return 7; break;
       }
   } // jqueryCalendarWeekInit

   
   function css_obj_select_ajax($Obj)
   {
      switch ($Obj)
      {
         case "tipo_dte" : return ('class="scFilterObjectOdd"'); break;
         default       : return ("");
      }
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
}

?>
