<?
$enviar=$_GET['flag'];
$id_categoria=$_GET['id'];
$var=$categorias_titulos->getId($id_categoria);
$descripcion_categoria=$categorias_titulos->descripcion_categoria;
$comparacion=$categorias_titulos->requiere_campo_comparacion_economica;
$nombre_categoria=$categorias_titulos->nombre_categoria;
if ($enviar =="SI"){

?>

<? $flag_del=true; ?>




<?
if($flag_del){
$error=$categorias_titulos->delete($id_categoria );
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Bloques%20de%20Clausulas&accion=listar";
-->
</script>

<?
}
}

?>

