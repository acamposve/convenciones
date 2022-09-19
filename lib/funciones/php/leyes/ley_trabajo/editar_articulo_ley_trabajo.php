<?
$enviar=$_POST['enviar'];
$id_articulo=$_GET['id'];
$page_return=$_GET['page'];

	$var=$ley_trabajo->getArticuloLey($id_articulo);
	$nro_articulo				= $ley_trabajo->nro_articulo;
	$titulo						= $ley_trabajo->titulo_articulo;
	$texto_completo_articulo	= $ley_trabajo->texto_completo_articulo;
	$resumen_articulo			= $ley_trabajo->resumen_articulo;
	$codigo_titulo				= $ley_trabajo->codigo_titulo_comparativo;
	$campo_comparativo			= $ley_trabajo->campo_comparativo;


if ($enviar ==1){
		$nro_articulo1=$_POST['nro_articulo'];
		$titulo_articulo=$_POST['titulo_articulo'];
		$articulo_completo= $_POST['articulo_completo'];
		$resumen_articulo=$_POST['resumen_articulo'];
		$titulo_comparativo=$_POST['titulo_comparativo'];
		$campo_comparativo=$_POST['campo_comparativo'];

				if($nro_articulo!=$nro_articulo1){
					if($ley_trabajo->getArticuloLey($nro_articulo)){
						?>
						<script type="text/javascript" language="javascript" >
						<!--
						alert("EL NUMERO DE ARTICULO INGRESADO YA EXISTE EN LA BASE DE DATOS");
						
						-->
						</script>
						<?
					}else{
			
						$error=$ley_trabajo->update($nro_articulo1, $titulo_articulo, $articulo_completo, $resumen_articulo, $titulo_comparativo, $campo_comparativo,$nro_articulo);
			
								if($error){
								?>
								<script type="text/javascript" language="javascript" >
								<!--
								location.href="index.php?url=leyes&tbl=LOT&page=<? print $page_return ?>";

								-->
								</script>
								<?
								}
					}
				}else{
				
							$error=$ley_trabajo->update($nro_articulo, $titulo_articulo, $articulo_completo, $resumen_articulo, $titulo_comparativo, $campo_comparativo,$nro_articulo);
			
								if($error){
								?>
								<script type="text/javascript" language="javascript" >
								<!--
								location.href="index.php?url=leyes&tbl=LOT&page=<? print $page_return ?>";
								
								-->
								</script>
								<?
			
					}
				}
			}
?>