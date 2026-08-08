<link href="../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>

<link href="../../../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<table align="left"  width="100%" bgcolor="#ededed" >
<tr>
    
    <script  type="" language="">
    <!--
    function abrirVentana(){
    //window.open("modulos/contratos/comparacion/comparacion.php","ventana1","width=800,height=600, scrollbars=yes, resizable=no");
    window.open("../../admin/modulos/contratos/comparacion/comparador.php","ventana1"," scrollbars=yes, resizable=1, location=no");
    
    }
    -->
    </script>
    
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
    				<tr><td bgcolor="#8F2323">
                    <div id="CollapsiblePanel<? echo $Id_categoria_original?>" class="CollapsiblePanel">
                    <div class="CollapsiblePanelTab" tabindex="0"><? echo $Nombre_categoria?> </div>
    <? 
                }
                ?>
                
            <div class="CollapsiblePanelContent"  >
            <?php
            $mod=$modulos->listarModulo();
            while( list( $key, $val) = each($mod) ) 
                {
                    $Id_modulo = $mod[$key][Id_modulo];
                    $Nombre_modulo = $mod[$key][Nombre_modulo];
                    $Id_categoria2 = $mod[$key][Id_categoria];
                    if ($Id_categoria2 ==$Id_categoria_original)
                        {
                        if  ($Id_modulo==25){	

                        ?>
                            &nbsp;&nbsp;<a href="#" onclick="abrirVentana()" ><img src="../../../plantillas/plantilla_admin/images/arbolPositivo.gif" /> <font class="textMenu"><? print $Nombre_modulo; ?> </font> </a> <br />
                        <?php
                        }else {
                            ?>
                            &nbsp;&nbsp; <a href="./?url=<? print $desc;?>&amp;tbl=<? print $Nombre_modulo ?>" ><img src="../../../plantillas/plantilla_admin/images/arbolPositivo.gif" /> <font class="textMenu"><? print $Nombre_modulo; ?> </font> </a> <br />
                            <? 
                            }
                        }
                }
                ?></div></div>
    <script type="text/javascript">
    <!--
    var CollapsiblePanel<? echo $Id_categoria_original?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $Id_categoria_original?>",{contentIsOpen:false});
    //-->
    </script>
    <?php
        }
    ?>
        <div id="CollapsiblePanel97"   class="CollapsiblePanelTab"><a href="?url=tablas&amp;tbl=Noticiero&amp;accion=listar">Noticiero </a></div>
        <div id="CollapsiblePanel98"   class="CollapsiblePanelTab"><a href="#">Manuales de Usuario </a></div>
        <div id="CollapsiblePanel99"  class="CollapsiblePanelTab"><a href="#"><a href="#">Acerca de .. </a></div>
        <div id="CollapsiblePanel100"  class="CollapsiblePanelTab"><a href="?url=logout">Cerrar Sesi&oacute;n </a></div>   
    <script type="text/javascript">
    <!--
    
    //-->
    </script>
</td>
</tr>

</table>