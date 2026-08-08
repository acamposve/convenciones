<?
$id_pais=$_GET['id'];
$var=$pais->getId($id_pais);
$nombre=$pais->nombre;
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
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="100%">Nombre de La Categoria: </td>
        </tr>
        <tr>
          <td class="Estilo9">&nbsp;
              <input name="categoria" type="text"  id="categoria" value=" <? print $nombre_categoria ;?> " readonly="true" /></td>
        </tr>
        <tr>
          <td>Descripcion de la Categoria : </td>
        </tr>
        <tr>
          <td><span class="Estilo9"> &nbsp;
			<table width="90%" cellpadding="0" cellspacing="0" bordercolor="#000000"  style="border-style: inset;border-width:1;" >
              <tr>
                <td bgcolor="#FFFFFF" class=""> <? print  $descripcion_categoria ?></td>
              </tr>
            </table>
          </span></td>
        </tr>
        <tr>
          <td>Requiere comprobacion economica? </td>
        </tr>
        <tr>
          <td><? print mb_strtoupper($comparacion) ;?></td>
        </tr>
        <tr>
          <td height="44">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>            <div align="right">Desea Eliminar esta Categoria:&nbsp;&nbsp;&nbsp;<a href="?url=tablas&amp;tbl=Bloques%20de%20Clausulas&amp;accion=eliminar&amp;id=<? print $id_pais;?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" /></a>&nbsp;<a href="?url=tablas&amp;tbl=Bloques%20de%20Clausulas&amp;accion=listar">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
          </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10"></td>
    <td ></td>
  </tr>
  <tr >
    <td height="1"></td>
    <td ></td>
    <td ></td>
  </tr>
</table>

