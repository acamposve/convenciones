<?
$enviar=$_GET['flag'];
$id_sector=$_GET['id'];
$var=$sectores->getId($id_sector);
$nombre=$sectores->nombre_sector;
$descripcion=$sectores->descripcion_sector;
if ($enviar =="SI"){
$flag_del=true;

			?>
            
		<script type="text/javascript">
		<!--
		window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
		//-->
		</script>
			<?	


}

if($flag_del)
	{
	$error=$sectores->delete($id_sector );
	}

if ($error)
	{
	?>
	<script language="javascript" type="text/javascript">
	location.href="index.php?url=tablas&tbl=Sectores&accion=listar";
	</script>

	<?
}


?>

