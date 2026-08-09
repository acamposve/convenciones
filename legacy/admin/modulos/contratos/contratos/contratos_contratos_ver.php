<?
$codigo_contrato=$_GET['id'];
$var=$contratos->obtenerContratosPorId($codigo_contrato);
$pdf=$contratos->pdf_auto;
$duracion=$contratos->duracion;
$inicio=$contratos->fecha_inicio;
$termino=$contratos->fecha_termino;
$ambito=$contratos->ambito_aplicacion;
$empresa=$contratos->codigo_empresa;
?>
<script  type="" language="">
<!--
function VentanaPDF(){
window.open("modulos/contratos/contratos/documentos/<? print $codigo_contrato?>/<?print $pdf?>","ventana1","width=800,height=600, scrollbars=yes, resizable=yes");
}
-->
</script>
<style type="text/css">
<!--
.style1 {font-size: 9px}
-->
</style>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" >
<tr  >
    <td width="1%" height="32" ></td>
    <td width="99%" ></td>
</tr>
<tr >
    <td height="119" > </td>
  <td rowspan="3" valign="top" >
    	<form name="form1" method="post" action="" enctype="multipart/form-data">
	    <table width="100%" height="100%"  class="trTblReg1">

        <tr>
          	<td height="16">Fecha de Inicio: <span class="style1">(dd/mm/yyyy) </span></td>
          	<td>Fecha de Termino: <span class="style1">(dd/mm/yyyy) </span></td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td height="20"><input name="inicio" type="text"  id="inicio" readonly="" value="<? print  $inicio ?>" size="15" /></td>
          	<td height="20"><input name="termino" type="text"  id="termino" readonly="" value="<? print $termino ?>" size="15" /></td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td>Ambito de Aplicacion:</td>
          	<td>Empresa:</td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td><input name="ambito" type="text" id="ambito" readonly="" value="<? $ambito = strtoupper($ambito); print $ambito ?>" /></td>
          	<td><select name="empresa" class="textfield" id="empresa" disabled="disabled">
            <option value="0">Seleccione una Epresa</option>
<?
			$var=$empresas->listarEmpresas2();
			while( list( $key, $val) = each($var) ) 
				{
					$codigo_empresa = $var[$key][codigo_empresa];
					$nombre_empresa = $var[$key][nombre_empresa];
					if($empresa==$codigo_empresa)
						{
?>
							<option value="<? print $codigo_empresa; ?>" selected="selected"><? print $nombre_empresa; ?> </option>
<?
							$nombre_emp_ori=$nombre_empresa;
						}
					else
						{
?>
							<option value="<? print $codigo_empresa; ?>"><? print $nombre_empresa; ?> </option>
<?
						}
				}
?>
			</select></td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
          	<td><input name="enviar" type="hidden" value="1"/></td>
          	<td>&nbsp;</td>
          	<td>&nbsp;</td>
        </tr>
        <tr class="trTblReg1" >
          	<td colspan="3" valign="bottom" align="right">
				<?
				if ($pdf=="")
				{
				?>
                	<a href="#"  title="Contrato Sin PDF" >
					<? print $nombre_emp_ori ?></a>
                <?php
				}else{
				?>
	            	<a href="modulos/contratos/contratos/documentos/<? echo $codigo_contrato?>/<? echo $pdf ?>"  onclick="window.open('', 'VEN_PDF')" title="Ver PDF"   target="VEN_PDF"> <img src="../plantillas/plantilla_admin/images/impresora.gif" width="28" height="28" /> </a>          
                <?php
				}
					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['contratos_contratos_editar.php']);
							$id_pantalla=104;
							$pantalla->Limpiar();
							
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{
								?><a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar&amp;id=<? print $codigo_contrato;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" width="28" height="28" border="0" /></a>
								<?
                                }
						}
					else
						{
						?>
                            <a href="?url=contratos&amp;tbl=Contratos&amp;accion=editar&amp;id=<? print $codigo_contrato;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" alt="Editar" width="28" height="28" border="0" /></a>

						<?
                        }

					if($_SESSION["tipo"]!=1)
						{
							$var1=$pantalla->getporUrl($acciones['contratos_contratos_eliminar.php']);
							$id_pantalla=105;
							$pantalla->Limpiar();
							
							if($seguridad->getSeguridad($id_pantalla,$_SESSION["tipo"]))
    							{
								?>
                                    <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar&amp;id=<? print $codigo_contrato;?>">&nbsp;<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" width="28" height="28" border="0" /></a>&nbsp;
								<?
                                }
						}
					else
						{
						?>
                            <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar&amp;id=<? print $codigo_contrato;?>">&nbsp;<img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" alt="Eliminar" width="28" height="28" border="0" /></a>&nbsp;
						<?
                        }


?>			</td>
        </tr>
      	</table>
    	</form></td>
</tr>
</table>