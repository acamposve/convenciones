<?
$enviar=$_GET['flag'];
$codigo_ley=$_GET['id'];
$var=$otras_leyes->obtenerLeysPorId($codigo_ley);
$pdf=$otras_leyes->pdf_ley;
if ($enviar =="SI"){
	$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/leyes/otras_varias/documentos/".$codigo_ley."/".$pdf;
	$archivo_eliminar="modulos/leyes/otras_varias/documentos/".$codigo_ley."/".$pdf;
	if(file_exists($archivo))
		{
		chmod($archivo_eliminar,0777);
		unlink($archivo_eliminar);
		rmdir($_SERVER['DOCUMENT_ROOT']."/admin/modulos/leyes/otras_varias/documentos/".$codigo_ley);
		}


 $flag_del=true 
 

?>


<?
if($flag_del){
$error=$otras_leyes->borrarLey($codigo_ley);
$error_articulos=$otras_leyes->borrarLey_articulos($codigo_ley);
}

if ($error){
?>
<script language="javascript" type="text/javascript">

location.href="index.php?url=leyes&tbl=Otras Varias&accion=listar";

</script>

<?
}
}

?>
