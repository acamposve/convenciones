<?
$codigo_contrato=$_GET['id'];

$var=$contratos->obtenerContratosPorId($codigo_contrato);
$pdf=$contratos->pdf_auto;
$duracion=$contratos->duracion;
$inicio=$contratos->fecha_inicio;
$termino=$contratos->fecha_termino;
$ambito=$contratos->ambito_aplicacion;
$empresa=$contratos->codigo_empresa;

?>
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK> 
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16">PDF Asociado:</td>
          <td width="50%">Duracion:</td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="pdf_asociado" type="file"   value="" /></td>
          <td valign="middle"><input name="duracion" type="text"   id="duracion" value="<? $duracion = strtoupper($duracion); 	print $duracion ?>" /></td>
        </tr>
        <tr>
          <td width="10%">	
			Fecha de Inicio: <br />
  			<input name="inicio" type="text"  id="inicio" size="15"  value="<? print $inicio ?>"/>
				<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.inicio,'dd-mm-yyyy',this)" />
           </td>
           <td>
			Fecha de Termino: <br />
     			<input name="termino" type="text"  id="termino" size="15"  value="<? print  $termino ?>" />
				<img src="../../../../plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.termino,'dd-mm-yyyy',this)" />
           </td>
                    
        </tr>
       
        
        <tr>
          <td>Ambito de Aplicacion:</td>
          <td>Empresa:</td>
        </tr>
        <tr>
          <td><input name="ambito" type="text" id="ambito"  value="<? $ambito = strtoupper($ambito); 
		  print $ambito ?>" /></td>
          <td><select name="empresa" class="textfield" id="empresa" >
            <option value="0">Seleccione una Epresa</option>
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
          <td><input name="enviar" type="hidden" value="1"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
          	
            <input name="Submit" type="submit" class="linkAgregar" value="Guardar"  /></td>
        </tr>
      </table>
    </form></td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
    <td ></td>
  </tr>
</table>
