<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$nombre=$_POST['nombre'];
$descripcion=$_POST['descripcion'];
$error=$categoria_sector->insert($nombre,$descripcion);

if ($error){
$msg="SE HA AGREGADO UNA CATEGORIA SECTOR";

		?>
		<script type="text/javascript">
		<!--
		window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
		//-->
		</script>
	<?	
		
}
}

?>