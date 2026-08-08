<? 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php   
# Clase= Estados
# Descripcion: clase que contiene todas las funciones relacionadas con autentificacion
# y seguridad de usuarios
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_ESTADO' )) {
	return;
}

define( '_ESTADO', '1.0' );

class Estado {

//FUNCION QUE DEVUELVE UN ESTADO
 
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from estados"
				. " where Id_estado = %d",
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
 
 	function update ( $nombre, $id, $id_pais )  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update estados set"
				. " Nombre_estado= %s , Id_pais = %d WHERE Id_estado = %d",
		
				$dbconn->quoteString($nombre),
				$id_pais,
				$id);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//FUNCION QUE ELIMINA UN ESTADO

function delete( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from estados "
		. "where Id_estado = %d",
		$id);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION QUE ELIMNAR POR ID DEL PAIS
function deleteporPais( $id_pais ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from estados "
		. "where Id_pais = %d",
		$id_pais);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR Estados


function listarEstado( $ini , $fin, $filtro ) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  =  "select * from estados where Nombre_estado LIKE '%".$filtro."%' ORDER BY  Nombre_estado ASC LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_estado" )] = array();
		$data[$list_result->value( "Id_estado" )][Id_pais]
		= $list_result->value( "Id_pais" );
		$data[$list_result->value( "Id_estado" )][Id_estado]
		= $list_result->value( "Id_estado" );
		$data[$list_result->value( "Id_estado" )][Nombre_estado]
		= $list_result->value( "Nombre_estado" );
		}
		$list_result->close();
		
		return $data;
		}	
//ESTADOS PARA COMPARATIVO
function listarEstados_Comparativo() {
		include( '../../../../local/configuracion.php' );
		$con = mysql_connect("localhost", "root", "vertrigo"); 
		mysql_select_db("CCCOL", $con); 

		$list_sql  = sprintf( "select * from estados ORDER BY Nombre_estado ASC");
		$list_result	 = mysql_query($list_sql); 
		reset($list_result);
		while ($fila = mysql_fetch_assoc($list_result)){
			$x=$x+1;
    		$data[$x][Nombre_estado]= $fila["Nombre_estado"];
    		$data[$x][Id_estado]= $fila["Id_estado"];
		}
		mysql_free_result($list_result);
  		return $data;
		}	
		
//FDUNCION LISTAR ESTADOS_2
function listarEstado2($idPais) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from estados WHERE Id_pais = %d ORDER BY  Nombre_estado ASC ",$idPais  );
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "Id_estado" )] = array();
		$data[$list_result->value( "Id_estado" )][Id_pais]
		= $list_result->value( "Id_pais" );
		$data[$list_result->value( "Id_estado" )][Id_estado]
		= $list_result->value( "Id_estado" );
		$data[$list_result->value( "Id_estado" )][Nombre_estado]
		= $list_result->value( "Nombre_estado" );
		}
		$list_result->close();
		
		return $data;
		}			
//FUNCION INSERT ESTADOS
function insert($nombre, $id_pais) {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into estados ( "
		."Nombre_estado,"
		."Id_pais"
		.") values ( "
		. "%s, %d)",
		$dbconn->quoteString($nombre),
		$id_pais);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION CONTAR ESTADOS PAR PAGINADOR
function contarEstados() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from estados " );
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

$this->id_pais			= $user_record->value('Id_pais');
$this->id_estado		= $user_record->value('Id_estado');
$this->nombre			= $user_record->value('Nombre_estado');
$this->Total			= $user_record->value('Total');
}	
	

}

if( ! isset($estado)) { 
	global $estado;
	$estado = New Estado();
}
?>