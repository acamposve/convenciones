
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="42%" height="16"><label>Fecha de Publicacion:</label></td>
          <td width="53%">PDF Asociado:</td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="fecha_publicacion" type="text"   value="<? print $fecha_publicacion; ?>" />
          </td>
          <td valign="middle"><input name="pdf_asociado" type="file"  value="" />
            &nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td height="16">Descripcion Resumen:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="2"><textarea name="descripcion_resumen" cols="90" rows="25"  class="mceEditor" ><? print $descripcion_resumen;  ?></textarea></td>
        </tr>
        <tr>
          <td height="16">Total Art&iacute;culos:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="total_articulos" type="text"  id="Cant_articulos" size="6" readonly="" value="<? print $total_articulos; ?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="enviar" type="hidden" value="1"/></td>
          <td>&nbsp;</td>
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
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
	
  </tr>
</table>

