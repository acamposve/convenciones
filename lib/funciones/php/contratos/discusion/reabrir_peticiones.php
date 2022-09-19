<?
$enviar=$_POST['enviar'];
$codigo_peticion=$_GET['id'];
$codigo_bitacora=$_GET['id_bitacora'];


		
		$var=$discusion->actualizarPeticiones_reabir($codigo_peticion);
	
		if($var)
			{?>
		<script language="javascript" type="text/javascript">
		<!--
			location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_peticiones&id=<?php print $codigo_bitacora?>";
		-->
		</script>			
        <?
			}
	
?>
