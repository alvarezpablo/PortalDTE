<?php
class rep_est_dte_lookup
{
//  
   function lookup_codi_empr(&$conteudo , $codi_empr) 
   {   
      static $save_conteudo = "" ; 
      static $save_conteudo1 = "" ; 
      $tst_cache = $codi_empr; 
      if ($tst_cache === $save_conteudo && $conteudo != "") 
      { 
          $conteudo = $save_conteudo1 ; 
          return ; 
      } 
      $save_conteudo = $tst_cache ; 
      if (trim($codi_empr) === "")
      { 
          $conteudo = "";
          $save_conteudo  = ""; 
          $save_conteudo1 = ""; 
          return ; 
      } 
      $nm_comando = "select rs_empr from \"public\".empresa where codi_empr = $codi_empr order by rs_empr" ; 
      $conteudo = "" ; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      if ($rx = $this->Db->Execute($nm_comando)) 
      { 
          if (isset($rx->fields[0]))  
          { 
              $conteudo = trim($rx->fields[0]); 
          } 
          $save_conteudo1 = $conteudo ; 
          $rx->Close(); 
      } 
      elseif ($GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
          exit; 
      } 
      if ($conteudo === "") 
      { 
          $conteudo = "";
          $save_conteudo1 = $conteudo ; 
      } 
   }  
//  
   function lookup_tipo_docu(&$conteudo , $tipo_docu) 
   {   
      static $save_conteudo = "" ; 
      static $save_conteudo1 = "" ; 
      $tst_cache = $tipo_docu; 
      if ($tst_cache === $save_conteudo && $conteudo != "") 
      { 
          $conteudo = $save_conteudo1 ; 
          return ; 
      } 
      $save_conteudo = $tst_cache ; 
      if (trim($tipo_docu) === "")
      { 
          $conteudo = "";
          $save_conteudo  = ""; 
          $save_conteudo1 = ""; 
          return ; 
      } 
      $nm_comando = "select desc_tipo_docu from \"public\".dte_tipo where tipo_docu = $tipo_docu order by desc_tipo_docu" ; 
      $conteudo = "" ; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando; 
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      if ($rx = $this->Db->Execute($nm_comando)) 
      { 
          if (isset($rx->fields[0]))  
          { 
              $conteudo = trim($rx->fields[0]); 
          } 
          $save_conteudo1 = $conteudo ; 
          $rx->Close(); 
      } 
      elseif ($GLOBALS["NM_ERRO_IBASE"] != 1)  
      { 
          $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
          exit; 
      } 
      if ($conteudo === "") 
      { 
          $conteudo = "";
          $save_conteudo1 = $conteudo ; 
      } 
   }  
//  
   function lookup_est_xdte(&$est_xdte) 
   {
      $conteudo = "" ; 
      if ($est_xdte == "1")
      { 
          $conteudo = "Cargado";
      } 
      if ($est_xdte == "3")
      { 
          $conteudo = "Error";
      } 
      if ($est_xdte == "5")
      { 
          $conteudo = "Empaquetado ";
      } 
      if ($est_xdte == "13")
      { 
          $conteudo = "Enviado a SII";
      } 
      if ($est_xdte == "29")
      { 
          $conteudo = "Aceptado SII";
      } 
      if ($est_xdte == "45")
      { 
          $conteudo = "Aceptado con Reparos";
      } 
      if ($est_xdte == "77")
      { 
          $conteudo = "Rechazado SII";
      } 
      if ($est_xdte == "157")
      { 
          $conteudo = "Enviado a Clientes ";
      } 
      if ($est_xdte == "173")
      { 
          $conteudo = "Enviado a Cliente DTE con Reparos";
      } 
      if ($est_xdte == "413")
      { 
          $conteudo = "Aceptado Cliente";
      } 
      if ($est_xdte == "429")
      { 
          $conteudo = "Aceptado Cliente DTE con Reparos";
      } 
      if (!empty($conteudo)) 
      { 
          $est_xdte = $conteudo; 
      } 
   }  
}
?>
