<?
$enviar=$_GET['flag'];
$codigo_bitacora=$_GET['id'];
$var=$discusion->obtenerDiscusionesPorId($codigo_bitacora);	
	$pdf_peticiones_sindicato	= 	$discusion->pdf_peticiones_sindicato;
	$pdf_ofertas_patrono	= 	$discusion->pdf_ofertas_patrono;

/*  
$dir='direccion del archivo'; //puedes usar dobles comillas si quieres 
if(file_exists($dir)) 
{ 
if(unlink($dir)) 
print "El archivo fue borrado"; 
} 
else 
print "Este archivo no existe"; 
 */
$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_peticiones_sindicato;
$archivo_eliminar="modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_ofertas_patrono;

	
if ($enviar =="SI"){


	$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_peticiones_sindicato;
	$archivo_eliminar="modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_peticiones_sindicato;

	if(file_exists($archivo))
		{
		chmod($archivo,0777);
		unlink($archivo);
		}

	$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_ofertas_patrono;
	$archivo_eliminar="modulos/contratos/discusion/documentos/".$codigo_bitacora."/".$pdf_ofertas_patrono;
	if(file_exists($archivo))
		{
		chmod($archivo_eliminar,0777);
		unlink($archivo_eliminar);
		}
	rmdir($_SERVER['DOCUMENT_ROOT']."/admin/modulos/contratos/discusion/documentos/".$codigo_bitacora);
									
$error=$discusion->eliminarDiscusion($codigo_bitacora);
$error_oferta=$discusion->eliminarDiscusion_ofertas($codigo_bitacora);
$error_oferta=$discusion->eliminarDiscusion_reuniones($codigo_bitacora);
$error_oferta=$discusion->eliminarDiscusion_acuerdos($codigo_bitacora);
$error_peticiones=$discusion->eliminarDiscusion_peticiones($codigo_bitacora);
$error_peticiones=$discusion->eliminarDiscusion_Historico($codigo_bitacora);


if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
//alert ("DISCUSION ELIMINADA");
	location.href="index.php?url=contratos&tbl=Bitacora";
-->
</script>

<?
}
}

?>

