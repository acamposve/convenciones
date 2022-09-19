<?
$enviar=$_POST['enviar'];
$id_categoria=$_GET['id'];
$var=$categoria_sector->getId($id_categoria);
$nombre=$categoria_sector->nombre_categoria;
$descripcion=$categoria_sector->descripcion_categoria;
if ($enviar ==1){
$nombre=$_POST['nombre'];
$descripcion=$_POST['descripcion'];
$error=$categoria_sector->update($nombre,$descripcion,$id_categoria);

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