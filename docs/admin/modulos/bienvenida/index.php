<?
session_start();
$tabla=$_GET['tbl'];
$accion=$_GET['accion'];
$t_usuario=$_SESSION["tipo"];

include('../lib/objetos/categoria.php');
include('../lib/objetos/modulo.php');

include('./modulos/tablas/settings.php');

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


<table width="59%" border="0" align="center" cellpadding="0" cellspacing="0" background="login/media/bg.gif" >
  <tr>
    <td  valign="top" ><table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td  valign="top" width="4" bgcolor="#ededed"></td>
          <td  valign="top" height="2" colspan="4" bgcolor="#ededed" style="background-repeat:repeat-x"></td>
          <td  valign="top" width="11" bgcolor="#ededed"></td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#ededed"style="background-repeat:repeat-y"></td>
          <td colspan="4" valign="top" bgcolor="#ededed"><table width="100%" height="72%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td valign="top"><? //include('modulos/menu/menu_tbls.php');
				include('./modulos/menu/menu.php');
				?>
                  &nbsp;</td>
                  </tr><tr>
                <td width="100%" valign="top"><table width="99%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	              <tr bgcolor="#ededed" > 
    	            <td width="80%" height="17"    >&nbsp;
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
                      <tr  bgcolor="#ededed"> <!--FFFFFF-->
                      	<td height="17" > <font class="texMenu">
                        <a href="?url=tablas&tbl=<?php echo $tabla; ?>" >
						 <?php print "&nbsp;&nbsp;".$tabla."-";?></a> 	
   	                   	 <? print $accion;
							}
						?>	
                        	</font>
                      </td>
                      <td >  <font class="texMenu" ><center>
		                    	<a href="#" onclick="javscript:window.history.go(-1)" title="Atras" >&lt;--</a>
		                        //
        		               	<a href="#" onclick="javscript:window.history.go(1)" title="Adelante">--&gt;</a>
                        </center></font>
                    </td>

              </tr>
                    <tr>
                      <td height="218" colspan="2"  valign="top"><? include ($tbl); ?></td>
                    </tr>
                    
                    <tr>
                      <td height="17" colspan="2"  valign="top"><div align="center"></div></td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
