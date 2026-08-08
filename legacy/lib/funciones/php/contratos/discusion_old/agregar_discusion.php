<?
$enviar=$_POST['enviar'];

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


	if($ext!==""||$ext2!==""){
			$var=$discusion->insertarDiscusion($representanteEm,$representanteSn,$representanteMt,$telefonoEm,$telefonoSn,$telefonoMt,$emailEm,$emailSn,$emailMt,$cargoEm,$cargoSn,$cargoMt,$peticiones,$oferta,$empresa,$inicio);
			$codigo_discusion=$discusion->obtenerIdUltimaDiscusion();
			$directorio="modulos/contratos/discusion/documentos/";
			$carpeta=$codigo_discusion;
			$ruta=$directorio.$carpeta;
			mkdir("modulos/contratos/discusion/documentos/".$carpeta);
				  if($ext!=""){
					if (move_uploaded_file($_FILES['peticiones']['tmp_name'], $ruta."/".$peticiones)){ 
						chmod( $ruta."/".$peticiones, 0777);
						$msg= "El archivo ha sido cargado correctamente."; 
					}else{ 
						$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
					} 		
					}  
				  if($ext2!=""){
					if (move_uploaded_file($_FILES['oferta']['tmp_name'], $ruta."/".$oferta)){ 
						chmod( $ruta."/".$oferta, 0777);
						$msg= "El archivo ha sido cargado correctamente."; 
					}else{ 
						$msg= "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
					} 
				  }
				}




		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Discusion%20de%20contratos";
		-->
		</script>
		<?
		}
}
?>
