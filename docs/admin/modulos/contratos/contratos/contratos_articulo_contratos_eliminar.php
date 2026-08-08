<?
$codigo_articulo=$_GET['id'];
$page_return = $_GET['page'];
$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$nro_articulo=$contratos->nro_articulo;
$texto_completo=$contratos->texto_completo_articulo;
$resumen_texto=$contratos->resumen_articulo;
$titulo=$contratos->codigo_titulo_comparativo;
$campo=$contratos->campo_comparativo;
$titulo_articulo=$contratos->titulo_articulo;

$articulo=$contratos->ListarContratoDefinido( $codigo_articulo,2);
	    while( list( $key, $val) = each($articulo) )
			{
				$codigo_contrato = $articulo[$key][codigo_contrato];

			}

$contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
	    while( list( $key, $val) = each($contr) )
			{
				$nombre_empresa = $contr[$key][nombre_empresa];
			}

?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><div align="center"><strong></strong></span>
	<? print $nombre_empresa; ?>
	</td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%" height="16">Nro de  Clausula : &nbsp;&nbsp;&nbsp;
				                    	
                                        Titulo Clausula : </td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="nro_articulo" type="text" class="textfield" id="nro_articulo" size="3" value="<? print  $nro_articulo ?>" readonly="" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="titulo_articulo" type="text" class="textfield" id="titulo_articulo" size="50" value="<? print  $titulo_articulo ?>" readonly="" /></td>

        </tr>
        <tr>
          <td height="16">Texto Completo de la Clausula: </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print  $texto_completo ?></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td height="16">Resumen de la Clausula:</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print  $resumen_texto ?></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td height="16">Titulo Comparativo: 
          					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          								
							Campo Comparativo: </td>
        </tr>
        <tr>
          <td height="16"><select name="titulo" class="textfield" id="titulo" disabled="disabled">
              <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
              <?
						$var=$titulos->listarTitulosparaleyes();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($titulo==$codigo_titulo_comparativo){
							?>
              <option value="<? print $codigo_titulo_comparativo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
              <? }else{
			?>
              <option value="<? print $codigo_titulo_comparativo; ?>"> <? print $nombre_titulo; ?> </option>
              <?
						}
						
						}
					?>
          </select>&nbsp;&nbsp;&nbsp;
            <input name="campo" type="text" class="textfield" id="campo" size="35" value="<? print  $campo ?>" readonly=""/>
            <input type="hidden" name="enviar" value="1" /></td>

        </tr>
        
        <tr>
          <td colspan="3" valign="bottom"><label></label>
            <div align="right">&nbsp;&nbsp;&nbsp;
            <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_articulos&amp;id=<? print $codigo_articulo; ?>&amp;flag=SI&page=<? print $page_return ?>"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;</a>
            <a href="?url=contratos&amp;tbl=Contratos&amp;accion=listar_articulos&amp;id=<? print $codigo_contrato; ?>">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
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
    <td height="10" ></td>
    <td ></td>

  </tr>
</table>
