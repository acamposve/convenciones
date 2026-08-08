<?php
	switch ($url) {
	 case "login":
	 	include('../lib/objetos/user.php');
		include('../lib/funciones/php/login/login.php');
		$url= '../admin/modulos/login/index.php';
		$modulo='login';
	 	break ;
	case "seguridad":
		$url= '../admin/modulos/seguridad/index.php';
		$modulo='seguridad';
		$titulo= 'Seguridad del sistema';
	 	break ;
	 case "principal":
		 $url='../admin/modulos/principal/index.php';
		 $modulo='principal';
		 $titulo = 'Menu Principal';
		 break;
	 case "contratos":
		 $url='../admin/modulos/contratos/index.php';
		 $modulo='contratos';
		 $titulo = 'Convenciones Colectivas';
		 break;
	 case "tablas":
		 $url='../admin/modulos/tablas/index.php';
		 $modulo='tablas';
		 $titulo= 'Tablas Basicas';
	 	break ;
	 case "perfil":
		 $url='../admin/modulos/perfil/index.php';
		 $modulo='perfil';
		 $titulo= 'Perfil de Usuario';
	 	break ;
	 case "leyes":
		 $url='../admin/modulos/leyes/index.php';
		 $modulo='perfil';
		 $titulo= 'Leyes Marco';
	 	break ;
	 case "logout":
		//Logout
		$con = mysql_connect("localhost", "pv014_admcccol", "b31sb0LA");
		mysql_select_db("CCCOL", $con);
		$login=$_SESSION["login"];;
		$pass=$_REQUEST['pass'];
		$date= date("Y-m-d h:i:s A");
		$ip = $_SERVER['REMOTE_ADDR'];
		$qQuery = "INSERT INTO `usuarios_login` (`Login_usuario` ,`date_login` ,`ip_login`, `tipo` )VALUES ('".$login."', '".$date."', '".$ip."','2')";
		$list_result	 = mysql_query($qQuery);
		//VERIFICAMOS SI EXISTE UN BLOQUE DE USUARIO
		$motivo=$_GET['motivo'];

		//Destruir la Sesion
		session_destroy();
		if ($motivo==""){
			// SI ES NEGATIVO
			$refer='?url=login';
		}else{
			//USUARIO BLOQUEADO, NO PERMITE ACCESO Y DA EL MENSAJE
			$refer='?url=login&motivo=BLO';
		}

		include('../lib/funciones/php/redirect.php');
		redirect($refer);
		break ;
	 default:
		$url='../admin/modulos/principal/index.php';
		$modulo='principal';
		$titulo = 'Menu Principal';
		break;
	 }
?>
