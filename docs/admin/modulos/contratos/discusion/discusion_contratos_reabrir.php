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
<script  type="" language="">
<!--
function VentanaPDF_Sindicato(){
window.open("modulos/contratos/discusion/documentos/<?php print $codigo_bitacora."/".$pdf_peticiones_sindicato ;?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");

}
-->
</script>
<script  type="" language="">
<!--
function VentanaPDF_Patrono(){
window.open("modulos/contratos/discusion/documentos/<?php print $codigo_bitacora."/".$pdf_ofertas_patrono ;?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");
}
-->
</script>


<script  type="" language="">
<!--
function Aviso_cierre(){
		var chk= document.form1.estatu_pase_contratos.value;
		alert(chk);
	if (chk=="off")
	{
		if(confirm("Enviar a Contratos?"))
		{
			var buttonobject= document.form1.Submit;
			buttonobject.click();
	
		}
	}
}
-->
</script>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />


<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
   		<br/>
		<div align="center">   <font color="#FFFFFF" ><? print $nombre_empresa;?> </div>

    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16">Empresa:</td>
          <td width="50%">Fecha Inicio: </td>
        </tr>
        <tr>
          <td height="16" valign="middle"><select name="empresa" class="textfield" id="empresa" disabled="disabled">
            <option value="0">Seleccione una Empresa</option>
            <?
			$var=$empresas->listarEmpresas2();
	  
			while( list( $key, $val) = each($var) ) {
						$empresa = $var[$key][codigo_empresa];
						$nombre_empresa = $var[$key][nombre_empresa];
						
				if($codigo_empresa==$empresa){
			?>
            <option value="<? print $empresa; ?>" selected="selected"><? print $nombre_empresa; ?> </option>
            <? $cod_empresa=$empresa;
			
			}else{
			?>
            <option value="<? print $empresa; ?>"><? print $nombre_empresa; ?> </option>
            <?
			}
			}
			?>
          </select>
          </td>
          <td valign="middle"><input name="inicio" type="text" class="texto" id="inicio" value="<?php print $fecha_inicio_disc ;?>" readonly="readonly"/></td>
        </tr>
        <tr>
          <td height="16">Representante de la empresa </td>
          <td>Telefono representante </td>
        </tr>
        <tr>
          <td height="20"><input name="representanteEm" type="text" class="texto" id="representanteEm" value="<?php print $representante_empresa ;?>" readonly="readonly"  /></td>
          <td height="20"><input name="telefonoEm" type="text" class="texto" id="telefonoEm" value="<?php print $telefono_representante_empresa ;?>"  readonly="readonly" /></td>
        </tr>
        <tr>
          <td>Email Representante de la empresa </td>
          <td>Cargo Representante de la empresa </td>
        </tr>
        <tr>
          <td><input name="emailEm" type="text" class="texto" id="emailEm" value="<?php print $email_representante_empresa ;?>"  readonly="readonly" /></td>
          <td><input name="cargoEm" type="text" class="texto" id="cargoEm" value="<?php print $cargo_representante_empresa ;?>"  readonly="readonly" /></td>
        </tr>
        <tr>
          <td height="16">Representante del sindicato </td>
          <td>Telefono representante sindicato </td>
        </tr>
        <tr>
          <td height="20"><input name="representanteSn" type="text" class="texto" id="representanteSn" value="<?php print $representante_sindicato ;?>" readonly="readonly" /></td>
          <td height="20"><input name="telefonoSn" type="text" class="texto" id="telefonoSn"  value="<?php print $telefono_representante_sindicato ;?>" readonly="readonly" /></td>
        </tr>
        <tr>
          <td>Email Representante sindicato </td>
          <td>Cargo Representante sindicato </td>
        </tr>
        <tr>
          <td><input name="emailSn" type="text" class="texto" id="emailSn" value="<?php print $email_representante_sindicato ;?>"  readonly="readonly" /></td>
          <td><input name="cargoSn" type="text" class="texto" id="cargoSn" value="<?php print $cargo_representante_sindicato ;?>"  readonly="readonly" /></td>
        </tr>
        <tr>
          <td height="16">Representante del Ministerio del Trabajo </td>
          <td>Telefono representante Ministerio del Trabajo </td>
        </tr>
        <tr>
          <td height="20"><input name="representanteMt" type="text" class="texto" id="representanteMt" value="<?php print $representante_min_trab ;?>" readonly="readonly" /></td>
          <td height="20"><input name="telefonoMt" type="text" class="texto" id="telefonoMt" value="<?php print $telefono_representante_min_trab ;?>"  readonly="readonly" /></td>
        </tr>
        <tr>
          <td>Email Representante Ministerio del Trabajo  </td>
          <td>Cargo Representante Ministerio del Trabajo  </td>
        </tr>
        <tr>
          <td><input name="emailMt" type="text" class="texto" id="emailMt" value="<?php print $email_representante_min_trab ;?>" readonly="readonly"  /></td>
          <td><input name="cargoMt" type="text" class="texto" id="cargoMt" value="<?php print $cargo_representante_min_trab ;?>" readonly="readonly" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Pdf Peticiones Sindicato :<br />
                <img onclick="VentanaPDF_Sindicato()" src="../plantillas/plantilla_admin/images/boton_pdf.gif" alt="Ver PDF" border="0"   />          </td>
          <td>Pdf Oferta del Patrono :<br />
                 <img onclick="VentanaPDF_Patrono()" src="../plantillas/plantilla_admin/images/boton_pdf.gif" alt="Ver PDF" border="0"   />          </td>
        </tr>
        <tr>
          <td >
          <input name="enviar" type="hidden" value="1"/>
          <input name="cod_empresa" type="hidden" value="<? print $cod_empresa ?> "/>
          </td>


        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
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
    <td ></td>
    <td ></td>
  </tr>
</table>
