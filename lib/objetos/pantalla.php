<?php 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php
# Clase= User
# Descripcion: clase que contiene todas las funciones relacionadas con la tabla
# tipos_de_usuarios
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_PANTALLA' )) {
	return;
}

define( '_PANTALLA', '1.0' );

class PANTALLA {

//FUNCION QUE DEVUELVE UNA PANTALLA ESPECIFICA
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from pantallas"
				. " where id_pantalla = %d",
				$id);

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}

//FUNCION QUE DEVUELVE LA PANTALLA POR EL URL
 	function getporUrl ( $url )  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from pantallas"
				. " where url_pantalla = %s",
				$dbconn->quoteString($url));

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}
//FUNCION QUE DEVUELVE LA PANTALLA POR EL URL  EXTERNO
 	function getporUrl_externo ( $url )  {

		include( '../../../../local/configuracion.php' );
		$con = mysql_connect("localhost", "presenci_cccol", "Cccol2MP42qi");
		mysql_select_db("presenci_cccol", $con);

		$query = "Select * from pantallas where url_pantalla = '".$url."'";
		$query_result	 = mysql_query($query);
			while ($row = mysql_fetch_assoc($query_result)){
					$id_pantalla 	= $row['id_pantalla'];
					return ($id_pantalla);
				}
	}



//FUNCION UPDATE

 	function update ( $url, $id_modulo, $nombre, $id )  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update pantallas set"
				. " url_pantalla= %s, id_modulo = %d, nombre_pantalla= %s WHERE id_pantalla = %d",

				$dbconn->quoteString($url),
				$id_modulo,
				$dbconn->quoteString($nombre),
				$id);
		$update_result = $dbconn->execute( $query );
		$update_result->close();
		return true;
	}


//FUNCION ELIMINAR PANTALLA

function delete( $id ) {
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from pantallas "
		. "where id_pantalla = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}
//FUNCION LISTAR PAISES


function listarPantallas( $ini , $fin, $filtro ) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  =  "select * from pantallas where nombre_pantalla LIKE '%".$filtro."%' ORDER BY  id_pantalla ASC LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "id_pantalla" )] = array();
		$data[$list_result->value( "id_pantalla" )][id_pantalla]
		= $list_result->value( "id_pantalla" );
		$data[$list_result->value( "id_pantalla" )][url_pantalla]
		= $list_result->value( "url_pantalla" );
		$data[$list_result->value( "id_pantalla" )][nombre_pantalla]
		= $list_result->value( "nombre_pantalla" );
		$data[$list_result->value( "id_pantalla" )][id_modulo]
		= $list_result->value( "id_modulo" );
		}
		$list_result->close();

		return $data;
		}
//FUNCION LISTAR PANTALLAS POR ID MODULO
function listarPantallasporModulos( $id_modulo ) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select * from pantallas WHERE  id_modulo = %d ", $id_modulo );
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "id_pantalla" )] = array();
		$data[$list_result->value( "id_pantalla" )][id_pantalla]
		= $list_result->value( "id_pantalla" );
		$data[$list_result->value( "id_pantalla" )][url_pantalla]
		= $list_result->value( "url_pantalla" );
		$data[$list_result->value( "id_pantalla" )][nombre_pantalla]
		= $list_result->value( "nombre_pantalla" );
		$data[$list_result->value( "id_pantalla" )][id_modulo]
		= $list_result->value( "id_modulo" );
		}
		$list_result->close();

		return $data;
		}
//FUNCION LISTAR PANTALLAS_2
function listarPantallas2( ) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select * from pantallas ORDER BY  id_modulo ASC" );
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "id_pantalla" )] = array();
		$data[$list_result->value( "id_pantalla" )][id_pantalla]
		= $list_result->value( "id_pantalla" );
		$data[$list_result->value( "id_pantalla" )][url_pantalla]
		= $list_result->value( "url_pantalla" );
		$data[$list_result->value( "id_pantalla" )][nombre_pantalla]
		= $list_result->value( "nombre_pantalla" );
		$data[$list_result->value( "id_pantalla" )][id_modulo]
		= $list_result->value( "id_modulo" );
		}
		$list_result->close();

		return $data;
		}

//FUNCION INSERT PANTALLAS
function insert($url_pantalla,$nombre_pantalla,$id_modulo) {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$insert_sql = sprintf( "insert into pantallas ( "
		."url_pantalla ,"
		."nombre_pantalla ,"
		."id_modulo"
		.") values ( "
		. "%s,%s,%d)",
		$dbconn->quoteString($url_pantalla),
		$dbconn->quoteString($nombre_pantalla),
		$id_modulo);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}

//FUNCION CONTAR PANTALLAS PARA PAGINADOR
function contarPantallas() {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select count(*) as Total from pantallas " );
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
//FUNCION CONTAR PANTALLAS PARA PAGINADOR POR ID MODULO
function contarPantallasporModulo($id_modulo) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select count(*) as Total from pantallas WHERE id_modulo = %d ", $id_modulo);
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


//ASIGNAR REGISTRO
function assignRecord( $user_record ) {

$this->id_pantalla			= $user_record->value('id_pantalla');
$this->nombre_pantalla			= $user_record->value('nombre_pantalla');
$this->url_pantalla			= $user_record->value('url_pantalla');
$this->id_modulo			= $user_record->value('id_modulo');
$this->Total			= $user_record->value('Total');
}
//ASIGNAR LOS DATOS QUE TRAE LA CONSULTA A VARIBLES DE LA CLASE
    function Limpiar(  )
    {
        $this->id_pantalla		= "";
        $this->nombre_pantalla	= "";
        $this->url_pantalla		= "";
        $this->id_modulo		= "";
        $this->prioridad		= "";
        $this->Total			= "";
    }

}

if( ! isset($pantalla)) {
	global $pantalla;
	$pantalla = New PANTALLA();
}
?>
