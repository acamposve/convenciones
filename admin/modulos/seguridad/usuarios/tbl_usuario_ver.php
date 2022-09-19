<?
$id_user=$_GET['id'];
$var=$user->getId($id_user);
$nombre=$user->Nombre_usuario;
$login=$user->Login_usuario; 
$password=$user->password; 
$direccion=$user->direccion_usuario; 
$empresa=$user->codigo_empresa;
$telefono=$user->telefono_usuario; 
$email=$user->email_usuario; 
$Codigo_Tipo=$user->Codigo_tipo_usuario; 
$var=$tipo_usuario->getId($Codigo_Tipo);
$nombre_tipo=$tipo_usuario->nombre;
$fech_venc=$user->fech_venc; 
$estatus=$user->estatus; 
?>
<style type="text/css">
<!--
.Estilo9 {
	font-size: 12px
}
-->
</style>
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="1%" height="32" >&nbsp;</td>
    <td width="99%" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><fieldset>
      <legend>Ver Usuario</legend>
      <form name="form1" method="post" action="">
        <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
          <tr>
            <th><div align="left">Login del Usuario: </div></th>
            <td><div align="left"><span class="Estilo9">
            </span></div>              <span class="Estilo9"><label> 
              <div align="left"><? print $login; ?> </div>
              </label>
              </span></td>
            <th> <div align="left">Fecha Expiración Usuario</div></th>
            <td><div align="left"><? print $fech_venc ?></div></td>
          </tr>
          <tr>
            <th><div align="left">Clave:</div></th>
            <td><div align="left"><span class="Estilo9"><? print $password; ?></span></div></td>
            <th><div align="left">Estatus </div></th>
            <td><div align="left"><span class="Estilo9">
              <?php 
				if ($estatus=="HAB"){
					print "Habilitado";
				}elseif($estatus=="BLO"){
					print "Bloqueado";
				}else{
					print "No Definido";
				}
			  ?>
            </span> </div></td>
          </tr>
          <tr >
            <th><div align="left">Nombre del  Usuario: </div></th>
            <td><div align="left"><span class="Estilo9"><? print $nombre ?></span></div></td>
            <th><div align="left">Tipo de Usuario </div></th>
            <td><div align="left"><span class="Estilo9"><? print $nombre_tipo; ?></span> </div></td>
          </tr>
          <tr>
            <th><div align="left">Email:</div></th>
            <td><div align="left"><span class="Estilo9"><? print $email; ?></span></div></td>
            <th><div align="left">Telefono:</div></th>
            <td><div align="left"><span class="Estilo9"><? print $telefono; ?></span></div></td>
          </tr>
          <tr>
            <th> <div align="left">Empresa: </div></th>
            <td colspan="3"><div align="left">
              <select name="empresa" class="textfield" id="empresa" disabled="disabled">
                <option value="0">Seleccione una Empresa</option>
                <?
			$var=$empresas->listarEmpresas2();
	  
			while( list( $key, $val) = each($var) ) {
						$codigo_empresa = $var[$key][codigo_empresa];
						$nombre_empresa = $var[$key][nombre_empresa];
						
				if($empresa==$codigo_empresa){
			?>
                <option value="<? print $codigo_empresa; ?>" selected="selected"><? print $nombre_empresa; ?> </option>
                <?
			}else{
			?>
                <option value="<? print $codigo_empresa; ?>"><? print $nombre_empresa; ?> </option>
                <?
			}
			}
			?>
              </select>
            </div></td>
          </tr>
          <tr>
            <th><div align="left">Direccion:</div></th>
            <td colspan="3"><div align="left"><span class="Estilo9"><? print $direccion; ?></span></div></td>
          </tr>
          <tr>
            <td colspan="4"><div align="right"><a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=editar&amp;id=<? print $id_user;?>"><img src="../plantillas/plantilla_admin/images/boton_editar.gif" width="28" height="28" border="0" alt="Editar" /></a><a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=eliminar&amp;id=<? print $id_user;?>"><img src="../plantillas/plantilla_admin/images/boton_eliminar.gif" width="28" height="28" border="0" alt="Editar"/></a></div></td>
          </tr>
        </table>
      </form>
      </fieldset></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td height="109" >&nbsp;</td>
  </tr>
  <tr>
    <td height="10" ></td>
    <td ></td>
  </tr>
</table>
