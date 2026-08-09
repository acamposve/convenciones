<META http-equiv="Content-Type" content="text/html; charset=iso-UTF-8">
<?
$id=$_GET['id'];
$bitacora=$_GET['bitacora'];
$seccion= $_GET['seccion'];
$page = $_GET['page'];
//DATOS GENERALES
	  $var=$discusion->listarDiscusiones( 0,500, $filtro,0);
	  while( list( $key, $val) = each($var) ) {
            $codigo_bitacora = $var[$key][codigo_bitacora];
			$fecha_inicio_disc = $var[$key][fecha_inicio_disc];
			$estatus_bitacora = $var[$key][estatus_bitacora];
			$paginador=$paginador+1;
			if ($bitacora==$codigo_bitacora){
				$codigo_empresa = $var[$key][codigo_empresa];
				$nombre_empresa=$empresas->getNombreEmpresa($codigo_empresa);				
				break;
				}
		}

//SI ES ACUERDO
	$texto_completo="";
	if ($seccion=="ACU"){
		$Seccion="Acuerdo";
		$listar="listar_acuerdos";
		  $var=$discusion->listarAcuerdos( 0,500,  $codigo_bitacora, "");
		  while( list( $key, $val) = each($var) ) {
			$codigo_acuerdos			= $var[$key][codigo_acuerdos];
			$codigo_bitacora			= $var[$key][codigo_bitacora];
			if ($id==$codigo_acuerdos){
				$texto_completo		=	"<font size='+2'>".$nombre_empresa."</font><br><br>".$var[$key][titulo_articulo]." : ".$var[$key][nro_articulo]."<br><br>".$var[$key][texto_completo_articulo]."<br><br>".$var[$key][fecha_acuerdo];
				}
			}
	
		}
	if ($seccion=="PET"){
		$Seccion="Peticion";
		$listar="listar_peticiones";
		$var=$discusion->listarPeticiones(0,500, $codigo_bitacora,"" );
		while( list( $key, $val) = each($var) ) {
	        $codigo_peticion = $var[$key][codigo_peticion];
			if ($id==$codigo_peticion){
				$texto_completo		=	"<font size='+2'>".$nombre_empresa."</font><br><br>".$var[$key][titulo_peticion]." : ".$var[$key][nro_peticion]."<br><br>".$var[$key][texto_completo_peticion]."<br><br>";
				}
			}
		}
	if ($seccion=="OFE"){
		$Seccion="Oferta";
		$listar="listar_ofertas";
		$var=$discusion->listarOfertas(0,500, $codigo_bitacora,"" );
		while( list( $key, $val) = each($var) ) {
	        $codigo_ofertas = $var[$key][codigo_ofertas];
			if ($id==$codigo_ofertas){
				$texto_completo		=	"<font size='+2'>".$nombre_empresa."</font><br><br>".$var[$key][titulo_oferta]." : ".$var[$key][nro_oferta]."<br><br>".$var[$key][texto_completo_oferta]."<br><br>";
				}
			}
		}
	if ($seccion=="REU"){
		$Seccion="Reunion";
		$listar="listar_reuniones";
		$var=$discusion->listarReunionesSelect( 0,500 ,$codigo_bitacora,"");
	  	while( list( $key, $val) = each($var) ) {
            $codigo_reunion = $var[$key][codigo_reunion];
			if ($id==$codigo_reunion){
				$texto_completo		=	"<font size='+2'>Minuta</font><br><br><font size='+2'>".$nombre_empresa."</font><br><br>".$var[$key][fecha_reunion]." <br><br> ".$var[$key][asistentes_reunion]."<br><br>".$var[$key][detalles_reunion]."<br><br>";
				}
			}
		}

?>

	<script  type="" language="">
		//alert ("Realice todas las Modificaciones e \n Imprima esta Clausula, este proceso \n no generara NINGUNA modificación \n en la Clausula Original.") 
	</script>

    <link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="22" height="32">&nbsp;</td>
    <td width="691" ><div align="center">
	   <? print $nombre_empresa."<br>".$Seccion ;?>
       
	</div></td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class="trTblReg1">

        <tr>
          <td height="16">Texto Completo </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="texto_completo" cols="90" rows="40"  class="mceEditor" id="texto_completo"><? print  $texto_completo ?></textarea></td>
        </tr>
        <tr>
          <td height="16">
          <input type="hidden" name="enviar" value="1" />
          <input type="hidden" name="id" value="<? print $id?>" />
          <input type="hidden" name="bitacora" value="<? print $bitacora?>" />
          <input type="hidden" name="seccion" value="<? print $seccion?>" />
          <input type="hidden" name="listar" value="<? print $listar?>" />
          <input type="hidden" name="page" value="<? print $page?>" />
          </td>

        </tr>
        <tr>
          <td colspan="3" valign="bottom"><label></label>
                <input name="Submit" type="submit" class="linkAgregar" value="Imprimir"  /></td>
        </tr>
      </table>
    </form>

<script type="text/javascript" src="../lib/funciones/js/tiny_mce/tiny_mce.js"></script> 
<script type="text/javascript"> 
tinyMCE.init(
		{ 
			mode : "textareas", 
			theme : "advanced", 
			plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
			
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,cut,copy,paste,pastetext,pasteword,cleanup",
 			theme_advanced_buttons2 : "styleselect,formatselect,fontselect,fontsizeselect", 
			theme_advanced_buttons3 : "bullist,numlist,|,outdent,indent,blockquote,undo,redo,|,link,unlink,|,insertdate,inserttime,preview,|,forecolor,backcolor", 
			theme_advanced_toolbar_location : "top", 
			theme_advanced_toolbar_align : "left", 
			
			plugins : 'inlinepopups', 
			setup : function(ed) 
			{ 
			// Add a custom button 
			ed.addButton('mybutton', 
			{ 

			onclick : function() 
			{ 
			// Add you own code to execute something on click 
 
			} 
}); 
} 
}); 
</script> 

</td>
 
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
</table>
