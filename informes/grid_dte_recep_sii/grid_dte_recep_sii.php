<?php
   include_once('grid_dte_recep_sii_session.php');
   @session_start() ;
   $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_perfil']          = "con";
   $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_prod']       = "";
   $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_conf']       = "";
   $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imagens']    = "";
   $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imag_temp']  = "";
   $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_doc']        = "";
//
class grid_dte_recep_sii_ini
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
   var $cor_link_dados;
   var $root;
   var $server;
   var $java_protocol;
   var $server_pdf;
   var $Arr_result;
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
   var $str_schema_filter;
   var $Str_btn_filter;
   var $path_cep;
   var $path_secure;
   var $path_js;
   var $path_help;
   var $path_adodb;
   var $path_grafico;
   var $path_atual;
   var $Gd_missing;
   var $sc_site_ssl;
   var $nm_cont_lin;
   var $nm_limite_lin;
   var $nm_limite_lin_prt;
   var $nm_limite_lin_res;
   var $nm_limite_lin_res_prt;
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
   var $sc_page;
//
   function init($Tp_init = "")
   {
       global
             $nm_url_saida, $nm_apl_dependente, $script_case_init, $nmgp_opcao;

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
      $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['decimal_db'] = "."; 

      $this->nm_cod_apl      = "grid_dte_recep_sii"; 
      $this->nm_nome_apl     = ""; 
      $this->nm_seguranca    = ""; 
      $this->nm_grupo        = "WebDTE"; 
      $this->nm_grupo_versao = "1"; 
      $this->nm_autor        = "admin"; 
      $this->nm_versao_sc    = "v7"; 
      $this->nm_tp_lic_sc    = "pe_pgsql_bronze"; 
      $this->nm_dt_criacao   = "20140114"; 
      $this->nm_hr_criacao   = "162800"; 
      $this->nm_autor_alt    = "admin"; 
      $this->nm_dt_ult_alt   = "20140901"; 
      $this->nm_hr_ult_alt   = "020529"; 
      $temp_bug_list         = explode(" ", microtime()); 
      list($NM_usec, $NM_sec) = $temp_bug_list; 
      $this->nm_timestamp    = (float) $NM_sec; 
      $this->nm_app_version  = "1.0.0";
// 
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
      if(empty($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_prod']))
      {
              /*check prod*/$_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_prod'] = $str_path_apl_url . "_lib/prod";
      }
      //check img
      if(empty($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imagens']))
      {
              /*check img*/$_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imagens'] = $str_path_apl_url . "_lib/file/img";
      }
      //check tmp
      if(empty($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imag_temp']))
      {
              /*check tmp*/$_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imag_temp'] = $str_path_apl_url . "_lib/tmp";
      }
      //check doc
      if(empty($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_doc']))
      {
              /*check doc*/$_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_doc'] = $str_path_apl_dir . "_lib/file/doc";
      }
      //end check publication with the prod
      $this->sc_site_ssl     = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? true : false;
      $this->sc_protocolo    = ($this->sc_site_ssl) ? 'https://' : 'http://';
      $this->sc_protocolo    = "";
      $this->path_prod       = $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_prod'];
      $this->path_conf       = $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_conf'];
      $this->path_imagens    = $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imagens'];
      $this->path_imag_temp  = $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_imag_temp'];
      $this->path_doc        = $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_path_doc'];
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
      $this->str_schema_filter = (isset($_SESSION['scriptcase']['str_schema_all']) && !empty($_SESSION['scriptcase']['str_schema_all'])) ? $_SESSION['scriptcase']['str_schema_all'] : "ScriptCase6_Blue/ScriptCase6_Blue";
      $_SESSION['scriptcase']['erro']['str_schema'] = $this->str_schema_all . "_error.css";
      $_SESSION['scriptcase']['erro']['str_lang']   = $this->str_lang;
      $this->server          = (!isset($_SERVER['HTTP_HOST'])) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
      if (!isset($_SERVER['HTTP_HOST']) && isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && !$this->sc_site_ssl )
      {
          $this->server         .= ":" . $_SERVER['SERVER_PORT'];
      }
      $this->java_protocol   = "http://";
      $this->server_pdf      = $this->server;
      $this->server          = "";
      $str_path_web          = $_SERVER['PHP_SELF'];
      $str_path_web          = str_replace("\\", '/', $str_path_web);
      $str_path_web          = str_replace('//', '/', $str_path_web);
      $this->root            = substr($str_path_sys, 0, -1 * strlen($str_path_web));
      $this->path_aplicacao  = substr($str_path_sys, 0, strrpos($str_path_sys, '/'));
      $this->path_aplicacao  = substr($this->path_aplicacao, 0, strrpos($this->path_aplicacao, '/')) . '/grid_dte_recep_sii';
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
      if (!class_exists('Services_JSON'))
      {
          include_once("grid_dte_recep_sii_json.php");
      }
      $this->SC_Link_View = (isset($_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['SC_Link_View'])) ? $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['SC_Link_View'] : false;
      if (isset($_GET['SC_Link_View']) && !empty($_GET['SC_Link_View']) && is_numeric($_GET['SC_Link_View']))
      {
          if ($_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['embutida'])
          {
              $this->SC_Link_View = true;
              $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['SC_Link_View'] = true;
          }
      }
      if (isset($_SESSION['scriptcase']['user_logout']))
      {
          foreach ($_SESSION['scriptcase']['user_logout'] as $ind => $parms)
          {
              if (isset($_SESSION[$parms['V']]) && $_SESSION[$parms['V']] == $parms['U'])
              {
                  unset($_SESSION['scriptcase']['user_logout'][$ind]);
                  $nm_apl_dest = $parms['R'];
                  $dir = explode("/", $nm_apl_dest);
                  if (count($dir) == 1)
                  {
                      $nm_apl_dest = str_replace(".php", "", $nm_apl_dest);
                      $nm_apl_dest = $this->path_link . SC_dir_app_name($nm_apl_dest) . "/" . $nm_apl_dest . ".php";
                  }
                  if (isset($_POST['nmgp_opcao']) && ($_POST['nmgp_opcao'] == "ajax_event" || $_POST['nmgp_opcao'] == "ajax_navigate"))
                  {
                      $this->Arr_result = array();
                      $this->Arr_result['redirInfo']['action']              = $nm_apl_dest;
                      $this->Arr_result['redirInfo']['target']              = $parms['T'];
                      $this->Arr_result['redirInfo']['metodo']              = "post";
                      $this->Arr_result['redirInfo']['script_case_init']    = $this->sc_page;
                      $this->Arr_result['redirInfo']['script_case_session'] = session_id();
                      $oJson = new Services_JSON();
                      echo $oJson->encode($this->Arr_result);
                      exit;
                  }
?>
                  <html>
                  <body>
                  <form name="FRedirect" method="POST" action="<?php echo $nm_apl_dest; ?>" target="<?php echo $parms['T']; ?>">
                  </form>
                  <script>
                   document.FRedirect.submit();
                  </script>
                  </body>
                  </html>
<?php
                  exit;
              }
          }
      }
      if ($Tp_init == "Path_sub")
      {
          return;
      }
      $str_path = substr($this->path_prod, 0, strrpos($this->path_prod, '/') + 1);
      if (!is_file($this->root . $str_path . 'devel/class/xmlparser/nmXmlparserIniSys.class.php'))
      {
          unset($_SESSION['scriptcase']['nm_sc_retorno']);
          unset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao']);
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
      foreach ($this->Nm_lang_conf_region as $ind => $dados)
      {
         if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($dados))
         {
             $this->Nm_lang_conf_region[$ind] = mb_convert_encoding($dados, $_SESSION['scriptcase']['charset'], "UTF-8");
         }
      }
      foreach ($this->Nm_conf_reg[$this->str_conf_reg] as $ind => $dados)
      {
         if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($dados))
         {
             $this->Nm_conf_reg[$this->str_conf_reg][$ind] = mb_convert_encoding($dados, $_SESSION['scriptcase']['charset'], "UTF-8");
         }
      }
      foreach ($this->Nm_lang as $ind => $dados)
      {
         if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($ind))
         {
             $ind = mb_convert_encoding($ind, $_SESSION['scriptcase']['charset'], "UTF-8");
             $this->Nm_lang[$ind] = $dados;
         }
         if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($dados))
         {
             $this->Nm_lang[$ind] = mb_convert_encoding($dados, $_SESSION['scriptcase']['charset'], "UTF-8");
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
      $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['path_doc'] = $this->path_doc; 
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
          echo ".scLink_default { text-decoration: underline; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:visited { text-decoration: underline; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:active { text-decoration: underline; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:hover { text-decoration: none; font-size: 12px; color: #0000AA;  }";
          echo "</style>";
          echo "<table width=\"80%\" border=\"1\" height=\"117\">";
          echo "<tr>";
          echo "   <td bgcolor=\"\">";
          echo "       <b><font size=\"4\">" . $this->Nm_lang['lang_errm_cmlb_nfnd'] . "</font>";
          echo "  " . $this->root . $this->path_prod;
          echo "   </b></td>";
          echo " </tr>";
          echo "</table>";
          if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu'] && (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan']) || !$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan'])) 
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

      $this->nm_cont_lin           = 0;
      $this->nm_limite_lin         = 0;
      $this->nm_limite_lin_prt     = 0;
      $this->nm_limite_lin_res     = 0;
      $this->nm_limite_lin_res_prt = 0;
// 
      include_once($this->path_aplicacao . "grid_dte_recep_sii_erro.class.php"); 
      $this->Erro = new grid_dte_recep_sii_erro();
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
      if (function_exists("nm_check_pdf_server")) $this->server_pdf = nm_check_pdf_server($this->path_libs, $this->server_pdf);
      if (!isset($_SESSION['scriptcase']['sc_num_img']))
      { 
          $_SESSION['scriptcase']['sc_num_img'] = 1;
      } 
      $this->regionalDefault();
      $this->sc_tem_trans_banco = false;
      $this->nm_bases_postgres   = array("postgres", "postgres64", "postgres7", "pdo_pgsql");
      $this->nm_bases_sqlite     = array("sqlite", "pdosqlite");
      $this->nm_bases_all        = array_merge($this->nm_bases_postgres, $this->nm_bases_sqlite);
      $this->nm_font_ttf = array("ar", "ja", "pl", "ru", "sk", "thai", "zh_cn", "zh_hk", "cz", "el", "ko", "mk");
      $this->nm_ttf_arab = array("ar");
      $this->nm_ttf_jap  = array("ja");
      $this->nm_ttf_rus  = array("pl", "ru", "sk", "cz", "el", "mk");
      $this->nm_ttf_thai = array("thai");
      $this->nm_ttf_chi  = array("zh_cn", "zh_hk", "ko");
      $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['seq_dir'] = 0; 
      $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['sub_dir'] = array(); 
      $_SESSION['scriptcase']['nm_bases_security']  = "enc_nm_enc_v1HQXGDQFUDSBYHQBODMBYVcBUH5XCVoBOHQXGZSBqZ1rYHuJwDMvCVkJqDWXCZuXGHQNmDuFaHIrKHuNUDMvmVcFKV5BmVoBqD9BsZkFGHAvsD5XGHgBeHEFiV5B3DoF7D9XsDuFaHAveHuF7HgrKDkB/H5XCHIFGD9JmH9BODSNOHuXGDEvsHEXeDuFYHIB/D9XsDQBqHArYHuJeDMvmVcFKV5BmVoBqD9BsZkFGHArKD5BqDMzGHEJqV5FaDoBODcBwZSFGD1BeV5XGHuNOVcXKV5X7VoBOD9XOZSB/Z1BeV5FUDENOVkXeDWFqHIJsD9XsZ9JeD1BeD5F7DMvmVcFiV5X7DoF7D9XOZSB/DSrYV5B/DMNKZSXeH5F/ZuFaDcBwDQFUZ1rwV5BOHgrKVcBOH5FqDoraD9XOZ1X7Z1BeD5JeDEBeHEXeHEB7VoBiD9NwDQJsHIrKV5JeDMvmVcFKV5BmVoBqD9BsZkFGHArKV5FUHgNOZSJ3H5X/ZuJeDcXGZSFUD1BeVWJwHgrKV9FeHEF/DoX7HQXOZ1FGHArKV5FUDMrYZSXeV5FqHIJsHQNmDuBqDSN7HQXGDMBOV9FeDurGVEX7HQBqZ1FUZ1rYHuFGDMveHArCDWr/HIrqHQXOZ9rqZ1BYHurqDMvmVIBsDWFYHMFaHQBsZ1FUZ1rYHuFGHgNOVkJ3DWrGZuBOHQJeDuBOD1BeD5rqHuvmVcBOH5B7VoBqHQXOZ1BiDSNOHuX7DMvCHEJqDWr/HMJsHQXsDuBOZ1zGVWJsDMvmV9BUDurGVoX7HQNmVIraZ1rYHQFGHgrKHENiH5FGDoJeHQNwZ9JeZ1BYHuF7DMNODkBsDWJeHMJsHQJmVIJwD1rwV5FGDEBeHEXeH5X/DoF7HQNwDuBqDSvCVWJeDMrYZSNiHEFYHMJsHQNwVIJwZ1vOZMJeHgNKHErCDuX/ZuFaDcXGZ9rqZ1zGVWJeDMNOV9FeH5FqHIX7DcNmVIraZ1vOZMBOHgvsHArCH5F/HIJeHQJKZ9JeD1BeD5rqHuvmVcBOH5B7VoBqD9XOH9B/D1rwD5BiDErKHEFiDWX7ZuFaD9JKDQB/Z1rwHuF7DMvsZSrCV5X7HIBiD9XOZSB/Z1BeV5BODErKVkXeDWX7VoFGDcJUDQFaHAN7D5BqHuNOVcFKDWFYVoFGHQFYH9BqZ1NOV5FaDEvsHErCV5FqDoraD9JKZSX7D1vOV5JwHuBYZSNiDuFGVoraD9BiH9FaHIBeZMBODErKVkXeV5FaDoB/D9NmDQBOZ1rwV5BqHgvsDkFCDWJeDoFGD9XOZ1rqD1rKD5rqDMBYHEJGH5FYVoB/HQXGZ9rqD1BeD5rqHuvmVcBOH5B7VoBqD9XOH9B/D1rwD5BiDEBeHEFiV5FaDoXGD9NmDQB/Z1rwD5F7HuNODkBOV5BmDoXGD9XOZ1rqD1rKV5FGDMBYHArCDWF/VoBiDcJUZSX7Z1BYHuFaDMvOZSNiDWB3VoF7HQNwZ1BiHAvsZMJeHgveHErsDWX7HIJsD9XsZ9JeD1BeD5F7DMvmVcFeDuFqHMFaHQBsZkFGHINKZMFaDMvCHEFKV5FqDoraHQBiZSFUHANOHuBiDMrYV9FeDur/HMBiD9BsVIraD1rwV5X7HgBeHENiDWF/DoraDcJeH9X7HIBeV5JwHgNKVcFeDWFaDoJeD9BiZSB/HABYD5XGDMzGHEJGHEB3DoNUHQXsZSX7Z1rwD5NUHuBOVIBODWFaDoJeDcJUZ1FaDSNOHQJwDEBODkFeH5FYVoFGHQJKDQBqHINaD5NUDMvOVcXeV5r/VEB/";
      ob_start();
      $this->prep_conect();
      if (isset($_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['initialize']) && $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['initialize'])  
      { 
          $_SESSION['scriptcase']['grid_dte_recep_sii']['contr_erro'] = 'on';
 $_SESSION['v_codi_empr'] = $_SESSION['_COD_EMP_USU_SESS'];

if ($_SESSION['v_codi_empr'] == ''){
	header('Location: /sel_emp.php');	
}

$_SESSION['scriptcase']['grid_dte_recep_sii']['contr_erro'] = 'off'; 
          $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['initialize'] = false;
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
          if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu'] && (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan']) || !$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan'])) 
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
          $this->nm_tabela = "dte_recibido_sii"; 
      }
   }
   function prep_conect()
   {
      if (isset($_SESSION['scriptcase']['sc_connection']) && !empty($_SESSION['scriptcase']['sc_connection']))
      {
          foreach ($_SESSION['scriptcase']['sc_connection'] as $NM_con_orig => $NM_con_dest)
          {
              if (isset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao']) && $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao'] == $NM_con_orig)
              {
/*NM*/            $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao'] = $NM_con_dest;
              }
              if (isset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_perfil']) && $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_perfil'] == $NM_con_orig)
              {
/*NM*/            $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_perfil'] = $NM_con_dest;
              }
              if (isset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_con_' . $NM_con_orig]))
              {
                  $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_con_' . $NM_con_orig] = $NM_con_dest;
              }
          }
      }
      $con_devel             = (isset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao'])) ? $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao'] : ""; 
      $perfil_trab           = ""; 
      $this->nm_falta_var    = ""; 
      $this->nm_falta_var_db = ""; 
      $nm_crit_perfil        = false;
      if (isset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao']) && !empty($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao']))
      {
          db_conect_devel($con_devel, $this->root . $this->path_prod, 'WebDTE', 2); 
          if (empty($_SESSION['scriptcase']['glo_tpbanco']) && empty($_SESSION['scriptcase']['glo_banco']))
          {
              $nm_crit_perfil = true;
          }
      }
      if (isset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_perfil']) && !empty($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_perfil']))
      {
          $perfil_trab = $_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_perfil'];
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
      if (!isset($_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['embutida_init']) || !$_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['embutida_init']) 
      {
          if (!isset($_SESSION['v_codi_empr'])) 
          {
              $this->nm_falta_var .= "v_codi_empr; ";
          }
      }
// 
      if (isset($_SESSION['scriptcase']['glo_decimal_db']) && !empty($_SESSION['scriptcase']['glo_decimal_db']))
      {
         $_SESSION['sc_session'][$this->sc_page]['grid_dte_recep_sii']['decimal_db'] = $_SESSION['scriptcase']['glo_decimal_db']; 
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
          echo ".scLink_default { text-decoration: underline; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:visited { text-decoration: underline; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:active { text-decoration: underline; font-size: 12px; color: #0000AA;  }";
          echo ".scLink_default:hover { text-decoration: none; font-size: 12px; color: #0000AA;  }";
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
          if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu'] && (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan']) || !$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan'])) 
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
      if (isset($_SESSION['scriptcase']['nm_sc_retorno']) && !empty($_SESSION['scriptcase']['nm_sc_retorno']) && isset($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao']) && !empty($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao']))
      { 
          $this->Db = db_conect_devel($_SESSION['scriptcase']['grid_dte_recep_sii']['glo_nm_conexao'], $this->root . $this->path_prod, 'WebDTE'); 
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
class grid_dte_recep_sii_sub_css
{
   function grid_dte_recep_sii_sub_css()
   {
      global $script_case_init;
      $str_schema_all = (isset($_SESSION['scriptcase']['str_schema_all']) && !empty($_SESSION['scriptcase']['str_schema_all'])) ? $_SESSION['scriptcase']['str_schema_all'] : "ScriptCase6_Blue/ScriptCase6_Blue";
      if ($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['SC_herda_css'] == "N")
      {
          $_SESSION['sc_session'][$script_case_init]['SC_sub_css']['grid_dte_recep_sii']    = $str_schema_all . "_grid.css";
          $_SESSION['sc_session'][$script_case_init]['SC_sub_css_bw']['grid_dte_recep_sii'] = $str_schema_all . "_grid_bw.css";
      }
   }
}
//
class grid_dte_recep_sii_apl
{
   var $Ini;
   var $Erro;
   var $Db;
   var $Lookup;
   var $nm_location;
   var $NM_ajax_flag    = false;
   var $NM_ajax_opcao   = '';
   var $NM_ajax_retorno = '';
   var $NM_ajax_info    = array('result'     => '',
                                  'param'      => array(),
                                  'autoComp'   => '',
                                  'rsSize'     => '',
                                  'msgDisplay' => '',
                                  'errList'    => array(),
                                  'fldList'    => array());
   var $grid;
   var $Res;
   var $Graf;
   var $pesq;
   var $xls;
//
//----- 
   function prep_modulos($modulo)
   {
      $this->$modulo->Ini = $this->Ini;
      $this->$modulo->Db = $this->Db;
      $this->$modulo->Erro = $this->Erro;
      $this->$modulo->Lookup = $this->Lookup;
      $this->$modulo->arr_buttons = $this->arr_buttons;
   }
//
//----- 
   function controle($linhas = 0)
   {
      global $nm_saida, $nm_url_saida, $script_case_init, $nmgp_parms_pdf, $nmgp_graf_pdf, $nm_apl_dependente, $nmgp_navegator_print, $nmgp_tipo_print, $nmgp_cor_print, $nmgp_cor_word, $NMSC_conf_apl, $NM_contr_var_session, $NM_run_iframe,
             $glo_senha_protect, $nmgp_opcao, $nm_call_php, $rec, $nmgp_quant_linhas, $nmgp_fast_search, $nmgp_cond_fast_search, $nmgp_arg_fast_search, $nmgp_ordem;

      if ($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'])
      { 
          if (!empty($GLOBALS['nmgp_parms'])) 
          { 
              $GLOBALS['nmgp_parms'] = str_replace("@aspass@", "'", $GLOBALS['nmgp_parms']);
              $todo = explode("?@?", $GLOBALS["nmgp_parms"]);
              foreach ($todo as $param)
              {
                   $cadapar = explode("?#?", $param);
                   if (1 < sizeof($cadapar))
                   {
                       if (isset($GLOBALS['sc_conv_var'][$cadapar[0]]))
                       {
                           $cadapar[0] = $GLOBALS['sc_conv_var'][$cadapar[0]];
                       }
                       elseif (isset($GLOBALS['sc_conv_var'][strtolower($cadapar[0])]))
                       {
                           $cadapar[0] = $GLOBALS['sc_conv_var'][strtolower($cadapar[0])];
                       }
                       nm_limpa_str_grid_dte_recep_sii($cadapar[1]);
                       $$cadapar[0] = $cadapar[1];
                       if ($cadapar[0] == "v_codi_empr") 
                       {
                           $_SESSION["v_codi_empr"] = $v_codi_empr;
                       }
                   }
              }
          } 
          if (isset($v_codi_empr)) 
          {
              $_SESSION['v_codi_empr'] = $v_codi_empr;
              nm_limpa_str_grid_dte_recep_sii($_SESSION["v_codi_empr"]);
          }
      } 
      $_SESSION['scriptcase']['sc_ctl_ajax'] = 'part';
      if (!$this->Ini) 
      { 
          $this->Ini = new grid_dte_recep_sii_ini(); 
          $this->Ini->init();
      } 
      $this->Db = $this->Ini->Db; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['nm_tpbanco'] = $this->Ini->nm_tpbanco;
      $this->nm_data = new nm_data("es");
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
      { 
          include_once($this->Ini->path_embutida . "grid_dte_recep_sii/grid_dte_recep_sii_erro.class.php"); 
      } 
      else 
      { 
          include_once($this->Ini->path_aplicacao . "grid_dte_recep_sii_erro.class.php"); 
      } 
      $this->Erro      = new grid_dte_recep_sii_erro();
      $this->Erro->Ini = $this->Ini;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
      { 
          require_once($this->Ini->path_embutida . "grid_dte_recep_sii/grid_dte_recep_sii_lookup.class.php"); 
      } 
      else 
      { 
          require_once($this->Ini->path_aplicacao . "grid_dte_recep_sii_lookup.class.php"); 
      } 
      $this->Lookup       = new grid_dte_recep_sii_lookup();
      $this->Lookup->Db   = $this->Db;
      $this->Lookup->Ini  = $this->Ini;
      $this->Lookup->Erro = $this->Erro;
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
      {
          $_SESSION['scriptcase']['saida_var'] = false;
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ajax_nav'] = false;
          if (isset($_POST['nmgp_opcao']) && $_POST['nmgp_opcao'] == "ajax_navigate")
          {
              ob_start();
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ajax_nav'] = true;
              $_SESSION['scriptcase']['saida_var']  = true;
              $_SESSION['scriptcase']['saida_html'] = "";
              $this->Ini->Arr_result = array();
              $nmgp_opcao = $_POST['opc'];
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = $nmgp_opcao;
              if ($nmgp_opcao == "edit" && isset($_POST['parm']) && $_POST['parm'] == "fim")
              {
                  $rec = $_POST['parm'];
              }
              if ($nmgp_opcao == "rec" || $nmgp_opcao == "muda_rec_linhas")
              {
                  $rec = $_POST['parm'];
              }
              if ($nmgp_opcao == "muda_qt_linhas")
              {
                  $nmgp_quant_linhas  = $_POST['parm'];
              }
              if ($nmgp_opcao == "fast_search")
              {
                  $temp = explode("_SCQS_", $_POST['parm']);
                  $nmgp_fast_search      = (isset($temp[0])) ? $temp[0] : "";
                  $nmgp_cond_fast_search = (isset($temp[1])) ? $temp[1] : "";
                  $nmgp_arg_fast_search  = (isset($temp[2])) ? $temp[2] : "";
              }
              if ($nmgp_opcao == "ordem")
              {
                  $nmgp_ordem = $_POST['parm'];
              }
          }
      }
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_dte_recep_sii.php" ; 
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
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['pdf_det'] = false;
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida']      = false;
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_grid']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_grid'] = false;
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_init']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_init'] = false;
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_label']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_label'] = false;
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['cab_embutida']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['cab_embutida'] = "";
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_pdf']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_pdf'] = "";
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_treeview']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_treeview'] = false;
      } 
      include("../_lib/css/" . $this->Ini->str_schema_all . "_grid.php");
      $this->Ini->Tree_img_col    = trim($str_tree_col);
      $this->Ini->Tree_img_exp    = trim($str_tree_exp);
      $this->Ini->Str_btn_grid    = trim($str_button) . "/" . trim($str_button) . ".php";
      $this->Ini->Str_btn_css     = trim($str_button) . "/" . trim($str_button) . ".css";
      include($this->Ini->path_btn . $this->Ini->Str_btn_grid);
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida']) || !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
      { 
          $this->Ini->Color_bg_ajax            = (!isset($str_ajax_bg)       || "" == trim($str_ajax_bg))         ? "#000" : $str_ajax_bg;
          $this->Ini->Border_c_ajax            = (!isset($str_ajax_border_c) || "" == trim($str_ajax_border_c))   ? ""     : $str_ajax_border_c;
          $this->Ini->Border_s_ajax            = (!isset($str_ajax_border_s) || "" == trim($str_ajax_border_s))   ? ""     : $str_ajax_border_s;
          $this->Ini->Border_w_ajax            = (!isset($str_ajax_border_w) || "" == trim($str_ajax_border_w))   ? ""     : $str_ajax_border_w;
          $this->Ini->Img_sep_grid             = "/" . trim($str_toolbar_separator);
          $this->Ini->Label_sort_pos           = trim($str_label_sort_pos);
          $this->Ini->Label_sort               = trim($str_label_sort);
          $this->Ini->Label_sort_asc           = trim($str_label_sort_asc);
          $this->Ini->Label_sort_desc          = trim($str_label_sort_desc);
          $this->Ini->Sum_ico_line             = trim($sum_ico_line);
          $this->Ini->Sum_ico_column           = trim($sum_ico_column);
          $this->Ini->Str_toolbarnav_separator = '/' . trim($str_toolbarnav_separator);
          $this->Ini->Img_qs_search            = '' != trim($img_qs_search)        ? trim($img_qs_search)        : 'scriptcase__NM__qs_lupa.png';
          $this->Ini->Img_qs_clean             = '' != trim($img_qs_clean)         ? trim($img_qs_clean)         : 'scriptcase__NM__qs_close.png';
          $this->Ini->Str_qs_image_padding     = '' != trim($str_qs_image_padding) ? trim($str_qs_image_padding) : '0';
          $this->Ini->sc_Include($this->Ini->path_lib_php . "/nm_gp_config_btn.php", "F", "nmButtonOutput") ; 
          $_SESSION['scriptcase']['css_popup']                 = $this->Ini->str_schema_all . "_grid.css";
          $_SESSION['scriptcase']['css_btn_popup']             = $this->Ini->Str_btn_css;
          $_SESSION['scriptcase']['bg_btn_popup']['bok']       = nmButtonOutput($this->arr_buttons, "bok", "processa()", "processa()", "bok", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
          $_SESSION['scriptcase']['bg_btn_popup']['bsair']     = nmButtonOutput($this->arr_buttons, "bsair", "window.close()", "window.close()", "bsair", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
          $_SESSION['scriptcase']['bg_btn_popup']['btbremove'] = nmButtonOutput($this->arr_buttons, "bsair", "self.parent.tb_remove()", "self.parent.tb_remove()", "bsair", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "");
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "tipo_dte";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "folio_dte";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "fecha_emision";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "rut_emisor";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "razon_social";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "monto_total";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "fecha_hora_recepcion";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['field_order'][] = "trackid";
      } 
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_dte_recep_sii']['exit']) && $_SESSION['scriptcase']['sc_apl_conf']['grid_dte_recep_sii']['exit'] != '')
      {
          $_SESSION['scriptcase']['sc_url_saida'][$this->Ini->sc_page] = $_SESSION['scriptcase']['sc_apl_conf']['grid_dte_recep_sii']['exit'];
      }

      $this->Ini->sc_Include($this->Ini->path_libs . "/nm_gc.php", "F", "nm_gc") ; 
      nm_gc($this->Ini->path_libs);
      if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'])
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
      if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'])
      { 
          $_SESSION['scriptcase']['sc_tp_pdf'] = "wkhtmltopdf";
          $_SESSION['scriptcase']['sc_idioma_pdf'] = array();
          $_SESSION['scriptcase']['sc_idioma_pdf']['es'] = array('titulo' => $this->Ini->Nm_lang['lang_pdff_titl'], 'tp_imp' => $this->Ini->Nm_lang['lang_pdff_type'], 'color' => $this->Ini->Nm_lang['lang_pdff_colr'], 'econm' => $this->Ini->Nm_lang['lang_pdff_bndw'], 'tp_pap' => $this->Ini->Nm_lang['lang_pdff_pper'], 'carta' => $this->Ini->Nm_lang['lang_pdff_letr'], 'oficio' => $this->Ini->Nm_lang['lang_pdff_legl'], 'customiz' => $this->Ini->Nm_lang['lang_pdff_cstm'], 'alt_papel' => $this->Ini->Nm_lang['lang_pdff_pper_hgth'], 'larg_papel' => $this->Ini->Nm_lang['lang_pdff_pper_wdth'], 'orient' => $this->Ini->Nm_lang['lang_pdff_pper_orie'], 'retrato' => $this->Ini->Nm_lang['lang_pdff_prtr'], 'paisag' => $this->Ini->Nm_lang['lang_pdff_lnds'], 'book' => $this->Ini->Nm_lang['lang_pdff_bkmk'], 'grafico' => $this->Ini->Nm_lang['lang_pdff_chrt'], 'largura' => $this->Ini->Nm_lang['lang_pdff_wdth'], 'fonte' => $this->Ini->Nm_lang['lang_pdff_font'], 'sim' => $this->Ini->Nm_lang['lang_pdff_chrt_yess'], 'nao' => $this->Ini->Nm_lang['lang_pdff_chrt_nooo'], 'cancela' => $this->Ini->Nm_lang['lang_pdff_cncl']);
      } 
      $_SESSION['scriptcase']['sc_idioma_graf_flash'] = array();
      $_SESSION['scriptcase']['sc_idioma_graf_flash']['es'] = array(
          'titulo' => $this->Ini->Nm_lang['lang_chrt_titl'],
          'tipo_g' => $this->Ini->Nm_lang['lang_chrt_type'],
          'tp_barra' => $this->Ini->Nm_lang['lang_flsh_chrt_bars'],
          'tp_pizza' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie'],
          'tp_linha' => $this->Ini->Nm_lang['lang_flsh_chrt_line'],
          'tp_area' => $this->Ini->Nm_lang['lang_flsh_chrt_area'],
          'tp_marcador' => $this->Ini->Nm_lang['lang_flsh_chrt_mrks'],
          'tp_gauge' => $this->Ini->Nm_lang['lang_flsh_chrt_gaug'],
          'tp_radar' => $this->Ini->Nm_lang['lang_flsh_chrt_radr'],
          'tp_polar' => $this->Ini->Nm_lang['lang_flsh_chrt_polr'],
          'tp_funil' => $this->Ini->Nm_lang['lang_flsh_chrt_funl'],
          'tp_pyramid' => $this->Ini->Nm_lang['lang_flsh_chrt_pyrm'],
          'largura' => $this->Ini->Nm_lang['lang_chrt_wdth'],
          'altura' => $this->Ini->Nm_lang['lang_chrt_hgth'],
          'modo_gera' => $this->Ini->Nm_lang['lang_chrt_gtmd'],
          'sintetico' => $this->Ini->Nm_lang['lang_chrt_smzd'],
          'analitico' => $this->Ini->Nm_lang['lang_chrt_anlt'],
          'order' => $this->Ini->Nm_lang['lang_chrt_ordr'],
          'order_none' => $this->Ini->Nm_lang['lang_chrt_ordr_none'],
          'order_asc' => $this->Ini->Nm_lang['lang_chrt_ordr_asc'],
          'order_desc' => $this->Ini->Nm_lang['lang_chrt_ordr_desc'],
          'barra_orien' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_layo'],
          'barra_orien_horiz' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_horz'],
          'barra_orien_verti' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_vrtc'],
          'barra_forma' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_shpe'],
          'barra_forma_barra' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_bars'],
          'barra_forma_cilin' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_cyld'],
          'barra_forma_cone' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_cone'],
          'barra_forma_piram' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_pyrm'],
          'barra_dimen' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_dmns'],
          'barra_dimen_2d' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_2ddm'],
          'barra_dimen_3d' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_3ddm'],
          'barra_sobre' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_3ovr'],
          'barra_sobre_nao' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_3ont'],
          'barra_sobre_sim' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_3oys'],
          'barra_empil' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_stck'],
          'barra_empil_desat' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_stan'],
          'barra_empil_ativa' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_stay'],
          'barra_empil_perce' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_stap'],
          'barra_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_invr'],
          'barra_inver_norma' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_invn'],
          'barra_inver_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_invi'],
          'barra_agrup' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_srgr'],
          'barra_agrup_conju' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_srst'],
          'barra_agrup_serie' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_srbs'],
          'barra_funil' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_funl'],
          'barra_funil_nao' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_3ont'],
          'barra_funil_sim' => $this->Ini->Nm_lang['lang_flsh_chrt_bars_3oys'],
          'pizza_forma' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_shpe'],
          'pizza_forma_pizza' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_fpie'],
          'pizza_forma_donut' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_dnts'],
          'pizza_dimen' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_dmns'],
          'pizza_dimen_2d' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_2ddm'],
          'pizza_dimen_3d' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_3ddm'],
          'pizza_orden' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_srtn'],
          'pizza_orden_desat' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_nsrt'],
          'pizza_orden_ascen' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_asrt'],
          'pizza_orden_desce' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_dsrt'],
          'pizza_explo' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_expl'],
          'pizza_explo_desat' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_dxpl'],
          'pizza_explo_ativa' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_axpl'],
          'pizza_explo_click' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_cxpl'],
          'pizza_valor' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_fval'],
          'pizza_valor_valor' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_fvlv'],
          'pizza_valor_perce' => $this->Ini->Nm_lang['lang_flsh_chrt_fpie_fvlp'],
          'linha_forma' => $this->Ini->Nm_lang['lang_flsh_chrt_line_shpe'],
          'linha_forma_linha' => $this->Ini->Nm_lang['lang_flsh_chrt_line_line'],
          'linha_forma_splin' => $this->Ini->Nm_lang['lang_flsh_chrt_line_spln'],
          'linha_forma_degra' => $this->Ini->Nm_lang['lang_flsh_chrt_line_step'],
          'linha_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_line_invr'],
          'linha_inver_norma' => $this->Ini->Nm_lang['lang_flsh_chrt_line_invn'],
          'linha_inver_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_line_invi'],
          'linha_agrup' => $this->Ini->Nm_lang['lang_flsh_chrt_line_srgr'],
          'linha_agrup_conju' => $this->Ini->Nm_lang['lang_flsh_chrt_line_srst'],
          'linha_agrup_serie' => $this->Ini->Nm_lang['lang_flsh_chrt_line_srbs'],
          'area_forma' => $this->Ini->Nm_lang['lang_flsh_chrt_area_shpe'],
          'area_forma_area' => $this->Ini->Nm_lang['lang_flsh_chrt_area_area'],
          'area_forma_splin' => $this->Ini->Nm_lang['lang_flsh_chrt_area_spln'],
          'area_empil' => $this->Ini->Nm_lang['lang_flsh_chrt_area_stak'],
          'area_empil_desat' => $this->Ini->Nm_lang['lang_flsh_chrt_area_stan'],
          'area_empil_ativa' => $this->Ini->Nm_lang['lang_flsh_chrt_area_stay'],
          'area_empil_perce' => $this->Ini->Nm_lang['lang_flsh_chrt_area_stap'],
          'area_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_area_invr'],
          'area_inver_norma' => $this->Ini->Nm_lang['lang_flsh_chrt_area_invn'],
          'area_inver_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_area_invi'],
          'area_agrup' => $this->Ini->Nm_lang['lang_flsh_chrt_area_srgr'],
          'area_agrup_conju' => $this->Ini->Nm_lang['lang_flsh_chrt_area_srst'],
          'area_agrup_serie' => $this->Ini->Nm_lang['lang_flsh_chrt_area_srbs'],
          'marca_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_mrks_invr'],
          'marca_inver_norma' => $this->Ini->Nm_lang['lang_flsh_chrt_mrks_invn'],
          'marca_inver_inver' => $this->Ini->Nm_lang['lang_flsh_chrt_mrks_invi'],
          'marca_agrup' => $this->Ini->Nm_lang['lang_flsh_chrt_mrks_srgr'],
          'marca_agrup_conju' => $this->Ini->Nm_lang['lang_flsh_chrt_mrks_srst'],
          'marca_agrup_serie' => $this->Ini->Nm_lang['lang_flsh_chrt_mrks_srbs'],
          'gauge_forma' => $this->Ini->Nm_lang['lang_flsh_chrt_gaug_shpe'],
          'gauge_forma_circular' => $this->Ini->Nm_lang['lang_flsh_chrt_gaug_circ'],
          'gauge_forma_semi' => $this->Ini->Nm_lang['lang_flsh_chrt_gaug_semi'],
          'pyram_slice' => $this->Ini->Nm_lang['lang_flsh_chrt_pyrm_slic'],
          'pyram_slice_s' => $this->Ini->Nm_lang['lang_flsh_chrt_pyrm_opcs'],
          'pyram_slice_n' => $this->Ini->Nm_lang['lang_flsh_chrt_pyrm_opcn'],
      );
      if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'])
      { 
          $_SESSION['scriptcase']['sc_idioma_prt'] = array();
          $_SESSION['scriptcase']['sc_idioma_prt']['es'] = array('titulo' => $this->Ini->Nm_lang['lang_btns_prtn_titl_hint'], 'modoimp' => $this->Ini->Nm_lang['lang_btns_mode_prnt_hint'], 'curr' => $this->Ini->Nm_lang['lang_othr_curr_page'], 'total' => $this->Ini->Nm_lang['lang_othr_full'], 'cor' => $this->Ini->Nm_lang['lang_othr_prtc'], 'pb' => $this->Ini->Nm_lang['lang_othr_bndw'], 'color' => $this->Ini->Nm_lang['lang_othr_colr'], 'cancela' => $this->Ini->Nm_lang['lang_btns_cncl_prnt_hint']);
      } 
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
      $NM_orig = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
      if (strpos($NM_orig, "/grid_dte_recep_sii") === false)
      {
          $nmgp_opcao = "busca";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = "busca";  
      }
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ordem_select']))  
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ordem_select'] = array(); 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ordem_select']['fecha_emision'] = 'DESC'; 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ordem_select_orig'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ordem_select']; 
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao']) || empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao']) || !isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig']))  
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = "busca" ;  
      }   
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_form']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida_form'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "busca")
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = "inicio";
      }
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig']) || !empty($nmgp_parms) || !empty($GLOBALS["nmgp_parms"]))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opc_liga'] = array();  
          if (isset($NMSC_conf_apl) && !empty($NMSC_conf_apl))
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opc_liga'] = $NMSC_conf_apl;  
          }   
          if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opc_liga']['inicial']))
          {
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opc_liga']['inicial'];
          }
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'] && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "busca")
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = "grid" ;  
      }   
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao_print']) || empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao_print']))  
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao_print'] = "inicio" ;  
      }   
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['print_all'] = false;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "res_print")  
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao']     = "resumo";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['print_all'] = true;
      } 
      if (substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'], 0, 7) == "grafico")  
      { 
          $_SESSION['scriptcase']['sc_ctl_ajax'] = 'part';
      } 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "pdf")
      { 
          $this->Ini->path_img_modelo = $this->Ini->path_img_modelo;
      } 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "fast_search")  
      { 
          $this->SC_fast_search($GLOBALS["nmgp_fast_search"], $GLOBALS["nmgp_cond_fast_search"], $GLOBALS["nmgp_arg_fast_search"]);
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq_ant'] == $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq'])
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = 'igual';
          } 
          else 
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq_ant'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq'];
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['contr_array_resumo'] = "NAO";
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['contr_total_geral']  = "NAO";
              unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['tot_geral']);
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = 'pesq';
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['orig_pesq'] = 'grid';
          } 
      } 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == 'pesq' && isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['orig_pesq']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['orig_pesq']))  
      { 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['orig_pesq'] == "res")  
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = 'resumo';
          } 
          elseif ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['orig_pesq'] == "grid") 
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = 'inicio';
          } 
      } 
//
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['prim_cons'] = false;  
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig']) || !empty($nmgp_parms) || !empty($GLOBALS["nmgp_parms"]))  
      { 
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['prim_cons'] = true;  
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig'] = " where empresa_receptor='" . $_SESSION['v_codi_empr'] . "'";  
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq']       = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig'];  
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq_ant']   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig'];  
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['cond_pesq'] = ""; 
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq_filtro'] = "";
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['contr_total_geral'] = "NAO";
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['total']);
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['tot_geral']);
      } 
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq_filtro'];
      $nm_flag_pdf   = false;
      $nm_vendo_pdf  = false;
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
      {
         $this->Ini->sc_Include($this->Ini->path_libs . "/nm_trata_saida.php", "C", "nm_trata_saida") ; 
         $nm_saida = new nm_trata_saida();
      }
//----------------------------------->
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "xls")  
      { 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
          { 
              require_once($this->Ini->path_embutida . "grid_dte_recep_sii/grid_dte_recep_sii_xls.class.php"); 
          } 
          else 
          { 
              require_once($this->Ini->path_aplicacao . "grid_dte_recep_sii_xls.class.php"); 
          } 
          $this->xls  = new grid_dte_recep_sii_xls();
          $this->prep_modulos("xls");
          $this->xls->monta_xls();
      }
      else
      if (substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'], 0, 7) == "grafico")  
      { 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
          { 
              require_once($this->Ini->path_embutida . " . grid_dte_recep_sii . /" . $this->Ini->Apl_grafico); 
          } 
          else 
          { 
              require_once($this->Ini->path_aplicacao . $this->Ini->Apl_grafico); 
          } 
          $this->Graf  = new grid_dte_recep_sii_grafico();
          $this->prep_modulos("Graf");
          if (substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'], 7, 1) == "_")  
          { 
              $this->Graf->grafico_col(substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'], 8));
          }
          else
          { 
              $this->Graf->monta_grafico();
          }
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] = "grid";
      }
      else 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "busca")  
      { 
          require_once($this->Ini->path_aplicacao . "grid_dte_recep_sii_pesq.class.php"); 
          $this->pesq  = new grid_dte_recep_sii_pesq();
          $this->prep_modulos("pesq");
          $this->pesq->NM_ajax_flag    = $this->NM_ajax_flag;
          $this->pesq->NM_ajax_opcao   = $this->NM_ajax_opcao;
          $this->pesq->NM_ajax_retorno = $this->NM_ajax_retorno;
          $this->pesq->NM_ajax_info    = $this->NM_ajax_info;
          $this->pesq->monta_busca();
      }
      else 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "resumo")  
      { 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
          { 
              require_once($this->Ini->path_embutida . "grid_dte_recep_sii/" . $this->Ini->Apl_resumo); 
          } 
          else 
          { 
              require_once($this->Ini->path_aplicacao . $this->Ini->Apl_resumo); 
          } 
          $this->Res = new grid_dte_recep_sii_resumo("out");
          $this->prep_modulos("Res");
          $this->Res->monta_resumo();
      }
      else 
      { 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "print" && $nmgp_tipo_print == "RC")
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['print_navigator'] = $nmgp_navegator_print;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['print_all'] = true;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao']     = "pdf";
              $GLOBALS['nmgp_tipo_pdf'] = strtolower($nmgp_cor_print);
          } 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao'] == "doc_word")
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['print_navigator'] = "Microsoft Internet Explorer";
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['print_all'] = true;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['doc_word']  = true;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['opcao']     = "pdf";
              $_SESSION['scriptcase']['saida_word'] = true;
              $nm_arquivo_doc_word = "/sc_grid_dte_recep_sii_" . session_id() . ".doc";
              $nm_saida->seta_arquivo($this->Ini->root . $this->Ini->path_imag_temp . $nm_arquivo_doc_word);
              $this->ret_word = "volta_grid";
              $this->Ini->nm_limite_lin_prt = 0;
              $GLOBALS['nmgp_tipo_pdf'] = $nmgp_cor_word;
          } 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
          { 
              require_once($this->Ini->path_embutida . "grid_dte_recep_sii/grid_dte_recep_sii_grid.class.php"); 
          } 
          else 
          { 
              require_once($this->Ini->path_aplicacao . "grid_dte_recep_sii_grid.class.php"); 
          } 
          $this->grid  = new grid_dte_recep_sii_grid();
          $this->prep_modulos("grid");
          $this->grid->monta_grid($linhas);
      }   
//--- 
      if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'])
      {
           $this->Db->Close(); 
      }
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['embutida'])
      {
         $nm_saida->finaliza();
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['ajax_nav'])
         {
             $this->Ini->Arr_result['setVar'][] = array('var' => 'scQtReg', 'value' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['qt_reg_grid']);
             $Temp = ob_get_clean();
             if ($Temp !== false && trim($Temp) != "")
             {
                 $this->Ini->Arr_result['htmOutput'] = $Temp;
             }
             $_SESSION['scriptcase']['saida_var'] = false;
             $oJson = new Services_JSON();
             echo $oJson->encode($this->Ini->Arr_result);
             exit;
         }
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['doc_word'])
         {
             $this->html_doc_word($nm_arquivo_doc_word);
         }
      }
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
  function close_emb()
  {
      if ($this->Db)
      {
          $this->Db->Close(); 
      }
  }
   function SC_fast_search($field, $arg_search, $data_search)
   {
      if (empty($data_search)) 
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq_filtro'] = "";
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['fast_search']);
          return;
      }
      $comando = "";
      if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($data_search))
      {
          $data_search = NM_conv_charset($data_search, $_SESSION['scriptcase']['charset'], "UTF-8");
      }
      $sv_data = $data_search;
      if ($field == "SC_all_Cmp") 
      {
          $this->SC_monta_condicao($comando, "tipo_dte", $arg_search, $data_search);
      }
      if ($field == "SC_all_Cmp") 
      {
          $this->SC_monta_condicao($comando, "folio_dte", $arg_search, $data_search);
      }
      if ($field == "SC_all_Cmp") 
      {
          $this->SC_monta_condicao($comando, "rut_emisor", $arg_search, $data_search);
      }
      if ($field == "SC_all_Cmp") 
      {
          $this->SC_monta_condicao($comando, "razon_social", $arg_search, $data_search);
      }
      if ($field == "SC_all_Cmp") 
      {
          $this->SC_monta_condicao($comando, "monto_total", $arg_search, $data_search);
      }
      if ($field == "SC_all_Cmp") 
      {
          $this->SC_monta_condicao($comando, "trackid", $arg_search, $data_search);
      }
      if (empty($comando)) 
      {
          $comando = " 1 <> 1 "; 
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq_filtro'] = "( " . $comando . " )";
      if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig'])) 
      {
          $comando = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_orig'] . " and ( " . $comando . " )"; 
      }
      else
      {
          $comando = " where (" . $comando . ")"; 
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['where_pesq'] = $comando;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['fast_search'][0] = $field;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['fast_search'][1] = $arg_search;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['fast_search'][2] = $sv_data;
   }
   function SC_monta_condicao(&$comando, $nome, $condicao, $campo, $tp_campo="")
   {
      $nm_aspas   = "'";
      $nm_numeric = array();
      $campo_join = strtolower(str_replace(".", "_", $nome));
      $nm_ini_lower = "";
      $nm_fim_lower = "";
      $nm_numeric[] = "folio_dte";$nm_numeric[] = "monto_total";$nm_numeric[] = "empresa_receptor";
      if (in_array($campo_join, $nm_numeric))
      {
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_dte_recep_sii']['decimal_db'] == ".")
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
      $Nm_datas[] = "fecha_emision";$Nm_datas[] = "fecha_hora_recepcion";
         if (in_array($campo_join, $nm_numeric) && in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres) && (strtoupper($condicao) == "II" || strtoupper($condicao) == "QP" || strtoupper($condicao) == "NP"))
         {
             $nome     = "CAST ($nome AS TEXT)";
             $nm_aspas = "'";
         }
         if (in_array($campo_join, $Nm_datas) && in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
         {
             $nome     = "to_char ($nome, 'YYYY-MM-DD')";
         }
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
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('year' from $nome) = " . $this->NM_data_qp['ano'];
                       }
                       else
                       {
                           $NM_cmd     .= "year($nome) = " . $this->NM_data_qp['ano'];
                       }
                   }
                   if ($this->NM_data_qp['mes'] != "__")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('month' from $nome) = " . $this->NM_data_qp['mes'];
                       }
                       else
                       {
                           $NM_cmd     .= "month($nome) = " . $this->NM_data_qp['mes'];
                       }
                   }
                   if ($this->NM_data_qp['dia'] != "__")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('day' from $nome) = " . $this->NM_data_qp['dia'];
                       }
                       else
                       {
                           $NM_cmd     .= "day($nome) = " . $this->NM_data_qp['dia'];
                       }
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
            case "NP":     // 
               if (substr($tp_campo, 0, 4) == "DATE")
               {
                   $NM_cmd     = "";
                   if ($this->NM_data_qp['ano'] != "____")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('year' from $nome) <> " . $this->NM_data_qp['ano'];
                       }
                       else
                       {
                           $NM_cmd     .= "year($nome) <> " . $this->NM_data_qp['ano'];
                       }
                   }
                   if ($this->NM_data_qp['mes'] != "__")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('month' from $nome) <> " . $this->NM_data_qp['mes'];
                       }
                       else
                       {
                           $NM_cmd     .= "month($nome) <> " . $this->NM_data_qp['mes'];
                       }
                   }
                   if ($this->NM_data_qp['dia'] != "__")
                   {
                       $NM_cmd     .= (empty($NM_cmd)) ? "" : " and ";
                       if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_postgres))
                       {
                           $NM_cmd     .= "extract('day' from $nome) <> " . $this->NM_data_qp['dia'];
                       }
                       else
                       {
                           $NM_cmd     .= "day($nome) <> " . $this->NM_data_qp['dia'];
                       }
                   }
                   if (!empty($NM_cmd))
                   {
                       $NM_cmd     = " (" . $NM_cmd . ")";
                       $comando        .= $NM_cmd;
                   }
               }
               else
               {
                   $comando        .= $nm_ini_lower . $nome . $nm_fim_lower ." not like '%" . $campo . "%'";
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
  function html_doc_word($nm_arquivo_doc_word)
  {
      global $nm_url_saida;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE>DTE Recibidos por SII :: Doc</TITLE>
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
<form name="Fview" method="get" action="<?php echo $this->Ini->path_imag_temp . $nm_arquivo_doc_word ?>" target="_blank" style="display: none"> 
</form>
<form name="Fdown" method="get" action="grid_dte_recep_sii_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="nm_tit_doc" value="grid_dte_recep_sii.doc"> 
<input type="hidden" name="nm_name_doc" value="<?php echo NM_encode_input($this->Ini->path_imag_temp . $nm_arquivo_doc_word) ?>"> 
</form>
<FORM name="F0" method=post action="grid_dte_recep_sii.php"> 
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
       include_once("grid_dte_recep_sii_nmutf8.php");
   }
   if (!function_exists("SC_dir_app_ini"))
   {
       include_once("../_lib/lib/php/nm_ctrl_app_name.php");
   }
   SC_dir_app_ini('WebDTE');
   $_SESSION['scriptcase']['grid_dte_recep_sii']['contr_erro'] = 'off';
   $sc_conv_var = array();
   if (!empty($_POST))
   {
       foreach ($_POST as $nmgp_var => $nmgp_val)
       {
            if (isset($sc_conv_var[$nmgp_var]))
            {
                $nmgp_var = $sc_conv_var[$nmgp_var];
            }
            elseif (isset($sc_conv_var[strtolower($nmgp_var)]))
            {
                $nmgp_var = $sc_conv_var[strtolower($nmgp_var)];
            }
            nm_limpa_str_grid_dte_recep_sii($nmgp_val);
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
            elseif (isset($sc_conv_var[strtolower($nmgp_var)]))
            {
                $nmgp_var = $sc_conv_var[strtolower($nmgp_var)];
            }
            nm_limpa_str_grid_dte_recep_sii($nmgp_val);
            $$nmgp_var = $nmgp_val;
       }
   }

    if (isset($_POST['rs']) && strpos($_POST['rs'], '_ajax_') !== false && isset($_POST['rsargs']) && !empty($_POST['rsargs']))
    {
        $nmgp_opcao = "busca";
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
   if (isset($script_case_init) && isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida_form_parms'])) 
   {
       if (!empty($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida_form_parms'])) 
       {
           $nmgp_parms = $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida_form_parms'];
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida_form_parms'] = "";
       }
   }
   elseif (isset($script_case_init))
   {
       unset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida_form']);
       unset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida_form_parms']);
   }
   if (!isset($nmgp_opcao) || !isset($script_case_init) || ((!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida']) || !$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida']) && $nmgp_opcao != "formphp"))
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
                    elseif (isset($sc_conv_var[strtolower($cadapar[0])]))
                    {
                        $cadapar[0] = $sc_conv_var[strtolower($cadapar[0])];
                    }
                    nm_limpa_str_grid_dte_recep_sii($cadapar[1]);
                    $$cadapar[0] = $cadapar[1];
                    if ($cadapar[0] == "v_codi_empr") 
                    {
                        $_SESSION["v_codi_empr"] = $v_codi_empr;
                    }
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
               unset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']);
               $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['b_sair'] = false;
           }   
       } 
   } 
   $ini_embutida = "";
   if (isset($script_case_init) && isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida']) && $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'])
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
        if (!isset($_SESSION['sc_session'][$ini_embutida]['grid_dte_recep_sii']['embutida']))
        { 
           $_SESSION['sc_session'][$ini_embutida]['grid_dte_recep_sii']['embutida'] = false;
        }
        if (!$_SESSION['sc_session'][$ini_embutida]['grid_dte_recep_sii']['embutida'])
        { 
           $script_case_init = $ini_embutida;
        }
   }
   if (isset($_SESSION['scriptcase']['grid_dte_recep_sii']['protect_modal']) && !empty($_SESSION['scriptcase']['grid_dte_recep_sii']['protect_modal']))
   {
       $script_case_init = $_SESSION['scriptcase']['grid_dte_recep_sii']['protect_modal'];
   }
   if (!isset($script_case_init) || empty($script_case_init))
   {
       $script_case_init = rand(2, 1000);
   }
   $salva_emb    = false;
   $salva_iframe = false;
   $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['doc_word'] = false;
   $_SESSION['scriptcase']['saida_word'] = false;
   if (isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu']))
   {
       $salva_iframe = $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu'];
       unset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu']);
   }
   if (isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida']))
   {
       $salva_emb = $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'];
       unset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida']);
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
            $_SESSION['scriptcase']['sc_apl_menu_atual'] = "grid_dte_recep_sii";
        }
        $achou = false;
        if (isset($_SESSION['sc_session'][$script_case_init]))
        {
            foreach ($_SESSION['sc_session'][$script_case_init] as $nome_apl => $resto)
            {
                if ($nome_apl == 'grid_dte_recep_sii' || $achou)
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
        $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu'] = true;
   }
   else
   {
       $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['iframe_menu'] = $salva_iframe;
   }
   $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'] = $salva_emb;

   if (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['initialize']))
   {
       $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['initialize'] = true;
   }
   elseif (!isset($_SERVER['HTTP_REFERER']))
   {
       $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['initialize'] = false;
   }
   elseif (false === strpos($_SERVER['HTTP_REFERER'], '.php'))
   {
       $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['initialize'] = true;
   }
   else
   {
       $sReferer = substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], '.php'));
       $sReferer = substr($sReferer, strrpos($sReferer, '/') + 1);
       if ('grid_dte_recep_sii' == $sReferer || 'grid_dte_recep_sii_' == substr($sReferer, 0, 19))
       {
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['initialize'] = false;
       }
       else
       {
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['initialize'] = true;
       }
   }
   if ($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['initialize'])
   {
       unset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['tot_geral']);
   }

   $_POST['script_case_init'] = $script_case_init;
   if (isset($nmgp_opcao) && $nmgp_opcao == "busca" && isset($nmgp_orig_pesq))
   {
       $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['orig_pesq'] = $nmgp_orig_pesq;
   }
   if (!isset($nmgp_opcao) || empty($nmgp_opcao) || $nmgp_opcao == "grid" && (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['b_sair'])))
   {
       $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['b_sair'] = true;
   }
   if (isset($_SESSION['scriptcase']['sc_outra_jan']) && $_SESSION['scriptcase']['sc_outra_jan'] == 'grid_dte_recep_sii')
   {
       $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan'] = true;
        unset($_SESSION['scriptcase']['sc_outra_jan']);
   }
   $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['menu_desenv'] = false;   
   if (!defined("SC_ERROR_HANDLER"))
   {
       define("SC_ERROR_HANDLER", 1);
       include_once(dirname(__FILE__) . "/grid_dte_recep_sii_erro.php");
   }
   $salva_tp_saida  = (isset($_SESSION['scriptcase']['sc_tp_saida']))  ? $_SESSION['scriptcase']['sc_tp_saida'] : "";
   $salva_url_saida  = (isset($_SESSION['scriptcase']['sc_url_saida'][$script_case_init])) ? $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] : "";
   if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'] && $nmgp_opcao != "formphp")
   { 
       if (empty($nmgp_opcao) && !isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao']))
       { 
           $nmgp_opcao = "busca";
       } 
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
           unset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['tot_geral']);
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
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['num_css'] = rand(0, 1000);
       } 
       if ($nmgp_opcao == "volta_grid")  
       { 
           $nmgp_opcao = "igual";  
       }   
       if (!empty($nmgp_opcao))  
       { 
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao'] = $nmgp_opcao ;  
       }   
       if (isset($_POST["v_codi_empr"])) 
       {
           $_SESSION["v_codi_empr"] = $_POST["v_codi_empr"];
           nm_limpa_str_grid_dte_recep_sii($_SESSION["v_codi_empr"]);
       }
       if (isset($_GET["v_codi_empr"])) 
       {
           $_SESSION["v_codi_empr"] = $_GET["v_codi_empr"];
           nm_limpa_str_grid_dte_recep_sii($_SESSION["v_codi_empr"]);
       }
       if (!isset($_SESSION["v_codi_empr"])) 
       {
           $_SESSION["v_codi_empr"] = "";
       }
       if (isset($nmgp_lig_edit_lapis)) 
       {
          $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['mostra_edit'] = $nmgp_lig_edit_lapis;
           unset($GLOBALS["nmgp_lig_edit_lapis"]) ;  
           if (isset($_SESSION['nmgp_lig_edit_lapis'])) 
           {
               unset($_SESSION['nmgp_lig_edit_lapis']);
           }
       }
       if (isset($nmgp_outra_jan) && $nmgp_outra_jan == 'true')
       {
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan'] = true;
       }
       if (isset($nmgp_rotaciona) && $nmgp_rotaciona == "S") 
       {
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['res_hrz'] = ($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['res_hrz']) ? false : true;
       }
       $nm_saida = "";
      if (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao']) || empty($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao']) || !isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['where_orig']))  
      { 
          $nmgp_opcao = "busca" ;  
      }   
       if (isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['volta_redirect_apl']) && !empty($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['volta_redirect_apl']))
       {
           $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['volta_redirect_apl']; 
           $nm_apl_dependente = $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['volta_redirect_tp']; 
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['volta_redirect_apl'] = "";
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['volta_redirect_tp'] = "";
           $nm_url_saida = "grid_dte_recep_sii_fim.php"; 
       
       }
       elseif (substr($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao'], 0, 7) != "grafico" && $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao'] != "pdf"  && $nmgp_opcao == "busca") 
       {
           if (isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan']) && $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan'])
           {
               if ($nmgp_url_saida == "modal")
               {
                   $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_modal'] = true;
               }
               $nm_url_saida = "grid_dte_recep_sii_fim.php"; 
           }
           else
           {
               $nm_url_saida = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ""; 
               $nm_url_saida = str_replace("_fim.php", ".php", $nm_url_saida);
               if (!empty($nmgp_url_saida)) 
               { 
                   $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['retorno_cons'] = $nmgp_url_saida ; 
               } 
               if (!empty($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['retorno_cons'])) 
               { 
                   $nm_url_saida = $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['retorno_cons']  . "?script_case_init=" . NM_encode_input($script_case_init);  
                   $nm_apl_dependente = 1 ; 
               } 
               if (!empty($nm_url_saida)) 
               { 
                   $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $nm_url_saida ; 
               } 
               $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $nm_url_saida; 
               $nm_url_saida = "grid_dte_recep_sii_fim.php"; 
               $_SESSION['scriptcase']['sc_tp_saida'] = "P"; 
               if ($nm_apl_dependente == 1) 
               { 
                   $_SESSION['scriptcase']['sc_tp_saida'] = "D"; 
               } 
           } 
       }
// 
       if (isset($_SESSION['scriptcase']['nm_sc_retorno']) && !empty($_SESSION['scriptcase']['nm_sc_retorno']) && $nm_apl_dependente != 1 && substr($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao'], 0, 7) != "grafico" && $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao'] != "pdf"  && $nmgp_opcao == "busca") 
       { 
            $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['menu_desenv'] = true;   
       } 
       if (isset($_GET["nmgp_parms_ret"])) 
       {
           $todo = explode(",", $nmgp_parms_ret);
           if (isset($sc_conv_var[$todo[2]]))
           {
               $todo[2] = $sc_conv_var[$todo[2]];
           }
           elseif (isset($sc_conv_var[strtolower($todo[2])]))
           {
               $todo[2] = $sc_conv_var[strtolower($todo[2])];
           }
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['form_psq_ret']  = $todo[0];
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['campo_psq_ret'] = $todo[1];
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['dado_psq_ret']  = $todo[2];
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['js_apos_busca'] = $nm_evt_ret_busca;
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opc_psq'] = true ;   
       } 
       elseif (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opc_psq']))
       {
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opc_psq'] = false ;   
       } 
       if ($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida_form'])
       {
           $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['mostra_edit'] = "N";   
           $_SESSION['scriptcase']['sc_tp_saida']  = $salva_tp_saida;
           $_SESSION['scriptcase']['sc_url_saida'][$script_case_init] = $salva_url_saida;
       } 
       $GLOBALS["NM_ERRO_IBASE"] = 0;  
       if (isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan']) && $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['sc_outra_jan'])
       {
           $nm_apl_dependente = 0;
       }
       $contr_grid_dte_recep_sii = new grid_dte_recep_sii_apl();

      if (!isset($_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida']) || !$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'])
      { 
          if (!function_exists("sajax_init")) 
          { 
              include_once(dirname(__FILE__) . '/grid_dte_recep_sii_sajax.php');
          }
          $sajax_request_type = "POST";
          sajax_init();
      }
      //$sajax_debug_mode = 1;
      sajax_handle_client_request();

      if ('ajax_autocomp' == $nmgp_opcao)
      {
          $nmgp_opcao = 'busca';
          $_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['opcao'] = "busca";
          $contr_grid_dte_recep_sii->NM_ajax_flag = true;
          $contr_grid_dte_recep_sii->NM_ajax_opcao = $NM_ajax_opcao;
      }

       $contr_grid_dte_recep_sii->controle();
   } 
   if (!$_SESSION['sc_session'][$script_case_init]['grid_dte_recep_sii']['embutida'] && $nmgp_opcao == "formphp")
   { 
       $GLOBALS["NM_ERRO_IBASE"] = 0;  
       $contr_grid_dte_recep_sii = new grid_dte_recep_sii_apl();
       $contr_grid_dte_recep_sii->controle();
   } 
//
   function nm_limpa_str_grid_dte_recep_sii(&$str)
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

    function grid_dte_recep_sii_nm_return_ajax()
    {
        global $contr_grid_dte_recep_sii;
        $contr_grid_dte_recep_sii->NM_ajax_flag    = $contr_grid_dte_recep_sii->pesq->NM_ajax_flag;
        $contr_grid_dte_recep_sii->NM_ajax_opcao   = $contr_grid_dte_recep_sii->pesq->NM_ajax_opcao;
        $contr_grid_dte_recep_sii->NM_ajax_retorno = $contr_grid_dte_recep_sii->pesq->NM_ajax_retorno;
        $contr_grid_dte_recep_sii->NM_ajax_info    = $contr_grid_dte_recep_sii->pesq->NM_ajax_info;
        grid_dte_recep_sii_pack_ajax_response();
    }

   function grid_dte_recep_sii_pack_ajax_response()
   {
      global $contr_grid_dte_recep_sii;
      $aResp = array();
      if ($contr_grid_dte_recep_sii->NM_ajax_info['calendarReload'])
      {
         $aResp['result'] = 'CALENDARRELOAD';
      }
      elseif ('' != $contr_grid_dte_recep_sii->NM_ajax_info['autoComp'])
      {
         $aResp['result'] = 'AUTOCOMP';
      }
//mestre_detalhe
      elseif (!empty($contr_grid_dte_recep_sii->NM_ajax_info['newline']))
      {
         $aResp['result'] = 'NEWLINE';
         ob_end_clean();
      }
      elseif (!empty($contr_grid_dte_recep_sii->NM_ajax_info['tableRefresh']))
      {
         $aResp['result'] = 'TABLEREFRESH';
      }
//-----
      elseif (!empty($contr_grid_dte_recep_sii->NM_ajax_info['errList']))
      {
         $aResp['result'] = 'ERROR';
      }
      elseif (!empty($contr_grid_dte_recep_sii->NM_ajax_info['fldList']))
      {
         $aResp['result'] = 'SET';
      }
      else
      {
         $aResp['result'] = 'OK';
      }
      if ('AUTOCOMP' == $aResp['result'])
      {
         $aResp = $contr_grid_dte_recep_sii->NM_ajax_info['autoComp'];
      }
//mestre_detalhe
      elseif ('NEWLINE' == $aResp['result'])
      {
         $aResp = $contr_grid_dte_recep_sii->NM_ajax_info['newline'];
      }
      else
//-----
      {
         $aResp['ajaxRequest'] = $contr_grid_dte_recep_sii->NM_ajax_opcao;
         if ('CALENDARRELOAD' == $aResp['result'])
         {
            grid_dte_recep_sii_pack_calendar_reload($aResp);
         }
         elseif ('ERROR' == $aResp['result'])
         {
            grid_dte_recep_sii_pack_ajax_errors($aResp);
         }
         elseif ('SET' == $aResp['result'])
         {
            grid_dte_recep_sii_pack_ajax_set_fields($aResp);
         }
         elseif ('TABLEREFRESH' == $aResp['result'])
         {
            grid_dte_recep_sii_pack_ajax_set_fields($aResp);
            $aResp['tableRefresh'] = grid_dte_recep_sii_pack_protect_string($contr_grid_dte_recep_sii->NM_ajax_info['tableRefresh']);
         }
         if ('OK' == $aResp['result'] || 'SET' == $aResp['result'])
         {
            grid_dte_recep_sii_pack_ajax_ok($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['focus']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['focus'])
         {
            $aResp['setFocus'] = $contr_grid_dte_recep_sii->NM_ajax_info['focus'];
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['closeLine']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['closeLine'])
         {
            $aResp['closeLine'] = $contr_grid_dte_recep_sii->NM_ajax_info['closeLine'];
         }
         else
         {
            $aResp['closeLine'] = 'N';
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['clearUpload']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['clearUpload'])
         {
            $aResp['clearUpload'] = $contr_grid_dte_recep_sii->NM_ajax_info['clearUpload'];
         }
         else
         {
            $aResp['clearUpload'] = 'N';
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['masterValue']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['masterValue'])
         {
            grid_dte_recep_sii_pack_master_value($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxAlert']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['ajaxAlert'])
         {
            grid_dte_recep_sii_pack_ajax_alert($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage'])
         {
            grid_dte_recep_sii_pack_ajax_message($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxJavascript']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['ajaxJavascript'])
         {
            grid_dte_recep_sii_pack_ajax_javascript($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['redir']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['redir']))
         {
            grid_dte_recep_sii_pack_ajax_redir($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['redirExit']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['redirExit']))
         {
            grid_dte_recep_sii_pack_ajax_redir_exit($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['blockDisplay']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['blockDisplay']))
         {
            grid_dte_recep_sii_pack_ajax_block_display($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['fieldDisplay']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['fieldDisplay']))
         {
            grid_dte_recep_sii_pack_ajax_field_display($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['buttonDisplay']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['buttonDisplay']))
         {
            $contr_grid_dte_recep_sii->NM_ajax_info['buttonDisplay'] = $contr_grid_dte_recep_sii->nmgp_botoes;
            grid_dte_recep_sii_pack_ajax_button_display($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['fieldLabel']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['fieldLabel']))
         {
            grid_dte_recep_sii_pack_ajax_field_label($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['readOnly']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['readOnly']))
         {
            grid_dte_recep_sii_pack_ajax_readonly($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['navStatus']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['navStatus']))
         {
            grid_dte_recep_sii_pack_ajax_nav_status($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['navSummary']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['navSummary']))
         {
            grid_dte_recep_sii_pack_ajax_nav_Summary($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['navPage']))
         {
            grid_dte_recep_sii_pack_ajax_navPage($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['btnVars']) && !empty($contr_grid_dte_recep_sii->NM_ajax_info['btnVars']))
         {
            grid_dte_recep_sii_pack_ajax_btn_vars($aResp);
         }
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['quickSearchRes']) && $contr_grid_dte_recep_sii->NM_ajax_info['quickSearchRes'])
         {
            $aResp['quickSearchRes'] = 'Y';
         }
         else
         {
            $aResp['quickSearchRes'] = 'N';
         }
         $aResp['htmOutput'] = '';
    
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['param']['buffer_output']) && $contr_grid_dte_recep_sii->NM_ajax_info['param']['buffer_output'])
         {
            $aResp['htmOutput'] = ob_get_contents();
            if (false === $aResp['htmOutput'])
            {
               $aResp['htmOutput'] = '';
            }
            else
            {
               $aResp['htmOutput'] = grid_dte_recep_sii_pack_protect_string(NM_charset_to_utf8($aResp['htmOutput']));
               ob_end_clean();
            }
         }
      }
      if (is_array($aResp))
      {
          $oJson = new Services_JSON();
          echo "var res = " . trim(sajax_get_js_repr($oJson->encode($aResp))) . "; res;";
      }
      else
      {
          echo "var res = " . trim(sajax_get_js_repr($aResp)) . "; res;";
      }
      exit;
   } // grid_dte_recep_sii_pack_ajax_response

   function grid_dte_recep_sii_pack_calendar_reload(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['calendarReload'] = 'OK';
   } // grid_dte_recep_sii_pack_calendar_reload

   function grid_dte_recep_sii_pack_ajax_errors(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['errList'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['errList'] as $sField => $aMsg)
      {
         if ('geral_grid_dte_recep_sii' == $sField)
         {
             $aMsg = grid_dte_recep_sii_pack_ajax_remove_erros($aMsg);
         }
         foreach ($aMsg as $sMsg)
         {
            $iNumLinha = (isset($contr_grid_dte_recep_sii->NM_ajax_info['param']['nmgp_refresh_row']) && 'geral_grid_dte_recep_sii' != $sField)
                       ? $contr_grid_dte_recep_sii->NM_ajax_info['param']['nmgp_refresh_row'] : "";
            $aResp['errList'][] = array('fldName'  => $sField,
                                        'msgText'  => grid_dte_recep_sii_pack_protect_string(NM_charset_to_utf8($sMsg)),
                                        'numLinha' => $iNumLinha);
         }
      }
   } // grid_dte_recep_sii_pack_ajax_errors

   function grid_dte_recep_sii_pack_ajax_remove_erros($aErrors)
   {
       $aNewErrors = array();
       if (!empty($aErrors))
       {
           $sErrorMsgs = str_replace(array('<br />', '<br>', '<BR />'), array('<BR>', '<BR>', '<BR>'), implode('<br />', $aErrors));
           $aErrorMsgs = explode('<BR>', $sErrorMsgs);
           foreach ($aErrorMsgs as $sErrorMsg)
           {
               $sErrorMsg = trim($sErrorMsg);
               if ('' != $sErrorMsg && !in_array($sErrorMsg, $aNewErrors))
               {
                   $aNewErrors[] = $sErrorMsg;
               }
           }
       }
       return $aNewErrors;
   } // grid_dte_recep_sii_pack_ajax_remove_erros

   function grid_dte_recep_sii_pack_ajax_ok(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $iNumLinha = (isset($contr_grid_dte_recep_sii->NM_ajax_info['param']['nmgp_refresh_row']))
                 ? $contr_grid_dte_recep_sii->NM_ajax_info['param']['nmgp_refresh_row'] : "";
      $aResp['msgDisplay'] = array('msgText'  => grid_dte_recep_sii_pack_protect_string($contr_grid_dte_recep_sii->NM_ajax_info['msgDisplay']),
                                   'numLinha' => $iNumLinha);
   } // grid_dte_recep_sii_pack_ajax_ok

   function grid_dte_recep_sii_pack_ajax_set_fields(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $iNumLinha = (isset($contr_grid_dte_recep_sii->NM_ajax_info['param']['nmgp_refresh_row']))
                 ? $contr_grid_dte_recep_sii->NM_ajax_info['param']['nmgp_refresh_row'] : "";
      if ('' != $contr_grid_dte_recep_sii->NM_ajax_info['rsSize'])
      {
         $aResp['rsSize'] = $contr_grid_dte_recep_sii->NM_ajax_info['rsSize'];
      }
      $aResp['fldList'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['fldList'] as $sField => $aData)
      {
         $aField = array();
         if (isset($aData['colNum']))
         {
            $aField['colNum'] = $aData['colNum'];
         }
         if (isset($aData['row']))
         {
            $aField['row'] = $aData['row'];
         }
         if (isset($aData['imgFile']))
         {
            $aField['imgFile'] = grid_dte_recep_sii_pack_protect_string($aData['imgFile']);
         }
         if (isset($aData['imgOrig']))
         {
            $aField['imgOrig'] = grid_dte_recep_sii_pack_protect_string($aData['imgOrig']);
         }
         if (isset($aData['imgLink']))
         {
            $aField['imgLink'] = grid_dte_recep_sii_pack_protect_string($aData['imgLink']);
         }
         if (isset($aData['keepImg']))
         {
            $aField['keepImg'] = $aData['keepImg'];
         }
         if (isset($aData['docLink']))
         {
            $aField['docLink'] = grid_dte_recep_sii_pack_protect_string($aData['docLink']);
         }
         if (isset($aData['docIcon']))
         {
            $aField['docIcon'] = grid_dte_recep_sii_pack_protect_string($aData['docIcon']);
         }
         if (isset($aData['keyVal']))
         {
            $aField['keyVal'] = $aData['keyVal'];
         }
         if (isset($aData['optComp']))
         {
            $aField['optComp'] = $aData['optComp'];
         }
         if (isset($aData['optClass']))
         {
            $aField['optClass'] = $aData['optClass'];
         }
         if (isset($aData['optMulti']))
         {
            $aField['optMulti'] = $aData['optMulti'];
         }
         if (isset($aData['lookupCons']))
         {
            $aField['lookupCons'] = $aData['lookupCons'];
         }
         if (isset($aData['imgHtml']))
         {
            $aField['imgHtml'] = grid_dte_recep_sii_pack_protect_string($aData['imgHtml']);
         }
         if (isset($aData['mulHtml']))
         {
            $aField['mulHtml'] = grid_dte_recep_sii_pack_protect_string($aData['mulHtml']);
         }
         if (isset($aData['updInnerHtml']))
         {
            $aField['updInnerHtml'] = $aData['updInnerHtml'];
         }
         if (isset($aData['htmComp']))
         {
            $aField['htmComp'] = str_replace("'", '__AS__', str_replace('"', '__AD__', $aData['htmComp']));
         }
         $aField['fldName']  = $sField;
         $aField['fldType']  = $aData['type'];
         $aField['numLinha'] = $iNumLinha;
         $aField['valList']  = array();
         foreach ($aData['valList'] as $iIndex => $sValue)
         {
            $aValue = array();
            if (isset($aData['labList'][$iIndex]))
            {
               $aValue['label'] = grid_dte_recep_sii_pack_protect_string($aData['labList'][$iIndex]);
            }
            $aValue['value']     = ('_autocomp' != substr($sField, -9)) ? grid_dte_recep_sii_pack_protect_string($sValue) : $sValue;
            $aField['valList'][] = $aValue;
         }
         foreach ($aField['valList'] as $iIndex => $aFieldData)
         {
             if ("null" == $aFieldData['value'])
             {
                 $aField['valList'][$iIndex]['value'] = '';
             }
         }
         if (isset($aData['optList']) && false !== $aData['optList'])
         {
            if (is_array($aData['optList']))
            {
               $aField['optList'] = array();
               foreach ($aData['optList'] as $aOptList)
               {
                  foreach ($aOptList as $sValue => $sLabel)
                  {
                     $sOpt = ($sValue !== $sLabel) ? $sValue : $sLabel;
                     $aField['optList'][] = array('value' => grid_dte_recep_sii_pack_protect_string($sOpt),
                                                  'label' => grid_dte_recep_sii_pack_protect_string($sLabel));
                  }
               }
            }
            else
            {
               $aField['optList'] = $aData['optList'];
            }
         }
         $aResp['fldList'][] = $aField;
      }
   } // grid_dte_recep_sii_pack_ajax_set_fields

   function grid_dte_recep_sii_pack_ajax_redir(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aInfo              = array('metodo', 'action', 'target', 'nmgp_parms', 'nmgp_outra_jan', 'nmgp_url_saida', 'script_case_init', 'script_case_session', 'h_modal', 'w_modal');
      $aResp['redirInfo'] = array();
      foreach ($aInfo as $sTag)
      {
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['redir'][$sTag]))
         {
            $aResp['redirInfo'][$sTag] = $contr_grid_dte_recep_sii->NM_ajax_info['redir'][$sTag];
         }
      }
   } // grid_dte_recep_sii_pack_ajax_redir

   function grid_dte_recep_sii_pack_ajax_redir_exit(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aInfo                  = array('metodo', 'action', 'target', 'nmgp_parms', 'nmgp_outra_jan', 'nmgp_url_saida', 'script_case_init', 'script_case_session');
      $aResp['redirExitInfo'] = array();
      foreach ($aInfo as $sTag)
      {
         if (isset($contr_grid_dte_recep_sii->NM_ajax_info['redirExit'][$sTag]))
         {
            $aResp['redirExitInfo'][$sTag] = $contr_grid_dte_recep_sii->NM_ajax_info['redirExit'][$sTag];
         }
      }
   } // grid_dte_recep_sii_pack_ajax_redir_exit

   function grid_dte_recep_sii_pack_master_value(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['masterValue'] as $sIndex => $sValue)
      {
         $aResp['masterValue'][] = array('index' => $sIndex,
                                         'value' => $sValue);
      }
   } // grid_dte_recep_sii_pack_master_value

   function grid_dte_recep_sii_pack_ajax_alert(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['ajaxAlert'] = array('message' => $contr_grid_dte_recep_sii->NM_ajax_info['ajaxAlert']['message']);
   } // grid_dte_recep_sii_pack_ajax_alert

   function grid_dte_recep_sii_pack_ajax_message(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['ajaxMessage'] = array('message'      => grid_dte_recep_sii_pack_protect_string($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['message']),
                                    'title'        => grid_dte_recep_sii_pack_protect_string($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['title']),
                                    'modal'        => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['modal'])        ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['modal']        : 'N',
                                    'timeout'      => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['timeout'])      ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['timeout']      : '',
                                    'button'       => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['button'])       ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['button']       : '',
                                    'button_label' => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['button_label']) ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['button_label'] : 'Ok',
                                    'top'          => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['top'])          ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['top']          : '',
                                    'left'         => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['left'])         ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['left']         : '',
                                    'width'        => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['width'])        ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['width']        : '',
                                    'height'       => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['height'])       ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['height']       : '',
                                    'redir'        => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['redir'])        ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['redir']        : '',
                                    'show_close'   => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['show_close'])   ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['show_close']   : 'Y',
                                    'body_icon'    => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['body_icon'])    ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['body_icon']    : 'Y',
                                    'redir_target' => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['redir_target']) ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['redir_target'] : '',
                                    'redir_par'    => isset($contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['redir_par'])    ? $contr_grid_dte_recep_sii->NM_ajax_info['ajaxMessage']['redir_par']    : '');
   } // grid_dte_recep_sii_pack_ajax_message

   function grid_dte_recep_sii_pack_ajax_javascript(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['ajaxJavascript'] as $aJsFunc)
      {
         $aResp['ajaxJavascript'][] = $aJsFunc;
      }
   } // grid_dte_recep_sii_pack_ajax_javascript

   function grid_dte_recep_sii_pack_ajax_block_display(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['blockDisplay'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['blockDisplay'] as $sBlockName => $sBlockStatus)
      {
        $aResp['blockDisplay'][] = array($sBlockName, $sBlockStatus);
      }
   } // grid_dte_recep_sii_pack_ajax_block_display

   function grid_dte_recep_sii_pack_ajax_field_display(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['fieldDisplay'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['fieldDisplay'] as $sFieldName => $sFieldStatus)
      {
        $aResp['fieldDisplay'][] = array($sFieldName, $sFieldStatus);
      }
   } // grid_dte_recep_sii_pack_ajax_field_display

   function grid_dte_recep_sii_pack_ajax_button_display(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['buttonDisplay'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['buttonDisplay'] as $sButtonName => $sButtonStatus)
      {
        $aResp['buttonDisplay'][] = array($sButtonName, $sButtonStatus);
      }
   } // grid_dte_recep_sii_pack_ajax_button_display

   function grid_dte_recep_sii_pack_ajax_field_label(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['fieldLabel'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['fieldLabel'] as $sFieldName => $sFieldLabel)
      {
        $aResp['fieldLabel'][] = array($sFieldName, grid_dte_recep_sii_pack_protect_string($sFieldLabel));
      }
   } // grid_dte_recep_sii_pack_ajax_field_label

   function grid_dte_recep_sii_pack_ajax_readonly(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['readOnly'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['readOnly'] as $sFieldName => $sFieldStatus)
      {
        $aResp['readOnly'][] = array($sFieldName, $sFieldStatus);
      }
   } // grid_dte_recep_sii_pack_ajax_readonly

   function grid_dte_recep_sii_pack_ajax_nav_status(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['navStatus'] = array();
      if (isset($contr_grid_dte_recep_sii->NM_ajax_info['navStatus']['ret']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['navStatus']['ret'])
      {
         $aResp['navStatus']['ret'] = $contr_grid_dte_recep_sii->NM_ajax_info['navStatus']['ret'];
      }
      if (isset($contr_grid_dte_recep_sii->NM_ajax_info['navStatus']['ava']) && '' != $contr_grid_dte_recep_sii->NM_ajax_info['navStatus']['ava'])
      {
         $aResp['navStatus']['ava'] = $contr_grid_dte_recep_sii->NM_ajax_info['navStatus']['ava'];
      }
   } // grid_dte_recep_sii_pack_ajax_nav_status

   function grid_dte_recep_sii_pack_ajax_nav_Summary(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['navSummary'] = array();
      $aResp['navSummary']['reg_ini'] = $contr_grid_dte_recep_sii->NM_ajax_info['navSummary']['reg_ini'];
      $aResp['navSummary']['reg_qtd'] = $contr_grid_dte_recep_sii->NM_ajax_info['navSummary']['reg_qtd'];
      $aResp['navSummary']['reg_tot'] = $contr_grid_dte_recep_sii->NM_ajax_info['navSummary']['reg_tot'];
   } // grid_dte_recep_sii_pack_ajax_nav_Summary

   function grid_dte_recep_sii_pack_ajax_navPage(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['navPage'] = $contr_grid_dte_recep_sii->NM_ajax_info['navPage'];
   } // grid_dte_recep_sii_pack_ajax_navPage


   function grid_dte_recep_sii_pack_ajax_btn_vars(&$aResp)
   {
      global $contr_grid_dte_recep_sii;
      $aResp['btnVars'] = array();
      foreach ($contr_grid_dte_recep_sii->NM_ajax_info['btnVars'] as $sBtnName => $sBtnValue)
      {
        $aResp['btnVars'][] = array($sBtnName, grid_dte_recep_sii_pack_protect_string($sBtnValue));
      }
   } // grid_dte_recep_sii_pack_ajax_btn_vars

   function grid_dte_recep_sii_pack_protect_string($sString)
   {
      $sString = (string) $sString;

      if (!empty($sString))
      {
         if (function_exists('NM_is_utf8') && NM_is_utf8($sString))
         {
             return $sString;
         }
         else
         {
             return htmlentities($sString);
         }
      }
      elseif ('0' === $sString || 0 === $sString)
      {
         return '0';
      }
      else
      {
         return '';
      }
   } // grid_dte_recep_sii_pack_protect_string
?>
