<?
$enviar=$_POST['enviar'];

if ($enviar==1){
$duracion=$_REQUEST['duracion'];
$termino=$_REQUEST['termino'];
$inicio=$_REQUEST['inicio'];
$ambito=$_REQUEST['ambito'];
$empresa=$_REQUEST['empresa'];

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

			if($ext!=""){
			$var=$contratos->insertarContrato($pdf_asociado, $inicio, $termino, $duracion, $ambito, $empresa);
			$codigo_contrato=$contratos->obtenerIdUltimoContrato();
			$directorio="modulos/contratos/contratos/documentos/";
			$carpeta=$codigo_contrato;
			$ruta=$directorio.$carpeta;
			if(mkdir($ruta,0777)){
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

		location.href="index.php?url=contratos&tbl=Contratos";
		-->
		</script>
		<?
		}
	}
}
?>
