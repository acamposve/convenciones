<?
session_start();
		$tbl=$_GET['tbl'];
		$codigo_contrato= $_GET['id'];
		$page = $_GET['page']; 
		$limit = 10;  
		$total=$contratos->contarArticulosContratos($codigo_contrato);
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page; 
		$paginador= 0;		

$Contr=$contratos->ListarContratoDefinido( $codigo_contrato);
	    while( list( $key, $val) = each($Contr) )
			{
				$codigo_articulo = $Contr[$key][codigo_contrato];
				$nombre_empresa = $Contr[$key][nombre_empresa];
			}
				
?>
<script  type="" language="">
<!--
function abrirVentana(){
window.open("?url=contratos&amp;tbl=Contratos&amp;accion=impresion_articulos&amp;id=<? print $codigo_articulo;?>");
}
-->
</script>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="13" height="32" bgcolor="CC6666">&nbsp;</td>
    <td width="505" bgcolor="CC6666"><a href="?url=contratos&amp;tbl=Contratos&amp;accion=agregar_articulos&id=<? print $codigo_contrato; ?>"><img src="modulos/contratos/contratos/media/articulo_contrato_agregar.jpg" border="0" /></a> <font  class="texto" >  <?print $nombre_empresa;?></font> </td> 
    <td width="11" bgcolor="CC6666">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="F8EEEE">&nbsp;</td>
    <td rowspan="3" valign="top" bgcolor="F8EEEE"><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
        <tr class="texto">
          <td width="3%" height="24">&nbsp;</td>
          <td width="8%"><div align="left">Publicar</div></td>
          <td width="17%"><div align="left">&nbsp;&nbsp;Nro Articulo </div></td>
          <td width="27%"><div align="left">Titulo Clausula </div></td>
          <td width="24%"><div align="left">Titulo Comparativo </div></td>
          
          <td width="21%"><div align="center">Acciones</div></td>
        </tr>
        <tr>
          <td colspan="6" align="center" height="1"><hr color="#FFFFFF" /></td>
        </tr>
		<?
$var=$contratos->listarArticulosContratos( $codigo_contrato, $offset, $limit);
		
		while( list( $key, $val) = each($var) ) {
					
				$codigo_articulo = $var[$key][codigo_articulo];
				$nro_articulo = $var[$key][nro_articulos];
				$titulo_comparativo = $var[$key][codigo_titulo_comparativo];
				$titulo_articulo = $var[$key][titulo_articulo];
 
				$status_articulo=$var[$key][status_articulo];
				$valor_titulo = $titulos -> getId($titulo_comparativo);
				$paginador =  $paginador +1;	

				if(!empty($nombre_titulo)){
				$nombre_titulo = $titulos->nombre_titulo;
				}else{
				$nombre_titulo = "Ninguna";
				}
?>
        <tr class="texto_simple">
          <td height="15"><div align="center">
              <label></label>
          </div></td>
          <td align="center"><div align="left">&nbsp;<input type="checkbox" name="publicacion" value="<? print $codigo_contrato ?>" <? if ($status_publicacion=='publicado'){?>checked="checked"<? } ?> /></div></td>
          <td><div align="left">&nbsp;&nbsp;
			<a href="?url=contratos&amp;tbl=Contratos&amp;accion=impresion_articulos&amp;id=<? print $codigo_articulo;?>" title="Ver Clausula Contrato" >
		  	<? print $nro_articulo; ?></a></div></td>
          <td><div align="left">&nbsp;
   			<a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_articulo&amp;id=<? print $codigo_articulo;?>" title="Ver Clausula Contrato" >
			<? print $titulo_articulo; ?></a>          </div></td>
          <td><? print $nombre_titulo; ?></td>
          
          <td align="center"><a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_articulos&amp;id=<? print $codigo_contrato;?>"></a> <a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_articulo&amp;id=<? print $codigo_articulo;?>"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Clausulas  Contrato" width="19" height="17" border="0" /></a> <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar_articulos&amp;id=<? print $codigo_articulo;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar Clausulas Contratos" width="18" height="17" border="0" /></a> <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_articulos&amp;id=<? print $codigo_articulo;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar Clausulas Contrato" width="18" height="17" border="0" /></a>
        <!--  <img  onclick="abrirVentana()" src="../plantillas/plantilla_admin/images/impresora.gif" alt="Imprimir Clausula Contrato" width="18" height="17" border="0" />-->
          </td>
        </tr>
        <tr>
          <td colspan="6" align="center" height="1"><hr color="#FFFFFF" /></td>
        </tr>
        <?  } ?>
        <tr class="texto">
          <td colspan="6" align="center" valign="bottom">
<? 
if (($paginador<10) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{
			if ($page == 1) // Esta es la primera pag
    		    echo "Anterior"; 
		    else            // not the first page, link to the previous page 
        		echo "<a href=\"?url=contratos&tbl=Contratos&accion=listar_articulos&id=".$codigo_contrato."&page=" . ($page - 1) . "\"> Anterior </a>"; 
		    for ($i = 1; $i <= $pager->numPages; $i++) 
				{ 
			        if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2))
						{
					        if ($i == $pager->page) 
					            echo " <strong>$i</strong> "; 
					        else 
					            echo "<a href=\"?url=contratos&tbl=Contratos&accion=listar_articulos&id=".$codigo_contrato."&page=$i\"> $i </a>"; 

					    } 
				}
		    if ($page == $pager->numPages) // this is the last page - there is no next page 
        		echo "Siguiente"; 
		    else            // not the last page, link to the next page 
        		echo "<a href=\"?url=contratos&tbl=Contratos&accion=listar_articulos&id=".$codigo_contrato."&page=".($page + 1)."\"> Siguiente </a>"; 
}
?>
</td>
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
