
  <?
session_start();
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$filtro = $_GET['filtro'];		
		$limit = 20;  
		$var=$discusion->contarDiscusiones();
		$total=$discusion->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page; 
		$desc	="contratos";

session_start();
$t_usuario=$_SESSION["tipo"];
$login=$_SESSION["login"];

		$bt_login=$user->getId($login);
		$empresa   = $user->codigo_empresa; 
?>
<script  type="" language="" runat=server >

function abrirVentanaHistorico($id){
window.open("../../admin/modulos/contratos/discusion/discusion_contratos_historico.php?dato="+escape($id),"ventanaHistorico"," scrollbars=yes, resizable=1, location=yes");
}
-->
</script>
  <link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
 
<form name="formdiscuciones" id="formdiscuciones" action="" method="get">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="Color_tabla">
  <tr height="1">
    <td></td>
    <td></td>

  </tr>
  <tr class="Color_tabla">
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><a href="?url=contratos&amp;tbl=Bitacora&amp;accion=agregar" class="linkAgregar">Agregar Registro</a>
  </tr>
  <tr>


    <td height="113">&nbsp;</td>
    
    <td rowspan="3" valign="top" ><table width="100%" 	 border="0" cellpadding="0" cellspacing="0" >
	<label>
    	<div style="padding-top:5px"><input type="text" name="filtro" id="filtro" /><input  name="Buscar" type="button" onclick="javascript:formdiscuciones.submit()" value="Buscar"  /></div>
	</label>    

        <tr class="tabla_inicio_fin">
          <td width="40%"><div align="left">Empresa</div></td>
          <td width="10%"><div align="left">Fecha Inicio </div></td>
          <td width="10%"><div align="left">Status</div></td>
          <td width="40%"><div align="left">Acciones</div></td>
        </tr>

        <?
	  $var=$discusion->listarDiscusiones( $offset, $limit, $filtro,$empresa);
		if (count($var)==0)
		{	print "<br>";}
	  while( list( $key, $val) = each($var) ) {
		            $codigo_bitacora = $var[$key][codigo_bitacora];
					$codigo_empresa = $var[$key][codigo_empresa];
					$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);
					$fecha_inicio_disc = $var[$key][fecha_inicio_disc];
					$estatus_bitacora = $var[$key][estatus_bitacora];
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
          <td height="47" valign="top">&nbsp;
          <?
					if ($estatus_bitacora==10){
						if($_SESSION['tipo']==1){
							?>
				          	<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=reabrir&amp;id=<? print $codigo_bitacora;?>" title="Modificar Status Discución">
							<? print $nombre_empresa; ?></a> 
	                        <?
							}else {
								print $nombre_empresa;
								}
						}
		  			else {
						if($_SESSION['tipo']==1){
							?>
				          	<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=cerrar&amp;id=<? print $codigo_bitacora;?>" title="Cerrar Discución">
							<? print $nombre_empresa; ?></a>
                           
					<?
							}else {
								print $nombre_empresa;
								}
                    }
		?>		</td>
          <td valign="top"><? print $fecha_inicio_disc; ?></td>
          <td valign="top" align="left">
		  		<? 
					if ($estatus_bitacora==10)
						{print "Cerrado";
						}
		  			elseif($estatus_bitacora==1) {
						print "Modificación";
						}
		  			elseif($estatus_bitacora=="") {
						print "Pendiente";
						}
		  	
					 ?>        </td>
          <td valign="top" align="center">
		  <a href="#" title="Historico de Reuniones" onclick="abrirVentanaHistorico(<? print $codigo_bitacora ?> )">
          	<img src="../plantillas/plantilla_admin/images/boton_historico.png" alt="Historico de Bitacora"  border="0" width="28" height="28" /></a>          
		  <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=listar_acuerdos&amp;id=<? print $codigo_bitacora;?>" width="28" height="28">
          	<img src="../plantillas/plantilla_admin/images/boton_reuniones.png" alt="Listar Acuerdos"  border="0" width="28" height="28" /></a>
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=listar_reuniones&amp;id=<? print $codigo_bitacora;?>" width="28" height="28" >
          	<img src="../plantillas/plantilla_admin/images/boton_acuerdos.png" alt="Listar Reuniones"  border="0"  width="28" height="28"/></a>          
		  <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=listar_ofertas&amp;id=<? print $codigo_bitacora;?>">
          		<img src="../plantillas/plantilla_admin/images/listar-anexo.png" alt="Listar Ofertas"  border="0" width="28" height="28" /></a> 
          <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=listar_peticiones&amp;id=<? print $codigo_bitacora;?>">
          <img src="../plantillas/plantilla_admin/images/listar-articulo.png" alt="Listar Peticiones"  border="0" width="28" height="28" /></a>
          	
			<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=ver&amp;id=<? print $codigo_bitacora;?>">
            	<img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Contrato"  border="0" width="28" height="28" /></a> 
			<?	if ($estatus_bitacora!=10)
				{
				?>
	            <a href="?url=contratos&amp;tbl=Bitacora&amp;accion=editar&amp;id=<? print $codigo_bitacora;?>">
            	<img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar"border="0" width="28" height="28" /></a> 
				<a href="?url=contratos&amp;tbl=Bitacora&amp;accion=eliminar&amp;id=<? print $codigo_bitacora;?>">
            	<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar"  border="0"  width="28" height="28" /></a>
				<? 
				}
				?>		</td>
        </tr>

        <?  } 
		
		?>
        <tr class="tabla_inicio_fin">
          <td colspan="6" align="center" valign="bottom"><?
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
    } 
	}

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "Siguiente"; 
    else            // not the last page, link to the next page 
        echo "<a href=\"?url=".$desc."&tbl=".$tbl."&page=" . ($page + 1) . "\"> Siguiente </a>"; 
	}
?></td>
        </tr>
        <tr>
        <td height="25">        </td>
        </tr>
        
                <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="20" /> 
                <input name="url" type="hidden" value="contratos" /> 
                <input name="tbl" type="hidden" value="Discusion de contratos" /> 
            
      </table></td>

</table>
</form>