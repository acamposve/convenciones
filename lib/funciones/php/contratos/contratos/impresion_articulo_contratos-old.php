<?

$enviar=$_POST['enviar'];
$codigo_articulo=$_GET['id'];
$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$codigo_contrato=$contratos->codigo_contrato;
		if ($enviar==1){
			$nro_articulo=$_REQUEST['nro_articulo'];
			$texto_completo=utf8_decode($_REQUEST['texto_completo']);
			$resumen_texto=utf8_decode($_REQUEST['resumen_texto']);
			$titulo=$_REQUEST['titulo'];
			$campo=$_REQUEST['campo'];
			$titulo_articulo=$_REQUEST['titulo_articulo'];
			$nombre_titulo =$_REQUEST['nombre_titulo'];
			$nom_emp =$_REQUEST['nom_emp'];

			
include_once('../lib/funciones/php/pdf/fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Times','B',16);


	$pdf->image('../plantillas/plantilla_admin/images/header.gif',0,0, 210 );
	$pdf->ln(20);
	$pdf->Write(10,"Clausula # " .$nro_articulo. "   Perteneciente a la Empresa: " .$nom_emp);
	$pdf->ln(20);
	$pdf->Write(10,"Titulo de Clausula # "  .$titulo_articulo);	
	$pdf->SetFont('Times','',12);
	$pdf->ln(20);
	$pdf->Write(10,"Texto Completo de la Clausula ");
	$pdf->ln(10);
	//RETIRAR TAGS DEL TEXTO COMPLETO
	$texto_completo2=strip_tags( $texto_completo);
	$pdf->Write(10,  $texto_completo2);
	$pdf->ln(20);
	$pdf->Write(10,"Resumen de la Clausula ");
	//RETIRAR TAGS DEL TEXTO RESUMEN
	$resumen_texto=strip_tags($resumen_texto);
	$pdf->ln(10);
	$pdf->Write(10, $resumen_texto);
	$pdf->ln(20);
	$pdf->Write(10,"Campo Comparativo ");
	$pdf->ln(10);
	$pdf->Write(10, $campo);



//Determinar un nombre temporal de fichero en el directorio actual
	$file = basename(tempnam('.', 'tmp'));
	rename($file, $file.'.pdf');
	$file .= '.pdf';
//Guardar el PDF en un fichero
	
	$pdf->Output($file, 'F');
//Redirección
	echo "<SCRIPT>window.open('$file')</SCRIPT>";
			?>
			<script language="javascript" type="text/javascript">
			<!--
			//location.href="index.php?url=contratos&tbl=Contratos&accion=listar_articulos&id=<? print $codigo_contrato ?>";
			-->
			</script>
			<?
			
		}
?>