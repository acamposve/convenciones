<?php 
session_start();
$t_usuario=$_SESSION["tipo"];

include"../../../../lib/funciones/php/contratos/comparacion/conexion.php";
include_once ('../../../../lib/objetos/seguridad.php');
include_once ('../../../../lib/objetos/pantalla.php');
include_once ('../../../../lib/objetos/titulos.php');

// SEGURIDAD
$url="discusion_contratos_historico.php";
if($_SESSION['tipo']!=1){
	$var=$pantalla->getporUrl_externo($url);
	$seg=$seguridad->getSeguridad_externa($var,$t_usuario );
	if($seg==0){ 
		?>
            <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#999999">
              <tr>
                <td height="20%" >&nbsp;</td>
              </tr>
              <tr>
                <td height="10%"  bgcolor="#eeeeee" valign="middle" ><div align="center" class="tex3" ><font size="+3" color="#8f2323">No tiene Permiso para esta Secci&oacute;n</font></div></td>
              </tr>
              <tr>
                <td height="20%">&nbsp;</td>
              </tr>
            </table>
		<?
		exit;
		}
	}
$dato_bitacora	=$_GET['dato'];
?>
<script src="../../../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../../../../SpryAssets/SpryCollapsiblePanel_2.css" rel="stylesheet" type="text/css" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>..::Historico de Reuniones::.. Asesores RRHH C.A.</title>

<script>
<!--
function Recargar(){
document.frmbusqueda.submit();
-->
}

</script>

</head>
<body  bgcolor="#d9d7d7">

    <table width="20%" >
        <tr>
            <td bgcolor="#6d0c07">
            <img src="../../../../plantillas/plantilla_admin/images/LogoCCCColBlanco.png" alt=""  /> 
    
            </td>
        </tr>
    </table>
<font size='+3'>Historico de Reuniones</font>

	<br />
    <table  >
    	<tr>
	    	<td>
			<?php
				$bitacoras  = "select * from bitacoras inner  join empresas on bitacoras.codigo_empresa = empresas.codigo_empresa   order by nombre_empresa ";
				$bitacoras_result	 = mysql_query($bitacoras); 
				?>
				<select  name='dato' class='textfield'  disabled='disabled' onchange='Recargar()' <? if (count($peticiones)!=0){?> disabled='disabled' <? }?> >
					   <option value='' selected='selected'  >Seleccione una Discucion</option>
				<?
				while ($fila = mysql_fetch_assoc($bitacoras_result))
				{
					$codigo_bitacora	= $fila["codigo_bitacora"];
					$nombre_empresa		= $fila["nombre_empresa"];
					?>
					<option  value="<? print $codigo_bitacora; ?>" <?php if($dato_bitacora==$codigo_bitacora){ ?> selected="selected" <?php } ?> ><?php print "&nbsp;".$nombre_empresa ?></option>
					<?
				}
		
			?>
			</select>
            </td>
		</tr>
        </table><br />

				<? 
				$reuniones  = "select * from reuniones  where codigo_bitacora= '".$dato_bitacora."' order by codigo_reunion";
				$reuniones_result	 = mysql_query($reuniones); 
				$x=0;
				$y=0;
				while ($row = mysql_fetch_assoc($reuniones_result))
				{ 
				$x=$x+1;
				$y=$y+1;
					$codigo_reunion 	= $row["codigo_reunion"];
					$fecha_reunion 		= $row["fecha_reunion"];
					$hora_reunion 		= $row["hora_reunion"];
					$detalles_reunion 	= $row["detalles_reunion"];
					$resumen_reunion 	= $row["resumen_reunion"];
					$asistentes_reunion	= $row["asistentes_reunion"];
					$duracion_reunion	= $row["duracion_reunion"];
					 
					?>
						<table border="1" width="120%">
                        	<tr>
                            	<td width="10%">
		    	                    Fecha de Reunión: <br />
    	    	                	<? print "&nbsp;&nbsp;".$fecha_reunion ?></td>
								<td width="10%">
                                	Hora de la Reunión: <br />
            	    	        	<? print "&nbsp;&nbsp;".$hora_reunion ?></td>
                                <td width="10%">
                                	Duración de la Reunión: <br />
            	    	        	<? print "&nbsp;&nbsp;".$duracion_reunion ?></td>
                                	</td>
                                <td width="30%">
                                	</td>
                                <td width="30%">
                                	</td>
                	        <tr>
                    	        	<td  colspan="5"> 
                                    <!--Detalle Reunion-->
                                        <div id="CollapsiblePanel<? echo $codigo_reunion."D"?>" class="CollapsiblePanel" >
                                          <div class="CollapsiblePanelTab" tabindex="0">Detalle Reunión: </div>
                                                    <div class="CollapsiblePanelContent " >
                                                    <? print $detalles_reunion ?>
                                        </div></div>
                                        <script type="text/javascript">
                                        <!--
                                        var CollapsiblePanel<? echo $codigo_reunion."D"?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_reunion."D"?>",{contentIsOpen:false});
                                        //-->
                                        </script>
                                    <!--Resumen Reunion-->
                                        <div id="CollapsiblePanel<? echo $codigo_reunion."R"?>" class="CollapsiblePanel" >
                                          <div class="CollapsiblePanelTab" tabindex="0">Resumen Reunión: </div>
                                                    <div class="CollapsiblePanelContent " >
                                                    <? print $resumen_reunion ?>
                                        </div></div>
                                        <script type="text/javascript">
                                        <!--
                                        var CollapsiblePanel<? echo $codigo_reunion."R"?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_reunion."R"?>",{contentIsOpen:false});
                                        //-->
                                        </script>
                                    <!--Asistentes Reunion-->
                                        <div id="CollapsiblePanel<? echo $codigo_reunion."A"?>" class="CollapsiblePanel" >
                                          <div class="CollapsiblePanelTab" tabindex="0">Asistentes de la Reunión: </div>
                                                    <div class="CollapsiblePanelContent " >
                                                    <? print $asistentes_reunion ?>
                                        </div></div>
                                        <script type="text/javascript">
                                        <!--
                                        var CollapsiblePanel<? echo $codigo_reunion."A"?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_reunion."A"?>",{contentIsOpen:false});
                                        //-->
                                        </script>
                                    </td>
                            </tr>
                            <tr>
                                <td  colspan="3" align='justify' valign='top'>
                            	<table  width="100%"   border="1">
                            	<?php 
									//Peticiones en Reuniones
									$historico_reuniones_pet		="select * from reuniones_peticiones  where codigo_reuniones = ".$codigo_reunion."";
									$historico_reuniones_pet_result	= mysql_query($historico_reuniones_pet); 
									
									while ($fila = mysql_fetch_assoc($historico_reuniones_pet_result))
										{
										$codigo_peticiones 	= $fila["codigo_peticiones"];
										$x=$x+1;
										$peticiones_sindicato		="select * from peticiones_sindicato   where codigo_peticion = ".$codigo_peticiones."";
										$peticiones_sindicato_result= mysql_query($peticiones_sindicato); 
										while ($row = mysql_fetch_assoc($peticiones_sindicato_result))
											{	
												$codigo_peticion 			= $row["codigo_peticion"];
												$codigo_bitacora 			= $row["codigo_bitacora"];
												$nro_peticion 				= $row["nro_peticion"];
												$texto_completo_peticion	= $row["texto_completo_peticion"];
												$titulo_peticion 			= $row["titulo_peticion"];
												$codigo_titulo_comparativo 	= $row["codigo_titulo_comparativo"];
												$titulos_busqueda=$titulos->listarTitulos_historico_bitacora($codigo_titulo_comparativo);
												print "<tr><td colspan='2' align='center'>SINDICATO</td></tr><tr><td>Nro. Petición<br>".$nro_peticion."<td>Título Petición:<br>&nbsp;".$titulo_peticion;
												print "<tr><td colspan='2'>";
												?>
                                                <div id="CollapsiblePanel<? echo $codigo_peticion.$x?>" class="CollapsiblePanel" >
                                                  <div class="CollapsiblePanelTab" tabindex="0">Contenido Petición:</div>
                                                            <div class="CollapsiblePanelContent " >
                                                            <? print $texto_completo_peticion;
															 ?>
                                                </div></div>
                                                <script type="text/javascript">
                                                <!--
                                                var CollapsiblePanel<? echo $codigo_peticion.$x?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_peticion.$x?>",{contentIsOpen:false});
                                                //-->
		                                        </script>
												<?php
												//print "</td></tr>";
												print "</td></tr><td colspan='2'>&nbsp;<tr><td></tr>";

											}
										}
									
									//Ofertas en Reuniones
									print "</table><td align='justify' valign='top' width='30%'> <table border='1'  width='100%'>";
									$historico_reuniones_ofe		="select * from reuniones_ofertas  where codigo_reuniones = ".$codigo_reunion."";
									$historico_reuniones_ofe_result	= mysql_query($historico_reuniones_ofe); 
									while ($fila = mysql_fetch_assoc($historico_reuniones_ofe_result))
										{
										$codigo_ofertas  	= $fila["codigo_ofertas"];

										$ofertas_patrono		="select * from ofertas_patrono   where codigo_ofertas = ".$codigo_ofertas."";
										$ofertas_patrono_result	= mysql_query($ofertas_patrono); 
										while ($row = mysql_fetch_assoc($ofertas_patrono_result))
											{	
												$y=$y+1;
												$x=$x+1;
												$codigo_ofertas 			= $row["codigo_ofertas"];
												$codigo_bitacora 			= $row["codigo_bitacora"];
												$nro_oferta 				= $row["nro_oferta"];
												$texto_completo_oferta		= $row["texto_completo_oferta"];
												$estatus_oferta 			= $row["estatus_oferta"];
												$codigo_titulo_comparativo 	= $row["codigo_titulo_comparativo"];
												$titulo_oferta 				= $row["titulo_oferta"];
												$titulos_busqueda=$titulos->listarTitulos_historico_bitacora($codigo_titulo_comparativo);
												print "<tr><td colspan='2' align='center'>PATRONO</td></tr><tr><td>Nro. Oferta<br>".$nro_oferta."<td>Título Oferta:<br>&nbsp;".$titulo_oferta;
												print "<tr><td colspan='2'>";
												?>
                                                <div id="CollapsiblePanel<? echo $codigo_ofertas.$y.$x?>" class="CollapsiblePanel" >
                                                  <div class="CollapsiblePanelTab" tabindex="0">Contenido Oferta: </div>
                                                            <div class="CollapsiblePanelContent " >
                                                            <? print $texto_completo_peticion;
															 ?>
                                                </div></div>
                                                <script type="text/javascript">
                                                <!--
                                                var CollapsiblePanel<? echo $codigo_ofertas.$y.$x?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_ofertas.$y.$x?>",{contentIsOpen:false});
                                                //-->
		                                        </script>
												<?php
												//print "</td></tr>";
												print "</td></tr><td colspan='2'>&nbsp;<tr><td></tr>";
											}
										} 
										print "</td></tr></table><td align='justify' valign='top' width='30%'><table border = '1' width='100%'>";
										
 									//Acuerdos en Reuniones
									$historico_reuniones_acu ="select * from reuniones_acuerdos  where codigo_reuniones = '".$codigo_reunion."' and codigo_bitacora=".$dato_bitacora;
									$historico_reuniones_acu_result	= mysql_query($historico_reuniones_acu); 
									while ($fila = mysql_fetch_assoc($historico_reuniones_acu_result))
										{
										$codigo_acuerdos  	= $fila["codigo_acuerdos"];
										$acuerdos			= "select * from acuerdos   where codigo_acuerdos = '".$codigo_acuerdos."'";
 										$acuerdos_result	= mysql_query($acuerdos); 
										while ($row = mysql_fetch_assoc($acuerdos_result))
											{	$y=$y+1;
												$x=+$x+1;
												$codigo_acuerdos 			= $row["codigo_acuerdos"];
												$nro_articulo 				= $row["nro_articulo"];
												$texto_completo_articulo 	= $row["texto_completo_articulo"];
												$resumen_articulo 			= $row["resumen_articulo"];
												$codigo_titulo_comparativo	= $row["codigo_titulo_comparativo"];
												$campo_comparativo 			= $row["campo_comparativo"];
												$titulo_articulo 			= $row["titulo_articulo"];
												$codigo_peticion_2 			= $row["codigo_peticion"];
												$codigo_oferta_2 			= $row["codigo_oferta"];
												//Titulo Comparativo del Acuerdo
												$titulos_busqueda=$titulos->listarTitulos_historico_bitacora($codigo_titulo_comparativo);
												
												print "<tr><td>Nro. Acuerdo<br>".$nro_articulo."<td>Título Acuerdo:<br>&nbsp;".$titulo_articulo;
												print "<tr><td colspan='2'>";
												?>
                                                <div id="CollapsiblePanel<? echo $codigo_acuerdos.$y.$x?>" class="CollapsiblePanel" >
                                                  <div class="CollapsiblePanelTab" tabindex="0">Contenido Acuerdo: </div>
                                                            <div class="CollapsiblePanelContent " >
                                                            <? print $texto_completo_articulo; ?>
                                                </div></div>
                                                <script type="text/javascript">
                                                <!--
                                                var CollapsiblePanel<? echo $codigo_acuerdos.$y.$x?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_acuerdos.$y.$x?>",{contentIsOpen:false});
                                                //-->
		                                        </script>
												<?php
												print "</td></tr>";
												//Busqueda Ofertas Relacioandas con Peticiones 
												print "<tr><td width='50%' valign='top'>Petición Relacionada ";
												$relacion_peticion_acuerdo= "select * from peticiones_sindicato  where codigo_peticion=".$codigo_peticion_2."";
												 $relacion_peticion_acuerdo_result	= mysql_query($relacion_peticion_acuerdo); 
													while ($row = mysql_fetch_assoc($relacion_peticion_acuerdo_result))
													{	
														$codigo_peticion 			= $row["codigo_peticion"];
														$nro_peticion 				= $row["nro_peticion"];
														$texto_completo_peticion 	= $row["texto_completo_peticion"];
														$codigo_titulo_comparativo	= $row["codigo_titulo_comparativo"];
														$titulo_peticion 			= $row["titulo_peticion"];
														//Titulo Comparativo del Acuerdo con Respecto a la Peticion del Sindicato
														$titulos_busqueda=$titulos->listarTitulos_historico_bitacora($codigo_titulo_comparativo);

														?>
                                                            <div id="CollapsiblePanel<? echo $codigo_peticion.$x.$y."R"?>" class="CollapsiblePanel" >
                                                              <div class="CollapsiblePanelTab" tabindex="0">Contenido Petición: </div>
                                                                        <div class="CollapsiblePanelContent " >
                                                                        <? print "Nro Petición: ".$nro_peticion."<br> <hr>Texto Completo Petición: <br>".$texto_completo_peticion;
																		
																		?>
                                                            </div></div>
                                                            <script type="text/javascript">
                                                            <!--
                                                            var CollapsiblePanel<? echo $codigo_peticion.$x.$y."R"?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_peticion.$x.$y."R"?>",{contentIsOpen:false});
                                                            //-->
                                                            </script>
														<?php
													}
												
												//Busqueda Ofertas Relacioandas con Acuerdo
												print "<td valign='top'>Oferta Relacionada ";
												$relacion_oferta_acuerdo= "select * from ofertas_patrono  where codigo_ofertas=".$codigo_oferta_2."";
												 $relacion_oferta_acuerdo_result	= mysql_query($relacion_oferta_acuerdo); 
													while ($row = mysql_fetch_assoc($relacion_oferta_acuerdo_result))
													{	
														$x=$x+1;
														$y=$y+1;
														$codigo_ofertas 			= $row["codigo_ofertas"];
														$nro_oferta 				= $row["nro_oferta"];
														$texto_completo_oferta 		= $row["texto_completo_oferta"];
														$codigo_titulo_comparativo	= $row["codigo_titulo_comparativo"];
														$titulo_oferta	 			= $row["titulo_oferta"];
														//Titulo Comparativo del Acuerdo con Respecto a la Oferta Patronal
														$titulos_busqueda=$titulos->listarTitulos_historico_bitacora($codigo_titulo_comparativo);
														?>
                                                            <div id="CollapsiblePanel<? echo $codigo_ofertas.$y.$x?>" class="CollapsiblePanel" >
                                                              <div class="CollapsiblePanelTab" tabindex="0">Contenido Petición: </div>
                                                                        <div class="CollapsiblePanelContent " >
                                                                        <? print "Nro Oferta: ".$nro_oferta."<br> <hr>Texto Completo Oferta: <br>".$texto_completo_oferta;
																		 ?>
                                                            </div></div>
                                                            <script type="text/javascript">
                                                            <!--
                                                            var CollapsiblePanel<? echo $codigo_ofertas.$y.$x?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_ofertas.$y.$x?>",{contentIsOpen:false});
                                                            //-->
                                                            </script>
														<?php
													}
											} 
										
										} 
									print "</td></tr></table>";

									
								?>
                                </td>
                            
                            </tr>
						</table>
                        <hr color="#666666"  width="120%"/>
                        <br />
                    <?php
				} if (($x==0)|| ($y==0))
					{print "No existen Reuniones Para esta Bitacora";
					}
				?>
</body>
</html>
