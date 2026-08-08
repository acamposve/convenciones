<?
		$page = $_GET['page']; 
		$limit = 30;  
		$var=$user->contarUsuarios();
		$total=$user->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page;  
		$paginador= 0;
		$filtro = $_GET['filtro'];

?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<form name="formUser" id="formUser" action="" method="get">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="1" colspan="3"></td>
  </tr>
  <tr class=" Color_tabla">
    <td width="22" height="32" ></td>
    <td width="867" >
	
	
	<?
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['agregar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=agregar" class="linkAgregar" >Agregar Registro</a>';
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=agregar" class="linkAgregar" >Agregar Registro</a>';
				}
	?>
	
	
	</td>
  </tr>
  <tr class="Color_tabla"> 
      <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
            	  <label>
        	  <div style="padding-top:5px"><input type="text" name="filtro" id="filtro" /><input  name="Buscar" type="button" onclick="javascript:formUser.submit()" value="Buscar"  />
        	  </div>
       	    </label>
      <tr class="texto">

        <td width="25%">Tipo de Usuario </td>
        <td width="20%"><div align="left">Usuario</div></td>
        <td width="15%"><div align="left">Login</div></td>
        <td width="10%"><div align="left">Fecha V.</div></td>
        <td width="10%"><div align="left">Estatus</div></td>
		<td width="20%"><div align="center">Acciones</div></td>
      </tr>
	  <?
	  $var=$user->listarUsuarios( $offset, $limit, $filtro );
	  
	  while( list( $key, $val) = each($var) ) {
		            $id_usuarios = $var[$key][id];
		            $Login_usuarios = $var[$key][Login_usuario];
					$Nombre 	= $var[$key][Nombre_usuario];
					$tipo_us 	= $var[$key][Codigo_tipo_usuario];
					$tipo_us   	= $tipo_usuario->getId($tipo_us);
					$tipo_us   	= $tipo_usuario->nombre;
					$fech_venc	= $var[$key][fech_venc];;
					$estatus	= $var[$key][estatus];;
					$filtro = $var[$key][Nombre_usuario];
					$paginador =  $paginador +1;
	  ?>
	  
        <tr class="<? if (($paginador%2)!=0){print 'trTblReg1';	}else {	print 'trTblReg2';	}?>">
        <td ><? print $tipo_us; ?></td>
        <td><div align="left">&nbsp;&nbsp;<? print $Nombre; ?></div></td>
        <td ><div align="left">	<?php print $Login_usuarios  ?></div></td>
        <td ><div align="center"><?php print $fech_venc  ?></div> </td>
        <td><div align="center">
			<?php 
				if ($estatus=="HAB"){
					print "Habilitado";
				}elseif($estatus=="BLO"){
					print "Bloqueado";
				}else{
					print "N.D.";
				}
			  ?>
              </div> 
		</td>
		<td align="center" >
		
			<?
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['ver']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=ver&amp;id='.$id_usuarios.'"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=ver&amp;id='.$id_usuarios.'"><img src="../plantillas/plantilla_admin/images/boton_buscar.gif" width="28" height="28" border="0" Alt="Ver" /></a>';
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
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=editar&amp;id='.$id_usuarios.'"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" /></a>';
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=editar&amp;id='.$id_usuarios.'"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar" /></a>';
				}
				?>
				
	<?
		if($_SESSION["tipo"]!=1)
		{
			$var1=$pantalla->getporUrl($acciones['eliminar']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    			{
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=eliminar&amp;id='.$id_usuarios.'"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" /></a>';
				}
				}else{
				echo '<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=eliminar&amp;id='.$id_usuarios.'"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar" /></a>';
				}
				
				?></td>
      </tr>
<? } ?>

      <tr class="texto">
        <td colspan="6" align="center" valign="bottom">
<? 
if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{
	if ($page == 1) // Esta es la primera pag
        echo "Anterior"; 
    else            // not the first page, link to the previous page 
        echo "<a href=\"?url=seguridad&amp;tbl=Usuarios&page=" . ($page - 1) . "\"> Anterior </a>"; 

    for ($i = 1; $i <= $pager->numPages; $i++) { 
        
        if ($i == $pager->page) 
            echo " $i "; 
        else 
            echo "<a href=\"?url=seguridad&amp;tbl=Usuarios&page=$i\"> $i </a>"; 
    } 

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=seguridad&amp;tbl=Usuarios&page=" . ($page + 1) . "\"> Siguiente </a>"; 
}
?>
		</td>
		</tr>
        <tr>
        	<td height="5">
                <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="20" /> 
                <input name="url" type="hidden" value="seguridad" /> 
                <input name="tbl" type="hidden" value="<?php echo $tabla; ?>" /> 
                <input name="accion" type="hidden" value="listar" />
				<font class='tex3'> ND : No Definido</font>

                </td>	
		</tr>
        </table>

</table>
</form>
