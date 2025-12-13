<?php

class grid_public_v_dte_pesq
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
   function grid_public_v_dte_pesq()
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
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['path_libs_php'] = $this->Ini->path_lib_php;
      $this->Img_sep_filter = "/" . trim($str_toolbar_separator);
      $this->Block_img_col  = trim($str_block_col);
      $this->Block_img_exp  = trim($str_block_exp);
      $this->Bubble_tail    = trim($str_bubble_tail);
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_gp_config_btn.php", "F", "nmButtonOutput"); 
      if ($this->NM_ajax_flag)
      {
          ob_start();
          $this->processa_ajax();
          $this->Db->Close(); 
          $Temp = ob_get_clean();
          exit;
      }
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['prim_vez']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['prim_vez'] = "S";
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
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "igual";
   }

   function processa_ajax()
   {
      global 
      $tipo_docu_cond, $tipo_docu,
             $folio_dte_cond, $folio_dte,
             $fec_emi_dte_cond, $fec_emi_dte, $fec_emi_dte_dia, $fec_emi_dte_mes, $fec_emi_dte_ano, $fec_emi_dte_input_2_dia, $fec_emi_dte_input_2_mes, $fec_emi_dte_input_2_ano,
             $fech_carg_cond, $fech_carg, $fech_carg_dia, $fech_carg_mes, $fech_carg_ano, $fech_carg_input_2_dia, $fech_carg_input_2_mes, $fech_carg_input_2_ano,
             $est_xdte_cond, $est_xdte,
             $rut_rec_dte_cond, $rut_rec_dte,
      $NM_filters, $NM_filters_del, $nmgp_save_name, $NM_operador, $nmgp_save_option, $bprocessa, $Ajax_label, $Ajax_val, $Campo_bi, $Opc_bi;
      $this->init();
      if (isset($this->NM_ajax_info['param']['tipo_docu_cond']))
      {
          $tipo_docu_cond = $this->NM_ajax_info['param']['tipo_docu_cond'];
      }
      if (isset($this->NM_ajax_info['param']['tipo_docu']))
      {
          $tipo_docu = $this->NM_ajax_info['param']['tipo_docu'];
      }
      if (isset($this->NM_ajax_info['param']['folio_dte_cond']))
      {
          $folio_dte_cond = $this->NM_ajax_info['param']['folio_dte_cond'];
      }
      if (isset($this->NM_ajax_info['param']['folio_dte']))
      {
          $folio_dte = $this->NM_ajax_info['param']['folio_dte'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte_cond']))
      {
          $fec_emi_dte_cond = $this->NM_ajax_info['param']['fec_emi_dte_cond'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte']))
      {
          $fec_emi_dte = $this->NM_ajax_info['param']['fec_emi_dte'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte_dia']))
      {
          $fec_emi_dte_dia = $this->NM_ajax_info['param']['fec_emi_dte_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte_mes']))
      {
          $fec_emi_dte_mes = $this->NM_ajax_info['param']['fec_emi_dte_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte_ano']))
      {
          $fec_emi_dte_ano = $this->NM_ajax_info['param']['fec_emi_dte_ano'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte_input_2_dia']))
      {
          $fec_emi_dte_input_2_dia = $this->NM_ajax_info['param']['fec_emi_dte_input_2_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte_input_2_mes']))
      {
          $fec_emi_dte_input_2_mes = $this->NM_ajax_info['param']['fec_emi_dte_input_2_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fec_emi_dte_input_2_ano']))
      {
          $fec_emi_dte_input_2_ano = $this->NM_ajax_info['param']['fec_emi_dte_input_2_ano'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg_cond']))
      {
          $fech_carg_cond = $this->NM_ajax_info['param']['fech_carg_cond'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg']))
      {
          $fech_carg = $this->NM_ajax_info['param']['fech_carg'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg_dia']))
      {
          $fech_carg_dia = $this->NM_ajax_info['param']['fech_carg_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg_mes']))
      {
          $fech_carg_mes = $this->NM_ajax_info['param']['fech_carg_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg_ano']))
      {
          $fech_carg_ano = $this->NM_ajax_info['param']['fech_carg_ano'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg_input_2_dia']))
      {
          $fech_carg_input_2_dia = $this->NM_ajax_info['param']['fech_carg_input_2_dia'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg_input_2_mes']))
      {
          $fech_carg_input_2_mes = $this->NM_ajax_info['param']['fech_carg_input_2_mes'];
      }
      if (isset($this->NM_ajax_info['param']['fech_carg_input_2_ano']))
      {
          $fech_carg_input_2_ano = $this->NM_ajax_info['param']['fech_carg_input_2_ano'];
      }
      if (isset($this->NM_ajax_info['param']['est_xdte_cond']))
      {
          $est_xdte_cond = $this->NM_ajax_info['param']['est_xdte_cond'];
      }
      if (isset($this->NM_ajax_info['param']['est_xdte']))
      {
          $est_xdte = $this->NM_ajax_info['param']['est_xdte'];
      }
      if (isset($this->NM_ajax_info['param']['rut_rec_dte_cond']))
      {
          $rut_rec_dte_cond = $this->NM_ajax_info['param']['rut_rec_dte_cond'];
      }
      if (isset($this->NM_ajax_info['param']['rut_rec_dte']))
      {
          $rut_rec_dte = $this->NM_ajax_info['param']['rut_rec_dte'];
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
                  $Opt_filter .= "<option value=\"\">" . grid_public_v_dte_pack_protect_string($Nome_filter) . "</option>\r\n";
              }
              $Opt_filter .= "<option value=\"" . grid_public_v_dte_pack_protect_string($Tipo_filter[0]) . "\">.." . grid_public_v_dte_pack_protect_string($Cada_filter) .  "</option>\r\n";
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
                  $Opt_filter .= "<option value=\"\">" .  grid_public_v_dte_pack_protect_string($Nome_filter) . "</option>\r\n";
              }
              $Opt_filter .= "<option value=\"" . grid_public_v_dte_pack_protect_string($Tipo_filter[0]) . "\">.." . grid_public_v_dte_pack_protect_string($Cada_filter) .  "</option>\r\n";
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
      if (in_array("tipo_docu", $nmgp_refresh_fields) || $bprocessa == "filter_save")
      {
          $nmgp_def_dados = $this->lookup_ajax_tipo_docu();
          $this->NM_ajax_info['fldList']['tipo_docu'] = array(
                     'type'    => 'select',
                     'optList' => $nmgp_def_dados,
                     'valList' => $Ajax_val,
                     );
      }
   }
   function lookup_ajax_tipo_docu()
   {
      global $tipo_docu, $Ajax_label, $Ajax_val;
      $tmp_pos = strpos($tipo_docu, "##@@");
      if ($tmp_pos !== false)
      {
          $tipo_docu = substr($tipo_docu, 0, $tmp_pos);
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
            if (trim($rs->fields[0]) == $tipo_docu)
            {
                $Ajax_val[]   = $cmp1 . "##@@" . $cmp2;
                $Ajax_label[] = $cmp2;
            }
            $cmp1 = grid_public_v_dte_pack_protect_string($cmp1);
            $cmp2 = grid_public_v_dte_pack_protect_string($cmp2);
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

      $sel_ret_ajax  = "      <SELECT " . $this->css_obj_select_ajax('tipo_docu') . " name=\"tipo_docu\"  size=\"1\">\r\n";
      $sel_ret_ajax .= "       <OPTION value=\"\">" . grid_public_v_dte_pack_protect_string('Seleccione Tipo DTE') . "</OPTION>\r\n";
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
      $Nm_numeric[] = "codi_empr";$Nm_numeric[] = "tipo_docu";$Nm_numeric[] = "folio_dte";$Nm_numeric[] = "rut_emis_dte";$Nm_numeric[] = "rut_rec_dte";$Nm_numeric[] = "mntneto_dte";$Nm_numeric[] = "mnt_exen_dte";$Nm_numeric[] = "tasa_iva_dte";$Nm_numeric[] = "iva_dte";$Nm_numeric[] = "mont_tot_dte";$Nm_numeric[] = "valo_pag_dte";$Nm_numeric[] = "est_xdte";$Nm_numeric[] = "coderr_xdte";$Nm_numeric[] = "track_id";
      $campo_join = strtolower(str_replace(".", "_", $nome));
      if (in_array($campo_join, $Nm_numeric))
      {
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['decimal_db'] == ".")
         {
            $nm_aspas = "";
         }
         if ($condicao != "in")
         {
            if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['decimal_db'] == ".")
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
      $Nm_datas[] = "fec_emi_dte";$Nm_datas[] = "fec_venc_dte";$Nm_datas[] = "fech_carg";
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
         $nome_sum = "\"public\".v_dte.$nome";
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
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "II":     // 
               $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " like '" . $campo . "%'";
               $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " like '" . $campo . "%'";
               $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " like '" . $campo . "%'";
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_strt'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
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
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_like'] . " " . $NM_cond . "##*@@";
                   }
               }
               else
               {
                   $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower ." like '%" . $campo . "%'";
                   $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " like '%" . $campo . "%'";
                   $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " like '%" . $campo . "%'";
                   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_like'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
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
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_not_like'] . " " . $NM_cond . "##*@@";
                   }
               }
               else
               {
                   $this->comando        .= $nm_ini_lower . $nome . $nm_fim_lower ." not like '%" . $campo . "%'";
                   $this->comando_sum    .= $nm_ini_lower . $nome_sum . $nm_fim_lower . " not like '%" . $campo . "%'";
                   $this->comando_filtro .= $nm_ini_lower . $nome . $nm_fim_lower . " not like '%" . $campo . "%'";
                   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_not_like'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
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
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_diff'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "GT":     // 
               $this->comando        .= " $nome > " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum > " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome > " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $nmgp_lang['pesq_cond_maior'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "GE":     // 
               $this->comando        .= " $nome >= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum >= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome >= " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_grtr_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "LT":     // 
               $this->comando        .= " $nome < " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum < " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome < " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_less'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "LE":     // 
               $this->comando        .= " $nome <= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_sum    .= " $nome_sum <= " . $nm_aspas . $campo . $nm_aspas;
               $this->comando_filtro .= " $nome <= " . $nm_aspas . $campo . $nm_aspas;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_less_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "BW":     // 
               if ($tp_campo == "DTDF" || $tp_campo == "DATEDF" || $tp_campo == "DATETIMEDF")
               {
                   $this->comando        .= " $nome not between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_sum    .= " $nome_sum not between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_filtro .= " $nome not between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_diff'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
               }
               else
               {
                   $this->comando        .= " $nome between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_sum    .= " $nome_sum between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   $this->comando_filtro .= " $nome between " . $nm_aspas . $campo . $nm_aspas . " and " . $nm_aspas . $campo2 . $nm_aspas;
                   if ($tp_campo == "DTEQ" || $tp_campo == "DATEEQ" || $tp_campo == "DATETIMEEQ")
                   {
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_equl'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
                   }
                   else
                   {
                       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_betw'] . " " . $this->cmp_formatado[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_andd'] . " " . $this->cmp_formatado[$nome_campo . "_Input_2"] . "##*@@";
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
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_like'] . " " . $nm_cond . "##*@@";
            break;
            case "NU":     // 
               $this->comando        .= " $nome IS NULL ";
               $this->comando_sum    .= " $nome_sum IS NULL ";
               $this->comando_filtro .= " $nome IS NULL ";
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_null'] ." " . $this->cmp_formatado[$nome_campo] . "##*@@";
            break;
            case "NN":     // 
               $this->comando        .= " $nome IS NOT NULL ";
               $this->comando_sum    .= " $nome_sum IS NOT NULL ";
               $this->comando_filtro .= " $nome IS NOT NULL ";
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $nmgp_tab_label[$nome_campo] . " " . $this->Ini->Nm_lang['lang_srch_nnul'] . " " . $this->cmp_formatado[$nome_campo] . "##*@@";
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
   $NM_retorno = "grid_public_v_dte.php";
    if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['proc_res'])) 
    {
        $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['proc_res'] = false; 
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
<BODY class="scGridPage">
<SCRIPT type="text/javascript">
<?php
    if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['prim_vez'] == "N" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['proc_res']) 
    {
?>
   parent.document.getElementById('nmsc_iframe_grid_public_v_dte').contentWindow.nm_gp_submit_ajax('inicio', '');
<?php
    }
    else
    {
?>
   parent.document.getElementById('nmsc_iframe_grid_public_v_dte').src = 'grid_public_v_dte.php?nmgp_opcao=pesq&script_case_init=<?php echo NM_encode_input($this->Ini->sc_page) ?>&script_case_session=<?php echo session_id() ?>';
<?php
    }
    $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['prim_vez'] = "N";
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
 <TITLE><?php echo $this->Ini->Nm_lang['lang_othr_srch_titl'] ?> - "public".v_dte</TITLE>
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
  $("#sc_fec_emi_dte_jq").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fec_emi_dte_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emi_dte_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emi_dte_ano').value;
          document.getElementById('sc_fec_emi_dte_jq').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fec_emi_dte_dia').value = aParts[0];
          document.getElementById('SC_fec_emi_dte_mes').value = aParts[1];
          document.getElementById('SC_fec_emi_dte_ano').value = aParts[2];
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

  $("#sc_fec_emi_dte_jq2").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fec_emi_dte_input_2_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emi_dte_input_2_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fec_emi_dte_input_2_ano').value;
          document.getElementById('sc_fec_emi_dte_jq2').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fec_emi_dte_input_2_dia').value = aParts[0];
          document.getElementById('SC_fec_emi_dte_input_2_mes').value = aParts[1];
          document.getElementById('SC_fec_emi_dte_input_2_ano').value = aParts[2];
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

  $("#sc_fech_carg_jq").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fech_carg_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fech_carg_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fech_carg_ano').value;
          document.getElementById('sc_fech_carg_jq').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fech_carg_dia').value = aParts[0];
          document.getElementById('SC_fech_carg_mes').value = aParts[1];
          document.getElementById('SC_fech_carg_ano').value = aParts[2];
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

  $("#sc_fech_carg_jq2").datepicker({
    beforeShow: function(input, inst) {
          var_dt_ini  = document.getElementById('SC_fech_carg_input_2_dia').value + '/';
          var_dt_ini += document.getElementById('SC_fech_carg_input_2_mes').value + '/';
          var_dt_ini += document.getElementById('SC_fech_carg_input_2_ano').value;
          document.getElementById('sc_fech_carg_jq2').value = var_dt_ini;
    },
    onClose: function(dateText, inst) {
          aParts  = dateText.split("/");
          document.getElementById('SC_fech_carg_input_2_dia').value = aParts[0];
          document.getElementById('SC_fech_carg_input_2_mes').value = aParts[1];
          document.getElementById('SC_fech_carg_input_2_ano').value = aParts[2];
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
include_once("grid_public_v_dte_sajax_js.php");
?>
<script type="text/javascript">
 $(function() {
 });
</script>
 <FORM name="F1" action="grid_public_v_dte.php" method="post" target="_self"> 
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
             $tipo_docu_cond, $tipo_docu,
             $folio_dte_cond, $folio_dte,
             $fec_emi_dte_cond, $fec_emi_dte, $fec_emi_dte_dia, $fec_emi_dte_mes, $fec_emi_dte_ano, $fec_emi_dte_input_2_dia, $fec_emi_dte_input_2_mes, $fec_emi_dte_input_2_ano,
             $fech_carg_cond, $fech_carg, $fech_carg_dia, $fech_carg_mes, $fech_carg_ano, $fech_carg_input_2_dia, $fech_carg_input_2_mes, $fech_carg_input_2_ano,
             $est_xdte_cond, $est_xdte,
             $rut_rec_dte_cond, $rut_rec_dte,
             $nm_url_saida, $nm_apl_dependente, $nmgp_parms, $bprocessa, $nmgp_save_name, $NM_operador, $NM_filters, $nmgp_save_option, $NM_filters_del, $Script_BI;
      $Script_BI = "";
      $this->nmgp_botoes['clear'] = "on";
      $this->nmgp_botoes['save'] = "on";
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display'] as $NM_cada_btn => $NM_cada_opc)
          {
              $this->nmgp_botoes[$NM_cada_btn] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['scriptcase']['sc_aba_iframe']))
      {
          foreach ($_SESSION['scriptcase']['sc_aba_iframe'] as $aba => $apls_aba)
          {
              if (in_array("grid_public_v_dte", $apls_aba))
              {
                  $this->aba_iframe = true;
                  break;
              }
          }
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['iframe_menu'] && (!isset($_SESSION['scriptcase']['menu_mobile']) || empty($_SESSION['scriptcase']['menu_mobile'])))
      {
          $this->aba_iframe = true;
      }
      $nmgp_tab_label = "";
      $delimitador = "##@@";
      if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']) && $bprocessa != "recarga" && $bprocessa != "save_form" && $bprocessa != "filter_save" && $bprocessa != "filter_delete")
      { 
          if ($_SESSION['scriptcase']['charset'] != "UTF-8")
          {
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca'] = NM_conv_charset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca'], $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          $tipo_docu = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['tipo_docu']; 
          $tipo_docu_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['tipo_docu_cond']; 
          $folio_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['folio_dte']; 
          $folio_dte_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['folio_dte_cond']; 
          $fec_emi_dte_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_dia']; 
          $fec_emi_dte_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_mes']; 
          $fec_emi_dte_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_ano']; 
          $fec_emi_dte_input_2_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2_dia']; 
          $fec_emi_dte_input_2_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2_mes']; 
          $fec_emi_dte_input_2_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2_ano']; 
          $fec_emi_dte_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_cond']; 
          $fech_carg_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_dia']; 
          $fech_carg_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_mes']; 
          $fech_carg_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_ano']; 
          $fech_carg_input_2_dia = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2_dia']; 
          $fech_carg_input_2_mes = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2_mes']; 
          $fech_carg_input_2_ano = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2_ano']; 
          $fech_carg_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_cond']; 
          $est_xdte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['est_xdte']; 
          $est_xdte_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['est_xdte_cond']; 
          $rut_rec_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['rut_rec_dte']; 
          $rut_rec_dte_cond = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['rut_rec_dte_cond']; 
          $this->NM_operador = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['NM_operador']; 
          if (strtoupper($folio_dte_cond) != "II" && strtoupper($folio_dte_cond) != "QP" && strtoupper($folio_dte_cond) != "NP" && strtoupper($folio_dte_cond) != "IN") 
          { 
              nmgp_Form_Num_Val($folio_dte, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "1", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
          } 
          if (strtoupper($rut_rec_dte_cond) != "II" && strtoupper($rut_rec_dte_cond) != "QP" && strtoupper($rut_rec_dte_cond) != "NP" && strtoupper($rut_rec_dte_cond) != "IN") 
          { 
              nmgp_Form_Num_Val($rut_rec_dte, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "1", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
          } 
      } 
      if (!isset($tipo_docu_cond) || empty($tipo_docu_cond))
      {
         $tipo_docu_cond = "eq";
      }
      if (!isset($folio_dte_cond) || empty($folio_dte_cond))
      {
         $folio_dte_cond = "eq";
      }
      if (!isset($fec_emi_dte_cond) || empty($fec_emi_dte_cond))
      {
         $fec_emi_dte_cond = "bw";
      }
      if (!isset($fech_carg_cond) || empty($fech_carg_cond))
      {
         $fech_carg_cond = "bw";
      }
      if (!isset($rut_rec_dte_cond) || empty($rut_rec_dte_cond))
      {
         $rut_rec_dte_cond = "eq";
      }
      if (!isset($fec_venc_dte_cond) || empty($fec_venc_dte_cond))
      {
         $fec_venc_dte_cond = "eq";
      }
      if (!isset($rut_emis_dte_cond) || empty($rut_emis_dte_cond))
      {
         $rut_emis_dte_cond = "eq";
      }
      if (!isset($digi_emis_dte_cond) || empty($digi_emis_dte_cond))
      {
         $digi_emis_dte_cond = "eq";
      }
      if (!isset($nom_emis_dte_cond) || empty($nom_emis_dte_cond))
      {
         $nom_emis_dte_cond = "eq";
      }
      if (!isset($giro_emis_dte_cond) || empty($giro_emis_dte_cond))
      {
         $giro_emis_dte_cond = "eq";
      }
      if (!isset($dir_orig_dte_cond) || empty($dir_orig_dte_cond))
      {
         $dir_orig_dte_cond = "eq";
      }
      if (!isset($com_orig_dte_cond) || empty($com_orig_dte_cond))
      {
         $com_orig_dte_cond = "eq";
      }
      if (!isset($ciud_orig_dte_cond) || empty($ciud_orig_dte_cond))
      {
         $ciud_orig_dte_cond = "eq";
      }
      if (!isset($dig_rec_dte_cond) || empty($dig_rec_dte_cond))
      {
         $dig_rec_dte_cond = "eq";
      }
      if (!isset($nom_rec_dte_cond) || empty($nom_rec_dte_cond))
      {
         $nom_rec_dte_cond = "eq";
      }
      if (!isset($giro_rec_dte_cond) || empty($giro_rec_dte_cond))
      {
         $giro_rec_dte_cond = "eq";
      }
      if (!isset($dir_rec_dte_cond) || empty($dir_rec_dte_cond))
      {
         $dir_rec_dte_cond = "eq";
      }
      if (!isset($com_rec_dte_cond) || empty($com_rec_dte_cond))
      {
         $com_rec_dte_cond = "eq";
      }
      if (!isset($ciud_rec_dte_cond) || empty($ciud_rec_dte_cond))
      {
         $ciud_rec_dte_cond = "eq";
      }
      if (!isset($mntneto_dte_cond) || empty($mntneto_dte_cond))
      {
         $mntneto_dte_cond = "eq";
      }
      if (!isset($mnt_exen_dte_cond) || empty($mnt_exen_dte_cond))
      {
         $mnt_exen_dte_cond = "eq";
      }
      if (!isset($tasa_iva_dte_cond) || empty($tasa_iva_dte_cond))
      {
         $tasa_iva_dte_cond = "eq";
      }
      if (!isset($iva_dte_cond) || empty($iva_dte_cond))
      {
         $iva_dte_cond = "eq";
      }
      if (!isset($mont_tot_dte_cond) || empty($mont_tot_dte_cond))
      {
         $mont_tot_dte_cond = "eq";
      }
      if (!isset($valo_pag_dte_cond) || empty($valo_pag_dte_cond))
      {
         $valo_pag_dte_cond = "eq";
      }
      if (!isset($desc_tipo_docu_cond) || empty($desc_tipo_docu_cond))
      {
         $desc_tipo_docu_cond = "eq";
      }
      if (!isset($path_pdf_cond) || empty($path_pdf_cond))
      {
         $path_pdf_cond = "eq";
      }
      if (!isset($path_pdf_cedible_cond) || empty($path_pdf_cedible_cond))
      {
         $path_pdf_cedible_cond = "eq";
      }
      if (!isset($track_id_cond) || empty($track_id_cond))
      {
         $track_id_cond = "eq";
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

      $str_display_fec_emi_dte = ('bw' == $fec_emi_dte_cond) ? $display_aberto : $display_fechado;
      $str_display_fech_carg = ('bw' == $fech_carg_cond) ? $display_aberto : $display_fechado;

      if (!isset($tipo_docu) || $tipo_docu == "")
      {
          $tipo_docu = "";
      }
      if (isset($tipo_docu) && !empty($tipo_docu))
      {
         $tmp_pos = strpos($tipo_docu, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $tipo_docu = substr($tipo_docu, 0, $tmp_pos);
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
      if (!isset($fec_emi_dte) || $fec_emi_dte == "")
      {
          $fec_emi_dte = "";
      }
      if (isset($fec_emi_dte) && !empty($fec_emi_dte))
      {
         $tmp_pos = strpos($fec_emi_dte, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $fec_emi_dte = substr($fec_emi_dte, 0, $tmp_pos);
         }
      }
      if (!isset($fech_carg) || $fech_carg == "")
      {
          $fech_carg = "";
      }
      if (isset($fech_carg) && !empty($fech_carg))
      {
         $tmp_pos = strpos($fech_carg, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $fech_carg = substr($fech_carg, 0, $tmp_pos);
         }
      }
      if (!isset($est_xdte) || $est_xdte == "")
      {
          $est_xdte = "";
      }
      if (isset($est_xdte) && !empty($est_xdte))
      {
         $tmp_pos = strpos($est_xdte, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $est_xdte = substr($est_xdte, 0, $tmp_pos);
         }
      }
      if (!isset($rut_rec_dte) || $rut_rec_dte == "")
      {
          $rut_rec_dte = "";
      }
      if (isset($rut_rec_dte) && !empty($rut_rec_dte))
      {
         $tmp_pos = strpos($rut_rec_dte, "##@@");
         if ($tmp_pos === false)
         { }
         else
         {
         $rut_rec_dte = substr($rut_rec_dte, 0, $tmp_pos);
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
      if ( isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sc_modal']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sc_modal'])
   {
?>
       <?php echo nmButtonOutput($this->arr_buttons, "bsair", "document.form_cancel.submit()", "document.form_cancel.submit()", "sc_b_cancel_", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
?>
<?php
   }
?>
<?php
   if (is_file("grid_public_v_dte_help.txt"))
   {
      $Arq_WebHelp = file("grid_public_v_dte_help.txt"); 
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





      <TD class="scFilterLabelOdd"><?php echo (isset($this->New_label['tipo_docu'])) ? $this->New_label['tipo_docu'] : "Tipo DTE"; ?></TD>
     <TD class="scFilterFieldOdd"> 
      <INPUT type="hidden" name="tipo_docu_cond" value="eq"><?php echo $this->Ini->Nm_lang['lang_srch_exac'] ?>
 </TD>
     <TD  class="scFilterFieldOdd">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontOdd">
           <?php
 $SC_Label = (isset($this->New_label['tipo_docu'])) ? $this->New_label['tipo_docu'] : "Tipo DTE";
 $nmgp_tab_label .= "tipo_docu?#?" . $SC_Label . "?@?";
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
   <span id="idAjaxSelect_tipo_docu">
      <SELECT class="scFilterObjectOdd" name="tipo_docu"  size="1">
       <OPTION value="">Seleccione Tipo DTE</OPTION>
<?php
      $nm_opcoes = explode("?@?", $nmgp_def_dados);
      foreach ($nm_opcoes as $nm_opcao)
      {
         if (!empty($nm_opcao))
         {
            $temp_bug_list                                = explode("?#?", $nm_opcao);
            list($nm_opc_val, $nm_opc_cod, $nm_opc_sel) = $temp_bug_list;
            if ("" != $tipo_docu)
            {
                    $tipo_docu_sel = ($nm_opc_cod === $tipo_docu) ? "selected" : "";
            }
            else
            {
               $tipo_docu_sel = ("S" == $nm_opc_sel) ? "selected" : "";
            }
            $nm_sc_valor = $nm_opc_val;
            $nm_opc_val = $nm_sc_valor;
?>
       <OPTION value="<?php echo NM_encode_input($nm_opc_cod . $delimitador . $nm_opc_val); ?>" <?php echo $tipo_docu_sel; ?>><?php echo $nm_opc_val; ?></OPTION>
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





      <TD class="scFilterLabelEven"><?php echo (isset($this->New_label['folio_dte'])) ? $this->New_label['folio_dte'] : "Folio Dte"; ?></TD>
     <TD class="scFilterFieldEven"> 
      <INPUT type="hidden" name="folio_dte_cond" value="eq"><?php echo $this->Ini->Nm_lang['lang_srch_exac'] ?>
 </TD>
     <TD  class="scFilterFieldEven">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontEven">
           <?php
 $SC_Label = (isset($this->New_label['folio_dte'])) ? $this->New_label['folio_dte'] : "Folio Dte";
 $nmgp_tab_label .= "folio_dte?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>
<INPUT  type="text" id="SC_folio_dte" name="folio_dte" value="<?php echo NM_encode_input($folio_dte) ?>" size=10 alt="{datatype: 'integer', maxLength: 10, thousandsSep: '<?php echo $_SESSION['scriptcase']['reg_conf']['grup_num'] ?>', allowNegative: false, onlyNegative: false, enterTab: false}" class="sc-js-input scFilterObjectEven">

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelOdd"><?php echo (isset($this->New_label['fec_emi_dte'])) ? $this->New_label['fec_emi_dte'] : "Fecha Emisión"; ?></TD>
     <TD class="scFilterFieldOdd"> 
      <INPUT type="hidden" name="fec_emi_dte_cond" value="bw"><?php echo $this->Ini->Nm_lang['lang_srch_betw'] ?>
 </TD>
     <TD  class="scFilterFieldOdd">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontOdd">
           <?php
 $SC_Label = (isset($this->New_label['fec_emi_dte'])) ? $this->New_label['fec_emi_dte'] : "Fecha Emisión";
 $nmgp_tab_label .= "fec_emi_dte?#?" . $SC_Label . "?@?";
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
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_emi_dte_dia" name="fec_emi_dte_dia" value="<?php echo NM_encode_input($fec_emi_dte_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emi_dte_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_emi_dte_mes" name="fec_emi_dte_mes" value="<?php echo NM_encode_input($fec_emi_dte_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emi_dte_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_emi_dte_ano" name="fec_emi_dte_ano" value="<?php echo NM_encode_input($fec_emi_dte_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
<INPUT type="hidden" id="sc_fec_emi_dte_jq">
        <SPAN id="id_css_fec_emi_dte"  class="scFilterFieldFontOdd">
 <?php echo $date_format_show ?>         </SPAN>
                 </TD>
       </TR>
       <TR valign="top">
        <TD id="id_vis_fec_emi_dte"  <?php echo $str_display_fec_emi_dte; ?> class="scFilterFieldFontOdd">
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
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_emi_dte_input_2_dia" name="fec_emi_dte_input_2_dia" value="<?php echo NM_encode_input($fec_emi_dte_input_2_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emi_dte_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_emi_dte_input_2_mes" name="fec_emi_dte_input_2_mes" value="<?php echo NM_encode_input($fec_emi_dte_input_2_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fec_emi_dte_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectOdd" type="text" id="SC_fec_emi_dte_input_2_ano" name="fec_emi_dte_input_2_ano" value="<?php echo NM_encode_input($fec_emi_dte_input_2_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
         <INPUT type="hidden" id="sc_fec_emi_dte_jq2">

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelEven"><?php echo (isset($this->New_label['fech_carg'])) ? $this->New_label['fech_carg'] : "Fecha Carga"; ?></TD>
     <TD class="scFilterFieldEven"> 
      <INPUT type="hidden" name="fech_carg_cond" value="bw"><?php echo $this->Ini->Nm_lang['lang_srch_betw'] ?>
 </TD>
     <TD  class="scFilterFieldEven">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontEven">
           <?php
 $SC_Label = (isset($this->New_label['fech_carg'])) ? $this->New_label['fech_carg'] : "Fecha Carga";
 $nmgp_tab_label .= "fech_carg?#?" . $SC_Label . "?@?";
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
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fech_carg_dia" name="fech_carg_dia" value="<?php echo NM_encode_input($fech_carg_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fech_carg_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fech_carg_mes" name="fech_carg_mes" value="<?php echo NM_encode_input($fech_carg_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fech_carg_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fech_carg_ano" name="fech_carg_ano" value="<?php echo NM_encode_input($fech_carg_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
<INPUT type="hidden" id="sc_fech_carg_jq">
        <SPAN id="id_css_fech_carg"  class="scFilterFieldFontEven">
 <?php echo $date_format_show ?>         </SPAN>
                 </TD>
       </TR>
       <TR valign="top">
        <TD id="id_vis_fech_carg"  <?php echo $str_display_fech_carg; ?> class="scFilterFieldFontEven">
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
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fech_carg_input_2_dia" name="fech_carg_input_2_dia" value="<?php echo NM_encode_input($fech_carg_input_2_dia); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fech_carg_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "m")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fech_carg_input_2_mes" name="fech_carg_input_2_mes" value="<?php echo NM_encode_input($fech_carg_input_2_mes); ?>" size="2" alt="{datatype: 'mask', maskList: '99', alignRight: true, maxLength: 2, autoTab: false, enterTab: false}" onKeyUp="nm_tabula(this, 2, document.F1.fech_carg_cond.value)">

<?php
  }
?>
<?php
  if (substr($Part_date, 0,1) == "y")
  {
?>
<INPUT class="sc-js-input scFilterObjectEven" type="text" id="SC_fech_carg_input_2_ano" name="fech_carg_input_2_ano" value="<?php echo NM_encode_input($fech_carg_input_2_ano); ?>" size="4" alt="{datatype: 'mask', maskList: '9999', alignRight: true, maxLength: 4, autoTab: true, enterTab: false}">
 
<?php
  }
?>

<?php

}

?>
         <INPUT type="hidden" id="sc_fech_carg_jq2">

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelOdd"><?php echo (isset($this->New_label['est_xdte'])) ? $this->New_label['est_xdte'] : "Estado DTE"; ?></TD>
     <TD class="scFilterFieldOdd"> 
      <INPUT type="hidden" name="est_xdte_cond" value="eq"><?php echo $this->Ini->Nm_lang['lang_srch_exac'] ?>
 </TD>
     <TD  class="scFilterFieldOdd">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontOdd">
           <?php
 $SC_Label = (isset($this->New_label['est_xdte'])) ? $this->New_label['est_xdte'] : "Estado DTE";
 $nmgp_tab_label .= "est_xdte?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>

 <SELECT class="scFilterObjectOdd" name="est_xdte"  size="1">
 <OPTION value="">Seleccione Estado DTE</option>
 <OPTION value="1##@@Cargado"<?php if ($est_xdte == "1") { echo " selected" ;} ?>>Cargado</option>
 <OPTION value="3##@@Error"<?php if ($est_xdte == "3") { echo " selected" ;} ?>>Error</option>
 <OPTION value="5##@@Empaquetado "<?php if ($est_xdte == "5") { echo " selected" ;} ?>>Empaquetado </option>
 <OPTION value="13##@@Enviado a SII"<?php if ($est_xdte == "13") { echo " selected" ;} ?>>Enviado a SII</option>
 <OPTION value="29##@@Aceptado SII"<?php if ($est_xdte == "29") { echo " selected" ;} ?>>Aceptado SII</option>
 <OPTION value="77##@@Rechazado SII"<?php if ($est_xdte == "77") { echo " selected" ;} ?>>Rechazado SII</option>
 <OPTION value="157##@@Enviado a Clientes "<?php if ($est_xdte == "157") { echo " selected" ;} ?>>Enviado a Clientes </option>
 <OPTION value="413##@@Aceptado Cliente"<?php if ($est_xdte == "413") { echo " selected" ;} ?>>Aceptado Cliente</option>
 <OPTION value="1181##@@Rechazado Automaticamente"<?php if ($est_xdte == "1181") { echo " selected" ;} ?>>Rechazado Automaticamente</option>
 <OPTION value="1437##@@Rechazado Comercialmente"<?php if ($est_xdte == "1437") { echo " selected" ;} ?>>Rechazado Comercialmente</option>
 </SELECT>

        </TD>
       </TR>
      </TABLE>
     </TD>

   </tr><tr>





      <TD class="scFilterLabelEven"><?php echo (isset($this->New_label['rut_rec_dte'])) ? $this->New_label['rut_rec_dte'] : "Rut Recep."; ?></TD>
     <TD class="scFilterFieldEven"> 
      <INPUT type="hidden" name="rut_rec_dte_cond" value="eq"><?php echo $this->Ini->Nm_lang['lang_srch_exac'] ?>
 </TD>
     <TD  class="scFilterFieldEven">
      <TABLE  border="0" cellpadding="0" cellspacing="0">
       <TR valign="top">
        <TD class="scFilterFieldFontEven">
           <?php
 $SC_Label = (isset($this->New_label['rut_rec_dte'])) ? $this->New_label['rut_rec_dte'] : "Rut Recep.";
 $nmgp_tab_label .= "rut_rec_dte?#?" . $SC_Label . "?@?";
 $date_sep_bw = "";
?>
<INPUT  type="text" id="SC_rut_rec_dte" name="rut_rec_dte" value="<?php echo NM_encode_input($rut_rec_dte) ?>" size=9 alt="{datatype: 'integer', maxLength: 9, thousandsSep: '<?php echo $_SESSION['scriptcase']['reg_conf']['grup_num'] ?>', allowNegative: false, onlyNegative: false, enterTab: false}" class="sc-js-input scFilterObjectEven">

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
   if (is_file("grid_public_v_dte_help.txt"))
   {
      $Arq_WebHelp = file("grid_public_v_dte_help.txt"); 
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
    if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['prim_vez'] == "N") 
    {
?>
       <iframe id="nmsc_iframe_grid_public_v_dte" frameborder="0" marginwidth="0px" marginheight="0px" topmargin="0px" leftmargin="0px" align="center" height="1500px" name="nm_iframe_grid_public_v_dte" scrolling="auto" src="grid_public_v_dte.php?nmgp_opcao=pesq&script_case_init=<?php echo NM_encode_input($this->Ini->sc_page) ?>&script_case_session=<?php echo session_id() ?>" width="100%"></iframe>
<?php
    }
    else
    {
?>
       <iframe id="nmsc_iframe_grid_public_v_dte" frameborder="0" marginwidth="0px" marginheight="0px" topmargin="0px" leftmargin="0px" align="center" height="1500px" name="nm_iframe_grid_public_v_dte" scrolling="auto" src="" width="100%"></iframe>
<?php
    }
?>
<iframe id="nmsc_iframe_grid_public_v_dte_pesq"  name="nm_iframe_grid_public_v_dte_pesq" style= "display: none;" frameborder="0" marginwidth="0px" marginheight="0px" topmargin="0px" leftmargin="0px" src=""></iframe>
</td></tr></table></td></tr>
</TABLE>
   <INPUT type="hidden" name="form_condicao" value="3">
</FORM> 
   <FORM style="display:none;" name="form_cancel"  method="POST" action="<?php echo $nm_url_saida; ?>" target="_self"> 
   <INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
   <INPUT type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id()); ?>"> 
<?php
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['orig_pesq'] == "grid")
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
    document.F1.target = "nm_iframe_grid_public_v_dte_pesq";
    document.F1.submit();
 }
 function limpa_form()
 {
   document.F1.reset();
   NM_apaga_erro();
   document.F1.tipo_docu.value = "";
   document.F1.folio_dte.value = "";
   document.F1.fec_emi_dte_dia.value = "";
   document.F1.fec_emi_dte_mes.value = "";
   document.F1.fec_emi_dte_ano.value = "";
   document.F1.fec_emi_dte_input_2_dia.value = "";
   document.F1.fec_emi_dte_input_2_mes.value = "";
   document.F1.fec_emi_dte_input_2_ano.value = "";
   document.F1.fech_carg_dia.value = "";
   document.F1.fech_carg_mes.value = "";
   document.F1.fech_carg_ano.value = "";
   document.F1.fech_carg_input_2_dia.value = "";
   document.F1.fech_carg_input_2_mes.value = "";
   document.F1.fech_carg_input_2_ano.value = "";
   document.F1.est_xdte.value = "";
   document.F1.rut_rec_dte.value = "";
   nm_campos_between(document.getElementById('id_vis_fec_emi_dte'), document.F1.fec_emi_dte_cond, 'fec_emi_dte');
   nm_campos_between(document.getElementById('id_vis_fech_carg'), document.F1.fech_carg_cond, 'fech_carg');
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
    $('#SC_fec_emi_dte_dia').bind('change', function() {sc_grid_public_v_dte_valida_dia(this)});
    $('#SC_fec_emi_dte_input_2_dia').bind('change', function() {sc_grid_public_v_dte_valida_dia(this)});
    $('#SC_fec_emi_dte_input_2_mes').bind('change', function() {sc_grid_public_v_dte_valida_mes(this)});
    $('#SC_fec_emi_dte_mes').bind('change', function() {sc_grid_public_v_dte_valida_mes(this)});
    $('#SC_fech_carg_dia').bind('change', function() {sc_grid_public_v_dte_valida_dia(this)});
    $('#SC_fech_carg_input_2_dia').bind('change', function() {sc_grid_public_v_dte_valida_dia(this)});
    $('#SC_fech_carg_input_2_mes').bind('change', function() {sc_grid_public_v_dte_valida_mes(this)});
    $('#SC_fech_carg_mes').bind('change', function() {sc_grid_public_v_dte_valida_mes(this)});
 }
 function sc_grid_public_v_dte_valida_dia(obj)
 {
     if (obj.value < 1 || obj.value > 31)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_iday'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_public_v_dte_valida_mes(obj)
 {
     if (obj.value < 1 || obj.value > 12)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_mnth'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_public_v_dte_valida_hora(obj)
 {
     if (obj.value < 0 || obj.value > 23)
     {
         if (confirm (Nm_erro['lang_jscr_ivtm'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_public_v_dte_valida_min(obj)
 {
     if (obj.value < 0 || obj.value > 59)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_mint'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_public_v_dte_valida_seg(obj)
 {
     if (obj.value < 0 || obj.value > 59)
     {
         if (confirm (Nm_erro['lang_jscr_ivdt'] +  " " + Nm_erro['lang_jscr_secd'] +  " " + Nm_erro['lang_jscr_wfix']))
         {
            Xfocus = setTimeout(function() { obj.focus(); }, 10);
         }
     }
 }
 function sc_grid_public_v_dte_valida_cic(obj)
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
 function sc_grid_public_v_dte_valida_cnpj(obj)
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
 function sc_grid_public_v_dte_valida_ciccnpj(obj)
 {
     var CnpjIn = obj.value;
     CnpjIn = CnpjIn.replace(/[.]/g, '');
     CnpjIn = CnpjIn.replace(/[-]/g, '');
     CnpjIn = CnpjIn.replace(/[/]/g, '');
     if (CnpjIn.length <= 11)
     {
         return sc_grid_public_v_dte_valida_cic(obj);
     }
     else
     {
         return sc_grid_public_v_dte_valida_cnpj(obj);
     }
 }
 function sc_grid_public_v_dte_valida_cep(obj)
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
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_public_v_dte.php";
      $this->Campos_Mens_erro = ""; 
      $this->nm_data = new nm_data("es");
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] = "";
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
      $this->comando        = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];
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
      global $tipo_docu_cond, $tipo_docu,
             $folio_dte_cond, $folio_dte,
             $fec_emi_dte_cond, $fec_emi_dte, $fec_emi_dte_dia, $fec_emi_dte_mes, $fec_emi_dte_ano, $fec_emi_dte_input_2_dia, $fec_emi_dte_input_2_mes, $fec_emi_dte_input_2_ano,
             $fech_carg_cond, $fech_carg, $fech_carg_dia, $fech_carg_mes, $fech_carg_ano, $fech_carg_input_2_dia, $fech_carg_input_2_mes, $fech_carg_input_2_ano,
             $est_xdte_cond, $est_xdte,
             $rut_rec_dte_cond, $rut_rec_dte, $nmgp_tab_label;

      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_gp_limpa.php", "F", "nm_limpa_valor") ; 
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_conv_dados.php", "F", "nm_conv_limpa_dado") ; 
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_edit.php", "F", "nmgp_Form_Num_Val") ; 
      $tipo_docu_cond_salva = $tipo_docu_cond; 
      if (!isset($tipo_docu_input_2) || $tipo_docu_input_2 == "")
      {
          $tipo_docu_input_2 = $tipo_docu;
      }
      $folio_dte_cond_salva = $folio_dte_cond; 
      if (!isset($folio_dte_input_2) || $folio_dte_input_2 == "")
      {
          $folio_dte_input_2 = $folio_dte;
      }
      $fec_emi_dte_cond_salva = $fec_emi_dte_cond; 
      if (!isset($fec_emi_dte_input_2_dia) || $fec_emi_dte_input_2_dia == "")
      {
          $fec_emi_dte_input_2_dia = $fec_emi_dte_dia;
      }
      if (!isset($fec_emi_dte_input_2_mes) || $fec_emi_dte_input_2_mes == "")
      {
          $fec_emi_dte_input_2_mes = $fec_emi_dte_mes;
      }
      if (!isset($fec_emi_dte_input_2_ano) || $fec_emi_dte_input_2_ano == "")
      {
          $fec_emi_dte_input_2_ano = $fec_emi_dte_ano;
      }
      if (!isset($fec_emi_dte_input_2) || $fec_emi_dte_input_2 == "")
      {
          $fec_emi_dte_input_2 = $fec_emi_dte;
      }
      $fech_carg_cond_salva = $fech_carg_cond; 
      if (!isset($fech_carg_input_2_dia) || $fech_carg_input_2_dia == "")
      {
          $fech_carg_input_2_dia = $fech_carg_dia;
      }
      if (!isset($fech_carg_input_2_mes) || $fech_carg_input_2_mes == "")
      {
          $fech_carg_input_2_mes = $fech_carg_mes;
      }
      if (!isset($fech_carg_input_2_ano) || $fech_carg_input_2_ano == "")
      {
          $fech_carg_input_2_ano = $fech_carg_ano;
      }
      if (!isset($fech_carg_input_2) || $fech_carg_input_2 == "")
      {
          $fech_carg_input_2 = $fech_carg;
      }
      $est_xdte_cond_salva = $est_xdte_cond; 
      if (!isset($est_xdte_input_2) || $est_xdte_input_2 == "")
      {
          $est_xdte_input_2 = $est_xdte;
      }
      $rut_rec_dte_cond_salva = $rut_rec_dte_cond; 
      if (!isset($rut_rec_dte_input_2) || $rut_rec_dte_input_2 == "")
      {
          $rut_rec_dte_input_2 = $rut_rec_dte;
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
      if ($rut_rec_dte_cond != "in")
      {
          nm_limpa_numero($rut_rec_dte, $_SESSION['scriptcase']['reg_conf']['grup_num']) ; 
      }
      else
      {
          $Nm_sc_valores = explode(",", $rut_rec_dte);
          foreach ($Nm_sc_valores as $II => $Nm_sc_valor)
          {
              $Nm_sc_valor = trim($Nm_sc_valor);
              nm_limpa_numero($Nm_sc_valor, $_SESSION['scriptcase']['reg_conf']['grup_num']); 
              $Nm_sc_valores[$II] = $Nm_sc_valor;
          }
          $rut_rec_dte = implode(",", $Nm_sc_valores);
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']  = array(); 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['tipo_docu'] = $tipo_docu; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['tipo_docu_cond'] = $tipo_docu_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['folio_dte'] = $folio_dte; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['folio_dte_cond'] = $folio_dte_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_dia'] = $fec_emi_dte_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_mes'] = $fec_emi_dte_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_ano'] = $fec_emi_dte_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2_dia'] = $fec_emi_dte_input_2_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2_mes'] = $fec_emi_dte_input_2_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2_ano'] = $fec_emi_dte_input_2_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_cond'] = $fec_emi_dte_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_dia'] = $fech_carg_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_mes'] = $fech_carg_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_ano'] = $fech_carg_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2_dia'] = $fech_carg_input_2_dia; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2_mes'] = $fech_carg_input_2_mes; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2_ano'] = $fech_carg_input_2_ano; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_cond'] = $fech_carg_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['est_xdte'] = $est_xdte; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['est_xdte_cond'] = $est_xdte_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['rut_rec_dte'] = $rut_rec_dte; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['rut_rec_dte_cond'] = $rut_rec_dte_cond_salva; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['NM_operador'] = $this->NM_operador; 
      $Conteudo = $tipo_docu;
      if (strpos($Conteudo, "##@@") !== false)
      {
          $Conteudo = substr($Conteudo, strpos($Conteudo, "##@@") + 4);
      }
      $this->cmp_formatado['tipo_docu'] = $Conteudo;
      $Conteudo = $folio_dte;
      if (strtoupper($folio_dte_cond) != "II" && strtoupper($folio_dte_cond) != "QP" && strtoupper($folio_dte_cond) != "NP" && strtoupper($folio_dte_cond) != "IN") 
      { 
          nmgp_Form_Num_Val($Conteudo, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "1", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
      } 
      $this->cmp_formatado['folio_dte'] = $Conteudo;
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fec_emi_dte_dia))
      {
          $Conteudo .= (strlen($fec_emi_dte_dia) == 2) ? $fec_emi_dte_dia : "0" . $fec_emi_dte_dia;
          $Formato  .= "DD";
      }
      if (!empty($fec_emi_dte_mes))
      {
          $Conteudo .= (strlen($fec_emi_dte_mes) == 2) ? $fec_emi_dte_mes : "0" . $fec_emi_dte_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fec_emi_dte_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fec_emi_dte'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte'] = $Conteudo; 
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fec_emi_dte_input_2_dia))
      {
          $Conteudo .= (strlen($fec_emi_dte_input_2_dia) == 2) ? $fec_emi_dte_input_2_dia : "0" . $fec_emi_dte_input_2_dia;
          $Formato  .= "DD";
      }
      if (!empty($fec_emi_dte_input_2_mes))
      {
          $Conteudo .= (strlen($fec_emi_dte_input_2_mes) == 2) ? $fec_emi_dte_input_2_mes : "0" . $fec_emi_dte_input_2_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fec_emi_dte_input_2_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fec_emi_dte_Input_2'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2'] = $Conteudo; 
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fech_carg_dia))
      {
          $Conteudo .= (strlen($fech_carg_dia) == 2) ? $fech_carg_dia : "0" . $fech_carg_dia;
          $Formato  .= "DD";
      }
      if (!empty($fech_carg_mes))
      {
          $Conteudo .= (strlen($fech_carg_mes) == 2) ? $fech_carg_mes : "0" . $fech_carg_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fech_carg_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fech_carg'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg'] = $Conteudo; 
      $Conteudo  = "";
      $Formato   = "";
      if (!empty($fech_carg_input_2_dia))
      {
          $Conteudo .= (strlen($fech_carg_input_2_dia) == 2) ? $fech_carg_input_2_dia : "0" . $fech_carg_input_2_dia;
          $Formato  .= "DD";
      }
      if (!empty($fech_carg_input_2_mes))
      {
          $Conteudo .= (strlen($fech_carg_input_2_mes) == 2) ? $fech_carg_input_2_mes : "0" . $fech_carg_input_2_mes;
          $Formato  .= "MM";
      }
      $Conteudo .= $fech_carg_input_2_ano;
      $Formato  .= "YYYY";
      if (!empty($Conteudo))
      {
          if (is_numeric($Conteudo) && $Conteudo > 0) 
          { 
              $this->nm_data->SetaData($Conteudo, $Formato);
              $Conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
          } 
      }
      $this->cmp_formatado['fech_carg_Input_2'] = $Conteudo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2'] = $Conteudo; 
      $Conteudo = $est_xdte;
      if (strpos($Conteudo, "##@@") !== false)
      {
          $Conteudo = substr($Conteudo, strpos($Conteudo, "##@@") + 4);
      }
      $this->cmp_formatado['est_xdte'] = $Conteudo;
      $Conteudo = $rut_rec_dte;
      if (strtoupper($rut_rec_dte_cond) != "II" && strtoupper($rut_rec_dte_cond) != "QP" && strtoupper($rut_rec_dte_cond) != "NP" && strtoupper($rut_rec_dte_cond) != "IN") 
      { 
          nmgp_Form_Num_Val($Conteudo, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "1", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
      } 
      $this->cmp_formatado['rut_rec_dte'] = $Conteudo;

      //----- $tipo_docu
      if (isset($tipo_docu))
      {
         $this->monta_condicao("tipo_docu", $tipo_docu_cond, $tipo_docu, "", "tipo_docu");
      }

      //----- $folio_dte
      if (isset($folio_dte) || $folio_dte_cond == "nu" || $folio_dte_cond == "nn")
      {
         $this->monta_condicao("folio_dte", $folio_dte_cond, $folio_dte, "", "folio_dte");
      }

      //----- $fec_emi_dte
      $nm_tp_dado = "DATE";
      if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
      {
          $nm_format_db = "YYYY-MM-DD HH:II:SS";
      }
      else
      {
          $nm_format_db = "YYYY-MM-DD";
      }
      $condicao = strtoupper($fec_emi_dte_cond);
      $array_fec_emi_dte = array();
      $array_fec_emi_dte2 = array();
      $nm_psq_dt1 = $fec_emi_dte_dia . $fec_emi_dte_mes . $fec_emi_dte_ano . $fec_emi_dte_hor . $fec_emi_dte_min . $fec_emi_dte_seg;
      $nm_psq_dt_inf  = ("" == $fec_emi_dte_ano) ? "YYYY" : "$fec_emi_dte_ano";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fec_emi_dte_mes) ? "MM" : "$fec_emi_dte_mes";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fec_emi_dte_dia) ? "DD"   : "$fec_emi_dte_dia";
      $nm_psq_dt_inf .= " ";
      $nm_psq_dt_inf .= ("" == $fec_emi_dte_hor) ? "HH" : "$fec_emi_dte_hor";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fec_emi_dte_min) ? "II" : "$fec_emi_dte_min";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fec_emi_dte_seg) ? "SS" : "$fec_emi_dte_seg";
      nm_conv_form_data_hora($nm_psq_dt_inf, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      $array_fec_emi_dte["dia"] = ("" == $fec_emi_dte_dia) ? "__"   : "$fec_emi_dte_dia";
      $array_fec_emi_dte["mes"] = ("" == $fec_emi_dte_mes) ? "__"   : "$fec_emi_dte_mes";
      $array_fec_emi_dte["ano"] = ("" == $fec_emi_dte_ano) ? "____" : "$fec_emi_dte_ano";
      $array_fec_emi_dte["hor"] = ("" == $fec_emi_dte_hor) ? "__" : "$fec_emi_dte_hor";
      $array_fec_emi_dte["min"] = ("" == $fec_emi_dte_min) ? "__" : "$fec_emi_dte_min";
      $array_fec_emi_dte["seg"] = ("" == $fec_emi_dte_seg) ? "__" : "$fec_emi_dte_seg";
      $this->NM_data_qp = $array_fec_emi_dte;
      $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
      nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
//
      if ($condicao == "BW")
      {
          $array_fec_emi_dte2["dia"] = ("" == $fec_emi_dte_input_2_dia) ? "__"   : "$fec_emi_dte_input_2_dia";
          $array_fec_emi_dte2["mes"] = ("" == $fec_emi_dte_input_2_mes) ? "__"   : "$fec_emi_dte_input_2_mes";
          $array_fec_emi_dte2["ano"] = ("" == $fec_emi_dte_input_2_ano) ? "____" : "$fec_emi_dte_input_2_ano";
          $array_fec_emi_dte2["hor"] = ("" == $fec_emi_dte_input_2_hor) ? "__" : "$fec_emi_dte_input_2_hor";
          $array_fec_emi_dte2["min"] = ("" == $fec_emi_dte_input_2_min) ? "__" : "$fec_emi_dte_input_2_min";
          $array_fec_emi_dte2["seg"] = ("" == $fec_emi_dte_input_2_seg) ? "__" : "$fec_emi_dte_input_2_seg";
          $this->data_menor($array_fec_emi_dte);
          $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
          nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
          $this->data_maior($array_fec_emi_dte2);
          $nm_dt_compl_2 = $array_fec_emi_dte2["ano"] . "-" . $array_fec_emi_dte2["mes"] . "-" . $array_fec_emi_dte2["dia"] . " " . $array_fec_emi_dte2["hor"] . ":" . $array_fec_emi_dte2["min"] . ":" . $array_fec_emi_dte2["seg"];
          nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      }
      else
      {
          $array_fec_emi_dte2 = $array_fec_emi_dte;
      }
      if (FALSE !== strpos($nm_dt_compl, "__"))
      {
         if ($condicao == "II")
         {
             $condicao = "QP";
         }
         elseif ($condicao == "DF")
         {
             $this->data_menor($array_fec_emi_dte);
             $this->data_maior($array_fec_emi_dte2);
             $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fec_emi_dte2["ano"] . "-" . $array_fec_emi_dte2["mes"] . "-" . $array_fec_emi_dte2["dia"] . " " . $array_fec_emi_dte2["hor"] . ":" . $array_fec_emi_dte2["min"] . ":" . $array_fec_emi_dte2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "DF";
         }
         elseif ($condicao == "EQ")
         {
             $this->data_menor($array_fec_emi_dte);
             $this->data_maior($array_fec_emi_dte2);
             $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fec_emi_dte2["ano"] . "-" . $array_fec_emi_dte2["mes"] . "-" . $array_fec_emi_dte2["dia"] . " " . $array_fec_emi_dte2["hor"] . ":" . $array_fec_emi_dte2["min"] . ":" . $array_fec_emi_dte2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "EQ";
         }
         elseif ($condicao == "GT")
         {
             $this->data_maior($array_fec_emi_dte);
             $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "GE")
         {
             $this->data_menor($array_fec_emi_dte);
             $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LT")
         {
             $this->data_menor($array_fec_emi_dte);
             $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LE")
         {
             $this->data_maior($array_fec_emi_dte);
             $nm_dt_compl = $array_fec_emi_dte["ano"] . "-" . $array_fec_emi_dte["mes"] . "-" . $array_fec_emi_dte["dia"] . " " . $array_fec_emi_dte["hor"] . ":" . $array_fec_emi_dte["min"] . ":" . $array_fec_emi_dte["seg"];
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
          $this->monta_condicao("fec_emi_dte", $condicao, trim($nm_dt_compl), trim($nm_dt_compl_2), "fec_emi_dte", $nm_tp_dado);
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte']   = trim($nm_dt_compl); 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2'] = trim($nm_dt_compl_2); 
      }
      $nm_tp_dado = "";

      //----- $fech_carg
      $nm_tp_dado = "DATE";
      if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
      {
          $nm_format_db = "YYYY-MM-DD HH:II:SS";
      }
      else
      {
          $nm_format_db = "YYYY-MM-DD";
      }
      $condicao = strtoupper($fech_carg_cond);
      $array_fech_carg = array();
      $array_fech_carg2 = array();
      $nm_psq_dt1 = $fech_carg_dia . $fech_carg_mes . $fech_carg_ano . $fech_carg_hor . $fech_carg_min . $fech_carg_seg;
      $nm_psq_dt_inf  = ("" == $fech_carg_ano) ? "YYYY" : "$fech_carg_ano";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fech_carg_mes) ? "MM" : "$fech_carg_mes";
      $nm_psq_dt_inf .= "-";
      $nm_psq_dt_inf .= ("" == $fech_carg_dia) ? "DD"   : "$fech_carg_dia";
      $nm_psq_dt_inf .= " ";
      $nm_psq_dt_inf .= ("" == $fech_carg_hor) ? "HH" : "$fech_carg_hor";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fech_carg_min) ? "II" : "$fech_carg_min";
      $nm_psq_dt_inf .= ":";
      $nm_psq_dt_inf .= ("" == $fech_carg_seg) ? "SS" : "$fech_carg_seg";
      nm_conv_form_data_hora($nm_psq_dt_inf, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      $array_fech_carg["dia"] = ("" == $fech_carg_dia) ? "__"   : "$fech_carg_dia";
      $array_fech_carg["mes"] = ("" == $fech_carg_mes) ? "__"   : "$fech_carg_mes";
      $array_fech_carg["ano"] = ("" == $fech_carg_ano) ? "____" : "$fech_carg_ano";
      $array_fech_carg["hor"] = ("" == $fech_carg_hor) ? "__" : "$fech_carg_hor";
      $array_fech_carg["min"] = ("" == $fech_carg_min) ? "__" : "$fech_carg_min";
      $array_fech_carg["seg"] = ("" == $fech_carg_seg) ? "__" : "$fech_carg_seg";
      $this->NM_data_qp = $array_fech_carg;
      $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
      nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
//
      if ($condicao == "BW")
      {
          $array_fech_carg2["dia"] = ("" == $fech_carg_input_2_dia) ? "__"   : "$fech_carg_input_2_dia";
          $array_fech_carg2["mes"] = ("" == $fech_carg_input_2_mes) ? "__"   : "$fech_carg_input_2_mes";
          $array_fech_carg2["ano"] = ("" == $fech_carg_input_2_ano) ? "____" : "$fech_carg_input_2_ano";
          $array_fech_carg2["hor"] = ("" == $fech_carg_input_2_hor) ? "__" : "$fech_carg_input_2_hor";
          $array_fech_carg2["min"] = ("" == $fech_carg_input_2_min) ? "__" : "$fech_carg_input_2_min";
          $array_fech_carg2["seg"] = ("" == $fech_carg_input_2_seg) ? "__" : "$fech_carg_input_2_seg";
          $this->data_menor($array_fech_carg);
          $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
          nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
          $this->data_maior($array_fech_carg2);
          $nm_dt_compl_2 = $array_fech_carg2["ano"] . "-" . $array_fech_carg2["mes"] . "-" . $array_fech_carg2["dia"] . " " . $array_fech_carg2["hor"] . ":" . $array_fech_carg2["min"] . ":" . $array_fech_carg2["seg"];
          nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
      }
      else
      {
          $array_fech_carg2 = $array_fech_carg;
      }
      if (FALSE !== strpos($nm_dt_compl, "__"))
      {
         if ($condicao == "II")
         {
             $condicao = "QP";
         }
         elseif ($condicao == "DF")
         {
             $this->data_menor($array_fech_carg);
             $this->data_maior($array_fech_carg2);
             $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fech_carg2["ano"] . "-" . $array_fech_carg2["mes"] . "-" . $array_fech_carg2["dia"] . " " . $array_fech_carg2["hor"] . ":" . $array_fech_carg2["min"] . ":" . $array_fech_carg2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "DF";
         }
         elseif ($condicao == "EQ")
         {
             $this->data_menor($array_fech_carg);
             $this->data_maior($array_fech_carg2);
             $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $nm_dt_compl_2 = $array_fech_carg2["ano"] . "-" . $array_fech_carg2["mes"] . "-" . $array_fech_carg2["dia"] . " " . $array_fech_carg2["hor"] . ":" . $array_fech_carg2["min"] . ":" . $array_fech_carg2["seg"];
             nm_conv_form_data_hora($nm_dt_compl_2, "AAAA-MM-DD HH:II:SS", $nm_format_db);
             $condicao = "BW";
             $nm_tp_dado .= "EQ";
         }
         elseif ($condicao == "GT")
         {
             $this->data_maior($array_fech_carg);
             $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "GE")
         {
             $this->data_menor($array_fech_carg);
             $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LT")
         {
             $this->data_menor($array_fech_carg);
             $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
             nm_conv_form_data_hora($nm_dt_compl, "AAAA-MM-DD HH:II:SS", $nm_format_db);
         }
         elseif ($condicao == "LE")
         {
             $this->data_maior($array_fech_carg);
             $nm_dt_compl = $array_fech_carg["ano"] . "-" . $array_fech_carg["mes"] . "-" . $array_fech_carg["dia"] . " " . $array_fech_carg["hor"] . ":" . $array_fech_carg["min"] . ":" . $array_fech_carg["seg"];
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
          $this->monta_condicao("fech_carg", $condicao, trim($nm_dt_compl), trim($nm_dt_compl_2), "fech_carg", $nm_tp_dado);
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg']   = trim($nm_dt_compl); 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2'] = trim($nm_dt_compl_2); 
      }
      $nm_tp_dado = "";

      //----- $est_xdte
      if (isset($est_xdte))
      {
         $this->monta_condicao("est_xdte", $est_xdte_cond, $est_xdte, "", "est_xdte");
      }

      //----- $rut_rec_dte
      if (isset($rut_rec_dte) || $rut_rec_dte_cond == "nu" || $rut_rec_dte_cond == "nn")
      {
         $this->monta_condicao("rut_rec_dte", $rut_rec_dte_cond, $rut_rec_dte, "", "rut_rec_dte");
      }
   }

   /**
    * @access  public
    */
   function finaliza_resultado()
   {
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']) && $_SESSION['scriptcase']['charset'] != "UTF-8")
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca'] = NM_conv_charset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca'], "UTF-8", $_SESSION['scriptcase']['charset']);
      }

      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_lookup']  = $this->comando_sum . $this->comando_fim;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq']         = $this->comando . $this->comando_fim;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao']              = "pesq";
      if ("" == $this->comando_filtro)
      {
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'] = "";
      }
      else
      {
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'] = " (" . $this->comando_filtro . ")";
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'] != $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_ant'])
      {
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'] .= $this->NM_operador;
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_array_resumo'] = "NAO";
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_total_geral']  = "NAO";
         unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral']);
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_ant'] = $this->comando . $this->comando_fim;
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['fast_search']);

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
         case "tipo_docu" : return ('class="scFilterObjectOdd"'); break;
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
