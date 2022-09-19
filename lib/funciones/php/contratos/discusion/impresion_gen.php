<?
$enviar=$_POST['enviar'];
if ($enviar==1)
	{

$id=$_REQUEST['id'];
$bitacora=$_REQUEST['bitacora'];
$seccion= $_REQUEST['seccion'];
$listar = $_REQUEST['listar'];
$page_return = $_REQUEST['page'];
$texto_completo=$_REQUEST['texto_completo'];




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
miVentana.document.write("<br>");
miVentana.document.write("<br>");
miVentana.document.write("<? echo $texto_completo ?>");
miVentana.document.write("</body></html>");
// cierra la corriente de datos del documento 
miVentana.document.close();

			<!--
			location.href="index.php?url=contratos&tbl=Bitacora&accion=<? print $listar ?>&id=<? print $bitacora ?>&page=<? print $page_return ?>";
			-->
			</script>
			<?
			
		}
?>
