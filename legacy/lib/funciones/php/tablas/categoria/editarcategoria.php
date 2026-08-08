
<?
$enviar=$_POST['enviar'];
$id_categoria=$_GET['id'];
$var=$categorias_titulos->getId($id_categoria);
$descripcion_categoria=$categorias_titulos->descripcion_categoria;
$comparacion=$categorias_titulos->requiere_campo_comparacion_economica;
$nombre_categoria=$categorias_titulos->nombre_categoria;
if ($enviar ==1){
$categoria=$_POST['categoria'];
$descripcion=$_POST['descripcion'];
$comprobacion=$_POST['comprobacion'];
$error=$categorias_titulos->update($categoria,$descripcion,$comprobacion,$id_categoria);

if ($error){
$refer="?url=tablas&tbl=Categorias De Titulos";
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