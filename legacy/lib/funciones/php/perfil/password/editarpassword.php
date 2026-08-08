<?php
$enviar=$_REQUEST['enviar'];
$id_user=$_SESSION["login"] ;
$var=$user->getId($id_user);
$login=$user->Login_usuario; 
$password=$user->password; 



if ($enviar ==1){
$pass=$_POST['pass1'];
$repass=$_POST['repass'];
if($pass==$repass){
$error=$user->updatePassword($pass, $id_user);
if ($error){
?>
<script type="text/javascript" language="javascript">
<!--
alert("Contraseña Actualziada");

location.href="index.php?url=principal";
-->
</script>
<?
}
}else{
?>
<script type="text/javascript" language="javascript">
<!--
alert("LAS CONTRASEÑAS NO COINCIDEN INTENTE DE NUEVO");
form.pass1.focus( );
-->
</script>
<?
}
}

?>
