<?php

class grid_public_v_dte_grafico
{
   var $Db;
   var $Ini;
   var $Erro;
   var $Lookup;

   var $nm_data;
   var $total;
   var $array_datay_geral;
   var $array_label_geral;
   var $array_datay;
   var $array_label;
   var $campo;
   var $campo_val;
   var $comando;
   var $label;
   var $list_titulo;
   var $max_size_datay;
   var $max_size_label;
   var $total_datay;
   var $nivel;
   var $titulo;
   var $Decimais;
   var $sc_proc_grid; 
   var $sc_graf_sint = false;
   var $graf_cor_fundo;
   var $graf_cor_margens;
   var $graf_cor_label;
   var $graf_cor_valores;
   var $graf_tipo_marcas;
   var $NM_tit_val;
   var $NM_ind_val;

   //---- 
   function grid_public_v_dte_grafico()
   {
      $this->nm_data = new nm_data("es");
   }

   //---- 
   function monta_grafico($chart_key = '')
   {
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_opc_atual'] == 1)
       {
           $this->sc_graf_sint = true;
       }

       $b_export = false;
       if (isset($_GET['flash_graf']) && 'chart' == $_GET['flash_graf'])
       {
           $b_export = true;
           $chart_key = key($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pivot_charts']);
       }
       elseif ('' == $chart_key)
       {
           $chart_key = isset($_POST['nmgp_parms']) ? $_POST['nmgp_parms'] : '';
       }

       if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pivot_charts'][$chart_key]))
       {
           $chart_data = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pivot_charts'][$chart_key];

           $arr_param = array(
               'type'        => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_tipo'],
               'width'       => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_larg'],
               'height'      => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_alt'],
               'barra_orien' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_orien'],
               'barra_forma' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_forma'],
               'barra_dimen' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_dimen'],
               'barra_sobre' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_sobre'],
               'barra_empil' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_empil'],
               'barra_inver' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_inver'],
               'barra_agrup' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_agrup'],
               'barra_funil' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_barra_funil'],
               'pizza_forma' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_pizza_forma'],
               'pizza_dimen' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_pizza_dimen'],
               'pizza_orden' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_pizza_orden'],
               'pizza_explo' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_pizza_explo'],
               'pizza_valor' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_pizza_valor'],
               'linha_forma' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linha_forma'],
               'linha_inver' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linha_inver'],
               'linha_agrup' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linha_agrup'],
               'area_forma'  => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_area_forma'],
               'area_empil'  => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_area_empil'],
               'area_inver'  => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_area_inver'],
               'area_agrup'  => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_area_agrup'],
               'marca_inver' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_marca_inver'],
               'marca_agrup' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_marca_agrup'],
               'gauge_forma' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_gauge_forma'],
               'radar_forma' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_radar_forma'],
               'radar_empil' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_radar_empil'],
               'polar_forma' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_polar_forma'],
               'funil_dimen' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_funil_dimen'],
               'funil_inver' => $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_funil_inver'],
               'tit_datay'   => $chart_data['label_y'],
               'tit_label'   => $chart_data['label_x'],
               'tit_chart'   => $chart_data['title'],
               'export'      => $b_export ? 'Y' : 'N',
           );
           if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_full']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_full'])
           {
               $mode = 'full';
               unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_full']);
           }
           elseif (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_bot'])
           {
               $mode = 'full';
           }
           elseif ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_first'])
           {
               $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_first'] = false;
               $mode = array('js', 'chart');
           }
           else
           {
               $mode = 'chart';
           }
           $this->arr_param = $arr_param;
           $this->grafico_flash($arr_param, $this->grafico_dados($chart_data, $arr_param['export']), $mode);
           if ((!isset($_GET['flash_graf']) || 'chart' != $_GET['flash_graf']) && (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_bot']))
           {
               exit;
           }
       }
       elseif (isset($_GET['flash_graf']) && 'chart' == $_GET['flash_graf'])
       {
?>
<html>
<body>
<?php
           $this->grafico_flash_form();
?>
<script type="text/javascript" language="javascript">
  document.flashRedir.submit();
 </script>
</body>
</html>
<?php
       }
   }

   //---- 
   function inicializa_vars()
   {
      global 
             $nivel_quebra, $nm_lang, $campo, $campo_val;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_opc_atual'] == 1)
      {
         $this->sc_graf_sint = true;
      }
      //---- 
      $this->array_decimais = array();
      $this->NM_tit_val[0]     = "" .  $this->Ini->Nm_lang['lang_othr_rows'] . "";
      $this->NM_ind_val[0]     = 1;
      $this->array_decimais[0] = 0;
      $this->campo     = (isset($campo))        ? $campo        : 0;
      $this->nivel     = (isset($nivel_quebra)) ? $nivel_quebra : 0;
      $this->campo_val = (isset($campo_val))    ? $campo_val    : 1;
      //---- 
      //---- 
      $ind_tit = $this->campo_val;
      if ($this->campo > 0)
      {
          foreach ($this->NM_ind_val as $i => $seq)
          {
              if ($ind_tit == $seq)
              {
                  $ind_tit = $i;
                  break;
              }
          }
      }
      $this->list_titulo = (isset($this->NM_tit_val[$ind_tit]))  ? $this->NM_tit_val[$ind_tit]  : "";
      $this->Decimais    = (isset($this->array_decimais[$ind_tit])) ? $this->array_decimais[$ind_tit] : 2;
      $this->titulo      = $this->list_titulo;
      //---- Label
      $this->label    = array();
   }

   //---- 
   function prep_modulos($modulo)
   {
      $this->$modulo->Ini    = $this->Ini;
      $this->$modulo->Db     = $this->Db;
      $this->$modulo->Erro   = $this->Erro;
      $this->$modulo->Lookup = $this->Lookup;
   }

   //---- 
   function monta_arrays()
   {
      $this->array_label = array();
      $this->array_datay = array();
      if ($this->campo > 0)
      {
          $this->sc_graf_sint = true;
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_total']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_total'] as $label => $valor)
          {
              $this->array_label[] = $valor[2];
              if ($this->campo == 0 && $this->nivel == 0)
              {
                  if ($this->sc_graf_sint)
                  {
                      $this->array_datay[" "][] = $valor[$this->campo_val];
                  }
              }
          }
          if (!$this->sc_graf_sint)
          {
              foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linhas'] as $cada_elemento)
              {
                  if (substr($cada_elemento[0], 0, 1) == 1)
                  {
                      $ind_val = $this->NM_ind_val[$this->campo_val];
                      $legenda = substr($cada_elemento[0], 1);
                      foreach ($this->array_label as $ind => $lixo)
                      {
                          if (isset($cada_elemento[$ind + 1]))
                          {
                              $this->array_datay[$legenda][] = $cada_elemento[$ind + 1][$ind_val];
                          }
                          else
                          {
                              $this->array_datay[$legenda][] = 0;
                          }
                      }
                  }
              }
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linhas']))
      {
          if ($this->campo > 0)
          {
              $lab_quebra    = substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linhas'][$this->campo][0], 1);
              $lab_quebra    = str_replace("&nbsp;", "", $lab_quebra);
              $this->titulo .= " - " . $this->label[$this->nivel] . " = " . $lab_quebra;
          }
          if ($this->campo > 0)
          {
              foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linhas'][$this->campo] as $ind => $valor)
              {
                  if ($ind > 0)
                  {
                      $this->array_datay[" "][$ind - 1] = $valor[$this->campo_val];
                  }
              }
              for ($i = 0; $i < count($this->array_label); $i++)
              {
                   if (!isset($this->array_datay[" "][$i]))
                   {
                       $this->array_datay[" "][$i] = 0;
                   }
              }
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['del_graph_col']))
      {
          $trab_graf = $this->array_label;
          $this->array_label = array();
          foreach ($trab_graf as $ind => $resto)
          {
              $tst_ind = $ind + 1;
              if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['del_graph_col'][$tst_ind]))
              {
                  $this->array_label[] = $resto;
              }
          }
          $trab_graf = $this->array_datay;
          $this->array_datay = array();
          foreach ($trab_graf as $legenda => $dados)
          {
              ksort($dados);
              foreach ($dados as $ind => $resto)
              {
                  $tst_ind = $ind + 1;
                  if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['del_graph_col'][$tst_ind]))
                  {
                      $this->array_datay[$legenda][] = $resto;
                  }
              }
          }
      }
      $this->max_size_label = 0;
      for ($i = 0; $i < sizeof($this->array_label); $i++)
      {
         $size_label           = strlen("" . $this->array_label[$i]);
         $this->max_size_label = ($this->max_size_label < $size_label) ? $size_label : $this->max_size_label;
      }
      $this->max_size_datay = 0;
      $this->total_datay = 0;
      foreach ($this->array_datay as $legenda => $dadosY)
      {
          for ($i = 0; $i < sizeof($dadosY); $i++)
          {
             $size_datay           = strlen("" . $dadosY[$i]);
             $this->max_size_datay = ($this->max_size_datay < $size_datay) ? $size_datay : $this->max_size_datay;
             $this->total_datay   += $dadosY[$i];
          }
      }
   }

   function grafico_flash($arr_param, $arr_charts, $html_par = 'full')
   {
      global $nm_saida, $nm_retorno_graf;

         $this->orderCharts($arr_charts, $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_order']);

         $arr_series     = $arr_charts[0];

         $dp_settings    = array();
         $y_scale        = array();
         $shape_type     = '';
         $chart_series   = 'bar_series';
         $chart_series_a = array();
         $series_explode = '';

         $tipo           = $arr_param['type'];
         $width          = $arr_param['width'];
         $height         = $arr_param['height'];
         $tit_datay      = $arr_param['tit_datay'];
         $tit_label      = $arr_param['tit_label'];
         $tit_graf       = $arr_param['tit_chart'];
         $export         = $arr_param['export'];

         $host           = (isset($arr_param['host'])   && !empty($arr_param['host']))   ? $arr_param['host']   : '';
         $export         = (isset($arr_param['export']) && !empty($arr_param['export'])) ? $arr_param['export'] : 'N';

         $sFileLocal     = $this->Ini->path_imag_temp . '/sc_flashchart_' . md5(microtime() . mt_rand(1, 1000)) . '.xml';
         $ac             = fopen($this->Ini->root . $sFileLocal, 'w');
         fwrite($ac, $this->grafico_flash_xml($arr_param, $arr_series));
         fclose($ac);

         if ('full' == $html_par || 'page' == $html_par || in_array('page', $html_par))
         {
?>
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
<?php
             if (isset($_POST['summary_css']) && '' != $_POST['summary_css'])
             {
?>
<link rel="stylesheet" href="<?php echo NM_encode_input($_POST['summary_css']) ?>" type="text/css" media="screen" />
<?php
             }
?>
<title>
<?php
             if ('UTF-8' != $_SESSION['scriptcase']['charset'])
             {
                 echo mb_convert_encoding($tit_graf, $_SESSION['scriptcase']['charset'], 'UTF-8');
             }
             else
             {
                 echo $tit_graf;
             }
?>
</title>
<?php
         }
         if ('full' == $html_par || 'js' == $html_par || in_array('js', $html_par))
         {
            $this->grafico_flash_js();
         }
         if ('full' == $html_par || 'page' == $html_par || in_array('page', $html_par))
         {
?>
</head>
<body class="scGridPage">
<?php
         }
         if ('full' == $html_par || 'form' == $html_par || in_array('form', $html_par))
         {
            $this->grafico_flash_form();
         }
         if ('full' == $html_par || 'chart' == $html_par || in_array('chart', $html_par))
         {
            $this->grafico_flash_chart($width, $height, $sFileLocal, $export, $arr_charts);
         }
         if ('full' == $html_par || 'page' == $html_par || in_array('chart', $html_par))
         {
            if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
            {
                if (empty($nm_retorno_graf))
                {
                    $nm_retorno_graf = "resumo";
                }
            }
?>
</body>
</html>
<?php
         }
   }

   function grafico_flash_xml($arr_param, $arr_series, $newline = "\r\n")
   {
       $label_settings = array();
       $dp_settings    = array();
       $y_scale        = array();
       $shape_type     = '';
       $chart_series   = 'bar_series';
       $chart_series_a = array();
       $chart_style    = 'bar_style';
       $series_explode = '';
       $value_format   = isset($arr_series[0]['format']) && '' != $arr_series[0]['format'] ? $arr_series[0]['format'] : '{%Value}';
       $value_y_format = str_replace('{%Value}', '{%YValue}', $value_format);
       $axis_label_pos = '';
       $show_as_funnel = false;

       $tipo           = $arr_param['type'];
       $width          = $arr_param['width'];
       $height         = $arr_param['height'];
       $tit_datay      = $arr_param['tit_datay'];
       $tit_label      = $arr_param['tit_label'];
       $tit_graf       = $arr_param['tit_chart'];
       $export         = $arr_param['export'];

       $s_xml  = "<anychart>" . $newline;
       $s_xml .= "  <settings>" . $newline;
       if ('Y' == $export)
       {
           $s_xml .= "    <image_export url=\"grid_public_v_dte_save_flash.class.php\" />" . $newline;
       }
       else
       {
           $s_xml .= "    <animation enabled=\"True\" />" . $newline;
       }
       $s_xml .= "  </settings>" . $newline;

       if ('Gauge' == $tipo)
       {
           if ('Circular' == $arr_param['gauge_forma'])
           {
               $gauge_forma = 'Circular';
               $start_angle = '20';
               $sweep_angle = '320';
           }
           else
           {
               $gauge_forma = 'Auto';
               $start_angle = '90';
               $sweep_angle = '180';
           }

           $iMin = 0;
           $iMax = 0;
           foreach ($arr_series as $arr_serie)
           {
               $label = $arr_serie['label'];
               $datay = $arr_serie['data'];
               foreach ($label as $iIndex => $sLabel)
               {
                   $iMin = min($iMin, $datay[$iIndex]);
                   $iMax = max($iMax, $datay[$iIndex]);
               }
           }
           if (10 > $iMax) $iMax = 10;
           $iMax    = ceil($iMax * 1.1);
           $iMaxInt = ($iMax -$iMin) / 10;
           $iMinInt = ($iMax -$iMin) / 40;

           $s_xml .= "  <gauges>" . $newline;
           $s_xml .= "    <gauge>" . $newline;
           $s_xml .= "      <chart_settings>" . $newline;
           $s_xml .= "        <title>" . $newline;
           $s_xml .= "          <text>" . $tit_graf . "</text>" . $newline;
           $s_xml .= "          <font bold=\"True\" color=\"#4d614e\">" . $newline;
           $s_xml .= "          </font>" . $newline;
           $s_xml .= "        </title>" . $newline;
           $s_xml .= "        <chart_background enabled=\"false\" />" . $newline;
           $s_xml .= "      </chart_settings>" . $newline;
           $s_xml .= "      <circular name=\"main\">" . $newline;
           $s_xml .= "        <styles>" . $newline;
           $s_xml .= "          <needle_pointer_style name=\"anychart_default\" radius=\"100\" base_radius=\"0\" point_thickness=\"0\" thickness=\"12\" point_radius=\"0\">" . $newline;
           $s_xml .= "            <fill color=\"#F0673B\" type=\"Solid\" />" . $newline;
           $s_xml .= "            <border enabled=\"true\" type=\"Solid\" color=\"#494949\" opacity=\"0.3\" />" . $newline;
           $s_xml .= "            <states>" . $newline;
           $s_xml .= "              <hover>" . $newline;
           $s_xml .= "                <border color=\"Gold\" opacity=\"0.7\" />" . $newline;
           $s_xml .= "                <fill color=\"#F0975B\" />" . $newline;
           $s_xml .= "              </hover>" . $newline;
           $s_xml .= "              <selected_normal>" . $newline;
           $s_xml .= "                <hatch_fill enabled=\"true\" type=\"Percent50\" opacity=\"0.5\" />" . $newline;
           $s_xml .= "                <border enabled=\"true\" type=\"Solid\" color=\"#494949\" opacity=\"0.7\" />" . $newline;
           $s_xml .= "              </selected_normal>" . $newline;
           $s_xml .= "              <selected_hover>" . $newline;
           $s_xml .= "                <border color=\"Gold\" opacity=\"0.7\" />" . $newline;
           $s_xml .= "                <fill color=\"#F0975B\" />" . $newline;
           $s_xml .= "                <hatch_fill enabled=\"true\" type=\"Percent50\" opacity=\"0.5\" />" . $newline;
           $s_xml .= "              </selected_hover>" . $newline;
           $s_xml .= "            </states>" . $newline;
           $s_xml .= "            <cap enabled=\"true\" radius=\"15\">" . $newline;
           $s_xml .= "              <background enabled=\"true\">" . $newline;
           $s_xml .= "                <fill type=\"Gradient\">" . $newline;
           $s_xml .= "                  <gradient type=\"Radial\" focal_point=\"-0.5\" angle=\"45\">" . $newline;
           $s_xml .= "                    <key color=\"#FFFFFF\" />" . $newline;
           $s_xml .= "                    <key color=\"#777777\" />" . $newline;
           $s_xml .= "                  </gradient>" . $newline;
           $s_xml .= "                </fill>" . $newline;
           $s_xml .= "              </background>" . $newline;
           $s_xml .= "            </cap>" . $newline;
           $s_xml .= "          </needle_pointer_style>" . $newline;
           $s_xml .= "        </styles>" . $newline;
           $s_xml .= "        <axis radius=\"40\" start_angle=\"" . $start_angle . "\" sweep_angle=\"" . $sweep_angle . "\">" . $newline;
           $s_xml .= "          <scale minimum=\"" . $iMin . "\" maximum=\"" . $iMax . "\" major_interval=\"" . $iMaxInt . "\" minor_interval=\"" . $iMinInt . "\" />" . $newline;
           $s_xml .= "          <scale_bar enabled=\"true\" shape=\"Bar\" width=\"5\">" . $newline;
           $s_xml .= "            <fill enabled=\"true\" color=\"#75B7E1\" />" . $newline;
           $s_xml .= "          </scale_bar>" . $newline;
           $s_xml .= "          <major_tickmark enabled=\"true\" shape=\"Rectangle\" width=\"2\" length=\"5\">" . $newline;
           $s_xml .= "            <fill enabled=\"true\" color=\"#494949\" />" . $newline;
           $s_xml .= "          </major_tickmark>" . $newline;
           $s_xml .= "          <minor_tickmark enabled=\"true\" shape=\"Rectangle\" width=\"1\" length=\"3\">" . $newline;
           $s_xml .= "            <fill enabled=\"true\" color=\"#494949\" />" . $newline;
           $s_xml .= "          </minor_tickmark>" . $newline;
           $s_xml .= "          <labels enabled=\"true\" align=\"Inside\" padding=\"5\" rotate_circular=\"false\">" . $newline;
           $s_xml .= "            <format><![CDATA[{%Value}{numDecimals:0}]]></format>" . $newline;
           $s_xml .= "            <font family=\"Arial\" bold=\"true\" size=\"11\" />" . $newline;
           $s_xml .= "          </labels>" . $newline;
           $s_xml .= "          <color_ranges>" . $newline;
           $s_xml .= "            <color_range start=\"" . floor($iMax * 0.8) . "\" end=\"" . $iMax . "\" start_size=\"0\" end_size=\"15\" align=\"Inside\" padding=\"35\">" . $newline;
           $s_xml .= "              <fill type=\"Gradient\">" . $newline;
           $s_xml .= "                <gradient>" . $newline;
           $s_xml .= "                  <key color=\"Green\" />" . $newline;
           $s_xml .= "                  <key color=\"Yellow\" />" . $newline;
           $s_xml .= "                  <key color=\"Red\" />" . $newline;
           $s_xml .= "                </gradient>" . $newline;
           $s_xml .= "              </fill>" . $newline;
           $s_xml .= "              <border enabled=\"true\" color=\"Gray\" opacity=\"0.3\" />" . $newline;
           $s_xml .= "            </color_range>" . $newline;
           $s_xml .= "          </color_ranges>" . $newline;
           $s_xml .= "        </axis>" . $newline;
           $s_xml .= "        <frame type=\"" . $gauge_forma . "\">" . $newline;
           $s_xml .= "          <inner_stroke thickness=\"2\" />" . $newline;
           $s_xml .= "          <effects enabled=\"true\">" . $newline;
           $s_xml .= "            <drop_shadow enabled=\"true\" opacity=\"0.5\" blur_x=\"5\" blur_y=\"5\" />" . $newline;
           $s_xml .= "          </effects>" . $newline;
           $s_xml .= "        </frame>" . $newline;
           $s_xml .= "        <pointers>" . $newline;
           foreach ($arr_series as $arr_serie)
           {
               $label = $arr_serie['label'];
               $datay = $arr_serie['data'];
               foreach ($label as $iIndex => $sLabel)
               {
                   $s_xml .= "          <pointer type=\"Needle\" value=\"" . $datay[$iIndex] . "\">" . $newline;
                   $s_xml .= "            <label enabled=\"false\">" . $newline;
                   $s_xml .= "              <position placement_mode=\"ByPoint\" x=\"50\" y=\"35\" halign=\"Center\" valign=\"Center\" />" . $newline;
                   $s_xml .= "            </label>" . $newline;
                   $s_xml .= "          </pointer>" . $newline;
               }
           }
           $s_xml .= "        </pointers>" . $newline;
           $s_xml .= "      </circular>" . $newline;
           $s_xml .= "    </gauge>" . $newline;
           $s_xml .= "  </gauges>" . $newline;
           $s_xml .= "</anychart>" . $newline;

           return $s_xml;
       }

       switch ($tipo)
       {
           case 'Polar':
               $chart_series     = 'radar';
               $chart_series_a[] = 'use_polar_coords="true"';
               $tipo_series      = 'Radar';
               $tipo_plot        = 'Radar';
               $def_ser_type     = $tipo_series;
               if ('Area' == $arr_param['polar_forma'])
               {
                   $def_ser_type = 'Area';
               }
               break;

           case 'Radar':
               $chart_series = 'radar';
               $tipo_series  = 'Radar';
               $tipo_plot    = 'Radar';
               $def_ser_type = $tipo_series;
               if ('Area' == $arr_param['radar_forma'])
               {
                   $def_ser_type = 'Area';
               }
               if ('Percent' == $arr_param['radar_empil'])
               {
                   $y_scale[] = 'mode="PercentStacked"';
               }
               elseif ('On' == $arr_param['radar_empil'])
               {
                   $y_scale[] = 'mode="Stacked"';
               }
               break;

           case 'Mark':
               $tipo_series  = 'Marker';
               $tipo_plot    = ('Series' == $arr_param['marca_agrup']) ? 'CategorizedBySeriesVertical' : 'CategorizedVertical';
               $def_ser_type = $tipo_series;
               if ('Reversed' == $arr_param['marca_inver'])
               {
                   $y_scale[] = 'inverted="True"';
               }
               break;

           case 'Area':
               $chart_series = 'area_series';
               $chart_style  = 'area_style';
               $tipo_series  = ('Spline' == $arr_param['area_forma']) ? 'SplineArea' : 'Area';
               $tipo_plot    = ('Series' == $arr_param['area_agrup']) ? 'CategorizedBySeriesVertical' : 'CategorizedVertical';
               $def_ser_type = $tipo_series;
               if ('Reversed' == $arr_param['area_inver'])
               {
                   $y_scale[] = 'inverted="True"';
               }
               if ('Percent' == $arr_param['area_empil'])
               {
                   $y_scale[] = 'mode="PercentStacked"';
               }
               elseif ('On' == $arr_param['area_empil'])
               {
                   $y_scale[] = 'mode="Stacked"';
               }
               break;

           case 'Line':
               $chart_series = 'line_series';
               if ('Spline' == $arr_param['linha_forma'])
               {
                   $tipo_series = 'Spline';
               }
               elseif ('Step' == $arr_param['linha_forma'])
               {
                   $tipo_series = 'StepLineForward';
               }
               else
               {
                   $tipo_series = 'Line';
               }
               $tipo_plot    = ('Series' == $arr_param['linha_agrup']) ? 'CategorizedBySeriesVertical' : 'CategorizedVertical';
               $def_ser_type = $tipo_series;
               if ('Reversed' == $arr_param['linha_inver'])
               {
                   $y_scale[] = 'inverted="True"';
               }
               break;

           case 'Pie':
               $chart_series     = 'pie_series';
               $chart_style      = 'pie_style';
               $tipo_series      = 'Pie';
               $tipo_plot        = ('Donut'   == $arr_param['pizza_forma']) ? 'Doughnut' : 'Pie';
               $def_ser_type     = $tipo_series;
               $pie_value_format = ('Percent' == $arr_param['pizza_valor']) ? '{%YPercentOfSeries}{numDecimals:1}%' : $value_format;
               $dp_settings[]    = ('3d' == $arr_param['pizza_dimen']) ? 'enable_3d_mode="True"' : 'enable_3d_mode="False"';
               if ('Asc' == $arr_param['pizza_orden'] || 'Desc' == $arr_param['pizza_orden'])
               {
                   $chart_series_a[] = 'sort="' . $arr_param['pizza_orden'] . '"';
               }
               if ('On' == $arr_param['pizza_explo'] || 'Click' == $arr_param['pizza_explo'])
               {
                   if ('On' == $arr_param['pizza_explo'])
                   {
                       $series_explode = ' exploded="True" explode="10%"';
                   }
                   else
                   {
                       $chart_series_a[] = 'explode_on_click="True"';
                       $series_explode   = ' explode="10%"';
                   }
               }
               else
               {
                   $chart_series_a[] = 'explode_on_click="False"';
               }
               break;

           case 'Funnel':
               $show_as_funnel   = true;
               $tipo_series      = 'Funnel';
               $def_ser_type     = $tipo_series;
               $dp_settings[]    = ('3d' == $arr_param['funil_dimen']) ? 'enable_3d_mode="True"' : 'enable_3d_mode="False"';
               $chart_series     = 'funnel_series';
               $tipo_plot        = 'Funnel';
               $label_settings[] = 'placement_mode="OutsideLeftInColumn"';
               $label_settings[] = 'padding="20"';
               $chart_series_a[] = 'fit_aspect="0.9"';
               if ('Reversed' == $arr_param['funil_inver'])
               {
                   $chart_series_a[] = 'inverted="true"';
               }
               break;

           case 'Bar':
               $tipo_series = 'Bar';
               switch ($arr_param['barra_orien'])
               {
                   case 'Horizontal':
                       $tipo_plot      = ('Series' == $arr_param['barra_agrup']) ? 'CategorizedBySeriesHorizontal' : 'CategorizedHorizontal';
                       $axis_label_pos = " position=\"Opposite\"";
                       break;
                   default:
                   case 'Vertical':
                       $tipo_plot = ('Series' == $arr_param['barra_agrup']) ? 'CategorizedBySeriesVertical' : 'CategorizedVertical';
                       break;
               }
               $def_ser_type  = $tipo_series;
               $dp_settings[] = ('3d' == $arr_param['barra_dimen']) ? 'enable_3d_mode="True"' : 'enable_3d_mode="False"';
               if ('Reversed' == $arr_param['barra_inver'])
               {
                   $y_scale[] = 'inverted="True"';
               }
               if ('Percent' == $arr_param['barra_empil'])
               {
                   $y_scale[] = 'mode="PercentStacked"';
               }
               elseif ('On' == $arr_param['barra_empil'])
               {
                   $y_scale[] = 'mode="Stacked"';
               }
               elseif ('Yes' == $arr_param['barra_sobre'])
               {
                   $y_scale[] = 'mode="Overlay"';
               }
               if ('Cone' == $arr_param['barra_forma'])
               {
                   $show_as_funnel = 'Y' == $arr_param['barra_funil'];
                   $shape_type     = ' shape_type="Cone"';
               }
               elseif ('Cylinder' == $arr_param['barra_forma'])
               {
                   $shape_type = ' shape_type="Cylinder"';
               }
               elseif ('Pyramid' == $arr_param['barra_forma'])
               {
                   $show_as_funnel = 'Y' == $arr_param['barra_funil'];
                   $shape_type     = ' shape_type="Pyramid"';
               }
               if ($show_as_funnel)
               {
                   $chart_series     = 'funnel_series';
                   $tipo_plot        = 'Funnel';
                   $label_settings[] = 'placement_mode="OutsideLeftInColumn"';
                   $label_settings[] = 'padding="20"';
                   $chart_series_a[] = 'inverted="true"';
                   $chart_series_a[] = 'neck_height="0"';
                   $chart_series_a[] = 'min_width="0"';
                   $chart_series_a[] = 'padding="0"';
                   $chart_series_a[] = 'fit_aspect="0.9"';
               }
               break;
       }
       $dp_settings[] = 'default_series_type="' . $def_ser_type . '"';

       $s_data_font_size = '';

       $s_xml .= "  <charts>" . $newline;
       $s_xml .= "    <chart plot_type=\"" . $tipo_plot . "\">" . $newline;
       $s_xml .= "      <palettes>" . $newline;
       $s_xml .= "        <palette name=\"ScColorPaletteMany\" type=\"ColorRange\" color_count=\"auto\">" . $newline;
       $s_xml .= "          <gradient>" . $newline;
       $s_xml .= "            <key color=\"#d4e3d4\" />" . $newline;
       $s_xml .= "            <key color=\"#bee2bf\" />" . $newline;
       $s_xml .= "            <key color=\"#96cd97\" />" . $newline;
       $s_xml .= "            <key color=\"#7cde7f\" />" . $newline;
       $s_xml .= "            <key color=\"#3fb742\" />" . $newline;
       $s_xml .= "            <key color=\"#219524\" />" . $newline;
       $s_xml .= "            <key color=\"#0a6d0d\" />" . $newline;
       $s_xml .= "            <key color=\"#044b06\" />" . $newline;
       $s_xml .= "            <key color=\"#023303\" />" . $newline;
       $s_xml .= "            <key color=\"#002501\" />" . $newline;
       $s_xml .= "          </gradient>" . $newline;
       $s_xml .= "        </palette>" . $newline;
       $s_xml .= "        <palette name=\"ScColorPaletteOne\" type=\"DistinctColors\">" . $newline;
       $s_xml .= "          <item color=\"#d4e3d4\" />" . $newline;
       $s_xml .= "        </palette>" . $newline;
       $s_xml .= "      </palettes>" . $newline;
       $s_xml .= "      <data_plot_settings " . implode(' ', $dp_settings) . ">" . $newline;
       $s_xml .= "        <" . $chart_series . " " . implode(' ', $chart_series_a) . ">" . $newline;
       if ('Pie' == $tipo)
       {
           $s_xml .= "          <label_settings enabled=\"True\" " . implode(' ', $label_settings) . ">" . $newline;
           $s_xml .= "            <font color=\"White\">" . $newline;
           $s_xml .= "              <effects>" . $newline;
           $s_xml .= "                <drop_shadow enabled=\"True\" />" . $newline;
           $s_xml .= "              </effects>" . $newline;
           $s_xml .= "            </font>" . $newline;
           $s_xml .= "            <position anchor=\"Center\" valign=\"Center\" halign=\"Center\" padding=\"0\" />" . $newline;
           $s_xml .= "            <format><![CDATA[" . $pie_value_format . "]]></format>" . $newline;
           $s_xml .= "          </label_settings>" . $newline;
       }
       elseif ($show_as_funnel)
       {
           $s_xml .= "          <connector enabled=\"true\" color=\"Black\" opacity=\"0.4\" />" . $newline;
           $s_xml .= "          <label_settings enabled=\"True\" " . implode(' ', $label_settings) . ">" . $newline;
           if (!$this->sc_graf_sint && 1 < sizeof($arr_series))
           {
               $s_xml .= "            <format><![CDATA[{%SeriesName} :: {%Name}{numDecimals:0,thousandsSeparator:} = " . $value_y_format . "]]></format>" . $newline;
           }
           else
           {
               $s_xml .= "            <format><![CDATA[{%Name}{numDecimals:0,thousandsSeparator:} = " . $value_y_format . "]]></format>" . $newline;
           }
           $s_xml .= "            <background enabled=\"true\">" . $newline;
           $s_xml .= "              <inside_margin left=\"8\" right=\"8\" />" . $newline;
           $s_xml .= "              <corners type=\"Rounded\" all=\"3\" />" . $newline;
           $s_xml .= "            </background>" . $newline;
           $s_xml .= "          </label_settings>" . $newline;
       }
       else
       {
           $s_xml .= "          <label_settings enabled=\"True\" " . implode(' ', $label_settings) . ">" . $newline;
           $s_xml .= "            <format><![CDATA[" . $value_y_format . "]]></format>" . $newline;
           $s_xml .= "          </label_settings>" . $newline;
       }
       $s_xml .= "          <tooltip_settings enabled=\"True\">" . $newline;
       if (!$this->sc_graf_sint && 1 < sizeof($arr_series))
       {
           $s_xml .= "            <format><![CDATA[{%SeriesName} :: {%Name}{numDecimals:0,thousandsSeparator:} = " . $value_y_format . "]]></format>" . $newline;
       }
       else
       {
           $s_xml .= "            <format><![CDATA[{%Name}{numDecimals:0,thousandsSeparator:} = " . $value_y_format . "]]></format>" . $newline;
       }
       $s_xml .= "          </tooltip_settings>" . $newline;
       if ('Bar' == $tipo || 'Area' == $tipo)
       {
           $s_xml .= "          <" . $chart_style . ">" . $newline;
           $s_xml .= "            <fill type=\"Gradient\">" . $newline;
           $s_xml .= "              <gradient angle=\"90\">" . $newline;
           $s_xml .= "                <key position=\"0\" color=\"LightColor(%Color)\" opacity=\"0.9\" />" . $newline;
           $s_xml .= "                <key position=\"1\" color=\"DarkColor(%Color)\" opacity=\"0.9\" />" . $newline;
           $s_xml .= "              </gradient>" . $newline;
           $s_xml .= "            </fill>" . $newline;
           $s_xml .= "            <states>" . $newline;
           $s_xml .= "              <hover color=\"#888888\" />" . $newline;
           $s_xml .= "            </states>" . $newline;
           $s_xml .= "          </" . $chart_style . ">" . $newline;
       }
       $s_xml .= "        </" . $chart_series . ">" . $newline;
       $s_xml .= "      </data_plot_settings>" . $newline;
       $s_xml .= "      <chart_settings>" . $newline;
       if ('Pie' == $tipo)
       {
           $sIgnore = 1 == sizeof($arr_series) ? ' ignore_auto_item="True"' : '';
           $s_xml .= "        <legend enabled=\"True\"" . $sIgnore . ">" . $newline;
           $s_xml .= "          <title enabled=\"True\">" . $newline;
           $s_xml .= "            <text>" . $this->string_to_utf8($tit_label) . "</text>" . $newline;
           $s_xml .= "          </title>" . $newline;
           $s_xml .= "          <format><![CDATA[{%Icon} {%Name}{numDecimals:0,thousandsSeparator:}]]></format>" . $newline;
           if (1 == sizeof($arr_series))
           {
               $s_xml .= "          <items>" . $newline;
               $s_xml .= "            <item source=\"Points\" />" . $newline;
               $s_xml .= "          </items>" . $newline;
           }
           $s_xml .= "        </legend>" . $newline;
       }
       elseif (!$this->sc_graf_sint && 1 < sizeof($arr_series))
       {
           $s_xml .= "        <legend enabled=\"True\">" . $newline;
           $s_xml .= "          <title enabled=\"True\">" . $newline;
           $s_xml .= "            <text>" . $this->string_to_utf8('' != $arr_series[0]['legend'] ? $arr_series[0]['legend'] : $tit_label) . "</text>" . $newline;
           $s_xml .= "          </title>" . $newline;
           $s_xml .= "          <format><![CDATA[{%Icon} {%Name}{numDecimals:0,thousandsSeparator:}]]></format>" . $newline;
           $s_xml .= "        </legend>" . $newline;
       }
       $s_xml .= "        <title>" . $newline;
       $s_xml .= "          <text>" . $tit_graf . "</text>" . $newline;
       $s_xml .= "          <font bold=\"True\" color=\"#4d614e\">" . $newline;
       $s_xml .= "          </font>" . $newline;
       $s_xml .= "        </title>" . $newline;
       if ('' != $arr_series[0]['subtitle'])
       {
           $s_xml .= "        <subtitle enabled=\"True\">" . $newline;
           $s_xml .= "          <text>" . $arr_series[0]['subtitle'] . "</text>" . $newline;
           $s_xml .= "          <background enabled=\"False\" />" . $newline;
           $s_xml .= "          <font bold=\"False\" color=\"#4d614e\">" . $newline;
           $s_xml .= "          </font>" . $newline;
           $s_xml .= "        </subtitle>" . $newline;
       }
       $sMaxMin = '';
       if (true)
       {
           $iMax = '';
           $iMin = '';
           foreach ($arr_series as $arr_serie)
           {
               $label = $arr_serie['label'];
               $datay = $arr_serie['data'];
               foreach ($label as $iIndex => $sLabel)
               {
                   if ('' === $iMax)
                   {
                       $iMax = $datay[$iIndex];
                       $iMin = $datay[$iIndex];
                   }
                   else
                   {
                       $iMax = max($iMax, $datay[$iIndex]);
                       $iMin = min($iMin, $datay[$iIndex]);
                   }
               }
           }
           if (0 < $iMax && 0 < $iMin)
           {
               $sMaxMin = " minimum=\"0\"";
           }
           elseif (0 > $iMax && 0 > $iMin)
           {
               $sMaxMin = " maximum=\"0\"";
           }
       }
       $s_xml .= "        <axes>" . $newline;
       $s_xml .= "          <y_axis" . $axis_label_pos . ">" . $newline;
       $s_xml .= "            <labels>" . $newline;
       $s_xml .= "              <format><![CDATA[" . $value_format . "]]></format>" . $newline;
       $s_xml .= "              <font bold=\"False\" color=\"#4d614e\"" . $s_data_font_size . ">" . $newline;
       $s_xml .= "              </font>" . $newline;
       $s_xml .= "            </labels>" . $newline;
       if (!empty($y_scale))
       {
           $s_xml .= "            <scale " . implode(' ', $y_scale) . $sMaxMin . " />" . $newline;
       }
       elseif ('' != $sMaxMin)
       {
           $s_xml .= "            <scale " . $sMaxMin . " />" . $newline;
       }
       $s_xml .= "            <title>" . $newline;
       $s_xml .= "              <text>" . $tit_datay . "</text>" . $newline;
       $s_xml .= "              <font bold=\"True\" color=\"#4d614e\">" . $newline;
       $s_xml .= "              </font>" . $newline;
       $s_xml .= "            </title>" . $newline;
       $s_xml .= "            <major_grid enabled=\"True\" interlaced=\"True\">" . $newline;
       $s_xml .= "              <line color=\"#888888\"/>" . $newline;
       $s_xml .= "            </major_grid>" . $newline;
       $s_xml .= "            <minor_grid enabled=\"False\" />" . $newline;
       $s_xml .= "            <line color=\"#FFFFFF\" />" . $newline;
       $s_xml .= "            <major_tickmark enabled=\"True\" color=\"#FFFFFF\" />" . $newline;
       $s_xml .= "            <minor_tickmark enabled=\"False\" />" . $newline;
       $s_xml .= "          </y_axis>" . $newline;
       $s_xml .= "          <x_axis>" . $newline;
       $s_xml .= "            <labels" . (('Bar' != $tipo || 'Horizontal' != $arr_param['barra_orien']) ? ' rotation="90"' : '') . ">" . $newline;
       $s_xml .= "              <font bold=\"False\" color=\"#4d614e\"" . $s_data_font_size . ">" . $newline;
       $s_xml .= "              </font>" . $newline;
       $s_xml .= "            </labels>" . $newline;
       $s_xml .= "            <title>" . $newline;
       $s_xml .= "              <text>" . $tit_label . "</text>" . $newline;
       $s_xml .= "              <font bold=\"True\" color=\"#4d614e\">" . $newline;
       $s_xml .= "              </font>" . $newline;
       $s_xml .= "            </title>" . $newline;
       $s_xml .= "            <major_grid enabled=\"True\" interlaced=\"False\" />" . $newline;
       $s_xml .= "            <line color=\"#FFFFFF\" />" . $newline;
       $s_xml .= "            <major_tickmark enabled=\"True\" color=\"#FFFFFF\" />" . $newline;
       $s_xml .= "          </x_axis>" . $newline;
       $s_xml .= "        </axes>" . $newline;
       $s_xml .= "        <chart_background>" . $newline;
       $s_xml .= "          <fill type=\"Gradient\">" . $newline;
       $s_xml .= "            <gradient angle=\"90\">" . $newline;
       $s_xml .= "              <key position=\"0\" color=\"#FFFFFF\" />" . $newline;
       $s_xml .= "              <key position=\"1\" color=\"#BBFFE1\" />" . $newline;
       $s_xml .= "            </gradient>" . $newline;
       $s_xml .= "          </fill>" . $newline;
       $s_xml .= "        </chart_background>" . $newline;
       $s_xml .= "        <data_plot_background>" . $newline;
       if ($show_as_funnel)
       {
           $s_xml .= "        <inside_margin all=\"15\" />" . $newline;
       }
       $s_xml .= "          <border color=\"#0000FF\" type=\"Solid\" />" . $newline;
       $s_xml .= "          <fill color=\"#888888\" opacity=\"0.1\" />" . $newline;
       $s_xml .= "        </data_plot_background>" . $newline;
       $s_xml .= "      </chart_settings>" . $newline;
       if ('Radar' == $tipo || 'Polar' == $tipo)
       {
           $s_xml .= "      <styles>" . $newline;
           $s_xml .= "        <tooltip_style name=\"idTooltipStyle\">" . $newline;
           $s_xml .= "          <format><![CDATA[" . $value_y_format . "]]></format>" . $newline;
           $s_xml .= "        </tooltip_style>" . $newline;
           $s_xml .= "      </styles>" . $newline;
       }
       if (1 < sizeof($arr_series))
       {
           $sDataPalette   = " palette=\"ScColorPaletteMany\"";
           $sSeriesPalette = "";
       }
       elseif ('Bar' != $tipo && 'Pie' != $tipo && 'Mark' != $tipo && 'Funnel' != $tipo)
       {
           $sDataPalette   = "";
           $sSeriesPalette = " palette=\"ScColorPaletteOne\"";
       }
       else
       {
           $sDataPalette   = "";
           $sSeriesPalette = " palette=\"ScColorPaletteMany\"";
       }
       $s_xml .= "      <data" . $sDataPalette . ">" . $newline;
       foreach ($arr_series as $arr_serie)
       {
           $s_xml .= "        <series name=\"" . $arr_serie['name'] . "\" type=\"" . $tipo_series . "\"" . $shape_type . $series_explode . "" . $sSeriesPalette . ">" . $newline;
           if ('Radar' == $tipo || 'Polar' == $tipo)
           {
               $s_xml .= "          <tooltip enabled=\"true\" style=\"idTooltipStyle\" />" . $newline;
           }
           $s_xml .= "          <label enabled=\"True\" />" . $newline;
           $label = $arr_serie['label'];
           $datay = $arr_serie['data'];
           foreach ($label as $iIndex => $sLabel)
           {
               if (false)
               {
                   $s_xml .= "          <point name=\"" . $sLabel . "\" y=\"" . $datay[$iIndex] . "\">" . $newline;
                   $s_xml .= "            <actions>" . $newline;
                   $s_xml .= "              <action type=\"navigateToURL\" url=\"\" target=\"_blank\" />" . $newline;
                   $s_xml .= "            </actions>" . $newline;
                   $s_xml .= "          </point>" . $newline;
               }
               else
               {
                   $s_xml .= "          <point name=\"" . $sLabel . "\" y=\"" . $datay[$iIndex] . "\" />" . $newline;
               }
           }
           $s_xml .= "        </series>" . $newline;
       }
       $s_xml .= "      </data>" . $newline;
       $s_xml .= "    </chart>" . $newline;
       $s_xml .= "  </charts>" . $newline;
       $s_xml .= "</anychart>" . $newline;

       return $s_xml;
   }

   function grafico_flash_js()
   {
      global $nm_saida;

?>
<script type="text/javascript" language="javascript" src="<?php echo $this->Ini->path_prod ?>/third/anychart/js/AnyChart.js"></script>
<script type="text/javascript">
  function makeMultipartFormDataPostRequest(path, params) {

    var xmlhttp = false;
    /*@cc_on @*/
    /*@if (@_jscript_version >= 5)
    // JScript gives us Conditional compilation, we can cope with old IE versions.
    // and security blocked creation of the objects.
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
    @end @*/

    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
      try {
        xmlhttp = new XMLHttpRequest();
      } catch (e) {
        xmlhttp = false;
      }
    }

    if (!xmlhttp && window.createRequest) {
      try {
        xmlhttp = window.createRequest();
      } catch (e) {
        xmlhttp = false;
      }
    }

    xmlhttp.open("POST", path, false);

    var boundary = "Boundary_" + new Date().getMilliseconds() + ";";
    xmlhttp.setRequestHeader("Content-Type","multipart/form-data; boundary="+boundary);

    var dataString = "";

    for (var propName in params) {
      dataString += '--' + boundary + '\r\n';
      dataString += 'content-disposition: form-data; name="' + propName + '"' + '\r\n';
      dataString += 'content-type: application/octet-stream;\r\n\r\n\r\n';
      dataString += params[propName] + '\r\n';
    }
    dataString += "--"+boundary;

    xmlhttp.send(dataString);
    return xmlhttp.responseText ;
  }

  function saveChartAsImage(chart) {
    var requestData = {};
    requestData['imgData'] = chart.getPNG();
    requestData['imgType'] = 'png';
    requestData['imgName'] = aChartName[iChartName];
    requestData['timestamp'] = new Date().getTime();
    iChartName++;

    var path = makeMultipartFormDataPostRequest('grid_public_v_dte_save_flash.class.php', requestData);

    if (aChartName[iChartName])
      setTimeout("reescreve()", 10);
    else
      document.flashRedir.submit();
  }
</script>
<?php
   }

   function grafico_flash_form()
   {
      global $nm_saida;

      $sOpcao = isset($_GET['nmgp_opcao']) && 'pdf_res' == $_GET['nmgp_opcao'] ? 'pdf_res' : 'pdf';
?>
<form name="flashRedir" method="GET" action="grid_public_v_dte.php" style="display: none">
  <input type="hidden" name="flash_graf" value="pdf" />
  <input type="hidden" name="nmgp_opcao" value="<?php       echo NM_encode_input($sOpcao);                         ?>" />
  <input type="hidden" name="script_case_init" value="<?php echo NM_encode_input($_GET['script_case_init']);       ?>" />
  <input type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id());                  ?>" />
  <input type="hidden" name="pbfile" value="<?php           echo NM_encode_input($_GET['pbfile']);                 ?>" />
  <input type="hidden" name="sc_apbgcol" value="<?php       echo urlencode($this->Ini->path_css); ?>" />
  <input type="hidden" name="nmgp_tipo_pdf" value="<?php    echo NM_encode_input($_GET['nmgp_tipo_pdf']);          ?>" />
  <input type="hidden" name="nmgp_parms_pdf" value="<?php   echo NM_encode_input($_GET['nmgp_parms_pdf']);         ?>" />
  <input type="hidden" name="nmgp_graf_pdf" value="<?php    echo NM_encode_input($_GET['nmgp_graf_pdf']);          ?>" />
  <input type="hidden" name="pdf_base" value="<?php         echo NM_encode_input($_GET['pdf_base']);               ?>" />
  <input type="hidden" name="pdf_url" value="<?php          echo NM_encode_input($_GET['pdf_url']);                ?>" />
</form>
<?php
   }

   function grafico_flash_chart($width, $height, $sFileLocal, $export, $arr_charts)
   {
      global $nm_saida;

      $sChartId = 'id_chat_' . mt_rand(1, 1000);
      $sStyle   = 'Y' == $export ? ' style="position:absolute; top: 0px; left: 0px"' : '';
?>
<span id="<?php echo $sChartId; ?>"<?php echo $sStyle; ?>></span>
<script type="text/javascript" language="javascript">
  var chart = new AnyChart('<?php echo $this->Ini->path_prod ?>/third/anychart/swf/AnyChart.swf?r=<?php echo substr(md5(microtime()), 8, 16); ?>');
  chart.width = <?php echo $width ?>;
  chart.height = <?php echo $height ?>;
  chart.setXMLFile('<?php echo $sFileLocal ?>');
  chart.wMode = 'opaque';
<?php
      if ('Y' == $export)
      {
?>
  chart.addEventListener("draw", function() { saveChartAsImage(chart); });
<?php
      }
?>
  chart.write("<?php echo $sChartId; ?>");
  var iChartList = 1;
  var aChartList = new Array();
<?php
      if (1 < sizeof($arr_charts))
      {
         for ($i = 1; $i < sizeof($arr_charts); $i++)
         {
             $arr_param              = $this->arr_param;
             $arr_param['tit_datay'] = $arr_charts[$i][0]['label_y'];
             $arr_param['tit_label'] = $arr_charts[$i][0]['label_x'];
             $arr_param['tit_chart'] = $arr_charts[$i][0]['title'];
?>
  aChartList[<?php echo $i ?>] = '<?php echo $this->grafico_flash_xml($arr_param, $arr_charts[$i], ''); ?>';
<?php
         }
      }
?>
  var iChartName = 0;
  var aChartName = new Array();
<?php
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['chart_list'] = array();
      for ($i = 0; $i < sizeof($arr_charts); $i++)
      {
         $sChartName  = $this->Ini->path_imag_temp . '/sc_grid_public_v_dte_' . $i . '_' . substr(md5(mt_rand(1, 100)), 0, 4);
         $sChartTitle = '' != $arr_charts[$i][0]['subtitle'] ? $arr_charts[$i][0]['title'] . ' - ' . $arr_charts[$i][0]['subtitle'] : $arr_charts[$i][0]['title'];
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['chart_list'][] = array($sChartName, $sChartTitle);
?>
  aChartName[<?php echo $i ?>] = '<?php echo $this->Ini->root . $sChartName ?>';
<?php
      }
?>
  function reescreve()
  {
<?php
      if (1 < sizeof($arr_charts))
      {
?>
    chart.setData(aChartList[iChartList]);
    iChartList++;
<?php
      }
?>
  }
</script>
<?php
   }

   function string_to_utf8($str)
   {
       if ('UTF-8' != $_SESSION['scriptcase']['charset'])
       {
           $str = mb_convert_encoding($str, 'UTF-8', $_SESSION['scriptcase']['charset']);
       }
       return $str;
   }

   function grafico_dados($cht_data, $export)
   {
       $datay = $cht_data['values'];
       $label = $cht_data['labels'];
       $name  = $cht_data['label_x'];

       foreach ($label as $i => $v)
       {
           $label[$i] = $this->string_to_utf8($v);
       }

       $arr_charts = array();

       if ('Y' != $export)
       {
           $arr_data   = !$this->sc_graf_sint && isset($datay['anal']) && !empty($datay['anal']) ? $datay['anal'] : $datay['sint'];
           $arr_series = array();

           foreach ($arr_data as $i_chart => $arr_chart)
           {
               $arr_series[] = array('name' => $this->string_to_utf8($i_chart), 'label' => $label, 'data' => $arr_chart, 'title' => $cht_data['title'], 'subtitle' => $cht_data['subtitle'], 'label_x' => $cht_data['label_x'], 'label_y' => $cht_data['label_y'], 'legend' => $cht_data['legend'], 'format' => $cht_data['format']);
           }

           $arr_charts[] = $arr_series;
       }
       else
       {
           foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pivot_charts'] as $chart_index => $chart_data)
           {
               $datay = $chart_data['values'];
               $label = $chart_data['labels'];
               $name  = $chart_data['label_x'];

               $arr_data   = !$this->sc_graf_sint && isset($datay['anal']) && !empty($datay['anal']) ? $datay['anal'] : $datay['sint'];
               $arr_series = array();

               foreach ($arr_data as $i_chart =>$arr_chart)
               {
                   $arr_series[] = array('name' => $this->string_to_utf8($i_chart), 'label' => $label, 'data' => $arr_chart, 'title' => $chart_data['title'], 'subtitle' => $chart_data['subtitle'], 'label_x' => $chart_data['label_x'], 'label_y' => $chart_data['label_y'], 'legend' => $chart_data['legend'], 'format' => $chart_data['format']);
               }

               $arr_charts[] = $arr_series;
           }
       }

       return $arr_charts;
   }

   function orderCharts(&$arr_charts, $rule = '')
   {
       if ('' == $rule)
       {
           return;
       }

       foreach ($arr_charts as $i => $c)
       {
           if (1 == sizeof($c))
           {
               $this->orderChart($arr_charts[$i][0]['label'], $arr_charts[$i][0]['data'], $rule);
           }
       }
   }

   function orderChart(&$label, &$data, $rule = '')
   {
       if ('' == $rule)
       {
           return;
       }
       elseif ('asc' == $rule)
       {
           asort($data);
       }
       elseif ('desc' == $rule)
       {
           arsort($data);
       }

       $new_data  = array();
       $new_label = array();
       foreach ($data as $i => $v)
       {
           $new_data[]  = $v;
           $new_label[] = $label[$i];
       }
       $data  = $new_data;
       $label = $new_label;
   }

   function grafico_generico($tipo, $width, $height, $margem, $aspecto, $preenche, $exibe_val, $exibe_marc, $datay, $label, $tit_datay, $tit_label, $tit_graf, $subtit_graf, $tip_pizza = "ABS")
   {
      global $nm_saida, $nm_retorno_graf;
      if (1 == sizeof($datay))
      {
          $this->orderChart($label, $datay[0], $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_order']);
      }
      if ('' != $subtit_graf)
      {
          $tit_graf .= ' - ' . $subtit_graf;
      }
      $font_ttf_type = 2;
      $font_ttf_dir  = $this->Ini->path_grafico_fonts;
      if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_rus))
      {
          $font_ttf_type = 33;
          $font_ttf_dir  = $this->Ini->path_font;
      }
      if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_chi))
      {
          $font_ttf_type = 34;
          $font_ttf_dir  = $this->Ini->path_font;
      }
      if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_jap))
      {
          $font_ttf_type = 35;
          $font_ttf_dir  = $this->Ini->path_font;
      }
      if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_thai))
      {
          $font_ttf_type = 36;
          $font_ttf_dir  = $this->Ini->path_font;
      }
      if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_arab))
      {
          $font_ttf_type = 37;
          $font_ttf_dir  = $this->Ini->path_font;
      }
      DEFINE ("TTF_DIR", $font_ttf_dir);
      if (is_array($margem))
      {
          $margem_bot = (!is_integer($margem[0]) || !is_numeric($margem[0]) || 0 > $margem[0]) ? 10 : $margem[0];
          $margem_dir = (!is_integer($margem[1]) || !is_numeric($margem[1]) || 0 > $margem[1]) ? 10 : $margem[1];;
          $margem_top = (!is_integer($margem[2]) || !is_numeric($margem[2]) || 0 > $margem[2]) ? 10 : $margem[2];;
          $margem_esq = (!is_integer($margem[3]) || !is_numeric($margem[3]) || 0 > $margem[3]) ? 10 : $margem[3];;
      }
      else
      {
          $margem_bot = (!is_integer($margem) || !is_numeric($margem) || 0 > $margem) ? 10 : $margem;
          $margem_dir = (!is_integer($margem) || !is_numeric($margem) || 0 > $margem) ? 10 : $margem;;
          $margem_top = (!is_integer($margem) || !is_numeric($margem) || 0 > $margem) ? 10 : $margem;;
          $margem_esq = (!is_integer($margem) || !is_numeric($margem) || 0 > $margem) ? 10 : $margem;;
      }
      $this->graf_angulo = (empty($this->graf_angulo)) ? 0 : $this->graf_angulo;
      if (empty($datay) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
      {
         return;
      }
      if (!empty($this->Decimais))
      {
          $format_pie_abs = "%01." . $this->Decimais . "f";
      }
      else
      {
          $format_pie_abs = "%s";
      }
      $width  = (int)$width;
      $height = (int)$height;
      $orient_horiz = false;
      if ($tipo == 4)
      {
          $orient_horiz = true;
          $tipo     = 1;
      }
      if ($tipo == 5)
      {
          $orient_horiz = true;
          $tipo     = 2;
      }
      if ($tipo == 7)
      {
          $orient_horiz = true;
          $tipo     = 6;
      }
      $renda = false;
      if ($tipo == 6)
      {
          $renda = true;
          $tipo  = 2;
      }
      $pizza3d = false;
      if ($tipo == 3)
      {
          $tip_pizza = "ABS";
      }
      if ($tipo == 8)
      {
          $tip_pizza = "PER";
          $tipo  = 3;
      }
      if ($tipo == 20)
      {
          $tip_pizza = "ABS";
          $pizza3d   = true;
          $tipo      = 3;
      }
      if ($tipo == 21)
      {
          $tip_pizza = "PER";
          $pizza3d   = true;
          $tipo      = 3;
      }
      $impulse = false;
      if ($tipo == 26)
      {
          $impulse = true;
          $tipo    = 4;
      }
      if ($tipo == 27)
      {
          $orient_horiz = true;
          $impulse      = true;
          $tipo         = 4;
      }
      if ($tipo == 28)
      {
          $tipo    = 4;
      }
      if ($tipo == 29)
      {
          $orient_horiz = true;
          $tipo         = 4;
      }
      DEFINE("CACHE_DIR",        $this->Ini->root . $this->Ini->path_imag_temp);
      DEFINE("APACHE_CACHE_DIR", $this->Ini->path_imag_temp);
      DEFINE("TTF_DIR",          "");
      DEFINE("SC_GRAPH_BAR",  1);
      DEFINE("SC_GRAPH_LINE", 2);
      DEFINE("SC_GRAPH_PIE",  3);
      DEFINE("SC_GRAPH_SCATTER",  4);
      unset($GLOBALS['php_errormsg']);
      require_once($this->Ini->path_grafico . "/jpgraph.php"); 
      switch ($tipo)
      {
         case SC_GRAPH_PIE:
            require_once($this->Ini->path_grafico . "/jpgraph_pie.php"); 
            if ($pizza3d)
            {
                require_once($this->Ini->path_grafico . "/jpgraph_pie3d.php"); 
            }
         break;
         case SC_GRAPH_LINE:
            require_once($this->Ini->path_grafico . "/jpgraph_line.php"); 
         break;
         case SC_GRAPH_SCATTER:
            require_once($this->Ini->path_grafico . "/jpgraph_scatter.php"); 
         break;
         case SC_GRAPH_BAR:
         default:
            require_once($this->Ini->path_grafico . "/jpgraph_bar.php"); 
         break;
      }
      $this->graf_tipo_marcas = ($this->graf_tipo_marcas == "C") ? MARK_FILLEDCIRCLE : $this->graf_tipo_marcas;
      $this->graf_tipo_marcas = ($this->graf_tipo_marcas == "Q") ? MARK_SQUARE : $this->graf_tipo_marcas;
      $this->graf_tipo_marcas = ($this->graf_tipo_marcas == "U") ? MARK_UTRIANGLE : $this->graf_tipo_marcas;
      $this->graf_tipo_marcas = ($this->graf_tipo_marcas == "D") ? MARK_DTRIANGLE : $this->graf_tipo_marcas;
      $this->graf_tipo_marcas = ($this->graf_tipo_marcas == "L") ? MARK_DIAMOND : $this->graf_tipo_marcas;
      $this->graf_tipo_marcas = ($this->graf_tipo_marcas == "E") ? MARK_STAR : $this->graf_tipo_marcas;
      if (!is_integer($width) || !is_numeric($width) || 0 >= $width)
      {
         $width = 800;
      }
      if (!is_integer($height) || !is_numeric($height) || 0 >= $height)
      {
         $height = 600;
      }
      $max_size_label = 0;
      for ($i = 0; $i < sizeof($label); $i++)
      {
         $size_label     = strlen("" . $label[$i]);
         $max_size_label = ($max_size_label < $size_label) ? $size_label : $max_size_label;
      }
      $max_size_datay = 0;
      $max_larg_datay = 0;
      foreach ($datay as $legenda => $dadosY)
      {
          $max_larg_datay = ($max_larg_datay < sizeof($dadosY)) ?  sizeof($dadosY) : $max_larg_datay;
          for ($i = 0; $i < sizeof($dadosY); $i++)
          {
             $size_datay     = strlen("" . $dadosY[$i]);
             $max_size_datay = ($max_size_datay < $size_datay) ? $size_datay : $max_size_datay;
          }
      }
      if ("N" != strtoupper($aspecto))
      {
         $correcao_alt  = $max_size_label * 6;
         $correcao_larg = $max_size_datay * 6;
      }
      else
      {
         $correcao_alt  = 0;
         $correcao_larg = 0;
      }
      switch ($tipo)
      {
         case SC_GRAPH_PIE:
            $this->graf_cor_margens = $this->graf_cor_fundo;
            $img_width  = $width;
            $img_height = $height;
            $leg_height = 25 + (15 * sizeof($label));
            $leg_width  = 25 + ($max_size_label * 10);
            $img_height = ($leg_height > $img_height) ? ($leg_height + ($leg_height * 0.2)) : $img_height;
            $pie_size   = ($img_width  > $img_height) ? ($img_height / 2.5)                  : ($img_width / 2.5);
            $esp_val    = ("ABS" == $tip_pizza) ? $max_size_datay : 8;
            $img_width  += ($esp_val * 20);
            $img_height += ($esp_val * 10);
            if ($img_width < ((2 * $pie_size) + $leg_width))
            {
               $img_width  = (2 * $pie_size) + $leg_width;
            }
            if ($this->sc_graf_sint)
            {
                $pie_centerX = (($img_width - $leg_width) / 2) / $img_width;
                $pie_centerY = 0.5;
            }
            else
            {
                $qtd_col   = 0;
                $colunas   = 2;
                $leg_geral = false;
                $linhas    = ceil(sizeof($datay) / $colunas);
                $tmpW      = ($img_width - $leg_width - ($esp_val * 20)) / $colunas;
                $tmpH      = ($img_height / $linhas) - 50;
                $pie_size  = ($tmpW  > $tmpH) ? ($tmpH / 2.5) : ($tmpW / 2.5);
                $incr_col  = ($img_width - $leg_width) / $colunas;
                $ini_col   = $incr_col / 2;
                $atual_col = $ini_col;
                $incr_lin  = $img_height / $linhas;
                $ini_lin   = $incr_lin / 2;
                $atual_lin = $ini_lin;
            }
            $graph = new PieGraph($img_width, $img_height);
            $theme_class = new sc_theme;
            $graph->SetTheme($theme_class);
            $graph->img->SetMargin($margem_bot, $margem_dir, $margem_top, $margem_esq);
            $graph->legend->SetFont($font_ttf_type);
         break;
         case SC_GRAPH_LINE:
         case SC_GRAPH_BAR:
         case SC_GRAPH_SCATTER:
         default:
            $margem_esq = 35 + $margem_esq + ($max_size_datay * 6);
            $margem_dir = 10 + $margem_dir;
            $margem_top = 29 + $margem_top;
            $margem_bot = 29 + $margem_bot + ($max_size_label * 6);
            $img_width  = $width + $correcao_larg;
            $img_height = $height + $correcao_alt;
            $tmp_width = $margem_esq + $margem_dir + (9 * $max_larg_datay);
            if ($img_width < $tmp_width)
            {
               $img_width = $tmp_width;
            }
            if ($orient_horiz)
            {
                $graph = new Graph($img_width, $img_height);
                $graph->SetScale("textlin");
                $theme_class = new sc_theme;
                $graph->SetTheme($theme_class);
                $graph->Set90AndMargin($margem_bot, $margem_dir, $margem_top, $margem_esq);
                $graph->SetAngle(90);
                $graph->xaxis->SetFont($font_ttf_type);
                $graph->xaxis->SetTickLabels($label);
                $graph->xaxis->SetLabelAlign('right','center');
                $graph->xaxis->SetTitleMargin($max_size_label * 6);
                $graph->xaxis->SetTitle($tit_label,'middle');
                $graph->xaxis->title->SetAngle(90);
                $graph->xaxis->title->SetFont($font_ttf_type);
                $graph->yaxis->SetLabelAngle(90);
                $graph->yaxis->SetLabelAlign('center','top');
                $graph->yaxis->SetLabelSide(SIDE_RIGHT);
                $graph->yaxis->SetPos('max');
                $graph->yaxis->SetTitleMargin((20 + ($max_size_datay * 6)) * -1);
                $graph->yaxis->SetTitle($tit_datay);
                $graph->yaxis->title->SetAngle(0);
                $graph->yaxis->title->SetFont($font_ttf_type);
            }
            else
            {
                $graph = new Graph($img_width, $img_height);
                $graph->SetScale("textlin");
                $theme_class = new sc_theme;
                $graph->SetTheme($theme_class);
                $graph->img->SetMargin($margem_esq, $margem_dir, $margem_top, $margem_bot);
                $graph->xaxis->SetFont($font_ttf_type);
                $graph->xaxis->SetTickLabels($label);
                $graph->xaxis->SetLabelAngle(90);
                $graph->xaxis->SetTitleMargin($max_size_label * 6);
                $graph->xaxis->title->Set($tit_label);
                $graph->xaxis->SetFont($font_ttf_type);
                $graph->yaxis->SetTitleMargin(20 + ($max_size_datay * 6));
                $graph->yaxis->title->Set($tit_datay);
                $graph->yaxis->title->SetFont($font_ttf_type);
            }
            $graph->SetBox(true, "black", 1);
            $graph->legend->SetFont($font_ttf_type);
            if (!empty($this->graf_cor_label))
            {
               $graph->xaxis->SetColor($this->graf_cor_label);
            }
            if (!empty($this->graf_cor_valores))
            {
               $graph->yaxis->SetColor($this->graf_cor_valores);
            }
         break;
      }
      if (!empty($this->graf_cor_fundo))
      {
          $graph->SetColor($this->graf_cor_fundo);
      }
      else
      {
          $graph->SetColor("white");
      }
      if (!empty($this->graf_cor_margens))
      {
          $graph->SetMarginColor($this->graf_cor_margens);
      }
      else
      {
          $graph->SetMarginColor("white");
      }
      if ("" != $tit_graf && $session_nmgp_opcao_cons != "pdf")
      {
         $graph->title->Set($tit_graf);
         $graph->title->SetFont($font_ttf_type);
         $graph->title->SetBox("white", "black", true);
      }
      $gerou_graf     = false;
      $goup_graf_bar  = array();
      $goup_graf_line = array();
      $cores = array('#0000ff','#ff0000','#00ff00','#fa00ff','#b5b1b1','#fffa00','#e5778d','#ff9900','#00fffa',
                     '#ba6f00','#800080','#bfbc00','#00aaa8','#65abbf','#9764B1','#511e1e','#a52a2a','#706c6c',
                     '#e2e2e2
#0707b2','#a50303','#027f02','#a34658','#ffc0cb','#6969b2','#add8e6','#656577','#ad5b5b',
                     '#000000');
      $ind_cor = 0;
      foreach ($datay as $legenda => $dadosY)
      {
          $total_datay = 0;
          for ($i = 0; $i < sizeof($dadosY); $i++)
          {
             $total_datay += $dadosY[$i];
          }
          if ($total_datay != 0)
          {
              $cor_line = $cores[$ind_cor];
              $ind_cor++;
              $ind_cor  = ($ind_cor == sizeof($cores)) ? 0 : $ind_cor;
              switch ($tipo)
              {
                 case SC_GRAPH_PIE:
                    if ($pizza3d)
                    {
                        $pieplot = new PiePlot3D($dadosY);
                    }
                    else
                    {
                        $pieplot = new PiePlot($dadosY);
                    }
                    if ($this->sc_graf_sint)
                    {
                        $pieplot->SetLegends($label);
                    }
                    else
                    {
                        if (!$leg_geral)
                        {
                            $pieplot->SetLegends($label);
                            $leg_geral = true;
                        }
                        if (count($datay) > 1)
                        {
                            $pieplot->title->Set($legenda);
                        }
                        if ($qtd_col == $colunas)
                        {
                            $atual_col = $ini_col;
                            $qtd_col = 0;
                            $atual_lin += $incr_lin;
                        }
                        $pie_centerX = $atual_col / $img_width;
                        $atual_col += $incr_col;
                        $pie_centerY = $atual_lin / $img_height;
                        $pieplot->SetLabelPos(0.6);
                        $qtd_col++;
                    }
                    $pieplot->SetCenter($pie_centerX, $pie_centerY);
                    $pieplot->SetSize($pie_size);
                    $pieplot->SetSliceColors($cores);
                    if ($exibe_val == "S")
                    {
                        $pieplot->value->Show();
                    }
                    if ("ABS" != $tip_pizza)
                    {
                        $pieplot->SetValueType(PIE_VALUE_PER);
                        $pieplot->value->SetFormat("%01.2f%%");
                    }
                    else
                    {
                        $pieplot->SetValueType(PIE_VALUE_ABS);
                        $pieplot->value->SetFormat($format_pie_abs);
                    }
                    $graph->Add($pieplot);
                    $gerou_graf = true;
                 break;
                 case SC_GRAPH_LINE:
                    if (sizeof($dadosY) < 2)
                    {
                        continue;
                    }
                    $graph->legend->Pos(0);
                    $lineplot = new LinePlot($dadosY);
                    $lineplot->SetColor($cor_line);
                    $lineplot->SetWeight(2);
                    if ($preenche == "S")
                    {
                        $lineplot->SetFillColor($cor_line);;
                    }
                    if ($exibe_val == "S")
                    {
                        $lineplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
                        $lineplot->value->SetColor($cor_line);
                        $lineplot->value->SetAngle($this->graf_angulo);
                        $lineplot->value->SetFormat($format_pie_abs);
                        $lineplot->value->SetMargin(2);
                        $lineplot->value->Show();
                    }
                    if ($exibe_marc == "S")
                    {
                       if (!empty($this->graf_tipo_marcas))
                       {
                           $lineplot->mark->SetType($this->graf_tipo_marcas);
                       }
                       else
                       {
                           $lineplot->mark->SetType(MARK_SQUARE);
                       }
                       $lineplot->mark->SetFillColor($cor_line);
                       $lineplot->mark->SetWidth(4);
                    }
                    if ($renda)
                    {
                        $lineplot->SetStepStyle();
                    }
                    if (!$this->sc_graf_sint)
                    {
                        $lineplot->SetLegend($legenda);
                        $goup_graf_line[] = $lineplot;
                    }
                    else
                    {
                        $graph->Add($lineplot);
                    }
                    $gerou_graf = true;
                 break;
                 case SC_GRAPH_SCATTER:
                    $graph->legend->Pos(0);
                    $scatterplot = new ScatterPlot($dadosY);
                    $scatterplot->SetColor($cor_line);
                    $scatterplot->SetWeight(2);
                    if ($exibe_val == "S")
                    {
                        $scatterplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
                        $scatterplot->value->SetFormat($format_pie_abs);
                        $scatterplot->value->SetColor($cor_line);
                        $scatterplot->value->SetAngle($this->graf_angulo);
                        $scatterplot->value->SetMargin(20);
                        $scatterplot->value->Show();
                    }
                    if (!empty($this->graf_tipo_marcas))
                    {
                        $scatterplot->mark->SetType($this->graf_tipo_marcas);
                    }
                    else
                    {
                        $scatterplot->mark->SetType(MARK_SQUARE);
                    }
                    $scatterplot->mark->SetFillColor($cor_line);
                    $scatterplot->mark->SetWidth(4);;
                    if ($impulse)
                    {
                        $scatterplot->SetImpuls();
                    }
                    if (!$this->sc_graf_sint)
                    {
                        $scatterplot->SetLegend($legenda);
                    }
                    $graph->Add($scatterplot);
                    $gerou_graf = true;
                 break;
                 case SC_GRAPH_BAR:
                 default:
                    $graph->legend->Pos(0);
                    $barplot = new BarPlot($dadosY);
                    $barplot->SetFillColor($cor_line);
                    $barplot->SetWeight(1);
                    if ($exibe_val == "S")
                    {
                        $barplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
                        $barplot->value->SetFormat($format_pie_abs);
                        $barplot->value->SetColor($cor_line);
                        $barplot->value->SetAngle($this->graf_angulo);
                        $barplot->value->SetMargin(2);
                        $barplot->value->Show();
                    }
                    if (!$this->sc_graf_sint)
                    {
                        $barplot->SetLegend($legenda);
                        $goup_graf_bar[] =$barplot;
                    }
                    else
                    {
                        $graph->Add($barplot);
                    }
                    $gerou_graf = true;
                 break;
              }
          }
      }
      if (!empty($goup_graf_line))
      {
          $lineplot = new AccLinePlot ($goup_graf_line);
          $graph->Add($lineplot);
      }
      if (!empty($goup_graf_bar))
      {
          $barplot = new GroupBarPlot ($goup_graf_bar);
          $graph->Add($barplot);
      }
      if (isset($GLOBALS["php_errormsg"]) && "" == $GLOBALS["php_errormsg"])
      {
         unset($GLOBALS["php_errormsg"]);
      }
      if ($gerou_graf)
      {
          if(is_file($this->Ini->root . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg"))
          {
              @unlink($this->Ini->root . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg");
          }
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" || (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_bot']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_bot']))
          {
              if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_bot']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_bot'])
              {
                  
                  $lixo_graf = $graph->Stroke($this->Ini->root . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg");
                  $nm_saida->saida("<img src=\"" . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg\"/>\r\n");
                  $_SESSION['scriptcase']['sc_num_img']++;
              }
              else
              {
                  $lixo_graf = $graph->Stroke($this->Ini->root . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg");
                  $nm_saida->saida("<img src=\"" . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg\"/>\r\n");
                  $_SESSION['scriptcase']['sc_num_img']++;
              }
          }
          else
          {
              $tit_book_marks = str_replace(" ", "&nbsp;", $tit_graf);
              $lixo_graf = $graph->Stroke($this->Ini->root . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg");
              $nm_saida->saida("<B><H2 style=\\\"font-size: 0px;height:0px;\\\">$tit_book_marks</H2></B>\r\n");
              $nm_saida->saida("<img src=\"" . $this->Ini->path_imag_temp . "/sc_graf_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg\"/>\r\n");
              $_SESSION['scriptcase']['sc_num_img']++;
          }
      }
   }
//
}

?>
