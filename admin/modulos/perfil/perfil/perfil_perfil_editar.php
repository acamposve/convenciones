<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  bgcolor="ededed">
  <tr>
    <td width="1%" height="32"  >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td bgcolor="ededed" >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="49%" height="16">Login del Usuario: </td>
        </tr>
        <tr>
          <td height="28"><label>
            <input name="login" type="text" class="textfield" id="login" value="<? print $login; ?>" readonly="" size="25" />
          </label></td>
        </tr>
        <tr>
          <td height="23">Nombre del  Usuario: </td>
        </tr>
        <tr>
          <td height="23"><input name="nombre" type="text" class="textfield" id="nombre" size="50" value="<? print $nombre ?>" /></td>
        </tr>
        <tr>
          <td height="23">Empresa:</td>
        </tr>
        <tr>
          <td height="23"><select name="empresa" class="textfield" id="empresa">
            <option value="0">Seleccione una Empresa</option>
            <?
			$var=$empresas->listarEmpresas2();
	  
			while( list( $key, $val) = each($var) ) {
						$codigo_empresa = $var[$key][codigo_empresa];
						$nombre_empresa = $var[$key][nombre_empresa];
						
				if($empresa==$codigo_empresa){
			?>
            <option value="<? print $codigo_empresa; ?>" selected="selected"><? print $nombre_empresa; ?> </option>
            <?
			}else{
			?>
			<option value="<? print $codigo_empresa; ?>"><? print $nombre_empresa; ?> </option>
			<?
			}
			}
			?>
          </select></td>
        </tr>
        <tr>
          <td height="23">Email:</td>
        </tr>
        <tr>
          <td height="23"><input name="email" type="text" class="textfield" id="email" size="45" value="<? print $email; ?>" /></td>
        </tr>
        <tr>
          <td height="23">Telefono:</td>
        </tr>
        <tr>
          <td height="23"><input name="telefono" type="text" class="textfield" id="telefono" size="45" value="<? print $telefono; ?>" /></td>
        </tr>
        <tr>
          <td height="23">Direccion:</td>
        </tr>
        <tr>
          <td height="23"><textarea name="direccion" cols="50" rows="4" class="textfield" id="direccion"><? print $direccion; ?></textarea></td>
        </tr>
        <tr>
          <td valign="bottom"><label>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar" />
            &nbsp; 
            <input type="hidden" value="1" name="enviar" />
          </label></td>
        </tr>
      </table>
    </form>    </td>
  </tr>
  <tr>
    <td bgcolor="ededed" >&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="ededed" >&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="ededed" ></td>
    <td></td>
	
  </tr>
</table>

