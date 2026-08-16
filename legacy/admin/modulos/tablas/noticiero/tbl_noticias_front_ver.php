<?
$id=$_GET['id'];
$noticia=$_POST['noticia'];
if ($noticia!="")
	{	
		$id=$noticia;		
	}

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
    <td width="99%" ></td>

  </tr>
  <tr class=" Color_tabla">
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="data" method="post" action="" >
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td  colspan="2" ><font  size="+2" ><? print $titulo?></font>   <div align="right" > <? print  $fecha_publicacion?> </div></td> 
		</tr>
		<tr>
        	<td height="16" colspan="2" class="texto_simple"><br />
	        	  <table width="100%" cellpadding="0" cellspacing="0" bordercolor="#000000" class="texto_simple" style="border-style: inset;border-width:1">
    	              <tr>
        	          		<td bgcolor="#FFFFFF" width="100%"><? print  $noticia_completa ?></td>
            	      </tr>
       	    	</table>            </td>
        </tr>
		<tr >
	        <td width="50%">	
            	<?php if ($img_1=="") { 	
					print "";
					}else{
				?>
				<a href="#"  onclick="window.open('../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_1 ?>', 'IMG1')" title="Imagen 1" >
				<img src="../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_1 ?>" width="350" height="350" border="0" alt="Imagen 1"/>                </a>
				<?	
						}
				?>			</td>
			<td width="50%">
            	<?php if ($img_2=="") { 	
					print "";
			
					}else{
				?>
				<a href="#"  onclick="window.open('../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_2 ?>', 'IMG2')" title="Imagen 2" >
				<img src="../admin/modulos/tablas/noticias/documentos/<?php print $id."/".$img_2 ?>" width="350" height="350" border="0" alt="Imagen 2"/>                </a>
				<?	
						}
				?>            </td>
        </tr>
        
        <tr>
          <td height="67" valign="top">
          <?php 
		  	if (strlen(trim($origen)) >10){
				?>	<A HREF="<?php print $origen ?>" target="_new">Visitar Fuente de la Noticia</a>
                <?
                }
		  		?>           </td>
           <td  >
           		Categoria Noticia <br /><br />
				<?php print $nombre_categoria ;?>           </td>
        </tr>
        <tr>
        <td colspan="2">
    	    <? 		
			
			?>
            Seleccione una Noticia<br />
		<select name="noticia" class="textfield" id="noticia"  onchange="Recargar()">
                <?
			$noticias=$Noticias->listarNoticias_Publciadas( 0,500,"" );
	  
			while( list( $key, $val) = each($noticias) ) {
		    	$codigo_noticia 	= $noticias[$key][codigo_noticia];
				$nombre_categoria 	= $noticias[$key][nombre_categoria];
				$titulo 			= $noticias[$key][titulo];
				$fecha_publicacion 	= $noticias[$key][fecha_publicacion];
				$estatus_noticia	= $noticias[$key][estatus_noticia];
				if($id==$codigo_noticia){
				?>
    	            <option value="<? print $codigo_noticia;?>" selected="selected" ><? print $titulo; ?> </option>
                <?
				}else{
				?>
        	  	 	 <option value="<? print $codigo_noticia; ?>" onchange="Recargar()"><? print $titulo; ?>  </option>
                <?
				}
			}
			?>
         </select>
        
        </td>
        </tr>
        
        <input type="hidden" value="<?php print $codigo_noticia ?>"  name="id_nueva" id="id_nueva"/>
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

