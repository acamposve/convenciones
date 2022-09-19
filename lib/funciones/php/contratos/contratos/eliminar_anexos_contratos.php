<?
$enviar=$_GET['flag'];
$codigo_anexo=$_GET['id'];
$var=$contratos->obtenerAnexoContratosPorId($codigo_anexo);
$codigo_contrato=$contratos->codigo_contrato;
$pdf_anexo=$contratos->pdf_anexo;
if ($enviar =="SI"){


$archivo=$_SERVER['DOCUMENT_ROOT']."/cccol/admin/modulos/contratos/contratos/documentos/".$codigo_contrato."/anexos/".$pdf_anexo;
$archivo_eliminar="modulos/contratos/contratos/documentos/". $codigo_contrato."/anexos/".$pdf_anexo;
$error=$contratos->borrarAnexoContrato($codigo_anexo);
if(file_exists($archivo)){
	
	chmod($archivo_eliminar,0777);
	unlink($archivo_eliminar);
	//rmdir($_SERVER['DOCUMENT_ROOT']."/cccol/admin/modulos/contratos/contratos/documentos/".$codigo_contrato);
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--

location.href="index.php?url=contratos&tbl=Contratos&accion=listar_anexos&id=<? print $codigo_contrato?>";
-->
</script>

<?
}
}
?>

