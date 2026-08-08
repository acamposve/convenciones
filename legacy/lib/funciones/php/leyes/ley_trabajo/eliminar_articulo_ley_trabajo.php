<?
$enviar=$_GET['flag'];
$id_articulo=$_GET['id'];
$page_return=$_GET['page'];
if ($enviar =="SI")
	{
	$flag_del=true;
	}


if($flag_del)
{
	$error=$ley_trabajo->delete($id_articulo);
	$contador = $ley_trabajo->contarArticulos();
	$contador = $ley_trabajo->Total;

	$var=$ley_trabajo->get_Ley_Trabajo();
	$total_articulos=$ley_trabajo->total_articulos;
	$var=$ley_trabajo->update_nro_articulos($contador , $total_articulos);
	}

if ($error){
?>
<script language="javascript" type="text/javascript">
<!--
location.href="index.php?url=leyes&tbl=LOT&accion=listar&page=<? print $page_return ?>";
-->
</script>

<?
}


?>
