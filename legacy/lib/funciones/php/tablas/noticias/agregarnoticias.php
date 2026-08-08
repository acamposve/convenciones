<?

	$enviar=$_POST['enviar'];
	if ($enviar ==1){
		$titulo=$_POST['titulo'];
		$fecha=$_POST['fecha'];
		$texto_completo=$_POST['texto_completo'];
		$texto_resumen=$_POST['texto_resumen'];
		$categorias_noticias=$_POST['categorias_noticias'];
		$estatus_noticia=$_POST['estatus_noticia'];
		$origen 		= $_POST['origen'];
		if ((trim($origen))==""){
			?>
			<script type="text/javascript" language="javascript">
			<!--
			alert ("El dato Origen no Puede estar en Blanco o Tiene un formato errado")
			location.href="index.php?url=tablas&tbl=Noticias&accion=agregar";
			-->
			</script>


            <?php
		}else{	
			//manejo de archivos
				$tipo1=$_FILES['imagen_1']['type'];
				$img_1=$_FILES['imagen_1']['name'];

				if($tipo1=='application/pdf'){
					$ext1 = 'pdf';
				}else{
					$ext1='';
				}

				if($ext1!=""){
			
						$error=$Noticias->insertarNoticias( $titulo,$fecha,$texto_completo,$texto_resumen,$categorias_noticias,$estatus_noticia, $img_1, "", $origen );
						$codigo_noticia=$Noticias->obtenerIdUltimaNoticia();
			
						$directorio="modulos/tablas/noticias/documentos/";
						$carpeta=$codigo_noticia;
						$ruta=$directorio.$carpeta;
						mkdir("modulos/tablas/noticias/documentos/".$carpeta);
							  if($ext!==""){
								if (move_uploaded_file($_FILES['imagen_1']['tmp_name'], $ruta."/".$img_1)){ 
										chmod( $ruta."/".$img_1, 0777);
										$msg=  "El archivo ha sido cargado correctamente."; 
							
									}else{ 
										$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
									} 		
								}  
							  if($ext2!==""){
								if (move_uploaded_file($_FILES['imagen_2']['tmp_name'], $ruta."/".$img_2)){ 
										//chmod( $ruta."/".$img_2, 0777);
										$msg= "El archivo ha sido cargado correctamente."; 
									}else{ 
										$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse"; 
								} 
							  }
							  
							} else {
									$error=$Noticias->insertarNoticias( $titulo,$fecha,$texto_completo,$texto_resumen,$categorias_noticias,$estatus_noticia,"","", $origen);
									$codigo_noticia=$Noticias->obtenerIdUltimaNoticia();
						
									$directorio="modulos/tablas/noticias/documentos/";
									$carpeta=$codigo_noticia;
									$ruta=$directorio.$carpeta;
									mkdir("modulos/tablas/noticias/documentos/".$carpeta);
									?>
									<script type="text/javascript" language="javascript">
									<!--
									alert ("La Noticia Sera Incluida pero \n Recuerde Incluir El Boletin")
									-->
									</script>
									<?				
							}
			
						if ($error){
			
							?>
								<script type="text/javascript" language="javascript">
								<!--
								location.href="index.php?url=tablas&tbl=Noticias";
								-->
								</script>
								<?
							}
					
					}
		
			}	
		
		

?>