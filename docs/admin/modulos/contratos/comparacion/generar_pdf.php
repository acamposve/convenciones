<? 	ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
	require_once('lib/funciones/php/pdf/html2fpdf.php');
	include('lib/objetos/contratos.php');
	include('lib/objetos/titulos.php');
	include('lib/objetos/categoria_titulos.php');
	include('lib/objetos/sectores.php');
	include('lib/objetos/actividad_empresa.php');
	include('lib/objetos/tipos_empresas.php');
	include('lib/objetos/categoria_titulos.php');
	$id=$_GET['id'];
	ob_start();
	$titulo = $_GET['titulo'];
	$id_array = explode(" ", $id);
	$cont=count($id_array);
	if (!empty($titulo)){
		$cont--;
	}
	for($i=0;$i<$cont;$i++){
	

  		$Result=$contratos->obtenerEmpresaContratosPorId($id_array[$i]);
		$codigo_empresa = $Result[$id_array[$i]][codigo_empresa];
		$codigo_contrato = $Result[$id_array[$i]][codigo_contrato];
		$concat.=$codigo_contrato.' ';
		$codigo_sector = $Result[$id_array[$i]][codigo_sector];
		$codigo_categoria = $Result[$id_array[$i]][ codigo_categoria];
		$codigo_actividad = $Result[$id_array[$i]][codigo_actividad];
		$codigo_tipo = $Result[$id_array[$i]][codigo_tipo];
		$fecha_inicio = $Result[$id_array[$i]][fecha_inicio];
		$fecha_termino = $Result[$id_array[$i]][fecha_termino];
		$duracion = $Result[$id_array[$i]][duracion];
		$ambito_aplicacion = $Result[$id_array[$i]][ambito_aplicacion];
		$nombre_empresa = strtoupper( utf8_encode( $Result[$id_array[$i]][nombre_empresa]));	
			
$tabla.= '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" class="texto_simple"><span class="style1">DATOS BASICOS DEL CONTRATO <br />
</span></td>
  </tr>
  <tr>
    <td width="47%" class="texto_simple"><strong>DURACION</strong>: '.$duracion.'</td>
    <td width="1%" class="texto_simple">&nbsp;</td>
    <td width="52%" class="texto_simple">&nbsp;</td>
  </tr>
  <tr>
    <td class="texto_simple"><strong>INICIO</strong>: '.$fecha_inicio.' </td>
    <td class="texto_simple">&nbsp;</td>
    <td class="texto_simple"><strong>FIN</strong>: '.$fecha_termino.' </td>
  </tr>
  <tr>
    <td class="texto_simple"><strong>AMBITO DE APLICACION</strong> : '.$ambito_aplicacion.' </td>
    <td class="texto_simple">&nbsp;</td>
    <td class="texto_simple"><strong>EMPRESA</strong>: '.$nombre_empresa.' </td>
  </tr>';
		$tabla.='<tr>
		<td colspan="3" class="texto_simple"><strong>CLAUSULAS</strong></td>
		</tr>
		<tr>
		<td class="texto_simple">&nbsp;</td>
		<td class="texto_simple">&nbsp;</td>
		<td class="texto_simple">&nbsp;</td>
		</tr>';  
	 if($titulo == 0){
  		$var=$contratos->listarArticulosContratossinPaginar($id_array[$i]);
  	} else{
   		$var=$contratos->listarArticulosContratossinPaginarTitulo($id_array[$i],$titulo);
		}
		
			$codigo=0;
			$categoria=0;
				while( list( $key, $val) = each($var) ) 
				{
					$codigo_articulo = $var[$key][codigo_articulo];
					$nro_articulo = $var[$key][nro_articulos];
					$campo_comparativo = $var[$key][campo_comparativo];
					$status_articulo=$var[$key][status_articulo];
					$texto_completo_articulo=$var[$key][texto_completo_articulo];
					$codigo_titulo_comparativo=$var[$key][codigo_titulo_comparativo];
					$codigo_categoria_titulo=$var[$key][codigo_categoria_titulo];
					$nombre_categoria=$categorias_titulos->traerNombre($codigo_categoria_titulo);
					
					$tabla .='<tr>
					<td class="texto_simple" colspan="3" >';
					
					if($categoria!=$nombre_categoria){
					$tabla .= $nombre_categoria;
					$categoria=$nombre_categoria;
					}else{
					$tabla .='&nbsp;';
					}
					$tabla .='</td>
					</tr>
					<tr>
					<td class="texto_simple" colspan="3" height="15"><strong>'.$nro_articulo.':</strong>'.$texto_completo_articulo.'</td>
					</tr>';
				}
   $tabla.='<tr>
    <td class="texto_simple">&nbsp;</td>
    <td class="texto_simple">&nbsp;</td>
    <td class="texto_simple">&nbsp;</td>
  </tr>
</table><br />
<br />
<br />
';
$orientacion="P";

	}
print $tabla;
// Output-Buffer in variable:
$html=ob_get_contents();
// delete Output-Buffer
ob_end_clean();
$pdf = new HTML2FPDF($orientacion);
$pdf->Header("Comparacion de Contratos");
$pdf->DisplayPreferences('HideWindowUI');
$pdf->AddPage();
$pdf->WriteHTML($html);

$pdf->Output('reporte.pdf','D');
?>