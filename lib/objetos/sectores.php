<? 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php   
# Clase= sectores
# Descripcion: clase que contiene todas las funciones relacionadas con la tabla
# sectores
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_SECTORES' )) {
	return;
}

define( '_SECTORES', '1.0' );

class SECTORES {

//FUNCION QUE DEVUELVE UN SECTOR DE EMPRESA ESPECIFICO 
function getId ( $id )  {

		if( $id== "" || $id== 0) return false;
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from sectores_empresas"
				. " where codigo_sector = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->assignRecord( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
//FUNCION QUE DEVUELVE EL SECTOR POR ID 
function traerPorNombre ( $id )  {

		if( $id== "" || $id== 0) return false;
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from sectores_empresas"
				. " where codigo_sector = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$nombre=$query_result->value('nombre_sector');	
			return $nombre;
		} else {
			
			return false;
		}
		
	}
 
//FUNCION QUE DEVUELVE EL SECTOR POR SU NOMBRE
function getporNombre ( $nombre )  {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from sectores_empresas"
				. " where nombre_sector LIKE  %s",
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
 
function update ( $nombre ,$descripcion, $id_sector)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update  sectores_empresas set"
				. " nombre_sector= %s, descripcion_sector = %s WHERE codigo_sector = %d",
				$dbconn->quoteString($nombre),
				$dbconn->quoteString($descripcion),
				$id_sector);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//FUNCION ELIMINAR SECTOR

function delete( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from sectores_empresas "
		. "where codigo_sector = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR SECTORES


function listarSectores($offset, $limit , $filtro ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  =  "select * from sectores_empresas where nombre_sector like '%".$filtro."%' ORDER BY nombre_sector  ASC LIMIT 0, ".$limit."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_sector" )] = array();
		$data[$list_result->value( "codigo_sector" )][codigo_sector]
		= $list_result->value( "codigo_sector" );
		$data[$list_result->value( "codigo_sector" )][nombre_sector]
		= $list_result->value( "nombre_sector" );
		$data[$list_result->value( "codigo_sector" )][descripcion_sector]
		= $list_result->value( "descripcion_sector" );

		}
		$list_result->close();
		
		return $data;
		}	
		
	

//FUNCION LISATRE SECTORES_2
function listarSectores2() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  =  "select * from sectores_empresas ";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_sector" )] = array();
		$data[$list_result->value( "codigo_sector" )][codigo_sector]
		= $list_result->value( "codigo_sector" );
		$data[$list_result->value( "codigo_sector" )][nombre_sector]
		= $list_result->value( "nombre_sector" );
//		$data[$list_result->value( "codigo_sector" )][descripcion_sector]
//		= $list_result->value( "descripcion_sector" );

		}
		$list_result->close();
		
		return $data;
		}	

//LISTAR PARA COMPARATIVO		
function listarSectores_comparativo() {
		include( '../../../../local/configuracion.php' );
		$con = mysql_connect("localhost", "root", "162310"); 
		mysql_select_db("CCCOL", $con); 

		$list_sql  =  "select * from sectores_empresas ";
		$list_result	 = mysql_query($list_sql); 
		reset($list_result);
		while ($fila = mysql_fetch_assoc($list_result)){
			$x=$x+1;
    		$data[$x][codigo_sector]= $fila["codigo_sector"];
			$data[$x][nombre_sector]=$fila["nombre_sector"];
			}
		mysql_free_result($list_result);			
  		return $data;
		}	
		


//FUNCION INSERT SECTORES
function insert($nombre,$descripcion) {
		
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into sectores_empresas ( "
		."nombre_sector ,"
		."descripcion_sector"
		.") values ( "
		. "%s,%s)",
		
		$dbconn->quoteString($nombre),
		$dbconn->quoteString($descripcion));
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION CONTAR SECTORES PARA PAGINADOR
function contarSectores() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from sectores_empresas " );
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

$this->codigo_sector			= $user_record->value('codigo_sector');
$this->nombre_sector			= $user_record->value('nombre_sector');
$this->descripcion_sector		= $user_record->value('descripcion_sector');
$this->Total					= $user_record->value('Total');
}	

}

if( ! isset($sectores)) { 
	global $sectores;
	$sectores = New SECTORES();
}
?>