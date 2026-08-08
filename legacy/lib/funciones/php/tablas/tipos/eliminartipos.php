<?
$enviar=$_GET['flag'];
$id_tipo=$_GET['id'];
$var=$tipos_empresas->getId($id_tipo);
$nombre=$tipos_empresas->nombre_tipo;
$descripcion=$tipos_empresas->descripcion_tipo;
if ($enviar =="SI")
	{
	 $flag_del=true; 
	
?>

<?
if($flag_del){
$error=$tipos_empresas->delete($id_tipo);
}

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

