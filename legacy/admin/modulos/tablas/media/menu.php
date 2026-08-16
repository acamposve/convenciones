<link href="../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>

<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<?php 

include ('../lib/objetos/categoria.php');
$categoria=$categorias->listarcategoria();

while( list( $key, $val) = each($categoria) ) 
	{
		$Id_categoria_original = $categoria[$key][Id_categoria];
		$Nombre_categoria = $categoria[$key][Nombre_categoria];
		$desc = $categoria[$key][desc];
		if ($Id_categoria_original<=5)
			{
?>
				<div id="CollapsiblePanel<? echo $Id_categoria_original?>" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0"><? echo $Nombre_categoria?> </div>
<? 
			}
			?>
            
		<div class="CollapsiblePanelContent " >
        <?php
		$mod=$modulos->listarModulo();
		while( list( $key, $val) = each($mod) ) 
			{
				$Id_modulo = $mod[$key][Id_modulo];
				$Nombre_modulo = $mod[$key][Nombre_modulo];
				$Id_categoria2 = $mod[$key][Id_categoria];
				if ($Id_categoria2 ==$Id_categoria_original)
					{
?>
						<a href="./?url=<? print $desc;?>&amp;tbl=<? print $Nombre_modulo ?>" >&nbsp;&nbsp;<? print $Nombre_modulo; ?></a> <br />
<? 
					}
			}
			?></div></div>
<script type="text/javascript">
<!--
//var CollapsiblePanel<? echo $Id_categoria_original?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $Id_categoria_original?>");
//-->
</script>
<?php
	}
?>

	<div id="CollapsiblePanel98"   class="CollapsiblePanel"><a href="#">&nbsp;&nbsp;Manuales de Usuario </a></div>
	<div id="CollapsiblePanel99"  class="CollapsiblePanel"><a href="#"><a href="#">&nbsp;&nbsp;Acerca de .. </a></div>
	<div id="CollapsiblePanel100"  class="CollapsiblePanel"><a href="?url=logout">&nbsp;&nbsp;Cerrar Sesi&oacute;n </a></div>   
<script type="text/javascript">
<!--
var CollapsiblePanel98 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel",{contentIsOpen:false});
var CollapsiblePanel99 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel",{contentIsOpen:false});
var CollapsiblePanel100 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel",{contentIsOpen:false});
//-->
</script>
<table width="159" border="0" align="center" cellpadding="0" cellspacing="0" >
</table>


