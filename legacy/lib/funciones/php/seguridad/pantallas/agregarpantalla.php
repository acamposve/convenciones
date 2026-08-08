<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$nombre=$_POST['nombre'];
$url=$_POST['url'];
$modulo=$_POST['modulo'];
$error=$pantalla->insert($url, $nombre, $modulo);

if ($error){
$msg="SE HA AGREGADO UNA PANTALLA";

			?>
			<script type="text/javascript">
			window.location = "?url=seguridad&tbl=<?php echo $tabla; ?>"
			</script>
			<?	


}
}

?>