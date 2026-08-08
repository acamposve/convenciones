<?
$codigo_bitacora=$_GET['id'];
session_start();
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$limit = 20;  
		$var=$discusion->contarPeticiones($codigo_bitacora);
		$total=$discusion->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page; 
		$re_open=0;
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
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><a href="?url=contratos&amp;tbl=Bitacora&amp;accion=agregar_peticiones&codigo_discusion=<? print $codigo_bitacora; ?>">Agregar Registro</a>
    	<br />           <div align="center">   <? print $nombre_empresa;?></div>
  </td></tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
        <tr class=" tabla_inicio_fin">
          <td width="1%" height="24">&nbsp;</td>
          <td width="1%"></td>
          <td width="12%"><div align="center">Nro Peticion </div></td>
          <td width="58%"><div align="justify">Titulo Petici&oacute; </div></td>
          <td width="28%"><div align="center">Acciones</div></td>
        </tr>

        <?
	  $var=$discusion->listarPeticiones( $offset, $limit, $codigo_bitacora,$filtro );
	  
	  while( list( $key, $val) = each($var) ) {
		            $codigo_peticion = $var[$key][codigo_peticion];
					$nro_peticion = $var[$key][nro_peticion];
					$titulo_peticion = $var[$key][titulo_peticion];
					$estatus_peticion  = $var[$key][estatus_peticion ];
					
	  				$paginador=$paginador+1;
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
          <td align="center" >
          		  <?php if($_SESSION["tipo"]==1) {
			  	if ($estatus_peticion ==9)
				{ 	$re_open=1;
				?>
			          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=reabrir_peticiones&amp;id=<? print $codigo_peticion ?>&id_bitacora=<? print $codigo_bitacora?>">RA</a> 
          		<?php
				}
			}
          ?>
          
          </td>
          <td><div align="center"><? print $nro_peticion; ?></div></td>
          <td align="left "><? print $titulo_peticion; ?></td>
          <td align="center"><div align="center">

          
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=ver_peticiones&amp;id=<? print $codigo_peticion ?>&id_bitacora=<? print $codigo_bitacora?>">
          <img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Contrato" width="28" height="28" border="0" /></a> 
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar_peticiones&amp;id=<? print $codigo_peticion ?>&id_bitacora=<? print $codigo_bitacora?>">
          <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar Contratos" width="28" height="28" border="0" /></a> 
		<?php if ($estatus_peticion !=9){?>          
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar_peticiones&amp;id=<? print $codigo_peticion ?>&id_bitacora=<? print $codigo_bitacora?>">
          <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar Contrato" width="28" height="28" border="0" /></a>
          <?php } ?>
			<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=impresion_gen&amp;id=<? print $codigo_peticion;?>&page=<? print $page;?>&seccion=PET&bitacora=<? print $codigo_bitacora?>" >
    	      <img  src="../plantillas/plantilla_admin/images/impresora.gif" alt="Imprimir Clausula Contrato" width="28" height="28" border="0" /> </a></div>

          </td>
        </tr>
        <?  } ?>
        <tr class="tabla_inicio_fin">
          <td colspan="5" align="center" >
<?
if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{
	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
        echo "<a href=\"?url=contratos&tbl=".$tbl."&accion=listar_peticiones&id=".$codigo_bitacora."&page=" . ($page - 1) . "\"> Anterior </a>"; 
// http://localhost/admin/index.php?url=contratos&tbl=Bitacora&accion=listar_peticiones&id=8
      for ($i = 1; $i <= $pager->numPages; $i++) { 
        if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2)){
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
            echo "<a href=\"?url=contratos&tbl=".$tbl."&accion=listar_peticiones&id=".$codigo_bitacora."&page=$i\"> $i </a>"; 
    } 
	}

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=contratos&tbl=".$tbl."&accion=listar_peticiones&id=".$codigo_bitacora."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
	}
?></td>
        </tr>
        <tr>
        <td height="25">
        </td>
        <td colspan="4"><br />
        	<?php if ($re_open==1)
			{	
				print "<font class='tex3'> RA : Re Abrir Peticion</font>";

			}
			?>
        </td>
        </tr>
        
                <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="20" /> 
                <input name="url" type="hidden" value="contratos" /> 
                <input name="tbl" type="hidden" value="Discusion de contratos" /> 
            
      </table></td>

</table>
</form>