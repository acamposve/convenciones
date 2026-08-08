<?php 
$id1=$_POST['id'];
$login='';
$pass='';
$enviar=$_REQUEST['submit'];

if ($enviar == "1") {

$login=$_REQUEST['login'];
$pass=$_REQUEST['pass'];

		if( $user->login(  $login,  $pass )) { 
			
			$refer= "?url=principal";
			include( '../lib/funciones/php/redirect.php');
			redirect( $refer );
	
		} else {
			if( isset( $_REQUEST['login' ]))
			$message = $LibStr->no_loggin_check;
			$error_msg = "<div align=center >Su login y/o contraseña se encuentran errados</div>";
		}


}


?>
