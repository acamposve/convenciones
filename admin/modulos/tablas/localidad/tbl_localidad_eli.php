<?
$id_localidad=$_GET['id'];
$var=$localidad->getId($id_localidad);
$nombre=$localidad->nombre;
?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="13" height="32" >&nbsp;</td>
    <td width="505" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="49%" height="16">Nombre del Pais: </td>
        </tr>
        <tr>
          <td height="22" class="Estilo9">&nbsp;
            <? print $nombrepais ;?>          </td>
        </tr>
        <tr>
          <td height="16">Nombre del Estado: </td>
        </tr>
        <tr>
          <td height="16">&nbsp;&nbsp;<span class="Estilo9"><? print $nombreestado ;?></span></td>
        </tr>
        <tr>
          <td height="16">Nombre de la Localidad: </td>
        </tr>
        <tr>
          <td height="16"> &nbsp;&nbsp;<span class="Estilo9"><? print $nombrelocalidad ;?></span></td>
        </tr>
        <tr>
          <td height="16">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>            <div align="right">Desea Eliminar esta Localidad:&nbsp;&nbsp;&nbsp;<a href="?url=tablas&amp;tbl=Localidad&amp;accion=eliminar&amp;id=<? print $id_localidad;?>&amp;flag=SI"> <img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;&nbsp;</a><a href="?url=tablas&amp;tbl=Localidad&amp;accion=listar">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
          </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
    <td ></td>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
	
  </tr>
</table>

