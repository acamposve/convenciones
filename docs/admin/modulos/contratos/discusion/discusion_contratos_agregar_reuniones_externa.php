
<?php 
//Sesion y Usuario
session_start();
$t_usuario=$_SESSION["tipo"];
?>
<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK> 
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<?
include"../../../../lib/funciones/php/contratos/comparacion/conexion.php";
include ('../../../../lib/objetos/contratos.php');
include ('../../../../lib/objetos/categoria_titulos.php');
include ('../../../../lib/objetos/discusion.php');
include_once ('../../../../lib/objetos/titulos.php');
	  
include_once ('../../../../lib/objetos/seguridad.php');
include_once ('../../../../lib/objetos/pantalla.php');

// SEGURIDAD
$url="discusion_contratos_agregar_reuniones_externa.php";
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

$asistentes="Representante Sindical <br><br>Representante Empresarial <br><br>Representante Ministerio <br><br> Consulto/Asesor";
$t="";
$dato_bitacora	=$_GET['dato'];
$peticiones 	= $_GET['peticiones_data']; 
$llave_ofertas	= $_GET['llave_ofertas']; 

if ($llave_ofertas==1){
	//Datos Reunion
	$codigo_bt			= $_GET['codigo_bt'];
	$FechaReunion		= $_GET['FechaReunion'];
	$HoraReunion		= $_GET['HoraReunion']; 
	$DuracionReunion	= $_GET['DuracionReunion']; 
	$DetalleReunion		= $_GET['DetalleReunion']; 
	$ResumenReunion		= $_GET['ResumenReunion']; 
	$AsistentesReunion	= $_GET['AsistentesReunion']; 
	//INSERTAR REUNION
		$inserta_reunion_2=$discusion->inserta_Reuniones_Externas($codigo_bt,$FechaReunion,$HoraReunion,$DuracionReunion,$ResumenReunion,$DetalleReunion,$AsistentesReunion  );

	//Datos Peticiones-Ofertas
	$codigo_peticion_cargados	= $_GET['codigo_peticion_cargados']; 

	$nro_oferta					= $_GET['nro_oferta'];
	$titulo_oferta				= $_GET['titulo_oferta']; 
	$texto_oferta				= $_GET['texto_oferta']; 
	//$titulo_comparativo			= $_GET['titulo_comparativo']; 
	$peticiones_sindicato_2		= $_GET['peticiones_sindicato_2']; 
	$aprobador_confirmacion		= $_GET['aprobador']; 
	//Datos Peticiones-Ofertas-Acuerdo
	$nro_acuerdo 				= $_GET['nro_acuerdo']; 
    $titulo_acuerdo				= $_GET['titulo_acuerdo']; 
    $texto_acuerdo				= $_GET['texto_acuerdo']; 
	//$titulo_comparativo_acuerdo	= $_GET['titulo_comparativo_acuerdo']; 

	//Sacar Ultima Reunion
	$ultima_reunion	= "Select codigo_reunion from reuniones where codigo_bitacora=".$codigo_bt." order by codigo_reunion desc";
	$buscar_ultima_reunion	= mysql_query($ultima_reunion); 
	while ($fila = mysql_fetch_assoc($buscar_ultima_reunion))	{
		$codigo_ultima_reunion = $fila["codigo_reunion"];
		break;
		}
	//INSERTAR OFERTAS // OFERTAS_REUNION // PETICICIONES_REUNION
	$x=0;
	$peticiones_result_3	= mysql_query($peticiones_sindicato_2); 
	while ($fila = mysql_fetch_assoc($peticiones_result_3))	{
		$codigo_peticion_3			= $fila['codigo_peticion'];

		//Insertar Ofertas
			$mascara_nro_oferta=$discusion->mascara($nro_oferta[$x]);
			$inserta_ofe_pat=$discusion->insertar_Ofertas_Patrono($codigo_bt,$mascara_nro_oferta,$texto_oferta[$x],0,$codigo_peticion_cargados[$x],$titulo_oferta[$x]);

		//OBTENER ULTIMA OFERTA CARGADA

		$ultima_oferta	= "Select codigo_ofertas from ofertas_patrono  where codigo_bitacora=".$codigo_bt." order by codigo_ofertas desc";
		$buscar_ultima_oferta	= mysql_query($ultima_oferta); 
		while ($fila = mysql_fetch_assoc($buscar_ultima_oferta))	{
			$codigo_ultima_oferta = $fila['codigo_ofertas'];
			break;
		}
		//INSERTAR HISTORICO DE OFERTAS-REUNIONES Y PETCIONES-REUNIONES
		$insertar_historico	=$discusion->insertar_Historico_Ofe_Pet_Acu($codigo_ultima_reunion,$codigo_bt,$codigo_ultima_oferta,"OFE");
		$insertar_historico	=$discusion->insertar_Historico_Ofe_Pet_Acu($codigo_ultima_reunion,$codigo_bt,$codigo_peticion_3,"PET");
		
		
		//VERIFICAR SI HAY APROBACION('ON') O NO
		if ($aprobador_confirmacion[$x]=='on'){
			
			$mascara_nro_acuerdo=$discusion->mascara($nro_acuerdo[$x]);
			$inserta_acuerdos=$discusion->insertar_Acuerdos_Reuniones( $codigo_bt,$codigo_peticion_cargados[$x],$codigo_ultima_oferta,$codigo_ultima_reunion,$FechaReunion,$mascara_nro_acuerdo,$texto_acuerdo[$x],0,$titulo_acuerdo[$x]);
			
				//OBTENER ULTIMO ACUERDO
				$ultimo_acuerdo	= "Select * from acuerdos  where codigo_bitacora=".$codigo_bt." order by codigo_acuerdos desc";
				$buscar_ultimo_acuerdo	= mysql_query($ultimo_acuerdo); 
				while ($fila = mysql_fetch_assoc($buscar_ultimo_acuerdo))	{
					$codigo_ultimo_acuerdo = $fila['codigo_acuerdos'];
					break;
				}
				$insertar_historico	=$discusion->insertar_Historico_Ofe_Pet_Acu($codigo_ultima_reunion,$codigo_bt,$codigo_ultimo_acuerdo,"ACU");
				$update_oferta		=$discusion->update_Ofertas_Acuerdos($codigo_peticion_cargados[$x]);
				


			}
			
		$x=$x+1;

		}

	}


?>
<script src="../../../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../../../../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>..::Creacion de Reuniones::.. Asesores RRHH C.A.</title>


<script>
<!--
function Recargar(){
document.frmbusqueda.submit();
-->
}

</script>

</head>
<body  bgcolor="#d9d7d7">
<form name="frmbusqueda" onSubmit="buscarDato(); return false" action="<? $_SERVER["PHP_SELF"];?>"  method="get">
    <table width="20%" >
        <tr>
            <td bgcolor="#6d0c07">
            <img src="../../../../plantillas/plantilla_admin/images/LogoCCCColBlanco.png" alt=""  /> 
    
            </td>
        </tr>
    </table>

	<?php 
		if ($llave_ofertas==1){
		print "<font size='+5'>Cambios Almacenados</font><br>Inicie una Nueva Reunion o Cierre la Ventana";
		}
	?>
	<br /><br />	
    <table  border="1" >
    	<tr>
	    	<td>
			<?php

				$bitacoras  = "SELECT * FROM bitacoras INNER JOIN empresas ON bitacoras.codigo_empresa = empresas.codigo_empresa WHERE `codigo_bitacora`=".$dato_bitacora;
				$bitacoras_result	 = mysql_query($bitacoras); 
				?>
				<select name='dato' class='textfield'  onchange='Recargar()' <? if (count($peticiones)!=0){?> disabled='disabled' <? }?> >
					   <option value='' selected='selected' >Seleccione una Discucion</option>
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
		<tr>
        	<td>        
        
				<?php 
                    if (count($peticiones)!=0)
                    {
                        print "<a  href='discusion_contratos_agregar_reuniones_externa.php?dato=".$dato_bitacora."' onclick='renover()'>Retomar Busquedas </a>";
                        } 
                ?>
			</td>
		</tr>    

        <tr  >
        	<td >
				<?php
					//Peticiones para hacer listado
					if (count($peticiones)==0)
					{
						if ($dato_bitacora!="")
						{
						print "		<tr> <td> Peticiones de la Bitacora</td></tr>";
						$listar_peticiones	= "select * from peticiones_sindicato where codigo_bitacora= ".$dato_bitacora." order by nro_peticion";
						$peticiones_result	= mysql_query($listar_peticiones); 
							$control_peticiones=0;
 							while ($fila = mysql_fetch_assoc($peticiones_result))
							{
								$control_peticiones=$control_peticiones+1;
								$codigo_peticion	= $fila['codigo_peticion'];
								$titulo_peticion	= $fila['titulo_peticion'];
								$nro_peticion		= $fila['nro_peticion'];
								$estatus_peticion 		= $fila['estatus_peticion'];
								if ($estatus_peticion !=9){
									if ($control_peticiones==4){
											$control_peticiones==0;
											print "</tr><tr>";
										}
									print "<td width='25%'><input name='peticiones_data[]' type='checkbox' value=".$codigo_peticion." />".$nro_peticion."--".$titulo_peticion."<br>" ;
								}
							}
 						}
					}
                ?>

			</td>
		</tr><table border="1">
				<?php 
					//Para Crear Reunion
	
					if (count($peticiones)==0){
					}else{
					
					print"
						<tr ><td>
								Fecha Reunion:
							</td>
							<td>
								Hora Reunion:
							</td>
							<td>
								Duración Reunion:
							</td>
						</tr>
						<tr>
							<td>

								<input name='FechaReunion' type='text' class='texto' id='FechaReunion' readonly='readonly' />"?>
								<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.frmbusqueda.FechaReunion,'dd/mm/yyyy',this)" />
					<?php 
						print "
							</td>
							<td>
	        		    	    <input name='HoraReunion' type='text' class='texto' id='HoraReunion' />
							</td>
							<td>
	        	    	    	<input name='DuracionReunion' type='text' class='texto' id='DuracionReunion' />
							</td>
						</tr>
						<!--Espacio en blanco-!>
						<tr>
							<td colspan='3'>&nbsp;
							</td>
						</tr>
						<tr><td>
							Detalle de la Reunion:
							<textarea name='DetalleReunion' cols='70' rows='15' id='DetalleReunion' class='mceEditor' ></textarea>
						</td>
						<td>
							Resumen de la Reunion:
							<textarea name='ResumenReunion' cols='70' rows='15' id='ResumenReunion' class='mceEditor' ></textarea>
						</td>
						<td>
							Asistentes de la Reunion:
							<textarea name='AsistentesReunion' cols='70' rows='15' id='ResumenReunion' class='mceEditor' >".$asistentes."</textarea>
						</td></tr>
						</table><br>
								<font size='+1' color='#6d0c07'>Peticiones - Ofertas</font>
						<table border='1'>
						<!--Espacio en blanco-!>
						<tr>
							<td>
							</td>
						</tr>
						<tr valign='top' align='center' >
							<td  >
								Nro Peticion</td>
							<td width='10%'>
								Titulo Peticion</td>
							<td width='1%'>
								Nro Oferta</td>
							<td width='8%'>
								Titulo Oferta</td>
							<td width='8%'>
								Texto de la Oferta:</td>
							
						</tr>";							
						if (is_array($peticiones)) { 
							$busqueda_peticion=" and codigo_peticion in (";
							for ($i=0;$i<count($peticiones);$i++) { 
								if (count($peticiones)>($i+1)) {
									$busqueda_peticion .= "  $peticiones[$i],";
								}else{
									$busqueda_peticion .= "  $peticiones[$i])";
								}
								} 

							}  
						$peticiones_sindicato_2= "select * from peticiones_sindicato  where codigo_bitacora=".$dato_bitacora.$busqueda_peticion." order by nro_peticion";
						$peticiones_result_2	= mysql_query($peticiones_sindicato_2); 
						while ($fila = mysql_fetch_assoc($peticiones_result_2))
						{
							$codigo_peticion_2			= $fila['codigo_peticion'];
							$titulo_peticion_2			= $fila['titulo_peticion'];
							$nro_peticion_2				= $fila['nro_peticion']; 
							$codigo_titulo_comparativo_peticion	= $fila['codigo_titulo_comparativo'];
							$texto_completo_peticion			= $fila['texto_completo_peticion'];
							$titulos_busqueda=$titulos->listarTitulos_historico_bitacora($codigo_titulo_comparativo_peticion);							
									print 	"<tr valign='top'  >
											<td align='center' >
												<input name='codigo_peticion_cargados[]' type='hidden' value=".$codigo_peticion_2." >"
												.$nro_peticion_2."
											</td>
											<td > "
												.$titulo_peticion_2."<br><br>Titulo Comparativo: <br>".$titulos_busqueda[1]['nombre_titulo']."
											</td>
											<td>
												<input name='nro_oferta[]' type='text' size='8' /></td>
											<td>
												<input name='titulo_oferta[]' type='text' size='30' />
											</td>
											</td>
											<td>
												<textarea name='texto_oferta[]' cols='70' rows='15' id='texto_oferta' class='mceEditor' ></textarea>
											</td>";
										?>
		
										
                                        <tr align="center">
                                            <td> 
                                            </td>
	                                        <td >Existe Acuerdo
                                            </td>
                                            <td>Nro Cláusula
                                          </td>
                                            <td>Titulo de la Cl&aacute;usula                                            </td>
                                          <td>Texto de la Cl&aacute;usula                                            </td>
						      </tr>
                                        <tr valign="top">
                                            <td> 
                                            </td>
                                        	<td align="center"> <input type="checkbox" name="aprobador[]"  />
                                            </td>
                                            <td>
												<input name='nro_acuerdo[]' type='text' size='8' /></td>
											<td>
												<input name='titulo_acuerdo[]' type='text' size='30' />
											</td>
											</td>
											<td>
												<textarea name='texto_acuerdo[]' cols='70' rows='15' id='texto_oferta' class='mceEditor' ></textarea>
											</td>
                                        </tr>
                                        <tr  >
                                        <td colspan="7" >Texto Completo Petición
                                        </td></tr>
										<tr bordercolordark="#6d0c07">
                                        <td></td>
                                        <td   colspan="6">
											<?php print $texto_completo_peticion; ?>
                                        </td>
										<tr>
                                        	<td colspan="6">
                                            	<hr  color="#6d0c07"/>
                                            </td>
	                                    </tr>
							<?php
                        }

						print 	"<tr><td>
                                    <input type='submit' name='Ofertas_completas'  title='Guardar Ofertas' />
                                    <input type='hidden' name='llave_ofertas' value='1' />	
                                    <input type='hidden' name='peticiones_sindicato_2' value='".$peticiones_sindicato_2."' />	
									<input type='hidden' name='codigo_bt' value='".$dato_bitacora."' />	
									<input type='hidden' name='dato' value='".$dato_bitacora."' />	
								</td></tr>";
					}

				?>

                </td>
                </tr>
                                </table>
            </td>
        </td>
		

	</table>
				<?php 
                    if (count($peticiones)==0)
                    {
                        print "<input name='buscar' type='submit'  title='Comparacion'   />";
                    }
				?>
</form>

<script type="text/javascript" src="../../../../lib/funciones/js/tiny_mce/tiny_mce.js"></script> 
<script type="text/javascript"> 
tinyMCE.init(
		{ 
			mode : "textareas", 
			theme : "advanced", 
			plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager"	,
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,cleanup,|,bullist,numlist,|,outdent,indent,blockquote,undo,redo,|,link,unlink,|,forecolor,backcolor",
 			theme_advanced_buttons2 : "styleselect,formatselect,fontselect,fontsizeselect", 
				 
			theme_advanced_buttons3 : "tablecontrols", 
			theme_advanced_toolbar_location : "top", 
			theme_advanced_toolbar_align : "left", 
			theme_advanced_resizing : false, content_css : "css/example.css", 
			// Drop lists for link/image/media/template dialogs 
			template_external_list_url : "js/template_list.js", 
			external_link_list_url : "js/link_list.js", 
			external_image_list_url : "js/image_list.js", 
			media_external_list_url : "js/media_list.js", 
			// Replace values for the template plugin 
				template_replace_values : { 
				username : "Aseso", 
				staffid : "991234" 
				} 
		}); 
</script> 
</body>
</html>
