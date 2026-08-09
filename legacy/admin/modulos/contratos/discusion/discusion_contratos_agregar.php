<?php
session_start();
$t_usuario=$_SESSION["tipo"];
$login=$_SESSION["login"];

		$bt_login=$user->getId($login);
		$empresa   = $user->codigo_empresa ; 
?>


<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK> 
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><div align="center"><span class="verdana_blanco"><? print $msg; 
	$msg="";?></span></div></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">

        <tr>
          <td height="16" valign="middle">
          Empresa<br />
          <select name="empresa" class="textfield" id="empresa">
            <?
			$var=$empresas->listarEmpresas_BT($empresa);
	  
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

          <td valign="middle">
          	Fecha Inicio: <br />
          	<input name="inicio" type="text"  id="inicio"  readonly="readonly"/>
			<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.inicio,'dd/mm/yyyy',this)" />          </td>
        </tr>
          <td height="20">
          	Representante de la empresa  <br />
          	<input name="representanteEm" type="text"  id="representanteEm" /></td>
          <td height="20">
          	Telefono representante<br />
            <input name="telefonoEm" type="text"  id="telefonoEm" /></td>
        </tr>
        <tr>
          <td>
          	Email Representante de la empresa<br />
          	<input name="emailEm" type="text"  id="emailEm" /></td>
          <td>
          	Cargo Representante de la empresa<br />
          	<input name="cargoEm" type="text"  id="cargoEm" /></td>
        </tr>
        <tr>
          <td height="20">
          	Representante del sindicato <br />
          	<input name="representanteSn" type="text"  id="representanteSn" /></td>
          <td height="20">
          	Telefono representante sindicato<br />
          	<input name="telefonoSn" type="text"  id="telefonoSn" /></td>
        </tr>

        <tr>
          <td>Email Representante sindicato <br />
            <input name="emailSn" type="text"  id="emailSn" /></td>
          <td>
          	Cargo Representante sindicato<br />
            <input name="cargoSn" type="text"  id="cargoSn" /></td>
        </tr>
        <tr>
          <td height="20">Representante del Ministerio del Trabajo<br />
          	<input name="representanteMt" type="text"  id="representanteMt" /></td>
          <td height="20">Telefono representante Ministerio del Trabajo<br />
			<input name="telefonoMt" type="text"  id="telefonoMt" /></td>
        </tr>
        <tr>
          <td>Email Representante Ministerio del Trabajo<br />
<input name="emailMt" type="text"  id="emailMt" /></td>
          <td>Cargo Representante Ministerio del Trabajo<br />
<input name="cargoMt" type="text"  id="cargoMt" /></td>
        </tr>
        <tr>
          <td>Pdf Peticiones Sindicato<br />
<input name="peticiones" type="file"  id="peticiones" /></td>
          <td>Pdf Oferta del Patrono<br />
<input name="oferta" type="file"  id="oferta" /></td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
          <input name="enviar" type="hidden" value="1"/>
                <input name="Submit" type="submit" class="linkAgregar" value="Guardar" /></td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td></td>
  </tr>
</table>
