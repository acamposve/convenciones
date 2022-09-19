<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK> 
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><div align="center"><span class="verdana_blanco"><? print $msg; 
	$msg="";?></span></div></td>
    <td width="11" >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td height="16" width="50%">Nombre Ley </td>
          <td height="16" width="50%">&nbsp; </td>
        </tr>
        <tr>
          <td height="16" colspan="2"><input size="75" name="nombre_ley" type="text"  id="nombre_ley" /></td>
        </tr>
		<tr>
          <td  height="16">Origen</td>
        </tr>
        <tr>
          <td height="16" colspan="2"><input size="75" name="origen" type="text"  id="origen" />
			&nbsp;&nbsp;&nbsp;
            <img src="../../../../plantillas/plantilla_admin/images/boton_buscar.gif" width="21" height="21" alt="Ejemplo:  htttp://www.misitioweb.com/" />
          </td>
        </tr>
        <tr>
			<td>Fecha de Publicacion:</td>     
			<td  height="16">PDF Asociado:</td>            
            </td>     
        </tr>
        <tr>
       		<td align="left"><input name="fecha_publicacion" type="text" readonly="readonly"  />
			<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.fecha_publicacion,'dd/mm/yyyy',this)" /></td>        
          <td height="16" valign="middle"><input name="pdf_asociado" type="file"   value="" /></td>
        </tr>
        <tr>
          <td height="16">Descripcion Resumen:</td>
        </tr>
        <tr>
          <td height="20" colspan="2"><textarea name="descripcion_resumen" cols="90" rows="25"  class="mceEditor" ></textarea></td>
        </tr>
       
        <tr>
          <td colspan="3" valign="bottom"><label></label>
			<input name="enviar" type="hidden" value="1"/>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar" /></td>
          </tr>
      </table>
        </form>

	</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
    <td ></td>
	
  </tr>
</table>

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