<?
$codigo_discusion=$_GET['id_bitacora'];

$enviar=$_POST['enviar'];

if ($enviar==1){
$nroOferta=$_REQUEST['nroOferta'];
$oferta=$_REQUEST['oferta'];
$peticion=$_REQUEST['peticion'];
$titulo=$_REQUEST['titulo'];
$tituloOferta=$_REQUEST['tituloOferta'];



			$var=$discusion->insertarOferta($codigo_discusion, $nroOferta, $oferta,$peticion,$titulo,$tituloOferta);

		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Discusion%20de%20contratos&accion=listar_ofertas&id=<? print $codigo_discusion; ?>";
	

		-->
		</script>
		<?
		}
}
?>
