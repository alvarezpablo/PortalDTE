<?php
   include_once('rep_est_dte_session.php');
   @session_start() ;
   $_SESSION['scriptcase']['rep_est_dte']['glo_nm_perfil']          = "con";
   $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_prod']       = "";
   $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_conf']       = "";
   $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imagens']    = "";
   $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imag_temp']  = "";
   $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_doc']        = "";
//
class rep_est_dte_ini
{
   var $nm_cod_apl;
   var $nm_nome_apl;
   var $nm_seguranca;
   var $nm_grupo;
   var $nm_autor;
   var $nm_versao_sc;
   var $nm_tp_lic_sc;
   var $nm_dt_criacao;
   var $nm_hr_criacao;
   var $nm_autor_alt;
   var $nm_dt_ult_alt;
   var $nm_hr_ult_alt;
   var $nm_timestamp;
   var $nm_app_version;
   var $nm_path_pdf;
   var $root;
   var $server;
   var $server_pdf;
   var $java_path;
   var $java_bin;
   var $java_protocol;
   var $sc_protocolo;
   var $path_prod;
   var $path_link;
   var $path_aplicacao;
   var $path_embutida;
   var $path_botoes;
   var $path_img_global;
   var $path_img_modelo;
   var $path_icones;
   var $path_imagens;
   var $path_imag_cab;
   var $path_imag_temp;
   var $path_libs;
   var $path_doc;
   var $str_lang;
   var $str_conf_reg;
   var $str_schema_all;
   var $Str_btn_grid;
   var $path_cep;
   var $path_secure;
   var $path_js;
   var $path_help;
   var $path_adodb;
   var $path_grafico;
   var $path_atual;
   var $Gd_missing;
   var $sc_site_ssl;
   var $nm_falta_var;
   var $nm_falta_var_db;
   var $nm_tpbanco;
   var $nm_servidor;
   var $nm_usuario;
   var $nm_senha;
   var $nm_database_encoding;
   var $nm_con_db2 = array();
   var $nm_con_persistente;
   var $nm_con_use_schema;
   var $nm_tabela;
   var $nm_col_dinamica   = array();
   var $nm_order_dinamico = array();
   var $nm_hidden_blocos  = array();
   var $sc_tem_trans_banco;
   var $nm_bases_all;
   var $nm_bases_postgres;
   var $nm_bases_sqlite;
   var $nm_width_col_dados;
   var $sc_page;
//
   function init($Tp_init = "")
   {
       global
             $nm_url_saida, $nm_apl_dependente, $script_case_init;

      @ini_set('magic_quotes_runtime', 0);
      $this->sc_page = $script_case_init;
      $_SESSION['scriptcase']['sc_num_page'] = $script_case_init;
      $_SESSION['scriptcase']['sc_cnt_sql']  = 0;
      $this->sc_charset['UTF-8'] = 'utf-8';
      $this->sc_charset['ISO-8859-1'] = 'iso-8859-1';
      $this->sc_charset['SJIS'] = 'shift-jis';
      $this->sc_charset['ISO-8859-14'] = 'iso-8859-14';
      $this->sc_charset['ISO-8859-7'] = 'iso-8859-7';
      $this->sc_charset['ISO-8859-10'] = 'iso-8859-10';
      $this->sc_charset['ISO-8859-3'] = 'iso-8859-3';
      $this->sc_charset['ISO-8859-15'] = 'iso-8859-15';
      $this->sc_charset['WINDOWS-1252'] = 'windows-1252';
      $this->sc_charset['ISO-8859-13'] = 'iso-8859-13';
      $this->sc_charset['ISO-8859-4'] = 'iso-8859-4';
      $this->sc_charset['ISO-8859-2'] = 'iso-8859-2';
      $this->sc_charset['ISO-8859-5'] = 'iso-8859-5';
      $this->sc_charset['KOI8-R'] = 'koi8-r';
      $this->sc_charset['WINDOWS-1251'] = 'windows-1251';
      $this->sc_charset['BIG-5'] = 'big5';
      $this->sc_charset['EUC-CN'] = 'EUC-CN';
      $this->sc_charset['EUC-JP'] = 'euc-jp';
      $this->sc_charset['ISO-2022-JP'] = 'iso-2022-jp';
      $this->sc_charset['EUC-KR'] = 'euc-kr';
      $this->sc_charset['ISO-2022-KR'] = 'iso-2022-kr';
      $this->sc_charset['ISO-8859-9'] = 'iso-8859-9';
      $this->sc_charset['ISO-8859-6'] = 'iso-8859-6';
      $this->sc_charset['ISO-8859-8'] = 'iso-8859-8';
      $this->sc_charset['ISO-8859-8-I'] = 'iso-8859-8-i';
      $_SESSION['scriptcase']['trial_version'] = 'N';
      $_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['decimal_db'] = "."; 

      $this->nm_cod_apl      = "rep_est_dte"; 
      $this->nm_nome_apl     = ""; 
      $this->nm_seguranca    = ""; 
      $this->nm_grupo        = "WebDTE"; 
      $this->nm_grupo_versao = "1"; 
      $this->nm_autor        = "admin"; 
      $this->nm_versao_sc    = "v6"; 
      $this->nm_tp_lic_sc    = "pe_pgsql_bronze"; 
      $this->nm_dt_criacao   = "20120709"; 
      $this->nm_hr_criacao   = "012824"; 
      $this->nm_autor_alt    = "admin"; 
      $this->nm_dt_ult_alt   = "20121228"; 
      $this->nm_hr_ult_alt   = "221131"; 
      $temp_bug_list         = explode(" ", microtime()); 
      list($NM_usec, $NM_sec) = $temp_bug_list; 
      $this->nm_timestamp    = (float) $NM_sec; 
      $this->nm_app_version  = "1.0.0";
// 
      $NM_dir_atual = getcwd();
      if (empty($NM_dir_atual))
      {
          $str_path_sys          = (isset($_SERVER['SCRIPT_FILENAME'])) ? $_SERVER['SCRIPT_FILENAME'] : $_SERVER['ORIG_PATH_TRANSLATED'];
          $str_path_sys          = str_replace("\\", '/', $str_path_sys);
          $str_path_sys          = str_replace('//', '/', $str_path_sys);
      }
      else
      {
          $sc_nm_arquivo         = explode("/", $_SERVER['PHP_SELF']);
          $str_path_sys          = str_replace("\\", "/", str_replace("\\\\", "\\", getcwd())) . "/" . $sc_nm_arquivo[count($sc_nm_arquivo)-1];
      }
      //check publication with the prod
      $str_path_apl_url = $_SERVER['PHP_SELF'];
      $str_path_apl_url = str_replace("\\", '/', $str_path_apl_url);
      $str_path_apl_url = str_replace('//', '/', $str_path_apl_url);
      $str_path_apl_url = substr($str_path_apl_url, 0, strrpos($str_path_apl_url, "/"));
      $str_path_apl_url = substr($str_path_apl_url, 0, strrpos($str_path_apl_url, "/")+1);
      $str_path_apl_dir = substr($str_path_sys, 0, strrpos($str_path_sys, "/"));
      $str_path_apl_dir = substr($str_path_apl_dir, 0, strrpos($str_path_apl_dir, "/")+1);
      //check prod
      if(empty($_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_prod']))
      {
              /*check prod*/$_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_prod'] = $str_path_apl_url . "_lib/prod";
      }
      //check img
      if(empty($_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imagens']))
      {
              /*check img*/$_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imagens'] = $str_path_apl_url . "_lib/file/img";
      }
      //check tmp
      if(empty($_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imag_temp']))
      {
              /*check tmp*/$_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imag_temp'] = $str_path_apl_url . "_lib/tmp";
      }
      //check doc
      if(empty($_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_doc']))
      {
              /*check doc*/$_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_doc'] = $str_path_apl_dir . "_lib/file/doc";
      }
      //end check publication with the prod
      $this->sc_site_ssl     = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? true : false;
      $this->sc_protocolo    = ($this->sc_site_ssl) ? 'https://' : 'http://';
      $this->sc_protocolo    = "";
      $this->path_prod       = $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_prod'];
      $this->path_conf       = $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_conf'];
      $this->path_imagens    = $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imagens'];
      $this->path_imag_temp  = $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_imag_temp'];
      $this->path_doc        = $_SESSION['scriptcase']['rep_est_dte']['glo_nm_path_doc'];
      if (!isset($_SESSION['scriptcase']['str_lang']) || empty($_SESSION['scriptcase']['str_lang']))
      {
          $_SESSION['scriptcase']['str_lang'] = "es";
      }
      if (!isset($_SESSION['scriptcase']['str_conf_reg']) || empty($_SESSION['scriptcase']['str_conf_reg']))
      {
          $_SESSION['scriptcase']['str_conf_reg'] = "es_cl";
      }
      $this->str_lang        = $_SESSION['scriptcase']['str_lang'];
      $this->str_conf_reg    = $_SESSION['scriptcase']['str_conf_reg'];
      $this->str_schema_all    = (isset($_SESSION['scriptcase']['str_schema_all']) && !empty($_SESSION['scriptcase']['str_schema_all'])) ? $_SESSION['scriptcase']['str_schema_all'] : "ScriptCase6_Blue/ScriptCase6_Blue";
      $_SESSION['scriptcase']['erro']['str_schema'] = $this->str_schema_all . "_error.css";
      $_SESSION['scriptcase']['erro']['str_lang']   = $this->str_lang;
      $this->server          = (!isset($_SERVER['HTTP_HOST'])) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
      if (!isset($_SERVER['HTTP_HOST']) && isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && !$this->sc_site_ssl )
      {
          $this->server         .= ":" . $_SERVER['SERVER_PORT'];
      }
      $this->server_pdf      = $this->server;
      $this->server          = "";
      $this->java_bin        = "";
      $this->java_path       = "";
      $this->java_protocol   = "http://";
      $str_path_web          = $_SERVER['PHP_SELF'];
      $str_path_web          = str_replace("\\", '/', $str_path_web);
      $str_path_web          = str_replace('//', '/', $str_path_web);
      $this->root            = substr($str_path_sys, 0, -1 * strlen($str_path_web));
      $this->path_aplicacao  = substr($str_path_sys, 0, strrpos($str_path_sys, '/'));
      $this->path_aplicacao  = substr($this->path_aplicacao, 0, strrpos($this->path_aplicacao, '/')) . '/rep_est_dte';
      $this->path_embutida   = substr($this->path_aplicacao, 0, strrpos($this->path_aplicacao, '/') + 1);
      $this->path_aplicacao .= '/';
      $this->path_link       = substr($str_path_web, 0, strrpos($str_path_web, '/'));
      $this->path_link       = substr($this->path_link, 0, strrpos($this->path_link, '/')) . '/';
      $this->path_botoes     = $this->path_link . "_lib/img";
      $this->path_img_global = $this->path_link . "_lib/img";
      $this->path_img_modelo = $this->path_link . "_lib/img";
      $this->path_icones     = $this->path_link . "_lib/img";
      $this->path_imag_cab   = $this->path_link . "_lib/img";
      $this->path_help       = $this->path_link . "_lib/webhelp/";
      $this->path_font       = $this->root . $this->path_link . "_lib/font/";
      $this->path_btn        = $this->root . $this->path_link . "_lib/buttons/";
      $this->path_css        = $this->root . $this->path_link . "_lib/css/";
      $this->path_lib_php    = $this->root . $this->path_link . "_lib/lib/php";
      $this->path_lib_js     = $this->root . $this->path_link . "_lib/lib/js";
      $this->path_lang       = "../_lib/lang/";
      $this->path_lang_js    = "../_lib/js/";
      $this->path_cep        = $this->path_prod . "/cep";
      $this->path_cor        = $this->path_prod . "/cor";
      $this->path_js         = $this->path_prod . "/lib/js";
      $this->path_libs       = $this->root . $this->path_prod . "/lib/php";
      $this->path_third      = $this->root . $this->path_prod . "/third";
      $this->path_secure     = $this->root . $this->path_prod . "/secure";
      $this->path_adodb      = $this->root . $this->path_prod . "/third/adodb";
      $_SESSION['scriptcase']['dir_temp'] = $this->root . $this->path_imag_temp;
      $_SESSION['scriptcase']['font_ttf'] = $this->path_font;
      if ($Tp_init == "Path_sub")
      {
          return;
      }
      $str_path = substr($this->path_prod, 0, strrpos($this->path_prod, '/') + 1);
      if (!is_file($this->root . $str_path . 'devel/class/xmlparser/nmXmlparserIniSys.class.php'))
      {
          unset($_SESSION['scriptcase']['nm_sc_retorno']);
          unset($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao']);
      }
      include($this->path_lang . $this->str_lang . ".lang.php");
      include($this->path_lang . "config_region.php");
      include($this->path_lang . "lang_config_region.php");
      asort($this->Nm_lang_conf_region);
      $_SESSION['scriptcase']['charset']  = (isset($this->Nm_lang['Nm_charset']) && !empty($this->Nm_lang['Nm_charset'])) ? $this->Nm_lang['Nm_charset'] : "ISO-8859-15";
      $_SESSION['scriptcase']['charset_html']  = (isset($this->sc_charset[$_SESSION['scriptcase']['charset']])) ? $this->sc_charset[$_SESSION['scriptcase']['charset']] : $_SESSION['scriptcase']['charset'];
      if (!function_exists("mb_convert_encoding"))
      {
          echo "<div><font size=6>" . $this->Nm_lang['lang_othr_prod_xtmb'] . "</font></div>";exit;
      } 
      foreach ($this->Nm_conf_reg[$this->str_conf_reg] as $ind => $dados)
      {
         if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($dados))
         {
             $this->Nm_conf_reg[$this->str_conf_reg][$ind] = mb_convert_encoding($dados, $_SESSION['scriptcase']['charset'], "UTF-8");
         }
      }
      if (isset($this->Nm_lang['lang_errm_dbcn_conn']))
      {
          $_SESSION['scriptcase']['db_conn_error'] = $this->Nm_lang['lang_errm_dbcn_conn'];
      }
      $PHP_ver = str_replace(".", "", phpversion()); 
      if (substr($PHP_ver, 0, 3) < 434)
      {
          echo "<div><font size=6>" . $this->Nm_lang['lang_othr_prod_phpv'] . "</font></div>";exit;
      } 
      if (file_exists($this->path_libs . "/ver.dat"))
      {
          $SC_ver = file($this->path_libs . "/ver.dat"); 
          $SC_ver = str_replace(".", "", $SC_ver[0]); 
          if (substr($SC_ver, 0, 5) < 40015)
          {
              echo "<div><font size=6>" . $this->Nm_lang['lang_othr_prod_incp'] . "</font></div>";exit;
          } 
      } 
      $this->nm_path_pdf     = $this->path_imag_temp . "/sc_pdf_" . date("YmdHis") . "_" . rand(0, 1000) . "_rep_est_dte" . ".pdf";
      $_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['path_doc'] = $this->path_doc; 
      $_SESSION['scriptcase']['nm_path_prod'] = $this->root . $this->path_prod . "/"; 
      if (empty($this->path_imag_cab))
      {
          $this->path_imag_cab = $this->path_img_global;
      }
      if (!is_dir($this->root . $this->path_prod))
      {
          echo "<style type=\"text/css\">";
          echo ".scButton_default { font-family: Arial, sans-serif; font-size: 11px; color: #555555; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__space_blue.jpg); }";
          echo ".scButton_disabled { font-family: Arial, sans-serif; font-size: 11px; color: #666666; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__btn_blueocean_off.png); }";
          echo ".scButton_onmousedown { font-family: Arial, sans-serif; font-size: 11px; color: #666666; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__space_blue.jpg); }";
          echo ".scButton_onmouseover { font-family: Arial, sans-serif; font-size: 11px; color: #555555; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__space_blue.jpg); }";
          echo ".scLink_default { text-decoration: underline; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:visited { text-decoration: underline; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:active { text-decoration: underline; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:hover { text-decoration: none; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo "</style>";
          echo "<table width=\"80%\" border=\"1\" height=\"117\">";
          echo "<tr>";
          echo "   <td bgcolor=\"\">";
          echo "       <b><font size=\"4\">" . $this->Nm_lang['lang_errm_cmlb_nfnd'] . "</font>";
          echo "  " . $this->root . $this->path_prod;
          echo "   </b></td>";
          echo " </tr>";
          echo "</table>";
          if (!$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu'] && (!isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan']) || !$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan'])) 
          { 
              if (isset($_SESSION['scriptcase']['nm_sc_retorno']) && !empty($_SESSION['scriptcase']['nm_sc_retorno'])) 
              { 
               $btn_value = "" . $this->Ini->Nm_lang['lang_btns_back'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_value))
               {
                   $btn_value = mb_convert_encoding($btn_value, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
               $btn_hint = "" . $this->Ini->Nm_lang['lang_btns_back_hint'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_hint))
               {
                   $btn_hint = mb_convert_encoding($btn_hint, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
?>
                   <input type="button" id="sai" onClick="window.location='<?php echo $_SESSION['scriptcase']['nm_sc_retorno'] ?>'; return false" class="scButton_default" value="<?php echo $btn_value ?>" title="<?php echo $btn_hint ?>" style="vertical-align: middle;">

<?php
              } 
              else 
              { 
               $btn_value = "" . $this->Ini->Nm_lang['lang_btns_exit'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_value))
               {
                   $btn_value = mb_convert_encoding($btn_value, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
               $btn_hint = "" . $this->Ini->Nm_lang['lang_btns_exit_hint'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_hint))
               {
                   $btn_hint = mb_convert_encoding($btn_hint, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
?>
                   <input type="button" id="sai" onClick="window.location='<?php echo $nm_url_saida ?>'; return false" class="scButton_default" value="<?php echo $btn_value ?>" title="<?php echo $btn_hint ?>" style="vertical-align: middle;">

<?php
              } 
          } 
          exit ;
      }

      $this->path_atual  = getcwd();
      $opsys = strtolower(php_uname());

      $this->nm_width_col_dados = 100;
// 
      include_once($this->path_aplicacao . "rep_est_dte_erro.class.php"); 
      $this->Erro = new rep_est_dte_erro();
      include_once($this->path_adodb . "/adodb.inc.php"); 
      $this->sc_Include($this->path_libs . "/nm_sec_prod.php", "F", "nm_reg_prod") ; 
      $this->sc_Include($this->path_libs . "/nm_ini_perfil.php", "F", "perfil_lib") ; 
      $this->sc_Include($this->path_lib_php . "/nm_edit.php", "F", "nmgp_Form_Num_Val") ; 
      $this->sc_Include($this->path_lib_php . "/nm_conv_dados.php", "F", "nm_conv_limpa_dado") ; 
      $this->sc_Include($this->path_lib_php . "/nm_data.class.php", "C", "nm_data") ; 
      $this->nm_data = new nm_data("es");
      $_SESSION['scriptcase']['nmamd'] = array();
      perfil_lib($this->path_libs);
      if (!isset($_SESSION['sc_session'][$this->sc_page]['SC_Check_Perfil']))
      {
          if(function_exists("nm_check_perfil_exists")) nm_check_perfil_exists($this->path_libs, $this->path_prod);
          $_SESSION['sc_session'][$this->sc_page]['SC_Check_Perfil'] = true;
      }
      if(function_exists("nm_check_pdf_server")) $this->server_pdf = nm_check_pdf_server($this->path_libs, $this->server_pdf);
      if(function_exists("nm_check_java_path"))  $this->java_path  = nm_check_java_path($this->path_libs);
      if(function_exists("nm_check_java_bin"))  $this->java_bin  = nm_check_java_bin($this->path_libs);
      if(function_exists("nm_check_java_protocol"))  $this->java_protocol  = (nm_check_java_protocol($this->path_libs) != '')? nm_check_java_protocol($this->path_libs) : $this->java_protocol;
      if (!isset($_SESSION['scriptcase']['sc_num_img']))
      { 
          $_SESSION['scriptcase']['sc_num_img'] = 1;
      } 
      $this->regionalDefault();
      $this->sc_tem_trans_banco = false;
      $this->nm_bases_postgres   = array("postgres", "postgres64", "postgres7");
      $this->nm_bases_sqlite     = array("sqlite", "pdosqlite");
      $this->nm_bases_all        = array_merge($this->nm_bases_postgres, $this->nm_bases_sqlite);
      $this->nm_font_ttf = array("ar", "ja", "pl", "ru", "sk", "thai", "zh_cn", "zh_hk", "cz", "el", "ko", "mk");
      $this->nm_ttf_arab = array("ar");
      $this->nm_ttf_jap  = array("ja");
      $this->nm_ttf_rus  = array("pl", "ru", "sk", "cz", "el", "mk");
      $this->nm_ttf_thai = array("thai");
      $this->nm_ttf_chi  = array("zh_cn", "zh_hk", "ko");
      $_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['seq_dir'] = 0; 
      $_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['sub_dir'] = array(); 
      $_SESSION['scriptcase']['nm_bases_security']  = "enc_nm_enc_v1DcBwDQJsDSN7HQJwDMvOVcFeDuB7VEX7DcFYH9B/Z1rYHQraHgrKZSJqV5FaHIJsD9XsZ9JeD1BeD5F7DMvmVcBUDWFaHIF7HQBqZSBqDSBeHuFUHgBYHErsDWB3DoJeHQXGDQFUHABYHQrqDMNODkBsH5XKVEF7HQFYZ1BOHIveHuJsHgrKZSJqDWFqHIFGHQXGDuBqD1BeHuBOHgrwVIB/H5XCHIX7DcBwH9B/HIrwV5JeDMBYDkBsH5FYHMBqHQJeH9FUHABYHQFaDMzGVIB/Dur/HMFUHQFYZkFGD1rKHQJeHgveVkJ3DuFaHMB/HQXGDQFUDSBYV5FaHgrwVcFeH5XCHIJsHQFYZkFGHAN7HQFGDMvCZSJ3DuX/ZuBODcJUZSX7HIBeD5BqHgvsZSJ3H5FqHMBOHQBqZSBOD1rwHQFaHgNOVkJqDWF/HIFUHQXGDuBqD1veHuB/DMvsVcBUDWrmVoX7HQFYZkFGHAN7HQF7DMvCHArsDWr/HMFaHQXGDuFaD1BeHuFUDMvmVIBsH5XCHMXGDcBwH9B/HIrwV5JeDMBYDkBsH5FYHIF7HQJeH9FUHANKVWJeDMBOV9BUDWJeHMB/HQFYZ1BOHANOHQX7HgNKHArsDuJeHIJeHQXGDQFUHAN7HQXGDMBYVIBsV5X/VEX7HQFYZkFGHIrwHQX7HgBOVkJ3H5F/HMX7DcJUZSX7HIBeD5BqHgvsZSJ3H5FqHMBqHQBqZSBqHIBOZMBOHgBYHArsDWB3DoXGHQXGDQFUHAN7HQJwDMzGZSNiH5FqHIrqHQFYZkBiHIveHQBODMveVkJqDuFaHMFaHQXGDuFaD1veHQF7DMrYV9BUH5XCHMrqDcBwH9B/HIrwV5JeDMBYDkBsH5FYDoXGDcJeZSFUZ1rwV5JeHgvsVcFCH5XCDoX7DcNwH9BqD1NaZMJwHgvCZSJqDWF/DoJeD9XsZSX7HIrwV5BOHgvsVcBOV5X/VoFaHQBsZSB/DSrYV5FGDMzGHEJGH5X/DoB/HQNmH9X7HABYVWJsDMBYVcBODWFaDoFUDcJUZkFUZ1BeZMBqHgNKDkFeV5FaZuBqD9NmZSFGHIrwV5BOHuNODkBOV5X7VENUHQFYH9FaD1rwD5rqDErKVkXeHEFqDoBOD9NmDQJsD1BeV5FUHuzGDkBOH5XKVoraDcBwH9B/HIrwV5JeDMBYDkBsH5FYDoXGDcJeZSFUZ1rwD5BOHuNODkFCH5FqVENUDcNwH9B/Z1rYD5FaDEBODkFeV5FqDoBOD9NmDQJsHIBeV5FUDMvmVcFKV5BmVoBqD9BsZkFGHArKHQBqDEBeHErsDWF/DoBqD9FYDuFaZ1N7HuB/DMvOVcrsDuX7HINUDcFYZSFaDSrYHQJwDEBODkFeH5FYVoFGHQJKDQBOZ1rwV5BOHgrKVcFCDWXCVoJwDcJUZ1FaD1rwZMB/DMNKZSXeH5F/ZuFaDcBwDuBOZ1N7V5JeHuvmVcrsDWXCHMBiD9BsVIraD1rwV5X7HgBeHEBUDurmZuJeHQXOZ9JeHAveHuBiHgvsVcBOV5FYDoFGD9BsZ1rqDSvOD5raHgNKDkFeDWF/HMBqD9XsZSX7D1veD5JwHuNOVcFiDWB3VENUHQNmVIraHArKHuX7DEvsHEFiDWFqDoBOD9NwDQJsDSBYD5NUHgrKZSJ3DWB3VEF7HQFYZ1FUHArKHQNUDMzGHEXeV5XCZuB/D9JKDQX7Z1N7D5JwDMzGVcBOV5F/VENUDcJUZ1B/HIrwHQJwDEBODkFeH5FYVoFGHQJKDQJwHArYHuF7HgrwVcFCDur/HMJwDcBqZSFaD1rwV5FGDMBYZSXeH5F/HMJsDcXGZSFUDSBYD5JsDMvmVcFKV5BmVoBqD9BsZkFGHArKD5BqDMzGHEJqV5FaDoBODcBwZSFGD1BeV5XGHuNOVcXKV5X7VoBOD9XOZSB/Z1BeV5FUDENOVkXeDWFqHIJsD9XsZ9JeD1BeD5F7DMvmVcBUDWrmVorqHQNmZkBiHAvCD5BOHgveDkXKDWrGZuJeHQJKDQJsZ1vCV5FGHuNOV9FeDWXCVorqHQNmH9FaHArKHuB/HgrKZSJGDWF/HMFaDcJeZSBiZ1BYV5FaHuzGV9FeDWXCDoJsDcBwH9B/Z1rYHQJwHgNOHEFiDWX7DorqD9XsDQJsZ1NaV5BiDMvmVcBODuFqVErqDcBqZ1FaHAN7V5FaDENODkFeDWF/HIraD9XsZSX7Z1N7VWFaHgvsVcBODuFqDoraD9XGZSBqHArKV5FUDMrYZSXeV5FqHIJsHQXOZ9JeZ1vCV5Je";
      ob_start();
      $this->prep_conect();
      if (isset($_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['initialize']) && $_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['initialize'])  
      { 
          $_SESSION['scriptcase']['rep_est_dte']['contr_erro'] = 'on';
 
$_SESSION['v_codi_empr'] = $_SESSION['_COD_EMP_USU_SESS'];

if ($_SESSION['v_codi_empr'] == ''){ 
	header('Location: /sel_emp.php');	
}

$_SESSION['scriptcase']['rep_est_dte']['contr_erro'] = 'off'; 
          $_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['initialize'] = false;
      } 
      $this->conectDB();
      if (!in_array(strtolower($this->nm_tpbanco), $this->nm_bases_all))
      {
          echo "<tr>";
          echo "   <td bgcolor=\"\">";
          echo "       <b><font size=\"4\">" . $this->Nm_lang['lang_errm_dbcn_nspt'] . "</font>";
          echo "  " . $perfil_trab;
          echo "   </b></td>";
          echo " </tr>";
          echo "</table>";
          if (!$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu'] && (!isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan']) || !$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan'])) 
          { 
              if (isset($_SESSION['scriptcase']['nm_sc_retorno']) && !empty($_SESSION['scriptcase']['nm_sc_retorno'])) 
              { 
                  echo "<a href='" . $_SESSION['scriptcase']['nm_sc_retorno'] . "' target='_self'><img border='0' src='" . $this->path_botoes . "/nm_ScriptCase6_BlueOcean_bvoltar.gif' title='" . $this->Nm_lang['lang_btns_rtrn_scrp_hint'] . "' align=absmiddle></a> \n" ; 
              } 
              else 
              { 
                  echo "<a href='$nm_url_saida' target='_self'><img border='0' src='" . $this->path_botoes . "/nm_ScriptCase6_BlueOcean_bsair.gif' title='" . $this->Nm_lang['lang_btns_exit_appl_hint'] . "' align=absmiddle></a> \n" ; 
              } 
          } 
          exit ;
      } 
      $this->Ajax_result_set = ob_get_contents();
      ob_end_clean();
      if (empty($this->nm_tabela))
      {
          $this->nm_tabela = "v_dte"; 
      }
   }
   function prep_conect()
   {
      if (isset($_SESSION['scriptcase']['sc_connection']) && !empty($_SESSION['scriptcase']['sc_connection']))
      {
          foreach ($_SESSION['scriptcase']['sc_connection'] as $NM_con_orig => $NM_con_dest)
          {
              if (isset($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao']) && $_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao'] == $NM_con_orig)
              {
/*NM*/            $_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao'] = $NM_con_dest;
              }
              if (isset($_SESSION['scriptcase']['rep_est_dte']['glo_nm_perfil']) && $_SESSION['scriptcase']['rep_est_dte']['glo_nm_perfil'] == $NM_con_orig)
              {
/*NM*/            $_SESSION['scriptcase']['rep_est_dte']['glo_nm_perfil'] = $NM_con_dest;
              }
              if (isset($_SESSION['scriptcase']['rep_est_dte']['glo_con_' . $NM_con_orig]))
              {
                  $_SESSION['scriptcase']['rep_est_dte']['glo_con_' . $NM_con_orig] = $NM_con_dest;
              }
          }
      }
      $con_devel             = (isset($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao'])) ? $_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao'] : ""; 
      $perfil_trab           = ""; 
      $this->nm_falta_var    = ""; 
      $this->nm_falta_var_db = ""; 
      $nm_crit_perfil        = false;
      if (isset($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao']) && !empty($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao']))
      {
          db_conect_devel($con_devel, $this->root . $this->path_prod, 'WebDTE', 2); 
          if (empty($_SESSION['scriptcase']['glo_tpbanco']) && empty($_SESSION['scriptcase']['glo_banco']))
          {
              $nm_crit_perfil = true;
          }
      }
      if (isset($_SESSION['scriptcase']['rep_est_dte']['glo_nm_perfil']) && !empty($_SESSION['scriptcase']['rep_est_dte']['glo_nm_perfil']))
      {
          $perfil_trab = $_SESSION['scriptcase']['rep_est_dte']['glo_nm_perfil'];
      }
      elseif (isset($_SESSION['scriptcase']['glo_perfil']) && !empty($_SESSION['scriptcase']['glo_perfil']))
      {
          $perfil_trab = $_SESSION['scriptcase']['glo_perfil'];
      }
      if (!empty($perfil_trab))
      {
          $_SESSION['scriptcase']['glo_senha_protect'] = "";
          carrega_perfil($perfil_trab, $this->path_libs, "S", $this->path_conf);
          if (empty($_SESSION['scriptcase']['glo_senha_protect']))
          {
              $nm_crit_perfil = true;
          }
      }
      else
      {
          $perfil_trab = $con_devel;
      }
      if (!isset($_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['embutida_init']) || !$_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['embutida_init']) 
      {
          if (!isset($_SESSION['v_codi_empr'])) 
          {
              $this->nm_falta_var .= "v_codi_empr; ";
          }
      }
// 
      if (isset($_SESSION['scriptcase']['glo_decimal_db']) && !empty($_SESSION['scriptcase']['glo_decimal_db']))
      {
         $_SESSION['sc_session'][$this->sc_page]['rep_est_dte']['decimal_db'] = $_SESSION['scriptcase']['glo_decimal_db']; 
      }
      if (!isset($_SESSION['scriptcase']['glo_tpbanco']))
      {
          if (!$nm_crit_perfil)
          {
              $this->nm_falta_var_db .= "glo_tpbanco; ";
          }
      }
      else
      {
          $this->nm_tpbanco = $_SESSION['scriptcase']['glo_tpbanco']; 
      }
      if (!isset($_SESSION['scriptcase']['glo_servidor']))
      {
          if (!$nm_crit_perfil)
          {
              $this->nm_falta_var_db .= "glo_servidor; ";
          }
      }
      else
      {
          $this->nm_servidor = $_SESSION['scriptcase']['glo_servidor']; 
      }
      if (!isset($_SESSION['scriptcase']['glo_banco']))
      {
          if (!$nm_crit_perfil)
          {
              $this->nm_falta_var_db .= "glo_banco; ";
          }
      }
      else
      {
          $this->nm_banco = $_SESSION['scriptcase']['glo_banco']; 
      }
      if (!isset($_SESSION['scriptcase']['glo_usuario']))
      {
          if (!$nm_crit_perfil)
          {
              $this->nm_falta_var_db .= "glo_usuario; ";
          }
      }
      else
      {
          $this->nm_usuario = $_SESSION['scriptcase']['glo_usuario']; 
      }
      if (!isset($_SESSION['scriptcase']['glo_senha']))
      {
          if (!$nm_crit_perfil)
          {
              $this->nm_falta_var_db .= "glo_senha; ";
          }
      }
      else
      {
          $this->nm_senha = $_SESSION['scriptcase']['glo_senha']; 
      }
      if (isset($_SESSION['scriptcase']['glo_database_encoding']))
      {
          $this->nm_database_encoding = $_SESSION['scriptcase']['glo_database_encoding']; 
      }
      if (isset($_SESSION['scriptcase']['glo_use_persistent']))
      {
          $this->nm_con_persistente = $_SESSION['scriptcase']['glo_use_persistent']; 
      }
      if (isset($_SESSION['scriptcase']['glo_use_schema']))
      {
          $this->nm_con_use_schema = $_SESSION['scriptcase']['glo_use_schema']; 
      }
// 
      if (!empty($this->nm_falta_var) || !empty($this->nm_falta_var_db) || $nm_crit_perfil)
      {
          echo "<style type=\"text/css\">";
          echo ".scButton_default { font-family: Arial, sans-serif; font-size: 11px; color: #555555; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__space_blue.jpg); }";
          echo ".scButton_disabled { font-family: Arial, sans-serif; font-size: 11px; color: #666666; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__btn_blueocean_off.png); }";
          echo ".scButton_onmousedown { font-family: Arial, sans-serif; font-size: 11px; color: #666666; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__space_blue.jpg); }";
          echo ".scButton_onmouseover { font-family: Arial, sans-serif; font-size: 11px; color: #555555; font-weight: bold; border-style: solid; border-width: 1px; padding: 3px 14px; background-image: url(../../img/scriptcase__NM__space_blue.jpg); }";
          echo ".scLink_default { text-decoration: underline; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:visited { text-decoration: underline; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:active { text-decoration: underline; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:hover { text-decoration: none; font-family: Arial, sans-serif; font-size: 12px; color: #0000AA;  }";
          echo "</style>";
          echo "<table width=\"80%\" border=\"1\" height=\"117\">";
          if (empty($this->nm_falta_var_db))
          {
              if (!empty($this->nm_falta_var))
              {
                  echo "<tr>";
                  echo "   <td bgcolor=\"\">";
                  echo "       <b><font size=\"4\">" . $this->Nm_lang['lang_errm_glob'] . "</font>";
                  echo "  " . $this->nm_falta_var;
                  echo "   </b></td>";
                  echo " </tr>";
              }
              if ($nm_crit_perfil)
              {
                  echo "<tr>";
                  echo "   <td bgcolor=\"\">";
                  echo "       <b><font size=\"4\">" . $this->Nm_lang['lang_errm_dbcn_nfnd'] . "</font>";
                  echo "  " . $perfil_trab;
                  echo "   </b></td>";
                  echo " </tr>";
              }
          }
          else
          {
              echo "<tr>";
              echo "   <td bgcolor=\"\">";
              echo "       <b><font size=\"4\">" . $this->Nm_lang['lang_errm_dbcn_data'] . "</font></b>";
              echo "   </td>";
              echo " </tr>";
          }
          echo "</table>";
          if (!$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu'] && (!isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan']) || !$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan'])) 
          { 
              if (isset($_SESSION['scriptcase']['nm_sc_retorno']) && !empty($_SESSION['scriptcase']['nm_sc_retorno'])) 
              { 
               $btn_value = "" . $this->Ini->Nm_lang['lang_btns_back'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_value))
               {
                   $btn_value = mb_convert_encoding($btn_value, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
               $btn_hint = "" . $this->Ini->Nm_lang['lang_btns_back_hint'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_hint))
               {
                   $btn_hint = mb_convert_encoding($btn_hint, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
?>
                   <input type="button" id="sai" onClick="window.location='<?php echo $_SESSION['scriptcase']['nm_sc_retorno'] ?>'; return false" class="scButton_default" value="<?php echo $btn_value ?>" title="<?php echo $btn_hint ?>" style="vertical-align: middle;">

<?php
              } 
              else 
              { 
               $btn_value = "" . $this->Ini->Nm_lang['lang_btns_exit'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_value))
               {
                   $btn_value = mb_convert_encoding($btn_value, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
               $btn_hint = "" . $this->Ini->Nm_lang['lang_btns_exit_hint'] . "";
               if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($btn_hint))
               {
                   $btn_hint = mb_convert_encoding($btn_hint, $_SESSION['scriptcase']['charset'], "UTF-8");
               }
?>
                   <input type="button" id="sai" onClick="window.location='<?php echo $nm_url_saida ?>'; return false" class="scButton_default" value="<?php echo $btn_value ?>" title="<?php echo $btn_hint ?>" style="vertical-align: middle;">

<?php
              } 
          } 
          exit ;
      }
      if (isset($_SESSION['scriptcase']['glo_db_master_usr']) && !empty($_SESSION['scriptcase']['glo_db_master_usr']))
      {
          $this->nm_usuario = $_SESSION['scriptcase']['glo_db_master_usr']; 
      }
      if (isset($_SESSION['scriptcase']['glo_db_master_pass']) && !empty($_SESSION['scriptcase']['glo_db_master_pass']))
      {
          $this->nm_senha = $_SESSION['scriptcase']['glo_db_master_pass']; 
      }
      if (isset($_SESSION['scriptcase']['glo_db_master_cript']) && !empty($_SESSION['scriptcase']['glo_db_master_cript']))
      {
          $_SESSION['scriptcase']['glo_senha_protect'] = $_SESSION['scriptcase']['glo_db_master_cript']; 
      }
   }
   function conectDB()
   {
      global $glo_senha_protect;
      $glo_senha_protect = (isset($_SESSION['scriptcase']['glo_senha_protect'])) ? $_SESSION['scriptcase']['glo_senha_protect'] : "S";
      if (isset($_SESSION['scriptcase']['nm_sc_retorno']) && !empty($_SESSION['scriptcase']['nm_sc_retorno']) && isset($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao']) && !empty($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao']))
      { 
          $this->Db = db_conect_devel($_SESSION['scriptcase']['rep_est_dte']['glo_nm_conexao'], $this->root . $this->path_prod, 'WebDTE'); 
      } 
      else 
      { 
          $this->Db = db_conect($this->nm_tpbanco, $this->nm_servidor, $this->nm_usuario, $this->nm_senha, $this->nm_banco, $glo_senha_protect, "S", $this->nm_con_persistente, $this->nm_con_db2, $this->nm_database_encoding); 
      } 
      if (in_array(strtolower($this->nm_tpbanco), $this->nm_bases_postgres))
      {
          $this->Db->Execute("SET DATESTYLE TO ISO");
      } 
   }
   function regionalDefault()
   {
       $_SESSION['scriptcase']['reg_conf']['date_format']   = (isset($this->Nm_conf_reg[$this->str_conf_reg]['data_format']))              ?  $this->Nm_conf_reg[$this->str_conf_reg]['data_format'] : "ddmmyyyy";
       $_SESSION['scriptcase']['reg_conf']['date_sep']      = (isset($this->Nm_conf_reg[$this->str_conf_reg]['data_sep']))                 ?  $this->Nm_conf_reg[$this->str_conf_reg]['data_sep'] : "/";
       $_SESSION['scriptcase']['reg_conf']['date_week_ini'] = (isset($this->Nm_conf_reg[$this->str_conf_reg]['prim_dia_sema']))            ?  $this->Nm_conf_reg[$this->str_conf_reg]['prim_dia_sema'] : "SU";
       $_SESSION['scriptcase']['reg_conf']['time_format']   = (isset($this->Nm_conf_reg[$this->str_conf_reg]['hora_format']))              ?  $this->Nm_conf_reg[$this->str_conf_reg]['hora_format'] : "hhiiss";
       $_SESSION['scriptcase']['reg_conf']['time_sep']      = (isset($this->Nm_conf_reg[$this->str_conf_reg]['hora_sep']))                 ?  $this->Nm_conf_reg[$this->str_conf_reg]['hora_sep'] : ":";
       $_SESSION['scriptcase']['reg_conf']['time_pos_ampm'] = (isset($this->Nm_conf_reg[$this->str_conf_reg]['hora_pos_ampm']))            ?  $this->Nm_conf_reg[$this->str_conf_reg]['hora_pos_ampm'] : "right_without_space";
       $_SESSION['scriptcase']['reg_conf']['time_simb_am']  = (isset($this->Nm_conf_reg[$this->str_conf_reg]['hora_simbolo_am']))          ?  $this->Nm_conf_reg[$this->str_conf_reg]['hora_simbolo_am'] : "am";
       $_SESSION['scriptcase']['reg_conf']['time_simb_pm']  = (isset($this->Nm_conf_reg[$this->str_conf_reg]['hora_simbolo_pm']))          ?  $this->Nm_conf_reg[$this->str_conf_reg]['hora_simbolo_pm'] : "pm";
       $_SESSION['scriptcase']['reg_conf']['simb_neg']      = (isset($this->Nm_conf_reg[$this->str_conf_reg]['num_sinal_neg']))            ?  $this->Nm_conf_reg[$this->str_conf_reg]['num_sinal_neg'] : "-";
       $_SESSION['scriptcase']['reg_conf']['grup_num']      = (isset($this->Nm_conf_reg[$this->str_conf_reg]['num_sep_agr']))              ?  $this->Nm_conf_reg[$this->str_conf_reg]['num_sep_agr'] : ".";
       $_SESSION['scriptcase']['reg_conf']['dec_num']       = (isset($this->Nm_conf_reg[$this->str_conf_reg]['num_sep_dec']))              ?  $this->Nm_conf_reg[$this->str_conf_reg]['num_sep_dec'] : ",";
       $_SESSION['scriptcase']['reg_conf']['neg_num']       = (isset($this->Nm_conf_reg[$this->str_conf_reg]['num_format_num_neg']))       ?  $this->Nm_conf_reg[$this->str_conf_reg]['num_format_num_neg'] : 2;
       $_SESSION['scriptcase']['reg_conf']['monet_simb']    = (isset($this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_simbolo']))        ?  $this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_simbolo'] : "$";
       $_SESSION['scriptcase']['reg_conf']['monet_f_pos']   = (isset($this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_format_num_pos'])) ?  $this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_format_num_pos'] : 3;
       $_SESSION['scriptcase']['reg_conf']['monet_f_neg']   = (isset($this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_format_num_neg'])) ?  $this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_format_num_neg'] : 13;
       $_SESSION['scriptcase']['reg_conf']['grup_val']      = (isset($this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_sep_agr']))        ?  $this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_sep_agr'] : ".";
       $_SESSION['scriptcase']['reg_conf']['dec_val']       = (isset($this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_sep_dec']))        ?  $this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_sep_dec'] : ",";
       $_SESSION['scriptcase']['reg_conf']['html_dir']      = (isset($this->Nm_conf_reg[$this->str_conf_reg]['ger_ltr_rtl']))              ?  " DIR='" . $this->Nm_conf_reg[$this->str_conf_reg]['ger_ltr_rtl'] . "'" : "";
       $_SESSION['scriptcase']['reg_conf']['num_group_digit']       = (isset($this->Nm_conf_reg[$this->str_conf_reg]['num_group_digit']))       ?  $this->Nm_conf_reg[$this->str_conf_reg]['num_group_digit'] : "1";
       $_SESSION['scriptcase']['reg_conf']['unid_mont_group_digit'] = (isset($this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_group_digit'])) ?  $this->Nm_conf_reg[$this->str_conf_reg]['unid_mont_group_digit'] : "1";
   }
// 
   function sc_Include($path, $tp, $name)
   {
       if (($tp == "F" && !function_exists($name)) || ($tp == "C" && !class_exists($name)))
       {
           include_once($path);
       }
   } // sc_Include
   function sc_Sql_Protect($var, $tp, $conex="")
   {
       if (empty($conex) || $conex == "con")
       {
           $TP_banco = $_SESSION['scriptcase']['glo_tpbanco'];
       }
       else
       {
           eval ("\$TP_banco = \$this->nm_con_" . $conex . "['tpbanco'];");
       }
       if ($tp == "date")
       {
           return "'" . $var . "'";
       }
       else
       {
           return $var;
       }
   } // sc_Sql_Protect
}
//===============================================================================
//
class rep_est_dte_sub_css
{
   function rep_est_dte_sub_css()
   {
      global $script_case_init;
      $str_schema_all = (isset($_SESSION['scriptcase']['str_schema_all']) && !empty($_SESSION['scriptcase']['str_schema_all'])) ? $_SESSION['scriptcase']['str_schema_all'] : "ScriptCase6_Blue/ScriptCase6_Blue";
      if ($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['SC_herda_css'] == "N")
      {
          $_SESSION['sc_session'][$script_case_init]['SC_sub_css']['rep_est_dte']    = $str_schema_all . "_grid.css";
          $_SESSION['sc_session'][$script_case_init]['SC_sub_css_bw']['rep_est_dte'] = $str_schema_all . "_grid_bw.css";
      }
   }
}
//
class rep_est_dte_apl
{
   var $Ini;
   var $Erro;
   var $Db;
   var $Lookup;
   var $nm_location;
   var $grid;
   var $det;
   var $Res;
   var $Graf;
//
//----- 
   function prep_modulos($modulo)
   {
      $this->$modulo->Ini = $this->Ini;
      $this->$modulo->Db = $this->Db;
      $this->$modulo->Erro = $this->Erro;
      $this->$modulo->Lookup = $this->Lookup;
   }
//
//----- 
   function controle($linhas = 0)
   {
      global $nm_saida, $nm_url_saida, $script_case_init, $nmgp_parms_pdf, $nmgp_graf_pdf, $nm_apl_dependente, $nmgp_navegator_print, $nmgp_tipo_print, $nmgp_cor_print, $nmgp_cor_word, $NMSC_conf_apl, $NM_contr_var_session, $NM_run_iframe,
      $glo_senha_protect, $nmgp_opcao, $nm_call_php;

      $_SESSION['scriptcase']['sc_ctl_ajax'] = 'part';
      if (!$this->Ini) 
      { 
          $this->Ini = new rep_est_dte_ini(); 
          $this->Ini->init();
      } 
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "rep_est_dte.php" ; 
      $_SESSION['sc_session']['path_third'] = $this->Ini->path_prod . "/third";
      $_SESSION['sc_session']['path_img']   = $this->Ini->path_img_global;
      if (is_dir($this->Ini->path_aplicacao . 'img'))
      {
          $Res_dir_img = @opendir($this->Ini->path_aplicacao . 'img');
          if ($Res_dir_img)
          {
              while (FALSE !== ($Str_arquivo = @readdir($Res_dir_img))) 
              {
                 if (@is_file($this->Ini->path_aplicacao . 'img/' . $Str_arquivo) && '.' != $Str_arquivo && '..' != $this->Ini->path_aplicacao . 'img/' . $Str_arquivo)
                 {
                     @unlink($this->Ini->path_aplicacao . 'img/' . $Str_arquivo);
                 }
              }
          }
          @closedir($Res_dir_img);
          rmdir($this->Ini->path_aplicacao . 'img');
      }
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['rep_est_dte']['exit']) && $_SESSION['scriptcase']['sc_apl_conf']['rep_est_dte']['exit'] != '')
      {
          $_SESSION['scriptcase']['sc_url_saida'][$this->Ini->sc_page] = $_SESSION['scriptcase']['sc_apl_conf']['rep_est_dte']['exit'];
      }

      $this->Ini->sc_Include($this->Ini->path_libs . "/nm_gc.php", "F", "nm_gc") ; 
      nm_gc($this->Ini->path_libs);
      $this->nm_data = new nm_data("es");
      if (!$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida'])
      { 
          $_SESSION['scriptcase']['sc_page_process'] = $this->Ini->sc_page;
      } 
      $_SESSION['scriptcase']['sc_idioma_pivot']['es'] = array(
          'smry_ppup_titl'      => $this->Ini->Nm_lang['lang_othr_smry_ppup_titl'],
          'smry_ppup_fild'      => $this->Ini->Nm_lang['lang_othr_smry_ppup_fild'],
          'smry_ppup_posi'      => $this->Ini->Nm_lang['lang_othr_smry_ppup_posi'],
          'smry_ppup_sort'      => $this->Ini->Nm_lang['lang_othr_smry_ppup_sort'],
          'smry_ppup_posi_labl' => $this->Ini->Nm_lang['lang_othr_smry_ppup_posi_labl'],
          'smry_ppup_posi_data' => $this->Ini->Nm_lang['lang_othr_smry_ppup_posi_data'],
          'smry_ppup_sort_labl' => $this->Ini->Nm_lang['lang_othr_smry_ppup_sort_labl'],
          'smry_ppup_sort_vlue' => $this->Ini->Nm_lang['lang_othr_smry_ppup_sort_vlue'],
          'smry_ppup_chek_tabu' => $this->Ini->Nm_lang['lang_othr_smry_ppup_chek_tabu'],
      );
      $this->Ini->Gd_missing  = true;
      if (function_exists("getProdVersion"))
      {
          $_SESSION['scriptcase']['sc_prod_Version'] = str_replace(".", "", getProdVersion($this->Ini->path_libs));
      }
      if (function_exists("gd_info"))
      {
          $this->Ini->Gd_missing = false;
      }
      $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_trata_img.php", "C", "nm_trata_img") ; 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao']) || empty($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao']) || !isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig']))  
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = "inicio" ;  
      }   
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['rep_est_dte']['start']) && $_SESSION['scriptcase']['sc_apl_conf']['rep_est_dte']['start'] == 'filter')
      {
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] == "inicio" || $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] == "grid")  
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = "busca";
          }   
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['embutida_form']) && $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['embutida_form'] && $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] == "busca")
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = "inicio";
      }
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig']) || !empty($nmgp_parms) || !empty($GLOBALS["nmgp_parms"]))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opc_liga'] = array();  
          if (isset($NMSC_conf_apl) && !empty($NMSC_conf_apl))
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opc_liga'] = $NMSC_conf_apl;  
          }   
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] == "busca")
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = "grid" ;  
      }   
      $this->Db = $this->Ini->Db; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['nm_tpbanco'] = $this->Ini->nm_tpbanco;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] == "fast_search")  
      { 
          $this->SC_fast_search($GLOBALS["nmgp_fast_search"], $GLOBALS["nmgp_cond_fast_search"], $GLOBALS["nmgp_arg_fast_search"]);
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq_ant'] == $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq'])
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = 'igual';
          } 
          else 
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq_ant'] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq'];
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['contr_array_resumo'] = "NAO";
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['contr_total_geral']  = "NAO";
              unset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['tot_geral']);
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = 'pesq';
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['orig_pesq'] = 'grid';
          } 
      } 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] == 'pesq' && isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['orig_pesq']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['orig_pesq']))  
      { 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['orig_pesq'] == "res")  
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = 'resumo';
          } 
          elseif ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['orig_pesq'] == "grid") 
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] = 'inicio';
          } 
      } 
      include_once($this->Ini->path_aplicacao . "rep_est_dte_erro.class.php"); 
      $this->Erro      = new rep_est_dte_erro();
      $this->Erro->Ini = $this->Ini;
      require_once($this->Ini->path_aplicacao . "rep_est_dte_lookup.class.php"); 
      $this->Lookup       = new rep_est_dte_lookup();
      $this->Lookup->Db   = $this->Db;
      $this->Lookup->Ini  = $this->Ini;
      $this->Lookup->Erro = $this->Erro;
//
      $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['prim_cons'] = false;  
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig']) || !empty($nmgp_parms) || !empty($GLOBALS["nmgp_parms"]))  
      { 
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['prim_cons'] = true;  
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig'] = " where ((codi_empr in (2,3,4)  AND  '" . $_SESSION['v_codi_empr'] . "'  IN (2,3,4)) OR (codi_empr = '" . $_SESSION['v_codi_empr'] . "'  AND '" . $_SESSION['v_codi_empr'] . "'  NOT IN (2,3,4) )) AND fech_carg = (now()  -  INTERVAL '1 days')::date";  
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq']       = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig'];  
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq_ant']   = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig'];  
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['cond_pesq'] = ""; 
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq_filtro'] = "";
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['contr_total_geral'] = "NAO";
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['total']);
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['tot_geral']);
         $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['contr_array_resumo'] = "NAO";
      } 
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq_filtro'];
//----------------------------------->
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['opcao'] == "busca")  
      { 
          require_once($this->Ini->path_aplicacao . "rep_est_dte_pesq.class.php"); 
          $this->pesq  = new rep_est_dte_pesq();
          $this->prep_modulos("pesq");
          $this->pesq->monta_busca();
      }
      else 
      { 
           require_once($this->Ini->path_aplicacao . "rep_est_dte_grid.class.php"); 
           $this->grid  = new rep_est_dte_grid();
           $this->prep_modulos("grid");
           $this->grid->monta_grid($linhas);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
 <META http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT"/>
 <META http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT"/>
 <META http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate"/>
 <META http-equiv="Cache-Control" content="post-check=0, pre-check=0"/>
 <META http-equiv="Pragma" content="no-cache"/>
</HEAD>
<BODY>
<?php
if (!is_file($this->Ini->root . $this->Ini->nm_path_pdf))
{
?>
  <table><tr><td><font color="FF0000"><b><?php echo $this->Ini->Nm_lang['lang_pdff_errg']; ?></b></font></td></tr></table>
<?php
}
else
{
    if ($this->Db->debug)
    {
?>
       <SCRIPT>
         window.open('<?php echo $this->Ini->nm_path_pdf; ?>', '_blank');
       </SCRIPT>
<?php
    }
    else
    {
?>
      <SCRIPT>
        window.location='<?php echo $this->Ini->nm_path_pdf; ?>';
      </SCRIPT>
<?php
    }
}
?>
</BODY>
</HTML>
<?php
      }   
//--- 
      $this->Db->Close(); 
   } 
   function SC_fast_search($field, $arg_search, $data_search)
   {
      if (empty($data_search)) 
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq_filtro'] = "";
          $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq'] = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['fast_search']);
          return;
      }
      $comando = "";
      $sv_data = $data_search;
      if ($field == "SC_all_Cmp") 
      {
          $this->SC_monta_condicao($comando, "count(*)", $arg_search, $data_search);
      }
      if ($field == "SC_all_Cmp") 
      {
          $data_lookup = $this->SC_lookup_codi_empr($arg_search, $data_search);
          if (is_array($data_lookup) && !empty($data_lookup)) 
          {
              $this->SC_monta_condicao($comando, "codi_empr", $arg_search, $data_lookup);
          }
      }
      if ($field == "SC_all_Cmp") 
      {
          $data_lookup = $this->SC_lookup_tipo_docu($arg_search, $data_search);
          if (is_array($data_lookup) && !empty($data_lookup)) 
          {
              $this->SC_monta_condicao($comando, "tipo_docu", $arg_search, $data_lookup);
          }
      }
      if ($field == "SC_all_Cmp") 
      {
          $data_lookup = $this->SC_lookup_est_xdte($arg_search, $data_search);
          if (is_array($data_lookup) && !empty($data_lookup)) 
          {
              $this->SC_monta_condicao($comando, "est_xdte", $arg_search, $data_lookup);
          }
      }
      if (empty($comando)) 
      {
          $comando = " 1 <> 1 "; 
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq_filtro'] = "( " . $comando . " )";
      if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig'])) 
      {
          $comando = $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_orig'] . " and ( " . $comando . " )"; 
      }
      else
      {
          $comando = " where " . $comando ; 
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['where_pesq'] = $comando;
      $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['fast_search'][0] = $field;
      $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['fast_search'][1] = $arg_search;
      $_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['fast_search'][2] = $sv_data;
   }
   function SC_monta_condicao(&$comando, $nome, $condicao, $campo, $tp_campo="")
   {
      $nm_aspas   = "'";
      $nm_numeric = array();
      $campo_join = strtolower(str_replace(".", "_", $nome));
      $nm_ini_lower = "";
      $nm_fim_lower = "";
      $nm_numeric[] = "count(*)";$nm_numeric[] = "codi_empr";$nm_numeric[] = "tipo_docu";$nm_numeric[] = "est_xdte";
      if (in_array($campo_join, $nm_numeric))
      {
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['rep_est_dte']['decimal_db'] == ".")
         {
             $nm_aspas = "";
         }
         if (is_array($campo))
         {
             foreach ($campo as $Ind => $Cmp)
             {
                if (!is_numeric($Cmp))
                {
                    return;
                }
                if ($Cmp == "")
                {
                    $campo[$Ind] = 0;
                }
             }
         }
         else
         {
             if (!is_numeric($campo))
             {
                 return;
             }
             if ($campo == "")
             {
                $campo = 0;
             }
         }
      }
      $Nm_datas[] = "fech_carg";
         $comando .= (!empty($comando) ? " or " : "");
         if (is_array($campo))
         {
             $prep = "";
             foreach ($campo as $Ind => $Cmp)
             {
                 $prep .= (!empty($prep)) ? "," : "";
                 $Cmp   = substr($this->Db->qstr($Cmp), 1, -1);
                 $prep .= $nm_aspas . $Cmp . $nm_aspas;
             }
             $prep .= (empty($prep)) ? $nm_aspas . $nm_aspas : "";
             $comando .= $nm_ini_lower . $nome . $nm_fim_lower . " in (" . $prep . ")";
             return;
         }
         $campo  = substr($this->Db->qstr($campo), 1, -1);
         switch (strtoupper($condicao))
         {
            case "EQ":     // 
               $comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " = " . $nm_aspas . $campo . $nm_aspas;
            break;
            case "II":     // 
               $comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " like '" . $campo . "%'";
            break;
            case "QP":     // 
               if (substr($tp_campo, 0, 4) == "DATE")
               {
                   $NM_cmd     = "";
                   if ($this->NM_data_qp['ano'] != "____")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       $NM_cmd     .= "year($nome) = " . $this->NM_data_qp['ano'];
                   }
                   if ($this->NM_data_qp['mes'] != "__")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       $NM_cmd     .= "month($nome) = " . $this->NM_data_qp['mes'];
                   }
                   if ($this->NM_data_qp['dia'] != "__")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       $NM_cmd     .= "day($nome) = " . $this->NM_data_qp['dia'];
                   }
                   if (!empty($NM_cmd))
                   {
                       $NM_cmd     = " (" . $NM_cmd . ")";
                       $comando        .= $NM_cmd;
                   }
               }
               else
               {
                   $comando        .= $nm_ini_lower . $nome . $nm_fim_lower ." like '%" . $campo . "%'";
               }
            break;
            case "DF":     // 
               if ($tp_campo == "DTDF" || $tp_campo == "DATEDF")
               {
                   $comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " not like '%" . $campo . "%'";
               }
               else
               {
                   $comando        .= $nm_ini_lower . $nome . $nm_fim_lower . " <> " . $nm_aspas . $campo . $nm_aspas;
               }
            break;
            case "GT":     // 
               $comando        .= " $nome > " . $nm_aspas . $campo . $nm_aspas;
            break;
            case "GE":     // 
               $comando        .= " $nome >= " . $nm_aspas . $campo . $nm_aspas;
            break;
            case "LT":     // 
               $comando        .= " $nome < " . $nm_aspas . $campo . $nm_aspas;
            break;
            case "LE":     // 
               $comando        .= " $nome <= " . $nm_aspas . $campo . $nm_aspas;
            break;
         }
   }
   function SC_lookup_codi_empr($condicao, $campo)
   {
       $result = array();
       $nm_comando = "SELECT rs_empr, codi_empr FROM \"public\".empresa WHERE (rs_empr LIKE '%$campo%')" ; 
       if ($condicao == "ii")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "LIKE '$campo%'", $nm_comando);
       }
       if ($condicao == "df")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "NOT LIKE '%$campo%'", $nm_comando);
       }
       if ($condicao == "gt")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "> '$campo'", $nm_comando);
       }
       if ($condicao == "ge")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", ">= '$campo'", $nm_comando);
       }
       if ($condicao == "lt")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "< '$campo'", $nm_comando);
       }
       if ($condicao == "le")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "<= '$campo'", $nm_comando);
       }
       $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando; 
       $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
       if ($rx = $this->Db->Execute($nm_comando)) 
       { 
           while (!$rx->EOF) 
           { 
               $chave = $rx->fields[1];
               $label = $rx->fields[0];
               if ($condicao == "eq" && $campo == $label)
               {
                   $result[] = $chave;
               }
               if ($condicao == "ii" && $campo == substr($label, 0, strlen($campo)))
               {
                   $result[] = $chave;
               }
               if ($condicao == "qp" && strstr($label, $campo))
               {
                   $result[] = $chave;
               }
               if ($condicao == "df" && $campo != $label)
               {
                   $result[] = $chave;
               }
               if ($condicao == "gt" && $label > $campo )
               {
                   $result[] = $chave;
               }
               if ($condicao == "ge" && $label >= $campo)
               {
                   $result[] = $chave;
               }
               if ($condicao == "lt" && $label < $campo)
               {
                   $result[] = $chave;
               }
               if ($condicao == "le" && $label <= $campo)
               {
                   $result[] = $chave;
               }
               $rx->MoveNext() ;
           }  
           return $result;
       }  
       elseif ($GLOBALS["NM_ERRO_IBASE"] != 1)  
       { 
           $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
           exit; 
       } 
   }
   function SC_lookup_tipo_docu($condicao, $campo)
   {
       $result = array();
       $nm_comando = "SELECT desc_tipo_docu, tipo_docu FROM \"public\".dte_tipo WHERE (desc_tipo_docu LIKE '%$campo%')" ; 
       if ($condicao == "ii")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "LIKE '$campo%'", $nm_comando);
       }
       if ($condicao == "df")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "NOT LIKE '%$campo%'", $nm_comando);
       }
       if ($condicao == "gt")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "> '$campo'", $nm_comando);
       }
       if ($condicao == "ge")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", ">= '$campo'", $nm_comando);
       }
       if ($condicao == "lt")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "< '$campo'", $nm_comando);
       }
       if ($condicao == "le")
       {
           $nm_comando = str_replace("LIKE '%$campo%'", "<= '$campo'", $nm_comando);
       }
       $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando; 
       $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
       if ($rx = $this->Db->Execute($nm_comando)) 
       { 
           while (!$rx->EOF) 
           { 
               $chave = $rx->fields[1];
               $label = $rx->fields[0];
               if ($condicao == "eq" && $campo == $label)
               {
                   $result[] = $chave;
               }
               if ($condicao == "ii" && $campo == substr($label, 0, strlen($campo)))
               {
                   $result[] = $chave;
               }
               if ($condicao == "qp" && strstr($label, $campo))
               {
                   $result[] = $chave;
               }
               if ($condicao == "df" && $campo != $label)
               {
                   $result[] = $chave;
               }
               if ($condicao == "gt" && $label > $campo )
               {
                   $result[] = $chave;
               }
               if ($condicao == "ge" && $label >= $campo)
               {
                   $result[] = $chave;
               }
               if ($condicao == "lt" && $label < $campo)
               {
                   $result[] = $chave;
               }
               if ($condicao == "le" && $label <= $campo)
               {
                   $result[] = $chave;
               }
               $rx->MoveNext() ;
           }  
           return $result;
       }  
       elseif ($GLOBALS["NM_ERRO_IBASE"] != 1)  
       { 
           $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
           exit; 
       } 
   }
   function SC_lookup_est_xdte($condicao, $campo)
   {
       $data_look = array();
       $data_look['1'] = "Cargado";
       $data_look['3'] = "Error";
       $data_look['5'] = "Empaquetado ";
       $data_look['13'] = "Enviado a SII";
       $data_look['29'] = "Aceptado SII";
       $data_look['45'] = "Aceptado con Reparos";
       $data_look['77'] = "Rechazado SII";
       $data_look['157'] = "Enviado a Clientes ";
       $data_look['173'] = "Enviado a Cliente DTE con Reparos";
       $data_look['413'] = "Aceptado Cliente";
       $data_look['429'] = "Aceptado Cliente DTE con Reparos";
       $result = array();
       foreach ($data_look as $chave => $label) 
       {
           if ($condicao == "eq" && $campo == $label)
           {
               $result[] = $chave;
           }
           if ($condicao == "ii" && $campo == substr($label, 0, strlen($campo)))
           {
               $result[] = $chave;
           }
           if ($condicao == "qp" && strstr($label, $campo))
           {
               $result[] = $chave;
           }
           if ($condicao == "df" && $campo != $label)
           {
               $result[] = $chave;
           }
           if ($condicao == "gt" && $label > $campo )
           {
               $result[] = $chave;
           }
           if ($condicao == "ge" && $label >= $campo)
            {
               $result[] = $chave;
           }
           if ($condicao == "lt" && $label < $campo)
           {
               $result[] = $chave;
           }
           if ($condicao == "le" && $label <= $campo)
           {
               $result[] = $chave;
           }
          
       }
       return $result;
   }
  function html_doc_word($nm_arquivo_doc_word)
  {
      global $nm_url_saida;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE><?php echo $this->Ini->Nm_lang['lang_othr_grid_titl'] ?> - v_dte :: Doc</TITLE>
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
   <td class="scExportTitle" style="height: 25px">WORD</td>
  </tr>
  <tr>
   <td class="scExportLine" style="width: 100%">
    <table style="border-collapse: collapse; border-width: 0; width: 100%"><tr><td class="scExportLineFont" style="padding: 3px 0 0 0" id="idMessage">
    <?php echo $this->Ini->Nm_lang['lang_othr_file_msge'] ?>
    </td><td class="scExportLineFont" style="text-align:right; padding: 3px 0 0 0">
     <?php echo nmButtonOutput($this->arr_buttons, "bexportview", "document.Fview.submit()", "document.Fview.submit()", "idBtnView", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bdownload", "document.Fdown.submit()", "document.Fdown.submit()", "idBtnDown", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bvoltar", "document.F0.submit()", "document.F0.submit()", "idBtnBack", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right");
 ?>
    </td></tr></table>
   </td>
  </tr>
 </table>
</td></tr></table>
<form name="Fview" method="get" action="<?php echo $this->Ini->path_imag_temp . $nm_arquivo_doc_word ?>" target="_blank" style="display: none"> 
</form>
<form name="Fdown" method="get" action="rep_est_dte_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="nm_tit_doc" value="rep_est_dte.doc"> 
<input type="hidden" name="nm_name_doc" value="<?php echo NM_encode_input($this->Ini->path_imag_temp . $nm_arquivo_doc_word) ?>"> 
</form>
<FORM name="F0" method=post action="rep_est_dte.php"> 
<INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<INPUT type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id()); ?>"> 
<INPUT type="hidden" name="nmgp_opcao" value="<?php echo NM_encode_input($this->ret_word) ?>"> 
</FORM> 
</BODY>
</HTML>
<?php
  }
} 
// 
//======= =========================
   if (!function_exists("NM_is_utf8"))
   {
       include_once("rep_est_dte_nmutf8.php");
   }
   $_SESSION['scriptcase']['rep_est_dte']['contr_erro'] = 'off';
   $sc_conv_var = array();
   if (!empty($_POST))
   {
       foreach ($_POST as $nmgp_var => $nmgp_val)
       {
            if (isset($sc_conv_var[$nmgp_var]))
            {
                $nmgp_var = $sc_conv_var[$nmgp_var];
            }
            nm_limpa_str_rep_est_dte($nmgp_val);
            $$nmgp_var = $nmgp_val;
       }
   }
   if (!empty($_GET))
   {
       foreach ($_GET as $nmgp_var => $nmgp_val)
       {
            if (isset($sc_conv_var[$nmgp_var]))
            {
                $nmgp_var = $sc_conv_var[$nmgp_var];
            }
            nm_limpa_str_rep_est_dte($nmgp_val);
            $$nmgp_var = $nmgp_val;
       }
   }
   if (!empty($glo_perfil))  
   { 
      $_SESSION['scriptcase']['glo_perfil'] = $glo_perfil;
   }   
   if (isset($glo_servidor)) 
   {
       $_SESSION['scriptcase']['glo_servidor'] = $glo_servidor;
   }
   if (isset($glo_banco)) 
   {
       $_SESSION['scriptcase']['glo_banco'] = $glo_banco;
   }
   if (isset($glo_tpbanco)) 
   {
       $_SESSION['scriptcase']['glo_tpbanco'] = $glo_tpbanco;
   }
   if (isset($glo_usuario)) 
   {
       $_SESSION['scriptcase']['glo_usuario'] = $glo_usuario;
   }
   if (isset($glo_senha)) 
   {
       $_SESSION['scriptcase']['glo_senha'] = $glo_senha;
   }
   if (isset($glo_senha_protect)) 
   {
       $_SESSION['scriptcase']['glo_senha_protect'] = $glo_senha_protect;
   }
   if (isset($script_case_init) && isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida_form_parms'])) 
   {
       if (!empty($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida_form_parms'])) 
       {
           $nmgp_parms = $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida_form_parms'];
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida_form_parms'] = "";
       }
   }
   elseif (isset($script_case_init))
   {
       unset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida_form']);
       unset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida_form_parms']);
   }
   if (!isset($nmgp_opcao) || !isset($script_case_init) || ((!isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida']) || !$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida']) && $nmgp_opcao != "formphp"))
   { 
       if (!empty($nmgp_parms)) 
       { 
           $nmgp_parms = NM_decode_input($nmgp_parms);
           $nmgp_parms = str_replace("@aspass@", "'", $nmgp_parms);
           $nmgp_parms = str_replace("*scout", "?@?", $nmgp_parms);
           $nmgp_parms = str_replace("*scin", "?#?", $nmgp_parms);
           $todo = explode("?@?", $nmgp_parms);
           foreach ($todo as $param)
           {
                $cadapar = explode("?#?", $param);
                if (1 < sizeof($cadapar))
                {
                    if (isset($sc_conv_var[$cadapar[0]]))
                    {
                        $cadapar[0] = $sc_conv_var[$cadapar[0]];
                    }
                    nm_limpa_str_rep_est_dte($cadapar[1]);
                    $$cadapar[0] = $cadapar[1];
                }
           }
           $NMSC_conf_apl = array();
           if (isset($NMSC_inicial))
           {
               $NMSC_conf_apl['inicial'] = $NMSC_inicial;
           }
           if (isset($NMSC_rows))
           {
               $NMSC_conf_apl['rows'] = $NMSC_rows;
           }
           if (isset($NMSC_cols))
           {
               $NMSC_conf_apl['cols'] = $NMSC_cols;
           }
           if (isset($NMSC_paginacao))
           {
               $NMSC_conf_apl['paginacao'] = $NMSC_paginacao;
           }
           if (isset($NMSC_cab))
           {
               $NMSC_conf_apl['cab'] = $NMSC_cab;
           }
           if (isset($NMSC_nav))
           {
               $NMSC_conf_apl['nav'] = $NMSC_nav;
           }
           if (isset($NM_run_iframe) && $NM_run_iframe == 1) 
           { 
               unset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']);
               $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['b_sair'] = false;
           }   
       } 
   } 
   $ini_embutida = "";
   if (isset($script_case_init) && isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida']) && $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida'])
   {
       $nmgp_outra_jan = "";
   }
   if (isset($nmgp_outra_jan) && $nmgp_outra_jan == 'true')
   {
       $script_case_init = "";
   }
   if (isset($GLOBALS["script_case_init"]) && !empty($GLOBALS["script_case_init"]))
   {
       $ini_embutida = $GLOBALS["script_case_init"];
        if (!isset($_SESSION['sc_session'][$ini_embutida]['rep_est_dte']['embutida']))
        { 
           $_SESSION['sc_session'][$ini_embutida]['rep_est_dte']['embutida'] = false;
        }
        if (!$_SESSION['sc_session'][$ini_embutida]['rep_est_dte']['embutida'])
        { 
           $script_case_init = $ini_embutida;
        }
   }
   if (isset($_SESSION['scriptcase']['rep_est_dte']['protect_modal']) && !empty($_SESSION['scriptcase']['rep_est_dte']['protect_modal']))
   {
       $script_case_init = $_SESSION['scriptcase']['rep_est_dte']['protect_modal'];
   }
   if (!isset($script_case_init) || empty($script_case_init))
   {
       $script_case_init = rand(2, 1000);
   }
   $salva_emb    = false;
   $salva_iframe = false;
   $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['doc_word'] = false;
   $_SESSION['scriptcase']['saida_word'] = false;
   if (isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu']))
   {
       $salva_iframe = $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu'];
       unset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu']);
   }
   if (isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida']))
   {
       $salva_emb = $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida'];
       unset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida']);
   }
   if (isset($nm_run_menu) && $nm_run_menu == 1 && !$salva_emb)
   {
        if (isset($_SESSION['scriptcase']['sc_aba_iframe']) && isset($_SESSION['scriptcase']['sc_apl_menu_atual']) && $script_case_init == 1)
        {
            foreach ($_SESSION['scriptcase']['sc_aba_iframe'] as $aba => $apls_aba)
            {
                if ($aba == $_SESSION['scriptcase']['sc_apl_menu_atual'])
                {
                    unset($_SESSION['scriptcase']['sc_aba_iframe'][$aba]);
                    break;
                }
            }
        }
        if ($script_case_init == 1)
        {
            $_SESSION['scriptcase']['sc_apl_menu_atual'] = "rep_est_dte";
        }
        $achou = false;
        if (isset($_SESSION['sc_session'][$script_case_init]))
        {
            foreach ($_SESSION['sc_session'][$script_case_init] as $nome_apl => $resto)
            {
                if ($nome_apl == 'rep_est_dte' || $achou)
                {
                    unset($_SESSION['sc_session'][$script_case_init][$nome_apl]);
                }
            }
            if (!$achou && isset($nm_apl_menu))
            {
                foreach ($_SESSION['sc_session'][$script_case_init] as $nome_apl => $resto)
                {
                    if ($nome_apl == $nm_apl_menu || $achou)
                    {
                        $achou = true;
                        if ($nome_apl != $nm_apl_menu)
                        {
                            unset($_SESSION['sc_session'][$script_case_init][$nome_apl]);
                        }
                    }
                }
            }
        }
        $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu'] = true;
   }
   else
   {
       $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['iframe_menu'] = $salva_iframe;
   }
   $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida'] = $salva_emb;

   if (!isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['initialize']))
   {
       $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['initialize'] = true;
   }
   elseif (!isset($_SERVER['HTTP_REFERER']))
   {
       $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['initialize'] = false;
   }
   elseif (false === strpos($_SERVER['HTTP_REFERER'], '.php'))
   {
       $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['initialize'] = true;
   }
   else
   {
       $sReferer = substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], '.php'));
       $sReferer = substr($sReferer, strrpos($sReferer, '/') + 1);
       if ('rep_est_dte' == $sReferer || 'rep_est_dte_' == substr($sReferer, 0, 12))
       {
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['initialize'] = false;
       }
       else
       {
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['initialize'] = true;
       }
   }
   if ($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['initialize'])
   {
       unset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['tot_geral']);
   }

   $_POST['script_case_init'] = $script_case_init;
   if (isset($nmgp_opcao) && $nmgp_opcao == "busca" && isset($nmgp_orig_pesq))
   {
       $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['orig_pesq'] = $nmgp_orig_pesq;
   }
   if (!isset($nmgp_opcao) || empty($nmgp_opcao) || $nmgp_opcao == "grid" && (!isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['b_sair'])))
   {
       $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['b_sair'] = true;
   }
   if (isset($_SESSION['scriptcase']['sc_outra_jan']) && $_SESSION['scriptcase']['sc_outra_jan'] == 'rep_est_dte')
   {
       $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan'] = true;
        unset($_SESSION['scriptcase']['sc_outra_jan']);
   }
   $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['menu_desenv'] = false;   
   if (!defined("SC_ERROR_HANDLER"))
   {
       define("SC_ERROR_HANDLER", 1);
       include_once(dirname(__FILE__) . "/rep_est_dte_erro.php");
   }
   $salva_tp_saida  = (isset($_SESSION['scriptcase']['sc_tp_saida']))  ? $_SESSION['scriptcase']['sc_tp_saida'] : "";
   $salva_url_saida  = (isset($_SESSION['scriptcase']['sc_url_saida'][$script_case_init])) ? $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] : "";
   if (!$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida'] && $nmgp_opcao != "formphp")
   { 
       if ($nmgp_opcao == "change_lang" || $nmgp_opcao == "change_lang_res" || $nmgp_opcao == "change_lang_fil")  
       { 
           if ($nmgp_opcao == "change_lang_fil")  
           { 
               $nmgp_opcao  = "busca";  
           } 
           elseif ($nmgp_opcao == "change_lang_res")  
           { 
               $nmgp_opcao  = "resumo";  
           } 
           else 
           { 
               $nmgp_opcao  = "igual";  
           } 
           unset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['tot_geral']);
           $Temp_lang = explode(";" , $nmgp_idioma);  
           if (isset($Temp_lang[0]) && !empty($Temp_lang[0]))  
           { 
               $_SESSION['scriptcase']['str_lang'] = $Temp_lang[0];
           } 
           if (isset($Temp_lang[1]) && !empty($Temp_lang[1])) 
           { 
               $_SESSION['scriptcase']['str_conf_reg'] = $Temp_lang[1];
           } 
       } 
       if ($nmgp_opcao == "change_schema" || $nmgp_opcao == "change_schema_fil" || $nmgp_opcao == "change_schema_res")  
       { 
           if ($nmgp_opcao == "change_schema_fil")  
           { 
               $nmgp_opcao  = "busca";  
           } 
           elseif ($nmgp_opcao == "change_schema_res")  
           { 
               $nmgp_opcao  = "resumo";  
           } 
           else 
           { 
               $nmgp_opcao  = "igual";  
           } 
           $nmgp_schema = $nmgp_schema . "/" . $nmgp_schema;  
           $_SESSION['scriptcase']['str_schema_all'] = $nmgp_schema;
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['num_css'] = rand(0, 1000);
       } 
       if ($nmgp_opcao == "volta_grid")  
       { 
           $nmgp_opcao = "igual";  
       }   
       if (!empty($nmgp_opcao))  
       { 
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opcao'] = $nmgp_opcao ;  
       }   
       if (isset($v_codi_empr) && isset($NM_contr_var_session) && $NM_contr_var_session == "Yes") 
       {
           nm_limpa_str_rep_est_dte($v_codi_empr);
           $_SESSION['v_codi_empr'] = $v_codi_empr;
       }
       if (!isset($_SESSION["v_codi_empr"])) 
       {
           $_SESSION["v_codi_empr"] = "";
       }
       if (isset($nmgp_lig_edit_lapis)) 
       {
          $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['mostra_edit'] = $nmgp_lig_edit_lapis;
           unset($GLOBALS["nmgp_lig_edit_lapis"]) ;  
           if (isset($_SESSION['nmgp_lig_edit_lapis'])) 
           {
               unset($_SESSION['nmgp_lig_edit_lapis']);
           }
       }
       if (isset($nmgp_outra_jan) && $nmgp_outra_jan == 'true')
       {
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan'] = true;
       }
       if (isset($nmgp_rotaciona) && $nmgp_rotaciona == "S") 
       {
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['res_hrz'] = ($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['res_hrz']) ? false : true;
       }
       $nm_saida = "";
       if (isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['volta_redirect_apl']) && !empty($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['volta_redirect_apl']))
       {
           $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['volta_redirect_apl']; 
           $nm_apl_dependente = $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['volta_redirect_tp']; 
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['volta_redirect_apl'] = "";
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['volta_redirect_tp'] = "";
           $nm_url_saida = "rep_est_dte_fim.php"; 
       
       }
       elseif (substr($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opcao'], 0, 7) != "grafico" && $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opcao'] != "pdf" ) 
       {
           if (isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan']) && $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan'])
           {
               if ($nmgp_url_saida == "modal")
               {
                   $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_modal'] = true;
               }
               $nm_url_saida = "rep_est_dte_fim.php"; 
           }
           else
           {
               $nm_url_saida = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ""; 
               $nm_url_saida = str_replace("_fim.php", ".php", $nm_url_saida);
               if (!empty($nmgp_url_saida)) 
               { 
                   $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['retorno_cons'] = $nmgp_url_saida ; 
               } 
               if (!empty($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['retorno_cons'])) 
               { 
                   $nm_url_saida = $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['retorno_cons']  . "?script_case_init=" . NM_encode_input($script_case_init);  
                   $nm_apl_dependente = 1 ; 
               } 
               if (!empty($nm_url_saida)) 
               { 
                   $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $nm_url_saida ; 
               } 
               $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $nm_url_saida; 
               $nm_url_saida = "rep_est_dte_fim.php"; 
               $_SESSION['scriptcase']['sc_tp_saida'] = "P"; 
               if ($nm_apl_dependente == 1) 
               { 
                   $_SESSION['scriptcase']['sc_tp_saida'] = "D"; 
               } 
           } 
       }
// 
       if (isset($_SESSION['scriptcase']['nm_sc_retorno']) && !empty($_SESSION['scriptcase']['nm_sc_retorno']) && $nm_apl_dependente != 1 && substr($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opcao'], 0, 7) != "grafico" && $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opcao'] != "pdf" ) 
       { 
            $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['menu_desenv'] = true;   
       } 
       if (isset($_GET["nmgp_parms_ret"])) 
       {
           $todo = explode(",", $nmgp_parms_ret);
           if (isset($sc_conv_var[$todo[2]]))
           {
               $todo[2] = $sc_conv_var[$todo[2]];
           }
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['form_psq_ret']  = $todo[0];
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['campo_psq_ret'] = $todo[1];
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['dado_psq_ret']  = $todo[2];
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['js_apos_busca'] = $nm_evt_ret_busca;
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opc_psq'] = true ;   
       } 
       elseif (!isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opc_psq']))
       {
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['opc_psq'] = false ;   
       } 
       if ($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida_form'])
       {
           $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['mostra_edit'] = "N";   
           $_SESSION['scriptcase']['sc_tp_saida']  = $salva_tp_saida;
           $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $salva_url_saida;
       } 
       $GLOBALS["NM_ERRO_IBASE"] = 0;  
       if (isset($_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan']) && $_SESSION['sc_session'][$script_case_init]['rep_est_dte']['sc_outra_jan'])
       {
           $nm_apl_dependente = 0;
       }
       $contr_rep_est_dte = new rep_est_dte_apl();
       $contr_rep_est_dte->controle();
   } 
   if (!$_SESSION['sc_session'][$script_case_init]['rep_est_dte']['embutida'] && $nmgp_opcao == "formphp")
   { 
       $GLOBALS["NM_ERRO_IBASE"] = 0;  
       $contr_rep_est_dte = new rep_est_dte_apl();
       $contr_rep_est_dte->controle();
   } 
//
   function nm_limpa_str_rep_est_dte(&$str)
   {
       if (get_magic_quotes_gpc())
       {
           if (is_array($str))
           {
               foreach ($str as $x => $cada_str)
               {
                   $str[$x] = str_replace("@aspasd@", '"', $str[$x]);
                   $str[$x] = stripslashes($str[$x]);
               }
           }
           else
           {
               $str = str_replace("@aspasd@", '"', $str);
               $str = stripslashes($str);
           }
       }
   }
?>
