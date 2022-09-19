<?
$enviar=$_GET['flag'];
$id_articulo=$_GET['id'];
$page=$_GET['page'];

if ($enviar =="SI"){
 $flag_del=true; ?>
}

<?

if($flag_del){
$error=$otras_leyes->borrarArticulos($id_articulo);

$contador = $otras_leyes->contarArticulos($codigo_ley);
$contador = $otras_leyes->Total;

$var=$otras_leyes->update_nro_articulos($contador , $codigo_ley);
}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=leyes&tbl=Otras Varias&accion=listar articulos&ley=<? print $codigo_ley ?>&amp;page=<? print $page ?>";
-->
</script>

<?
}
}

?>