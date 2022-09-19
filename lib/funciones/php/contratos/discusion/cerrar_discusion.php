<?
$enviar=$_POST['enviar'];
$codigo_discusion=$_GET['id'];

$var=$discusion->obtenerDiscusionesPorId($codigo_discusion);	
	$pdf_peticiones_sindicato	= 	$discusion->pdf_peticiones_sindicato;
	$pdf_ofertas_patrono	= 	$discusion->pdf_ofertas_patrono;
		if ($enviar==1){
			$representanteEm=$_REQUEST['representanteEm'];
			$representanteSn=$_REQUEST['representanteSn'];
			$representanteMt=$_REQUEST['representanteMt'];
			$telefonoEm=$_REQUEST['telefonoEm'];
			$telefonoSn=$_REQUEST['telefonoSn'];
			$telefonoMt=$_REQUEST['telefonoMt'];
			$emailEm=$_REQUEST['emailEm'];
			$emailSn=$_REQUEST['emailSn'];
			$emailMt=$_REQUEST['emailMt'];
			$cargoEm=$_REQUEST['cargoEm'];
			$cargoSn=$_REQUEST['cargoSn'];
			$cargoMt=$_REQUEST['cargoMt'];
			$peticiones=$_REQUEST['peticiones'];
			$oferta=$_REQUEST['oferta'];
			$empresa=$_REQUEST['empresa'];
			$inicio=$_REQUEST['inicio'];

			$cod_empresa=$_REQUEST['cod_empresa'];

			$var=$contratos->insertarContrato("", "*Inicio", "*Fin", "*Duración", "*Ambito", $cod_empresa);
			$codigo_contrato=$contratos->obtenerIdUltimoContrato();
			$acuerdos_claudulas=$discusion->listarAcuerdos( 0, 1000,  $codigo_discusion, "");


	  while( list( $key, $val) = each($acuerdos_claudulas) ) {

		$nro_articulo				= $acuerdos_claudulas[$key][nro_articulo];
		$texto_completo_articulo	= $acuerdos_claudulas[$key][texto_completo_articulo];
		$resumen_articulo			= $acuerdos_claudulas[$key][resumen_articulo];
		$codigo_titulo_comparativo	= $acuerdos_claudulas[$key][codigo_titulo_comparativo];		
		$campo_comparativo			= $acuerdos_claudulas[$key][campo_comparativo];
		$titulo_articulo			= $acuerdos_claudulas[$key][titulo_articulo];						

		$agregar_Clausula=$contratos->agregarArticuloContrato($codigo_contrato, $nro_articulo , $texto_completo_articulo , $resumen_articulo ,  $codigo_titulo_comparativo, $campo_comparativo, $titulo_articulo);
		$i=$i+1;
		}
		
		$var=$discusion->actualizarDiscusiones_status($codigo_discusion,10);
			
			$directorio="modulos/contratos/contratos/documentos/";
			$carpeta=$codigo_contrato;
			$ruta=$directorio.$carpeta;
			if(mkdir($ruta,0777))
			{?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Contratos&accion=listar_articulos&id=<? print $codigo_contrato?>";		
		-->
		</script>			
        <?
			}

		
		}
?>
