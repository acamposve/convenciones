<?
$enviar=$_GET['flag'];
$id_categoria=$_GET['id'];
$var=$categoria_sector->getId($id_categoria);
$nombre=$categoria_sector->nombre_categoria;
$descripcion=$categoria_sector->descripcion_categoria;
if ($enviar =="SI"){
 $flag_del=true; 


			?>
            
		<script type="text/javascript">
		<!--
		window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
		//-->
		</script>
			<?	


if($flag_del){
$error=$categoria_sector->delete($id_categoria);
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Categorias&accion=listar";
-->
</script>

<?
}
}

?>

