<?
$enviar=$_POST['enviar'];
$var=$ley_trabajo->get_Ley_Trabajo();
$pdf_asociado=$ley_trabajo->pdf_asociado;
$fecha_publicacion=$ley_trabajo->fecha_publicacion;
$descripcion_resumen=$ley_trabajo->descripcion_resumen;
$total_articulos=$ley_trabajo->total_articulos;

 
if ($enviar==1){
$tipo=$_FILES['pdf_asociado']['type'];
$nombre=$_FILES['pdf_asociado']['name'];

	if($nombre == ""){
		$nombre = $pdf_asociado;
	}else{
			$eliminar="modulos/leyes/ley_trabajo/media/".$pdf_asociado;
			unlink ($eliminar); 
	}
	
	if($tipo=='application/pdf'){
		$ext = 'pdf';
	}
	
	if($tipo=='image/jpeg'){
		$ext = 'jpg';
	}	
	
	if($tipo=='image/png'){
		$ext = 'png';
	}
   
		
	$tamano_archivo = $_FILES['pdf_asociado']['size'];
	
	if($ext!=""){

				if (move_uploaded_file($_FILES['pdf_asociado']['tmp_name'], 'modulos/leyes/ley_trabajo/media/'.$nombre)){ 
										chmod( 'modulos/leyes/ley_trabajo/media/'.$nombre, 0777);
										$msg= "El archivo ha sido cargado correctamente."; 
									}else{ 
										$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
									} 
					}

					$fecha_publicacion=$_REQUEST['fecha_publicacion'];
					$descripcion_resumen=$_REQUEST['descripcion_resumen'];
					$var=$ley_trabajo->updateLey_trabajo($fecha_publicacion, $nombre, $descripcion_resumen,$pdf_asociado);

					if ($var){
					?>
					<script language="javascript" type="text/javascript">
					<!--
					location.href="index.php?url=leyes&tbl=LOT";
					-->
					</script>
					<?
					}
		}

?>
