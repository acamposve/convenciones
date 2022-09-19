<?
$enviar=$_POST['enviar'];
$id_pantalla=$_GET['id'];
$var=$pantalla->getId($id_pantalla);
$nombre=$pantalla->nombre_pantalla;
$url_pantalla = $pantalla->url_pantalla;
$Id_Modulo =$pantalla->id_modulo;

if ($enviar ==1){
$nombre=$_POST['nombre'];
$url_pantalla=$_POST['url_pantalla'];
$modulo=$_POST['modulo'];
$id_pantalla=$_POST['id_modificar'];
$error=$pantalla->update($url_pantalla, $modulo, $nombre, $id_pantalla);

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="pp ACTUALIZADO";

		?>
		<script language="javascript" type="text/javascript">
		<!--
		window.location.href="?url=seguridad&tbl=Pantallas";
		-->
		</script>
		<?
//include('../lib/funciones/php/redirect.php');
//redirect($refer);
}
}

?>