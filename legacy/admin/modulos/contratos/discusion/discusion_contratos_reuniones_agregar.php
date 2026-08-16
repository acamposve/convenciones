<?
$codigo_bitacora=$_GET['id_bitacora'];		
		$busqueda_empresa=$discusion->listarDiscusiones(0,500,"");
		while( list( $key, $val) = each($busqueda_empresa) ) {
            $codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
			$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
			if ($codigo_bitacora_original ==$codigo_bitacora )
			{
				$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
			} 
		}
?>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="F8EEEE">
  <tr>
    <td width="1%" height="32" bgcolor="CC6666">&nbsp;</td>
    <td width="99%" bgcolor="CC6666">
		<br/>
		<div align="center">   <font color="#FFFFFF" ><? print $nombre_empresa;?> </div>

    </td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="texto">
        <tr>
          <td >
          		Fecha Reunion : 
          		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          		Hora de la Reunion:          </td>
          </tr>
        <tr>
          <td width="100%" height="16" valign="middle">
          		<input name="FechaReunion" type="text" class="texto" id="FechaReunion" />
          		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&nbsp;&nbsp;
                <input name="HoraReunion" type="text" class="texto" id="HoraReunion" /></td>
        </tr>
        <tr>
          <td height="16" colspan="3">Duracion Reunion</td>
          </tr>
        <tr>
          <td height="20" colspan="2"><label>
            <input name="DuracionReunion" type="text" class="texto" id="DuracionReunion" />
          </label></td>
        </tr>
        <tr>
          <td colspan="3">Detalle de la Reunion: </td>
        </tr>
        <tr>
          <td colspan="3"><textarea name="DetalleReunion" cols="60" rows="20" id="oferta" class="mceEditor" ></textarea></td>
        </tr>
        <tr>
          <td colspan="3">Resumen de la Reunion:</td>
          </tr>
        <tr>
          <td><textarea name="ResumenReunion" cols="60" rows="20" id="oferta2" class="mceEditor" ></textarea></td>
        </tr>
        <tr>
          <td colspan="3">Asistentes de la Reunion:</td>
          </tr>
        
        <tr>
          <td><textarea name="AsistentesReunion" cols="60" rows="10" id="oferta2" class="mceEditor" ></textarea></td>
        </tr>

        <tr>
          <td colspan="3"><input name="enviar" type="hidden" value="1"/></td>
          </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
                <input name="Submit" type="submit" class="botonGuardar" value=" " /></td>
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
    <td height="30" bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr>
    <td height="92" bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" bgcolor="F8EEEE"></td>
    <td bgcolor="F8EEEE"></td>
  </tr>
</table>
