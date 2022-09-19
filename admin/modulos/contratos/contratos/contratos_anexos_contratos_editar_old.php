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
    <td width="799" bgcolor="CC6666"><div align="center"><span class="verdana_blanco"><? print $msg; 
	$msg="";?></span></div></td>
    <td width="10" bgcolor="CC6666">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="63%" height="100%" border="0" cellpadding="2" cellspacing="2" class="texto">
        <tr>
          <td width="29%" height="16">Nombre del Anexo: </td>
          <td width="4%">&nbsp;</td>
          <td width="67%">&nbsp;</td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="nombre_anexo" type="text" class="textfield" id="nombre_anexo" size="35" value="<? print $nombre_anexo ?>" /></td>
          <td valign="middle">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16">Descripcion Anexo : </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="descripcion" cols="50" rows="10"  class="mceEditor" id="descripcion"><? print $descripcion_anexo ?></textarea></td>
        </tr>
        <tr>
          <td height="16">Texto Completo :</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="texto_completo" cols="50" rows="10"  class="mceEditor" id="texto_completo"><? print $texto_completo_anexo ?></textarea></td>
        </tr>
        <tr>
          <td height="16">Pdf Asociado : </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16"><label>
            <input name="pdf_asociado" type="file" id="pdf_asociado" />
          </label></td>
          <td>&nbsp;</td>
          <td><input type="hidden" name="enviar" value="1" /></td>
        </tr>
        
        <tr>
          <td colspan="3" valign="bottom"><label></label>
                <input name="Submit" type="submit" class="botonGuardar" value=" " /></td>
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