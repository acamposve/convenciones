<?
$enviar=$_GET['flag'];
$id_localidad=$_GET['id'];
$id_estado=$_GET['id_estado'];
$id_pais=$_GET['id_pais'];
$var=$localidad->getId($id_localidad);
$nombrelocalidad=$localidad->nombre;
$var=$estado->getId($id_estado);
$nombreestado=$estado->nombre;
$var=$pais->getId($id_pais);
$nombrepais=$pais->nombre;

if ($enviar =="SI"){
$error=$localidad->delete($id_localidad );

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--

location.href="?url=tablas&tbl=Localidad&accion=listar";
-->
</script>

<?
}
}

?>

