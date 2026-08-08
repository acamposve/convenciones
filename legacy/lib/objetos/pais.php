<? 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php   
# Clase= User
# Descripcion: clase que contiene todas las funciones relacionadas con autentificacion
# y seguridad de usuarios
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_PAIS' )) {
	return;
}

define( '_PAIS', '1.0' );

class Pais {

//FUNCION QUE DEVUELVE UN PAIS ESPECIFICO
 
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from paises"
				. " where Id_Pais = %d",
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
		
		$query = sprintf("Update paises set"
				. " Nombre_pais= %s WHERE Id_pais = %d",
		
				$dbconn->quoteString($nombre),
				$id);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//FUNCION ELIMINAR UN PAIS

function delete( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from paises "
		. "where Id_pais = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR PAISES


function listarPais( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = "select * from paises  WHERE Nombre_pais LIKE '%".$filtro."%' ORDER BY  Nombre_pais ASC LIMIT ".$ini.", ".$fin."";
		
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
				$data[$list_result->value( "Id_pais" )] = array();
				$data[$list_result->value( "Id_pais" )][Id_pais] = $list_result->value( "Id_pais" );
				$data[$list_result->value( "Id_pais" )][Nombre_pais] = $list_result->value( "Nombre_pais" );
			}
		$list_result->close();
		
		return $data;
		}		
		
//FUNCION INSERT PAIS
function insert($nombre) {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into paises ( "
		."Nombre_pais"
		.") values ( "
		. "%s)",
		$dbconn->quoteString($nombre));
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION CONTAR PAISES
function contarPaises() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from paises " );
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
//FUNCION ASIGNAR REGISTROS
function assignRecord( $user_record ) {

$this->id_pais			= $user_record->value('Id_pais');
$this->nombre			= $user_record->value('Nombre_pais');
$this->Total			= $user_record->value('Total');
}	

//FUNCION  LISTAR PAISES_2
function listarPais2() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from paises ORDER BY  Nombre_pais ASC ");
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_pais" )] = array();
		$data[$list_result->value( "Id_pais" )][Id_pais]
		= $list_result->value( "Id_pais" );
		$data[$list_result->value( "Id_pais" )][Nombre_pais]
		= $list_result->value( "Nombre_pais" );
		}
		$list_result->close();
		
		return $data;
		}		

}

if( ! isset($pais)) { 
	global $pais;
	$pais = New Pais();
}
?>