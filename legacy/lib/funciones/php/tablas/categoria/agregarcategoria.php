<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$categoria=$_POST['categoria'];
$descripcion=$_POST['descripcion'];
$comprobacion=$_POST['comprobacion'];
$error=$categorias_titulos->insert($categoria,$descripcion,$comprobacion);

if ($error){
$msg="SE HA AGREGADO UNA CATEGORIA";
?>
		<script type="text/javascript">
		<!--
		window.location = "?url=tablas&tbl=Bloques%20de%20Clausulas"
		//-->
		</script>
        <?	
}
}

?>