<?
$id_titulo=$_GET['id'];
$var=$titulos->getId($id_titulo);
$descripcion_titulo=$titulos->descripcion_titulo;
$categoria_titulo=$titulos->codigo_categoria_titulo;
$nombre_titulo=$titulos->nombre_titulo;
?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr bgcolor="#D89B9B">
    <td height="1"></td>
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td width="1%" height="32" ></td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="80%">Nombre del Titulo: </td>
        </tr>
        <tr>
          <td><label>
            <input name="titulo" type="text"  id="titulo" size="35" value="<? print $nombre_titulo;?>" readonly="" />
          </label></td>
        </tr>
        <tr>
          <td>Descripcion del Titulo : </td>
        </tr>
        <tr>
          <td>
		<table width="90%" cellpadding="0" cellspacing="0" bordercolor="#000000"  style="border-style: inset;border-width:1;" >
              <tr>
                <td bgcolor="#FFFFFF" class=""> <? print  $descripcion_titulo ?></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>Categoria:</td>
        </tr>
        <tr>
          <td><select name="categoria" class="textfield" disabled="disabled" >
              <option value="" >Seleccione una categoria</option>
              <?
			$var=$categorias_titulos->listarCategoria2();
			
			 while( list( $key, $val) = each($var) ) {
		            $codigo_categoria_titulos = $var[$key][codigo_categoria_titulos];
					$nombre_categoria = $var[$key][nombre_categoria];
					
					if ($categoria_titulo==$codigo_categoria_titulos){
			?>
              <option  value="<? print $codigo_categoria_titulos; ?>" selected="selected"   ><? print $nombre_categoria ; ?></option>
              <? 
			}else{			
			?>
              <option  value="<? print $codigo_categoria_titulos; ?>"><? print $nombre_categoria ; ?></option>
              <?
			}
			
			}
			?>
            </select>
          </td>
        </tr>
        <tr>
          <td height="35"><input type="hidden" value="1" name="enviar"></td>
        </tr>
        <tr>
          <td><div align="right"><a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=editar&amp;id=<? print $id_titulo;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" Alt="Editar"/></a><a href="?url=tablas&amp;tbl=Comparativo%20de%20Clausulas&amp;accion=eliminar&amp;id=<? print $id_titulo;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" Alt="Eliminar"/></a></div></td>
        </tr>
      </table>
        </form>    </td>
  </tr>
  <tr>
    <td height="30" >&nbsp;</td>
  </tr>
  <tr>
    <td height="92" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
  </tr>
    <tr >
    <td height="1" ></td>
    <td ></td>
    <td ></td>
  </tr>
</table>

