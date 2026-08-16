<META http-equiv="Content-Type" content="text/html; charset=iso-UTF-8">
<?
$codigo_articulo=$_GET['id'];
$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$nro_articulo=$contratos->nro_articulo;
$texto_completo=$contratos->texto_completo_articulo;
$resumen_texto=$contratos->resumen_articulo;
$titulo=$contratos->codigo_titulo_comparativo;
$campo=$contratos->campo_comparativo;
$titulo_articulo=$contratos->titulo_articulo;

$articulo=$contratos->ListarContratoDefinido( $codigo_articulo,2);
	    while( list( $key, $val) = each($articulo) )
			{
				$codigo_articulo = $articulo[$key][codigo_contrato];
			}
$contr=$contratos->ListarContratoDefinido( $codigo_articulo,1);
	    while( list( $key, $val) = each($contr) )
			{
				$nombre_empresa 		= $contr[$key][nombre_empresa];
				$logo					= $contr[$key][logo];
				$codigo_empresa			= $contr[$key][codigo_empresa];
			}

		if ($logo==""){
			$imagen="";
		}else{
			$imagen="<img src='../admin/modulos/tablas/empresa/documentos/$codigo_empresa/$logo'  border='0' alt='logo'/><br>";
		}

?>

	<script  type="" language="">
		//alert ("Realice todas las Modificaciones e \n Imprima esta Clausula, este proceso \n no generara NINGUNA modificación \n en la Clausula Original.") 
	</script>

    <link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="22" height="32">&nbsp;</td>
    <td width="691" ><div align="center">
	   <?print $nombre_empresa;?>
	</div></td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%" height="16">Nro de  Clausula : &nbsp;&nbsp;&nbsp;
				                    	
                                        Titulo Clausula : </td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="nro_articulo" type="text" class="textfield" id="nro_articulo" size="5" value="<? print  $nro_articulo ?>"  />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="titulo_articulo" type="text" class="textfield" id="titulo_articulo" size="35" value="<? print  $titulo_articulo ?>"  /></td>
        </tr>
        <tr>
          <td height="16">Texto Completo de la Clausula: </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="texto_completo" cols="90" rows="35"  class="mceEditor" id="texto_completo"><? print  $imagen.$texto_completo ?></textarea></td>
        </tr>
        <tr>
          <td height="16"><input type="hidden" name="enviar" value="1" /></td>
			<input name="nombre_titulo" type="hidden" class="textfield" id="nombre_titulo" size="35" value="<? print  $nombre_titulo ?>" />	
			<input type="hidden" name="nom_emp"  id="nom_emp" value="<? echo $nombre_empresa ?>" />
            
          
          
        </tr>
        
        <tr>
          <td colspan="3" valign="bottom"><label></label>
                <input name="Submit" type="submit" class="linkAgregar" value="Imprimir"  /></td>
        </tr>
      </table>
    </form>

<script type="text/javascript" src="../lib/funciones/js/tiny_mce/tiny_mce.js"></script> 
<script type="text/javascript"> 
tinyMCE.init(
		{ 
			mode : "textareas", 
			theme : "advanced", 
			plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
			
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,cut,copy,paste,pastetext,pasteword,cleanup",
 			theme_advanced_buttons2 : "styleselect,formatselect,fontselect,fontsizeselect", 
			theme_advanced_buttons3 : "bullist,numlist,|,outdent,indent,blockquote,undo,redo,|,link,unlink,|,insertdate,inserttime,preview,|,forecolor,backcolor", 
			theme_advanced_toolbar_location : "top", 
			theme_advanced_toolbar_align : "left", 
			
			plugins : 'inlinepopups', 
			setup : function(ed) 
			{ 
			// Add a custom button 
			ed.addButton('mybutton', 
			{ 

			onclick : function() 
			{ 
			// Add you own code to execute something on click 
 
			} 
}); 
} 
}); 
</script> 

</td>
 
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
  
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>

  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>

  </tr>
</table>
