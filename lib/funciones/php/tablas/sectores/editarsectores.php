<?
$enviar=$_POST['enviar'];
$id_sector=$_GET['id'];
$var=$sectores->getId($id_sector);
$nombre=$sectores->nombre_sector;
$descripcion=$sectores->descripcion_sector;
if ($enviar ==1){
$nombre=$_POST['nombre'];
$descripcion=$_POST['descripcion'];
$error=$sectores->update($nombre,$descripcion,$id_sector);

if ($error){
$refer="?url=tablas&tbl=Titulos Comparativos";
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Sectores&accion=listar";
-->
</script>
<?
}
}

?>