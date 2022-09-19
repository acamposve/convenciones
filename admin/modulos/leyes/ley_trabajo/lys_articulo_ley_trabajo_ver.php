<?
$id_articulo=$_GET['id'];
$page=$_GET['page'];
$var=$ley_trabajo->getArticuloLey($id_articulo);
	$nro_articulo				= $ley_trabajo->nro_articulo;
	$titulo						= $ley_trabajo->titulo_articulo;
	$texto_completo_articulo	= $ley_trabajo->texto_completo_articulo;
	$resumen_articulo			= $ley_trabajo->resumen_articulo;
	$codigo_titulo				= $ley_trabajo->codigo_titulo_comparativo;
	$campo_comparativo			= $ley_trabajo->campo_comparativo;

?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="13" height="32" >&nbsp;</td>
    <td width="505" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="data" method="post" action="" onsubmit="return validarEstado(this)">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" height="16">Numero del Art&iacute;culo: </td>
          <td width="50%">Titulo del Art&iacute;culo:</td>
        </tr>
        <tr>
          <td height="16"><label>
            <input readonly="" name="nro_articulo" type="text" class="textfield" id="nro_articulo" size="8" value="<? print $nro_articulo  ?>" />
          </label></td>
          <td><input readonly="" name="titulo_articulo" type="text" class="textfield" id="titulo_articulo" size="50" value="<? print $titulo	 ?>" /></td>
        </tr>
        <tr>
          <td height="16">Texto Completo del Art&iacute;culo: </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="16" colspan="3" class="texto_simple"><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
              <tr>
                <td bgcolor="#FFFFFF"><? print  $texto_completo_articulo ?></td>
              </tr>
            </table></td>
          </tr>
	<? 
		session_start();
		$t_usuario=$_SESSION["tipo"];
		$login=$_SESSION["login"];
		if ($t_usuario==1){
			?>
			<tr>
              <td height="16">Resumen del Art&iacute;culo:</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
            	<td height="16" colspan="3">
                	<table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                    	<tr>
                	        <td bgcolor="#FFFFFF" class="texto_simple"><? print $resumen_articulo  ?></td>
	        	        </tr>
    		         </table>
				</td>
			</tr>
		<?php 
		
		}
		?>
        <tr>
          <td height="16">Titulo Comparativo: </td>
          <td>Campo Comparativo: </td>
        </tr>
        <tr>
          <td height="16"><select name="titulo_comparativo" class="textfield" id="titulo_comparativo"  disabled="disabled" >
            <option value="0" selected="selected">Seleccione Titulo Comparativo</option>
            <?
						$var=$titulos->listarTitulosparaleyes();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
							$nombre_titulo = $var[$key][nombre_titulo];
							if($codigo_titulo==$codigo_titulo_comparativo){
							?>
            <option value="<? print $codigo_titulo_comparativo; ?>" selected="selected"> <? print $nombre_titulo; ?> </option>
            <? }else{
			?>
            <option value="<? print $codigo_titulo_comparativo; ?>"> <? print $nombre_titulo; ?> </option>
            <?
						}
						
						}
					?>
          </select></td>
          <td><input name="campo_comparativo" type="text" class="textfield" id="campo_comparativo" size="35" value="<? print $campo_comparativo  ?>" readonly="" /></td>
        </tr>
        <tr>
          <td height="24"><input type="hidden" value="1" name="enviar"></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label>
            <? if($_SESSION["tipo"]!=1)
				{
			$var1=$pantalla->getporUrl($acciones['editar_articulo']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
			?>
            <div align="right"><a href="?url=leyes&amp;tbl=LOT&amp;accion=editar&amp;id=<? print $id_articulo;?>&page=<? print $page;?>">
            <img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a>
            <a href="?url=leyes&amp;tbl=LOT&amp;accion=eliminar&amp;id=<? print $id_articulo;?>&page=<? print $page;?>">
            <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a>
            &nbsp; </div>
            <?
            	}
            }else{ ?>
            <div align="right"><a href="?url=leyes&amp;tbl=LOT&amp;accion=editar&amp;id=<? print $id_articulo;?>&page=<? print $page;?>">
            <img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a>
            <a href="?url=leyes&amp;tbl=LOT&amp;accion=eliminar&amp;id=<? print $id_articulo;?>&page=<? print $page;?>">
            <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a>
            &nbsp; </div>
            <? } ?>
          </label></td>
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
    <td height="10"></td>
    <td ></td>
	
  </tr>
</table>

