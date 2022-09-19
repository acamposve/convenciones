<?
session_start();
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
$t_usuario=$_SESSION["tipo"];
include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');
include('../admin/modulos/tablas/settings.php');
?>
<table width="100%" height="72%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#ededed">
<tr>
	<td width="100%" valign="top" bgcolor="#ededed" height="100%" border="1" align="center" cellpadding="0" cellspacing="0" >
    	<table width="100%" height="100%" border="1" align="center" cellpadding="0" cellspacing="0">
<?php 
		if ($tabla!="principal") 
			{
?>
        		<tr  bgcolor="#ededed" valign="top">
          		<!--FFFFFF-->
          			<td valign="top" height="20"><font class="texMenu"> <?php print strtoupper($cat);?>  - 
                    <a href="?url=tablas&tbl=<?php echo $tabla; ?>" > <?php print "&nbsp;&nbsp;".$tabla."-";?></a> 
					<? print $accion; ?></font></td>
					<td valign="middle"><font class="texMenu" >
            		<center>
              		<a href="#" onclick="javscript:window.history.go(-1)" title="Atras" > <img src="/plantillas/plantilla_admin/images/atras.png" /> </a> &nbsp;&nbsp; 
                    <a href="#" onclick="javscript:window.history.go(1)" title="Adelante"> <img src="/plantillas/plantilla_admin/images/siguiente.png" /></a>
            		</center></font></td>
        		</tr>
<?php							
			}
?> 
		<tr>
        	<td height="207" colspan="2" valign="top"><?php include($tbl); ?></td>
        </tr>
      	</table></td>
</tr>
</table>