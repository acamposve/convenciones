<?php
if (isset($_POST['id']))
{
	$id1=$_POST['id'];
}
$login='';
$pass='';
$enviar=$_REQUEST['submit'];

if ($_POST['enviar'] == "1") {
	$login=$_REQUEST['login'];
	$pass=$_REQUEST['pass'];
	$date= date("Y-m-d h:i:s A");
	$ip = $_SERVER['REMOTE_ADDR'];
	if( $user->login(  $login,  $pass )) {
		$user->login_historico(  $login, $date,$ip,"1" );
		$status	=	$user->estatus;
		if 	($status!="BLO"){
			$refer= "?url=principal";
			include( '../lib/funciones/php/redirect.php');
			redirect( $refer );
		}elseif($status=="BLO"){
			$refer= "?url=logout&motivo=BLO";
			include( '../lib/funciones/php/redirect.php');
			redirect( $refer );
		}
} else {
echo "datos errados";
	}
}
?>
