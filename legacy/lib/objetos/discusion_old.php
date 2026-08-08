<? 
#======================================================================================
# Clase= Discusion
# Descripcion: Clase que contiene operaciones con la tabla Discusion
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_DISCUSION' )) {
	return;
}

define( '_DISCUSION', '1.0' );

class Discusion {

function obtenerDiscusionesPorId ($codigo_bitacora)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from bitacoras WHERE codigo_bitacora = ".$codigo_bitacora."");
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
function obtenerPeticionesPorId ($codigo_peticiones)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from peticiones_sindicato WHERE codigo_peticion = ".$codigo_peticiones."");
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro3( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}

function eliminarReunion ($codigo_reunion)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("DELETE from reuniones WHERE codigo_reunion = ".$codigo_reunion."");
		$query_result = $dbconn->execute( $query );		

			return true;
		
	}
	
function obtenerOfertasPorIdPeticion ($codigo_peticiones)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from ofertas_patrono WHERE codigo_peticion = ".$codigo_peticiones."");
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			$this->asignarRegistro4( $query_result );	
			return true;
		} else {
			unset($query_result);
			return false;
		}
		
	}
	

function obtenerOfertasPorId ($codigo_ofertas)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from ofertas_patrono WHERE codigo_ofertas = ".$codigo_ofertas."");
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			$this->limpiarRegistro4(  );
			$this->asignarRegistro4( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
#ELIMINAR UN CONTRATO 

function eliminarDiscusion( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from bitacoras "
		. "where codigo_bitacora = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
		
#ELIMINAR UNA OFERTA 

function eliminarOfertaDiscusion( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from ofertas_patrono "
		. "where codigo_ofertas= %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
		}
		
#ELIMINAR UNA PETICION 

function eliminarPeticionDiscusion( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from peticiones_sindicato "
		. "where codigo_peticion= %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
		
function actualizarReuniones($fecha_def,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes, $codigo_reunion)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update reuniones set "
				."`fecha_reunion` = %s ,"
				."`hora_reunion` = %s ,"
				."`duracion_reunion` = %s ,"
				."`resumen_reunion` = %s ,"
				."`detalles_reunion` = %s ,"
				."`asistentes_reunion` = %s "
				." WHERE codigo_reunion = %d",
		
		$dbconn->quoteString($fecha_def),
		$dbconn->quoteString($Hora),
		$dbconn->quoteString($Duracion),
		$dbconn->quoteString($Resumen),
		$dbconn->quoteString($Detalle),
		$dbconn->quoteString($Asistentes),
		$codigo_reunion);

		$update_result=$dbconn->execute($query);	
		$update_result->close();	
		return true;	
	}	
		
function actualizarDiscusiones($representanteEm,$representanteSn,$representanteMt,$telefonoEm,$telefonoSn,$telefonoMt,$emailEm,$emailSn,$emailMt,$cargoEm,$cargoSn,$cargoMt,$peticiones,$oferta,$empresa,$inicio,$codigo_bitacora)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update bitacoras set "
				."`codigo_empresa` = %d ,"
				."`fecha_inicio_disc` = %s ,"
				."`representante_empresa` = %s ,"
				."`telefono_representante_empresa` = %s ,"
				."`email_representante_empresa` = %s ,"
				."`cargo_representante_empresa` = %s ,"
				."`representante_sindicato` = %s ,"
				."`telefono_representante_sindicato` = %s ,"
				."`email_representante_sindicato` = %s ,"
				."`cargo_representante_sindicato` = %s ,"
				."`representante_min_trab` = %s ,"
				."`telefono_representante_min_trab` = %s ,"
				."`email_representante_min_trab` = %s ,"
				."`cargo_representante_min_trab` = %s ,"
				."`pdf_peticiones_sindicato` = %s ,"
				."`pdf_ofertas_patrono` = %s "
				." WHERE codigo_bitacora = %d",
		
		$empresa,
		$dbconn->quoteString($inicio),
		$dbconn->quoteString($representanteEm),
		$dbconn->quoteString($telefonoEm),
		$dbconn->quoteString($emailEm),
		$dbconn->quoteString($cargoEm),
		$dbconn->quoteString($representanteSn),
		$dbconn->quoteString($telefonoSn),
		$dbconn->quoteString($emailSn),
		$dbconn->quoteString($cargoSn),
		$dbconn->quoteString($representanteMt),
		$dbconn->quoteString($telefonoMt),
		$dbconn->quoteString($emailMt),
		$dbconn->quoteString($cargoMt),
		$dbconn->quoteString($peticiones),
		$dbconn->quoteString($oferta),
		$codigo_bitacora);
		$update_result=$dbconn->execute($query);	
		$update_result->close();	
		return true;	
	}	
	
function actualizarOfertasDiscusiones($codigo_discusion, $nroOferta, $oferta,$peticion,$titulo,$tituloOferta, $codigoOferta)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update ofertas_patrono set "
				."`nro_oferta` = %d ,"
				."`texto_completo_oferta` = %s ,"
				."`codigo_titulo_comparativo` = %s ,"
				."`codigo_peticion` = %s ,"
				."`titulo_oferta` = %s "
				." WHERE codigo_ofertas= %d",
		
		$nroOferta,
		$dbconn->quoteString($oferta),
		$dbconn->quoteString($titulo),
		$dbconn->quoteString($peticion),
		$dbconn->quoteString($tituloOferta),
		$codigoOferta);
		$update_result=$dbconn->execute($query);	
		$update_result->close();	
		return true;	
	}	
	
function actualizarPeticionesDiscusiones($codigo_bitacora, $nroPeticion, $textoPeticion,$tituloPeticion,$titulo, $codigoPeticion)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update peticiones_sindicato set "
				."`nro_peticion` = %d ,"
				."`texto_completo_peticion` = %s ,"
				."`codigo_titulo_comparativo` = %d ,"
				."`titulo_peticion` = %s "
				." WHERE codigo_peticion= %d",
		
		$nroPeticion,
		$dbconn->quoteString($textoPeticion),
		$titulo,
		$dbconn->quoteString($tituloPeticion),
		$codigoPeticion);
		$update_result=$dbconn->execute($query);	
		$update_result->close();	
		return true;	
	}	

function insertarDiscusion($representanteEm,$representanteSn,$representanteMt,$telefonoEm,$telefonoSn,$telefonoMt,$emailEm,$emailSn,$emailMt,$cargoEm,$cargoSn,$cargoMt,$peticiones,$oferta,$empresa,$inicio)
{

ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into bitacoras ( "
		."`codigo_empresa` ,"
		."`fecha_inicio_disc` ,"
		."`representante_empresa` ,"
		."`telefono_representante_empresa` ,"
		."`email_representante_empresa` ,"
		."`cargo_representante_empresa` ,"
		."`representante_sindicato` ,"
		."`telefono_representante_sindicato` ,"
		."`email_representante_sindicato` ,"
		."`cargo_representante_sindicato` ,"
		."`representante_min_trab` ,"
		."`telefono_representante_min_trab` ,"
		."`email_representante_min_trab` ,"
		."`cargo_representante_min_trab` ,"
		."`pdf_peticiones_sindicato` ,"
		."`pdf_ofertas_patrono` "
		.") values ( "
		. "%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
		$empresa,
		$dbconn->quoteString($inicio),
		$dbconn->quoteString($representanteEm),
		$dbconn->quoteString($telefonoEm),
		$dbconn->quoteString($emailEm),
		$dbconn->quoteString($cargoEm),
		$dbconn->quoteString($representanteSn),
		$dbconn->quoteString($telefonoSn),
		$dbconn->quoteString($emailSn),
		$dbconn->quoteString($cargoSn),
		$dbconn->quoteString($representanteMt),
		$dbconn->quoteString($telefonoMt),
		$dbconn->quoteString($emailMt),
		$dbconn->quoteString($cargoMt),
		$dbconn->quoteString($peticiones),
		$dbconn->quoteString($oferta));
		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}

function Reunion($Fecha,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes,$id_bitacora)
{
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into reuniones ( "
		."`codigo_bitacora` ,"
		."`fecha_reunion` ,"
		."`hora_reunion` ,"
		."`duracion_reunion` ,"
		."`resumen_reunion` ,"
		."`detalles_reunion` ,"
		."`asistentes_reunion` "
		.") values ( "
		. "%d, %s, %s, %s, %s, %s, %s)",
		$id_bitacora,
		$dbconn->quoteString($Fecha),
		$dbconn->quoteString($Hora),
		$dbconn->quoteString($Duracion),
		$dbconn->quoteString($Resumen),
		$dbconn->quoteString($Detalle),
		$dbconn->quoteString($Asistentes));
		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}

function agregarReunion($Fecha,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes,$id_bitacora)
{
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into reuniones ( "
		."`codigo_bitacora` ,"
		."`fecha_reunion` ,"
		."`hora_reunion` ,"
		."`duracion_reunion` ,"
		."`resumen_reunion` ,"
		."`detalles_reunion` ,"
		."`asistentes_reunion` "
		.") values ( "
		. "%d, %s, %s, %s, %s, %s, %s)",
		$id_bitacora,
		$dbconn->quoteString($Fecha),
		$dbconn->quoteString($Hora),
		$dbconn->quoteString($Duracion),
		$dbconn->quoteString($Resumen),
		$dbconn->quoteString($Detalle),
		$dbconn->quoteString($Asistentes));
		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}

function agregarAcuerdo($peticion,$fechaAcuerdo,$NroArticulo,$resumenArticulo,$textoCompleto,$titulo,$campoComparativo,$estatusAcuerdo,$id_bitacora)
{
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into acuerdos ( "
		."`codigo_bitacora` ,"
		."`codigo_peticion` ,"
		."`fecha_acuerdo` ,"
		."`nro_articulo` ,"
		."`texto_completo_articulo` ,"
		."`resumen_articulo` ,"
		."`codigo_titulo_comparativo` ,"
		."`campo_comparativo` ,"
		."`estatus_acuerdo` "
		.") values ( "
		. "%d, %d, %s, %s, %s, %s, %d, %s, %d)",
		$id_bitacora,
		$peticion,
		$dbconn->quoteString($fechaAcuerdo),
		$dbconn->quoteString($NroArticulo),
		$dbconn->quoteString($textoCompleto),
		$dbconn->quoteString($resumenArticulo),
		$titulo,
		$dbconn->quoteString($campoComparativo),
		$estatusAcuerdo);
		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}

function insertarPeticionDiscusion($codigo_discusion, $nroPeticion, $textoPeticion, $titulo,$tituloPeticion)
{

ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into peticiones_sindicato ( "
		."`codigo_bitacora` ,"
		."`nro_peticion` ,"
		."`texto_completo_peticion` ,"
		."`codigo_titulo_comparativo`, "
		."`titulo_peticion`"
		.") values ( "
		. "%d, %s, %s, %s, %s)",
		$codigo_discusion,
		$dbconn->quoteString($nroPeticion),
		$dbconn->quoteString($textoPeticion),
		$dbconn->quoteString($titulo),
		$dbconn->quoteString($tituloPeticion));
		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}

function insertarOferta($codigo_discusion, $nroOferta, $oferta,$peticion,$titulo, $tituloOferta)
{

ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into ofertas_patrono ( "
		."`codigo_bitacora` ,"
		."`nro_oferta` ,"
		."`texto_completo_oferta` ,"
		."`codigo_titulo_comparativo`, "
		."`codigo_peticion`, "
		."`titulo_oferta`"
		.") values ( "
		. "%d, %s, %s, %s, %s, %s)",
		$codigo_discusion,
		$dbconn->quoteString($nroOferta),
		$dbconn->quoteString($oferta),
		$dbconn->quoteString($titulo),
		$dbconn->quoteString($peticion),
		$dbconn->quoteString($tituloOferta));
		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}

 function contarDiscusiones() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from bitacoras " );
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
		
 function contarReuniones($id_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from reuniones WHERE codigo_bitacora = ".$codigo_bitacora." group by fecha_reunion " );
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
		
 function contarPeticiones($codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from peticiones_sindicato WHERE  codigo_bitacora = ".$codigo_bitacora." " );
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
		
 function contarOfertas($codigo_ofertas) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from ofertas_patrono WHERE  codigo_bitacora = ".$codigo_ofertas." " );
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


function listarPeticionesSelect($codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from peticiones_sindicato WHERE codigo_bitacora = ".$codigo_bitacora." ORDER BY  nro_peticion ASC");
		//print $list_sql;
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_peticion" )] = array();
		$data[$list_result->value( "codigo_peticion" )][codigo_peticion]
		= $list_result->value( "codigo_peticion" );
		$data[$list_result->value( "codigo_peticion" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_peticion" )][nro_peticion]
		= $list_result->value( "nro_peticion" );
		$data[$list_result->value( "codigo_peticion" )][texto_completo_peticion]
		= $list_result->value( "texto_completo_peticion" );
		$data[$list_result->value( "codigo_peticion" )][estatus_peticion]
		= $list_result->value( "estatus_peticion" );
		$data[$list_result->value( "codigo_peticion" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_peticion" )][titulo_peticion]
		= $list_result->value( "titulo_peticion" );
		}
		$list_result->close();
		
		return $data;
		}	


//funcion para buscar el acuerdo de una oferta

function buscarAcuerdo($fecha_reunion,$codigo_peticion,$codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from acuerdos WHERE codigo_bitacora = ".$codigo_bitacora." and codigo_peticion = ".$codigo_peticion." and fecha_acuerdo ='".$fecha_reunion."'");
		//print $list_sql;
		$list_result = $dbconn->execute( $list_sql );
		
		if( $list_result->realcount() > 0 ) {
			$this->asignarRegistro5($list_result);
			$list_result->close();
			return true;
		} else {
			$this->limpiarRegistro5();
			$list_result->close();
			return false;
		}

		$list_result->close();
		
		return $data;
		}	
		
function listarReunionesSelect($codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from reuniones WHERE codigo_bitacora = ".$codigo_bitacora." ORDER BY  fecha_reunion ASC");
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_reunion" )] = array();
		$data[$list_result->value( "codigo_reunion" )][codigo_reunion]
		= $list_result->value( "codigo_reunion" );
		$data[$list_result->value( "codigo_reunion" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_reunion" )][fecha_reunion]
		= $list_result->value( "fecha_reunion" );
		$data[$list_result->value( "codigo_reunion" )][hora_reunion]
		= $list_result->value( "hora_reunion" );
		$data[$list_result->value( "codigo_reunion" )][duracion_reunion]
		= $list_result->value( "duracion_reunion" );
		$data[$list_result->value( "codigo_reunion" )][resumen_reunion]
		= $list_result->value( "resumen_reunion" );
		$data[$list_result->value( "codigo_reunion" )][detalles_reunion]
		= $list_result->value( "detalles_reunion" );
		$data[$list_result->value( "codigo_reunion" )][asistentes_reunion]
		= $list_result->value( "asistentes_reunion" );
		}
		$list_result->close();
		
		return $data;
		}	


function verReunion($codigo_reunion) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = sprintf( "select * from reuniones WHERE codigo_reunion = ".$codigo_reunion." ORDER BY  fecha_reunion ASC");
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_reunion" )] = array();
		$data[$list_result->value( "codigo_reunion" )][codigo_reunion]
		= $list_result->value( "codigo_reunion" );
		$data[$list_result->value( "codigo_reunion" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_reunion" )][fecha_reunion]
		= $list_result->value( "fecha_reunion" );
		$data[$list_result->value( "codigo_reunion" )][hora_reunion]
		= $list_result->value( "hora_reunion" );
		$data[$list_result->value( "codigo_reunion" )][duracion_reunion]
		= $list_result->value( "duracion_reunion" );
		$data[$list_result->value( "codigo_reunion" )][resumen_reunion]
		= $list_result->value( "resumen_reunion" );
		$data[$list_result->value( "codigo_reunion" )][detalles_reunion]
		= $list_result->value( "detalles_reunion" );
		$data[$list_result->value( "codigo_reunion" )][asistentes_reunion]
		= $list_result->value( "asistentes_reunion" );
		}
		$list_result->close();
		
		return $data;
		}	
		
function verAcuerdo($codigo_acuerdo) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = sprintf( "select acuerdos.*, titulos.nombre_titulo from acuerdos" .
				" inner join titulos on titulos.codigo_titulo_comparativo = acuerdos.codigo_titulo_comparativo" .
				" inner join peticiones on peticiones.codigo_peticion = acuerdos.codigo_peticion" .
				" WHERE codigo_acuerdo = ".$codigo_acuerdo." ORDER BY  fecha_acuerdo ASC");
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_acuerdo" )] = array();
		$data[$list_result->value( "codigo_acuerdo" )][codigo_acuerdo]
		= $list_result->value( "codigo_acuerdo" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_ofertas]
		= $list_result->value( "codigo_ofertas" );
		$data[$list_result->value( "codigo_acuerdo" )][fecha_acuerdo]
		= $list_result->value( "fecha_acuerdo" );
		$data[$list_result->value( "codigo_acuerdo" )][nro_articulo]
		= $list_result->value( "nro_articulo" );
		$data[$list_result->value( "codigo_acuerdo" )][texto_completo_articulo]
		= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_acuerdo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_acuerdo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_acuerdo" )][estatus_acuerdo]
		= $list_result->value( "estatus_acuerdo" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_peticion]
		= $list_result->value( "codigo_peticion" );
		$data[$list_result->value( "codigo_acuerdo" )][nombre_titulo]
		= $list_result->value( "nombre_titulo" );
		}
		$list_result->close();
		
		return $data;
		}	
		
		
function listarPeticiones($ini , $fin , $codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from peticiones_sindicato WHERE codigo_bitacora = ".$codigo_bitacora."  ORDER BY  nro_peticion ASC LIMIT %d , %d", $ini , $fin);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_peticion" )] = array();
		$data[$list_result->value( "codigo_peticion" )][codigo_peticion]
		= $list_result->value( "codigo_peticion" );
		$data[$list_result->value( "codigo_peticion" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_peticion" )][nro_peticion]
		= $list_result->value( "nro_peticion" );
		$data[$list_result->value( "codigo_peticion" )][texto_completo_peticion]
		= $list_result->value( "texto_completo_peticion" );
		$data[$list_result->value( "codigo_peticion" )][estatus_peticion]
		= $list_result->value( "estatus_peticion" );
		$data[$list_result->value( "codigo_peticion" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_peticion" )][titulo_peticion]
		= $list_result->value( "titulo_peticion" );
		}
		$list_result->close();
		
		return $data;
		}	


function listarOfertas($ini , $fin, $codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from ofertas_patrono WHERE codigo_bitacora = ".$codigo_bitacora." ORDER BY  nro_oferta ASC LIMIT %d , %d", $ini , $fin);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_ofertas" )] = array();
		$data[$list_result->value( "codigo_ofertas" )][codigo_ofertas]
		= $list_result->value( "codigo_ofertas" );
		$data[$list_result->value( "codigo_ofertas" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_ofertas" )][nro_oferta]
		= $list_result->value( "nro_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][texto_completo_oferta]
		= $list_result->value( "texto_completo_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][estatus_oferta]
		= $list_result->value( "estatus_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_ofertas" )][titulo_oferta]
		= $list_result->value( "titulo_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][codigo_peticion]
		= $list_result->value( "codigo_peticion" );
		
		}
		$list_result->close();
		
		return $data;
		}
function listarOfertasSelect($codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from ofertas_patrono WHERE codigo_bitacora = ".$codigo_bitacora." ORDER BY  nro_oferta ASC ");
		
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_ofertas" )] = array();
		$data[$list_result->value( "codigo_ofertas" )][codigo_ofertas]
		= $list_result->value( "codigo_ofertas" );
		$data[$list_result->value( "codigo_ofertas" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_ofertas" )][nro_oferta]
		= $list_result->value( "nro_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][texto_completo_oferta]
		= $list_result->value( "texto_completo_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][estatus_oferta]
		= $list_result->value( "estatus_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_ofertas" )][titulo_oferta]
		= $list_result->value( "titulo_oferta" );
		$data[$list_result->value( "codigo_ofertas" )][codigo_peticion]
		= $list_result->value( "codigo_peticion" );
		
		}
		$list_result->close();
		
		return $data;
		}

function listarDiscusiones( $ini , $fin) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from bitacoras ORDER BY  codigo_bitacora ASC LIMIT %d , %d", $ini , $fin );
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_bitacora" )] = array();
		$data[$list_result->value( "codigo_bitacora" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_bitacora" )][codigo_empresa]
		= $list_result->value( "codigo_empresa" );
		$data[$list_result->value( "codigo_bitacora" )][fecha_inicio_disc]
		= $list_result->value( "fecha_inicio_disc" );
		$data[$list_result->value( "codigo_bitacora" )][estatus_bitacora]
		= $list_result->value( "estatus_bitacora" );
		}
		$list_result->close();
		
		return $data;
		}	

 	function obtenerIdUltimaDiscusion ()  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf(" Select * from bitacoras order by codigo_bitacora DESC LIMIT 0 , 1 ");

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$codigo_contrato = $query_result->value( "codigo_bitacora" );	
			return $codigo_contrato;
		} 
		
	}
function asignarRegistro3($discusiones_record){

		$this->codigo_peticion =	$discusiones_record->value('codigo_peticion');
		$this->codigo_bitacora=	$discusiones_record->value('codigo_bitacora');
		$this->nro_peticion=	$discusiones_record->value('nro_peticion');
		$this->codigo_peticion=	$discusiones_record->value('codigo_peticion');
		$this->texto_completo_peticion=	$discusiones_record->value('texto_completo_peticion');
		$this->estatus_peticion=	$discusiones_record->value('estatus_peticion');
		$this->codigo_titulo_comparativo=	$discusiones_record->value('codigo_titulo_comparativo');
		$this->titulo_peticion=	$discusiones_record->value('titulo_peticion');
	}
	
function asignarRegistro4($discusiones_record){

		$this->codigo_ofertas =	$discusiones_record->value('codigo_ofertas');
		$this->codigo_bitacora=	$discusiones_record->value('codigo_bitacora');
		$this->nro_oferta=	$discusiones_record->value('nro_oferta');
		$this->codigo_peticion=	$discusiones_record->value('codigo_peticion');
		$this->texto_completo_oferta=	$discusiones_record->value('texto_completo_oferta');
		$this->estatus_oferta=	$discusiones_record->value('estatus_oferta');
		$this->codigo_titulo_comparativo=	$discusiones_record->value('codigo_titulo_comparativo');
		$this->titulo_oferta=	$discusiones_record->value('titulo_oferta');
}
	
function limpiarRegistro4(){

		mysql_free_result($this->query_result);
}
	
function asignarRegistro2 ( $ley_record ) {
	$this->Total= $ley_record->value('Total');
}

function asignarRegistro5 ( $acuerdo_record ) {
	$this->codigo_acuerdo= $acuerdo_record->value('codigo_acuerdo');
	$this->codigo_bitacora= $acuerdo_record->value('codigo_bitacora');
	$this->codigo_ofertas= $acuerdo_record->value('codigo_ofertas');
	$this->fecha_acuerdo= $acuerdo_record->value('fecha_acuerdo');
	$this->nro_articulo= $acuerdo_record->value('nro_articulo');
	$this->texto_completo_articulo= $acuerdo_record->value('texto_completo_articulo');
	$this->resumen_articulo= $acuerdo_record->value('resumen_articulo');
	$this->codigo_titulo_comparativo= $acuerdo_record->value('codigo_titulo_comparativo');
	$this->campo_comparativo= $acuerdo_record->value('campo_comparativo');
	$this->estatus_acuerdo= $acuerdo_record->value('estatus_acuerdo');
}

function limpiarRegistro5 () {
	$this->codigo_acuerdo= " ";
	$this->codigo_ofertas= " ";
	$this->fecha_acuerdo= " ";
	$this->nro_articulo= " ";
	$this->texto_completo_articulo= " ";
	$this->resumen_articulo= " ";
	$this->codigo_titulo_comparativo= " ";
	$this->campo_comparativo= " ";
	$this->estatus_acuerdo= " ";
}



function asignarRegistro( $discusiones_record ) {
	$this->codigo_bitacora		=	$discusiones_record->value('codigo_bitacora');
	$this->codigo_empresa		 		=	$discusiones_record->value('codigo_empresa');
	$this->fecha_inicio_disc			=	$discusiones_record->value('fecha_inicio_disc');
	$this->fecha_termino		=	$discusiones_record->value('fecha_de_termino');
	$this->representante_empresa				=	$discusiones_record->value('representante_empresa');
	$this->telefono_representante_empresa	=	$discusiones_record->value('telefono_representante_empresa');
	$this->email_representante_empresa		=	$discusiones_record->value('email_representante_empresa');
	$this->cargo_representante_empresa	=	$discusiones_record->value('cargo_representante_empresa');
	$this->representante_sindicato	=	$discusiones_record->value('representante_sindicato');
	$this->telefono_representante_sindicato	=	$discusiones_record->value('telefono_representante_sindicato');
	$this->email_representante_sindicato	=	$discusiones_record->value('email_representante_sindicato');
	$this->cargo_representante_sindicato	=	$discusiones_record->value('cargo_representante_sindicato');
	$this->representante_min_trab	=	$discusiones_record->value('representante_min_trab');
	$this->telefono_representante_min_trab	=	$discusiones_record->value('telefono_representante_min_trab');
	$this->email_representante_min_trab	=	$discusiones_record->value('email_representante_min_trab');
	$this->cargo_representante_min_trab	=	$discusiones_record->value('cargo_representante_min_trab');
	$this->fecha_ultima_reunion	=	$discusiones_record->value('fecha_ultima_reunion');
	$this->fecha_proxima_reunion	=	$discusiones_record->value('fecha_proxima_reunion');
	$this->pdf_peticiones_sindicato	=	$discusiones_record->value('pdf_peticiones_sindicato');
	$this->pdf_ofertas_patrono	=	$discusiones_record->value('pdf_ofertas_patrono');
	$this->estatus_bitacora	=	$discusiones_record->value('estatus_bitacora');
	$this->estatu_pase_contratos	=	$discusiones_record->value('estatu_pase_contratos');
}	

}

if( ! isset($Discusion)) { 
	global $discusion;
	$discusion = New Discusion();
}
?>