<?php
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/seguridad.php
# Clase= Seguridad
# Descripcion: clase que contiene todas las funciones relacionadas con la tabla
# seguridades
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( 'SEGURIDAD' )) {
	return;
}

define( 'SEGURIDAD', '1.0' );

class SEGURIDAD {

//FUNCION QUE DEVUELVE UNA  PERMISOLOGIA ESPECIFICA
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from seguridades"
				. " where Id_seguridad = %d",
				$id);

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}

//FUNCION QUE DEVUELVE LA PERMISOLOGIA

  	function getSeguridad( $idPantalla, $id_tipo )  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from seguridades"
				. " where Id_pantalla = %d AND Id_tipo_Usuario = %d ",
				$idPantalla, $id_tipo );
		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}

  //FUNCION EXTERNA QUE BUSCA EN LA BASE DE DATOS POR ID_pantalla y por Tipo

  	function getSeguridad_externa( $idPantalla, $id_tipo )
		{
			include( '../../../../local/configuracion.php' );
			$con = mysql_connect("localhost", "presenci_cccol", "Cccol2MP42qi");
			mysql_select_db("presenci_cccol", $con);
			$query = sprintf("Select * from seguridades"
			. " where Id_pantalla = %d AND Id_tipo_Usuario = %d ",
			$idPantalla, $id_tipo );
			$query_result	 = mysql_query($query);
		}

//FUNCION
function contarSeguridadportipo($t_usuario , $id_pantalla) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = sprintf( "select count(*) as Total from seguridades WHERE Id_tipo_Usuario =  %d AND id_pantalla = %d ", $t_usuario , $id_pantalla );
		$list_result = $dbconn->execute( $list_sql );
		if( $list_result->realcount() > 0 ) {

		$this->assignRecord($list_result);
		$list_result->close();
		return true;
		} else {
		$list_result->close();
		return false;
		}
		}

//FUNCION UPDATE
 	function update ( $nombre, $id )  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update seguridades set"
				. " Id_tipo_Usuario= %d , Id_pantalla = %d WHERE Id_seguridad = %d",

				$tipo,
				$pantalla,
				$id);
		$update_result = $dbconn->execute( $query );

		$update_result->close();
		return true;
	}


//FUNCION ELIMINAR
function delete( $id ) {
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from seguridades "
		. "where Id_seguridad = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}




//FUNCION QUE BORRA TODA LA SEGURIDAD

function borrarTodo( $id ) {
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from seguridades "
		. "where Id_tipo_Usuario = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}

//FUNCION LISTAR SEGURIDAD


function listarSeguridad( $ini , $fin) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select * from seguridades ORDER BY  Id_tipo_Usuario ASC LIMIT %d , %d", $ini , $fin );
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_seguridad" )] = array();
		$data[$list_result->value( "Id_seguridad" )][Id_seguridad]
		= $list_result->value( "Id_seguridad" );
		$data[$list_result->value( "Id_seguridad" )][Id_tipo_Usuario]
		= $list_result->value( "Id_tipo_Usuario" );
		$data[$list_result->value( "Id_seguridad" )][id_pantalla]
		= $list_result->value( "id_pantalla" );
		}
		$list_result->close();

		return $data;
		}

//FUNCION LISTAR SEGURIDAD POR TIPO DE USUARIO


function listarSeguridadporTipo( $ini , $fin, $tipo) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select * from seguridades WHERE  Id_tipo_Usuario = %d ASC LIMIT %d , %d",$tipo , $ini , $fin );
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_seguridad" )] = array();
		$data[$list_result->value( "Id_seguridad" )][Id_seguridad]
		= $list_result->value( "Id_seguridad" );
		$data[$list_result->value( "Id_seguridad" )][Id_tipo_Usuario]
		= $list_result->value( "Id_tipo_Usuario" );
		$data[$list_result->value( "Id_seguridad" )][id_pantalla]
		= $list_result->value( "id_pantalla" );
		}
		$list_result->close();

		return $data;
		}

 //FUNCION INSERT

function insert($tipo, $pantalla) {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$insert_sql = sprintf( "insert into seguridades ( "
		."Id_tipo_Usuario,"
		."Id_pantalla"
		.") values ( "
		. "%d, %d)",
		$tipo,
		$pantalla);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}

 //FUNCION QUE ASIGNA REGISTROS


function assignRecord( $user_record ) {

$this->Id_seguridad			= $user_record->value('Id_seguridad');
$this->Id_tipo_Usuario		= $user_record->value('Id_tipo_Usuario');
$this->id_pantalla			= $user_record->value('id_pantalla');
$this->Total				= $user_record->value('Total');
}

}

if(! isset($seguridad)) {
	global $seguridad;
	$seguridad = New SEGURIDAD();
}
?>
