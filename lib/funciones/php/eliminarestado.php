<?
$enviar=$_GET['flag'];
$id_estado=$_GET['id'];
$id_pais=$_GET['id_pais'];
$var=$estado->getId($id_estado);
$nombreestado=$estado->nombre;
$var=$pais->getId($id_pais);
$nombrepais=$pais->nombre;
if ($enviar =="SI"){
?>
<script language="javascript" type="text/javascript">
<!--
if ( confirm("¿Esta seguro que desea eliminar este Estado?\n su eliminacion implica la eliminacion de todos los \n estados y localidades que  tengan referencia a el.") ){
<? $flag_del=true; ?>
}
-->
</script>

<?
if($flag_del){
$error=$estado->delete($id_estado );
$error=$localidad->deleteporEstado($id_estado );
}
if ($error){
?>
<script language="javascript" type="text/javascript">
<!--

location.href="?url=tablas&tbl=estados&accion=list";
-->
</script>

<?
}
}

?>

