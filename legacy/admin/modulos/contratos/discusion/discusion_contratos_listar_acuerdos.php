<?

			//$vr=$discusion->actualizarAcuerdos(22,"10-10-2008", "1","2","3","4",10,6,25,"5");
			print $vr;
$codigo_bitacora=$_GET['id'];
session_start();
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$filtro = $_GET['filtro'];				
		$limit = 20;  
		$var=$discusion->contarAcuerdos($codigo_bitacora);
		$total=$discusion->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page; 
		$desc	="contratos";		
			$busqueda_empresa=$discusion->listarDiscusiones(0,500,"");
		while( list( $key, $val) = each($busqueda_empresa) ) {
            $codigo_bitacora_original = $busqueda_empresa[$key][codigo_bitacora];
			$codigo_empresa = $busqueda_empresa[$key][codigo_empresa];
			if ($codigo_bitacora_original ==$codigo_bitacora )
			{
				$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
			} 
		}
		
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<form name="formdiscuciones_acuerdos" id="formdiscuciones_acuerdos" action="" method="get">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="Color_tabla">
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr class="Color_tabla">
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
    <!--
    <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=agregar_acuerdos&id_bitacora=<? print $codigo_bitacora?>" class="linkAgregar">
    Agregar Registro</a>
    -->
            <br />
            <div align="center">   <? print $nombre_empresa;?> </div>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
	<label>
    	<div style="padding-top:5px"><input type="text" name="filtro" id="filtro" /><input  name="Buscar" type="button" onclick="javascript:formdiscuciones_acuerdos.submit()" value="Buscar"  /></div>
	</label>    

        <tr  class="tabla_inicio_fin" >
          <td width="10%"><div align="left">&nbsp;&nbsp;Nro Acuerdo</div></td>
          <td width="20%"><div align="left">Fecha Acuerdo</div></td>
          <td width="50%"><div align="left">Titulo</div></td>          
          <td width="20%"><div align="center">Acciones</div></td>
        </tr>
        <?
	  $var=$discusion->listarAcuerdos( $offset, $limit,  $codigo_bitacora, $filtro);

	  while( list( $key, $val) = each($var) ) {

		$codigo_acuerdos			= $var[$key][codigo_acuerdos];
		$codigo_bitacora			= $var[$key][codigo_bitacora];
		$codigo_peticion			= $var[$key][codigo_peticion];
		$codigo_oferta				= $var[$key][codigo_oferta];
		$fecha_acuerdo				= $var[$key][fecha_acuerdo];
		$nro_articulo				= $var[$key][nro_articulo];
		$texto_completo_articulo	= $var[$key][texto_completo_articulo];
		$resumen_articulo			= $var[$key][resumen_articulo];
		$codigo_titulo_comparativo	= $var[$key][codigo_titulo_comparativo];		
		$campo_comparativo			= $var[$key][campo_comparativo];
		$estatus_acuerdo			= $var[$key][estatus_acuerdo];				
		$titulo_articulo			= $var[$key][titulo_articulo];						
		$paginador=$paginador+1;
	  ?>
        <tr class="<? if (($paginador%2)!=0)	{print 'trTblReg1';	}else { print 'trTblReg2';	}?>">
          <td height="15">&nbsp;&nbsp;
          	<? print $nro_articulo; ?>
          </td>
          <td height="15">
          	<? print $fecha_acuerdo; ?>
          </td>
          <td align="center"><div align="left">
		  	<? print $titulo_articulo; ?>
            </div></td>
          
          <td align="center"><div align="center">
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=ver_acuerdos&amp;id=<? print $codigo_acuerdos;?>&id_bitacora=<? print $codigo_bitacora?>">
          <img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver" width="28" height="28" border="0" /></a> 
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_acuerdos&amp;id=<? print $codigo_acuerdos;?>&id_bitacora=<? print $codigo_bitacora?>">
          <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" width="28" height="28" border="0" /></a> 

			<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=impresion_gen&amp;id=<? print $codigo_acuerdos;?>&page=<? print $page;?>&seccion=ACU&bitacora=<? print $codigo_bitacora?>" >
    	      <img  src="../plantillas/plantilla_admin/images/impresora.gif" alt="Imprimir Clausula Contrato" width="28" height="28" border="0" /> </a>

		<!-- 
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_acuerdos&amp;id=<? print $codigo_acuerdos;?>&id_bitacora=<? print $codigo_bitacora?>">
          <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" width="18" height="17" border="0" /></a>
           -->
          </div></td>
        </tr>

        <?  } ?>
        <tr  class="tabla_inicio_fin">
          <td colspan="5" align="center" valign="bottom">
<?
if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{

	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&accion=listar_acuerdos&id=".$codigo_bitacora."&page=" . ($page - 1) . "\"> Anterior </a>"; 

      for ($i = 1; $i <= $pager->numPages; $i++) { 
        if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2)){
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
            echo "<a href=\"?url=".$desc."&tbl=".$tbl."&accion=listar_acuerdos&id=".$codigo_bitacora."&page=$i\"> $i </a>"; 
    } 
	}

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&accion=listar_acuerdos&id=".$codigo_bitacora."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
}
?></td>
        </tr>
		<tr><td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
</table>
                <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="20" /> 
                <input name="url" type="hidden" value="contratos" /> 
                <input name="tbl" type="hidden" value="Discusion de contratos" /> 
                <input name="accion" type="hidden" value="listar_acuerdos" /> 
                <input name="id" type="hidden" value="<? print $codigo_bitacora ?>" /> 
</form>