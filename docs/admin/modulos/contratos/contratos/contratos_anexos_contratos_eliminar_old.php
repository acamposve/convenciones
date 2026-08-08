<?
$codigo_anexo=$_GET['id'];
$var=$contratos->obtenerAnexoContratosPorId($codigo_anexo);
$nombre_anexo=$contratos->nombre_anexo;
$descripcion_anexo=$contratos->descripcion_anexo;
$texto_completo_anexo=$contratos->texto_completo_anexo;
$pdf_anexo=$contratos->pdf_anexo;
$codigo_contrato=$contratos->codigo_contrato;

?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="F8EEEE">
  <tr>
    <td width="24" height="32" bgcolor="CC6666">&nbsp;</td>
    <td width="799" bgcolor="CC6666"><div align="center"><span class="verdana_blanco"><strong></strong></span></div></td>
    <td width="10" bgcolor="CC6666">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table width="63%" height="100%" border="0" cellpadding="2" cellspacing="2" class="texto">
        <tr>
          <td width="29%" height="16">Nombre del Anexo: </td>
          <td width="4%">&nbsp;</td>
          <td width="67%">&nbsp;</td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="nombre_anexo" type="text" class="textfield" id="nombre_anexo" readonly="" size="35" value="<? print $nombre_anexo?>" /></td>
          <td valign="middle">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16">Descripcion Anexo : </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><table width="425" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print $descripcion_anexo?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="16">Texto Completo :</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><table width="425" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print $texto_completo_anexo?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="16">Pdf Asociado : </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16"><label><? print $pdf_anexo  ?> <a href="modulos/contratos/contratos/documentos/<? print $codigo_contrato."/anexos/".$pdf_anexo  ?>">[ver pdf]</a> </label></td>
          <td>&nbsp;</td>
          <td><input type="hidden" name="enviar" value="1" /></td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
            <div align="right">&nbsp;&nbsp;&nbsp;<a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_anexos&amp;id=<? print $codigo_anexo; ?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;</a><a href="?url=contratos&amp;tbl=Contratos&amp;accion=listar_anexos&amp;id=<? print $codigo_contrato; ?>">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
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
