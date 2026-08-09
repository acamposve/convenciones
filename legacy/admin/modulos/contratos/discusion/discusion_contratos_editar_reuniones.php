<?
$codigo_bitacora=$_GET['id_bitacora'];		
$codigo_reunion=$_GET['id'];
	$var=$discusion->verReunion($codigo_reunion);	
		while( list( $key, $val) = each($var) ) {
			$codigo_reunion_busqueda = $var[$key][codigo_reunion];

			if ($codigo_reunion_busqueda ==$codigo_reunion )
			{
			$codigo_bitacora = $var[$key][codigo_bitacora];
			$duracion_reunion = $var[$key][codigo_empresa];
            $fecha_reunion = $var[$key][fecha_reunion];
            $hora_reunion = $var[$key][hora_reunion];
			$duracion_reunion = $var[$key][duracion_reunion];
            $resumen_reunion = $var[$key][resumen_reunion];
            $detalles_reunion = $var[$key][detalles_reunion];
            $asistentes_reunion = $var[$key][asistentes_reunion];
			}
		}
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
<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK> 
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
    
    		<br/>
		<div align="center">  <? print $nombre_empresa;?> </div>

    
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td height="16" width="50%">Fecha Reunion : </td>
          <td width="50%">
       		Hora de la Reunion: </td>
          </tr>
        <tr>
          <td width="50%" height="16" valign="middle">
          	<input name="FechaReunion" type="text"  id="FechaReunion"  value="<? print $fecha_reunion;?>"  readonly="readonly" />
			<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.FechaReunion,'dd/mm/yyyy',this)" />
            </td>
			<td>
			<input name="HoraReunion" type="text" size="50"  id="HoraReunion" value="<? print $hora_reunion;?>" /> </td> 
        </tr><tr>
          <td height="16" colspan="3">Duracion Reunion</td>
          </tr>
        <tr>
          <td height="20" colspan="2"><label>
            <input name="DuracionReunion" type="text"  id="DuracionReunion"  value="<? print $duracion_reunion ?>" />
          </label></td>
        </tr>
        <tr>
          <td height="16"  colspan="2" >Detalle Reunion</td>
        </tr>
        
        
          <td height="20" colspan="2"  ><label>
			<textarea name="DetalleReunion" cols="90" rows="20" id="DetalleReunion"  class="mceEditor" readonly="readonly"><?php print $detalles_reunion;?></textarea>
          </label></td>
        </tr>
        <tr>
          <td colspan="2" >Resumen de la Reunion:</td>
        </tr>
        <tr>
          <td colspan="2" >	<textarea name="ResumenReunion" cols="90" rows="20" id="ResumenReunion" class="mceEditor" readonly="readonly"><?php print $resumen_reunion;?></textarea>        </tr>
        <tr>
          <td colspan="2"  >Asistentes de la Reunion:</td>
          </tr>
        <tr>
          <td colspan="2" >
			<textarea name="AsistentesReunion" cols="90" rows="10" id="AsistentesReunion" class="mceEditor" readonly="readonly"><?php print $asistentes_reunion;?></textarea>          
            &nbsp;</td>
        </tr>

        <tr>
          <td colspan="3"><input name="enviar" type="hidden" value="1"/>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar" /></td>
          </tr>
      </table>
    </form></td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
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
