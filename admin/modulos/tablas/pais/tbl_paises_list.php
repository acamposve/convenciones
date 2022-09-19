<?
$page = $_GET['page']; 
$limit = 20;  
$var=$pais->contarPaises();
$total=$pais->Total;
$pager  = Pager::getPagerData($total, $limit, $page); 
$offset = $pager->offset; 
$limit  = $pager->limit; 
$page   = $pager->page;  
$filtro = $_GET['filtro'];
$paginado= 0;
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
<link href="/cccol/plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<form name="formPaises" id="formPaises" action="" method="get">
<table width="100%" height="183" border="1" cellpadding="0" cellspacing="0" >
<tr>
	<td colspan="2">
    	<table>
  		<tr>
    		<td><input type="text" name="filtro" id="filtro" /></td>
    		<td>&nbsp;</td>
    		<td><input  name="Buscar" type="button" onclick="javascript:formPaises.submit()" value="Buscar"  /></td>
    		<td>&nbsp;</td>
    		<td><a href="?url=tablas&amp;tbl=Paises&amp;accion=agregar" class="linkAgregar">Agregar Registro</a></td>
  		</tr>
		</table></td>
</tr>
<tr class="tabla_inicio_fin">
			<td width="54%"><div align="left">Nombre Pais </div></td>
			<td width="19%"><div align="center">Acciones</div></td>
      	</tr>
<?
		$var=$pais->listarPais( $offset, $limit, $filtro );
	    while( list( $key, $val) = each($var) ) 
			{
		    	$Id_pais = $var[$key][Id_pais];
				$Nombre_pais = $var[$key][Nombre_pais];
				$filtro = $var[$key][Nombre_pais];
				$paginador =  $paginador +1;
?>
				<tr class="<? if (($paginador%2)!=0){print 'trTblReg1';	}else {	print 'trTblReg2';	}?>">
					<td align="left" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? print $Nombre_pais; ?> </td>
					<td align="center" >
<?
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['ver']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{
									echo '<a href="?url=tablas&amp;tbl=Paises&amp;accion=ver&amp;id='.$Id_pais.'"  Alt="Ver Pais"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
								}
						}
					else
						{
							echo '<a href="?url=tablas&amp;tbl=Paises&amp;accion=ver&amp;id='.$Id_pais.'" Alt="Ver Pais"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
						}
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['editar']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{
									echo '<a href="?url=tablas&amp;tbl=Paises&amp;accion=editar&amp;id='.$Id_pais.'" Alt="Editar Pais"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" /></a>';
								}
						}
					else
						{
							echo '<a href="?url=tablas&amp;tbl=Paises&amp;accion=editar&amp;id='.$Id_pais.'" Alt="Editar Pais"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar"  /></a>';
						}
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['eliminar']);
							$id_pantalla=$pantalla->id_pantalla; 
							$pantalla->Limpiar();
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{
									echo '<a href="?url=tablas&amp;tbl=Paises&amp;accion=eliminar&amp;id='.$Id_pais.'" Alt="Eliminar Pais"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar"  /></a>';
								}
						}
					else
						{
							echo '<a href="?url=tablas&amp;tbl=Paises&amp;accion=eliminar&amp;id='.$Id_pais.'" Alt="Eliminar Pais"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" /></a>';
						}
?>
					</td>
      			</tr>      
<? 
			} 
?>
		<tr class="tabla_inicio_fin">
        	<td colspan="3" align="center" valign="bottom">
<? 
			if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
			else
				{
					if ($page == 1) // Esta es la primera pag
    		    		echo "Anterior"; 
		    		else            // not the first page, link to the previous page 
        				echo "<a href=\"?url=tablas&tbl=Paises&page=" . ($page - 1) . "\"> Anterior </a>"; 
		    		for ($i = 1; $i <= $pager->numPages; $i++) 
						{ 
			        		if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2))
								{
					        		if ($i == $pager->page) 
					            		echo " <strong>$i</strong> "; 
					        		else 
					            		echo "<a href=\"?url=tablas&tbl=Paises&page=$i\"> $i </a>"; 
					    		} 
						}
		    		if ($page == $pager->numPages) // this is the last page - there is no next page 
        				echo "Siguiente"; 
		    		else            // not the last page, link to the next page 
        				echo "<a href=\"?url=tablas&tbl=Paises&page=" . ($page + 1) . "\"> Siguiente </a>"; 
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
</form>