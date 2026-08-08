<?
$codigo_bitacora=$_GET['id_bitacora'];

$enviar=$_POST['enviar'];

if ($enviar==1){

$FechaAcuerdo			=$_REQUEST['FechaAcuerdo'];
$nro_articulo			=$_REQUEST['NroClausula'];
$TituloAcuerdo			=$_REQUEST['TituloAcuerdo'];
$TextoCompletoAcuerdo	=$_REQUEST['TextoCompletoAcuerdo'];
$ResumenAcuerdo			=$_REQUEST['ResumenAcuerdo'];
$oferta					=$_REQUEST['oferta'];
$peticion				=$_REQUEST['peticion'];
$TituloComparativo		=$_REQUEST['TituloComparativo'];
$CampoComparativo		=$_REQUEST['CampoComparativo'];
$nro_articulo= trim($nro_articulo);
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
$nro_articulo= trim($nro_articulo);			
	$var=$discusion->agregarAcuerdo($codigo_bitacora, $peticion, $oferta, $FechaAcuerdo,$nro_articulo, $TextoCompletoAcuerdo, $ResumenAcuerdo, $TituloComparativo,$CampoComparativo,$TituloAcuerdo,"0");


		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="?url=contratos&tbl=Bitacora&accion=listar_acuerdos&id=<? print $codigo_bitacora; ?>";
		-->
		</script>
		<?
		}
}
?>
