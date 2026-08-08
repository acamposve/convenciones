<?
$Id_Tipo=$_REQUEST['tipo'];
$enviar = $_REQUEST['enviar'];
$contador=$_REQUEST['contador'];
$action = $_POST["guardar_x"];
if ($action != ""){
	$var=$seguridad->borrarTodo($Id_Tipo);
	for ($i=0 ; $i<=$contador ; $i++){
		$id_pantalla=$_REQUEST['seg'.$i];
		
			if($id_pantalla!=""){
				$var=$seguridad->insert($Id_Tipo,$id_pantalla);
				$enviado=1;
			}
	
	
	}


}

if ($Id_Tipo!="") {
$flag=1;
}

?>
<script type="text/javascript" language="javascript">
<!-- 
function Recargar () {
document.form.submit();
}
-->

</script>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="1" colspan="3"></td>
  </tr>
  <tr>
    <td width="21" height="32" >&nbsp;</td>
    <td width="857" ><a href="?url=seguridad&amp;tbl=usuarios&amp;accion=add"></a></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form id="form" name="form" method="post" action="">
      <table width="100%" height="190" border="0" cellpadding="0" cellspacing="0" >
        <tr valign="top" class="texto">
          <td height="24" colspan="4"><label>
</label>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="44%"><div align="right" class="texto">SELECCIONE UN TIPO DE USUARIO </div></td>
                <td width="56%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <select name="tipo" class="texto" id="tipo" onchange="Recargar()" >
                  <option value="" > SELECCIONE UN TIPO DE USUARIO </option>
                  <? 
		  $var=$tipo_usuario->listarTipo2();
		  		while( list( $key, $val) = each($var) ) {
						$Id_tipo_Usuario = $var[$key][Id_tipo_Usuario];
						$Nombre_tipo = $var[$key][Nombre_tipo];
						
						if($Id_Tipo==$Id_tipo_Usuario) {
		  ?>
                  <option value="<? print $Id_tipo_Usuario?>" selected="selected" > <? print $Nombre_tipo?> </option>
                  <?
		  }else{
		  ?>
                  <option value="<? print $Id_tipo_Usuario?>" > <? print $Nombre_tipo?> </option>
                  <?
		  }
		  }
		  ?>
                </select></td>
              </tr>
            </table>
            <label></label></td>
          </tr>

        <? if ($flag == 1){?>		  <tr>
          <td width="12%" height="24">&nbsp;</td>
          <td width="31%">Id Pantalla </td>
          <td width="31%"><div align="left">Pantalla</div></td>
          <td width="26%"><div align="center">Permitir</div></td>
        </tr>

     <?
	 	$cont =0;
		  $var=$pantalla->listarPantallas2();
		  		while( list( $key, $val) = each($var) ) {
						$id_pantalla = $var[$key][id_pantalla];
						$nombre_pantalla = $var[$key][nombre_pantalla];
						$checked=$seguridad->getSeguridad($id_pantalla,$Id_Tipo);
						$cont ++;
						
	  ?>
        <tr class="<? if (($cont%2)!=0){print 'trTblReg1';	}else {	print 'trTblReg2';	}?>">
          <td height="15">&nbsp;</td>
          <td align="left"><? print $id_pantalla; ?></td>
          <td align="center"><div align="left"><? print $nombre_pantalla; ?></div></td>
          <td align="center"><label>
            <input type="checkbox" name="seg<? print $cont; ?>" value="<? print $id_pantalla; ?>" <? if ($checked){?> checked="checked" <? } ?>/>
          </label></td>
        </tr>

        <? } ?>
        <tr >
        
          <td colspan="4" align="center" valign="bottom" ><div align="left">
            <input name="guardar" type="image"  src="../plantillas/plantilla_admin/images/seguridad_btn.png"   />
            <input type="hidden" name="enviar" value="1" />
            <input type="hidden" name="contador" value="<? print $cont?>" />
            
          </div></td>
        </tr>
		<? }  ?>
      </table>
      </form>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
    <tr>
    <td height="1" colspan="3"></td>
  </tr>
</table>


