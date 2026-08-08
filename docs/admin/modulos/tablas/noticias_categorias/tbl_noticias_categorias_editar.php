<?php
$id=$_GET['id'];
		$var=$Noticias->getIdCategoria( $id );
	    while( list( $key, $val) = each($var) ) 
			{
		    	$codigo_categoria 		= $var[$key][codigo_categoria];
				$nombre_categoria 		= $var[$key][nombre_categoria];
				$descripcion_categoria 	= $var[$key][descripcion_categoria];
		
		
			}

?>

<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  class="Color_tabla">
  <tr>
    <td width="1%" >&nbsp;</td>
    <td width="99%" ><? 
	?></td>

  </tr>
  <tr class=" Color_tabla">
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%" >Nombre de la Categoria</td>
        </tr>
        <tr>
          <td  ><input type="text" name="nombre_categoria"  size="50" value="<? print $nombre_categoria ;?>" /></td>
        </tr>
		<tr>
        	<td height="16" colspan="3" class="texto_simple">Descripcion de la Categoria
			<textarea name="descrip_categoria" cols="90" rows="25"  class="mceEditor" id="descrip_categoria" ><?php print $descripcion_categoria ?></textarea>
            </td>
        </tr>
        <tr>
          <td height="67"><input type="hidden" value="1" name="enviar">          </td>
        </tr>


        <tr> 
          <td height="67"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td valign="bottom"><label>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar">
            &nbsp;          </label></td>
        </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10"></td>
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
