<?
	$enviar=$_POST['enviar'];

	if ($enviar ==1)
	{
		$id_pais=$_POST['id_pais'];
		$nombre=$_POST['nombreestado'];
		$error=$estado->insert($nombre, $id_pais);

		if ($error)
		{
			$msg="SE HA AGREGADO UN ESTADO";
			?>
			<script type="text/javascript">
		
			window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
			</script>
			<?	
		}
	}

?>