<SCRIPT LANGUAGE="JavaScript">
<!--
	rubros = new Array();
	subrubros = new Array();
	actividad = new Array();
<?php 
	$sql = "SELECT cod_rubro, nom_rubro FROM rubro ORDER BY nom_rubro";
	$result = rCursor($conn, $sql);  

	while (!$result->EOF) {
		echo "rubros[\"" . trim($result->fields["cod_rubro"]) . "\"] = \"" . trim($result->fields["nom_rubro"]) . "\";\n";
		echo "subrubros[\"" . trim($result->fields["cod_rubro"]) . "\"] = new Array();\n";
		echo "actividad[\"" . trim($result->fields["cod_rubro"]) . "\"] = new Array();\n";
		$result->MoveNext();
	}

	$sql = "SELECT cod_subrubro, cod_rubro, nom_subrubro FROM subrubro ORDER BY nom_subrubro ";
	$result = rCursor($conn, $sql);  

	while (!$result->EOF) {
		echo "subrubros[\"" . trim($result->fields["cod_rubro"]) . "\"][\"" . trim($result->fields["cod_subrubro"]) . "\"] = \"" . trim($result->fields["nom_subrubro"]) . "\";\n";
		echo "actividad[\"" . trim($result->fields["cod_rubro"]) . "\"][\"" . trim($result->fields["cod_subrubro"]) . "\"] = new Array();\n";
		$result->MoveNext();
	}

	$sql = "SELECT cod_rubro, cod_subrubro, cod_act, nom_act, cod_act_real FROM actividad ORDER BY nom_act";
	$result = rCursor($conn, $sql);  

	while (!$result->EOF) {
		echo "actividad[\"" . trim($result->fields["cod_rubro"]) . "\"][\"" . trim($result->fields["cod_subrubro"]) . "\"][\"" . trim($result->fields["cod_act"]) . "\"] = \"" . trim($result->fields["nom_act"]) . "\";\n";
		$result->MoveNext();
	}
?>	
	function llenaRubros(codAct){
		document.write("<option style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 9px;color:#FFFFFF;background:#DDDDDD;' value=''></option>\n");
		for (i in rubros){         
			document.write("<option style='font-weight: bold;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 9px;color:#000000;background:#FFFFFF;' value=''> " + rubros[i] + "</option>\n");
			for(j in subrubros[i]){
				document.write("<option style='font-weight: bold;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 9px;color:#444444;background:#FFFFFF;' value=''> &nbsp; " + subrubros[i][j] + "</option>\n");
				for(k in actividad[i][j]){
					if((codAct + "") == (i + j + k + ""))
						chChek = "selected";
					else
						chChek = "";

					document.write("<option style='font-style: italic;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 9px;color:#000000;background:#FFFFFF;' value='" + i + j + k + "' " + chChek + "> &nbsp;&nbsp;&nbsp; --> (" + i + j + k + ") " + actividad[i][j][k] + "</option>\n");
				}
			}
		}
	}

//-->
</SCRIPT>