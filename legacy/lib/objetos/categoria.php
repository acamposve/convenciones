<?php 

#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php
# Clase= categoria
# Descripcion: clase que contiene todas las funciones relacionadas con la tabla
# categorias
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_CATEGORIA' )) {
	return;
}

define( '_CATEGORIA', '1.0' );

class CATEGORIA {

 //FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI LA CATEGORIA EXISTE

 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from categorias"
				. " where Id_categoria = %d",
				$id);

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}

 //FUNCION GET QUE BUSCA EN LA BASE DE DATOS LA CATEGORIA POR NOMBRE

 	function getporNombre ( $nombre )  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from categorias"
				. " where Nombre_categoria LIKE  %s",
				$dbconn->quoteString($nombre));

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}
	//FUNCION UPDATE CATEGORIAS

 	function update ( $nombre_categoria,$id_categoria,$imagen, $prioridad)  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update  categorias set"
				. " Nombre_categoria= %s, Imagen,   Prioridad = %d WHERE Id_categoria = %d",
				$dbconn->quoteString($nombre_categoria),
				$imagen,
				$prioridad,
				$id_categoria);
		$update_result = $dbconn->execute( $query );

		$update_result->close();
		return true;
	}


//ELIMINAR CATEGORIA DE LA BD

function delete( $id ) {
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from categorias "
		. "where Id_categoria = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}
//FUNCION LISTAR CATEGORIAS, PARA MODULOS


function listarModulo() {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select * from categorias ORDER BY  Nombre_categoria ASC ");
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_categoria" )] = array();
		$data[$list_result->value( "Id_categoria" )][Id_categoria]
		= $list_result->value( "Id_categoria" );
		$data[$list_result->value( "Id_categoria" )][Nombre_categoria]
		= $list_result->value( "Nombre_categoria" );
		$data[$list_result->value( "Id_categoria" )][Prioridad]
		= $list_result->value( "Prioridad" );
		$data[$list_result->value( "Id_categoria" )][Imagen]
		= $list_result->value( "Imagen" );
		}
		$list_result->close();

		return $data;
		}
//FUNCION LISTAR CATEGORIAS
	function listarcategoria() {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select * from categorias ORDER BY  Id_categoria ASC");
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_categoria" )] = array();
		$data[$list_result->value( "Id_categoria" )][Id_categoria]		= $list_result->value( "Id_categoria" );
		$data[$list_result->value( "Id_categoria" )][Nombre_categoria]	= $list_result->value( "Nombre_categoria" );
		$data[$list_result->value( "Id_categoria" )][Prioridad]			= $list_result->value( "Prioridad" );
		$data[$list_result->value( "Id_categoria" )][Imagen]			= $list_result->value( "Imagen" );
		$data[$list_result->value( "Id_categoria" )][desc]				= $list_result->value( "desc" );
		}
		$list_result->close();

		return $data;
		}
//INSERT CATEGORIAS
function insert($nombre_categoria,$imagen,$prioridad) {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$insert_sql = sprintf( "insert into categorias ( "
		."Nombre_categoria ,"
		."Imagen ,"
		."Prioridad"
		.") values ( "
		. "%s,%s,%d)",
		$dbconn->quoteString($nombre_categoria),
		$dbconn->quoteString($imagen),
		$prioridad);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}

//FUNCION CONTAR CATEGORIAS PARA PAGINADOR
function contarPantallas() {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select count(*) as Total from categorias " );
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
//ASGINACION DE REGISTROS
function assignRecord( $user_record ) {

$this->Id_categoria		= $user_record->value('Id_categoria');
$this->Nombre_categoria			= $user_record->value('Nombre_categoria');
$this->Prioridad		= $user_record->value('Prioridad');
$this->Imagen		= $user_record->value('Imagen');
$this->desc		= $user_record->value('desc');
$this->Total			= $user_record->value('Total');
}

}

if( ! isset($categorias)) {
	global $categorias;
	$categorias = New CATEGORIA();
}
?>
