<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen">
</LINK>
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></span>
      </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td rowspan="3" valign="top" >
    
<fieldset>
<legend>Agregar Usuario</legend>    
<form name="data" method="post" action="" onSubmit="return validarPais(this)">
<table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
<tr>
	<th><div align="left">Login del Usuario: </div></th>
    <td height="28"><label>
      <div align="left">
        <input name="login" type="text" class="textfield" id="login" size="25">
      </div>
    </label></td>
    <th> <div align="left">Fecha Expiración Usuario </div></th>
    <td><div align="left">
      <input name="fech_ven" type="text"  id="fech_ven" size="15"  readonly="readonly" />
      <img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.data.fech_ven,'yyyy-mm-dd',this)" /> </div></td>
</tr>
<tr>
	<th><div align="left">Clave:</div></th>
    <td><div align="left">
      <input name="clave" type="password" class="textfield" id="clave" size="25"   />
    </div></td>
    <th><div align="left">Estatus </div></th>
    <td><div align="left">
      <select name="estatus" class="textfield" id="estatus"  />
      
      <option value="HAB"  selected="selected" > Habilitado </option>
      <option value="BLO"> Bloqueado </option>
      </select>
    </div></td>
</tr>
<tr>
	<th><div align="left">Nombre del  Usuario: </div></th>
    <td><div align="left">
      <input name="nombre" type="text" class="textfield" id="nombre" size="50" />
    </div></td>
    <th><div align="left">Tipo de Usuario </div></th>
    <td><div align="left">
      <select name="tipo" class="textfield" id="tipo" >
        <option value="" > SELECCIONE UN TIPO DE USUARIO </option>
        <?php 
	$var=$tipo_usuario->listarTipo2();
	while( list( $key, $val) = each($var) ) 
		{
			$Id_tipo_Usuario = $var[$key][Id_tipo_Usuario];
			$Nombre_tipo = $var[$key][Nombre_tipo];
			if($Id_Tipo==$Id_tipo_Usuario) 
				{
?>
        <option value="<? print $Id_tipo_Usuario?>" selected="selected" > <? print $Nombre_tipo?> </option>
                  <?php
				}
			else
				{
?>
        <option value="<? print $Id_tipo_Usuario?>" > <? print $Nombre_tipo?> </option>
                  <?
		  		}
		}
?>
      </select>
    </div></td>
</tr>
          <tr>
            <th><div align="left">Email:</div></th>
            <td><div align="left">
              <input name="email" type="text" class="textfield" id="email" size="45" />
            </div></td>

            <th><div align="left">Telefono:</div></th>
<td><div align="left">
  <input name="telefono" type="text" class="textfield" id="telefono" size="45" />
</div></td>          </tr>
     
<tr>
	<th><div align="left">Empresa: </div></th>
    <td colspan="3"><div align="left">
      <select name="empresa" class="textfield" id="empresa">
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
      </select>
      </p>            
    </div></td>          </tr>     
          
          
          
          <tr>
            <th><div align="left">Direccion:</div></th>
            <td colspan="3"><div align="left">
              <textarea name="direccion" cols="50" rows="4" class="textfield" id="direccion"></textarea>
            </div></td>
          </tr>

          <tr>
            <td valign="bottom" colspan="4" align="right"><label>
              <input name="Submit" type="submit" class="linkAgregar" value="Guardar">
              &nbsp; </label></td>
          </tr>
        </table>
     <input type="hidden" value="1" name="enviar"> </form></fieldset></td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
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
