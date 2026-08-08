<?php 
// EN EL SETTING DEL MODULO SE COLOCAN LAS FUNCIONALIDADES UTILIZANDO  2 VARIABLES.				#
// LA VARIABLE $tabla  ESPECIFICA LA TABLA DE SISTEMA O SUBMODULO A CARGAR Y LA VARIABLE		#
// $accion QUE ESPECIFICA LAS ACCIONES A EJECUTAR EN EL MODULO LAS CUALES PUEDER SER ADD,		#
// EDIT, VER,  ELI, LIST PARA CADA ACCION SE CARGA LA FUNCION CORRESPONDIENTE, QUE DEBE			#
// SER INSTALADA EN LIB/FUNCIONES/PHP/ELNOMBREDELMODULO/ELNOMBREDELSUBMODULO/  Y LOS OBJETOS	#
// QUE UTILIZA QUE DEBEN ESTAR UBICADOS EN LIB/OBJETOS/ 										#
 
include('../lib/objetos/pais.php');
include('../lib/objetos/estados.php');
include('../lib/objetos/localidad.php');
include('../lib/objetos/categoria.php');
include('../lib/objetos/categoria_titulos.php');
include('../lib/objetos/titulos.php');
include('../lib/objetos/sectores.php');
include('../lib/objetos/tipos_empresas.php');
include('../lib/objetos/categoria_sector.php');
include('../lib/objetos/actividad_empresa.php');
include('../lib/objetos/empresas.php');
include('../lib/objetos/pantalla.php');
include('../lib/objetos/seguridad.php');
 
if ($accion=="")
	{
		$accion="listar";
	}
if ($tabla=="")
	{
		$tabla="principal";
	}

switch ($tabla) 
	{
		
	    case"principal":
	  	$url='modulos/bienvenida/principal.php';
	  	break;
	  	case"default":
	  	$url='modulos/bienvenida/principal.php';
	  	break;
	}	
// SEGURIDAD
if($t_usuario!=1)
	{
		$var=$pantalla->getporUrl($url);
		$id_pantalla=$pantalla->id_pantalla; 
		$seg=$seguridad->getSeguridad($id_pantalla,$t_usuario);
	}
if(!$seg)
	{
		$tbl="error.php";
	}
// DATOS DEL MENU 
$cat="Bienvenido al Sistema CCCOL";
$var=$categorias->getporNombre($cat);
$id_categoria=$categorias->Id_categoria;
$desc=$categorias->desc;

if($dir=="")
	$tbl=$url;
else	
	$tbl=$dir."/".$url;
?>