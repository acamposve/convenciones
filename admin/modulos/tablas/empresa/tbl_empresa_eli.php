<?
$id_empresa=$_GET['id'];
$id=$id_empresa;
$var=$empresas->getId($id_empresa);

$codigo_empresa=$empresas->codigo_empresa;
$nombre_empresa=$empresas->nombre_empresa;
$direccion_empresa=$empresas->direccion_empresa;
$id_pais=$empresas->codigo_pais;
$id_estado=$empresas->codigo_estado;
$codigo_localidad=$empresas->codigo_localidad;
$telefonos_empresa=$empresas->telefonos_empresa;
$fax_empresa=$empresas->fax_empresa;
$email_empresa=$empresas->email_empresa;
$url_empresa=$empresas->url_empresa;
$rif_empresa=$empresas->rif_empresa;
$nit_empresa=$empresas->nit_empresa;
$contacto_empresa=$empresas->contacto_empresa;
$cargo_contacto=$empresas->cargo_contacto;
$telefonos_contacto=$empresas->telefonos_contacto;
$email_contacto=$empresas->email_contacto;
$id_sector=$empresas->codigo_sector;
$codigo_tipo=$empresas->codigo_tipo;
$codigo_categoria=$empresas->codigo_categoria;
$codigo_actividad=$empresas->codigo_actividad;
$cantidad_obreros_empresa=$empresas->cantidad_obreros_empresa;
$cantidad_empleados_empresa=$empresas->cantidad_empleados_empresa;
$tamano_empresa=$empresas->tamano_empresa;
$logo=$empresas->logo;

?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16">Nombre Empresa:</td>
          <td width="50%"><label>
            <input name="nombre" type="text" size="55" class="texto_simple" id="nombre" value="<? print  $nombre_empresa   ;?>" readonly="" />
          </label></td>
        </tr>
        <tr>
          <td>Direccion:</td>
          <td><textarea name="direccion" rows="3" cols="50" class="texto_simple" id="direccion" readonly="readonly"><? print $direccion_empresa    ;?></textarea></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26">Pais: </td>
          <td><select name="id_pais" class="textfield" id="id_pais" onchange="Recargar()">
              <option value="0" selected="selected">Seleccione un pais</option>
              <?
			$var=$pais->listarPais2();
	  
			while( list( $key, $val) = each($var) ) {
						$Id_pais = $var[$key][Id_pais];
						$Nombre_pais = $var[$key][Nombre_pais];
						if($id_pais==$Id_pais) {
			?>
              <option value="<? print $Id_pais; ?>" selected="selected"><? print $Nombre_pais; ?> </option>
              <? }else{
			?>
              <option value="<? print $Id_pais; ?>" ><? print $Nombre_pais; ?> </option>
              <?
			}
}
			?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Estado:</td>
          <td><select name="id_estado" class="textfield" id="id_estado" onchange="Recargar()">
              <option value="0" selected="selected">Seleccione un Estado</option>
              <?
			$var=$estado->listarEstado2($id_pais);
	  
			while( list( $key, $val) = each($var) ) {
						$Id_estado = $var[$key][Id_estado];
						$Nombre_estado = $var[$key][Nombre_estado];
						if($id_estado==$Id_estado) {
			?>
              <option value="<? print $Id_estado; ?>" selected="selected"><? print $Nombre_estado; ?> </option>
              <? }else{
			?>
              <option value="<? print $Id_estado; ?>"><? print $Nombre_estado; ?> </option>
              <?
			}
}
			?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26">Localidad:</td>
          <td><select name="id_localidad" class="textfield" id="select" >
              <option value="0">Seleccione una Localidad</option>
              <?
						$var=$localidad->listarLocalidad2($id_estado);
						
							while( list( $key, $val) = each($var) ) {
							$Id_localidad = $var[$key][Id_localidad];
							$Nombre_localidad = $var[$key][Nombre_localidad];
							
							if($Id_localidad==$codigo_localidad){
							?>
              <option value="<? print $Id_localidad; ?>" selected="selected"><? print $Nombre_localidad; ?> </option>
              <?
							}else{
							?>
              <option value="<? print $Id_localidad; ?>"><? print $Nombre_localidad; ?> </option>
              <?
							}
						}
					?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Telefonos:</td>
          <td><input name="telefonos" type="text" class="texto_simple" id="telefonos" readonly=""  value="<? print $telefonos_empresa    ;?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26">Fax:</td>
          <td><input name="fax" type="text" class="texto_simple" id="fax" readonly="" value="<? print $fax_empresa    ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Email:</td>
          <td><input name="email" type="text" class="texto_simple" readonly="" id="email" value="<? print $email_empresa    ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26">Pagina Web </td>
          <td><input name="web" type="text" class="texto_simple" readonly="" id="web" value="<? print $url_empresa    ;?>"   size="30"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>R.I.F</td>
          <td><input name="rif" type="text" class="texto_simple" readonly="" id="rif" value="<? print  $rif_empresa   ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26">N.I.T</td>
          <td><input name="nit" type="text" class="texto_simple" readonly="" id="nit" value="<? print  $nit_empresa   ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Persona de Contacto </td>
          <td><input name="persona_contacto" type="text" readonly="" class="texto_simple" id="persona_contacto" value="<? print $contacto_empresa    ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26">Cargo</td>
          <td><input name="cargo" type="text" readonly="" class="texto_simple" id="cargo" value="<? print  $cargo_contacto   ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Telefonos de Contacto </td>
          <td><input name="telefono_contacto" readonly="" type="text" class="texto_simple" id="telefono_contacto" value="<? print $telefonos_contacto    ;?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26"><label>Email Contacto </label></td>
          <td><input name="email_contacto" readonly="" type="text" class="texto_simple" id="email_contacto" size="30" value="<? print $email_contacto    ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="26">Pagina Web </td>
          <td><input name="web" type="text" class="texto_simple"  id="web" value="<? print $url_empresa    ;?>"  size="30"/></td>
          </tr>
        <tr>
          <td>Sector de empresa </td>
          <td><select name="sector" class="textfield" id="select2"  >
              <option value="0" selected="selected">Seleccione Sector</option>
              <?
						$var=$sectores->listarSectores2();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_Sector = $var[$key][codigo_sector];
							$nombre_Sector = $var[$key][nombre_sector];
							
							if($id_sector==$codigo_Sector){
							$msg=conio1;
							?>
              <option value="<? print $codigo_Sector; ?>" selected="selected"> <? print $nombre_Sector; ?> </option>
              <? }else{
			$msg=conio1
						
						?>
              <option value="<? print $codigo_Sector; ?>" > <? print $nombre_Sector; ?> </option>
              <?
						}
						}
					?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16">Tipo de empresa </td>
          <td><select name="tipos" class="textfield" id="select3"  >
              <option value="0" selected="selected">Seleccione Tipo de Empresa</option>
              <?
						$var=$tipos_empresas->listarTipos2();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_Tipo = $var[$key][codigo_tipo];
							$nombre_Tipo = $var[$key][nombre_tipo];
							if($codigo_tipo==$codigo_Tipo){
							?>
              <option value="<? print $codigo_Tipo; ?>" selected="selected"> <? print $nombre_Tipo; ?> </option>
              <? }else{
			?>
              <option value="<? print $codigo_Tipo; ?>"> <? print $nombre_Tipo; ?> </option>
              <?
						}
						
						}
					?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Categoria</td>
          <td><select name="categoria" class="textfield" id="select4"  >
              <option value="0" selected="selected">Seleccione Categorias del Sector</option>
              <?
						$var=$categoria_sector->listarCategoria2();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_Categoria = $var[$key][codigo_categoria];
							$nombre_Categoria = $var[$key][nombre_categoria];
							
							if($codigo_Categoria==$codigo_categoria){
							?>
              <option value="<? print $codigo_Categoria; ?>" selected="selected"> <? print $nombre_Categoria; ?> </option>
              <?  }else{
			
			?>
              <option value="<? print $codigo_Categoria; ?>"> <? print $nombre_Categoria; ?> </option>
              <?
			}
						}
					?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16"><label>Actividad</label></td>
          <td><select name="actividad" class="textfield" id="select5"  >
              <option value="0" selected="selected">Seleccione Actividad</option>
              <?
						$var=$actividades->listarActividades2();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_Actividad = $var[$key][codigo_actividad];
							$nombre_Actividad = $var[$key][nombre_actividad];
							
							if($codigo_Actividad==$codigo_actividad){
							?>
              <option value="<? print $codigo_Actividad; ?>" selected="selected"> <? print $nombre_Actividad; ?> </option>
              <? }else{
			?>
              <option value="<? print $codigo_Actividad; ?>"> <? print $nombre_Actividad; ?> </option>
              <?
						}
						}
					?>
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Cantidad de Obreros</td>
          <td><input name="obreros" type="text" class="texto_simple" id="obreros" readonly="" size="5" value="<? print $cantidad_obreros_empresa    ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16">Cantidad de Empleados</td>
          <td><input name="empleados" type="text" class="texto_simple" id="empleados" readonly="" size="5" value="<? print $cantidad_empleados_empresa   ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Tama&ntilde;o Empresa </td>
          <td><input name="tamanio_empresa" type="text" class="texto_simple" readonly="" id="tamanio_empresa" value="<? print $tamano_empresa    ;?>" /></td>
          <td>&nbsp;</td>
        </tr>
          <tr>
          <td height="23" colspan="2">
            	<?php if ($logo=="") { 	
					print "Empresa Sin logo";
					}else{
				?>
				<a href="#"  na onclick="window.open('../admin/modulos/tablas/empresa/documentos/<?php print $id."/".$logo ?>', 'IMG1')" title="Imagen 1" >
				<img src="../admin/modulos/tablas/empresa/documentos/<?php print $id."/".$logo ?>"  border="0" alt="Imagen 1"/>
                </a>
				<?	
					}
				?>
			</td>
          </tr>  
        <tr>
          <td colspan="3" valign="bottom"><label></label>            <div align="right">Desea Eliminar esta Empresa:&nbsp;&nbsp;&nbsp;<a href="?url=tablas&amp;tbl=Empresas&amp;accion=eliminar&amp;id=<? print $id_empresa;?>&amp;flag=SI"> <img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;&nbsp;</a><a href="?url=tablas&amp;tbl=Empresas&amp;accion=listar">&nbsp;&nbsp;<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
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

