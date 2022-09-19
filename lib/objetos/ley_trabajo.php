<? 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# Clase= Ley_trabajo
# Descripcion: Clase que contiene operaciones con la tabla Ley_trabajo
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_LEY_TRABAJO' )) {
	return;
}

define( '_LEY_TRABAJO', '1.0' );

class Ley_trabajo {

//FUNCION PARA OBTENER EL NOMBRE DEL PDF ASOCIADO A LA LOT
function obtenerpdf ()  
	{
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query = sprintf("Select pdf_asociado from ley_trabajo");
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) 
			{
				$this->asignarRegistro( $query_result );	
				return true;
			} 
		else 
			{
				return false;
			}
	}


//FUNCION QUE DEVUELVE UN ARTICULO DE LA LEY
 
 	function getArticuloLey ( $nro_articulo )  
		{
			if( $nro_articulo== "" || $nro_articulo== 0) return false;
			include( '../local/configuracion.php' );
			$dbconn = $LibSite->openDBConn(0);	
			$query = sprintf("Select * from articulos_ley_trabajo"
			. " where nro_articulo = %d",
			$nro_articulo);



print $query;
			$query_result = $dbconn->execute( $query );		
			if( $query_result->realcount() == 1 ) 
				{
					$this->asignarRegistro2( $query_result );	
					return true;
				} 
			else 
				{
					return false;
				}
		}



//FUNCION QUE DEVUELVE EL CONTENIDO DE LA LEY
 	function get_Ley_Trabajo ()  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from ley_trabajo");

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
 

//FUNCION UPDATE DE LOS ARTICULOS
 
 	function update ( $nro_articulo1,$titulo_articulo, $texto_completo_articulos, $resumen_articulo, $codigo_titulo_comparativo, $campo_comparativo,$nro_articulo)  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update articulos_ley_trabajo set "
				."nro_articulo = %d, "
				."titulo_articulo = %s,"
				."texto_completo_articulos = %s, "
				."resumen_articulo = %s, "
				."codigo_titulo_comparativo = %d, "
				."campo_comparativo = %s "
				." WHERE nro_articulo = %d",
		
		$nro_articulo1,
		$dbconn->quoteString($titulo_articulo),
		$dbconn->quoteString($texto_completo_articulos),
		$dbconn->quoteString($resumen_articulo),
		$codigo_titulo_comparativo,
		$dbconn->quoteString($campo_comparativo),
		$nro_articulo);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
//FUNCION UPDATE LEY
function updateLey_trabajo ( $fecha_publicacion, $pdf_asociar, $descripcion_resumen, $pdf_asociado)  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update ley_trabajo set "
				."fecha_publicacion = %s, "
				."pdf_asociado = %s, "
				."descripcion_resumen = %s "
				." WHERE pdf_asociado = %s",
		
		$dbconn->quoteString($fecha_publicacion),
		$dbconn->quoteString($pdf_asociar),
		$dbconn->quoteString($descripcion_resumen),
		$dbconn->quoteString($pdf_asociado));
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
	

//ACTUALIZAR NRO DE ARTICULOS DE LEY
	
function update_nro_articulos ( $contador, $total_articulos)  {

		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update ley_trabajo set "
				."total_articulos = %d "
				." WHERE total_articulos = %d",
		
		$contador,
		$total_articulos);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
//ELIMINAR ARTICULOS DE LA LEY

function delete( $id ) {
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from articulos_ley_trabajo "
		. "where nro_articulo = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR ARTICULOS


function listarArticulos( $ini , $fin, $filtro, $tipobusqueda) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		if ( $tipobusqueda==2 )
			{
				$list_sql  =  "select * from articulos_ley_trabajo where nro_articulo =".$filtro."";

			}
		else
			{
				$list_sql  =  "select * from articulos_ley_trabajo where titulo_articulo like '%".$filtro."%' ORDER BY  nro_articulo ASC LIMIT $ini , $fin ";	
			}
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "nro_articulo" )] = array();
		$data[$list_result->value( "nro_articulo" )][nro_articulo]
		= $list_result->value( "nro_articulo" );
		$data[$list_result->value( "nro_articulo" )][texto_completo_articulos]
		= $list_result->value( "texto_completo_articulos" );
		$data[$list_result->value( "nro_articulo" )][titulo_articulo]
		= $list_result->value( "titulo_articulo" );
		$data[$list_result->value( "nro_articulo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "nro_articulo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "nro_articulo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		}
		$list_result->close();
		
		return $data;
		}	
		
//FUNCION INSERTAR ARTICULOS A LEY
function insertarArticulo($nro_articulo, $titulo_articulo, $texto_completo_articulos, $resumen_articulo, $codigo_titulo_comparativo, $campo_comparativo ) {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into articulos_ley_trabajo ( "
		."nro_articulo, "
		."texto_completo_articulos, "
		."titulo_articulo, "
		."resumen_articulo, "
		."codigo_titulo_comparativo, "
		."campo_comparativo "
		.") values ( "
		. "%d, %s, %s, %s, %d, %s)",
		$nro_articulo,
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
		
//FUNCION CONTAR ARTICULOS PARA PAGINADOR
function contarArticulos() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from articulos_ley_trabajo " );
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


//FUNCION ASIGNAR REGISTROS DE ARTICULOS
function asignarRegistro2 ( $ley_record ) {
	$this->nro_articulo					= $ley_record->value('nro_articulo');
	$this->titulo_articulo						= $ley_record->value('titulo_articulo');
	$this->texto_completo_articulo		= $ley_record->value('texto_completo_articulos');
	$this->resumen_articulo				= $ley_record->value('resumen_articulo');
	$this->codigo_titulo_comparativo	= $ley_record->value('codigo_titulo_comparativo');
	$this->campo_comparativo			= $ley_record->value('campo_comparativo');
	$this->Total						= $ley_record->value('Total');
}
//FUNCION ASIGNAR REGISTROS DE LEY
function asignarRegistro( $ley_record ) {

	$this->fecha_publicacion			= $ley_record->value('fecha_publicacion');
	$this->pdf_asociado					= $ley_record->value('pdf_asociado');
	$this->descripcion_resumen			= $ley_record->value('descripcion_resumen');
	$this->total_articulos				= $ley_record->value('total_articulos');
	$this->Total						= $ley_record->value('Total');
}	


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

if( ! isset($ley_trabajo)) { 
	global $ley_trabajo;
	$ley_trabajo = New Ley_trabajo();
}
?>