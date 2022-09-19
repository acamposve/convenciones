<?php 
#======================================================================================
# Modificada por: Gabriel Battista
# Email: gabriel.battista@webconsult.com
#
#======================================================================================
# lib/objects/user.php
# Clase= User
# Descripcion: clase que contiene todas las funciones relacionadas con autentificacion
# y seguridad de usuarios
# Realizada por: Oscar Caldeira
# Email: Oscar.Caldeira@webconsult.com
#=======================================================================================
if( defined( '_USER' )) {
	return;
}

define( '_USER', '1.0' );

class User {

//FUNCION QUE DEVUELVE EL USUARIO SEGUN SU ID

 	function getId ( $id )  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from usuarios"
				. " where id = %s",
				$dbconn->quoteString($id));
		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}


//FUNCION QUE DEVUELVE EL USUARIO SEGUN SU ID

 	function update ( $nombre,  $password, $direcion, $empresa, $telefono, $email,  $Codigo_Tipo ,$login,$FecExp,$estatus)  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update usuarios set"
				. " Nombre_usuario = %s, "
				. " Clave_usuario = %s, "
				. " Codigo_empresa = %d, "
				. " Direccion_usuario = %s, "
				. " Telefono_usuario = %s, "
				. " Email_usuario = %s, "
				. " Codigo_tipo_usuario = %d, "
				. " fech_venc = %s, "
				. " estatus = %s "
				. " where Login_usuario = %s",
				$dbconn->quoteString($nombre),
				$dbconn->quoteString($password),
				$empresa,
				$dbconn->quoteString($direcion),
				$dbconn->quoteString($telefono),
				$dbconn->quoteString($email),
				$Codigo_Tipo,
				$dbconn->quoteString($FecExp),
				$dbconn->quoteString($estatus),
				$dbconn->quoteString($login));
		$update_result = $dbconn->execute( $query );
		$update_result->close();
		return true;
	}

//FUNCION UPDATE

 	function updatePerfil ( $nombre, $direcion, $empresa, $telefono, $email, $id_usuario)  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update usuarios set"
				. " Nombre_usuario = %s, "
				. " Codigo_empresa = %d, "
				. " Direccion_usuario = %s, "
				. " Telefono_usuario = %s, "
				. " Email_usuario = %s "
				. " where Login_usuario = %s",
				$dbconn->quoteString($nombre),
				$empresa,
				$dbconn->quoteString($direcion),
				$dbconn->quoteString($telefono),
				$dbconn->quoteString($email),
				$dbconn->quoteString($id_usuario));

		$update_result = $dbconn->execute( $query );
		$update_result->close();
		return true;
	}

//FUNCION UpdatePassword ACTUALIZA EL PASSWORD DEL USUARIO

 	function updatePassword ( $password, $id_usuario)  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update usuarios set"
				. " Clave_usuario = %s "
				. " where Login_usuario = %s",
				$dbconn->quoteString($password),
				$dbconn->quoteString($id_usuario));

		$update_result = $dbconn->execute( $query );
		$update_result->close();
		return true;
	}



//FUNCION QUE DEVUELVE LA EXISTENCIA DEL USUARIO

 	function getLogin ( $login )  {

		include( 'local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Select * from usuario"
				. " where Login LIKE %s",
				$dbconn->quoteString($login ));


		$query_result = $dbconn->execute( $query );
		if( $query_result->realcount() == 1 ) {

			$this->assignRecord( $query_result );
			return true;
		} else {

			return false;
		}

	}

//FUNCION LOGIN--> Permite autentificar al usuario
function login($user, $password){
include( '../local/configuracion.php' );
$dbconn = $LibSite->openDBConn(0);
$today = getdate();
$qQuery = "Select * from usuarios WHERE Login_usuario LIKE '".$user."' AND Clave_usuario LIKE '".$password."'";
$query_result = $dbconn->execute( $qQuery  );
if( $query_result->realcount() == 1 ) {
$this->loged = false;
$this->assignRecord( $query_result );
$this->assignUserSession();
$query_result->close();

return true;
} else {
$query_result->close();
return false;
}

}

//DESPUES DEL LOGIN, IP Y HORA DE LOGIN
function login_historico($login, $date,$ip, $tipo){
	include( '../local/configuracion.php' );
	$dbconn = $LibSite->openDBConn(0);
		$qQuery = "INSERT INTO `usuarios_login` (`Login_usuario` ,`date_login` ,`ip_login`, `tipo` )VALUES ('".$login."', '".$date."', '".$ip."','1')";
		$query_result = $dbconn->execute( $qQuery  );

	}




//FUNCION DE BLOQUE DE USUARIO
function Bloqueo ($id_usuario)  {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$query = sprintf("Update usuarios set"
				. " estatus = %s "
				. " where Login_usuario = %s",
				$dbconn->quoteString("BLO"),
				$dbconn->quoteString($id_usuario));

		$update_result = $dbconn->execute( $query );
		$update_result->close();
		return true;
	}




#LISTAR USUARIOS DE LA BASE DE DATOS

function listarUsuarios( $ini , $fin, $filtro) {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = "select * from usuarios where Nombre_usuario  like '%".$filtro."%'  ORDER BY  Login_usuario,Nombre_usuario ASC   LIMIT ".$ini.", ".$fin."";
		$list_result = $dbconn->execute( $list_sql );
		reset($list_result);
		while( $list_result->next() ) {
			$data[$list_result->value( "Login_usuario" )] = array();
			$data[$list_result->value( "Login_usuario" )][id]= $list_result->value( "id" );
			$data[$list_result->value( "Login_usuario" )][Login_usuario]= $list_result->value( "Login_usuario" );
			$data[$list_result->value( "Login_usuario" )][Nombre_usuario]= $list_result->value( "Nombre_usuario" );
			$data[$list_result->value( "Login_usuario" )][Clave_usuario]= $list_result->value( "Clave_usuario" );
			$data[$list_result->value( "Login_usuario" )][Codigo_tipo_usuario]	= $list_result->value( "Codigo_tipo_usuario" );
			$data[$list_result->value( "Login_usuario" )][password]	= $list_result->value( "Password" );
			$data[$list_result->value( "Login_usuario" )][telefonos]= $list_result->value( "Telefono" );
			$data[$list_result->value( "Login_usuario" )][email]= $list_result->value( "Correo_E" );
			$data[$list_result->value( "Login_usuario" )][fech_venc]= $list_result->value( "fech_venc" );
			$data[$list_result->value( "Login_usuario" )][estatus]= $list_result->value( "estatus" );
		}
		$list_result->close();

		return $data;
		}

//FUNCION CONTAR USUARIO PARA PAGINADOR

function contarUsuarios() {
		include( '../local/configuracion.php' );
		$data = array();
		$dbconn = $LibSite->openDBConn(0);

		$list_sql  = sprintf( "select count(*) as Total from usuarios " );
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

//FUNCION ELIMINAR USUARIO

function delete( $id ) {
		include( '../local/configuracion.php' );


		$dbconn = $LibSite->openDBConn(0);

		$delete_sql  = sprintf( "delete from usuarios "
		. "where id = %s",
		$dbconn->quoteString($id));

		$delete_result = $dbconn->execute( $delete_sql );

		$delete_result->close();
		return true;

		}
//FUNCION INSERT


function insert($nombre, $login, $clave, $direccion, $email, $telefono, $empresa, $tipo,$fech_ven,$estatus) {

		include( '../local/configuracion.php' );

		$dbconn = $LibSite->openDBConn(0);

		$insert_sql ="INSERT INTO `usuarios` ( `Login_usuario` , `Nombre_usuario` , `Codigo_empresa` , `Codigo_tipo_usuario` , `Direccion_usuario` , `Telefono_usuario` , `Email_usuario` , `Clave_usuario` , `fech_venc` , `estatus` )  VALUES ('".$login."', '	".$nombre."', '		".$empresa."', '".$tipo."','".$direccion."', '".$telefono."', '".$email."', '".$clave."', '".$fech_ven."', '".$estatus."')";

		$insert_result = $dbconn->execute( $insert_sql );
		$insert_result->close();
		return true;
		}

//FUNCION ASGINAR REGISTROS

function assignRecord( $user_record ) {
$this->id		= $user_record->value('id');
$this->Login_usuario		= $user_record->value('Login_usuario');
$this->password 			= $user_record->value('Clave_usuario');
$this->codigo_empresa 		= $user_record->value('Codigo_empresa');
$this->direccion_usuario 	= $user_record->value('Direccion_usuario');
$this->telefono_usuario 	= $user_record->value('Telefono_usuario');
$this->email_usuario 		= $user_record->value('Email_usuario');
$this->Nombre_usuario 		= $user_record->value('Nombre_usuario');
$this->Codigo_tipo_usuario	= $user_record->value('Codigo_tipo_usuario');
$this->fech_venc			= $user_record->value('fech_venc');
$this->estatus				= $user_record->value('estatus');
$this->Total 					= $user_record->value('Total');

}

function assignUserSession() {
session_start();
$_SESSION["login"] 		= $this->Login_usuario;
$_SESSION["nombre"] 	= $this->Nombre_usuario;
$_SESSION["tipo"]		= $this->Codigo_tipo_usuario;
$_SESSION["fecha_vence"]	= $this->fech_venc;
}

}

if( ! isset($user)) {
	global $user;
	$user = New User();
}
?>
