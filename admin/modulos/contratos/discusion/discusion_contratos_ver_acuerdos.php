<?
	$codigo_bitacora = $_GET['id_bitacora'];
	$codigo_acuerdo = $_GET['id'];
	$busqueda_empresa=$discusion->listarDiscusiones(0,500,"");
	while( list( $key, $val) = each($busqueda_empresa) ) {
    	$codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
		$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
		if ($codigo_bitacora_original ==$codigo_bitacora )
		{
			$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
		} 
	}
	$var=$discusion->verAcuerdo($codigo_acuerdo);	

	while( list( $key, $val) = each($var) ) {
		$codigo_oferta_acuerdo = $var[$key][codigo_oferta];
		$fecha_acuerdo = $var[$key][fecha_acuerdo];
		$nro_articulo = $var[$key][nro_articulo];
		$texto_completo_articulo = $var[$key][texto_completo_articulo];
		$resumen_articulo = $var[$key][resumen_articulo];
		$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
		$campo_comparativo = $var[$key][campo_comparativo];
		$estatus_acuerdo = $var[$key][estatus_acuerdo];		
		$codigo_peticion = $var[$key][codigo_peticion];
		$nombre_titulo = $var[$key][nombre_titulo];
		$titulo_articulo = $var[$key][titulo_articulo];
		}

?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  >
  <tr>
    <td width="1$" height="32" >&nbsp;</td>
    <td width="99%">
            <br />
            <div align="center">  <? print $nombre_empresa;?></div>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td height="16" width="50%">
          		Fecha Acuerdo:</td>
		<td width="50%">                Nro Clausula          </td>
          </tr>
        <tr>
          <td width="42%" height="16" valign="middle">
   		    <input name="FechaAcuerdo" type="text"  id="FechaAcuerdo"  readonly="readonly" value="<? print $fecha_acuerdo ?>"/></td>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<td>
                <input name="NroClausula" type="text"   id="NroClausula" readonly="readonly"  value="<? print $nro_articulo ?> " /></td>
        </tr>
        <tr>
          <td height="16" colspan="3">Titulo del Acuerdo</td>
          </tr>
        <tr>
          <td height="16" colspan="3">
               <input name="TituloAcuerdo" type="text"  size="50" id="TituloAcuerdo" readonly="readonly" value="<? print $titulo_articulo ?> " /></td>
        <tr>
          <td height="16" colspan="3">Texto Completo Acuerdo</td>
          </tr>

        <tr>
          <td height="20" colspan="2">
			<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                	<td bgcolor="#FFFFFF"><? print  $texto_completo_articulo ?></td>
            </table>            
			</td>
        </tr>
        <tr>
          <td colspan="3">Resumen Acuerdo</td>
        </tr>
        <tr>
          <td colspan="3">
			<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                	<td bgcolor="#FFFFFF"><? print  $resumen_articulo ?></td>
            </table>
            </td>
		</tr>
        <tr>
          <td colspan="3">Oferta:</td>
          </tr>
        <tr>
          <td colspan="2"><select name="oferta" class="textfield" id="oferta"  disabled="disabled">
            <?
						
				$ofertas=$discusion->listarOfertas( 0, 500,$codigo_bitacora,"");
					while( list( $key, $val) = each($ofertas) ) {
							$codigo_ofe = $ofertas[$key][codigo_ofertas];
							$nro_oferta = $ofertas[$key][nro_oferta];
							$titulo_oferta = $ofertas[$key][titulo_oferta]; 
						if ($codigo_oferta_acuerdo==$codigo_ofe)
						{
							?>
							<option value="<? print $codigo_ofe; ?>" selected="selected"> <? print $titulo_oferta; ?> </option>
							<?
						}
	
						}
						?>
          </select>
          </td>
        </tr>
        <tr>
        <tr>        </tr>
          <td colspan="2">Peticion:</td>
        <tr>
          <td>
          <select name="peticion" class="textfield" id="peticion"  disabled="disabled">
            <?
						
				$var=$discusion->listarPeticiones( 0, 500,$codigo_bitacora,"");
				while( list( $key, $val) = each($var) ) {
					$codigo_pet = $var[$key][codigo_peticion];
					$nro_peticion = $var[$key][nro_peticion];
					$titulo_peticion = $var[$key][titulo_peticion];
					if ($codigo_pet==$codigo_peticion){
					?>
		            <option value="<? print $codigo_peticion; ?>" selected="selected"> <? print $titulo_peticion; ?> </option>
        		    <?
					}
						}
					?>
          </select></td>
        </tr>
<!--        
          <td colspan="3">Titulo Comparativo: </td>
        </tr>
        <tr>
          <td colspan="2"><select name="TituloComparativo" class="textfield" id="TituloComparativo"  disabled="disabled" >
            <?
						$var=$titulos->listarTitulos(0,500,"");
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comp = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
								if($codigo_titulo_comparativo==$codigo_titulo_comp)
								{
									?>
									<option value="<? print $codigo_titulo_comparativo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
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
          <td colspan="3">
   		    <input name="FechaAcuerdo" type="text"  id="FechaAcuerdo"  size="50" readonly="readonly" value="<? print $campo_comparativo  ?>"/></td>

		 </tr>     
-->

        <tr>
          <td colspan="3" valign="bottom"><input name="enviar" type="hidden" value="1"/>
            <div align="right">
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_acuerdos&amp;id=<? print $codigo_acuerdo;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="editar" width="28" height="28" border="0" /></a>
            <!--
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_acuerdos&amp;id=<? print $codigo_acuerdo;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="eliminar" width="18" height="17" border="0" /></a> 
            -->
            </div>
            </td>
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
