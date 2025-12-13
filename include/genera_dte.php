<?php 

$set = $_POST["set"];
$caso = $_POST["caso"];

  function gArchivoBoleta($conn, $nCodDoc, $nCodEmp){
        global $_PATH_REAL_DTE_BOLETA, $_AMOTIVO_NC_ND, $_EN_CERTIFICACION, $set, $caso;
//echo $_PATH_REAL_DTE_BOLETA;
//exit;
          $sql = " SELECT  ";
      $sql .= "   tipo_docu, ";
      $sql .= "   1, ";
      $sql .= "   correl_doc, ";
      $sql .= "   to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') as fec_emi_doc, ";
      $sql .= "   '1', ";
      $sql .= "   '', ";
      $sql .= "   per_desd_dte, ";
      $sql .= "   per_hast_dte, ";

          $sql .= "  (CASE WHEN fec_venc_dte='' THEN to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') ";
          $sql .= "   WHEN fec_venc_dte isnull  THEN to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') ";
      $sql .= "   ELSE to_char(to_date(fec_venc_dte, 'YYYYMMDD'),'YYYY-MM-DD') ";
      $sql .= "   END) as fec_venc_dte, ";
      $sql .= "   (rut_emis_dte || '-' || digi_emis_dte) as rut_emi, ";
      $sql .= "   nom_emis_dte, ";
          $sql .= "  (CASE WHEN giro_emis_dte = '' THEN (SELECT giro_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
          $sql .= "   WHEN giro_emis_dte isnull  THEN (SELECT giro_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
      $sql .= "   ELSE giro_emis_dte ";
      $sql .= "   END) as giro_emis_dte, ";
      $sql .= "   cod_sucu_dte, ";
      $sql .= "   dir_orig_dte, ";

          $sql .= "  (CASE WHEN com_orig_dte = '' THEN (SELECT com_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
          $sql .= "   WHEN com_orig_dte isnull  THEN (SELECT com_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
      $sql .= "   ELSE com_orig_dte ";
      $sql .= "   END) as com_orig_dte, ";
      $sql .= "   ciud_orig_dte, ";
      $sql .= "   (rut_rec_dte || '-' || dig_rec_dte) as rut_rec, ";
      $sql .= "   codi_rec_dte, ";
      $sql .= "   nom_rec_dte, ";
      $sql .= "   cont_rec_dte, ";
      $sql .= "   dir_rec_dte, ";
      $sql .= "   com_rec_dte, ";
      $sql .= "   ciud_rec_dte, ";
      $sql .= "   dir_post_dte, ";
      $sql .= "   com_post_dte, ";
      $sql .= "   ciud_post_dte, ";
          $sql .= "  (CASE  WHEN tipo_docu=41 THEN null ";
          $sql .= "                     WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
          $sql .= "                     WHEN tipo_doc_ref = 41 AND tipo_docu = 61 THEN 0 ";
          $sql .= "                     WHEN tipo_docu = 52 AND mnt_exen_dte > 0 THEN 0 ";
      $sql .= "   ELSE mntneto_dte ";
      $sql .= "   END) as mntneto_dte, ";
          $sql .= "  (CASE WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
          $sql .= "   ELSE CAST(mnt_exen_dte AS INT) ";
          $sql .= "   END) as mnt_exen_dte, ";
          $sql .= "  (CASE WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
          $sql .= "                     WHEN tipo_doc_ref = 41 THEN 0 ";
          $sql .= "   ELSE CAST(iva_dte AS INT) ";
          $sql .= "   END) as iva_dte, ";
          $sql .= "  (CASE WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
          $sql .= "   ELSE CAST(mont_tot_dte AS INT) ";
          $sql .= "   END) as mont_tot_dte, ";
      $sql .= "   '', ";
      $sql .= "   '', ";
          $sql .= "   sald_ant_dte, ";
      $sql .= "   valo_pag_dte ";
          $sql .= " FROM  ";
      $sql .= "   documentos ";
      $sql .= " WHERE  ";
      $sql .= "   correl_doc = '" . str_replace("'","''",$nCodDoc) . "'";

      $result = rCursor($conn, $sql);
      if(!$result->EOF){
                $nTipDoc = trim($result->fields[0]);
                $nFactRef = trim($result->fields["fact_ref"]);
                $nMotiRef = trim($result->fields["moti_nc_nd"]);
                $nTipoDocFactRef = trim($result->fields["tipo_doc_ref"]);

                for($i=0; $i < 34; $i++)
                  $strEnc .= trim($result->fields[$i]) . ";";
          }

        $strEnc = "ENC;" . $strEnc;             // ENCABEZADO


        $sql = " SELECT ";
        $sql .= "       num_lin_ddoc, ";

        $sql .= "  (CASE WHEN ind_exen_det = 0 THEN null ";
        $sql .= "   ELSE ind_exen_det ";
        $sql .= "   END) as ind_exen_det, ";

        $sql .= "  (CASE WHEN nom_item_det = '' THEN desc_item_det ";
        $sql .= "   WHEN nom_item_det isnull  THEN desc_item_det ";
        $sql .= "   ELSE nom_item_det ";
        $sql .= "   END) as nom_item_det, ";
        $sql .= "       desc_item_det, ";

        $sql .= "  (CASE WHEN cant_item_det = 0 THEN null ";
        $sql .= "   ELSE CAST(cant_item_det AS INT) ";
        $sql .= "   END) as cant_item_det, ";

        $sql .= "       unid_ref_det, ";

        $sql .= "  (CASE WHEN prec_ref_det = 0 THEN null ";
 


       $sql .= "   ELSE CAST(prec_ref_det AS INT) ";
/*
			CASE WHEN ind_exen_det != 1 THEN
				CAST(((prec_ref_det * (select (100 + tasa_iva_dte) from documentos where correl_doc = detalle_doc.correl_doc)) / 100) AS INT)
			ELSE
			        CAST(prec_ref_det AS INT)
			END  ";
*/

        $sql .= "   END) as prec_ref_det, ";



        $sql .= "  (CASE WHEN '" . $nMotiRef . "' = '2' AND tipo_docu = 61 THEN 0 ";
/*  
      $sql .= "   ELSE 
                        CASE WHEN ind_exen_det != 1 THEN
                                CAST(((prec_item_det * (select (100 + tasa_iva_dte) from documentos where correl_doc = detalle_doc.correl_doc)) / 100) AS INT)
                        ELSE
                                CAST(prec_item_det AS INT)  
                        END  ";
*/
        $sql .= "   ELSE CAST(prec_item_det AS INT) ";
        $sql .= "   END) as prec_item_det ";

        $sql .= " FROM ";
        $sql .= "   detalle_doc ";
        $sql .= " WHERE  ";
        $sql .= "   correl_doc = '" . str_replace("'","''",$nCodDoc) . "' ORDER BY num_lin_ddoc";
        $result = rCursor($conn, $sql);

        $j = 0;

        while(!$result->EOF){
                $strDetTmp = "";
                for($i=0; $i < 8; $i++)
                  $strDetTmp .= trim($result->fields[$i]) . ";";

                if($nMotiRef == '2' || ($nMotiRef == '1' && $nTipDoc == '56')){
                        if($j == 0)
                                $strDet = "DET;" . $strDetTmp . "\r\n" . $strDet;
                }
                else
                        $strDet .= "DET;" . $strDetTmp . "\r\n";

                $j++;
                $result->MoveNext();
        }

        if($nFactRef != ""){
                $sql = "SELECT tipo_docu, fec_emi_dte, rut_rec_dte, dig_rec_dte FROM dte_enc WHERE tipo_docu = '" . $nTipoDocFactRef . "' AND folio_dte = '" . $nFactRef . "'";
                $result = rCursor($conn, $sql);
                if(!$result->EOF){
                        $nTipDocRef = trim($result->fields["tipo_docu"]);
                        $dFecRef = trim($result->fields["fec_emi_dte"]);
                        $nRutRef = trim($result->fields["rut_rec_dte"]);
                        $sDigRef = trim($result->fields["dig_rec_dte"]);

                        if($_EN_CERTIFICACION == true){
                                $sRef = "REF;1;SET;;" . $set . ";;" . $dFecRef . ";;CASO " . $caso . ";\r\n";
                                $sRef .= "REF;2;" . $nTipDocRef . ";;" . $nFactRef . ";" . $nRutRef . "-" . $sDigRef . ";" . $dFecRef . ";" . $nMotiRef . ";" . $_AMOTIVO_NC_ND[$nMotiRef] . ";\r\n";
                        }
                        else
                                $sRef = "REF;1;" . $nTipDocRef . ";;" . $nFactRef . ";" . $nRutRef . "-" . $sDigRef . ";" . $dFecRef . ";" . $nMotiRef . ";" . $_AMOTIVO_NC_ND[$nMotiRef] . ";\r\n";
                }
        }
        else{
                 if($_EN_CERTIFICACION == true)
                                $sRef = "REF;1;SET;;" . $set . ";;" . date("Y-m-d") . ";;CASO " . $caso . ";\r\n";

        }



        /****** DESCUENTOS GLOBALES *********/

        $sql = "SELECT tmov_dr, tvalor_dr, valor_dr, index_dr FROM desrec_glob WHERE correl_doc = '" . $nCodDoc . "'";
        $result = rCursor($conn, $sql);
        $nLinDesc = 1;
        while (!$result->EOF){
                $sTipMov = trim($result->fields["tmov_dr"]);

                if($sTipMov == "D"){
                        $sTipMovDesc = trim($result->fields["tmov_dr"]);
                        $sTipDesc = trim($result->fields["tvalor_dr"]);
                        $sTipMntDesc = trim($result->fields["valor_dr"]);
                        $sTipIndDesc = trim($result->fields["index_dr"]);
                        $sLinDescG = "DESCG;" . $nLinDesc . ";D;DESCUENTO GLOBAL;" . $sTipDesc .";" . $sTipMntDesc . ";" . $sTipIndDesc . ";\r\n";
                        $nLinDesc++;
                }

                if($sTipMov == "R"){
                        $sTipMovRec = trim($result->fields["tmov_dr"]);
                        $sTipRec = trim($result->fields["tvalor_dr"]);
                        $sTipMntRec = trim($result->fields["valor_dr"]);
                        $sTipIndRec = trim($result->fields["index_dr"]);
                        $sLinRecG = "DESCG;" . $nLinDesc . ";R;DESCUENTO GLOBAL;" . $sTipDesc .";" . $sTipMntDesc . ";" . $sTipIndDesc . ";\r\n";
                        $nLinDesc++;
                }

                $result->MoveNext();
        }
        /************************************/

        $sNomDteFile = $_PATH_REAL_DTE_BOLETA . "dte-" . $nTipDoc . "-" . $nCodDoc . ".txt";                           // nombre del dte

        if ($gestor = fopen($sNomDteFile, 'w')){
                $contenido = $strEnc . "\r\n";
            //    $contenido .= $strAct . "\r\n";
                $contenido .= $strDet;

                if(($nFactRef != "" && $sRef != "") || $_EN_CERTIFICACION == true)
                        $contenido .= $sRef;

                if(trim($sLinDescG) <> "")
                        $contenido .= $sLinDescG;
                if(trim($sLinRecG) <> "")
                        $contenido .= $sLinRecG;

                if (fwrite($gestor, $contenido) === FALSE)
                        return false;
                else
                        return true;
        }
        else
                return false;
  }

  
  function gArchivo($conn, $nCodDoc, $nCodEmp){
	global $_PATH_REAL_DTE, $_AMOTIVO_NC_ND, $_EN_CERTIFICACION, $set, $caso;
	  $sql = " SELECT  ";
      $sql .= "   tipo_docu, ";
      $sql .= "   1, ";
      $sql .= "   correl_doc, ";
      $sql .= "   to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') as fec_emi_doc, ";
      $sql .= "   ind_nore_dte, ";
      $sql .= "   tip_desp_dte, ";
      $sql .= "   tt_prod_dte, ";
      $sql .= "   ind_pserv_dte, ";
      $sql .= "   ind_mntbruto_dte, ";
      $sql .= "   fma_pago_dte, ";

	  $sql .= "  (CASE WHEN fec_canc_dte='' THEN to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') ";
	  $sql .= "   WHEN fec_canc_dte isnull  THEN to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') ";
      $sql .= "   ELSE to_char(to_date(fec_canc_dte, 'YYYYMMDD'),'YYYY-MM-DD') ";
      $sql .= "   END) as fec_canc_dte, "; 

      $sql .= "   per_desd_dte, ";
      $sql .= "   per_hast_dte, ";
      $sql .= "   med_pago_dte, ";
      $sql .= "   codi_tepa_dte, ";
      $sql .= "   dias_tepa_dte, ";

  	  $sql .= "  (CASE WHEN fec_venc_dte='' THEN to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') ";
	  $sql .= "   WHEN fec_venc_dte isnull  THEN to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD') ";
      $sql .= "   ELSE to_char(to_date(fec_venc_dte, 'YYYYMMDD'),'YYYY-MM-DD') ";
      $sql .= "   END) as fec_venc_dte, "; 
	  
      $sql .= "   (rut_emis_dte || '-' || digi_emis_dte) as rut_emi, ";
      $sql .= "   nom_emis_dte, ";
      
  	  $sql .= "  (CASE WHEN giro_emis_dte = '' THEN (SELECT giro_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
	  $sql .= "   WHEN giro_emis_dte isnull  THEN (SELECT giro_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
      $sql .= "   ELSE giro_emis_dte ";
      $sql .= "   END) as giro_emis_dte, "; 
	  
	  $sql .= "   nom_sucu_dte, ";
      $sql .= "   cod_sucu_dte, ";
      $sql .= "   dir_orig_dte, ";

  	  $sql .= "  (CASE WHEN com_orig_dte = '' THEN (SELECT com_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
	  $sql .= "   WHEN com_orig_dte isnull  THEN (SELECT com_emp FROM empresa WHERE rut_empr = documentos.rut_emis_dte) ";
      $sql .= "   ELSE com_orig_dte ";
      $sql .= "   END) as com_orig_dte, "; 

      $sql .= "   ciud_orig_dte, ";
      $sql .= "   cod_vend_dte, ";
      $sql .= "   (rut_mand_dte || '-' || dig_mand_dte) as rut_mand, ";
      $sql .= "   (rut_rec_dte || '-' || dig_rec_dte) as rut_rec, ";
      $sql .= "   codi_rec_dte, ";
      $sql .= "   nom_rec_dte, ";
      $sql .= "   giro_rec_dte, ";
      $sql .= "   cont_rec_dte, ";
      $sql .= "   dir_rec_dte, ";
      $sql .= "   com_rec_dte, ";
      $sql .= "   ciud_rec_dte, ";
      $sql .= "   dir_post_dte, ";
      $sql .= "   com_post_dte, ";
      $sql .= "   ciud_post_dte, ";
      $sql .= "   (rut_solfa_dte || '-' || dig_solfa_dte) as rut_solfa, ";
      $sql .= "   pat_veh_dte, ";
      $sql .= "   (rut_tran_dte || '-' || dig_tran_dte) as rut_tran,  ";
      $sql .= "   dir_dest_dte, ";
      $sql .= "   com_dest_dte, ";
      $sql .= "   ciud_dest_dte, ";

	  $sql .= "  (CASE  WHEN tipo_docu=34 THEN null ";
	  $sql .= "			WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
	  $sql .= "			WHEN tipo_doc_ref = 34 AND tipo_docu = 61 THEN 0 ";	  
	  $sql .= "			WHEN tipo_docu = 52 AND mnt_exen_dte > 0 THEN 0 ";	  
      $sql .= "   ELSE mntneto_dte ";
      $sql .= "   END) as mntneto_dte, "; 

	  $sql .= "  (CASE WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
	  $sql .= "   ELSE CAST(mnt_exen_dte AS INT) ";
	  $sql .= "   END) as mnt_exen_dte, "; 

      $sql .= "   mnt_base_dte, ";	  

/*  Fecha  17-01-2007 
 *  Motivo: Segun requerimientos SII de tasa de iva para NC sobre Factura Exentas.+
 *  Accion: Correcion de Query   	  
      $sql .= "  (CASE WHEN tipo_docu=34 OR (tipo_docu = 61 AND moti_nc_nd = 2) THEN 0 ";
	  $sql .= "			WHEN tipo_doc_ref = 34 AND tipo_docu = 61 THEN 0 ";	  
	  $sql .= "			WHEN tipo_docu = 52 AND mnt_exen_dte > 0 THEN 0 ";	  
	  $sql .= "   ELSE tasa_iva_dte ";
      $sql .= "   END) as tasa_iva_dte, "; 
*/
//      $sql .= "   tasa_iva_dte, ";

      $sql .= "  (CASE WHEN tipo_docu=34  THEN 0 ";
	  $sql .= "   ELSE tasa_iva_dte ";
      $sql .= "   END) as tasa_iva_dte, ";       
      
	  $sql .= "  (CASE WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
	  $sql .= "			WHEN tipo_doc_ref = 34 THEN 0 ";	  
	  $sql .= "   ELSE CAST(iva_dte AS INT) ";
	  $sql .= "   END) as iva_dte, "; 

      $sql .= "   '','', ";

// 20/01/2010

  //        $sql .= "  (CASE WHEN tipo_docu = 52 THEN mont_tot_dte ";
    //      $sql .= "   ELSE (mnt_exen_dte + mntneto_dte) ";
      //    $sql .= "   END) as subtotal, ";
$sql .= "   '', ";
  //    $sql .= "   (mnt_exen_dte + mntneto_dte) , "; // Subtotal


      $sql .= "   '', ";
      $sql .= "   '', ";

	  $sql .= "  (CASE WHEN moti_nc_nd = 2 AND tipo_docu = 61 THEN 0 ";
	  $sql .= "   ELSE CAST(mont_tot_dte AS INT) ";
	  $sql .= "   END) as mont_tot_dte, "; 
	  
	  $sql .= "   sald_ant_dte, ";
      $sql .= "   valo_pag_dte, ";

      $sql .= "   fact_ref, ";
      $sql .= "   moti_nc_nd, ";	  	  

      $sql .= "   tipo_desc, ";      
      $sql .= "   mnt_desc, tipo_doc_ref ";      
	  
	  $sql .= " FROM  ";
      $sql .= "   documentos ";
      $sql .= " WHERE  ";
      $sql .= "   correl_doc = '" . str_replace("'","''",$nCodDoc) . "'";
      
$result = rCursor($conn, $sql);
      if(!$result->EOF){
		$nTipDoc = trim($result->fields[0]);
		$nFactRef = trim($result->fields["fact_ref"]);
		$nMotiRef = trim($result->fields["moti_nc_nd"]);
		$nTipoDocFactRef = trim($result->fields["tipo_doc_ref"]);

//        $sTipDesc = trim($result->fields["tipo_desc"]);        		
//        $nMntDesc = trim($result->fields["mnt_desc"]);        		


		for($i=0; $i < 57; $i++)
		  $strEnc .= trim($result->fields[$i]) . ";";
	  }

	$strEnc = "ENC;" . $strEnc;		// ENCABEZADO

	$sql = "SELECT cod_act FROM empresa WHERE codi_empr = '" . $nCodEmp . "'";	
	$result = rCursor($conn, $sql);
	if(!$result->EOF)
		$strAct = "ACT;" . trim($result->fields[0]) . ";";		// ACTIVIDAD DE LA EMPRESA EMISORA

	$sql = " SELECT ";
	$sql .= "	num_lin_ddoc, ";

	$sql .= "  (CASE WHEN ind_exen_det = 0 THEN null ";
	$sql .= "   ELSE ind_exen_det ";
	$sql .= "   END) as ind_exen_det, "; 

	$sql .= "  (CASE WHEN nom_item_det = '' THEN desc_item_det ";
	$sql .= "   WHEN nom_item_det isnull  THEN desc_item_det ";
	$sql .= "   ELSE nom_item_det ";
	$sql .= "   END) as nom_item_det, "; 

// elimina la descripcion para evitar doble linea igual	$sql .= "	desc_item_det, ";
	$sql .= "'',";

	$sql .= "  (CASE WHEN cant_ref_det = 0 THEN null ";
	$sql .= "   ELSE CAST(cant_ref_det AS INT) ";
	$sql .= "   END) as cant_ref_det, "; 

	$sql .= "	unid_ref_det, ";

	$sql .= "  (CASE WHEN prec_ref_det = 0 THEN null ";
	$sql .= "   ELSE CAST(prec_ref_det AS INT) ";
	$sql .= "   END) as prec_ref_det, "; 
	
	$sql .= "  (CASE WHEN cant_item_det = 0 THEN null ";
	$sql .= "   ELSE CAST(cant_item_det AS INT) ";
	$sql .= "   END) as cant_item_det, "; 

	$sql .= "	fec_elab_det, ";
	$sql .= "	fec_venc_det, ";
//	$sql .= "	(SELECT nom_med FROM unid_med WHERE cod_med = detalle_doc.unid_medi_det) as unid_medi_det, ";
	$sql .= "	(SELECT nom_med FROM servicios S, unid_med U WHERE S.cod_med = U.cod_med AND S.serv_cod = detalle_doc.nom_item_det) as unid_medi_det, ";
	$sql .= "  (CASE WHEN '" . $nMotiRef . "' = '2' AND tipo_docu = 61 THEN null ";
	$sql .= "		 WHEN prec_ref_det = 0 THEN null ";
	$sql .= "   ELSE CAST(prec_ref_det AS INT) ";
	$sql .= "   END) as prec_ref_det, "; 

	$sql .= "	'', ";
	$sql .= "	'', ";
	$sql .= "	'', ";

	$sql .= "  (CASE WHEN desc_porc_det = 0 THEN null ";
	$sql .= "		 WHEN '" . $nMotiRef . "' = '2' AND tipo_docu = 61 THEN null ";
	$sql .= "   ELSE desc_porc_det ";
	$sql .= "   END) as desc_porc_det, "; 

	$sql .= "  (CASE WHEN dcto_item_det = 0 THEN null ";
	$sql .= "		 WHEN '" . $nMotiRef . "' = '2' AND tipo_docu = 61 THEN null ";
	$sql .= "   ELSE dcto_item_det ";
	$sql .= "   END) as dcto_item_det, "; 

	$sql .= "  (CASE WHEN reca_porc_det = 0 THEN null ";
	$sql .= "		 WHEN '" . $nMotiRef . "' = '2' AND tipo_docu = 61 THEN null ";
	$sql .= "   ELSE reca_porc_det ";
	$sql .= "   END) as reca_porc_det, "; 


	$sql .= "  (CASE WHEN reca_item_det = 0 THEN null ";
	$sql .= "		 WHEN '" . $nMotiRef . "' = '2' AND tipo_docu = 61 THEN null ";
	$sql .= "   ELSE reca_item_det ";
	$sql .= "   END) as reca_item_det, "; 

	$sql .= "	'', ";

	$sql .= "  (CASE WHEN '" . $nMotiRef . "' = '2' AND tipo_docu = 61 THEN 0 ";
	$sql .= "   ELSE CAST(prec_item_det AS INT) ";
	$sql .= "   END) as prec_item_det "; 

	$sql .= " FROM ";
	$sql .= "   detalle_doc ";
	$sql .= " WHERE  ";
	$sql .= "   correl_doc = '" . str_replace("'","''",$nCodDoc) . "' ORDER BY num_lin_ddoc";

	$result = rCursor($conn, $sql);

	$j = 0;

	while(!$result->EOF){
		$strDetTmp = "";
		for($i=0; $i < 21; $i++)
		  $strDetTmp .= trim($result->fields[$i]) . ";";
		
		if($nMotiRef == '2' || ($nMotiRef == '1' && $nTipDoc == '56')){
			if($j == 0)
				$strDet = "DET;" . $strDetTmp . "\r\n" . $strDet;
		}
		else
			$strDet .= "DET;" . $strDetTmp . "\r\n";

		$j++;
		$result->MoveNext();
	}

	if($nFactRef != ""){
		$sql = "SELECT tipo_docu, fec_emi_dte, rut_rec_dte, dig_rec_dte FROM dte_enc WHERE tipo_docu = '" . $nTipoDocFactRef . "' AND folio_dte = '" . $nFactRef . "'";
		$result = rCursor($conn, $sql);
		if(!$result->EOF){
			$nTipDocRef = trim($result->fields["tipo_docu"]);
			$dFecRef = trim($result->fields["fec_emi_dte"]);
			$nRutRef = trim($result->fields["rut_rec_dte"]);
			$sDigRef = trim($result->fields["dig_rec_dte"]);

		  	if($_EN_CERTIFICACION == true){	
				$sRef = "REF;1;SET;;" . $set . ";;" . $dFecRef . ";;CASO " . $caso . ";\r\n";		
				$sRef .= "REF;2;" . $nTipDocRef . ";;" . $nFactRef . ";;" . $dFecRef . ";" . $nMotiRef . ";" . $_AMOTIVO_NC_ND[$nMotiRef] . ";\r\n";		
			}			
			else
				$sRef = "REF;1;" . $nTipDocRef . ";;" . $nFactRef . ";;" . $dFecRef . ";" . $nMotiRef . ";" . $_AMOTIVO_NC_ND[$nMotiRef] . ";\r\n";		
		}	
	}
	else{
		 if($_EN_CERTIFICACION == true)
				$sRef = "REF;1;SET;;" . $set . ";;" . date("Y-m-d") . ";;CASO " . $caso . ";\r\n";		

	}



	/****** DESCUENTOS GLOBALES *********/

	$sql = "SELECT tmov_dr, tvalor_dr, valor_dr, index_dr FROM desrec_glob WHERE correl_doc = '" . $nCodDoc . "'";
	$result = rCursor($conn, $sql);  
	$nLinDesc = 1;
	while (!$result->EOF){      
		$sTipMov = trim($result->fields["tmov_dr"]);

		if($sTipMov == "D"){
			$sTipMovDesc = trim($result->fields["tmov_dr"]);
			$sTipDesc = trim($result->fields["tvalor_dr"]);
			$sTipMntDesc = trim($result->fields["valor_dr"]);
			$sTipIndDesc = trim($result->fields["index_dr"]);
			$sLinDescG = "DESCG;" . $nLinDesc . ";D;DESCUENTO GLOBAL;" . $sTipDesc .";" . $sTipMntDesc . ";" . $sTipIndDesc . ";\r\n";
			$nLinDesc++;
		}

		if($sTipMov == "R"){
			$sTipMovRec = trim($result->fields["tmov_dr"]);
			$sTipRec = trim($result->fields["tvalor_dr"]);
			$sTipMntRec = trim($result->fields["valor_dr"]);
			$sTipIndRec = trim($result->fields["index_dr"]);
			$sLinRecG = "DESCG;" . $nLinDesc . ";R;DESCUENTO GLOBAL;" . $sTipDesc .";" . $sTipMntDesc . ";" . $sTipIndDesc . ";\r\n";
			$nLinDesc++;
		}
		
		$result->MoveNext();
	}

	/************************************/

	$sNomDteFile = $_PATH_REAL_DTE . "dte-" . $nTipDoc . "-" . $nCodDoc . ".txt";				// nombre del dte
	
	if ($gestor = fopen($sNomDteFile, 'w')){
		$contenido = $strEnc . "\r\n";
		$contenido .= $strAct . "\r\n";
		$contenido .= $strDet;

		if(($nFactRef != "" && $sRef != "") || $_EN_CERTIFICACION == true)
			$contenido .= $sRef;

		if(trim($sLinDescG) <> "")
			$contenido .= $sLinDescG;
		if(trim($sLinRecG) <> "")
			$contenido .= $sLinRecG;

		if (fwrite($gestor, $contenido) === FALSE) 
			return false;
		else
			return true;
	}
	else
		return false;
  }
  
  
  function generaLibroTotalVenta($conn, $nCodEmp, $sPeriodo){
	global $_PATH_REAL_DTE_LIBROS, $_AMOTIVO_NC_ND, $_RUT_CONTRIBUYENTE_ENVIADOR, $_NUMERO_RESOLUCION, $_FECHA_RESOLUCION;

	$sPeriodo = str_replace("-","",$sPeriodo);
	$sPeriodo = str_replace("/","",$sPeriodo);
	$sPeriodo = substr($sPeriodo,0,4) . "-" . substr($sPeriodo,4,2);

	$sql = " SELECT ";
	$sql .= "	(rut_empr || '-' || dv_empr) as rut_contr ";
	$sql .= "	FROM ";
	$sql .= "		empresa ";
	$sql .= "	WHERE ";
	$sql .= "		codi_empr = $nCodEmp ";
	$result = rCursor($conn, $sql);
	if(!$result->EOF){
		$sRutEmp = trim($result->fields["rut_contr"]);
		$sCaratula = "CAR;" . $sRutEmp . ";";
		$sCaratula .= $_RUT_CONTRIBUYENTE_ENVIADOR . ";";		
		$sCaratula .= $sPeriodo . ";";		
		$sCaratula .= $_FECHA_RESOLUCION . ";";		
		$sCaratula .= $_NUMERO_RESOLUCION . ";";		
		$sCaratula .= "VENTA;";		
		$sCaratula .= "MENSUAL;";		
		$sCaratula .= "TOTAL;;;\r\n";
	}

	$sql = "SELECT  ";
	$sql .= "	tipo_docu,  ";
	$sql .= "	COUNT(tipo_docu) as cantidad,	 ";
	
	$sql .= "	(CASE  ";
	$sql .= "		WHEN  SUM(iva_dte) > 0 THEN COUNT(tipo_docu)";
	$sql .= "	END) AS NumDocIvaRetenido , ";

	$sql .= "	SUM(mnt_exen_dte) as exento,  ";
	$sql .= "	SUM(mntneto_dte) as neto,  ";
	$sql .= "	SUM(iva_dte) as iva,  ";
	$sql .= "	SUM(mont_tot_dte) as total ";
	$sql .= "FROM  ";
	$sql .= "	documentos  ";
	$sql .= "WHERE  ";
	$sql .= "	tipo_docu IN(30,32,33,34,39,41,55,56, 60,61,101) AND ";
	$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
	$sql .= "	CASE  ";
	$sql .= "		WHEN  ";
	$sql .= "			tipo_docu = 33 OR tipo_docu = 34 OR tipo_docu = 56 OR tipo_docu = 61 then   ";
	$sql .= "				(SELECT est_xdte FROM xmldte WHERE folio_txt = documentos.correl_doc) IN(29,45,157,413)  ";
	$sql .= "		ELSE ";
	$sql .= "			fact_ref is not null       ";
	$sql .= "	END ";
	$sql .= "	AND rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) ";
	$sql .= "GROUP BY tipo_docu "; 

	$result = rCursor($conn, $sql);
	$sResPer = "";
	while(!$result->EOF){
		$sResPer .= "RESPER;" . trim($result->fields["tipo_docu"]) .";";
		$sResPer .= trim($result->fields["cantidad"]) .";";
		$sResPer .= "0;"; 
		$sResPer .= ";";
		$sResPer .= trim($result->fields["exento"]) .";";
		$sResPer .= trim($result->fields["neto"]) .";";
		$sResPer .= trim($result->fields["iva"]) .";";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= trim($result->fields["NumDocIvaRetenido"]) .";";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= trim($result->fields["total"]) .";";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;";
		$sResPer .= "0;\r\n";
		$result->MoveNext();
	}

	$sql = " SELECT  ";
	$sql .= "	tipo_docu, '', fact_ref, '','',0,'','','',to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD'), ";	// COMENTADO SOLO PARA EL SET DE PRUEBA
//	$sql .= "	tipo_docu, '', (SELECT folio_dte FROM xmldte WHERE folio_txt = documentos.correl_doc) AS folio_dte, '','',0,'','','',to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD'), "; // agregado solo para generar el libro PARA EL SET DE PRUEBA
	$sql .= "	'', ";
	$sql .= "  (CASE WHEN tipo_docu = 101 THEN '55555555-5' ";
	$sql .= "   ELSE (rut_rec_dte || '-' || dig_rec_dte) ";
	$sql .= "   END) ,"; 
	
	
	$sql .= " nom_rec_dte, '','','','', mnt_exen_dte, mntneto_dte, iva_dte, ";
	$sql .= "	'','','','','','','','',mont_tot_dte, '', ";
	$sql .= "	'','','',''	 ";
	$sql .= " FROM  ";
	$sql .= "	documentos  ";
	$sql .= " WHERE  ";
	$sql .= "	tipo_docu IN(30,32,55, 60, 101) AND ";	// COMENTADO SOLO PARA EL SET DE PRUEBA
	$sql .= "	fact_ref is not null AND       ";	// COMENTADO SOLO PARA EL SET DE PRUEBA
//	$sql .= "	tipo_docu IN(33,34,39,41,56, 61) AND ";	// agregado solo para generar el libro PARA EL SET DE PRUEBA
	$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
	$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) ";
	$result = rCursor($conn, $sql);
	
	while(!$result->EOF){
		$strDetTmp = "";
		for($i=0; $i < 34; $i++)
		  $strDetTmp .= trim($result->fields[$i]) . ";";
		
		$strDetVta = "DETVTA;" . $strDetTmp . "\r\n" . $strDetVta;
		$result->MoveNext();
	}

	$sql = " SELECT  ";
//	$sql .= "	tipo_docu, '', fact_ref, '','',0,'','','',to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD'), ";	// COMENTADO SOLO PARA EL SET DE PRUEBA
	$sql .= "	tipo_docu, '', (SELECT folio_dte FROM xmldte WHERE folio_txt = documentos.correl_doc) AS folio_dte, '','',0,'','','',to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD'), "; // agregado solo para generar el libro PARA EL SET DE PRUEBA
	$sql .= "	'', (rut_rec_dte || '-' || dig_rec_dte), nom_rec_dte, '','','','', mnt_exen_dte, mntneto_dte, iva_dte, ";
	$sql .= "	'','','','','','','','',mont_tot_dte, '', ";
	$sql .= "	'','','',''	 ";
	$sql .= " FROM  ";
	$sql .= "	documentos  ";
	$sql .= " WHERE  ";
//	$sql .= "	tipo_docu IN(30,32,55, 60, 101) AND ";	// COMENTADO SOLO PARA EL SET DE PRUEBA
//	$sql .= "	fact_ref is not null AND       ";	// COMENTADO SOLO PARA EL SET DE PRUEBA
	$sql .= "	tipo_docu IN(33,34,39,41,56, 61) AND ";	// agregado solo para generar el libro PARA EL SET DE PRUEBA
	$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
	$sql .= "	(SELECT est_xdte FROM xmldte WHERE folio_txt = documentos.correl_doc) IN(29,45,157,413) AND  ";
	$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) ";
	$result = rCursor($conn, $sql);
	while(!$result->EOF){
		$strDetTmp = "";
		for($i=0; $i < 34; $i++)
		  $strDetTmp .= trim($result->fields[$i]) . ";";
		
		$strDetVta = "DETVTA;" . $strDetTmp . "\r\n" . $strDetVta;
		$result->MoveNext();
	}

	$sNomDteFile = $_PATH_REAL_DTE_LIBROS . "dte-libvta-" . $sPeriodo . ".txt";				// nombre del libro

	if ($gestor = fopen($sNomDteFile, 'w')){
		$contenido = $sCaratula . $sResPer . $strDetVta;

		if (fwrite($gestor, $contenido) === FALSE) 
			return false;
		else
			return true;
	}
	else
		return false;
  }		

  function generaLibroTotalCompra($conn, $nCodEmp, $sPeriodo, $nFactor){
	global $_PATH_REAL_DTE_LIBROS, $_AMOTIVO_NC_ND, $_RUT_CONTRIBUYENTE_ENVIADOR, $_NUMERO_RESOLUCION, $_FECHA_RESOLUCION;

	$sPeriodo = str_replace("-","",$sPeriodo);
	$sPeriodo = str_replace("/","",$sPeriodo);
	$sPeriodo2 = substr($sPeriodo,0,4) . "-" . substr($sPeriodo,4,2);

	$sql = " SELECT ";
	$sql .= "	(rut_empr || '-' || dv_empr) as rut_contr ";
	$sql .= "	FROM ";
	$sql .= "		empresa ";
	$sql .= "	WHERE ";
	$sql .= "		codi_empr = $nCodEmp ";
	$result = rCursor($conn, $sql);
	if(!$result->EOF){
		$sRutEmp = trim($result->fields["rut_contr"]);
		$sCaratula = "CAR;" . $sRutEmp . ";";
		$sCaratula .= $_RUT_CONTRIBUYENTE_ENVIADOR . ";";		
		$sCaratula .= $sPeriodo2 . ";";		
		$sCaratula .= $_FECHA_RESOLUCION . ";";		
		$sCaratula .= $_NUMERO_RESOLUCION . ";";		
		$sCaratula .= "COMPRA;";		
		$sCaratula .= "MENSUAL;";		
		$sCaratula .= "TOTAL;;;\r\n";
	}

	$sql = "SELECT   ";
	$sql .= "	tipo_docu, ";
	$sql .= "	COUNT(tipo_docu) as cantidad,	  ";
	$sql .= "	(CASE   ";
	$sql .= "		WHEN  tipo_docu = 32 THEN COUNT(tipo_docu) ";
	$sql .= "		WHEN  tipo_docu = 34 THEN COUNT(tipo_docu) ";
	$sql .= "		WHEN  tipo_docu = 101 THEN COUNT(tipo_docu) ";
	$sql .= "		ELSE 0 ";
	$sql .= "	END) AS opexento,  ";
	$sql .= "	SUM(mnt_exen_dte) as exento, ";  
	$sql .= "	SUM(mntneto_dte) as neto, ";

	$sql .= "	CASE  ";
	$sql .= "		WHEN tipo_docu = 45 OR tipo_docu = 46 THEN (SUM(mntneto_dte) + SUM(mnt_exen_dte)) ";
	$sql .= "		ELSE ";
	$sql .= "			SUM(mont_tot_dte) ";
	$sql .= "	END as total, ";

	$sql .= "	SUM(iva_dte) as total_iva ";
	$sql .= " FROM   ";
	$sql .= "	documentoscompras   ";
	$sql .= " WHERE   ";
	$sql .= "	tipo_docu IN(30,32,33,34,45,46,55,56, 60,61, 101) AND  ";
//	$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND  ";
	$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
	$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp)  ";
	$sql .= "GROUP BY tipo_docu  ";
	$result = rCursor($conn, $sql);
	$sResPer = "";

	while(!$result->EOF){
		$sResPer .= "RESPER;" . trim($result->fields["tipo_docu"]) .";";		// Tipo Documento 1
		$sResPer .= "1;";														// Tipo de Impuesto 2
		$sResPer .= trim($result->fields["cantidad"]) .";";						// Cantidad Documentos 3
		$sResPer .= "0;";														// Total Anulados 4
		$sResPer .= trim($result->fields["opexento"]) .";";						// Total Operaciones Exenta 5
		$sResPer .= trim($result->fields["exento"]) .";";						// Total Exento 6
		$sResPer .= trim($result->fields["neto"]) .";";							// Total Neto 7

		/** DEL GIRO IVA RECUPERABLE **/
		$sql = "SELECT 	";
		$sql .= "	COUNT(tipo_docu), ";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE SUM(iva_dte) END ";
		$sql .= "FROM ";
		$sql .= "	documentoscompras ";
		$sql .= "WHERE ";
		$sql .= "	tipo_docu = '" . trim($result->fields["tipo_docu"]) . "' AND ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND  ";
		$sql .= "	tipo_fact != 'IVANORECUPER' ";
		$resultDet = rCursor($conn, $sql);
		if(!$resultDet->EOF){
			$sResPer .= trim($resultDet->fields[0]) .";";						// Total Op. IVA Recuperable 8
//			$sResPer .= trim($resultDet->fields[1]) .";";						// Total Monto IVA 9
		}
		else{
			$sResPer .= "0;";													// Total Op. IVA Recuperable 8
//			$sResPer .= "0;";													// Total Monto IVA 9
		}


		/** DEL GIRO IVA RECUPERABLE **/
		$sql = "SELECT 	";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE SUM(iva_dte) END ";
		$sql .= "FROM ";
		$sql .= "	documentoscompras ";
		$sql .= "WHERE ";
		$sql .= "	tipo_docu = '" . trim($result->fields["tipo_docu"]) . "' AND ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND  ";
		$sql .= "	tipo_fact in ('DELGIRO', 'ACTFIJO') ";
		$resultDet = rCursor($conn, $sql);
		if(!$resultDet->EOF)
			$sResPer .= round($resultDet->fields[0]) . ";";		// Total Monto IVA 9


		/** ACTIVO FIJO **/
		$sql = "SELECT 	";
		$sql .= "	COUNT(tipo_docu), ";
		$sql .= "	CASE WHEN SUM(mntneto_dte) IS NULL THEN 0 ELSE SUM(mntneto_dte) END, ";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE SUM(iva_dte) END ";
		$sql .= "FROM ";
		$sql .= "	documentoscompras ";
		$sql .= "WHERE ";
		$sql .= "	tipo_docu = '" . trim($result->fields["tipo_docu"]) . "' AND ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND  ";
		$sql .= "	tipo_fact = 'ACTFIJO' ";

		$resultDet = rCursor($conn, $sql);
		if(!$resultDet->EOF){
			$sResPer .= trim($resultDet->fields[0]) .";";						// Total Op. Activo Fijo 10
			$sResPer .= trim($resultDet->fields[1]) .";";						// Monto Neto Activo Fijo 11
			$sResPer .= trim($resultDet->fields[2]) .";";						// Total IVA Activo Fijo 12
		}
		else{
			$sResPer .= "0;";													// Total Op. Activo Fijo 10
			$sResPer .= "0;";													// Monto Neto Activo Fijo 11
			$sResPer .= "0;";													// Total IVA Activo Fijo 12
		}

		/** IVA USO COMUN **/
		$sql = "SELECT 	";
		$sql .= "	COUNT(tipo_docu), ";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE SUM(iva_dte) END, ";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE round(SUM(iva_dte) - (SUM(ROUND(iva_dte * $nFactor)))) END ";
		$sql .= "FROM ";
		$sql .= "	documentoscompras ";
		$sql .= "WHERE ";
		$sql .= "	tipo_docu = '" . trim($result->fields["tipo_docu"]) . "' AND ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND  ";
		$sql .= "	tipo_fact = 'IVAUSOCOMUN' ";
		$resultDet = rCursor($conn, $sql);

		if(!$resultDet->EOF){
			$sResPer .= trim($resultDet->fields[0]) .";";						// Total Op. IVA Uso Com�n 13
			$sResPer .= trim($resultDet->fields[1]) .";";						// IVA Uso Com�n 14

			if(trim($resultDet->fields[1]) == "0")
				$nFactorTmp = "";
			else
				$nFactorTmp = $nFactor;
		}
		else{
			$sResPer .= "0;";													// Total Op. IVA Uso Com�n 13
			$sResPer .= "0;";													// IVA Uso Com�n 14
			$nFactorTmp = "";													// SI NO AHI IVAUSO COMUN ENTONCES EL FACTOR ES CERO
		}

		if(trim($nFactorTmp) == "" || trim($nFactorTmp) == "0"){
			$sResPer .= ";";													// Factor Proporcionalidad IVA 15
			$sResPer .= ";";													// Total Cr�dito IVA uso com�n 16
			$nDifIvaNoRecupera = 0;												// iva no recuperable del iva uso comun 20-09-2006
		}
		else{
			$sResPer .= $nFactorTmp .";";										// Factor Proporcionalidad IVA 15
			$sResPer .= round($resultDet->fields[1] * $nFactorTmp) . ";";		// Total Cr�dito IVA uso com�n 16
//			$nDifIvaNoRecupera = ($resultDet->fields[1] - ($resultDet->fields[1] * $nFactorTmp));												// iva no recuperable del iva uso comun 20-09-2006
			$nDifIvaNoRecupera = $resultDet->fields[2];
		}

		/** DEL GIRO IVA NO RECUPERABLE **/
		$sql = "SELECT 	";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE SUM(iva_dte) END ";
		$sql .= "FROM ";
		$sql .= "	documentoscompras ";
		$sql .= "WHERE ";
		$sql .= "	tipo_docu = '" . trim($result->fields["tipo_docu"]) . "' AND ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND  ";
		$sql .= "	tipo_fact = 'IVANORECUPER' AND ";
		$sql .= "	nMotIvaNoRec != 4  ";		// NO SE ENTREGA GRATUITA DEL PROVEEDOR

		$resultDet = rCursor($conn, $sql);
		if(!$resultDet->EOF)
			$sResPer .= round($resultDet->fields[0] + $nDifIvaNoRecupera) .";";						// Impuesto Sin derecho a cr�dito 17
		else
			$sResPer .= round($nDifIvaNoRecupera) . ";";													// Impuesto Sin derecho a cr�dito 17
		
		$sResPer .= trim($result->fields["total"]) .";";						// Total Monto Total 18
		$sResPer .= ";";														// IVA No Retenido 19
		$sResPer .= ";";														// Total Tabaco Puros 20
		$sResPer .= ";";														// Total Tabaco Cigarrillos 21
		$sResPer .= ";";														// Total Tabaco Elaborado 22
		$sResPer .= ";\r\n";													// Total Impuesto Veh�culo 23


		/************************** IVA NO RECUPERABLE TOTAL *************************************************/

		$sql = "SELECT 	 ";
		$sql .= "	moti_iva_norec,  ";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE SUM(iva_dte) END , ";
		$sql .= "	COUNT(moti_iva_norec)  ";		
		$sql .= "FROM  ";
		$sql .= "	documentoscompras  ";
		$sql .= "WHERE  ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND  ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND   ";
		$sql .= "	tipo_fact = 'IVANORECUPER' AND ";
		$sql .= "	tipo_docu = '" . trim($result->fields["tipo_docu"]) . "'  ";
		$sql .= "GROUP BY moti_iva_norec ";
		$resultDet = rCursor($conn, $sql);
		$sIvaTotNoRecupera = "";
		while(!$resultDet->EOF){
			$sIvaTotNoRecupera .= "IVANOREC;".trim($resultDet->fields[0]) .";";			// C�digo No Recuperable
			$sIvaTotNoRecupera .= trim($resultDet->fields[2]) .";";							// num operaciones
			$sIvaTotNoRecupera .= trim($resultDet->fields[1]) .";\r\n";						// Monto IVA No Recuperable
			$resultDet->MoveNext();
		} 

		/*************************************************************************************************/


		/************************** OTROS IMPUESTOS *************************************************/

		$sql = "SELECT 	 ";
		$sql .= "	CASE WHEN SUM(iva_dte) IS NULL THEN 0 ELSE SUM(iva_dte) END  ";
		$sql .= "FROM  ";
		$sql .= "	documentoscompras  ";
		$sql .= "WHERE  ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND  ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND   ";
		$sql .= "	tipo_docu IN(45,46) AND ";
		$sql .= "	tipo_docu = '" . trim($result->fields["tipo_docu"]) . "'  ";
		$resultDet = rCursor($conn, $sql);
		$sOtrosImpuesto = "";
		while(!$resultDet->EOF){
			if($resultDet->fields[0] > 0)
				$sOtrosImpuesto .= "PEROTROIMP;15;" . trim($resultDet->fields[0]) . ";;;\r\n";			// C�digo No Recuperable
			$resultDet->MoveNext();
		} 

		/*************************************************************************************************/

		$sResPer .= $sIvaTotNoRecupera;
		$sResPer .= $sOtrosImpuesto;
		$result->MoveNext();
	}


	if(trim($nFactor) == "")
		$nFactor = 0;

	/************* DETALLE *******************/
	$sql = "SELECT ";
	$sql .= "	tipo_docu, ";
	$sql .= "	CASE  ";
	$sql .= "		WHEN tipo_docu = 45 OR tipo_docu = 46 THEN 1 ";
	$sql .= "		ELSE ";
	$sql .= "			null ";
	$sql .= "	END, ";
	$sql .= "	fact_ref, ";
	$sql .= "	'', ";
	$sql .= "	'', ";
	$sql .= "	'1', ";
	$sql .= "	tasa_iva_dte, ";
	$sql .= "	correl_doc, ";
	$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD'), ";
	$sql .= "	'', ";
	$sql .= "	(rut_rec_dte || '-' || dig_rec_dte) AS rut_dte, ";
	$sql .= "	nom_rec_dte, ";
	$sql .= "	mnt_exen_dte, ";
	$sql .= "	mntneto_dte, ";
	$sql .= "	CASE WHEN tipo_fact = 'IVANORECUPER' THEN 0 ";
	$sql .= "		 WHEN tipo_fact = 'IVAUSOCOMUN' THEN 0 ";
	$sql .= "	     ELSE iva_dte ";
	$sql .= "	END, "; 
	$sql .= "	CASE  ";
	$sql .= "		WHEN tipo_fact = 'ACTFIJO' THEN mntneto_dte ";
	$sql .= "		ELSE ";
	$sql .= "			null ";
	$sql .= "	END, ";
	$sql .= "	CASE  ";
	$sql .= "		WHEN tipo_fact = 'ACTFIJO' THEN iva_dte ";
	$sql .= "		ELSE ";
	$sql .= "			null ";
	$sql .= "	END, ";
	$sql .= "	CASE  ";
	$sql .= "		WHEN tipo_fact = 'IVAUSOCOMUN' THEN iva_dte ";
	$sql .= "		ELSE ";
	$sql .= "			null ";
	$sql .= "	END, ";
	$sql .= "	CASE  ";
//	$sql .= "		WHEN tipo_fact = 'IVANORECUPER' AND moti_iva_norec != 4 THEN iva_dte ";
	$sql .= "		WHEN tipo_fact = 'IVANORECUPER' THEN 0 ";
	$sql .= "		WHEN tipo_fact = 'IVAUSOCOMUN' THEN ROUND(iva_dte - (" . $nFactor . " * iva_dte)) ";
	$sql .= "		ELSE ";
	$sql .= "			null ";
	$sql .= "	END, ";

	$sql .= "	CASE  ";
	$sql .= "		WHEN tipo_docu = 45 OR tipo_docu = 46 THEN mntneto_dte + mnt_exen_dte ";
	$sql .= "		ELSE ";
	$sql .= "			mont_tot_dte ";
	$sql .= "	END, ";

	$sql .= "	'', ";
	$sql .= "	'', ";
	$sql .= "	'', ";
	$sql .= "	'', ";
	$sql .= "	'' ";
	$sql .= "FROM 	 ";
	$sql .= "	documentoscompras ";
	$sql .= "WHERE ";
//	$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND  ";
	$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
	$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp)  ";
	$result3 = rCursor($conn, $sql);
	while(!$result3->EOF){
		$strDetTmp = "";
		for($i=0; $i < 25; $i++)
		  $strDetTmp .= trim($result3->fields[$i]) . ";";
		
		/************************** IVA NO RECUPERABLE *************************************************/

		$sql = "SELECT 	 ";
		$sql .= "	moti_iva_norec,  ";
		$sql .= "	CASE WHEN iva_dte IS NULL THEN 0 ELSE iva_dte END  ";
		$sql .= "FROM  ";
		$sql .= "	documentoscompras  ";
		$sql .= "WHERE  ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND  ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND   ";
		$sql .= "	tipo_fact = 'IVANORECUPER' AND ";
		$sql .= "	correl_doc = '" . trim($result3->fields[7]) . "'  ";
		$result = rCursor($conn, $sql);
		$sIvaNoRecupera = "";
		if(!$result->EOF){
			$sIvaNoRecupera .= "DETCOMIVANR;".trim($result->fields[0]) .";";			// C�digo No Recuperable
			$sIvaNoRecupera .= trim($result->fields[1]) .";\r\n";						// Monto IVA No Recuperable
		} 

		/*************************************************************************************************/

		/************************** OTROS IMPUESTOS *************************************************/

		$sql = "SELECT 	 ";
		$sql .= "	tasa_iva_dte, ";
		$sql .= "	CASE WHEN iva_dte IS NULL THEN 0 ELSE iva_dte END  ";
		$sql .= "FROM  ";
		$sql .= "	documentoscompras  ";
		$sql .= "WHERE  ";
//		$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND  ";
		$sql .= "	peri_tribu = '" . $sPeriodo . "' AND  ";
		$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) AND   ";
		$sql .= "	tipo_docu IN(45,46) AND ";
		$sql .= "	correl_doc = '" . trim($result3->fields[7]) . "'  ";
		$result = rCursor($conn, $sql);
		$sOtrosImpuesto = "";
		if(!$result->EOF){
			$sOtrosImpuesto .= "DETCOMIMP;15;" . trim($result->fields[0]) . ";" . trim($result->fields[1]) . ";\r\n";			// C�digo No Recuperable
		} 

		/*************************************************************************************************/

		$strDetVta = "DETCOM;" . $strDetTmp . "\r\n" . $sIvaNoRecupera . $sOtrosImpuesto . $strDetVta;

		$result3->MoveNext();
	}

	$sNomDteFile = $_PATH_REAL_DTE_LIBROS . "dte-libcompra-" . $sPeriodo2 . ".txt";				// nombre del libro

	if ($gestor = fopen($sNomDteFile, 'w')){
		$contenido = $sCaratula . $sResPer . $strDetVta;

		if (fwrite($gestor, $contenido) === FALSE) 
			return false;
		else
			return true;
	}
	else
		return false;
  }	
	

  function generaLibroTotalGuia($conn, $nCodEmp, $sPeriodo, $sNumFolio){
	global $_PATH_ENTRADA_LIBRO_GUIA, $_AMOTIVO_NC_ND, $_RUT_CONTRIBUYENTE_ENVIADOR, $_NUMERO_RESOLUCION, $_FECHA_RESOLUCION;

	$sPeriodo = str_replace("-","",$sPeriodo);
	$sPeriodo = str_replace("/","",$sPeriodo);
	$sPeriodo = substr($sPeriodo,0,4) . "-" . substr($sPeriodo,4,2);

	$sql = " SELECT ";
	$sql .= "	(rut_empr || '-' || dv_empr) as rut_contr ";
	$sql .= "	FROM ";
	$sql .= "		empresa ";
	$sql .= "	WHERE ";
	$sql .= "		codi_empr = $nCodEmp ";
	$result = rCursor($conn, $sql);
	if(!$result->EOF){
		$sRutEmp = trim($result->fields["rut_contr"]);
		$sCaratula = "CAR;" . $sRutEmp . ";";
		$sCaratula .= $_RUT_CONTRIBUYENTE_ENVIADOR . ";";		
		$sCaratula .= $sPeriodo . ";";		
		$sCaratula .= $_FECHA_RESOLUCION . ";";		
		$sCaratula .= $_NUMERO_RESOLUCION . ";";		
		$sCaratula .= "ESPECIAL;";		
		$sCaratula .= "TOTAL;;". $sNumFolio .";\r\n";
	}

	$sql = "SELECT  ";
/*	$sql .= "	0,  "; 
	$sql .= "	0,	";
	$sql .= "	0,	";
	$sql .= "	0,	";
	$sql .= "	0,	";
	$sql .= "	'',	"; */
	$sql .= "	2,	";
	$sql .= "	COUNT(tipo_docu),	";
	$sql .= "	SUM(mont_tot_dte) as total  ";
	$sql .= "FROM  ";
	$sql .= "	documentos  ";
	$sql .= "WHERE  ";
	$sql .= "	tipo_docu IN(52) AND ";
	$sql .= "	to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND ";
	$sql .= "	(SELECT est_xdte FROM xmldte WHERE folio_txt = documentos.correl_doc) IN(29,45,157,413) AND  ";
	$sql .= "	rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp) ";
	$sql .= "GROUP BY tipo_docu "; 

	$result = rCursor($conn, $sql);
	$sResPer = "RESPER;;;0;0;;\r\n";
	while(!$result->EOF){
		$strDetTmp = "";

		for($i=0; $i < 3; $i++)
		  $strDetTmp .= trim($result->fields[$i]) . ";";

		$sResPer = "TGNV;" . $strDetTmp . "\r\n" . $sResPer;
		
		$result->MoveNext();
	}

	$sql = "	 SELECT   ";
	$sql .= "		(SELECT folio_dte FROM xmldte WHERE folio_txt = documentos.correl_doc) AS folio, ";
	$sql .= "		'', ";
	$sql .= "		2, ";
	$sql .= "		to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM-DD'), ";
	$sql .= "		(rut_rec_dte || '-' || dig_rec_dte), ";
	$sql .= "		nom_rec_dte, ";
	$sql .= "		mntneto_dte, ";
	$sql .= "		tasa_iva_dte, ";
	$sql .= "		iva_dte, ";
	$sql .= "		mont_tot_dte, ";
	$sql .= "		'', ";
	$sql .= "		'', ";
	$sql .= "		'', ";
	$sql .= "		'' ";
	$sql .= "	 FROM   ";
	$sql .= "		documentos   ";
	$sql .= "	 WHERE   ";
	$sql .= "		tipo_docu IN(52) AND  ";
	$sql .= "		to_char(to_date(fec_emi_doc, 'YYYYMMDD'),'YYYY-MM') = '" . $sPeriodo . "' AND  ";
	$sql .= "		(SELECT est_xdte FROM xmldte WHERE folio_txt = documentos.correl_doc) IN(29,45,157,413) AND   ";
	$sql .= "		rut_emis_dte IN(SELECT rut_empr FROM empresa WHERE codi_empr = $nCodEmp)  ";
	$result = rCursor($conn, $sql);
	while(!$result->EOF){
		$strDetTmp = "";
		for($i=0; $i < 14; $i++)
		  $strDetTmp .= trim($result->fields[$i]) . ";";
		
		$strDetVta = "DET;" . $strDetTmp . "\r\n" . $strDetVta;
		$result->MoveNext();
	}

	$sNomDteFile = $_PATH_ENTRADA_LIBRO_GUIA . "dte-libguia-" . $sPeriodo . ".txt";				// nombre del libro

	if ($gestor = fopen($sNomDteFile, 'w')){
		$contenido = $sCaratula . $sResPer . $strDetVta;

		if (fwrite($gestor, $contenido) === FALSE) 
			return false;
		else
			return true;
	}
	else
		return false;
  }	


?>
