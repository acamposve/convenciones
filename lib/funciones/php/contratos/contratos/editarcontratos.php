<?
$enviar=$_POST['enviar'];
$codigo_contrato=$_GET['id'];
$var=$contratos->obtenerContratosPorId($codigo_contrato);
$pdf=$contratos->pdf_auto;
		if ($enviar==1){
			$duracion=$_REQUEST['duracion'];
			$termino=$_REQUEST['termino'];
			$inicio=$_REQUEST['inicio'];
			$ambito=$_REQUEST['ambito'];
			$empresa=$_REQUEST['empresa'];
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
						$pdf_asociado=$pdf;
					}else{
						$pdf_asociado=$_FILES['pdf_asociado']['name'];
$archivo=$_SERVER['DOCUMENT_ROOT']."/cccol/admin/modulos/contratos/contratos/documentos/".$codigo_contrato."/".$pdf;
$archivo_eliminar="modulos/contratos/contratos/documentos/".$codigo_contrato."/".$pdf;
if(file_exists($archivo)){
	chmod($archivo_eliminar,0777);
	unlink($archivo_eliminar);
}
						
						if($ext!=""){
				
							$directorio="modulos/contratos/contratos/documentos/";
							$carpeta=$codigo_contrato;
							$ruta=$directorio.$carpeta;
	
								if (move_uploaded_file($_FILES['pdf_asociado']['tmp_name'], $ruta."/".$pdf_asociado)){ 
									chmod( $ruta.$nombre, 0777);
									$msg= "El archivo ha sido cargado correctamente."; 
								}else{ 
									$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
								} 
					
					
						}
					}
			
			
			
		$var=$contratos->actualizarContratos($pdf_asociado , $duracion , $inicio ,  $termino, $ambito , $empresa, $codigo_contrato);
		
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="?url=contratos&tbl=Contratos";
		-->
		</script>
		<?
		
		}
?>
