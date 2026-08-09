<?
$id_estado=$_GET['id'];
$var=$estado->getId($id_estado);
$nombreEstado=$estado->nombre;
$id_pais=$estado->id_pais;
$var=$pais->getId($id_pais);
$nombrePais=$pais->nombre;
?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr >
    <td height="1"></td>
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
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%" height="16">Nombre del Pais: </td>
        </tr>
        <tr>
          <td height="22" class="Estilo9">&nbsp;
            <? print $nombrePais ;?>          </td>
        </tr>
        <tr>
          <td height="16">Nombre del Estado: </td>
        </tr>
        <tr>
          <td height="16"><span class="Estilo9"> &nbsp;&nbsp;<? print $nombreEstado ;?></span></td>
        </tr>
        <tr>
          <td height="35"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td><div align="right"><a href="?url=tablas&amp;tbl=Estados&amp;accion=editar&amp;id=<? print $id_estado;?>&amp;id_pais=<? print $id_pais;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a><a href="?url=tablas&amp;tbl=Estados&amp;accion=eliminar&amp;id=<? print $id_estado;?>&amp;id_pais=<? print $id_pais;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a></div></td>
        </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
  </tr>
    <tr >
    <td height="1" ></td>
    <td ></td>
    <td ></td>
  </tr>
</table>

