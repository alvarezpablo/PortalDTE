<?php

class grid_public_v_dte_resumo
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;
   var $total;
   var $tipo;
   var $nm_data;
   var $NM_export;
   var $prim_linha;
   var $que_linha;
   var $css_line_back; 
   var $css_line_fonf; 
   var $comando_grafico;
   var $resumo_campos;
   var $nm_location;
   var $Print_All;
   var $NM_raiz_img; 
   var $NM_tit_val; 
   var $NM_totaliz_hrz; 
   var $link_graph_tot; 
   var $Tot_ger; 
   var $array_total_geral;
   var $array_tot_lin;
   var $array_final;
   var $array_links;
   var $array_export;
   var $quant_colunas;
   var $conv_col;
   var $count_ger;

   //---- 
   function grid_public_v_dte_resumo($tipo = "")
   {
      $this->Graf_left_dat   = false;
      $this->Graf_left_tot   = false;
      $this->NM_export       = false;
      $this->NM_totaliz_hrz  = false;
      $this->link_graph_tot  = array();
      $this->array_final     = array();
      $this->array_links     = array();
      $this->array_export    = array();
      $this->resumo_campos   = array();
      $this->comando_grafico = array();
      $this->nm_data = new nm_data("es");
      if ("" != $tipo && "out" == strtolower($tipo))
      {
         $this->tipo = "out";
      }
      else
      {
         $this->tipo = "pag";
      }
      $this->nmgp_botoes['print']  = "on";
      $this->nmgp_botoes['pdf']  = "on";
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_public_v_dte']['btn_display'] as $NM_cada_btn => $NM_cada_opc)
          {
              $this->nmgp_botoes[$NM_cada_btn] = $NM_cada_opc;
          }
      }
   }

   //---- 
   function resumo_export()
   { 
      $this->NM_export = true;
      $this->monta_resumo();
   } 

   function monta_resumo($b_export = false)
   {
       global $nm_saida;

      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['res_hrz']))
      { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['res_hrz'] = $this->NM_totaliz_hrz;
      } 
      $this->NM_totaliz_hrz = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['res_hrz'];
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz . "grid_public_v_dte.php" ; 
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['array_graf_pdf'] = array();
      $this->resumo_init();
      $this->total->quebra_geral();
      foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['tot_geral'] as $ind => $val)
      {
           if ($ind > 0)
           {
               $this->array_total_geral[$ind - 1] = $val;
           }
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_array_resumo'] == "OK" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_total_geral'] == "OK")
      {
      }
      else
      {
          $this->totaliza();
          $this->finaliza_arrays();
      }
      $this->completeMatrix();
      $this->buildMatrix();
      $this->buildChart();
      if ($b_export)
      {
          return;
      }
      $this->drawMatrix();
      $this->resumo_final();
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_label_graf'] = array();
      if (isset($this->nmgp_label_quebras) && !empty($this->nmgp_label_quebras))
      {
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_label_graf'] = $this->nmgp_label_quebras;
      }
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['contr_array_resumo'] = "OK";
   }

   function completeMatrix()
   {
       $this->comp_align       = array();
       $this->comp_display     = array();
       $this->comp_field       = array();
       $this->comp_field_nm    = array();
       $this->comp_fill        = array();
       $this->comp_group       = array();
       $this->comp_index       = array();
       $this->comp_label       = array();
       $this->comp_links_fl    = array();
       $this->comp_links_gr    = array();
       $this->comp_order       = array();
       $this->comp_order_col   = '';
       $this->comp_order_level = '';
       $this->comp_order_sort  = '';
       $this->comp_sum         = array();
       $this->comp_sum_dummy   = array();
       $this->comp_sum_fn      = array();
       $this->comp_sum_lnk     = array();
       $this->comp_sum_css     = array();
       $this->comp_tabular     = false;
       $this->comp_totals_a    = array();
       $this->comp_totals_al   = array();
       $this->comp_totals_g    = array();
       $this->comp_totals_x    = array();
       $this->comp_totals_y    = array();
       $this->comp_x_axys      = array();
       $this->comp_y_axys      = array();

       $this->show_totals_x = true;
       $this->show_totals_y = true;
   }

   function arrangeLabelList($label, $level, $method) {
       $new_label = $label;

       if (0 == $level) {
           if ('reverse' == $method) {
               $new_label = array_reverse($new_label, true);
           }
           elseif ('asort' == $method) {
               asort($new_label);
           }
           else {
               ksort($new_label);
           }
       }
       else {
           foreach ($label as $i => $sub_label) {
               $new_label[$i] = $this->arrangeLabelList($sub_label, $level - 1, $method);
           }
       }

       return $new_label;
   }

   function getCompData($level, $params = array()) {

       if (array() == $params) {
           return $return;
       }

       foreach ($params as $i_param => $param) {
           if (!isset($return[$param])) {
               return 0;
           }

           $return = $return[$param];
       }

       return $return;
   }   

   function buildMatrix()
   {
       $this->build_labels = $this->getXAxys();
       $this->build_data   = $this->getYAxys();
   }

   function getXAxys()
   {
       $a_axys = array();

       if (0 == sizeof($this->comp_x_axys))
       {
           if (0 < sizeof($this->comp_sum))
           {
               foreach ($this->comp_sum as $i_sum => $l_sum)
               {
                   $chart    = '0|' . ($i_sum - 1) . '|';
                   $a_axys[] = array(
                       'group'    => 1,
                       'value'    => $i_sum,
                       'label'    => $l_sum,
                       'function' => $this->comp_sum_fn[$i_sum],
                       'params'   => array($i_sum - 1),
                       'children' => array(),
                       'chart'    => $chart,
                       'css'      => isset($this->comp_sum_css[$i_sum]) ? $this->comp_sum_css[$i_sum] : '',
                   );
               }
           }
           else
           {
               $a_axys = array();
           }
           $a_labels[] = $a_axys;
       }
       else
       {
           foreach ($this->comp_index[0] as $i_group => $l_group)
           {
               $a_params = array($i_group);
               $a_axys[] = array(
                   'group'    => 0,
                   'value'    => $i_group,
                   'label'    => $l_group,
                   'params'   => $a_params,
                   'children' => $this->addChildren(1, $this->comp_fill[1], $this->comp_group[$i_group], $a_params),
               );
           }

           $a_labels = array();
           $this->addChildrenLabel($a_axys, $a_labels);
       }

       if ($this->show_totals_x && 0 < sizeof($this->comp_x_axys))
       {
           $a_labels[0][] = array(
               'group'   => -1,
               'value'   => $this->Ini->Nm_lang['lang_othr_chrt_totl'],
               'label'   => $this->Ini->Nm_lang['lang_othr_chrt_totl'],
               'params'  => array(),
               'colspan' => sizeof($this->comp_sum),
               'rowspan' => sizeof($this->comp_x_axys),
           );
           foreach ($this->comp_sum as $i_sum => $s_label)
           {
               $a_labels[sizeof($this->comp_x_axys)][] = array(
                   'group'    => -1,
                   'value'    => $s_label,
                   'label'    => $s_label,
                   'function' => $this->comp_sum_fn[$i_sum],
                   'params'   => array(),
                   'chart'    => 'T|' . ($i_sum - 1),
                       'css'      => isset($this->comp_sum_css[$i_sum]) ? $this->comp_sum_css[$i_sum] : '',
               );
           }
       }

       return $a_labels;
   }

   function addChildren($group, $fill, $children, $params)
   {
       if (!isset($this->comp_x_axys[$group]))
       {
           if (0 < sizeof($this->comp_sum))
           {
               $a_sum = array();
               foreach ($this->comp_sum as $i_sum => $l_sum)
               {
                   $chart    = $group . '|' . ($i_sum - 1) . '|' . implode('|', $params);
                   $params_n = array_merge($params, array($i_sum - 1));
                   $a_sum[] = array(
                       'group'    => $group,
                       'value'    => $i_sum,
                       'label'    => $l_sum,
                       'function' => $this->comp_sum_fn[$i_sum],
                       'params'   => $params_n,
                       'children' => array(),
                       'chart'    => $chart,
                       'css'      => isset($this->comp_sum_css[$i_sum]) ? $this->comp_sum_css[$i_sum] : '',
                   );
               }
               return $a_sum;
           }
           else
           {
               return array();
           }
       }

       $a_axys = array();

       if ($fill)
       {
           foreach ($this->comp_index[$group] as $i_group => $l_group)
           {
               $params_n = array_merge($params, array($i_group));
               $a_axys[] = array(
                   'group'    => $group,
                   'value'    => $i_group,
                   'label'    => $l_group,
                   'params'   => $params_n,
                   'children' => $this->addChildren($group + 1, $this->comp_fill[$group + 1], $children[$i_group], $params_n),
               );
           }
       }
       else
       {
           foreach ($children as $i_group => $a_group)
           {
               $params_n = array_merge($params, array($i_group));
               $a_axys[] = array(
                   'group'    => $group,
                   'value'    => $i_group,
                   'label'    => $this->comp_index[$group][$i_group],
                   'params'   => $params_n,
                   'children' => $this->addChildren($group + 1, $this->comp_fill[$group + 1], $children[$i_group], $params_n),
               );
           }
       }

       return $a_axys;
   }

   function countChildren($children)
   {
       if (empty($children))
       {
           return 1;
       }

       $i = 0;
       foreach ($children as $data)
       {
           $i += $this->countChildren($data['children']);
       }
       return $i;
   }

   function addChildrenLabel($children, &$a_labels)
   {
       foreach ($children as $a_cols)
       {
           $a_labels[$a_cols['group']][] = array(
               'group'    => $a_cols['group'],
               'value'    => $a_cols['value'],
               'label'    => $a_cols['label'],
               'function' => isset($a_cols['function']) ? $a_cols['function'] : '',
               'params'   => $a_cols['params'],
               'colspan'  => $this->countChildren($a_cols['children']),
               'chart'    => isset($a_cols['chart']) ? $a_cols['chart'] : '',
               'css'      => isset($a_cols['css'])   ? $a_cols['css']   : '',
           );
           if (!empty($a_cols['children']))
           {
               $this->addChildrenLabel($a_cols['children'], $a_labels);
           }
       }
   }

   function getYAxys()
   {
       $a_axys = array();

       $this->addYChildren(0, $this->comp_group, $a_axys, array());
       $this->fixOrder($a_axys);
       $this->orderBy($a_axys, $this->comp_order_sort, $this->comp_order_col - 1, 0, array());
       $this->comp_chart_axys = $a_axys;

       $a_data              = array();
       $i_row               = 0;
       $this->subtotal_data = array();
       $this->addYChildrenData($a_axys, $a_data, $i_row, 0, array(), array());

       if (!empty($this->subtotal_data))
       {
           end($this->subtotal_data);
           $i_max = key($this->subtotal_data);
           for ($i = $i_max; $i >= 0; $i--)
           {
               $a_data[] = $this->subtotal_data[$i];
           }
       }

       $this->makeTabular($a_data);

       $this->buildTotalsY($a_data);

       return $a_data;
   }

   function addYChildren($group, $tree, &$axys, $param)
   {
       $comp_label = $this->comp_label[$group];
       $tmp_param  = $param;
       while (!empty($tmp_param))
       {
           $tmp_index  = array_shift($tmp_param);
           $comp_label = $comp_label[$tmp_index];
       }
       foreach ($comp_label as $i_group => $l_group)
       {
           if (isset($tree[$i_group]))
           {
               $new_param = array_merge($param, array($i_group));
               if (in_array($group, $this->comp_y_axys))
               {
                   if (!isset($axys[$i_group]))
                   {
                       $axys[$i_group] = array(
                           'group'    => $group,
                           'value'    => $i_group,
                           'label'    => $l_group,
                           'children' => array(),
                       );
                   }
                   $this->addYChildren($group + 1, $tree[$i_group], $axys[$i_group]['children'], $new_param);
               }
               else
               {
                   $this->addYChildren($group + 1, $tree[$i_group], $axys, $new_param);
               }
           }
       }
   }

   function fixOrder(&$axys)
   {
       $n_axys = array();
       $key    = key($axys);
       $group  = $axys[$key]['group'];

       foreach ($this->comp_index[$group] as $i_group => $l_group)
       {
           if (isset($axys[$i_group]))
           {
               $n_axys[$i_group] = $axys[$i_group];
           }
           if (!empty($n_axys[$i_group]['children']))
           {
               $this->fixOrder($n_axys[$i_group]['children']);
           }
       }

       $axys = $n_axys;
   }

   function orderBy(&$axys, $ord, $col, $level, $keys)
   {
       if (-1 == $col || '' == $ord)
       {
           return;
       }

       if ($this->comp_order_level <= $level)
       {
           $n_axys = array();
           $o_axys = array();

           foreach ($axys as $i_group => $d_group)
           {
               $o_axys[$i_group] = 0;
           }

           $a_order = $this->getOrderArray($this->getCompData($level), $ord, $col, $keys, $o_axys);

           foreach ($a_order as $i_group => $v_group)
           {
               $n_axys[$i_group] = $axys[$i_group];
           }

           $axys = $n_axys;
       }

       foreach ($axys as $i_group => $d_group)
       {
           if (!empty($d_group['children']))
           {
               $n_keys = array_merge($keys, array($i_group));
               $this->orderBy($axys[$i_group]['children'], $ord, $col, $level + 1, $n_keys);
           }
       }
   }

   function getOrderArray($data, $ord, $col, $keys, $elem)
   {
       while (!empty($keys))
       {
           $key = key($keys);

           if (isset($data[ $keys[$key] ]))
           {
               $data = $data[ $keys[$key] ];
           }

           unset($keys[$key]);
       }

       foreach ($elem as $i_group => $v_group)
       {
           if (isset($data[$i_group]) && isset($data[$i_group][$col]))
           {
               $elem[$i_group] = $data[$i_group][$col];
           }
       }

       if ('a' == $ord)
       {
           asort($elem);
       }
       else
       {
           arsort($elem);
       }

       return $elem;
   }

   function addYChildrenData($axys, &$data, &$row, $level, $params, $tab_col)
   {
       foreach ($axys as $i_data)
       {
           $params_n = array_merge($params, array($i_data['value']));
           if (sizeof($this->comp_y_axys) > $level + 1)
           {
               $tab_col[$level]['label'] = $i_data['label'];
               $tab_col[$level]['group'] = $i_data['group'];
           }
           $b_subtotal = !(!$this->comp_tabular || ($this->comp_tabular && sizeof($this->comp_y_axys) == $level + 1));
           if (1)
           {
               $new_data = array();
               if ($this->comp_tabular)
               {
                   foreach ($tab_col as $a_col_data)
                   {
                       $new_data[] = array(
                           'level'  => $level,
                           'label'  => $a_col_data['label'],
                           'link'   => in_array($a_col_data['group'], $this->comp_links_gr) ? $this->getLabelLink($params) : '',
                       );
                   }
               }
               if (!$b_subtotal)
               {
                   $new_data[] = array(
                       'level'  => $level,
                       'group'  => $i_data['group'],
                       'value'  => $i_data['value'],
                       'label'  => $i_data['label'],
                       'params' => $params_n,
                       'link'   => in_array($i_data['group'], $this->comp_links_gr) ? $this->getLabelLink($params_n) : '',
                   );
               }
               else
               {
                   $new_data[] = array(
                       'level'   => $level,
                       'group'   => $i_data['group'],
                       'value'   => $this->Ini->Nm_lang['lang_othr_chrt_totl'],
                       'label'   => $this->Ini->Nm_lang['lang_othr_chrt_totl'],
                       'params'  => $params_n,
                       'link'    => '',
                       'colspan' => sizeof($this->comp_y_axys) - sizeof($params_n),
                   );
               }
               $a_columns = 1 == sizeof($this->build_labels)
                          ? current($this->build_labels)
                          : $this->build_labels[sizeof($this->build_labels) - 1];
               if (0 < sizeof($this->comp_x_axys))
               {
                   $this->initTotalsX();
               }
               $i = 0;
               foreach ($a_columns as $a_col_data)
               {
                   if (-1 < $a_col_data['group'])
                   {
                       $val = $this->getCellValue($a_col_data['params'], $params_n);
                       $fmt = isset($a_col_data['params']) ? $a_col_data['params'][sizeof($a_col_data['params']) - 1] : 0;
                       $key = '';
                       $lnk = $this->getDataLinkParams($params_n, $a_col_data['params']);
                       if (1 == sizeof($this->comp_x_axys))
                       {
                           $key = $this->addTotalsG($i_data, $a_col_data, $params, $val);
                       }
                       unset($a_col_data['chart']);
                       if (sizeof($this->comp_y_axys) - 1 > $level)
                       {
                           $a_chart_params = $a_col_data['params'];
                           unset($a_chart_params[sizeof($a_col_data['params']) - 1]);
                           if (0 < sizeof($params_n))
                           {
                               for ($j = 0; $j < sizeof($params_n); $j++)
                               {
                                   $a_chart_params[] = $params_n[$j];
                               }
                           }
                           $a_col_data['chart'] = ($i_data['group'] + 1). '|' . $fmt . '|' . implode('|', $a_chart_params);
                       }
                       $new_data[] = array(
                           'level'     => -1,
                           'value'     => $val,
                           'format'    => $fmt,
                           'link_fld'  => $fmt + 1,
                           'link_data' => $lnk,
                           'chart'     => isset($a_col_data['chart']) ? $a_col_data['chart'] : '',
                           'css'       => isset($a_col_data['css'])   ? $a_col_data['css']   : '',
                           'subtotal'  => $b_subtotal,
                       );
                       if (0 < sizeof($this->comp_x_axys))
                       {
                           $i_col_x = array_pop($a_col_data['params']);
                           $this->addTotalsX($i_col_x, $val, $key);
                           if (0 == $level && 1 == sizeof($this->comp_x_axys))
                           {
                               $this->addTotalsA ('anal', $i_col_x, $val, $a_col_data['params'][0]);
                               $this->addTotalsAL('anal', $i_col_x, $val, $i_data['value']);
                           }
                       }
                       if (($this->comp_tabular || 0 == $level) && !$b_subtotal)
                       {
                           $this->addTotalsY($i, $val, $a_col_data['function'], $fmt);
                       }
                       $i++;
                   }
               }
               if (0 < sizeof($this->comp_x_axys))
               {
                   $this->buildTotalsX($new_data, $i, $level, $i_data['label'], $b_subtotal);
               }
               if (!$b_subtotal)
               {
                   $data[$row] = $new_data;
                   $row++;
               }
               elseif ($this->show_totals_y)
               {
                   if (!isset($this->subtotal_data[$level]))
                   {
                       $this->subtotal_data[$level] = $new_data;
                   }
                   else
                   {
                       end($this->subtotal_data);
                       $i_max = key($this->subtotal_data);
                       for ($i = $i_max; $i >= $level; $i--)
                       {
                           $data[$row] = $this->subtotal_data[$i];
                           $row++;
                           if ($i != $level)
                           {
                               unset($this->subtotal_data[$i]);
                           }
                       }
                       $this->subtotal_data[$level] = $new_data;
                   }
               }
           }
           $this->addYChildrenData($i_data['children'], $data, $row, $level + 1, $params_n, $tab_col);
       }
   }

   function getDataLinkParams($param, $col)
   {
       $a_par = array();

       if (1 < sizeof($col))
       {
           for ($i = 0; $i < sizeof($col) - 1; $i++)
           {
               $a_par[] = $col[$i];
           }
       }

       return implode('|', array_merge($a_par, $param));
   }

   function getDataLink($field, $data, $value)
   {
       if (!isset($this->comp_sum_lnk[$field]) || !$this->comp_sum_lnk[$field]['show'])
       {
           return $value;
       }

       $s_link_field = $this->comp_sum_lnk[$field]['field'];

       $a_link = array(
       );

       if (!isset($a_link[$s_link_field]))
       {
           return $value;
       }

       $a_data = explode('|', $data);
       $a_par  = array();
       $b_ok   = true;

       foreach ($a_link[$s_link_field]['param'] as $s_param => $a_param)
       {
           if ('C' == $a_param['type'])
           {
               if (!isset($a_data[ $this->comp_field_nm[ $a_param['value'] ] ]))
               {
                   $b_ok = false;
               }
               else
               {
                   $a_par[$s_param] = $a_data[ $this->comp_field_nm[ $a_param['value'] ] ];
               }
           }
           else
           {
               $a_par[$s_param] = $a_param['value'];
           }
       }

       if (!$b_ok)
       {
           return $value;
       }

       $b_modal = false;
       if (false !== strpos($a_link[$s_link_field]['html'], '__NM_FLD_PAR_M__'))
       {
           $b_modal                       = true;
           $a_link[$s_link_field]['html'] = str_replace('__NM_FLD_PAR_M__', '__NM_FLD_PAR__', $a_link[$s_link_field]['html']);
       }

       $return = str_replace('__NM_FLD_PAR__', $this->getDataLinkValue($a_par), $a_link[$s_link_field]['html']) . $value . '</a>';

       return $b_modal ? $this->getModalLink($return) :  $return;
   }

   function getDataLinkValue($param)
   {
       $a_links = array();

       foreach ($param as $i => $v)
       {
           $a_links[] = $i . '?#?' . $v;
       }

       return implode('?@?', $a_links);
   }

   function getModalLink($param)
   {
       return str_replace(array('?#?', '?@?'), array('*scin', '*scout'), $param);
   }

   function getLabelLink($param)
   {
       $a_links = array();

       foreach ($param as $i => $v)
       {
           $i_fld     = $i + sizeof($this->comp_x_axys);
           $a_links[] = $this->comp_links_fl[$i_fld]['name'] . '?#?' . $this->comp_links_fl[$i_fld]['prot'] . $v . $this->comp_links_fl[$i_fld]['prot'];
       }

       return implode('?@?', $a_links);
   }

   function getCellValue($aColPar, $aRowPar)
   {
       $i_tot = array_pop($aColPar);
       $a_val = (0 == sizeof($this->comp_x_axys))
              ? array_merge($aRowPar, array($i_tot))
              : array_merge($aColPar, $aRowPar, array($i_tot));
       return $this->getCompDataCell($a_val, $this->getCompData(sizeof($aColPar) + sizeof($aRowPar) - 1));
   }

   function getCompDataCell($par, $data)
   {
       $key = key($par);
       $cur = $par[$key];
       if (is_array($data[$cur]))
       {
           unset($par[$key]);
           return $this->getCompDataCell($par, $data[$cur]);
       }
       elseif (isset($data[$cur]))
       {
           return $data[$cur];
       }
       else
       {
           return 0;
       }
   }

   function makeTabular(&$a_data)
   {
       if ($this->comp_tabular)
       {
           $a_labels = array();
           foreach ($a_data as $row => $columns)
           {
               for ($i = 0; $i < sizeof($this->comp_y_axys) - 1; $i++)
               {
                   if (!isset($a_labels[$i]))
                   {
                       $a_labels[$i] = array(
                           'old'  => $columns[$i]['label'],
                           'row'  => $row,
                           'span' => 1,
                       );
                   }
                   elseif ($a_labels[$i]['old'] == $columns[$i]['label'])
                   {
                       unset($a_data[$row][$i]);
                       $a_labels[$i]['span']++;
                   }
                   else
                   {
                       $a_data[ $a_labels[$i]['row'] ][$i]['rowspan'] = $a_labels[$i]['span'];
                       $a_labels[$i]['old']  = $columns[$i]['label'];
                       $a_labels[$i]['row']  = $row;
                       $a_labels[$i]['span'] = 1;
                   }
               }
           }
           foreach ($a_labels as $i_col => $a_col_data)
           {
               $a_data[ $a_col_data['row'] ][$i_col]['rowspan'] = $a_col_data['span'];
           }
       }
   }

   function initTotalsX()
   {
       $this->comp_totals_x = array();

       if (!$this->show_totals_x)
       {
           return;
       }

       foreach ($this->comp_sum as $i_sum => $l_sum)
       {
           $this->comp_totals_x[$i_sum - 1] = array('values' => array(), 'chart' => '');
       }
   }

   function addTotalsX($col, $val, $chart)
   {
       if (!$this->show_totals_x)
       {
           return;
       }

       $this->comp_totals_x[$col]['chart']    = $chart;
       $this->comp_totals_x[$col]['values'][] = $val;
   }

   function buildTotalsX(&$row, $col, $level, $label, $sub)
   {
       if (!$this->show_totals_x)
       {
           return;
       }

       foreach ($this->comp_sum as $i_sum => $l_sum)
       {
           $i_temp[$i_sum - 1] = '';
       }

       $key = key($this->comp_totals_x);

       for ($i = 0; $i < sizeof($this->comp_totals_x[$key]['values']); $i++)
       {
           foreach ($this->comp_sum as $i_sum => $l_sum)
           {
               if ('' == $i_temp[$i_sum - 1])
               {
                   $i_temp[$i_sum - 1] = $this->comp_totals_x[$i_sum - 1]['values'][$i];
               }
               elseif ('M' == $this->comp_sum_fn[$i_sum])
               {
                   $i_temp[$i_sum - 1] = min($i_temp[$i_sum - 1], $this->comp_totals_x[$i_sum - 1]['values'][$i]);
               }
               elseif ('X' == $this->comp_sum_fn[$i_sum])
               {
                   $i_temp[$i_sum - 1] = max($i_temp[$i_sum - 1], $this->comp_totals_x[$i_sum - 1]['values'][$i]);
               }
               else
               {
                   $i_temp[$i_sum - 1] += $this->comp_totals_x[$i_sum - 1]['values'][$i];
               }
           }
       }
       foreach ($this->comp_sum as $i_sum => $l_sum)
       {
           if ('A' == $this->comp_sum_fn[$i_sum])
           {
               $i_temp[$i_sum - 1] /= sizeof($this->comp_totals_x[$i_sum - 1]['values']);
           }
           if ('P' == $this->comp_sum_fn[$i_sum])
           {
               $i_temp[$i_sum - 1] = 100.00;
           }
       }
       foreach ($this->comp_sum as $i_sum => $l_sum)
       {
           $row[] = array(
               'total'  => true,
               'level'  => -1,
               'value'  => $i_temp[$i_sum - 1],
               'format' => $i_sum - 1,
               'chart'  => $this->comp_totals_x[$i_sum - 1]['chart'],
           );
           if (0 == $level && 1 == sizeof($this->comp_x_axys))
           {
               $this->addTotalsA('sint', $i_sum - 1, $i_temp[$i_sum - 1], $label);
           }
           if (($this->comp_tabular || 0 == $level) && !$sub)
           {
               $this->addTotalsY($col + ($i_sum - 1), $i_temp[$i_sum - 1], $this->comp_sum_fn[$i_sum], $i_sum - 1);
           }
       }
   }

   function addTotalsA($mode, $col, $val, $label)
   {
       if (!isset($this->comp_totals_a[$col]))
       {
           $this->comp_totals_a[$col] = array(
               'labels' => array(),
               'values' => array(
                   'anal' => array(),
                   'sint' => array(),
               ),
           );
       }
       if ('sint' == $mode)
       {
           $this->comp_totals_a[$col]['labels'][]         = $label;
           $this->comp_totals_a[$col]['values']['sint'][] = $val;
       }
       elseif ('anal' == $mode)
       {
           if (isset($this->comp_index[ $this->comp_x_axys[0] ][$label]))
           {
               $label = $this->comp_index[ $this->comp_x_axys[0] ][$label];
           }
           $this->comp_totals_a[$col]['values']['anal'][$label][] = $val;
       }
   }

   function addTotalsAL($mode, $col, $val, $label)
   {
       if (!isset($this->comp_totals_al[$col]))
       {
           $this->comp_totals_al[$col] = array(
               'labels' => array(),
               'values' => array(
                   'anal' => array(),
                   'sint' => array(),
               ),
           );
       }
       if ('sint' == $mode)
       {
           $this->comp_totals_al[$col]['labels'][]         = $label;
           $this->comp_totals_al[$col]['values']['sint'][] = $val;
       }
       elseif ('anal' == $mode)
       {
           if (isset($this->comp_index[ $this->comp_y_axys[0] ][$label]))
           {
               $label = $this->comp_index[ $this->comp_y_axys[0] ][$label];
           }
           $this->comp_totals_al[$col]['values']['anal'][$label][] = $val;
       }
   }

   function addTotalsY($col, $val, $fun, $fmt)
   {
       if (!$this->show_totals_y)
       {
           return;
       }

       if (!isset($this->comp_totals_y[$col]))
       {
           $this->comp_totals_y[$col] = array(
               'format'   => $fmt,
               'function' => $fun,
               'values'   => array(),
           );
       }
       $this->comp_totals_y[$col]['values'][] = $val;
   }

   function buildTotalsY(&$matrix)
   {
       if (!$this->show_totals_y)
       {
           return;
       }

       $row = sizeof($matrix);

       $matrix[$row][] = array(
           'group'   => -1,
           'value'   => $this->Ini->Nm_lang['lang_othr_chrt_totl'],
           'label'   => $this->Ini->Nm_lang['lang_othr_chrt_totl'],
           'params'  => array(),
           'colspan' => $this->comp_tabular ? sizeof($this->comp_y_axys) : 1,
       );

       $aTotals = array();
       foreach ($this->comp_totals_y as $cols)
       {
           $iSum           = $this->buildColumnTotal($cols['function'], $cols['values']);
           $aTotals[]      = $iSum;
           $matrix[$row][] = array(
               'total'  => true,
               'level'  => -1,
               'value'  => $iSum,
               'format' => $cols['format'],
           );
       }

       if (1 == sizeof($this->comp_x_axys))
       {
           $i_count = 0;
           $aLabels = array();
           foreach ($this->comp_index[0] as $group_label)
           {
               $aLabels[] = $group_label;
               foreach ($this->comp_sum as $i_sum => $l_sum)
               {
                   $this->comp_totals_al[$i_sum - 1]['values']['sint'][] = $aTotals[$i_count];
                   $i_count++;
               }
           }
           foreach ($this->comp_sum as $i_sum => $l_sum)
           {
               $this->comp_totals_al[$i_sum - 1]['labels'] = $aLabels;
           }
       }
   }

   function addTotalsG($line, $column, $param, $value)
   {
       $s_item  = $column['params'][0];
       $i_total = $column['params'][1];
       $param[] = $line['value'];
       $s_key   = 'G|' . $i_total . '|' . implode('|', $param);

       if (!isset($this->comp_totals_g[$s_key]))
       {
           $this->comp_totals_g[$s_key] = array(
               'title'    => $this->getChartText($this->comp_sum[$i_total + 1]),
               'show_sub' => true,
               'subtitle' => $this->getChartText($this->getChartSubtitle($param, 1)),
               'label_x'  => $this->getChartText($this->comp_field[0]),
               'label_y'  => $this->getChartText($this->comp_sum[$i_total + 1]),
               'labels'   => array(),
               'values'   => array(
                   'sint' => array(0 => array()),
               ),
           );
       }

       $this->comp_totals_g[$s_key]['labels'][]            = isset($this->comp_index[0][$s_item]) ? $this->comp_index[0][$s_item] : $s_item;
       $this->comp_totals_g[$s_key]['values']['sint'][0][] = $value;

       return $s_key;
   }

   function buildColumnTotal($fun, $rows)
   {
       $total = '';

       foreach ($rows as $val)
       {
           if ('' == $total)
           {
               $total = $val;
           }
           elseif ('M' == $fun)
           {
               $total = min($total, $val);
           }
           elseif ('X' == $fun)
           {
               $total = max($total, $val);
           }
           else
           {
               $total += $val;
           }
       }

       if ('A' == $fun)
       {
           $total /= sizeof($rows);
       }
       if ('P' == $fun)
       {
           $total = 100.00;
       }

       return $total;
   }

   function buildChart()
   {
       $this->comp_chart_data   = array();

       $this->comp_chart_config = array(
           'T|0' => array(
               'title'    => $this->getChartText(""),
               'show_sub' => true,
               'subtitle' => "",
               'label_x'  => $this->getChartText(""),
               'label_y'  => $this->getChartText(""),
               'format'   => $this->formatChartValue(0),
           ),
       );

       $aTotalGeneral = false ? $this->comp_totals_al : $this->comp_totals_a;
       if (!empty($aTotalGeneral))
       {
           foreach ($aTotalGeneral as $i_total => $a_total)
           {
               if (isset($this->comp_chart_config['T|' . $i_total]))
               {
                   if (false)
                   {
                       $sTitleAxysX  = $this->comp_field[0];
                       $sTitleLegend = '' != $this->comp_chart_config[$key]['label_x']  ? $this->comp_chart_config[$key]['label_x']  : $this->getChartText($this->comp_field[sizeof($this->comp_x_axys)]);
                   }
                   else
                   {
                       $sTitleAxysX  = '' != $this->comp_chart_config[$key]['label_x']  ? $this->comp_chart_config[$key]['label_x']  : $this->getChartText($this->comp_field[sizeof($this->comp_x_axys)]);
                       $sTitleLegend = $this->comp_field[0];
                   }
                   $key = 'T|' . $i_total;
                   $this->comp_chart_data[$key] = array(
                       'title'    => '' != $this->comp_chart_config[$key]['title']    ? $this->comp_chart_config[$key]['title']    : $this->getChartText($this->Ini->Nm_lang['lang_othr_chrt_totl']),
                       'show_sub' => $this->comp_chart_config[$key]['show_sub'],
                       'subtitle' => '' != $this->comp_chart_config[$key]['subtitle'] ? $this->comp_chart_config[$key]['subtitle'] : $this->getChartText($this->comp_sum[$i_total + 1]),
                       'legend'   => $sTitleLegend,
                       'label_x'  => $sTitleAxysX,
                       'label_y'  => '' != $this->comp_chart_config[$key]['label_y']  ? $this->comp_chart_config[$key]['label_y']  : $this->getChartText($this->comp_sum[$i_total + 1]),
                       'labels'   => $a_total['labels'],
                       'values'   => array(
                           'anal' => $a_total['values']['anal'],
                           'sint' => array($a_total['values']['sint']),
                       ),
                   );
               }
           }
       }

       foreach ($this->comp_y_axys as $i_group)
       {
           $this->addGroupCharts($this->comp_chart_data, $this->getCompData($i_group), $i_group, $i_group, array());
       }

       if (!empty($this->comp_totals_g))
       {
           foreach ($this->comp_totals_g as $chart => $data)
           {
               $info = explode('|', $chart);
               $key  = 'G|' . (sizeof($info) - 2) . '|' . $info[1];
               if (isset($this->comp_chart_config[$key]))
               {
                   $this->comp_chart_data[$chart]             = $data;
                   $this->comp_chart_data[$chart]['show_sub'] = $this->comp_chart_config[$key]['show_sub'];
                   if ('' != $this->comp_chart_config[$key]['title'])
                   {
                       $this->comp_chart_data[$chart]['title'] = $this->comp_chart_config[$key]['title'];
                   }
                   if ('' != $this->comp_chart_config[$key]['subtitle'])
                   {
                       $this->comp_chart_data[$chart]['subtitle'] = $this->comp_chart_config[$key]['subtitle'];
                   }
                   if ('' != $this->comp_chart_config[$key]['label_x'])
                   {
                       $this->comp_chart_data[$chart]['label_x'] = $this->comp_chart_config[$key]['label_x'];
                   }
                   if ('' != $this->comp_chart_config[$key]['label_y'])
                   {
                       $this->comp_chart_data[$chart]['label_y'] = $this->comp_chart_config[$key]['label_y'];
                   }
               }
           }
       }

       $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pivot_charts'] = $this->comp_chart_data;
   }

   function formatChartValue($total)
   {
       $arr_param = array();

       if ($total == 0)
       {
           $arr_param = array(
               'numDecimals'        => "",
               'decimalSeparator'   => "",
               'thousandsSeparator' => "",
               'trailingZeros'      => "",
               'monetarySymbol'     => "",
               'monetaryPosition'   => "",
               'monetarySpace'      => "",
               'monetaryDecimal'    => "",
               'monetaryThousands'  => "",
           );
       }

       $aFormat   = array();
       $sFormat   = '';
       $sMonetIni = '';
       $sMonetEnd = '';

       if ('' != $arr_param['monetarySymbol'])
       {
           if ('' == $arr_param['monetaryPosition'] || 'left' == $arr_param['monetaryPosition'])
           {
               $sMonetIni = $arr_param['monetarySymbol'] . $arr_param['monetarySpace'];
               $sMonetEnd = '';
           }
           else
           {
               $sMonetIni = '';
               $sMonetEnd = $arr_param['monetarySpace'] . $arr_param['monetarySymbol'];
           }
           $arr_param['decimalSeparator']   = $arr_param['monetaryDecimal'];
           $arr_param['thousandsSeparator'] = $arr_param['monetaryThousands'];
       }
       if ('' == $arr_param['numDecimals'])
       {
           $arr_param['numDecimals'] = 0;
           unset($arr_param['trailingZeros']);
       }
       if ('' == $arr_param['trailingZeros'])
       {
           unset($arr_param['trailingZeros']);
       }
       unset($arr_param['monetarySymbol']);
       unset($arr_param['monetaryPosition']);
       unset($arr_param['monetarySpace']);
       unset($arr_param['monetaryDecimal']);
       unset($arr_param['monetaryThousands']);

       if (',' == $arr_param['decimalSeparator'])
       {
           unset($arr_param['decimalSeparator']);
           $arr_param['decimalSeparator'] = ',';
       }
       if (',' == $arr_param['thousandsSeparator'])
       {
           unset($arr_param['thousandsSeparator']);
           $arr_param['thousandsSeparator'] = ',';
       }

       foreach ($arr_param as $i => $v)
       {
           if ('' !== $v)
           {
               $aFormat[] = $i . ':' . $v;
           }
       }
       if (!empty($aFormat))
       {
           $sFormat = '{' . implode(',', $aFormat) . '}';
       }

       return $sMonetIni . '{%Value}' . $sFormat . $sMonetEnd;
   }

   function addGroupCharts(&$charts, $data, $group, $level, $param)
   {
       if (0 == $level)
       {
           $a_keys   = array();
           $a_totals = array();
           $this->getKeysTotals($a_keys, $a_totals, $data, $param);
           foreach ($a_totals as $i_total => $values)
           {
               $key_data  = $key_config = $group . '|' . $i_total;
               $key_data .= '|' . implode('|', $param);
               if (isset($this->comp_chart_config[$key_config]))
               {
                   $this->comp_chart_data[$key_data]                      = $this->comp_chart_config[$key_config];
                   $this->comp_chart_data[$key_data]['labels']            = $this->getGroupLabels($group, $a_keys);
                   $this->comp_chart_data[$key_data]['values']['sint'][0] = $values;
                   if (0 < $group && !empty($param))
                   {
                       $this->comp_chart_data[$key_data]['subtitle'] = $this->getChartText($this->getChartSubtitle($param));
                   }
                   if (0 == sizeof($this->comp_x_axys) && empty($param))
                   {
                       $this->getAnaliticCharts($i_total, $this->comp_chart_data[$key_data]);
                   }
               }
           }
       }
       else
       {
           foreach ($data as $key => $list)
           {
               $this->addGroupCharts($charts, $list, $group, $level - 1, array_merge($param, array($key)));
           }
       }
   }

   function getKeysTotals(&$a_keys, &$a_totals, $data, $param)
   {
       for ($i = 0; $i < sizeof($this->comp_x_axys); $i++)
       {
           $key_param = key($param);
           unset($param[$key_param]);
       }
       $list_data = $this->comp_chart_axys;
       foreach ($param as $now_param)
       {
           $list_data = $list_data[$now_param]['children'];
       }
       $list_data = array_keys($list_data);
       $size = sizeof($this->comp_sum_dummy);
       foreach ($list_data as $k_group)
       {
           if (isset($data[$k_group])) {
               $totals = $data[$k_group];
           }
           else {
               $totals = $this->comp_sum_dummy;
           }
           $a_keys[] = $k_group;
           $count    = 0;
           foreach ($totals as $i_total => $v_total)
           {
               if ($count == $size)
               {
                   break;
               }
               $a_totals[$i_total][] = $v_total;
               $count++;
           }
       }
       if (!empty($param))
       {
           $a_indexes = $this->getRealIndexes($this->comp_chart_axys, $param);
           foreach ($a_keys as $i => $v)
           {
               if (!in_array($v, $a_indexes))
               {
                   unset($a_keys[$i]);
                   foreach ($a_totals as $t => $l)
                   {
                       unset($a_totals[$t][$i]);
                   }
               }
           }
           $a_keys = array_values($a_keys);
           foreach ($a_totals as $t => $l)
           {
               $a_totals[$t] = array_values($a_totals[$t]);
           }
       }
   }

   function getRealIndexes($data, $param)
   {
       if (empty($param))
       {
           $a_indexes = array();
           foreach ($data as $i => $v)
           {
               $a_indexes[] = $i;
           }
           return $a_indexes;
       }
       else
       {
           $key = key($param);
           $val = $param[$key];
           unset($param[$key]);
           return $this->getRealIndexes($data[$val]['children'], $param);
       }
   }

   function getGroupLabels($group, $keys)
   {
       $a_labels = array();
       foreach ($keys as $key)
       {
           $a_labels[] = isset($this->comp_index[$group][$key]) ? $this->comp_index[$group][$key] : $key;
       }
       return $a_labels;
   }

   function getChartSubtitle($param, $s = 0)
   {
       $a_links = array();

       foreach ($param as $i => $v)
       {
           $a_links[] = $this->comp_field[$i + $s] . ' = ' . $this->comp_index[$i + $s][$v];
       }

       return implode(' :: ', $a_links);
   }

   function getAnaliticCharts($total, &$chart_data)
   {
       $chart_data['labels']         = array();
       $chart_data['legend']         = $this->comp_field[1];
       $chart_data['values']['anal'] = array();

       foreach ($this->comp_index[0] as $i_0 => $v_0)
       {
           $chart_data['labels'][] = $v_0;
       }
       foreach ($this->comp_index[1] as $i_1 => $v_1)
       {
           $chart_data['values']['anal'][$v_1] = array();
           foreach ($this->comp_index[0] as $i_0 => $v_0)
           {
               $vCompData                            = $this->getCompData(1, array($i_0, $i_1, $total));
               $chart_data['values']['anal'][$v_1][] = isset($vCompData) ? $vCompData : 0;
           }
       }
   }

   function getChartText($s)
   {
       if ('UTF-8' != $_SESSION['scriptcase']['charset'])
       {
           $s = mb_convert_encoding($s, 'UTF-8', $_SESSION['scriptcase']['charset']);
       }
       return function_exists('html_entity_decode') ? html_entity_decode($s) : $s;
   }

   function drawMatrix()
   {
       global $nm_saida;

       if ($this->NM_export)
       {
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['arr_export']['label'] = $this->build_labels;
           $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['arr_export']['data']  = $this->build_data;
           return;
       }

if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
{ 
       $nm_saida->saida("<script type=\"text/javascript\">\r\n");
       $nm_saida->saida("function summaryConfig() {\r\n");
       $nm_saida->saida("  tb_show('', 'grid_public_v_dte_config_pivot.php?nome_apl=grid_public_v_dte&sc_page=" . NM_encode_input($this->Ini->sc_page) . "&language=es&TB_iframe=true&modal=true&height=300&width=500', '');\r\n");
       $nm_saida->saida("}\r\n");
       $nm_saida->saida("function changeSort(col, ord) {\r\n");
       $nm_saida->saida("  document.refresh_config.change_sort.value = 'Y';\r\n");
       $nm_saida->saida("  document.refresh_config.sort_col.value = col;\r\n");
       $nm_saida->saida("  document.refresh_config.sort_ord.value = ord;\r\n");
       $nm_saida->saida("  document.refresh_config.submit();\r\n");
       $nm_saida->saida("}\r\n");
       $nm_saida->saida("</script>\r\n");
       $nm_saida->saida("<form name=\"refresh_config\" method=\"post\" action=\"grid_public_v_dte.php\" style=\"display: none\">\r\n");
       $nm_saida->saida("<input type=\"hidden\" name=\"nmgp_opcao\" value=\"resumo\" />\r\n");
       $nm_saida->saida("<input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\" />\r\n");
       $nm_saida->saida("<input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\" />\r\n");
       $nm_saida->saida("<input type=\"hidden\" name=\"change_sort\" value=\"N\" />\r\n");
       $nm_saida->saida("<input type=\"hidden\" name=\"sort_col\" />\r\n");
       $nm_saida->saida("<input type=\"hidden\" name=\"sort_ord\" />\r\n");
       $nm_saida->saida("</form>\r\n");
}
       $nm_saida->saida("<tr><td class=\"" . $this->css_scGridTabelaTd . "\">\r\n");
       $nm_saida->saida("<table class=\"scGridTabela\" style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\" width=\"100%\">\r\n");

       $this->drawMatrixLabels();

       $s_class = 'scGridFieldOdd';
       foreach ($this->build_data as $lines)
       {
           $this->prim_linha = false;
           $nm_saida->saida(" <tr>\r\n");
           foreach ($lines as $columns)
           {
               $this->NM_graf_left = $this->Graf_left_dat;
               if (0 <= $columns['level'])
               {
                   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
                   {
                       $s_label   = '' != $columns['link'] ? "<a href=\"javascript: nm_link_cons('" . $columns['link'] . "')\" class=\"scGridBlockLink\">" . $columns['label'] . '</a>' : $columns['label'];
                   }
                   else
                   {
                       $s_label   = $columns['label'];
                   }
                   $s_style   = '';
                   $s_text    = $this->comp_tabular ? $s_label : str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $columns['level']) . $s_label;
                   $s_class_v = 'scGridBlock';
               }
               else
               {
                   $s_style = '';
                   if (isset($columns['total']) && $columns['total'])
                   {
                       $s_style   = ' style="text-align: right"';
                       $s_text    = $this->formatValue($columns['format'], $columns['value']);
                       $s_class_v = 'scGridTotal';
                       $this->NM_graf_left = $this->Graf_left_tot;
                   }
                   elseif (isset($columns['subtotal']) && $columns['subtotal'])
                   {
                       $s_text    = $this->formatValue($columns['format'], $columns['value']);
                       $s_class_v = 'scGridSubtotal';
                   }
                   else
                   {
                       $s_text    = $this->getDataLink($columns['link_fld'], $columns['link_data'], $this->formatValue($columns['format'], $columns['value']));
                       $s_class_v = $s_class;
                   }
               }
               $css     = ('' != $columns['css']) ? ' ' . $columns['css'] . '_field' : '';
               $colspan = (isset($columns['colspan']) && 1 < $columns['colspan']) ? ' colspan="' . $columns['colspan'] . '"' : '';
               $rowspan = (isset($columns['rowspan']) && 1 < $columns['rowspan']) ? ' rowspan="' . $columns['rowspan'] . '"' : '';
               $chart   = (($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida']) && isset($columns['chart']) && '' != $columns['chart'] && isset($this->comp_chart_data[ $columns['chart'] ]))
                        ? nmButtonOutput($this->arr_buttons, "bgraf", "nm_graf_submit_2('" . $columns['chart'] . "')", "nm_graf_submit_2('" . $columns['chart'] . "')", "", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "" . $this->comp_chart_data[ $columns['chart'] ]['label_x'] . " X " . $this->comp_chart_data[ $columns['chart'] ]['label_y'] . "", "", "", "", "only_text", "text_right") : '';
               if ($this->NM_graf_left)
               {
                   $nm_saida->saida("  <td" . $s_style . " class=\"" . $s_class_v . ' ' . $s_class_v . "Font" . $css . "\"" . $colspan . "" . $rowspan . ">" . $chart . "" . $s_text . "</td>\r\n");
               }
               else
               {
                   $nm_saida->saida("  <td" . $s_style . " class=\"" . $s_class_v . ' ' . $s_class_v . "Font" . $css . "\"" . $colspan . "" . $rowspan . ">" . $s_text . "" . $chart . "</td>\r\n");
               }
           }
           $nm_saida->saida(" </tr>\r\n");
           if ('scGridFieldOdd' == $s_class)
           {
               $s_class                   = 'scGridFieldEven';
               $this->Ini->cor_link_dados = 'scGridFieldEvenLink';
           }
           else
           {
               $s_class                   = 'scGridFieldOdd';
               $this->Ini->cor_link_dados = 'scGridFieldOddLink';
           }
       }

       $nm_saida->saida("</table>\r\n");
       $nm_saida->saida("</td></tr>\r\n");
   }

   function drawMatrixLabels()
   {
       global $nm_saida;

       $this->prim_linha = true;

       $apl_cab_resumo = $this->Ini->Nm_lang['lang_othr_smry_msge'];

       $b_display = false;
       foreach ($this->build_labels as $lines)
       {
           $nm_saida->saida(" <tr class=\"scGridLabel\">\r\n");
           if (!$b_display)
           {
               $s_colspan = $this->comp_tabular ? ' colspan="' . sizeof($this->comp_y_axys) .'"' : '';
               $nm_saida->saida("  <td class=\"scGridLabelFont\" rowspan=\"" . sizeof($this->build_labels) . "\"" . $s_colspan . ">" . $apl_cab_resumo . "</td>\r\n");
               $b_display = true;
           }
           foreach ($lines as $columns)
           {
               $this->NM_graf_left = $this->Graf_left_dat;
               if (isset($columns['group']) && $columns['group'] == -1)
               {
                   $this->NM_graf_left = $this->Graf_left_tot;
               }
               if ('C' == $columns['function'])
               {
                   $style = ' style="text-align: right"';
               }
               elseif ('' == $columns['function'] && '' != $this->comp_align[ $columns['group'] ])
               {
                   $style = ' style="text-align: ' . $this->comp_align[ $columns['group'] ] . '"';
               }
               else
               {
                   $style = '';
               }
               $css       = ('' != $columns['css']) ? ' ' . $columns['css'] . '_label' : '';
               $colspan   = (isset($columns['colspan']) && 1 < $columns['colspan']) ? ' colspan="' . $columns['colspan'] . '"' : '';
               $rowspan   = (isset($columns['rowspan']) && 1 < $columns['rowspan']) ? ' rowspan="' . $columns['rowspan'] . '"' : '';
               $col_label = $this->getColumnLabel($columns['label'], $columns['params'][0]);
               $col_float = $columns['label'] != $col_label ? 'float: left' : '';
               $chart   = (($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida']) && isset($columns['chart']) && '' != $columns['chart'] && isset($this->comp_chart_data[ $columns['chart'] ]))
                        ? nmButtonOutput($this->arr_buttons, "bgraf", "nm_graf_submit_2('" . $columns['chart'] . "')", "nm_graf_submit_2('" . $columns['chart'] . "')", "", "", "", "$col_float", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "" . $this->comp_chart_data[ $columns['chart'] ]['label_x'] . " X " . $this->comp_chart_data[ $columns['chart'] ]['label_y'] . "", "", "", "", "only_text", "text_right") : '';
               if ($this->NM_graf_left)
               {
                   $nm_saida->saida("  <td" . $style . " class=\"scGridLabelFont" . $css . "\"" . $colspan . "" . $rowspan . ">" . $chart . "" . $col_label . "</td>\r\n");
               }
               else
               {
                   $nm_saida->saida("  <td" . $style . " class=\"scGridLabelFont" . $css . "\"" . $colspan . "" . $rowspan . ">" . $col_label . "" . $chart . "</td>\r\n");
               }
           }
           $nm_saida->saida(" </tr>\r\n");
       }
   }

   function getColumnLabel($label, $col)
   {
       if (0 != sizeof($this->comp_x_axys))
       {
           return $label;
       }

       return $label;
   }

   function formatValue($total, $valor_campo)
   {
       if ($total == 0)
       {
           nmgp_Form_Num_Val($valor_campo, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "");
       }
       return $valor_campo;
   }

   //---- 
   function resumo_init()
   {
      $this->monta_css();
      $this->inicializa_vars();
      if ($this->NM_export)
      {
          return;
      }
      if ("out" == $this->tipo)
      {
         $this->monta_html_ini();
         $this->monta_cabecalho();
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf")
         {
             $this->monta_barra_top();
         }
      }
      elseif ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf" || $this->Print_All)
      {
          $this->monta_cabecalho();
      }
   }

   function monta_css()
   {
      global $nm_saida, $nmgp_tipo_pdf, $nmgp_cor_print;
       $compl_css = "";
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       {
           include($this->Ini->path_btn . $this->Ini->Str_btn_grid);
       }
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
       {
          if (($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "print" && strtoupper($nmgp_cor_print) == "PB") || $nmgp_tipo_pdf == "pb")
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
       $this->css_scGridToolbar       = $compl_css . "scGridToolbar";
       $this->css_scGridToolbarPadd   = $compl_css . "scGridToolbarPadding";
       $this->css_css_toolbar_obj     = $compl_css . "css_toolbar_obj";
       $this->css_scGridHeader        = $compl_css . "scGridHeader";
       $this->css_scGridHeaderFont    = $compl_css . "scGridHeaderFont";
       $this->css_scGridFooter        = $compl_css . "scGridFooter";
       $this->css_scGridFooterFont    = $compl_css . "scGridFooterFont";
       $this->css_scGridTotal         = $compl_css . "scGridTotal";
       $this->css_scGridTotalFont     = $compl_css . "scGridTotalFont";
       $this->css_scGridFieldEven     = $compl_css . "scGridFieldEven";
       $this->css_scGridFieldEvenFont = $compl_css . "scGridFieldEvenFont";
       $this->css_scGridFieldEvenVert = $compl_css . "scGridFieldEvenVert";
       $this->css_scGridFieldEvenLink = $compl_css . "scGridFieldEvenLink";
       $this->css_scGridFieldOdd      = $compl_css . "scGridFieldOdd";
       $this->css_scGridFieldOddFont  = $compl_css . "scGridFieldOddFont";
       $this->css_scGridFieldOddVert  = $compl_css . "scGridFieldOddVert";
       $this->css_scGridFieldOddLink  = $compl_css . "scGridFieldOddLink";
       $this->css_scGridLabel         = $compl_css . "scGridLabel";
       $this->css_scGridLabelFont     = $compl_css . "scGridLabelFont";
       $this->css_scGridLabelLink     = $compl_css . "scGridLabelLink";
       $this->css_scGridTabela        = $compl_css . "scGridTabela";
       $this->css_scGridTabelaTd      = $compl_css . "scGridTabelaTd";
   }

   //---- 
   function resumo_final()
   {
      if ($this->NM_export)
      {
          return;
      }
      $this->monta_html_fim();
   }

   //---- 
   function inicializa_vars()
   {
      $this->Tot_ger = false;
      $this->Print_All = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['print_all'];
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
      { 
          $this->NM_raiz_img = $this->Ini->root; 
      } 
      else 
      { 
          $this->NM_raiz_img = ""; 
      } 
      if ($this->Print_All)
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] = "print";
          $this->Ini->nm_limite_lin = $this->Ini->nm_limite_lin_res_prt; 
      }
      else
      {
          $this->Ini->nm_limite_lin = $this->Ini->nm_limite_lin_res; 
      }
      $this->total   = new grid_public_v_dte_total($this->Ini->sc_page);
      $this->prep_modulos("total");
      if ($this->NM_export)
      {
          return;
      }
      $this->que_linha = "impar";
      $this->css_line_back = $this->css_scGridFieldOdd;
      $this->css_line_fonf = $this->css_scGridFieldOddFont;
      $this->Ini->cor_link_dados = $this->css_scGridFieldOddLink;
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
   function totaliza()
   {
      $this->total->resumo("res");
   }

   //----- 
   function monta_html_ini($first_table = true)
   {
      global $nm_saida, $nmgp_tipo_pdf, $nmgp_cor_print;

      if ($first_table)
      {

      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
      { 
          $nm_saida->saida("<TABLE style=\"padding: 0px; border-spacing: 0px; border-width: 0px;\" width=\"100%\">\r\n");
          return;
      } 
if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
{
       $nm_saida->saida("<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:word\" xmlns=\"http://www.w3.org/TR/REC-html40\">\r\n");
}
$nm_saida->saida("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\r\n");
$nm_saida->saida("            \"http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd\">\r\n");
      $nm_saida->saida("<HTML" . $_SESSION['scriptcase']['reg_conf']['html_dir'] . ">\r\n");
      $nm_saida->saida("<HEAD>\r\n");
      $nm_saida->saida(" <TITLE></TITLE>\r\n");
      $nm_saida->saida(" <META http-equiv=\"Content-Type\" content=\"text/html; charset=" . $_SESSION['scriptcase']['charset_html'] . "\" />\r\n");
if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
{
      $nm_saida->saida(" <META http-equiv=\"Expires\" content=\"Fri, Jan 01 1900 00:00:00 GMT\"/>\r\n");
      $nm_saida->saida(" <META http-equiv=\"Last-Modified\" content=\"" . gmdate("D, d M Y H:i:s") . " GMT\"/>\r\n");
      $nm_saida->saida(" <META http-equiv=\"Cache-Control\" content=\"no-store, no-cache, must-revalidate\"/>\r\n");
      $nm_saida->saida(" <META http-equiv=\"Cache-Control\" content=\"post-check=0, pre-check=0\"/>\r\n");
      $nm_saida->saida(" <META http-equiv=\"Pragma\" content=\"no-cache\"/>\r\n");
}
       $css_body = "";
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
      {
          if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_arab))
          {
              $css_body .= " font-family: Simplified Arabic;";
          }
          if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_jap))
          {
              $css_body .= " font-family: IPAGothic;";
          }
          if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_rus))
          {
              $css_body .= " font-family: Gentium;";
          }
          if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_chi))
          {
              $css_body .= " font-family: HanWangMingMedium;";
          }
          if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_ttf_thai))
          {
              $css_body .= " font-family: Courpro;";
          }
      }
      $nm_saida->saida(" <style type=\"text/css\">\r\n");
      $nm_saida->saida("  BODY { " . $css_body . " }\r\n");
       $nm_saida->saida(" .scGridTabela TD { white-space: nowrap }\r\n");
       $nm_saida->saida(" .res_NM_Count__field { text-align: right }\r\n");
      $nm_saida->saida(" </style>\r\n");
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
      { 
      $nm_saida->saida(" <script type=\"text/javascript\" src=\"" . $this->Ini->path_prod . "/third/jquery/js/jquery.js\"></script>\r\n");
      $nm_saida->saida(" <script type=\"text/javascript\">var sc_pathToTB = '" . $this->Ini->path_prod . "/third/jquery_plugin/thickbox/';</script>\r\n");
      $nm_saida->saida(" <script type=\"text/javascript\" src=\"" . $this->Ini->path_prod . "/third/jquery_plugin/thickbox/thickbox-compressed.js\"></script>\r\n");
      $nm_saida->saida(" <link rel=\"stylesheet\" href=\"" . $this->Ini->path_prod . "/third/jquery_plugin/thickbox/thickbox.css\" type=\"text/css\" media=\"screen\" />\r\n");
      $nm_saida->saida(" <link rel=\"stylesheet\" type=\"text/css\" href=\"../_lib/buttons/" . $this->Ini->Str_btn_css . "\" /> \r\n");
      } 
      if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] = rand(0, 1000);
      }
      $NM_css = @fopen($this->Ini->root . $this->Ini->path_imag_temp . '/sc_css_grid_public_v_dte_sum_' . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] . '.css', 'w');
      if (($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "print" && strtoupper($nmgp_cor_print) == "PB") || $nmgp_tipo_pdf == "pb")
      {
          $NM_css_file = $this->Ini->str_schema_all . "_grid_bw.css";
      }
      else
      {
          $NM_css_file = $this->Ini->str_schema_all . "_grid.css";
      }
      if (is_file($this->Ini->path_css . $NM_css_file))
      {
          $NM_css_attr = file($this->Ini->path_css . $NM_css_file);
          foreach ($NM_css_attr as $NM_line_css)
          {
              if (in_array(trim($this->Ini->str_lang), $this->Ini->nm_font_ttf))
              {
                  $tmp_pos = strpos($NM_line_css, "font-family");
                  if ($tmp_pos !== false)
                  {
                      $tmp_pos1 = strpos($NM_line_css, ";", $tmp_pos);
                      if ($tmp_pos1 === false)
                      {
                          $tmp_pos1 = strpos($NM_line_css, "}", $tmp_pos);
                      }
                      $NM_line_css = substr($NM_line_css, 0, $tmp_pos) . substr($NM_line_css, ($tmp_pos1 + 1));
                  }
              }
              $NM_line_css = str_replace("../../img", $this->Ini->path_imag_cab  , $NM_line_css);
              @fwrite($NM_css, "    " .  $NM_line_css . "\r\n");
          }
      }
      @fclose($NM_css);
     $this->Ini->summary_css = $this->Ini->path_imag_temp . '/sc_css_grid_public_v_dte_sum_' . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] . '.css';
     if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "print")
     {
         $nm_saida->saida("  <style type=\"text/css\">\r\n");
         $NM_css = file($this->Ini->root . $this->Ini->path_imag_temp . '/sc_css_grid_public_v_dte_sum_' . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] . '.css');
         foreach ($NM_css as $cada_css)
         {
              $nm_saida->saida("  " . str_replace("rn", "", $cada_css) . "\r\n");
         }
         $nm_saida->saida("  </style>\r\n");
     }
     else
     {
         $nm_saida->saida("   <link rel=\"stylesheet\" href=\"" . $this->Ini->summary_css . "\" type=\"text/css\" media=\"screen\" />\r\n");
     }
      $nm_saida->saida("</HEAD>\r\n");
      $nm_saida->saida(" <BODY class=\"" . $this->css_scGridPage . "\">\r\n");
      $nm_saida->saida("  " . $this->Ini->Ajax_result_set . "\r\n");
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
      { 
              $nm_saida->saida("  <H1 style=\"font-size: 0px;height:0px;\">" . $this->Ini->Nm_lang['lang_othr_smry_msge'] . "</H1>\r\n");
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "pdf" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
      { 
          $nm_saida->saida("      <STYLE>\r\n");
          $nm_saida->saida("       .ttip {border:1px solid black;font-size:12px;layer-background-color:lightyellow;background-color:lightyellow}\r\n");
          $nm_saida->saida("      </STYLE>\r\n");
          $nm_saida->saida("      <div id=\"tooltip\" style=\"position:absolute;visibility:hidden;border:1px solid black;font-size:12px;layer-background-color:lightyellow;background-color:lightyellow;padding:1px\"></div>\r\n");
      } 

      }

      $nm_saida->saida("<TABLE id=\"main_table_res\" class=\"scGridBorder\" align=\"center\" valign=\"top\" >\r\n");
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && !$this->Print_All && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
      { 
          $nm_saida->saida("    <TR>\r\n");
          $nm_saida->saida("    <TD align=\"right\"><pd4ml:page.footer> $[page]</TD>\r\n");
          $nm_saida->saida("    </TR>\r\n");
      } 
      $nm_saida->saida("<TR>\r\n");
      $nm_saida->saida("<TD style=\"padding: 0px\">\r\n");
      $nm_saida->saida("<TABLE style=\"padding: 0px; border-spacing: 0px; border-width: 0px; border-collapse: collapse\" width=\"100%\">\r\n");
   }

   //-----  top
   function monta_barra_top()
   {
      global $nm_url_saida, $nm_apl_dependente, $nm_saida;
      $HTTP_REFERER = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ""; 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] != "print" && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
      {
         $nm_saida->saida(" <TR class=\"" . $this->css_scGridPage . "\" align=\"center\" id=\"obj_barra_top\">\r\n");
         $nm_saida->saida("  <TD class=\"" . $this->css_scGridTabelaTd . "\">\r\n");
         $nm_saida->saida("   <TABLE class=\"" . $this->css_scGridToolbar . "\" style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\" width=\"100%\">\r\n");
         $nm_saida->saida("    <TR>\r\n");
         $nm_saida->saida("     <TD class=\"" . $this->css_scGridToolbarPadd . "\" align=\"center\">\r\n");
      if (!$this->grid_emb_form && $this->nmgp_botoes['print'] == "on")
      {
         $Cod_Btn = nmButtonOutput($this->arr_buttons, "bprint", "", "", "Rprint_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "thickbox", "" . $this->Ini->path_link . "grid_public_v_dte/grid_public_v_dte_config_print.php?nm_opc=resumo&nm_cor=AM&language=es&KeepThis=true&TB_iframe=true&modal=true", "", "only_text", "text_right");
         $nm_saida->saida("           $Cod_Btn \r\n");
      }
      if (!$this->grid_emb_form && $this->nmgp_botoes['pdf'] == "on")
      {
      }
      if (!$this->grid_emb_form)
      {
         $Cod_Btn = nmButtonOutput($this->arr_buttons, "smry_conf", "summaryConfig()", "summaryConfig()", "Rconfig_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right");
         $nm_saida->saida("           $Cod_Btn \r\n");
      }
      if (!$this->grid_emb_form)
      {
         $Cod_Btn = nmButtonOutput($this->arr_buttons, "blink_resumogrid", "nm_gp_move('inicio', '0')", "nm_gp_move('inicio', '0')", "Rrotac_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right");
         $nm_saida->saida("           $Cod_Btn \r\n");
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
                    if (!empty($Tmp1[0]) && isset($Tmp1[1]) && !empty($Tmp1[1]) && $Tmp1[0] == "res" && is_file($this->Ini->root . $this->Ini->path_help . $Tmp1[1]))
                    {
                        $Cod_Btn = nmButtonOutput($this->arr_buttons, "bhelp", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "nm_open_popup('" . $this->Ini->path_help . $Tmp1[1] . "')", "Rhelp_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right");
                        $nm_saida->saida("           $Cod_Btn \r\n");
                    }
                }
            }
         }
      if (!$this->grid_emb_form)
      {
         $Cod_Btn = nmButtonOutput($this->arr_buttons, "bvoltar", "nm_gp_move('busca', '0', '')", "nm_gp_move('busca', '0', '')", "Rsai_top", "", "", "", "absmiddle", "", "0px", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right");
         $nm_saida->saida("           $Cod_Btn \r\n");
      }
         $nm_saida->saida("     </TD>\r\n");
         $nm_saida->saida("    </TR>\r\n");
         $nm_saida->saida("   </TABLE>\r\n");
         $nm_saida->saida("  </TD>\r\n");
         $nm_saida->saida(" </TR>\r\n");
      }
   }

   //----- 
   function monta_html_fim()
   {
      global $nm_saida;
      $str_pbfile = $this->Ini->root . $this->Ini->path_imag_temp . '/sc_pb_' . session_id() . '.tmp';
      $nm_saida->saida("</TABLE>\r\n");
      $nm_saida->saida("</TD>\r\n");
      $nm_saida->saida("</TR>\r\n");
      $nm_saida->saida("</TABLE>\r\n");
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
      { 
          return;
      } 
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['doc_word'])
      { 
      $nm_saida->saida("</BODY>\r\n");
      $nm_saida->saida("</HTML>\r\n");
          return;
      } 
      $nm_saida->saida("<FORM name=\"F3\" method=\"post\" action=\"grid_public_v_dte.php\"\r\n");
      $nm_saida->saida("                                  target = \"_self\" style=\"display: none\"> \r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_chave\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_opcao\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_tipo_pdf\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_ordem\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_chave_det\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_quant_linhas\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_url_saida\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_parms\" value=\"\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_outra_jan\" value=\"\"/>\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_rotaciona\" value=\"\"/>\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"nmgp_orig_pesq\" value=\"\"/>\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\" />\r\n");
      $nm_saida->saida("<INPUT type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\" />\r\n");
      $nm_saida->saida("</FORM>\r\n");
      $nm_saida->saida("<form name=\"FRES\" method=\"post\" \r\n");
      $nm_saida->saida("                    action=\"\" \r\n");
      $nm_saida->saida("                    target=\"_self\" style=\"display: none\"> \r\n");
      $nm_saida->saida("<input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
      $nm_saida->saida("<input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\" />\r\n");
      $nm_saida->saida("</form> \r\n");
      $nm_saida->saida("<form name=\"Fgraf\" method=\"post\" \r\n");
      $nm_saida->saida("                   action=\"grid_public_v_dte.php\" \r\n");
      $nm_saida->saida("                   target=\"_self\" style=\"display: none\"> \r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"nmgp_opcao\" value=\"grafico\"/>\r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"campo\" value=\"\"/>\r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"nivel_quebra\" value=\"\"/>\r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"campo_val\" value=\"\"/>\r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"nmgp_parms\" value=\"\" />\r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"script_case_init\" value=\"" . NM_encode_input($this->Ini->sc_page) . "\"/> \r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"script_case_session\" value=\"" . NM_encode_input(session_id()) . "\" />\r\n");
      $nm_saida->saida("  <input type=\"hidden\" name=\"summary_css\" value=\"" . NM_encode_input($this->Ini->summary_css) . "\"/> \r\n");
      $nm_saida->saida("</form> \r\n");
      $nm_saida->saida("<SCRIPT language=\"Javascript\">\r\n");
      $nm_saida->saida("   function nm_gp_print_conf(tp, cor)\r\n");
      $nm_saida->saida("   {\r\n");
      $nm_saida->saida("       window.open('" . $this->Ini->path_link . "grid_public_v_dte/grid_public_v_dte_iframe_prt.php?path_botoes=" . $this->Ini->path_botoes . "&script_case_init=" . NM_encode_input($this->Ini->sc_page) . "&script_case_session=" . session_id() . "&opcao=res_print&cor_print=' + cor,'','location=no,menubar,resizable,scrollbars,status=no,toolbar');\r\n");
      $nm_saida->saida("   }\r\n");
      $nm_saida->saida(" function nm_gp_move(x, y, z, p, g) \r\n");
      $nm_saida->saida(" {\r\n");
      $nm_saida->saida("  if (x == 'rotaciona') \r\n");
      $nm_saida->saida("  {\r\n");
      $nm_saida->saida("      x = \"resumo\";\r\n");
      $nm_saida->saida("      document.F3.nmgp_rotaciona.value = \"S\"; \r\n");
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  document.F3.nmgp_opcao.value = x;\r\n");
      $nm_saida->saida("  document.F3.target = \"_self\"; \r\n");
      $nm_saida->saida("  if (y == 1) \r\n");
      $nm_saida->saida("  {\r\n");
      $nm_saida->saida("      document.F3.target = \"_blank\"; \r\n");
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  if (\"busca\" == x)\r\n");
      $nm_saida->saida("  {\r\n");
      $nm_saida->saida("      document.F3.nmgp_orig_pesq.value = z; \r\n");
      $nm_saida->saida("      z = '';\r\n");
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  if (z != null && z != '') \r\n");
      $nm_saida->saida("  {\r\n");
      $nm_saida->saida("     document.F3.nmgp_tipo_pdf.value = z;\r\n");
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  document.F3.script_case_init.value = \"" . NM_encode_input($this->Ini->sc_page) . "\" ;\r\n");
      $nm_saida->saida("  if (\"xls_res\" == x)\r\n");
      $nm_saida->saida("  {\r\n");
      if (!extension_loaded("zip"))
      {
      $nm_saida->saida("      alert (\"" . html_entity_decode($this->Ini->Nm_lang['lang_othr_prod_xtzp']) . "\");\r\n");
      $nm_saida->saida("      return false;\r\n");
      } 
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  if (\"pdf\" == x || \"pdf_res\" == x)\r\n");
      $nm_saida->saida("  {\r\n");
      if (!$_SESSION['scriptcase']['sc_java_install'])
      {
      $nm_saida->saida("      alert (\"" . html_entity_decode($this->Ini->Nm_lang['lang_othr_prod_java']) . "\");\r\n");
      $nm_saida->saida("      return false;\r\n");
      } 
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  if (\"pdf\" == x)\r\n");
      $nm_saida->saida("  {\r\n");
      $nm_saida->saida("      window.location = \"" . $this->Ini->path_link . "grid_public_v_dte/grid_public_v_dte_iframe.php?scsess=" . session_id() . "&str_tmp=" . $this->Ini->path_imag_temp . "&str_prod=" . $this->Ini->path_prod . "&str_btn=" . $this->Ini->Str_btn_css . "&str_lang=" . $this->Ini->str_lang . "&str_schema=" . $this->Ini->str_schema_all . "&script_case_init=" . NM_encode_input($this->Ini->sc_page) . "&script_case_session=" . session_id() . "&pbfile=" . urlencode($str_pbfile) . "&jspath=" . urlencode($this->Ini->path_js) . "&sc_apbgcol=" . urlencode($this->Ini->path_css) . "&sc_tp_pdf=\" + z;\r\n");
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  else\r\n");
      $nm_saida->saida("  {\r\n");
      $nm_saida->saida("      document.F3.submit();\r\n");
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida(" }\r\n");
      $nm_saida->saida(" function nm_gp_submit5(apl_lig, apl_saida, parms, target, opc, modal_h, modal_w) \r\n");
      $nm_saida->saida(" { \r\n");
      $nm_saida->saida("    if (apl_lig.substr(0, 7) == \"http://\" || apl_lig.substr(0, 8) == \"https://\")\r\n");
      $nm_saida->saida("    {\r\n");
      $nm_saida->saida("        if (target == '_blank') \r\n");
      $nm_saida->saida("        {\r\n");
      $nm_saida->saida("            window.open (apl_lig);\r\n");
      $nm_saida->saida("        }\r\n");
      $nm_saida->saida("        else\r\n");
      $nm_saida->saida("        {\r\n");
      $nm_saida->saida("            window.location = apl_lig;\r\n");
      $nm_saida->saida("        }\r\n");
      $nm_saida->saida("        return;\r\n");
      $nm_saida->saida("    }\r\n");
      $nm_saida->saida("    if (target == 'modal') \r\n");
      $nm_saida->saida("    {\r\n");
      $nm_saida->saida("        par_modal = '?script_case_init=" . NM_encode_input($this->Ini->sc_page) . "&script_case_session=" . session_id() . "&nmgp_outra_jan=true&nmgp_url_saida=modal';\r\n");
      $nm_saida->saida("        if (opc != null && opc != '') \r\n");
      $nm_saida->saida("        {\r\n");
      $nm_saida->saida("            par_modal += '&nmgp_opcao=grid';\r\n");
      $nm_saida->saida("        }\r\n");
      $nm_saida->saida("        if (parms != null && parms != '') \r\n");
      $nm_saida->saida("        {\r\n");
      $nm_saida->saida("            par_modal += '&nmgp_parms=' + parms;\r\n");
      $nm_saida->saida("        }\r\n");
      $nm_saida->saida("        tb_show('', apl_lig + par_modal + '&TB_iframe=true&modal=true&height=' + modal_h + '&width=' + modal_w, '');\r\n");
      $nm_saida->saida("        return;\r\n");
      $nm_saida->saida("    }\r\n");
      $nm_saida->saida("    document.F3.target               = target; \r\n");
      $nm_saida->saida("    if (target == '_blank') \r\n");
      $nm_saida->saida("    {\r\n");
      $nm_saida->saida("        document.F3.nmgp_outra_jan.value = \"true\" ;\r\n");
      $nm_saida->saida("    }\r\n");
      $nm_saida->saida("    document.F3.action               = apl_lig  ;\r\n");
      $nm_saida->saida("    if (opc != null && opc != '') \r\n");
      $nm_saida->saida("    {\r\n");
      $nm_saida->saida("        document.F3.nmgp_opcao.value = \"grid\" ;\r\n");
      $nm_saida->saida("    }\r\n");
      $nm_saida->saida("    else\r\n");
      $nm_saida->saida("    {\r\n");
      $nm_saida->saida("        document.F3.nmgp_opcao.value = \"\" ;\r\n");
      $nm_saida->saida("    }\r\n");
      $nm_saida->saida("    document.F3.nmgp_url_saida.value = apl_saida ;\r\n");
      $nm_saida->saida("    document.F3.nmgp_parms.value = parms ;\r\n");
      $nm_saida->saida("    document.F3.submit() ;\r\n");
      $nm_saida->saida("    document.F3.nmgp_outra_jan.value = \"\";\r\n");
      $nm_saida->saida("    document.F3.nmgp_parms.value = \"\";\r\n");
      $nm_saida->saida("    document.F3.action = \"grid_public_v_dte.php\";\r\n");
      $nm_saida->saida(" } \r\n");
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
      $nm_saida->saida("                  elm.style.top = nm_evt.pageY + 2;\r\n");
      $nm_saida->saida("                  elm.style.left = nm_evt.pageX + 14;\r\n");
      $nm_saida->saida("              }\r\n");
      $nm_saida->saida("              else\r\n");
      $nm_saida->saida("              {\r\n");
      $nm_saida->saida("                  elm.style.top = nm_evt.y + document.body.scrollTop + 10;\r\n");
      $nm_saida->saida("                  elm.style.left = nm_evt.x + document.body.scrollLeft + 10;\r\n");
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
      $nm_saida->saida(" function nm_graf_submit(campo, nivel, campo_val, parms, target) \r\n");
      $nm_saida->saida(" { \r\n");
      $nm_saida->saida("    document.Fgraf.campo.value = campo ;\r\n");
      $nm_saida->saida("    document.Fgraf.nivel_quebra.value = nivel ;\r\n");
      $nm_saida->saida("    document.Fgraf.campo_val.value = campo_val ;\r\n");
      $nm_saida->saida("    document.Fgraf.nmgp_parms.value   = parms ;\r\n");
      $nm_saida->saida("    if (target != null) \r\n");
      $nm_saida->saida("    {\r\n");
      $nm_saida->saida("        document.Fgraf.target = target; \r\n");
      $nm_saida->saida("    }\r\n");
      $nm_saida->saida("    document.Fgraf.submit() ;\r\n");
      $nm_saida->saida(" } \r\n");
      $nm_saida->saida(" function nm_graf_submit_2(chart)\r\n");
      $nm_saida->saida(" {\r\n");
      $nm_saida->saida("    var oldAction = document.Fgraf.action;\r\n");
      $nm_saida->saida("    document.Fgraf.action = nm_url_rand(document.Fgraf.action);\r\n");
      $nm_saida->saida("    document.Fgraf.nmgp_parms.value = chart;\r\n");
      $nm_saida->saida("    document.Fgraf.target = \"_blank\";\r\n");
      $nm_saida->saida("    document.Fgraf.submit();\r\n");
      $nm_saida->saida("    document.Fgraf.action = oldAction;\r\n");
      $nm_saida->saida(" } \r\n");
      $nm_saida->saida(" function nm_open_popup(parms)\r\n");
      $nm_saida->saida(" {\r\n");
      $nm_saida->saida("     NovaJanela = window.open (parms, '', 'resizable, scrollbars');\r\n");
      $nm_saida->saida(" }\r\n");
      $nm_saida->saida(" function nm_url_rand(v_str_url)\r\n");
      $nm_saida->saida(" {\r\n");
      $nm_saida->saida("  str_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';\r\n");
      $nm_saida->saida("  str_rand  = v_str_url;\r\n");
      $nm_saida->saida("  str_rand += (-1 == v_str_url.indexOf('?')) ? '?' : '&';\r\n");
      $nm_saida->saida("  str_rand += 'r=';\r\n");
      $nm_saida->saida("  for (i = 0; i < 8; i++)\r\n");
      $nm_saida->saida("  {\r\n");
      $nm_saida->saida("   str_rand += str_chars.charAt(Math.round(str_chars.length * Math.random()));\r\n");
      $nm_saida->saida("  }\r\n");
      $nm_saida->saida("  return str_rand;\r\n");
      $nm_saida->saida(" }\r\n");
      $nm_saida->saida("</SCRIPT>\r\n");
      $nm_saida->saida("</BODY>\r\n");
      $nm_saida->saida("</HTML>\r\n");
   }

   function monta_html_ini_pdf()
   {
      global $nm_saida;
       $tp_quebra = "";
       if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css']))
       {
           $NM_css = @fopen($this->Ini->root . $this->Ini->path_imag_temp . '/sc_css_grid_public_v_dte_grid_' . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['num_css'] . '.css', 'a');
           @fwrite($NM_css, " .res_NM_Count__field { text-align: right }\r\n");
           @fclose($NM_css);
       }
       $this->Print_All = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['print_all'];
       $tp_quebra = "<table><tr style=\"page-break-before:always;\"><td style=\"border: 0px;\"></td></tr></table>";
       if ($this->Print_All || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['print_all'])
       {
           $tp_quebra = "<table><tr><td style=\"page-break-before:always;\"></td></tr></table>";
       }
       $nm_saida->saida("" . $tp_quebra . "\r\n");
       $nm_saida->saida("<TABLE style=\"padding: 0px; border-spacing: 0px; border-width: 0px;\" align=\"center\" valign=\"top\" >\r\n");
       if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'] && !$this->Print_All && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['opcao'] == "pdf")
       { 
           $nm_saida->saida("    <TR>\r\n");
           $nm_saida->saida("    <TD align=\"right\"><pd4ml:page.footer> $[page]</TD>\r\n");
           $nm_saida->saida("    </TR>\r\n");
       } 
       $nm_saida->saida("<TR>\r\n");
       $nm_saida->saida("<TD style=\"padding: 0px\">\r\n");
       $nm_saida->saida("<TABLE style=\"padding: 0px; border-spacing: 0px; border-width: 0px;\" width=\"100%\">\r\n");
   }
   function monta_html_fim_pdf()
   {
      global $nm_saida;
      $nm_saida->saida("</TABLE>\r\n");
      $nm_saida->saida("</TD>\r\n");
      $nm_saida->saida("</TR>\r\n");
      $nm_saida->saida("</TABLE>\r\n");
   }
//--- 
//--- 
 function grafico_pdf()
 {
   global $nm_saida, $nm_lang;
   require_once($this->Ini->path_aplicacao . "grid_public_v_dte_grafico.class.php"); 
   $this->Graf  = new grid_public_v_dte_grafico();
   $this->Graf->Db     = $this->Db;
   $this->Graf->Erro   = $this->Erro;
   $this->Graf->Ini    = $this->Ini;
   $this->Graf->Lookup = $this->Lookup;
   if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pivot_charts']))
   {
       $nm_saida->saida("<B><H1 style=\"font-size: 0px;height:0px;\">" . $this->Ini->Nm_lang['lang_btns_chrt_pdff_hint'] . "</H1></B>\r\n");
       foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['pivot_charts'] as $chart_index => $chart_data)
       {
           $nm_saida->saida("<table><tr style=\"page-break-before:always;\"><td style=\"border: 0px;\"></td></tr></table>\r\n");
           $nm_saida->saida("<TABLE><TR><TD>\r\n");
           $this->Graf->monta_grafico($chart_index);
           $nm_saida->saida("</TD></TR></TABLE>\r\n");
       }
   }
   $nm_saida->saida("</body>\r\n");
   $nm_saida->saida("</HTML>\r\n");
 }
//--- 
//--- 
 function grafico_pdf_flash()
 {
   global $nm_saida, $nm_lang;
   if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['chart_list']))
   {
       foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['chart_list'] as $arr_chart)
       {
           $nm_saida->saida("<table><tr style=\"page-break-before:always;\"><td style=\"border: 0px;\"></td></tr></table>\r\n");
       $nm_saida->saida("<b><H1 style=\"font-size: 0px;height:0px;\">" . $this->Ini->Nm_lang['lang_btns_chrt_pdff_hint'] . "</H1></b>\r\n");
           $nm_saida->saida("<table><tr><td>\r\n");
           $tit_graf       = $arr_chart[1];
           $tit_book_marks = str_replace(" ", "&nbsp;", $tit_graf);
           $nm_saida->saida("<b><h2>$tit_book_marks</h2></b>\r\n");
           $nm_saida->saida("<img src=\"" . $arr_chart[0] . ".png\"/>\r\n");
           $_SESSION['scriptcase']['sc_num_img']++;
           $nm_saida->saida("</td></tr></table>\r\n");
       }
   }
   $nm_saida->saida("</body>\r\n");
   $nm_saida->saida("</HTML>\r\n");
 }
//--- 
   //----- 
   function monta_cabecalho()
   {
      global $nm_saida;
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['embutida'])
      { 
          return;
      } 
      $this->nm_data->SetaData(date("Y/m/d H:i:s"), "YYYY/MM/DD HH:II:SS");
      $nm_cab_filtro   = ""; 
      $nm_cab_filtrobr = ""; 
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']))
      { 
          $tipo_docu = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['tipo_docu']; 
          $tmp_pos = strpos($tipo_docu, "##@@");
          if ($tmp_pos !== false)
          {
              $tipo_docu = substr($tipo_docu, 0, $tmp_pos);
          }
          $folio_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['folio_dte']; 
          $tmp_pos = strpos($folio_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $folio_dte = substr($folio_dte, 0, $tmp_pos);
          }
          $fec_emi_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte']; 
          $tmp_pos = strpos($fec_emi_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $fec_emi_dte = substr($fec_emi_dte, 0, $tmp_pos);
          }
          $fec_emi_dte_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fec_emi_dte_input_2']; 
          $fech_carg = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg']; 
          $tmp_pos = strpos($fech_carg, "##@@");
          if ($tmp_pos !== false)
          {
              $fech_carg = substr($fech_carg, 0, $tmp_pos);
          }
          $fech_carg_2 = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['fech_carg_input_2']; 
          $est_xdte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['est_xdte']; 
          $tmp_pos = strpos($est_xdte, "##@@");
          if ($tmp_pos !== false)
          {
              $est_xdte = substr($est_xdte, 0, $tmp_pos);
          }
          $rut_rec_dte = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['campos_busca']['rut_rec_dte']; 
          $tmp_pos = strpos($rut_rec_dte, "##@@");
          if ($tmp_pos !== false)
          {
              $rut_rec_dte = substr($rut_rec_dte, 0, $tmp_pos);
          }
      } 
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
      $nm_saida->saida(" <TR class=\"" . $this->css_scGridPage . "\" align=\"center\">\r\n");
      $nm_saida->saida("  <TD class=\"" . $this->css_scGridTabelaTd . "\">\r\n");
      $nm_saida->saida("<style>\r\n");
      $nm_saida->saida("#lin1_col1 { padding-left:9px; padding-top:7px;  height:27px; overflow:hidden; text-align:left;}			 \r\n");
      $nm_saida->saida("#lin1_col2 { padding-right:9px; padding-top:7px; height:27px; text-align:right; overflow:hidden;   font-size:12px; font-weight:normal;}\r\n");
      $nm_saida->saida("</style>\r\n");
      $nm_saida->saida("<div style=\"width: 100%\">\r\n");
      $nm_saida->saida(" <div class=\"" . $this->css_scGridHeader . "\" style=\"height:11px; display: block; border-width:0px; \"></div>\r\n");
      $nm_saida->saida(" <div style=\"height:37px; background-color:#FFFFFF; border-width:0px 0px 1px 0px;  border-style: dashed; border-color:#ddd; display: block\">\r\n");
      $nm_saida->saida(" 	<table style=\"width:100%; border-collapse:collapse; padding:0;\">\r\n");
      $nm_saida->saida("    	<tr>\r\n");
      $nm_saida->saida("        	<td id=\"lin1_col1\" class=\"" . $this->css_scGridHeaderFont . "\"><span></span></td>\r\n");
      $nm_saida->saida("            <td id=\"lin1_col2\" class=\"" . $this->css_scGridHeaderFont . "\"><span></span></td>\r\n");
      $nm_saida->saida("        </tr>\r\n");
      $nm_saida->saida("    </table>		 \r\n");
      $nm_saida->saida(" </div>\r\n");
      $nm_saida->saida("</div>\r\n");
      $nm_saida->saida("  </TD>\r\n");
      $nm_saida->saida(" </TR>\r\n");
   }

   //---- 
   function monta_titulo()
   {
      global 
             $nm_saida;
      $this->prim_linha = true;
      $this->quant_colunas = 1;
      $apl_cab_resumo = $this->Ini->Nm_lang['lang_othr_smry_msge'];
      $span_lin_res = ($this->NM_totaliz_hrz) ? 2 : 1;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_total'] = $this->array_total_;
      $nm_saida->saida(" <TR class=\"" . $this->css_scGridPage . "\" align=\"center\">\r\n");
      $nm_saida->saida("  <TD class=\"" . $this->css_scGridTabelaTd . "\">\r\n");
      $nm_saida->saida("   <TABLE class=\"" . $this->css_scGridTabela . "\" style=\"padding: 0px; border-spacing: 0px; border-width: 0px; vertical-align: top;\" align=\"center\"  width=\"100%\">\r\n");
      $nm_saida->saida("    <TR align=\"center\" class=\"" . $this->css_scGridLabel . "\">\r\n");
      $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\" COLSPAN=" . $span_lin_res . ">" . $apl_cab_resumo . "</TD>\r\n");
      $span_lin_res = ($this->NM_totaliz_hrz) ? 1 : 1;
      $title_align = " align=\"right\"";
      ksort($this->array_total_);
      if (isset($this->array_total_))
      {
         foreach ($this->array_total_ as $campo_ => $dados_)
         {
            $campo_titulo = $campo_;
            $this->quant_colunas++;
      $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\" COLSPAN=" . $span_lin_res . " >" . $campo_titulo . "</TD>\r\n");
         }
      }
      $valor_campo = $this->Ini->Nm_lang['lang_othr_chrt_totl'];
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['del_graph_col']['tot']))
      {
          $valor_campo = "&nbsp;";
      }
      $nm_saida->saida("     <TD class=\"" . $this->css_scGridLabelFont . "\" COLSPAN=" . $span_lin_res . " >" . $valor_campo . "</TD>\r\n");
      $nm_saida->saida("    </TR>\r\n");
   }

   //---- 
   function monta_corpo()
   {
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linhas'] = array();
      $col = 0;
      $lin = 0;
      $this->conv_col = array();
      if ($this->NM_export)
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['arr_export'] = $this->array_export;
          return;
      }
      if (!empty($this->array_final))
      {
          foreach ($this->array_final as $lin => $this->resumo_campos)
          {
              if ($this->NM_totaliz_hrz)
              {
                  $IND = 1;
                  foreach ($this->NM_tit_val as $lixo => $campo_titulo)
                  {
                      $this->monta_linha($lin, $IND, $campo_titulo);
                      $IND++;
                  }
              }
              else
              {
                  $this->monta_linha($lin);
              }
          }
      }
   }

   function monta_tab($campo, $nivel, $lin, $col, $nome_dados)
   {
      if (!isset($this->array_final[$lin][0]))
      {
          $valor_campo = ("" == $campo)                  ? "&nbsp;" : $campo;
          $espacos  = str_repeat("&nbsp; &nbsp; &nbsp;", $nivel - 1);
          $this->array_export[$lin][0] = $nivel . $espacos . $valor_campo;
          $this->array_final[$lin][0]  = $nivel . $espacos . $valor_campo;
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linhas'][$lin][0] = $nivel . $espacos . $valor_campo;
      }
      foreach ($this->resumo_campos as $cmp_col => $Xvalores)
      {
          $valores   = array();
          $valores[] = $Xvalores[0];
          $col = $this->conv_col[$cmp_col];
          foreach ($valores as $i => $valor_campo)
          {
              $ind = $i + 1;
              if (isset($this->array_tot_lin[$lin][$ind]))
              {
                  $this->array_tot_lin[$lin][$ind] += $valor_campo;
              }
              else
              {
                  $this->array_tot_lin[$lin][$ind] = $valor_campo;
              }
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['graf_linhas'][$lin][$col][$ind] = $valor_campo;
              $this->array_export[$lin][$col][$ind] = $valor_campo;
              $this->array_final[$lin][$col][$ind] = $valor_campo;
          }
      }
   }

   //---- 
   function monta_linha($lin, $ind_tot=1, $tit_tot="")
   {
      global $nm_saida ;
      $HTTP_REFERER = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ""; 
      $Nivel = substr($this->resumo_campos[0], 0, 1);
      $this->prim_linha = false;
      if (!$this->NM_totaliz_hrz || $ind_tot == 1)
      {
          $tit_quebra = substr($this->resumo_campos[0], 1);
      }
      else
      {
          $tit_quebra = "&nbsp;";
      }
      $nm_saida->saida("    <TR class=\"" . $this->css_line_back . "\" align=\"right\" valign=\"middle\">\r\n");
      $nm_saida->saida("     <TD class=\"" . $this->css_line_fonf . "\"  align=\"left\">" . $tit_quebra . "</TD>\r\n");
      if ($this->NM_totaliz_hrz)
      {
      $nm_saida->saida("     <TD class=\"" . $this->css_line_fonf . "\"  align=\"left\" NOWRAP>" . $tit_tot . "</TD>\r\n");
      }
      for ($i = 1; $i < $this->quant_colunas; $i++)
      {
          for ($ind = 1; $ind <= 1; $ind++)
          {
              if (!$this->NM_totaliz_hrz || $ind_tot == $ind)
              {
                  $Img_lin   = $this->img_linha;
                  $Font_size = $this->fonte_tamanho;
                  $Cor_txt   = $this->cor_texto;
                  $Font_tipo = $this->fonte_tipo;
                  if (isset($this->resumo_campos[$i][$ind]) && ($Nivel == " "))
                  {
                       $valor_campo = $this->resumo_campos[$i][$ind];
                  }
                  else
                  {
                       $valor_campo = "";
                  }
                  if ("" == $valor_campo)
                  {
                      $valor_campo = "&nbsp;";
                  }
                  elseif ($ind == 1)
                  {
                      nmgp_Form_Num_Val($valor_campo, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "");
                      $css_usr = "";
                  }
                  if (isset($this->array_links[$lin][$i][$ind]))
                  {
                      $valor_campo = $this->array_links[$lin][$i][$ind] . $valor_campo . "</a>";
                  }
      $nm_saida->saida("     <TD class=\"" . $this->css_line_fonf . "\" " . $css_usr . " align=\"right\" NOWRAP>" . $valor_campo . "</TD>\r\n");
              }
          }
      }
      $img_html = "";
      for ($IND = 1; $IND <= 1; $IND++)
      {
           if (!$this->NM_totaliz_hrz || $ind_tot == $IND)
           {
               $Img_lin   = $this->img_linha;
               $Font_size = $this->fonte_tamanho;
               $Cor_txt   = $this->cor_texto;
               $Font_tipo = $this->fonte_tipo;
               if ($Nivel == " ")
               {
                   $valor_campo = $this->array_tot_lin[$lin][$IND];
                   if ($IND == 1)
                   {
                       nmgp_Form_Num_Val($valor_campo, $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "");
                       $css_usr = "";
                   }
               }
               else
               {
                   $valor_campo = "&nbsp;";
               }
               if ($lin == 0 && $this->NM_totaliz_hrz && isset($this->link_graph_tot[$IND]))
               {
                   $img_html = "&nbsp;" . $this->link_graph_tot[$IND];
               }
               if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_public_v_dte']['del_graph_col']['tot']))
               {
                   $valor_campo = "&nbsp;";
               }
      $nm_saida->saida("     <TD class=\"" . $this->css_line_fonf . "\" " . $css_usr . " align=\"right\" NOWRAP>" . $valor_campo . $img_html . "</TD>\r\n");
          }
      }
      $nm_saida->saida("    </TR>\r\n");
      if (!$this->Tot_ger)
      {
          $this->que_linha = ("impar" == $this->que_linha) ? "par" : "impar";
          if ("impar" == $this->que_linha)
          {
              $this->css_line_back = $this->css_scGridFieldOdd;
              $this->css_line_fonf = $this->css_scGridFieldOddFont;
              $this->Ini->cor_link_dados = $this->css_scGridFieldOddLink; 
          }
          else
          {
              $this->css_line_back = $this->css_scGridFieldEven;
              $this->css_line_fonf = $this->css_scGridFieldEvenFont;
              $this->Ini->cor_link_dados = $this->css_scGridFieldEvenLink; 
          }
      }
   }

   //---- 
   function monta_geral()
   {
      $this->Tot_ger = true;
      $this->css_line_back = $this->css_scGridTotal;
      $this->css_line_fonf = "";
      $this->resumo_campos = array();
      $total_geral = array();
      if (isset($this->array_total_))
      {
         $this->resumo_campos[] = " <B>" . $this->Ini->Nm_lang['lang_othr_totl'] . "</B>";
         $col = 1;
         foreach ($this->array_total_ as $campo_ => $dados_)
         {
            $valores   = array();
            $valores[0] = $dados_[0];
            $ind = 1;
            foreach ($valores as $i => $valor_campo)
            {
                if (isset($this->array_total_geral[$i]))
                {
                    $total_geral[$ind] = $this->array_total_geral[$i];
                }
                $this->resumo_campos[$col][$ind] = $valor_campo;
                $ind++;
             }
             $col++;
         }
         $this->array_tot_lin[0] = $total_geral;
         if ($this->NM_totaliz_hrz)
         {
             $ind = 1;
             foreach ($this->NM_tit_val as $lixo => $campo_titulo)
             {
                 $this->monta_linha(0, $ind, $campo_titulo);
                 $ind++;
             }
         }
         else
         {
             $this->monta_linha(0);
         }
      }
   }

   //---- 
   function monta_fim()
   {
      global $nm_saida;
      $nm_saida->saida("   </TABLE>\r\n");
      $nm_saida->saida("  </TD>\r\n");
      $nm_saida->saida(" </TR>\r\n");
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
