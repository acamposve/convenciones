<?
$codigo_bitacora=$_GET['id_bitacora'];		
$codigo_reunion=$_GET['id'];
	$var=$discusion->verReunion($codigo_reunion);	
		while( list( $key, $val) = each($var) ) {
			$codigo_reunion_busqueda = $var[$key][codigo_reunion];

			if ($codigo_reunion_busqueda ==$codigo_reunion )
			{
			$codigo_bitacora = $var[$key][codigo_bitacora];
			$duracion_reunion = $var[$key][codigo_empresa];
            $fecha_reunion = $var[$key][fecha_reunion];
            $hora_reunion = $var[$key][hora_reunion];
			$duracion_reunion = $var[$key][duracion_reunion];
            $resumen_reunion = $var[$key][resumen_reunion];
            $detalles_reunion = $var[$key][detalles_reunion];
            $asistentes_reunion = $var[$key][asistentes_reunion];
			}
		}
		$busqueda_empresa=$discusion->listarDiscusiones(0,500,"");
		while( list( $key, $val) = each($busqueda_empresa) ) {
            $codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
			$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
			if ($codigo_bitacora_original ==$codigo_bitacora )
			{
				$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
			} 
		}


?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
    
    		<br/>
		<div align="center">   <? print $nombre_empresa;?> </div>

    
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td height="16"  width="50%">Fecha Reunion : </td>
            <td>Hora de la Reunion: </td>
          </tr>
        <tr>
          <td height="16" valign="middle">
          	<input name="FechaReunion" type="text"  id="FechaReunion"  value="<? print $fecha_reunion;?>"   readonly="" /></td>
			
			<td>
			<input name="HoraReunion" type="text"   size="50" id="HoraReunion" value="<? print $hora_reunion;?>" readonly=""/> </td> 
        </tr>
        <tr>
         <tr>
          <td height="16" colspan="3">Duracion Reunion</td>
          </tr>
        <tr>
          <td height="20" colspan="2"><label>
            <input name="DuracionReunion" type="text" id="DuracionReunion"  value="<? print $duracion_reunion ?>" readonly=""/>
          </label></td>
        </tr>
        <tr>
          <td height="16" colspan="3">Detalle Reunion</td>
        </tr>
          <td height="16" colspan="3">
          	<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
            	<tr>
                	<td bgcolor="#FFFFFF"><? print  $detalles_reunion ?></td>
              	</tr>
            </table>
			</td>
        </tr>
        <tr>
          <td colspan="3">Resumen de la Reunion:</td>
        </tr>
        <tr>
          <td colspan="3">
			<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
            	<tr>
                	<td bgcolor="#FFFFFF"><? print  $resumen_reunion ?></td>
              	</tr>
            </table>
        </tr>
        <tr>
          <td colspan="3">Asistentes de la Reunion:</td>
          </tr>
        <tr>
          <td colspan="2">
			<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
            	<tr>
                	<td bgcolor="#FFFFFF"><? print  $asistentes_reunion ?></td>
              	</tr>
            </table>
            &nbsp;</td>
        </tr>

        <tr>
          <td colspan="3"><input name="enviar" type="hidden" value="1"/></td>
          </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
            <div align="right">
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_reuniones&amp;id=<? print $codigo_reunion;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="editar" width="28" height="28" border="0" /></a>
            <!--
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_reuniones&amp;id=<? print $codigo_reunion;?>&id_bitacora=<? print $codigo_bitacora?>">
            &nbsp;<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="eliminar" width="18" height="17" border="0" /></a>
            -->
            &nbsp;</div></td>
        </tr>
      </table>
    </form></td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
    <td></td>
  </tr>
</table>
