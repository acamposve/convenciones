<?php

$tabla=$_GET['tbl'];
$tbl=$_GET['tbl'];
if (isset($_GET['accion'])){

$accion=$_GET['accion'];
} else {
$accion="";

}

$t_usuario=1;
include('../lib/objetos/user.php');
include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');
include('./modulos/contratos/settings.php');
include('../lib/objetos/pantalla.php');
include('../lib/objetos/seguridad.php');
?>
<script language="javascript" type="text/javascript">
<!--
function Recargar(){
document.data.submit();

}
function validarEstado(data){
var pais= data.id_pais.value;
var estado= data.nombreestado.value;

if(pais==""||pais==0){
alert("Debe seleccionar Pais");
return false;
}
if(estado==""||estado==0){
alert("Debe introducir un nombre de Estado");
return false;
}
}

function validarPais(data){
var pais= data.nombrepais.value;

if(pais==""||pais==0){
alert("Debe introducir un nombre de Pais");
return false;
}
}

function validarLocalidad(data){
var pais= data.id_pais.value;
var estado= data.id_estado.value;
var localidad= data.nombrelocalidad.value;
if(pais==""||pais==0){
alert("Debe Seleccionar un Pais");
return false;
}

if(estado==""||estado==0){
alert("Debe Seleccionar un Estado");
return false;
}

if(localidad==""||localidad==0){
alert("Debe escribir localidad");
return false;
}
}

-->
</script>
<style type="text/css">
<!--
.style1 {color: #000000}
-->
</style>
<link href="../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="780" bgcolor="#ededed" border="0" align="center" cellpadding="0" cellspacing="0" background="login/media/bg.gif" >
<tr valign="top">
	<td bgcolor="#ededed" valign="top">
		<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="12" valign="top"></td>
			<td width="1%" valign="top"></td>
		</tr>
		<tr>
			<td colspan="4" bgcolor="#ededed">
				<table width="100%" height="72%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#ededed">
				<tr>
					<td width="100%" >
						<table width="100%"  bgcolor="#ededed" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
						if ($tabla!="principal")
							{
?>
								<tr  bgcolor="#ededed"  valign="top"> <!--FFFFFF-->
									<td height="20"> <font class="texMenu"><?php print strtoupper($cat);?>&nbsp; -
									<a href="?url=contratos&tbl=<?php echo $tabla; ?>" >
									<?php print "&nbsp;&nbsp;".$tabla."-";?></a>
									<? print $accion;?></font></td>
									<td valign="middle"> <font class="texMenu" ><center>
									<a href="#" onclick="javscript:window.history.go(-1)" title="Atras" ><img src="/plantillas/plantilla_admin/images/atras.png" /></a>
									&nbsp;&nbsp;
									<a href="#" onclick="javscript:window.history.go(1)" title="Adelante"><img src="/plantillas/plantilla_admin/images/siguiente.png" /></a>
									</center></font></td>
								</tr>
<?php
							}
?>
						<tr>
							<td height="100%" colspan="2" valign="top" ><? include($tbl); ?></td>
						</tr>
						</table></td>
				</tr>
				</table></td>
		</tr>
		<tr>
			<td  class="cabecera" colspan="4"  height="1"  ></td>
		</tr>
		</table></td>
</tr>
</table>
