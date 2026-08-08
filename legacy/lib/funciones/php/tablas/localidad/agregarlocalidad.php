<?
$enviar=$_POST['Submit'];
$id_pais=$_POST['id_pais'];
$id_estado=$_POST['id_estado'];
$nombre=$_POST['nombrelocalidad'];


if ($enviar !=""){


$error=$localidad->insert($nombre, $id_pais,$id_estado);

if ($error){

$msg="SE HA AGREGADO UNA LOCALIDAD";

			?>
            
		<script type="text/javascript">
		<!--
		location.href="?url=tablas&tbl=Localidad&accion=listar";
		//-->
		</script>
			<?	

}
}

?>