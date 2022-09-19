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
if( defined( '_LINKS' )) {
	return;
}

define( '_PAIS', '1.0' );

class Links {
//FUNCION QUE DEVUELVE UN LINK DE INTERES ESPECIFICO
 	function getId ( $id )  {
		if( $id== "" || $id== 0) return false;
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
		}
	}
//FUNCION UPDATE 
  	function update ( $idLinkInteres,$lit_Sector,$lit_Detalle,$lit_Link )  {
		include( '../local/configuracion.php' );
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
		return true;	
	}	
//FUNCION ELIMINAR UN LINK DE INTERES
	function delete( $id ) {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		$delete_sql  = sprintf( "delete from links_interes "
		. "where lit_IdLink = %d",
		$id);
		$delete_result = $dbconn->execute( $delete_sql );		
		$delete_result->close();
		return true;
		}	
//FUNCION LISTAR LINKS DE INTERES
	function ListarLinksI( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );		
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
		return $data;
		}		
		
//FUNCION INSERT LINK INTERES
	function insert($lit_Sector,$lit_Detalle, $lit_Link ) {
		include( '../local/configuracion.php' );
		$dbconn = $LibSite->openDBConn(0);	
		session_start();
		$usuario=$_SESSION['login'];
		$fecha = date("Y-m-d  H:i:s");
		$insert_sql = "insert into links_interes ( lit_Sector, lit_Detalle, lit_Link, lit_UsuarioCrea, lit_FechaCrea, lit_UsuarioMod, lit_FechaMod ) values ( ";
		$insert_sql .= 							$lit_Sector.", '".$lit_Detalle."', '".$lit_Link."','".$usuario."', '".$fecha."', '".$usuario."' ,'".$fecha."')";
		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}	
//FUNCION CONTAR LINKS
	function contarLinks() {
		include( '../local/configuracion.php' );		
		$data = array();
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
		}
	}
//FUNCION ASIGNAR REGISTROS
	function assignRecord( $user_record ) {
		
		$this->lit_IdLink			= $user_record->value('lit_IdLink');
		$this->lit_Sector			= $user_record->value('lit_Sector');
		$this->lit_Detalle			= $user_record->value('lit_Detalle');
		$this->lit_Link				= $user_record->value('lit_Link');
		$this->lit_UsuarioCrea		= $user_record->value('lit_UsuarioCrea');
		$this->lit_FechaCrea		= $user_record->value('lit_FechaCrea');
		$this->lit_UsuarioMod		= $user_record->value('lit_UsuarioMod');
		$this->lit_FechaMod			= $user_record->value('lit_FechaMod');
		$this->Total				= $user_record->value('Total');
	}	
}
	if( ! isset($LinkI)) { 
		global $LinkI;
		$LinkI = New Links();
	}
?>