<?
$id_pantalla=$_GET['id'];
$var=$pantalla->getId($id_pantalla);
$nombre=$pantalla->nombre_pantalla;
$url = $pantalla->url_pantalla;
$Id_Modulo =$pantalla->id_modulo;
$nombre_modulo=$modulos->getId($Id_Modulo);
$nombre_modulo=$modulos->nombre_modulo;

?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
.style1 {font-size: 12}
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
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td height="16"><strong>Nombre de la Pantalla: </strong></td>
        </tr>
        <tr>
          <td height="28"><span class="style4 Estilo9 style1"><? print $nombre ;?></span></td>
        </tr>
        <tr>
          <td height="23"><strong>Url de la Pantalla: </strong></td>
        </tr>
        <tr>
          <td height="23"><span class="style4 Estilo9 style1"><? print $url ;?></span></td>
        </tr>
        <tr>
          <td height="23"><strong>Modulo:</strong></td>
        </tr>
        <tr>
          <td height="23"><span class="style4 Estilo9 style1"><? print $nombre_modulo ;?></span></td>
        </tr>
        
        <tr>
          <td width="52%" height="67"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>            <div align="right">Desea Eliminar este Tipo de Usuario:&nbsp;&nbsp;&nbsp;<a href="?url=seguridad&amp;tbl=Pantallas&amp;accion=eliminar&amp;id=<? print $id_pantalla;?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;&nbsp;</a><a href="?url=seguridad&amp;tbl=Pantallas&amp;accion=listar"><img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
          </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="109">&nbsp;</td>
  </tr>
  <tr>
    <td height="10"></td>
    <td ></td>
	
  </tr>
</table>

