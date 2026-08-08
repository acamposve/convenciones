<? 
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
if( defined( '_CATEGORIA_SECTOR' )) {
	return;
}

define( '_CATEGORIA_SECTOR', '1.0' );

class CATEGORIA_SECTOR {

 //FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI EL SECTOR EXISTE
 
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from categoria_sector"
				. " where codigo_categoria = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->assignRecord( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
 
 //FUNCION GET QUE BUSCA EN LA BASE DE DATOS POR NOMBRE SECTOR
 
 	function getporNombre ( $nombre )  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from categoria_sector"
				. " where nombre_categoria LIKE  %s",
				$dbconn->quoteString($nombre));

		$query_result = $dbconn->execute( $query );	
		if( $query_result->realcount() == 1 ) {
			
			$this->assignRecord( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
//FUNCION UPDATE SECTOR
 
 	function update ( $categoria,$descripcion,$id_categoria)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update  categoria_sector set"
				. " nombre_categoria= %s, descripcion_categoria = %s WHERE codigo_categoria = %d",
				$dbconn->quoteString($categoria),
				$dbconn->quoteString($descripcion),
				$id_categoria);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//FUNCION ELIMINAR SECTOR

function delete( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from categoria_sector "
		. "where codigo_categoria = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR SECTORES


function listarCategoria($offset, $limit, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );
	
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = "select * from categoria_sector where nombre_categoria LIKE '%".$filtro."%'  ORDER BY nombre_categoria ASC LIMIT ".$offset.", ".$limit."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_categoria" )] = array();
		$data[$list_result->value( "codigo_categoria" )][codigo_categoria]
		= $list_result->value( "codigo_categoria" );
		$data[$list_result->value( "codigo_categoria" )][nombre_categoria]
		= $list_result->value( "nombre_categoria" );
		$data[$list_result->value( "codigo_categoria" )][descripcion_categoria]
		= $list_result->value( "descripcion_categoria" );
		}
		$list_result->close();
		
		return $data;
		}	
//FUNCION LISTAR SECTOR 2
function listarCategoria2() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );
	
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from categoria_sector ORDER BY nombre_categoria ASC");
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_categoria" )] = array();
		$data[$list_result->value( "codigo_categoria" )][codigo_categoria]
		= $list_result->value( "codigo_categoria" );
		$data[$list_result->value( "codigo_categoria" )][nombre_categoria]
		= $list_result->value( "nombre_categoria" );
		$data[$list_result->value( "codigo_categoria" )][descripcion_categoria]
		= $list_result->value( "descripcion_categoria" );
		}
		$list_result->close();
		
		return $data;
		}	


//LISTAR CATEGORIAS PARA COMPARATIVO
function listarCategoria_Comparativo() {
		include( '../../../../local/configuracion.php' );
		$con = mysql_connect("localhost", "root", "vertrigo"); 
		mysql_select_db("CCCOL", $con); 

		$list_sql  = sprintf( "select * from categoria_sector ORDER BY nombre_categoria ASC");
		$list_result	 = mysql_query($list_sql); 
		reset($list_result);
		while ($fila = mysql_fetch_assoc($list_result)){
			$x=$x+1;
    		$data[$x][codigo_categoria]= $fila["codigo_categoria"];
    		$data[$x][nombre_categoria]= $fila["nombre_categoria"];
    		$data[$x][descripcion_categoria]= $fila["descripcion_categoria"];
		}
		mysql_free_result($list_result);			
  		return $data;
		}	


//FUNCION INSERTAR SECTORES
function insert($nombre_categoria,$descripcion) {
		
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into categoria_sector ( "
		."nombre_categoria ,"
		."descripcion_categoria"
		.") values ( "
		. "%s,%s)",
		
		$dbconn->quoteString($nombre_categoria),
		$dbconn->quoteString($descripcion));
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION CONTAR SECTORES PARA PAGINADOR
function contarCategorias() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );
		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from categoria_sector " );
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

$this->codigo_categoria_titulos					= $user_record->value('codigo_categoria');
$this->descripcion_categoria					= $user_record->value('descripcion_categoria');
$this->nombre_categoria							= $user_record->value('nombre_categoria');
$this->Total									= $user_record->value('Total');
}	

}

if( ! isset($categoria_sector)) { 
	global $categoria_sector;
	$categoria_sector = New CATEGORIA_SECTOR();
}
?>