<link href="../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<script  type="" language="">
<!--
function abrirVentana()
	{
    	window.open("../admin/modulos/contratos/comparacion/comparador.php","ventana1"," scrollbars=yes, resizable=1, location=no");
    }
-->
</script>
<table align="left"  width="100%" bgcolor="#ededed" cellpadding="0" cellspacing="0">
<tr>
<?php 
include ('../lib/objetos/categoria.php');
$categoria=$categorias->listarcategoria();
while( list( $key, $val) = each($categoria) ) 
	{
?>
		<td valign="top">
<?php
		$Id_categoria_original = $categoria[$key][Id_categoria];
		$Nombre_categoria = $categoria[$key][Nombre_categoria];
		$desc = $categoria[$key][desc];
		if (($_SESSION["tipo"]!=1) && ($Id_categoria_original<=3))
			{
			}
		else
			{
				if ($Id_categoria_original<=5)
					{
?>
      					<div id="CollapsiblePanel<? echo $Id_categoria_original?>" class="CollapsiblePanel">
        				<div class="CollapsiblePanelTab" tabindex="0"><? echo $Nombre_categoria?> </div>
<? 
					}
?>
				<div class="CollapsiblePanelContent "  >
<?php
				$mod=$modulos->listarModulo();
				while( list( $key, $val) = each($mod) )
					{
						$Id_modulo = $mod[$key][Id_modulo];
						$Nombre_modulo = $mod[$key][Nombre_modulo];
						$Id_categoria2 = $mod[$key][Id_categoria];
						if ($Id_categoria2 ==$Id_categoria_original)
							{
								if  ($Id_modulo==25)
									{
?>
          								&nbsp;&nbsp;<a href="#" onclick="abrirVentana()" ><img src="/plantillas/plantilla_admin/images/arbolPositivo.gif" /> <font class="textMenu"><? print $Nombre_modulo; ?> </font> </a><br />
<?php
									}
								else
									{
?>
		          						&nbsp;&nbsp; <a href="./?url=<? print $desc;?>&amp;tbl=<? print $Nombre_modulo ?>" ><img src="/plantillas/plantilla_admin/images/arbolPositivo.gif" /> <font class="textMenu"><? print $Nombre_modulo; ?> </font> </a><br />
<? 
									}
							}
					}
?>
        </div>
      </div>
      <script type="text/javascript">
					<!--
					var CollapsiblePanel<? echo $Id_categoria_original?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $Id_categoria_original?>",{contentIsOpen:false});
					//-->
					</script>
      <?php
		        }
				?></td>
    <?php
				
				
			}
?>
		<td valign="top"><div id="CollapsiblePanel6"   class="CollapsiblePanelTab"><a href="?url=tablas&amp;tbl=Boletin&amp;accion=listar">Boletin </a></div></td>
		<td valign="top"><div id="CollapsiblePanel7"   class="CollapsiblePanelTab"><a href="?url=tablas&amp;tbl=Links de Interes&amp;accion=Front">Links de Interes</a></div></td>
		<td valign="top"><div id="CollapsiblePanel9"  class="CollapsiblePanelTab"><a href="http://www.asesoresrrhh.com"><img src="/plantillas/plantilla_admin/images/flecha_.gif" />...Regresar al Home </a></div></td>
</tr>
</table>
