<?
$id_pais=$_GET['id'];
$var=$pais->getId($id_pais);
$nombre=$pais->nombre;
?>

<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  class="Color_tabla">
  <tr>
    <td width="1%" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>

  </tr>
  <tr class=" Color_tabla">
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="59%" height="16">Nombre del Pais: </td>
        </tr>
        <tr>
          <td height="28" class="Estilo9">&nbsp;
            <? print $nombre ;?>          </td>
        </tr>
        <tr>
          <td height="67"><input type="hidden" value="1" name="enviar">

          </td>
        </tr>
        <tr>
          <td><div align="right"><a href="?url=tablas&amp;tbl=Paises&amp;accion=editar&amp;id=<? print $id_pais;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a><a href="?url=tablas&amp;tbl=Paises&amp;accion=eliminar&amp;id=<? print $id_pais;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a></div></td>
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

