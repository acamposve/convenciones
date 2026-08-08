<?
$enviar=$_POST['enviar'];
$codigo_anexo=$_GET['id'];
$var=$contratos->obtenerAnexoContratosPorId($codigo_anexo);
$pdf_anexo=$contratos->pdf_anexo;
$codigo_contrato=$contratos->codigo_contrato;
		if ($enviar==1){
			$nombre_anexo=$_REQUEST['nombre_anexo'];
			$descripcion=$_REQUEST['descripcion'];
			$texto_completo=$_REQUEST['texto_completo'];
			//manejo de archivos
			$tipo=$_FILES['pdf_asociado']['type'];
			?>
		<script language="javascript" type="text/javascript">
		</script>

<?
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
						$pdf_asociado=$pdf_anexo;
					}else{
						$pdf_asociado=$_FILES['pdf_asociado']['name'];


		$archivo=$_SERVER['DOCUMENT_ROOT']."/cccol/admin/modulos/contratos/contratos/documentos/".$codigo_contrato."/anexos/".$pdf_anexo;
		$archivo_eliminar="modulos/contratos/contratos/documentos/".$codigo_contrato."/anexos/".$pdf_anexo;
		if(file_exists($archivo)){
			chmod($archivo_eliminar,0777);
			unlink($archivo_eliminar);
	}
							
							if($ext!=""){
					
								$directorio="modulos/contratos/contratos/documentos/".$codigo_contrato."/anexos/";
								$ruta=$directorio;
		
							if (move_uploaded_file($_FILES['pdf_asociado']['tmp_name'], $ruta."/".$pdf_asociado)){ 
								chmod( $ruta.$nombre, 0777);
								$msg= "El archivo ha sido cargado correctamente."; 
							}else{ 
								$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
							} 
						
						
							}
						}
				
				
				
			$var=$contratos->actualizarAnexoContratos($nombre_anexo , $descripcion , $texto_completo ,  $pdf_asociado, $codigo_anexo);
			
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
	//}
?>