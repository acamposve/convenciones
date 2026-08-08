<?
$var=$ley_trabajo->get_Ley_Trabajo();
$fecha_publicacion=$ley_trabajo->fecha_publicacion;
$pdf_asociado=$ley_trabajo->pdf_asociado;
$descripcion_resumen=$ley_trabajo->descripcion_resumen;
$total_articulos=$ley_trabajo->total_articulos;
?>
<script  type="" language="">
<!--
function VentanaPDF(){
window.open("modulos/leyes/ley_trabajo/media/<? print $pdf_asociado;?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");
}
-->
</script>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
    <div align="center"><span class="verdana_blanco"><? print $msg; 
	$msg="";?></span></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16"><label>Fecha de Publicacion:</label></td>
          <td width="50%">PDF Asociado:</td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="fecha_publicacion" type="text"   readonly="" value="<? print $fecha_publicacion; ?>" /></td>
          <td valign="middle"><input name="pdf_asociado" type="text"  readonly="" value="<? print $pdf_asociado; ?>" />
            </td>
        </tr>
        <tr>
          <td height="16">Descripcion Resumen:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="2" class="texto_simple"><? print $descripcion_resumen;  ?></td>
        </tr>
        <tr>
          <td height="16">Total Art&iacute;culos:</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input name="total_articulos" type="text"  id="Cant_articulos" size="6" readonly="" value="<? print $total_articulos; ?>" /></td>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td colspan="3" valign="bottom" >
          	<div align="right">
  				<img onclick="VentanaPDF()" src="../plantillas/plantilla_admin/images/impresora.gif" width="28" height="28"  border="0"  Alt="Ver PDF" />
                &nbsp;&nbsp;
                
      <?
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['editar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				
			echo '&nbsp;&nbsp;&nbsp;<a href="?url=leyes&amp;tbl=Ley Organica del Trabajo&amp;accion=editar ley" class="linkAgregar">Editar Datos LOT</a>';
			}
			}else{
			echo '&nbsp;&nbsp;&nbsp;<a href="?url=leyes&amp;tbl=Ley Organica del Trabajo&amp;accion=editar ley" class="linkAgregar">Editar Datos LOT</a>';
			}
			?>
		</a>
	</div>
    </td>
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
	
  </tr>
</table>

