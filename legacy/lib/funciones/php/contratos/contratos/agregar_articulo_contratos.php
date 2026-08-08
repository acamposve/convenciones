<?
$enviar=$_POST['enviar'];
$codigo_contrato=$_GET['id'];
$var=$contratos->obtenerContratosPorId($codigo_contrato);
$pdf=$contratos->pdf_auto;
		if ($enviar==1){
			$nro_articulo_post=$_REQUEST['nro_articulo'];
			$texto_completo=$_REQUEST['texto_completo'];
			$resumen_texto=$_REQUEST['resumen_texto'];
			$titulo=$_REQUEST['titulo'];
			$campo=$_REQUEST['campo'];
			$titulo_articulo=$_REQUEST['titulo_articulo'];
			
			$cadena=split("-",$nro_articulo_post); 
			switch ($cadena) {
			
				case (count($cadena) >= 3):
					$nro_articulo= $cadena[0]."-".$cadena[1];
				break;
			   
				case (strlen($cadena[0])==3):
					if ($cadena[1] == ""){
					$nro_articulo= $cadena[0];
					} 	else {
						$nro_articulo= $cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==2):
					if ($cadena[1] == ""){
					$nro_articulo= "0".$cadena[0];
					} 	else {
						$nro_articulo= "0".$cadena[0]."-".$cadena[1];
						}
					break;
				case (strlen($cadena[0])==1): 
					if ($cadena[1] == ""){
					$nro_articulo= "00".$cadena[0];
					} 	else {
						$nro_articulo= "00".$cadena[0]."-".$cadena[1];
						}
					break;
				break;
			}	
			
			$var=$contratos->agregarArticuloContrato($codigo_contrato,$nro_articulo , $texto_completo , $resumen_texto ,  $titulo, $campo, $titulo_articulo );
		
			if ($var){
			?>
			<script language="javascript" type="text/javascript">
			<!--
			window.location.href="index.php?url=contratos&tbl=Contratos&accion=listar_articulos&id=<? print $codigo_contrato ?>";
			-->
			</script>
			<?
			}
			}
?>
