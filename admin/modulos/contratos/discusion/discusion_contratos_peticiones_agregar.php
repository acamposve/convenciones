<?php
$codigo_bitacora=$_GET['codigo_discusion'];
		$busqueda_empresa=$discusion->listarDiscusiones(0,500,"");
		while( list( $key, $val) = each($busqueda_empresa) ) {
            $codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
			$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
			if ($codigo_bitacora_original ==$codigo_bitacora )
			{	
				$estatus_bt=$busqueda_empresa[$key][estatus_bitacora];
				
				$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
			} 
		}
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
           <div align="center">   <? print $nombre_empresa;?></div>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="45%" height="16" colspan="1" >Nro Peticion :</td>
          <td width="55%">Titulo Peticion: </td>
        </tr>
        <tr>
          <td height="16"  valign="middle"><input name="nroPeticion" type="text"  id="nroPeticion" /></td>
          <td><input name="tituloPeticion" type="text"  size="50" id="tituloPeticion" /></td>
        </tr>
        <tr>
          <td height="16" colspan="3">Texto Peticion : </td>
        </tr>
        <tr>
          <td height="20" colspan="3"><label>
            <textarea name="textoPeticion" cols="90" rows="20" id="textoPeticion" class="mceEditor" ></textarea>
          </label></td>
        </tr>

        <tr>
          <td colspan="3"><input name="enviar" type="hidden" value="1"/>
              <input name="codigo_discusion" type="hidden" id="codigo_discusion" value="<?php print $codigo_discusion; ?>"/></td>
        </tr>
<!--
        <tr>
          <td  colspan="2">Titulo Comparativo: </td>
        </tr>
        <tr>
          <td colspan="3"><select name="titulo_comparativo" class="textfield" id="titulo_comparativo"  >
            <option value="0">Seleccione Titulo Comparativo</option>
            <?
						$var=$titulos->listarTitulos(0,500,"");
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_c = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($codigo_titulo_c==$codigo_titulo_comparativo){
							?>
            <option value="<? print $codigo_titulo_c; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
            <? }else{
			?>
            <option value="<? print $codigo_titulo_c; ?>"> <? print $nombre_titulo; ?> </option>
            <?
						}
						
						}
					?>
          </select></td>
          </tr>
-->
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
    <td height="92">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
  </tr>
</table>
