<?php
function MostrarParticipantes()
{
	ini_set("include_path",".:$_SERVER[DOCUMENT_ROOT]");
	include_once('lib/objetos/control_acceso.php');
	$objResponse = new xajaxResponse();
	$Result=$control->BucarCategoriaPuntoAcceso();
	
	$Select = 'Punto de Acceso : </span><select name="punto" class="texto_tablas_titulos" onChange="ver_fechas()">
	<option value="" selected>SELECCIONE PUNTO DE ACCESO</option>
	<option value="0"> TODOS LOS PUNTOS </option>';
	while( list( $key, $val) = each($Result) )
	     {
		 $Nombre = $Result[$key][Nombre];
		 $IdCategoria = $Result[$key][Id_categoria_acceso];
		 $Select.='<option value="'.$IdCategoria.'"> '.$Nombre.' </option>';
		 }
	$Select.= '</select>';
	$objResponse->addAssign("subeventos", "innerHTML", "$Select");	 
    return $objResponse;
}

function ListarEmpresas($formulario){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include_once('lib/objetos/empresas.php');
	$objResponse = new xajaxResponse();
	$nombre=$formulario['nombre'];
	$sector=$formulario['sector'];
	$tipo=$formulario['tipos'];
	$categoria=$formulario['categoria'];
	$actividad=$formulario['actividad'];
	$Result=$empresas->listarEmpresasContrato($nombre, $sector, $tipo, $categoria, $actividad);
	$Select = '<span class="texto"> Empresas : </span><br />
	<select name="empresa"   class="texto_simple" onChange="">
	<option value="" selected>SELECCIONE EMPRESA</option>';
			while( list( $key, $val) = each($Result) ) {
				$codigo_empresa = $Result[$key][codigo_empresa];
				$nombre_empresa = $Result[$key][nombre_empresa];
				if($empresa==$codigo_empresa){
			$Select.= '<option value="'.$codigo_empresa.'" selected="selected">'.$nombre_empresa.'</option>';
				}else{
			$Select.= '<option value="'.$codigo_empresa.'">'.$nombre_empresa.'</option>';
				}
			}
	$Select.= '</select>';
	$objResponse->addAssign("empresas", "innerHTML", "$Select");	 
    return $objResponse;
}

function Mostrar_Contrato($codigo_contrato){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include_once('lib/objetos/contratos.php');
	$objResponse = new xajaxResponse();
	$Result=$contratos->obtenerContratosPorId($codigo_contrato);
	$codigo_contrato=$contratos->codigo_contrato;
	$pdf_auto=$contratos->pdf_auto;
	$fecha_inicio=$contratos->fecha_inicio;
	$fecha_termino=$contratos->fecha_termino;
	$duracion=$contratos->duracion;
	$ambito_aplicacion=$contratos->ambito_aplicacion;
	$codigo_empresa=$contratos->codigo_empresa;
	$tabla="HOLA MUNDO";
	$objResponse->addAssign("mostrarContrato", "innerHTML", "$tabla");	 
    return $objResponse;
}

function buscarContratos($formulario, $ordenar = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include_once('lib/objetos/contratos.php');
	$objResponse = new xajaxResponse();
	$nombre=$formulario['nombre'];
	$sector=$formulario['sector'];
	$tipo=$formulario['tipos'];
	$categoria=$formulario['categoria'];
	$actividad=$formulario['actividad'];
	$empresa=$formulario['empresa'];
	$Result=$contratos->listarContratosBuscador($nombre,$sector, $tipo, $categoria, $actividad, $empresa, $ordenar);
	$Select .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
    $Select .= '<tr> <td class="titulo" colspan="3">Resultado de la Busqueda</td></tr>
	<tr> <td class="texto" colspan="3"><table width="100%">
<tr> <td class="texto" colspan="3">&nbsp;</td></tr>';
	$Select .='<tr>
	  <td class="texto">ORDENAR POR:</td>
	  <td class="texto">&nbsp;</td>
	  <td class="texto"><a src="#" onClick="xajax_buscarContratos(xajax.getFormValues(document.buscador),\'codigo_sector\')" style="cursor:pointer">
        <strong>';
		if($ordenar=='codigo_sector')  
		$Select .='<u>X</u>&nbsp; ';
		
		$Select .='SECTOR</strong></a></td>
  </tr>';
  	$Select .='<tr> <td class="texto"><a src="#" onClick="xajax_buscarContratos(xajax.getFormValues(document.buscador),\'codigo_tipo\')" style="cursor:pointer"> 
	  <strong>';
		if($ordenar=='codigo_tipo')  
		$Select .='<u>X</u>&nbsp; ';
		
		$Select .='TIPO</strong></a>&nbsp;</td>
	  <td class="texto">&nbsp;</td>
	  <td class="texto"><a src="#" onClick="xajax_buscarContratos(xajax.getFormValues(document.buscador),\'codigo_actividad\')" style="cursor:pointer"> <strong>';
	if($ordenar=='codigo_actividad')  
		$Select .='<u>X</u>&nbsp; ';
	$Select .='ACTIVIDAD</strong></a></td>
	</tr>';
	$Select .='<tr> <td class="texto"><a src="#" onClick="xajax_buscarContratos(xajax.getFormValues(document.buscador),\'codigo_categoria\')" style="cursor:pointer">  
	  <strong>';
		if($ordenar=='codigo_categoria')  
		$Select .='<u>X</u>&nbsp; ';
		
		$Select .='CATEGORIA</strong></a>&nbsp;&nbsp;</td>
	  <td class="texto">&nbsp;</td>
	  <td class="texto"><a src="#" onClick="xajax_buscarContratos(xajax.getFormValues(document.buscador),\'nombre_empresa\')" style="cursor:pointer"> 
        <strong>';
	if($ordenar=='nombre_empresa')  
	$Select .='<u>X</u>&nbsp; ';
		$Select .='NOMBRE</strong></a></td>
	</tr>
		<tr> <td class="texto" colspan="3">&nbsp;</td>
	</tr>
			<tr> <td class="texto" colspan="3" height="1" bgcolor="#000000"></td>
	</tr>
</table></td></tr>';
	if(!empty($Result)){
	$cont=0;
	while( list( $key, $val) = each($Result) ) {
	$cont++;
	$codigo_empresa = $Result[$key][codigo_empresa];
	$codigo_contrato = $Result[$key][codigo_contrato];
	$nombre_empresa = utf8_encode( $Result[$key][nombre_empresa]);	
	$pdf_auto_acta= utf8_encode( $Result[$key][pdf_auto_acta]);	
	$Select .= '<tr><td class="texto"><input type="checkbox" name="'.$cont.'" id="'.$cont.'" onclick="generarLista()" value="'.$codigo_contrato.'">'.strtoupper($nombre_empresa).'</td><td class="texto"><a href="../contratos/documentos/'.$codigo_contrato.'/'.$pdf_auto_acta.'"><img src="../../../../plantillas/plantilla_admin/images/pdf.gif" border="0" width="14" height="14" alt="Ver Pdf" /></a></td><td class="texto"><a href="#" onclick="xajax_Mostrar_Contrato('.$codigo_contrato.')"><img src="../../../../plantillas/plantilla_admin/images/txt.gif" width="14" border="0" height="14" alt="Ver TxT" /></a></td></tr>
	<tr><td height="1" bgcolor="#CCCCCC" colspan="3"></td></tr>';
	
	}
	}
    $Select .='<tr><td colspan="3"><input type="hidden" name="contador"   id="contador" value="'.$cont.'"></td></tr></table>';
	$Select.= '';
	$objResponse->addAssign("listado_contratos", "innerHTML", "$Select");	 
    return $objResponse;


}

function listarContratosComparar($formulario = 0, $contador = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/contratos.php');
	$objResponse = new xajaxResponse();
	$cont=0;
	$tabla.='<table>';
	$tabla.='<tr><td class="texto"><strong>Usted ha seleccionado: </strong></td></tr>';
	for($i=1; $i<=$contador; $i++){
		if(!empty($formulario[$i])){
		$Result=$contratos->obtenerEmpresaContratosPorId($formulario[$i]);
		$codigo_empresa = $Result[$formulario[$i]][codigo_empresa];
		$codigo_contrato = $Result[$formulario[$i]][codigo_contrato];
		$nombre_empresa = utf8_encode( $Result[$formulario[$i]][nombre_empresa]);	
		$tabla.='<tr><td class="texto">'.$nombre_empresa.'</td></tr>';
		}
	}
	$tabla.='<tr><td class="texto">Titulo comparativo:<br />
<select name="titulo_comparativo" class="textfield" id="titulo_comparativo" onChange="generarListaTitulo()"><option value="0" selected="selected">Seleccione Titulo Comparativo</option>';
include('lib/objetos/titulos.php');
					$var=$titulos->listarTitulos(0,100,"");
						
						while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
						if($codigo_titulo==$codigo_titulo_comparativo){
            $tabla.='<option value="'.$codigo_titulo_comparativo.'" selected="selected">'.utf8_encode($nombre_titulo).'</option>'; 
						}else{
            $tabla.='<option value="'.$codigo_titulo_comparativo.'"> '.utf8_encode($nombre_titulo).'</option>';
						}
						
					}

	$tabla.='</select></td></tr>';
	$tabla.='</table>';
	$objResponse->addAssign("comparacion", "innerHTML", "$tabla");
    return $objResponse;
}

function verArticulosBlanquear(){
	$objResponse = new xajaxResponse();
	$tabla.=' ';
	$objResponse->addAssign("tweedledeeDiv", "innerHTML", "$tabla");
    return $objResponse;
}

function verArticulos( $articulo = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/contratos.php');
	include('lib/objetos/titulos.php');
	include('lib/objetos/categoria_titulos.php');
	include('lib/objetos/sectores.php');
	include('lib/objetos/actividad_empresa.php');
	include('lib/objetos/tipos_empresas.php');
	include('lib/objetos/categoria_titulos.php');
	$objResponse = new xajaxResponse();
	$var=$contratos->buscarArticulosContratos($articulo);
	
	$codigo_articulo = $var[$articulo][codigo_articulo];
				$nro_articulo = $var[$articulo][nro_articulos];
				$campo_comparativo = $var[$articulo][campo_comparativo];
				$status_articulo=$var[$articulo][status_articulo];
				$resumen_articulo=$var[$articulo][resumen_articulo];
				$texto_completo_articulo=$var[$articulo][texto_completo_articulo];
				$codigo_titulo_comparativo=$var[$articulo][codigo_titulo_comparativo];
				$codigo_categoria_titulo=$var[$articulo][codigo_categoria_titulo];
				
	$tabla.='<table border="1" cellpadding="0" cellspacing="0" bgcolor="ffffff">
	<tr>
	  <td class="texto"><div align="right"><a href="#" onClick="xajax_verArticulosBlanquear()">[X]</a></div></td>
  	</tr>
	<tr><td class="texto"><strong>'.$nro_articulo.':</strong> '.strip_tags($texto_completo_articulo).'</td></tr>
	</table>';
	$objResponse->addAssign("tweedledeeDiv", "innerHTML", "$tabla");
    return $objResponse;
}

function compararContratos($formulario = 0, $contador = 0, $titulo = 0){
	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	include('lib/objetos/contratos.php');
	include('lib/objetos/titulos.php');
	include('lib/objetos/categoria_titulos.php');
	include('lib/objetos/sectores.php');
	include('lib/objetos/actividad_empresa.php');
	include('lib/objetos/tipos_empresas.php');
	include('lib/objetos/categoria_titulos.php');
	$objResponse = new xajaxResponse();
	$cont=0;
	$tabla.='<table valign="top"  border="0" cellspacing="0" cellpadding="0 width="270" ><tr valign="top">';
	for($i=1; $i<=$contador; $i++){
		if(!empty($formulario[$i])){
		$tabla.='<td colspan="2" bgcolor="#F3CBCB">
		<img src="../media/superior_resto.gif" width="270" height="11"></td><td rowspan="3" >&nbsp;&nbsp; </td>';
		}
	}
	$tabla.='</tr>';
	$tabla.='<tr valign="top">';
	for($i=1; $i<=$contador; $i++){
		if(!empty($formulario[$i])){
			$Result=$contratos->obtenerEmpresaContratosPorId($formulario[$i]);
			$codigo_empresa =utf8_encode( $Result[$formulario[$i]][codigo_empresa]);
			$codigo_contrato = utf8_encode($Result[$formulario[$i]][codigo_contrato]);
			$concat.=$codigo_contrato.' ';
			$codigo_sector = utf8_encode($Result[$formulario[$i]][codigo_sector]);
			$codigo_categoria = utf8_encode($Result[$formulario[$i]][codigo_categoria]);
			$codigo_actividad = utf8_encode($Result[$formulario[$i]][codigo_actividad]);
			$codigo_tipo = utf8_encode($Result[$formulario[$i]][codigo_tipo]);
			$fecha_inicio = utf8_encode($Result[$formulario[$i]][fecha_inicio]);
			$fecha_termino = utf8_encode($Result[$formulario[$i]][fecha_termino]);
			$duracion = utf8_encode($Result[$formulario[$i]][duracion]);
			$ambito_aplicacion = utf8_encode($Result[$formulario[$i]][ambito_aplicacion]);
			
			$nombre_empresa = strtoupper( utf8_encode( $Result[$formulario[$i]][nombre_empresa]));	
			$tabla.=utf8_encode('<td class="texto" valign="top" bgcolor="#F3CBCB">
			<table height="100%" width="270" border="0" cellspacing="0" cellpadding="0" valign="top" bgcolor="#F3CBCB">
  <tr> 
	<td colspan="2" class="texto">DATOS DE LA EMPRESA:</td>
  </tr>
  <tr>
    <td width="52%" class="texto"><p>Empresa:</p>      </td>
    <td width="48%" class="texto"><p>&nbsp;</p>      </td>
  </tr>
  <tr>
    <td colspan="2" class="texto_simple"><span class="Estilo8">'. $nombre_empresa.'</span><span class="texto_simple"></span></td>
  </tr>
  <tr>
    <td colspan="2" valign="top" class="texto">Sector:</td>
  </tr>
  <tr>
    <td colspan="2" class="texto_simple">'.$sectores->traerPorNombre($codigo_sector).'</td>
  </tr>
  <tr>
    <td colspan="2" class="texto">Tipo:</td>
  </tr>
  <tr>
    <td colspan="2" class="texto_simple">'.$tipos_empresas->traerTipo($codigo_tipo).'</td>
  </tr>
  <tr>
    <td colspan="2" class="texto">Categoria:</td>
  </tr>
  <tr>
    <td colspan="2" class="texto_simple">'.$categorias_titulos->traerTitulo($codigo_categoria) .'</td>
  </tr>
  <tr>
    <td colspan="2" class="texto">Actividad:</td>
  </tr>
  <tr>
    <td colspan="2" class="texto_simple">'.$actividades->traerActividad($codigo_actividad).'</td>
  </tr>
  <tr>
    <td colspan="2" class="texto" height="1" bgcolor="#cccccc"></td>
  </tr>
  <tr>
    <td colspan="2" class="texto">DATOS DEL CONTRATO:</td>
  </tr>
  <tr>
    <td class="texto"><p>Duracion:</p>      </td>
    <td class="texto"><p>Fecha de Inicio:</p>      </td>
  </tr>
  <tr>
    <td class="texto_simple">'.$duracion.'</td>
    <td class="texto_simple">'.$fecha_inicio.'</td>
  </tr>
  <tr>
    <td class="texto"><p>Fecha de Termino:</p>      </td>
    <td class="texto">Ambito de Aplicacion:</td>
  </tr>
  <tr>
    <td><span class="texto_simple">'.$fecha_termino.'</span></td>
    <td><span class="texto_simple">'.$ambito_aplicacion.'</span></td>
  </tr>
  
    <tr>
    <td colspan="2" class="texto" height="1" bgcolor="#cccccc"></td>
  </tr>
  
  ');
  
  if($titulo == 0)
  $var=$contratos->listarArticulosContratossinPaginar($codigo_contrato);
  else
   $var=$contratos->listarArticulosContratossinPaginarPorTitulo($codigo_contrato,$titulo);
		$codigo=0;
		$categoria=0;		
		while( list( $key, $val) = each($var) ) {
				$codigo_articulo = utf8_encode($var[$key][codigo_articulo]);
				$nro_articulo = utf8_encode($var[$key][nro_articulos]);
				$campo_comparativo = utf8_encode($var[$key][campo_comparativo]);
				$status_articulo=utf8_encode($var[$key][status_articulo]);
				$resumen_articulo=utf8_encode($var[$key][resumen_articulo]);
				$codigo_titulo_comparativo=$var[$key][codigo_titulo_comparativo];
				$codigo_categoria_titulo=$var[$key][codigo_categoria_titulo];
				if($codigo_categoria_titulo!=$categoria){
				$nombre_categoria=utf8_encode($categorias_titulos->traerNombre($codigo_categoria_titulo));
				$tabla.= '<tr><td colspan="2"><span class="texto"><strong><u>'.utf8_encode(strtoupper($nombre_categoria)).'</u></span></td></tr>';
				if($codigo_titulo_comparativo!=$codigo){
				$valor=$titulos->getId($codigo_titulo_comparativo);
				$nombre_titulo=$titulos->nombre_titulo;
				$tabla.= '<tr> <td colspan="2"><span class="texto"><strong>'.utf8_encode(strtoupper($nombre_titulo)).'</span></td></tr>';	
				$codigo=$codigo_titulo_comparativo;
				$categoria=$codigo_categoria_titulo;
				$codigo=$codigo_titulo_comparativo;
				}
				}else{
				if($codigo_titulo_comparativo!=$codigo){
				$valor=$titulos->getId($codigo_titulo_comparativo);
				$nombre_titulo=$titulos->nombre_titulo;
				$tabla.= '<tr> <td colspan="2"><span class="texto"><strong>'.utf8_encode(strtoupper($nombre_titulo)).'</span></td></tr>';
				$codigo=$codigo_titulo_comparativo;
				}
				}
			$tabla.= '<tr> <td colspan="2"><span class="texto_simple"><a  onClick="xajax_verArticulos(\''.$codigo_articulo.'\')"><strong>'.$nro_articulo.':</strong> '.strip_tags($resumen_articulo).'</a></span></td></tr>';		
		}		

		$tabla.= '</table>
			</td><td width="3"></td>';
		}
	}
	$tabla.='</tr>';
$tabla.='<tr valign="top">';
	for($i=1; $i<=$contador; $i++){
	if(!empty($formulario[$i])){
	$tabla.='<td colspan="2">
<img src="../media/inferior_resto.gif" width="270" height="11"></td>';
}
	}
	$tabla.='</tr></table>';
	$objResponse->addAssign("comparaciones_tabla", "innerHTML", "$tabla");
	
	if($titulo!=0){
	$concat.="&titulo=".$titulo;
	}
	$pdf = '<div align="left"><a href="generar_pdf.php?id='.$concat.'"><img src="../../../../plantillas/plantilla_admin/images/boton_pdf_gde.gif" alt="GENERAR PDF" width="32" height="32" border="0"></a></div>';
	$objResponse->addAssign("pdf", "innerHTML", "$pdf");
    return $objResponse;

}


ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
require_once ("lib/componentes/xajax/xajax.inc.php");
$xajax = new xajax("../../../../lib/funciones/php/contratos/comparacion/FuncionesComparacionesXajax.php");
$xajax->registerFunction("MostrarParticipantes");
$xajax->registerFunction("ListarEmpresas");
$xajax->registerFunction("buscarContratos");
$xajax->registerFunction("Mostrar_Contrato");
$xajax->registerFunction("listarContratosComparar");
$xajax->registerFunction("compararContratos");
$xajax->registerFunction("verArticulos");
$xajax->registerFunction("verArticulosBlanquear");
$xajax->processRequests();
?>