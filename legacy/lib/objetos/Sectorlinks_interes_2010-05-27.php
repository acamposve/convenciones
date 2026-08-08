<? 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php   
# Clase= User
# Descripcion: clase que contiene todas las funciones relacionadas con autentificacion
# y seguridad de usuarios
# Realizada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#=======================================================================================
if( defined( '_SECTORLINKS' )) {
	return;
}

define( '_SECTORLINKS', '1.0' );

class SLinks {

//FUNCION QUE BUSCA EN LA TABLA DE links_interes LOS TIPOS DE SECTORES
//	1- Sector Sindical
//	2- Ministerio de Trabajo
//	3- Sector Empresarial
//	NO SE POSEE ADMINISTRADOR NI TABLA ASOCIADA REAL.

	function SLinksDistinct() {
 		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = "select distinct (lit_Sector) as Sectores from links_interes order by lit_Sector";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
				$data[$list_result->value( "Sectores" )] = array();
				$data[$list_result->value( "Sectores" )][Sector]	 = $list_result->value( "Sectores" );
			}
		$list_result->close();
		return $data; 
		}		

//FUNCION LISTAR LINKS DE INTERES SEGUN SU SECTOR
	function ListarLinksISector( $ini , $fin, $filtro, $sector) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = "select * from links_interes  WHERE lit_Detalle LIKE '%".$filtro."%' and lit_Sector='".$sector."' ORDER BY  lit_Detalle ASC LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );	
/* 		print_r ($list_result);
		exit(); */
		reset($list_result);
		while( $list_result->next() ) 
			{
				$data[$list_result->value( "lit_IdLink" )] = array();
				$data[$list_result->value( "lit_IdLink" )][lit_IdLink]	 = $list_result->value( "lit_IdLink" );
				$data[$list_result->value( "lit_IdLink" )][lit_Sector]	 = $list_result->value( "lit_Sector" );
				$data[$list_result->value( "lit_IdLink" )][lit_Detalle] = $list_result->value( "lit_Detalle" );
				$data[$list_result->value( "lit_IdLink" )][lit_Link] 	= $list_result->value( "lit_Link" );
			}
		$list_result->close();
		return $data;
		}	
//FUNCION CONTAR LINKS POR SECTOR
	function contarLinksSector($sector) {
		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = sprintf( "select count(*) as Total from links_interes where lit_Sector='".$sector."'" );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
			$this->assignRecordSector($list_result);
			$list_result->close();
		return true;
		} else {
			$list_result->close();
		return false;
		}
	}
//FUNCION QUE DEVUELVE UN SECTOR PARA UN LINK DE INTERES ESPECIFICO
 	function getId ( $id )  {
/* 		if( $id== "" || $id== 0) return false;
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$query = sprintf("Select * from links_interes"
				. " where lit_IdLink = %d",
				$id);
		$query_result = $dbconn->execute( $query );		
		if( $query_result->realcount() == 1 ) {
			
			$this->assignRecord( $query_result );	
			return true;
		} else {
			return false;
		} */
	}
//FUNCION UPDATE 
  	function update ( $idLinkInteres,$lit_Sector,$lit_Detalle,$lit_Link )  {
/* 		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		session_start();
		$usuario=$_SESSION['login'];
		$fecha = date("Y-m-d H:m:s");
		$query  ="UPDATE `links_interes` SET `lit_Sector` = '".$lit_Sector."',";
		$query .="`lit_Detalle` = '".$lit_Detalle."',";
		$query .="`lit_Link` = '".$lit_Link."',";
		$query .="`lit_UsuarioMod` = '".$usuario."',";
		$query .="`lit_FechaMod` = '".$fecha."' WHERE `links_interes`.`lit_IdLink` =".$idLinkInteres ;
		$update_result = $dbconn->execute( $query );	
		$update_result->close();	
		return true;	 */
	}	
//FUNCION ELIMINAR UN SECTOR PARA LINK DE INTERES
	function delete( $id ) {
/* 		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$delete_sql  = sprintf( "delete from links_interes "
		. "where lit_IdLink = %d",
		$id);
		$delete_result = $dbconn->execute( $delete_sql );		
		$delete_result->close();
		return true; */
		}	
//FUNCION LISTAR SECTORES DE LINKS DE INTERES
	function ListarLinksI( $ini , $fin, $filtro) {
/* 		include( '../local/configuracion.php' );		
		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = "select * from links_interes  WHERE lit_Detalle LIKE '%".$filtro."%' ORDER BY  lit_Detalle ASC LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );	
		reset($list_result);
		while( $list_result->next() ) 
			{
				$data[$list_result->value( "lit_IdLink" )] = array();
				$data[$list_result->value( "lit_IdLink" )][lit_IdLink]	 = $list_result->value( "lit_IdLink" );
				$data[$list_result->value( "lit_IdLink" )][lit_Sector]	 = $list_result->value( "lit_Sector" );
				$data[$list_result->value( "lit_IdLink" )][lit_Detalle] = $list_result->value( "lit_Detalle" );
				$data[$list_result->value( "lit_IdLink" )][lit_Link] 	= $list_result->value( "lit_Link" );
			}
		$list_result->close();
		return $data; */
		}		
//FUNCION INSERT SECTORES LINK INTERES
	function insert($lit_Sector,$lit_Detalle, $lit_Link ) {
/* 		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		session_start();
		$usuario=$_SESSION['login'];
		$fecha = date("Y-m-d  H:i:s");
		$insert_sql = "insert into links_interes ( lit_Sector, lit_Detalle, lit_Link, lit_UsuarioCrea, lit_FechaCrea, lit_UsuarioMod, lit_FechaMod ) values ( ";
		$insert_sql .= 							$lit_Sector.", '".$lit_Detalle."', '".$lit_Link."','".$usuario."', '".$fecha."', '".$usuario."' ,'".$fecha."')";
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
 */		}	
//FUNCION CONTAR SECTORES
	function contarLinks() {
		include( '../local/configuracion.php' );		
/* 		$data = array();
		$dbconn = $LibSite->openDBConn(0);
		$list_sql  = sprintf( "select count(*) as Total from links_interes " );
		$list_result = $dbconn->execute( $list_sql );	
		if( $list_result->realcount() == 1 ) {
		$this->assignRecord($list_result);
		$list_result->close();
		return true;
		} else {
		$list_result->close();
		return false; 
		}*/
	}	
//FUNCION ASIGNAR REGISTROS
	function assignRecord( $user_record ) {
		$this->Total	= $user_record->value('Total');
	}	
	function assignRecordSector( $user_record ) {
		$this->TotalLinksSector	= $user_record->value('Total');
	}	

	
}
	if( ! isset($SectorLinkI)) { 
		global $SectorLinkI;
		$SectorLinkI = New SLinks();
	}
?>