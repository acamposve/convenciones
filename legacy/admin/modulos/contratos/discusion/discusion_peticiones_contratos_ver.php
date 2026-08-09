<?
$codigo_peticion=$_GET['id'];

	$var=$discusion->obtenerPeticionesPorId($codigo_peticion);	
		$codigo_peticion =	$discusion->codigo_peticion;
		$codigo_bitacora=	$discusion->codigo_bitacora;
		$nro_peticion=	$discusion->nro_peticion;
		$texto_completo_peticion=	$discusion->texto_completo_peticion;
		$estatus_peticion=	$discusion->estatus_peticion;
		$codigo_titulo_comparativo=	$discusion->codigo_titulo_comparativo;
		$titulo_peticion=	$discusion->titulo_peticion;

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
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
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
          <td height="16"  width="50%">Nro Peticion :</td>
          <td width="50%">Titulo Peticion: </td>
          </tr>
        <tr>
          <td height="16"  valign="middle"><input name="nroPeticion" type="text" class="textfield" id="nroPeticion" value="<? print $nro_peticion?>" readonly=""/></td>
          <td><input name="tituloPeticion"  size="50" type="text" class="textfield" id="tituloPeticion" value="<? print $titulo_peticion?>" readonly=""  /></td>
          </tr>
        <tr>
          <td height="16"  colspan="2">Texto Peticion : </td>
          </tr>
        <tr>
          <td height="20" colspan="3">
          <table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000"  style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print  $texto_completo_peticion ?></td>
              </tr>
            </table>
       </td>
          </tr>
<!--
        <tr>
          <td >Titulo Comparativo: </td>
          <td >&nbsp;</td>

        </tr>
        <tr>
          <td colspan="3"><select name="titulo" class="textfield" id="titulo" disabled="disabled"  >
            <option value="0">Seleccione Titulo Comparativo</option>
            <?
						$var=$titulos->listarTitulos(0,500,"");
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($codigo_titulo==$codigo_titulo_comparativo){
							?>
            <option value="<? print $codigo_titulo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
            <? }else{
			?>
            <option value="<? print $codigo_titulo; ?>"> <? print $nombre_titulo; ?> </option>
            <?
						}
						
						}
					?>
          </select></td>
          </tr>
-->
        <tr>
          <td colspan="3" valign="bottom"><div align="right">
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_peticiones&amp;id=<? print $codigo_peticion;?>&amp;id_bitacora=<? print $codigo_bitacora?>">
          <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="editar" width="28" height="28" border="0" /></a>

		<?php if ($estatus_peticion !=9){?>          
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_peticiones&amp;id=<? print $codigo_peticion;?>&amp;id_bitacora=<? print $codigo_bitacora?>">&nbsp;
          <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="eliminar" width="28" height="28" border="0" /></a>&nbsp;         
          <?php } ?>
		 </div>
          </td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10"></td>
    <td ></td>
  </tr>
</table>
