<?php
class rep_est_relsa_grid
{
   var $Ini;
   var $Erro;
   var $Pdf;
   var $Db;
   var $rs_grid;
   var $nm_grid_sem_reg;
   var $SC_seq_register;
   var $nm_location;
   var $nm_data;
   var $nm_cod_barra;
   var $sc_proc_grid; 
   var $nmgp_botoes = array();
   var $Campos_Mens_erro;
   var $NM_raiz_img; 
   var $Font_ttf; 
   var $total = array();
   var $codi_empr = array();
   var $tipo_docu = array();
   var $est_xdte = array();
   var $fech_carg = array();
   var $look_est_xdte = array();
//--- 
 function monta_grid($linhas = 0)
 {

   clearstatcache();
   $this->inicializa();
   $this->grid();
 }
//--- 
 function inicializa()
 {
   global $nm_saida, 
   $rec, $nmgp_chave, $nmgp_opcao, $nmgp_ordem, $nmgp_chave_det, 
   $nmgp_quant_linhas, $nmgp_quant_colunas, $nmgp_url_saida, $nmgp_parms;
//
   $this->nm_data = new nm_data("es");
   $this->default_font = 'Arial';
   $this->default_font_sr  = 'Arial';
   $this->default_style    = '';
   $this->default_style_sr = 'B';
   $Tp_papel = "Letter";
   $old_dir = getcwd();
   $File_font_ttf = "";
   $this->Font_ttf = false;
   if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_font_ttf) && is_dir($this->Ini->path_third . "/tfpdf/"))
   {
       if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_arab))
       {
           $this->default_font    = "Simplified Arabic";
           $this->default_font_sr = "Simplified Arabic";
           $File_font_ttf         = "simpo.ttf";
       }
       if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_jap))
       {
           $this->default_font    = "IPAGothic";
           $this->default_font_sr = "IPAGothic";
           $File_font_ttf         = "ipag.ttf";
       }
       if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_rus))
       {
           $this->default_font    = "Gentium";
           $this->default_font_sr = "Gentium";
           $File_font_ttf         = "GenR102.TTF";
       }
       if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_chi))
       {
           $this->default_font    = "HanWangMingMedium";
           $this->default_font_sr = "HanWangMingMedium";
           $File_font_ttf         = "wt002.ttf";
       }
       if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_thai))
       {
           $this->default_font    = "Courpro";
           $this->default_font_sr = "Courpro";
           $File_font_ttf         = "courpro.ttf";
       }
       $this->default_style    = '';
       $this->default_style_sr = '';
       chdir($this->Ini->path_third . "/tfpdf/");
   }
   else
   {
       chdir($this->Ini->path_third . "/fpdf/");
   }
   include_once("visibility.php");
   chdir($old_dir);
   include_once($this->Ini->path_aplicacao . "rep_est_relsa_head_foot.php"); 
   $this->Pdf = new Header_Footer('L', 'mm', $Tp_papel);
   if (!empty($File_font_ttf))
   {
       $this->Font_ttf = true;
       $this->Pdf->AddFont($this->default_font, '', $File_font_ttf, true);
   }
   $this->Pdf->SetDisplayMode('real');
   $this->Pdf->AliasNbPages();
   $this->aba_iframe = false;
   if (isset($_SESSION['scriptcase']['sc_aba_iframe']))
   {
       foreach ($_SESSION['scriptcase']['sc_aba_iframe'] as $aba => $apls_aba)
       {
           if (in_array("rep_est_relsa", $apls_aba))
           {
               $this->aba_iframe = true;
               break;
           }
       }
   }
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['iframe_menu'])
   {
       $this->aba_iframe = true;
   }
   $this->nmgp_botoes['exit'] = "off";
   $this->sc_proc_grid = false; 
   $this->NM_raiz_img = $this->Ini->root;
   $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
   $this->nm_where_dinamico = "";
   $this->nm_grid_colunas = 0;
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['campos_busca']))
   { 
       $this->total[0] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['campos_busca']['total']; 
       $tmp_pos = strpos($this->total[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->total[0] = substr($this->total[0], 0, $tmp_pos);
       }
       $this->codi_empr[0] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['campos_busca']['codi_empr']; 
       $tmp_pos = strpos($this->codi_empr[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->codi_empr[0] = substr($this->codi_empr[0], 0, $tmp_pos);
       }
       $this->tipo_docu[0] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['campos_busca']['tipo_docu']; 
       $tmp_pos = strpos($this->tipo_docu[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->tipo_docu[0] = substr($this->tipo_docu[0], 0, $tmp_pos);
       }
       $this->est_xdte[0] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['campos_busca']['est_xdte']; 
       $tmp_pos = strpos($this->est_xdte[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->est_xdte[0] = substr($this->est_xdte[0], 0, $tmp_pos);
       }
   } 
   $this->nm_field_dinamico = array();
   $this->nm_order_dinamico = array();
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq_filtro'];
   $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
   $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
   $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "rep_est_relsa.php" ; 
   $_SESSION['scriptcase']['contr_link_emb'] = $this->nm_location;
   $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['qt_col_grid'] = 1 ;  
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['rep_est_relsa']['cols']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['rep_est_relsa']['cols']))
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['qt_col_grid'] = $_SESSION['scriptcase']['sc_apl_conf']['rep_est_relsa']['cols'];  
       unset($_SESSION['scriptcase']['sc_apl_conf']['rep_est_relsa']['cols']);
   }
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_select']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_select'] = array(); 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_select']['2'] = 'asc'; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_select']['3'] = 'asc'; 
   } 
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_quebra']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_grid'] = "" ; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_ant']  = "2,3"; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_desc'] = "" ; 
   }   
   if (!empty($nmgp_parms) && $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['opcao'] != "pdf")   
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['opcao'] = "igual";
       $rec = "ini";
   }
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_orig']) || $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['prim_cons'] || !empty($nmgp_parms))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['prim_cons'] = false;  
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_orig'] = " where ((codi_empr in (2,3,4)  AND  '" . $_SESSION['v_codi_empr'] . "'  IN (2,3,4)) OR (codi_empr = '" . $_SESSION['v_codi_empr'] . "'  AND '" . $_SESSION['v_codi_empr'] . "'  NOT IN (2,3,4) )) AND fech_carg = (now()  -  INTERVAL '3 days')::date";  
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq']        = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_orig'];  
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq_ant']    = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_orig'];  
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['cond_pesq']         = ""; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq_filtro'] = "";
   }   
   if  (!empty($this->nm_where_dinamico)) 
   {   
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq'] .= $this->nm_where_dinamico;
   }   
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq_filtro'];
//
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['tot_geral'][1])) 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['total'] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['tot_geral'][1] ;  
   }
   $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq_ant'] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq'];  
//----- 
   $nmgp_select = "SELECT count(*) as total, codi_empr, tipo_docu, est_xdte, fech_carg from " . $this->Ini->nm_tabela; 
   $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq']; 
   $nmgp_select .= " group by 2,3,4,5"; 
   $nmgp_order_by = ""; 
   $campos_order_select = "";
   foreach($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_select'] as $campo => $ordem) 
   {
        if ($campo != $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_grid']) 
        {
           if (!empty($campos_order_select)) 
           {
               $campos_order_select .= ", ";
           }
           $campos_order_select .= $campo . " " . $ordem;
        }
   }
   if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_grid'])) 
   { 
       $nmgp_order_by = " order by " . $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_grid'] . $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['ordem_desc']; 
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
   $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['order_grid'] = $nmgp_order_by;
   $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select; 
   $this->rs_grid = $this->Db->Execute($nmgp_select) ; 
   if ($this->rs_grid === false && !$this->rs_grid->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1) 
   { 
       $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
       exit ; 
   }  
   if ($this->rs_grid->EOF || ($this->rs_grid === false && $GLOBALS["NM_ERRO_IBASE"] == 1)) 
   { 
       $this->nm_grid_sem_reg = $this->Ini->Nm_lang['lang_errm_empt']; 
   }  
// 
 }  
// 
 function Pdf_init()
 {
   $this->Pdf->SetFont($this->default_font, $this->default_style, 12);
   $this->Pdf->SetTextColor(0, 0, 0);
 }
// 
 function Pdf_image()
 {
   $this->Pdf->Image($this->NM_raiz_img . $this->Ini->path_img_global . "/sys__NM__logoOpenB_341x77.jpg", 5, 180, 125, 30);
 }
// 
//----- 
 function grid($linhas = 0)
 {
    global 
           $nm_saida, $nm_url_saida;
   $HTTP_REFERER = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ""; 
   $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['seq_dir'] = 0; 
   $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['sub_dir'] = array(); 
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['where_pesq_filtro'];
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['rep_est_relsa']['lig_edit']) && $_SESSION['scriptcase']['sc_apl_conf']['rep_est_relsa']['lig_edit'] != '')
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['mostra_edit'] = $_SESSION['scriptcase']['sc_apl_conf']['rep_est_relsa']['lig_edit'];
   }
   if (!empty($this->nm_grid_sem_reg))
   {
       $this->Pdf->AddPage();
       $this->Pdf_init();
       $this->Pdf->SetFont($this->default_font_sr, 'B', 12);
       $this->Pdf->SetTextColor(0, 0, 0);
       $this->Pdf->Text(10, 10, html_entity_decode($this->nm_grid_sem_reg));
       $this->Pdf->Output($this->Ini->root . $this->Ini->nm_path_pdf);
       return;
   }
// 
   $Init_Pdf = true;
   $this->SC_seq_register = 0; 
   while (!$this->rs_grid->EOF) 
   {  
      $this->nm_grid_colunas = 0; 
      $nm_quant_linhas = 0;
      while (!$this->rs_grid->EOF && $nm_quant_linhas < $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_relsa']['qt_col_grid']) 
      {  
          $this->sc_proc_grid = true;
          $this->SC_seq_register++; 
          $this->total[$this->nm_grid_colunas] = $this->rs_grid->fields[0] ;  
          $this->total[$this->nm_grid_colunas] = (string)$this->total[$this->nm_grid_colunas];
          $this->codi_empr[$this->nm_grid_colunas] = $this->rs_grid->fields[1] ;  
          $this->codi_empr[$this->nm_grid_colunas] = (string)$this->codi_empr[$this->nm_grid_colunas];
          $this->tipo_docu[$this->nm_grid_colunas] = $this->rs_grid->fields[2] ;  
          $this->tipo_docu[$this->nm_grid_colunas] =  str_replace(",", ".", $this->tipo_docu[$this->nm_grid_colunas]);
          $this->tipo_docu[$this->nm_grid_colunas] = (string)$this->tipo_docu[$this->nm_grid_colunas];
          $this->est_xdte[$this->nm_grid_colunas] = $this->rs_grid->fields[3] ;  
          $this->est_xdte[$this->nm_grid_colunas] = (string)$this->est_xdte[$this->nm_grid_colunas];
          $this->fech_carg[$this->nm_grid_colunas] = $this->rs_grid->fields[4] ;  
          $this->look_est_xdte[$this->nm_grid_colunas] = $this->est_xdte[$this->nm_grid_colunas]; 
   $this->Lookup->lookup_est_xdte($this->look_est_xdte[$this->nm_grid_colunas]); 
          $this->total[$this->nm_grid_colunas] = trim($this->total[$this->nm_grid_colunas]); 
          if ($this->total[$this->nm_grid_colunas] === "") 
          { 
              $this->total[$this->nm_grid_colunas] = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($this->total[$this->nm_grid_colunas], $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
          } 
          if ($this->Font_ttf && !NM_is_utf8($this->total[$this->nm_grid_colunas]))
          {
              $this->total[$this->nm_grid_colunas] = mb_convert_encoding($this->total[$this->nm_grid_colunas], "UTF-8", $_SESSION['scriptcase']['charset']);
          }
          $this->Lookup->lookup_codi_empr($this->codi_empr[$this->nm_grid_colunas] , $this->codi_empr[$this->nm_grid_colunas]) ; 
          if ($this->Font_ttf && !NM_is_utf8($this->codi_empr[$this->nm_grid_colunas]))
          {
              $this->codi_empr[$this->nm_grid_colunas] = mb_convert_encoding($this->codi_empr[$this->nm_grid_colunas], "UTF-8", $_SESSION['scriptcase']['charset']);
          }
          $this->Lookup->lookup_tipo_docu($this->tipo_docu[$this->nm_grid_colunas] , $this->tipo_docu[$this->nm_grid_colunas]) ; 
          if ($this->Font_ttf && !NM_is_utf8($this->tipo_docu[$this->nm_grid_colunas]))
          {
              $this->tipo_docu[$this->nm_grid_colunas] = mb_convert_encoding($this->tipo_docu[$this->nm_grid_colunas], "UTF-8", $_SESSION['scriptcase']['charset']);
          }
          $this->est_xdte[$this->nm_grid_colunas] = trim($this->look_est_xdte[$this->nm_grid_colunas]); 
          if ($this->est_xdte[$this->nm_grid_colunas] === "") 
          { 
              $this->est_xdte[$this->nm_grid_colunas] = "" ;  
          } 
          if ($this->Font_ttf && !NM_is_utf8($this->est_xdte[$this->nm_grid_colunas]))
          {
              $this->est_xdte[$this->nm_grid_colunas] = mb_convert_encoding($this->est_xdte[$this->nm_grid_colunas], "UTF-8", $_SESSION['scriptcase']['charset']);
          }
          $this->fech_carg[$this->nm_grid_colunas] = trim($this->fech_carg[$this->nm_grid_colunas]); 
          if ($this->fech_carg[$this->nm_grid_colunas] === "") 
          { 
              $this->fech_carg[$this->nm_grid_colunas] = "" ;  
          } 
          else    
          { 
               $fech_carg_x =  $this->fech_carg[$this->nm_grid_colunas];
               nm_conv_limpa_dado($fech_carg_x, "YYYY-MM-DD");
               if (is_numeric($fech_carg_x) && $fech_carg_x > 0) 
               { 
                   $this->nm_data->SetaData($this->fech_carg[$this->nm_grid_colunas], "YYYY-MM-DD");
                   $this->fech_carg[$this->nm_grid_colunas] = html_entity_decode($this->nm_data->FormataSaida($this->Nm_date_format("DT", "ddmmaaaa")));
               } 
          } 
          if ($this->Font_ttf && !NM_is_utf8($this->fech_carg[$this->nm_grid_colunas]))
          {
              $this->fech_carg[$this->nm_grid_colunas] = mb_convert_encoding($this->fech_carg[$this->nm_grid_colunas], "UTF-8", $_SESSION['scriptcase']['charset']);
          }
          $_SESSION['rep_est_relsa']['fech_carg'] = $this->fech_carg[$this->nm_grid_colunas];
          $_SESSION['rep_est_relsa']['total'] = $this->total[$this->nm_grid_colunas];
          $_SESSION['rep_est_relsa']['codi_empr'] = $this->codi_empr[$this->nm_grid_colunas];
          $_SESSION['rep_est_relsa']['tipo_docu'] = $this->tipo_docu[$this->nm_grid_colunas];
          $_SESSION['rep_est_relsa']['est_xdte'] = $this->est_xdte[$this->nm_grid_colunas];
          if ($Init_Pdf)
          {
              $this->Pdf_init();
              $this->Pdf->AddPage();
              $this->Pdf_image();
              $Save_num_page = $this->Pdf->PageNo();
              $this->Pdf->SetY(33);
              $Init_Pdf = false;
          }
          $Current_num_page = $this->Pdf->PageNo();
          if ($Current_num_page != $Save_num_page)
          {
              $Save_num_page = $Current_num_page;
              $this->Pdf_image();
          }
            $cell_5 = array('posx' => 230, 'posy' => 10, 'data' => $this->fech_carg[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_6 = array('posx' => 200, 'posy' => 10, 'data' => 'Fecha DTE: ', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_7 = array('posx' => 250, 'posy' => 25, 'data' => 'Total', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_8 = array('posx' => 4, 'posy' => 25, 'data' => 'Empresa', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_9 = array('posx' => 90, 'posy' => 25, 'data' => 'Tipo DTE', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_10 = array('posx' => 180, 'posy' => 25, 'data' => 'Estado DTE', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_11 = array('posx' => 100, 'posy' => 10, 'data' => 'Reporte Estado DTE Empresas RELSA', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
          
            $cell_total = array('posx' => 250, 'posy' => 0, 'data' => $this->total[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_codi_empr = array('posx' => 5, 'posy' => 0, 'data' => $this->codi_empr[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_tipo_docu = array('posx' => 80, 'posy' => 0, 'data' => $this->tipo_docu[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_est_xdte = array('posx' => 160, 'posy' => 0, 'data' => $this->est_xdte[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);


            $this->Pdf->SetFont($cell_total['font_type'], $cell_total['font_style'], $cell_total['font_size']);
            $this->Pdf->SetTextColor($cell_total['color_r'], $cell_total['color_g'], $cell_total['color_b']);
            if (!empty($cell_total['posx']) && !empty($cell_total['posy']))
            {
                $this->Pdf->SetXY($cell_total['posx'], $cell_total['posy']);
            }
            elseif (!empty($cell_total['posx']))
            {
                $this->Pdf->SetX($cell_total['posx']);
            }
            elseif (!empty($cell_total['posy']))
            {
                $this->Pdf->SetY($cell_total['posy']);
            }
            $this->Pdf->Cell($cell_total['width'], 0, $cell_total['data'], 0, 0, $cell_total['align']);

            $this->Pdf->SetFont($cell_codi_empr['font_type'], $cell_codi_empr['font_style'], $cell_codi_empr['font_size']);
            $this->Pdf->SetTextColor($cell_codi_empr['color_r'], $cell_codi_empr['color_g'], $cell_codi_empr['color_b']);
            if (!empty($cell_codi_empr['posx']) && !empty($cell_codi_empr['posy']))
            {
                $this->Pdf->SetXY($cell_codi_empr['posx'], $cell_codi_empr['posy']);
            }
            elseif (!empty($cell_codi_empr['posx']))
            {
                $this->Pdf->SetX($cell_codi_empr['posx']);
            }
            elseif (!empty($cell_codi_empr['posy']))
            {
                $this->Pdf->SetY($cell_codi_empr['posy']);
            }
            $this->Pdf->Cell($cell_codi_empr['width'], 0, $cell_codi_empr['data'], 0, 0, $cell_codi_empr['align']);

            $this->Pdf->SetFont($cell_tipo_docu['font_type'], $cell_tipo_docu['font_style'], $cell_tipo_docu['font_size']);
            $this->Pdf->SetTextColor($cell_tipo_docu['color_r'], $cell_tipo_docu['color_g'], $cell_tipo_docu['color_b']);
            if (!empty($cell_tipo_docu['posx']) && !empty($cell_tipo_docu['posy']))
            {
                $this->Pdf->SetXY($cell_tipo_docu['posx'], $cell_tipo_docu['posy']);
            }
            elseif (!empty($cell_tipo_docu['posx']))
            {
                $this->Pdf->SetX($cell_tipo_docu['posx']);
            }
            elseif (!empty($cell_tipo_docu['posy']))
            {
                $this->Pdf->SetY($cell_tipo_docu['posy']);
            }
            $this->Pdf->Cell($cell_tipo_docu['width'], 0, $cell_tipo_docu['data'], 0, 0, $cell_tipo_docu['align']);

            $this->Pdf->SetFont($cell_est_xdte['font_type'], $cell_est_xdte['font_style'], $cell_est_xdte['font_size']);
            $this->Pdf->SetTextColor($cell_est_xdte['color_r'], $cell_est_xdte['color_g'], $cell_est_xdte['color_b']);
            if (!empty($cell_est_xdte['posx']) && !empty($cell_est_xdte['posy']))
            {
                $this->Pdf->SetXY($cell_est_xdte['posx'], $cell_est_xdte['posy']);
            }
            elseif (!empty($cell_est_xdte['posx']))
            {
                $this->Pdf->SetX($cell_est_xdte['posx']);
            }
            elseif (!empty($cell_est_xdte['posy']))
            {
                $this->Pdf->SetY($cell_est_xdte['posy']);
            }
            $this->Pdf->Cell($cell_est_xdte['width'], 0, $cell_est_xdte['data'], 0, 0, $cell_est_xdte['align']);

          $this->Pdf->Ln(5);
          $this->rs_grid->MoveNext();
          $this->sc_proc_grid = false;
          $nm_quant_linhas++ ;
      }  
   }  
   $this->rs_grid->Close();
   $this->Pdf->Output($this->Ini->root . $this->Ini->nm_path_pdf);
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
