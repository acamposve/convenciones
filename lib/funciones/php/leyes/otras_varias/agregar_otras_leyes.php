<?
$enviar=$_POST['enviar'];

if ($enviar==1){
$fecha_publicacion=$_REQUEST['fecha_publicacion'];
$descripcion_resumen=$_REQUEST['descripcion_resumen'];
$nombre_ley=$_REQUEST['nombre_ley'];
$ambito=$_REQUEST['ambito'];
$empresa=$_REQUEST['empresa'];
$origen=$_REQUEST['origen'];


//manejo de archivos
	$tipo=$_FILES['pdf_asociado']['type'];
	$pdf_asociado=$_FILES['pdf_asociado']['name'];
		if($tipo=='application/pdf'){
			$ext = 'pdf';
		}

		if($tipo=='image/jpeg'){
			$ext = 'jpg';
		}	

		if($tipo=='image/png'){
			$ext = 'png';
		}
 
  if ($pdf_asociado=="")
	{?>
		<script language="javascript" type="text/javascript">
		<!--
		alert("Debe Asociar un PDF al Contrato");
		-->
		</script>
        
	<? }
	else 
	{
 
	$tamano_archivo = $_FILES['pdf_asociado']['size'];

			if($tipo!=""){
				$var=$otras_leyes->insertarLey($nombre_ley, $fecha_publicacion, $descripcion_resumen,$pdf_asociado,$origen);
				$var=$otras_leyes->obtenerUltimaLey();
				$codigo_otra_ley=$otras_leyes->codigo_otra_ley;
				$directorio="modulos/leyes/otras_varias/documentos/";
				$carpeta=$codigo_otra_ley;
				$ruta=$directorio.$carpeta;
				if(mkdir($ruta,0777)){
				print $ruta." - CREADA";
				}
					if (move_uploaded_file($_FILES['pdf_asociado']['tmp_name'], $ruta."/".$pdf_asociado)){ 
						chmod( $ruta.$nombre, 0777);
						$msg= "El archivo ha sido cargado correctamente."; 
					}else{ 
						$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
					} 
			}




		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=leyes&tbl=Otras%20Varias";
		-->
		</script>
		<?
		}
	}
}
?>
