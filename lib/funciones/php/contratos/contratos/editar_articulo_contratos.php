<? 
$articulo_cerrado_checkbox = $_REQUEST['articulo_cerrado_checkbox'];
if ($articulo_cerrado_checkbox=='on'){ $articulo_cerrado=1; } else {$articulo_cerrado=0;}
$enviar=$_POST['enviar'];
$codigo_articulo=$_GET['id'];
$page_return = $_GET['page'];
$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$codigo_contrato=$contratos->codigo_contrato;
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
						
			$var=$contratos->actualizarArticulosContratos($nro_articulo , $texto_completo , $resumen_texto ,  $titulo, $campo , $codigo_articulo, $titulo_articulo, $articulo_cerrado);
		
			if ($var){
			?>
			<script language="javascript" type="text/javascript">
			<!--
			location.href="index.php?url=contratos&tbl=Contratos&accion=listar_articulos&id=<? print $codigo_contrato ?>&page=<? print $page_return ?>";
			-->
			</script>
			<?
			}
		}
?>
