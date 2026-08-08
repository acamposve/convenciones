<?
$enviar=$_POST['enviar'];
$id_actividad=$_GET['id'];
$var=$actividades->getId($id_actividad);
$nombre=$actividades->nombre_actividad;
$descripcion=$actividades->descripcion_actividad;
if ($enviar ==1){
$nombre=$_POST['nombre'];
$descripcion=$_POST['descripcion'];
$error=$actividades->update($nombre,$descripcion,$id_actividad);

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Actividades&accion=listar";
-->
</script>
<?
}
}

?>