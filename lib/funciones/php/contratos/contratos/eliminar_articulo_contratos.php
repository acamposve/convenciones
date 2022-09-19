<?
$enviar=$_GET['flag'];
$page_return = $_GET['page'];	
$codigo_articulo=$_GET['id'];

$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$codigo_contrato=$contratos->codigo_contrato;
	if ($enviar=="SI"){
		$var=$contratos->eliminarArticuloContrato($codigo_articulo);
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--

		location.href="index.php?url=contratos&tbl=Contratos&accion=listar_articulos&id=<? print $codigo_contrato ?>&page=<? print $page_return ?>";
		-->
		</script>
		<?
		}
	}
?>
