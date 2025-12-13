<?php 
    include("../include/config.php");  
    include("../include/ver_aut.php");      
    include("../include/ver_aut_adm.php");      
    include("../include/tables.php");  
?>
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


var opt_no_frames = false;
var opt_integrated_mode = false;
setActiveButtonByName("clients");
//-->
		</script>
	</head>
	<body onLoad="_body_onload();" onUnload="_body_onunload();" id="mainCP">

	<a href="#" name="top" id="top"></a>
	<table border="0" cellspacing="0" cellpadding="0" id="loaderContainer" onClick="return false;"><tr><td id="loaderContainerWH"><div id="loader"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><p><img src="skins/<?php echo $_SKINS; ?>/icons/loading.gif" height="32" width="32" alt=""/><strong>Por favor espere.<br>Cargando ...</strong></p></td></tr></table></div></td></tr></table>
	<?php sTituloCabecera("Usuarios / Empresas"); ?>
	<?php // sAgregaHerramienta("screenClientList", "Herramientas", $aBotonUserEmpHerramienta); ?>

	<div class="screenBody">
		<div class="listArea">
			<fieldset>
				<legend>Usuarios / Empresas</legend>
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><p>Usuarios / Empresas (4)</p>
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
									<th width="0">Estado</th>								
									<th width="45%"><a href="javascript:void(0);">User</a></th>
									<th width="45%" class="sort"><a href="javascript:void(0);">Empresa</a><img src='skins/<?php echo $_SKINS; ?>/icons/arrow_up.gif'></th>
									<th width="0" class="select"><input type="checkbox" class="checkbox" name="clientslistSelectAll" value="true" ></th>
								</tr>

								<tr class="oddrowbg">
									<td class="icon"><img src='skins/<?php echo $_SKINS; ?>/icons/on.gif'></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_user_emp.php';">FBustamante</a></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_emp_user.php';">OpenB</a></td>
									<td class="select"><input type="checkbox" class="checkbox" name="del[]" id="del_1" value="1" ></td>
								</tr>

								<tr class="evenrowbg">
									<td class="icon"><img src='skins/<?php echo $_SKINS; ?>/icons/off.gif'></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_user_emp.php';">CDiaz</a></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_emp_user.php';">OpenB</a></td>
									<td class="select"><input type="checkbox" class="checkbox" name="del[]" id="del_2" value="2"></td>
								</tr>

								<tr class="oddrowbg">
									<td class="icon"><img src='skins/<?php echo $_SKINS; ?>/icons/on.gif'></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_user_emp.php';">PRojas</a></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_emp_user.php';">Emol.com</a></td>
									<td class="select"><input type="checkbox" class="checkbox" name="del[]" id="del_1" value="1" ></td>
								</tr>

								<tr class="evenrowbg">
									<td class="icon"><img src='skins/<?php echo $_SKINS; ?>/icons/off.gif'></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_user_emp.php';">CDiaz</a></td>
									<td><a href="javascript:location.href='<?php echo $_LINK_BASE; ?>user_emp/add_emp_user.php';">Emol.com</a></td>
									<td class="select"><input type="checkbox" class="checkbox" name="del[]" id="del_2" value="2"></td>
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
