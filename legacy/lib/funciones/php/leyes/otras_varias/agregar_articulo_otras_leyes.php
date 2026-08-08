<?
$enviar=$_POST['enviar'];
$codigo_ley=$_GET['ley'];
if ($enviar==1){
		$nro_articulo_post=$_POST['nro_articulo'];
		$titulo_articulo=$_POST['titulo_articulo'];
		$articulo_completo= $_POST['articulo_completo'];
		$resumen_articulo=$_POST['resumen_articulo'];
		$titulo_comparativo=$_POST['titulo_comparativo'];
		$campo_comparativo=$_POST['campo_comparativo'];
		
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
	

		
if($otras_leyes->getArticuloLey2($nro_articulo,$codigo_ley)){
?>
<script type="text/javascript" language="javascript" >
<!--
alert("EL NUMERO DE ARTICULO INGRESADO YA EXISTE EN LA BASE DE DATOS");
-->
</script>
<?
}else{

		$error=$otras_leyes->insertarArticulo($codigo_ley, $nro_articulo, $titulo_articulo, $articulo_completo, $resumen_articulo, $titulo_comparativo, $campo_comparativo);
			
			$contador = $otras_leyes->contarArticulos($codigo_ley);
			$contador = $otras_leyes->Total;
			
			$var=$otras_leyes->update_nro_articulos($contador , $codigo_ley);

				if($error){
					?>
					<script type="text/javascript" language="javascript" >
					<!--
					location.href="index.php?url=leyes&tbl=Otras Varias&accion=listar articulos&ley=<? print $codigo_ley ?>";
					-->
					</script>
					<?
				}

		}
}
?>
