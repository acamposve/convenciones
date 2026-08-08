<?
$codigo_discusion=$_GET['codigo_discusion'];
$enviar=$_POST['enviar'];

if ($enviar==1){
$nroPeticion=$_REQUEST['nroPeticion'];
$textoPeticion=$_REQUEST['textoPeticion'];
$titulo=$_REQUEST['titulo'];
$tituloPeticion=$_REQUEST['tituloPeticion'];

			$var=$discusion->insertarPeticionDiscusion($codigo_discusion, $nroPeticion, $textoPeticion, $titulo,$tituloPeticion);


		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Discusion%20de%20contratos&accion=listar_peticiones&id=<? print $codigo_discusion; ?>";
		-->
		</script>
		<?
		}
}
?>
