<?
$enviar=$_GET['flag'];
$id_noticia=$_GET['id'];
if ($enviar =="SI"){
	$error=$Noticias->eliminarCategoriasNoticias($id_noticia );
	if ($error){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=tablas&tbl=Categorias de Noticias&accion=listar";
		-->
		</script>
		
		<?
		}
}

?>

