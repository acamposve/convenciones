<?
	$codigo_bitacora = $_GET['id_bitacora'];
	$codigo_acuerdo = $_GET['id'];
	$busqueda_empresa=$discusion->listarDiscusiones(0,500,"");
	while( list( $key, $val) = each($busqueda_empresa) ) {
    	$codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
		$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
		if ($codigo_bitacora_original ==$codigo_bitacora )
		{
			$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
		} 
	}
	$var=$discusion->verAcuerdo($codigo_acuerdo);	

	while( list( $key, $val) = each($var) ) {
		$codigo_oferta_acuerdo = $var[$key][codigo_oferta];
		$fecha_acuerdo = $var[$key][fecha_acuerdo];
		$nro_articulo = $var[$key][nro_articulo];
		$texto_completo_articulo = $var[$key][texto_completo_articulo];
		$resumen_articulo = $var[$key][resumen_articulo];
		$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
		$campo_comparativo = $var[$key][campo_comparativo];
		$estatus_acuerdo = $var[$key][estatus_acuerdo];		
		$codigo_peticion = $var[$key][codigo_peticion];
		$nombre_titulo = $var[$key][nombre_titulo];
		$titulo_articulo = $var[$key][titulo_articulo];
		}

?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1$" height="32" >&nbsp;</td>
    <td width="99%" >
            <br />
            <div align="center">   <font color="#FFFFFF" ><? print $nombre_empresa;?></font> </div>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>
          <td height="16" >
       		Fecha Acuerdo:</td>
			<td width="50%">                Nro Clausula          </td>
          </tr>
        <tr>
          <td width="50%" height="16" valign="middle">
   		    <input name="FechaAcuerdo" type="text" id="FechaAcuerdo"  readonly="readonly" value="<? print $fecha_acuerdo ?>"/></td>
			<td>
                <input name="NroClausula" type="text" id="NroClausula" readonly="readonly"  value="<? print $nro_articulo ?> " /></td>
        </tr>
        <tr>
          <td height="16" colspan="3">Titulo del Acuerdo</td>
          </tr>
        <tr>
          <td height="16" colspan="3">
               <input name="TituloAcuerdo" type="text" size="55" id="TituloAcuerdo" readonly="readonly" value="<? print $titulo_articulo ?> " /></td>
        <tr>
          <td height="16" colspan="3">Texto Completo Acuerdo</td>
          </tr>

        <tr>
          <td height="20" colspan="2">
			<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                	<td bgcolor="#FFFFFF"><? print  $texto_completo_articulo ?></td>
            </table>            
			</td>
        </tr>
        <tr>
          <td colspan="3">Resumen Acuerdo</td>
        </tr>
        <tr>
          <td colspan="3">
			<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                	<td bgcolor="#FFFFFF"><? print  $resumen_articulo ?></td>
            </table>
            </td>
		</tr>
        <tr>
          <td colspan="3">Oferta:</td>
          </tr>
        <tr>
          <td colspan="3"><select name="oferta" class="textfield" id="oferta"  disabled="disabled">
            <?
						
				$ofertas=$discusion->listarOfertas( 0, 500,$codigo_bitacora,"");
					while( list( $key, $val) = each($ofertas) ) {
							$codigo_ofe = $ofertas[$key][codigo_ofertas];

							$nro_oferta = $ofertas[$key][nro_oferta];
							$titulo_oferta = $ofertas[$key][titulo_oferta]; 
						if ($codigo_oferta_acuerdo==$codigo_ofe)
						{
							?>
							<option value="<? print $codigo_pet; ?>" selected="selected"> <? print $titulo_oferta; ?> </option>
							<?
						}
	
						}
						?>
          </select>
          </td>
        </tr>
        <tr>
        <tr>        </tr>
          <td colspan="3">Peticion:</td>
        <tr>
          <td colspan="3">
          <select name="peticion" class="textfield" id="peticion"  disabled="disabled">
            <?
						
				$var=$discusion->listarPeticiones( 0, 500,$codigo_bitacora,"");
				while( list( $key, $val) = each($var) ) {
					$codigo_pet = $var[$key][codigo_peticion];
					$nro_peticion = $var[$key][nro_peticion];
					$titulo_peticion = $var[$key][titulo_peticion];
					if ($codigo_pet==$codigo_peticion){
					?>
		            <option value="<? print $codigo_peticion; ?>" selected="selected"> <? print $titulo_peticion; ?> </option>
        		    <?
					}
						}
					?>
          </select></td>
        </tr>
        
        
        
        
          <td colspan="3">Titulo Comparativo: </td>
        </tr>
        <tr>
          <td colspan="3"> <select name="TituloComparativo" class="textfield" id="TituloComparativo"  disabled="disabled" >
            <?
						$var=$titulos->listarTitulos(0,500,"");
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comp = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
								if($codigo_titulo_comparativo==$codigo_titulo_comp)
								{
									?>
									<option value="<? print $codigo_titulo_comparativo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
									<? 
								}
							}
					?>
                    </select></td>
        </tr>
         <tr>
         	<td colspan="3" >Campo Comparativo: </td>
		 </tr>        
         <tr>
          <td colspan="3">
			<table width="95%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                	<td bgcolor="#FFFFFF"><? print  $campo_comparativo ?></td>
            </table>
		 </tr>     
        <tr>
          <td colspan="3"><input name="enviar" type="hidden" value="1"/></td>
          </tr>

        <tr>
          <td colspan="3" valign="bottom"><label></label>
      <div align="right">
                    <div align="right">Desea eliminar este Acuerdo&nbsp;&nbsp;&nbsp;
                    <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_acuerdos&amp;id=<? print $codigo_acuerdo?>&amp;flag=SI&id_bitacora=<? print $codigo_bitacora;?>">
                    <img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" /></a>
                    <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=listar_acuerdos&amp;id=<? print $codigo_bitacora; ?>">
                    <img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div>

          </td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
  </tr>
  <tr>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
    <td ></td>
  </tr>
</table>
