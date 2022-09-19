<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$nombre=$_POST['nombrepais'];
$error=$pais->insert($nombre);

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="SE HA AGREGADO UN PAIS";
include('../lib/funciones/php/redirect.php');
//redirect($refer);
}
}

?>