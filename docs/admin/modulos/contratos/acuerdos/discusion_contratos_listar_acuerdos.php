<?
$codigo_bitacora=$_GET['id'];
session_start();
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$limit = 50;  
		$var=$discusion->contarOfertas($codigo_bitacora);
		$total=$discusion->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page; 
?>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="13" height="32" bgcolor="CC6666">&nbsp;</td>
    <td width="505" bgcolor="CC6666"><a href="?url=contratos&amp;tbl=Discusion%20de%20contratos&amp;accion=agregar_ofertas&id_bitacora=<? print $codigo_bitacora?>"><img src="../plantillas/plantilla_admin/images/boton_agregar_oferta.jpg" width="112" height="15" border="0" /></a></td>
    <td width="11" bgcolor="CC6666">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
        <tr class="texto">
          <td width="1%" height="24">&nbsp;</td>
          <td width="1%">&nbsp;</td>
          <td width="26%"><div align="left">Nro Oferta </div></td>
          <td width="55%"><div align="left">Titulo</div></td>
          <td width="17%"><div align="center">Acciones</div></td>
        </tr>
        <tr>
          <td colspan="5" align="center" height="1"><hr color="#FFFFFF" /></td>
        </tr>
        <?
	  $var=$discusion->listarOfertas( $offset, $limit,  $codigo_bitacora);
	   
	  while( list( $key, $val) = each($var) ) {
		            $codigo_oferta = $var[$key][codigo_ofertas];
					$nro_oferta = $var[$key][nro_oferta];
					$titulo_oferta = $var[$key][titulo_oferta];
	  
	  ?>
        <tr class="texto_simple">
          <td height="15"><div align="center">
              <label></label>
          </div></td>
          <td align="center">&nbsp;</td>
          <td><div align="left">&nbsp;<? print $nro_oferta; ?></div></td>
          <td align="center"><div align="left"><? print $titulo_oferta; ?></div>          </td>
          <td align="center"><div align="center"><a href="?url=contratos&amp;tbl=Discusion%20de%20contratos&amp;accion=ver_reuniones&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Contrato" width="19" height="17" border="0" /></a> <a href="?url=contratos&amp;tbl=Discusion%20de%20contratos&amp;accion=editar_ofertas&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar Contratos" width="18" height="17" border="0" /></a> <a href="?url=contratos&amp;tbl=Discusion%20de%20contratos&amp;accion=eliminar_ofertas&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar Oferta" width="18" height="17" border="0" /></a></div></td>
        </tr>
        <tr>
          <td colspan="5" align="center" height="1"><hr color="#FFFFFF" /></td>
        </tr>
        <?  } ?>
        <tr class="texto">
          <td colspan="5" align="center" valign="bottom"><? 					if ($page == 1) // Esta es la primera pag
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
