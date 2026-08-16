<?
$enviar=$_POST['enviar'];
$id_categoria=$_GET['id'];
$var=$categorias_titulos->getId($id_categoria);
$descripcion_categoria=$categorias_titulos->descripcion_categoria;
$comparacion=$categorias_titulos->requiere_campo_comparacion_economica;
$nombre_categoria=$categorias_titulos->nombre_categoria;
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
    <td ></td>
  </tr>
  <tr>
    <td width="1%" height="32">&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="59%" height="16">Nombre de La Categoria: </td>
        </tr>
        <tr>
          <td height="22" class="Estilo9">&nbsp;
            <input name="categoria" type="text"  id="categoria" value=" <? print $nombre_categoria ;?> " readonly="true" /></td>
        </tr>
        <tr>
          <td height="16">Descripcion de la Categoria : </td>
        </tr>
        <tr>
          <td height="16">
			<table width="90%" cellpadding="0" cellspacing="0" bordercolor="#000000"  style="border-style: inset;border-width:1;" >
              <tr>
                <td bgcolor="#FFFFFF" class=""> <? print  $descripcion_categoria ?></td>
              </tr>
            </table>
            </td>
        </tr>
        <tr>
          <td height="16">Requiere comprobacion economica? </td>
        </tr>
        <tr>
          <td height="16"><? print mb_strtoupper($comparacion) ;?></td>
        </tr>
        <tr>
          <td height="35"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td><div align="right"><a href="?url=tablas&amp;tbl=Bloques%20de%20Clausulas&amp;accion=editar&amp;id=<? print $id_categoria;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a><a href="?url=tablas&amp;tbl=Bloques%20de%20Clausulas&amp;accion=eliminar&amp;id=<? print $id_categoria;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a></div></td>
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
    <td height="10"></td>
    <td ></td>
  </tr>
    <tr >
    <td height="1" ></td>
    <td ></td>
    <td ></td>
  </tr>
</table>

