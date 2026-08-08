<?
$enviar=$_POST['enviar'];
$codigo_discusion=$_GET['id'];
$var=$discusion->obtenerDiscusionesPorId($codigo_discusion);	
	$pdf_peticiones_sindicato	= 	$discusion->pdf_peticiones_sindicato;
	$pdf_ofertas_patrono	= 	$discusion->pdf_ofertas_patrono;
		if ($enviar==1){
			$representanteEm=$_REQUEST['representanteEm'];
			$representanteSn=$_REQUEST['representanteSn'];
			$representanteMt=$_REQUEST['representanteMt'];
			$telefonoEm=$_REQUEST['telefonoEm'];
			$telefonoSn=$_REQUEST['telefonoSn'];
			$telefonoMt=$_REQUEST['telefonoMt'];
			$emailEm=$_REQUEST['emailEm'];
			$emailSn=$_REQUEST['emailSn'];
			$emailMt=$_REQUEST['emailMt'];
			$cargoEm=$_REQUEST['cargoEm'];
			$cargoSn=$_REQUEST['cargoSn'];
			$cargoMt=$_REQUEST['cargoMt'];
			$peticiones=$_REQUEST['peticiones'];
			$oferta=$_REQUEST['oferta'];
			$empresa=$_REQUEST['empresa'];
			$inicio=$_REQUEST['inicio'];
			//manejo de archivos
			

	$tipo1=$_FILES['peticiones']['type'];
	$peticiones=$_FILES['peticiones']['name'];
		if($tipo1=='application/pdf'){
			$ext = 'pdf';
		}else if($tipo1=='image/jpeg'){
			$ext = 'jpg';
		}else if($tipo1=='image/png'){
			$ext = 'png';
		}
		
	$tipo2=$_FILES['oferta']['type'];
	$oferta=$_FILES['oferta']['name'];
		if($tipo2=='application/pdf'){
			$ext2 = 'pdf';
		}else if($tipo2=='image/jpeg'){
			$ext2 = 'jpg';
		}else if($tipo2=='image/png'){
			$ext2 = 'png';
		}

			
					if($_FILES['peticiones']['name']==""){
						$pdf_peticiones=$pdf_peticiones_sindicato;
					}else{
						$pdf_peticiones=$_FILES['peticiones']['name'];
$archivo=$_SERVER['DOCUMENT_ROOT']."/cccol/admin/modulos/contratos/discusion/documentos/".$codigo_discusion."/".$pdf_peticiones_sindicato;
$archivo_eliminar="modulos/contratos/discusion/documentos/".$codigo_discusion."/".$pdf_peticiones_sindicato;
						if(file_exists($archivo)){
						chmod($archivo_eliminar,0777);
						unlink($archivo_eliminar);
						}
						
						if($ext!=""){
				
							$directorio="modulos/contratos/discusion/documentos/";
							$carpeta=$codigo_discusion;
							$ruta=$directorio.$carpeta;
	
								if (move_uploaded_file($_FILES['peticiones']['tmp_name'], $ruta."/".$pdf_peticiones)){ 
								chmod( $ruta.$nombre, 0777);
								$msg= "El archivo ha sido cargado correctamente."; 
								}else{ 
								$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
								} 
					
					
						}
					}
					
					if($_FILES['oferta']['name']==""){
						$pdf_oferta=$pdf_ofertas_patrono;
					}else{
						$pdf_oferta=$_FILES['oferta']['name'];
$archivo=$_SERVER['DOCUMENT_ROOT']."/cccol/admin/modulos/contratos/discusion/documentos/".$codigo_discusion."/".$pdf_oferta;
$archivo_eliminar="modulos/contratos/discusion/documentos/".$codigo_discusion."/".$pdf_oferta;
						if(file_exists($archivo)){
						chmod($archivo_eliminar,0777);
						unlink($archivo_eliminar);
						}
						
						if($ext!=""){
				
							$directorio="modulos/contratos/contratos/documentos/";
							$carpeta=$codigo_discusion;
							$ruta=$directorio.$carpeta;
	
								if (move_uploaded_file($_FILES['oferta']['tmp_name'], $ruta."/".$pdf_oferta)){ 
								chmod( $ruta.$nombre, 0777);
								$msg= "El archivo ha sido cargado correctamente."; 
								}else{ 
								$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
								} 
						}
					}
			
			
			
		$var=$discusion->actualizarDiscusiones($representanteEm,$representanteSn,$representanteMt,$telefonoEm,$telefonoSn,$telefonoMt,$emailEm,$emailSn,$emailMt,$cargoEm,$cargoSn,$cargoMt,$pdf_peticiones,$pdf_oferta,$empresa,$inicio,$codigo_discusion);
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora";
		-->
		</script>
		<?
		}
		}
?>
