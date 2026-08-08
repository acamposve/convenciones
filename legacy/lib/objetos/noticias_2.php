<? 

#======================================================================================
# lib/objects/noticias.php   
# Clase= noticias
# Descripcion: clase que contiene todas las funciones relacionadas con Noticias
# y Categorias de Noticias.
# Realizada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#=======================================================================================
if( defined( '_NOTICIAS' )) {
	return;
}

define( '_NOTICIAS', '1.0' );

class Noticias {

//FUNCION QUE DEVUELVE UNA NOTICIA ESPECIFICA
 
 	function getId ( $id )  {
		if( $id== "" || $id== 0) return false;
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query 	= 	"Select * from noticias ".
					"INNER JOIN categoria_noticias ON noticias.`codigo_categoria` = categoria_noticias.`codigo_categoria` " .
					"where codigo_noticia = ".$id;
		$query_result = $dbconn->execute( $query );		
		while( $query_result->next() ) 
			{
				$data[$query_result->value( "codigo_noticia" )] = array();
				$data[$query_result->value( "codigo_noticia" )][codigo_noticia] 	= $query_result->value( "codigo_noticia" );
				$data[$query_result->value( "codigo_noticia" )][codigo_categoria] 	= $query_result->value( "codigo_categoria" );
				$data[$query_result->value( "codigo_noticia" )][titulo] 			= $query_result->value( "titulo" );
				$data[$query_result->value( "codigo_noticia" )][fecha_publicacion] 	= $query_result->value( "fecha_publicacion" );
				$data[$query_result->value( "codigo_noticia" )][estatus_noticia] 	= $query_result->value( "estatus_noticia" );
				$data[$query_result->value( "codigo_noticia" )][nombre_categoria] 	= $query_result->value( "nombre_categoria" );
				$data[$query_result->value( "codigo_noticia" )][resumen_noticia] 	= $query_result->value( "resumen_noticia" );
				$data[$query_result->value( "codigo_noticia" )][noticia_completa] 	= $query_result->value( "noticia_completa" );
				$data[$query_result->value( "codigo_noticia" )][imagen_1] 			= $query_result->value( "imagen_1" );
				$data[$query_result->value( "codigo_noticia" )][imagen_2] 			= $query_result->value( "imagen_2" );
				$data[$query_result->value( "codigo_noticia" )][origen] 			= $query_result->value( "origen" );
			}
		$query_result->close();
		return $data;
	
	}
 
//FUNCION PARA LISTAR CATEGORIAS DE NOTICIAS
 	function listarCategoriasNoticias ()  {

		include( '../local/configuracion.php' );
print $codigo_categoria;
		$dbconn = $LibSite->openDBConn(0);	
		
		$query 	= 	"Select * from categoria_noticias ORDER BY `nombre_categoria` ";
		$query_result = $dbconn->execute( $query );		
		while( $query_result->next() ) 
			{	
				$data[$query_result->value( "codigo_categoria" )] = array();
				$data[$query_result->value( "codigo_categoria" )][codigo_categoria] 	= $query_result->value( "codigo_categoria" );
				$data[$query_result->value( "codigo_categoria" )][nombre_categoria]	 	= $query_result->value( "nombre_categoria" );
				$data[$query_result->value( "codigo_categoria" )][descripcion_categoria]= $query_result->value( "descripcion_categoria" );

			}
		return $data;
	}
	


//FUNCION UPDATE NOTICIAS
 
 	function update ( $id_noticia,$titulo,$fecha,$texto_completo,$texto_resumen,$categorias_noticias,$estatus_noticia, $origen )  {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query = 	"UPDATE noticias SET resumen_noticia = '".$texto_resumen."', noticia_completa = '".$texto_completo."', ". 
					"codigo_categoria = '".$categorias_noticias."',titulo = '".$titulo."', fecha_publicacion = '".$fecha."', ".
					"estatus_noticia = '".$estatus_noticia."', origen = '".$origen."' WHERE `noticias`.`codigo_noticia` =".$id_noticia;
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	

//FUNCION UPDATE IMAGEN 1 NOTICIAS
 
 	function update_imagen1 ( $id_noticia,$img1 )  {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query = 	"UPDATE noticias SET imagen_1  = '".$img1."' WHERE `noticias`.`codigo_noticia` =".$id_noticia;
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
//FUNCION UPDATE IMAGEN 2 NOTICIAS
 
 	function update_imagen2 ( $id_noticia,$img1 )  {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query = 	"UPDATE noticias SET imagen_2  = '".$img1."' WHERE `noticias`.`codigo_noticia` =".$id_noticia;
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	


//ELIMINA NOTICIA

function eliminarNoticias( $id ) {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$delete_sql  =  "delete from noticias where codigo_noticia = ".$id;
		$delete_result = $dbconn->execute( $delete_sql );		
		$delete_result->close();
		return true;
 	
		}	
//FUNCION LISTAR NOTICIAS ADMINISTRADOR
function listarNoticias( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from noticias ".
					 "INNER JOIN categoria_noticias ON noticias.codigo_categoria = categoria_noticias.codigo_categoria ".
					 " WHERE titulo  LIKE '%".$filtro."%' order by nombre_categoria,fecha_publicacion,titulo ASC LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
				$data[$list_result->value( "codigo_noticia" )] = array();
				$data[$list_result->value( "codigo_noticia" )][codigo_noticia] 		= $list_result->value( "codigo_noticia" );
				$data[$list_result->value( "codigo_noticia" )][codigo_categoria] 	= $list_result->value( "codigo_categoria" );
				$data[$list_result->value( "codigo_noticia" )][titulo] 				= $list_result->value( "titulo" );
				$data[$list_result->value( "codigo_noticia" )][fecha_publicacion] 	= $list_result->value( "fecha_publicacion" );
				$data[$list_result->value( "codigo_noticia" )][estatus_noticia] 	= $list_result->value( "estatus_noticia" );
				$data[$list_result->value( "codigo_noticia" )][nombre_categoria] 	= $list_result->value( "nombre_categoria" );
			}
		$list_result->close();
		return $data;
		}		
//FUNCION LISTAR NOTICIAS  PUBLICADAS ADMINISTRADOR
function listarNoticias_Publciadas( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from noticias ".
					 "INNER JOIN categoria_noticias ON noticias.codigo_categoria = categoria_noticias.codigo_categoria ".
					 " WHERE titulo  LIKE '%".$filtro."%' and estatus_noticia='Publicar' order by nombre_categoria, fecha_publicacion, titulo ASC LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
				$data[$list_result->value( "codigo_noticia" )] = array();
				$data[$list_result->value( "codigo_noticia" )][codigo_noticia] 		= $list_result->value( "codigo_noticia" );
				$data[$list_result->value( "codigo_noticia" )][codigo_categoria] 	= $list_result->value( "codigo_categoria" );
				$data[$list_result->value( "codigo_noticia" )][titulo] 				= $list_result->value( "titulo" );
				$data[$list_result->value( "codigo_noticia" )][fecha_publicacion] 	= $list_result->value( "fecha_publicacion" );
				$data[$list_result->value( "codigo_noticia" )][estatus_noticia] 	= $list_result->value( "estatus_noticia" );
				$data[$list_result->value( "codigo_noticia" )][nombre_categoria] 	= $list_result->value( "nombre_categoria" );
				$data[$list_result->value( "codigo_noticia" )][imagen_1] 	= $list_result->value( "imagen_1" );
			}
		$list_result->close();
		return $data;
		}		
//FUNCION INSERTAR NOTICIAS
function insertarNoticias ( $titulo,$fecha,$texto_completo,$texto_resumen,$categorias_noticias,$estatus_noticia,$imag_1,$imag_2, $origen )  {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = 	"INSERT INTO `noticias` (`resumen_noticia` ,`noticia_completa` ,`codigo_categoria` ,`titulo` ,`fecha_publicacion` ,`estatus_noticia`,`imagen_1`,`imagen_2`,`origen` )".
						"VALUES ( '".$texto_resumen."', '".$texto_completo."', '".$categorias_noticias."', '".$titulo."', '".$fecha."','Pendiente','".$imag_1."','".$imag_2."','".$origen."' )";
		print $insert_sql ;
		$insert_result = $dbconn->execute( $insert_sql );
	
		$insert_result->close();
		return true;
		}	
		
//FUNCION OBTENER ULTIMA NOTICIA
 	function obtenerIdUltimaNoticia()  {

		ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$query = sprintf(" Select * from noticias order by codigo_noticia DESC LIMIT 0 , 1 ");

		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$codigo_contrato = $query_result->value( "codigo_noticia" );	
			return $codigo_contrato;
		} 
		
	}


//FUNCION LISTAR CATEGORIAS DE NOTICIAS ADMINISTRADOR
function listarCategoriasNoticias_Adm( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from categoria_noticias  ".
					 " WHERE nombre_categoria  LIKE '%".$filtro."%' order by nombre_categoria ASC LIMIT ".$ini.", ".$fin."";

		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
				$data[$list_result->value( "codigo_categoria" )] = array();
				$data[$list_result->value( "codigo_categoria" )][codigo_categoria] 		= $list_result->value( "codigo_categoria" );
				$data[$list_result->value( "codigo_categoria" )][nombre_categoria] 		= $list_result->value( "nombre_categoria" );
				$data[$list_result->value( "codigo_categoria" )][descripcion_categoria] 	= $list_result->value( "descripcion_categoria" );
			}
		$list_result->close();
		return $data;
		}	

		
		
		
//FUNCION CONTAR NOTICIAS PARA PAGINADOR
function contarNoticias() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from noticias " );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
			//$this->assignRecord($list_result);
		reset($list_result);
			while( $list_result->next() ) 
				{
					$data[$list_result->value( "Total" )] = array();
					$data[$list_result->value( "Total" )][Total] 		= $list_result->value( "Total" );
				}
			return $list_result->value( "Total" );
		} else {
			$list_result->close();
			return false;
		}
		}	
//FUNCION CONTAR CATEGORIAS NOTICIAS PARA PAGINADOR
function contarCategoriasNoticias() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from categoria_noticias " );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
			//$this->assignRecord($list_result);
		reset($list_result);
			while( $list_result->next() ) 
				{
					$data[$list_result->value( "Total" )] = array();
					$data[$list_result->value( "Total" )][Total] 		= $list_result->value( "Total" );
				}
			return $list_result->value( "Total" );
		} else {
			$list_result->close();
			return false;
		}
		}	

		
//FUNCION CONTAR NOTICIAS PUBLICADAS PARA PAGINADOR
function contarNoticias_Publicadas() {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		
		$list_sql  = sprintf( "select count(*) as Total from noticias " );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
			//$this->assignRecord($list_result);
		reset($list_result);
			while( $list_result->next() ) 
				{
					$data[$list_result->value( "Total" )] = array();
					$data[$list_result->value( "Total" )][Total] 		= $list_result->value( "Total" );
				}
			return $list_result->value( "Total" );
		} else {
			$list_result->close();
			return false;
		}
		}	



//FUNCION INSERTAR CATEGORIA DE NOTICIAS 
function insertarCategoriasNoticias ( $nombre_categoria, $descrip_categoria)  {
		
		include( '../local/configuracion.php' );
		
		$dbconn = $LibSite->openDBConn(0);	
		
		$insert_sql = 	"INSERT INTO categoria_noticias (nombre_categoria , descripcion_categoria) VALUES ( '".$nombre_categoria."', '.".$descrip_categoria."' )";

		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	


		
		
		
//FUNCION QUE DEVUELVE UNA CATEGORIA DE NOTICIA ESPECIFICA
 
 	function getIdCategoria ( $id )  {
		if( $id== "" || $id== 0) return false;
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query 	= 	"Select * from categoria_noticias where codigo_categoria= ". $id;
		print $querry."<br>"; 
		$query_result = $dbconn->execute( $query );		
		while( $query_result->next() ) 
			{
			
				$data[$query_result->value( "codigo_CATEGORIA" )] = array();
				$data[$query_result->value( "codigo_CATEGORIA" )][codigo_categoria] 	= $query_result->value( "codigo_categoria" );
				$data[$query_result->value( "codigo_CATEGORIA" )][nombre_categoria] 	= $query_result->value( "nombre_categoria" );
				$data[$query_result->value( "codigo_CATEGORIA" )][descripcion_categoria]= $query_result->value( "descripcion_categoria" );
			}
		$query_result->close();
		return $data;
	
	}
//ELIMINA CATEGORIAS NOTICIA

function eliminarCategoriasNoticias( $id ) {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$delete_sql  =  "delete from categoria_noticias where codigo_categoria = ".$id;
		print $delete_sql;
		$delete_result = $dbconn->execute( $delete_sql );		
		$delete_result->close();
		return true;
 	
		}	

//FUNCION UPDATE CATEGORIAS NOTICIAS
 
 	function updateCategorias ( $id_categoria,$nombre_categoria,$descrip_categoria)  {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query = 	"UPDATE categoria_noticias SET nombre_categoria = '".$nombre_categoria."', descripcion_categoria = '".$descrip_categoria ."' ".
					" WHERE codigo_categoria =".$id_categoria;
		print $query;
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	
	}	
	

function assignRecord( $user_record ) {
	$this->codigo_noticia	= $user_record->value('codigo_noticia');
	$this->resumen_noticia	= $user_record->value('resumen_noticia ');
	$this->Total			= $user_record->value('Total');

	}	



}

if( ! isset($Noticias)) { 
	global $Noticias;
	$Noticias = New Noticias();
}
?>