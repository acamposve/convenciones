<?
$codigo_articulo=$_GET['id'];
$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$nro_articulo=$contratos->nro_articulo;
$texto_completo=$contratos->texto_completo_articulo;
$resumen_texto=$contratos->resumen_articulo;
$titulo=$contratos->codigo_titulo_comparativo;
$campo=$contratos->campo_comparativo;
$titulo_articulo=$contratos->titulo_articulo;
$articulo_cerrado=$contratos->articulo_cerrado;
$articulo=$contratos->ListarContratoDefinido( $codigo_articulo,2);
	    while( list( $key, $val) = each($articulo) )
			{
				$codigo_contrato = $articulo[$key][codigo_contrato];
		
			}

$contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
	    while( list( $key, $val) = each($contr) )
			{
				$nombre_empresa = $contr[$key][nombre_empresa];

			}

?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32"  class="">&nbsp;</td>
    <td width="99%" ><div align="center">
	  <? print $nombre_empresa;?></font>
    </div></td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%" height="16">	Nro de  Articulo : &nbsp;&nbsp;&nbsp;
				                    	
                                        Titulo Clausula : </td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="nro_articulo" type="text" class="textfield" id="nro_articulo" size="10" value="<? print  $nro_articulo ?>"   />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input name="titulo_articulo" type="text" class="textfield" id="titulo_articulo" size="50" value="<? print  $titulo_articulo ?>"  /></td>
        </tr>
        <tr>
          <td height="16">Texto Completo de la Clausula: </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="texto_completo" cols="90" rows="25"  class="mceEditor" id="texto_completo"><? print  $texto_completo ?></textarea></td>
        </tr>
        <tr>
          <td height="16">Resumen de la Clausula:</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="resumen_texto" cols="90" rows="25"  class="mceEditor" id="resumen_texto"><? print  $resumen_texto ?></textarea></td>
        </tr>
        <tr>
          <td height="16">	Titulo Comparativo: 
          					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          								
							Campo Comparativo: </td>
   
        </tr>
        <tr>
          <td height="16"><select name="titulo" class="textfield" id="titulo"  >
              <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
              <?
						$var=$titulos->listarTitulosparaleyes();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($titulo==$codigo_titulo_comparativo){
							?>
              <option value="<? print $codigo_titulo_comparativo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
              <? }else{
			?>
              <option value="<? print $codigo_titulo_comparativo; ?>"> <? print $nombre_titulo; ?> </option>
              <?
						}
						
						}
					?>
          </select>  &nbsp;&nbsp;&nbsp; 
            <input name="campo" type="text" class="textfield" id="campo" size="35" value="<? print  $campo ?>" />
                         
            <input type="hidden" name="enviar" value="1" /></td>
  
        </tr>
                <tr> 
          <td> 
                <input type="checkbox" name="articulo_cerrado_checkbox"  <?php if($articulo_cerrado == "1"){echo " CHECKED";}?>> Clausula Cerrada/Pendiente
          </td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
                <input name="Submit" type="submit" class="linkAgregar" value="Guardar" /></td>
        </tr>
      </table>
    </form>
<script type="text/javascript" src="../lib/funciones/js/tiny_mce/tiny_mce.js"></script> 
<script type="text/javascript"> 
tinyMCE.init(
		{ 
			mode : "textareas", 
			theme : "advanced", 
			plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager"	,
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,cleanup,|,bullist,numlist,|,outdent,indent,blockquote,undo,redo,|,link,unlink,|,forecolor,backcolor",
 			theme_advanced_buttons2 : "styleselect,formatselect,fontselect,fontsizeselect", 
				 
			theme_advanced_buttons3 : "tablecontrols", 
			theme_advanced_toolbar_location : "top", 
			theme_advanced_toolbar_align : "left", 
			theme_advanced_resizing : false, content_css : "css/example.css", 
			// Drop lists for link/image/media/template dialogs 
			template_external_list_url : "js/template_list.js", 
			external_link_list_url : "js/link_list.js", 
			external_image_list_url : "js/image_list.js", 
			media_external_list_url : "js/media_list.js", 
			// Replace values for the template plugin 
				template_replace_values : { 
				username : "Aseso", 
				staffid : "991234" 
				} 
		}); 
</script> 
    </td>
  </tr>
  <tr >
    <td height="30" >&nbsp;</td>
  </tr>
  <tr>
    <td height="971">&nbsp;</td>

  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>

  </tr>
</table>
