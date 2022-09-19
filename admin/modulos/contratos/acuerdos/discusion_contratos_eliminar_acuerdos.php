<?
$codigo_oferta=$_GET['id'];

	$var=$discusion->obtenerOfertasPorId($codigo_oferta);	
		$codigo_ofertas =	$discusion->codigo_ofertas;
		$codigo_bitacora=	$discusion->codigo_bitacora;
		$codigo_peticion=	$discusion->codigo_peticion;
		$nro_oferta=	$discusion->nro_oferta;
		$texto_completo_oferta=	$discusion->texto_completo_oferta;
		$estatus_oferta=	$discusion->estatus_oferta;
		$codigo_titulo=	$discusion->codigo_titulo_comparativo;
		$titulo_oferta=	$discusion->titulo_oferta;

?><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="F8EEEE">
  <tr>
    <td width="13" height="32" bgcolor="CC6666">&nbsp;</td>
    <td width="505" bgcolor="CC6666"><div align="center"><span class="verdana_blanco"><? print $msg; 
	$msg="";?></span></div></td>
    <td width="11" bgcolor="CC6666">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="texto">
        <tr>
          <td height="16" colspan="2">Nro Oferta :</td>
          </tr>
        <tr>
          <td height="16" colspan="2" valign="middle"><input name="nroOferta" type="text" class="texto" id="nroOferta" value="<?php print $nro_oferta;?>" readonly="" /></td>
          </tr>
        <tr>
          <td height="16" colspan="2">Texto Oferta : </td>
          </tr>
        <tr>
          <td height="20" colspan="2"><label>
            <textarea name="oferta" cols="60" rows="6" id="oferta" readonly="readonly"><?php print $texto_completo_oferta;?></textarea>
          </label></td>
          </tr>
        <tr>
          <td colspan="2">Titulo Oferta: </td>
        </tr>
        <tr>
          <td colspan="2"><input readonly="" name="tituloOferta" type="text" class="texto" id="tituloOferta" value="<?php print $titulo_oferta;?>"/></td>
        </tr>
        <tr>
          <td colspan="2">Peticion:</td>
          </tr>
        <tr>
          <td width="42%"><select name="peticion" class="textfield" id="peticion" disabled="disabled"  >
            <option value="0" selected="selected">Seleccione Peticion Asociada</option>
            <?
						$var=$discusion->listarPeticionesSelect($codigo_bitacora);
						
							while( list( $key, $val) = each($var) ) {
							$codigo_pet = $var[$key][codigo_peticion];
							$nro_peticion = $var[$key][nro_peticion];
							$titulo_peticion = $var[$key][titulo_peticion];

			 if ($codigo_peticion==$codigo_pet){
			?>
            <option value="<? print $codigo_pet; ?>" selected="selected"> <? print $titulo_peticion; ?> </option>
            <? }else{
			?>
            <option value="<? print $codigo_pet; ?>"> <? print $titulo_peticion; ?> </option>
            <?
						}
						}
					?>
          </select></td>
          <td width="5%">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">Titulo Comparativo: </td>
          </tr>
        <tr>
          <td><select name="titulo" class="textfield" id="titulo" disabled="disabled"  >
            <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
            <?
						$var=$titulos->listarTitulos();
						
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
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><input name="enviar" type="hidden" value="1"/></td>
          </tr>
        <tr>
          <td colspan="2" valign="bottom"><label></label>
            <div align="right">Desea eliminar esta oferta &nbsp;&nbsp;&nbsp;<a href="?url=contratos&amp;tbl=Discusion%20de%20contratos&amp;accion=eliminar_ofertas&amp;id=<? print $codigo_oferta?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;</a><a href="?url=contratos&amp;tbl=Contratos&amp;accion=listar_ofertas&amp;id=<? print $codigo_bitacora; ?>">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
        </tr>
      </table>
    </form></td>
    <td bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" bgcolor="F8EEEE">&nbsp;</td>
    <td bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr>
    <td height="92" bgcolor="F8EEEE">&nbsp;</td>
    <td bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr>
    <td height="10" bgcolor="F8EEEE"></td>
    <td bgcolor="F8EEEE"></td>
    <td bgcolor="F8EEEE"></td>
  </tr>
</table>
