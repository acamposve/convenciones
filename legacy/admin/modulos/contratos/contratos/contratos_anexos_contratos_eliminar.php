<?
$codigo_anexo=$_GET['id'];
$var=$contratos->obtenerAnexoContratosPorId($codigo_anexo);
$nombre_anexo=$contratos->nombre_anexo;
$descripcion_anexo=$contratos->descripcion_anexo;
$texto_completo_anexo=$contratos->texto_completo_anexo;
$pdf_anexo=$contratos->pdf_anexo;
$codigo_contrato=$contratos->codigo_contrato;

$codigo_contrato=$contratos->codigo_contrato;
$Contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
	    while( list( $key, $val) = each($Contr) )
			{
				$codigo_contrato = $Contr[$key][codigo_contrato];
				$nombre_empresa = $Contr[$key][nombre_empresa];
			}


?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><div align="center"><span class="verdana_blanco"><strong></strong></span>
			<?print $nombre_empresa;?>
    </div></td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td height="16">Nombre del Anexo: </td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="nombre_anexo" type="text" class="textfield" id="nombre_anexo" readonly="" size="35" value="<? print $nombre_anexo?>" /></td>
        </tr>
        <tr>
          <td height="16">Descripcion Anexo : </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print $descripcion_anexo?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="16">Texto Completo :</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print $texto_completo_anexo?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="16">Pdf Asociado : </td>
        </tr>
        <tr>
          <td height="16">
          		<a href="modulos/contratos/contratos/documentos/<? print $codigo_contrato?>/anexos/<? print $pdf_anexo  ?>" onclick="window.open('', 'VEN_PDF')" title="Ver PDF"  target="VEN_PDF" >
				<img src="../plantillas/plantilla_admin/images/impresora.gif" alt="ver pdf" border="0"  align="left"/></a>
			<input type="hidden" name="enviar" value="1" /></td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
            <div align="right">&nbsp;&nbsp;&nbsp;<a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_anexos&amp;id=<? print $codigo_anexo; ?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;</a><a href="?url=contratos&amp;tbl=Contratos&amp;accion=listar_anexos&amp;id=<? print $codigo_contrato; ?>">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
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
