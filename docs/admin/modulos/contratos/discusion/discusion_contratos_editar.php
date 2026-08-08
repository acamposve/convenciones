<?
$codigo_discusion=$_GET['id'];
$var=$discusion->obtenerDiscusionesPorId($codigo_discusion);	
$codigo_bitacora	= 	$discusion->codigo_bitacora;
$codigo_empresa		 = 	$discusion->codigo_empresa;
$fecha_inicio_disc	= 	$discusion->fecha_inicio_disc;
$fecha_termino		= 	$discusion->fecha_termino;
$representante_empresa		= 	$discusion->representante_empresa;
$telefono_representante_empresa	= 	$discusion->telefono_representante_empresa;
$email_representante_empresa	= 	$discusion->email_representante_empresa;
$cargo_representante_empresa= 	$discusion->cargo_representante_empresa;
$representante_sindicato	= 	$discusion->representante_sindicato;
$telefono_representante_sindicato	= 	$discusion->telefono_representante_sindicato;	
$email_representante_sindicato	= 	$discusion->email_representante_sindicato;
$cargo_representante_sindicato	= 	$discusion->cargo_representante_sindicato;
$representante_min_trab	= 	$discusion->representante_min_trab;
$telefono_representante_min_trab	= 	$discusion->telefono_representante_min_trab;
$email_representante_min_trab	= 	$discusion->email_representante_min_trab;
$cargo_representante_min_trab	= 	$discusion->cargo_representante_min_trab;
$fecha_ultima_reunion	= 	$discusion->fecha_ultima_reunion;
$fecha_proxima_reunion	= 	$discusion->fecha_proxima_reunion;
$pdf_peticiones_sindicato	= 	$discusion->pdf_peticiones_sindicato;
$pdf_ofertas_patrono	= 	$discusion->pdf_ofertas_patrono;
$estatus_bitacora	= 	$discusion->estatus_bitacora;
$estatu_pase_contratos	= 	$discusion->estatu_pase_contratos;
$busqueda_empresa=$discusion->listarDiscusiones(0,500,"", $codigo_empresa);
while( list( $key, $val) = each($busqueda_empresa) ) 
	{
    	$codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
		if ($codigo_bitacora_original ==$codigo_bitacora )
			{
				$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
			} 
	}
?>
<script  type="" language="">
<!--
function VentanaPDF_Sindicato(){
window.open("modulos/contratos/discusion/documentos/<?php print $codigo_bitacora."/".$pdf_peticiones_sindicato ;?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");

}
function VentanaPDF_Patrono(){
window.open("modulos/contratos/discusion/documentos/<?php print $codigo_bitacora."/".$pdf_ofertas_patrono ;?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");
}
-->
</script>
<link rel="stylesheet" href="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen">
</LINK>
<SCRIPT type="text/javascript" src="../../../../lib/componentes/calendario/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
        <fieldset>
        <legend><? print $nombre_empresa?></legend>
        <form name="form1" method="post" action="" enctype="multipart/form-data">
    	<table width="100%" height="100%" border="2" cellpadding="2" cellspacing="2" class="trTblReg1">

		<tr>
        	<th>Empresa:</th>
            <th>Fecha Inicio:</th>
		</tr>
        <tr>
        	<td>
            <select name="empresa" class="textfield" id="empresa">
            <option value="0">Seleccione una Empresa</option>
<?
			$var=$empresas->listarEmpresas2();
			while( list( $key, $val) = each($var) ) 
				{
					$empresa = $var[$key][codigo_empresa];
					$nombre_empresa = $var[$key][nombre_empresa];
					if($codigo_empresa==$empresa)
						{
?>
							<option value="<? print $empresa; ?>" selected="selected"><? print $nombre_empresa ?> </option>
<?
							$empresa_ori=$nombre_empresa;
						}
					else
						{
?>
                			<option value="<? print $empresa; ?>"><? print $nombre_empresa; ?> </option>
<?
						}
				}
?>
			</select></td>
            <td><input name="inicio" type="text"   id="inicio" value="<?php print $fecha_inicio_disc ;?>"  readonly="readonly"/><img src="/plantillas/plantilla_admin/images/calendario2.gif" alt="" width="20" height="15" onClick="displayCalendar(document.form1.inicio,'dd/mm/yyyy',this)" /></td>
		</tr>
        <tr>
        	<th>Representante de la empresa</th>
            <th>Telefono representante</th>
		</tr>
        <tr>
        	<td><input name="representanteEm" type="text"   id="representanteEm" value="<?php print $representante_empresa ;?>"  /></td>
            <td><input name="telefonoEm" type="text"   id="telefonoEm" value="<?php print $telefono_representante_empresa ;?>"  /></td>
		</tr>
        <tr>
        	<th>Email Representante de la empresa</th>
          	<th>Cargo Representante de la empresa</th>
		</tr>
        <tr>
            <td><input name="emailEm" type="text"   id="emailEm" value="<?php print $email_representante_empresa ;?>"  /></td>
            <td><input name="cargoEm" type="text"   id="cargoEm" value="<?php print $cargo_representante_empresa ;?>"  /></td>
		</tr>
        <tr>
            <th>Representante del sindicato</th>
            <th>Telefono representante sindicato</th>
		</tr>
        <tr>
            <td><input name="representanteSn" type="text"   id="representanteSn" value="<?php print $representante_sindicato ;?>"  /></td>
            <td><input name="telefonoSn" type="text"   id="telefonoSn"  value="<?php print $telefono_representante_sindicato ;?>" /></td>
		</tr>
        <tr>
        	<th>Email Representante sindicato</th>
            <th>Cargo Representante sindicato</th>
		</tr>
		<tr>
        	<td><input name="emailSn" type="text"   id="emailSn" value="<?php print $email_representante_sindicato ;?>"  /></td>
            <td><input name="cargoSn" type="text"   id="cargoSn" value="<?php print $cargo_representante_sindicato ;?>"  /></td>
		</tr>
        <tr>
            <th>Representante del Ministerio del Trabajo</th>
            <th>Telefono representante Ministerio del Trabajo</th>
		</tr>
        <tr>
        	<td><input name="representanteMt" type="text"   id="representanteMt" value="<?php print $representante_min_trab ;?>"  /></td>
            <td><input name="telefonoMt" type="text"   id="telefonoMt" value="<?php print $telefono_representante_min_trab ;?>"  /></td>
		</tr>
        <tr>
        	<th>Email Representante Ministerio del Trabajo</th>
            <th>Cargo Representante Ministerio del Trabajo</th>
		</tr>
        <tr>
            <td><input name="emailMt" type="text"   id="emailMt" value="<?php print $email_representante_min_trab ;?>"  /></td>
            <td><input name="cargoMt" type="text"   id="cargoMt" value="<?php print $cargo_representante_min_trab ;?>"  /></td>
		</tr>
        <tr>
            <th>Pdf Peticiones Sindicato</th>
            <th>Pdf Oferta del Patrono</th>
		</tr>
        <tr>
        	<td>
<?php 
			if ($pdf_peticiones_sindicato=="")
				{
?>
              		<a href="#"  title="Discución Sin PDF" > <? print $empresa_ori ?></a>
<?php
				}
			else
				{
?>
					<img onclick="VentanaPDF_Sindicato()" src="../plantillas/plantilla_admin/images/boton_pdf.gif" alt="Ver PDF" border="0"   />
<?php
				}
?>
			</td>
            <td>
<?php
			if ($pdf_ofertas_patrono=="")
				{
?>
					<a href="#"  title="Discución Sin PDF" > <? print $empresa_ori ?></a>
<?php
				}
			else
				{
?>
              		<img onclick="VentanaPDF_Patrono()" src="../plantillas/plantilla_admin/images/boton_pdf.gif" alt="Ver PDF" border="0"  />
<?php
				}
?>
            </td>
		</tr>
        <tr>
        	<td><input name="peticiones" type="file"   id="peticiones" value=""/></td>
            <td><input name="oferta" type="file"   id="oferta"/></td>
		</tr>        
        <tr>
        	<td colspan="2" valign="bottom"><input name="Submit" type="submit" value="Guardar" /></td>
        </tr>
        </table>
		<input name="enviar" type="hidden" value="1"/>
		</form></fieldset>