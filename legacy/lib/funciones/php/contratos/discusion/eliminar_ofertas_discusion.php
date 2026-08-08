<?
$enviar=$_GET['flag'];
$codigo_oferta=$_GET['id'];
$codigo_bitacora = $_GET['id_bitacora'];
if ($enviar =="SI"){
$error=$discusion->eliminarOfertaDiscusion($codigo_oferta);
if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
	location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_ofertas&id=<? print $codigo_bitacora?>";
-->
</script>

<?
}
}

?>

