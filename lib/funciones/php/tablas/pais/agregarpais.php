<?
	$enviar=$_POST['enviar'];

	if ($enviar ==1)
		{
			$nombre=$_POST['nombrepais'];
			$error=$pais->insert($nombre);

		if ($error)
			{
			$msg="SE HA AGREGADO UN PAIS";
			?>
			<script type="text/javascript">
			window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
			</script>
			<?	
			}
		}

?>