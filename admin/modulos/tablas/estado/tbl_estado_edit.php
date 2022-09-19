<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="13" height="32">&nbsp;</td>
    <td width="505" ><? print $msg; 
	$msg="";?></span></div></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td width="40%" height="16">Nombre del Pais: </td>
        </tr>
        <tr>
          <td height="28"><label>
          <select name="id_pais" class="textfield" id="id_pais">
            <option>Seleccione un pais</option>
            <?
			$var=$pais->listarPais2();
	  
			while( list( $key, $val) = each($var) ) {
						$Id_pais = $var[$key][Id_pais];
						$Nombre_pais = $var[$key][Nombre_pais];
						
			if($id_pais==$Id_pais){			
			?>
            <option value="<? print $Id_pais; ?>" selected="selected"><? print $Nombre_pais; ?> </option>
            <?
			}else{
			?>
			<option value="<? print $Id_pais; ?>"><? print $Nombre_pais; ?> </option>
			<?
			
			}
			}
			?>
          </select>
          </label></td>
        </tr>
        <tr>
          <td height="16">Nombre del Estado: </td>
        </tr>
        <tr>
          <td height="16"><input name="nombreestado" type="text" class="textfield" id="nombreestado" size="35" value="<? print  $nombreestado?>" /></td>
        </tr>
        <tr>
          <td height="37"><input type="hidden" value="1" name="enviar"></td>
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

