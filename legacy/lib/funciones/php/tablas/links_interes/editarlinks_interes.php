<?
$enviar=$_POST['enviar'];
$idLinkInteres=$_GET['id'];
$var=$LinkI->getId($idLinkInteres);
	$lit_Sector=$LinkI->lit_Sector;
	$lit_Detalle=$LinkI->lit_Detalle;
	$lit_Link=$LinkI->lit_Link;
	if ($enviar ==1){
		$lit_Sector		=$_POST['Sector'];
		$lit_Detalle	=$_POST['Detalle'];
		$lit_Link		=$_POST['Link'];

		$error=$LinkI->update($idLinkInteres,$lit_Sector,$lit_Detalle,$lit_Link );
		
		if ($error){
			$refer="?url=tablas&tbl=Links de Interes";
			?>
			<script type="text/javascript" language="javascript">
			<!--
			alert ("Registro Modificado");
			location.href="index.php?url=tablas&tbl=Links de Interes&accion=listar";
			-->
			</script>
			<?
			}
	}

?>