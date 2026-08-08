<?
$enviar=$_GET['flag'];
$id_user=$_GET['id'];
$var=$user->getId($id_user);
$nombre=$user->Nombre_usuario;
if ($enviar =="SI"){

?>


<? $flag_del=true; ?>

</script>

<?
if($flag_del){
$error=$user->delete($id_user);
}

if ($error){
?>
<script language="javascript" type="text/javascript">
location.href="index.php?url=seguridad&tbl=Usuarios&accion=listar";

</script>

<?
}
}

?>

