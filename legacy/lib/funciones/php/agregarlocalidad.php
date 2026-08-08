<?
$enviar=$_POST['Submit'];
$id_pais=$_POST['id_pais'];
$id_estado=$_POST['id_estado'];
$nombre=$_POST['nombrelocalidad'];


if ($enviar !=""){


$error=$localidad->insert($nombre, $id_pais,$id_estado);

if ($error){
$refer="?url=tablas&tbl=pais";
$msg="SE HA AGREGADO UNA LOCALIDAD";
include('../lib/funciones/php/redirect.php');
//redirect($refer);
}
}

?>