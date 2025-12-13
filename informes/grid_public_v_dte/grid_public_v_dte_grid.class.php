<?php
class grid_public_v_dte_grid
{
   var $Ini;
   var $Erro;
   var $Db;
   var $Tot;
   var $Lin_impressas;
   var $Lin_final;
   var $Rows_span;
   var $NM_colspan;
   var $rs_grid;
   var $nm_grid_ini;
   var $nm_grid_sem_reg;
   var $nm_prim_linha;
   var $Rec_ini;
   var $Rec_fim;
   var $nmgp_reg_start;
   var $nmgp_reg_inicial;
   var $nmgp_reg_final;
   var $SC_seq_register;
   var $SC_seq_page;
   var $nm_location;
   var $nm_data;
   var $nm_cod_barra;
   var $sc_proc_grid; 
   var $NM_raiz_img; 
   var $Ind_lig_mult; 
   var $NM_opcao; 
   var $NM_flag_antigo; 
   var $nm_campos_cab = array();
   var $NM_cmp_hidden = array();
   var $nmgp_botoes = array();
   var $nmgp_label_quebras = array();
   var $nmgp_prim_pag_pdf;
   var $Campos_Mens_erro;
   var $Print_All;
   var $NM_field_over;
   var $NM_field_click;
   var $progress_fp;
   var $progress_tot;
   var $progress_now;
   var $progress_lim_tot;
   var $progress_lim_now;
   var $progress_lim_qtd;
   var $progress_grid;
   var $progress_pdf;
   var $progress_res;
   var $progress_graf;
   var $count_ger;
   var $pdf;
   var $cedible;
   var $xml;
   var $acuse_recibo;
   var $aprob_comercial;
   var $rec_mercaderia;
   var $tipo_docu;
   var $folio_dte;
   var $est_xdte;
   var $fec_emi_dte;
   var $fech_carg;
   var $rut_rec_dte;
   var $nom_rec_dte;
   var $dir_rec_dte;
   var $com_rec_dte;
   var $mntneto_dte;
   var $mnt_exen_dte;
   var $iva_dte;
   var $mont_tot_dte;
   var $track_id;
   var $path_pdf_cedible;
   var $msg_xdte;
   var $est_rec_doc;
   var $est_res_rev;
   var $est_recibo_mercaderias;
   var $look_est_xdte;
//--- 
 function monta_grid($linhas = 0)
 {
   global $nm_saida;

   clearstatcache();
   $this->NM_cor_embutida();
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
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_init'])
   { 
        return; 
   } 
   $this->inicializa();
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['charts_html'] = '';
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       $this->Lin_impressas = 0;
       $this->Lin_final     = FALSE;
       $this->grid($linhas);
       $this->nm_fim_grid();
   } 
   else 
   { 
       $this->cabecalho();
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
       { 
           $this->nmgp_barra_top();
       } 
       $this->grid();
       $flag_apaga_pdf_log = TRUE;
       if (!$this->Print_All && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
       { 
           $flag_apaga_pdf_log = FALSE;
       } 
       $this->nm_fim_grid($flag_apaga_pdf_log);
       if (!$this->Print_All && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "igual";
       } 
   }
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] == "print")
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_ant'];
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] = "";
   }
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_ant'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'];
 }
 function resume($linhas = 0)
 {
    $this->Lin_impressas = 0;
    $this->Lin_final     = FALSE;
    $this->grid($linhas);
 }
//--- 
 function inicializa()
 {
   global $nm_saida,
   $rec, $nmgp_chave, $nmgp_opcao, $nmgp_ordem, $nmgp_chave_det,
   $nmgp_quant_linhas, $nmgp_quant_colunas, $nmgp_url_saida, $nmgp_parms;
//
   $this->force_toolbar = false;
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['proc_res'] = false;
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['lig_edit']) && $_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['lig_edit'] != '')
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['mostra_edit'] = $_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['lig_edit'];
   }
   $this->grid_emb_form = false;
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_form']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_form'])
   {
       $this->grid_emb_form = true;
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['mostra_edit'] = "N";
   }
   if ($this->Ini->SC_Link_View)
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['mostra_edit'] = "N";
   }
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['NM_arr_tree'] = array();
   }
   $this->Ind_lig_mult = 0;
   $this->nm_data    = new nm_data("es");
   $this->aba_iframe = false;
   $this->Print_All = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['print_all'];
   if ($this->Print_All)
   {
       $this->Ini->nm_limite_lin = $this->Ini->nm_limite_lin_prt; 
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
   $this->nmgp_botoes['exit'] = "on";
   $this->nmgp_botoes['first'] = "on";
   $this->nmgp_botoes['back'] = "on";
   $this->nmgp_botoes['forward'] = "on";
   $this->nmgp_botoes['last'] = "on";
   $this->nmgp_botoes['pdf'] = "on";
   $this->nmgp_botoes['xls'] = "on";
   $this->nmgp_botoes['xml'] = "on";
   $this->nmgp_botoes['qsearch'] = "on";
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display']))
   {
       foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display'] as $NM_cada_btn => $NM_cada_opc)
       {
           $this->nmgp_botoes[$NM_cada_btn] = $NM_cada_opc;
       }
   }
   $this->sc_proc_grid = false; 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
   { 
       $this->NM_raiz_img = $this->Ini->root; 
   } 
   else 
   { 
       $this->NM_raiz_img = ""; 
   } 
   $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
   $this->nm_where_dinamico = "";
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_ant'];  
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
       $fec_emi_dte_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2']; 
       $this->fec_emi_dte_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2']; 
       $this->fech_carg = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg']; 
       $tmp_pos = strpos($this->fech_carg, "##@@");
       if ($tmp_pos !== false)
       {
           $this->fech_carg = substr($this->fech_carg, 0, $tmp_pos);
       }
       $fech_carg_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2']; 
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
   else 
   { 
       $this->fec_emi_dte_2 = ""; 
       $this->fech_carg_2 = ""; 
   } 
   $this->nm_field_dinamico = array();
   $this->nm_order_dinamico = array();
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'];
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "muda_qt_linhas")
   { 
       unset($rec);
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "muda_rec_linhas")
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "muda_qt_linhas";
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   {
       $nmgp_ordem = ""; 
       $rec = "ini"; 
   } 
//
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       include_once($this->Ini->path_embutida . "grid_public_v_dte/grid_public_v_dte_total.class.php"); 
   } 
   else 
   { 
       include_once($this->Ini->path_aplicacao . "grid_public_v_dte_total.class.php"); 
   } 
   $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
   $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
   $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_public_v_dte.php"; 
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_pdf'] != "pdf")  
       { 
           $_SESSION['scriptcase']['contr_link_emb'] = $this->nm_location;
       } 
       else 
       { 
           $_SESSION['scriptcase']['contr_link_emb'] = "pdf";
       } 
   } 
   else 
   { 
       $this->nm_location = $_SESSION['scriptcase']['contr_link_emb'];
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_pdf'] = $_SESSION['scriptcase']['contr_link_emb'];
   } 
   $this->Tot         = new grid_public_v_dte_total($this->Ini->sc_page);
   $this->Tot->Db     = $this->Db;
   $this->Tot->Erro   = $this->Erro;
   $this->Tot->Ini    = $this->Ini;
   $this->Tot->Lookup = $this->Lookup;
   if (empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'] = 20 ;  
   }   
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['rows']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['rows']))
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'] = $_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['rows'];  
       unset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['rows']);
   }
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['cols']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['cols']))
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_col_grid'] = $_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['cols'];  
       unset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['cols']);
   }
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['rows']))
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['rows'];  
   }
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['cols']))
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_col_grid'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['cols'];  
   }
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "muda_qt_linhas") 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao']  = "igual" ;  
       if (!empty($nmgp_quant_linhas)) 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'] = $nmgp_quant_linhas ;  
       } 
   }   
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid']; 
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_select']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_select'] = array(); 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_select']['fec_emi_dte'] = 'DESC'; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_select']['tipo_docu'] = 'asc'; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_select_orig'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_select']; 
   } 
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid'] = "" ; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_ant']  = "" ; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] = "" ; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_cmp']  = "" ; 
   }   
   if (!empty($nmgp_ordem))  
   { 
       $nmgp_ordem = str_replace('\"', '"', $nmgp_ordem); 
       if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_quebra'][$nmgp_ordem])) 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "inicio" ;  
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid'] = ""; 
           if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_quebra'][$nmgp_ordem] == "asc") 
           { 
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_quebra'][$nmgp_ordem] = "desc"; 
           }   
           else   
           { 
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_quebra'][$nmgp_ordem] = "asc"; 
           }   
       }   
       else   
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid'] = $nmgp_ordem  ; 
       }   
   }   
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "ordem")  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "inicio" ;  
       if (($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_ant'] == $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] == "")  
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] = " desc" ; 
       } 
       else   
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] = "" ;  
       } 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_ant'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid'];  
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_cmp'] = $nmgp_ordem;  
   }  
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] = 0 ;  
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']  = 0 ;  
   }   
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_edit'])  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_edit'] = false;  
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "inicio") 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "edit" ; 
       } 
   }   
   if (!empty($nmgp_parms) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")   
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "igual";
       $rec = "ini";
   }
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig']) || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['prim_cons'] || !empty($nmgp_parms))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['prim_cons'] = false;  
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'] = " where codi_empr='" . $_SESSION['v_codi_empr'] . "'";  
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq']        = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];  
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_ant']    = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];  
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq']         = ""; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'] = "";
   }   
   if  (!empty($this->nm_where_dinamico)) 
   {   
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'] .= $this->nm_where_dinamico;
   }   
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'];
   $this->sc_where_atual_f = (!empty($this->sc_where_atual)) ? "(" . trim(substr($this->sc_where_atual, 6)) . ")" : "";
   $this->sc_where_atual_f = str_replace("%", "@percent@", $this->sc_where_atual_f);
   $this->sc_where_atual_f = "NM_where_filter*scin" . str_replace("'", "@aspass@", $this->sc_where_atual_f) . "*scout";
//
//--------- 
//
   $nmgp_opc_orig = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao']; 
   if (isset($rec)) 
   { 
       if ($rec == "ini") 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "inicio" ; 
       } 
       elseif ($rec == "fim") 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "final" ; 
       } 
       else 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "avanca" ; 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final'] = $rec; 
           if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final'] > 0) 
           { 
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']-- ; 
           } 
       } 
   } 
   $this->NM_opcao = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao']; 
   if ($this->NM_opcao == "print") 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] = "print" ; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao']       = "igual" ; 
   } 
// 
   $this->count_ger = 0;
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "final" || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] == "all") 
   { 
       $this->Tot->quebra_geral() ; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['total'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'][1] ;  
       $this->count_ger = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'][1];
   } 
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_dinamic']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_dinamic'] != $this->nm_where_dinamico)  
   { 
       unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral']);
   } 
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_dinamic'] = $this->nm_where_dinamico;  
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral']) || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'] != $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_ant'] || $nmgp_opc_orig == "edit") 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_total_geral'] = "NAO";
       unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['total']);
       $this->Tot->quebra_geral() ; 
   } 
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['total'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'][1] ;  
   $this->count_ger = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'][1];
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] == "all") 
   { 
        $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] = $this->count_ger;
        $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao']       = "inicio";
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "inicio" || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pesq") 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] = 0 ; 
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "final") 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['total'] - $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid']; 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] < 0) 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] = 0 ; 
       } 
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "retorna") 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] - $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid']; 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] < 0) 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] = 0 ; 
       } 
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "avanca" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['total'] >  $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']) 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']; 
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print" && substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'], 0, 7) != "detalhe" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf") 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "igual"; 
   } 
   $this->Rec_ini = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] - $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid']; 
   if ($this->Rec_ini < 0) 
   { 
       $this->Rec_ini = 0; 
   } 
   $this->Rec_fim = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] + $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] + 1; 
   if ($this->Rec_fim > $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['total']) 
   { 
       $this->Rec_fim = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['total']; 
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] > 0) 
   { 
       $this->Rec_ini++ ; 
   } 
   $this->nmgp_reg_start = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio']; 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] > 0) 
   { 
       $this->nmgp_reg_start-- ; 
   } 
   $this->nm_grid_ini = $this->nmgp_reg_start + 1; 
   if ($this->nmgp_reg_start != 0) 
   { 
       $this->nm_grid_ini++;
   }  
//----- 
   $nmgp_select = "SELECT tipo_docu, folio_dte, est_xdte, fec_emi_dte, fech_carg, rut_rec_dte, nom_rec_dte, dir_rec_dte, com_rec_dte, mntneto_dte, mnt_exen_dte, iva_dte, mont_tot_dte, track_id, path_pdf_cedible, msg_xdte, est_rec_doc, est_res_rev, est_recibo_mercaderias from " . $this->Ini->nm_tabela; 
   $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq']; 
   $nmgp_order_by = ""; 
   $campos_order_select = "";
   foreach($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_select'] as $campo => $ordem) 
   {
        if ($campo != $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid']) 
        {
           if (!empty($campos_order_select)) 
           {
               $campos_order_select .= ", ";
           }
           $campos_order_select .= $campo . " " . $ordem;
        }
   }
   if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid'])) 
   { 
       $nmgp_order_by = " order by " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_grid'] . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc']; 
   } 
   if (!empty($campos_order_select)) 
   { 
       if (!empty($nmgp_order_by)) 
       { 
          $nmgp_order_by .= ", " . $campos_order_select; 
       } 
       else 
       { 
          $nmgp_order_by = " order by $campos_order_select"; 
       } 
   } 
   $nmgp_select .= $nmgp_order_by; 
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['order_grid'] = $nmgp_order_by;
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" || isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['paginacao']))
   {
       $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select; 
       $this->rs_grid = $this->Db->Execute($nmgp_select) ; 
   }
   else  
   {
       $_SESSION['scriptcase']['sc_sql_ult_comando'] = "SelectLimit($nmgp_select, " . ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] + 2) . ", $this->nmgp_reg_start)" ; 
       $this->rs_grid = $this->Db->SelectLimit($nmgp_select, $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] + 2, $this->nmgp_reg_start) ; 
   }  
   if ($this->rs_grid === false && !$this->rs_grid->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1) 
   { 
       $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
       exit ; 
   }  
   if ($this->rs_grid->EOF || ($this->rs_grid === false && $GLOBALS["NM_ERRO_IBASE"] == 1)) 
   { 
       $this->nm_grid_sem_reg = $this->Ini->Nm_lang['lang_errm_empt']; 
   }  
   else 
   { 
       $this->tipo_docu = $this->rs_grid->fields[0] ;  
       $this->tipo_docu = (string)$this->tipo_docu;
       $this->folio_dte = $this->rs_grid->fields[1] ;  
       $this->folio_dte =  str_replace(",", ".", $this->folio_dte);
       $this->folio_dte = (string)$this->folio_dte;
       $this->est_xdte = $this->rs_grid->fields[2] ;  
       $this->est_xdte = (string)$this->est_xdte;
       $this->fec_emi_dte = $this->rs_grid->fields[3] ;  
       $this->fech_carg = $this->rs_grid->fields[4] ;  
       $this->rut_rec_dte = $this->rs_grid->fields[5] ;  
       $this->rut_rec_dte = (string)$this->rut_rec_dte;
       $this->nom_rec_dte = $this->rs_grid->fields[6] ;  
       $this->dir_rec_dte = $this->rs_grid->fields[7] ;  
       $this->com_rec_dte = $this->rs_grid->fields[8] ;  
       $this->mntneto_dte = $this->rs_grid->fields[9] ;  
       $this->mntneto_dte =  str_replace(",", ".", $this->mntneto_dte);
       $this->mntneto_dte = (string)$this->mntneto_dte;
       $this->mnt_exen_dte = $this->rs_grid->fields[10] ;  
       $this->mnt_exen_dte =  str_replace(",", ".", $this->mnt_exen_dte);
       $this->mnt_exen_dte = (string)$this->mnt_exen_dte;
       $this->iva_dte = $this->rs_grid->fields[11] ;  
       $this->iva_dte =  str_replace(",", ".", $this->iva_dte);
       $this->iva_dte = (string)$this->iva_dte;
       $this->mont_tot_dte = $this->rs_grid->fields[12] ;  
       $this->mont_tot_dte =  str_replace(",", ".", $this->mont_tot_dte);
       $this->mont_tot_dte = (string)$this->mont_tot_dte;
       $this->track_id = $this->rs_grid->fields[13] ;  
       $this->track_id = (string)$this->track_id;
       $this->path_pdf_cedible = $this->rs_grid->fields[14] ;  
       $this->msg_xdte = $this->rs_grid->fields[15] ;  
       $this->est_rec_doc = $this->rs_grid->fields[16] ;  
       $this->est_res_rev = $this->rs_grid->fields[17] ;  
       $this->est_recibo_mercaderias = $this->rs_grid->fields[18] ;  
       $this->look_est_xdte = $this->est_xdte; 
       $this->Lookup->lookup_est_xdte($this->look_est_xdte); 
       $this->SC_seq_register = $this->nmgp_reg_start ; 
       $this->SC_seq_page = 0; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final'] = $this->nmgp_reg_start ; 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['inicio'] != 0 && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf") 
       { 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']++ ; 
           $this->SC_seq_register = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']; 
           $this->rs_grid->MoveNext(); 
           $this->tipo_docu = $this->rs_grid->fields[0] ;  
           $this->folio_dte = $this->rs_grid->fields[1] ;  
           $this->est_xdte = $this->rs_grid->fields[2] ;  
           $this->fec_emi_dte = $this->rs_grid->fields[3] ;  
           $this->fech_carg = $this->rs_grid->fields[4] ;  
           $this->rut_rec_dte = $this->rs_grid->fields[5] ;  
           $this->nom_rec_dte = $this->rs_grid->fields[6] ;  
           $this->dir_rec_dte = $this->rs_grid->fields[7] ;  
           $this->com_rec_dte = $this->rs_grid->fields[8] ;  
           $this->mntneto_dte = $this->rs_grid->fields[9] ;  
           $this->mnt_exen_dte = $this->rs_grid->fields[10] ;  
           $this->iva_dte = $this->rs_grid->fields[11] ;  
           $this->mont_tot_dte = $this->rs_grid->fields[12] ;  
           $this->track_id = $this->rs_grid->fields[13] ;  
           $this->path_pdf_cedible = $this->rs_grid->fields[14] ;  
           $this->msg_xdte = $this->rs_grid->fields[15] ;  
           $this->est_rec_doc = $this->rs_grid->fields[16] ;  
           $this->est_res_rev = $this->rs_grid->fields[17] ;  
           $this->est_recibo_mercaderias = $this->rs_grid->fields[18] ;  
       } 
   } 
   $this->nmgp_reg_inicial = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final'] + 1;
   $this->nmgp_reg_final   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final'] + $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'];
   $this->nmgp_reg_final   = ($this->nmgp_reg_final > $this->count_ger) ? $this->count_ger : $this->nmgp_reg_final;
// 
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       if (!$this->Print_All && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pdf_res'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_pdf'] != "pdf")
       {
           //---------- Gauge ----------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>Consulta DTE Emitidos :: PDF</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <META http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
 <META http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?>" GMT">
 <META http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
 <META http-equiv="Cache-Control" content="post-check=0, pre-check=0">
 <META http-equiv="Pragma" content="no-cache">
 <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_all ?>_grid.css" /> 
 <link rel="stylesheet" type="text/css" href="../_lib/buttons/<?php echo $this->Ini->Str_btn_css ?>" /> 
 <SCRIPT LANGUAGE="Javascript" SRC="<?php echo $this->Ini->path_js; ?>/nm_gauge.js"></SCRIPT>
</HEAD>
<BODY scrolling="no">
<table class="scGridTabela" style="padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;"><tr class="scGridFieldOddVert"><td>
<?php echo $this->Ini->Nm_lang['lang_pdff_gnrt']; ?>...<br>
<?php
           $this->progress_grid    = $this->rs_grid->RecordCount();
           $this->progress_pdf     = 0;
           $this->progress_res     = 0;
           $this->progress_graf    = 0;
           $this->progress_tot     = 0;
           $this->progress_now     = 0;
           $this->progress_lim_tot = 0;
           $this->progress_lim_now = 0;
           if (-1 < $this->progress_grid)
           {
               $this->progress_lim_qtd = (250 < $this->progress_grid) ? 250 : $this->progress_grid;
               $this->progress_lim_tot = floor($this->progress_grid / $this->progress_lim_qtd);
               $this->progress_pdf     = floor($this->progress_grid * 0.25) + 1;
               $this->progress_tot     = $this->progress_grid + $this->progress_pdf + $this->progress_res + $this->progress_graf;
               $str_pbfile             = isset($_GET['pbfile']) ? urldecode($_GET['pbfile']) : $this->Ini->root . $this->Ini->path_imag_temp . '/sc_pb_' . session_id() . '.tmp';
               $this->progress_fp      = fopen($str_pbfile, 'w');
               fwrite($this->progress_fp, "PDF\n");
               fwrite($this->progress_fp, $this->Ini->path_js   . "\n");
               fwrite($this->progress_fp, $this->Ini->path_prod . "/img/\n");
               fwrite($this->progress_fp, $this->progress_tot   . "\n");
               $lang_protect = $this->Ini->Nm_lang['lang_pdff_strt'];
               if (!NM_is_utf8($lang_protect))
               {
                   $lang_protect = mb_convert_encoding($lang_protect, "UTF-8", $_SESSION['scriptcase']['charset']);
               }
               fwrite($this->progress_fp, "1_#NM#_" . $lang_protect . "...\n");
               flush();
           }
       }
       $nm_fundo_pagina = ""; 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
       {
       $nm_saida->saida("  <html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:word\" xmlns=\"http://www.w3.org/TR/REC-html40\">\r\n");
       }
$nm_saida->saida("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\r\n");
$nm_saida->saida("            \"http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n");
       $nm_saida->saida("  <HTML" . $_SESSION['scriptcase']['reg_conf']['html_dir'] . ">\r\n");
       $nm_saida->saida("  <HEAD>\r\n");
       $nm_saida->saida("   <TITLE>Consulta DTE Emitidos</TITLE>\r\n");
       $nm_saida->saida("   <META http-equiv=\"Content-Type\" content=\"text/html; charset=" . $_SESSION['scriptcase']['charset_html'] . "\" />\r\n");
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
       {
       $nm_saida->saida("   <META http-equiv=\"Expires\" content=\"Fri, Jan 01 1900 00:00:00 GMT\"/>\r\n");
       $nm_saida->saida("   <META http-equiv=\"Last-Modified\" content=\"" . gmdate("D, d M Y H:i:s") . " GMT\"/>\r\n");
       $nm_saida->saida("   <META http-equiv=\"Cache-Control\" content=\"no-store, no-cache, must-revalidate\"/>\r\n");
       $nm_saida->saida("   <META http-equiv=\"Cache-Control\" content=\"post-check=0, pre-check=0\"/>\r\n");
       $nm_saida->saida("   <META http-equiv=\"Pragma\" content=\"no-cache\"/>\r\n");
       }
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       { 
           $css_body = "";
       } 
       else 
       { 
           $css_body = "margin-left:0px;margin-right:0px;margin-top:0px;margin-bottom:0px;";
       } 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
       { 
           $nm_saida->saida("   <script type=\"text/javascript\" src=\"" . $this->Ini->path_prod . "/third/jquery/js/jquery.js\"></script>\r\n");
           $nm_saida->saida("   <script type=\"text/javascript\" src=\"" . $this->Ini->path_prod . "/third/jquery_plugin/malsup-blockui/jquery.blockUI.js\"></script>\r\n");
           $nm_saida->saida("   <script type=\"text/javascript\" src=\"grid_public_v_dte_jquery.js\"></script>\r\n");
           $nm_saida->saida("   <script type=\"text/javascript\">\r\n");
           $nm_saida->saida("     var sc_ajaxBg = '" . $this->Ini->Color_bg_ajax . "';\r\n");
           $nm_saida->saida("     var sc_ajaxBordC = '" . $this->Ini->Border_c_ajax . "';\r\n");
           $nm_saida->saida("     var sc_ajaxBordS = '" . $this->Ini->Border_s_ajax . "';\r\n");
           $nm_saida->saida("     var sc_ajaxBordW = '" . $this->Ini->Border_w_ajax . "';\r\n");
           $nm_saida->saida("   </script>\r\n");
           include_once("grid_public_v_dte_sajax_js.php");
           $nm_saida->saida("   <script type=\"text/javascript\">var sc_pathToTB = '" . $this->Ini->path_prod . "/third/jquery_plugin/thickbox/';</script>\r\n");
           $nm_saida->saida("   <script type=\"text/javascript\" src=\"" . $this->Ini->path_prod . "/third/jquery_plugin/thickbox/thickbox-compressed.js\"></script>\r\n");
           $nm_saida->saida("   <script type=\"text/javascript\" src=\"../_lib/lib/js/jquery.scInput.js\"></script>\r\n");
           $nm_saida->saida("   <link rel=\"stylesheet\" href=\"" . $this->Ini->path_prod . "/third/jquery_plugin/thickbox/thickbox.css\" type=\"text/css\" media=\"screen\" />\r\n");
           $nm_saida->saida("   <link rel=\"stylesheet\" type=\"text/css\" href=\"../_lib/buttons/" . $this->Ini->Str_btn_css . "\" /> \r\n");
           $nm_saida->saida("   <style type=\"text/css\">\r\n");
           $nm_saida->saida("     #quicksearchph_top {\r\n");
           $nm_saida->saida("       position: relative;\r\n");
           $nm_saida->saida("     }\r\n");
           $nm_saida->saida("     #quicksearchph_top img {\r\n");
           $nm_saida->saida("       position: absolute;\r\n");
           $nm_saida->saida("       top: 0;\r\n");
           $nm_saida->saida("       right: 0;\r\n");
           $nm_saida->saida("     }\r\n");
           $nm_saida->saida("   </style>\r\n");
           $nm_saida->saida("   <script type=\"text/javascript\"> \r\n");
           $nm_saida->saida("   var SC_Link_View = false;\r\n");
           if ($this->Ini->SC_Link_View)
           {
               $nm_saida->saida("   SC_Link_View = true;\r\n");
           }
           $nm_saida->saida("   var Qsearch_ok = true;\r\n");
           if (!$this->Ini->SC_Link_View && $this->nmgp_botoes['qsearch'] != "on")
           {
               $nm_saida->saida("   Qsearch_ok = false;\r\n");
           }
           $nm_saida->saida("   var scQSInit = true;\r\n");
           $nm_saida->saida("   var scQtReg  = " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] . ";\r\n");
           $nm_saida->saida("  function SC_init_jquery(){ \r\n");
           $nm_saida->saida("   \$(function(){ \r\n");
           if (!$this->Ini->SC_Link_View && $this->nmgp_botoes['qsearch'] == "on")
           {
               $nm_saida->saida("     \$('#SC_fast_search_top').keyup(function(e) {\r\n");
               $nm_saida->saida("       scQuickSearchKeyUp('top', e);\r\n");
               $nm_saida->saida("     });\r\n");
           }
           $nm_saida->saida("   }); \r\n");
           $nm_saida->saida("  }\r\n");
           $nm_saida->saida("  SC_init_jquery();\r\n");
           $nm_saida->saida("   \$(window).load(function() {\r\n");
           if (!$this->Ini->SC_Link_View && $this->nmgp_botoes['qsearch'] == "on")
           {
               $nm_saida->saida("     scQuickSearchInit(false, '');\r\n");
               $nm_saida->saida("     $('#SC_fast_search_top').listen();\r\n");
               $nm_saida->saida("     scQuickSearchKeyUp('top', null);\r\n");
               $nm_saida->saida("     scQSInit = false;\r\n");
           }
           $nm_saida->saida("   });\r\n");
           $nm_saida->saida("   function scQuickSearchSubmit_top() {\r\n");
           $nm_saida->saida("     document.F0_top.nmgp_opcao.value = 'fast_search';\r\n");
           $nm_saida->saida("     document.F0_top.submit();\r\n");
           $nm_saida->saida("   }\r\n");
           $nm_saida->saida("   function scQuickSearchInit(bPosOnly, sPos) {\r\n");
           $nm_saida->saida("     if (!bPosOnly) {\r\n");
           $nm_saida->saida("       if ('' == sPos || 'top' == sPos) scQuickSearchSize('SC_fast_search_top', 'SC_fast_search_close_top', 'quicksearchph_top');\r\n");
           $nm_saida->saida("     }\r\n");
           $nm_saida->saida("   }\r\n");
           $nm_saida->saida("   function scQuickSearchSize(sIdInput, sIdClose, sPlaceHolder) {\r\n");
           $nm_saida->saida("     var oInput = $('#' + sIdInput),\r\n");
           $nm_saida->saida("         oClose = $('#' + sIdClose),\r\n");
           $nm_saida->saida("         oPlace = $('#' + sPlaceHolder),\r\n");
           $nm_saida->saida("         iInputP = parseInt(oInput.css('padding-right')) || 0,\r\n");
           $nm_saida->saida("         iInputB = parseInt(oInput.css('border-right-width')) || 0,\r\n");
           $nm_saida->saida("         iInputW = oInput.outerWidth(),\r\n");
           $nm_saida->saida("         iPlaceW = oPlace.outerWidth(),\r\n");
           $nm_saida->saida("         oInputO = oInput.offset(),\r\n");
           $nm_saida->saida("         oPlaceO = oPlace.offset(),\r\n");
           $nm_saida->saida("         iNewRight;\r\n");
           $nm_saida->saida("     iNewRight = (iPlaceW - iInputW) - (oInputO.left - oPlaceO.left) + iInputB + 1;\r\n");
           $nm_saida->saida("     oInput.css({\r\n");
           $nm_saida->saida("       'height': Math.max(oInput.height(), 16) + 'px',\r\n");
           $nm_saida->saida("       'padding-right': iInputP + 16 + " . $this->Ini->Str_qs_image_padding . " + 'px'\r\n");
           $nm_saida->saida("     });\r\n");
           $nm_saida->saida("     oClose.css({\r\n");
           $nm_saida->saida("       'right': iNewRight + " . $this->Ini->Str_qs_image_padding . " + 'px',\r\n");
           $nm_saida->saida("       'cursor': 'pointer'\r\n");
           $nm_saida->saida("     });\r\n");
           $nm_saida->saida("   }\r\n");
           $nm_saida->saida("   function scQuickSearchKeyUp(sPos, e) {\r\n");
           $nm_saida->saida("     if (null != e) {\r\n");
           $nm_saida->saida("       var keyPressed = e.charCode || e.keyCode || e.which;\r\n");
           $nm_saida->saida("       if (13 == keyPressed) {\r\n");
           $nm_saida->saida("         if ('top' == sPos) scQuickSearchSubmit_top();\r\n");
           $nm_saida->saida("       }\r\n");
           $nm_saida->saida("     }\r\n");
           $nm_saida->saida("   }\r\n");
           $nm_saida->saida("   </script> \r\n");
       } 
       if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css']))
       {
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] = rand(0, 1000);
       }
       $write_css = true;
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && !$this->Print_All && $this->NM_opcao != "print" && $this->NM_opcao != "pdf")
       {
           $write_css = false;
       }
       if ($write_css) {$NM_css = @fopen($this->Ini->root . $this->Ini->path_imag_temp . '/sc_css_grid_public_v_dte_grid_' . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] . '.css', 'w');}
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       {
           $this->NM_field_over  = 0;
           $this->NM_field_click = 0;
           $Css_sub_cons = array();
           if (($this->NM_opcao == "print" && $GLOBALS['nmgp_cor_print'] == "PB") || ($this->NM_opcao == "pdf" &&  $GLOBALS['nmgp_tipo_pdf'] == "pb") || ($_SESSION['scriptcase']['contr_link_emb'] == "pdf" &&  $GLOBALS['nmgp_tipo_pdf'] == "pb")) 
           { 
               $NM_css_file = $this->Ini->str_schema_all . "_grid_bw.css";
               if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css_bw'])) 
               { 
                   foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css_bw'] as $Apl => $Css_apl)
                   {
                       $Css_sub_cons[] = $Css_apl;
                   }
               } 
           } 
           else 
           { 
               $NM_css_file = $this->Ini->str_schema_all . "_grid.css";
               if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css'])) 
               { 
                   foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css'] as $Apl => $Css_apl)
                   {
                       $Css_sub_cons[] = $Css_apl;
                   }
               } 
           } 
           if (is_file($this->Ini->path_css . $NM_css_file))
           {
               $NM_css_attr = file($this->Ini->path_css . $NM_css_file);
               foreach ($NM_css_attr as $NM_line_css)
               {
                   if (substr(trim($NM_line_css), 0, 16) == ".scGridFieldOver" && strpos($NM_line_css, "background-color:") !== false)
                   {
                       $this->NM_field_over = 1;
                   }
                   if (substr(trim($NM_line_css), 0, 17) == ".scGridFieldClick" && strpos($NM_line_css, "background-color:") !== false)
                   {
                       $this->NM_field_click = 1;
                   }
                   $NM_line_css = str_replace("../../img", $this->Ini->path_imag_cab  , $NM_line_css);
                   if ($write_css) {@fwrite($NM_css, "    " .  $NM_line_css . "\r\n");}
               }
           }
           if (!empty($Css_sub_cons))
           {
               $Css_sub_cons = array_unique($Css_sub_cons);
               foreach ($Css_sub_cons as $Cada_css_sub)
               {
                   if (is_file($this->Ini->path_css . $Cada_css_sub))
                   {
                       $compl_css = str_replace(".", "_", $Cada_css_sub);
                       $temp_css  = explode("/", $compl_css);
                       if (isset($temp_css[1])) { $compl_css = $temp_css[1];}
                       $NM_css_attr = file($this->Ini->path_css . $Cada_css_sub);
                       foreach ($NM_css_attr as $NM_line_css)
                       {
                           $NM_line_css = str_replace("../../img", $this->Ini->path_imag_cab  , $NM_line_css);
                           if ($write_css) {@fwrite($NM_css, "    ." .  $compl_css . "_" . substr(trim($NM_line_css), 1) . "\r\n");}
                       }
                   }
               }
           }
       }
       if ($write_css) {@fclose($NM_css);}
       if (!$write_css)
       {
           $nm_saida->saida("   <link rel=\"stylesheet\" href=\"../_lib/css/" . $this->Ini->str_schema_all . "_grid.css\" type=\"text/css\" media=\"screen\" />\r\n");
           $nm_saida->saida("   <link rel=\"stylesheet\" href=\"../_lib/css/" . $_SESSION['scriptcase']['erro']['str_schema'] . "\" type=\"text/css\" media=\"screen\" />\r\n");
       }
       elseif ($this->NM_opcao == "print" || $this->Print_All)
       {
           $nm_saida->saida("  <style type=\"text/css\">\r\n");
           $NM_css = file($this->Ini->root . $this->Ini->path_imag_temp . '/sc_css_grid_public_v_dte_grid_' . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] . '.css');
           foreach ($NM_css as $cada_css)
           {
              $nm_saida->saida("  " . str_replace("\r\n", "", $cada_css) . "\r\n");
           }
           $nm_saida->saida("   <link rel=\"stylesheet\" href=\"../_lib/css/" . $_SESSION['scriptcase']['erro']['str_schema'] . "\" type=\"text/css\" media=\"screen\" />\r\n");
           $nm_saida->saida("  </style>\r\n");
       }
       else
       {
           $nm_saida->saida("   <link rel=\"stylesheet\" href=\"" . $this->Ini->path_imag_temp . "/sc_css_grid_public_v_dte_grid_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] . ".css\" type=\"text/css\" media=\"screen\" />\r\n");
           $nm_saida->saida("   <link rel=\"stylesheet\" href=\"../_lib/css/" . $_SESSION['scriptcase']['erro']['str_schema'] . "\" type=\"text/css\" media=\"screen\" />\r\n");
       }
       $str_iframe_body = ($this->aba_iframe) ? 'marginwidth="0px" marginheight="0px" topmargin="0px" leftmargin="0px"' : '';
       $nm_saida->saida("  <style type=\"text/css\">\r\n");
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_pdf'] != "pdf")
       {
           $nm_saida->saida("  .css_iframes   { margin-bottom: 0px; margin-left: 0px;  margin-right: 0px;  margin-top: 0px; }\r\n");
       }
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       { 
           $nm_saida->saida("       .ttip {border:1px solid black;font-size:12px;layer-background-color:lightyellow;background-color:lightyellow}\r\n");
       } 
       $nm_saida->saida("  </style>\r\n");
       $nm_saida->saida("  </HEAD>\r\n");
   } 
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       $nm_saida->saida("  <body class=\"" . $this->css_scGridPage . "\" " . $str_iframe_body . " style=\"" . $css_body . "\">\r\n");
       $nm_saida->saida("  " . $this->Ini->Ajax_result_set . "\r\n");
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" && !$this->Print_All)
       { 
           $nm_saida->saida("      <div style=\"height:1px;overflow:hidden\"><H1 style=\"font-size:0;padding:1px\"></H1></div>\r\n");
       } 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
       { 
           $nm_saida->saida("      <div id=\"tooltip\" style=\"position:absolute;visibility:hidden;border:1px solid black;font-size:12px;layer-background-color:lightyellow;background-color:lightyellow;padding:1px\"></div>\r\n");
       } 
       $this->Tab_align  = "center";
       $this->Tab_valign = "top";
       $this->Tab_width = "";
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       { 
           $this->form_navegacao();
       } 
       $nm_saida->saida("   <TABLE id=\"main_table_grid\" class=\"scGridBorder\" align=\"" . $this->Tab_align . "\" valign=\"" . $this->Tab_valign . "\" " . $this->Tab_width . ">\r\n");
       $nm_saida->saida("  <div id=\"id_div_process\" style=\"display: none; margin: 10px; whitespace: nowrap\" class=\"scFormProcessFixed\"><span class=\"scFormProcess\"><img border=\"0\" src=\"" . $this->Ini->path_icones . "/scriptcase__NM__ajax_load.gif\" align=\"absmiddle\" />&nbsp;" . $this->Ini->Nm_lang['lang_othr_prcs'] . "...</span></div>\r\n");
       $nm_saida->saida("  <div id=\"id_div_process_block\" style=\"display: none; margin: 10px; whitespace: nowrap\"><span class=\"scFormProcess\"><img border=\"0\" src=\"" . $this->Ini->path_icones . "/scriptcase__NM__ajax_load.gif\" align=\"absmiddle\" />&nbsp;" . $this->Ini->Nm_lang['lang_othr_prcs'] . "...</span></div>\r\n");
       $nm_saida->saida("  <div id=\"id_fatal_error\" class=\"" . $this->css_scGridLabel . "\" style=\"display: none; position: absolute\"></div>\r\n");
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print") 
       { 
           $nm_saida->saida("    <TR>\r\n");
           $nm_saida->saida("    <TD  colspan=3 style=\"padding: 0px; border-width: 0px; vertical-align: top;\">\r\n");
           $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_A_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_A_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
           $nm_saida->saida("    </TD>\r\n");
           $nm_saida->saida("    </TR>\r\n");
           $nm_saida->saida("    <TR>\r\n");
           $nm_saida->saida("    <TD style=\"padding: 0px; border-width: 0px; vertical-align: top;\">\r\n");
           $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_E_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_E_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
           $nm_saida->saida("    </TD>\r\n");
           $nm_saida->saida("    <td style=\"padding: 0px;  vertical-align: top;\"><table style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\" width=\"100%\"><tr>\r\n");
           $nm_saida->saida("    <TD colspan=3 style=\"padding: 0px; border-width: 0px; vertical-align: top;\">\r\n");
      $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_AL_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_AL_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
           $nm_saida->saida("    </TD>\r\n");
           $nm_saida->saida("    </TR>\r\n");
        } 
   }  
 }  
 function NM_cor_embutida()
 {  
   $compl_css = "";
   include($this->Ini->path_btn . $this->Ini->Str_btn_grid);
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   {
   }
   elseif ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['SC_herda_css'] == "N")
   {
       if (($this->NM_opcao == "print" && $GLOBALS['nmgp_cor_print'] == "PB") || ($this->NM_opcao == "pdf" &&  $GLOBALS['nmgp_tipo_pdf'] == "pb") || ($_SESSION['scriptcase']['contr_link_emb'] == "pdf" &&  $GLOBALS['nmgp_tipo_pdf'] == "pb")) 
       { 
           if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css_bw']['grid_public_v_dte']))
           {
               $compl_css = str_replace(".", "_", $_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css_bw']['grid_public_v_dte']) . "_";
           } 
       } 
       else 
       { 
           if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css']['grid_public_v_dte']))
           {
               $compl_css = str_replace(".", "_", $_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css']['grid_public_v_dte']) . "_";
           } 
       }
   }
   $temp_css  = explode("/", $compl_css);
   if (isset($temp_css[1])) { $compl_css = $temp_css[1];}
   $this->css_scGridPage          = $compl_css . "scGridPage";
   $this->css_scGridPageLink      = $compl_css . "scGridPageLink";
   $this->css_scGridToolbar       = $compl_css . "scGridToolbar";
   $this->css_scGridToolbarPadd   = $compl_css . "scGridToolbarPadding";
   $this->css_css_toolbar_obj     = $compl_css . "css_toolbar_obj";
   $this->css_scGridHeader        = $compl_css . "scGridHeader";
   $this->css_scGridHeaderFont    = $compl_css . "scGridHeaderFont";
   $this->css_scGridFooter        = $compl_css . "scGridFooter";
   $this->css_scGridFooterFont    = $compl_css . "scGridFooterFont";
   $this->css_scGridBlock         = $compl_css . "scGridBlock";
   $this->css_scGridBlockFont     = $compl_css . "scGridBlockFont";
   $this->css_scGridBlockAlign    = $compl_css . "scGridBlockAlign";
   $this->css_scGridTotal         = $compl_css . "scGridTotal";
   $this->css_scGridTotalFont     = $compl_css . "scGridTotalFont";
   $this->css_scGridSubtotal      = $compl_css . "scGridSubtotal";
   $this->css_scGridSubtotalFont  = $compl_css . "scGridSubtotalFont";
   $this->css_scGridFieldEven     = $compl_css . "scGridFieldEven";
   $this->css_scGridFieldEvenFont = $compl_css . "scGridFieldEvenFont";
   $this->css_scGridFieldEvenVert = $compl_css . "scGridFieldEvenVert";
   $this->css_scGridFieldEvenLink = $compl_css . "scGridFieldEvenLink";
   $this->css_scGridFieldOdd      = $compl_css . "scGridFieldOdd";
   $this->css_scGridFieldOddFont  = $compl_css . "scGridFieldOddFont";
   $this->css_scGridFieldOddVert  = $compl_css . "scGridFieldOddVert";
   $this->css_scGridFieldOddLink  = $compl_css . "scGridFieldOddLink";
   $this->css_scGridFieldClick    = $compl_css . "scGridFieldClick";
   $this->css_scGridFieldOver     = $compl_css . "scGridFieldOver";
   $this->css_scGridLabel         = $compl_css . "scGridLabel";
   $this->css_scGridLabelVert     = $compl_css . "scGridLabelVert";
   $this->css_scGridLabelFont     = $compl_css . "scGridLabelFont";
   $this->css_scGridLabelLink     = $compl_css . "scGridLabelLink";
   $this->css_scGridTabela        = $compl_css . "scGridTabela";
   $this->css_scGridTabelaTd      = $compl_css . "scGridTabelaTd";
   $this->css_scGridBlockBg       = $compl_css . "scGridBlockBg";
   $this->css_scGridBlockLineBg   = $compl_css . "scGridBlockLineBg";
   $this->css_scGridBlockSpaceBg  = $compl_css . "scGridBlockSpaceBg";
   $this->css_scGridLabelNowrap   = "";
 }  
// 
//----- 
 function cabecalho()
 {
   global
          $nm_saida;
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['cab']))
   {
       return; 
   }
   $nm_cab_filtro   = ""; 
   $nm_cab_filtrobr = ""; 
   $Str_date = strtolower($_SESSION['scriptcase']['reg_conf']['date_format']);
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
   $Str  = "";
   foreach ($Arr_D as $Cada_d)
   {
       $Str .= (!$Prim) ? $_SESSION['scriptcase']['reg_conf']['date_sep'] : "";
       $Str .= $Cada_d;
       $Prim = false;
   }
   $Str = str_replace("a", "Y", $Str);
   $Str = str_replace("y", "Y", $Str);
   $nm_data_fixa = date($Str); 
   $this->sc_proc_grid = false; 
   $HTTP_REFERER = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ""; 
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'];
   if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq']))
   {  
       $pos       = 0;
       $trab_pos  = false;
       $pos_tmp   = true; 
       $tmp       = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'];
       while ($pos_tmp)
       {
          $pos = strpos($tmp, "##*@@", $pos);
          if ($pos !== false)
          {
              $trab_pos = $pos;
              $pos += 4;
          }
          else
          {
              $pos_tmp = false;
          }
       }
       $nm_cond_filtro_or  = (substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'], $trab_pos + 5) == "or")  ? " " . trim($this->Ini->Nm_lang['lang_srch_orrr']) . " " : "";
       $nm_cond_filtro_and = (substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'], $trab_pos + 5) == "and") ? " " . trim($this->Ini->Nm_lang['lang_srch_andd']) . " " : "";
       $nm_cab_filtro   = substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cond_pesq'], 0, $trab_pos);
       $nm_cab_filtrobr = str_replace("##*@@", ", " . $nm_cond_filtro_or . "<br />", $nm_cab_filtro);
       $pos       = 0;
       $trab_pos  = false;
       $pos_tmp   = true; 
       $tmp       = $nm_cab_filtro;
       while ($pos_tmp)
       {
          $pos = strpos($tmp, "##*@@", $pos);
          if ($pos !== false)
          {
              $trab_pos = $pos;
              $pos += 4;
          }
          else
          {
              $pos_tmp = false;
          }
       }
       if ($trab_pos === false)
       {
       }
       else  
       {  
          $nm_cab_filtro = substr($nm_cab_filtro, 0, $trab_pos) . " " .  $nm_cond_filtro_or . $nm_cond_filtro_and . substr($nm_cab_filtro, $trab_pos + 5);
          $nm_cab_filtro = str_replace("##*@@", ", " . $nm_cond_filtro_or, $nm_cab_filtro);
       }   
   }   
   $this->nm_data->SetaData(date("Y/m/d H:i:s"), "YYYY/MM/DD HH:II:SS"); 
   $nm_saida->saida(" <TR id=\"sc_grid_head\">\r\n");
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sv_dt_head']))
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sv_dt_head'] = array();
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sv_dt_head']['fix'] = $nm_data_fixa;
       $nm_refresch_cab_rod = true;
   } 
   else 
   { 
       $nm_refresch_cab_rod = false;
   } 
   foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sv_dt_head'] as $ind => $val)
   {
       $tmp_var = "sc_data_cab" . $ind;
       if ($$tmp_var != $val)
       {
           $nm_refresch_cab_rod = true;
           break;
       }
   }
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sv_dt_head']['fix'] != $nm_data_fixa)
   {
       $nm_refresch_cab_rod = true;
   }
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'] && $nm_refresch_cab_rod)
   { 
       $_SESSION['scriptcase']['saida_html'] = "";
   } 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sv_dt_head']['fix'] = $nm_data_fixa;
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
   { 
       $nm_saida->saida("  <TD class=\"" . $this->css_scGridTabelaTd . "\" colspan=3 style=\"vertical-align: top\">\r\n");
   } 
   else 
   { 
       $nm_saida->saida("  <TD class=\"" . $this->css_scGridTabelaTd . "\" style=\"vertical-align: top\">\r\n");
   } 
   $nm_saida->saida("<style>\r\n");
   $nm_saida->saida("#lin1_col1 { padding-left:9px; padding-top:7px;  height:27px; overflow:hidden; text-align:left;}			 \r\n");
   $nm_saida->saida("#lin1_col2 { padding-right:9px; padding-top:7px; height:27px; text-align:right; overflow:hidden;   font-size:12px; font-weight:normal;}\r\n");
   $nm_saida->saida("</style>\r\n");
   $nm_saida->saida("<div style=\"width: 100%\">\r\n");
   $nm_saida->saida(" <div class=\"" . $this->css_scGridHeader . "\" style=\"height:11px; display: block; border-width:0px; \"></div>\r\n");
   $nm_saida->saida(" <div style=\"height:37px; background-color:#FFFFFF; border-width:0px 0px 1px 0px;  border-style: dashed; border-color:#ddd; display: block\">\r\n");
   $nm_saida->saida(" 	<table style=\"width:100%; border-collapse:collapse; padding:0;\">\r\n");
   $nm_saida->saida("    	<tr>\r\n");
   $nm_saida->saida("        	<td id=\"lin1_col1\" class=\"" . $this->css_scGridHeaderFont . "\"><span>Consulta DTE Emitidos</span></td>\r\n");
   $nm_saida->saida("            <td id=\"lin1_col2\" class=\"" . $this->css_scGridHeaderFont . "\"><span>" . $nm_data_fixa . "</span></td>\r\n");
   $nm_saida->saida("        </tr>\r\n");
   $nm_saida->saida("    </table>		 \r\n");
   $nm_saida->saida(" </div>\r\n");
   $nm_saida->saida("</div>\r\n");
   $nm_saida->saida("  </TD>\r\n");
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'] && $nm_refresch_cab_rod)
   { 
       $this->Ini->Arr_result['setValue'][] = array('field' => 'sc_grid_head', 'value' => NM_charset_to_utf8($_SESSION['scriptcase']['saida_html']));
       $_SESSION['scriptcase']['saida_html'] = "";
   } 
   $nm_saida->saida(" </TR>\r\n");
 }
// 
 function label_grid($linhas = 0)
 {
   global 
           $nm_saida;
   static $nm_seq_titulos   = 0; 
   $contr_embutida = false;
   $salva_htm_emb  = "";
   if (1 < $linhas)
   {
      $this->Lin_impressas++;
   }
   $nm_seq_titulos++; 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['exibe_titulos'] != "S")
   { 
   } 
   else 
   { 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_label'])
      { 
          if (!isset($_SESSION['scriptcase']['saida_var']) || !$_SESSION['scriptcase']['saida_var']) 
          { 
              $_SESSION['scriptcase']['saida_var']  = true;
              $_SESSION['scriptcase']['saida_html'] = "";
              $contr_embutida = true;
          } 
          else 
          { 
              $salva_htm_emb = $_SESSION['scriptcase']['saida_html'];
              $_SESSION['scriptcase']['saida_html'] = "";
          } 
      } 
   $nm_saida->saida("    <TR id=\"tit_grid_public_v_dte#?#" . $nm_seq_titulos . "\" align=\"center\" class=\"" . $this->css_scGridLabel . "\">\r\n");
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq']) { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:left;vertical-align:middle;font-weight:bold;\" >&nbsp;</TD>\r\n");
   } 
   foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['field_order'] as $Cada_label)
   { 
       $NM_func_lab = "NM_label_" . $Cada_label;
       $this->$NM_func_lab();
   } 
   $nm_saida->saida("</TR>\r\n");
     if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_label'])
     { 
         if (isset($_SESSION['scriptcase']['saida_var']) && $_SESSION['scriptcase']['saida_var'])
         { 
             $Cod_Html = $_SESSION['scriptcase']['saida_html'];
             $pos_tag = strpos($Cod_Html, "<TD ");
             $Cod_Html = substr($Cod_Html, $pos_tag);
             $pos      = 0;
             $pos_tag  = false;
             $pos_tmp  = true; 
             $tmp      = $Cod_Html;
             while ($pos_tmp)
             {
                $pos = strpos($tmp, "</TR>", $pos);
                if ($pos !== false)
                {
                    $pos_tag = $pos;
                    $pos += 4;
                }
                else
                {
                    $pos_tmp = false;
                }
             }
             $Cod_Html = substr($Cod_Html, 0, $pos_tag);
             $Nm_temp = explode("</TD>", $Cod_Html);
             $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cols_emb'] = count($Nm_temp) - 1;
             if ($contr_embutida) 
             { 
                 $_SESSION['scriptcase']['saida_var']  = false;
                 $nm_saida->saida($Cod_Html);
             } 
             else 
             { 
                 $_SESSION['scriptcase']['saida_html'] = $salva_htm_emb . $Cod_Html;
             } 
         } 
     } 
   } 
 }
 function NM_label_tipo_docu()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['tipo_docu'])) ? $this->New_label['tipo_docu'] : "Tipo DTE"; 
   if (!isset($this->NM_cmp_hidden['tipo_docu']) || $this->NM_cmp_hidden['tipo_docu'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >\r\n");
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
   {
      $link_img = "";
      $nome_img = $this->Ini->Label_sort;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_cmp'] == 'tipo_docu')
      {
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] == " desc")
          {
              $nome_img = $this->Ini->Label_sort_desc;
          }
          else
          {
              $nome_img = $this->Ini->Label_sort_asc;
          }
      }
      if (empty($this->Ini->Label_sort_pos) || $this->Ini->Label_sort_pos == "right")
      {
          $this->Ini->Label_sort_pos = "right_field";
      }
      if (empty($nome_img))
      {
          $link_img = nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_field")
      {
          $link_img = nl2br($SC_Label) . "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>";
      }
      elseif ($this->Ini->Label_sort_pos == "left_field")
      {
          $link_img = "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_cell")
      {
          $link_img = "<IMG style=\"float: right\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "left_cell")
      {
          $link_img = "<IMG style=\"float: left\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit2('tipo_docu')\" class=\"" . $this->css_scGridLabelLink . "\">" . $link_img . "</a>\r\n");
   }
   else
   {
   $nm_saida->saida("" . nl2br($SC_Label) . "\r\n");
   }
   $nm_saida->saida("</TD>\r\n");
   } 
 }
 function NM_label_folio_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['folio_dte'])) ? $this->New_label['folio_dte'] : "Folio Dte"; 
   if (!isset($this->NM_cmp_hidden['folio_dte']) || $this->NM_cmp_hidden['folio_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >\r\n");
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
   {
      $link_img = "";
      $nome_img = $this->Ini->Label_sort;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_cmp'] == 'folio_dte')
      {
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] == " desc")
          {
              $nome_img = $this->Ini->Label_sort_desc;
          }
          else
          {
              $nome_img = $this->Ini->Label_sort_asc;
          }
      }
      if (empty($this->Ini->Label_sort_pos) || $this->Ini->Label_sort_pos == "right")
      {
          $this->Ini->Label_sort_pos = "right_field";
      }
      if (empty($nome_img))
      {
          $link_img = nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_field")
      {
          $link_img = nl2br($SC_Label) . "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>";
      }
      elseif ($this->Ini->Label_sort_pos == "left_field")
      {
          $link_img = "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_cell")
      {
          $link_img = "<IMG style=\"float: right\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "left_cell")
      {
          $link_img = "<IMG style=\"float: left\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit2('folio_dte')\" class=\"" . $this->css_scGridLabelLink . "\">" . $link_img . "</a>\r\n");
   }
   else
   {
   $nm_saida->saida("" . nl2br($SC_Label) . "\r\n");
   }
   $nm_saida->saida("</TD>\r\n");
   } 
 }
 function NM_label_est_xdte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['est_xdte'])) ? $this->New_label['est_xdte'] : "Estado DTE"; 
   if (!isset($this->NM_cmp_hidden['est_xdte']) || $this->NM_cmp_hidden['est_xdte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_fec_emi_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['fec_emi_dte'])) ? $this->New_label['fec_emi_dte'] : "Fecha Emisión"; 
   if (!isset($this->NM_cmp_hidden['fec_emi_dte']) || $this->NM_cmp_hidden['fec_emi_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:center;vertical-align:middle;font-weight:bold;\" >\r\n");
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
   {
      $link_img = "";
      $nome_img = $this->Ini->Label_sort;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_cmp'] == 'fec_emi_dte')
      {
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] == " desc")
          {
              $nome_img = $this->Ini->Label_sort_desc;
          }
          else
          {
              $nome_img = $this->Ini->Label_sort_asc;
          }
      }
      if (empty($this->Ini->Label_sort_pos) || $this->Ini->Label_sort_pos == "right")
      {
          $this->Ini->Label_sort_pos = "right_field";
      }
      if (empty($nome_img))
      {
          $link_img = nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_field")
      {
          $link_img = nl2br($SC_Label) . "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>";
      }
      elseif ($this->Ini->Label_sort_pos == "left_field")
      {
          $link_img = "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_cell")
      {
          $link_img = "<IMG style=\"float: right\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "left_cell")
      {
          $link_img = "<IMG style=\"float: left\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit2('fec_emi_dte')\" class=\"" . $this->css_scGridLabelLink . "\">" . $link_img . "</a>\r\n");
   }
   else
   {
   $nm_saida->saida("" . nl2br($SC_Label) . "\r\n");
   }
   $nm_saida->saida("</TD>\r\n");
   } 
 }
 function NM_label_fech_carg()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['fech_carg'])) ? $this->New_label['fech_carg'] : "Fecha Carga"; 
   if (!isset($this->NM_cmp_hidden['fech_carg']) || $this->NM_cmp_hidden['fech_carg'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:center;vertical-align:middle;font-weight:bold;\" >\r\n");
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
   {
      $link_img = "";
      $nome_img = $this->Ini->Label_sort;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_cmp'] == 'fech_carg')
      {
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ordem_desc'] == " desc")
          {
              $nome_img = $this->Ini->Label_sort_desc;
          }
          else
          {
              $nome_img = $this->Ini->Label_sort_asc;
          }
      }
      if (empty($this->Ini->Label_sort_pos) || $this->Ini->Label_sort_pos == "right")
      {
          $this->Ini->Label_sort_pos = "right_field";
      }
      if (empty($nome_img))
      {
          $link_img = nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_field")
      {
          $link_img = nl2br($SC_Label) . "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>";
      }
      elseif ($this->Ini->Label_sort_pos == "left_field")
      {
          $link_img = "<IMG SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "right_cell")
      {
          $link_img = "<IMG style=\"float: right\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
      elseif ($this->Ini->Label_sort_pos == "left_cell")
      {
          $link_img = "<IMG style=\"float: left\" SRC=\"" . $this->Ini->path_img_global . "/" . $nome_img . "\" BORDER=\"0\"/>" . nl2br($SC_Label);
      }
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit2('fech_carg')\" class=\"" . $this->css_scGridLabelLink . "\">" . $link_img . "</a>\r\n");
   }
   else
   {
   $nm_saida->saida("" . nl2br($SC_Label) . "\r\n");
   }
   $nm_saida->saida("</TD>\r\n");
   } 
 }
 function NM_label_rut_rec_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['rut_rec_dte'])) ? $this->New_label['rut_rec_dte'] : "Rut Recep."; 
   if (!isset($this->NM_cmp_hidden['rut_rec_dte']) || $this->NM_cmp_hidden['rut_rec_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_nom_rec_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['nom_rec_dte'])) ? $this->New_label['nom_rec_dte'] : "Nombre Recep."; 
   if (!isset($this->NM_cmp_hidden['nom_rec_dte']) || $this->NM_cmp_hidden['nom_rec_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:left;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_dir_rec_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['dir_rec_dte'])) ? $this->New_label['dir_rec_dte'] : "Dir."; 
   if (!isset($this->NM_cmp_hidden['dir_rec_dte']) || $this->NM_cmp_hidden['dir_rec_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:left;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_com_rec_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['com_rec_dte'])) ? $this->New_label['com_rec_dte'] : "Comuna"; 
   if (!isset($this->NM_cmp_hidden['com_rec_dte']) || $this->NM_cmp_hidden['com_rec_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:left;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_mntneto_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['mntneto_dte'])) ? $this->New_label['mntneto_dte'] : "Monto Neto"; 
   if (!isset($this->NM_cmp_hidden['mntneto_dte']) || $this->NM_cmp_hidden['mntneto_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_mnt_exen_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['mnt_exen_dte'])) ? $this->New_label['mnt_exen_dte'] : "Monto Exento"; 
   if (!isset($this->NM_cmp_hidden['mnt_exen_dte']) || $this->NM_cmp_hidden['mnt_exen_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_iva_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['iva_dte'])) ? $this->New_label['iva_dte'] : "IVA  "; 
   if (!isset($this->NM_cmp_hidden['iva_dte']) || $this->NM_cmp_hidden['iva_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_mont_tot_dte()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['mont_tot_dte'])) ? $this->New_label['mont_tot_dte'] : "Total"; 
   if (!isset($this->NM_cmp_hidden['mont_tot_dte']) || $this->NM_cmp_hidden['mont_tot_dte'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_track_id()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['track_id'])) ? $this->New_label['track_id'] : "Track Id"; 
   if (!isset($this->NM_cmp_hidden['track_id']) || $this->NM_cmp_hidden['track_id'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:right;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_pdf()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['pdf'])) ? $this->New_label['pdf'] : "PDF"; 
   if (!isset($this->NM_cmp_hidden['pdf']) || $this->NM_cmp_hidden['pdf'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:center;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_cedible()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['cedible'])) ? $this->New_label['cedible'] : "Cedible"; 
   if (!isset($this->NM_cmp_hidden['cedible']) || $this->NM_cmp_hidden['cedible'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:center;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_xml()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['xml'])) ? $this->New_label['xml'] : "XML"; 
   if (!isset($this->NM_cmp_hidden['xml']) || $this->NM_cmp_hidden['xml'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:center;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_acuse_recibo()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['acuse_recibo'])) ? $this->New_label['acuse_recibo'] : "Acuse"; 
   if (!isset($this->NM_cmp_hidden['acuse_recibo']) || $this->NM_cmp_hidden['acuse_recibo'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:left;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_aprob_comercial()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['aprob_comercial'])) ? $this->New_label['aprob_comercial'] : "Aprob."; 
   if (!isset($this->NM_cmp_hidden['aprob_comercial']) || $this->NM_cmp_hidden['aprob_comercial'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:left;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
 function NM_label_rec_mercaderia()
 {
   global $nm_saida;
   $SC_Label = (isset($this->New_label['rec_mercaderia'])) ? $this->New_label['rec_mercaderia'] : "RM "; 
   if (!isset($this->NM_cmp_hidden['rec_mercaderia']) || $this->NM_cmp_hidden['rec_mercaderia'] != "off") { 
   $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\"  style=\"" . $this->css_scGridLabelNowrap . "text-align:left;vertical-align:middle;font-weight:bold;\" >" . nl2br($SC_Label) . "</TD>\r\n");
   } 
 }
// 
//----- 
 function grid($linhas = 0)
 {
    global 
           $nm_saida;
   $fecha_tr               = "</tr>";
   $this->Ini->qual_linha  = "par";
   $HTTP_REFERER = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ""; 
   $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['rows_emb'] = 0;
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   {
       if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ini_cor_grid']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ini_cor_grid'] == "impar")
       {
           $this->Ini->qual_linha = "impar";
           unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ini_cor_grid']);
       }
   }
   static $nm_seq_execucoes = 0; 
   static $nm_seq_titulos   = 0; 
   $this->Rows_span = 1;
   $nm_seq_execucoes++; 
   $nm_seq_titulos++; 
   $this->nm_prim_linha  = true; 
   $this->Ini->nm_cont_lin = 0; 
   $this->sc_where_orig    = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_orig'];
   $this->sc_where_atual   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq'];
   $this->sc_where_filtro  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq_filtro'];
   if (!$this->grid_emb_form && isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['lig_edit']) && $_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['lig_edit'] != '')
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['mostra_edit'] = $_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['lig_edit'];
   }
   if (!empty($this->nm_grid_sem_reg))
   {
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       {
           $this->Lin_impressas++;
           if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_grid'])
           {
               $NM_span_sem_reg  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cols_emb'];
               $nm_saida->saida("  <TR> <TD class=\"" . $this->css_scGridFieldOdd . "\" colspan = \"$NM_span_sem_reg\" align=\"center\" style=\"vertical-align: top;font-family:" . $this->Ini->texto_fonte_tipo_impar . ";font-size:12px;color:#000000;\">\r\n");
               $nm_saida->saida("     " . $this->nm_grid_sem_reg . "</TD> </TR>\r\n");
               $nm_saida->saida("##NM@@\r\n");
               $this->rs_grid->Close();
           }
           else
           {
               $nm_saida->saida("<table id=\"apl_grid_public_v_dte#?#$nm_seq_execucoes\" style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\">\r\n");
               $nm_saida->saida("  <tr><td style=\"padding: 0px; font-family:" . $this->Ini->texto_fonte_tipo_impar . ";font-size:12px;color:#000000;\"><table style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\" width=\"100%\">\r\n");
               $nm_id_aplicacao = "";
               if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cab_embutida'] != "S")
               {
                   $this->label_grid($linhas);
               }
               $this->NM_calc_span();
               $nm_saida->saida("  <tr><td class=\"" . $this->css_scGridFieldOdd . "\"  style=\"padding: 0px; font-family:" . $this->Ini->texto_fonte_tipo_impar . ";font-size:12px;color:#000000;\" colspan = \"" . $this->NM_colspan . "\" align=\"center\">\r\n");
               $nm_saida->saida("     " . $this->nm_grid_sem_reg . "\r\n");
               $nm_saida->saida("  </td></tr>\r\n");
               $nm_saida->saida("  </table></td></tr></table>\r\n");
               $this->Lin_final = $this->rs_grid->EOF;
               if ($this->Lin_final)
               {
                   $this->rs_grid->Close();
               }
           }
       }
       else
       {
           if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && 
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print") 
           { 
               $nm_saida->saida("    <TD style=\"padding: 0px; border-width: 0px; vertical-align: top;\">\r\n");
      $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_EL_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_EL_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
               $nm_saida->saida("    </TD>\r\n");
               $nm_saida->saida("    <TD style=\"padding: 0px; border-width: 0px; vertical-align: top;\"><TABLE style=\"padding: 0px; border-spacing: 0px; border-width: 0px;\" width=\"100%\"><TR>\r\n");
           } 
           $nm_saida->saida("  <tr><td id=\"sc_grid_body\" class=\"" . $this->css_scGridTabelaTd . " " . $this->css_scGridFieldOdd . "\" align=\"center\" style=\"vertical-align: top;font-family:" . $this->Ini->texto_fonte_tipo_impar . ";font-size:12px;color:#000000;\">\r\n");
           if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['force_toolbar']))
           { 
               $this->force_toolbar = true;
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['force_toolbar'] = true;
           } 
           if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
           { 
               $_SESSION['scriptcase']['saida_html'] = "";
           } 
           $nm_saida->saida("  " . $this->nm_grid_sem_reg . "\r\n");
           if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
           { 
               $this->Ini->Arr_result['setValue'][] = array('field' => 'sc_grid_body', 'value' => NM_charset_to_utf8($_SESSION['scriptcase']['saida_html']));
               $_SESSION['scriptcase']['saida_html'] = "";
           } 
           $nm_saida->saida("  </td></tr>\r\n");
           $this->nmgp_barra_bot();
           if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" &&
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print") 
           { 
               $nm_saida->saida("</TABLE></TD>\r\n");
               $nm_saida->saida("<TD style=\"padding: 0px; border-width: 0px;\" valign=\"top\">\r\n");
      $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_DL_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_DL_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
               $nm_saida->saida("</TD>\r\n");
               $nm_saida->saida(" </tr></table></td>\r\n");
               $nm_saida->saida("    <TD style=\"padding: 0px; border-width: 0px; vertical-align: top;\">\r\n");
               $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_D_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_D_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
               $nm_saida->saida("    </TD>\r\n");
               $nm_saida->saida("    </TR>\r\n");
           } 
       }
       return;
   }
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['force_toolbar']))
   { 
       $this->force_toolbar = true;
       unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['force_toolbar']);
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       $nm_saida->saida("<table id=\"apl_grid_public_v_dte#?#$nm_seq_execucoes\" style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\">\r\n");
       $nm_saida->saida(" <TR> \r\n");
       $nm_id_aplicacao = "";
   } 
   else 
   { 
       $nm_saida->saida(" <TR> \r\n");
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && 
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print") 
       { 
           $nm_saida->saida("    <TD style=\"padding: 0px; border-width: 0px; vertical-align: top;\">\r\n");
      $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_EL_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_EL_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
           $nm_saida->saida("    </TD>\r\n");
           $nm_saida->saida("    <TD style=\"padding: 0px; border-width: 0px; vertical-align: top;\"><TABLE style=\"padding: 0px; border-spacing: 0px; border-width: 0px;\" width=\"100%\"><TR>\r\n");
       } 
       $nm_id_aplicacao = " id=\"apl_grid_public_v_dte#?#1\"";
   } 
   $nm_saida->saida("  <TD id=\"sc_grid_body\" class=\"" . $this->css_scGridTabelaTd . "\" style=\"vertical-align: top;text-align: center;\">\r\n");
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
   { 
       $_SESSION['scriptcase']['saida_html'] = "";
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq'])
   { 
       $nm_saida->saida("        <div id=\"div_FBtn_Run\" style=\"display: none\"> \r\n");
       $nm_saida->saida("        <form name=\"Fpesq\" method=post>\r\n");
       $nm_saida->saida("         <input type=hidden name=\"nm_ret_psq\"> \r\n");
       $nm_saida->saida("        </div> \r\n");
   } 
   $nm_saida->saida("   <TABLE class=\"" . $this->css_scGridTabela . "\" align=\"center\" " . $nm_id_aplicacao . " width=\"100%\">\r\n");
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['cab_embutida'] != "S" )
      { 
          $this->label_grid($linhas);
      } 
   } 
   else 
   { 
      $this->label_grid($linhas);
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_grid'])
   { 
       $_SESSION['scriptcase']['saida_html'] = "";
   } 
// 
   $nm_quant_linhas = 0 ;
   $nm_inicio_pag = 0 ;
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final'] = 0;
   } 
   $this->nmgp_prim_pag_pdf = false;
   $this->Ini->cor_link_dados = $this->css_scGridFieldEvenLink;
   $this->NM_flag_antigo = FALSE;
   $this->Ini->link_pdf_apl = "";
   $this->Ini->link_cedible_apl = "";
   $this->Ini->link_xml_apl = "";
   $this->Ini->link_est_xdte_apl = "";
   $this->Ini->link_acuse_recibo_apl = "";
   $this->Ini->link_aprob_comercial_apl = "";
   while (!$this->rs_grid->EOF && $nm_quant_linhas < $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_reg_grid'] && ($linhas == 0 || $linhas > $this->Lin_impressas)) 
   {  
          $this->Rows_span = 1;
          $this->NM_field_style = array();
          //---------- Gauge ----------
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" && -1 < $this->progress_grid)
          {
              $this->progress_now++;
              if (0 == $this->progress_lim_now)
              {
               $lang_protect = $this->Ini->Nm_lang['lang_pdff_rows'];
               if (!NM_is_utf8($lang_protect))
               {
                   $lang_protect = mb_convert_encoding($lang_protect, "UTF-8", $_SESSION['scriptcase']['charset']);
               }
                  fwrite($this->progress_fp, $this->progress_now . "_#NM#_" . $lang_protect . " " . $this->progress_now . "...\n");
              }
              $this->progress_lim_now++;
              if ($this->progress_lim_tot == $this->progress_lim_now)
              {
                  $this->progress_lim_now = 0;
              }
          }
          $this->Lin_impressas++;
          $this->tipo_docu = $this->rs_grid->fields[0] ;  
          $this->tipo_docu = (string)$this->tipo_docu;
          $this->folio_dte = $this->rs_grid->fields[1] ;  
          $this->folio_dte =  str_replace(",", ".", $this->folio_dte);
          $this->folio_dte = (string)$this->folio_dte;
          $this->est_xdte = $this->rs_grid->fields[2] ;  
          $this->est_xdte = (string)$this->est_xdte;
          $this->fec_emi_dte = $this->rs_grid->fields[3] ;  
          $this->fech_carg = $this->rs_grid->fields[4] ;  
          $this->rut_rec_dte = $this->rs_grid->fields[5] ;  
          $this->rut_rec_dte = (string)$this->rut_rec_dte;
          $this->nom_rec_dte = $this->rs_grid->fields[6] ;  
          $this->dir_rec_dte = $this->rs_grid->fields[7] ;  
          $this->com_rec_dte = $this->rs_grid->fields[8] ;  
          $this->mntneto_dte = $this->rs_grid->fields[9] ;  
          $this->mntneto_dte =  str_replace(",", ".", $this->mntneto_dte);
          $this->mntneto_dte = (string)$this->mntneto_dte;
          $this->mnt_exen_dte = $this->rs_grid->fields[10] ;  
          $this->mnt_exen_dte =  str_replace(",", ".", $this->mnt_exen_dte);
          $this->mnt_exen_dte = (string)$this->mnt_exen_dte;
          $this->iva_dte = $this->rs_grid->fields[11] ;  
          $this->iva_dte =  str_replace(",", ".", $this->iva_dte);
          $this->iva_dte = (string)$this->iva_dte;
          $this->mont_tot_dte = $this->rs_grid->fields[12] ;  
          $this->mont_tot_dte =  str_replace(",", ".", $this->mont_tot_dte);
          $this->mont_tot_dte = (string)$this->mont_tot_dte;
          $this->track_id = $this->rs_grid->fields[13] ;  
          $this->track_id = (string)$this->track_id;
          $this->path_pdf_cedible = $this->rs_grid->fields[14] ;  
          $this->msg_xdte = $this->rs_grid->fields[15] ;  
          $this->est_rec_doc = $this->rs_grid->fields[16] ;  
          $this->est_res_rev = $this->rs_grid->fields[17] ;  
          $this->est_recibo_mercaderias = $this->rs_grid->fields[18] ;  
          $this->look_est_xdte = $this->est_xdte; 
          $this->Lookup->lookup_est_xdte($this->look_est_xdte); 
          $this->SC_seq_page++; 
          $this->SC_seq_register = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final'] + 1; 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['rows_emb']++;
          $this->Ini->link_pdf_apl = "";
          $this->Ini->link_cedible_apl = "";
          $this->Ini->link_xml_apl = "";
          $this->Ini->link_est_xdte_apl = "";
          $this->Ini->link_acuse_recibo_apl = "";
          $this->Ini->link_aprob_comercial_apl = "";
          $this->sc_proc_grid = true;
          $_SESSION['scriptcase']['grid_public_v_dte']['contr_erro'] = 'on';
if (!isset($_SESSION['v_codi_empr'])) {$_SESSION['v_codi_empr'] = "";}
if (!isset($this->sc_temp_v_codi_empr)) {$this->sc_temp_v_codi_empr = (isset($_SESSION['v_codi_empr'])) ? $_SESSION['v_codi_empr'] : "";}
 $this->Ini->link_pdf_apl = "http://prod.opendte.cl/dte/view_pdf_rem.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu ";
$this->Ini->link_pdf_apl = str_replace("'", "\'", $this->Ini->link_pdf_apl);
$this->Ini->link_pdf_parms = "";
$this->Ini->link_pdf_parms = str_replace("'", "\'", $this->Ini->link_pdf_parms);
$this->Ini->link_pdf_hint = "Ver PDF";
$this->Ini->link_pdf_hint = str_replace("'", "\'", $this->Ini->link_pdf_hint);
$this->Ini->link_pdf_target = "_blank";
$this->Ini->link_pdf_pos = "";
$this->Ini->link_pdf_alt = "440";
$this->Ini->link_pdf_larg = "630";
;
if ($this->path_pdf_cedible  <> "") {
	$this->Ini->link_cedible_apl = "http://portaldte.opendte.cl/dte/view_pdf_rem.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &cd=true";
$this->Ini->link_cedible_apl = str_replace("'", "\'", $this->Ini->link_cedible_apl);
$this->Ini->link_cedible_parms = "";
$this->Ini->link_cedible_parms = str_replace("'", "\'", $this->Ini->link_cedible_parms);
$this->Ini->link_cedible_hint = "Ver PDF Cedible";
$this->Ini->link_cedible_hint = str_replace("'", "\'", $this->Ini->link_cedible_hint);
$this->Ini->link_cedible_target = "_blank";
$this->Ini->link_cedible_pos = "";
$this->Ini->link_cedible_alt = "440";
$this->Ini->link_cedible_larg = "630";
;
}		
		
$this->Ini->link_xml_apl = "http://portaldte.opendte.cl/dte/view_xml_rem.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu ";
$this->Ini->link_xml_apl = str_replace("'", "\'", $this->Ini->link_xml_apl);
$this->Ini->link_xml_parms = "";
$this->Ini->link_xml_parms = str_replace("'", "\'", $this->Ini->link_xml_parms);
$this->Ini->link_xml_hint = "Ver XML";
$this->Ini->link_xml_hint = str_replace("'", "\'", $this->Ini->link_xml_hint);
$this->Ini->link_xml_target = "_blank";
$this->Ini->link_xml_pos = "";
$this->Ini->link_xml_alt = "440";
$this->Ini->link_xml_larg = "630";
;		
		
if ($this->est_xdte >1000) {
	$this->Ini->link_est_xdte_apl = $this->Ini->path_link . "" . SC_dir_app_name('#') . "/#.php";
$this->Ini->link_est_xdte_apl = str_replace("'", "\'", $this->Ini->link_est_xdte_apl);
$this->Ini->link_est_xdte_parms = "";
$this->Ini->link_est_xdte_parms = str_replace("'", "\'", $this->Ini->link_est_xdte_parms);
$this->Ini->link_est_xdte_hint = "" . $this->msg_xdte  . "";
$this->Ini->link_est_xdte_hint = str_replace("'", "\'", $this->Ini->link_est_xdte_hint);
$this->Ini->link_est_xdte_target = "_blank";
$this->Ini->link_est_xdte_pos = "";
$this->Ini->link_est_xdte_alt = "440";
$this->Ini->link_est_xdte_larg = "630";
;		
}	
		
if ($this->est_rec_doc =='R'){
	$this->acuse_recibo ="<img src='../_lib/img/sys__NM__success.png'>";
	$this->Ini->link_acuse_recibo_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=AR";
$this->Ini->link_acuse_recibo_apl = str_replace("'", "\'", $this->Ini->link_acuse_recibo_apl);
$this->Ini->link_acuse_recibo_parms = "";
$this->Ini->link_acuse_recibo_parms = str_replace("'", "\'", $this->Ini->link_acuse_recibo_parms);
$this->Ini->link_acuse_recibo_hint = "Ver XML";
$this->Ini->link_acuse_recibo_hint = str_replace("'", "\'", $this->Ini->link_acuse_recibo_hint);
$this->Ini->link_acuse_recibo_target = "_blank";
$this->Ini->link_acuse_recibo_pos = "";
$this->Ini->link_acuse_recibo_alt = "440";
$this->Ini->link_acuse_recibo_larg = "630";
;			
}	
else if ($this->est_rec_doc =='X'){
	$this->Ini->link_acuse_recibo_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=AR";
$this->Ini->link_acuse_recibo_apl = str_replace("'", "\'", $this->Ini->link_acuse_recibo_apl);
$this->Ini->link_acuse_recibo_parms = "";
$this->Ini->link_acuse_recibo_parms = str_replace("'", "\'", $this->Ini->link_acuse_recibo_parms);
$this->Ini->link_acuse_recibo_hint = "Ver XML";
$this->Ini->link_acuse_recibo_hint = str_replace("'", "\'", $this->Ini->link_acuse_recibo_hint);
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
$this->Ini->link_acuse_recibo_apl = str_replace("'", "\'", $this->Ini->link_acuse_recibo_apl);
$this->Ini->link_acuse_recibo_parms = "";
$this->Ini->link_acuse_recibo_parms = str_replace("'", "\'", $this->Ini->link_acuse_recibo_parms);
$this->Ini->link_acuse_recibo_hint = "Acuse de Recibido no Recepcionado";
$this->Ini->link_acuse_recibo_hint = str_replace("'", "\'", $this->Ini->link_acuse_recibo_hint);
$this->Ini->link_acuse_recibo_target = "_blank";
$this->Ini->link_acuse_recibo_pos = "";
$this->Ini->link_acuse_recibo_alt = "440";
$this->Ini->link_acuse_recibo_larg = "630";
;	
}	
			
			
if ($this->est_res_rev =='A'){
	$this->Ini->link_aprob_comercial_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=ARC";
$this->Ini->link_aprob_comercial_apl = str_replace("'", "\'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "\'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "\'", $this->Ini->link_aprob_comercial_hint);
$this->Ini->link_aprob_comercial_target = "_blank";
$this->Ini->link_aprob_comercial_pos = "";
$this->Ini->link_aprob_comercial_alt = "440";
$this->Ini->link_aprob_comercial_larg = "630";
;			
	$this->aprob_comercial ="<img src='../_lib/img/sys__NM__success.png'>";
}	
else if ($this->est_res_rev =='R'){
	$this->Ini->link_aprob_comercial_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=ARC";
$this->Ini->link_aprob_comercial_apl = str_replace("'", "\'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "\'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "\'", $this->Ini->link_aprob_comercial_hint);
$this->Ini->link_aprob_comercial_target = "_blank";
$this->Ini->link_aprob_comercial_pos = "";
$this->Ini->link_aprob_comercial_alt = "440";
$this->Ini->link_aprob_comercial_larg = "630";
;			
	$this->aprob_comercial ="<img src='../_lib/img/sys__NM__warning.png'>";
}				
else if ($this->est_res_rev =='X'){
	$this->Ini->link_aprob_comercial_apl = "http://portaldte.opendte.cl/dte/view_xml_resp.php?c=$this->sc_temp_v_codi_empr&f=$this->folio_dte &t=$this->tipo_docu &o=ARC";
$this->Ini->link_aprob_comercial_apl = str_replace("'", "\'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "\'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "\'", $this->Ini->link_aprob_comercial_hint);
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
$this->Ini->link_aprob_comercial_apl = str_replace("'", "\'", $this->Ini->link_aprob_comercial_apl);
$this->Ini->link_aprob_comercial_parms = "";
$this->Ini->link_aprob_comercial_parms = str_replace("'", "\'", $this->Ini->link_aprob_comercial_parms);
$this->Ini->link_aprob_comercial_hint = "Ver XML";
$this->Ini->link_aprob_comercial_hint = str_replace("'", "\'", $this->Ini->link_aprob_comercial_hint);
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
          $nm_inicio_pag++;
          if (!$this->NM_flag_antigo)
          {
             $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']++ ; 
          }
          $seq_det =  $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['final']; 
          $this->Ini->cor_link_dados = ($this->Ini->cor_link_dados == $this->css_scGridFieldOddLink) ? $this->css_scGridFieldEvenLink : $this->css_scGridFieldOddLink; 
          $this->Ini->qual_linha   = ($this->Ini->qual_linha == "par") ? "impar" : "par";
          if ("impar" == $this->Ini->qual_linha)
          {
              $this->css_line_back = $this->css_scGridFieldOdd;
              $this->css_line_fonf = $this->css_scGridFieldOddFont;
          }
          else
          {
              $this->css_line_back = $this->css_scGridFieldEven;
              $this->css_line_fonf = $this->css_scGridFieldEvenFont;
          }
          $NM_destaque = " onmouseover=\"over_tr(this, '" . $this->css_line_back . "');\" onmouseout=\"out_tr(this, '" . $this->css_line_back . "');\" onclick=\"click_tr(this, '" . $this->css_line_back . "');\"";
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_grid'])
          {
             $NM_destaque ="";
          }
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq'])
          {
              $temp = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['dado_psq_ret'];
              eval("\$teste = \$this->$temp;");
              if ($temp == "fec_emi_dte")
              {
                  $conteudo_x = $teste;
                  nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
                  if (is_numeric($conteudo_x) && $conteudo_x > 0) 
                  { 
                      $this->nm_data->SetaData($teste, "YYYY-MM-DD");
                      $teste = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
                  } 
              }
              if ($temp == "fech_carg")
              {
                  $conteudo_x = $teste;
                  nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
                  if (is_numeric($conteudo_x) && $conteudo_x > 0) 
                  { 
                      $this->nm_data->SetaData($teste, "YYYY-MM-DD");
                      $teste = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
                  } 
              }
          }
          $nm_saida->saida("    <TR  class=\"" . $this->css_line_back . "\"" . $NM_destaque . ">\r\n");
 if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq']){ 
          $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"left\" valign=\"top\" WIDTH=\"1px\"  HEIGHT=\"0px\">\r\n");
 $Cod_Btn = nmButtonOutput($this->arr_buttons, "bcapture", "document.Fpesq.nm_ret_psq.value='" . $teste . "'; nm_escreve_window();", "document.Fpesq.nm_ret_psq.value='" . $teste . "'; nm_escreve_window();", "", "Rad_psq", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
          $nm_saida->saida(" $Cod_Btn</TD>\r\n");
 } 
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['field_order'] as $Cada_col)
          { 
              $NM_func_grid = "NM_grid_" . $Cada_col;
              $this->$NM_func_grid();
          } 
          $nm_saida->saida("</TR>\r\n");
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_grid'] && $this->nm_prim_linha)
          { 
              $nm_saida->saida("##NM@@"); 
              $this->nm_prim_linha = false; 
          } 
          $this->rs_grid->MoveNext();
          $this->sc_proc_grid = false;
          $nm_quant_linhas++ ;
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" || isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['paginacao']))
          { 
              $nm_quant_linhas = 0; 
          } 
   }  
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
      $this->Lin_final = $this->rs_grid->EOF;
      if ($this->Lin_final)
      {
         $this->rs_grid->Close();
      }
   } 
   else
   {
      $this->rs_grid->Close();
   }
   if ($this->rs_grid->EOF) 
   { 
  
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['exibe_total'] == "S")
       { 
           $this->quebra_geral_top() ;
       } 
   }  
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_grid'])
   {
       $nm_saida->saida("X##NM@@X");
   }
   $nm_saida->saida("</TABLE>");
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq'])
   { 
          $nm_saida->saida("       </form>\r\n");
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
   { 
       $this->Ini->Arr_result['setValue'][] = array('field' => 'sc_grid_body', 'value' => NM_charset_to_utf8($_SESSION['scriptcase']['saida_html']));
       $_SESSION['scriptcase']['saida_html'] = "";
   } 
   $nm_saida->saida("</TD>");
   $nm_saida->saida($fecha_tr);
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida_grid'])
   { 
       return; 
   } 
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       { 
            $this->nmgp_barra_bot();
       } 
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       $_SESSION['scriptcase']['contr_link_emb'] = "";   
   } 
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && empty($this->nm_grid_sem_reg) && 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print") 
   { 
       $nm_saida->saida("</TABLE></TD>\r\n");
       $nm_saida->saida("<TD style=\"padding: 0px; border-width: 0px;\" valign=\"top\">\r\n");
      $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_DL_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_DL_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
       $nm_saida->saida("</TD>\r\n");
       $nm_saida->saida(" </tr></table></td>\r\n");
           $nm_saida->saida("    <TD style=\"padding: 0px; border-width: 0px; vertical-align: top;\">\r\n");
           $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_D_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_D_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
           $nm_saida->saida("    </TD>\r\n");
           $nm_saida->saida("    </TR>\r\n");
   } 
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   {
       $nm_saida->saida("</TABLE>\r\n");
   }
   if ($this->Print_All) 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao']       = "igual" ; 
   } 
 }
 function NM_grid_tipo_docu()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['tipo_docu']) || $this->NM_cmp_hidden['tipo_docu'] != "off") { 
          $conteudo = $this->tipo_docu; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          $this->Lookup->lookup_tipo_docu($conteudo , $this->tipo_docu) ; 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_tipo_docu_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_folio_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['folio_dte']) || $this->NM_cmp_hidden['folio_dte'] != "off") { 
          $conteudo = $this->folio_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($conteudo, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "0", "S", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_folio_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_est_xdte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['est_xdte']) || $this->NM_cmp_hidden['est_xdte'] != "off") { 
          $conteudo = trim($this->look_est_xdte); 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"right\" valign=\"top\"   HEIGHT=\"0px\">\r\n");
if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['scriptcase']['contr_link_emb'] != "pdf"  && $conteudo != "&nbsp;" && !empty($this->Ini->link_est_xdte_apl)) { 
  if (!empty($this->Ini->link_est_xdte_pos)) { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit6('" . $this->Ini->link_est_xdte_apl . "', '$this->nm_location', '" . $this->Ini->link_est_xdte_parms . "', '" . $this->Ini->link_est_xdte_target . "', '" . $this->Ini->link_est_xdte_pos . "', '" . $this->Ini->link_est_xdte_alt . "', '" . $this->Ini->link_est_xdte_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_est_xdte_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  } else { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit5('" . $this->Ini->link_est_xdte_apl . "', '$this->nm_location', '" . $this->Ini->link_est_xdte_parms . "', '" . $this->Ini->link_est_xdte_target . "', '', '" . $this->Ini->link_est_xdte_alt . "', '" . $this->Ini->link_est_xdte_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_est_xdte_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  }
 } else {
   $nm_saida->saida("$conteudo \r\n");
} 
   $nm_saida->saida("</TD>\r\n");
      }
 }
 function NM_grid_fec_emi_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['fec_emi_dte']) || $this->NM_cmp_hidden['fec_emi_dte'] != "off") { 
          $conteudo = $this->fec_emi_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
               $conteudo_x =  $conteudo;
               nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
               if (is_numeric($conteudo_x) && $conteudo_x > 0) 
               { 
                   $this->nm_data->SetaData($conteudo, "YYYY-MM-DD");
                   $conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
               } 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"center\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_fec_emi_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_fech_carg()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['fech_carg']) || $this->NM_cmp_hidden['fech_carg'] != "off") { 
          $conteudo = $this->fech_carg; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
               $conteudo_x =  $conteudo;
               nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD");
               if (is_numeric($conteudo_x) && $conteudo_x > 0) 
               { 
                   $this->nm_data->SetaData($conteudo, "YYYY-MM-DD");
                   $conteudo = $this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa"));
               } 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"center\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_fech_carg_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_rut_rec_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['rut_rec_dte']) || $this->NM_cmp_hidden['rut_rec_dte'] != "off") { 
          $conteudo = $this->rut_rec_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($conteudo, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_rut_rec_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_nom_rec_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['nom_rec_dte']) || $this->NM_cmp_hidden['nom_rec_dte'] != "off") { 
          $conteudo = $this->nom_rec_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"left\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_nom_rec_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_dir_rec_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['dir_rec_dte']) || $this->NM_cmp_hidden['dir_rec_dte'] != "off") { 
          $conteudo = $this->dir_rec_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"left\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_dir_rec_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_com_rec_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['com_rec_dte']) || $this->NM_cmp_hidden['com_rec_dte'] != "off") { 
          $conteudo = $this->com_rec_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"left\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_com_rec_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_mntneto_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['mntneto_dte']) || $this->NM_cmp_hidden['mntneto_dte'] != "off") { 
          $conteudo = $this->mntneto_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($conteudo, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "0", "N", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_mntneto_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_mnt_exen_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['mnt_exen_dte']) || $this->NM_cmp_hidden['mnt_exen_dte'] != "off") { 
          $conteudo = $this->mnt_exen_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($conteudo, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "0", "N", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_mnt_exen_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_iva_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['iva_dte']) || $this->NM_cmp_hidden['iva_dte'] != "off") { 
          $conteudo = $this->iva_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($conteudo, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "0", "N", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_iva_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_mont_tot_dte()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['mont_tot_dte']) || $this->NM_cmp_hidden['mont_tot_dte'] != "off") { 
          $conteudo = $this->mont_tot_dte; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($conteudo, $_SESSION['scriptcase']['reg_conf']['grup_val'], $_SESSION['scriptcase']['reg_conf']['dec_val'], "0", "N", "2", "", "V:" . $_SESSION['scriptcase']['reg_conf']['monet_f_pos'] . ":" . $_SESSION['scriptcase']['reg_conf']['monet_f_neg'], $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit']) ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_mont_tot_dte_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_track_id()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['track_id']) || $this->NM_cmp_hidden['track_id'] != "off") { 
          $conteudo = $this->track_id; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($conteudo, "", "", "0", "S", "2", "", "N:2", "-") ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"right\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_track_id_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
 }
 function NM_grid_pdf()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['pdf']) || $this->NM_cmp_hidden['pdf'] != "off") { 
          $conteudo = $this->pdf; 
          if (!is_file($this->Ini->root  . $this->Ini->path_imag_cab . "/grp__NM__nm_icon_pdf.gif"))
          { 
              $conteudo = "&nbsp;" ;  
          } 
          else 
          { 
              $conteudo = "<img border=\"0\" src=\"" . $this->NM_raiz_img  . $this->Ini->path_imag_cab . "/grp__NM__nm_icon_pdf.gif\"/>" ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"center\" valign=\"top\"   HEIGHT=\"0px\">\r\n");
if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['scriptcase']['contr_link_emb'] != "pdf"  && $conteudo != "&nbsp;" && !empty($this->Ini->link_pdf_apl)) { 
  if (!empty($this->Ini->link_pdf_pos)) { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit6('" . $this->Ini->link_pdf_apl . "', '$this->nm_location', '" . $this->Ini->link_pdf_parms . "', '" . $this->Ini->link_pdf_target . "', '" . $this->Ini->link_pdf_pos . "', '" . $this->Ini->link_pdf_alt . "', '" . $this->Ini->link_pdf_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_pdf_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  } else { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit5('" . $this->Ini->link_pdf_apl . "', '$this->nm_location', '" . $this->Ini->link_pdf_parms . "', '" . $this->Ini->link_pdf_target . "', '', '" . $this->Ini->link_pdf_alt . "', '" . $this->Ini->link_pdf_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_pdf_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  }
 } else {
   $nm_saida->saida("$conteudo \r\n");
} 
   $nm_saida->saida("</TD>\r\n");
      }
 }
 function NM_grid_cedible()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['cedible']) || $this->NM_cmp_hidden['cedible'] != "off") { 
          $conteudo = $this->cedible; 
          if (!is_file($this->Ini->root  . $this->Ini->path_imag_cab . "/grp__NM__nm_icon_pdf.gif"))
          { 
              $conteudo = "&nbsp;" ;  
          } 
          else 
          { 
              $conteudo = "<img border=\"0\" src=\"" . $this->NM_raiz_img  . $this->Ini->path_imag_cab . "/grp__NM__nm_icon_pdf.gif\"/>" ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"center\" valign=\"top\"   HEIGHT=\"0px\">\r\n");
if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['scriptcase']['contr_link_emb'] != "pdf"  && $conteudo != "&nbsp;" && !empty($this->Ini->link_cedible_apl)) { 
  if (!empty($this->Ini->link_cedible_pos)) { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit6('" . $this->Ini->link_cedible_apl . "', '$this->nm_location', '" . $this->Ini->link_cedible_parms . "', '" . $this->Ini->link_cedible_target . "', '" . $this->Ini->link_cedible_pos . "', '" . $this->Ini->link_cedible_alt . "', '" . $this->Ini->link_cedible_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_cedible_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  } else { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit5('" . $this->Ini->link_cedible_apl . "', '$this->nm_location', '" . $this->Ini->link_cedible_parms . "', '" . $this->Ini->link_cedible_target . "', '', '" . $this->Ini->link_cedible_alt . "', '" . $this->Ini->link_cedible_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_cedible_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  }
 } else {
   $nm_saida->saida("$conteudo \r\n");
} 
   $nm_saida->saida("</TD>\r\n");
      }
 }
 function NM_grid_xml()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['xml']) || $this->NM_cmp_hidden['xml'] != "off") { 
          $conteudo = $this->xml; 
          if (!is_file($this->Ini->root  . $this->Ini->path_imag_cab . "/sys__NM__xml.jpeg"))
          { 
              $conteudo = "&nbsp;" ;  
          } 
          else 
          { 
              $conteudo = "<img border=\"0\" src=\"" . $this->NM_raiz_img  . $this->Ini->path_imag_cab . "/sys__NM__xml.jpeg\"/>" ; 
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"  NOWRAP align=\"center\" valign=\"top\"   HEIGHT=\"0px\">\r\n");
if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['scriptcase']['contr_link_emb'] != "pdf"  && $conteudo != "&nbsp;" && !empty($this->Ini->link_xml_apl)) { 
  if (!empty($this->Ini->link_xml_pos)) { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit6('" . $this->Ini->link_xml_apl . "', '$this->nm_location', '" . $this->Ini->link_xml_parms . "', '" . $this->Ini->link_xml_target . "', '" . $this->Ini->link_xml_pos . "', '" . $this->Ini->link_xml_alt . "', '" . $this->Ini->link_xml_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_xml_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  } else { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit5('" . $this->Ini->link_xml_apl . "', '$this->nm_location', '" . $this->Ini->link_xml_parms . "', '" . $this->Ini->link_xml_target . "', '', '" . $this->Ini->link_xml_alt . "', '" . $this->Ini->link_xml_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_xml_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  }
 } else {
   $nm_saida->saida("$conteudo \r\n");
} 
   $nm_saida->saida("</TD>\r\n");
      }
 }
 function NM_grid_acuse_recibo()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['acuse_recibo']) || $this->NM_cmp_hidden['acuse_recibo'] != "off") { 
          $conteudo = $this->acuse_recibo; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"\" valign=\"top\"   HEIGHT=\"0px\">\r\n");
if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['scriptcase']['contr_link_emb'] != "pdf"  && $conteudo != "&nbsp;" && !empty($this->Ini->link_acuse_recibo_apl)) { 
  if (!empty($this->Ini->link_acuse_recibo_pos)) { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit6('" . $this->Ini->link_acuse_recibo_apl . "', '$this->nm_location', '" . $this->Ini->link_acuse_recibo_parms . "', '" . $this->Ini->link_acuse_recibo_target . "', '" . $this->Ini->link_acuse_recibo_pos . "', '" . $this->Ini->link_acuse_recibo_alt . "', '" . $this->Ini->link_acuse_recibo_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_acuse_recibo_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  } else { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit5('" . $this->Ini->link_acuse_recibo_apl . "', '$this->nm_location', '" . $this->Ini->link_acuse_recibo_parms . "', '" . $this->Ini->link_acuse_recibo_target . "', '', '" . $this->Ini->link_acuse_recibo_alt . "', '" . $this->Ini->link_acuse_recibo_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_acuse_recibo_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  }
 } else {
   $nm_saida->saida("$conteudo \r\n");
} 
   $nm_saida->saida("</TD>\r\n");
      }
 }
 function NM_grid_aprob_comercial()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['aprob_comercial']) || $this->NM_cmp_hidden['aprob_comercial'] != "off") { 
          $conteudo = $this->aprob_comercial; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"\" valign=\"top\"   HEIGHT=\"0px\">\r\n");
if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['scriptcase']['contr_link_emb'] != "pdf"  && $conteudo != "&nbsp;" && !empty($this->Ini->link_aprob_comercial_apl)) { 
  if (!empty($this->Ini->link_aprob_comercial_pos)) { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit6('" . $this->Ini->link_aprob_comercial_apl . "', '$this->nm_location', '" . $this->Ini->link_aprob_comercial_parms . "', '" . $this->Ini->link_aprob_comercial_target . "', '" . $this->Ini->link_aprob_comercial_pos . "', '" . $this->Ini->link_aprob_comercial_alt . "', '" . $this->Ini->link_aprob_comercial_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_aprob_comercial_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  } else { 
   $nm_saida->saida("<a href=\"javascript:nm_gp_submit5('" . $this->Ini->link_aprob_comercial_apl . "', '$this->nm_location', '" . $this->Ini->link_aprob_comercial_parms . "', '" . $this->Ini->link_aprob_comercial_target . "', '', '" . $this->Ini->link_aprob_comercial_alt . "', '" . $this->Ini->link_aprob_comercial_larg . "')\" onMouseover=\"nm_mostra_hint(this, event, '" . $this->Ini->link_aprob_comercial_hint . "')\" onMouseOut=\"nm_apaga_hint()\" class=\"" . $this->Ini->cor_link_dados . "\">" . $conteudo . "</a>\r\n");
  }
 } else {
   $nm_saida->saida("$conteudo \r\n");
} 
   $nm_saida->saida("</TD>\r\n");
      }
 }
 function NM_grid_rec_mercaderia()
 {
      global $nm_saida;
      if (!isset($this->NM_cmp_hidden['rec_mercaderia']) || $this->NM_cmp_hidden['rec_mercaderia'] != "off") { 
          $conteudo = $this->rec_mercaderia; 
          if ($conteudo === "") 
          { 
              $conteudo = "&nbsp;" ;  
              $graf = "" ;  
          } 
   $nm_saida->saida("     <TD rowspan=\"" . $this->Rows_span . "\" class=\"" . $this->css_line_fonf . "\"   align=\"\" valign=\"top\"   HEIGHT=\"0px\"><span id=\"id_sc_field_rec_mercaderia_" . $this->SC_seq_page . "\">" . $conteudo . "</span></TD>\r\n");
      }
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

 function NM_calc_span()
 {
   $this->NM_colspan  = 20;
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq'])
   {
       $this->NM_colspan++;
   }
   foreach ($this->NM_cmp_hidden as $Cmp => $Hidden)
   {
       if ($Hidden == "off")
       {
           $this->NM_colspan--;
       }
   }
 }
 function quebra_geral_top() 
 {
   global $nm_saida; 
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
// 
 function gera_icone($doc) 
 {
    $cam_icone = "";
    $path =  $this->Ini->root . $this->Ini->path_icones . "/";
    if (is_dir($path))
    {
        $nm_icones = nm_list_icon($path); 
        $nm_tipo = strtolower(substr($doc, strrpos($doc, ".") + 1));
        if ($nm_tipo == "xlsx")
        {
            $nm_tipo = "xls";
        }
        if (isset($nm_icones[$nm_tipo]) && !empty($nm_icones[$nm_tipo]))
        {
            $cam_icone = $this->Ini->path_icones . "/" . $nm_icones[$nm_tipo];
        }
        else
        {
            $cam_icone = $this->Ini->path_icones . "/" . $nm_icones["default"];
        }
    }
    return $cam_icone;
 } 
   function nmgp_barra_top()
   {
      global 
             $nm_saida, $nm_url_saida, $nm_apl_dependente;
      $NM_btn = false;
      $nm_saida->saida("      <tr>\r\n");
      $nm_saida->saida("      <div id=\"div_F0_top\" style=\"display: none\"> \r\n");
      $nm_saida->saida("      <form name=\"F0_top\" method=\"post\" action=\"grid_public_v_dte.php\" target=\"_self\"> \r\n");
      $nm_saida->saida("      <input type=\"hidden\" id=\"script_init_f0_top\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
      $nm_saida->saida("      <input type=hidden id=\"script_session_f0_top\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\"/>\r\n");
      $nm_saida->saida("      <input type=\"hidden\" id=\"opcao_f0_top\" name=\"nmgp_opcao\" value=\"muda_qt_linhas\"/> \r\n");
      $nm_saida->saida("      </div> \r\n");
      $nm_saida->saida("       <td id=\"sc_grid_toobar_top\"  colspan=3 class=\"" . $this->css_scGridTabelaTd . "\" valign=\"top\"> \r\n");
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
      { 
          $_SESSION['scriptcase']['saida_html'] = "";
      } 
      $nm_saida->saida("        <table class=\"" . $this->css_scGridToolbar . "\" style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\" width=\"100%\" valign=\"top\">\r\n");
      $nm_saida->saida("         <tr> \r\n");
      $nm_saida->saida("          <td class=\"" . $this->css_scGridToolbarPadd . "\" nowrap valign=\"middle\" align=\"left\" width=\"33%\"> \r\n");
      if (!$this->Ini->SC_Link_View && $this->nmgp_botoes['qsearch'] == "on")
      {
          $OPC_cmp = (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['fast_search'])) ? $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['fast_search'][0] : "";
          $OPC_arg = (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['fast_search'])) ? $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['fast_search'][1] : "";
          $OPC_dat = (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['fast_search'])) ? $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['fast_search'][2] : "";
          $nm_saida->saida("           <script type=\"text/javascript\">var change_fast_top = \"\";</script>\r\n");
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
          {
              $this->Ini->Arr_result['setVar'][] = array('var' => 'change_fast_top', 'value' => "");
          }
          if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($OPC_cmp))
          {
              $OPC_cmp = NM_conv_charset($OPC_cmp, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($OPC_arg))
          {
              $OPC_arg = NM_conv_charset($OPC_arg, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($OPC_dat))
          {
              $OPC_dat = NM_conv_charset($OPC_dat, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          $nm_saida->saida("          <input type=\"hidden\"  id=\"fast_search_f0_top\" name=\"nmgp_fast_search\" value=\"SC_all_Cmp\">\r\n");
          $nm_saida->saida("          <input type=\"hidden\" id=\"cond_fast_search_f0_top\" name=\"nmgp_cond_fast_search\" value=\"qp\">\r\n");
          $nm_saida->saida("          <script type=\"text/javascript\">var scQSInitVal = \"" . $OPC_dat . "\";</script>\r\n");
          $nm_saida->saida("          <span id=\"quicksearchph_top\">\r\n");
          $nm_saida->saida("           <input type=\"text\" id=\"SC_fast_search_top\" class=\"" . $this->css_css_toolbar_obj . "\" style=\"vertical-align: middle;\" name=\"nmgp_arg_fast_search\" value=\"" . NM_encode_input($OPC_dat) . "\" size=\"10\" onChange=\"change_fast_top = 'CH';\" alt=\"{watermark:'" . $this->Ini->Nm_lang['lang_othr_qk_watermark'] . "', watermarkClass:'css_toolbar_objWm', maxLength: 255}\">\r\n");
          $nm_saida->saida("           <img style=\"display: none\" id=\"SC_fast_search_close_top\" src=\"" . $this->Ini->path_botoes . "/" . $this->Ini->Img_qs_clean . "\" onclick=\"document.getElementById('SC_fast_search_top').value = ''; nm_gp_submit_qsearch('top');\">\r\n");
          $nm_saida->saida("          </span>\r\n");
          $Cod_Btn = nmButtonOutput($this->arr_buttons, "bquick_search", "if (change_fast_top == 'CH') {document.F0_top.nmgp_opcao.value='fast_search';document.F0_top.submit();} return false", "if (change_fast_top == 'CH') {document.F0_top.nmgp_opcao.value='fast_search';document.F0_top.submit();} return false", "fast_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
          $nm_saida->saida("          $Cod_Btn \r\n");
          $NM_btn = true;
      }
          $nm_saida->saida("         </td> \r\n");
          $nm_saida->saida("          <td class=\"" . $this->css_scGridToolbarPadd . "\" nowrap valign=\"middle\" align=\"center\" width=\"33%\"> \r\n");
      if (is_file($this->Ini->root . $this->Ini->path_img_global . $this->Ini->Img_sep_grid))
      {
          if ($NM_btn)
          {
              $NM_btn = false;
              $NM_ult_sep = "NM_sep_1";
              $nm_saida->saida("          <img id=\"NM_sep_1\" src=\"" . $this->Ini->path_img_global . $this->Ini->Img_sep_grid . "\" align=\"absmiddle\" style=\"vertical-align: middle;\">\r\n");
          }
      }
          $nm_saida->saida("         </td> \r\n");
          $nm_saida->saida("          <td class=\"" . $this->css_scGridToolbarPadd . "\" nowrap valign=\"middle\" align=\"right\" width=\"33%\"> \r\n");
      if ($this->nmgp_botoes['xls'] == "on" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq'] && empty($this->nm_grid_sem_reg) && !$this->grid_emb_form)
      {
              $Cod_Btn = nmButtonOutput($this->arr_buttons, "bexcel", "nm_gp_move('xls', '0')", "nm_gp_move('xls', '0')", "xls_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
              $nm_saida->saida("           $Cod_Btn \r\n");
              $NM_btn = true;
      }
      if ($this->nmgp_botoes['xml'] == "on" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq'] && empty($this->nm_grid_sem_reg) && !$this->grid_emb_form)
      {
              $Cod_Btn = nmButtonOutput($this->arr_buttons, "bxml", "nm_gp_move('xml', '0')", "nm_gp_move('xml', '0')", "xml_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
              $nm_saida->saida("           $Cod_Btn \r\n");
              $NM_btn = true;
      }
          if (is_file("grid_public_v_dte_help.txt") && !$this->grid_emb_form)
          {
             $Arq_WebHelp = file("grid_public_v_dte_help.txt"); 
             if (isset($Arq_WebHelp[0]) && !empty($Arq_WebHelp[0]))
             {
                 $Arq_WebHelp[0] = str_replace("\r\n" , "", trim($Arq_WebHelp[0]));
                 $Tmp = explode(";", $Arq_WebHelp[0]); 
                 foreach ($Tmp as $Cada_help)
                 {
                     $Tmp1 = explode(":", $Cada_help); 
                     if (!empty($Tmp1[0]) && isset($Tmp1[1]) && !empty($Tmp1[1]) && $Tmp1[0] == "cons" && is_file($this->Ini->root . $this->Ini->path_help . $Tmp1[1]))
                     {
                        $Cod_Btn = nmButtonOutput($this->arr_buttons, "bhelp", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "help_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
                        $nm_saida->saida("           $Cod_Btn \r\n");
                        $NM_btn = true;
                     }
                 }
             }
          }
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['b_sair'] || $this->grid_emb_form)
      {
         $this->nmgp_botoes['exit'] = "off"; 
      }
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_psq'])
      {
      }
      elseif ($this->nmgp_botoes['exit'] == "on")
      {
        if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sc_modal']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sc_modal'])
        {
           $Cod_Btn = nmButtonOutput($this->arr_buttons, "bvoltar", "self.parent.tb_remove()", "self.parent.tb_remove()", "sai_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
        }
        else
        {
           $Cod_Btn = nmButtonOutput($this->arr_buttons, "bvoltar", "window.close()", "window.close()", "sai_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
        }
         $nm_saida->saida("           $Cod_Btn \r\n");
         $NM_btn = true;
      }
      $nm_saida->saida("         </td> \r\n");
      $nm_saida->saida("        </tr> \r\n");
      $nm_saida->saida("       </table> \r\n");
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
      { 
          $this->Ini->Arr_result['setValue'][] = array('field' => 'sc_grid_toobar_top', 'value' => NM_charset_to_utf8($_SESSION['scriptcase']['saida_html']));
          $_SESSION['scriptcase']['saida_html'] = "";
      } 
      $nm_saida->saida("      </td> \r\n");
      $nm_saida->saida("     </form> \r\n");
      $nm_saida->saida("     </tr> \r\n");
      if (!$NM_btn && isset($NM_ult_sep))
      {
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
          { 
              $this->Ini->Arr_result['setDisplay'][] = array('field' => $NM_ult_sep, 'value' => 'none');
          } 
          $nm_saida->saida("     <script language=\"javascript\">\r\n");
          $nm_saida->saida("        document.getElementById('" . $NM_ult_sep . "').style.display='none';\r\n");
          $nm_saida->saida("     </script>\r\n");
      }
   }
   function nmgp_barra_bot()
   {
      global 
             $nm_saida, $nm_url_saida, $nm_apl_dependente;
      $NM_btn = false;
      $this->NM_calc_span();
      $nm_saida->saida("      <tr>\r\n");
      $nm_saida->saida("      <div id=\"div_F0_bot\" style=\"display: none\"> \r\n");
      $nm_saida->saida("      <form name=\"F0_bot\" method=\"post\" action=\"grid_public_v_dte.php\" target=\"_self\"> \r\n");
      $nm_saida->saida("      <input type=\"hidden\" id=\"script_init_f0_bot\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
      $nm_saida->saida("      <input type=hidden id=\"script_session_f0_bot\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\"/>\r\n");
      $nm_saida->saida("      <input type=\"hidden\" id=\"opcao_f0_bot\" name=\"nmgp_opcao\" value=\"muda_qt_linhas\"/> \r\n");
      $nm_saida->saida("      </div> \r\n");
      $nm_saida->saida("       <td id=\"sc_grid_toobar_bot\" class=\"" . $this->css_scGridTabelaTd . "\" valign=\"top\"> \r\n");
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
      { 
          $_SESSION['scriptcase']['saida_html'] = "";
      } 
      $nm_saida->saida("        <table class=\"" . $this->css_scGridToolbar . "\" style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\" width=\"100%\" valign=\"top\">\r\n");
      $nm_saida->saida("         <tr> \r\n");
      $nm_saida->saida("          <td class=\"" . $this->css_scGridToolbarPadd . "\" nowrap valign=\"middle\" align=\"left\" width=\"33%\"> \r\n");
          if (empty($this->nm_grid_sem_reg) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['paginacao']))
          {
              $Reg_Page  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'];
              $Cod_Btn = nmButtonOutput($this->arr_buttons, "birpara", "var rec_nav = ((document.getElementById('rec_f0_bot').value - 1) * " . NM_encode_input($Reg_Page) . ") + 1; nm_gp_submit_ajax('muda_rec_linhas', rec_nav)", "var rec_nav = ((document.getElementById('rec_f0_bot').value - 1) * " . NM_encode_input($Reg_Page) . ") + 1; nm_gp_submit_ajax('muda_rec_linhas', rec_nav)", "brec_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
              $nm_saida->saida("           $Cod_Btn \r\n");
              $Page_Atu   = ceil($this->nmgp_reg_inicial / $Reg_Page);
              $nm_saida->saida("          <input id=\"rec_f0_bot\" type=\"text\" class=\"" . $this->css_css_toolbar_obj . "\" name=\"rec\" value=\"" . NM_encode_input($Page_Atu) . "\" style=\"width:25px;vertical-align: middle;\"/> \r\n");
              $NM_btn = true;
          }
          if (empty($this->nm_grid_sem_reg) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['paginacao']))
          {
              $nm_saida->saida("          <span class=\"" . $this->css_css_toolbar_obj . "\" style=\"border: 0px;vertical-align: middle;\">" . $this->Ini->Nm_lang['lang_btns_rows'] . "</span>\r\n");
              $nm_saida->saida("          <select class=\"" . $this->css_css_toolbar_obj . "\" style=\"vertical-align: middle;\" id=\"quant_linhas_f0_bot\" name=\"nmgp_quant_linhas\" onchange=\"sc_ind = document.getElementById('quant_linhas_f0_bot').selectedIndex; nm_gp_submit_ajax('muda_qt_linhas', document.getElementById('quant_linhas_f0_bot').options[sc_ind].value)\"> \r\n");
              $obj_sel = ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'] == 10) ? " selected" : "";
              $nm_saida->saida("           <option value=\"10\" " . $obj_sel . ">10</option>\r\n");
              $obj_sel = ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'] == 20) ? " selected" : "";
              $nm_saida->saida("           <option value=\"20\" " . $obj_sel . ">20</option>\r\n");
              $obj_sel = ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'] == 50) ? " selected" : "";
              $nm_saida->saida("           <option value=\"50\" " . $obj_sel . ">50</option>\r\n");
              $nm_saida->saida("          </select>\r\n");
              $NM_btn = true;
          }
          $nm_saida->saida("         </td> \r\n");
          $nm_saida->saida("          <td class=\"" . $this->css_scGridToolbarPadd . "\" nowrap valign=\"middle\" align=\"center\" width=\"33%\"> \r\n");
          if ($this->nmgp_botoes['first'] == "on" && empty($this->nm_grid_sem_reg) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['nav']))
          {
              if ($this->Rec_ini == 0)
              {
                  $Cod_Btn = nmButtonOutput($this->arr_buttons, "bcons_inicio_off", "nm_gp_submit_rec('ini')", "nm_gp_submit_rec('ini')", "first_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
                  $nm_saida->saida("           $Cod_Btn \r\n");
              }
              else
              {
                  $Cod_Btn = nmButtonOutput($this->arr_buttons, "bcons_inicio", "nm_gp_submit_rec('ini')", "nm_gp_submit_rec('ini')", "first_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
                  $nm_saida->saida("           $Cod_Btn \r\n");
              }
                  $NM_btn = true;
          }
          if ($this->nmgp_botoes['back'] == "on" && empty($this->nm_grid_sem_reg) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['nav']))
          {
              if ($this->Rec_ini == 0)
              {
                  $Cod_Btn = nmButtonOutput($this->arr_buttons, "bcons_retorna_off", "nm_gp_submit_rec('" . $this->Rec_ini . "')", "nm_gp_submit_rec('" . $this->Rec_ini . "')", "back_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
                  $nm_saida->saida("           $Cod_Btn \r\n");
              }
              else
              {
                  $Cod_Btn = nmButtonOutput($this->arr_buttons, "bcons_retorna", "nm_gp_submit_rec('" . $this->Rec_ini . "')", "nm_gp_submit_rec('" . $this->Rec_ini . "')", "back_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
                  $nm_saida->saida("           $Cod_Btn \r\n");
              }
                  $NM_btn = true;
          }
          if (empty($this->nm_grid_sem_reg) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['nav']) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['paginacao']))
          {
              $Reg_Page  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['qt_lin_grid'];
              $Max_link   = 5;
              $Mid_link   = ceil($Max_link / 2);
              $Corr_link  = (($Max_link % 2) == 0) ? 0 : 1;
              $Qtd_Pages  = ceil($this->count_ger / $Reg_Page);
              $Page_Atu   = ceil($this->nmgp_reg_final / $Reg_Page);
              $Link_ini   = 1;
              if ($Page_Atu > $Max_link)
              {
                  $Link_ini = $Page_Atu - $Mid_link + $Corr_link;
              }
              elseif ($Page_Atu > $Mid_link)
              {
                  $Link_ini = $Page_Atu - $Mid_link + $Corr_link;
              }
              if (($Qtd_Pages - $Link_ini) < $Max_link)
              {
                  $Link_ini = ($Qtd_Pages - $Max_link) + 1;
              }
              if ($Link_ini < 1)
              {
                  $Link_ini = 1;
              }
              for ($x = 0; $x < $Max_link && $Link_ini <= $Qtd_Pages; $x++)
              {
                  $rec = (($Link_ini - 1) * $Reg_Page) + 1;
                  if ($Link_ini == $Page_Atu)
                  {
                      $nm_saida->saida("            <span class=\"scGridToolbarNavOpen\" style=\"vertical-align: middle;\">" . $Link_ini . "</span>\r\n");
                  }
                  else
                  {
                      $nm_saida->saida("            <a class=\"scGridToolbarNav\" style=\"vertical-align: middle;\" href=\"javascript: nm_gp_submit_rec(" . $rec . ")\">" . $Link_ini . "</a>\r\n");
                  }
                  $Link_ini++;
                  if (($x + 1) < $Max_link && $Link_ini <= $Qtd_Pages && '' != $this->Ini->Str_toolbarnav_separator && @is_file($this->Ini->root . $this->Ini->path_img_global . $this->Ini->Str_toolbarnav_separator))
                  {
                      $nm_saida->saida("            <img src=\"" . $this->Ini->path_img_global . $this->Ini->Str_toolbarnav_separator . "\" align=\"absmiddle\" style=\"vertical-align: middle;\">\r\n");
                  }
              }
              $NM_btn = true;
          }
          if ($this->nmgp_botoes['forward'] == "on" && empty($this->nm_grid_sem_reg) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['nav']))
          {
              $Cod_Btn = nmButtonOutput($this->arr_buttons, "bcons_avanca", "nm_gp_submit_rec('" . $this->Rec_fim . "')", "nm_gp_submit_rec('" . $this->Rec_fim . "')", "forward_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
              $nm_saida->saida("           $Cod_Btn \r\n");
              $NM_btn = true;
          }
          if ($this->nmgp_botoes['last'] == "on" && empty($this->nm_grid_sem_reg) && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['nav']))
          {
              $Cod_Btn = nmButtonOutput($this->arr_buttons, "bcons_final", "nm_gp_submit_rec('fim')", "nm_gp_submit_rec('fim')", "last_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
              $nm_saida->saida("           $Cod_Btn \r\n");
              $NM_btn = true;
          }
          $nm_saida->saida("         </td> \r\n");
          $nm_saida->saida("          <td class=\"" . $this->css_scGridToolbarPadd . "\" nowrap valign=\"middle\" align=\"right\" width=\"33%\"> \r\n");
          if (empty($this->nm_grid_sem_reg))
          {
              $nm_sumario = "[" . $this->Ini->Nm_lang['lang_othr_smry_info'] . "]";
              $nm_sumario = str_replace("?start?", $this->nmgp_reg_inicial, $nm_sumario);
              $nm_sumario = str_replace("?final?", $this->nmgp_reg_final, $nm_sumario);
              $nm_sumario = str_replace("?total?", $this->count_ger, $nm_sumario);
              $nm_saida->saida("           <span class=\"" . $this->css_css_toolbar_obj . "\" style=\"border:0px;\">" . $nm_sumario . "</span>\r\n");
              $NM_btn = true;
          }
          if (is_file("grid_public_v_dte_help.txt") && !$this->grid_emb_form)
          {
             $Arq_WebHelp = file("grid_public_v_dte_help.txt"); 
             if (isset($Arq_WebHelp[0]) && !empty($Arq_WebHelp[0]))
             {
                 $Arq_WebHelp[0] = str_replace("\r\n" , "", trim($Arq_WebHelp[0]));
                 $Tmp = explode(";", $Arq_WebHelp[0]); 
                 foreach ($Tmp as $Cada_help)
                 {
                     $Tmp1 = explode(":", $Cada_help); 
                     if (!empty($Tmp1[0]) && isset($Tmp1[1]) && !empty($Tmp1[1]) && $Tmp1[0] == "cons" && is_file($this->Ini->root . $this->Ini->path_help . $Tmp1[1]))
                     {
                        $Cod_Btn = nmButtonOutput($this->arr_buttons, "bhelp", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "help_bot", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
                        $nm_saida->saida("           $Cod_Btn \r\n");
                        $NM_btn = true;
                     }
                 }
             }
          }
      $nm_saida->saida("         </td> \r\n");
      $nm_saida->saida("        </tr> \r\n");
      $nm_saida->saida("       </table> \r\n");
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
      { 
          $this->Ini->Arr_result['setValue'][] = array('field' => 'sc_grid_toobar_bot', 'value' => NM_charset_to_utf8($_SESSION['scriptcase']['saida_html']));
          $_SESSION['scriptcase']['saida_html'] = "";
      } 
      $nm_saida->saida("      </td> \r\n");
      $nm_saida->saida("     </form> \r\n");
      $nm_saida->saida("     </tr> \r\n");
      if (!$NM_btn && isset($NM_ult_sep))
      {
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
          { 
              $this->Ini->Arr_result['setDisplay'][] = array('field' => $NM_ult_sep, 'value' => 'none');
          } 
          $nm_saida->saida("     <script language=\"javascript\">\r\n");
          $nm_saida->saida("        document.getElementById('" . $NM_ult_sep . "').style.display='none';\r\n");
          $nm_saida->saida("     </script>\r\n");
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
 function nm_fim_grid($flag_apaga_pdf_log = TRUE)
 {
   global
   $nm_saida, $nm_url_saida, $NMSC_modal;
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && isset($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css']))
   {
       unset($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css']);
       unset($_SESSION['sc_session'][$this->Ini->sc_page]['SC_sub_css_bw']);
   }
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
        return;
   } 
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" &&
        $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao_print'] != "print" && !$this->Print_All) 
   { 
      $nm_saida->saida("     <tr><td colspan=3  class=\"" . $this->css_scGridTabelaTd . "\" style=\"vertical-align: top\"> \r\n");
      $nm_saida->saida("     <iframe class=\"css_iframes\" id=\"nmsc_iframe_liga_B_grid_public_v_dte\" marginWidth=\"0px\" marginHeight=\"0px\" frameborder=\"0\" valign=\"top\" height=\"0px\" width=\"0px\" name=\"nm_iframe_liga_B_grid_public_v_dte\" scrolling=\"auto\" src=\"NM_Blank_Page.htm\"></iframe>\r\n");
      $nm_saida->saida("     </td></tr> \r\n");
   } 
   $nm_saida->saida("   </TABLE>\r\n");
   $nm_saida->saida("   </body>\r\n");
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" || $this->Print_All)
   { 
   $nm_saida->saida("   </HTML>\r\n");
        return;
   } 
   $nm_saida->saida("   <script type=\"text/javascript\">\r\n");
   if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
   { 
       if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['NM_arr_tree']))
       {
           $temp = array();
           foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['NM_arr_tree'] as $NM_aplic => $resto)
           {
               $temp[] = $NM_aplic;
           }
           $temp = array_unique($temp);
           foreach ($temp as $NM_aplic)
           {
               $nm_saida->saida("   NM_tab_" . $NM_aplic . " = new Array();\r\n");
           }
           foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['NM_arr_tree'] as $NM_aplic => $resto)
           {
               foreach ($resto as $NM_ind => $NM_quebra)
               {
                   foreach ($NM_quebra as $NM_nivel => $NM_tipo)
                   {
                       $nm_saida->saida("   NM_tab_" . $NM_aplic . "[" . $NM_ind . "] = '" . $NM_tipo . $NM_nivel . "';\r\n");
                   }
               }
           }
       }
   }
   $nm_saida->saida("   function NM_liga_tbody(tbody, Obj, Apl)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("      Nivel = parseInt (Obj[tbody].substr(3));\r\n");
   $nm_saida->saida("      for (ind = tbody + 1; ind < Obj.length; ind++)\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("           Nv = parseInt (Obj[ind].substr(3));\r\n");
   $nm_saida->saida("           Tp = Obj[ind].substr(0, 3);\r\n");
   $nm_saida->saida("           if (Nivel == Nv && Tp == 'top')\r\n");
   $nm_saida->saida("           {\r\n");
   $nm_saida->saida("               break;\r\n");
   $nm_saida->saida("           }\r\n");
   $nm_saida->saida("           if (((Nivel + 1) == Nv && Tp == 'top') || (Nivel == Nv && Tp == 'bot'))\r\n");
   $nm_saida->saida("           {\r\n");
   $nm_saida->saida("               document.getElementById('tbody_' + Apl + '_' + ind + '_' + Tp).style.display='';\r\n");
   $nm_saida->saida("           } \r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   function NM_apaga_tbody(tbody, Obj, Apl)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("      Nivel = Obj[tbody].substr(3);\r\n");
   $nm_saida->saida("      for (ind = tbody + 1; ind < Obj.length; ind++)\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("           Nv = Obj[ind].substr(3);\r\n");
   $nm_saida->saida("           Tp = Obj[ind].substr(0, 3);\r\n");
   $nm_saida->saida("           if ((Nivel == Nv && Tp == 'top') || Nv < Nivel)\r\n");
   $nm_saida->saida("           {\r\n");
   $nm_saida->saida("               break;\r\n");
   $nm_saida->saida("           }\r\n");
   $nm_saida->saida("           if ((Nivel != Nv) || (Nivel == Nv && Tp == 'bot'))\r\n");
   $nm_saida->saida("           {\r\n");
   $nm_saida->saida("               document.getElementById('tbody_' + Apl + '_' + ind + '_' + Tp).style.display='none';\r\n");
   $nm_saida->saida("               if (Tp == 'top')\r\n");
   $nm_saida->saida("               {\r\n");
   $nm_saida->saida("                   document.getElementById('b_open_' + Apl + '_' + ind).style.display='';\r\n");
   $nm_saida->saida("                   document.getElementById('b_close_' + Apl + '_' + ind).style.display='none';\r\n");
   $nm_saida->saida("               } \r\n");
   $nm_saida->saida("           } \r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   NM_obj_ant = '';\r\n");
   $nm_saida->saida("   function NM_apaga_div_lig(obj_nome)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("      if (NM_obj_ant != '')\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          NM_obj_ant.style.display='none';\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      obj = document.getElementById(obj_nome);\r\n");
   $nm_saida->saida("      NM_obj_ant = obj;\r\n");
   $nm_saida->saida("      ind_time = setTimeout(\"obj.style.display='none'\", 300);\r\n");
   $nm_saida->saida("      return ind_time;\r\n");
   $nm_saida->saida("   }\r\n");
   $str_pbfile = $this->Ini->root . $this->Ini->path_imag_temp . '/sc_pb_' . session_id() . '.tmp';
   if (@is_file($str_pbfile) && $flag_apaga_pdf_log)
   {
      @unlink($str_pbfile);
   }
   if ($this->Rec_ini == 0 && empty($this->nm_grid_sem_reg) && !$this->Print_All && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
   { 
       $nm_saida->saida("   document.getElementById('first_bot').disabled = true;\r\n");
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
       {
           $this->Ini->Arr_result['setDisabled'][] = array('field' => 'first_bot', 'value' => "true");
       }
       $nm_saida->saida("   document.getElementById('back_bot').disabled = true;\r\n");
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
       {
           $this->Ini->Arr_result['setDisabled'][] = array('field' => 'back_bot', 'value' => "true");
       }
   } 
   if ($this->rs_grid->EOF && empty($this->nm_grid_sem_reg) && !$this->Print_All && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
   {
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opc_liga']['nav']))
       { 
           if ($this->arr_buttons['bcons_avanca']['type'] != 'image')
           { 
               $nm_saida->saida("   document.getElementById('forward_bot').disabled = true;\r\n");
               $nm_saida->saida("   document.getElementById('forward_bot').className = \"scButton_" . $this->arr_buttons['bcons_avanca_off']['style'] . "\";\r\n");
               if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
               {
                   $this->Ini->Arr_result['setDisabled'][] = array('field' => 'forward_bot', 'value' => "true");
                   $this->Ini->Arr_result['setClass'][] = array('field' => 'forward_bot', 'value' => "scButton_" . $this->arr_buttons['bcons_avanca_off']['style']);
               }
               if ($this->arr_buttons['bcons_avanca']['display'] == 'only_img' || $this->arr_buttons['bcons_avanca']['display'] == 'text_img')
               { 
                   $nm_saida->saida("   document.getElementById('id_img_forward_bot').src = \"" . $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_avanca_off']['image'] . "\";\r\n");
                   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
                   {
                       $this->Ini->Arr_result['setSrc'][] = array('field' => 'id_img_forward_bot', 'value' => $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_avanca_off']['image']);
                   }
               } 
           } 
           else 
           { 
               $nm_saida->saida("   document.getElementById('forward_bot').disabled = true;\r\n");
               $nm_saida->saida("   document.getElementById('id_img_forward_bot').src = \"" . $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_avanca_off']['image'] . "\";\r\n");
               if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
               {
                   $this->Ini->Arr_result['setDisabled'][] = array('field' => 'forward_bot', 'value' => "true");
                   $this->Ini->Arr_result['setSrc'][] = array('field' => 'id_img_forward_bot', 'value' => $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_avanca_off']['image']);
               }
           } 
           if ($this->arr_buttons['bcons_final']['type'] != 'image')
           { 
               $nm_saida->saida("   document.getElementById('last_bot').disabled = true;\r\n");
               $nm_saida->saida("   document.getElementById('last_bot').className = \"scButton_" . $this->arr_buttons['bcons_final_off']['style'] . "\";\r\n");
               if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
               {
                  $this->Ini->Arr_result['setDisabled'][] = array('field' => 'last_bot', 'value' => "true");
                  $this->Ini->Arr_result['setClass'][] = array('field' => 'last_bot', 'value' => "scButton_" . $this->arr_buttons['bcons_final_off']['style']);
               }
               if ($this->arr_buttons['bcons_final']['display'] == 'only_img' || $this->arr_buttons['bcons_final']['display'] == 'text_img')
               { 
                   $nm_saida->saida("   document.getElementById('id_img_last_bot').src = \"" . $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_final_off']['image'] . "\";\r\n");
                   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
                   {
                       $this->Ini->Arr_result['setSrc'][] = array('field' => 'id_img_last_bot', 'value' => $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_final_off']['image']);
                   }
               } 
           } 
           else 
           { 
               $nm_saida->saida("   document.getElementById('last_bot').disabled = true;\r\n");
               $nm_saida->saida("   document.getElementById('id_img_last_bot').src = \"" . $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_final_off']['image'] . "\";\r\n");
               if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
               {
                   $this->Ini->Arr_result['setDisabled'][] = array('field' => 'last_bot', 'value' => "true");
                   $this->Ini->Arr_result['setSrc'][] = array('field' => 'id_img_last_bot', 'value' => $this->Ini->path_botoes . "/" . $this->arr_buttons['bcons_final_off']['image']);
               }
           } 
       } 
       $nm_saida->saida("   nm_gp_fim = \"fim\";\r\n");
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
       {
           $this->Ini->Arr_result['setVar'][] = array('var' => 'nm_gp_fim', 'value' => "fim");
       }
   }
   else
   {
       $nm_saida->saida("   nm_gp_fim = \"\";\r\n");
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
       {
           $this->Ini->Arr_result['setVar'][] = array('var' => 'nm_gp_fim', 'value' => "");
       }
   }
   if (isset($this->redir_modal) && !empty($this->redir_modal))
   {
       echo $this->redir_modal;
   }
   $nm_saida->saida("   </script>\r\n");
   if ($this->grid_emb_form)
   {
       $nm_saida->saida("   <script type=\"text/javascript\">\r\n");
       $nm_saida->saida("      parent.scAjaxDetailHeight('grid_public_v_dte', $(document).innerHeight());\r\n");
       $nm_saida->saida("   </script>\r\n");
   }
   $nm_saida->saida("   </HTML>\r\n");
 }
//--- 
//--- 
 function form_navegacao()
 {
   global
   $nm_saida, $nm_url_saida;
   $str_pbfile = $this->Ini->root . $this->Ini->path_imag_temp . '/sc_pb_' . session_id() . '.tmp';
   $nm_saida->saida("   <form name=\"F3\" method=\"post\" \r\n");
   $nm_saida->saida("                     action=\"grid_public_v_dte.php\" \r\n");
   $nm_saida->saida("                     target=\"_self\" style=\"display: none\"> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_chave\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_opcao\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_ordem\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_chave_det\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_quant_linhas\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_url_saida\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_parms\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_tipo_pdf\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_outra_jan\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_orig_pesq\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\"/> \r\n");
   $nm_saida->saida("   </form> \r\n");
   $nm_saida->saida("   <form name=\"F4\" method=\"post\" \r\n");
   $nm_saida->saida("                     action=\"grid_public_v_dte.php\" \r\n");
   $nm_saida->saida("                     target=\"_self\" style=\"display: none\"> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_opcao\" value=\"rec\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"rec\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nm_call_php\" value=\"\"/>\r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\"/> \r\n");
   $nm_saida->saida("   </form> \r\n");
   $nm_saida->saida("   <form name=\"F5\" method=\"post\" \r\n");
   $nm_saida->saida("                     action=\"grid_public_v_dte_pesq.class.php\" \r\n");
   $nm_saida->saida("                     target=\"_self\" style=\"display: none\"> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\"/> \r\n");
   $nm_saida->saida("   </form> \r\n");
   $nm_saida->saida("   <form name=\"F6\" method=\"post\" \r\n");
   $nm_saida->saida("                     action=\"grid_public_v_dte.php\" \r\n");
   $nm_saida->saida("                     target=\"_self\" style=\"display: none\"> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\"/> \r\n");
   $nm_saida->saida("   </form> \r\n");
   $nm_saida->saida("  <form name=\"Fdoc_word\" method=\"post\" \r\n");
   $nm_saida->saida("        action=\"grid_public_v_dte.php\" \r\n");
   $nm_saida->saida("        target=\"_self\"> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_opcao\" value=\"doc_word\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_cor_word\" value=\"AM\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"nmgp_navegator_print\" value=\"\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
   $nm_saida->saida("    <input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\"> \r\n");
   $nm_saida->saida("  </form> \r\n");
   $nm_saida->saida("   <script type=\"text/javascript\">\r\n");
   $nm_saida->saida("    document.Fdoc_word.nmgp_navegator_print.value = navigator.appName;\r\n");
   $nm_saida->saida("   function nm_gp_word_conf(cor)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       document.Fdoc_word.nmgp_cor_word.value = cor;\r\n");
   $nm_saida->saida("       document.Fdoc_word.submit();\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   var obj_tr      = \"\";\r\n");
   $nm_saida->saida("   var css_tr      = \"\";\r\n");
   $nm_saida->saida("   var field_over  = " . $this->NM_field_over . ";\r\n");
   $nm_saida->saida("   var field_click = " . $this->NM_field_click . ";\r\n");
   $nm_saida->saida("   function over_tr(obj, class_obj)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       if (field_over != 1)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       if (obj_tr == obj)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       obj.className = '" . $this->css_scGridFieldOver . "';\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   function out_tr(obj, class_obj)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       if (field_over != 1)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       if (obj_tr == obj)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       obj.className = class_obj;\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   function click_tr(obj, class_obj)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       if (field_click != 1)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       if (obj_tr != \"\")\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           obj_tr.className = css_tr;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       css_tr        = class_obj;\r\n");
   $nm_saida->saida("       if (obj_tr == obj)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           obj_tr     = '';\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       obj_tr        = obj;\r\n");
   $nm_saida->saida("       css_tr        = class_obj;\r\n");
   $nm_saida->saida("       obj.className = '" . $this->css_scGridFieldClick . "';\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   var tem_hint;\r\n");
   $nm_saida->saida("   function nm_mostra_hint(nm_obj, nm_evt, nm_mens)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       if (nm_mens == \"\")\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       tem_hint = true;\r\n");
   $nm_saida->saida("       if (document.layers)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           theString=\"<DIV CLASS='ttip'>\" + nm_mens + \"</DIV>\";\r\n");
   $nm_saida->saida("           document.tooltip.document.write(theString);\r\n");
   $nm_saida->saida("           document.tooltip.document.close();\r\n");
   $nm_saida->saida("           document.tooltip.left = nm_evt.pageX + 14;\r\n");
   $nm_saida->saida("           document.tooltip.top = nm_evt.pageY + 2;\r\n");
   $nm_saida->saida("           document.tooltip.visibility = \"show\";\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       else\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           if(document.getElementById)\r\n");
   $nm_saida->saida("           {\r\n");
   $nm_saida->saida("              nmdg_nav = navigator.appName;\r\n");
   $nm_saida->saida("              elm = document.getElementById(\"tooltip\");\r\n");
   $nm_saida->saida("              elml = nm_obj;\r\n");
   $nm_saida->saida("              elm.innerHTML = nm_mens;\r\n");
   $nm_saida->saida("              if (nmdg_nav == \"Netscape\")\r\n");
   $nm_saida->saida("              {\r\n");
   $nm_saida->saida("                  elm.style.height = elml.style.height;\r\n");
   $nm_saida->saida("                  elm.style.top = nm_evt.pageY + 2 + 'px';\r\n");
   $nm_saida->saida("                  elm.style.left = nm_evt.pageX + 14 + 'px';\r\n");
   $nm_saida->saida("              }\r\n");
   $nm_saida->saida("              else\r\n");
   $nm_saida->saida("              {\r\n");
   $nm_saida->saida("                  elm.style.top = nm_evt.y + document.body.scrollTop + 10 + 'px';\r\n");
   $nm_saida->saida("                  elm.style.left = nm_evt.x + document.body.scrollLeft + 10 + 'px';\r\n");
   $nm_saida->saida("              }\r\n");
   $nm_saida->saida("              elm.style.visibility = \"visible\";\r\n");
   $nm_saida->saida("           }\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   function nm_apaga_hint()\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       if (!tem_hint)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           return;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       tem_hint = false;\r\n");
   $nm_saida->saida("       if (document.layers)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           document.tooltip.visibility = \"hidden\";\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       else\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           if(document.getElementById)\r\n");
   $nm_saida->saida("           {\r\n");
   $nm_saida->saida("              elm.style.visibility = \"hidden\";\r\n");
   $nm_saida->saida("           }\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("   }\r\n");
   if ($this->Rec_ini == 0)
   {
       $nm_saida->saida("   nm_gp_ini = \"ini\";\r\n");
   }
   else
   {
       $nm_saida->saida("   nm_gp_ini = \"\";\r\n");
   }
   $nm_saida->saida("   nm_gp_rec_ini = \"" . $this->Rec_ini . "\";\r\n");
   $nm_saida->saida("   nm_gp_rec_fim = \"" . $this->Rec_fim . "\";\r\n");
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['ajax_nav'])
   {
       if ($this->Rec_ini == 0)
       {
           $this->Ini->Arr_result['setVar'][] = array('var' => 'nm_gp_ini', 'value' => "ini");
       }
       else
       {
           $this->Ini->Arr_result['setVar'][] = array('var' => 'nm_gp_ini', 'value' => "");
       }
       $this->Ini->Arr_result['setVar'][] = array('var' => 'nm_gp_rec_ini', 'value' => $this->Rec_ini);
       $this->Ini->Arr_result['setVar'][] = array('var' => 'nm_gp_rec_fim', 'value' => $this->Rec_fim);
   }
   $nm_saida->saida("   function nm_gp_submit_rec(campo) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      if (nm_gp_ini == \"ini\" && (campo == \"ini\" || campo == nm_gp_rec_ini)) \r\n");
   $nm_saida->saida("      { \r\n");
   $nm_saida->saida("          return; \r\n");
   $nm_saida->saida("      } \r\n");
   $nm_saida->saida("      if (nm_gp_fim == \"fim\" && (campo == \"fim\" || campo == nm_gp_rec_fim)) \r\n");
   $nm_saida->saida("      { \r\n");
   $nm_saida->saida("          return; \r\n");
   $nm_saida->saida("      } \r\n");
   $nm_saida->saida("      nm_gp_submit_ajax(\"rec\", campo); \r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit_qsearch(pos) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      var out_qsearch = \"\";\r\n");
   $nm_saida->saida("       out_qsearch = document.getElementById('fast_search_f0_' + pos).value;\r\n");
   $nm_saida->saida("       out_qsearch += \"_SCQS_\" + document.getElementById('cond_fast_search_f0_' + pos).value;\r\n");
   $nm_saida->saida("       out_qsearch += \"_SCQS_\" + document.getElementById('SC_fast_search_' + pos).value;\r\n");
   $nm_saida->saida("       ajax_navigate('fast_search', out_qsearch); \r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit_ajax(opc, parm) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      ajax_navigate(opc, parm); \r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit(campo) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      document.F3.target = \"_self\"; \r\n");
   $nm_saida->saida("      document.F3.nmgp_parms.value = campo ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_opcao.value = \"igual\" ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_url_saida.value = \"\";\r\n");
   $nm_saida->saida("      document.F3.action           = \"grid_public_v_dte.php\"  ;\r\n");
   $nm_saida->saida("      document.F3.submit() ;\r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit2(campo) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      nm_gp_submit_ajax(\"ordem\", campo); \r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit3(campo, opc) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      document.F3.nmgp_parms.value = \"\";\r\n");
   $nm_saida->saida("      document.F3.target               = \"_self\"; \r\n");
   $nm_saida->saida("      document.F3.nmgp_chave_det.value = campo ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_opcao.value     = opc ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_url_saida.value = \"\";\r\n");
   $nm_saida->saida("      document.F3.action               = \"grid_public_v_dte.php\"  ;\r\n");
   $nm_saida->saida("      document.F3.submit() ;\r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit4(apl_lig, apl_saida, parms, target, opc) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      document.F3.target               = target; \r\n");
   $nm_saida->saida("      document.F3.action               = apl_lig  ;\r\n");
   $nm_saida->saida("      if (opc != null) \r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_opcao.value = \"grid\" ;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      else\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_opcao.value = \"igual\" ;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      document.F3.nmgp_url_saida.value   = apl_saida ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_parms.value       = parms ;\r\n");
   $nm_saida->saida("      if (target == '_blank') \r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_outra_jan.value = \"true\" ;\r\n");
   $nm_saida->saida("          window.open('','jan_sc','location=no,menubar=no,resizable,scrollbars,status=no,toolbar=no');\r\n");
   $nm_saida->saida("          document.F3.target = \"jan_sc\"; \r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      document.F3.submit() ;\r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit5(apl_lig, apl_saida, parms, target, opc, modal_h, modal_w, m_confirm) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      parms = parms.replace(/@percent@/g, \"%\") \r\n");
   $nm_saida->saida("      if (m_confirm != null && m_confirm != '') \r\n");
   $nm_saida->saida("      { \r\n");
   $nm_saida->saida("          if (confirm(m_confirm))\r\n");
   $nm_saida->saida("          { }\r\n");
   $nm_saida->saida("          else\r\n");
   $nm_saida->saida("          {\r\n");
   $nm_saida->saida("             return;\r\n");
   $nm_saida->saida("          }\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      if (apl_lig.substr(0, 7) == \"http://\" || apl_lig.substr(0, 8) == \"https://\")\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          if (target == '_blank') \r\n");
   $nm_saida->saida("          {\r\n");
   $nm_saida->saida("              window.open (apl_lig);\r\n");
   $nm_saida->saida("          }\r\n");
   $nm_saida->saida("          else\r\n");
   $nm_saida->saida("          {\r\n");
   $nm_saida->saida("              window.location = apl_lig;\r\n");
   $nm_saida->saida("          }\r\n");
   $nm_saida->saida("          return;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      if (target == 'modal') \r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          par_modal = '?script_case_init=" . NM_encode_input($this->Ini->sc_page) . "&script_case_session=" . session_id() . "&nmgp_outra_jan=true&nmgp_url_saida=modal';\r\n");
   $nm_saida->saida("          if (opc != null && opc != '') \r\n");
   $nm_saida->saida("          {\r\n");
   $nm_saida->saida("              par_modal += '&nmgp_opcao=grid';\r\n");
   $nm_saida->saida("          }\r\n");
   $nm_saida->saida("          if (parms != null && parms != '') \r\n");
   $nm_saida->saida("          {\r\n");
   $nm_saida->saida("              par_modal += '&nmgp_parms=' + parms;\r\n");
   $nm_saida->saida("          }\r\n");
   $nm_saida->saida("          tb_show('', apl_lig + par_modal + '&TB_iframe=true&modal=true&height=' + modal_h + '&width=' + modal_w, '');\r\n");
   $nm_saida->saida("          return;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      document.F3.target               = target; \r\n");
   $nm_saida->saida("      if (target == '_blank') \r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_outra_jan.value = \"true\" ;\r\n");
   $nm_saida->saida("          window.open('','jan_sc','location=no,menubar=no,resizable,scrollbars,status=no,toolbar=no');\r\n");
   $nm_saida->saida("          document.F3.target = \"jan_sc\"; \r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      document.F3.action               = apl_lig  ;\r\n");
   $nm_saida->saida("      if (opc != null && opc != '') \r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_opcao.value = \"grid\" ;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      else\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_opcao.value = \"\" ;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      document.F3.nmgp_url_saida.value = apl_saida ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_parms.value     = parms ;\r\n");
   $nm_saida->saida("      document.F3.submit() ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_outra_jan.value   = \"\" ;\r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_submit6(apl_lig, apl_saida, parms, target, pos, alt, larg, opc, modal_h, modal_w) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      if (apl_lig.substr(0, 7) == \"http://\" || apl_lig.substr(0, 8) == \"https://\")\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          if (target == '_blank') \r\n");
   $nm_saida->saida("          {\r\n");
   $nm_saida->saida("              window.open (apl_lig);\r\n");
   $nm_saida->saida("          }\r\n");
   $nm_saida->saida("          else\r\n");
   $nm_saida->saida("          {\r\n");
   $nm_saida->saida("              window.location = apl_lig;\r\n");
   $nm_saida->saida("          }\r\n");
   $nm_saida->saida("          return;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      if (pos == \"A\") {obj = document.getElementById('nmsc_iframe_liga_A_grid_public_v_dte');} \r\n");
   $nm_saida->saida("      if (pos == \"B\") {obj = document.getElementById('nmsc_iframe_liga_B_grid_public_v_dte');} \r\n");
   $nm_saida->saida("      if (pos == \"E\") {obj = document.getElementById('nmsc_iframe_liga_E_grid_public_v_dte');} \r\n");
   $nm_saida->saida("      if (pos == \"D\") {obj = document.getElementById('nmsc_iframe_liga_D_grid_public_v_dte');} \r\n");
   $nm_saida->saida("      obj.style.height = (alt == parseInt(alt)) ? alt + 'px' : alt;\r\n");
   $nm_saida->saida("      obj.style.width  = (larg == parseInt(larg)) ? larg + 'px' : larg;\r\n");
   $nm_saida->saida("      document.F3.target = target; \r\n");
   $nm_saida->saida("      document.F3.action = apl_lig  ;\r\n");
   $nm_saida->saida("      if (opc != null && opc != '') \r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_opcao.value = \"grid\" ;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      else\r\n");
   $nm_saida->saida("      {\r\n");
   $nm_saida->saida("          document.F3.nmgp_opcao.value = \"\" ;\r\n");
   $nm_saida->saida("      }\r\n");
   $nm_saida->saida("      document.F3.nmgp_url_saida.value = apl_saida ;\r\n");
   $nm_saida->saida("      document.F3.nmgp_parms.value     = parms ;\r\n");
   $nm_saida->saida("      document.F3.submit() ;\r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_move(tipo) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("      document.F6.target = \"_self\"; \r\n");
   $nm_saida->saida("      document.F6.submit() ;\r\n");
   $nm_saida->saida("      return;\r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   function nm_gp_move(x, y, z, p, g) \r\n");
   $nm_saida->saida("   { \r\n");
   $nm_saida->saida("       document.F3.action           = \"grid_public_v_dte.php\"  ;\r\n");
   $nm_saida->saida("       document.F3.nmgp_parms.value = \"\" ;\r\n");
   $nm_saida->saida("       document.F3.nmgp_orig_pesq.value = \"\" ;\r\n");
   $nm_saida->saida("       document.F3.nmgp_url_saida.value = \"\" ;\r\n");
   $nm_saida->saida("       document.F3.nmgp_opcao.value = x; \r\n");
   $nm_saida->saida("       document.F3.nmgp_outra_jan.value = \"\" ;\r\n");
   $nm_saida->saida("       document.F3.target = \"_self\"; \r\n");
   $nm_saida->saida("       if (y == 1) \r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           document.F3.target = \"_blank\"; \r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       if (\"busca\" == x)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           document.F3.nmgp_orig_pesq.value = z; \r\n");
   $nm_saida->saida("           z = '';\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       if (z != null && z != '') \r\n");
   $nm_saida->saida("       { \r\n");
   $nm_saida->saida("           document.F3.nmgp_tipo_pdf.value = z; \r\n");
   $nm_saida->saida("       } \r\n");
   $nm_saida->saida("       if (\"xls\" == x)\r\n");
   $nm_saida->saida("       {\r\n");
   if (!extension_loaded("zip"))
   {
       $nm_saida->saida("           alert (\"" . html_entity_decode($this->Ini->Nm_lang['lang_othr_prod_xtzp']) . "\");\r\n");
       $nm_saida->saida("           return false;\r\n");
   } 
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       if (\"pdf\" == x)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           window.location = \"" . $this->Ini->path_link . "grid_public_v_dte/grid_public_v_dte_iframe.php?scsess=" . session_id() . "&str_tmp=" . $this->Ini->path_imag_temp . "&str_prod=" . $this->Ini->path_prod . "&str_btn=" . $this->Ini->Str_btn_css . "&str_lang=" . $this->Ini->str_lang . "&str_schema=" . $this->Ini->str_schema_all . "&script_case_init=" . NM_encode_input($this->Ini->sc_page) . "&script_case_session=" . session_id() . "&pbfile=" . urlencode($str_pbfile) . "&jspath=" . urlencode($this->Ini->path_js) . "&sc_apbgcol=" . urlencode($this->Ini->path_css) . "&sc_tp_pdf=\" + z + \"&sc_parms_pdf=\" + p + \"&sc_graf_pdf=\" + g;\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       else\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("           document.F3.submit();  \r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("   } \r\n");
   $nm_saida->saida("   nm_img = new Image();\r\n");
   $nm_saida->saida("   function nm_mostra_img(imagem, altura, largura)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       tb_show(\"\", imagem, \"\");\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   function nm_mostra_doc(campo1, campo2, campo3, campo4)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       while (campo2.lastIndexOf(\"&\") != -1)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("          campo2 = campo2.replace(\"&\" , \"**Ecom**\");\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       while (campo2.lastIndexOf(\"#\") != -1)\r\n");
   $nm_saida->saida("       {\r\n");
   $nm_saida->saida("          campo2 = campo2.replace(\"#\" , \"**Jvel**\");\r\n");
   $nm_saida->saida("       }\r\n");
   $nm_saida->saida("       NovaJanela = window.open (campo4 + \"?script_case_init=" . NM_encode_input($this->Ini->sc_page) . "&script_case_session=" . session_id() . "&nm_cod_doc=\" + campo1 + \"&nm_nome_doc=\" + campo2 + \"&nm_cod_apl=\" + campo3, \"ScriptCase\", \"resizable\");\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   function nm_escreve_window()\r\n");
   $nm_saida->saida("   {\r\n");
   if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['form_psq_ret']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campo_psq_ret']) && empty($this->nm_grid_sem_reg))
   {
      $nm_saida->saida("      if (document.Fpesq.nm_ret_psq.value != \"\")\r\n");
      $nm_saida->saida("      {\r\n");
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sc_modal']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['sc_modal'])
      {
          $nm_saida->saida("          var Obj_Form = parent.parent.document." . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['form_psq_ret'] . "." . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campo_psq_ret'] . ";\r\n");
          $nm_saida->saida("          var Obj_Doc = parent.parent;\r\n");
          $nm_saida->saida("          if (parent.parent.document.getElementById(\"id_read_on_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campo_psq_ret'] . "\"))\r\n");
          $nm_saida->saida("          {\r\n");
          $nm_saida->saida("              var Obj_Readonly = parent.parent.document.getElementById(\"id_read_on_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campo_psq_ret'] . "\");\r\n");
          $nm_saida->saida("          }\r\n");
      }
      else
      {
          $nm_saida->saida("          var Obj_Form = parent.opener.document." . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['form_psq_ret'] . "." . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campo_psq_ret'] . ";\r\n");
          $nm_saida->saida("          var Obj_Doc = parent.opener;\r\n");
          $nm_saida->saida("          if (parent.opener.document.getElementById(\"id_read_on_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campo_psq_ret'] . "\"))\r\n");
          $nm_saida->saida("          {\r\n");
          $nm_saida->saida("              var Obj_Readonly = parent.opener.document.getElementById(\"id_read_on_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campo_psq_ret'] . "\");\r\n");
          $nm_saida->saida("          }\r\n");
      }
          $nm_saida->saida("          else\r\n");
          $nm_saida->saida("          {\r\n");
          $nm_saida->saida("              var Obj_Readonly = null;\r\n");
          $nm_saida->saida("          }\r\n");
      $nm_saida->saida("          if (Obj_Form.value != document.Fpesq.nm_ret_psq.value)\r\n");
      $nm_saida->saida("          {\r\n");
      $nm_saida->saida("              Obj_Form.value = document.Fpesq.nm_ret_psq.value;\r\n");
      $nm_saida->saida("              if (null != Obj_Readonly)\r\n");
      $nm_saida->saida("              {\r\n");
      $nm_saida->saida("                  Obj_Readonly.innerHTML = document.Fpesq.nm_ret_psq.value;\r\n");
      $nm_saida->saida("              }\r\n");
     if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['js_apos_busca']))
     {
      $nm_saida->saida("              if (Obj_Doc." . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['js_apos_busca'] . ")\r\n");
      $nm_saida->saida("              {\r\n");
      $nm_saida->saida("                  Obj_Doc." . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['js_apos_busca'] . "();\r\n");
      $nm_saida->saida("              }\r\n");
      $nm_saida->saida("              else if (Obj_Form.onchange && Obj_Form.onchange != '')\r\n");
      $nm_saida->saida("              {\r\n");
      $nm_saida->saida("                  Obj_Form.onchange();\r\n");
      $nm_saida->saida("              }\r\n");
     }
     else
     {
      $nm_saida->saida("              if (Obj_Form.onchange && Obj_Form.onchange != '')\r\n");
      $nm_saida->saida("              {\r\n");
      $nm_saida->saida("                  Obj_Form.onchange();\r\n");
      $nm_saida->saida("              }\r\n");
     }
      $nm_saida->saida("          }\r\n");
      $nm_saida->saida("      }\r\n");
   }
   $nm_saida->saida("      document.F5.action = \"grid_public_v_dte_fim.php\";\r\n");
   $nm_saida->saida("      document.F5.submit();\r\n");
   $nm_saida->saida("   }\r\n");
   $nm_saida->saida("   function nm_open_popup(parms)\r\n");
   $nm_saida->saida("   {\r\n");
   $nm_saida->saida("       NovaJanela = window.open (parms, '', 'resizable, scrollbars');\r\n");
   $nm_saida->saida("   }\r\n");
   if ($this->grid_emb_form && isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['reg_start']))
   {
       $nm_saida->saida("      parent.scAjaxDetailStatus('grid_public_v_dte');\r\n");
       $nm_saida->saida("      parent.scAjaxDetailHeight('grid_public_v_dte', $(document).innerHeight());\r\n");
   }
   $nm_saida->saida("   </script>\r\n");
 }
}
?>
