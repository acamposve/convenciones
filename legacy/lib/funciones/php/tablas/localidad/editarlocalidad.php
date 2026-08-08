<?
$enviar=$_POST['Submit'];

if($_POST['id_pais']==""){
$id_estado=$_GET['id_estado'];
$id_pais=$_GET['id_pais'];
$id_localidad=$_GET['id'];
$var=$localidad->getId($id_localidad);
$nombrelocalidad=$localidad->nombre;
}else{
$id_pais=$_POST['id_pais'];
$id_localidad=$_GET['id'];
$var=$localidad->getId($id_localidad);
$nombrelocalidad=$localidad->nombre;
}

if ($enviar !=""){
$nombrelocalidad=$_POST['nombrelocalidad'];
$id_pais=$_POST['id_pais'];
$id_estado=$_POST['id_estado'];
$error=$localidad->update($nombrelocalidad, $id_estado, $id_pais, $id_localidad );

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="LOCALIDAD ACTUALIZADA";
?>
<script type="text/javascript" language="javascript">
<!--
location.href="index.php?url=tablas&tbl=Localidad&accion=listar";
-->
</script>
<?
}
}

?>