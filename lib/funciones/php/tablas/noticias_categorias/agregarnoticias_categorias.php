<?
	
	$enviar=$_POST['enviar'];

	if ($enviar ==1){
		$nombre_categoria	=$_POST['nombre_categoria'];
		$descrip_categoria	=$_POST['descrip_categoria'];
		print $nombre_categoria	."<br>".$descrip_categoria;

 		$error=$Noticias->insertarCategoriasNoticias( $nombre_categoria,$descrip_categoria);
			if ($error){

				?>
					<script type="text/javascript" language="javascript">
					<!--
					location.href="index.php?url=tablas&tbl=Categorias%20de%20Noticias";
					-->
					</script>
					<?
				} 
		}

?>