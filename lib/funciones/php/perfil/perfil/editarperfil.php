<?php
$enviar=$_REQUEST['enviar'];
$id_user=$_SESSION["login"] ;
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
$direccion=$_POST['direccion'];
$empresa=$_POST['empresa'];
$telefono=$_POST['telefono'];
$email=$_POST['email'];
$error=$user->updatePerfil($nombre,$direccion, $empresa, $telefono, $email, $id_user);
if ($error){


?>
<script type="text/javascript" language="javascript">
<!--
alert("Perfil Actualziado");

location.href="index.php?url=principal";
-->
</script>


<?
}
}

?>