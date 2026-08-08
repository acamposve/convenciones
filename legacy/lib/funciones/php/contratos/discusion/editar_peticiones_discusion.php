<?
$enviar=$_POST['enviar'];
$codigo_bitacora = $_GET['id_bitacora'];
		if ($enviar==1){
			$nroPeticion=$_REQUEST['nroPeticion'];
			$textoPeticion=$_REQUEST['textoPeticion'];
			$tituloPeticion=$_REQUEST['tituloPeticion'];
			$titulo_comparativo=$_REQUEST['titulo_comparativo'];
			$codigoPeticion=$_GET['id'];
			$cadena=split("-",$nroPeticion); 
			switch ($cadena) {
			
				case (count($cadena) >= 3):
					$nroPeticion= $cadena[0]."-".$cadena[1];
				break;
			   
				case (strlen($cadena[0])==3):
					if ($cadena[1] == ""){
					$nroPeticion= $cadena[0];
					} 	else {
						$nroPeticion= $cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==2):
					if ($cadena[1] == ""){
					$nroPeticion= "0".$cadena[0];
					} 	else {
						$nro_articulo= "0".$cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==1): 
					if ($cadena[1] == ""){
					$nroPeticion= "00".$cadena[0];
					} 	else {
						$nroPeticion= "00".$cadena[0]."-".$cadena[1];
						}
					break;
				break;
			}	

			
			$var=$discusion->actualizarPeticionesDiscusiones($codigo_bitacora, $nroPeticion, $textoPeticion,$tituloPeticion,0, $codigoPeticion);
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_peticiones&id=<? print $codigo_bitacora?>";
		-->
		</script>
		<?
		}
		}
?>
