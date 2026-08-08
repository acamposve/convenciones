<? 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php   
# Clase= Localidad
# Descripcion: clase que contiene todas las funciones relacionadas con autentificacion
# y seguridad de usuarios
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_localidades' )) {
	return;
}

define( '_localidades', '1.0' );

class Localidad {

//FUNCION QUE DEVUELVE UNA LOCALIDAD 
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from localidades"
				. " where Id_localidad = %d",
				$id);

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->assignRecord( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
 

//FUNCION UPDATE
 
 	function update ( $nombre, $id_estado, $id_pais, $id_localidad )  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update localidades set"
				. " Nombre_localidad= %s , Id_pais = %d, Id_estado= %d WHERE Id_localidad = %d",		
				$dbconn->quoteString($nombre),
				$id_pais,
				$id_estado,
				$id_localidad);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//ELIMINAR UNA LOCALIDAD

function delete( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from localidades "
		. "where Id_localidad = %d",
		$id);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION ELIMINAR LOCALIDAD POR PAIS
		function deleteporPais( $id_pais ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from localidades "
		. "where Id_pais = %d",
		$id_pais);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}
//FUNCION ELIMINAR LOCALIDAD POR ESTADO
		function deleteporEstado( $id_estado ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from localidades "
		. "where Id_estado = %d",
		$id_estado);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}
//FUNCION LISTAR LOCALIDAD


function listarLocalidad( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  =  "select * from localidades where Nombre_localidad  LIKE '%".$filtro."%' ORDER BY  Nombre_localidad ASC LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_localidad" )] = array();
		$data[$list_result->value( "Id_localidad" )][Id_localidad]
		= $list_result->value( "Id_localidad" );
		$data[$list_result->value( "Id_localidad" )][Id_estado]
		= $list_result->value( "Id_estado" );
		$data[$list_result->value( "Id_localidad" )][Id_pais]
		= $list_result->value( "Id_pais" );
		$data[$list_result->value( "Id_localidad" )][Nombre_localidad]
		= $list_result->value( "Nombre_localidad" );
		}
		$list_result->close();
		
		return $data;
		}	
//FUNCION LISTAR LOCALIDAD_2

function listarLocalidad2($id_estado) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from localidades  WHERE Id_estado = %d ORDER BY  Nombre_localidad ASC ",$id_estado);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_localidad" )] = array();
		$data[$list_result->value( "Id_localidad" )][Id_localidad]
		= $list_result->value( "Id_localidad" );
		$data[$list_result->value( "Id_localidad" )][Id_estado]
		= $list_result->value( "Id_estado" );
		$data[$list_result->value( "Id_localidad" )][Id_pais]
		= $list_result->value( "Id_pais" );
		$data[$list_result->value( "Id_localidad" )][Nombre_localidad]
		= $list_result->value( "Nombre_localidad" );
		}
		$list_result->close();
		
		return $data;
		}			
//FUNCION INSERT LOCALIDAD

function insert($nombre, $id_pais, $id_estado) {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into localidades ( "
		."Nombre_localidad ,"
		."Id_pais ,"
		."Id_estado"
		.") values ( "
		. "%s, %d, %d)",
		$dbconn->quoteString($nombre),
		$id_pais,
		$id_estado);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION CONTAR LOCALIDADES PARA PAGINADOR

function contarLocalidades() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from localidades " );
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
//FUNCION ASGINAR REGISTROS 
function assignRecord( $user_record ) {

$this->id_pais			= $user_record->value('Id_pais');
$this->id_estado		= $user_record->value('Id_estado');
$this->id_localidad		= $user_record->value('Id_localidad');
$this->nombre			= $user_record->value('Nombre_localidad');
$this->Total			= $user_record->value('Total');
}	
	

}

if( ! isset($localidad)) { 
	global $localidad;
	$localidad = New Localidad();
}
?>