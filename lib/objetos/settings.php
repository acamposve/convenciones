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
 
if ($accion==""){
	$accion="listar";
}
if ($tabla==""){
	$tabla="principal";
}




switch ($tabla) {
	 case "Paises":
	 
	 		$acciones['ver']="tbl_paises_ver.php";
			$acciones['editar']="tbl_paises_edit.php";
			$acciones['eliminar']="tbl_paises_eli.php";
			$acciones['agregar']="tbl_paises_add.php";
			
	    $dir = 'pais';
	 	switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/pais/agregarpais.php');
		$url= 'tbl_paises_add.php';
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/pais/editarpais.php');
		$url= 'tbl_paises_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/pais/eliminarpais.php');
		$url= 'tbl_paises_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_paises_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_paises_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_paises_list.php'; 
	 	break ;
		}
	  break;
	  
	  
	 case "Estados":
	 
	 	 	$acciones['ver']="tbl_estado_ver.php";
			$acciones['editar']="tbl_estado_edit.php";
			$acciones['eliminar']="tbl_estado_eli.php";
			$acciones['agregar']="tbl_estado_add.php";
	 	$dir = 'estado';
	 	switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/estado/agregarestado.php');
		$url= 'tbl_estado_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/estado/editarestado.php');
		$url= 'tbl_estado_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/estado/eliminarestado.php');
		$url= 'tbl_estado_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_estado_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_estado_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_estado_list.php'; 
	 	break ;
		}
	  break;
	  
	  case "Localidad":
	  
		$acciones['ver']="tbl_localidad_ver.php";
		$acciones['editar']="tbl_localidad_edit.php";
		$acciones['eliminar']="tbl_localidad_eli.php";
		$acciones['agregar']="tbl_localidad_add.php";
		
	  	$dir = 'localidad';
	 	switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/localidad/agregarlocalidad.php');
		  $url= 'tbl_localidad_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/localidad/editarlocalidad.php');
		  $url= 'tbl_localidad_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/localidad/eliminarlocalidad.php');
		  $url= 'tbl_localidad_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		  $url= 'tbl_localidad_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		  $url= 'tbl_localidad_ver.php'; 
	 	break ;
		case "default":
		  $url= 'tbl_localidad_list.php'; 
	 	break ;
		}
	  break;
	  
	  case "Categorias De Titulos":
	  	$acciones['ver']="tbl_categoria_ver.php";
		$acciones['editar']="tbl_categoria_edit.php";
		$acciones['eliminar']="tbl_categoria_eli.php";
		$acciones['agregar']="tbl_categoria_add.php";
		
	  	$dir = 'categoria';
		switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/categoria/agregarcategoria.php');
		  $url= 'tbl_categoria_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/categoria/editarcategoria.php');
		  $url= 'tbl_categoria_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/categoria/eliminarcategoria.php');
		  $url= 'tbl_categoria_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		  $url= 'tbl_categoria_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		  $url= 'tbl_categoria_ver.php'; 
	 	break ;
		case "default":
		  $url= 'tbl_categoria_list.php'; 
	 	break ;
		}
	  break;
	  
	  
	  	case "Titulos Comparativos":
		$acciones['ver']="tbl_titulos_ver.php";
		$acciones['editar']="tbl_titulos_edit.php";
		$acciones['eliminar']="tbl_titulos_eli.php";
		$acciones['agregar']="tbl_titulos_add.php";
		
	  	$dir = 'titulos';
		switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/titulos/agregartitulo.php');
		$url= 'tbl_titulos_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/titulos/editartitulo.php');
		$url= 'tbl_titulos_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/titulos/eliminartitulo.php');
		$url= 'tbl_titulos_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_titulos_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_titulos_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_titulos_list.php'; 
	 	break ;
		}
	  break;
	 
	case "Sectores":
	
		$acciones['ver']="tbl_sectores_ver.php";
		$acciones['editar']="tbl_sectores_edit.php";
		$acciones['eliminar']="tbl_sectores_eli.php";
		$acciones['agregar']="tbl_sectores_add.php";
	  	$dir = 'sectores';
		switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/sectores/agregarsectores.php');
		$url= 'tbl_sectores_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/sectores/editarsectores.php');
		$url= 'tbl_sectores_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/sectores/eliminarsectores.php');
		$url= 'tbl_sectores_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_sectores_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_sectores_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_sectores_list.php'; 
	 	break ;
		}
	  break;
	  
	  	case "Tipos":
	  	$dir = 'tipos';
		$acciones['ver']="tbl_tipos_ver.php";
		$acciones['editar']="tbl_tipos_edit.php";
		$acciones['eliminar']="tbl_tipos_eli.php";
		$acciones['agregar']="tbl_tipos_add.php";
		switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/tipos/agregartipos.php');
		$url= 'tbl_tipos_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/tipos/editartipos.php');
		$url= 'tbl_tipos_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/tipos/eliminartipos.php');
		$url= 'tbl_tipos_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_tipos_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_tipos_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_tipos_list.php'; 
	 	break ;
		}
	  break;
	  
	  case "Categorias":
	  	$acciones['ver']="tbl_categoria_sector_ver.php";
		$acciones['editar']="tbl_categoria_sector_edit.php";
		$acciones['eliminar']="tbl_categoria_sector_eli.php";
		$acciones['agregar']="tbl_categoria_sector_add.php";
	  	$dir = 'categoria_sector';
		switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/categoria_sector/agregar_categoria_sector.php');
		$url= 'tbl_categoria_sector_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/categoria_sector/editar_categoria_sector.php');
		$url= 'tbl_categoria_sector_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/categoria_sector/eliminar_categoria_sector.php');
		$url= 'tbl_categoria_sector_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_categoria_sector_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_categoria_sector_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_categoria_sector_list.php'; 
	 	break ;
		}
	  break;
	  
	  
	  case "Actividades":
	  	$acciones['ver']="tbl_actividad_empresa_ver.php";
		$acciones['editar']="tbl_actividad_empresa_edit.php";
		$acciones['eliminar']="tbl_actividad_empresa_eli.php";
		$acciones['agregar']="tbl_actividad_empresa_add.php";
	  	$dir = 'actividad_empresa';
		switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/actividad_empresa/agregar_actividad_empresa.php');
		$url= 'tbl_actividad_empresa_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/actividad_empresa/editar_actividad_empresa.php');
		$url= 'tbl_actividad_empresa_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/actividad_empresa/eliminar_actividad_empresa.php');
		$url= 'tbl_actividad_empresa_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_actividad_empresa_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_actividad_empresa_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_actividad_empresa_list.php'; 
	 	break ;
		}
	  break;
	  
	  case "Empresas":
	  	$acciones['ver']="tbl_empresa_ver.php";
		$acciones['editar']="tbl_empresa_edit.php";
		$acciones['eliminar']="tbl_empresa_eli.php";
		$acciones['agregar']="tbl_empresa_add.php";
	  	$dir = 'empresa';
		switch ($accion) {
		case "agregar":
		include('../lib/funciones/php/tablas/empresa/agregarempresa.php');
		$url= 'tbl_empresa_add.php'; 
	 	break ;
		case "editar":
		include('../lib/funciones/php/tablas/empresa/editarempresa.php');
		$url= 'tbl_empresa_edit.php'; 
	 	break ;
		case "eliminar":
		include('../lib/funciones/php/tablas/empresa/eliminarempresa.php');
		$url= 'tbl_empresa_eli.php'; 
	 	break ;
		case "listar":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_empresa_list.php'; 
	 	break ;
		case "ver":
		include( '../lib/objetos/pager.php');
		$url= 'tbl_empresa_ver.php'; 
	 	break ;
		case "default":
		$url= 'tbl_empresa_list.php'; 
	 	break ;
		}
	  break;
	  
	  case"principal":
	  	$url='modulos/tablas/principal.php';
	  break;
	  case"default":
	  	$url='modulos/tablas/principal.php';
	  break;
	   }


// SEGURIDAD



if($t_usuario!=1){
$var=$pantalla->getporUrl($url);
$id_pantalla=$pantalla->id_pantalla; 
$seg=$seguridad->getSeguridad($id_pantalla,$t_usuario);
if(!$seg){
$tbl="error.php";
}
}

print $tbl;
if($dir=="")
$tbl=$url;
else	
$tbl=$dir."/".$url;

// DATOS DEL MENU 
$cat="Tablas Basicas";
$var=$categorias->getporNombre($cat);
$id_categoria=$categorias->Id_categoria;
$desc=$categorias->desc;

?>