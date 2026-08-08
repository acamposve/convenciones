<?php
session_start();
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
$t_usuario=$_SESSION["tipo"];
include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');
include('settings.php');
$FechaHoy=date("d/m/Y");
?>

	    						<table width="100%" height="100%" border="1" align="center" cellpadding="0" cellspacing="0">
<?php 
								if ($tabla!="principal") 
									{
?>
										<tr  bgcolor="#ededed" valign="top">
											<td valign="top" height="20"> <font class="texMenu">
	                						<a href="?url=tablas&tbl=<?php echo $tabla; ?>" >
											<?php print "&nbsp;&nbsp;".$tabla."-";?></a> 	
	   	            						<? print $accion; ?></font></td>
	                						<td valign="middle">
											<font class="texMenu" >
											<center>
					        				<a href="#" onclick="javscript:window.history.go(-1)" title="Atras" ><img src="../plantillas/plantilla_admin/images/atras.png" /></a>
											&nbsp;&nbsp;
			        		    			<a href="#" onclick="javscript:window.history.go(1)" title="Adelante"><img src="../plantillas/plantilla_admin/images/siguiente.png" /></a>
			                				</center></font></td>
										</tr>
<?php
                    				}
?>	
								<tr>
									<td height="207" colspan="2"><? include($tbl); ?></td>
	            				</tr>
	            				<tr>
									<td colspan="2"><div align="center"></div></td>
	            				</tr>
	        					</table>