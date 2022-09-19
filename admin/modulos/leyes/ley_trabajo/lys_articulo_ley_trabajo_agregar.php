<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32">&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="data" method="post" action="" onsubmit="return validarEstado(this)">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16">Numero del Art&iacute;culo: </td>
          <td width="50%">Titulo del Art&iacute;culo:</td>
        </tr>
        <tr>
          <td height="16"><label>
            <input name="nro_articulo" type="text" class="textfield" id="nro_articulo" size="8" />
          </label></td>
          <td><input name="titulo_articulo" type="text" class="textfield" id="titulo_articulo" size="35" /></td>
        </tr>
        <tr>
          <td height="16">Texto Completo del Art&iacute;culo: </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="articulo_completo" cols="90" rows="25"  class="mceEditor" id="articulo_completo"></textarea></td>
          </tr>
        <tr>
          <td height="16">Resumen del Art&iacute;culo:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="resumen_articulo" cols="90" rows="25"  class="mceEditor" id="resumen_articulo"></textarea></td>
          </tr>
        <tr>
          <td height="16">Titulo Comparativo: </td>
          <td>Campo Comparativo: </td>
        </tr>
        <tr>
          <td height="16"><select name="titulo_comparativo" class="textfield" id="titulo_comparativo"  >
            <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
            <?
						$filtro="";
						$var=$titulos->listarTitulosparaleyes();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($codigo_titulo==$codigo_titulo_comparativo){
							?>
            <option value="<? print $codigo_titulo_comparativo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
            <? }else{
			?>
            <option value="<? print $codigo_titulo_comparativo; ?>"> <? print $nombre_titulo; ?> </option>
            <?
						}
						
						}
					?>
          </select></td>
          <td><input name="campo_comparativo" type="text" class="textfield" id="campo_comparativo" size="35" /></td>
        </tr>
        <tr>
          <td height="24"><input type="hidden" value="1" name="enviar"></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td valign="bottom"><label>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar">
            &nbsp;          </label></td>
          <td>&nbsp;</td>
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
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10"></td>
    <td ></td>
	
  </tr>
</table>

