<?
$enviar=$_GET['flag'];
$id_tipo=$_GET['id'];
$var=$tipo_usuario->getId($id_tipo);
$nombre=$tipo_usuario->nombre;
if ($enviar =="SI"){

?>
<script language="javascript" type="text/javascript">
<!--
if ( confirm("¿Esta seguro que desea eliminar este pais?\n su eliminacion implica la eliminacion de todos los \n usuarios que  tengan referencia a el.") ){
<? $flag_del=true; ?>
}
-->
</script>

<?
if($flag_del){
$error=$tipo_usuario->delete($id_tipo );
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=seguridad&tbl=Tipos%20de%20Usuarios&accion=listar";
-->
</script>

<?
}
}

?>

