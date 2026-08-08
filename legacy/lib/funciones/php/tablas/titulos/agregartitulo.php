<?
$enviar=$_POST['enviar'];

if ($enviar ==1)
	{
		$titulo=$_POST['titulo'];
		$descripcion=$_POST['descripcion'];
		$categoria=$_POST['categoria'];
		$error=$titulos->insert($titulo,$descripcion,$categoria);
		
		if ($error)
			{
			$msg="SE HA AGREGADO UN TITULO";
			?>
			<script type="text/javascript">
			window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
			</script>
    	    <?
		
			}
	}

?>