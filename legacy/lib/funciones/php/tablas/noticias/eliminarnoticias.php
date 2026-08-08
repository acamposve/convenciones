<?
$enviar=$_GET['flag'];
$id_noticia=$_GET['id'];
		$var=$Noticias->getId( $id_noticia );
	    while( list( $key, $val) = each($var) ) 
			{
		    	$codigo_noticia 	= $var[$key][codigo_noticia];
				$nombre_categoria 	= $var[$key][nombre_categoria];
				$titulo 			= $var[$key][titulo];
				$fecha_publicacion 	= $var[$key][fecha_publicacion];
				$estatus_noticia	= $var[$key][estatus_noticia];
				$codigo_categoria 	= $var[$key][codigo_categoria];
				$nombre_categoria	= $var[$key][nombre_categoria];
				$noticia_completa 	= $var[$key][noticia_completa];
				$resumen_noticia	= $var[$key][resumen_noticia];
				$img_1				= $var[$key][imagen_1];
				$img_2				= $var[$key][imagen_2];
			}
if ($enviar =="SI"){
$error=$Noticias->eliminarNoticias($id_noticia );
	
	$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/noticias/documentos/".$id_noticia."/".$img_1;
	$archivo_eliminar="modulos/tablas/noticias/documentos/".$id_noticia."/".$img_1;

	if(file_exists($archivo))
		{
		chmod($archivo,0777);
		unlink($archivo);
		print "uno-";
		}

	$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/noticias/documentos/".$id_noticia."/".$img_2;
	$archivo_eliminar="modulos/tablas/noticias/documentos/".$id_noticia."/".$img_2;
	if(file_exists($archivo))
		{
		chmod($archivo_eliminar,0777);
		unlink($archivo_eliminar);
		print "dos";
		}
	rmdir($_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/noticias/documentos/".$id_noticia);
 
	
	if ($error){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=tablas&tbl=Noticias&accion=listar";
		-->
		</script>
		<?
		}
}

?>

