<?php 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php
# Clase= empresas
# Descripcion: clase que contiene todas las funciones relacionadas con la tabla
# empresas
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_EMPRESAS' ))
	{
		return;
	}
define( '_EMPRESAS', '1.0' );
class EMPRESAS
	{
		#FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI EL USUARIO ESTA INSCRITO
 		function getId ( $id )
			{
				if( $id== "" || $id== 0) return false;
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Select * from empresas"
				. " where codigo_empresa = %d",
				$id);
				$query_result = $dbconn->execute( $query );
				if( $query_result->realcount() == 1 )
					{
						$this->assignRecord( $query_result );
						return true;
					}
				else
					{
						return false;
					}
			}
		#FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI EL USUARIO ESTA INSCRITO
 		function getNombreEmpresa ( $id )
			{
				if( $id== "" || $id== 0) return false;
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Select * from empresas"
				. " where codigo_empresa = %d",
				$id);
				$query_result = $dbconn->execute( $query );
				if( $query_result->realcount() == 1 )
					{
						$nombre_empresa=$query_result->value('nombre_empresa');
						return $nombre_empresa;
					}
				else
					{
						return false;
					}
			}
		#FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI EL USUARIO ESTA INSCRITO
 		function getporRif ( $nombre )
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Select * from empresas"
				. " where rif_empresa LIKE  %s",
				$dbconn->quoteString($nombre));
				$query_result = $dbconn->execute( $query );
				if( $query_result->realcount() == 1 )
					{
						$this->assignRecord( $query_result );
						return true;
					}
				else
					{
						return false;
					}
			}
		#FUNCION UPDATE
 		function update ($nombre_empresa, $direccion_empresa, $codigo_pais, $codigo_estado, $codigo_localidad, $telefonos_empresa, $fax_empresa, $email_empresa, $url_empresa, $rif_empresa, $nit_empresa, $contacto_empresa, $cargo_contacto, $telefonos_contacto, $email_contacto, $codigo_sector, $codigo_tipo, $codigo_categoria, $codigo_actividad, $cantidad_obreros_empresa, $cantidad_empleados_empresa, $tamano_empresa , $codigo_empresa)
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$query = sprintf("Update  empresas set "
				."nombre_empresa = %s ,"
				."direccion_empresa = %s ,"
				."codigo_pais = %d ,"
				."codigo_estado = %d ,"
				."codigo_localidad = %s ,"
				."telefonos_empresa = %s ,"
				."fax_empresa  = %s ,"
				."email_empresa =%s ,"
				."url_empresa =%s ,"
				."rif_empresa =%s ,"
				."nit_empresa =%s ,"
				."contacto_empresa =%s ,"
				."cargo_contacto =%s ,"
				."telefonos_contacto =%s ,"
				."email_contacto =%s ,"
				."codigo_sector =%d ,"
				."codigo_tipo =%d ,"
				."codigo_categoria =%d ,"
				."codigo_actividad =%d ,"
				."cantidad_obreros_empresa =%s ,"
				."cantidad_empleados_empresa =%s ,"
				."tamano_empresa =%s "
				."WHERE codigo_empresa =%d " ,
				$dbconn->quoteString($nombre_empresa),
				$dbconn->quoteString($direccion_empresa),
				$codigo_pais,
				$codigo_estado,
				$codigo_localidad,
				$dbconn->quoteString($telefonos_empresa),
				$dbconn->quoteString($fax_empresa),
				$dbconn->quoteString($email_empresa),
				$dbconn->quoteString($url_empresa),
				$dbconn->quoteString($rif_empresa),
				$dbconn->quoteString($nit_empresa),
				$dbconn->quoteString($contacto_empresa),
				$dbconn->quoteString($cargo_contacto),
				$dbconn->quoteString($telefonos_contacto),
				$dbconn->quoteString($email_contacto),
				$codigo_sector,
				$codigo_tipo,
				$codigo_categoria,
				$codigo_actividad,
				$dbconn->quoteString($cantidad_obreros_empresa),
				$dbconn->quoteString($cantidad_empleados_empresa),
				$dbconn->quoteString($tamano_empresa),
				$codigo_empresa);
				$update_result = $dbconn->execute( $query );
				$update_result->close();
				return true;
			}

		#eliminar Empresa de la Base de Datos
		function delete( $id )
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$delete_sql  = sprintf( "delete from empresas "
				. "where codigo_empresa = %d",
				$id);
				$delete_result = $dbconn->execute( $delete_sql );
				$delete_result->close();
				return true;
 			}
		//FUNCION LISTAR EMPRESAS
		function listarEmpresas($offset, $limit, $filtro )
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( '../local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);
				$list_sql  =	"select * from empresas  where nombre_empresa LIKE '%".$filtro."%' order by nombre_empresa ASC LIMIT $offset, $limit";
/*				$list_sql  =	"select * from empresas  where nombre_empresa LIKE '%".$filtro."%' order by nombre_empresa ASC LIMIT $offset, $limit";
				$list_sql  = 	"select * from empresas inner join tipo_empresa on empresas.codigo_tipo=tipo_empresa.codigo_tipo ".
								"where nombre_empresa LIKE '%".$filtro."%' order by tipo_empresa.nombre_tipo, empresas.nombre_empresa  ASC LIMIT $offset, $limit";
*/
				$list_sql	=	"select * from empresas where nombre_empresa LIKE '%".$filtro."%' order by empresas.nombre_empresa  ASC LIMIT $offset, $limit";
				$list_result = $dbconn->execute( $list_sql );
				reset($list_result);
				while( $list_result->next() )
					{
						$data[$list_result->value( "codigo_empresa" )] = array();
						$data[$list_result->value( "codigo_empresa" )][codigo_empresa]					= $list_result->value( "codigo_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nombre_sector]					= $list_result->value( "nombre_sector" );
						$data[$list_result->value( "codigo_empresa" )][nombre_empresa]					= $list_result->value( "nombre_empresa" );
						$data[$list_result->value( "codigo_empresa" )][direccion_empresa]				= $list_result->value( "direccion_empresa" );
						$data[$list_result->value( "codigo_empresa" )][codigo_pais]						= $list_result->value( "codigo_pais" );
						$data[$list_result->value( "codigo_empresa" )][codigo_estado]					= $list_result->value( "codigo_estado" );
						$data[$list_result->value( "codigo_empresa" )][codigo_localidad]				= $list_result->value( "codigo_localidad" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_empresa]				= $list_result->value( "telefonos_empresa" );
						$data[$list_result->value( "codigo_empresa" )][fax_empresa]						= $list_result->value( "fax_empresa" );
						$data[$list_result->value( "codigo_empresa" )][email_empresa]					= $list_result->value( "email_empresa" );
						$data[$list_result->value( "codigo_empresa" )][url_empresa]						= $list_result->value( "url_empresa" );
						$data[$list_result->value( "codigo_empresa" )][rif_empresa]						= $list_result->value( "rif_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nit_empresa]						= $list_result->value( "nit_empresa" );
						$data[$list_result->value( "codigo_empresa" )][contacto_empresa]				= $list_result->value( "contacto_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cargo_contacto]					= $list_result->value( "cargo_contacto" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_contacto]				= $list_result->value( "telefonos_contacto" );
						$data[$list_result->value( "codigo_empresa" )][email_contacto]					= $list_result->value( "email_contacto" );
						$data[$list_result->value( "codigo_empresa" )][codigo_sector]					= $list_result->value( "codigo_sector" );
						$data[$list_result->value( "codigo_empresa" )][codigo_tipo]						= $list_result->value( "codigo_tipo" );
						$data[$list_result->value( "codigo_empresa" )][codigo_categoria]				= $list_result->value( "codigo_categoria" );
						$data[$list_result->value( "codigo_empresa" )][codigo_actividad]				= $list_result->value( "codigo_actividad" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_obreros_empresa]		= $list_result->value( "cantidad_obreros_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_empleados_empresa]		= $list_result->value( "cantidad_empleados_empresa" );
						$data[$list_result->value( "codigo_empresa" )][tamano_empresa]					= $list_result->value( "tamano_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nombre_tipo]						= $list_result->value( "nombre_tipo" );

					}
				$list_result->close();
				return $data;
			}
//UPDATE IMAGEN LOGO
 	function update_imagen ( $id_empresa,$img1 )  {
		include( 'local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);
		$query = 	"UPDATE empresas SET logo = '".$img1."' WHERE empresas.codigo_empresa =".$id_empresa;

		$update_result = $dbconn->execute( $query );
		$update_result->close();
		return true;
	}

		//LISTAR EMPREAS PARA CONTRATOS
		function listarEmpresasContrato($nombre = "" , $sector=0, $tipo=0 , $categoria=0, $actividad=0)
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);
				$list_sql  = 'select * from empresas WHERE codigo_empresa > 0';
				if(!empty($nombre))
					$list_sql.=' AND nombre_empresa LIKE \'%'.$nombre.'%\' ';
				if($sector!=0)
					$list_sql.=' AND codigo_sector = '.$sector.' ';
				if($tipo!=0)
					$list_sql.= ' AND codigo_tipo = '.$tipo.' ';
				if($categoria!=0)
					$list_sql.=' AND codigo_categoria = '.$categoria.' ';
				if($actividad!=0)
					$list_sql.=' AND codigo_actividad = '.$actividad.' ';
					$list_sql.='ORDER BY nombre_empresa ASC';
				$list_result = $dbconn->execute( $list_sql );
				reset($list_result);
				while( $list_result->next() )
					{
						$data[$list_result->value( "codigo_empresa" )] = array();
						$data[$list_result->value( "codigo_empresa" )][codigo_empresa]		= $list_result->value( "codigo_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nombre_sector]		= $list_result->value( "nombre_sector" );
						$data[$list_result->value( "codigo_empresa" )][nombre_empresa]		= $list_result->value( "nombre_empresa" );
						$data[$list_result->value( "codigo_empresa" )][direccion_empresa]		= $list_result->value( "direccion_empresa" );
						$data[$list_result->value( "codigo_empresa" )][codigo_pais]		= $list_result->value( "codigo_pais" );
						$data[$list_result->value( "codigo_empresa" )][codigo_estado]		= $list_result->value( "codigo_estado" );
						$data[$list_result->value( "codigo_empresa" )][codigo_localidad]		= $list_result->value( "codigo_localidad" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_empresa]		= $list_result->value( "telefonos_empresa" );
						$data[$list_result->value( "codigo_empresa" )][fax_empresa]		= $list_result->value( "fax_empresa" );
						$data[$list_result->value( "codigo_empresa" )][email_empresa]		= $list_result->value( "email_empresa" );
						$data[$list_result->value( "codigo_empresa" )][url_empresa]		= $list_result->value( "url_empresa" );
						$data[$list_result->value( "codigo_empresa" )][rif_empresa]		= $list_result->value( "rif_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nit_empresa]		= $list_result->value( "nit_empresa" );
						$data[$list_result->value( "codigo_empresa" )][contacto_empresa]		= $list_result->value( "contacto_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cargo_contacto]		= $list_result->value( "cargo_contacto" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_contacto]		= $list_result->value( "telefonos_contacto" );
						$data[$list_result->value( "codigo_empresa" )][email_contacto]		= $list_result->value( "email_contacto" );
						$data[$list_result->value( "codigo_empresa" )][codigo_sector]		= $list_result->value( "codigo_sector" );
						$data[$list_result->value( "codigo_empresa" )][codigo_tipo]		= $list_result->value( "codigo_tipo" );
						$data[$list_result->value( "codigo_empresa" )][codigo_categoria]		= $list_result->value( "codigo_categoria" );
						$data[$list_result->value( "codigo_empresa" )][codigo_actividad]		= $list_result->value( "codigo_actividad" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_obreros_empresa]		= $list_result->value( "cantidad_obreros_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_empleados_empresa]		= $list_result->value( "cantidad_empleados_empresa" );
						$data[$list_result->value( "codigo_empresa" )][tamano_empresa]		= $list_result->value( "tamano_empresa" );
					}
				$list_result->close();
				return $data;
			}
		//LISTAR EMPREAS
		function listarEmpresas2()
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);
				$list_sql  = sprintf( "select * from empresas order by nombre_empresa ");
				$list_result = $dbconn->execute( $list_sql );
				reset($list_result);
				while( $list_result->next() )
					{
						$data[$list_result->value( "codigo_empresa" )] = array();
						$data[$list_result->value( "codigo_empresa" )][codigo_empresa]					= $list_result->value( "codigo_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nombre_sector]					= $list_result->value( "nombre_sector" );
						$data[$list_result->value( "codigo_empresa" )][nombre_empresa]					= $list_result->value( "nombre_empresa" );
						$data[$list_result->value( "codigo_empresa" )][direccion_empresa]				= $list_result->value( "direccion_empresa" );
						$data[$list_result->value( "codigo_empresa" )][codigo_pais]						= $list_result->value( "codigo_pais" );
						$data[$list_result->value( "codigo_empresa" )][codigo_estado]					= $list_result->value( "codigo_estado" );
						$data[$list_result->value( "codigo_empresa" )][codigo_localidad]				= $list_result->value( "codigo_localidad" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_empresa]				= $list_result->value( "telefonos_empresa" );
						$data[$list_result->value( "codigo_empresa" )][fax_empresa]						= $list_result->value( "fax_empresa" );
						$data[$list_result->value( "codigo_empresa" )][email_empresa]					= $list_result->value( "email_empresa" );
						$data[$list_result->value( "codigo_empresa" )][url_empresa]						= $list_result->value( "url_empresa" );
						$data[$list_result->value( "codigo_empresa" )][rif_empresa]						= $list_result->value( "rif_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nit_empresa]						= $list_result->value( "nit_empresa" );
						$data[$list_result->value( "codigo_empresa" )][contacto_empresa]				= $list_result->value( "contacto_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cargo_contacto]					= $list_result->value( "cargo_contacto" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_contacto]				= $list_result->value( "telefonos_contacto" );
						$data[$list_result->value( "codigo_empresa" )][email_contacto]					= $list_result->value( "email_contacto" );
						$data[$list_result->value( "codigo_empresa" )][codigo_sector]					= $list_result->value( "codigo_sector" );
						$data[$list_result->value( "codigo_empresa" )][codigo_tipo]						= $list_result->value( "codigo_tipo" );
						$data[$list_result->value( "codigo_empresa" )][codigo_categoria]				= $list_result->value( "codigo_categoria" );
						$data[$list_result->value( "codigo_empresa" )][codigo_actividad]				= $list_result->value( "codigo_actividad" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_obreros_empresa]		= $list_result->value( "cantidad_obreros_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_empleados_empresa]		= $list_result->value( "cantidad_empleados_empresa" );
						$data[$list_result->value( "codigo_empresa" )][tamano_empresa]					= $list_result->value( "tamano_empresa" );
					}
				$list_result->close();
				return $data;
			}

		//LISTAR EMPREAS PARA BITACORAS
		function listarEmpresas_BT($empresa)
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);

				if ($empresa==0){
					$list_sql  = sprintf( "select * from empresas order by nombre_empresa ");
				}else{
					$list_sql  = sprintf( "select * from empresas where codigo_empresa='".$empresa."' order by nombre_empresa ");
				}

				$list_result = $dbconn->execute( $list_sql );
				reset($list_result);
				while( $list_result->next() )
					{
						$data[$list_result->value( "codigo_empresa" )] = array();
						$data[$list_result->value( "codigo_empresa" )][codigo_empresa]					= $list_result->value( "codigo_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nombre_sector]					= $list_result->value( "nombre_sector" );
						$data[$list_result->value( "codigo_empresa" )][nombre_empresa]					= $list_result->value( "nombre_empresa" );
						$data[$list_result->value( "codigo_empresa" )][direccion_empresa]				= $list_result->value( "direccion_empresa" );
						$data[$list_result->value( "codigo_empresa" )][codigo_pais]						= $list_result->value( "codigo_pais" );
						$data[$list_result->value( "codigo_empresa" )][codigo_estado]					= $list_result->value( "codigo_estado" );
						$data[$list_result->value( "codigo_empresa" )][codigo_localidad]				= $list_result->value( "codigo_localidad" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_empresa]				= $list_result->value( "telefonos_empresa" );
						$data[$list_result->value( "codigo_empresa" )][fax_empresa]						= $list_result->value( "fax_empresa" );
						$data[$list_result->value( "codigo_empresa" )][email_empresa]					= $list_result->value( "email_empresa" );
						$data[$list_result->value( "codigo_empresa" )][url_empresa]						= $list_result->value( "url_empresa" );
						$data[$list_result->value( "codigo_empresa" )][rif_empresa]						= $list_result->value( "rif_empresa" );
						$data[$list_result->value( "codigo_empresa" )][nit_empresa]						= $list_result->value( "nit_empresa" );
						$data[$list_result->value( "codigo_empresa" )][contacto_empresa]				= $list_result->value( "contacto_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cargo_contacto]					= $list_result->value( "cargo_contacto" );
						$data[$list_result->value( "codigo_empresa" )][telefonos_contacto]				= $list_result->value( "telefonos_contacto" );
						$data[$list_result->value( "codigo_empresa" )][email_contacto]					= $list_result->value( "email_contacto" );
						$data[$list_result->value( "codigo_empresa" )][codigo_sector]					= $list_result->value( "codigo_sector" );
						$data[$list_result->value( "codigo_empresa" )][codigo_tipo]						= $list_result->value( "codigo_tipo" );
						$data[$list_result->value( "codigo_empresa" )][codigo_categoria]				= $list_result->value( "codigo_categoria" );
						$data[$list_result->value( "codigo_empresa" )][codigo_actividad]				= $list_result->value( "codigo_actividad" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_obreros_empresa]		= $list_result->value( "cantidad_obreros_empresa" );
						$data[$list_result->value( "codigo_empresa" )][cantidad_empleados_empresa]		= $list_result->value( "cantidad_empleados_empresa" );
						$data[$list_result->value( "codigo_empresa" )][tamano_empresa]					= $list_result->value( "tamano_empresa" );
					}
				$list_result->close();
				return $data;
			}
		//INSERTAR EMPRESAS
		function insert($nombre_empresa, $direccion_empresa, $codigo_pais, $codigo_estado, $codigo_localidad, $telefonos_empresa, $fax_empresa, $email_empresa, $url_empresa, $rif_empresa, $nit_empresa, $contacto_empresa, $cargo_contacto, $telefonos_contacto, $email_contacto, $codigo_sector, $codigo_tipo, $codigo_categoria, $codigo_actividad, $cantidad_obreros_empresa, $cantidad_empleados_empresa, $tamano_empresa, $logo)
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$dbconn = $LibSite->openDBConn(0);
				$insert_sql = sprintf( "insert into empresas ( "
				."nombre_empresa ,"
				."direccion_empresa ,"
				."codigo_pais ,"
				."codigo_estado ,"
				."codigo_localidad ,"
				."telefonos_empresa ,"
				."fax_empresa  ,"
				."email_empresa  ,"
				."url_empresa  ,"
				."rif_empresa  ,"
				."nit_empresa  ,"
				."contacto_empresa  ,"
				."cargo_contacto  ,"
				."telefonos_contacto  ,"
				."email_contacto  ,"
				."codigo_sector  ,"
				."codigo_tipo ,"
				."codigo_categoria  ,"
				."codigo_actividad  ,"
				."cantidad_obreros_empresa  ,"
				."cantidad_empleados_empresa  ,"
				."tamano_empresa, "
				."logo "
				.") values ( "
				. "%s,%s,%d,%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%d,%d,%d,%s,%s,%s,%s)",
				$dbconn->quoteString($nombre_empresa),
				$dbconn->quoteString($direccion_empresa),
				$codigo_pais,
				$codigo_estado,
				$codigo_localidad,
				$dbconn->quoteString($telefonos_empresa),
				$dbconn->quoteString($fax_empresa),
				$dbconn->quoteString($email_empresa),
				$dbconn->quoteString($url_empresa),
				$dbconn->quoteString($rif_empresa),
				$dbconn->quoteString($nit_empresa),
				$dbconn->quoteString($contacto_empresa),
				$dbconn->quoteString($cargo_contacto),
				$dbconn->quoteString($telefonos_contacto),
				$dbconn->quoteString($email_contacto),
				$codigo_sector,
				$codigo_tipo,
				$codigo_categoria,
				$codigo_actividad,
				$dbconn->quoteString($cantidad_obreros_empresa),
				$dbconn->quoteString($cantidad_empleados_empresa),
				$dbconn->quoteString($tamano_empresa),
				$dbconn->quoteString($logo));

				$insert_result = $dbconn->execute( $insert_sql );
				$insert_result->close();
				return true;
			}

//FUNCION OBTENER ULTIMA EMPRESA
 	function obtenerIdUltimaEmpresa()  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf(" Select * from empresas order by codigo_empresa DESC LIMIT 0 , 1 ");

		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$codigo_empresa = $query_result->value( "codigo_empresa" );
			return $codigo_empresa;
		}

	}
		//CONTAR EMPRESAS
		function contarEmpresas()
			{
				ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
				include( 'local/configuracion.php' );
				$data = array();
				$dbconn = $LibSite->openDBConn(0);
				$list_sql  = sprintf( "select count(*) as Total from empresas " );
				$list_result = $dbconn->execute( $list_sql );
				if( $list_result->realcount() == 1 )
					{
						$this->assignRecord($list_result);
						$list_result->close();
						return true;
					}
				else
					{
						$list_result->close();
						return false;
					}
			}
		//ASIGNACION DE REGISTROS
		function assignRecord( $user_record )
			{
				$this->codigo_empresa 				= $user_record->value('codigo_empresa');
				$this->nombre_empresa 				= $user_record->value('nombre_empresa');
				$this->direccion_empresa 			= $user_record->value('direccion_empresa');
				$this->codigo_pais 					= $user_record->value('codigo_pais');
				$this->codigo_estado 				= $user_record->value('codigo_estado');
				$this->codigo_localidad				= $user_record->value('codigo_localidad');
				$this->telefonos_empresa 			= $user_record->value('telefonos_empresa');
				$this->fax_empresa 					= $user_record->value('fax_empresa');
				$this->email_empresa 				= $user_record->value('email_empresa');
				$this->url_empresa 					= $user_record->value('url_empresa');
				$this->rif_empresa 					= $user_record->value('rif_empresa');
				$this->nit_empresa					= $user_record->value('nit_empresa');
				$this->contacto_empresa 			= $user_record->value('contacto_empresa');
				$this->cargo_contacto 				= $user_record->value('cargo_contacto');
				$this->telefonos_contacto 			= $user_record->value('telefonos_contacto');
				$this->email_contacto 				= $user_record->value('email_contacto');
				$this->codigo_sector 				= $user_record->value('codigo_sector');
				$this->codigo_tipo					= $user_record->value('codigo_tipo');
				$this->codigo_categoria 			= $user_record->value('codigo_categoria');
				$this->codigo_actividad				= $user_record->value('codigo_actividad');
				$this->cantidad_obreros_empresa 	= $user_record->value('cantidad_obreros_empresa');
				$this->cantidad_empleados_empresa  	= $user_record->value('cantidad_empleados_empresa');
				$this->tamano_empresa 				= $user_record->value('tamano_empresa');
				$this->logo							= $user_record->value('logo');
				$this->Total						= $user_record->value('Total');
			}
	}
if( ! isset($empresas))
	{
		global $empresas;
		$empresas = New EMPRESAS();
	}
?>
