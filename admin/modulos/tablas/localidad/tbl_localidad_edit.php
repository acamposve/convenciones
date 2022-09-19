<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr >
    <td width="13" height="32" >&nbsp;</td>
    <td width="505" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top"  ><form name="form" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="59%" height="16">Nombre del Pais: </td>
        </tr>
        <tr>
          <td height="26"><label>
          <select name="id_pais" class="textfield" id="id_pais" onchange="Recargar()">
            <option selected="selected">Seleccione un pais</option>
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
          <td height="16"><select name="id_estado" class="textfield" id="id_estado">
            <option>Seleccione un Estado</option>
            <?
			$var=$estado->listarEstado2($id_pais);
	  
			while( list( $key, $val) = each($var) ) {
						$Id_estado = $var[$key][Id_estado];
						$Nombre_estado = $var[$key][Nombre_estado];
						
						if($id_estado==$Id_estado){		
			?>
            <option value="<? print $Id_estado; ?>" selected="selected"><? print $Nombre_estado; ?> </option>
            <?
			}else{
			?>
			<option value="<? print $Id_estado; ?>" ><? print $Nombre_estado; ?> </option>
			<?
			}
			}
			?>
          </select></td>
        </tr>
        <tr>
          <td height="16">Nombre de la Localidad </td>
        </tr>
        <tr>
          <td height="23"><input name="nombrelocalidad" type="text" class="textfield" id="nombrelocalidad" size="35" value="<? print  $nombrelocalidad?>" />
            <input type="hidden" value="1" name="enviar" /></td>
        </tr>
        <tr>
          <td height="2"></td>
          <td></td>
          <td></td>
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

