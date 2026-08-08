<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META http-equiv="Content-Type" content="text/html;" charset="iso-8859-1">
<title>Contratos Colectivos</title>
<script language="javascript" type="text/javascript" src="../lib/funciones/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	theme : "advanced",
	mode : "specific_textareas",
	editor_selector : "mceEditor"

});

function cerrar_sesion()
    {
        window.location = "?url=logout"
    }
function levantar_sesion2()
    {
        var login2    = document.cabecera.login2.value;
        var pass2    = document.cabecera.pass2.value;
        document.form1.login.value=login2;
        document.form1.pass.value=pass2;
        document.form1.Submit2.click();
    }
</script>
<link rel="stylesheet" type="text/css" href="../plantillas/plantilla_admin/css/admin.css">
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
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

<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />

<meta name="description" content="Sistema de Comparacion de Contratos Colectivos CCCOL AsesoresRRHH.com">
</head>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
<table width="780" align="center" border="0">
  <tr>
    <td>
			<form name="cabecera" method="post" action="">
				<input name="enviar" type="hidden"   value="1" />
        <table style="width:780px;" border="0" cellspacing="0" cellpadding="0"  bgcolor="#6d0c07"  >
          <tr>
            <td width="200" align="center" valign="middle" height="68"><img src="http://localhost:8090/convenciones/img/logo_cccol.jpg" width="219" height="68" /></td>
            <td width="580"  align="right"  background="/plantillas/plantilla_admin/images/barra.png">
							<table align="left" width="100%" border="0">
              <tr align="center">
              	<td width="50%" align="right">
                    <table>
                      <tr>
                        <td valign="top"><span class="txtPnlogin">Usuario:</span></td>
                        <td valign="top"><input name="login2" type="text" class="textfield" id="login2" size="10"></td>
                        <td valign="top"><span class="txtPnlogin">Clave:</span></td>
                        <td valign="top"><input name="pass2" type="password" class="textfield" id="pass" size="10"></td>
                        <td valign="top"><input type="submit" name="" value="Ingresar">

                          &nbsp;</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table></td>
          </tr>
        </table>
      </form></td>
  </tr>
  <?php
if (isset($t_usuario)){
	if($t_usuario!=""){
?>
  	<tr>
    	<td  valign="top" bgcolor="#ededed" style="width:100%;" align="right"><?php include('./menu2.php'); ?></td>
  	</tr>
<?php
	}
} ?>

  <tr>
    <td valign="top">
