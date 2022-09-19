<?
		$page = $_GET['page']; 
		$limit = 10;  
		$var=$tipo_usuario->contarTipos();
		$total=$tipo_usuario->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page;  
		$paginador= 0;
		$filtro = $_GET['filtro']; 
		
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<form name="formTipoUser" id="formTipoUser" action="" method="get">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">

  <tr>
    <td height="1" colspan="3"></td>
  </tr>
  <tr>
    <td width="1%" height="32" class="Color_tabla" >&nbsp;</td>
    <td width="99%"  class="Color_tabla" >
	
		<?
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['agregar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=agregar" class="linkAgregar">Agregar Registro</a>';
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=agregar" class="linkAgregar">Agregar Registro</a>';
				}
				?>
                
  </td>
  </tr>
  <tr class=" Color_tabla">
    <td  >&nbsp;</td>
    <td rowspan="3" valign="top" ><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
            	  <label>
        	  <div style="padding-top:5px"><input type="text" name="filtro" id="filtro" /><input  name="Buscar" type="button" onclick="javascript:formTipoUser.submit()" value="Buscar"  />
        	  </div>
       	    </label>
      <tr class="tabla_inicio_fin">
        <td width="14%" height="24"><div align="center">Activar</div></td>
        <td width="44%"><div align="left">Tipo de Usuario </div></td>
		<td width="19%"><div align="center">Acciones</div></td>

	  <?
	  $var=$tipo_usuario->listarTipo( $offset, $limit, $filtro );
	  
	  while( list( $key, $val) = each($var) ) {
		            $Id_tipo = $var[$key][Id_tipo_Usuario];
					$Nombre_tipo = $var[$key][Nombre_tipo];
					
					$filtro = $var[$key][Nombre_tipo];
					$paginador =  $paginador +1;
	  
	  ?>
	  
        <tr class="<? if (($paginador%2)!=0){print 'trTblReg1';	}else {	print 'trTblReg2';	}?>">
      
      
        <td height="15"><div align="center">
          <label>
          <input type="checkbox" name="checkbox" value="checkbox" />
          </label>
        </div></td>
        <td align="center"><div align="left">&nbsp;&nbsp;<? print $Nombre_tipo; ?></div></td>
		<td align="center">
		
				<?
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['ver']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=ver&amp;id='.$Id_tipo.'"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=ver&amp;id='.$Id_tipo.'"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
				}
				?>
						<?
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['editar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=editar&amp;id='.$Id_tipo.'"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" /></a>'; 
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=editar&amp;id='.$Id_tipo.'"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" /></a>'; 	
				}		
				?>
				<?
		if ($Id_tipo!=1)
		{
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['eliminar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=eliminar&amp;id='. $Id_tipo.'"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" /></a>';
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&amp;accion=eliminar&amp;id='. $Id_tipo.'"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" /></a>';
				}
				?>
				
				</td>
<? } }?>

      <tr class="tabla_inicio_fin">
        <td colspan="3" align="center" valign="bottom">
<?

if ($paginador<10){echo("Registros Hallados: ");  echo($paginador);}
else	
{
	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
        echo "<a href=\"?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&page=" . ($page - 1) . "\"> Anterior </a>"; 

    for ($i = 1; $i <= $pager->numPages; $i++) { 
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
            echo "<a href=\"url=seguridad&amp;tbl=Tipos%20de%20Usuarios&page=$i\"> $i </a>"; 
    } 

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=seguridad&amp;tbl=Tipos%20de%20Usuarios&page=" . ($page + 1) . "\"> Siguiente </a>"; 
}
?>
		</td>
		</tr>
        <tr >
        	<td height="4" >
                <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="20" /> 
                <input name="url" type="hidden" value="seguridad" /> 
                <input name="tbl" type="hidden" value="<?php echo $tabla; ?>" /> 
                <input name="accion" type="hidden" value="listar" />
		</tr>
       </table>
       
</table>
</form>