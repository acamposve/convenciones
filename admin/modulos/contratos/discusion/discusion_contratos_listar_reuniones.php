<?
$codigo_bitacora=$_GET['id'];

session_start();
$paginador=0;
$desc="contratos";
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$limit = 20;  

		$var=$discusion->contarReuniones($codigo_bitacora);
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
<script  type="" language="">
<!--
function abrirVentana(id){
//var id = <? echo $codigo_bitacora;?>;
//alert (id);
window.open("../../admin/modulos/contratos/discusion/discusion_contratos_agregar_reuniones_externa.php?dato="+escape(id),"ventana1"," scrollbars=yes, resizable=1, location=yes");
}
-->
</script><link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="117"  border="0" cellpadding="0" cellspacing="0"  class=" Color_tabla">
  <tr >
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr class="Color_tabla">
    <td width="1%"  >&nbsp;</td>
    <td width="99%" >
    	<?php 
		if ($estatus_bt==10){
					print "Discución Cerrada";
				}else{
				print "<a href='#' class='linkAgregar'  onclick='abrirVentana(".$codigo_bitacora.")'> Agregar Registro</a> ";
				}
				?>

    	<!--<a href="modulos/contratos/discusion/discusion_contratos_agregar_reuniones_externa.php&amp;id_bitacora=<? print $codigo_bitacora?>" class=" linkAgregar">
		Agregar Registro</a>-->
            <br />
            <div align="center">   <? print $nombre_empresa;?></div>
        </td>
  </tr>
  
  <tr class="Color_tabla">
    <td height="63" >&nbsp;</td>
    <td valign="top" ><table width="100%" height="76" border="0" cellpadding="0" cellspacing="0"   class="Color_tabla">
	<tr >
    
    <tr>
        <tr class="tabla_inicio_fin">

          	<td width="25%"  ><div align="left">Fecha Reunion</div></td>
          	<td width="25%"><div align="left">Hora Reunion </div></td>
			<td width="25%"><div align="left">Duracion Reunion</div></td>
          	<td width="25%"><div align="center">Acciones</div></td>
        </tr>

        <?
	  $var=$discusion->listarReunionesSelect( $offset, $limit,  $codigo_bitacora,$filtro);

	  while( list( $key, $val) = each($var) ) {
            $codigo_reunion = $var[$key][codigo_reunion];
            $codigo_bitacora = $var[$key][codigo_bitacora];
            $fecha_reunion = $var[$key][fecha_reunion];
			$hora_reunion = $var[$key][hora_reunion];
			$duracion_reunion = $var[$key][duracion_reunion];
	  		$paginador=$paginador+1;
	  ?>
        <tr class="<? if (($paginador%2)!=0){print 'trTblReg1';	}else 	{	print 'trTblReg2';		}	?>">

          <td>
		  		&nbsp;<? print $fecha_reunion; ?>
          </td>
          <td>
				&nbsp;&nbsp;<? print $hora_reunion; ?>
          </td>
		  <td>
				<? print $duracion_reunion; ?>
          </td>

          <td align="center">
          	<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=ver_reuniones&amp;id=<? print $codigo_reunion;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver" width="28" height="28" border="0" /></a> 
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_reuniones&amp;id=<? print $codigo_reunion;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" width="28" height="28" border="0" /></a> 
			<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=impresion_gen&amp;id=<? print $codigo_reunion;?>&page=<? print $page;?>&seccion=REU&bitacora=<? print $codigo_bitacora?>" >
    	      <img  src="../plantillas/plantilla_admin/images/impresora.gif" alt="Imprimir Clausula Contrato" width="28" height="28" border="0" /> </a>

            <!--  
            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_reuniones&amp;id=<? print $codigo_reunion;?>&id_bitacora=<? print $codigo_bitacora?>">
            <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" width="18" height="17" border="0" /></a>
            -->
            </div>
          </td>
        </tr>
        <tr>
        </tr>
        <?  } ?>
        <tr class="tabla_inicio_fin" >
          <td colspan="4" align="center" valign="bottom" >
<?
	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&accion=listar_reuniones&id=".$codigo_bitacora."&page=" . ($page - 1) . "\"> Anterior </a>"; 

      for ($i = 1; $i <= $pager->numPages; $i++) { 
        if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2)){
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
            echo "<a href=\"?url=".$desc."&tbl=".$tbl."&accion=listar_reuniones&id=".$codigo_bitacora."&page=$i\"> $i </a>"; 
    } 
	}
    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&accion=listar_reuniones&id=".$codigo_bitacora."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
?></td>

        </tr>
      </table>
<tr>
	<td>&nbsp;
		
	</td>
</tr>
</table>
