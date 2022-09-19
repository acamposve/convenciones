<?php 
// EN EL SETTING DEL MODULO SE COLOCAN LAS FUNCIONALIDADES UTILIZANDO  2 VARIABLES.				#
// LA VARIABLE $tabla  ESPECIFICA LA TABLA DE SISTEMA O SUBMODULO A CARGAR Y LA VARIABLE		#
// $accion QUE ESPECIFICA LAS ACCIONES A EJECUTAR EN EL MODULO LAS CUALES PUEDER SER ADD,		#
// EDIT, VER,  ELI, LIST PARA CADA ACCION SE CARGA LA FUNCION CORRESPONDIENTE, QUE DEBE			#
// SER INSTALADA EN LIB/FUNCIONES/PHP/ELNOMBREDELMODULO/ELNOMBREDELSUBMODULO/  Y LOS OBJETOS	#
// QUE UTILIZA QUE DEBEN ESTAR UBICADOS EN LIB/OBJETOS/ 										#
if ($accion=="")
	{
		$accion="listar";
	}
if ($tabla=="")
	{
		$tabla="principal";
	}
include('../lib/objetos/ley_trabajo.php');
include('../lib/objetos/otras_leyes.php');
include('../lib/objetos/titulos.php');
include('../lib/objetos/pantalla.php');
include('../lib/objetos/seguridad.php');
switch ($tabla)
	{
		case "LOT":
			$dir = 'ley_trabajo';
			$acciones['ver']="lys_ley_trabajo_ver.php";
			$acciones['editar']="lys_ley_trabajo_editar.php";
			$acciones['ver_articulo']="lys_articulo_ley_trabajo_ver.php";
			$acciones['agregar_articulo']="lys_articulo_ley_trabajo_agregar.php";
			$acciones['editar_articulo']="lys_articulo_ley_trabajo_editar.php";
			$acciones['eliminar_articulo']="lys_articulo_ley_trabajo_eliminar.php";
			switch ($accion)
				{
					case "agregar":
						include('../lib/funciones/php/leyes/ley_trabajo/agregar_articulo_ley_trabajo.php');
						$url= 'lys_articulo_ley_trabajo_agregar.php'; 
					break ;
					case "editar":
						include('../lib/funciones/php/leyes/ley_trabajo/editar_articulo_ley_trabajo.php');
						$url= 'lys_articulo_ley_trabajo_editar.php'; 
					break ;
					case "editar ley":
						include('../lib/funciones/php/leyes/ley_trabajo/editar_ley_trabajo.php');
						$url= 'lys_ley_trabajo_editar.php'; 
					break ;
					case "eliminar":
						include('../lib/funciones/php/leyes/ley_trabajo/eliminar_articulo_ley_trabajo.php');
						$url= 'lys_articulo_ley_trabajo_eliminar.php'; 
					break ;
					case "listar":
						include( '../lib/objetos/pager.php');
						$url= 'lys_articulos_ley_trabajo_listar.php'; 
					break ;
					case "ver":
						include( '../lib/objetos/pager.php');
						$url= 'lys_articulo_ley_trabajo_ver.php'; 
					break ;
					case "ver ley":
						include( '../lib/objetos/pager.php');
						$url= 'lys_ley_trabajo_ver.php'; 
					break ;
					case "imprimir":
						$url= 'lys_ley_trabajo_imprimir.php'; 
					break ;
					case "default":
						$url= 'lys_ley_trabajo_listar.php'; 
					break ;
				}
			break;
			case "Otras Varias":
				$dir = 'otras_varias';
				$acciones['ver']="lys_otras_leyes_ver.php";
				$acciones['editar']="lys_otras_leyes_editar.php";
				$acciones['agregar']="lys_otras_leyes_agregar.php";
				$acciones['eliminar']="lys_otras_leyes_eliminar.php";
				$acciones['ver_articulo']="lys_articulo_otras_leyes_ver.php";
				$acciones['agregar_articulo']="lys_articulo_otras_leyes_agregar.php";
				$acciones['editar_articulo']="lys_articulo_otras_leyes_editar.php";
				$acciones['eliminar_articulo']="lys_articulo_otras_leyes_eliminar.php";
				$acciones['listar_articulos']="lys_articulos_otras_leyes_listar.php";
				include('../lib/funciones/php/leyes/otras_varias/titulo_otras_leyes.php');
				switch ($accion)
					{
						case "agregar":
							$nivel=2;
							include('../lib/funciones/php/leyes/otras_varias/agregar_articulo_otras_leyes.php');
							$url= 'lys_articulo_otras_leyes_agregar.php'; 
						break ;
						case "agregar ley":
							include('../lib/funciones/php/leyes/otras_varias/agregar_otras_leyes.php');
							$url= 'lys_otras_leyes_agregar.php'; 
						break ;
						case "editar":
							$nivel=2;
							include('../lib/funciones/php/leyes/otras_varias/editar_articulo_otras_leyes.php');
							$url= 'lys_articulo_otras_leyes_editar.php'; 
						break ;
						case "editar ley":
							include('../lib/funciones/php/leyes/otras_varias/editar_otras_leyes.php');
							$url= 'lys_otras_leyes_editar.php'; 
						break ;
						case "eliminar ley":
							include('../lib/funciones/php/leyes/otras_varias/eliminar_otras_leyes.php');
							$url= 'lys_otras_leyes_eliminar.php'; 
						break ;
						case "eliminar":
							$nivel=2;
							include('../lib/funciones/php/leyes/otras_varias/eliminar_articulo_otras_leyes.php');
							$url= 'lys_articulo_otras_leyes_eliminar.php'; 
						break ;
						case "listar articulos":
							$nivel=2;
							include( '../lib/objetos/pager.php');
							$url= 'lys_articulos_otras_leyes_listar.php'; 
						break ;
						case "listar":
							include( '../lib/objetos/pager.php');
							$url= 'lys_otras_leyes_listar.php'; 
						break ;
						case "ver":
							$nivel=2;
							include( '../lib/objetos/pager.php');
							$url= 'lys_articulo_otras_leyes_ver.php'; 
						break ;
						case "ver ley":
							include( '../lib/objetos/pager.php');
							$url= 'lys_otras_leyes_ver.php'; 
						break ;
						case "imprimir":
							$nivel=2;
							$url= 'lys_otras_leyes_imprimir.php'; 
						break ;
						case "default":
							$url= 'lys_leyes_listar.php'; 
						break ;
					}
				break;
			case"principal":
				$url='principal.php';
			break;
			case"default":
				$url='principal.php';
			break;
	}
if ($dir!="")
	{
		$tbl=$dir."/".$url;
	}
else
	{
		$tbl=$url;
	}
// SEGURIDAD
if($t_usuario!=1)
	{
		$var=$pantalla->getporUrl($url);
		$id_pantalla=$pantalla->id_pantalla; 
		$seg=$seguridad->getSeguridad($id_pantalla,$t_usuario );
		if(!$seg)
			{
				$tbl="error.php";
			}
	}

 
 
// DATOS DEL MENU 
$cat="Leyes Marco";
$var=$categorias->getporNombre($cat);
$id_categoria=$categorias->Id_categoria;
$desc=$categorias->desc;
?>