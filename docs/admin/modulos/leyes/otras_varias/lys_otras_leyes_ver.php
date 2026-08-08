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
          <td colspan="3" valign="bottom"><label></label>            <div align="right"><span class="Estilo5"><a href="modulos/leyes/otras_varias/documentos/<? echo $codigo_otra_ley?>/<? echo $pdf_ley ?>"  onclick="window.open('', 'VEN_PDF')" title="Ver PDF"  target="VEN_PDF"><img src="../plantillas/plantilla_admin/images/impresora.gif" width="28" height="28" border="0"  alt="Ver Doc PDF" /></a>
          <? if($_SESSION["tipo"]!=1)
				{
			$var1=$pantalla->getporUrl($acciones['editar_articulo']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
			?><a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=editar ley&amp;id=<? print $codigo_ley;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar" /></a><a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=eliminar ley&amp;id=<? print $codigo_ley;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar" /></a>
            <?
            	}
            }else{ ?>
            <a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=editar ley&amp;id=<? print $codigo_ley;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar" /></a><a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=eliminar ley&amp;id=<? print $codigo_ley;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar" /></a>
            <? } ?>
            </div></td>
          </tr>
      </table>
        </form>    </td>
    <td >&nbsp;</td>
  </tr>
  <tr> 
    <td height="30" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>

  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>

	
  </tr>
</table>

