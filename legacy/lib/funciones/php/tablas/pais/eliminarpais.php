<?
$enviar=$_GET['flag'];
$id_pais=$_GET['id'];
$var=$pais->getId($id_pais);
$nombre=$pais->nombre;
if ($enviar =="SI"){
$flag_del=true; ?>
<?
if($flag_del){
$error=$pais->delete($id_pais );
$error=$estado->deleteporPais($id_pais);
$error=$localidad->deleteporPais($id_pais);

			?>
            
		<script type="text/javascript">
		<!--
		window.location = "?url=tablas&tbl=<?php echo $tabla; ?>"
		//-->
		</script>
			<?	


}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=tablas&tbl=Paises&accion=list";
-->
</script>

<?
}
}

?>

