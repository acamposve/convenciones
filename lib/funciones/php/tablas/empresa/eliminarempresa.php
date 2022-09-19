<?
$enviar=$_GET['flag'];
$id_empresa=$_GET['id'];
$var=$empresas->getId($id_empresa);
$logo=$empresas->logo;

if ($enviar =="SI")
	{
	 $flag_del=true; 
?>
	}

<?
if($flag_del){


	$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/empresa/documentos/".$id_empresa."/".$logo;
	$archivo_eliminar="modulos/tablas/noticias/empresa/".$id_empresa."/".$logo;

	if(file_exists($archivo))
		{
		chmod($archivo,0777);
		unlink($archivo);
		print "uno-";
		}
	rmdir($_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/empresa/documentos/".$id_empresa);




		$error=$empresas->delete($id_empresa );
	}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Empresas&accion=listar";
-->
</script>

<?
}
}

?>
