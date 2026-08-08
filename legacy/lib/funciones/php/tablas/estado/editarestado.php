<?
$enviar=$_POST['enviar'];
$id_estado=$_GET['id'];
$var=$estado->getId($id_estado);
$nombreestado=$estado->nombre;
$id_pais=$_GET['id_pais'];
if ($enviar ==1){
$nombreestado=$_POST['nombreestado'];
$id_pais=$_POST['id_pais'];
$error=$estado->update($nombreestado, $id_estado, $id_pais );

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="ESTADO ACTUALIZADO";
?>
<script type="text/javascript" language="javascript">
<!--
location.href="index.php?url=tablas&tbl=Estados&accion=listar";
-->
</script>
<?
}
}

?>