<?php 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
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
//FUNCION OBTENER DISCUCION POR SU ID
function obtenerDiscusionesPorId ($codigo_bitacora)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

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
//FUNCION OBTENER PETICIONES POR SU ID
function obtenerPeticionesPorId ($codigo_peticiones)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

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
//FUNCION ELIMINAR REUNION
function eliminarReunion ($codigo_reunion)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("DELETE from reuniones WHERE codigo_reunion = ".$codigo_reunion."");
		$query_result = $dbconn->execute( $query );

			return true;

	}
//FUNCION ELIMINAR ACUERDO
function eliminarAcuerdo ($codigo_acuerdo)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("DELETE from acuerdos WHERE codigo_acuerdos = ".$codigo_acuerdo."");
		$query_result = $dbconn->execute( $query );

			return true;

	}

//FUNCION QUE DEVUELVE LOS TITULOS DE LAS OFERTAS PARA LAS PETICIONES
function obtenerTituloOfertasPorIdPeticion ($codigo_oferta)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = "Select * from ofertas_patrono WHERE codigo_ofertas = ".$codigo_oferta."";
		$query_result = $dbconn->execute( $query );

         while( $list_result->next() ) {
		$data[$list_result->value( "codigo_peticion" )] = array();
		$data[$list_result->value( "codigo_peticion" )][codigo_peticion] 		= $list_result->value( "codigo_peticion" );
		$data[$list_result->value( "codigo_peticion" )][codigo_bitacora]		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_peticion" )][nro_peticion]			= $list_result->value( "nro_peticion" );
		$data[$list_result->value( "codigo_peticion" )][texto_completo_peticion]= $list_result->value( "texto_completo_peticion" );
		$data[$list_result->value( "codigo_peticion" )][estatus_peticion]		= $list_result->value( "estatus_peticion" );
		$data[$list_result->value( "codigo_peticion" )][codigo_titulo_comparativo]= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_peticion" )][titulo_peticion]		= $list_result->value( "titulo_peticion" );
	}

	}
//FUNCION QUE DEVUELVE LOS TITULOS DE LAS OFERTAS PARA LAS PETICIONES
function obtenerOfertasPorIdPeticion ($codigo_oferta)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = "Select * from ofertas_patrono WHERE codigo_ofertas = ".$codigo_oferta."";
		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {
			$this->asignarRegistro4( $query_result );
			return true;
		} else {
			unset($query_result);
			return false;
		}

	}

//FUNCION QUE DEVUELVE UNA OFERTA ESPECIFICA
function obtenerOfertasPorId ($codigo_ofertas)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from ofertas_patrono WHERE codigo_ofertas = ".$codigo_ofertas."");
		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {
			//$this->limpiarRegistro4($query_result);
			$this->asignarRegistro4( $query_result );
			return true;
		} else {

			return false;
		}

	}

//FUNCION QUE ELIMINA UNA DISCUCION

function eliminarDiscusion( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from bitacoras "
		. "where codigo_bitacora = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}

//FUNCION QUE ELIMINA LAS OFERTAS DE UNA DISCUCION
function eliminarDiscusion_ofertas( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from ofertas_patrono "
		. "where codigo_bitacora = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}
//FUNCION QUE ELIMINA LAS PETCIONES DE UNA DISCUCON

function eliminarDiscusion_peticiones( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from peticiones_sindicato "
		. "where codigo_bitacora = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}

//FUNCION QUE ELIMINAR LAS REUNIONES DE UNA DISCUCION

function eliminarDiscusion_reuniones( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from reuniones "
		. "where codigo_bitacora = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}
//FUNCION QUE ELIMINA LOS ACUERDOS DE UNA DISUCION

function eliminarDiscusion_acuerdos( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from acuerdos "
		. "where codigo_bitacora = %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}

//FUNCION QUE LOS HISTORICOS DE UNA DISCUCION

function eliminarDiscusion_Historico( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);
		//Reuniones_Acuerdos
		$delete_sql  =  "delete from reuniones_acuerdos where codigo_bitacora =".$id;
		$delete_result = $dbconn->execute( $delete_sql );
		//Reuniones_Ofertas
		$delete_sql  =  "delete from reuniones_ofertas where codigo_bitacora =".$id;
		$delete_result = $dbconn->execute( $delete_sql );
		//Reuniones_Peticiones
		$delete_sql  =  "delete from reuniones_peticiones where codigo_bitacora =".$id;
		$delete_result = $dbconn->execute( $delete_sql );


		$delete_result->close();
		return true;
		}

//FUNCION QUE ELIMINA UNA OFERTA

function eliminarOfertaDiscusion( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from ofertas_patrono "
		. "where codigo_ofertas= %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;
		}

//FUNCION QUE ELIMINA UNA PETICION

function eliminarPeticionDiscusion( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from peticiones_sindicato "
		. "where codigo_peticion= %d",
		$id);

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}
//FUNCION UPDATE PARA REUNIONES
function actualizarReuniones($codigo_reunion,$fecha_reunion,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update reuniones set "
				."`fecha_reunion` = %s ,"
				."`hora_reunion` = %s ,"
				."`duracion_reunion` = %s ,"
				."`resumen_reunion` = %s ,"
				."`detalles_reunion` = %s ,"
				."`asistentes_reunion` = %s "
				." WHERE codigo_reunion = %d",

		$dbconn->quoteString($fecha_reunion),
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
//FUNCION UPDATE PARA ACUERDOS
function actualizarAcuerdos($codigo_acuerdo,$fecha_acuerdo, $nro_articulo,$titulo_articulo,$texto_completo_articulo,$resumen_articulo,$codigo_oferta,$codigo_peticion,$codigo_titulo_comparativo,$campo_comparativo)
	{
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);
		$query = "Update acuerdos set fecha_acuerdo = '".$fecha_acuerdo."' ,`nro_articulo` = '".$nro_articulo."' ,`titulo_articulo` = '".$titulo_articulo."' ,`texto_completo_articulo` = '".$texto_completo_articulo."' , `resumen_articulo` ='".$resumen_articulo."' , `codigo_oferta` =".$codigo_oferta.", codigo_peticion =".$codigo_peticion."  , `codigo_titulo_comparativo` =".$codigo_titulo_comparativo." ,`campo_comparativo` = '".$campo_comparativo."'    WHERE codigo_acuerdos = ".$codigo_acuerdo."";
		$update_result=$dbconn->execute($query);
		$update_result->close();
		return true;
	}

//FUNCION UPDATE PARA DISCUCIONES
function actualizarDiscusiones($representanteEm,$representanteSn,$representanteMt,$telefonoEm,$telefonoSn,$telefonoMt,$emailEm,$emailSn,$emailMt,$cargoEm,$cargoSn,$cargoMt,$peticiones,$oferta,$empresa,$inicio,$codigo_bitacora)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

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
				."`fecha_proxima_reunion`=%s,"
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
		$dbconn->quoteString($inicio),
		$dbconn->quoteString($peticiones),
		$dbconn->quoteString($oferta),
		$codigo_bitacora);
		$update_result=$dbconn->execute($query);
		$update_result->close();
		return true;
	}
//FUNCION UPDATE PARA ESTATUS DE LA DISCUCION (PENDIENTE,CERRADA,MODIFICACION)
function actualizarDiscusiones_status($codigo_bitacora, $status)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update bitacoras set "
				."`estatus_bitacora` = %d "
				." WHERE codigo_bitacora = %d",
		$status,
		$codigo_bitacora);
		$update_result=$dbconn->execute($query);
		$update_result->close();
		return true;
	}

//FUNCION UPDATE PARA OFERTAS
function actualizarOfertasDiscusiones($codigo_discusion, $nroOferta, $oferta,$peticion,$titulo,$tituloOferta, $codigoOferta)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update ofertas_patrono set "
				."`nro_oferta` = %s ,"
				."`texto_completo_oferta` = %s ,"
				."`codigo_titulo_comparativo` = %s ,"
				."`codigo_peticion` = %s ,"
				."`titulo_oferta` = %s "
				." WHERE codigo_ofertas= %d",

		$dbconn->quoteString($nroOferta),
		$dbconn->quoteString($oferta),
		$dbconn->quoteString($titulo),
		$dbconn->quoteString($peticion),
		$dbconn->quoteString($tituloOferta),
		$codigoOferta);
		$update_result=$dbconn->execute($query);
		$update_result->close();
		return true;
	}
//FUNCION UPDATE PARA PETICIONES
function actualizarPeticionesDiscusiones($codigo_bitacora, $nroPeticion, $textoPeticion,$tituloPeticion,$titulo, $codigoPeticion)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update peticiones_sindicato set "
				."`nro_peticion` = %s ,"
				."`texto_completo_peticion` = %s ,"
				."`codigo_titulo_comparativo` = %d ,"
				."`titulo_peticion` = %s "
				." WHERE codigo_peticion= %d",

		$dbconn->quoteString($nroPeticion),
		$dbconn->quoteString($textoPeticion),
		$titulo,
		$dbconn->quoteString($tituloPeticion),
		$codigoPeticion);
		$update_result=$dbconn->execute($query);
		$update_result->close();
		return true;
	}
//FUNCION INSERT PARA  DISCUCIONES
function insertarDiscusion($representanteEm,$representanteSn,$representanteMt,$telefonoEm,$telefonoSn,$telefonoMt,$emailEm,$emailSn,$emailMt,$cargoEm,$cargoSn,$cargoMt,$peticiones,$oferta,$empresa,$inicio)
{

ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

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

//FUNCION INSETR PARA REUNIONES
function agregarReunion($id_bitacora,$Fecha,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes)
{
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

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
//FUNCION INSERT PARA ACUERDOS
function agregarAcuerdo($id_bitacora,$id_peticion,$id_oferta,$fechaAcuerdo,$NroArticulo,$textoCompleto,$resumenArticulo,$NroComparativo,$campoComparativo,$Titulo_Articulo,$estatusAcuerdo)
{
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$insert_sql = sprintf( "insert into acuerdos ( "
		."`codigo_bitacora` ,"
		."`codigo_peticion` ,"
		."`codigo_oferta` ,"
		."`fecha_acuerdo` ,"
		."`nro_articulo` ,"
		."`texto_completo_articulo` ,"
		."`resumen_articulo` ,"
		."`codigo_titulo_comparativo` ,"
		."`campo_comparativo` ,"
		."`titulo_articulo` ,"
		."`estatus_acuerdo` "
		.") values ( "
		. "%d, %d, %d, %s,%s, %s, %s, %d, %s, %s, %d)",
		$id_bitacora,
		$id_peticion,
		$id_oferta,
		$dbconn->quoteString($fechaAcuerdo),
		$dbconn->quoteString($NroArticulo),
		$dbconn->quoteString($textoCompleto),
		$dbconn->quoteString($resumenArticulo),
		$NroComparativo,
		$dbconn->quoteString($campoComparativo),
		$dbconn->quoteString($Titulo_Articulo),
		$estatusAcuerdo);
		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}
//FUNCION INSERT PARA PETICIONES
function insertarPeticionDiscusion($codigo_discusion, $nroPeticion, $textoPeticion, $titulo,$tituloPeticion)
{

ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$insert_sql = sprintf( "insert into peticiones_sindicato ( "
		."`codigo_bitacora` ,"
		."`nro_peticion` ,"
		."`texto_completo_peticion` ,"
		."`titulo_peticion`, "
		."`codigo_titulo_comparativo`"
		.") values ( "
		. "%d, %s, %s, %s, %s)",
		$codigo_discusion,
		$dbconn->quoteString($nroPeticion),
		$dbconn->quoteString($textoPeticion),
		$dbconn->quoteString($titulo),
		$tituloPeticion);

		$insert_result = $dbconn->execute($insert_sql);
		$insert_result->close();
		return true;
}
//FUNCION INSERT PARA OFERTAS
function insertarOferta($codigo_discusion, $nroOferta, $oferta,$peticion,$titulo, $tituloOferta)
{

ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

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
//FUNCION CONTAR DISCUCIONES PARA PAGINADOR
 function contarDiscusiones() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
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
//FUNCION CONTAR REUNIONES PARA PAGINADOR
 function contarReuniones($id_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select count(*) as Total from reuniones WHERE codigo_bitacora = ".$id_bitacora." " );
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
//FUNCION CONTAR PETICIONES PARA PAGINADOR
 function contarPeticiones($codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
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
//FUNCION CONTAR OFERTAS PARA PAGINADOR
 function contarOfertas($codigo_ofertas) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
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

//FUNCION CONTAR ACUERDOS PARA PAGINADOR
 function contarAcuerdos($codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select count(*) as Total from acuerdos WHERE  codigo_bitacora = ".$codigo_bitacora." " );
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
//REVEZAR ESTA ES PARA BUSQUEDAS PARA VISTAS Y EDICION, ESTA COMO LISTAR PETICIONES
function listarPeticionesSelect($ini,$fin,$codigo_bitacora, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = sprintf( "select * from peticiones_sindicato where codigo_bitacora= ".$codigo_bitacora." and titulo_peticion like '%".$filtro."%'order by titulo_peticion  ASC limit ".$ini.",".$fin."");
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_peticion" )] = array();
		$data[$list_result->value( "codigo_peticion" )][codigo_peticion]
		= $list_result->value( "codigo_peticion" );
		$data[$list_result->value( "codigo_peticion" )][codigo_bitacora]
		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_peticion" )][nro_oferta]
		= $list_result->value( "nro_oferta" );
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
//LISTAR ACUERDOS

function listarAcuerdos($ini,$fin,$codigo_bitacora, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = "select * from acuerdos where codigo_bitacora=".$codigo_bitacora." and titulo_articulo like '%".$filtro."%' order by nro_articulo ASC limit ".$ini.",".$fin." ";
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_acuerdos" )] = array();
		$data[$list_result->value( "codigo_acuerdos" )][codigo_acuerdos]			= $list_result->value( "codigo_acuerdos" );
		$data[$list_result->value( "codigo_acuerdos" )][codigo_bitacora]			= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_acuerdos" )][codigo_peticion]			= $list_result->value( "codigo_peticion" );
		$data[$list_result->value( "codigo_acuerdos" )][codigo_oferta]			= $list_result->value( "codigo_oferta" );
		$data[$list_result->value( "codigo_acuerdos" )][fecha_acuerdo]			= $list_result->value( "fecha_acuerdo" );
		$data[$list_result->value( "codigo_acuerdos" )][nro_articulo]			= $list_result->value( "nro_articulo" );
		$data[$list_result->value( "codigo_acuerdos" )][texto_completo_articulo]	= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_acuerdos" )][resumen_articulo]		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_acuerdos" )][codigo_titulo_comparativo]	= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_acuerdos" )][campo_comparativo]		= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_acuerdos" )][estatus_acuerdo]			= $list_result->value( "estatus_acuerdo" );
		$data[$list_result->value( "codigo_acuerdos" )][titulo_articulo]			= $list_result->value( "titulo_articulo" );


		}
		$list_result->close();

		return $data;
		}



//FUNCION PARA BUSCAR EL ACEURDO DE UNA OFERTA

function buscarAcuerdo($fecha_reunion,$codigo_peticion,$codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select * from acuerdos WHERE codigo_bitacora = ".$codigo_bitacora." and codigo_peticion = ".$codigo_peticion." and fecha_acuerdo ='".$fecha_reunion."'");
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
//FUNCION LISTAR REUNIONES
function listarReunionesSelect($ini,$fin,$codigo_bitacora, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from reuniones WHERE codigo_bitacora = ".$codigo_bitacora." and  fecha_reunion like '%".$filtro."%' ORDER BY  fecha_reunion ASC  limit ".$ini.",".$fin." ";
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_reunion" )] = array();
		$data[$list_result->value( "codigo_reunion" )][codigo_reunion]		= $list_result->value( "codigo_reunion" );
		$data[$list_result->value( "codigo_reunion" )][codigo_bitacora]		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_reunion" )][fecha_reunion]		= $list_result->value( "fecha_reunion" );
		$data[$list_result->value( "codigo_reunion" )][hora_reunion]		= $list_result->value( "hora_reunion" );
		$data[$list_result->value( "codigo_reunion" )][duracion_reunion]	= $list_result->value( "duracion_reunion" );
		$data[$list_result->value( "codigo_reunion" )][resumen_reunion]		= $list_result->value( "resumen_reunion" );
		$data[$list_result->value( "codigo_reunion" )][detalles_reunion]	= $list_result->value( "detalles_reunion" );
		$data[$list_result->value( "codigo_reunion" )][asistentes_reunion]	= $list_result->value( "asistentes_reunion" );
		}
		$list_result->close();

		return $data;
		}

//FUNCION QUE DEVUELVE UNA REUNION
function verReunion($codigo_reunion) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
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
//FUNCION QUE DEVUELVE UN ACUERDO
function verAcuerdo($codigo_acuerdo) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = sprintf( "SELECT  *  FROM acuerdos" .
							" WHERE codigo_acuerdos = ".$codigo_acuerdo." ORDER BY  fecha_acuerdo ASC");
		//print $list_sql;

		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_acuerdo" )] = array();
		$data[$list_result->value( "codigo_acuerdo" )][codigo_acuerdo]		= $list_result->value( "codigo_acuerdo" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_bitacora]		= $list_result->value( "codigo_bitacora" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_oferta]		= $list_result->value( "codigo_oferta" );
		$data[$list_result->value( "codigo_acuerdo" )][fecha_acuerdo]		= $list_result->value( "fecha_acuerdo" );
		$data[$list_result->value( "codigo_acuerdo" )][nro_articulo]		= $list_result->value( "nro_articulo" );
		$data[$list_result->value( "codigo_acuerdo" )][texto_completo_articulo]		= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_acuerdo" )][resumen_articulo]			= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_titulo_comparativo]	= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_acuerdo" )][campo_comparativo]			= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_acuerdo" )][estatus_acuerdo]				= $list_result->value( "estatus_acuerdo" );
		$data[$list_result->value( "codigo_acuerdo" )][codigo_peticion]				= $list_result->value( "codigo_peticion" );
		$data[$list_result->value( "codigo_acuerdo" )][nombre_titulo]				= $list_result->value( "nombre_titulo" );
		$data[$list_result->value( "codigo_acuerdo" )][titulo_articulo]				= $list_result->value( "titulo_articulo" );



		}
		$list_result->close();

		return $data;
		}

//FUNCION LISTAR PETICIONES
function listarPeticiones($ini , $fin , $codigo_bitacora, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from peticiones_sindicato WHERE codigo_bitacora = ".$codigo_bitacora."  and titulo_peticion like '%".$filtro."%' ORDER BY  nro_peticion ASC LIMIT ".$ini." , ".$fin."";
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

//FUNCION LISATR OFERTAS
function listarOfertas($ini , $fin, $codigo_bitacora, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  =  "select * from ofertas_patrono WHERE codigo_bitacora = ".$codigo_bitacora."  and titulo_oferta like '%".$filtro."%' ORDER BY  nro_oferta ASC LIMIT ".$ini." , ".$fin."";
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
//FUNCION LISATR FOERTAS
function listarOfertasSelect($codigo_bitacora) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
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
//FUNCION LISTAR DISCUCIONES
function listarDiscusiones( $ini , $fin, $filtro,$empresa) {



		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		if ($empresa==0){
			$list_sql  = "select * from bitacoras inner  join empresas on bitacoras.codigo_empresa = empresas.codigo_empresa  where empresas.nombre_empresa like '%".$filtro."%' order by nombre_empresa ASC LIMIT ".$ini." , ".$fin."";
		}else{
			$list_sql  = "select * from bitacoras inner  join empresas on bitacoras.codigo_empresa = empresas.codigo_empresa  where empresas.nombre_empresa like '%".$filtro."%' and empresas.codigo_empresa='".$empresa."' order by nombre_empresa ASC LIMIT ".$ini." , ".$fin."";
		}
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
//FUNCION QUE DEVUELVE UTLIMA DISCUCION
 	function obtenerIdUltimaDiscusion ()  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf(" Select * from bitacoras order by codigo_bitacora DESC LIMIT 0 , 1 ");

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$codigo_contrato = $query_result->value( "codigo_bitacora" );
			return $codigo_contrato;
		}

	}


//INSERTAR REUNIONES PANTALLA EXTERNA
function inserta_Reuniones_Externas($codigo_bt,$FechaReunion,$HoraReunion,$DuracionReunion,$ResumenReunion,$DetalleReunion,$AsistentesReunion  ){

		$data = array();

		$con = mysql_connect("localhost", "root", "162310")		or die ('I cannot connect to the database because: ' . mysql_error());
		mysql_select_db("cccol", $con);

		$inserta_reunion	= " INSERT INTO reuniones( `codigo_bitacora`, `fecha_reunion`, `hora_reunion`, `duracion_reunion`, `resumen_reunion`, `detalles_reunion`, `asistentes_reunion`) VALUES ( '".$codigo_bt."', '".$FechaReunion."', '".$HoraReunion."', '".$DuracionReunion."', '".$ResumenReunion."', '".$DetalleReunion."', '".$AsistentesReunion."')";
		$list_result	 = mysql_query($inserta_reunion);
	}




//INSERTAR HISTORICO DE OFERTAS-REUNIONES Y PETCIONES-REUNIONES-ACUERDOS
function insertar_Historico_Ofe_Pet_Acu($codigo_ultima_reunion,$codigo_bt,$codigo_ultima_relacionado,$exacto){
		include( '../../../../local/configuracion.php' );
		$data = array();
		$con = mysql_connect("localhost", "root", "162310");
		mysql_select_db("CCCOL", $con);
		if ($exacto=="OFE"){
			$list_sql  = "insert into `reuniones_ofertas` (`codigo_reuniones` ,`codigo_bitacora` ,`codigo_ofertas` ) VALUES ( ".$codigo_ultima_reunion.", ".$codigo_bt.", ".$codigo_ultima_relacionado.")";
			$list_result	 = mysql_query($list_sql);
		}elseif($exacto=="PET"){
			$list_sql  = "INSERT INTO `reuniones_peticiones` (`codigo_reuniones` ,`codigo_bitacora` ,`codigo_peticiones` )VALUES (".$codigo_ultima_reunion.", ".$codigo_bt.",".$codigo_ultima_relacionado.")";
			$list_result	 = mysql_query($list_sql);
		}elseif($exacto=="ACU"){
			$list_sql  = "INSERT INTO `reuniones_acuerdos` (`codigo_reuniones` ,`codigo_bitacora` ,`codigo_acuerdos` )VALUES (".$codigo_ultima_reunion.", ".$codigo_bt.",".$codigo_ultima_relacionado.")";
			$list_result	 = mysql_query($list_sql);

		}

	}
//INSERTAR ACUERDOS PANTALLA EXTERNA
function insertar_Acuerdos_Reuniones( $codigo_bt,$codigo_peticion_cargados,$codigo_ultima_oferta,$codigo_ultima_reunion,$FechaReunion,	$nro_acuerdo,$texto_acuerdo,$titulo_comparativo_acuerdo,$titulo_acuerdo){
		include( '../../../../local/configuracion.php' );
		$data = array();
		$con = mysql_connect("localhost", "root", "162310");
		mysql_select_db("CCCOL", $con);
		$inserta_acuerdos="INSERT INTO `acuerdos` (`codigo_bitacora` ,	`codigo_peticion` ,					`codigo_oferta` ,	`codigo_reunion` ,				`fecha_acuerdo` ,				`nro_articulo` ,		`texto_completo_articulo` ,	`codigo_titulo_comparativo` ,		`campo_comparativo` ,		`resumen_articulo` ,	`titulo_articulo` 	)".
									" VALUES (".$codigo_bt.", 		".$codigo_peticion_cargados.", 	".$codigo_ultima_oferta." ,	".$codigo_ultima_reunion.",		'".$FechaReunion."','"	.$nro_acuerdo."','"	.$texto_acuerdo."'," 	.$titulo_comparativo_acuerdo.",	'Campo Comparativo Pendiente',	'Resumen Pentiende','".$titulo_acuerdo."')";
		$list_result	 = mysql_query($inserta_acuerdos);

		}

//INSERTAR OFERTAS PANTALLA EXTERNA
function insertar_Ofertas_Patrono($codigo_bt,$nro_oferta,$texto_oferta,$titulo_comparativo,$codigo_peticion,$titulo_oferta){
		include( '../../../../local/configuracion.php' );
		$data = array();
		$con = mysql_connect("localhost", "root", "162310");
		mysql_select_db("CCCOL", $con);
		$inserta_ofertas="INSERT INTO `ofertas_patrono` (`codigo_bitacora` ,`nro_oferta` ,`texto_completo_oferta` ,	`codigo_titulo_comparativo` ,`codigo_peticion` ,`titulo_oferta` )
											VALUES ( '".$codigo_bt."', '".$nro_oferta."', '".$texto_oferta."', '".$titulo_comparativo."', '".$codigo_peticion."', '".$titulo_oferta."')";
		$list_result	 = mysql_query($inserta_ofertas);

		}
// Actualizar PETICIONES QUE TUBIERON ACUERDOS PANTALLA EXTERNA
function update_Ofertas_Acuerdos($codigo_peticion){
		include( '../../../../local/configuracion.php' );
		$data = array();
		$con = mysql_connect("localhost", "root", "162310");
		mysql_select_db("CCCOL", $con);

		$update_ofe_acu = sprintf("Update peticiones_sindicato set "
				."`estatus_peticion` = 9 "
				." WHERE codigo_peticion= %d",
		$codigo_peticion);
		$list_result	 = mysql_query($update_ofe_acu);
		}


//MASCARA O CADENA PARA LOS NRO DE OFERTAS PETICIONES ACUERDOS....
function mascara($nro){
			$nro_articulo= trim($nroOferta);
			$cadena=split("-",$nro);
			switch ($cadena) {

				case (count($cadena) >= 3):
					$nro_articulo= $cadena[0]."-".$cadena[1];
				break;

				case (strlen($cadena[0])==3):
					if ($cadena[1] == ""){
					$nro_articulo= $cadena[0];
					} 	else {
						$nro_articulo= $cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==2):
					if ($cadena[1] == ""){
					$nro_articulo= "0".$cadena[0];
					} 	else {
						$nro_articulo= "0".$cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==1):
					if ($cadena[1] == ""){
					$nro_articulo= "00".$cadena[0];
					} 	else {
						$nro_articulo= "00".$cadena[0]."-".$cadena[1];
						}
					break;
				break;
			}
			return $nro_articulo;


	}
//Re-abrir Peticion
function actualizarPeticiones_reabir($codigo_peticion)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query =" UPDATE `peticiones_sindicato` SET `estatus_peticion` =''  WHERE codigo_peticion = ".$codigo_peticion;
		print $query;
		$update_result=$dbconn->execute($query);
		$update_result->close();
		return true;
	}
//FUNCION ASIGNAR REGISTROS DE PETICION
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
//FUNCION ASIGNAR REGISTROS DE  OFERTA
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
//FUNCION ASIGNAR LIMPIAR REGISTRO
function limpiarRegistro4($query){

		mysql_free_result($query);
}
//FUNCION ASIGNAR CONTADORES
function asignarRegistro2 ( $ley_record ) {
	$this->Total= $ley_record->value('Total');
}
//FUNCION ASIGNAR REGISTROS DE ACUERDOS
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
