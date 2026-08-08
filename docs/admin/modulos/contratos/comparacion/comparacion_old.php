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
<title>..::COMPARACION DE CONTRATOS COLECTIVOS::..</title>


<style type="text/css">
body {
  background-color: #ffffff;
}


.style1 {
	color: #000000;
	font-weight: bold;
}

#tweedledeeDiv { 	
    position:absolute;
	left:249px;
	top:88px;
	width:321px;
	height:166px;
	z-index:1;
	}


HTML>BODY #articuloVer {
  position: fixed;
}
</style>
<script type="text/javascript" src="fixedLayerManager.js"></script>
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
		

//document.onmousemove = checkwhere;
//sif(document.captureEvents) {document.captureEvents(Event.MOUSEMOVE);}


</script>
<script language="javascript">
<!--
function generarLista(Valor){
	contador=document.getElementById('contador').value;
	xajax_listarContratosComparar(xajax.getFormValues(document.lista),contador);
	xajax_compararContratos(xajax.getFormValues(document.lista),contador)
}
function generarListaTitulo(){
	contador=document.getElementById('contador').value;
	Titulo = document.getElementById('titulo_comparativo').value;
	xajax_listarContratosComparar(xajax.getFormValues(document.lista),contador);
	xajax_compararContratos(xajax.getFormValues(document.lista),contador,Titulo)
}
-->
</script>


<div id="tweedledeeDiv"></div>
<div class="Estilo5" id="hola" style="visibility:hidden"><span size="1px">&nbsp;</span></div>

<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onLoad="<? print $onload; ?>">
<table width="100%" height="431" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#8F2323" id="page">
  <tr>
    <td height="60" valign="top" bgcolor="#FFFFFF"></td>
    <td height="60" colspan="3" valign="top" bgcolor="#FFFFFF"><img src="../media/arriba_.png" width="744" height="83"></td>
  </tr>
  <tr>
    <td width="8" valign="top"></td>
    <td width="217" height="2" valign="top"></td>
    <td height="2" colspan="2" valign="top"></td>
  </tr>
  <tr>
    <td height="9" valign="top" bgcolor="#FFFFFF"></td>
    <td rowspan="2" valign="top"><table width="200" height="304" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <tr>
        <td><table vspace="10"  width="215" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
          <tr bgcolor="#ffffff" valign="top">
            <td height="206" colspan="3" valign="top"><form name="buscador" method="post" action="">
              <table width="215" border="0" align="left" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" class="titulo style1"><div align="center"></div></td>
                </tr>
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" class="titulo style1">BUSCADOR:</td>
                </tr>
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" class="texto">Por Nombre: </td>
                </tr>
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" class="texto"><label>
                    <input name="nombre" id="nombre" type="text" class="texto" onChange="xajax_ListarEmpresas(xajax.getFormValues(document.buscador))"/>
                  </label></td>
                </tr>
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" class="texto"><p>Sector :<br />
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
                  <td valign="top" bgcolor="#FFFFFF" class="texto">Tipo<br />
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
                  <td valign="top" bgcolor="#FFFFFF" class="texto">Categoria<br />
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
                  <td height="30" valign="top" bgcolor="#FFFFFF" class="texto">Actividad<br />
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
                  <td valign="top" bgcolor="#FFFFFF" class="texto"><div id="empresas"></div></td>
                </tr>
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" class="texto">&nbsp;</td>
                </tr>
                <tr>
                  <td height="13" valign="top" bgcolor="#FFFFFF" class="texto"><div align="right"> <a href="#" onClick="xajax_buscarContratos(xajax.getFormValues(document.buscador),'codigo_sector')"> <img src="../media/buscar_.png" alt="BUSCAR CONTRATOS" width="46" height="17" border="0" /> </a></div></td>
                </tr>
              </table>
            </form></td>
          </tr>
          <tr>
            <td height="20" colspan="3" bgcolor="#FFFFFF"><form name="lista">
              <div id="listado_contratos"> </div>
            </form></td>
          </tr>
          <tr>
            <td width="215" height="2"colspan="3"  bgcolor="#ffffff"></td>
          </tr>
          <tr>
            <td height="20" colspan="3" bgcolor="#FFFFFF"><div id="comparacion"></div></td>
          </tr>
          <tr>
            <td height="20" colspan="3" valign="bottom" bgcolor="#FFFFFF"><div align="center">&nbsp;</div></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="12" valign="top">&nbsp;</td>
    <td width="695" valign="top"><div id="comparaciones_tabla"></div></td>
  </tr>
  <tr>
    <td height="279" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#ffffff"></td>
    <td height="1" valign="top"></td>
  </tr>
  <tr>
    <td height="5" valign="top" bgcolor="#FFFFFF"></td>
    <td colspan="3" rowspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td height="25" background="../media/contratos_.png"><div id="pdf"></div></td>
        <td width="1%">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="15" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>
</body>
