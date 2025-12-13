<?php 
  include("../include/config.php");  
  include("../include/ver_aut.php");      
//  include("../include/ver_aut_adm.php");        
  include("../include/db_lib.php"); 
  include ("../include/upload_class.php"); 
  include ("../include/genera_dte.php"); 

  $conn = conn();
       
  $nCodEmp = $_SESSION["_COD_EMP_USU_SESS"];
  $nCodCorr = $_POST["nCodCorr"];				// correlativo
  $sEstado = $_POST["sEstado"];					// estado
  $nMotIvaNoRec = $_POST["nMotIvaNoRec"];					// estado
  $sTipFac = $_POST["sTipFac"];					// estado  

  $sRecinto = $_POST["sRecinto"];				// donde se recibe la mercaderia
  $sFirma = $_POST["sFirma"];					// rut de quien recibe mercaderia
  $nGeneraAcuse = $_POST["nGeneraAcuse"];			// genera acuse de recibo

  $nAprobacion = $_POST["nAprobacion"];		
  $sRespuesta =   $_POST["sRespuesta"];	
  $sGlosa     =   $_POST["sGlosa"];
  


  function dAceptar($conn){
	global $nCodCorr, $sEstado, $nMotIvaNoRec, $sTipFac, $nCodEmp, $sRecinto, $sFirma, $nGeneraAcuse, $nAprobacion, $sRespuesta, $sGlosa;

	$sql = "SELECT tipo_docu, REPLACE(REPLACE(fec_emi_doc,'-',''),'/',''), rut_rec_dte, dig_rec_dte, nom_rec_dte, dir_rec_dte, rut_emis_dte, digi_emis_dte, nom_emis_dte, mntneto_dte ,  mnt_exen_dte ,  tasa_iva_dte ,  iva_dte ,  giro_rec_dte ,  dir_orig_dte, com_rec_dte, ciud_rec_dte, tipo_prod_serv, mont_tot_dte, moti_nc_nd, tipo_doc_ref, fact_ref, doc_ref, com_orig_dte, ciud_orig_dte, giro_emis_dte FROM documentoscompras_temp WHERE correl_doc = '" . $nCodCorr . "'";
//echo $sql;
//exit;
	$result = rCursor($conn, $sql);  

	if(!$result->EOF){
		$nTipDoc = trim($result->fields["tipo_docu"]);
		$nRutClie = trim($result->fields["rut_rec_dte"]);
		$nTotal = trim($result->fields["mont_tot_dte"]);
		$nDteRecibe = trim($result->fields["fact_ref"]);
		

		$sql = "INSERT INTO documentoscompras(tipo_fact, moti_iva_norec,correl_doc, tipo_docu, fec_emi_doc, rut_rec_dte, dig_rec_dte, nom_rec_dte, dir_rec_dte, rut_emis_dte, digi_emis_dte, nom_emis_dte, mntneto_dte ,  mnt_exen_dte ,  tasa_iva_dte ,  iva_dte ,  giro_rec_dte ,  dir_orig_dte, com_rec_dte, ciud_rec_dte, tipo_prod_serv, mont_tot_dte, moti_nc_nd, tipo_doc_ref, fact_ref, doc_ref ) ";
		$sql .= " VALUES('" . str_replace("'","''",$sTipFac) . "','" . str_replace("'","''",$nMotIvaNoRec) . "', nextval('documentoscompras_correl_doc_seq') ";


		for($i=0; $i < 23; $i++)
			$sql .= "	,'" . str_replace("'","''",trim($result->fields[$i])) . "'";
		$sql .= ")";

		nrExecuta($conn, $sql);

		$sql = "SELECT currval('documentoscompras_correl_doc_seq') as cod ";
		$result2 = rCursor($conn, $sql);  		
		if(!$result2->EOF)
			$nCodNew = trim($result2->fields["cod"]);

		$sql = "SELECT num_lin_ddoc , codi_empr, tipo_docu, ind_exen_det, nom_item_det, desc_item_det, cant_item_det, prec_item_det, cant_item_det, prec_item_det, desc_porc_det,dcto_item_det,reca_porc_det,reca_item_det FROM detalle_doc_compras_temp WHERE correl_doc = '" . $nCodCorr . "'";
		$result2 = rCursor($conn, $sql);  

		while(!$result2->EOF){
			$sql = "INSERT INTO detalle_doc_compras( correl_doc, num_lin_ddoc , codi_empr, tipo_docu, ind_exen_det, nom_item_det, desc_item_det, cant_ref_det, prec_ref_det, cant_item_det, prec_item_det, desc_porc_det,dcto_item_det,reca_porc_det,reca_item_det) ";
			$sql .= " VALUES($nCodNew ";
			for($i=0; $i < 14; $i++)
				$sql .= "	,'" . str_replace("'","''",trim($result2->fields[$i])) . "'";
			$sql .= ")";
			nrExecuta($conn, $sql);
			$result2->MoveNext();
		}

		$sql = "UPDATE documentoscompras_temp SET est_doc = '" . str_replace("'","''",$sEstado) . " '";
		$sql .= " WHERE correl_doc = '" . $nCodCorr . "'";
		nrExecuta($conn, $sql);

		nMovi($conn, $nTipDoc, $nCodNew, $nRutClie, $nCodEmp, $nTotal);

		$sql = "SELECT cod_clie FROM clientes WHERE rut_cli = '" . str_replace("'","''",trim($result->fields["rut_rec_dte"])) . "' AND codi_empr = '" . str_replace("'","''",$nCodEmp) . "'";
		$result2 = rCursor($conn, $sql);  
		
		if($result2->EOF){	// sino existe el emisor se crea como cliente/proveedor
			$sql = " INSERT INTO clientes( ";
			$sql .= "   rut_cli, ";
			$sql .= "   dv_cli, ";
			$sql .= "   raz_social, ";
			$sql .= "   dir_clie,  ";
			$sql .= "   com_clie,  ";
			$sql .= "   giro_clie,  ";
			$sql .= "   ciud_cli,  ";
			$sql .= "   codi_empr, "; 
			$sql .= "   emi_elec_cli, "; 
			$sql .= "   acrec_email ) ";
			$sql .= " VALUES( ";
			$sql .= " '" . str_replace("'","''",trim($result->fields["rut_rec_dte"])) . "', ";
			$sql .= " '" . str_replace("'","''",trim($result->fields["dig_rec_dte"])) . "', ";
			$sql .= " '" . str_replace("'","''",trim($result->fields["nom_rec_dte"])) . "', ";
			$sql .= " '" . str_replace("'","''",trim($result->fields["dir_rec_dte"])) . "', ";
			$sql .= " '" . str_replace("'","''",trim($result->fields["com_orig_dte"])) . "', ";
			$sql .= " '" . str_replace("'","''",trim($result->fields["giro_emis_dte"])) . "', ";
			$sql .= " '" . str_replace("'","''",trim($result->fields["ciud_orig_dte"])) . "', ";
			$sql .= " '" . str_replace("'","''",$nCodEmp) . "', ";
			$sql .= " 'S', ";
			$sql .= " 'S') ";
			nrExecuta($conn, $sql);
		}

		if(trim($nGeneraAcuse) == "1"){
			$sql = "INSERT INTO recibo_mercaderias ( ";
			$sql .= "	codi_empr , ";
			$sql .= "       id, ";
			$sql .= "	rut_rec, ";
			$sql .= "	ndte_rec, ";
			$sql .= "	tipo_docu, ";
			$sql .= "	recinto, ";
			$sql .= "	rut_firma, ";
			$sql .= "	estado ) ";
			$sql .= " VALUES( ";
			$sql .= "       '" . $nCodEmp . "',";
			$sql .= "	nextval('recibo_mercaderias_id_seq'), ";
			$sql .= "	'" . str_replace("'","''", trim($result->fields["rut_rec_dte"])) . "',";
			$sql .= "	'" . str_replace("'","''", trim($nDteRecibe)) . "',";
			$sql .= "	'" . str_replace("'","''", trim($nTipDoc)) . "',";
			$sql .= "	'" . str_replace("'","''", trim($sRecinto)) . "',";
			$sql .= "	'" . str_replace("'","''", trim($sFirma)) . "', ";
			$sql .= "	0)";
			nrExecuta($conn, $sql);
		}

		if(trim($nAprobacion) == "1"){

			$sql =  "INSERT INTO public.respuestadterecepcionados ( ";
  			$sql .= "	rut_rec,";
  			$sql .= "	ndte_rec,";
  			$sql .= "	tipo_docu,";
  			$sql .= "	codi_empr,";
  			$sql .= "	estado,";
  			$sql .= "	glosa,";
  			$sql .= "	dv_rut_rec ";
			$sql .= ") ";
			$sql .= "VALUES ( ";
			$sql .= " 	'" . str_replace("'","''",trim($result->fields["rut_rec_dte"])) . "', ";
			$sql .= "	'" . str_replace("'","''", trim($nDteRecibe)) . "',";
			$sql .= "	'" . str_replace("'","''", trim($nTipDoc)) . "',";
			$sql .= " 	'" . str_replace("'","''",$nCodEmp) . "', ";
			$sql .= " 	'" . str_replace("'","''",$sRespuesta) . "', ";

			if ($sRespuesta == 0)
				$sGlosa = "DTE ACEPTADO OK";
			else if ($sRespuesta == 1)
				$sGlosa = "DTE ACEPTADO con Discrepancia - " . $sGlosa;
			else if ($sRespuesta == 2)
				$sGlosa = "DTE Rechazado - " . $sGlosa;

			$sql .= " 	'" . str_replace("'","''",$sGlosa) . "', ";
			$sql .= " 	'" . str_replace("'","''",trim($result->fields["dig_rec_dte"])) . "' ";
			$sql .= ")";


			nrExecuta($conn, $sql);

		}



	}		  
  }

  function nMovi($conn,$nTipDoc, $nCodDoc, $nRutClie, $nCodEmp, $nTotal){
			$sql = "UPDATE documentoscompras SET ";
			$sql .= "   cargo = '" . str_replace("'","''",$nTotal    ) . "', ";      
			$sql .= "   saldo = '" . str_replace("'","''",$nTotal    ) . "' ";      
			$sql .= " WHERE ";
			$sql .= "   correl_doc = " . $nCodDoc;    
			nrExecuta($conn, $sql);
			
			$sql = "DELETE FROM movimientocompras WHERE correl_doc = '" . str_replace("'","''",$nCodDoc) . "' ";     
			nrExecuta($conn, $sql);

			$sql = "INSERT INTO movimientocompras(  ";
			$sql .= "	  tipo_docu, ";
			$sql .= "	  rut_cli, ";
			$sql .= "	  codi_empr, ";
			$sql .= "	  correl_doc, ";
			$sql .= "	  cod_movi, ";
			$sql .= "	  det_movi, ";
			$sql .= "	  debe_movi, ";
			$sql .= "	  haber_movi, ";
			$sql .= "	  saldo_movi,  ";
			$sql .= "	  fecha_movi  ";
			$sql .= ")  ";
			$sql .= " VALUES( ";
			$sql .= "	'" . str_replace("'","''",$nTipDoc) . "', ";
			$sql .= "	'" . str_replace("'","''",$nRutClie) . "', ";
			$sql .= "	'" . str_replace("'","''",$nCodEmp) . "', ";
			$sql .= "	'" . str_replace("'","''",$nCodDoc) . "', ";

			if($nTipDoc == "60" || $nTipDoc == "61"){
				$sql .= "	'SALD_FAVOR' , ";
				$sql .= "	'SALDO A FAVOR POR NOTA DE CREDITO', ";
				$sql .= "	0, ";
				$sql .= "	'" . str_replace("'","''",$nTotal) . "', ";
			}
			else{
				$sql .= "	'CARGO_INIC' , ";
				$sql .= "	'CARGO POR DTE', ";
				$sql .= "	'" . str_replace("'","''",$nTotal) . "', ";
				$sql .= "	0, ";
			}
			$sql .= "	'" . str_replace("'","''",$nTotal) . "', ";
			$sql .= "	now()) ";

			nrExecuta($conn, $sql);
  }
  

  function dRechazar($conn){
	global $nCodCorr, $sEstado, $nMotIvaNoRec, $sTipFac, $nCodEmp, $sRecinto, $sFirma, $nGeneraAcuse, $nAprobacion, $sRespuesta, $sGlosa;

	$sql = "SELECT tipo_docu, REPLACE(REPLACE(fec_emi_doc,'-',''),'/',''), rut_rec_dte, dig_rec_dte, nom_rec_dte, dir_rec_dte, rut_emis_dte, digi_emis_dte, nom_emis_dte, mntneto_dte ,  mnt_exen_dte ,  tasa_iva_dte ,  iva_dte ,  giro_rec_dte ,  dir_orig_dte, com_rec_dte, ciud_rec_dte, tipo_prod_serv, mont_tot_dte, moti_nc_nd, tipo_doc_ref, fact_ref, doc_ref, com_orig_dte, ciud_orig_dte, giro_emis_dte FROM documentoscompras_temp WHERE correl_doc = '" . $nCodCorr . "'";
	$result = rCursor($conn, $sql);  
	
	if(!$result->EOF){
		$nTipDoc = trim($result->fields["tipo_docu"]);
		$nRutClie = trim($result->fields["rut_rec_dte"]);
		$nTotal = trim($result->fields["mont_tot_dte"]);
		$nDteRecibe = trim($result->fields["fact_ref"]);

		if(trim($nAprobacion) == "1"){

			$sql =  "INSERT INTO public.respuestadterecepcionados ( ";
  			$sql .= "	rut_rec,";
  			$sql .= "	ndte_rec,";
  			$sql .= "	tipo_docu,";
  			$sql .= "	codi_empr,";
  			$sql .= "	estado,";
  			$sql .= "	glosa,";
  			$sql .= "	dv_rut_rec ";
			$sql .= ") ";
			$sql .= "VALUES ( ";
			$sql .= " 	'" . str_replace("'","''",trim($result->fields["rut_rec_dte"])) . "', ";
			$sql .= "	'" . str_replace("'","''", trim($nDteRecibe)) . "',";
			$sql .= "	'" . str_replace("'","''", trim($nTipDoc)) . "',";
			$sql .= " 	'" . str_replace("'","''",$nCodEmp) . "', ";
			$sql .= " 	'" . str_replace("'","''",$sRespuesta) . "', ";

			if ($sRespuesta == 0)
				$sGlosa = "DTE ACEPTADO OK";
			else if ($sRespuesta == 1)
				$sGlosa = "DTE ACEPTADO con Discrepancia - " . $sGlosa;
			else if ($sRespuesta == 2)
				$sGlosa = "DTE Rechazado - " . $sGlosa;

			$sql .= " 	'" . str_replace("'","''",$sGlosa) . "', ";
			$sql .= " 	'" . str_replace("'","''",trim($result->fields["dig_rec_dte"])) . "' ";
			$sql .= ")";


			nrExecuta($conn, $sql);

		}


		
	}

	$sql = "UPDATE documentoscompras_temp SET est_doc = '" . str_replace("'","''",$sEstado) . " '";
	$sql .= " WHERE correl_doc = '" . $nCodCorr . "'";
    nrExecuta($conn, $sql);
  }

  
  switch ($sEstado) {
    case "ACEPTADO": 
        dAceptar($conn);
        break;
    
    case "RECHAZADO": 
        dRechazar($conn);
        break;

  }
  header("location:fin_newrecielec.php");
  exit;    
?>
