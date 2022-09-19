<?
$codigo_bitacora=$_GET['id'];
session_start();
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$limit = 20;  
		$var=$discusion->contarOfertas($codigo_bitacora);
		$total=$discusion->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page; 
		$busqueda_empresa=$discusion->listarDiscusiones(0,500,"");
		while( list( $key, $val) = each($busqueda_empresa) ) {
            $codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
			$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
			if ($codigo_bitacora_original ==$codigo_bitacora )
			{	
				$estatus_bt=$busqueda_empresa[$key][estatus_bitacora];
				
				$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
			} 
		}

?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />


<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="Color_tabla">
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr >
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
    <!--
    <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=agregar_ofertas&id_bitacora=<? print $codigo_bitacora?>" class="linkAgregar">Agregar Ofertas</a>
    -->
   		<br/>
		<div align="center">   <? print $nombre_empresa;?> </div>

    </td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
        <tr  class="tabla_inicio_fin">
          <td width="1%" height="24">&nbsp;</td>
          <td width="1%">&nbsp;</td>
          <td width="26%"><div align="left">Nro Oferta </div></td>
          <td width="51%"><div align="left">Titulo</div></td>
          <td width="21%"><div align="center">Acciones</div></td>
        </tr>

        <?
	  $var=$discusion->listarOfertas( $offset, $limit,  $codigo_bitacora, $filtro);
	   
	  while( list( $key, $val) = each($var) ) {
		            $codigo_oferta = $var[$key][codigo_ofertas];
					$nro_oferta = $var[$key][nro_oferta];
					$titulo_oferta = $var[$key][titulo_oferta];
	  				$paginador=$paginador+1;
	  ?>
        <tr class="<? if (($paginador%2)!=0){print 'trTblReg1';	}else 	{	print 'trTblReg2';	}?>">          <td height="15"><div align="center">
              <label></label>
          </div></td>
          <td align="center">&nbsp;</td>
          <td><div align="left">&nbsp;<? print $nro_oferta; ?></div></td>
          <td align="center"><div align="left"><? print $titulo_oferta; ?></div>          </td>
          <td align="center"><div align="center">
          	<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=ver_ofertas&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Contrato" width="28" height="28" border="0" /></a> 
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_ofertas&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar Contratos" width="28" height="28" border="0" /></a> 
			<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=impresion_gen&amp;id=<? print $codigo_oferta;?>&page=<? print $page;?>&seccion=OFE&bitacora=<? print $codigo_bitacora?>" >
    	      <img  src="../plantillas/plantilla_admin/images/impresora.gif" alt="Imprimir Clausula Contrato" width="28" height="28" border="0" /> </a>

			<!--  
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_ofertas&amp;id=<? print $codigo_oferta;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar Oferta" width="18" height="17" border="0" /></a>
            -->
            </div></td>
        </tr>

        <?  } ?>
        <tr class="tabla_inicio_fin">
          <td colspan="5" align="center" valign="bottom">
		  
<?
if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{
	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
            echo "<a href=\"?url=contratos&tbl=Bitacora&accion=listar_ofertas&id=".$codigo_bitacora."&page=" . ($page - 1) . "\"> Anterior </a>"; 

      for ($i = 1; $i <= $pager->numPages; $i++) { 
        if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2)){
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
            echo "<a href=\"?url=contratos&tbl=Bitacora&accion=listar_ofertas&id=".$codigo_bitacora."&page=$i\"> $i </a>"; 
    } 
	}

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=contratos&tbl=Bitacora&accion=listar_ofertas&id=".$codigo_bitacora."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
}
?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr height="1">
    <td>&nbsp;</td>
    <td></td>
    <td></td>
  </tr>
</table>
