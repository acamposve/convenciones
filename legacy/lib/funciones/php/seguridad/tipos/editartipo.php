<?
$enviar=$_POST['enviar'];
$id_tipo=$_GET['id'];
$var=$tipo_usuario->getId($id_tipo);
$nombre=$tipo_usuario->nombre;
if ($enviar ==1){
$nombre=$_POST['nombretipo'];
$error=$tipo_usuario->update($nombre, $id_tipo );

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="TIPO DE USUARIO ACTUALIZADO";

		?>
		<script language="javascript" type="text/javascript">
		<!--
		window.location.href="?url=seguridad&tbl=Tipos%20de%20Usuarios";
		-->
		</script>
		<?

//include('../lib/funciones/php/redirect.php');
//redirect($refer);
}
}

?>