<?php 
include ('../../../../lib/objetos/contratos.php');
include ('../../../../lib/objetos/categoria_titulos.php');
include ('../../../../lib/objetos/titulos.php');
?>

<script src="../../../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../../../../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>..::Comparacion de Contratos::.. Asesores RRHH C.A.</title>
<script type="text/javascript" src="../../../../lib/funciones/js/comparacion/comparacionAjax.js"></script>

<style type="text/css">
.legend {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
	color:#FFFFFF
}
.Texto{
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
.contenido{
	width:60%;
}
.autor{
	width:15%;
}
</style>
<script>
function mostrar(id) {
  obj = document.getElementById(id);
  obj.style.visibility = (obj.style.visibility == 'hidden') ? 'visible' : 'hidden';
}
</script>

</head>


<body  bgcolor="#d9d7d7">
<img src="../../../../plantillas/plantilla_admin/images/header.gif" alt=""  /> 

<form name="frmbusqueda" action="" onSubmit="buscarDato(); return false">
	<br/>
    <font  class="Texto"> Definiciones </font>
    <br/>
    <table width="50%">
	<tr>
    	<td bgcolor="#6d0c07"><img src="../../../../plantillas/plantilla_admin/images/LogoCCCColBlanco.gif" alt=""  /> 
        </td>
    </tr>
    <tr>
    <td width="40%"  bordercolor="#FFFF00"> 
	<?
	$categorias			=$categorias_titulos->listarCategoria_comparacion();
	$x=0;
		while (list( $key, $val) = each($categorias)) 
			{
			$codigo_categoria_titulos		=	$categorias[$key][codigo_categoria_titulos];
			$nombre_categoria				=	$categorias[$key][nombre_categoria];
			$descripcion_categoria			=	$categorias[$key][descripcion_categoria];
			$x=$x+1;
			?>
			<div id="CollapsiblePanel<? echo $codigo_categoria_titulos?>" class="CollapsiblePanel" >
			  <div class="CollapsiblePanelTab" tabindex="0"><? echo $nombre_categoria?> </div>
                		<div class="CollapsiblePanelContent " >
                        <? print $descripcion_categoria ?>
            </div></div>
			<script type="text/javascript">
			<!--
			var CollapsiblePanel<? echo $codigo_categoria_titulos?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_categoria_titulos?>",{contentIsOpen:false});
			//-->
			</script>
			<?
			}
			?>
		
            </div>
	</td>
    </tr>
	</tr>
    <td>
            <br />
      <?
		$categorias_titulos	=$titulos->listarTitulos_comparacion();
		?>
      <select name="dato" class="textfield" >
        
        <option value="" selected="selected" >Seleccione un Titulo Comparativo</option>
        
        <?php	
	
				$x=0;
				$nombre_categoria_ordenamiento="";
			while (list( $key, $val) = each($categorias_titulos)) 
				{
				$codigo_titulo_comparativo	=	$categorias_titulos[$key][codigo_titulo_comparativo];
				$nombre_titulo				=	$categorias_titulos[$key][nombre_titulo];
				$codigo_categoria_titulo	=	$categorias_titulos[$key][codigo_categoria_titulo];
				$nombre_categoria			=	$categorias_titulos[$key][nombre_categoria];				
				$codigo_categoria_titulos_original	=	$categorias_titulos[$key][codigo];		
				$x=$x+1;
				if ($nombre_categoria_ordenamiento!=$nombre_categoria)
					{
					?>
        <option  value="" ><? print $nombre_categoria ?></option>
        <option  value="<? print $codigo_titulo_comparativo; ?>" ><? print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nombre_titulo ?></option>
        <? 
					$nombre_categoria_ordenamiento=$nombre_categoria;
					}
				else
					{
					?>
        <option  value="<? print $codigo_titulo_comparativo; ?>" ><? print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nombre_titulo ?></option>
        <? 
					}
				}
				?>
      </select>	
      </td>
      </tr>
      </table>
      <br/>
  <div align="left" >Termino a buscar:
    <input name="Buscar" type="submit"  title="Comparacion" /> 
    
  </div>
<br/>
<? print $i;?>
</form>

Resultado
<div id="resultado"></div>

</body>
</html>

