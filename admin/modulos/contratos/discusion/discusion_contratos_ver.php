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

		$busqueda_empresa=$discusion->listarDiscusiones(0,500,"",$codigo_empresa);
		while( list( $key, $val) = each($busqueda_empresa) ) {
            $codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
			$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
			if ($codigo_bitacora_original ==$codigo_bitacora )
			{	
				$estatus_bt=$busqueda_empresa[$key][estatus_bitacora];
				
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
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />



<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
		<div align="center">   <? print $nombre_empresa;?></div>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16" valign="middle">Empresa<br />
            <select name="empresa" class="textfield" id="empresa" disabled="disabled">
            <option value="0">Seleccione una Empresa</option>
            <?
			$var=$empresas->listarEmpresas2();
	  
			while( list( $key, $val) = each($var) ) {
						$empresa = $var[$key][codigo_empresa];
						$nombre_empresa = $var[$key][nombre_empresa];
						
				if($codigo_empresa==$empresa){
			?>
            <option value="<? print $empresa; ?>" selected="selected"><? print $nombre_empresa; ?> </option>
            <?
				$empresa_ori=$nombre_empresa;
			}else{
			?>
            <option value="<? print $empresa; ?>"><? print $nombre_empresa; ?> </option>
            <?
			}
			}
			?>
          </select></td>
          <td width="50%" valign="middle">Fecha Inicio:<br />
<input readonly="" name="inicio" type="text"  id="inicio" value="<?php print $fecha_inicio_disc ;?>" /></td>
        </tr>
        <tr>
          <td height="20">Representante de la empresa<br />
<input readonly="" name="representanteEm" type="text"  id="representanteEm" value="<?php print $representante_empresa ;?>"  /></td>
          <td height="20">Telefono representante<br />
<input readonly="" name="telefonoEm" type="text"  id="telefonoEm" value="<?php print $telefono_representante_empresa ;?>"  /></td>
        </tr>
        <tr>
          <td>Email Representante de la empresa<br />
<input readonly="" name="emailEm" type="text"  id="emailEm" value="<?php print $email_representante_empresa ;?>"  /></td>
          <td>Cargo Representante de la empresa<br />
<input readonly="" name="cargoEm" type="text"  id="cargoEm" value="<?php print $cargo_representante_empresa ;?>"  /></td>
        </tr>
        <tr>
          <td height="20">Representante del sindicato<br />
<input readonly="" name="representanteSn" type="text"  id="representanteSn" value="<?php print $representante_sindicato ;?>"  /></td>
          <td height="20">Telefono representante sindicato<br />
<input readonly="" name="telefonoSn" type="text"  id="telefonoSn"  value="<?php print $telefono_representante_sindicato ;?>" /></td>
        </tr>
        <tr>
          <td>Email Representante sindicato<br />
<input readonly="" name="emailSn" type="text"  id="emailSn" value="<?php print $email_representante_sindicato ;?>"  /></td>
          <td>Cargo Representante sindicato<br />
<input readonly="" name="cargoSn" type="text"  id="cargoSn" value="<?php print $cargo_representante_sindicato ;?>"  /></td>
        </tr>
        <tr>
          <td height="20">Representante del Ministerio del Trabajo<br />
  <input readonly="" name="representanteMt" type="text"  id="representanteMt" value="<?php print $representante_min_trab ;?>"  /></td><td height="20">Telefono representante Ministerio del Trabajo<br />
<input readonly="" name="telefonoMt" type="text"  id="telefonoMt" value="<?php print $telefono_representante_min_trab ;?>"  /></td>
        </tr>
        <tr>
          <td>Email Representante Ministerio del Trabajo<br />
<input readonly="" name="emailMt" type="text"  id="emailMt" value="<?php print $email_representante_min_trab ;?>"  /></td>
          <td>Cargo Representante Ministerio del Trabajo<br />
<input readonly="" name="cargoMt" type="text"  id="cargoMt" value="<?php print $cargo_representante_min_trab ;?>"  /></td>
        </tr>
        <tr>
          <td>	
			Pdf Peticiones Sindicato <br />
            <?php 
			
				if ($pdf_peticiones_sindicato=="")
				{
				?>
                	<a href="#"  title="Discución Sin PDF" >
					<? print $empresa_ori ?></a>
                <?php
				}else{
				?>
	                <img onclick="VentanaPDF_Sindicato()" src="../plantillas/plantilla_admin/images/boton_pdf.gif" alt="Ver PDF" border="0"    />	            	     
                <?php
				}
				?>                            </td>
          <td>Pdf Oferta del Patrono <br />
          <?php
				if ($pdf_ofertas_patrono=="")
				{
				
				?>
                	<a href="#"  title="Discución Sin PDF" > 
					<? print $empresa_ori ?></a>
					</a>
                <?php
				}else{
				?>
	                 <img onclick="VentanaPDF_Patrono()" src="../plantillas/plantilla_admin/images/boton_pdf.gif" alt="Ver PDF" border="0"   />
             
                <?php
				}
				?>			</td>
        </tr>

        <tr>
          <td colspan="3" valign="bottom"><label></label><input name="enviar" type="hidden" value="1"/>
            <div align="right"><a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar&amp;id=<? print $codigo_bitacora;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" width="28" height="28" border="0" /></a><a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar&amp;id=<? print $codigo_bitacora;?>">&nbsp;<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" width="28" height="28" border="0" /></a>&nbsp; </div></td>
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
