<?
	$id_noticia=$_GET['id'];
	
	$enviar=$_POST['enviar'];
	$var=$pais->getId($id_pais);
	$nombre=$pais->nombre;
	if ($enviar ==1){
		$titulo=$_POST['titulo'];
		$fecha=$_POST['fecha'];
		$texto_completo=$_POST['texto_completo'];
		$texto_resumen=$_POST['texto_resumen'];
		$categorias_noticias=$_POST['categorias_noticias'];
		$estatus_noticia=$_POST['estatus_noticia'];
		$img_car_1	=$_POST['imagen_1'];
		$img_car_2	=$_POST['imagen_2'];
		$origen 	=$_POST['origen'];

	if ((trim($origen))==""){
	?>
		<script type="text/javascript" language="javascript">
        <!--
        alert ("El dato Origen no Puede estar en Blanco o Tiene un formato errado")
        location.href="index.php?url=tablas&tbl=Noticias&accion=editar&id=<? print $id_noticia;?>";
        -->
        </script>

	<?php
	}else{
		//Imagenes		
		$var=$Noticias->getId( $id_noticia );
	    while( list( $key, $val) = each($var) ) 
			{
				$img_bd_1				= $var[$key][imagen_1];
				$img_bd_2				= $var[$key][imagen_2];
				}

	//manejo de archivos
				$tipo1=$_FILES['imagen_1']['type'];
				$img_1=$_FILES['imagen_1']['name'];

				if($tipo1=='application/pdf'){
					$ext1 = 'pdf';
				}else{
					$ext1='';
				}


	//JUEGO PARA SABER QUE SI SE CARGO UN ARCHIVO ELIMINAR EL ANTERIOR Y SUBIR EL NUEVO, O DEJAR EL ANTERIOR
			$directorio="modulos/tablas/noticias/documentos/";
			$carpeta=$id_noticia;
			$ruta=$directorio.$carpeta;	
			//imagen 1
			if ($ext1!="")
				{	
					//ELIMINAR ARCHIVO EXISTENTE
					$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/noticias/documentos/".$id_noticia."/".$img_bd_1;
					$archivo_eliminar="modulos/tablas/noticias/documentos/".$id_noticia."/".$img_bd_1;
					if(file_exists($archivo))
						{
						chmod($archivo,0777);
						unlink($archivo);
						}
					//SUBIR ARCHIVO NUEVO 
					if (move_uploaded_file($_FILES['imagen_1']['tmp_name'], $ruta."/".$img_1)){ 
						chmod( $ruta."/".$img_1, 0777);
							$msg =  "<br>El archivo ha sido cargado correctamente.".$img_1; 
						}else{ 
							$msg = "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
						} 	
					$sus_img_1=$Noticias->update_imagen1( $id_noticia,$img_1 );
				}elseif ($tipo1!="")
					{
					?>
					<script type="text/javascript" language="javascript">
					<!--
					alert ("Formatos diferentes a Imagen 1 \n No sera cargada");
					-->
					</script>
					<?
					}
	
			/* //imagen 2
			if ($ext2!="")
				{	
					//ELIMINAR ARCHIVO EXISTENTE
					$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/noticias/documentos/".$id_noticia."/".$img_bd_2;
					$archivo_eliminar="modulos/tablas/noticias/documentos/".$id_noticia."/".$img_bd_2;
					if(file_exists($archivo))
						{
						chmod($archivo,0777);
						unlink($archivo);
						}
					//SUBIR ARCHIVO NUEVO 
					if (move_uploaded_file($_FILES['imagen_2']['tmp_name'], $ruta."/".$img_2)){ 
						chmod( $ruta."/".$img_2, 0777);
							$msg =  "El archivo ha sido cargado correctamente. ".$img_2; 
						}else{ 
							$msg = "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
						} 	
					$sus_img_2=$Noticias->update_imagen2( $id_noticia,$img_2 );
				}elseif ($tipo2!="")
					{
					?>
					<script type="text/javascript" language="javascript">
					<!--
					alert ("Formatos diferentes a Imagen 2 \n No sera cargada");
					-->
					</script>
					<?
					} */
	

	
	
	
			$error=$Noticias->update( $id_noticia,$titulo,$fecha,$texto_completo,$texto_resumen,$categorias_noticias,$estatus_noticia, $origen  );
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