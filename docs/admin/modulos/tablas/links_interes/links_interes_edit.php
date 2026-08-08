<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<?php
$idLinkInteres=$_GET['id'];
$var=$LinkI->getId($idLinkInteres);
	$lit_Sector=$LinkI->lit_Sector;
	$lit_Detalle=$LinkI->lit_Detalle;
	$lit_Link=$LinkI->lit_Link;
?>
<script language="javascript">
	function validar(doc){
		var detalle, MyLink,error;
		error=0;
		detalle	=trim(doc.Detalle.value);
		MyLink	=trim(doc.Link.value);
		if (detalle==""){
			alert ("Debe Indicar el Detalle");
			doc.Detalle.focus();
			error=1;
		}else{
			if (MyLink==""){
				alert ("Debe Indicar el Link asociado");
				doc.Link.focus();
				error=1;
			}
		}
		if (error==0){
			doc.submit();
			}
	}
//FUNCION APRA LIMPIAR ESPACIOS EN BLANCO		
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}

</script>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1$" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="10%" height="16">Detalle  </td>
          <td height="28">
            <input name="Detalle" type="text" class="textfield" id="Detalle" size="40" maxlength="40"  value="<?php print $lit_Detalle?>">
		  </td>
        </tr>
        <tr>
          <td  height="16">Sector  </td>
          <td height="28">
				<select  id="Sector" name="Sector" >
                	<option value="1" <?php if ($lit_Sector==1) print "selected='selected'"?> >Sector Sindical</option>
                	<option value="2" <?php if ($lit_Sector==2) print "selected='selected'"?> >Ministerio del Trabajo</option>
                	<option value="3" <?php if ($lit_Sector==3) print "selected='selected'"?> >Sector Empresarial</option>
                </select>
		  </td>
        </tr>
        <tr>
          <td height="16">Link  </td>
          <td height="28">
            <input name="Link" type="text" class="textfield" id="Link" size="100" maxlength="100"  value="<?php print $lit_Link?>">
			&nbsp;&nbsp;&nbsp;
			<img src="../../../../plantillas/plantilla_admin/images/boton_buscar.gif" width="21" height="21" alt="Ejemplo:  htttp://www.misitioweb.com/" />
		  </td>
          
        </tr>
        <tr>
          <td height="67"><input type="hidden" value="1" name="enviar"></td>
        </tr>

        <tr>
          <td valign="bottom"><label>
            <input name="Submit" type="button" class="linkAgregar" value="Guardar" onclick="javascript:validar(document.form1);">
            &nbsp;          </label></td>
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
</table>

