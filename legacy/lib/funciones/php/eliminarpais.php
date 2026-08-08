<?
$enviar=$_GET['flag'];
$id_pais=$_GET['id'];
$var=$pais->getId($id_pais);
$nombre=$pais->nombre;
if ($enviar =="SI"){

?>
<script language="javascript" type="text/javascript">
<!--
if ( confirm("¿Esta seguro que desea eliminar este pais?\n su eliminacion implica la eliminacion de todos los \n estados y localidades que  tengan referencia a el.") ){
<? $flag_del=true; ?>
}
-->
</script>

<?
if($flag_del){
$error=$pais->delete($id_pais );
$error=$estado->deleteporPais($id_pais);
$error=$localidad->deleteporPais($id_pais);
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=paises&accion=list";
-->
</script>

<?
}
}

?>

