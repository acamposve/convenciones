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
if( defined( '_CATEGORIA_TITULO_TITULO' )) {
	return;
}

define( '_CATEGORIA_TITULO_TITULO', '1.0' );

class CATEGORIA_TITULO {

 #FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI EL TITULO EXISTE
 
 	function getId ( $id )  {

		if( $id== "" || $id== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from categorias_titulos"
				. " where codigo_categoria_titulos = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->assignRecord( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
//FUNCION GET QUE DEVUELVE EL TITULO
 
 	function traerTitulo ( $id )  {

		if( $id== "" || $id== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from categorias_titulos"
				. " where codigo_categoria_titulos = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			
			return $query_result->value('nombre_categoria');
			} else {
			
			return false;
		}
		
	}
	
//FUNCION QUE DEVUELVE EL NOMBRE DE UN TITULO POR EL ID
 	function traerNombre ( $id )  {

		if( $id== "" || $id== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from categorias_titulos"
				. " where codigo_categoria_titulos = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			return $query_result->value('nombre_categoria');
		}
		
	}
 
//FUNCION QUE BUSCA POR EL NOMBRE DLE TITULO
 
 	function getporNombre ( $nombre )  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from categorias_titulos"
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
//FUNCION UPDATE 
 
 	function update ( $categoria,$descripcion,$comprobacion,$id_categoria)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update  categorias_titulos set"
				. " nombre_categoria= %s, descripcion_categoria = %s,   requiere_campo_comparacion_economica = %s WHERE codigo_categoria_titulos = %d",
				$dbconn->quoteString($categoria),
				$dbconn->quoteString($descripcion),
				$dbconn->quoteString($comprobacion),
				$id_categoria);
		$update_result = $dbconn->execute( $query );	
				
		$update_result->close();	
		return true;	
	}	


//FUNCION ELIMINAR TITULO

function delete( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );

		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from categorias_titulos "
		. "where codigo_categoria_titulos = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR TITULOS


function listarCategoria($offset, $limit, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );
	
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  =  "select * from categorias_titulos  where nombre_categoria  LIKE '%".$filtro."%' ORDER BY nombre_categoria ASC LIMIT 0, 10";  
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
			$data[$list_result->value( "codigo_categoria_titulos" )] = array();
			$data[$list_result->value( "codigo_categoria_titulos" )][codigo_categoria_titulos]= $list_result->value( "codigo_categoria_titulos" );
			$data[$list_result->value( "codigo_categoria_titulos" )][nombre_categoria]= $list_result->value( "nombre_categoria" );
			$data[$list_result->value( "codigo_categoria_titulos" )][descripcion_categoria]= $list_result->value( "descripcion_categoria" );
			$data[$list_result->value( "codigo_categoria_titulos" )][requiere_campo_comparacion_economica]= $list_result->value( "requiere_campo_comparacion_economica" );
			}
		$list_result->close();
		
		return $data;
		}	
//FUNCION LISTAR TITULOS 2
function listarCategoria2() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( 'local/configuracion.php' );
	
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from categorias_titulos ORDER BY nombre_categoria ASC");
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_categoria_titulos" )] = array();
		$data[$list_result->value( "codigo_categoria_titulos" )][codigo_categoria_titulos]
		= $list_result->value( "codigo_categoria_titulos" );
		$data[$list_result->value( "codigo_categoria_titulos" )][nombre_categoria]
		= $list_result->value( "nombre_categoria" );
		$data[$list_result->value( "codigo_categoria_titulos" )][descripcion_categoria]
		= $list_result->value( "descripcion_categoria" );
		$data[$list_result->value( "codigo_categoria_titulos" )][requiere_campo_comparacion_economica]
		= $list_result->value( "requiere_campo_comparacion_economica" );
		}
		$list_result->close();
		
		return $data;
		}	
		
//FUNCION INSERTAR TITULO 
function insert($nombre_categoria,$descripcion,$comparacion) {
		
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );

		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into categorias_titulos ( "
		."nombre_categoria ,"
		."descripcion_categoria ,"
		."requiere_campo_comparacion_economica"
		.") values ( "
		. "%s,%s,%s)",
		
		$dbconn->quoteString($nombre_categoria),
		$dbconn->quoteString($descripcion),
		$dbconn->quoteString($comparacion));
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION CONTAR TITULOS PARA PAGINADOR
function contarCategorias() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include( '../local/configuracion.php' );
		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from categorias_titulos " );
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
//LISTAR CATEGORIAS PARA COMPARACION
function listarCategoria_comparacion() 
	{
//		ini_set("include_path", ".;$_SERVER[DOCUMENT_ROOT]/");
		include( '../../../../local/configuracion.php' );
		$data = array();
		$con = mysql_connect("localhost", "presenci_cccol", "Cccol2MP42qi"); 
		mysql_select_db("presenci_cccol", $con); 
		
		$list_sql  =  "select codigo_categoria_titulos,nombre_categoria, descripcion_categoria from categorias_titulos order by nombre_categoria ASC";
		$list_result	 = mysql_query($list_sql); 
		$i=0;
		while ($fila = mysql_fetch_assoc($list_result))
			{
			$i=$i+1;
    		$data[$i][codigo_categoria_titulos]= $fila["codigo_categoria_titulos"];
			$data[$i][nombre_categoria]=$fila["nombre_categoria"];
			$data[$i][descripcion_categoria]=$fila["descripcion_categoria"];
			}
		mysql_free_result($list_result);
  		return $data;
	}
//FUNCION ASGINAR REGISTROS
function assignRecord( $user_record ) {

$this->codigo_categoria_titulos					= $user_record->value('codigo_categoria_titulos');
$this->descripcion_categoria					= $user_record->value('descripcion_categoria');
$this->requiere_campo_comparacion_economica		= $user_record->value('requiere_campo_comparacion_economica');
$this->nombre_categoria							= $user_record->value('nombre_categoria');
$this->Total									= $user_record->value('Total');
}	

}

if( ! isset($categorias_titulos)) { 
	global $categorias_titulos;
	$categorias_titulos = New CATEGORIA_TITULO();
}
?>