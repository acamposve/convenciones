<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK> 
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="">
  <tr>
    <td width="1" height="32" >&nbsp;</td>
    <td width="99%" ><div align="center"><span class="verdana_blanco"><? print $msg; 
	$msg="";?></span></div></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16" >PDF Asociado:
          <br/>
            
              <input name="pdf_asociado" type="file"   value="" />            </td>
        </tr>

        <tr>
          <td height="16">Duracion:<br/>
            <input name="duracion" type="text"  id="duracion" /></td>
        </tr>
        
        <tr>
          <td width="10%">	
			Fecha de Inicio: <br />
  			<input name="inicio" type="text"  id="inicio" size="15"  />
				<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.inicio,'dd-mm-yyyy',this)" />
           </td>
           </tr>
          <tr>
           <td>
			Fecha de Termino: <br />
     			<input name="termino" type="text"  id="termino" size="15"  />
				<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.termino,'dd-mm-yyyy',this)" />
           </td>
                    
        </tr>
    <tr>    </tr>
    	<td>Ambito de Aplicacion:
				<br/>
                <input name="ambito" type="text"  id="ambito" /></td>
	<tr>
          <td>
          Empresa:
            <br/>
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
              </p>          </td>
        </tr>
        <tr>
          <td><input name="enviar" type="hidden" value="1"/></td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
                 <input name="Submit" type="submit" class="linkAgregar" value="Guardar"  /></td>
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
    <td ></td>
  </tr>
</table>
