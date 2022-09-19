<?
$enviar=$_POST['enviar'];
$id_tipo=$_GET['id'];
$var=$tipos_empresas->getId($id_tipo);
$nombre=$tipos_empresas->nombre_tipo;
$descripcion=$tipos_empresas->descripcion_tipo;
if ($enviar ==1){
$nombre=$_POST['nombre'];
$descripcion=$_POST['descripcion'];
$error=$tipos_empresas->update($nombre,$descripcion,$id_tipo);

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Tipos&accion=listar";
-->
</script>
<?
}
}

?>