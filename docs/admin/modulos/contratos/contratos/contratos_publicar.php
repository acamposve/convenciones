<?
$codigo_contrato=$_GET['id'];
$var=$contratos->obtenerContratosPorId($codigo_contrato);
$pdf=$contratos->pdf_auto;
$duracion=$contratos->duracion;
$inicio=$contratos->fecha_inicio;
$termino=$contratos->fecha_termino;
$ambito=$contratos->ambito_aplicacion;
$empresa=$contratos->codigo_empresa;
?>
<script  type="" language="">
<!--
function VentanaPDF(){
window.open("modulos/contratos/contratos/documentos/<? print $codigo_contrato?>/<?print $pdf?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");
}
-->
</script>
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" >
<tr  >
    <td width="1%" height="32" ></td>
    <td width="99%"  align="center"><font size="+2" >Publicar Contrato </font></td>
</tr>
<tr >
    <td height="119" > </td>
  <td rowspan="3" valign="top" >
    	<form name="form1" method="post" action="" enctype="multipart/form-data">
	    <table width="100%" height="100%"  class="trTblReg1">

        <tr>
          	<td height="16">Fecha de Inicio: <span class="style1">(dd/mm/yyyy) </span></td>
          	<td>Fecha de Termino: <span class="style1">(dd/mm/yyyy) </span></td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td height="20"><input name="inicio" type="text" class="texto" id="inicio" readonly="" value="<? print  $inicio ?>" size="15" /></td>
          	<td height="20"><input name="termino" type="text" class="texto" id="termino" readonly="" value="<? print $termino ?>" size="15" /></td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td>Ambito de Aplicacion:</td>
          	<td>Empresa:</td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td><input name="ambito" type="text" class="texto" id="ambito" readonly="" value="<? $ambito = strtoupper($ambito); print $ambito ?>" /></td>
          	<td><select name="empresa" class="textfield" id="empresa" disabled="disabled">
            <option value="0">Seleccione una Epresa</option>
<?
			$var=$empresas->listarEmpresas2();
			while( list( $key, $val) = each($var) ) 
				{
					$codigo_empresa = $var[$key][codigo_empresa];
					$nombre_empresa = $var[$key][nombre_empresa];
					if($empresa==$codigo_empresa)
						{
?>
							<option value="<? print $codigo_empresa; ?>" selected="selected"><? print $nombre_empresa; ?> </option>
<?
						}
					else
						{
?>
							<option value="<? print $codigo_empresa; ?>"><? print $nombre_empresa; ?> </option>
<?
						}
				}
?>
			</select></td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td><input name="enviar" type="hidden" value="1"/></td>
          	<td>&nbsp;</td>
          	<td>&nbsp;</td>
        </tr>
        <tr class="trTblReg1" >
          	<td colspan="3" valign="bottom" align="right">
			<img onclick="VentanaPDF()" src="../plantillas/plantilla_admin/images/boton_pdf.gif" alt="Ver PDF" border="0"  width="28" height="28" />
                <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar&amp;id=<? print $codigo_contrato;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" width="28" height="28" border="0" /></a>
                <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar&amp;id=<? print $codigo_contrato;?>">&nbsp;<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" width="28" height="28" border="0" /></a>&nbsp;
			</td>
        </tr>
      	</table>
    	</form></td>
</tr>
</table>