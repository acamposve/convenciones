<?

$codigo_ley = $_GET['id'];
$var=$otras_leyes->get_Ley($codigo_ley);

$codigo_otra_ley=$otras_leyes->codigo_otra_ley;
$nombre_ley	=$otras_leyes->nombre_ley;
$fecha_publicacion_ley=$otras_leyes->fecha_publicacion_ley;
$descripcion_ley=$otras_leyes->descripcion_ley;
$pdf_ley=$otras_leyes->pdf_ley;
$total_articulos_ley=$otras_leyes->total_articulos_ley;
$origen=$otras_leyes->origen;
?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><div align="center"><span class="verdana_blanco"><? print $msg; 
	$msg="";?></span></div></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td height="16" width="50%">Nombre Ley </td>
        
        </tr>
        <tr>
          <td height="16" colspan="2"><input name="nombre_ley" type="text" id="nombre_ley" value="<? print $nombre_ley;?>"  size="75" readonly="readonly"/></td>
        </tr>

        <tr>
          <td width="50%">Origen</td>
        </tr>
        <tr>
          <td  colspan="2"><input  size="75" name="origen" type="text"  id="origen" value="<? print $origen;?>"  readonly="readonly"/>
          </td>

        </tr>
        <tr>
          <td width="50%">Fecha de Publicacion:</td>
          <td height="16">PDF Asociado:</td>
        </tr>
        <tr>
        <td>
        <input name="fecha_publicacion" type="text" value="<? print $fecha_publicacion_ley; ?>" readonly="readonly"/>
        </td>
          <td height="16" colspan="2" valign="middle"><span class="Estilo5">

            <?php 
			
				if ($pdf_ley=="")
				{
				?>
					<a href="#"  title="Ley Sin  PDF"  target="VEN_PDF">	<img src="../plantillas/plantilla_admin/images/impresora.gif"  border="0" alt="Ver PDF"/></a>
                <?php
				}else{
				?>
					<a href="modulos/leyes/otras_varias/documentos/<? echo $codigo_otra_ley?>/<? echo $pdf_ley ?>"  onclick="window.open('', 'VEN_PDF')" title="Ver PDF"  target="VEN_PDF">	<img src="../plantillas/plantilla_admin/images/impresora.gif" border="0" alt="Ver PDF"/></a>
                <?php
				}
				?> 
        </tr>
        <tr>
          <td height="16">Descripcion Resumen:</td>

        </tr>
        <tr>
          <td height="20" colspan="2"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td ><? print $descripcion_ley; ?></td>
              </tr>
            </table></td>
        </tr>
        
        <tr>
          <td height="16">Total Art&iacute;culos:</td>

        </tr>
        <tr>
          <td><input name="total_articulos" type="text"  id="Cant_articulos" size="6" readonly="" value="<? print $total_articulos_ley; ?>" /></td>
        </tr>
        
        <tr>
          <td colspan="3" valign="bottom"><label></label>            <div align="right">Desea Eliminar Esta Ley:&nbsp;&nbsp;&nbsp;<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=eliminar ley&amp;id=<? print $codigo_ley; ?>&amp;ley=<? print $codigo_ley;?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;</a><a href="?url=leyes&tbl=Otras Varias">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
          </tr>
      </table>
        </form>    </td>
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
    <td ></td>
	
  </tr>
</table>

