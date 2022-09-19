<?
$enviar=$_POST['enviar'];
$codigo_bitacora = $_GET['id_bitacora'];
$codigo_acuerdo = $_GET['id'];

		if ($enviar==1)
		{
			$fecha_acuerdo 					= $_REQUEST['FechaAcuerdo'];
			$nro_articulo 					= $_REQUEST['NroClausula'];
			$titulo_articulo				= $_REQUEST['TituloAcuerdo'];
			$texto_completo_articulo		= $_REQUEST['TextoCompletoAcuerdo'];
			$resumen_articulo				= $_REQUEST['ResumenAcuerdo'];
			$codigo_oferta		 			= $_REQUEST['oferta'];
			$peticion						= $_REQUEST['peticion'];
			$codigo_titulo_comparativo		= $_REQUEST['titulocomparativo'];
			$campo_comparativo				= $_REQUEST['CampoComparativo'];
			$nro_articulo= trim($nro_articulo);

			$cadena=split("-",$nro_articulo); 
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
			$nro_articulo= trim($nro_articulo);
			$var=$discusion->actualizarAcuerdos($codigo_acuerdo,$fecha_acuerdo, $nro_articulo,$titulo_articulo,$texto_completo_articulo,$resumen_articulo,$codigo_oferta,$peticion,0,"");
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_acuerdos&id=<? print $codigo_bitacora?>";
		-->
		</script>
		<?
		}
		}
?>
