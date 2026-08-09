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
$id=$tipo_usuario->id;

$fech_venc=$user->fech_venc; 
$estatus=$user->estatus; 

?>

<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo9 {font-size: 12px}
-->
</style>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="13" height="32">&nbsp;</td>
    <td width="505" ><? print $msg; 
	$msg="";?></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">
        <tr>
          <td width="50%">Login del Usuario: </td>
          <td> Fecha Expiración Usuario</td>

        </tr>
        <tr>
          <td><span class="Estilo9">
            <label> <? print $login; ?> </label>
          </span></td>
          <td> <? print $fech_venc ?></td>
        </tr>
        <tr>
          <td>Clave:</td>
          <td>Estatus 
        </tr>
        <tr>
          <td><span class="Estilo9"><? print $password; ?></span></td>
          <td><span class="Estilo9">
          	<?php 
				if ($estatus=="HAB"){
					print "Habilitado";
				}elseif($estatus=="BLO"){
					print "Bloqueado";
				}else{
					print "No Definido";
				}
			  ?>
		</span>
          </td>

        </tr>
        <tr>
          <td>Nombre del  Usuario: </td>
        </tr>
        <tr>
          <td><span class="Estilo9"><? print $nombre ?></span></td>
        </tr>
        <tr>
          <td>Tipo de Usuario </td>
        </tr>
        <tr>
          <td><span class="Estilo9"><? print $nombre_tipo; ?></span> </td>
        </tr>
        <tr>
	<tr>
          <td>
          Empresa:
            <br/>
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
              </p>          </td>
        </tr>
        <tr>        <tr>
          <td>Email:</td>
        </tr>
        <tr>
          <td><span class="Estilo9"><? print $email; ?></span></td>
        </tr>
        <tr>
          <td>Telefono:</td>
        </tr>
        <tr>
          <td><span class="Estilo9"><? print $telefono; ?></span></td>
        </tr>
        <tr>
          <td>Direccion:</td>
        </tr>
        <tr>
          <td><span class="Estilo9"><? print $direccion; ?></span></td>
        </tr>
        <tr>
          	<td colspan="3" valign="bottom"><label></label>            
          	<div align="right">Desea Eliminar este Tipo de Usuario:&nbsp;&nbsp;
        	<a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=eliminar&amp;id=<? print $id_user;?>&amp;flag=SI"><img src="../plantillas/plantilla_admin/images/boton_si.gif" width="18" height="11" border="0" />&nbsp;&nbsp;</a><a href="?url=seguridad&amp;tbl=Usuarios&amp;accion=listar"><img src="../plantillas/plantilla_admin/images/boton_no.gif" width="18" height="11" border="0" /></a></div></td>
        </tr>
      	</table>
		</form></td>
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