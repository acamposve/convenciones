<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$nombre=$_POST['nombretipo'];
$error=$tipo_usuario->insert($nombre);

if ($error){
$msg="SE HA AGREGADO UN TIPO DE USUARIO";
		?>
		<script type="text/javascript">
		<!--
		window.location = "?url=seguridad&tbl=<?php echo $tabla; ?>"
		//-->
		</script>
	<?	

}
}

?>