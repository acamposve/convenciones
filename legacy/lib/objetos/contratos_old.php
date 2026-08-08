<? 
#======================================================================================
# Clase= Contratos
# Descripcion: Clase que contiene operaciones con la tabla Contratos
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_CONTRATOS' )) {
	return;
}

define( '_CONTRATOS', '1.0' );

class Contratos {

 #FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI EL USUARIO ESTA INSCRITO
 
 	function obtenerArticuloContratosPorId( $id_contrato )  {

		if( $id_contrato== "" || $id_contrato== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from articulos_contratos"
				. " where codigo_articulo = %d",
				$id_contrato);

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro2( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
#LISTA LOS CONTRATOS OBTENIDOS POR EL BUSCADOR

function listarContratosBuscador($nombre = "", $sector = 0, $tipo = 0, $categoria = 0, $actividad = 0, $empresa = 0, $ordenado = ""){
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		if($empresa!=0) {
		$list_sql  = sprintf( "select emp.codigo_empresa, emp.nombre_empresa, emp.codigo_sector, emp.codigo_tipo, emp.codigo_categoria, emp.codigo_actividad, con.codigo_contrato, con.fecha_de_inicio,con.pdf_auto_acta, con.fecha_de_termino, con.codigo_empresa  from contratos con INNER JOIN  empresas emp ON emp.codigo_empresa = con.codigo_empresa WHERE emp.codigo_empresa = ".$empresa."");
		}else{
		$list_sql  = sprintf( "select emp.codigo_empresa, emp.nombre_empresa, emp.codigo_sector, emp.codigo_tipo, emp.codigo_categoria, emp.codigo_actividad, con.codigo_contrato, con.fecha_de_inicio,con.pdf_auto_acta, con.fecha_de_termino, con.codigo_empresa  from contratos con INNER JOIN  empresas emp ON emp.codigo_empresa = con.codigo_empresa WHERE con.codigo_contrato > 0  ");
		
		if(!empty($nombre))
			$list_sql.=' AND emp.nombre_empresa LIKE \'%'.$nombre.'%\'';
		if($sector != 0)
			$list_sql.=' AND emp.codigo_sector = '.$sector.'';
		if($tipo != 0)
			$list_sql.=' AND emp.codigo_tipo = '.$tipo.'';		
		if($categoria != 0)
		  	$list_sql.=' AND emp.codigo_categoria = '.$categoria.'';
		if($actividad != 0)
			$list_sql.=' AND emp.codigo_actividad = '.$actividad.'';
		if(!empty($ordenado)){
			$list_sql.=' ORDER BY emp.'.$ordenado.' ASC'; 
		}
		}
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_contrato" )] = array();
		$data[$list_result->value( "codigo_contrato" )][codigo_contrato]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_contrato" )][codigo_empresa]
		= $list_result->value( "codigo_empresa" );
		$data[$list_result->value( "codigo_contrato" )][pdf_auto_acta]
		= $list_result->value( "pdf_auto_acta" );
		$data[$list_result->value( "codigo_contrato" )][fecha_inicio]
		= $list_result->value( "fecha_de_inicio" );
		$data[$list_result->value( "codigo_contrato" )][fecha_termino]
		= $list_result->value( "fecha_de_termino" );
		$data[$list_result->value( "codigo_contrato" )][nombre_empresa]
		= $list_result->value( "nombre_empresa" );
		$data[$list_result->value( "codigo_contrato" )][codigo_sector]
		= $list_result->value( "codigo_sector" );
		$data[$list_result->value( "codigo_contrato" )][codigo_tipo]
		= $list_result->value( "codigo_tipo" );
		$data[$list_result->value( "codigo_contrato" )][cantidad_obreros_empresa]
		= $list_result->value( "cantidad_obreros_empresa" );
		$data[$list_result->value( "codigo_contrato" )][codigo_actividad]
		= $list_result->value( "codigo_actividad" );

		}
		$list_result->close();
		
		return $data;

}

#FUNCION GET QUE BUSCA EN LA BASE DE DATOS SI EL USUARIO ESTA INSCRITO
 
 	function obtenerAnexoContratosPorId( $id_anexo )  {

		if( $id_anexo== "" || $id_anexo== 0) return false;

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from anexos_contratos"
				. " where codigo_anexo = %d",
				$id_anexo);

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro3( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
 	function obtenerContratosPorId ($codigo_contrato)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Select * from contratos WHERE codigo_contrato = ".$codigo_contrato."");
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->asignarRegistro( $query_result );	
			return true;
		} else {
			
			return false;
		}
		
	}
	
	
	function obtenerEmpresaContratosPorId ($codigo_contrato)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
	
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("select emp.codigo_empresa, emp.nombre_empresa, emp.codigo_sector, emp.codigo_tipo, emp.codigo_categoria, emp.codigo_actividad, con.codigo_contrato , con.ambito_aplicacion, con.duracion, con.fecha_de_inicio,con.pdf_auto_acta, con.fecha_de_termino, con.codigo_empresa  from contratos con INNER JOIN  empresas emp ON emp.codigo_empresa = con.codigo_empresa WHERE con.codigo_contrato = ".$codigo_contrato."");
		$list_result = $dbconn->execute( $query );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_contrato" )] = array();
		$data[$list_result->value( "codigo_contrato" )][codigo_contrato]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_contrato" )][codigo_empresa]
		= $list_result->value( "codigo_empresa" );
		$data[$list_result->value( "codigo_contrato" )][pdf_auto_acta]
		= $list_result->value( "pdf_auto_acta" );
		$data[$list_result->value( "codigo_contrato" )][fecha_inicio]
		= $list_result->value( "fecha_de_inicio" );
		$data[$list_result->value( "codigo_contrato" )][fecha_termino]
		= $list_result->value( "fecha_de_termino" );
		$data[$list_result->value( "codigo_contrato" )][nombre_empresa]
		= $list_result->value( "nombre_empresa" );
		$data[$list_result->value( "codigo_contrato" )][duracion]
		= $list_result->value( "duracion" );
		$data[$list_result->value( "codigo_contrato" )][ambito_aplicacion]
		= $list_result->value( "ambito_aplicacion" );
		$data[$list_result->value( "codigo_contrato" )][codigo_sector]
		= $list_result->value( "codigo_sector" );
		$data[$list_result->value( "codigo_contrato" )][codigo_categoria]
		= $list_result->value( "codigo_categoria" );
		$data[$list_result->value( "codigo_contrato" )][codigo_actividad]
		= $list_result->value( "codigo_actividad" );
		$data[$list_result->value( "codigo_contrato" )][codigo_tipo]
		= $list_result->value( "codigo_tipo" );
		$data[$list_result->value( "codigo_contrato" )][cantidad_obreros_empresa]
		= $list_result->value( "cantidad_obreros_empresa" );
		$data[$list_result->value( "codigo_contrato" )][codigo_actividad]
		= $list_result->value( "codigo_actividad" );

		}
		$list_result->close();
		
		return $data;
		
	}
	
#devuelve el id del ultimo contrato	
 	function obtenerIdUltimoContrato ()  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf(" Select * from contratos order by codigo_contrato DESC LIMIT 0 , 1 ");

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$codigo_contrato = $query_result->value( "codigo_contrato" );	
			return $codigo_contrato;
		} 
		
	}
	
#devuelve el id del ultimo contrato	
 	function obtenerIdUltimoAnexoContrato ()  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf(" Select * from anexos_contratos order by codigo_anexo DESC LIMIT 0 , 1 ");

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$codigo_anexo = $query_result->value( "codigo_anexo" );	
			return $codigo_anexo;
		} 
		
	}

 
 #contar contartos
 
 function contarContratos() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from contratos " );
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
		
 #contar contartos
 
 function contarArticulosContratos($codigo_contrato) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from articulos_contratos WHERE codigo_contratos = %d ",$codigo_contrato );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
			$total=$list_result->value('Total');
			$list_result->close();
			return $total;
		} else {
			$list_result->close();
			return false;
		}
		}	
		
 function contarAnexosContratos($codigo_contrato) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from anexos_contratos WHERE codigo_contrato = %d ",$codigo_contrato );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
			$total=$list_result->value('Total');
			$list_result->close();
			return $total;
		} else {
			$list_result->close();
			return false;
		}
		}	

  #FUNCION UPDATE
 
 	function actualizarContratos($pdf , $duracion , $inicio ,  $termino, $ambito , $empresa, $codigo_contrato)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		$duracion = strtoupper($duracion);
		$ambito = strtoupper($ambito);
		$query = sprintf("Update contratos set "
				."pdf_auto_acta = %s, "
				."fecha_de_inicio = %s,"
				."fecha_de_termino = %s, "
				."duracion = %s, "
				."ambito_aplicacion = %s, "
				."codigo_empresa = %d "
				." WHERE codigo_contrato = %d",
		
		$dbconn->quoteString($pdf),
		$dbconn->quoteString($inicio),
		$dbconn->quoteString($termino),
		$dbconn->quoteString($duracion),
		$dbconn->quoteString($ambito),
		$empresa,
		$codigo_contrato);
		$update_result=$dbconn->execute($query);	
		$update_result->close();	
		return true;	
	}	
	

function actualizarAnexoContratos($nombre_anexo , $descripcion , $texto_completo ,  $pdf_asociado, $codigo_anexo){
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update anexos_contratos set "
				."nombre_anexo = %s, "
				."descripcion_anexo = %s, "
				."texto_completo_anexo = %s, "
				."pdf_anexo = %s "
				." WHERE codigo_anexo = %d",
		
		$dbconn->quoteString($nombre_anexo),
		$dbconn->quoteString($descripcion),
		$dbconn->quoteString($texto_completo),
		$dbconn->quoteString($pdf_asociado),
		$codigo_anexo);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
}


function actualizarArticulosContratos($nro_articulo , $texto_completo , $resumen_texto ,  $titulo, $campo , $codigo_articulo,$titulo_articulo)  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update articulos_contratos set "
				."nro_articulos = %s, "
				."texto_completo_articulo = %s, "
				."resumen_articulo = %s, "
				."codigo_titulo_comparativo = %d, "
				."campo_comparativo = %s, "
				."titulo_articulo = %s"
				." WHERE codigo_articulo = %d",
		
		$dbconn->quoteString($nro_articulo),
		$dbconn->quoteString($texto_completo),
		$dbconn->quoteString($resumen_texto),
		$titulo,
		$dbconn->quoteString($campo),
		$dbconn->quoteString($titulo_articulo),
		$codigo_articulo);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
	
//actualizar Nro de articulos de la ley de trabajo
	
function update_nro_articulos ( $contador, $total_articulos)  {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf("Update contratos set "
				."total_articulos = %d "
				." WHERE total_articulos = %d",
		
		$contador,
		$total_articulos);
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
	
#insertar contratos

function insertarContrato($pdf_contrato, $fecha_inicio, $fecha_fin, $duracion, $ambito, $empresa ) {
		
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		$duracion = strtoupper($duracion);
		$ambito = strtoupper($ambito);
		$insert_sql = sprintf( "insert into contratos ( "
		."pdf_auto_acta, "
		."fecha_de_inicio, "
		."fecha_de_termino, "
		."duracion, "
		."ambito_aplicacion, "
		."codigo_empresa "
		.") values ( "
		. "%s, %s, %s, %s, %s, %d)",
		$dbconn->quoteString($pdf_contrato),
		$dbconn->quoteString($fecha_inicio),
		$dbconn->quoteString($fecha_fin),
		$dbconn->quoteString($duracion),
		$dbconn->quoteString($ambito),
		$empresa
		);
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
//FUNCION LISTAR CONTRATOS


function listarContratos( $ini , $fin, $filtro) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql= "select 	contratos.codigo_contrato,
							contratos.codigo_empresa, 
							contratos.codigo_empresa,
							contratos.ambito_aplicacion,
							contratos.status_publicacion,
							empresas.nombre_empresa,
							fecha_de_inicio,
							fecha_de_termino,
							duracion
							from contratos INNER JOIN empresas ON contratos.codigo_empresa = empresas.codigo_empresa
							where empresas.nombre_empresa like '%".$filtro."%'  ORDER BY  empresas.nombre_empresa ASC LIMIT ".$ini." , ".$fin."";
		
		
		
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_contrato" )] = array();
		$data[$list_result->value( "codigo_contrato" )][codigo_contrato]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_contrato" )][pdf_auto-acta]
		= $list_result->value( "pdf_auto-acta" );
		$data[$list_result->value( "codigo_contrato" )][fecha_inicio]
		= $list_result->value( "fecha_de_inicio" );
		$data[$list_result->value( "codigo_contrato" )][fecha_termino]
		= $list_result->value( "fecha_de_termino" );
		$data[$list_result->value( "codigo_contrato" )][duracion]
		= $list_result->value( "duracion" );
		$data[$list_result->value( "codigo_contrato" )][ambito_aplicacion]
		= $list_result->value( "ambito_aplicacion" );
		$data[$list_result->value( "codigo_contrato" )][codigo_empresa]
		= $list_result->value( "codigo_empresa" );
		$data[$list_result->value( "codigo_contrato" )][status_publicacion]
		= $list_result->value( "status_publicacion" );
		$data[$list_result->value( "codigo_contrato" )][fecha_de_termino]
		= $list_result->value( "fecha_de_termino" );
		$data[$list_result->value( "codigo_contrato" )][duracion]
		= $list_result->value( "duracion" );
		}
		$list_result->close();
		
		return $data;
		}	



#eliminar usuarios de la Base de Datos

function eliminarArticuloContrato( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from articulos_contratos "
		. "where codigo_articulo = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
		
#ELIMINAR UN CONTRATO 

function borrarContrato( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from contratos "
		. "where codigo_contrato = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
		
#ELIMINAR UN CONTRATO 

function borrarAnexoContrato( $id ) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$delete_sql  = sprintf( "delete from anexos_contratos "
		. "where codigo_anexo = %d",
		$id);
		
		$delete_result = $dbconn->execute( $delete_sql );		
		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR ARTICULOS DE CONTRATO (CLAUSULAS)


function listarArticulosContratos($codigo_contrato, $ini , $fin) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from articulos_contratos WHERE codigo_contratos = %d ORDER BY  nro_articulos ASC LIMIT %d , %d",$codigo_contrato, $ini , $fin );
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_articulo" )] = array();
		$data[$list_result->value( "codigo_articulo" )][codigo_articulo]
		= $list_result->value( "codigo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_contratos]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_articulo" )][nro_articulos]
		= $list_result->value( "nro_articulos" );
		$data[$list_result->value( "codigo_articulo" )][texto_completo_articulo]
		= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][status_articulo]
		= $list_result->value( "status_articulo" );
		$data[$list_result->value( "codigo_articulo" )][titulo_articulo]
		= $list_result->value( "titulo_articulo" );

		}
		$list_result->close();
		
		return $data;
		}	

#BUSCAR EMPRESA DEL CONTRATO

function ListarContratoDefinido( $id ) 
{
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$list_sql  = sprintf( "SELECT * from contratos inner join empresas on contratos.codigo_empresa = empresas.codigo_empresa where codigo_contrato = %d",
		$id);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
			$data[$list_result->value( "codigo_contrato" )] = array();
			$data[$list_result->value( "codigo_contrato" )][codigo_contrato]
			= $list_result->value( "codigo_contrato" );
			$data[$list_result->value( "codigo_contrato" )][nombre_empresa]
			= $list_result->value( "nombre_empresa" );
			}
		$list_result->close();
		
		return $data;
	}


//FUNCION LISTAR ARTICULOS


function buscarArticulosContratos($codigo_articulo) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from articulos_contratos WHERE codigo_articulo = %d",$codigo_articulo);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_articulo" )] = array();
		$data[$list_result->value( "codigo_articulo" )][codigo_articulo]
		= $list_result->value( "codigo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_contratos]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_articulo" )][nro_articulos]
		= $list_result->value( "nro_articulos" );
		$data[$list_result->value( "codigo_articulo" )][texto_completo_articulo]
		= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][status_articulo]
		= $list_result->value( "status_articulo" );
		$data[$list_result->value( "codigo_articulo" )][titulo_articulo]
		= $list_result->value( "titulo_articulo" );
		
		}
		$list_result->close();
		
		return $data;
		}	
//LISTAR ARTICULOS CONTRATOS SIN PAGINAR

function listarArticulosContratossinPaginar($codigo_contrato) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select art.*, tit.codigo_titulo_comparativo, tit.codigo_categoria_titulo  from articulos_contratos art INNER JOIN titulos tit ON art.codigo_titulo_comparativo =  tit.codigo_titulo_comparativo WHERE codigo_contratos = %d ORDER BY  tit.codigo_categoria_titulo, tit.codigo_titulo_comparativo ASC",$codigo_contrato);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_articulo" )] = array();
		$data[$list_result->value( "codigo_articulo" )][codigo_articulo]
		= $list_result->value( "codigo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_contratos]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_articulo" )][nro_articulos]
		= $list_result->value( "nro_articulos" );
		$data[$list_result->value( "codigo_articulo" )][texto_completo_articulo]
		= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][status_articulo]
		= $list_result->value( "status_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_categoria_titulo]
		= $list_result->value( "codigo_categoria_titulo" );
		}
		$list_result->close();
		
		return $data;
		}			
		
	function listarArticulosContratossinPaginarTitulo($codigo_contrato,$titulo) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select art.*, tit.codigo_titulo_comparativo, tit.codigo_categoria_titulo  from articulos_contratos art INNER JOIN titulos tit ON art.codigo_titulo_comparativo =  tit.codigo_titulo_comparativo WHERE codigo_contratos = %d AND art.codigo_titulo_comparativo = ".$titulo." ORDER BY  tit.codigo_categoria_titulo, tit.codigo_titulo_comparativo ASC",$codigo_contrato);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_articulo" )] = array();
		$data[$list_result->value( "codigo_articulo" )][codigo_articulo]
		= $list_result->value( "codigo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_contratos]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_articulo" )][nro_articulos]
		= $list_result->value( "nro_articulos" );
		$data[$list_result->value( "codigo_articulo" )][texto_completo_articulo]
		= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][status_articulo]
		= $list_result->value( "status_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_categoria_titulo]
		= $list_result->value( "codigo_categoria_titulo" );
		}
		$list_result->close();
		
		return $data;
		}			
			
		function listarArticulosContratossinPaginarPorTitulo($codigo_contrato,$titulo) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select art.*, tit.codigo_titulo_comparativo, tit.codigo_categoria_titulo  from articulos_contratos art INNER JOIN titulos tit ON art.codigo_titulo_comparativo =  tit.codigo_titulo_comparativo WHERE codigo_contratos = %d AND tit.codigo_titulo_comparativo = %d ORDER BY  tit.codigo_categoria_titulo, tit.codigo_titulo_comparativo ASC",$codigo_contrato,$titulo);
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_articulo" )] = array();
		$data[$list_result->value( "codigo_articulo" )][codigo_articulo]
		= $list_result->value( "codigo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_contratos]
		= $list_result->value( "codigo_contrato" );
		$data[$list_result->value( "codigo_articulo" )][nro_articulos]
		= $list_result->value( "nro_articulos" );
		$data[$list_result->value( "codigo_articulo" )][texto_completo_articulo]
		= $list_result->value( "texto_completo_articulo" );
		$data[$list_result->value( "codigo_articulo" )][resumen_articulo]
		= $list_result->value( "resumen_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_titulo_comparativo]
		= $list_result->value( "codigo_titulo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][campo_comparativo]
		= $list_result->value( "campo_comparativo" );
		$data[$list_result->value( "codigo_articulo" )][status_articulo]
		= $list_result->value( "status_articulo" );
		$data[$list_result->value( "codigo_articulo" )][codigo_categoria_titulo]
		= $list_result->value( "codigo_categoria_titulo" );
		}
		$list_result->close();
		
		return $data;
		}			
//FUNCION LISTAR ANEXOS


function listarAnexosContratos($codigo_contrato, $ini , $fin) {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select * from anexos_contratos WHERE codigo_contrato = %d ORDER BY  codigo_anexo ASC LIMIT %d , %d",$codigo_contrato, $ini , $fin );
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) {
		$data[$list_result->value( "codigo_anexo" )] = array();
			$data[$list_result->value( "codigo_anexo" )][codigo_anexo]
			= $list_result->value( "codigo_anexo" );
			$data[$list_result->value( "codigo_anexo" )][codigo_contrato]
			= $list_result->value( "codigo_contrato" );
			$data[$list_result->value( "codigo_anexo" )][nombre_anexo]
			= $list_result->value( "nombre_anexo" );
			$data[$list_result->value( "codigo_anexo" )][descripcion_anexo]
			= $list_result->value( "descripcion_anexo" );
			$data[$list_result->value( "codigo_anexo" )][texto_completo_anexo]
			= $list_result->value( "texto_completo_anexo" );
			$data[$list_result->value( "codigo_anexo" )][pdf_anexo]
			= $list_result->value( "pdf_anexo" );
		}
		$list_result->close();
		
		return $data;
		}	

		
function agregarArticuloContrato($codigo_contrato, $nro_articulo , $texto_completo , $resumen_texto ,  $titulo, $campo, $titulo_articulo) {
		
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into articulos_contratos ( "
		."nro_articulos, "
		."codigo_contratos, "
		."texto_completo_articulo, "
		."resumen_articulo, "
		."codigo_titulo_comparativo, "
		."campo_comparativo, "
		."titulo_articulo"
		.") values ( "
		. "%s, %d, %s, %s, %d, %s,%s)",
		$dbconn->quoteString($nro_articulo),
		$codigo_contrato,
		$dbconn->quoteString($texto_completo),
		$dbconn->quoteString($resumen_texto),
		$titulo,
		$dbconn->quoteString($campo),
		$dbconn->quoteString($titulo_articulo));
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
		
function insertarAnexosContrato($nombre_anexo, $descripcion, $texto_completo, $archivo, $codigo_contrato) {
	
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = sprintf( "insert into anexos_contratos ( "
		."nombre_anexo, "
		."descripcion_anexo, "
		."texto_completo_anexo, "
		."pdf_anexo, "
		."codigo_contrato "
		.") values ( "
		. "%s, %s, %s, %s, %d)",
		$dbconn->quoteString($nombre_anexo),
		$dbconn->quoteString($descripcion),
		$dbconn->quoteString($texto_completo),
		$dbconn->quoteString($archivo),
		$codigo_contrato);
		print $insert_sql;
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
		
		
function contarArticulos() {
		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( 'local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from articulos_contratos " );
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



function asignarRegistro2 ( $ley_record ) {
	$this->codigo_contrato				= $ley_record->value('codigo_contratos');
	$this->nro_articulo					= $ley_record->value('nro_articulos');
	$this->titulo_articulo				= $ley_record->value('titulo_articulo');
	$this->texto_completo_articulo		= $ley_record->value('texto_completo_articulo');
	$this->resumen_articulo				= $ley_record->value('resumen_articulo');
	$this->codigo_titulo_comparativo	= $ley_record->value('codigo_titulo_comparativo');
	$this->campo_comparativo			= $ley_record->value('campo_comparativo');
	$this->Total						= $ley_record->value('Total');
	$this->titulo_articulo				= $ley_record->value('titulo_articulo');
}

function asignarRegistro3 ( $anexo_record ) {
	$this->codigo_contrato				= $anexo_record->value('codigo_contrato');
	$this->nombre_anexo					= $anexo_record->value('nombre_anexo');
	$this->descripcion_anexo			= $anexo_record->value('descripcion_anexo');
	$this->texto_completo_anexo			= $anexo_record->value('texto_completo_anexo');
	$this->pdf_anexo 					= $anexo_record->value('pdf_anexo');
	$this->Total						= $anexo_record->value('Total');
}

function asignarRegistro( $contrato_record ) {

	$this->codigo_contrato		=	$contrato_record->value('codigo_contrato');
	$this->pdf_auto		 		=	$contrato_record->value('pdf_auto_acta');
	$this->fecha_inicio			=	$contrato_record->value('fecha_de_inicio');
	$this->fecha_termino		=	$contrato_record->value('fecha_de_termino');
	$this->duracion				=	$contrato_record->value('duracion');
	$this->ambito_aplicacion	=	$contrato_record->value('ambito_aplicacion');
	$this->codigo_empresa		=	$contrato_record->value('codigo_empresa');
	$this->status_publicacion	=	$contrato_record->value('status_publicacion');

}	



}

if( ! isset($Contratos)) { 
	global $contratos;
	$contratos = New Contratos();
}
?>