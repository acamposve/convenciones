<?
session_start();
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
$t_usuario=$_SESSION["tipo"];

include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');
include('./modulos/bienvenida/settings.php');

?>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 9px
}
.Estilo2 {
	font-size: 10px
}
-->
</style>
<script type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<link href="../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<body onLoad="MM_preloadImages('media/bot_tablas_on.gif','media/bot_seguridad_on.gif')">
<table width="1010" border="0" align="center" cellpadding="0" cellspacing="0" background="login/media/bg.gif" >
<tr>
	<td bgcolor="#8F2323">
		<table width="727" border="0" align="center" cellpadding="0" cellspacing="0" background="login/media/bg.gif">

    <tr>
  
  <td><table  bgcolor="#ededed" width="1010" height="10" border="0" cellpadding="0" cellspacing="0" align="left">
      <tr>
        <td width="12"></td>
        <td width="1%"></td>
      </tr>
      <tr>
        <td colspan="4" valign="top" bgcolor="#ededed" align="left"><table width="101%" bgcolor="#ededed"  height="83%" border="0" align="left" cellpadding="2" cellspacing="2">
            <tr>
              <td  valign="top" bgcolor="#ededed"><? include('./modulos/menu/menu.php')?></td>
              </tr><tr>
              <td width="100%" valign="top" bgcolor="#ededed" style="background-repeat:no-repeat; background-position:inherit"><table width="100%" height="100%" border="1" align="center" cellpadding="0" cellspacing="0">
                  <tr bgcolor="#ededed" >
                    <td width="75%" height="17"    >&nbsp; <font   class="texMenu"> <?php print strtoupper($cat);?>&nbsp; </font></td>
                    <td width="25%"><font   class="texMenu" >
                      <center>
                        <?
                       $FechaHoy=date("d/m/Y");
						print "Fecha Actual ".$FechaHoy;
					   ?>
                      </center>
                      </font></td>
                  </tr>
                  <tr>
                    <td height="207" width="100%"colspan="2" valign="top"><? include("./".$tbl); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><div align="center"></div></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
        </tr>
      
    </table></td>
    </tr>
  
</table>
