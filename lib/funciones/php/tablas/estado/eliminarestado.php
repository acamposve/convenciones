<?
$enviar=$_GET['flag'];
$id_estado=$_GET['id'];
$id_pais=$_GET['id_pais'];
$var=$estado->getId($id_estado);
$nombreestado=$estado->nombre;
$var=$pais->getId($id_pais);
$nombrepais=$pais->nombre;
if ($enviar =="SI"){
 $flag_del=true; ?>
<?
if($flag_del){
$error=$estado->delete($id_estado );
$error=$localidad->deleteporEstado($id_estado );

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

location.href="?url=tablas&tbl=Estados&accion=listar";
-->
</script>

<?
}
}

?>

