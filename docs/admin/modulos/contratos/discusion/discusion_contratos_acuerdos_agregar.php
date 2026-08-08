<?
	$codigo_bitacora = $_GET['id_bitacora'];
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
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="1$" height="32" >&nbsp;</td>
    <td width="99%" >
            <br />
            <div align="center">   <font color="#FFFFFF" ><? print $nombre_empresa;?></font> </div>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16" >
          		Fecha Acuerdo:</td>
                             
                           
		<td width="50%">
                Nro Clausula          </td>
          </tr>
        <tr>
          <td  height="16" valign="middle" >
          		<input name="FechaAcuerdo" type="text"  id="FechaAcuerdo" /></td>
                             
          <td>
                <input name="NroClausula" type="text"  id="NroClausula" /></td>
        </tr>
        <tr>
          <td height="16" colspan="3">Titulo del Acuerdo</td>
          </tr>
        <tr>
          <td height="16" colspan="3">
               <input name="TituloAcuerdo" type="text"  size="55" id="TituloAcuerdo" /></td>
        <tr>
          <td height="16" colspan="3">Texto Completo Acuerdo</td>
          </tr>

        <tr>
          <td height="20" colspan="2"><label>
            <textarea name="TextoCompletoAcuerdo" cols="90" rows="20" class="mceEditor" id="TextoCompletoAcuerdo" /></textarea>
            
          </label></td>
        </tr>
        <tr>
          <td colspan="3">Resumen Acuerdo</td>
        </tr>
        <tr>
          <td colspan="3"><textarea name="ResumenAcuerdo"  cols="90" rows="20" class="mceEditor" id="ResumenAcuerdo" />
          
            </textarea>		  </td>
        </tr>
        <tr>
          <td colspan="3">Oferta:</td>
          </tr>
        <tr>
          <td colspan="3">
          <select name="oferta" class="textfield" id="oferta"  >
            <option value="0" selected="selected">Seleccione Oferta Asociada</option>
            <?
						
				$ofertas=$discusion->listarOfertas( 0, 500,$codigo_bitacora,"");
						while( list( $key, $val) = each($ofertas) ) {
					$codigo_ofertas = $ofertas[$key][codigo_ofertas];
					$nro_oferta = $ofertas[$key][nro_oferta];
					$titulo_oferta = $ofertas[$key][titulo_oferta];

			
			?>
            <option value="<? print $codigo_ofertas; ?>"> <? print $titulo_oferta; ?> </option>
            <?

						}
					?>
          </select></td>
        </tr>
        <tr>
        <tr>        </tr>
          <td colspan="3">Peticion:</td>
        <tr>
          <td colspan="3">
          <select name="peticion" class="textfield" id="peticion"  >
            <option value="0" selected="selected">Seleccione Peticion Asociada</option>
            <?
						
				$var=$discusion->listarPeticiones( 0, 5000,$codigo_bitacora,"");
						while( list( $key, $val) = each($var) ) {
					$codigo_peticion = $var[$key][codigo_peticion];
					$nro_peticion = $var[$key][nro_peticion];
					$titulo_peticion = $var[$key][titulo_peticion];

			
			?>
            <option value="<? print $codigo_peticion; ?>"> <? print $titulo_peticion; ?> </option>
            <?

						}
					?>
          </select></td>
        </tr>
        
        
        
        
          <td colspan="3">Titulo Comparativo: </td>
        </tr>
        <tr>
          <td colspan="3"><select name="TituloComparativo" class="textfield" id="TituloComparativo"  >
            <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
            <?
						$var=$titulos->listarTitulos(0,500,"");
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($codigo_titulo==$codigo_titulo_comparativo){
							?>
            <option value="<? print $codigo_titulo_comparativo; ?>"> <? print $nombre_titulo; ?> </option>
            <? }else{
			?>
            <option value="<? print $codigo_titulo_comparativo; ?>"> <? print $nombre_titulo; ?> </option>
            <?
						}
						
						}
					?>
                    </select></td>
        </tr>
         <tr>
         	<td colspan="3" >Campo Comparativo: </td>
		 </tr>        
         <tr>
                <td colspan="3"> <input name="CampoComparativo" type="text"  size="55" id="CampoComparativo" /></td>
		 </tr>     
        <tr>
          <td colspan="3"><input name="enviar" type="hidden" value="1"/>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar" /></td>
          </tr>
		<tr><td>&nbsp; </td>
        </tr>
      </table>
    </form>
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
