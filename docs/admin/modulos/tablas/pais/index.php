<?
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
if ($accion=="")
	{
		$accion="list";
	}
switch ($tabla) 
	{
		case "paises":
	    	include('../../lib/objetos/pais.php');
	 		switch ($accion) 
				{
					case "add":
						include('/cccol/lib/funciones/php/agregarpais.php');
						$tbl= 'tbl_paises_add.php'; 
	 				break;
					case "edit":
						$tbl= 'tbl_paises_edit.php'; 
	 				break ;
		case "eli":
		$tbl= 'tbl_paises_eli.php'; 
	 	break ;
		case "list":
		include( '../../lib/objetos/pager.php');
		$tbl= 'tbl_paises_list.php'; 
	 	break ;
		case "default":
		$tbl= 'tbl_paises_list.php'; 
	 	break ;
		}
	  break;
	  case"default":
		$tbl= 'tbl_principal.php';
	  break;
	   }
	   
	 

?>
<table width="59%" border="0" align="center" cellpadding="0" cellspacing="0" background="../../pais/modulos/login/media/bg.gif">
  <tr>
    <td><table width="72%" height="10" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="12"><img src="../../pais/modulos/principal/media/corner1.gif" width="12" height="11" /></td>
        <td colspan="4"><img src="../../pais/modulos/principal/media/linia_sup.gif" width="718" height="9" /></td>
        <td width="1%"><img src="../../pais/modulos/principal/media/corner2.gif" width="10" height="9" /></td>
      </tr>
      <tr>
        <td background="../../pais/modulos/principal/media/linea_izq.gif" style="background-repeat:repeat-y; background-position:left">&nbsp;</td>
        <td colspan="4" valign="top"><table width="100%" height="72%" border="0" align="center" cellpadding="2" cellspacing="2">
          <tr>
            <td width="26%" valign="top"><table width="163" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td><img src="../../pais/modulos/tablas/media/top_table.jpg" width="163" height="19" /></td>
              </tr>
              <tr>
                <td height="20" bgcolor="#E2D0D0" class="Estilo4 Estilo5">&nbsp;<a href="../../pais/?url=tablas&amp;tbl=paises">Paises</a></td>
              </tr>
              <tr>
                <td height="20" bgcolor="#F1F1F1" class="Estilo8">&nbsp;Estados</td>
              </tr>
              <tr>
                <td height="20" bgcolor="#E8D8D8" class="Estilo8">&nbsp;Localidades</td>
              </tr>
              <tr>
                <td height="20" bgcolor="#F1F1F1" class="Estilo8">&nbsp;Categorias De Titulos</td>
              </tr>
              <tr>
                <td height="20" bgcolor="#E8D8D8" class="Estilo8">&nbsp;Titulos Comparativos</td>
              </tr>
              <tr>
                <td height="20" bgcolor="#F1F1F1" class="Estilo8">&nbsp;Sectores</td>
              </tr>
              <tr>
                <td height="20" bgcolor="#E8D8D8" class="Estilo8">&nbsp;Tipos</td>
              </tr>
              <tr>
                <td><div align="center"><img src="../../pais/modulos/tablas/media/botton_table.jpg" width="163" height="17" /></div></td>
              </tr>
            </table>
</td>
            <td width="74%" valign="top"><table width="80%" height="251" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="18"><img src="modulos/tablas/media/<? print $tabla."_".$accion."_";?>top_table1.jpg" width="531" height="19" /></td>
                </tr>
                <tr>
                  <td height="215" valign="top" background="../../pais/modulos/tablas/media/bg.gif"><? include($tbl); ?></td>
                </tr>
                <tr>
                  <td><img src="../../pais/modulos/tablas/media/botton_table1.jpg" width="531" height="18" /></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
        <td height="263" background="../../pais/modulos/principal/media/linea_der.gif" style="background-repeat:repeat-y; background-position:right">&nbsp;</td>
      </tr>
      
      <tr>
        <td><img src="../../pais/modulos/principal/media/corner3.gif" width="12" height="9" /></td>
        <td colspan="4"><img src="../../pais/modulos/principal/media/linea_inf.gif" width="718" height="9" /></td>
        <td><img src="../../pais/modulos/principal/media/corner4.gif" width="10" height="9" /></td>
      </tr>
    </table></td>
  </tr>
</table>

