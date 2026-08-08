<?
include ('../lib/objetos/links_interes.php');
$idLinkInteres=$_GET['id'];
$var=$LinkI->getId($idLinkInteres);
	$lit_Sector=$LinkI->lit_Sector;
	$lit_Detalle=$LinkI->lit_Detalle;
	$lit_Link=$LinkI->lit_Link;
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
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="10%" height="16">Detalle  </td>
          <td height="28">
            <input name="Detalle" type="text" class="textfield" id="Detalle" size="40" maxlength="40" disabled="disabled" readonly="readonly" value="<?php print $lit_Detalle?>">
		  </td>
        </tr>
        <tr>
          <td  height="16">Sector  </td>
          <td height="28">
				<select  id="Sector" name="Sector" disabled="disabled" readonly="readonly">
                	<option value="1" <?php if ($lit_Sector==1) print "selected='selected'"?> >Sector Sindical</option>
                	<option value="2" <?php if ($lit_Sector==2) print "selected='selected'"?> >Ministerio del Trabajo</option>
                	<option value="3" <?php if ($lit_Sector==3) print "selected='selected'"?> >Sector Empresarial</option>
                </select>
		  </td>
        </tr>
        <tr>
          <td height="16">Link  </td>
          <td height="28">
            <input name="Link" type="text" class="textfield" id="Link" size="100" maxlength="100" disabled="disabled" readonly="readonly" value="<?php print $lit_Link?>">
            <!--&nbsp;&nbsp;&nbsp;
			<img src="../../../../plantillas/plantilla_admin/images/boton_buscar.gif" width="21" height="21" alt="Ejemplo:  htttp://www.misitioweb.com/" />-->
		  </td>
          
        </tr>
        <tr>
          <td height="67"><input type="hidden" value="1" name="enviar">

          </td>
        </tr>
        <tr>
          <td colspan="2">
          	<div align="right">
            	<a href="?url=tablas&amp;tbl=Links de Interes&amp;accion=editar&amp;id=<? print $id_pais;?>">
	          	<img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a>
                <a href="?url=tablas&amp;tbl=Links de Interes&amp;accion=eliminar&amp;id=<? print $id_pais;?>">
                <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a>
			</div>
		  </td>
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

