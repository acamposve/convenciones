<?
session_start();
		$tbl=$_GET['tbl'];
		$codigo_contrato= $_GET['id'];
		$page = $_GET['page']; 
		$limit = 20;  
		$$paginador=0;
		$total=$contratos->contarAnexosContratos($codigo_contrato);
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page; 
	$Contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
	    while( list( $key, $val) = each($Contr) )
			{
				$codigo_articulo = $Contr[$key][codigo_contrato];
				$nombre_empresa = $Contr[$key][nombre_empresa];
			}		
			$desc="contratos";
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />


<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr height="1">
    <td></td>
    <td></td>
  </tr>
  <tr class="Color_tabla">
    <td width="100%" ><a href="?url=contratos&amp;tbl=Contratos&amp;accion=agregar_anexos&id=<? print $codigo_contrato; ?>" class="linkAgregar"> Agregar Registro</a> <br />
        <center>
          <? print $nombre_empresa;?>
        </center></td>
  </tr>
  <tr >
    <td rowspan="3" valign="top" ><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0"  >
      <tr class="tabla_inicio_fin">
        <td width="1%" height="24">&nbsp;</td>
        <td width="69%"><div align="left">Nombre Anexo </div></td>
        <td width="30%"><div align="center">Acciones</div></td>
      </tr>
      <?
		$var=$contratos->listarAnexosContratos( $codigo_contrato,$offset, $limit );
		
		while( list( $key, $val) = each($var) ) {
				$codigo_anexo = $var[$key][codigo_anexo];
				$nombre_anexo = $var[$key][nombre_anexo];
				$status_anexo=$var[$key][status_anexo];
				$paginador = $paginador+1;
		?>
      <tr class="<? 
				if (($paginador%2)!=0)
					{
						print 'trTblReg1';
					}
					else 
					{	
						print 'trTblReg2';
					}
					?>">
        <td height="15"><div align="center">
          <label></label>
        </div></td>

        <td><div align="left">&nbsp; <a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_anexos&amp;id=<? print $codigo_anexo;?>" title="Ver Anexos Contrato"> <? print $nombre_anexo; ?></a></div></td>
        <td align="center"><a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_anexos&amp;id=<? print $codigo_anexo;?>"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Anexos  Contrato"  width="28" height="28"  border="0" /></a> <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar_anexos&amp;id=<? print $codigo_anexo;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar Anexos Contratos" width="28" height="28"  border="0" /></a> <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_anexos&amp;id=<? print $codigo_anexo;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar Anexos Contrato"  width="28" height="28"  border="0" /></a></td>
      </tr>
      <?  } ?>
      <tr class="tabla_inicio_fin">
        <td colspan="6" align="center" valign="bottom"><?
	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
        		echo "<a href=\"?url=contratos&tbl=Contratos&accion=listar_anexos&id=".$codigo_contrato."&page=" . ($page - 1) . "\"> Anterior </a>"; 

        for ($i = 1; $i <= $pager->numPages; $i++) { 
        if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2)){
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
           echo "<a href=\"?url=contratos&tbl=Contratos&accion=listar_anexos&id=".$codigo_contrato."&page=$i\"> $i </a>"; 
    } 
	}

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        		echo "<a href=\"?url=contratos&tbl=Contratos&accion=listar_anexos&id=".$codigo_contrato."&page=".($page + 1)."\"> Siguiente </a>"; 
		

		
?>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr> </tr>
  <tr> </tr>
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
