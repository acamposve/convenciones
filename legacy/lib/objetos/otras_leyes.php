<? 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# Clase= Otras_leyes
# Descripcion: Clase que contiene operaciones con la tabla Otras_leyes
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_OTRAS_LEYES' )) {
	return;
}

define( '_OTRAS_LEYES', '1.0' );

class Otras_Leyes {

//FUNCION  QUE DEVUELVE UN ARTICULO DE LA LEY ESPECIFICA 
 	function getArticuloLey ( $nro_articulo, $codigo_ley )  {

		if( $nro_articulo== "" || $nro_articulo== 0) return false;

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from articulos_otras_leyes"
				. " where codigo_articulo = %d AND codigo_otra_ley = %d",
				$nro_articulo, $codigo_ley);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro2( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
	
//FUNCION  QUE DEVUELVE UN ARTICULO DE LA LEY ESPECIFICA 
 	function getArticuloLey2 ( $nro_articulo, $codigo_ley )  {

		if( $nro_articulo== "" || $nro_articulo== 0) return false;

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from articulos_otras_leyes"
				. " where nro_articulo = %d AND codigo_otra_ley = %d",
				$nro_articulo, $codigo_ley);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro2( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
//FUNCION DEVUELVE EL CONTENIDO DE UNA LEY ESPECIFICA
 	function get_Ley ($id)  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from otras_leyes WHERE codigo_otra_ley = %d", $id);
		$query_result = $dbconn->execute( $query );	
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
 

//FUNCION  QUE DEVUELVE ULTIMA LEY INSTERTADA

 
 	function obtenerUltimaLey ()  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from otras_leyes"
				. " ORDER BY codigo_otra_ley DESC LIMIT 0,1");

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}

//FUNCION  UPDATE ARTICULOS DE LEYES

 
 	function actualizarArticulos ( $codigo_articulo,$nro_articulo, $titulo_articulo, $texto_completo_articulo, $resumen_articulo,$codigo_titulo_comparativo, $campo_comparativo )  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update articulos_otras_leyes set "
				."codigo_articulo = %d, "
				."nro_articulo = %s, "
				."titulo_articulo = %s,"
				."texto_completo_articulo = %s, "
				."resumen_articulo = %s, "
				."codigo_titulo_comparativo = %d, "
				."campo_comparativo = %s "
				." WHERE codigo_articulo = %d",
				$codigo_articulo,
				$dbconn->quoteString($nro_articulo),
				$dbconn->quoteString($titulo_articulo),
				$dbconn->quoteString($texto_completo_articulo), 
				$dbconn->quoteString($resumen_articulo),
				$codigo_titulo_comparativo, 
				$dbconn->quoteString($campo_comparativo),
				$codigo_articulo);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
//FUNCION  LEYES
function actualizarOtrasLeyes ( $nombre_ley, $fecha_publicacion, $pdf_asociado, $descripcion_resumen,$codigo_ley, $origen)  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update otras_leyes set "
				."nombre_ley = %s, "
				."fecha_publicacion_ley = %s, "
				."pdf_ley = %s, "
				."descripcion_ley = %s, "
				."origen = %s "
				." WHERE codigo_otra_ley = %d",
		
		$dbconn->quoteString($nombre_ley),
		$dbconn->quoteString($fecha_publicacion),
		$dbconn->quoteString($pdf_asociado),
		$dbconn->quoteString($descripcion_resumen),
		$dbconn->quoteString($origen),
		$codigo_ley);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
	
//ACTULIZAR  NRO DE ARTICULOS EN LAY
	
function update_nro_articulos ($total_articulos, $codigo_otra_ley)  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update otras_leyes set "
				."total_articulos_ley = %d "
				." WHERE codigo_otra_ley= %d",
		
		$total_articulos,
		$codigo_otra_ley);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	

//ELIMINAR UN ARTICULO

function borrarArticulos( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from articulos_otras_leyes "
		. "where codigo_articulo = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	


//FUNCION ELIMINAR LEY

function borrarLey( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from otras_leyes "
		. "where codigo_otra_ley = %d",
		$id);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}
		
//FUNCION ARTICULOS DE OTRA LEY

function borrarLey_articulos( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from articulos_otras_leyes "
		. "where codigo_otra_ley = %d",
		$id);
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}			
				
//FUNCION LISTAR ARTICULOS


function listarArticulos( $ini , $fin , $codigo_ley, $filtro) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = "select * from articulos_otras_leyes WHERE codigo_otra_ley = ".$codigo_ley." and titulo_articulo like '%".$filtro."%' ORDER BY  nro_articulo ASC LIMIT ".$ini." , ".$fin."" ;
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_articulo" )] = array();
		$data[$list_result->value( "codigo_articulo" )][codigo_articulo]
		= $list_result->value( "codigo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][nro_articulo]
		= $list_result->value( "nro_articulo" );
		$data[$list_result->value( "codigo_articulo" )][texto_completo_articulos]
		= $list_result->value( "texto_completo_articulos" );
		$data[$list_result->value( "codigo_articulo" )][titulo_articulo]
		= $list_result->value( "titulo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		}
		$list_result->close();
		
		return $data;
		}	

//BUSCAR LEY ESPECIFICA

function leyespecifica( $codigo_ley) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from otras_leyes  where codigo_otra_ley =".$codigo_ley."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_otra_ley" )] = array();
		$data[$list_result->value( "codigo_otra_ley" )][codigo_otra_ley]
		= $list_result->value( "codigo_otra_ley" );
		$data[$list_result->value( "codigo_otra_ley" )][nombre_ley]
		= $list_result->value( "nombre_ley" );
		$data[$list_result->value( "codigo_otra_ley" )][pdf_ley]
		= $list_result->value( "pdf_ley" );		
		}
		$list_result->close();
		
		return $data;
		}
	
	
//OBTENER LEY POR ID
function obtenerLeysPorId ($codigo_ley)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from otras_leyes WHERE codigo_otra_ley = ".$codigo_ley."");
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
		
//FUNCION LISTAR LEYES


function listarLeyes( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = "select * from otras_leyes  where nombre_ley like '%".$filtro."%' ORDER BY  nombre_ley ASC LIMIT $ini , $fin ";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_otra_ley" )] = array();
		$data[$list_result->value( "codigo_otra_ley" )][codigo_otra_ley]
		= $list_result->value( "codigo_otra_ley" );
		$data[$list_result->value( "codigo_otra_ley" )][nombre_ley]
		= $list_result->value( "nombre_ley" );
		$data[$list_result->value( "codigo_otra_ley" )][fecha_publicacion_ley]
		= $list_result->value( "fecha_publicacion_ley" );
		$data[$list_result->value( "codigo_otra_ley" )][descripcion_ley]
		= $list_result->value( "descripcion_ley" );
		$data[$list_result->value( "codigo_otra_ley" )][pdf_ley]
		= $list_result->value( "pdf_ley" );
		$data[$list_result->value( "codigo_otra_ley" )][origen]
		= $list_result->value( "origen" );
		}
		$list_result->close();
		
		return $data;
		}	

//FUNCION  INSERTA ARTICULO EN LEY
function insertarArticulo( $codigo_ley, $nro_articulo, $titulo_articulo, $texto_completo_articulos, $resumen_articulo, $codigo_titulo_comparativo, $campo_comparativo ) {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into articulos_otras_leyes ( "
		."codigo_otra_ley, "
		."nro_articulo, "
		."texto_completo_articulo, "
		."titulo_articulo, "
		."resumen_articulo, "
		."codigo_titulo_comparativo, "
		."campo_comparativo "
		.") values ( "
		. "%d,%s, %s, %s, %s, %d, %s)",
		$codigo_ley,
		$dbconn->quoteString($nro_articulo),
		$dbconn->quoteString($texto_completo_articulos),
		$dbconn->quoteString($titulo_articulo),
		$dbconn->quoteString($resumen_articulo),
		$codigo_titulo_comparativo,
		$dbconn->quoteString($campo_comparativo)
		);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION  INSERT LEY 
function insertarLey($nombre_ley, $fecha_publicacion, $resumen_ley, $pdf_ley,$origen ) {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = "insert into otras_leyes ( nombre_ley, fecha_publicacion_ley, descripcion_ley, pdf_ley, origen ) values ( '".$nombre_ley."','" .$fecha_publicacion."',
						'".$resumen_ley."','".$pdf_ley."', '".$origen."')";
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION  CONTAR ARTICULOS PARA PAGINADOR 
function contarArticulos($codigo_ley) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$query  = sprintf( "select count(*) as Total from articulos_otras_leyes WHERE codigo_otra_ley = %d ",$codigo_ley );
		$list_result = $dbconn->execute( $query );	
		if( $list_result->realcount() == 1 ) {
		
			$this->asignarRegistro2($list_result);
			$list_result->close();
			return true;
		} else {
			$list_result->close();
			return false;
		}
		}	

//FUNCION  CONTAR LEYES PARA PAGINADOR 
function contarLeyes() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from otras_leyes " );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
		
			$this->asignarRegistro2($list_result);
			$list_result->close();
			return true;
		} else {
			$list_result->close();
			return false;
		}
		}	

//FUNCION  ASIGNAR REGISTROS DE ARTICULOS
function asignarRegistro2 ( $ley_record ) {
	$this->codigo_articulo				= $ley_record->value('codigo_articulo');
	$this->codigo_otra_ley				= $ley_record->value('codigo_otra_ley');
	$this->nro_articulo					= $ley_record->value('nro_articulo');
	$this->texto_completo_articulo		= $ley_record->value('texto_completo_articulo');
	$this->resumen_articulo				= $ley_record->value('resumen_articulo');
	$this->titulo_articulo				= $ley_record->value('titulo_articulo');
	$this->codigo_titulo_comparativo	= $ley_record->value('codigo_titulo_comparativo');
	$this->campo_comparativo			= $ley_record->value('campo_comparativo');
	$this->Total						= $ley_record->value('Total');
}

//FUNCION  ASIGNAR REGISTROS DE LEY
function asignarRegistro( $ley_record ) {

	$this->codigo_otra_ley				= $ley_record->value('codigo_otra_ley');
	$this->nombre_ley					= $ley_record->value('nombre_ley');
	$this->fecha_publicacion_ley		= $ley_record->value('fecha_publicacion_ley');
	$this->descripcion_ley				= $ley_record->value('descripcion_ley');
	$this->pdf_ley						= $ley_record->value('pdf_ley');
	$this->total_articulos_ley			= $ley_record->value('total_articulos_ley');
	$this->origen						= $ley_record->value('origen');
	$this->Total						= $ley_record->value('Total');
	
}			

}

if( ! isset($otras_leyes)) { 
	global $otras_leyes;
	$otras_leyes = New Otras_leyes();
}
?>