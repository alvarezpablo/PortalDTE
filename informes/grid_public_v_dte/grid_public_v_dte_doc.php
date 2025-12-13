<?php
   session_cache_limiter("");
   session_start();
   if (!empty($_GET))
   {
       foreach ($_GET as $nmgp_var => $nmgp_val)
       {
            $$nmgp_var = $nmgp_val;
       }
   }
   $nm_nome_doc = str_replace("**Ecom**", "&", $nm_nome_doc);
   $nm_nome_doc = str_replace("**Jvel**", "#", $nm_nome_doc);
   $nm_nome_doc = str_replace("**Plus**", "+", $nm_nome_doc);
   if ($nm_cod_doc == "documento_db")
   {
       $NM_dir_atual = getcwd();
       if (empty($NM_dir_atual))
       {
          $str_path_sys    = (isset($_SERVER['SCRIPT_FILENAME'])) ? $_SERVER['SCRIPT_FILENAME'] : $_SERVER['ORIG_PATH_TRANSLATED'];
          $str_path_sys    = str_replace("\\", '/', $str_path_sys);
          $str_path_sys    = str_replace('//', '/', $str_path_sys);
       }
       else
       {
           $sc_nm_arquivo = explode("/", $_SERVER['PHP_SELF']);
           $str_path_sys  = str_replace("\\", "/", str_replace("\\\\", "\\", getcwd())) . "/" . $sc_nm_arquivo[count($sc_nm_arquivo)-1];
       }
       $str_path_web  = $_SERVER['PHP_SELF'];
       $str_path_web  = str_replace("\\", '/', $str_path_web);
       $str_path_web  = str_replace('//', '/', $str_path_web);
       $root          = substr($str_path_sys, 0, -1 * strlen($str_path_web));
       $trab_doc      = $root . $_SESSION['scriptcase']['grid_public_v_dte']['glo_nm_path_imag_temp'] . "/sc_" . $nm_nome_doc;
       $nm_nome_doc   = substr($nm_nome_doc, strpos($nm_nome_doc, "__NM__") + 6);
   }
   else
   {
       $path_raiz = $_SESSION['sc_session'][$script_case_init][$nm_cod_apl]['path_doc'];
       $sub_path  = $_SESSION['sc_session'][$script_case_init][$nm_cod_apl]['sub_dir'][$nm_cod_doc];
       $trab_doc = $path_raiz . $sub_path . "/" . $nm_nome_doc;
       $trab_doc = str_replace("\\", "/", $trab_doc);
       $trab_doc = str_replace("//", "/", $trab_doc);
   }
   if (is_file($trab_doc))  
   { 
       header("Content-type: application/force-download");
       $sProtectedFilename = str_replace(array(' ', "'", '!', ',', '-', '+'), array('__SC_SPACE__', '__SC_QUOTES__', '__SC_EXCLAMATION__', '__SC_COMMA__', '__SC_MINUS__', '__SC_PLUS__'), $nm_nome_doc);
       $sProtectedFilename = urlencode($sProtectedFilename);
       $sProtectedFilename = str_replace(array('__SC_SPACE__', '__SC_QUOTES__', '__SC_EXCLAMATION__', '__SC_COMMA__', '__SC_MINUS__', '__SC_PLUS__'), array(' ', "'", '!', ',', '-', '+'), $sProtectedFilename);
       if (isset($_SERVER['HTTP_USER_AGENT']) && false !== strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'chrome'))
       {
           header("Content-Disposition: attachment; filename=\"" . $sProtectedFilename . "\"");
       }
       elseif (isset($_SERVER['HTTP_USER_AGENT']) && false !== strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'applewebkit'))
       {
           header("Content-Disposition: attachment; filename=\"" . $nm_nome_doc . "\"");
       }
       elseif (function_exists('NM_utf8_urldecode') && $nm_nome_doc != NM_utf8_urldecode($nm_nome_doc))
       {
           header("Content-Disposition: attachment; filename=\"" . $nm_nome_doc . "\" filename*=UTF-8''" . $sProtectedFilename);
       }
       else
       {
           header("Content-Disposition: attachment; filename=\"" . $sProtectedFilename . "\"");
       }
       readfile($trab_doc);
   } 
   else 
   { 
       $STR_lang    = (isset($_SESSION['scriptcase']['str_lang']) && !empty($_SESSION['scriptcase']['str_lang'])) ? $_SESSION['scriptcase']['str_lang'] : "es";
       $NM_arq_lang = "../_lib/lang/" . $STR_lang . ".lang.php";
       if (!function_exists("NM_is_utf8"))
       {
           include_once("grid_public_v_dte_nmutf8.php");
       }
       $Nm_lang = array();
       if (is_file($NM_arq_lang))
       {
           $Lixo = file($NM_arq_lang);
           foreach ($Lixo as $Cada_lin) 
           {
               if (strpos($Cada_lin, "array()") === false && (trim($Cada_lin) != "<?php")  && (trim($Cada_lin) != "?" . ">"))
               {
                   eval (str_replace("\$this->", "\$", $Cada_lin));
               }
           }
       }
       $_SESSION['scriptcase']['charset']  = (isset($Nm_lang['Nm_charset']) && !empty($Nm_lang['Nm_charset'])) ? $Nm_lang['Nm_charset'] : "ISO-8859-15";
       foreach ($Nm_lang as $ind => $dados)
       {
          if ($_SESSION['scriptcase']['charset'] != "UTF-8" && NM_is_utf8($dados))
          {
               $Nm_lang[$ind] = mb_convert_encoding($dados, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
       }
       echo $Nm_lang['lang_errm_fnfd'] . ": " . $nm_nome_doc;
   } 
   exit;
?>
