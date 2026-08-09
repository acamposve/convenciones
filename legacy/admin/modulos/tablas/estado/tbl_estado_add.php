<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="13" height="32" >&nbsp;</td>
    <td width="505" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="data" method="post" action="" onsubmit="return validarEstado(this)">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="100%" height="16">Nombre del Pais: </td>
        </tr>
        <tr>
          <td height="28"><label>
          <select name="id_pais" class="textfield" id="id_pais">
            <option selected="selected" value="0">Seleccione un pais</option>
			<?
			$var=$pais->listarPais2();
	  
			while( list( $key, $val) = each($var) ) {
						$Id_pais = $var[$key][Id_pais];
						$Nombre_pais = $var[$key][Nombre_pais];
			?>
			<option value="<? print $Id_pais; ?>"><? print $Nombre_pais; ?> </option>
			
			<?
			}
			?>
            </select>
          </label></td>
        </tr>
        <tr>
          <td height="16">Nombre del Estado: </td>
        </tr>
        <tr>
          <td height="16"><label>
            <input name="nombreestado" type="text" class="textfield" id="nombreestado" size="35" />
          </label></td>
        </tr>
        <tr>
          <td height="24"><input type="hidden" value="1" name="enviar"></td>
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
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
	
  </tr>
</table>

