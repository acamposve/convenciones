<?
$codigo_contrato=$_GET['id'];
$page=$_GET['page'];
//PU publciado//Pe pendiente

// obtenerContratosPorId
$var=$contratos->obtenerContratosPorId($codigo_contrato);
$status_publicacion=$contratos->status_publicacion;
//print $status_publicacion."<br>";


if ($status_publicacion=="Pe"){
		//publciar
		$var=$contratos->actualizarContratos_Publciar($codigo_contrato,"PU"); 
	}else{
		//despublica
		$var=$contratos->actualizarContratos_Publciar($codigo_contrato,"Pe");
	}



		
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="?url=contratos&tbl=Contratos&amp;page=<?php print $page ?>";
		-->
		</script>
		<? 
		
?>
