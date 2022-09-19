<?
$enviar=$_GET['flag'];
$id_actividad=$_GET['id'];
$var=$actividades->getId($id_actividad);
$nombre=$actividades->nombre_actividad;
$descripcion=$actividades->descripcion_actividad;
if ($enviar =="SI")
	{

	$flag_del=true; 
	}

if($flag_del){
$error=$actividades->delete($id_actividad);


			?>
            
		<script type="text/javascript">
		<!--
		window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
		//-->
		</script>
			<?

}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Actividades&accion=listar";
-->
</script>

<?
}


?>

