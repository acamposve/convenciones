<?
$enviar=$_POST['enviar'];
$codigo_bitacora = $_GET['id_bitacora'];
		if ($enviar==1){
			$nroOferta=$_REQUEST['nroOferta'];
			$oferta=$_REQUEST['oferta'];
			$peticion=$_REQUEST['peticion'];
			$titulo=$_REQUEST['titulo'];
			$tituloOferta=$_REQUEST['tituloOferta'];
			$codigoOferta=$_GET['id'];
	
			$var=$discusion->actualizarOfertasDiscusiones($codigo_bitacora, $nroOferta, $oferta,$peticion,$titulo,$tituloOferta, $codigoOferta);
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Discusion%20de%20contratos&accion=listar_ofertas&id=<? print $codigo_bitacora?>";
		-->
		</script>
		<?
		}
		}
?>
