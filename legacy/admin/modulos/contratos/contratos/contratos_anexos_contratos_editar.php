<?
$codigo_anexo=$_GET['id'];
$var=$contratos->obtenerAnexoContratosPorId($codigo_anexo);
$nombre_anexo=$contratos->nombre_anexo;
$descripcion_anexo=$contratos->descripcion_anexo;
$texto_completo_anexo=$contratos->texto_completo_anexo;
$pdf_anexo=$contratos->pdf_anexo;
$codigo_contrato=$contratos->codigo_contrato;
$codigo_contrato=$contratos->codigo_contrato;
$Contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
	    while( list( $key, $val) = each($Contr) )
			{
				$codigo_contrato = $Contr[$key][codigo_contrato];
				$nombre_empresa = $Contr[$key][nombre_empresa];
			}

?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><div align="center">
    	  <?print $nombre_empresa;?>
    </div></td>
  
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="100%" height="16">Nombre del Anexo: </td>
        </tr>
        <tr>
          <td height="16"  valign="middle"><input name="nombre_anexo" type="text" class="textfield" id="nombre_anexo" size="50" value="<? print $nombre_anexo ?>" /></td>
        </tr>
        <tr>
          <td height="16">Descripcion Anexo : </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="descripcion" cols="90" rows="20"  class="mceEditor" id="descripcion"><? print $descripcion_anexo ?></textarea></td>
        </tr>
        <tr>
          <td height="16">Texto Completo :</td>


        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="texto_completo" cols="90" rows="20"  class="mceEditor" id="texto_completo"><? print $texto_completo_anexo ?></textarea></td>
        </tr>
        <tr>
          <td height="16">Pdf Asociado : </td>

        </tr>
        <tr>
          <td height="16"><label>
            <input name="pdf_asociado" type="file" id="pdf_asociado" />
            <input type="hidden" name="enviar" value="1" />
          </label></td>

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