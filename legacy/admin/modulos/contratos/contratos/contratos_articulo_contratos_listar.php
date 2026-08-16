<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<form name="formcontratos_clausulas" id="formcontratos_clausulas" action="" method="get">
<?
session_start();
$tbl=$_GET['tbl'];
$codigo_contrato= $_GET['id'];
$page = $_GET['page']; 
$filtro = $_GET['filtro'];
$limit  = 20;
$total=$contratos->contarArticulosContratos($codigo_contrato);
$pager  = Pager::getPagerData($total, $limit, $page); 
$offset = $pager->offset; 
$page   = $pager->page; 
$paginador= 0;
$Contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
while( list( $key, $val) = each($Contr) )
	{
		$codigo_contrato = $Contr[$key][codigo_contrato];
		$nombre_empresa = $Contr[$key][nombre_empresa];
	}
?>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<?php
if($_SESSION["tipo"]==1)
	{
?>
		<tr>
			<td width="100%" height="30"  class="Color_tabla" ><a href="?url=contratos&amp;tbl=Contratos&amp;accion=agregar_articulos&id=<? print $codigo_contrato; ?>" class="linkAgregar">AGREGAR REGISTRO</a></td> 
		</tr>
<?php
	}
?>
<tr>
	<td rowspan="3" valign="top" class="">
		<table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
		<label>
        	<div style="padding-top:5px">&nbsp;&nbsp;
			<input type="text" name="filtro" id="filtro" /> <input  name="Buscar" type="button" onclick="javascript:formcontratos_clausulas.submit()" value="Buscar"  />
		</div>
		</label>
        <tr><td colspan="4"> <center><?print $nombre_empresa;?></center></td></tr>
        <tr class="tabla_inicio_fin">
          <td width="1%" height="24"></td>
          <!--
          <td width="10%" class="tabla_inicio_fin"><div align="left">Publicar</div></td>
          -->
          <td width="13%" class="tabla_inicio_fin"><div align="left">&nbsp;&nbsp;Nro Clausula </div></td>
          <td width="50%" class="tabla_inicio_fin"> <div align="left">Titulo Clausula </div></td>
          <td width="25%" class="tabla_inicio_fin"><div align="center">Acciones</div></td>
        </tr>

		<?

$var=$contratos->listarArticulosContratos( $codigo_contrato, $offset, $limit, $filtro);
		
		while( list( $key, $val) = each($var) ) {
					
				$codigo_articulo = $var[$key][codigo_articulo];
				$nro_articulo = $var[$key][nro_articulos];
				$titulo_comparativo = $var[$key][codigo_titulo_comparativo];
				$titulo_articulo = $var[$key][titulo_articulo];
 
				$articulo_cerrado=$var[$key][articulo_cerrado];
				$valor_titulo = $titulos -> getId($titulo_comparativo);
				$paginador =  $paginador +1;	
				if(!empty($nombre_titulo)){
				$nombre_titulo = $titulos->nombre_titulo;
				}else{
				$nombre_titulo = "Ninguna";
				}
?>
        <tr class="<? if (($paginador%2)!=0){print 'trTblReg1';	}else {	print 'trTblReg2';	}?>">
        
          <td height="15"><div align="center">
              <label></label>
          </div></td>
          <!--
          <td align="center"><div align="left">&nbsp;<input type="checkbox" name="publicacion" value="<? print $codigo_contrato ?>" <? if ($status_publicacion=='publicado'){?>checked="checked"<? } ?> /></div></td>
			-->
          <td><a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_articulo&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" title="Ver Clausula Contrato" >&nbsp; <? print $nro_articulo; ?></a></td>
          <td align="left"><a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_articulo&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" title="Ver Clausula Contrato" ><? print $titulo_articulo; ?></a></td>
          
          <td align="center" ><a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_articulos&amp;id=<? print $codigo_contrato;?>"></a> 
        <a href="?url=contratos&amp;tbl=Contratos&amp;accion=ver_articulo&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>">
          	<img src="../plantillas/plantilla_admin/images/boton_buscar.gif" alt="Ver Clausulas  Contrato" width="28" height="28" border="0" /> </a> 

			<?
			if($_SESSION["tipo"]!=1)
				{
					$var1=$pantalla->getporUrl($acciones['contratos_articulo_contratos_eliminar.php']);
					$id_pantalla=110;
					$pantalla->Limpiar();
					if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
   						{
						?>
                          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_articulos&amp;id=<? print $codigo_articulo;?>" >
                          <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" border="0"   width="28" height="28"/></a>						
						<?
	                   	}
				}
			else
				{
				?>
                      <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar_articulos&amp;id=<? print $codigo_articulo;?>" >
                      <img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" border="0"  width="28" height="28"/></a>
          		<?
               }            

			if($_SESSION["tipo"]!=1)
				{
					$var1=$pantalla->getporUrl($acciones['contratos_articulo_contratos_editar.php']);
					$id_pantalla=108;
					$pantalla->Limpiar();
					if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
   						{
						?>
                          <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar_articulos&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" >
                          <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" border="0"   width="28" height="28"/></a>
						<?
	                   	}
				}
			else
				{
				?>
                      <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar_articulos&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" >
                      <img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" border="0"   width="28" height="28"/></a>
          		<?
               }            
           ?>       
          
            
            
       <? 
       if ($_SESSION['tipo']!=1){
	   		if ($articulo_cerrado==0)
				{
				?>
                	<a href="?url=contratos&amp;tbl=Contratos&amp;accion=impresion_articulos&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" >
					<img  src="../plantillas/plantilla_admin/images/impresora.gif" alt="Imprimir Clausula Contrato" width="28" height="28" border="0" /> </a>
                <?
				}
		}else{
			?>
			<a href="?url=contratos&amp;tbl=Contratos&amp;accion=impresion_articulos&amp;id=<? print $codigo_articulo;?>&page=<? print $page;?>" >
    	      <img  src="../plantillas/plantilla_admin/images/impresora.gif" alt="Imprimir Clausula Contrato" width="28" height="28" border="0" /> </a>
		<?  
		}
       	?>
        
          
          </td>
        </tr>

        <?  } ?>
        <tr class="tabla_inicio_fin">
        <td></td>
          <td colspan="6" align="center" valign="bottom">
<? 

if (($paginador<20) and ($page<2)){echo("Registros Hallados: ");  echo($paginador);}
else
{
			if ($page == 1) // Esta es la primera pag
    		    echo "Anterior"; 
		    else            // not the first page, link to the previous page 
        		echo "<a href=\"?url=contratos&tbl=Contratos&accion=listar_articulos&id=".$codigo_contrato."&page=" . ($page - 1) . "\"> Anterior </a>"; 
		    for ($i = 1; $i <= $pager->numPages; $i++) 
				{ 
			        if($pager->page>=(($i)-5)&&$pager->page<=(($i)+5))
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
?></td>
        </tr>
      </table></td>

  </tr>
  <tr>

  </tr>
  <tr>


  </tr>
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
                <input name="inicio" type="hidden" value="0" />            	
                <input name="fin" type="hidden" value="20" /> 
                <input name="url" type="hidden" value="contratos" /> 
                <input name="tbl" type="hidden" value="Contratos" /> 
                <input name="accion" type="hidden" value="listar_articulos" />
                <input name="id" type="hidden" value="<? print $codigo_contrato ?>" />
</form>