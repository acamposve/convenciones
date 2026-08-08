<?php 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php
# Clase= User
# Descripcion: clase que contiene todas las funciones relacionadas con la tabla
# Modulos
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_MODULO' ))
	{
		return;
	}
define( '_MODULO', '1.0' );
class MODULO
	{
//FUNCION QUE DEVUELVE UN MDULO
 	 	function getId ( $id )
			{
				if( $id== "" || $id== 0) return false;
				include( '../local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Select * from modulos"
				. " where Id_modulo = %d",
				$id);
				$query_result = $dbconn->execute( $query );
				if( $query_result->realcount() == 1 )
					{
						$this->assignRecord( $query_result );
						return true;
					}
				else
					{
						return false;
					}
			}

//FUNCION QUE DEVUELVE UN MODULO POR SU CATEGORIA
 	 	function getIdCategoria ( $id )
			{
				if( $id== "" || $id== 0) return false;
				include( '../local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Select * from modulos"
				. " where Id_categoria = %d",
				$id);
				$query_result = $dbconn->execute( $query );
				if( $query_result->realcount() == 1 )
					{
						$this->assignRecord( $query_result );
						return true;
					}
				else
					{
						return false;
					}
			}

//FUNCION QUE DEVUELVE UNA MODULO POR SU NOMBRE
	 	function getporNombre ( $nombre )
			{
				include( '../local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Select * from modulos"
				. " where Nombre_modulo LIKE  %s",
				$dbconn->quoteString($nombre));
				$query_result = $dbconn->execute( $query );
				if( $query_result->realcount() == 1 )
					{
						$this->assignRecord( $query_result );
						return true;
					}
				else
					{
						return false;
					}
			}
//FUNCION UPDATE
 	 	function update ( $nombre_modulo,$id_categoria, $prioridad, $id_modulo )
			{
				include( '../local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Update  modulos set"
				. " Nombre_modulo= %s, Id_categoria=%d, Prioridad = %d WHERE Id_modulo = %d",
				$dbconn->quoteString($nombre_modulo),
				$id_categoria,
				$prioridad,
				$id_modulo);
				$update_result = $dbconn->execute( $query );
				$update_result->close();
				return true;
			}

//FUNCION ELIMNAR MODULO
		function delete( $id )
			{
				include( '../local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$delete_sql  = sprintf( "delete from Id_modulo "
				. "where id_pantalla = %d",
				$id);
				$delete_result = $dbconn->execute( $delete_sql );
				$delete_result->close();
				return true;
 			}
//FUNCION LISTAR MODULOS
		function listarModulo()
			{
				include( '../local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);
				$list_sql  = sprintf( "select * from modulos ORDER BY  Id_modulo ASC ");
				$list_result = $dbconn->execute( $list_sql );
				reset($list_result);
				while( $list_result->next() )
					{
						$data[$list_result->value( "Id_modulo" )] = array();
						$data[$list_result->value( "Id_modulo" )][Id_modulo]		= $list_result->value( "Id_modulo" );
						$data[$list_result->value( "Id_modulo" )][Nombre_modulo]	= $list_result->value( "Nombre_modulo" );
						$data[$list_result->value( "Id_modulo" )][Prioridad]		= $list_result->value( "Prioridad" );
						$data[$list_result->value( "Id_modulo" )][Id_categoria]		= $list_result->value( "Id_categoria" );
					}
				$list_result->close();
				return $data;
			}
//FUNCION  LISTAR MODULOS POR ID
		function listarModuloporId($id)
			{
				include( '../local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);
				$list_sql  = sprintf( "select * from modulos WHERE Id_categoria = %d ORDER BY  Id_modulo ASC ",$id);
				$list_result = $dbconn->execute( $list_sql );
				reset($list_result);
				while( $list_result->next() )
					{
						$data[$list_result->value( "Id_modulo" )] = array();
						$data[$list_result->value( "Id_modulo" )][Id_modulo]		= $list_result->value( "Id_modulo" );
						$data[$list_result->value( "Id_modulo" )][Nombre_modulo]	= $list_result->value( "Nombre_modulo" );
						$data[$list_result->value( "Id_modulo" )][Prioridad]		= $list_result->value( "Prioridad" );
						$data[$list_result->value( "Id_modulo" )][Id_categoria]		= $list_result->value( "Id_categoria" );
						$data[$list_result->value( "Id_modulo" )][Desc]				= $list_result->value( "desc" );
					}
				$list_result->close();
				return $data;
			}
//FUNCION INSERT MODULO
		function insert($nombre_modulo,$id_categoria,$prioridad)
			{
				include( '../local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$insert_sql = sprintf( "insert into modulos ( "
				."Nombre_modulo ,"
				."Id_categoria ,"
				."Prioridad"
				.") values ( "
				. "%s,%d,%d)",
				$dbconn->quoteString($nombre_modulo),
				$id_categoria,
				$prioridad);
				$insert_result = $dbconn->execute( $insert_sql );
				$insert_result->close();
				return true;
			}
//FUNCION CONTAR MODULOS PARA PAGINADOR
		function contarPantallas()
			{
				include( '../local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);
				$list_sql  = sprintf( "select count(*) as Total from modulos " );
				$list_result = $dbconn->execute( $list_sql );
				if( $list_result->realcount() == 1 )
					{
						$this->assignRecord($list_result);
						$list_result->close();
						return true;
					}
				else
					{
						$list_result->close();
						return false;
					}
			}
//FUNCION ASIGNAR REGISTROS
		function assignRecord( $user_record )
			{
				$this->id_modulo		= $user_record->value('Id_modulo');
				$this->nombre_modulo	= $user_record->value('Nombre_modulo');
				$this->url_modulo		= $user_record->value('Prioridad');
				$this->id_categoria		= $user_record->value('Id_categoria');
				$this->Total			= $user_record->value('Total');
			}
	}
if( ! isset($modulos))
	{
		global $modulos;
		$modulos = New MODULO();
	}
?>
