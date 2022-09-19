<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$pantalla1=$_POST['pantalla'];
$tipo=$_POST['tipo'];
$error=$seguridad->insert($tipo, $pantalla1);

if ($error){
$msg="LA SEGURIDAD SE HA AGREGADO";
}
}

?>