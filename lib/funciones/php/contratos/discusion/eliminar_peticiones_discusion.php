<?
$enviar=$_GET['flag'];
$codigo_peticion=$_GET['id'];
$codigo_bitacora = $_GET['id_bitacora'];
	
if ($enviar =="SI"){
$error=$discusion->eliminarPeticionDiscusion($codigo_peticion);
if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
	location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_peticiones&id=<? print $codigo_bitacora?>";
-->
</script>

<?
}
}

?>

