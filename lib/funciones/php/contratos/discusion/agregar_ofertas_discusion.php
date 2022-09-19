<?
$codigo_discusion=$_GET['id_bitacora'];

$enviar=$_POST['enviar'];

if ($enviar==1){
$nroOferta=$_REQUEST['nroOferta'];
$oferta=$_REQUEST['oferta'];
$peticion=$_REQUEST['peticion'];
$titulo=$_REQUEST['titulo'];
$tituloOferta=$_REQUEST['tituloOferta'];

			$cadena=split("-",$nroOferta); 
			switch ($cadena) {
			
				case (count($cadena) >= 3):
					$nroOferta= $cadena[0]."-".$cadena[1];
				break;
			   
				case (strlen($cadena[0])==3):
					if ($cadena[1] == ""){
					$nroOferta= $cadena[0];
					} 	else {
						$nroOferta= $cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==2):
					if ($cadena[1] == ""){
					$nroOferta= "0".$cadena[0];
					} 	else {
						$nroOferta= "0".$cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==1): 
					if ($cadena[1] == ""){
					$nroOferta= "00".$cadena[0];
					} 	else {
						$nroOferta= "00".$cadena[0]."-".$cadena[1];
						}
					break;
				break;
			}	


			$var=$discusion->insertarOferta($codigo_discusion, $nroOferta, $oferta,$peticion,$titulo,$tituloOferta);

		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_ofertas&id=<? print $codigo_discusion; ?>";
		-->
		</script>
		<?
		}
}
?>
