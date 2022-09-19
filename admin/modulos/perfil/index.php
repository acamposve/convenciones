<?
session_start();
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
$t_usuario=$_SESSION["tipo"];
include( '../lib/objetos/user.php');
include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');

include('./modulos/perfil/settings.php');

?>
<script language="javascript" type="text/javascript">
<!--
function Recargar(){
document.data.submit();

}
function validarEstado(data){
var pais= data.id_pais.value;
var estado= data.nombreestado.value;

if(pais==""||pais==0){
alert("Debe seleccionar Pais");
return false;
}
if(estado==""||estado==0){
alert("Debe introducir un nombre de Estado");
return false;
}
}

function validarPais(data){
var pais= data.nombrepais.value;

if(pais==""||pais==0){
alert("Debe introducir un nombre de Pais");
return false;
}
}

function validarLocalidad(data){
var pais= data.id_pais.value;
var estado= data.id_estado.value;
var localidad= data.nombrelocalidad.value;
if(pais==""||pais==0){
alert("Debe Seleccionar un Pais");
return false;
}

if(estado==""||estado==0){
alert("Debe Seleccionar un Estado");
return false;
}

if(localidad==""||localidad==0){
alert("Debe escribir localidad");
return false;
}
}

-->
</script>
<style type="text/css">
<!--
.style1 {color: #000000}
-->
</style>




<link href="../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="1010" bgcolor="#ededed" border="0" align="center" cellpadding="0" cellspacing="0" background="login/media/bg.gif" >
  <tr valign="top">
    <td bgcolor="#ededed" valign="top"><table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="12" valign="top"></td>
          <td width="1%" valign="top"></td>
        </tr>
        <tr>
          <td colspan="4" bgcolor="#ededed"><table width="100%" height="72%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#ededed">
              <tr>
                <td  bgcolor="#ededed" valign="top" ><? //include('modulos/menu/menu_tbls.php');
				include('./modulos/menu/menu.php');	?></td>
                </tr><tr>
                <td width="100%" ><table width="100%"  bgcolor="#9d9d9d" height="100%" border="1" align="center" cellpadding="0" cellspacing="0">
	              <tr bgcolor="#ededed" > 
    	            <td width="75%" height="20"    >&nbsp;
	                     <font class="texMenu" >
						<?php print strtoupper($cat);?>&nbsp;
						<?php if ($tabla!="principal") 
							{
							?>
                            </td>
			                <td><font class="texMenu">
							 <?php  $FechaHoy=date("d/m/Y");
								print "Fecha Actual ".$FechaHoy;
							?></font>
                            </td>
                      </tr>
                      <tr  bgcolor="#ededed" valign="top"> <!--FFFFFF-->
                      	<td  valign="top" height="17"> <font class="texMenu">
                        <a href="?url=perfil&tbl=<?php echo $tabla; ?>" >
						 <?php print "&nbsp;&nbsp;".$tabla."-";?></a> 	
   	                   	 <? print $accion;
							}
						?>	
                        	</font>
                      </td>
                      <td valign="middle"> <font class="texMenu" ><center>
		                    	<a href="#" onclick="javscript:window.history.go(-1)" title="Atras" >
                                <img src="../../../plantillas/plantilla_admin/images/atras.png" />
                                </a>

                        </center></font>
                    </td>


              </tr>

                    <tr>
                      <td height="100%" colspan="2" valign="top" ><? include($tbl); ?></td>
                    </tr>
                    
                </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
        <td  class="cabecera" colspan="4"  height="1"  ></td>
        </tr>
      </table></td>
  </tr>
</table>
