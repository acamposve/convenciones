<?
$idLinkInteres=$_GET['id'];
$var=$LinkI->getId($idLinkInteres);
	$lit_Sector=$LinkI->lit_Sector;
	$lit_Detalle=$LinkI->lit_Detalle;
	$lit_Link=$LinkI->lit_Link;
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
	$msg="";?></span></div></td>
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
          <td height="67"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom">
          	<div align="right">
            	Desea Eliminar este Link de Interes:&nbsp;&nbsp;&nbsp;
               	<a href="?url=tablas&amp;tbl=Links de Interes&amp;accion=eliminar&amp;id=<? print $idLinkInteres;?>&amp;flag=SI">
                <img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" /></a>&nbsp;&nbsp;
                <a href="?url=tablas&amp;tbl=Links de Interes&amp;accion=listar"><img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a>
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

