<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<?php
	include ('../lib/objetos/Sectorlinks_interes.php');
	$Sectores=$SectorLinkI->SLinksDistinct();
	//contarLinksSector($sector);		CONTADOR POR SECTOR
	//ListarLinksISector($sector); 		LISTAR POR SECTOR

?>
<form name="formComparativosClausulas" id="formComparativosClausulas" action="" method="get">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td height="1" colspan="3"></td>
</tr>
<tr class="Color_tabla">
    <td width="100%" >
	</td>
</tr>
<tr class="Color_tabla">
    <td rowspan="3" valign="top" >
    	<table width="100%"  border="0" cellpadding="0" cellspacing="0"  >
			<tr class="tabla_inicio_fin" align="center"></tr>
		<?php 
			$_CantidadSectores= count($Sectores);
			$_anchocolumnas=100/$_CantidadSectores;
			print_r ($Sectores);
			$_ContFilas=0;
			//while ($fila = mysql_fetch_assoc($Sectores))
			while( list( $key, $val) = each($Sectores) ) 

				{
					$Sectores 	= $fila['Sector'];
					print "";
					if ($_ContFilas==0){
						$_ContFilas==1;
					}elseif($_ContFilas==1){
						$_ContFilas==0;
					}
					print "";
				}
				/*
				$page = $_GET['page']; 
				$limit = 20;  
	 			$var=$LinkI->contarLinks();
				$total=$LinkI->Total;
				$pager  = Pager::getPagerData($total, $limit, $page); 
				$offset = $pager->offset; 
				$limit  = $pager->limit; 
				$page   = $pager->page;  
				$filtro = $_GET['filtro'];
				$paginado= 0; */
			
		?>
	      	<tr class="tabla_inicio_fin" align="center"></tr>
		</table>
	</td>
</table>
</form>