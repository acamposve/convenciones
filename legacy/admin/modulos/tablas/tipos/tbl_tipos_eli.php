<?
$id_tipo=$_GET['id'];
$var=$tipos_empresas->getId($id_tipo);
$nombre=$tipos_empresas->nombre_tipo;
$descripcion=$tipos_empresas->descripcion_tipo;
?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td height="1"></td>

  </tr>
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%">Nombre del Tipos: </td>
        </tr>
        <tr>
          <td><label>
            <input name="nombre" readonly="" type="text" class="textfield" id="nombre" size="35" value="<? print $nombre; ?>">
          </label></td>
        </tr>
        <tr>
          <td>Descripcion del Tipos : </td>
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
          <td height="44">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>            <div align="right">Desea Eliminar este Sector:&nbsp;&nbsp;&nbsp;<a href="?url=tablas&amp;tbl=Tipos&amp;accion=eliminar&amp;id=<? print $id_tipo;?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;</a><a href="?url=tablas&amp;tbl=Tipos&amp;accion=listar">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
          </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
  </tr>
  <tr #D89B9B>
    <td height="1"></td>
    <td ></td>
    <td ></td>
  </tr>
</table>

