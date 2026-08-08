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
if( defined( '_TIPOS_EMPRESAS' )) {
	return;
}

define( '_TIPOS_EMPRESAS', '1.0' );

class TIPOS_EMPRESAS {

//FUNCION QUE DEVUELVE TIPO DE EMPRESA SEGUN SU ID
 
function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from tipo_empresa"
				. " where codigo_tipo = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->assignRecord( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
//FUNCION QUE DEVUELVE TIPO DE EMPRESA SEGUN SU ID
 
function traerTipo ( $id )  {

		if( $id== "" || $id== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from tipo_empresa"
				. " where codigo_tipo = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			return $query_result->value('nombre_tipo');
		} else {
			
			return false;
		}
		
	}
//FUNCION QUE DEVUELVE TIPO DE EMPRESA SEGUN SU NOBRE
 
function getporNombre ( $nombre )  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from tipo_empresa"
				. " where nombre_tipo LIKE  %s",
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
 
function update ( $nombre ,$descripcion, $id_tipo)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update  tipo_empresa set"
				. " nombre_tipo= %s, descripcion_tipo = %s WHERE codigo_tipo = %d",
				$dbconn->quoteString($nombre),
				$dbconn->quoteString($descripcion),
				$id_tipo);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//FUNCION ELIMINAR 

function delete( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from tipo_empresa"
		. " where codigo_tipo = %d",
		$id);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR TIPO EMPRESA


function listarTipos($offset, $limit , $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  =  "select * from tipo_empresa where nombre_tipo like '%".$filtro."%' ORDER BY  nombre_tipo ASC LIMIT ".$offset.", ".$limit."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_tipo" )] = array();
		$data[$list_result->value( "codigo_tipo" )][codigo_tipo]
		= $list_result->value( "codigo_tipo" );
		$data[$list_result->value( "codigo_tipo" )][nombre_tipo]
		= $list_result->value( "nombre_tipo" );
		$data[$list_result->value( "codigo_tipo" )][descripcion_tipo]
		= $list_result->value( "descripcion_tipo" );

		}
		$list_result->close();
		
		return $data;
		}	
//FUNCION LISTAR TIPO EMPRESA_2

function listarTipos2() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from tipo_empresa ORDER BY  nombre_tipo ASC ");
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_tipo" )] = array();
		$data[$list_result->value( "codigo_tipo" )][codigo_tipo]
		= $list_result->value( "codigo_tipo" );
		$data[$list_result->value( "codigo_tipo" )][nombre_tipo]
		= $list_result->value( "nombre_tipo" );
		$data[$list_result->value( "codigo_tipo" )][descripcion_tipo]
		= $list_result->value( "descripcion_tipo" );

		}
		$list_result->close();
		
		return $data;
		}	

//LISTAR TIPOS PARA COMPARATIVOS		
function listarTipos_Comparativo() {
		include( '../../../../local/configuracion.php' );
		$con = mysql_connect("localhost", "root", "vertrigo"); 
		mysql_select_db("CCCOL", $con); 

		$list_sql  = sprintf( "select * from tipo_empresa ORDER BY  nombre_tipo ASC ");
		$list_result	 = mysql_query($list_sql); 
		reset($list_result);
		while ($fila = mysql_fetch_assoc($list_result)){
			$x=$x+1;
    		$data[$x][codigo_tipo]= $fila["codigo_tipo"];
    		$data[$x][nombre_tipo]= $fila["nombre_tipo"];
			}
		mysql_free_result($list_result);			
  		return $data;
		}	



//FUNCION INSERT TIPO EMPRESA

function insert($nombre,$descripcion) {
		
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into tipo_empresa ( "
		."nombre_tipo ,"
		."descripcion_tipo"
		.") values ( "
		. "%s,%s)",
		
		$dbconn->quoteString($nombre),
		$dbconn->quoteString($descripcion));
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
//FUNCION  CONTAR TIPO EMPRESAS PARA PAGINADOR
		
function contarSectores() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from tipo_empresa " );
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

$this->codigo_tipo			= $user_record->value('codigo_tipo');
$this->nombre_tipo			= $user_record->value('nombre_tipo');
$this->descripcion_tipo		= $user_record->value('descripcion_tipo');
$this->Total				= $user_record->value('Total');
}	

}

if( ! isset($tipos_empresas)) { 
	global $tipos_empresas;
	$tipos_empresas = New TIPOS_EMPRESAS();
}
?>