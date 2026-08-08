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

include('../lib/objetos/user.php');
include('../lib/objetos/pager.php');
include('../lib/objetos/empresas.php');
include('../lib/objetos/contratos.php');
include('../lib/objetos/titulos.php');
include('../lib/objetos/discusion.php');
include('../lib/objetos/user.php');

switch ($tabla) {
	 case "Contratos":
	    $dir = 'contratos';
	 	switch ($accion) {
		case "editar":
		include('../lib/funciones/php/contratos/contratos/editarcontratos.php');
		$url= 'contratos_contratos_editar.php';
	 	break ;
		case "agregar":
		include('../lib/funciones/php/contratos/contratos/agregarcontratos.php');
		$url= 'contratos_contratos_agregar.php';
		break ;
		case "agregar_articulos":
		$nivel=2;
		include('../lib/funciones/php/contratos/contratos/agregar_articulo_contratos.php');
		$url= 'contratos_articulo_contratos_agregar.php';
	 	break ;
		case "editar_articulos":
		$nivel=2;
		include('../lib/funciones/php/contratos/contratos/editar_articulo_contratos.php');
		$url= 'contratos_articulo_contratos_editar.php';
			$accion="editar_clausula";
	 	break ;
		case "impresion_articulos":
		$nivel=2;
		include('../lib/funciones/php/contratos/contratos/impresion_articulo_contratos.php');
		$url= 'contratos_articulo_contratos_editar_impresion.php';
			$accion="impresion_clausula";
	 	break ;
		case "impresion_articulos_total":
		$nivel=2;
		include('../lib/funciones/php/contratos/contratos/impresion_articulo_total_contratos.php');
		$url= 'contratos_articulo_contratos_impresion_clausulas.php';
			$accion="impresion_clausula_total";
	 	break ;
		case "listar_articulos":
		$nivel=2;
		$url= 'contratos_articulo_contratos_listar.php';
			$accion="listar_clausulas";
	 	break ;
		case "publicar_contrato":
		$nivel=2;
		$url= 'contratos_publicar.php';
		include('../lib/funciones/php/contratos/contratos/publicar.php');
	 	break ;
		case "eliminar_articulos":
		$nivel=2;
		include('../lib/funciones/php/contratos/contratos/eliminar_articulo_contratos.php');
		$url= 'contratos_articulo_contratos_eliminar.php';
			$accion="eliminar_clausula";
	 	break ;
		case "agregar_anexos":
		$nivel=3;
		include('../lib/funciones/php/contratos/contratos/agregar_anexos_contratos.php');
		$url= 'contratos_anexos_contratos_agregar.php';
	 	break ;
		case "editar_anexos":
		$nivel=3;
		include('../lib/funciones/php/contratos/contratos/editar_anexos_contratos.php');
		$url= 'contratos_anexos_contratos_editar.php';
	 	break ;
		case "listar_anexos":
		$nivel=3;
		$url= 'contratos_anexos_contratos_listar.php';
	 	break ;
		case "eliminar_anexos":
		$nivel=3;
		include('../lib/funciones/php/contratos/contratos/eliminar_anexos_contratos.php');
		$url= 'contratos_anexos_contratos_eliminar.php';
	 	break ;
		case "ver_anexos":
		$nivel=3;
		$url= 'contratos_anexos_contratos_ver.php';
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/contratos/contratos/eliminarcontratos.php');
		$url= 'contratos_contratos_eliminar.php';
	 	break ;
		case "listar":
		$url= 'contratos_contratos_listar.php';
	 	break ;
		case "ver":
		$url= 'contratos_contratos_ver.php';
	 	break ;
		case "ver_articulo":
		$url= 'contratos_articulo_contratos_ver.php';
			$accion="ver_clausula";
	 	break ;
		case "default":
		$url= 'contratos_contratos_listar.php';
	 	break ;
		}
		$tbl=$dir."/".$url;
	  break;

	  case "Comparacion de Contratos":
	    $dir = 'comparacion';
	 	switch ($accion) {
		case "listar":
		$url= 'comparacion_contratos_listar.php';
	 	break ;
		case "default":
		$url= 'comparacion_contratos_listar.php';
	 	break ;
		}
		$tbl=$dir."/".$url;
	  break;

	  case "Bitacora":
	    $dir = 'discusion';
	 	switch ($accion) {
		case "listar":
		$url= 'discusion_contratos_listar.php';
	 	break ;
		case "agregar":
		include('../lib/funciones/php/contratos/discusion/agregar_discusion.php');
		$url= 'discusion_contratos_agregar.php';
	 	break ;
		case "agregar_peticiones":
		include('../lib/funciones/php/contratos/discusion/agregar_peticiones_discusion.php');
		$url= 'discusion_contratos_peticiones_agregar.php';
	 	break ;
		case "agregar_ofertas":
		include('../lib/funciones/php/contratos/discusion/agregar_ofertas_discusion.php');
		$url= 'discusion_contratos_ofertas_agregar.php';
	 	break ;
		case "agregar_reuniones":
		include('../lib/funciones/php/contratos/discusion/agregar_reuniones_discusion.php');
		$url= 'discusion_contratos_reuniones_agregar.php';
		break;
		case "agregar_acuerdos":
		include('../lib/funciones/php/contratos/discusion/agregar_acuerdos_discusion.php');
		$url= 'discusion_contratos_acuerdos_agregar.php';
		break;


		case "listar_peticiones":
		$url= 'discusion_contratos_listar_peticiones.php';
	 	break ;
		case "listar_ofertas":
		$url= 'discusion_contratos_listar_ofertas.php';
	 	break ;
		case "listar_reuniones":
		$url= 'discusion_contratos_listar_reuniones.php';
	 	break ;
		case "listar_acuerdos":
		$url= 'discusion_contratos_listar_acuerdos.php';
	 	break ;
		case "impresion_gen":
		$nivel=2;
		include('../lib/funciones/php/contratos/discusion/impresion_gen.php');
		$url= 'discucion_impresion_gen.php';
	 	break ;


		case "editar":
		include('../lib/funciones/php/contratos/discusion/editar_discusion.php');
		$url= 'discusion_contratos_editar.php';
	 	break ;
		case "cerrar":
		include('../lib/funciones/php/contratos/discusion/cerrar_discusion.php');
		$url= 'discusion_contratos_cerrar.php';
	 	break ;
		case "reabrir":
		include('../lib/funciones/php/contratos/discusion/reabrir_discusion.php');
		$url= 'discusion_contratos_reabrir.php';
	 	break ;
		case "reabrir_peticiones":
		include('../lib/funciones/php/contratos/discusion/reabrir_peticiones.php');
		$url= 'discusion_contratos_reabrir_peticiones.php';
	 	break ;
		case "editar_ofertas":
		include('../lib/funciones/php/contratos/discusion/editar_ofertas_discusion.php');
		$url= 'discusion_contratos_editar_ofertas.php';
	 	break ;
		case "editar_peticiones":
		include('../lib/funciones/php/contratos/discusion/editar_peticiones_discusion.php');
		$url= 'discusion_contratos_editar_peticiones.php';
	 	break ;
		case "editar_reuniones":
		include('../lib/funciones/php/contratos/discusion/editar_reuniones_discusion.php');
		$url= 'discusion_contratos_editar_reuniones.php';
	 	break ;
		case "editar_acuerdos":
		include('../lib/funciones/php/contratos/discusion/editar_acuerdos_discusion.php');
		$url= 'discusion_contratos_editar_acuerdos.php';
	 	break ;

		case "ver":
		$url= 'discusion_contratos_ver.php';
	 	break ;
		case "ver_ofertas":
		$url= 'discusion_contratos_ver_ofertas.php';
	 	break ;
		case "ver_peticiones":
		$url= 'discusion_peticiones_contratos_ver.php';
	 	break ;
		case "ver_reuniones":
		$url= 'discusion_contratos_ver_reuniones.php';
	 	break ;

		case "ver_acuerdos":
		$url= 'discusion_contratos_ver_acuerdos.php';
	 	break ;

		case "eliminar":
		include('../lib/funciones/php/contratos/discusion/eliminar_discusion.php');
		$url= 'discusion_contratos_eliminar.php';
	 	break ;
		case "eliminar_ofertas":
		include('../lib/funciones/php/contratos/discusion/eliminar_ofertas_discusion.php');
		$url= 'discusion_contratos_eliminar_ofertas.php';
	 	break ;
		case "eliminar_peticiones":
		include('../lib/funciones/php/contratos/discusion/eliminar_peticiones_discusion.php');
		$url= 'discusion_contratos_eliminar_peticiones.php';
	 	break ;

		case "eliminar_reuniones":
		include('../lib/funciones/php/contratos/discusion/eliminar_reuniones_discusion.php');
		$url= 'discusion_contratos_eliminar_reuniones.php';
	 	break ;
		case "eliminar_acuerdos":
		include('../lib/funciones/php/contratos/discusion/eliminar_acuerdos_discusion.php');
		$url= 'discusion_contratos_eliminar_acuerdos.php';
	 	break ;
		case "default":
		$url= 'discusion_contratos_listar.php';
	 	break ;
		}
/* 	  case "Discusion de contratos":
	    $dir = 'discusion';
	 	switch ($accion) {

		case "listar":
		$url= 'discusion_contratos_listar.php';
	 	break ;
		case "agregar":
		include('../lib/funciones/php/contratos/discusion/agregar_discusion.php');
		$url= 'discusion_contratos_agregar.php';
	 	break ;
		case "agregar_peticiones":
		include('../lib/funciones/php/contratos/discusion/agregar_peticiones_discusion.php');
		$url= 'discusion_contratos_peticiones_agregar.php';
	 	break ;
		case "agregar_ofertas":
		include('../lib/funciones/php/contratos/discusion/agregar_ofertas_discusion.php');
		$url= 'discusion_contratos_ofertas_agregar.php';
	 	break ;
		case "agregar_reuniones":
		include('../lib/funciones/php/contratos/discusion/agregar_reuniones_discusion.php');
		$url= 'discusion_contratos_reuniones_agregar.php';
		break;
		case "agregar_acuerdos":
		include('../lib/funciones/php/contratos/discusion/agregar_acuerdos_discusion.php');
		$url= 'discusion_contratos_acuerdos_agregar.php';
		break;


		case "listar_peticiones":
		$url= 'discusion_contratos_listar_peticiones.php';
	 	break ;
		case "listar_ofertas":
		$url= 'discusion_contratos_listar_ofertas.php';
	 	break ;
		case "listar_reuniones":
		$url= 'discusion_contratos_listar_reuniones.php';
	 	break ;

		case "listar_acuerdos":
		$url= 'discusion_contratos_listar_acuerdos.php';
	 	break ;

		case "editar":
		include('../lib/funciones/php/contratos/discusion/editar_discusion.php');
		$url= 'discusion_contratos_editar.php';
	 	break ;
		case "cerrar":
		include('../lib/funciones/php/contratos/discusion/cerrar_discusion.php');
		$url= 'discusion_contratos_cerrar.php';
	 	break ;
		case "reabrir":
		include('../lib/funciones/php/contratos/discusion/reabrir_discusion.php');
		$url= 'discusion_contratos_reabrir.php';
	 	break ;
		case "reabrir_peticiones":
		include('../lib/funciones/php/contratos/discusion/reabrir_peticiones.php');
		$url= 'discusion_contratos_reabrir_peticiones.php';
	 	break ;
		case "editar_ofertas":
		include('../lib/funciones/php/contratos/discusion/editar_ofertas_discusion.php');
		$url= 'discusion_contratos_editar_ofertas.php';
	 	break ;
		case "editar_peticiones":
		include('../lib/funciones/php/contratos/discusion/editar_peticiones_discusion.php');
		$url= 'discusion_contratos_editar_peticiones.php';
	 	break ;
		case "editar_reuniones":
		include('../lib/funciones/php/contratos/discusion/editar_reuniones_discusion.php');
		$url= 'discusion_contratos_editar_reuniones.php';
	 	break ;
		case "editar_acuerdos":
		include('../lib/funciones/php/contratos/discusion/editar_acuerdos_discusion.php');
		$url= 'discusion_contratos_editar_acuerdos.php';
	 	break ;

		case "ver":
		$url= 'discusion_contratos_ver.php';
	 	break ;
		case "ver_ofertas":
		$url= 'discusion_contratos_ver_ofertas.php';
	 	break ;
		case "ver_peticiones":
		$url= 'discusion_peticiones_contratos_ver.php';
	 	break ;
		case "ver_reuniones":
		$url= 'discusion_contratos_ver_reuniones.php';
	 	break ;

		case "ver_acuerdos":
		$url= 'discusion_contratos_ver_acuerdos.php';
	 	break ;

		case "eliminar":
		include('../lib/funciones/php/contratos/discusion/eliminar_discusion.php');
		$url= 'discusion_contratos_eliminar.php';
	 	break ;
		case "eliminar_ofertas":
		include('../lib/funciones/php/contratos/discusion/eliminar_ofertas_discusion.php');
		$url= 'discusion_contratos_eliminar_ofertas.php';
	 	break ;
		case "eliminar_peticiones":
		include('../lib/funciones/php/contratos/discusion/eliminar_peticiones_discusion.php');
		$url= 'discusion_contratos_eliminar_peticiones.php';
	 	break ;

		case "eliminar_reuniones":
		include('../lib/funciones/php/contratos/discusion/eliminar_reuniones_discusion.php');
		$url= 'discusion_contratos_eliminar_reuniones.php';
	 	break ;
		case "eliminar_acuerdos":
		include('../lib/funciones/php/contratos/discusion/eliminar_acuerdos_discusion.php');
		$url= 'discusion_contratos_eliminar_acuerdos.php';
	 	break ;
		case "default":
		$url= 'discusion_contratos_listar.php';
	 	break ;
		}
 */


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


/*
if($_SESSION['tipo']!=1){
	$var=$pantalla->getporUrl($url);
	$id_pantalla=$pantalla->id_pantalla;
	$seg=$seguridad->getSeguridad($id_pantalla,$t_usuario );
	if(!$seg){
		$tbl="error.php";
	}
}
*/
// DATOS DEL MENU
$cat="Convenciones Colectivas";
$var=$categorias->getporNombre($cat);
$id_categoria=$categorias->Id_categoria;
$desc=$categorias->desc;

?>
