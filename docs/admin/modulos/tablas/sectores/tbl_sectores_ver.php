<?
$id_sector=$_GET['id'];
$var=$sectores->getId($id_sector);
$nombre=$sectores->nombre_sector;
$descripcion=$sectores->descripcion_sector;
?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr bgcolor="#D89B9B">
    <td height="1"></td>
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="59%">Nombre del Sector: </td>
        </tr>
        <tr>
          <td><label>
          
            <input name="nombre" type="text" class="textfield" id="nombre" size="35" value="<? print $nombre; ?>" readonly="" />
          </label></td>
        </tr>
        <tr>
          <td>Descripcion del Sector : </td>
        </tr>
        <tr>
          <td>
			<table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print  $descripcion ?></td>
              </tr>
            </table>
          
         </td>
        </tr>
        <tr>
          <td height="35"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td><div align="right"><a href="?url=tablas&amp;tbl=Sectores&amp;accion=editar&amp;id=<? print $id_sector;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar"/></a><a href="?url=tablas&amp;tbl=Sectores&amp;accion=eliminar&amp;id=<? print $id_sector;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar"/></a></div></td>
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
    <tr>
    <td height="1" ></td>
    <td ></td>
    <td ></td>
  </tr>
</table>

