<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="ededed">
  <tr>
    <td width="1%" height="32" bgcolor="ededed" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td bgcolor="ededed" >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form" method="post" action="" id="form">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" ">
        <tr>
          <td width="49%" height="16">Login del Usuario: </td>
        </tr>
        <tr>
          <td height="28"><label>
            <input name="login" type="text" class="textfield" id="login" value="<? print $login; ?>" readonly="" size="25" />
          </label></td>
        </tr>
        <tr>
          <td height="23">Nuevo Password: </td>
        </tr>
        <tr>
          <td height="23"><input name="pass1" type="password" class="textfield" size="50" /></td>
        </tr>
        <tr>
          <td height="23">Repita Nuevo Password: </td>
        </tr>
        <tr>
          <td height="23"><input name="repass" type="password" class="textfield" size="50" value="" /></td>
        </tr>
        <tr>
          <td valign="bottom"><label>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar" />
            &nbsp; 
            <input type="hidden" value="1" name="enviar" />
          </label></td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td bgcolor="ededed">&nbsp;</td>
  </tr>
  <tr>
    <td  bgcolor="ededed">&nbsp;</td>
  </tr>
  <tr>
    <td  bgcolor="ededed" ></td>
    <td ></td>
  </tr>
</table>
