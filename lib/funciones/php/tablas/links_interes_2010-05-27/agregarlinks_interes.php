<?
	$enviar=$_POST['enviar'];

	if ($enviar ==1)
		{
			$Detalle	=$_POST['Detalle'];
			$Sector		=$_POST['Sector'];
			$Link		=$_POST['Link'];
			$error=$LinkI->insert($Sector,$Detalle,$Link);

		if ($error)
			{
			?>
			<script type="text/javascript">
			alert ("Se agrego el Registro");
			window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
			</script>
			<?	
			}
		}

?>