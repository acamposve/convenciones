<?php  
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include('lib/objetos/sectores.php');
include('lib/objetos/tipos_empresas.php');
include('lib/objetos/categoria_sector.php');
include('lib/objetos/actividad_empresa.php');
include('lib/objetos/empresas.php');
include('lib/objetos/categoria.php');
include('lib/objetos/categoria_titulos.php');
include('lib/objetos/titulos.php');	
require("lib/funciones/php/contratos/comparacion/FuncionesComparacionesXajax.php");
$onload="xajax_ListarEmpresas(xajax.getFormValues(document.buscador))";
$xajax->printJavascript('../../../../lib/componentes/xajax'); 
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<title>Buscador de Contratos</title>
<style type="text/css">
<!--
#articuloVer {
	position:absolute;
	left:441px;
	top:68px;
	width:321px;
	height:166px;
	z-index:1;
}
body {
	background-color: #E48B8B;
}
.style1 {
	color: #000000;
	font-weight: bold;
}
-->
</style>
<script>
function checkwhere(e) {
        if (document.layers){
        xCoord = e.x;
        yCoord = e.y;
}
        else if (document.all){
        xCoord = event.clientX;
        yCoord = event.clientY;
}
        else if (document.getElementById){
        xCoord = e.clientX;
        yCoord = e.clientY;
}
        document.getElementById('articuloVer').style.left = xCoord+20;
		document.getElementById('articuloVer').style.top = yCoord+20;
        }
		
document.onmousemove = checkwhere;
if(document.captureEvents) {document.captureEvents(Event.MOUSEMOVE);}

</script>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onLoad="<? print $onload; ?>">

<script language="javascript">
<!--
function generarLista(Valor){
	contador=document.getElementById('contador').value;
	xajax_listarContratosComparar(xajax.getFormValues(document.lista),contador);
	xajax_compararContratos(xajax.getFormValues(document.lista),contador)
}
-->
</script>
<table width="62%" height="424" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="60" colspan="2"><img src="../media/superior.gif" width="786" height="60"><span id="articuloVer"></span></td>
  </tr>
  <tr>
    <td width="28%" height="20" valign="top">&nbsp;</td>
    <td width="72%" height="20" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="9" valign="top"><img src="../media/superior_buscador.gif" width="222" height="9"></td>
    <td width="72%" rowspan="3" valign="top"><div id="comparaciones_tabla"></div>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td height="257" valign="top" bgcolor="#F8E2E2"><table vspace="10"  width="222" height="100" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F8E2E2">
      <tr bgcolor="#F8E2E2">
        <td height="194" colspan="3" valign="top"><form name="buscador" method="post" action="">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F8E2E2">
              <tr>
                <td bgcolor="#E48B8B" class="titulo style1" ></td>
              </tr>
              <tr>
                <td bgcolor="#F8E2E2" class="titulo style1"><div align="center"></div></td>
              </tr>
              <tr>
                <td bgcolor="#F8E2E2" class="titulo style1">BUSCADOR:</td>
              </tr>
              <tr>
                <td bgcolor="#F8E2E2" class="texto"><p>Sector :<br>
                        <select name="sector" class="texto_simple" id="select2" onChange="xajax_ListarEmpresas(xajax.getFormValues(document.buscador))"  >
                          <option value="" selected="selected">Seleccione Sector</option>
                          <?
						$var=$sectores->listarSectores2();
						
						while( list( $key, $val) = each($var) ) {
						$codigo_Sector = $var[$key][codigo_sector];
						$nombre_Sector = $var[$key][nombre_sector];
						
						if($codigo_sector==codigo_Sector){
							?>
                          <option value="<? print $codigo_Sector; ?>" selected="selected"> <? print $nombre_Sector; ?> </option>
                          <? }else{
						
						?>
                          <option value="<? print $codigo_Sector; ?>" > <? print $nombre_Sector; ?> </option>
                          <?
						}
						}
					?>
                        </select>
                </p></td>
              </tr>
              <tr>
                <td bgcolor="#F8E2E2" class="texto">Tipo<br>
                    <select name="tipos" class="texto_simple" id="select3" onChange="xajax_ListarEmpresas(xajax.getFormValues(document.buscador))"  >
                      <option value="" selected="selected">Seleccione Tipo de Empresa</option>
                      <?
						$var=$tipos_empresas->listarTipos2();
							while( list( $key, $val) = each($var) ) {
							$codigo_Tipo = $var[$key][codigo_tipo];
							$nombre_Tipo = $var[$key][nombre_tipo];
							if($codigo_tipo==$codigo_Tipo){
							?>
                      <option value="<? print $codigo_Tipo; ?>" selected="selected"> <? print $nombre_Tipo; ?> </option>
                      <? }else{
			?>
                      <option value="<? print $codigo_Tipo; ?>"> <? print $nombre_Tipo; ?> </option>
                      <?
						}
						
						}
					?>
                  </select></td>
              </tr>
              <tr>
                <td bgcolor="#F8E2E2" class="texto">Categoria<br>
                    <select name="categoria" class="texto_simple" id="select4"  onChange="xajax_ListarEmpresas(xajax.getFormValues(document.buscador))" >
                      <option value="" selected="selected">Seleccione Categorias del Sector</option>
                      <?
						$var=$categoria_sector->listarCategoria2();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_Categoria = $var[$key][codigo_categoria];
							$nombre_Categoria = $var[$key][nombre_categoria];
							
							if($codigo_Categoria==$codigo_categoria){
							?>
                      <option value="<? print $codigo_Categoria; ?>" selected="selected"> <? print $nombre_Categoria; ?> </option>
                      <?  }else{
			
			?>
                      <option value="<? print $codigo_Categoria; ?>"> <? print $nombre_Categoria; ?> </option>
                      <?
			}
						}
					?>
                  </select></td>
              </tr>
              <tr>
                <td bgcolor="#F8E2E2" class="texto">Actividad<br>
                    <select name="actividad" class="texto_simple" id="select5" onChange="xajax_ListarEmpresas(xajax.getFormValues(document.buscador))"  >
                      <option value="" selected="selected">Seleccione Actividad</option>
                      <?
						$var=$actividades->listarActividades2();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_Actividad = $var[$key][codigo_actividad];
							$nombre_Actividad = $var[$key][nombre_actividad];
							
							if($codigo_Actividad==$codigo_actividad){
							?>
                      <option value="<? print $codigo_Actividad; ?>" selected="selected"> <? print $nombre_Actividad; ?> </option>
                      <? }else{
			?>
                      <option value="<? print $codigo_Actividad; ?>"> <? print $nombre_Actividad; ?> </option>
                      <?
						}
						}
					?>
                  </select></td>
              </tr>
              <tr>
                <td bgcolor="#F8E2E2" class="texto"><div id="empresas"></div></td>
              </tr>
              <tr>
                <td height="13" bgcolor="#F8E2E2" class="texto"><div align="right"> <a href="#" onClick="xajax_buscarContratos(xajax.getFormValues(document.buscador),'codigo_sector')"> <img src="../../../../plantillas/plantilla_admin/images/buscar.gif" alt="BUSCAR CONTRATOS" width="47" height="13" border="0"> </a></div></td>
              </tr>
            </table>
        </form></td>
      </tr>
      <tr>
        <td height="20" colspan="3" bgcolor="#F8E2E2"><form name="lista">
            <div id="listado_contratos"> </div>
        </form></td>
      </tr>
      <tr>
        <td width="11" height="2"colspan="3"  bgcolor="#F8E2E2"></td>
      </tr>
      <tr>
        <td height="20" colspan="3" bgcolor="#F8E2E2"><div id="comparacion"></div></td>
      </tr>
      <tr>
        <td height="21" colspan="3" valign="bottom" bgcolor="#F8E2E2"><div align="center">&nbsp;</div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="9" valign="top"><img src="../media/inferior_buscador.gif" width="222" height="9"></td>
  </tr>
  <tr>
    <td height="20" valign="top">&nbsp;</td>
    <td width="72%" height="20" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="15" valign="top" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="1%"><img src="../../../../plantillas/plantilla_admin/images/inferior_lateral_izquierdo.gif" width="13" height="48"></td>
        <td width="98%" background="../../../../plantillas/plantilla_admin/images/inferior_centro.gif"><div id="pdf"></div></td>
        <td width="1%"><img src="../../../../plantillas/plantilla_admin/images/inferior_lateral_derecho.gif" width="13" height="48"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>