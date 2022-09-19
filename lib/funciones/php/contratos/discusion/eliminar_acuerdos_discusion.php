<?
$enviar=$_GET['flag'];
$codigo_acuerdo=$_GET['id'];
$codigo_bitacora = $_GET['id_bitacora'];

if ($enviar =="SI"){
$error=$discusion->eliminarAcuerdo($codigo_acuerdo);
if ($error){
?>
<script language="javascript" type="text/javascript">
<!--

	location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_acuerdos&id=<? print $codigo_bitacora?>";
-->
</script>

<?
}
}

?>

