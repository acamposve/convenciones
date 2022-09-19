<?
$id=$_GET['id'];


		$var=$Noticias->getId( $id );
	    while( list( $key, $val) = each($var) ) 
			{
		    	$codigo_noticia 	= $var[$key][codigo_noticia];
				$nombre_categoria 	= $var[$key][nombre_categoria];
				$titulo 			= $var[$key][titulo];
				$fecha_publicacion 	= $var[$key][fecha_publicacion];
				$estatus_noticia	= $var[$key][estatus_noticia];
				$codigo_categoria 	= $var[$key][codigo_categoria];
				$nombre_categoria	= $var[$key][nombre_categoria];
				$noticia_completa 	= $var[$key][noticia_completa];
				$resumen_noticia	= $var[$key][resumen_noticia];
				$img_1				= $var[$key][imagen_1];
				$img_2				= $var[$key][imagen_2];
				$origen 				= $var[$key][origen];
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
    <td width="99%" ><? print $msg; 
	$msg="";?></td>

  </tr>
  <tr class=" Color_tabla">
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%" >Titulo de la Noticia: </td>
          <td width="50%" >Fecha de Publicación de la Noticia: </td>
        </tr>
        <tr>
          <td  ><input type="text" readonly="readonly" size="50" value="<? print $titulo ;?>"/></td>
          <td  ><input type="text" readonly="readonly" size="30" value="<? print $fecha_publicacion ;?>"/> </td>
        </tr>
		<tr>
        	<td height="16" colspan="3" class="texto_simple">Noticia Completa <br /><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                  <tr>
                    <td bgcolor="#FFFFFF" width="100%"><? print  $noticia_completa ?></td>
                  </tr>
            	</table>
			</td>
        </tr>
        <!--
		<tr>
        	<td height="16" colspan="3" class="texto_simple">Resumen de la Noticia <br /><table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
                  <tr>
                    <td bgcolor="#FFFFFF" width="100%"><? print  $resumen_noticia ?></td>
                  </tr>
            	</table>
			</td>
        </tr>
        -->
        <tr>
          <td  >Categoria Noticia </td>
          <td  >Estatus de la Noticia </td>
        </tr>
        <tr>
          <td  > <input type="text" readonly="readonly" size="50" value="<? print $nombre_categoria ;?>"/></td>
          <td  > <input type="text" readonly="readonly" size="30" value="<? print $estatus_noticia ;?>"/> </td>
        </tr>
		<tr valign="top" align="center" >
	        <td width="50%">	
            	<?php if ($img_1=="") { 	
					print "Boletin Sin PDF";
					}else{
				?>
				<a href="#"  na onclick="window.open('../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_1 ?>', 'IMG1')" title="Imagen 1" >
				<img src="../plantillas/plantilla_admin/images/impresora.gif" width="28" height="28" />
                </a>
				<?	
						}
				?>			</td>
		  <td >&nbsp;</td>
        </tr>
        
        <tr>
          <td height="67"><input type="hidden" value="1" name="enviar">
			Origen <br /> 
			<A HREF="<?php print $origen ?>" target="_new"><?php print $origen ?></a
          ></td>
        </tr>
        <tr>
          <td colspan="2"><div align="right"><a href="?url=tablas&amp;tbl=Noticias&amp;accion=editar&amp;id=<? print $id;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar"/></a><a href="?url=tablas&amp;tbl=Noticias&amp;accion=eliminar&amp;id=<? print $id;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Eliminar"/></a></div></td>
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

