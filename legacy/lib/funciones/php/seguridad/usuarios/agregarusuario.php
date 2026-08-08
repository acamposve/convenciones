<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
	$nombre=$_POST['nombre'];

	$login=$_POST['login'];
	$clave=$_POST['clave'];
	$direccion=$_POST['direccion'];
	$email=$_POST['email'];
	$telefono=$_POST['telefono'];
	$empresa=$_POST['empresa'];
	$tipo=$_POST['tipo'];
	$fech_ven =$_POST['fech_ven'];
	$estatus =$_POST['estatus'];

	
	if(($nombre=="")||($login=="")||($clave=="")||(($empresa==0)&&($tipo!=1))||($tipo=="")||($estatus=="")||($fech_ven==""))
		{
			?>
			<script type="text/javascript">
			<!--
			alert("Todos los Campos Son Obligatorios");
			window.location = "?url=seguridad&tbl=Usuarios&accion=agregar";
			//-->
			</script>
			<?	
		}else{
				$error=$user->insert($nombre, $login, $clave, $direccion, $email, $telefono, $empresa, $tipo,$fech_ven,$estatus);
				if ($error){
					$msg="SE HA AGREGADO UN USUARIO";
					?>
					<script type="text/javascript">
					<!--
					window.location = "?url=seguridad&tbl=Usuarios&accion=listar";
					//-->
					</script>
					<?	
				}
		}

	}


?>