<?
$enviar=$_GET['flag'];
$id_tipo=$_GET['id'];
$var=$tipo_usuario->getId($id_tipo);
$nombre=$tipo_usuario->nombre;
if ($enviar =="SI"){

?>

<? $flag_del=true; ?>


</script>

<?
if($flag_del){
$error=$tipo_usuario->delete($id_tipo );
		?>
		<script type="text/javascript">
		<!--
		window.location = "?url=seguridad&tbl=<?php echo $tabla; ?>"
		//-->
		</script>
	<?
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=seguridad&tbl=Tipos%20de%20Usuarios&accion=listar";
-->
</script>

<?
}
}

?>

