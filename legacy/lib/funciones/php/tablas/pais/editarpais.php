<?
$enviar=$_POST['enviar'];
$id_pais=$_GET['id'];
$var=$pais->getId($id_pais);
$nombre=$pais->nombre;
if ($enviar ==1){
$nombre=$_POST['nombrepais'];
$error=$pais->update($nombre, $id_pais );

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="PAIS ACTUALIZADO";
?>
<script type="text/javascript" language="javascript">
<!--
location.href="index.php?url=tablas&tbl=Paises&accion=listar";
-->
</script>
<?
}
}

?>