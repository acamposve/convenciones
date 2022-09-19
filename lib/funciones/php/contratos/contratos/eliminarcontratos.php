<?
$enviar=$_GET['flag'];
$codigo_contrato=$_GET['id'];
$var=$contratos->obtenerContratosPorId($codigo_contrato);
$pdf=$contratos->pdf_auto;

if ($enviar =="SI"){


$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/contratos/documentos/".$codigo_contrato."/".$pdf;
$archivo_eliminar="modulos/contratos/contratos/documentos/".$codigo_contrato."/".$pdf;
$error=$contratos->borrarContrato($codigo_contrato);
$error_articulos=$contratos->borrarContrato_articulos($codigo_contrato);
$error_anexos=$contratos->borrarContrato_anexos($codigo_contrato);
if(file_exists($archivo)){
	
	chmod($archivo_eliminar,0777);
	unlink($archivo_eliminar);
	rmdir($_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/contratos/documentos/".$codigo_contrato);
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--

location.href="index.php?url=contratos&tbl=Contratos&accion=listar";
-->
</script>

<?
}
}

?>

