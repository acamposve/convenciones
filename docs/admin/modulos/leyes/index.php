<?php
session_start();
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
$t_usuario=$_SESSION["tipo"];
include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');
include('./modulos/leyes/settings.php');
?>
<script language="javascript" type="text/javascript">
<!--
function Recargar()
	{
		document.data.submit();
	}
function validarEstado(data)
	{
		var pais= data.id_pais.value;
		var estado= data.nombreestado.value;
		if(pais==""||pais==0)
			{
				alert("Debe seleccionar Pais");
				return false;
			}
		if(estado==""||estado==0)
			{
				alert("Debe introducir un nombre de Estado");
				return false;
			}
	}
function validarPais(data)
	{
		var pais= data.nombrepais.value;
		if(pais==""||pais==0)
			{
				alert("Debe introducir un nombre de Pais");
				return false;
			}
	}
function validarLocalidad(data)
	{
		var pais= data.id_pais.value;
		var estado= data.id_estado.value;
		var localidad= data.nombrelocalidad.value;
		if(pais==""||pais==0)
			{
				alert("Debe Seleccionar un Pais");
				return false;
			}
		if(estado==""||estado==0)
			{
				alert("Debe Seleccionar un Estado");
				return false;
			}
		if(localidad==""||localidad==0)
			{
				alert("Debe escribir localidad");
				return false;
			}
	}
-->
</script>
<table width="780" bgcolor="#ededed" border="0" align="center" cellpadding="0" cellspacing="0" background="login/media/bg.gif" >
<tr>
	<td bgcolor="#ededed">
		<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" background="login/media/bg.gif" >
		<tr>
			<td>
				<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="12"></td>
					<td width="1%"></td>
				</tr>
				<tr>
					<td colspan="4" valign="top" bgcolor="#9d9d9d">
						<table width="100%"  align="center" cellpadding="2" cellspacing="2" bgcolor="#ededed">
						<tr>
							<td width="100%" valign="top" bgcolor="#ededed"  border="1" align="center" cellpadding="0" cellspacing="0" >
								<table width="100%" height="100%" border="1" align="center" cellpadding="0" cellspacing="0">
<?php 
								if ($tabla!="principal") 
									{
										echo "aqui";
?>
										<tr  bgcolor="#ededed" valign="top"> <!--FFFFFF-->
											<td height="17" > <font class="texMenu">
											<a href="?url=leyes&tbl=<?php echo $tabla; ?>" ><?php print strtoupper($cat);?> - 
											<?php print "&nbsp;&nbsp;".$tabla."-";?></a>
											<? print $accion;?></font></td>
											<td valign="middle"> <font class="texMenu" ><center>
											<a href="#" onclick="javscript:window.history.go(-1)" title="Atras" >
											<img src="../plantillas/plantilla_admin/images/atras.png" />
											</a>
											&nbsp;&nbsp;
											<a href="#" onclick="javscript:window.history.go(1)" title="Adelante">
											<img src="../plantillas/plantilla_admin/images/siguiente.png" /></a>
											</center></font></td>
										</tr>
<?php
									}
									
									
									echo $tbl;
?>
								<tr>
									<td height="207" colspan="2" style="width:780px;"><? include($tbl); ?></td>
								</tr>
								<tr>
									<td colspan="2"><div align="center"></div></td>
								</tr>
								</table></td>
						</tr>
						</table></td>
				</tr>
				<tr class="cabecera" >
					<td  colspan="4"  height="1"  ></td>
					<td> </td>
					<td> </td>
				</tr>
				</table></td>
		</tr>
		</table></td>
</tr>
</table>