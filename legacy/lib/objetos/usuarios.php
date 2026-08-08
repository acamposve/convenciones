<? 
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
if( defined( '_TIPO_USUARIO' )) {
	return;
}

define( '_TIPO_USUARIO', '1.0' );

class TIPO_USUARIO {

//FUNCION QUE DEVUELVE EL TIPO DE USUARIO SEGUN SU ID
 
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from tipos_de_usuarios"
				. " where Id_tipo_Usuario = %d",
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
 
 	function update ( $nombre, $id )  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update tipos_de_usuarios set"
				. " Nombre_tipo= %s WHERE Id_tipo_Usuario = %d",
		
				$dbconn->quoteString($nombre),
				$id);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//FUNCION ELIMINAR

function delete( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from tipos_de_usuarios "
		. "where Id_tipo_Usuario = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR TIPOS DE USUARIOS


function listarTipo( $ini , $fin) {
		include( '../local/configuracion.php' );	
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from tipos_de_usuarios ORDER BY  Nombre_tipo ASC LIMIT %d , %d", $ini , $fin );
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_tipo_Usuario" )] = array();
		$data[$list_result->value( "Id_tipo_Usuario" )][Id_tipo_Usuario]
		= $list_result->value( "Id_tipo_Usuario" );
		$data[$list_result->value( "Id_tipo_Usuario" )][Nombre_tipo]
		= $list_result->value( "Nombre_tipo" );
		}
		$list_result->close();
		
		return $data;
		}	
		
//FUNCION INSERT
function insert($nombre) {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into tipos_de_usuarios ( "
		."Nombre_tipo"
		.") values ( "
		. "%s)",
		$dbconn->quoteString($nombre));
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION CONTAR TIPOS PARA PAGINADOR
function contarTipos() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from tipos_de_usuarios " );
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
//FUNCION ASIGNAR REGISTRO 
function assignRecord( $user_record ) {

$this->id_pais			= $user_record->value('Id_tipo_usuario');
$this->nombre			= $user_record->value('Nombre_tipo');
$this->Total			= $user_record->value('Total');
}	


function listarPais2() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from tipos_de_usuarios ORDER BY  Nombre_tipo ASC ");
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_tipo_usuario" )] = array();
		$data[$list_result->value( "Id_tipo_usuario" )][Id_tipo_usuario]
		= $list_result->value( "Id_tipo_usuario" );
		$data[$list_result->value( "Id_tipo_usuario" )][Nombre_tipo]
		= $list_result->value( "Nombre_tipo" );
		}
		$list_result->close();
		
		return $data;
		}		

}

if( ! isset($tipo_usuario)) { 
	global $tipo_usuario;
	$tipo_usuario = New TIPO_USUARIO();
}
?>