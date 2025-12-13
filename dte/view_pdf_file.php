<?php
  $sUrlPdf = $_GET["sUrlPdf"];
  // Enviaremos un PDF


	  header('Content-type: application/pdf');
	  // Se va a llamar descarga.pdf
	  header('Content-Disposition: attachment; filename="descarga.pdf"');
	  // La fuente del PDF se encuentra en original.pdf
	  readfile($sUrlPdf);

/*
  $filename = "d:\\REXX.PDF" ;
  $dataFile = fopen( $filename, "r" ) ;

  if ( $dataFile )
  {
   while (!feof($dataFile))
   {
       $buffer = fgets($dataFile, 4096);
       echo $buffer;
   }

   fclose($dataFile);
  }
  else
  {
   die( "fopen failed for $filename" ) ;
  }
  */
?>
