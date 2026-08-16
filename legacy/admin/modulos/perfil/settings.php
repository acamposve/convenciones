<?php 
// EN EL SETTING DEL MODULO SE COLOCAN LAS FUNCIONALIDADES UTILIZANDO  2 VARIABLES.				#
// LA VARIABLE $tabla  ESPECIFICA LA TABLA DE SISTEMA O SUBMODULO A CARGAR Y LA VARIABLE		#
// $accion QUE ESPECIFICA LAS ACCIONES A EJECUTAR EN EL MODULO LAS CUALES PUEDER SER ADD,		#
// EDIT, VER,  ELI, LIST PARA CADA ACCION SE CARGA LA FUNCION CORRESPONDIENTE, QUE DEBE			#
// SER INSTALADA EN LIB/FUNCIONES/PHP/ELNOMBREDELMODULO/ELNOMBREDELSUBMODULO/  Y LOS OBJETOS	#
// QUE UTILIZA QUE DEBEN ESTAR UBICADOS EN LIB/OBJETOS/ 										#
if ($accion==""){
	$accion="editar";
}
if ($tabla==""){
	$tabla="principal";
}

include('../lib/objetos/user.php');
include('../lib/objetos/empresas.php');

switch ($tabla) {
	 case "Editar Perfil":
	    $dir = 'perfil';
	 	switch ($accion) {
		case "editar":
		include('../lib/funciones/php/perfil/perfil/editarperfil.php');
		$url= 'perfil_perfil_editar.php'; 
	 	break ;
		case "default":
		$url= 'perfil_perfil_editar.php'; 
	 	break ;
		}
		$tbl=$dir."/".$url;
	  break;
	  
	  case "Cambiar Password":
	    $dir = 'password';
	 	switch ($accion) {
		case "editar":
		include('../lib/funciones/php/perfil/password/editarpassword.php');
		$url= 'perfil_password_editar.php'; 
	 	break ;
		case "default":
		$url= 'perfil_password_editar.php'; 
	 	break ;
		}
		$tbl=$dir."/".$url;
	  break;
	  
	  case"principal":
	  	$tbl='principal.php';
	  break;
	  case"default":
	  	$tbl='principal.php';
	  break;
	   }


// SEGURIDAD

	

if($_SESSION['tipo']!=1){
$var=$pantalla->getporUrl($url);
$id_pantalla=$pantalla->id_pantalla; 
$seg=$seguridad->getSeguridad($id_pantalla,$t_usuario );

if(!$seg){
$tbl="error.php";
}
}

// DATOS DEL MENU 
$cat="Perfil de Usuarios";
$var=$categorias->getporNombre($cat);
$id_categoria=$categorias->Id_categoria;
$desc=$categorias->desc;

?>