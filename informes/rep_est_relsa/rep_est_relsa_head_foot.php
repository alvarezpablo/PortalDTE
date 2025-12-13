<?php
class Header_Footer extends PDF_Visibility
{
   var $nm_data;
   var $Nm_lang;
   function Header()
   {
            $str_lang = (isset($_SESSION['scriptcase']['str_lang']) && !empty($_SESSION['scriptcase']['str_lang'])) ? $_SESSION['scriptcase']['str_lang'] : "es";
            if (empty($this->Nm_lang))
            {
                include("../_lib/lang/" . $str_lang . ".lang.php");
            }
            $this->nm_data = new nm_data("es");
            $cell_5 = array('posx' => 230, 'posy' => 10, 'data' => $_SESSION['rep_est_relsa']['fech_carg'], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_6 = array('posx' => 200, 'posy' => 10, 'data' => 'Fecha DTE: ', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_7 = array('posx' => 250, 'posy' => 25, 'data' => 'Total', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_8 = array('posx' => 4, 'posy' => 25, 'data' => 'Empresa', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_9 = array('posx' => 90, 'posy' => 25, 'data' => 'Tipo DTE', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_10 = array('posx' => 180, 'posy' => 25, 'data' => 'Estado DTE', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_11 = array('posx' => 100, 'posy' => 10, 'data' => 'Reporte Estado DTE Empresas RELSA', 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 12, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
          
            $cell_total = array('posx' => 250, 'posy' => 0, 'data' => $_SESSION['rep_est_relsa']['total'], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_codi_empr = array('posx' => 5, 'posy' => 0, 'data' => $_SESSION['rep_est_relsa']['codi_empr'], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_tipo_docu = array('posx' => 80, 'posy' => 0, 'data' => $_SESSION['rep_est_relsa']['tipo_docu'], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_est_xdte = array('posx' => 160, 'posy' => 0, 'data' => $_SESSION['rep_est_relsa']['est_xdte'], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 10, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);


            $this->SetFont($cell_5['font_type'], $cell_5['font_style'], $cell_5['font_size']);
            $this->SetTextColor($cell_5['color_r'], $cell_5['color_g'], $cell_5['color_b']);
            if (!empty($cell_5['posx']) && !empty($cell_5['posy']))
            {
                $this->SetXY($cell_5['posx'], $cell_5['posy']);
            }
            elseif (!empty($cell_5['posx']))
            {
                $this->SetX($cell_5['posx']);
            }
            elseif (!empty($cell_5['posy']))
            {
                $this->SetY($cell_5['posy']);
            }
            $this->Cell($cell_5['width'], 0, $cell_5['data'], 0, 0, $cell_5['align']);

            $this->SetFont($cell_6['font_type'], $cell_6['font_style'], $cell_6['font_size']);
            $this->SetTextColor($cell_6['color_r'], $cell_6['color_g'], $cell_6['color_b']);
            if (!empty($cell_6['posx']) && !empty($cell_6['posy']))
            {
                $this->SetXY($cell_6['posx'], $cell_6['posy']);
            }
            elseif (!empty($cell_6['posx']))
            {
                $this->SetX($cell_6['posx']);
            }
            elseif (!empty($cell_6['posy']))
            {
                $this->SetY($cell_6['posy']);
            }
            $this->Cell($cell_6['width'], 0, $cell_6['data'], 0, 0, $cell_6['align']);

            $this->SetFont($cell_7['font_type'], $cell_7['font_style'], $cell_7['font_size']);
            $this->SetTextColor($cell_7['color_r'], $cell_7['color_g'], $cell_7['color_b']);
            if (!empty($cell_7['posx']) && !empty($cell_7['posy']))
            {
                $this->SetXY($cell_7['posx'], $cell_7['posy']);
            }
            elseif (!empty($cell_7['posx']))
            {
                $this->SetX($cell_7['posx']);
            }
            elseif (!empty($cell_7['posy']))
            {
                $this->SetY($cell_7['posy']);
            }
            $this->Cell($cell_7['width'], 0, $cell_7['data'], 0, 0, $cell_7['align']);

            $this->SetFont($cell_8['font_type'], $cell_8['font_style'], $cell_8['font_size']);
            $this->SetTextColor($cell_8['color_r'], $cell_8['color_g'], $cell_8['color_b']);
            if (!empty($cell_8['posx']) && !empty($cell_8['posy']))
            {
                $this->SetXY($cell_8['posx'], $cell_8['posy']);
            }
            elseif (!empty($cell_8['posx']))
            {
                $this->SetX($cell_8['posx']);
            }
            elseif (!empty($cell_8['posy']))
            {
                $this->SetY($cell_8['posy']);
            }
            $this->Cell($cell_8['width'], 0, $cell_8['data'], 0, 0, $cell_8['align']);

            $this->SetFont($cell_9['font_type'], $cell_9['font_style'], $cell_9['font_size']);
            $this->SetTextColor($cell_9['color_r'], $cell_9['color_g'], $cell_9['color_b']);
            if (!empty($cell_9['posx']) && !empty($cell_9['posy']))
            {
                $this->SetXY($cell_9['posx'], $cell_9['posy']);
            }
            elseif (!empty($cell_9['posx']))
            {
                $this->SetX($cell_9['posx']);
            }
            elseif (!empty($cell_9['posy']))
            {
                $this->SetY($cell_9['posy']);
            }
            $this->Cell($cell_9['width'], 0, $cell_9['data'], 0, 0, $cell_9['align']);

            $this->SetFont($cell_10['font_type'], $cell_10['font_style'], $cell_10['font_size']);
            $this->SetTextColor($cell_10['color_r'], $cell_10['color_g'], $cell_10['color_b']);
            if (!empty($cell_10['posx']) && !empty($cell_10['posy']))
            {
                $this->SetXY($cell_10['posx'], $cell_10['posy']);
            }
            elseif (!empty($cell_10['posx']))
            {
                $this->SetX($cell_10['posx']);
            }
            elseif (!empty($cell_10['posy']))
            {
                $this->SetY($cell_10['posy']);
            }
            $this->Cell($cell_10['width'], 0, $cell_10['data'], 0, 0, $cell_10['align']);

            $this->SetFont($cell_11['font_type'], $cell_11['font_style'], $cell_11['font_size']);
            $this->SetTextColor($cell_11['color_r'], $cell_11['color_g'], $cell_11['color_b']);
            if (!empty($cell_11['posx']) && !empty($cell_11['posy']))
            {
                $this->SetXY($cell_11['posx'], $cell_11['posy']);
            }
            elseif (!empty($cell_11['posx']))
            {
                $this->SetX($cell_11['posx']);
            }
            elseif (!empty($cell_11['posy']))
            {
                $this->SetY($cell_11['posy']);
            }
            $this->Cell($cell_11['width'], 0, $cell_11['data'], 0, 0, $cell_11['align']);

            $this->SetY(33);
   }
}
?>
