<?php
$id=$_GET['id'];

		$var=$Noticias->getId( $id );
	    while( list( $key, $val) = each($var) ) 
			{
		    	$codigo_noticia 	= $var[$key][codigo_noticia];
				$nombre_categoria 	= $var[$key][nombre_categoria];
				$titulo 			= $var[$key][titulo];
				$fecha_publicacion 	= $var[$key][fecha_publicacion];
				$estatus_noticia	= $var[$key][estatus_noticia];
				$codigo_categoria 	= $var[$key][codigo_categoria];
				$nombre_categoria	= $var[$key][nombre_categoria];
				$noticia_completa 	= $var[$key][noticia_completa];
				$resumen_noticia	= $var[$key][resumen_noticia];
				$img_1				= $var[$key][imagen_1];
				$img_2				= $var[$key][imagen_2];
				$origen 				= $var[$key][origen];
			}
?>
<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK> 
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  class="Color_tabla">
  <tr>
    <td width="1%" >&nbsp;</td>
    <td width="99%" ></td>

  </tr>
  <tr class=" Color_tabla">
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="noticias" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" >Titulo de la Noticia: </td>
          <td width="50%" >Fecha de Publicación de la Noticia: </td>
        </tr>
        <tr>
          <td  ><input type="text"  name="titulo" size="50" value="<? print $titulo ;?>"/></td>
          <td  ><input type="text"  name="fecha" size="30" value="<? print $fecha_publicacion ;?>" readonly="readonly"/>
				<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.noticias.fecha,'yyyy/mm/dd',this)" />
         </td>
        </tr>
		<tr>
        	<td colspan="2">Noticia Completa</td>
        </tr>
        <tr>
        	<td height="16" colspan="3" class="texto_simple">
            <textarea name="texto_completo" cols="90" rows="25"  class="mceEditor" id="texto_completo"><? print  $noticia_completa ?></textarea>
			</td>
        </tr>
		<tr>
        	<td colspan="2">Resumen de la Noticia</td>
        </tr>
        <tr>
        	<td height="16" colspan="3" class="texto_simple">
            <textarea name="texto_resumen" cols="90" rows="25"  class="mceEditor" id="texto_completo"><? print  $resumen_noticia ?></textarea>
			</td>
        </tr>
        <tr>
          <td  >Categoria Noticia </td>
          <td  >Estatus de la Noticia </td>
        </tr>
        <tr>
            <td> 
       			<select name="categorias_noticias" class="textfield" id="categorias_noticias"  >
				<? $Categorias=$Noticias->listarCategoriasNoticias();
					while( list( $key, $val) = each($Categorias) ) 
						{
							$codigo_categoria_gen 	= $Categorias[$key][codigo_categoria];
							$nombre_categoria_gen 	= $Categorias[$key][nombre_categoria];
							if($codigo_categoria==$codigo_categoria_gen){
							?>
				    	          <option value="<? print $codigo_categoria_gen; ?>" selected="selected"> <? print $nombre_categoria_gen; ?> </option>
				            <? }else{
							?>
					              <option value="<? print $codigo_categoria_gen; ?>"> <? print $nombre_categoria_gen; ?> </option>
				              <?
							}
						
						}
					?>
	          </select>
            </td>
          	<td  > 
          			<select name="estatus_noticia" class="textfield" id="estatus_noticia"  >
					<option <?php if ($estatus_noticia=='Pendiente'){?>  selected="selected" <?php }?> value="Pendiente">Pendiente</option>
					<option <?php if ($estatus_noticia=='Publicar'){?>  selected="selected" <?php }?> value="Publicar">Publicar</option>
                   </select>
			</td>
        </tr>
        <tr>
          <td><input name="imagen_1" type="file"  id="imagen_1"  /></td>
          <td><input name="imagen_2" type="file"  id="imagen_2" /></td>
        </tr>

		<tr >
	        <td width="50%">	
            	<?php if ($img_2=="") { 	
					print "Noticia Sin Imagen";
					}else{
				?>
				<a href="#"  na onclick="window.open('../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_1 ?>', 'IMG1')" title="Imagen 1" >
				<img src="../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_1 ?>" width="350" height="350" border="0" alt="Imagen 1"/>
                </a>
				<?	
						}
				?>
			</td>
			<td width="50%">
            	<?php if ($img_2=="") { 	
					print "Noticia Sin Imagen";
			
					}else{
				?>
				<a href="#"  na onclick="window.open('../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_2 ?>', 'IMG2')" title="Imagen 2" >
				<img src="../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_2 ?>" width="350" height="350" border="0" alt="Imagen 2"/>
                </a>
				<?	
						}
				?>
            </td>
        </tr>
        <tr> 
          <td height="67"><input type="hidden" value="1" name="enviar">
			Origen <br /> 
           	<input type="text" size="30"   name="origen"  value="<?php print $origen ?>"/>
	        </td>
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
			plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
			
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,cut,copy,paste,pastetext,pasteword,cleanup",
 			theme_advanced_buttons2 : "styleselect,formatselect,fontselect,fontsizeselect", 
			theme_advanced_buttons3 : "bullist,numlist,|,outdent,indent,blockquote,undo,redo,|,link,unlink,|,forecolor,backcolor,", 
			theme_advanced_buttons4 : "tablecontrols", 

			theme_advanced_toolbar_location : "top", 
			theme_advanced_toolbar_align : "left", 
			
			plugins : 'inlinepopups,table', 
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