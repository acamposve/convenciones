<?
$enviar=$_POST['Submit'];

$id_pais=$_POST['id_pais'];
$id_estado=$_POST['id_estado'];
$nombre_empresa=$_REQUEST['nombre'];
$direccion_empresa=$_REQUEST['direccion'];
$codigo_pais=$_REQUEST['id_pais'];
$codigo_estado=$_REQUEST['id_estado'];
$codigo_localidad=$_REQUEST['id_localidad'];
$telefonos_empresa=$_REQUEST['telefonos'];
$fax_empresa=$_REQUEST['fax'];
$email_empresa=$_REQUEST['email'];
$url_empresa=$_REQUEST['web'];
$rif_empresa=$_REQUEST['rif'];
$nit_empresa=$_REQUEST['nit'];
$contacto_empresa=$_REQUEST['persona_contacto'];
$cargo_contacto=$_REQUEST['cargo'];
$telefonos_contacto=$_REQUEST['telefono_contacto'];
$email_contacto=$_REQUEST['email_contacto'];
$codigo_sector=$_REQUEST['sector'];
$codigo_tipo=$_REQUEST['tipos'];
$codigo_categoria=$_REQUEST['categoria'];
$codigo_actividad=$_REQUEST['actividad'];
$cantidad_obreros_empresa=$_REQUEST['obreros'];
$cantidad_empleados_empresa=$_REQUEST['empleados'];
$tamano_empresa=$_REQUEST['tamanio_empresa'];

				//manejo de archivos
				$tipo1=$_FILES['imagen_1']['type'];
				$img_1=$_FILES['imagen_1']['name'];
					if($tipo1=='application/pdf'){
						$ext = 'pdf';
					}else if($tipo1=='image/jpeg'){
						$ext = 'jpg';
					}else if($tipo1=='image/png'){
						$ext = 'png';
					}
if ($enviar !=""){
				if($tipo1!==""){

					$error=$empresas->insert($nombre_empresa, $direccion_empresa, $codigo_pais, $codigo_estado, $codigo_localidad, $telefonos_empresa, $fax_empresa, $email_empresa, $url_empresa, $rif_empresa, $nit_empresa, $contacto_empresa, $cargo_contacto, $telefonos_contacto, $email_contacto, $codigo_sector, $codigo_tipo, $codigo_categoria, $codigo_actividad, $cantidad_obreros_empresa, $cantidad_empleados_empresa, $tamano_empresa,$img_1 );
					$codigo_empresa=$empresas->obtenerIdUltimaEmpresa();
				
			
						$directorio="modulos/tablas/empresa/documentos/";
						$carpeta=$codigo_empresa;
						$ruta=$directorio.$carpeta;

						mkdir("modulos/tablas/empresa/documentos/".$carpeta);
							  if($ext!==""){
								if (move_uploaded_file($_FILES['imagen_1']['tmp_name'], $ruta."/".$img_1)){ 
										chmod( $ruta."/".$img_1, 0777);
										$msg=  "El archivo ha sido cargado correctamente."; 
							
									}else{ 
										$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
									} 		
								}  

							  
				} else {
					print "2<br>";
						$error=$empresas->insert($nombre_empresa, $direccion_empresa, $codigo_pais, $codigo_estado, $codigo_localidad, $telefonos_empresa, $fax_empresa, $email_empresa, $url_empresa, $rif_empresa, $nit_empresa, $contacto_empresa, $cargo_contacto, $telefonos_contacto, $email_contacto, $codigo_sector, $codigo_tipo, $codigo_categoria, $codigo_actividad, $cantidad_obreros_empresa, $cantidad_empleados_empresa, $tamano_empresa,$img_1 );
						$codigo_empresa=$empresas->obtenerIdUltimaEmpresa();
						
			
						$directorio="modulos/tablas/empresa/documentos/";
						$carpeta=$codigo_empresa;
						$ruta=$directorio.$carpeta;
									mkdir("modulos/tablas/empresa/documentos/".$carpeta);
									?>
									<script type="text/javascript" language="javascript">
									<!--
									alert ("Recuerde Incluir El logo de esta Empresa")
									-->
									</script>
									<?				
							}
			
						if ($error){
			
							?>
								<script type="text/javascript" language="javascript">
								<!--
								window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
								-->
								</script>
								<?
							}
					
		}
		
	
?>