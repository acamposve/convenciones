<?
$enviar=$_GET['flag'];
$idLinkInteres=$_GET['id'];
$var=$LinkI->getId($idLinkInteres);
	$lit_Sector=$LinkI->lit_Sector;
	$lit_Detalle=$LinkI->lit_Detalle;
	$lit_Link=$LinkI->lit_Link;

if ($enviar =="SI"){
$flag_del=true; 

	if($flag_del){
		$error=$LinkI->delete($idLinkInteres );
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
		location.href="index.php?url=tablas&tbl=Links de Interes&accion=list";
		-->
		</script>
		
		<?
		}
    }

?>

