<?
$enviar=$_GET['flag'];
$codigo_bitacora=$_GET['id'];
$var=$discusion->obtenerDiscusionesPorId($codigo_bitacora);	
	$pdf_peticiones_sindicato	= 	$discusion->pdf_peticiones_sindicato;
	$pdf_ofertas_patrono	= 	$discusion->pdf_ofertas_patrono;
	
if ($enviar =="SI"){


$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_peticiones_sindicato;
$archivo_eliminar="modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_peticiones_sindicato;

if(file_exists($archivo)){
	
	chmod($archivo,0777);
	unlink($archivo);
}

$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_ofertas_patrono;
$archivo_eliminar="modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_ofertas_patrono;
if(file_exists($archivo)){
	chmod($archivo_eliminar,0777);
	unlink($archivo_eliminar);
}
rmdir($_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/discusion/documentos/".$codigo_bitacora);

$error=$discusion->eliminarDiscusion($codigo_bitacora);
if ($error){
?>
<script language="javascript" type="text/javascript">
<!--

	location.href="index.php?url=contratos&tbl=Discusion%20de%20contratos";
-->
</script>

<?
}
}

?>

