<? 
ini_set("include_path", ".:$_SERVER[DOCUMENT_ROOT]/");
include('lib/objetos/user.php');
include('lib/objetos/pager.php');
include('lib/objetos/empresas.php');
include('lib/objetos/contratos.php');
include('lib/objetos/titulos.php');
include('lib/objetos/discusion.php');

require("lib/funciones/php/contratos/comparacion/FuncionesComparacionesXajax.php");
$onload="xajax_ListarEmpresas(xajax.getFormValues(document.buscador))";
$xajax->printJavascript('../../../../lib/componentes/xajax'); 
$codigo_bitacora = $_GET['id_bitacora'];
?>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-size: 12px}
-->
</style>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" > <table width="100%" height="100%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td height="14" valign="top"><strong class="titulo style1">PETICIONES</strong></td>
    <td width="928" rowspan="3" align="center" valign="top"><iframe src="reuniones.php" width="100%" height="90%" align="middle" ></iframe></td>
    <td class="style1 titulo"><strong>OFERTAS</strong></td>
  </tr>
  <tr>

    <td width="155" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" >   <?
  $var=$discusion->listarPeticionesSelect($codigo_bitacora);
  while( list( $key, $val) = each($var) ) {
							$codigo_peticion = $var[$key][codigo_peticion];
							$nro_peticion = $var[$key][nro_peticion];
							$titulo_peticion = $var[$key][titulo_peticion];
							$texto_completo_peticion  = $var[$key][texto_completo_peticion];
   ?>
      <tr>
        <td class="texto"></td>
      </tr>
      <tr class="texto_simple">
        <td class="texto"><span class="style1"><strong>
          <?=$nro_peticion.": ".$titulo_peticion ?>
        </strong></span><br>
<?=$texto_completo_peticion ?></td>
      </tr>
    <tr class="texto_simple" height="1" bgcolor="#000000"> <td class="texto"></td> </tr>	<?
	}
	?> </table></td>

    <td width="168" align="center" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
      <?
  $var=$discusion->listarOfertasSelect($codigo_bitacora);
  while( list( $key, $val) = each($var) ) {
							$codigo_ofertas = $var[$key][codigo_ofertas];
							$nro_oferta = $var[$key][nro_oferta];
							$titulo_oferta = $var[$key][titulo_oferta];
							$texto_completo_oferta = $var[$key][texto_completo_oferta];
   ?>
      <tr>
        <td class="texto"></td>
      </tr>
      <tr class="texto_simple">
        <td class="texto"><span class="style1"><strong>
          <?=$nro_oferta.": ".$titulo_oferta ?>
        </strong></span><br>
<?=$texto_completo_oferta ?></td>
      </tr>
      <tr class="texto_simple" height="1" bgcolor="#000000">
        <td class="texto"></td>
      </tr>
      <?
	}
	?>
    </table></td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td width="168">&nbsp;</td>
  </tr>
</table>
</body>