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
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  class="">
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
    		<br/>
		<div align="center">   <? print $nombre_empresa;?> </div>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16" >Nro Oferta :</td>
          <td>Titulo Oferta: </td>
          </tr>
        <tr>
          <td  height="16" valign="middle"><input name="nroOferta" type="text"  id="nroOferta" value="<?php print $nro_oferta;?>" readonly="" /></td>
          <td width="50%"><input name="tituloOferta" type="text" id="tituloOferta" value="<?php print $titulo_oferta;?>" readonly=""/></td>
        </tr>
        <tr>
          <td height="16" colspan="3">Texto Oferta : </td>
          </tr>
        <tr>
          <td height="20" colspan="2">
          <table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
            	<tr>
                	<td bgcolor="#FFFFFF"><? print  $texto_completo_oferta ?></td>
              	</tr>
            </table>

			</td>
        </tr>
        <tr>
          <td colspan="3">Peticion:</td>
          </tr>
        <tr>
          <td colspan="3"><select name="peticion" class="textfield" id="peticion" disabled="disabled"  >
            <option value="0" selected="selected">Seleccione Peticion Asociada</option>
            <?
						$var=$discusion->listarPeticiones(0,500,$codigo_bitacora,"");
						
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
        </tr>
<!--
        <tr>
          <td colspan="3"> Titulo Comparativo: </td>
        </tr>
        <tr>
          <td colspan="3"><select name="titulo" class="textfield" id="titulo" disabled="disabled"  >
            <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
            <?
						$var=$titulos->listarTitulos(0,500,"");
						
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
        </tr>
-->
        <tr>
          <td colspan="3"><input name="enviar" type="hidden" value="1"/></td>
          </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
            <div align="right">
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_ofertas&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="editar"width="28" height="28" border="0" /></a>
            <!--
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_ofertas&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>">&nbsp;
            <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="eliminar" width="18" height="17" border="0" /></a>
             -->
            &nbsp; </div></td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
  </tr>
</table>
