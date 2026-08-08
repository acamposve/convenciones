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




if( defined( '_ACTIVIDADES' )) {

	return;

}



define( '_ACTIVIDADES', '1.0' );



class ACTIVIDADES {



//FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI LA ACTIVIDAD EXISTE

 

function getId ( $id )  {



		if( $id== "" || $id== 0) return false;



		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		

		$dbconn = $LibSite->openDBConn(0);	

		

		$query = sprintf("Select * from actividad_empresa"

				. " where codigo_actividad = %d",

				$id);

		$query_result = $dbconn->execute( $query );		

		if( $query_result->realcount() == 1 ) {

			

			$this->assignRecord( $query_result );	

			return true;

		} else {

			

			return false;

		}

		

	}

 

 
//FUNCION QUE DEVUELVE UNA ACTIVIDAD ESPECIFICA
 function traerActividad ( $id )  {



		if( $id== "" || $id== 0) return false;



		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		

		$dbconn = $LibSite->openDBConn(0);	

		

		$query = sprintf("Select * from actividad_empresa"

				. " where codigo_actividad = %d",

				$id);

		$query_result = $dbconn->execute( $query );		

		if( $query_result->realcount() == 1 ) {

			

			return $query_result->value('nombre_actividad');;

		} else {

			

			return false;

		}

		

	}

	

 //FUNCION GET QUE BUSCA EN LA BASE DE DATOS LA ACTIVIDAD POR NOMBRE

function getporNombre ( $nombre )  {



		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		

		$dbconn = $LibSite->openDBConn(0);	

		

		$query = sprintf("Select * from actividad_empresa"

				. " where nombre_actividad LIKE  %s",

				$dbconn->quoteString($nombre));



		$query_result = $dbconn->execute( $query );	

		if( $query_result->realcount() == 1 ) {

			

			$this->assignRecord( $query_result );	

			return true;

		} else {

			

			return false;

		}

		

	}

//FUNCION UPDATE ACTIVIDAD
function update ( $nombre ,$descripcion, $id_actividad)  {



		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		

		$dbconn = $LibSite->openDBConn(0);	

		

		$query = sprintf("Update  actividad_empresa set"

				. " nombre_actividad= %s, descripcion_actividad = %s WHERE codigo_actividad = %d",

				$dbconn->quoteString($nombre),

				$dbconn->quoteString($descripcion),

				$id_actividad);

		$update_result = $dbconn->execute( $query );	

				

		$update_result->close();	

		return true;	

	}	





//ELIMINAR ACTIVAD DE LA BD



function delete( $id ) {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		

		

		$dbconn = $LibSite->openDBConn(0);	

		

		$delete_sql  = sprintf( "delete from actividad_empresa "

		. "where codigo_actividad = %d",

		$id);

		

		$delete_result = $dbconn->execute( $delete_sql );		

		

		$delete_result->close();

		return true;

 	

		}	

//FUNCION LISTAR ACTIVIDADES

function listarActividades($offset, $limit, $filtro ) {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		$data = array();

		$dbconn = $LibSite->openDBConn(0);

		

		$list_sql  =  "SELECT * FROM actividad_empresa where nombre_actividad LIKE '%".$filtro."%' ORDER BY `nombre_actividad` ASC LIMIT  $offset, $limit ";

		$list_result = $dbconn->execute( $list_sql );	

		reset($list_result);

		while( $list_result->next() ) {

		$data[$list_result->value( "codigo_actividad" )] = array();

		$data[$list_result->value( "codigo_actividad" )][codigo_actividad]

		= $list_result->value( "codigo_actividad" );

		$data[$list_result->value( "codigo_actividad" )][nombre_actividad]

		= $list_result->value( "nombre_actividad" );

		$data[$list_result->value( "codigo_actividad" )][descripcion_actividad]

		= $list_result->value( "descripcion_actividad" );



		}

		$list_result->close();

		

		return $data;

		}	

//LISTAR ACTIVIDADES 2

function listarActividades2() {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		$data = array();

		$dbconn = $LibSite->openDBConn(0);

		

		$list_sql  = sprintf( "select * from actividad_empresa ");

		$list_result = $dbconn->execute( $list_sql );	

		reset($list_result);

		while( $list_result->next() ) {

		$data[$list_result->value( "codigo_actividad" )] = array();

		$data[$list_result->value( "codigo_actividad" )][codigo_actividad]

		= $list_result->value( "codigo_actividad" );

		$data[$list_result->value( "codigo_actividad" )][nombre_actividad]

		= $list_result->value( "nombre_actividad" );

		$data[$list_result->value( "codigo_actividad" )][descripcion_actividad]

		= $list_result->value( "descripcion_actividad" );



		}

		$list_result->close();

		

		return $data;

		}			

//LISTAR ACTIVIDADES PARA COMPARATIVO
function listarActividades_Comparativo() {
		include( '../../../../local/configuracion.php' );
		$con = mysql_connect("localhost", "root", "vertrigo"); 
		mysql_select_db("CCCOL", $con); 

		$list_sql  = sprintf( "select * from actividad_empresa ");
		$list_result	 = mysql_query($list_sql); 
		reset($list_result);
		while ($fila = mysql_fetch_assoc($list_result)){
			$x=$x+1;
    		$data[$x][codigo_actividad]= $fila["codigo_actividad"];
			$data[$x][nombre_actividad]= $fila["nombre_actividad"];
			$data[$x][descripcion_actividad]= $fila["descripcion_actividad"];
		}
		mysql_free_result($list_result);			
  		return $data;
		}			



//INSERTAR ACTIVIDADES

function insert($nombre,$descripcion) {

		

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		

		$dbconn = $LibSite->openDBConn(0);	

		

		$insert_sql = sprintf( "insert into actividad_empresa ( "

		."nombre_actividad ,"

		."descripcion_actividad"

		.") values ( "

		. "%s,%s)",

		

		$dbconn->quoteString($nombre),

		$dbconn->quoteString($descripcion));

		$insert_result = $dbconn->execute( $insert_sql );

		$insert_result->close();

		return true;

		}	

		

	//CONTAR ACTIVIDADES PAGINADOR

	function contarActividades() {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");

		include( 'local/configuracion.php' );

		$data = array();

		$dbconn = $LibSite->openDBConn(0);

		

		$list_sql  = sprintf( "select count(*) as Total from actividad_empresa " );

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

//ASGINACION DE REGISTRO

function assignRecord( $user_record ) {



$this->codigo_actividad			= $user_record->value('codigo_actividad');

$this->nombre_actividad			= $user_record->value('nombre_actividad');

$this->descripcion_actividad	= $user_record->value('descripcion_actividad');

$this->Total					= $user_record->value('Total');

}	



}



if( ! isset($actividades)) { 

	global $actividades;

	$actividades = New ACTIVIDADES();

}

?>