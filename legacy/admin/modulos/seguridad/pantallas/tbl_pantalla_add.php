<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="data" method="post" action="" onSubmit="return validarPais(this)">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="49%" height="23">Nombre de la Pantalla: </td>
        </tr>
        <tr>
          <td height="23"><input name="nombre" type="text" class="textfield" id="nombre" size="50" /></td>
        </tr>
        <tr>
          <td height="23">Url de la pantalla </td>
        </tr>
        <tr>
          <td height="23"><input name="url" type="text" class="textfield" id="url" size="50" /></td>
        </tr>
        <tr>
          <td height="23">Modulo:</td>
        </tr>
        <tr>
          <td height="23"><select name="modulo" class="textfield" id="modulo" >
		  <option value="" selected="selected">SELECCIONE UN MODULO</option>
		  <? 
		  $var=$modulos->listarModulo();
		  		while( list( $key, $val) = each($var) ) {
						$Id_modulo = $var[$key][Id_modulo];
						$Nombre_modulo = $var[$key][Nombre_modulo];
						
						if($Id_Modulo==$Id_modulo) {
		  ?>
		  <option value="<? print $Id_modulo; ?>"><? print $Nombre_modulo; ?></option>
		  <? }else{
		  ?>
		  <option value="<? print $Id_modulo; ?>"><? print $Nombre_modulo; ?></option>
		  <? } 
		  }?>
                                </select></td>
        </tr>
        <tr>
          <td height="23"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td valign="bottom"><label>
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar">
            &nbsp;          </label></td>
        </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td height="10"> </td>
    <td ></td>
	
  </tr>
</table>

