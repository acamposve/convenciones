<?
$enviar=$_GET['flag'];
$id_titulo=$_GET['id'];
$var=$titulos->getId($id_titulo);
$descripcion_titulo=$titulos->descripcion_titulo;
$categoria_titulo=$titulos->codigo_categoria_titulo;
$nombre_titulo=$titulos->nombre_titulo;
if ($enviar =="SI"){

?>
<script language="javascript" type="text/javascript">
<!--
{
<? $flag_del=true; ?>
}
-->
</script>

<?
if($flag_del){
$error=$titulos->delete($id_titulo );
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Comparativo%20de%20Clausulas&accion=listar";
-->
</script>

<?
}
}

?>

