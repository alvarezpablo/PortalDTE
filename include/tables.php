<?php 

	function sTituloCabecera($sTitulo){
		echo '<div class="screenTitle">
			<table width="100%" cellspacing="0">
				<tr>
					<td>' . $sTitulo . '</td>
					<td class="uplevel"></td>
				</tr>
			</table>
		</div>';	
	}

	function sCabeceraHerramienta($sId, $sTitulo){
		$strCabecera = '<div class="screenBody" id="' . $sId . '"> 
			<TABLE width="100%" cellspacing="0" cellpadding="0" border="0">
				<TR>
					<TD>
						<div class="toolsArea">
							<fieldset>
								<legend>' . $sTitulo . '</legend>
									<div class="screenButtons">
										@@_BOTONES_HERRAMIENTAS_@@
									</div>
							</fieldset>
						</div>
					</TD>
				</TR>
			</TABLE>	
		</div>	';

		return $strCabecera;
	}

	function sButtonHerramienta($id, $onMouseOver, $onMouseOut, $onClick, $sEtiqueta){

		$strButton = '<div class="commonButton" id="' . $id . '" name="' . $id . '" title="' . $sEtiqueta . '"  onMouseOver="' . $onMouseOver . '"  onMouseOut="' . $onMouseOut . '"  onClick="' . $onClick . '">
			<button name="bname_' . $id . '">'. $sEtiqueta .'</button>
			<span>'. $sEtiqueta .'</span>
		</div> ';							
		return $strButton;
	}

	function sAgregaHerramienta($idCabecera, $sTituCabecera, $aBotonHerramienta){
		
		$strCabecera = sCabeceraHerramienta($idCabecera, $sTituCabecera);
		$strButton = "";

		for($i=0; $i < sizeof($aBotonHerramienta["ID"]); $i++)
			$strButton .= sButtonHerramienta($aBotonHerramienta["ID"][$i], $aBotonHerramienta["ONMOUSEOVER"][$i], $aBotonHerramienta["ONMOUSEOUT"][$i], $aBotonHerramienta["ONCLICK"][$i], $aBotonHerramienta["SETIQUETA"][$i]);

		$strCabecera = str_replace("@@_BOTONES_HERRAMIENTAS_@@",$strButton,$strCabecera);
		echo $strCabecera;
	}


	function sTableLista(){
		
	}

	function sTablePaginacion($arrayPagina){
		$strPag = '<div class="paging">';

		for($i=0; $i < sizeof($arrayPagina); $i++)
			$strPag .= "<a href='" . $arrayPagina["URL"][$i] . "'>" . $arrayPagina["NUMPAG"][$i] . "</a>"; 

		$strPag .= '</div>';
	}

?>