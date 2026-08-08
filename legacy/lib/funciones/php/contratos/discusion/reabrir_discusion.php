<?
$enviar=$_POST['enviar'];
$codigo_discusion=$_GET['id'];


		
		$var=$discusion->actualizarDiscusiones_status($codigo_discusion,1);
			if($var)
			{?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora";
		-->
		</script>			
        <?
			}
	
?>
