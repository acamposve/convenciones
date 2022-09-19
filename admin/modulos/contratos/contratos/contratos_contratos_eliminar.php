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
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" >
<tr  >
    <td width="1%" height="32" ></td>
    <td width="99%" class="" ></td>
</tr>
<tr >
    <td height="119" > </td>
  <td rowspan="3" valign="top" >
<form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1"> 	
        <tr>
        	<td>
            Duracion:            </td>
        </tr>
        <tr>
        	<td>
            <input name="duracion" type="text"  readonly="" id="duracion" value="<? $duracion = strtoupper($duracion);   print $duracion ?>" />            </td>
        </tr>        
        
        
        <tr>
          	<td height="16">
            	Fecha de Inicio:
          					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          		Fecha de Termino:			</td>
        </tr>
        <tr>
          <td height="20">
          		<input name="inicio" type="text"  id="inicio" readonly="" value="<? print  $inicio ?>" size="15" />
            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            	<input name="termino" type="text"  id="termino" readonly="" value="<? print $termino ?>" size="15" />			</td>
        </tr>
        <tr>
          <td>Ambito de Aplicacion:</td>
        </tr>
        <tr>
          <td><input name="ambito" type="text"  id="ambito" readonly="" value="<? $ambito = strtoupper($ambito);  print $ambito ?>" /></td>
        </tr>
        <tr>
        	<td>
				Empresa: <br />
                  <select name="empresa" class="textfield" id="empresa" disabled="disabled">
                    <option value="0">Seleccione una Epresa</option>
                    <?
			$var=$empresas->listarEmpresas2();
	  
			while( list( $key, $val) = each($var) ) {
						$codigo_empresa = $var[$key][codigo_empresa];
						$nombre_empresa = $var[$key][nombre_empresa];
						
				if($empresa==$codigo_empresa){
			?>
                    <option value="<? print $codigo_empresa; ?>" selected="selected"><? print $nombre_empresa; ?> </option>
                    <?
					$nombre_emp_ori=$nombre_empresa;

			}else{
			?>
                    <option value="<? print $codigo_empresa; ?>"><? print $nombre_empresa; ?> </option>
                    <?
			}
			}
			?>
                  </select></td>
        <tr>
          <td>
          	<input name="enviar" type="hidden" value="1"/>
            PDF Asociado: <br />
				<?
				if ($pdf=="")
				{
				?>
                	<a href="#"  title="Contrato Sin PDF" >
					<? print $nombre_emp_ori ?></a>
                <?php
				}else{
				?>
	            	<a href="modulos/contratos/contratos/documentos/<? echo $codigo_contrato?>/<? echo $pdf ?>"  onclick="window.open('', 'VEN_PDF')" title="Ver PDF"   target="VEN_PDF"> <img src="../plantillas/plantilla_admin/images/impresora.gif" width="28" height="28"/> </a>          		
          <?php
				}
				?>        </tr>
        <tr class="trTblReg1" >
          	<td colspan="3" valign="bottom" align="right">
            Desea Eliminar este Contrato: &nbsp;&nbsp;&nbsp;
            <a href="?url=contratos&amp;tbl=Contratos&amp;accion=eliminar&amp;id=<? print $codigo_contrato; ?>&amp;flag=SI">
            <img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;            </a><a href="?url=contratos&amp;tbl=Contratos">&nbsp;&nbsp;
            <img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" />
            </a>            </td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
  <tr>
  </tr>
</table>

