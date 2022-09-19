<?
$enviar=$_POST['enviar'];
$id_articulo=$_GET['id'];
$page_return=$_GET['page'];


	$var=$otras_leyes->getArticuloLey($id_articulo, $codigo_ley);
	$codigo_articulo 			= $otras_leyes->codigo_articulo;
	$nro_articulo				= $otras_leyes->nro_articulo;
	$titulo_articulo			= $otras_leyes->titulo_articulo;
	$texto_completo_articulo	= $otras_leyes->texto_completo_articulo;
	$resumen_articulo			= $otras_leyes->resumen_articulo;
	$codigo_titulo				= $otras_leyes->codigo_titulo_comparativo;
	$campo_comparativo			= $otras_leyes->campo_comparativo;


if ($enviar ==1){
		$nro_articulo_post=$_POST['nro_articulo'];
		$titulo_articulo=$_POST['titulo_articulo'];
		$articulo_completo= $_POST['articulo_completo'];
		$resumen_articulo=$_POST['resumen_articulo'];
		$titulo_comparativo=$_POST['titulo_comparativo'];
		$campo_comparativo=$_POST['campo_comparativo'];

$cadena=split("-",$nro_articulo_post); 
switch ($cadena) {

	case (count($cadena) >= 3):
		$nro_articulo1= $cadena[0]."-".$cadena[1];
	break;
   
   	case (strlen($cadena[0])==3):
		if ($cadena[1] == ""){
		$nro_articulo1= $cadena[0];
		} 	else {
			$nro_articulo1= $cadena[0]."-".$cadena[1];
			}
		break;
	case (strlen($cadena[0])==2):
		if ($cadena[1] == ""){
		$nro_articulo1= "0".$cadena[0];
		} 	else {
			$nro_articulo1= "0".$cadena[0]."-".$cadena[1];
			}
		break;
	case (strlen($cadena[0])==1): 
		if ($cadena[1] == ""){
		$nro_articulo1= "00".$cadena[0];
		} 	else {
			$nro_articulo1= "00".$cadena[0]."-".$cadena[1];
			}
		break;
	break;
}


				/* if($nro_articulo==$nro_articulo_post){
				
					if($otras_leyes->getArticuloLey($nro_articulo, $codigo_ley )){
					
						?>
						<script type="text/javascript" language="javascript" >
						<!--
						alert("EL NUMERO DE ARTICULO INGRESADO YA EXISTE EN LA BASE DE DATOS");
						
						-->
						</script>
						<?
					}else{
			
						$error=$otras_leyes->actualizarArticulos($codigo_articulo,$nro_articulo1, $titulo_articulo, $articulo_completo, $resumen_articulo,$titulo_comparativo, $campo_comparativo);
			
								if($error){
								?>
								<script type="text/javascript" language="javascript" >
								<!--
								location.href="index.php?url=leyes&tbl=Otras Varias&accion=listar articulos&ley=<? print $codigo_ley ?>&amp;page=<? print $page_return ?>";
								
								-->
								</script>
								<?
								}
					}
				}else{ */
				
							$error=$otras_leyes->actualizarArticulos($codigo_articulo,$nro_articulo1, $titulo_articulo, $articulo_completo, $resumen_articulo,$titulo_comparativo, $campo_comparativo);
			
								if($error){
								?>
								<script type="text/javascript" language="javascript" >
								<!--
								location.href="index.php?url=leyes&tbl=Otras Varias&accion=listar articulos&ley=<? print $codigo_ley ?>&amp;page=<? print $page_return ?>";
								
								-->
								</script>
								<?
			
			//}
								}
}
?>