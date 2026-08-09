<?
$page = $_GET['page']; 
$limit = 20;  
$var=$titulos->contarTitulos();
$total=$titulos->Total;
$pager  = Pager::getPagerData($total, $limit, $page); 
$offset = $pager->offset; 
//$limit  = $pager->limit; 
$page   = $pager->page;  
		$filtro = $_GET['filtro'];
		$paginador= 0;
include ('../lib/objetos/seguridad.php');
$t_usuario=$_SESSION["tipo"];
if($t_usuario!=1)
	{
		$var=$pantalla->getporUrl($url);
		$id_pantalla=$pantalla->id_pantalla; 
		$seg=$seguridad->getSeguridad($id_pantalla,$t_usuario);

if(!$seg)
	{
	?>
            <table width="100%" height="100%" border="0"  class="" cellpadding="0" cellspacing="0" >
              <tr>
                <td height="20%" >&nbsp;</td>
              </tr>
              <tr>
                <td height="10%"  bgcolor="#eeeeee" valign="middle" ><div align="center" class="tex3" ><font size="+3">No tiene Permiso para esta Secci&oacute;n</font></div></td>
              </tr>
              <tr>
                <td height="20%">&nbsp;</td>
              </tr>
            </table>
			<?php
			exit;
	}
}


?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<form name="formComparativosClausulas" id="formComparativosClausulas" action="" method="get">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td height="1" colspan="3"></td>
</tr>
<tr class="Color_tabla">
    <td width="1%" height="32"  >&nbsp;</td>
    <td width="99%" >
<?
	if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['agregar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
			if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
					echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=agregar" class="linkAgregar">Agregar Registro</a>';
				}
		}
	else
		{
			echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=agregar" class="linkAgregar">Agregar Registro</a>';
		}
?>
	</td>
</tr>
<tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" >
    	<table width="100%" border="0" cellpadding="0" cellspacing="0" >
             <label>
        	  <div style="padding-top:5px"><input type="text" name="filtro" id="filtro" /><input  name="Buscar" type="button" onclick="javascript:formComparativosClausulas.submit()" value="Buscar"  /> </div>
       	    </label>
      	<tr class="tabla_inicio_fin">
        	<td width="14%" height="24"><div align="center">Activar</div></td>
<!--			<td width="23%"><div align="left">Categoria</div></td>
 -->			<td width="44%"><div align="left">Nombre del Titulo</div></td>
			<td width="19%"><div align="center">Acciones</div></td>
      	</tr>

<?
		$var=$titulos->listarTitulos( $offset, $limit, $filtro );
	    while( list( $key, $val) = each($var) ) 
			{
		    	$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
				$nombre_titulo = $var[$key][nombre_titulo];
				$descripcion_titulo = $var[$key][descripcion_titulo];
				$codigo_categoria_titulo = $var[$key][codigo_categoria_titulo];
				$nombre_categoria=$categorias_titulos->getId($codigo_categoria_titulo);
				$nombre_categoria=$categorias_titulos->nombre_categoria;
				$paginador =  $paginador +1;
?>
        <tr class="<? 	if (($paginador%2)!=0)	{print 'trTblReg1';} else {print 'trTblReg2';}?>">
        			<td height="15"><div align="center"><label><input type="checkbox" name="checkbox" value="checkbox" /></label></div></td>
<!--					<td><div align="left">&nbsp;&nbsp;<? print $nombre_categoria; ?></div></td>
 -->					<td align="center"><div align="left">&nbsp;&nbsp;<? print $nombre_titulo; ?></div></td>
					<td align="center">		
<?
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['ver']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{
									echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=ver&amp;id='. $codigo_titulo_comparativo.'" ><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
								}
						}
					else
						{
							echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=ver&amp;id='.$codigo_titulo_comparativo.'" Alt="Ver Titulos"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
						}
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['editar']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{
									echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=editar&amp;id='.$codigo_titulo_comparativo.'" Alt="Editar Titulos"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" /></a>';
								}
						}
					else
						{
							echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=editar&amp;id='.$codigo_titulo_comparativo.'" Alt="Editar Titulos"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" /></a>';
						}
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['eliminar']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{				 
				 					 echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=eliminar&amp;id='. $codigo_titulo_comparativo.'" Alt="Eliminar Titulos"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" /></a>';
								 }	
						}
					else
						{
							echo '<a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=eliminar&amp;id='. $codigo_titulo_comparativo.'" Alt="Eliminar Titulos"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" /></a>';
				 		}
?>
					</td>
                    </tr>

<? 
			} 
?>
</table>
<table>
		<tr class="tabla_inicio_fin">
        	<td colspan="4" align="center" valign="bottom">
<? 		

if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{			
			if ($page == 1) // Esta es la primera pag
        		echo "Anterior"; 
    		else            // not the first page, link to the previous page 
        		echo "<a href=\"?url=tablas&tbl=Comparativo%20de%20Clausulas&page=" . ($page - 1) . "\"> Anterior </a>"; 
		    for ($i = 1; $i <= $pager->numPages; $i++) 
				{ 
					if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2))
						{
					        if ($i == $pager->page) 
					            echo " $i "; 
					        else 
					            echo "<a href=\"?url=tablas&tbl=Comparativo%20de%20Clausulas&page=$i\"> $i </a>"; 
    					} 
				}
			if ($page == $pager->numPages) // this is the last page - there is no next page 
        		echo "Siguiente"; 
    		else            // not the last page, link to the next page 
        		echo "<a href=\"?url=tablas&tbl=Comparativo%20de%20Clausulas&page=" . ($page + 1) . "\"> Siguiente </a>"; 
}
?>

			</td>
		</tr>
        <tr>
        	<td height="4">
                <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="10" /> 
                <input name="url" type="hidden" value="tablas" /> 
                <input name="tbl" type="hidden" value="<?php echo $tabla; ?>" /> 
                <input name="accion" type="hidden" value="listar" />
				</td>	
		</tr>
        </table>
       
    
</table>
</form>