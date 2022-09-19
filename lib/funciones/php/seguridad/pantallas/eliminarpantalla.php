<?
$enviar=$_GET['flag'];
$id_pantalla=$_GET['id'];
$var=$pantalla->getId($id_pantalla);
$nombre=$pantalla->nombre_pantalla;
if($enviar =="SI"){
$flag_del=true; 
?>

<? $flag_del=true; ?>
}
-->
</script>

<?
if($flag_del){
$error=$pantalla->delete($id_pantalla );
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=seguridad&tbl=Pantallas&accion=listar";
-->
</script>

<?
}
}

?>

