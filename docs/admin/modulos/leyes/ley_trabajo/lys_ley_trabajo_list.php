<?
session_start();
		/*$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$limit = 10;  
		$var=$empresas->contarEmpresas();
		$total=$empresas->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page;  */
		$desc="leyes";
?>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="13" height="32" bgcolor="CC6666">&nbsp;</td>
    <td width="505" bgcolor="CC6666">
	
				<?
		if($_SESSION['tipo']!=1)
		{
			$var1=$pantalla->getporUrl($acciones['ver']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
				
			echo '<a href="?url=leyes&amp;tbl=LOT&amp;accion=ver ley"><img src="modulos/leyes/ley_trabajo/media/ley_trabajo_consultar.jpg" width="116" height="14" border="0" Alt="Ver-" /></a>';
			}
			}else{
			echo '<a href="?url=leyes&amp;tbl=LOT&amp;accion=ver ley"><img src="modulos/leyes/ley_trabajo/media/ley_trabajo_consultar.jpg" width="116" height="14" border="0" Alt="Ver-" /></a>';
			}
			?>
			
			<?
		if($_SESSION['tipo']!=1)
		{
			$var1=$pantalla->getporUrl($acciones['editar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
				
			echo '&nbsp;&nbsp;&nbsp;<a href="?url=leyes&amp;tbl=LOT&amp;accion=editar ley"><img src="modulos/leyes/ley_trabajo/media/ley_trabajo_editar.jpg" width="104" height="14" border="0" /></a>';
			}
			}else{
			echo '&nbsp;&nbsp;&nbsp;<a href="?url=leyes&amp;tbl=LOT&amp;accion=editar ley"><img src="modulos/leyes/ley_trabajo/media/ley_trabajo_editar.jpg" width="104" height="14" border="0" /></a>';
			}
			?>
			
			<?
			
			if($_SESSION['tipo']!=1)
				{
			$var1=$pantalla->getporUrl($acciones['agregar_articulo']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
				
			echo '&nbsp;&nbsp;&nbsp;<a href="?url=leyes&amp;tbl=LOT&amp;accion=agregar"><img src="modulos/leyes/ley_trabajo//media/articulo_ley_trabajo_agregar.jpg" width="104" height="14" border="0" /></a>';
			}
			}else{
			echo '&nbsp;&nbsp;&nbsp;<a href="?url=leyes&amp;tbl=LOT&amp;accion=agregar"><img src="modulos/leyes/ley_trabajo//media/articulo_ley_trabajo_agregar.jpg" width="104" height="14" border="0" /></a>';
			}
			?></td>
    <td width="11" bgcolor="CC6666">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
        <tr class="texto">
          <td width="0%" height="24">&nbsp;</td>
          <td width="13%"><div align="left">Nro Art</div></td>
          <td width="49%"><div align="left">Resumen del Art&iacute;culo </div></td>
          <td width="23%"><div align="left">Titulo Comparativo </div></td>
          <td width="15%"><div align="center">Acciones</div></td>
        </tr>
        <tr>
          <td colspan="5" align="center" height="1"><hr color="#FFFFFF" /></td>
        </tr>
        <?
	  /*$var=$empresas->listarEmpresas( $offset, $limit );
	  
	  while( list( $key, $val) = each($var) ) {
		            $codigo_empresa = $var[$key][codigo_empresa];
					$nombre_sector = $var[$key][nombre_sector];
					$nombre_empresa = $var[$key][nombre_empresa];
					$direccion_empresa = $var[$key][direccion_empresa];
					$rif_empresa = $var[$key][rif_empresa];
*/
	  
	  ?>
        <tr class="texto_simple">
          <td height="15"><div align="center">
              <label></label>
          </div></td>
          <td align="center"><div align="left">&nbsp;<? print $nombre_empresa; ?> </div></td>
          <td><div align="left">&nbsp;<? print $rif_empresa; ?></div></td>
          <td align="center"><div align="left">&nbsp;<? print $nombre_sector; ?></div></td>
          <td align="center"><a href="?url=tablas&amp;tbl=LOT&amp;accion=ver&amp;id=<? print $codigo_empresa;?>"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="19" height="17" border="0" /></a> <a href="?url=tablas&amp;tbl=LOT&amp;accion=editar&amp;id=<? print $codigo_empresa;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="18" height="17" border="0" /></a> <a href="?url=tablas&amp;tbl=LOT&amp;accion=eliminar&amp;id=<? print $codigo_empresa;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="18" height="17" border="0" /></a></td>
        </tr>
        <tr>
          <td colspan="5" align="center" height="1"><hr color="#FFFFFF" /></td>
        </tr>
        <? // } ?>
        <tr class="texto">
          <td colspan="5" align="center" valign="bottom">
<?
$desc="leyes";
	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=" . ($page - 1) . "\"> Anterior </a>"; 

        for ($i = 1; $i <= $pager->numPages; $i++) { 
        if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2)){
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
            echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=$i\"> $i </a>"; 
    } 
	}

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
?></td>
        </tr>
      </table></td>
    <td bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td bgcolor="F8EEEE">&nbsp;</td>
  </tr>
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
