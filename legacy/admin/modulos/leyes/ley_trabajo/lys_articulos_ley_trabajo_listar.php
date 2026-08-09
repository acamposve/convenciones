<?php
$tbl=$_GET['tbl'];
$page = $_GET['page']; 
$limit = 20;  
$var=$ley_trabajo->contarArticulos();
$total=$ley_trabajo->Total;
$pager  = Pager::getPagerData($total, $limit, $page); 
$offset = $pager->offset; 
$limit  = $pager->limit; 
$page   = $pager->page;  
$filtro = $_GET['filtro'];
$paginador= 0;
$desc="leyes";
$var=$ley_trabajo->get_Ley_Trabajo();
$pdf_asociado=$ley_trabajo->pdf_asociado;
?>
<script  type="" language="">
<!--
function VentanaPDF()
	{
		window.open("modulos/leyes/ley_trabajo/media/<? print $pdf_asociado;?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");
	}
-->
</script>
<link href="../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<form name="formListarArticulos" id="formListarArticulos" action="" method="get">

<?php
	if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['ver']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
			if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
				{
?>
					<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=ver ley"class="linkAgregar">Consultar Datos LOTT</a>
<?php
				}
		}
	else
		{
?>
			<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=ver ley" class="linkAgregar">Consultar Datos LOTTT</a>
<?php
		}
	if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['editar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
					{
?>
						<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=editar ley" class="linkAgregar">Editar Datos LOTTT</a>
<?php
					}
		}
	else
		{
?>
			<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=editar ley" class="linkAgregar">Editar Datos LOTTT</a>
<?php
		}
	if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['agregar_articulo']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
					{
?>
						<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=agregar" class="linkAgregar">Agregar Articulo</a>
<?php
					}
		}
	else
		{
?>
			<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=agregar" class="linkAgregar">Agregar Articulo</a>
<?php
		}
?>

		<table border="1" style="width:780px;" cellpadding="0" cellspacing="0">
        <tr><td>
		<label>
		<label>
		<div style="padding-top:5px">
		<input type="text" name="filtro" id="filtro" />
		<input  name="Buscar" type="button" onclick="javascript:formListarArticulos.submit()" value="Buscar"    />
		<table>
		  <tr>
		    <td><label>
		      <input type="radio" name="tipoBusqueda" value="1" id="tipoBusqueda_0" />Titulos Artículos</label></td>
	      </tr>
		  <tr>
		    <td><label>
		      <input type="radio" name="tipoBusqueda" value="2" id="tipoBusqueda_1" />Nro Artículos</label></td>
	      </tr>
		  </table>
		<img onclick="VentanaPDF()" src="../plantillas/plantilla_admin/images/impresora.gif" width="25" height="25"  border="0"  Alt="Ver PDF" />
		</td>
		</div></label>
        </tr>
        </table>
<table border="1" style="width:780px;" cellpadding="0" cellspacing="0">
		<tr class="tabla_inicio_fin">
			<td width="2%" height="24">&nbsp;</td>
			<td width="10%"><div align="left">Nro Art</div></td>
			<td width="40%"><div align="left">Titulo del Art&iacute;culo </div></td>
			<td width="18%"><div align="center">Acciones</div></td>
		</tr>
<?php
		$var=$ley_trabajo->listarArticulos( $offset, $limit, $filtro, $_REQUEST["tipoBusqueda"]);
		while( list( $key, $val) = each($var) )
			{
				$nro_articulo = $var[$key][nro_articulo];
				$resumen_articulo = $var[$key][resumen_articulo];
				$titulo_articulo = $var[$key][titulo_articulo];
				$resumen= substr($resumen_articulo,0,60);
				$codigo_titulo_comparativo = $var[$key][codigo_titulo_comparativo];
				$titulo_comparativo=$titulos->getId($codigo_titulo_comparativo);
				$titulo_comparativo=$titulos->nombre_titulo;
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
					<td height="15"><div align="center"></label></div></td>
					<td align="center"><div align="left">&nbsp;<? print $nro_articulo; ?> </div></td>
					<td><div align="left"> <a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=ver&amp;id=<? echo $nro_articulo ?>&amp;page=<? print $page ?>" title="Ver"> <? print $titulo_articulo; ?> </a> </div></td>
					<td align="center">
<?php
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['ver_articulo']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
								{
?>
									<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=ver&amp;id=<? echo $nro_articulo ?>&page=<? print $page ?>"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" title="Ver" /></a>
<?php
								}
						}
					else
						{
?>
							<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=ver&amp;id=<? echo $nro_articulo ?>&page=<? print $page ?>"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" title="Ver" /></a>
<?php
						}
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['editar_articulo']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
								{
?>
									<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=editar&amp;id=<? echo $nro_articulo ?>&page=<? print $page ?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" title="Editar" /></a>
<?php
								}
						}
					else
						{
?>
							<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=editar&amp;id=<? echo $nro_articulo ?>&page=<? print $page ?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" title="Editar" /></a>
<?php
						}
					if($_SESSION['tipo']!=1)
						{
							$var1=$pantalla->getporUrl($acciones['eliminar_articulo']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
								{
?>
									<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=eliminar&amp;id=<? echo $nro_articulo ?>&page=<? print $page ?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" title="Eliminar" /></a>
<?php
								}
						}
					else
						{
?>
							<a href="?url=<?php echo $_GET['url']; ?>&amp;tbl=<?php echo $_GET['tbl']; ?>&amp;accion=eliminar&amp;id=<? echo $nro_articulo ?>&page=<? print $page ?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" title="Eliminar" /></a>
<?php
						}
?>
					</td>
				</tr>
<? 
			}
?>
</table>
<table style="width:780px;">
<tr class="tabla_inicio_fin">
	<td colspan="5" align="center" valign="bottom">
<?
	if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
	else
		{
			if ($page == 1) // Esta es la primera pag
			echo "Anterior"; 
			else            // not the first page, link to the previous page 
			echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=" . ($page - 1) . "\"> Anterior </a>"; 
			for ($i = 1; $i <= $pager->numPages; $i++)
				{ 
					if($pager->page>=(($i)-15)&&$pager->page<=(($i)+15))
						{
							if ($i == $pager->page) 
								echo " $i "; 
							else 
								echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=$i\"> $i </a>"; 
						} 
				}
			if ($page == $pager->numPages) //ULTIMA PAGINA 
				echo "Siguiente"; 
			else            // SIGUIENTE PAGINA 
				echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
		}
?>
	</td>
</tr>
<tr>
	<td><input name="inicio" type="hidden" value="0" />
    <input name="fin" type="hidden" value="20" />
    <input name="url" type="hidden" value="leyes" />
    <input name="tbl" type="hidden" value="<?php echo $tabla; ?>" />
    <input name="accion" type="hidden" value="listar" />
    <input name="TIPO_BUSQUEDA" type="hidden" value="<?php echo $busqueda; ?> " /></td>
</tr>
</table>
</form>