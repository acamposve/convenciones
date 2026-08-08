<?
$enviar=$_POST['enviar'];
$codigo_bitacora = $_GET['id_bitacora'];
		if ($enviar==1){
			$nroPeticion=$_REQUEST['nroPeticion'];
			$textoPeticion=$_REQUEST['textoPeticion'];
			$tituloPeticion=$_REQUEST['tituloPeticion'];
			$titulo_comparativo=$_REQUEST['titulo_comparativo'];
			$codigoPeticion=$_GET['id'];
			$var=$discusion->actualizarPeticionesDiscusiones($codigo_bitacora, $nroPeticion, $textoPeticion,$tituloPeticion,$titulo_comparativo, $codigoPeticion);
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Discusion%20de%20contratos&accion=listar_peticiones&id=<? print $codigo_bitacora?>";
		-->
		</script>
		<?
		}
		}
?>
