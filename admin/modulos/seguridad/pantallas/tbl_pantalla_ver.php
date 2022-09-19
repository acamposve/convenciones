<?
$id_pantalla=$_GET['id'];
$var=$pantalla->getId($id_pantalla);
$nombre=$pantalla->nombre_pantalla;
$url = $pantalla->url_pantalla;
$Id_Modulo =$pantalla->id_modulo;
$nombre_modulo=$modulos->getId($Id_Modulo);
$nombre_modulo=$modulos->nombre_modulo;

?>
<style type="text/css">
<!--
.style4 {font-size: 12px}
-->
</style>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%"><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="57%" height="16"><strong>Nombre de la Pantalla: </strong></td>
        </tr>
        <tr>
          <td height="28"><span class="style4"><? print $nombre ;?></span></td>
        </tr>
        <tr>
          <td height="23"><strong>Url de la Pantalla: </strong></td>
        </tr>
        <tr>
          <td height="23"><span class="style4"><? print $url ;?></span></td>
        </tr>
        <tr>
          <td height="23"><strong>Modulo:</strong></td>
        </tr>
        <tr>
          <td height="23"><span class="style4"><? print $nombre_modulo ;?></span></td>
        </tr>
        <tr>
          <td height="23"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td><div align="right"><a href="?url=seguridad&amp;tbl=Pantallas&amp;accion=editar&amp;id=<? print $id_pantalla;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a><a href="?url=seguridad&amp;tbl=Pantallas&amp;accion=eliminar&amp;id=<? print $id_pantalla;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a></div></td>
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
</table>

