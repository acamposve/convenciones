<?
$enviar=$_POST['enviar'];
$id_user=$_GET['id'];
$var=$user->getId($id_user);
$nombre=$user->Nombre_usuario;
$login=$user->Login_usuario; 
$password=$user->password; 
$direccion=$user->direccion_usuario; 
$empresa=$user->codigo_empresa;
$telefono=$user->telefono_usuario; 
$email=$user->email_usuario; 
$Codigo_Tipo=$user->Codigo_tipo_usuario; 
if ($enviar ==1){
$nombre=$_POST['nombre'];
$login=$_POST['login'];
$password=$_POST['clave'];
$direcion=$_POST['direccion'];
$empresa=$_POST['empresa'];
$telefono=$_POST['telefono'];
$email=$_POST['email'];
$Codigo_Tipo=$_POST['tipo'];
	$FecExp =$_POST['FecExp'];
	$estatus =$_POST['estatus'];


	if (($FecExp=="") ){
		?>
		<script type="text/javascript">
		<!--
		alert("Fecha de Vencimeinto  No puede ser  Nula");

		window.location = "?url=seguridad&tbl=Usuarios&accion=editar&id=<?php print $id_user?>";
		//-->
		</script>
		<?	
	}else{
		$error=$user->update($nombre,  $password, $direcion, $empresa, $telefono, $email,  $Codigo_Tipo ,$login,$FecExp,$estatus);
		if ($error){
		?>
		<script language="javascript">
		<!--
		location.href="?url=seguridad&tbl=Usuarios";
		-->
		</script>
		
		<?
		}
	}
}

?>