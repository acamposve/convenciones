<link href="../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="159" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="157" height="17" background="modulos/tablas/media/top_table.jpg" style="background-repeat: no-repeat"></td>
  </tr>
<?
$mod=$modulos->listarModuloporId($id_categoria);

	while( list( $key, $val) = each($mod) ) {
						$Id_modulo = $mod[$key][Id_modulo];
						$Nombre_modulo = $mod[$key][Nombre_modulo];
						$pant=$pantalla->listarPantallasporModulos($Id_modulo);
						while( list( $key2, $val2) = each($pant) ) {
						$Id_pantalla=  $pant[$key2][Id_pantalla];
						$val=$seguridad->contarSeguridadportipo($t_usuario,$Id_pantalla);
						$total++;
						}
						if($total>0||$t_usuario==1){
							if($cont ==1){
								$bg="#E2D0D0";
								$cont=0;
							}else{
								$bg="#F1F1F1";
								$cont=1;
							}
?>

  <tr class="texto">
    <td height="20" valign="middle" bgcolor="<? print $bg; ?>"><p><a href="?url=<? print $desc;?>&tbl=<? print $Nombre_modulo ?>" >&nbsp;&nbsp;<? print $Nombre_modulo;?></a></p></td>
  </tr>
 <?
 $total=0;
 }
 }
 ?>
  <tr>
    <td height="17" background="modulos/tablas/media/botton_table.jpg" class="texto" style="background-repeat:no-repeat">&nbsp;&nbsp;<a href="?url=principal">Ir a Principal</a></td>
  </tr>
</table>

