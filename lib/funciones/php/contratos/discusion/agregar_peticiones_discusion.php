<?
$codigo_discusion=$_GET['codigo_discusion'];
$enviar=$_POST['enviar'];

if ($enviar==1){
$nroPeticion=$_REQUEST['nroPeticion'];
$textoPeticion=$_REQUEST['textoPeticion'];
$titulo=$_REQUEST['tituloPeticion'];

$tituloPeticion=$_REQUEST['titulo_comparativo'];
			$nro_articulo= trim($nroPeticion);
			$cadena=split("-",$nro_articulo); 
			switch ($cadena) {
			
				case (count($cadena) >= 3):
					$nro_articulo= $cadena[0]."-".$cadena[1];
				break;
			   
				case (strlen($cadena[0])==3):
					if ($cadena[1] == ""){
					$nro_articulo= $cadena[0];
					} 	else {
						$nro_articulo= $cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==2):
					if ($cadena[1] == ""){
					$nro_articulo= "0".$cadena[0];
					} 	else {
						$nro_articulo= "0".$cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==1): 
					if ($cadena[1] == ""){
					$nro_articulo= "00".$cadena[0];
					} 	else {
						$nro_articulo= "00".$cadena[0]."-".$cadena[1];
						}
					break;
				break;
			}	

			$nroPeticion=$nro_articulo;
			$var=$discusion->insertarPeticionDiscusion($codigo_discusion, $nroPeticion, $textoPeticion, $titulo,0);


		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_peticiones&id=<? print $codigo_discusion; ?>";
		-->
		</script>
		<?
		}
}
?>
