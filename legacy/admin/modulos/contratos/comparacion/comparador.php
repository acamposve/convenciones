<?php 
//Sesion y Usuario
session_start();
$t_usuario=$_SESSION["tipo"];

include"../../../../lib/funciones/php/contratos/comparacion/conexion.php";
include ('../../../../lib/objetos/contratos.php');
include ('../../../../lib/objetos/categoria_titulos.php');
include_once ('../../../../lib/objetos/titulos.php');
include_once ('../../../../lib/objetos/ley_trabajo.php');

include_once ('../../../../lib/objetos/seguridad.php');
include_once ('../../../../lib/objetos/pantalla.php');

include_once ('../../../../lib/objetos/sectores.php');
include_once ('../../../../lib/objetos/estados.php');
include_once ('../../../../lib/objetos/tipos_empresas.php');
include_once ('../../../../lib/objetos/categoria_sector.php');
include_once ('../../../../lib/objetos/actividad_empresa.php');

// SEGURIDAD
$url="comparador.php";



$t="";
$empresa_dato	=$_GET['dato'];
$fields = $_GET['fields']; 

$id_sector=$_GET['sector_empresa'];
$codigo_tipo=$_GET['tipo_empresa'];
$estados=$_GET['estados'];

 
?>
<script src="../../../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../../../../SpryAssets/SpryCollapsiblePanel_2.css" rel="stylesheet" type="text/css" />

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="../../../../plantillas/plantilla_admin/css/admin.css" rel="stylesheet" type="text/css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>..::Comparacion de Contratos::.. Asesores RRHH C.A.</title>
<script>
function doPrint(){
document.all.item("noprint").style.visibility='hidden' 
window.print()
document.all.item("noprint").style.visibility='visible'
}

<!--
function Recargar(){
document.frmbusqueda.submit();
-->
}
function mostrar(id) {
  obj = document.getElementById(id);
  obj.style.visibility = (obj.style.visibility == 'hidden') ? 'visible' : 'hidden';
}
</script>
</head>
<body  bgcolor="#d9d7d7">
<form name="frmbusqueda" onSubmit="buscarDato(); return false" action="<? $_SERVER["PHP_SELF"];?>"  method="get">
  <div id="noprint">
  <table  border="1"  width="100%" >
    <tr bgcolor="#6d0c07">
      <td bgcolor="#6d0c07" ><img src="../../../../plantillas/plantilla_admin/images/LogoCCCColBlanco.png" alt=""  /></td>
    </tr>
    <tr>
      <td   colspan="5"><font  class="tex2" > Definiciones </font><br />
<?php
	$categorias			=$categorias_titulos->listarCategoria_comparacion();
	$x=0;
		while (list( $key, $val) = each($categorias)) 
			{
			$codigo_categoria_titulos		=	$categorias[$key][codigo_categoria_titulos];
			$nombre_categoria				=	$categorias[$key][nombre_categoria];
			$descripcion_categoria			=	$categorias[$key][descripcion_categoria];
			$x=$x+1;
?>
        <div id="CollapsiblePanel<? echo $codigo_categoria_titulos?>" class="CollapsiblePanel" >
          <div class="CollapsiblePanelTab" tabindex="0"><? echo $nombre_categoria?> </div>
          <div class="CollapsiblePanelContent " > <? print $descripcion_categoria ?> </div>
        </div>
        <script type="text/javascript">
			<!--
			var CollapsiblePanel<? echo $codigo_categoria_titulos?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_categoria_titulos?>",{contentIsOpen:false});
			//-->
			</script>
        <?
			}
			?>
      </div >
    
      </td>
    
      </tr>
    
    <tr>
      <td></td>
    </tr>
      </tr>
    
    <tr>
      <td><font size="-1">Sector Empresa: </font></td>
      <td><font size="-1">Tipo Empresa: </font></td>
      <td><font size="-1">Ubicaci&oacute;n - Estado</font></td>
      <td><font size="-1">&nbsp; </font></td>
    </tr>
    <tr>
      <td><select name="sector_empresa" class="textfield" id="sector_empresa"  onchange="Recargar()" >
          <option value="0" selected="selected">Seleccione Sector</option>
          <?
                	$var=$sectores->listarSectores_comparativo();
                	while( list( $key, $val) = each($var) ) {
                    	$codigo_Sector = $var[$key][codigo_sector];
                        $nombre_Sector = $var[$key][nombre_sector];
                        if($id_sector==$codigo_Sector){
                                    $msg=conio1;
                        ?>
          <option value="<? print $codigo_Sector; ?>" selected="selected"> <? print $nombre_Sector; ?> </option>
          <? }else{
                    	$msg=conio1
                         ?>
          <option value="<? print $codigo_Sector; ?>" > <? print $nombre_Sector; ?> </option>
          <?
                        }
        	      }
               ?>
        </select></td>
      <td><select name="tipo_empresa" class="textfield" id="tipos"  onchange="Recargar()" >
          <option value="0" selected="selected">Seleccione Tipo de Empresa</option>
          <?
						$var=$tipos_empresas->listarTipos_Comparativo();
						
							while( list( $key, $val) = each($var) ) {
							$codigo_Tipo = $var[$key][codigo_tipo];
							$nombre_Tipo = $var[$key][nombre_tipo];
							if($codigo_tipo==$codigo_Tipo){
							?>
          <option value="<? print $codigo_Tipo; ?>" selected="selected"> <? print $nombre_Tipo; ?> </option>
          <? }else{
			?>
          <option value="<? print $codigo_Tipo; ?>"> <? print $nombre_Tipo; ?> </option>
          <?
						}
						
						}
					?>
        </select></td>
      <td><select name="estados" class="textfield" id="estados" onChange="Recargar()" >
          <option value="0" selected="selected">Ubicaci&oacute;n - Estado</option>
          <?
						$var=$estado->listarEstados_Comparativo();
						
							while( list( $key, $val) = each($var) ) {
							$Id_estado = $var[$key][Id_estado];
							$Nombre_estado = $var[$key][Nombre_estado];
							if($Id_estado==$estados){
							?>
          <option value="<? print $Id_estado; ?>" selected="selected"> <? print $Nombre_estado; ?> </option>
          <?  }else{
			
			?>
          <option value="<? print $Id_estado; ?>"> <? print $Nombre_estado; ?> </option>
          <?
			}
						}
					?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <tr>
      <td><?
		$categorias_titulos	=$titulos->listarTitulos_comparacion();
		?>
        <select name="dato" class="textfield"  onchange="Recargar()">
          <option value="" selected="selected" >Seleccione un Titulo Comparativo</option>
          <?php	
				$x=0;
				$nombre_categoria_ordenamiento="";
			while (list( $key, $val) = each($categorias_titulos)) 
				{
				$codigo_titulo_comparativo	=	$categorias_titulos[$key][codigo_titulo_comparativo];
				$nombre_titulo				=	$categorias_titulos[$key][nombre_titulo];
				$codigo_categoria_titulo	=	$categorias_titulos[$key][codigo_categoria_titulo];
				$nombre_categoria			=	$categorias_titulos[$key][nombre_categoria];				
				$codigo_categoria_titulos_original	=	$categorias_titulos[$key][codigo];		
				$x=$x+1;
				if ($nombre_categoria_ordenamiento!=$nombre_categoria)
					{
					?>
          <option  value=""  onchange=""><? print $nombre_categoria ?></option>
          <option  value="<? print $codigo_titulo_comparativo; ?>" <?php if($empresa_dato==$codigo_titulo_comparativo){ ?> selected="selected" <?php } ?> > <?php print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nombre_titulo ?></option>
          <? 
					$nombre_categoria_ordenamiento=$nombre_categoria;
					}
				else
					{
					?>
          <option  value="<? print $codigo_titulo_comparativo; ?>" <?php if($empresa_dato==$codigo_titulo_comparativo){ ?> selected="selected" <?php } ?> ><? print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nombre_titulo ?></option>
          <? 
					}
				}
				?>
        </select></td>
    </tr>
    <?php
                    if ($empresa_dato!=""){
                        print "<br>";
	$busqueda_enriquecida= " ";
	if ($id_sector!=0){
		$busqueda_enriquecida=$busqueda_enriquecida. " and codigo_sector = '".$id_sector."' ";
		}
	if ($codigo_tipo!=0){
		$busqueda_enriquecida=$busqueda_enriquecida. " and codigo_tipo = '".$codigo_tipo."' ";
		}
	if ($estados!=0){
		$busqueda_enriquecida=$busqueda_enriquecida. " and codigo_estado = '".$estados."' ";
		}

						//Creacion  de la Busqueda
						$conteo_empresa=count($fields);
							if (is_array($fields)) { 
								$busqueda_empresa=" and codigo_contrato in (";
								for ($i=0;$i<count($fields);$i++) { 
									if (count($fields)>($i+1)) {
										$busqueda_empresa .= "  $fields[$i],";
									}else{
										$busqueda_empresa .= "  $fields[$i])";
									}
									
								} 
		
							}  
                            $cadbusca_empresa	=	"SELECT distinct(nombre_empresa), codigo_contrato,  contratos.status_publicacion  FROM articulos_contratos ".
                                                    "inner join contratos on contratos.codigo_contrato = articulos_contratos.codigo_contratos ".
                                                    "inner join empresas  on empresas.codigo_empresa   = contratos.codigo_empresa  ".
                                                    "WHERE articulos_contratos.codigo_titulo_comparativo='".$empresa_dato."'".$busqueda_enriquecida." and contratos.status_publicacion='PU'".
													"order by nombre_empresa"  ;



                            $listar_Empresa=mysql_query($cadbusca_empresa, $con);
                            $x=0;
                            while ($row = mysql_fetch_array($listar_Empresa)){
								$x=$x+1;
								//// 6 COLUMNAS DE NOMBRES
								if (($x)==1){
									print "<tr><td width='25%'>";
									print "&nbsp;&nbsp;<font size='-1'><input name='fields[]' type='checkbox' value=".$row['codigo_contrato']." /> &nbsp;";
	       	                        print $row['nombre_empresa']."</td>";
									}elseif(($x)==2){
									print "<td width='25%'>";
									print "&nbsp;&nbsp;<font size='-1'><input name='fields[]' type='checkbox' value=".$row['codigo_contrato']." /> &nbsp;";
									print $row['nombre_empresa']."</td>";
									}elseif(($x)==3){
									print "<td width='25%'>";
									print "&nbsp;&nbsp;<font size='-1'><input name='fields[]' type='checkbox' value=".$row['codigo_contrato']." /> &nbsp;";
									print $row['nombre_empresa']."</td>";
									}elseif(($x)==4){
									print "<td width='25%'>";
									print "&nbsp;&nbsp;<font size='-1'><input name='fields[]' type='checkbox' value=".$row['codigo_contrato']." /> &nbsp;";
									print $row['nombre_empresa']."</td>";
									$x=0;
									
									}
								}
                            }
                            ?>
      </font>
    
    <tr>
      <td><div align="left" >Termino a buscar:
          <input name="buscar" type="submit"  title="Comparacion"   />
          <!--<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" onclick="doPrint()" class="linkAgregar"> Imprimir </a>
						--> 
        </div></td>
    </tr>
    <tr >
      <td ></td>
    </tr>
  </table>
  </div>
  <?php
                	if ($busqueda_empresa!=""){

if ($empresa_dato==""){
		print "<h1><strong>Eliga el Titulo Comparativo</strong></h1>";

}
else
{
	//Empresa a Buscas
	$cadbusca_empresa	=	"SELECT distinct(nombre_empresa) AS empresa, codigo_contrato, fecha_de_inicio, fecha_de_termino, duracion ".
							"FROM articulos_contratos ".
							"inner join contratos on contratos.codigo_contrato = articulos_contratos.codigo_contratos ".
							"inner join empresas  on empresas.codigo_empresa   = contratos.codigo_empresa  ".
							"WHERE articulos_contratos.codigo_titulo_comparativo='".$empresa_dato."'".$busqueda_empresa." order by nombre_empresa"  ;



  	//Titulos Comparativos para LOT y Otras Leyes
	//LISTAR LEYES PARA COMPARADOR 
	
	?>
  <div align="left">
  <table width="85%" >
    <tr>
      <td align="left" valign="top"><?

		print 	"<font  class='tex2'>Leyes Relacionadas<br></font>";
		$lot_listar	=	"SELECT * from articulos_ley_trabajo  where codigo_titulo_comparativo= ".$empresa_dato;
		echo $lot_listar;
		$lot=0;
		$lot_result	 = mysql_query($lot_listar); 
		while ($fila = mysql_fetch_assoc($lot_result)){
			if ($lot==0){
				print  "<center>LEY ORGANICA DEL TRABAJO</center>";
				}
		
			$lot_nro_art 		= $fila['nro_articulo'];
			$lot_texto_completo = $fila['texto_completo_articulos'];
			$lot_titulo			= $fila['titulo_articulo'];
			?>
        <div id="CollapsiblePanel<? print $lot_nro_art?>" class="CollapsiblePanel" >
          <div class="CollapsiblePanelTab" tabindex="0"><? print "#".$lot_nro_art."--".$lot_titulo?> </div>
          <div class="CollapsiblePanelContent " > <? print $lot_texto_completo ?> </div>
        </div>
        <script type="text/javascript">
			<!--
			var CollapsiblePanel<? echo $lot_nro_art?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $lot_nro_art?>",{contentIsOpen:false});
			//-->
			</script>
        <?
			$lot=$lot+1;
			}
			?>
      </div >
    
    <?PHP
		//LISTAR OTRAS LEYES	
		$otras_leyes_listar_leyes	= "SELECT DISTINCT (nombre_ley ), otras_leyes.codigo_otra_ley from articulos_otras_leyes ".
									" inner join otras_leyes on articulos_otras_leyes.`codigo_otra_ley` = otras_leyes.codigo_otra_ley ".
									" where codigo_titulo_comparativo=".$empresa_dato." order by otras_leyes.nombre_ley";
		$otras_leyes_articulos_pra	= "select * from articulos_otras_leyes  where codigo_titulo_comparativo=".$empresa_dato." and codigo_otra_ley= ";

		$otras_leyes_result_leyes	 = mysql_query($otras_leyes_listar_leyes); 
		while ($fila = mysql_fetch_assoc($otras_leyes_result_leyes)){
			$otra_ley_nombre = $fila['nombre_ley'];
			$otra_ley_codigo = $fila['codigo_otra_ley'];
			print "<hr><center>".$otra_ley_nombre."</center>";
			$otras_ley_busqueda_concatenada= $otras_leyes_articulos_pra	.	$otra_ley_codigo ;
			
			$otras_ley_busqueda_arti	 = mysql_query($otras_ley_busqueda_concatenada); 

			while ($row = mysql_fetch_assoc($otras_ley_busqueda_arti)){
					$otra_ley_codigo_articulo 			= $row['codigo_articulo'];
					$otra_ley_nro_articulo 				= $row['nro_articulo'];
					$otra_ley_titulo_articulo 				= $row['titulo_articulo'];
					$otra_ley_texto_compelto_articulo 	= $row['texto_completo_articulo'];
				?>
    <div id="CollapsiblePanel<? print $otra_ley_codigo_articulo?>" class="CollapsiblePanel" >
      <div class="CollapsiblePanelTab" tabindex="0"><? print "#".$otra_ley_nro_articulo."--".$otra_ley_titulo_articulo?> </div>
      <div class="CollapsiblePanelContent " > <? print $otra_ley_texto_compelto_articulo ?> </div>
    </div>
    <script type="text/javascript">
                        <!--
                        var CollapsiblePanel<? echo $otra_ley_codigo_articulo?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $otra_ley_codigo_articulo?>",{contentIsOpen:false});
                        //-->
                        </script>
      </div >
    
    <?
				}
	
			}
			?>
      </td>
      </tr>
  </table>
  </div>
  <table  border="2px"   width="150%"  >
    <?php
	$control=0;

 	$Empresas=mysql_query($cadbusca_empresa, $con);
	$Empresas_conteo=mysql_query($cadbusca_empresa, $con);

	while ($row = mysql_fetch_array($Empresas_conteo)){
	$t.=+1;
	}
	$wi=strlen($t);
	$ancho=(100/$wi);
	while ($row = mysql_fetch_array($Empresas))

		{
		$Empresa_codigo	=$row[strip_tags('codigo_contrato')];
		$Contrato_busqueda			=$Empresa_ori_codigo_clausula;
		$Nombre_empresa				=$row[strip_tags('empresa')];
		$Empresa_fecha_de_inicio	=$row[strip_tags('fecha_de_inicio')];
		$Empresa_fecha_de_termino	=$row[strip_tags('fecha_de_termino')];
		$Empresa_duracion			=$row[strip_tags('duracion')];
		print "<td valign='top' aling='left' width='".$ancho."%' ><br>".$Nombre_empresa."<br>";
		print "<hr> <font class='textAlignLeft'> Inicio: ".$Empresa_fecha_de_inicio."</font><br><font class='textAlignLeft'>Fin: ".$Empresa_fecha_de_termino."</font><br><font class='textAlignLeft'>Duracion: ".$Empresa_duracion."</font><br>";

		$cadbusca_clau	=	"SELECT * FROM articulos_contratos ".
							"inner join contratos on contratos.codigo_contrato = articulos_contratos.codigo_contratos ".
							"inner join empresas  on empresas.codigo_empresa   = contratos.codigo_empresa  ".
							"WHERE articulos_contratos.codigo_titulo_comparativo='".$empresa_dato."' and contratos.codigo_contrato='".$Empresa_codigo."' order by nombre_empresa,nro_articulos"  ;

		$clausulas=mysql_query($cadbusca_clau, $con);
		$control="";
		while ($row = mysql_fetch_array($clausulas))
			{
			$y=$y+1;
			$codigo_articulo 		=	$row[strip_tags('codigo_articulo')];
			$nro_articulos			=	$row[strip_tags('nro_articulos')];
			$titulo_clausula		=	$row[strip_tags('titulo_articulo')];
			$texto_completo_articulo=	$row[strip_tags('texto_completo_articulo')];
			$resumen_articulo		=	$row[strip_tags('resumen_articulo')];
		if ($row[strip_tags('resumen_articulo')]=="") {	$resum_art="SIN TEXTO RESUMEN";	}else{$resum_art=$row['resumen_articulo'];	}
			print 	"<table border='2px' width='100%'><tr><td>".
					"<br>Nro Clausula: ".$nro_articulos."<br><br><font size='-1' >".
					$titulo_clausula."</font><br>".
					$resum_art."<br><br>"; 
			?>
    <div id="CollapsiblePanel<? echo $codigo_articulo.$y.$control ?>" class="CollapsiblePanel" >
      <div class="CollapsiblePanelTab" tabindex="0">Articulo Completo</div>
      <div class="CollapsiblePanelContent "  > <? print $texto_completo_articulo."  <hr  color='#6d0c07'/>" ?> </div>
    </div>
    <script type="text/javascript">
			<!--
			var CollapsiblePanel<? echo $codigo_articulo.$y.$control ?> = new Spry.Widget.CollapsiblePanel("CollapsiblePanel<? echo $codigo_articulo.$y.$control?>",{contentIsOpen:false});
			//-->
			</script>
    <?
 			print "</div></table>";		

			$control=$control+1;
			$wi=$wi+1;
		}
	}  

}	

					
}
?>
  </table>
</form>
<div id="resultado"></div>
</body>
</html>