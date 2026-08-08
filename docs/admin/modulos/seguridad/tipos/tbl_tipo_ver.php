<?
$id_tipo=$_GET['id'];
$var=$tipo_usuario->getId($id_tipo);
$nombre=$tipo_usuario->nombre;
?>

<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
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
          <td width="100%" height="16">Nombre del Tipo de Usuario: </td>
        </tr>
        <tr>
          <td height="28" class="Estilo9">&nbsp;
            <? print $nombre ;?>          </td>
        </tr>
        <tr>
          <td height="67"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td><div align="right"><a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=editar&amp;id=<? print $id_tipo;?>">
          <img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a>
          <?php if ($id_tipo!=1)
		  	{
		  	?>
	          <a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=eliminar&amp;id=<? print $id_tipo;?>">
          	<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a>
          <?php 
		  }
		  ?>
          
          </div></td>
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
</table>

