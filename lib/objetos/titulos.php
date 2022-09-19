<?php 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php
# Clase= titulos
# Descripcion: clase que contiene todas las funciones relacionadas con la tabla
# titulos
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_TITULOS' )) {
	return;
}

define( '_TITULOS', '1.0' );

class TITULOS {

//FUNCION QUE DEVUELVE EL TITULO SEGUN SU ID

 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from titulos"
				. " where codigo_titulo_comparativo = %d",
				$id);

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}

//FUNCION QUE DEVUELVE EL TITULO SEGUN SU NOMBRE

 	function getporNombre ( $nombre )  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from titulos"
				. " where nombre_titulo LIKE  %s",
				$dbconn->quoteString($nombre));

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}


//FUNCION UPDATE
function update ( $titulo ,$descripcion,$categoria,$id_titulo)  {
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

	$dbconn = $LibSite->openDBConn(0);

	$query = sprintf("Update  titulos set"
			. " nombre_titulo= %s, descripcion_titulo = %s,   codigo_categoria_titulo = %d WHERE codigo_titulo_comparativo = %d",
			$dbconn->quoteString($titulo),
			$dbconn->quoteString($descripcion),
			$categoria,
			$id_titulo);
	$update_result = $dbconn->execute( $query );
	$update_result->close();
	return true;
}


//FUNCION ELIMINAR TITULO
function delete( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from titulos "
		. "where codigo_titulo_comparativo = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}
//FUNCION LISTAR TITULOS


function listarTitulos($ini, $fin, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  =  "select * from titulos where nombre_titulo LIKE '%".$filtro."%' ORDER BY nombre_titulo  ASC LIMIT $ini , $fin ";
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_titulo_comparativo" )] = array();
		$data[$list_result->value( "codigo_titulo_comparativo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_titulo_comparativo" )][nombre_titulo]
		= $list_result->value( "nombre_titulo" );
		$data[$list_result->value( "codigo_titulo_comparativo" )][descripcion_titulo]
		= $list_result->value( "descripcion_titulo" );
		$data[$list_result->value( "codigo_titulo_comparativo" )][codigo_categoria_titulo]
		= $list_result->value( "codigo_categoria_titulo" );
		}
		$list_result->close();

		return $data;
		}


//LISTAR TITULOS PARA LEYES
function listarTitulosparaleyes() {

include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from titulos ORDER BY nombre_titulo ASC";
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_titulo_comparativo" )] = array();
		$data[$list_result->value( "codigo_titulo_comparativo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_titulo_comparativo" )][nombre_titulo]
		= $list_result->value( "nombre_titulo" );
		$data[$list_result->value( "codigo_titulo_comparativo" )][descripcion_titulo]
		= $list_result->value( "descripcion_titulo" );
		$data[$list_result->value( "codigo_titulo_comparativo" )][codigo_categoria_titulo]
		= $list_result->value( "codigo_categoria_titulo" );
		}
		$list_result->close();

		return $data;
		}


//LISTAR TITULOS PARA COMPARACION
function listarTitulosparaComp() {

include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from titulos ORDER BY nombre_titulo ASC";
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_titulo_comparativo" )] = array();
		$data[$list_result->value( "codigo_titulo_comparativo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_titulo_comparativo" )][nombre_titulo]
		= $list_result->value( "nombre_titulo" );

		}
		$list_result->close();

		return $data;
		}

//FUNCION INSERT TITULOS
function insert($titulo,$descripcion,$categoria) {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$insert_sql = sprintf( "insert into titulos ( "
		."nombre_titulo ,"
		."descripcion_titulo ,"
		."codigo_categoria_titulo"
		.") values ( "
		. "%s,%s,%d)",

		$dbconn->quoteString($titulo),
		$dbconn->quoteString($descripcion),
		$categoria);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}

//FUNCION CONTAR TITULOS PARA PAGINADOR
function contarTitulos() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select count(*) as Total from titulos " );
		$list_result = $dbconn->execute( $list_sql );
		if( $list_result->realcount() == 1 ) {

		$this->assignRecord($list_result);
		$list_result->close();
		return true;
		} else {
		$list_result->close();
		return false;
		}
		}

//LISTAR TITULOS PARA COMPARACION
function listarTitulos_comparacion() {

		include( '../../../../local/configuracion.php' );
		$data = array();
		$con = mysql_connect("localhost", "presenci_cccol", "Cccol2MP42qi");
		mysql_select_db("presenci_cccol", $con);

		$list_sql  =	"SELECT  categorias_titulos.codigo_categoria_titulos as codigo,".
						"categorias_titulos.nombre_categoria,titulos.codigo_titulo_comparativo,".
						"titulos.nombre_titulo FROM `categorias_titulos` ".
						"inner join titulos on  ".
						"categorias_titulos.codigo_categoria_titulos = titulos.codigo_categoria_titulo ".
						"order by categorias_titulos.nombre_categoria,titulos.nombre_titulo";


		$list_result	 = mysql_query($list_sql);
		$x=0;
		while ($fila = mysql_fetch_assoc($list_result))
			{
			$x=$x+1;
    		$data[$x][codigo_titulo_comparativo]= $fila["codigo_titulo_comparativo"];
			$data[$x][nombre_titulo]=$fila["nombre_titulo"];
			$data[$x][codigo_categoria_titulo]=$fila["codigo_categoria_titulo"];
			$data[$x][nombre_categoria]=$fila["nombre_categoria"];
			$data[$x][codigo]=$fila["codigo"];
			}
		mysql_free_result($list_result);
  		return $data;
		}

//LISTAR TITULOS PARA Historico
function listarTitulos_historico_bitacora($id) {

		include( '../../../../local/configuracion.php' );
		$data = array();
		$con = mysql_connect("localhost", "root", "vertrigo");
		mysql_select_db("CCCOL", $con);

		$list_sql  =	"SELECT * FROM titulos where  codigo_titulo_comparativo='".$id."'";
		 $list_result	 = mysql_query($list_sql);
		$x=0;
		while ($fila = mysql_fetch_assoc($list_result))
			{
			$x=$x+1;
			$data[$x][nombre_titulo]=$fila["nombre_titulo"];

			}
		mysql_free_result($list_result);
  		return $data;
		}

//LISTAR EMPRESAS PARA COMPARACION
function listar_Articulos_Empresa( $titulo, $codigo_emp) {

		include( '../../../../../local/configuracion.php');
		$data = array();
		$con = mysql_connect("localhost", "root", "vertrigo");
		mysql_select_db("CCCOL", $con);

	$list_sql= "select * from articulos_contratos where codigo_titulo_comparativo ='".$titulo."'  and codigo_contratos= '".$codigo_emp."' order by nro_articulos";
		$list_result	 = mysql_query($list_sql);
		$x=0;
		while ($fila = mysql_fetch_assoc($list_result))
			{
    		$data[$x][codigo_articulo]			= $fila["codigo_articulo"];
    		$data[$x][nro_articulos]			= $fila["nro_articulos"];
			$data[$x][codigo_contratos]			=$fila["codigo_contratos"];
			$data[$x][texto_completo_articulo]	=$fila["texto_completo_articulo"];
			$data[$x][resumen_articulo]			=$fila["resumen_articulo"];
			$data[$x][codigo_titulo_comparativo]=$fila["codigo_titulo_comparativo"];
			$data[$x][campo_comparativo]		=$fila["campo_comparativo"];
			$data[$x][titulo_articulo]			=$fila["titulo_articulo"];
			$x=$x+1;
			}
		mysql_free_result($list_result);

  		return $data;
		}
//FUNCION ASIGNAR REGISTROS
function assignRecord( $user_record ) {

$this->codigo_titulo_comparativo		= $user_record->value('codigo_titulo_comparativo');
$this->nombre_titulo					= $user_record->value('nombre_titulo');
$this->descripcion_titulo				= $user_record->value('descripcion_titulo');
$this->codigo_categoria_titulo			= $user_record->value('codigo_categoria_titulo');
$this->Total							= $user_record->value('Total');
}

}

if( ! isset($titulos)) {
	global $titulos;
	$titulos = New TITULOS();
}
?>
