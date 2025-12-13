<?php include("../include/config.php");  ?>
<?php include("../include/tables.php");  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<link rel="shortcut icon" href="/favicon.ico">
		<title>OpenB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<base href="<?php echo $_LINK_BASE; ?>" />

		<script language="javascript" type="text/javascript" src="javascript/common.js"></script>
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/general.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/custom.css">
		<link rel="stylesheet" type="text/css" href="skins/<?php echo $_SKINS; ?>/css/main/layout.css">
		<link rel="stylesheet" type="text/nonsense" href="skins/<?php echo $_SKINS; ?>/css/misc.css">


		<script type="text/javascript">
<!--
function _body_onload()
{
	SetContext('clients');
	setActiveButtonByName('clients');
	loff();
	
}

function _body_onunload()
{
	lon();
	
}

function open_factura()
{
	try {
		window.open('<?php echo $_LINK_BASE; ?>dte/dte-33-68.pdf',
				'DTE',
				'toolbar=no,width=500,height=400,innerHeight=400,innerWidth=500,scrollbars=yes,resizable=yes');
	} catch (e) {
		return false;
	}
}

function open_error()
{
	try {
		window.open('<?php echo $_LINK_BASE; ?>dte/error.php',
				'DTE',
				'toolbar=no,width=250,height=250,innerHeight=400,innerWidth=500,scrollbars=yes,resizable=yes');
	} catch (e) {
		return false;
	}
}

var opt_no_frames = false;
var opt_integrated_mode = false;
setActiveButtonByName("clients");
//-->
		</script>
	</head>
	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP" class="visibilityAdminMode">
	
	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>

	<a href="#" name="top" id="top"></a>

	<?php sTituloCabecera("Empresa"); ?>
	<?php // sAgregaHerramienta("screenClientList", "Herramientas", $aBotonEmpHerramienta); ?>

	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>DTE</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><p>Dte Enviados SII (2)</p>
							<table width="100%" cellspacing="0" class="buttons">
								<td class="main">
									<div>
										<input type="text" name="filter" id="searchInput" value=""size="20" maxlength="245">
										<div class="commonButton" id="bid-search" title="Buscar"  name="bid-search">
											<button name="bname_search">Buscar</button><span>Buscar</span>
										</div>
										<div class="commonButton" id="bid-show-all" title="Mostrar todo" name="bid-show-all">
											<button name="bname_show_all">Mostrar todo</button><span>Mostrar todo</span>
										</div>
									</div>
								</td>
								<td class="misc">
									<div>
										<div class="commonButton">&nbsp;</div>

										<div class="commonButton" id="bid-remove-selected" title="Eliminar seleccion"  name="bid-remove-selected">
											<button name="bname_remove_selected" >Eliminar seleccion</button><span>Eliminar seleccion</span>
										</div>
									</div>
								</td>
							</table>
							<table width="100%" cellspacing="0" class="list">
								<tr>
									<th><a href="javascript:void(0);">Nº DTE</a></th>
									<th><a href="javascript:void(0);">Folio</a></th>
									<th>Fecha</th>								
									<th>Rut</th>								
									<th class="sort"><a href="javascript:void(0);">Raz&oacute;n Social</a><img src='skins/<?php echo $_SKINS; ?>/icons/arrow_up.gif'></th>
									<th>Giro</th>								
									<th>Direcci&oacute;n</th>								
									<th>Comuna</th>								
									<th>Ciudad</th>								
									<th>Estado</th>								
								</tr>

								<tr class="oddrowbg">
									<td>000001</td>
									<td>000125</td>
									<td>01-01-2005</td>
									<td>11.111.111-1</td>
									<td>OpenB S.A</td>
									<td>Ingenieria de Software</td>
									<td>Av 11 de Septiembre 1881</td>
									<td>Providencia</td>									
									<td>Santiago</td>									
									<td class="icon"><a href="javascript:open_factura();"><img src='skins/<?php echo $_SKINS; ?>/icons/ok.gif' title="Aceptado"></a></td>
								</tr>

								<tr class="evenrowbg">
									<td>000002</td>
									<td>000126</td>
									<td>01-01-2005</td>
									<td>11.111.111-1</td>
									<td>OpenB S.A</a></td>
									<td>Ingenieria de Software</td>
									<td>Av 11 de Septiembre 1881</td>
									<td>Providencia</td>									
									<td>Santiago</td>									
									<td class="icon"><a href="javascript:open_error();"><img src='skins/<?php echo $_SKINS; ?>/icons/off.gif' title="Rechazado"></a></td>
							</tr>
							</table>
							<div class="paging">1 2 3 4 5 6 7 8 9 10</div>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</div>
 	
 </body>
</html>
