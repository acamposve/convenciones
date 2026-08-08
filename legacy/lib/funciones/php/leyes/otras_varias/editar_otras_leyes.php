<?
$enviar=$_POST['enviar'];

$codigo_ley = $_GET['id'];
$var=$otras_leyes->get_Ley($codigo_ley);
$codigo_otra_ley=$otras_leyes->codigo_otra_ley;
$nombre_ley	=$otras_leyes->nombre_ley;
$fecha_publicacion_ley=$otras_leyes->fecha_publicacion_ley;
$descripcion_ley=$otras_leyes->descripcion_ley;
$pdf_ley=$otras_leyes->pdf_ley;
$total_articulos_ley=$otras_leyes->total_articulos_ley;

		if ($enviar==1){
			$fecha_publicacion=$_REQUEST['fecha_publicacion'];
			$descripcion_resumen=$_REQUEST['descripcion_resumen'];
			$nombre_ley=$_REQUEST['nombre_ley'];
			$origen=$_REQUEST['origen'];
			//manejo de archivos
			$tipo=$_FILES['pdf_asociado']['type'];
			

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
			
					if($_FILES['pdf_asociado']['name']==""){
						$pdf_asociado=$pdf_ley;
					}else{
						$pdf_asociado=$_FILES['pdf_asociado']['name'];
						$archivo="modulos/leyes/otras_varias/documentos/".$codigo_ley."/".$pdf_ley;
						unlink($archivo);
						if($ext!=""){
				
							$directorio="modulos/leyes/otras_varias/documentos/";
							$carpeta=$codigo_ley;
							$ruta=$directorio.$carpeta;
	
								if (move_uploaded_file($_FILES['pdf_asociado']['tmp_name'], $ruta."/".$pdf_asociado)){ 
									chmod( $ruta.$nombre, 0777);
									$msg= "El archivo ha sido cargado correctamente."; 
								}else{ 
									$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
								} 
					
					
						}
					}
			
			
			
		$var=$otras_leyes->actualizarOtrasLeyes($nombre_ley, $fecha_publicacion, $pdf_asociado, $descripcion_resumen,$codigo_ley,$origen);
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=leyes&tbl=Otras Varias";
		-->
		</script>
		<?
		}
	
}
?>
