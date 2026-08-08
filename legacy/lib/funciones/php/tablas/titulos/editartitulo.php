<?
$enviar=$_POST['enviar'];
$id_titulo=$_GET['id'];
$var=$titulos->getId($id_titulo);
$descripcion_titulo=$titulos->descripcion_titulo;
$categoria_titulo=$titulos->codigo_categoria_titulo;
$nombre_titulo=$titulos->nombre_titulo;
if ($enviar ==1){
$titulo=$_POST['titulo'];
$descripcion=$_POST['descripcion'];
$categoria=$_POST['categoria'];
$error=$titulos->update($titulo,$descripcion,$categoria,$id_titulo);

if ($error){
$refer="?url=tablas&tbl=Titulos Comparativos";
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