<?php

class grid_public_v_dte_total
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;

   var $nm_data;

   //----- 
   function grid_public_v_dte_total($sc_page)
   {
      $this->sc_page = $sc_page;
      $this->nm_data = new nm_data("es");
      if (isset($_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']) && !empty($_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']))
      { 
          $this->tipo_docu = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['tipo_docu']; 
          $tmp_pos = strpos($this->tipo_docu, "##@@");
          if ($tmp_pos !== false)
          {
              $this->tipo_docu = substr($this->tipo_docu, 0, $tmp_pos);
          }
          $this->folio_dte = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['folio_dte']; 
          $tmp_pos = strpos($this->folio_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->folio_dte = substr($this->folio_dte, 0, $tmp_pos);
          }
          $this->fec_emi_dte = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte']; 
          $tmp_pos = strpos($this->fec_emi_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fec_emi_dte = substr($this->fec_emi_dte, 0, $tmp_pos);
          }
          $fec_emi_dte_2 = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2']; 
          $this->fec_emi_dte_2 = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2']; 
          $this->fech_carg = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg']; 
          $tmp_pos = strpos($this->fech_carg, "##@@");
          if ($tmp_pos !== false)
          {
              $this->fech_carg = substr($this->fech_carg, 0, $tmp_pos);
          }
          $fech_carg_2 = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2']; 
          $this->fech_carg_2 = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2']; 
          $this->est_xdte = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['est_xdte']; 
          $tmp_pos = strpos($this->est_xdte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->est_xdte = substr($this->est_xdte, 0, $tmp_pos);
          }
          $this->rut_rec_dte = $_SESSION['sc_session'][$this->sc_page]['grid_public_v_dte']['campos_busca']['rut_rec_dte']; 
          $tmp_pos = strpos($this->rut_rec_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $this->rut_rec_dte = substr($this->rut_rec_dte, 0, $tmp_pos);
          }
      } 
   }

   //---- 
   function quebra_geral()
   {
      global $nada, $nm_lang , $tipo_docu;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_total_geral'] == "OK") 
      { 
          return; 
      } 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'] = array() ;  
      $nm_comando = "select count(*) from " . $this->Ini->nm_tabela . " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['where_pesq']; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nm_comando;
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = '';
      if (!$rt = $this->Db->Execute($nm_comando)) 
      { 
         $this->Erro->mensagem (__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
         exit ; 
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'][0] = "" . $this->Ini->Nm_lang['lang_msgs_totl'] . ""; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'][1] = $rt->fields[0] ; 
      $rt->Close(); 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_total_geral'] = "OK";
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

}

?>
