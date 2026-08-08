<?
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$limit = 20;  
		$var=$otras_leyes->contarLeyes();
		$total=$otras_leyes->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page;  
		$filtro = $_GET['filtro'];
		$paginador= 0;
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<form name="formLeyesOtras" id="formLeyesOtras" action="" method="get">
<table width="100%" height="69%" border="0" cellpadding="0" cellspacing="0">
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr class="Color_tabla"> 
    <td width="1%" height="32"  class="Color_tabla" >&nbsp;</td>
    <td width="99%"  class="Color_tabla">      <?
			
			if($_SESSION['tipo']!=1)
				{
			$var1=$pantalla->getporUrl($acciones['agregar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
				
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=agregar ley" class="linkAgregar">Agregar Registro</a>';
			}
			}else{
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=agregar ley" class="linkAgregar">Agregar Registro</a>';
			}
			?>
			</td>
    <td width="11" >&nbsp;</td>
  </tr>
  <tr  class="Color_tabla">
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><table width="100%" height="119" border="0" cellpadding="0" cellspacing="0" >
    
                  <label>
             <label>
        	  <div style="padding-top:5px"><input type="text" name="filtro" id="filtro" /><input  name="Buscar" type="button" onclick="javascript:formLeyesOtras.submit()" value="Buscar"  /> </div>
       	    <td height="30"></label>
        <tr class="tabla_inicio_fin">
          <td width="75%"><div align="left">Nombre de la Ley </div></td>
          <td width="25%"><div align="center">Acciones</div></td>
        </tr>

        <?
	  $var=$otras_leyes->listarLeyes( $offset, $limit, $filtro );
	  
	  while( list( $key, $val) = each($var) ) {
		            $codigo_otra_ley = $var[$key][codigo_otra_ley];
					$nombre_ley = $var[$key][nombre_ley];
					$pdf_ley = $var[$key][pdf_ley];					
					
					$paginador= $paginador +1;
						  
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
        
        
          <td><div align="left">
          	<a href="modulos/leyes/otras_varias/documentos/<? echo $codigo_otra_ley?>/<? echo $pdf_ley ?>"  onclick="window.open('', 'VEN_PDF')" title="Ver PDF"  target="VEN_PDF">		  <? print $nombre_ley; ?> </a>
          </div></td>
          <td align="center">
		  
		  <?
			
			if($_SESSION['tipo']!=1)
				{
			$var1=$pantalla->getporUrl($acciones['listar_articulos']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
				
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=listar articulos&amp;ley='.$codigo_otra_ley.'" alt="Listar Articulos"><img src="../plantillas/plantilla_admin/images/listar-articulo.png" width="19" height="17" border="0" alt="Listar"   /></a>';
			}
			}else{
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=listar articulos&amp;ley='.$codigo_otra_ley.'" alt="Listar Articulos"><img src="../plantillas/plantilla_admin/images/listar-articulo.png" width="19" height="17" border="0" alt="Listar"  /></a>';
			}
			?> 
			
					  <?
			
			if($_SESSION['tipo']!=1)
				{
			$var1=$pantalla->getporUrl($acciones['ver']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
				
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=ver ley&amp;id='.$codigo_otra_ley.'" alt="Ver Articulo"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="19" height="17" border="0" alt="Ver" /></a>';
			}
			}else{
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=ver ley&amp;id='.$codigo_otra_ley.'" alt="Ver Articulo"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="19" height="17" border="0" alt="Ver" /></a>';
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
				
			echo ' <a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=editar ley&amp;id='.$codigo_otra_ley.'" alt="Editar Articulo"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="19" height="17" border="0" alt="Editar"/></a>';
			}
			}else{
			echo ' <a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=editar ley&amp;id='.$codigo_otra_ley.'" alt="Editar Articulo"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="19" height="17" border="0" alt="Editar"/></a>';
			}
			?>
					  <?
			
			if($_SESSION['tipo']!=1)
				{
			$var1=$pantalla->getporUrl($acciones['eliminar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
				
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=eliminar ley&amp;id='.$codigo_otra_ley.'" alt="Eliminar Articulo"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="19" height="17" border="0" alt="Eliminar"/></a>';
			}
			}else{
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=eliminar ley&amp;id='.$codigo_otra_ley.'" alt="Eliminar Articulo"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="19" height="17" border="0" alt="Eliminar" /></a>';
			}
			?>

			<a href="modulos/leyes/otras_varias/documentos/<? echo $codigo_otra_ley?>/<? echo $pdf_ley ?>"  onclick="window.open('', 'VEN_PDF')" title="Ver PDF"  target="VEN_PDF">	<img src="../plantillas/plantilla_admin/images/impresora.gif" width="19" height="17" border="0" alt="Ver PDF"/></a>
			            
			</td>
        </tr>

        <? } ?>
        <tr class="tabla_inicio_fin">
          <td height="38" colspan="4" align="center" valign="bottom">
<?
if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
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
    } }

    if ($page == $pager->numPages) //ULTIMA PAGINA 
        echo "Siguiente"; 
    else            // SIGUIENTE PAGINA 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
}
?>			</td>
		</tr>
        <tr>
        	<td height="4">

			</td>	
		</tr>
        </table>
       
                    <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="20" /> 
                <input name="url" type="hidden" value="leyes" /> 
                <input name="tbl" type="hidden" value="<?php echo $tabla; ?>" /> 
                <input name="accion" type="hidden" value="listar" />
</table>
</form>