<?
$codigo_anexo=$_GET['id'];
$var=$contratos->obtenerAnexoContratosPorId($codigo_anexo);
$nombre_anexo=$contratos->nombre_anexo;
$descripcion_anexo=$contratos->descripcion_anexo;
$texto_completo_anexo=$contratos->texto_completo_anexo;
$pdf_anexo=$contratos->pdf_anexo;
$codigo_contrato=$contratos->codigo_contrato;

		$total=$contratos->contarAnexosContratos($codigo_contrato);

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
    <td width="99%"><div align="center">
    	  <?print $nombre_empresa;?>
    </div></td>
  
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%" height="16">Nombre del Anexo: </td>
        </tr>
        <tr>
          <td height="16"  valign="middle"><input name="nombre_anexo" type="text" class="textfield" id="nombre_anexo" size="35" value="<? print $nombre_anexo ?>" /></td>
        </tr>
        <tr>
          <td height="16">Descripcion Anexo : </td>
        </tr>
        <tr>
          <td height="16" colspan="3">
          <table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000"  style="border-style: inset;border-width:1">
              <tr>
                <td ><? print $descripcion_anexo?></td>
              </tr>
           </td>
            </table>
        </td>
        </tr>
        <tr>
          <td height="16">Texto Completo :</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3">
          <table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000"  style="border-style: inset;border-width:1">
              <tr>
                <td ><? print $texto_completo_anexo?></td>
              </tr>
           </td>
            </table>
        </td>
        </tr>        <tr>
          <td height="16">Pdf Asociado : 
          <br />
          <a href="modulos/contratos/contratos/documentos/<? print $codigo_contrato?>/anexos/<? print $pdf_anexo  ?>" onclick="window.open('', 'VEN_PDF')" title="Ver PDF"  target="VEN_PDF" >
          <img src="../plantillas/plantilla_admin/images/impresora.gif" alt="ver pdf" border="0"  align="left"  width="28" height="28" /></a>
          </td>

        </tr>
        <tr> 
          <td height="10" colspan="3" >
          
          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_anexos&amp;id=<? print $codigo_anexo;?>" >
          <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar Anexos Contratos" border="0"  align="right"  width="28" height="28" /></a>
          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar_anexos&amp;id=<? print $codigo_anexo;?>" >
          <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar Anexos Contratos" border="0"  align="right"  width="28" height="28" />
        <tr>
                  <td height="16">            </td>
      
          <td colspan="3" valign="bottom">&nbsp;</td>
        </tr>
      </table>
    </form>
    <td height="30" >&nbsp;</td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
  </tr>
  <tr>
    <td height="22" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
    <td ></td>
  </tr>
</table>
