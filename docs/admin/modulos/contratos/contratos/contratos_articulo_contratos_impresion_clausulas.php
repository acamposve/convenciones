<META http-equiv="Content-Type" content="text/html; charset=iso-UTF-8">
<?
//$codigo_articulo=$_GET['codigo_contrato'];
$empresa_datos=$contratos->obtenerContratosPorId ($codigo_contrato);
$codigo_empresa= $contratos->codigo_empresa	;

$datos_empresa=$empresas->getId($codigo_empresa);
$logo=$empresas->logo;

$var=$contratos->listarArticulosContratos($codigo_contrato,0,500,"");
		if ($logo==""){
			$imagen="";
		}else{
			$imagen="<img src='../admin/modulos/tablas/empresa/documentos/$codigo_empresa/$logo'  border='0' alt='logo'/><br>";
		}
	    while( list( $key, $val) = each($var) )
			{
				$articulo_cerrado =$var[$key][articulo_cerrado];
				if ($articulo_cerrado!=1){
					$texto_completo_articulo = $var[$key][texto_completo_articulo];
					$completo = $completo . $texto_completo_articulo . "<br/>";
				}
			}

	if (strip_tags($completo)=="")
	{	
		$completo='<center><br><h1>'."NO se Encontraron Clausulas".'</h1></center>';
		?>
		<script language="javascript">
			alert("NO se Encontraron Clausulas");
		</script>
        <?
	}


$contr=$contratos->ListarContratoDefinido( $codigo_contrato,1);
	    while( list( $key, $val) = each($contr) )
			{
				$nombre_empresa = $contr[$key][nombre_empresa];
			}
$titulo = "Empresa: " .$nombre_empresa."<br>";

?>

	<script  type="" language="">
		//alert ("Realice todas las Modificaciones e \n Imprima esta Clausula, este proceso \n no generara NINGUNA modificación \n en la Clausula Original.") 
	</script>


    <link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="22" height="32" class="">&nbsp;</td>
    <td width="691" ><div align="center">
	<font >  <?print $nombre_empresa;?></font>
    </div></td>

  </tr>
  <tr>
    <td >&nbsp;</td>
    <td rowspan="3" valign="top" ><form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="2" class=" trTblReg1">
        <tr>        </tr>
        <tr>
          <td height="16">Texto de la Clausula: </td>
        </tr>
        <tr>
          <td height="16" colspan="3"><textarea name="texto_completo"   cols="90" rows="35"  class="mceEditor"  id="texto_completo"><? print  $imagen.$titulo .$completo ?></textarea></td>
        </tr>
        <tr>
        </tr>
        
        <tr>
          <td colspan="3" valign="bottom"><label></label>
				<input name="Submit" type="submit" class="linkAgregar" value="Imprimir"  />
				<input type="hidden" name="nom_emp"  id="nom_emp" value="<? echo $nombre_empresa ?>" />
				<input type="hidden" name="enviar" value="1" /></td>
        </tr>
      </table>
    </form></td>
 

 
 
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


<script language="javascript">
var buttonobject= document.form1.Submit;
function impresion_auto() {   

	buttonobject.click();
	}   
	//window.onload=impresion_auto; 
</script>
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

