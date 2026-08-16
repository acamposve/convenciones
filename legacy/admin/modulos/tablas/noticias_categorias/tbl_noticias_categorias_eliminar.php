<?
$id=$_GET['id'];
		$var=$Noticias->getIdCategoria( $id );
	    while( list( $key, $val) = each($var) ) 
			{
		    	$codigo_categoria 		= $var[$key][codigo_categoria];
				$nombre_categoria 		= $var[$key][nombre_categoria];
				$descripcion_categoria 	= $var[$key][descripcion_categoria];
		
		
			}

?>

<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"  class="Color_tabla">
  <tr>
    <td width="1%" >&nbsp;</td>
    <td width="99%" ><? 
	?></td>

  </tr>
  <tr class=" Color_tabla">
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="100%" >Nombre de la Categoria</td>
        </tr>
        <tr>
          <td  ><input type="text" readonly="readonly" size="50" value="<? print $nombre_categoria ;?>"/></td>
        </tr>
		<tr>
        	<td height="16" colspan="3" class="texto_simple">Descripcion de la Categoria
        	  <table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                  <tr>
                    <td bgcolor="#FFFFFF" width="100%"><? print  $descripcion_categoria ?></td>
                  </tr>
            	</table>			</td>
        </tr>
        <tr>
          <td height="67"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>

        <tr>
          <td colspan="3" valign="bottom">
          	<div align="right">Desea Eliminar esta Noticia:&nbsp;&nbsp;&nbsp;
            	<a href="?url=tablas&amp;tbl=Categorias de Noticias&amp;accion=eliminar&amp;id=<? print $id;?>&amp;flag=SI">
            	<img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" /></a>&nbsp;&nbsp;
				<a href="?url=tablas&amp;tbl=Categorias de Noticias&amp;accion=listar">
            	<img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a>
            </div>
            </td>
          </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
	
  </tr>
</table>

