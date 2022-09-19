<?php
session_start();
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
//ini_set("display_errors", 1);
include_once("../lib/objetos/seguridad.php");
include_once("../lib/objetos/pantalla.php");
if (isset($_GET['url'])){
	$url = $_GET['url'];
}




if (isset($_SESSION['nombre'])){
	$nombreses = $_SESSION['nombre'];
} else {
$nombreses="";
}

$nombreses="Alex";
if($nombreses!=""){
		include('modulos/settings.php');
} else {
		include('../lib/objetos/user.php');
		include('../lib/funciones/php/login/login.php');
		$url='../admin/modulos/login/index.php';
		$modulo='login';
		$titulo="Acceso al Sistema";
}
include_once($_SERVER['DOCUMENT_ROOT']."/convenciones/lib/objetos/user.php");
$t_usuario=1;
include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');

include('../plantillas/plantilla_admin/cabecera.php');
include($url);
include('../plantillas/plantilla_admin/pie.php');

?>
