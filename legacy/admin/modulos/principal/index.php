<?php
session_start();
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
$t_usuario=$_SESSION["tipo"];
$fecha_vence =$_SESSION["fecha_vence"];

include('./modulos/bienvenida/settings.php');
$today = date("Y-m-d");
if ($_SESSION["tipo"]<> 1)
{
	if ($today > $fecha_vence)
		{
			$usuario_vencido=1;
		}
}

if ($usuario_vencido==1)
	{
?>
		Su Usuario est&aacute; vencido, no tiene privilegios para ingresar.
<?php
	}
else
	{
?>
			<table bgcolor="#ededed" border="0" align="left" cellpadding="2" cellspacing="2">
  			<tr>
    			<td valign="top" bgcolor="#ededed">
    				<table width="100%" align="center" cellpadding="0" cellspacing="0">
        			<tr>
          				<td width="100%"colspan="2" valign="top"><?php include("./".$tbl); ?></td>
        			</tr>
        			<tr>
          				<td colspan="2"><div align="center"></div></td>
        			</tr>
      				</table></td>
  			</tr>
			</table>
<?php
		}
?>
