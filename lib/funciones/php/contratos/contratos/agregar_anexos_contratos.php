<?
$enviar=$_POST['enviar'];
		$codigo_contrato=$_GET['id'];
		if ($enviar==1){
			$nombre_anexo=$_REQUEST['nombre_anexo'];
			$descripcion=$_REQUEST['descripcion'];
			$texto_completo=$_REQUEST['texto_completo'];

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

	$tamano_archivo = $_FILES['pdf_asociado']['size'];
	if ($pdf_asociado=="")
	{?>
		<script language="javascript" type="text/javascript">
		<!--
		alert("Debe Asociar un PDF para los Anexos");
		-->
		</script>
        
	<? }
	else
	{
			if($ext!=""){
			$archivo=$pdf_asociado;
			$var=$contratos->insertarAnexosContrato($nombre_anexo, $descripcion, $texto_completo, $archivo, $codigo_contrato);
			$codigo_anexo=$contratos->obtenerIdUltimoAnexoContrato();
			$directorio="modulos/contratos/contratos/documentos/".$codigo_contrato."/";
			$carpeta="anexos";
			$ruta=$directorio.$carpeta;
			
			
			
			if(is_dir($ruta)){
				if (move_uploaded_file($_FILES['pdf_asociado']['tmp_name'], $ruta."/".$archivo)){ 
					chmod( $ruta."/".$archivo, 0777);
					$msg= "El archivo ha sido cargado correctamente."; 
				}else{ 
					$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
				} 
			}elseif(mkdir($ruta,0777)){
				}
					if (move_uploaded_file($_FILES['pdf_asociado']['tmp_name'], $ruta."/".$archivo)){ 
						chmod( $ruta."/".$archivo, 0777);
						$msg= "El archivo ha sido cargado correctamente."; 
					}else{ 
						$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
					} 
				}



		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		window.location.href="index.php?url=contratos&tbl=Contratos&accion=listar_anexos&id=<? print $codigo_contrato ?>";
		-->
		</script>
		<?
		}
		}
	}	
?>
