<?php
function verPanelBlanquear(){
	$objResponse = new xajaxResponse();
	$tabla.=' ';
	$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");
    return $objResponse;
}

function verDetalleReunion($codigo_reunion = 0 ){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();
	
	$var= $discusion->verReunion($codigo_reunion);
	
	while( list( $key, $val) = each($var) ) {
  	$codigo_reunion = $var[$key][codigo_reunion];
   	$fecha_reunion = $var[$key][fecha_reunion];
	$hora_reunion = $var[$key][hora_reunion];
	$duracion_reunion = $var[$key][duracion_reunion];
	$resumen_reunion = $var[$key][resumen_reunion];
	$detalles_reunion = $var[$key][detalles_reunion];
	$asistentes_reunion = $var[$key][asistentes_reunion];
	
	$tabla='<form id="registro" name="registro" method="post" action="">
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="2" bordercolor="#FFFFFF">
        <tr>
          <td colspan="2" class="tituloCabecera"><div align="left"><strong>DETALLE REUNION </strong></div></td>
          <td class="texto"><div align="right"><a href="#" onClick="xajax_verPanelBlanquear();transparencia(0);posicionamiento(1)" >[X]</a></div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"></td>
        </tr>
        <tr>
          <td width="22%" class="texto"><div align="left">Fecha Reunion:</div></td>
          <td width="78%" colspan="2" valign="middle">
              <div align="left">
                '.$fecha_reunion.'
              </div>
            </td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Hora Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$hora_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Duracion Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$duracion_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Resumen Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$resumen_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Detalle Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$detalles_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Asistentes Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$asistentes_reunion.' </div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"><label>
              <div align="right"><a href="#" onClick="xajax_verEditarReunion('.$codigo_reunion.')">[Editar]</a><a href="#" onClick="xajax_verEliminarReunion('.$codigo_reunion.')">[Eliminar]</a></div>
              </label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>';
}

$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");

 return $objResponse;
}




function verDetalleAcuerdo($codigo_acuerdo = 0 ){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();
	
	$var= $discusion->verAcuerdo($codigo_acuerdo);
	
	while( list( $key, $val) = each($var) ) {
  	$codigo_reunion = $var[$key][codigo_reunion];
   	$fecha_reunion = $var[$key][fecha_reunion];
	$hora_reunion = $var[$key][hora_reunion];
	$duracion_reunion = $var[$key][duracion_reunion];
	$resumen_reunion = $var[$key][resumen_reunion];
	$detalles_reunion = $var[$key][detalles_reunion];
	$asistentes_reunion = $var[$key][asistentes_reunion];
	
	$tabla='<form id="registro" name="registro" method="post" action="">
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="2" bordercolor="#FFFFFF">
        <tr>
          <td colspan="2" class="tituloCabecera"><div align="left"><strong>DETALLE REUNION </strong></div></td>
          <td class="texto"><div align="right"><a href="#" onClick="xajax_verPanelBlanquear();transparencia(0);posicionamiento(1)" >[X]</a></div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"></td>
        </tr>
        <tr>
          <td width="22%" class="texto"><div align="left">Fecha Reunion:</div></td>
          <td width="78%" colspan="2" valign="middle">
              <div align="left">
                '.$fecha_reunion.'
              </div>
            </td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Hora Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$hora_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Duracion Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$duracion_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Resumen Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$resumen_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Detalle Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$detalles_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Asistentes Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$asistentes_reunion.' </div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"><label>
              <div align="right"><a href="#" onClick="xajax_verEditarReunion('.$codigo_reunion.')">[Editar]</a><a href="#" onClick="xajax_verEliminarReunion('.$codigo_reunion.')">[Eliminar]</a></div>
              </label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>';
}

$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");

 return $objResponse;
}

function verEliminarReunion($codigo_reunion = 0 ){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();
	
	$var= $discusion->verReunion($codigo_reunion);
	
	while( list( $key, $val) = each($var) ) {
  	$codigo_reunion = $var[$key][codigo_reunion];
   	$fecha_reunion = $var[$key][fecha_reunion];
	$hora_reunion = $var[$key][hora_reunion];
	$duracion_reunion = $var[$key][duracion_reunion];
	$resumen_reunion = $var[$key][resumen_reunion];
	$detalles_reunion = $var[$key][detalles_reunion];
	$asistentes_reunion = $var[$key][asistentes_reunion];
	
	$tabla='<form id="registro" name="registro" method="post" action="">
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="2" bordercolor="#FFFFFF">
        <tr>
          <td colspan="2" class="tituloCabecera"><div align="left"><strong>ELIMINAR REUNION </strong></div></td>
          <td class="texto"><div align="right"><a href="#" onClick="xajax_verPanelBlanquear();transparencia(0);posicionamiento(1)" >[X]</a></div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"></td>
        </tr>
        <tr>
          <td width="22%" class="texto"><div align="left">Fecha Reunion:</div></td>
          <td width="78%" colspan="2" valign="middle">
              <div align="left">
                '.$fecha_reunion.'
              </div>
            </td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Hora Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$hora_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Duracion Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$duracion_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Resumen Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$resumen_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Detalle Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$detalles_reunion.' </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Asistentes Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">'.$asistentes_reunion.' </div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"><label>
              <div align="center">REALMENTE DESEA ELIMINAR ESTA REUNION ? </div>
              <div align="center"><a href="#" onClick="xajax_eliminarReunion('.$codigo_reunion.')">[SI]</a><a href="#" onClick="xajax_verPanelBlanquear();transparencia(0);posicionamiento(1">[NO]</a></div>
              </label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>0';
}

$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");

 return $objResponse;
}



function agregarReunion($Formulario, $id_bitacora = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();
	$Fecha=utf8_decode($Formulario['fecha']);
	$Hora=utf8_decode($Formulario['hora']);
	$Duracion=utf8_decode($Formulario['duracion']);
	$Resumen=utf8_decode($Formulario['resumen']);
	$Detalle=utf8_decode($Formulario['detalle']);
	$Asistentes=utf8_decode($Formulario['asistentes']);
	$fecha_def = FechaConvertirFormato($Fecha);
			if (empty($Fecha)){
				$objResponse->addAlert("El Campo Fecha no puede estar Vacio");
				$error=true;
			}
			if (empty($Hora)){
				$objResponse->addAlert("El Campo Hora no puede estar Vacio");
				$error=true;
			}
			if (empty($Duracion)){
				$objResponse->addAlert("El Campo Duracion no puede estar Vacio");
				$error=true;
			}
			if (empty($Resumen)){
				$objResponse->addAlert("El Campo Resumen no puede estar Vacio");
				$error=true;
			}
			if (empty($Detalle)){
				$objResponse->addAlert("El Campo Detalle no puede estar Vacio");
				$error=true;
			}
			if (empty($Asistentes)){
				$objResponse->addAlert("El Campo Asistentes no puede estar Vacio");
				$error=true;
			}
			
			if ($error){
			$objResponse->addAlert("NO SE HA GUARDADO NINGUN REGISTRO");
			}else{
			$discusion -> agregarReunion($fecha_def,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes, $id_bitacora);
			$objResponse->addAlert("REGISTRO AGREGADO EXITOSAMENTE");
			$objResponse->addScriptCall("posicionamiento(1)");
			$objResponse->addScriptCall("transparencia(0)");
			$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");
			}
			
	
    return $objResponse;
}


function agregarAcuerdo($Formulario, $id_bitacora = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();
	$peticion=utf8_decode($Formulario['peticion']);
	$fechaAcuerdo=utf8_decode($Formulario['fechaAcuerdo']);
	$NroArticulo=utf8_decode($Formulario['NroArticulo']);
	$resumenArticulo=utf8_decode($Formulario['resumenArticulo']);
	$textoCompleto=utf8_decode($Formulario['textoCompleto']);
	$titulo=utf8_decode($Formulario['titulo']);
	$campoComparativo=utf8_decode($Formulario['campoComparativo']);
	$estatusAcuerdo=utf8_decode($Formulario['estatusAcuerdo']);
	
	
	$fecha_def = FechaConvertirFormato($fechaAcuerdo);
	$fecha_def = FechaConvertirFormato($fechaAcuerdo);
			if (empty($peticion)){
				$objResponse->addAlert("El Campo Peticion no puede estar Vacio");
				$error=true;
			}
			if (empty($fechaAcuerdo)){
				$objResponse->addAlert("El Campo Fecha no puede estar Vacio");
				$error=true;
			}
			if (empty($NroArticulo)){
				$objResponse->addAlert("El Nro del articulo no puede estar Vacio");
				$error=true;
			}
			if (empty($textoCompleto)){
				$objResponse->addAlert("El Texto del acuerdo no puede estar vacio no puede estar Vacio");
				$error=true;
			}
			if (empty($titulo)){
				$objResponse->addAlert("El Campo Titulo no puede estar Vacio");
				$error=true;
			}

			if (empty($estatusAcuerdo)){
				$objResponse->addAlert("Debe seleccionar un estatus para el acuerdo");
				$error=true;
			}
			
			if ($error){
			$objResponse->addAlert("NO SE HA GUARDADO NINGUN REGISTRO");
			}else{
			$discusion -> agregarAcuerdo($peticion,$fecha_def,$NroArticulo,$resumenArticulo,$textoCompleto,$titulo,$campoComparativo,$estatusAcuerdo, $id_bitacora);
			$objResponse->addAlert("REGISTRO AGREGADO EXITOSAMENTE");
			$objResponse->addScriptCall("posicionamiento(1)");
			$objResponse->addScriptCall("transparencia(0)");
			$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");
			}
			
	
    return $objResponse;
}

function eliminarReunion($codigo_reunion = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();

			$discusion -> eliminarReunion($codigo_reunion);
			$objResponse->addAlert("REGISTRO ELIMINADO EXITOSAMENTE");
			$objResponse->addScriptCall("posicionamiento(1)");
			$objResponse->addScriptCall("transparencia(0)");
			$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");

    return $objResponse;
}

function actualizarReunion($Formulario){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();
	$Fecha=utf8_decode($Formulario['fecha']);
	$codigo_reunion=utf8_decode($Formulario['codigo_reunion']);
	$Hora=utf8_decode($Formulario['hora']);
	$Duracion=utf8_decode($Formulario['duracion']);
	$Resumen=utf8_decode($Formulario['resumen']);
	$Detalle=utf8_decode($Formulario['detalle']);
	$Asistentes=utf8_decode($Formulario['asistentes']);
	$fecha_def = FechaConvertirFormato($Fecha);
			if (empty($Fecha)){
				$objResponse->addAlert("El Campo Fecha no puede estar Vacio");
				$error=true;
			}
			if (empty($Hora)){
				$objResponse->addAlert("El Campo Hora no puede estar Vacio");
				$error=true;
			}
			if (empty($Duracion)){
				$objResponse->addAlert("El Campo Duracion no puede estar Vacio");
				$error=true;
			}
			if (empty($Resumen)){
				$objResponse->addAlert("El Campo Resumen no puede estar Vacio");
				$error=true;
			}
			if (empty($Detalle)){
				$objResponse->addAlert("El Campo Detalle no puede estar Vacio");
				$error=true;
			}
			if (empty($Asistentes)){
				$objResponse->addAlert("El Campo Asistentes no puede estar Vacio");
				$error=true;
			}
			
			if ($error){
			$objResponse->addAlert("NO SE HA GUARDADO NINGUN REGISTRO");
			}else{
			$discusion -> actualizarReuniones($fecha_def,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes, $codigo_reunion);
			$objResponse->addAlert("REGISTRO ACTUALIZADO EXITOSAMENTE");
			$objResponse->addScriptCall("posicionamiento(1)");
			$objResponse->addScriptCall("transparencia(0)");
			$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");
			}
			
	
    return $objResponse;
}

function verPanelAgregarReunion($id_bitacora=0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/contratos.php');
	include('lib/objetos/titulos.php');
	include('lib/objetos/categoria_titulos.php');
	include('lib/objetos/sectores.php');
	include('lib/objetos/actividad_empresa.php');
	include('lib/objetos/tipos_empresas.php');
	include('lib/objetos/categoria_titulos.php');


	$objResponse = new xajaxResponse();
				
	$tabla.='<form id="registro" name="registro" method="post" action="">
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="2" bordercolor="#FFFFFF">
        <tr>
          <td colspan="2" class="tituloCabecera"><div align="left"><strong>AGREGAR REUNION </strong></div></td>
          <td class="texto"><div align="right"><a href="#" onclick="xajax_verPanelBlanquear();transparencia(0);posicionamiento(1)" >[X]</a></div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"></td>
        </tr>
        <tr>
          <td width="22%" class="texto"><div align="left">Fecha Reunion:</div></td>
          <td width="78%" colspan="2" valign="middle">
              <div align="left"><input name="fecha" type="text" id="fecha" size="15" readonly /><img src="../../../../plantillas/plantilla_admin/images/calendar_icon.png" width="24" onclick="displayCalendar(document.forms[0].fecha,\'dd-mm-yyyy\',this)" >
              </div>
            </td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Hora Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <input name="hora" type="text" id="hora" size="10" />
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Duracion Reunion: </div></td>
          <td colspan="2" valign="middle"><div align="left">
              <input name="duracion" type="text" id="duracion" size="15" />
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Resumen Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <textarea name="resumen" cols="35" rows="4" id="resumen"></textarea>
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Detalle Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <textarea name="detalle" cols="35" rows="4" id="detalle"></textarea>
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Asistentes Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <textarea name="asistentes" cols="35" rows="4" id="asistentes"></textarea>
          </div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"><label>
              <div align="right">
                <input name="Submit" type="button" class="Estilo4" value="GUARDAR" onclick="xajax_agregarReunion(xajax.getFormValues(document.registro),\''.$id_bitacora.'\')" />
              </div>
            </label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>';
	$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");
    return $objResponse;
}
function verPanelAgregarAcuerdo($id_bitacora=0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/contratos.php');
	include('lib/objetos/titulos.php');
	include('lib/objetos/categoria_titulos.php');
	include('lib/objetos/sectores.php');
	include('lib/objetos/actividad_empresa.php');
	include('lib/objetos/tipos_empresas.php');
	include('lib/objetos/categoria_titulos.php');
	include('lib/objetos/discusion.php');
	$objResponse = new xajaxResponse();
				
	$tabla.='<form id="registro" name="registro" method="post" action="">
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="4" bordercolor="#FFFFFF">
        <tr>
          <td colspan="2" class="tituloCabecera"><div align="left"><strong>AGREGAR ACUERDO </strong></div></td>
          <td class="texto"><div align="right"><a href="#" onClick="xajax_verPanelBlanquear();transparencia(0);posicionamiento(1)" >[X]</a></div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"></td>
        </tr>
        <tr>
          <td width="22%" class="texto"><div align="left">Peticion:</div></td>
          <td width="78%" colspan="2" valign="middle">
              <div align="left">
                <select name="peticion" class="textfield" id="peticion"  >
                  <option value="0" selected="selected">Seleccione Peticion</option>';
			 
						
						$var=$discusion->listarPeticionesSelect($id_bitacora);
						
							while( list( $key, $val) = each($var) ) {
							$codigo_peticion = $var[$key][codigo_peticion];
							$nro_peticion = $var[$key][nro_peticion];
							$titulo_peticion = str_split($var[$key][titulo_peticion],50);

			
                  $tabla.= '<option value="'.$codigo_peticion.'"> '.$titulo_peticion[0].'</option>';
                  			}

                 $tabla.= '</select>
              </div>            </td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Fecha Acuerdo: </div></td>
          <td colspan="2" valign="middle"><div align="left">
              <input name="fechaAcuerdo" readonly type="text" id="fechaAcuerdo" size="15" /><img src="../../../../plantillas/plantilla_admin/images/calendar_icon.png" width="24" onclick="displayCalendar(document.forms[0].fechaAcuerdo,\'dd-mm-yyyy\',this)" >
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Nro Articulo: </div></td>
          <td colspan="2" valign="middle"><div align="left">
              <input name="NroArticulo" type="text" id="NroArticulo" size="15" />
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Resumen Articulo:</div></td>
          <td colspan="2" valign="middle"><div align="left">
            <textarea name="resumenArticulo" cols="35" rows="4" id="resumenArticulo"></textarea>
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Texto Completo Articulo</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <textarea name="textoCompleto" cols="35" rows="4" id="textoCompleto"></textarea>
          </div></td>
        </tr>
        <tr>
          <td class="texto">Titulo Comparativo </td>
          <td colspan="2" valign="middle"><label>
            <select name="titulo" class="textfield" id="titulo">
              <option value="0" selected="selected">Seleccione Titulo Comparativo</option>';
						$var=$titulos->listarTitulos();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = utf8_encode( $var[$key][nombre_titulo]);
							if($codigo_titulo==$codigo_titulo_comparativo){
		
             $tabla.= '<option value="'.$codigo_titulo_comparativo.'" selected="selected"> '.$nombre_titulo.' </option>';
              }else{

             $tabla.= '<option value="'.$codigo_titulo_comparativo.'"> '.$nombre_titulo.' </option>';
              
						}
						
						}
					$tabla.= '</select>
          </label></td>
        </tr>
        <tr>
          <td class="texto">Campo Comparativo </td>
          <td colspan="2" valign="middle"><input name="campoComparativo" type="text" id="campoComparativo" size="15" /></td>
        </tr>
        <tr>
          <td class="texto">Estatus del Acuerdo </td>
          <td colspan="2" valign="middle"><select name="estatusAcuerdo" id="estatusAcuerdo">
            <option selected>-Seleccione Estatus-</option' .
            		'><option value="Pendiente">Pendiente</option>
            <option value="Aprobado">Aprobado</option>
            
                    </select></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"><label>
              <div align="right">
                <input name="Submit" type="button" class="Estilo4" value="GUARDAR" onClick="xajax_agregarAcuerdo(xajax.getFormValues(document.registro),\''.$id_bitacora.'\')" />
              </div>
            </label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>';
	$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");
    return $objResponse;
}

function verEditarReunion($codigo_reunion = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/discusion.php');
	include('lib/funciones/php/MenejoFechas.php');
	$objResponse = new xajaxResponse();
	
	$var= $discusion->verReunion($codigo_reunion);
	
	while( list( $key, $val) = each($var) ) {
		$codigo_reunion = $var[$key][codigo_reunion];
		$fecha_reunion = $var[$key][fecha_reunion];
		$hora_reunion = $var[$key][hora_reunion];
		$duracion_reunion = $var[$key][duracion_reunion];
		$resumen_reunion = $var[$key][resumen_reunion];
		$detalles_reunion = $var[$key][detalles_reunion];
		$asistentes_reunion = $var[$key][asistentes_reunion];

	$tabla.='<form id="registro" name="registro" method="post" action="">
  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr>
      <td><table width="100%" border="0" cellpadding="0" cellspacing="2" bordercolor="#FFFFFF">
        <tr>
          <td colspan="2" class="tituloCabecera"><div align="left"><strong>EDITAR REUNION</strong></div></td>
          <td class="texto"><div align="right"><a href="#" onclick="xajax_verPanelBlanquear();transparencia(0);posicionamiento(1)" >[X]</a></div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"></td>
        </tr>
        <tr>
          <td width="22%" class="texto"><div align="left">Fecha Reunion:</div></td>
          <td width="78%" colspan="2" valign="middle">
              <div align="left">
                <input name="fecha" type="text" id="fecha" size="15" value="'.FechaConvertirFormato2($fecha_reunion).'"/>
				<input name="codigo_reunion" type="hidden" id="codigo_reunion" size="15" value="'.$codigo_reunion.'"/>
              </div>
            </td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Hora Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <input name="hora" type="text" id="hora" size="10"  value="'.$hora_reunion.'"/>
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Duracion Reunion: </div></td>
          <td colspan="2" valign="middle"><div align="left">
              <input name="duracion" type="text" id="duracion" size="15" value="'.$duracion_reunion.'"/>
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Resumen Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <textarea name="resumen" cols="35" rows="4" id="resumen">'.$resumen_reunion.'</textarea>
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Detalle Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <textarea name="detalle" cols="35" rows="4" id="detalle">'.$detalles_reunion.'</textarea>
          </div></td>
        </tr>
        <tr>
          <td class="texto"><div align="left">Asistentes Reunion:</div></td>
          <td colspan="2" valign="middle"><div align="left">
              <textarea name="asistentes" cols="35" rows="4" id="asistentes">'.$asistentes_reunion.'</textarea>
          </div></td>
        </tr>
        <tr>
          <td colspan="3" class="texto"><label>
              <div align="right">
                <input name="Submit" type="button" class="Estilo4" value="GUARDAR" onclick="xajax_actualizarReunion(xajax.getFormValues(document.registro))" />
              </div>
            </label></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>';
}
	$objResponse->addAssign("panelReunion", "innerHTML", "$tabla");
    return $objResponse;
}

ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
require_once ("lib/componentes/xajax/xajax.inc.php");
$xajax = new xajax("../../../../lib/funciones/php/contratos/discusion/FuncionesDiscusionesXajax.php");
$xajax->registerFunction("verPanelAgregarReunion");
$xajax->registerFunction("verPanelBlanquear");
$xajax->registerFunction("agregarReunion");
$xajax->registerFunction("agregarAcuerdo");
$xajax->registerFunction("actualizarReunion");
$xajax->registerFunction("verDetalleReunion");
$xajax->registerFunction("verDetalleAcuerdo");
$xajax->registerFunction("verEditarReunion");
$xajax->registerFunction("verEliminarReunion");
$xajax->registerFunction("eliminarReunion");
$xajax->registerFunction("verPanelAgregarAcuerdo");



$xajax->processRequests();
?>