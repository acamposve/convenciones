<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="13" height="32" >&nbsp;</td>
    <td width="505" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="59%">Nombre del Titulo: </td>
        </tr>
        <tr>
          <td><label>
            <input name="titulo" type="text" class="textfield" id="titulo" size="35" value="<? print $nombre_titulo;?>" />
          </label></td>
        </tr>
        <tr>
          <td>Descripcion del Titulo : </td>
        </tr>
        <tr>
          <td><label>
            <textarea name="descripcion" cols="90" rows="20" class="mceEditor" id="descripcion"><? print $descripcion_titulo;?></textarea>
          </label></td>
        </tr>
        <tr>
          <td>Categoria:</td>
        </tr>
        <tr>
          <td><select name="categoria" class="textfield">
              <option value="" >Seleccione una categoria</option>
              <?
			$var=$categorias_titulos->listarCategoria2();
			
			 while( list( $key, $val) = each($var) ) {
		            $codigo_categoria_titulos = $var[$key][codigo_categoria_titulos];
					$nombre_categoria = $var[$key][nombre_categoria];
					
					if ($categoria_titulo==$codigo_categoria_titulos){
			?>
              <option  value="<? print $codigo_categoria_titulos; ?>" selected="selected" ><? print $nombre_categoria ; ?></option>
              <? 
			}else{			
			?>
              <option  value="<? print $codigo_categoria_titulos; ?>"><? print $nombre_categoria ; ?></option>
              <?
			}
			
			}
			?>
            </select>
          </td>
        </tr>
        <tr>
          <td height="37"><input type="hidden" value="1" name="enviar"></td>
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
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
	
  </tr>
</table>

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
