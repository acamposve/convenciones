<?
$enviar=$_POST['enviar'];
$codigo_articulo=$_GET['id'];
$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$codigo_contrato=$contratos->codigo_contrato;
if ($enviar==1)
	{

 		$nro_articulo=$_REQUEST['nro_articulo'];
		$texto_completo=$_REQUEST['texto_completo'];
		$resumen_texto=$_REQUEST['resumen_texto'];
		$titulo=$_REQUEST['titulo'];
		$campo=$_REQUEST['campo'];
		$titulo_articulo=$_REQUEST['titulo_articulo'];
		$nombre_titulo =$_REQUEST['nombre_titulo'];
		$nom_emp =$_REQUEST['nom_emp'];
		$page_return = $_GET['page'];		

?>
<script language="JavaScript">

// abrir una nueva ventana con el nombre pasado como parámetro
miVentana= open("#", 'Ventana_HTML',"width=770, height=800, status=yes, toolbar=yes ,menubar=yes, resizable=yes, scrollbars=yes");
// Abre la corriente de datos del documento para escribir 
miVentana.document.open();
// Crea el documento
miVentana.document.write("<html><head>"); 
miVentana.document.write("</head><body>");
miVentana.document.write("<br><br>");
miVentana.document.write("<p> Empresa: <? echo $nom_emp ?>" );
miVentana.document.write("<br><br>");
miVentana.document.write("<br>");
miVentana.document.write("<br>");
miVentana.document.write("<? echo $texto_completo ?>");
miVentana.document.write("</body></html>");
// cierra la corriente de datos del documento 
miVentana.document.close();

			<!--
			location.href="index.php?url=contratos&tbl=Contratos&accion=listar_articulos&id=<? print $codigo_contrato ?>&page=<? print $page_return ?>";
			-->
			</script>
			<?
			
		}
?>
