<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<?php
	include ('../lib/objetos/Sectorlinks_interes.php');
	$Sectores=$SectorLinkI->SLinksDistinct();
	//contarLinksSector($sector);		CONTADOR POR SECTOR
	//ListarLinksISector($sector); 		LISTAR POR SECTOR

?>
	
<form name="formComparativosClausulas" id="formComparativosClausulas" action="" method="get">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
	<tr class="Color_tabla" valign="top" >
        <td rowspan="3" valign="top" >
            <table width="100%"  border="0" cellpadding="0" cellspacing="0"  >
                <tr class="tabla_inicio_fin" align="center"></tr>
                <tr valign="top" >
            <?php 
				$_LimiteRegistros=10;
                $_CantidadSectores= count($Sectores);
                $_anchocolumnas=100/$_CantidadSectores;
                $_HtmlPaginador="";
				$_FranjaDivision="<td bgcolor='#000000' width='2px'></td>";
				print $_FranjaDivision;
                while( list( $key, $val) = each($Sectores) ) 
                    {
                        $UnidadSector	= $Sectores[$key]['Sector'];
                        if ($UnidadSector==1){
                            $NombreSector="Sector Sindical";
                        }elseif($UnidadSector==2){
                            $NombreSector="Sector Gobierno";
                        }elseif($UnidadSector==3){
                            $NombreSector="Sector Empresarial";
                            }
                            print "<td valign='top' width='".$_anchocolumnas."%' bordercolor='#000000' >&nbsp;&nbsp;".$NombreSector."<br>";
                                print "<table width='100%' border='0' cellpadding='10' >";
                                   	if (($UnidadSector==$_GET['SectorCambio'])&&($_GET['page'])){
                                        $page = $_GET['page']; 
									}else{
									    $page =0; 
										$paginador=0;
									}
                                        $limit = $_LimiteRegistros;  
                                        $SectorLinkI->contarLinksSector($UnidadSector);
                                        $TotalLinksPorSector=$SectorLinkI->TotalLinksSector;
                                        $pager  = Pager::getPagerData($TotalLinksPorSector, $limit, $page); 
                                        $offset = $pager->offset; 
                                        $limit  = $pager->limit; 
                                        $page   = $pager->page;  
                                        $paginador= 0;
						                $_ContFilas=1;
                                        $LinksAsociados=$SectorLinkI->ListarLinksISector($offset, $limit,"",$UnidadSector);
                                        while( list( $llave, $val) = each($LinksAsociados) ) 
                                            {
												if ($_ContFilas==0){
													$_ContFilas=1;
													$class="class='trTblReg1'";
												}elseif($_ContFilas==1){
													$_ContFilas=0;
													$class="class='trTblReg2'";
													}
											
                                                print "<tr $class>";
	                                                print "<td >";
													$lit_IdLink		= $LinksAsociados[$llave]['lit_IdLink'];
													$lit_Sector		= $LinksAsociados[$llave]['lit_Sector'];
													$lit_Detalle	= $LinksAsociados[$llave]['lit_Detalle'];
													$lit_Link		= $LinksAsociados[$llave]['lit_Link'];
    	                                            	print "<a href='$lit_Link' target='_blank'>".$lit_Detalle."</a>";
	                                                print "</td>";
                                                print "</tr>";
                                                $paginador =  $paginador +1;
                                            }
									//FOR PARA COMPLETAR EL INTERLINEADO
 									if (count($LinksAsociados)<=$_LimiteRegistros){
										$_TDS=0;
										for ($_TDS=count($LinksAsociados);$_TDS<=(10-count($LinksAsociados)); $_TDS+=1){
												if ($_ContFilas==0){
													$_ContFilas=1;
													$class="class='trTblReg1'";
												}elseif($_ContFilas==1){
													$_ContFilas=0;
													$class="class='trTblReg2'";
													}
                                                print "<tr $class><td>&nbsp;</td></tr>";
											} 
									//FOR PARA COMPLETAR EL INTERLINEADO
											if ($_TDS<=10){
												for ($_TDS2=$_TDS;$_TDS2<=9; $_TDS2+=1){
													if ($_ContFilas==0){
														$_ContFilas=1;
														$class="class='trTblReg1'";
													}elseif($_ContFilas==1){
														$_ContFilas=0;
														$class="class='trTblReg2'";
														}
	                                                print "<tr $class><td>&nbsp;</td></tr>";
													}
											}										
										}
                                print "</table>";
                                $_HtmlPaginador.="<td class='tabla_inicio_fin' width='".$_anchocolumnas."%' bordercolor='#000000'>";
                                if (($paginador<$_LimiteRegistros) and ($page<2)){$_HtmlPaginador.="Registros Hallados: ".$paginador;}
                                else
                                {
                                            if ($page == 1) // Esta es la primera pag
                                                $_HtmlPaginador.= "Anterior"; 
                                            else            // not the first page, link to the previous page 
                                                $_HtmlPaginador.= "<a class='tabla_inicio_fin' href=\"?url=tablas&tbl=Links de Interes&accion=Front2&SectorCambio=$UnidadSector&page=".($page-1)."\"> Anterior </a>"; 
                                            for ($i = 1; $i <= $pager->numPages; $i++) 
                                                { 
                                                    if($pager->page>=(($i)-2)&&$pager->page<=(($i)+2))
                                                        {
                                                            if ($i == $pager->page) 
                                                                $_HtmlPaginador.=" <strong>$i</strong> "; 
                                                            else 
                                                                $_HtmlPaginador.="<a href=\"?url=tablas&tbl=Links de Interes&accion=Front2&SectorCambio=$UnidadSector&page=$i\"> $i </a>"; 
                                                        } 
                                                }
                                            if ($page == $pager->numPages) // this is the last page - there is no next page 
                                                $_HtmlPaginador.= "Siguiente"; 
                                            else            // not the last page, link to the next page 
                                                $_HtmlPaginador.= "<a href=\"?url=tablas&tbl=Links de Interes&accion=Front2&SectorCambio=$UnidadSector&page=".($page+1)."\"> Siguiente </a>";                              
											}
                                $_HtmlPaginador.= "</td>";
								$_HtmlPaginador.= $_FranjaDivision;
                            print "</td>";
							print $_FranjaDivision;
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
                </tr>
                <tr  align="center" >
								<?php
                                    print $_FranjaDivision.$_HtmlPaginador;
                                ?>                            	
                    </td>
                </tr>
            </table>
        </td>
</table>
</form>