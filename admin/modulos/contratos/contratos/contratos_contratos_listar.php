<?
session_start();
$tbl=$_GET['tbl'];
$page = $_GET['page']; 
$limit = 20; 
if($_SESSION['tipo']==1)
	{
		$var=$contratos->contarContratos();
	}
else
	{
		$var=$contratos->contarContratos_sesion();
	}
$total=$contratos->Total;
$pager  = Pager::getPagerData($total, $limit, $page); 
$offset = $pager->offset; 
$limit  = $pager->limit; 
$page   = $pager->page; 
$paginador= 0;
$filtro = $_GET['filtro'];		
$desc="contratos";
?>
<script  type="" language="">
<!--
function publicar(id)
	{
		var page = <? echo $page;?>;
		location.href="?url=contratos&tbl=Contratos&amp;accion=publicar_contrato&amp;id="+escape(id)+"&amp;page="+escape(page);
	}
-->
</script>
<link href="/plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0"   >
<tr height="1">
	<td></td>
    <td></td>
    <td></td>
  </tr>
<?php




	if($_SESSION["tipo"]==1)
	{
	?>
<tr class="Color_tabla" >
    <td width="100%"   ><a href="?url=contratos&amp;tbl=Contratos&amp;accion=agregar" class="linkAgregar" >AGREGAR REGISTRO</a>
    </td>

  </tr>
  <?php } ?>
  <tr class="Color_tabla" >
    <td rowspan="3" valign="top" ><form name="formcontratos" id="formcontratos" action="" method="get">
                <input name="inicio" type="hidden" value="0" />
                <input name="fin" type="hidden" value="10" /> 
                <input name="url" type="hidden" value="contratos" /> 
                <input name="tbl" type="hidden" value="Contratos" /> 
                <input name="accion" type="hidden" value="listar" />
		<input type="text" name="filtro" id="filtro" />
		
		<input  name="Buscar" type="submit" onclick="javascript:formcontratos.submit()" value="Buscar"  />
		</form><table width="100%" height="208" border="0" cellpadding="0" cellspacing="0"  >
				

        <tr class="tabla_inicio_fin" >
 		<?php 
			if($_SESSION['tipo']==1){
				?>
		          <td width="5%" ><div align="left">&nbsp;&nbsp;Publicar</div></td>
       		<?php
				  }else{ print "<td width='5%'>&nbsp;</td>";}
				  ?>
          <td width="30%" ><div align="left">&nbsp;&nbsp;Empresa</div></td>
          <td  width="5%" align="center">Sector
          	</td>
          <td width="10%" ><div align="center">
            <p>Fecha  <br/> Inicio </p>
            </div></td>
          <td width="10%" ><div align="center">Fecha Fin </div></td>
          <td width="10%" ><div align="center">Duraci&oacute;n</div></td>
          <td width="30%" ><div align="center">Acciones</div></td>
        </tr>
        <tr>
          <td colspan="6" align="center"  bgcolor="eeeeee" height="1"></td>
        </tr>	
        <?
	$sesion =$_SESSION['tipo'];
	  $var=$contratos->listarContratos( $offset, $limit, $filtro, $sesion );

	  while( list( $key, $val) = each($var) ) {
		            $codigo_contrato = $var[$key][codigo_contrato];
					$codigo_empresa = $var[$key][codigo_empresa];
					$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
					$origen_datos 	=$empresas->getId($codigo_empresa);
					$origen			= $empresas->url_empresa;
					$ambito_aplicacion = $var[$key][ambito_aplicacion];
					$status_publicacion = $var[$key][status_publicacion];
					$fecha_inicio = $var[$key][fecha_inicio];
					$fecha_de_termino = $var[$key][fecha_de_termino];
					$duracion = $var[$key][duracion];
					$nombre_sector = $var[$key][nombre_sector];
					$duracion = strtoupper($duracion);
					$pdf = $var[$key][pdf_auto_acta];
	  				$paginador =  $paginador +1;
	  
	  ?>
        <tr class="<? if (($paginador%2)!=0){print 'trTblReg1';	}else {	print 'trTblReg2';	}	?>">

		<?php 
			if($_SESSION['tipo']==1){
				?>
		        <td align="center"><div align="center">&nbsp;<input type="checkbox" title="Publicar / Despublicar" name="publicacion" value="<? print $codigo_contrato ?>" <? if ($status_publicacion=='PU'){?>checked="checked"<? } ?>   onclick="publicar(<?php print $codigo_contrato ?>) "/></div></td>
				<?php
				  }else{ print "<td width='5%'>&nbsp;</td>";}
				?>
          <td >
          	<div align="left" class="textAlignLeft" >
            <?php 
				if ($pdf=="")
				{
				?>
                	<a href="#"  title="Contrato Sin PDF" >
					<? print $nombre_empresa ?></a>
                <?php
				}else{
				?>
	            	<a href="modulos/contratos/contratos/documentos/<? echo $codigo_contrato?>/<? echo $pdf ?>"  onclick="window.open('', 'VEN_PDF')" title="Ver PDF"  target="VEN_PDF">
					<? print $nombre_empresa ?>  </a>          
                <?php
				}
				?> 
                 
				
           </div> 
          </td>
          <td align="center" valign="top">
          	<?php
				if ($paginador==1){
					print $nombre_sector;
					$nombre_tipo_ant=$nombre_sector;
 				}else{
					if ($nombre_sector!=$nombre_tipo_ant){
						print $nombre_sector;
						$nombre_tipo_ant=$nombre_sector;
					}
				} 
				?>
          </td>
          <td align="center" class="textAlignCenter"><div align="center">&nbsp;<? print $fecha_inicio; ?></div></td>
          <td align="center" class="textAlignCenter"><div align="center">&nbsp;<? print $fecha_de_termino ?></div></td>
          <td align="center" class="textAlignCenter"><div align="center">&nbsp;<? print $duracion ?></div></td>
          <td align="center">
			<?
			if (trim($origen)!=""){
		 		?>
				<a href="<? echo $origen ?>"  onclick="window.open('', '<?php print $codigo_contrato ?>')"   target="<?php print $codigo_contrato ?>"></a>  &nbsp;
                <?
			}
			?>
    	      
          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=listar_articulos&amp;id=<? print $codigo_contrato;?>">
    	      <img src="../plantillas/plantilla_admin/images/listar-articulo.png" alt="Listar Clausulas de Contrato"  border="0" width="28" height="28" /></a>
          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=listar_anexos&amp;id=<? print $codigo_contrato;?>" >
          	<img src="../plantillas/plantilla_admin/images/listar-anexo.png"   border="0"   alt="Listar Anexos de Contrato" width="28" height="28" /></a>
              
          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver&amp;id=<? print $codigo_contrato;?>">
	          <img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Contrato"  border="0" width="28" height="28" /></a>
		<?
				if($_SESSION["tipo"]!=1)
					{
						$var1=$pantalla->getporUrl($acciones['contratos_contratos_editar.php']);
						$id_pantalla=104;
						$pantalla->Limpiar();
						
						if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
   							{
							?>
 	                           <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar&amp;id=<? print $codigo_contrato;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar"  border="0" width="28" height="28" /></a>
							<?
                            }
					}
				else
					{
					?>
                           <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar&amp;id=<? print $codigo_contrato;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" border="0"  width="28" height="28" /></a>
					<?
                    }
				if($_SESSION["tipo"]!=1)
					{
						$var1=$pantalla->getporUrl($acciones['contratos_contratos_eliminar.php']);
						$id_pantalla=105;
						$pantalla->Limpiar();
						if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
   							{
							?>
	                            <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar&amp;id=<? print $codigo_contrato;?>">&nbsp;<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar"  border="0" width="28" height="28"  /></a>&nbsp;
							<?
                            }
					}
				else
					{
					?>
    	                <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar&amp;id=<? print $codigo_contrato;?>">&nbsp;<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar"  border="0" width="28" height="28"  /></a>&nbsp;
					<?
                    }            
            ?>
            <a href="?url=contratos&amp;tbl=Contratos&amp;accion=impresion_articulos_total&amp;id=<? print $codigo_contrato;?>" >
          	<img src="../plantillas/plantilla_admin/images/impresora.gif"  border="0"   alt="Imprimir todas las Clausulas"/></a>
            </td>
        </tr>

        <?  } 

		
		
		?>
        <tr class="tabla_inicio_fin">
          <td colspan="7" align="center" valign="bottom">
<?
if (($paginador<5) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{
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

}
?>
	</td>
		</tr>
        <tr>
        	<td height="4">

				</td>	
		</tr>
        </table>
       
    
</table>

