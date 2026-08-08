<?
$enviar=$_GET['flag'];
$codigo_reunion=$_GET['id'];
$codigo_bitacora = $_GET['id_bitacora'];

if ($enviar =="SI"){
$error=$discusion->eliminarReunion($codigo_reunion);
if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
	location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_reuniones&id=<? print $codigo_bitacora?>";
-->
</script>

<?
}
}

?>

