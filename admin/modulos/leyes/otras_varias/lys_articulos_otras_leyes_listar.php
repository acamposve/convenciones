<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<form name="formleyesarticulos" id="formleyesarticulos" action="" method="get">
<?
		$tbl=$_GET['tbl'];
		$page = $_GET['page']; 
		$codigo_ley = $_GET['ley'];
		$filtro = $_GET['filtro']; 		
		$limit = 20;  
		$paginador=0;
		$var=$otras_leyes->contarArticulos($codigo_ley);
		$total=$otras_leyes->Total;
		$pager  = Pager::getPagerData($total, $limit, $page); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$page   = $pager->page;  
?>

<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr height="1">
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr class="Color_tabla">
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" >
    	<?
			
			if($_SESSION['tipo']!=1)
				{
			$var1=$pantalla->getporUrl($acciones['agregar_articulo']);
			$id_pantalla=$pantalla->id_pantalla; 
			$pantalla->Limpiar();
				if($seguridad->getSeguridad($id_pantalla,$_SESSION['tipo']))
    			{
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=agregar&amp;ley='.$codigo_ley.'" class="linkAgregar" >Agregar Registro</a>';
			}
			}else{
			echo '<a href="?url=leyes&amp;tbl=Otras Varias&amp;accion=agregar&amp;ley='.$codigo_ley.'" class="linkAgregar">Agregar Registro</a>';
			}
			?>	<?	
			  $ley=$otras_leyes->leyespecifica( $codigo_ley );
			  while( list( $key, $val) =