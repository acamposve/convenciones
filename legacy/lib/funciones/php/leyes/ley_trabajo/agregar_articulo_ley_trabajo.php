<?
$enviar=$_POST['enviar'];

if ($enviar ==1){
$nro_articulo=$_POST['nro_articulo'];
$titulo_articulo=$_POST['titulo_articulo'];
$articulo_completo= $_POST['articulo_completo'];
$resumen_articulo=$_POST['resumen_articulo'];
$titulo_comparativo=$_POST['titulo_comparativo'];
$campo_comparativo=$_POST['campo_comparativo'];


if($ley_trabajo->getArticuloLey($nro_articulo)){
?>
<script type="text/javascript" language="javascript" >
<!--
alert("EL NUMERO DE ARTICULO INGRESADO YA EXISTE EN LA BASE DE DATOS");
-->
</script>
<?
}else{

			$error=$ley_trabajo->insertarArticulo($nro_articulo,$titulo_articulo, $articulo_completo, $resumen_articulo, $titulo_comparativo, $campo_comparativo);
			
			$contador = $ley_trabajo->contarArticulos();
			$contador = $ley_trabajo->Total;
			
			$var=$ley_trabajo->get_Ley_Trabajo();
			$total_articulos=$ley_trabajo->total_articulos;
			
			$var=$ley_trabajo->update_nro_articulos($contador , $total_articulos);

				if($error){
					?>
					<script type="text/javascript" language="javascript" >
					<!--
					location.href="index.php?url=leyes&tbl=LOT";
					-->
					</script>
					<?
				}

		}
}
?>