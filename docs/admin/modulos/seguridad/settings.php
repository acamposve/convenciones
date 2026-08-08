<?php 
// EN EL SETTING DEL MODULO SE COLOCAN LAS FUNCIONALIDADES UTILIZANDO  2 VARIABLES.				#
// LA VARIABLE $tabla  ESPECIFICA LA TABLA DE SISTEMA O SUBMODULO A CARGAR Y LA VARIABLE		#
// $accion QUE ESPECIFICA LAS ACCIONES A EJECUTAR EN EL MODULO LAS CUALES PUEDER SER ADD,		#
// EDIT, VER,  ELI, LIST PARA CADA ACCION SE CARGA LA FUNCION CORRESPONDIENTE, QUE DEBE			#
// SER INSTALADA EN LIB/FUNCIONES/PHP/ELNOMBREDELMODULO/ELNOMBREDELSUBMODULO/  Y LOS OBJETOS	#
// QUE UTILIZA QUE DEBEN ESTAR UBICADOS EN LIB/OBJETOS/ 										#
 
if ($accion==""){
	$accion="listar";
}
if ($tabla==""){
	$tabla="principal";
} 
	  	include( '../lib/objetos/user.php');
		include( '../lib/objetos/tipo_usuario.php'); 
		include('../lib/objetos/pantalla.php');
		include('../lib/objetos/seguridad.php');
		include( '../lib/objetos/modulo.php');
		include('../lib/objetos/empresas.php');

				
switch ($tabla) {
	 
	  case "Tipos de Usuarios":
	  	$acciones['ver']="tbl_tipo_ver.php";
		$acciones['editar']="tbl_tipo_edit.php";
		$acciones['eliminar']="tbl_tipo_eli.php";
		$acciones['agregar']="tbl_tipo_add.php";
		$dir = 'tipos';
	 	switch ($accion) {
		case "agregar":
			include('../lib/funciones/php/seguridad/tipos/agregartipo.php');
			$url= 'tbl_tipo_add.php'; 
	 	break ;
		case "editar":
			include('../lib/funciones/php/seguridad/tipos/editartipo.php');
			$url= 'tbl_tipo_edit.php'; 
	 	break ;
		case "eliminar":
			include('../lib/funciones/php/seguridad/tipos/eliminartipo.php');
			$url= 'tbl_tipo_eli.php'; 
	 	break ;
		case "listar":
			include( '../lib/objetos/pager.php');
			$url= 'tbl_tipo_list.php'; 
	 	break ;
		case "ver":
			include( '../lib/objetos/pager.php');
			$url= 'tbl_tipo_ver.php'; 
	 	break ;
			case "default":
			$url= 'tbl_tipo_list.php'; 
	 	break ;
		}
	  break;
	  
	  case "Usuarios":
	  	$acciones['ver']="tbl_usuario_ver.php";
		$acciones['editar']="tbl_usuario_edit.php";
		$acciones['eliminar']="tbl_usuario_eli.php";
		$acciones['agregar']="tbl_usuario_add.php";
		
	  	$dir = 'usuarios';
	 	switch ($accion) {
		case "agregar":
			include('../lib/funciones/php/seguridad/usuarios/agregarusuario.php');
			$url= 'tbl_usuario_add.php'; 
	 	break ;
		case "editar":
			include('../lib/funciones/php/seguridad/usuarios/editarusuario.php');
			$url= 'tbl_usuario_edit.php'; 
	 	break ;
		case "eliminar":
			include('../lib/funciones/php/seguridad/usuarios/eliminarusuario.php');
			$url= 'tbl_usuario_eli.php'; 
	 	break ;
		case "listar":
			include( '../lib/objetos/pager.php');
			$url= 'tbl_usuario_list.php'; 
	 	break ;
		case "ver":
			include( '../lib/objetos/pager.php');
			$url= 'tbl_usuario_ver.php'; 
	 	break ;
		case "default":
			$url= 'tbl_usuario_list.php'; 
	 	break ;
		}
	  break;
	  
	  case "Pantallas":
	  	$acciones['ver']="tbl_pantalla_ver.php";
		$acciones['editar']="tbl_pantalla_edit.php";
		$acciones['eliminar']="tbl_pantalla_eli.php";
		$acciones['agregar']="tbl_pantalla_add.php";
		$dir = 'pantallas';
	 	switch ($accion) {
		case "agregar":
			include('../lib/funciones/php/seguridad/pantallas/agregarpantalla.php');
			$url= 'tbl_pantalla_add.php'; 
	 	break ;
		case "editar":
			include('../lib/funciones/php/seguridad/pantallas/editarpantalla.php');
			$url= 'tbl_pantalla_edit.php'; 
	 	break ;
		case "eliminar":
			include('../lib/funciones/php/seguridad/pantallas/eliminarpantalla.php');
			$url= 'tbl_pantalla_eli.php'; 
	 	break ;
		case "listar":
			include( '../lib/objetos/pager.php');
			$url= 'tbl_pantalla_list.php'; 
	 	break ;
		case "ver":
			include( '../lib/objetos/pager.php');
			$url= 'tbl_pantalla_ver.php'; 
	 	break ;
			case "default":
			$url= 'tbl_pantalla_list.php'; 
	 	break ;
		}
	  break;
	  
	  case "Seguridad Pantallas":
	  	include( '../lib/objetos/user.php');
		include( '../lib/objetos/tipo_usuario.php');
		include( '../lib/objetos/pantalla.php');
		include( '../lib/objetos/seguridad.php');
		
		$acciones['ver']="tbl_seguridad_ver.php";
		$acciones['editar']="tbl_seguridad_edit.php";
		$acciones['eliminar']="tbl_seguridad_eli.php";
		$acciones['agregar']="tbl_seguridad_add.php";

	  	$dir = 'seguridad';
	 	switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/seguridad/seguridad/agregarseguridad.php');
		$url= 'tbl_seguridad_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/seguridad/seguridad/editarseguridad.php');
		$url= 'tbl_seguridad_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/seguridad/seguridad/eliminarseguridad.php');
		$url= 'tbl_seguridad_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_seguridad_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_seguridad_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_seguridad_list.php'; 
	 	break ;
		}
	  break;
	  
	  case"principal":
	  	$url='modulos/seguridad/principal.php';
	  break;
	  case"default":
	  	$url='modulos/seguridad/principal.php';
	  break;
	   }
// SEGURIDAD

if($dir=="")
$tbl=$url;
else	
$tbl=$dir."/".$url;	


if($t_usuario!=1){
$var=$pantalla->getporUrl($url);
$id_pantalla=$pantalla->id_pantalla; 
$seg=$seguridad->getSeguridad($id_pantalla,$t_usuario);

if(!$seg){
$tbl="error.php";
}
}



// DATOS DEL MENU 
$cat="Seguridad del Sistema";
$var=$categorias->getporNombre($cat);
$id_categoria=$categorias->Id_categoria;
$desc=$categorias->desc;


?>