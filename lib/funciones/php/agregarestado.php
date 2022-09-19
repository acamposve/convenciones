<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$id_pais=$_POST['id_pais'];
$nombre=$_POST['nombreestado'];
$error=$estado->insert($nombre, $id_pais);

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="SE HA AGREGADO UN ESTADO";
include('../lib/funciones/php/redirect.php');
//redirect($refer);
}
}

?>