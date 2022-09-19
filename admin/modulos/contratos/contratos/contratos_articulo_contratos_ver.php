<?
session_start();

$codigo_articulo=$_GET['id'];
$page=$_GET['page'];

$var=$contratos->obtenerArticuloContratosPorId($codigo_articulo);
$nro_articulo=$contratos->nro_articulo;
$texto_completo=$contratos->texto_completo_articulo;
$resumen_texto=$contratos->resumen_articulo;
$titulo=$contratos->codigo_titulo_comparativo;
$campo=$contratos->campo_comparativo;
$titulo_articulo=$contratos->titulo_articulo;
$articulo_cerrado=$contratos->articulo_cerrado;
$codigo_contrato=$contratos->codigo_contrato;
$Contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
	    while( list( $key, $val) = each($Contr) )
			{
				$codigo_contrato = $Contr[$key][codigo_contrato];
				$nombre_empresa = $Contr[$key][nombre_empresa];
			}
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="30" height="32" >
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
&nbsp;</td>
    <td width="1010" ><div align="center">   <?print $nombre_empresa;?> </span></div></td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1" >
        <tr>
          <td width="100%" height="16">Nro de Clausula : 
                    	 &nbsp;&nbsp;&nbsp;
                        Titulo de la Clausula :          </td>
        </tr>
        <tr>
          <td height="16" valign="middle"><input name="nro_articulo" type="text" class="textfield" id="nro_articulo" size="3" value="<? print  $nro_articulo ?>" readonly="" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="titulo_articulo" type="text" class="textfield" id="titulo_articulo" size="50" value="<? print  $titulo_articulo ?>" readonly="" /></td>
        </tr>
        <tr>
          <td height="16">Texto Completo de la Clausula: </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print  $texto_completo ?></td>
              </tr>
            </table></td>
        </tr>
        <? session_start();
		if($_SESSION["tipo"]==1){
			?>	
			<tr>
			  <td height="16">Resumen de la Clausula:</td>
			</tr>
			<tr>
			  <td height="16" colspan="3"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
				  <tr>
					<td bgcolor="#FFFFFF"><? print  $resumen_texto ?></td>
				  </tr>
            </table>
            <?
			}
			?>
		</td>
        </tr>
        <tr>
          <td height="16">	Titulo Comparativo: 
          					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          								
							Campo Comparativo: </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16"><select name="titulo" class="textfield" id="titulo" disabled="disabled">
              <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
              <?
						$var=$titulos->listarTitulosparaleyes();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($titulo==$codigo_titulo_comparativo){
							?>
              <option value="<? print $codigo_titulo_comparativo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
              <? }else{
			?>
              <option value="<? print $codigo_titulo_comparativo; ?>"> <? print $nombre_titulo; ?> </option>
              <?
						}
						
						}
					?>
          </select>
          &nbsp;&nbsp;&nbsp;
            <input name="campo" type="text" class="textfield" id="campo" size="35" value="<? print  $campo ?>" readonly=""/>
            <input type="hidden" name="enviar" value="1" /></td>
        </tr>
		<?
       if ($_SESSION['tipo']==1){
	   		
			?>
            <tr> 
              <td> 
                    <input type="checkbox" name="articulo_cerrado_checkbox" disabled="disabled" <?php if($articulo_cerrado == "1"){echo " CHECKED";}?>> Clausula Cerrada
              </td>
            </tr>
           <?
		   }
		?>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
          <?
			if($_SESSION["tipo"]!=1)
				{
					$var1=$pantalla->getporUrl($acciones['contratos_articulo_contratos_eliminar.php']);
					$id_pantalla=110;
					$pantalla->Limpiar();
					if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
   						{
						?>
                          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_articulos&amp;id=<? print $codigo_articulo;?>" >
                          <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" border="0"  align="right" width="28" height="28"/></a>						
						<?
	                   	}
				}
			else
				{
				?>
                      <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_articulos&amp;id=<? print $codigo_articulo;?>" >
                      <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" border="0"  align="right" width="28" height="28"/></a>
          		<?
               }            

			if($_SESSION["tipo"]!=1)
				{
					$var1=$pantalla->getporUrl($acciones['contratos_articulo_contratos_editar.php']);
					$id_pantalla=108;
					$pantalla->Limpiar();
					if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
   						{
						?>
                          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar_articulos&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" >
                          <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" border="0"  align="right" width="28" height="28"/></a>
						<?
	                   	}
				}
			else
				{
				?>
                      <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar_articulos&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" >
                      <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" border="0"  align="right" width="28" height="28"/></a>
          		<?
               }            
           ?>       
          
          
          
          


      </tr>
      </table>
    </form></td>
   
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
    
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>

  </tr>
</table>
