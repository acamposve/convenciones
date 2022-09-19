<?php
include ('../../../../objetos/titulos.php');
require('conexion.php');
$busqueda=$_POST['busqueda'];


if ($busqueda==""){
		print "<h1><strong>Busqueda Sin Parametros</strong></h1>";

}
else
{
	$cadbusca_empresa	= "SELECT  DISTINCT (empresas.nombre_empresa),empresas.nombre_empresa as 'nombre',empresas.codigo_empresa as 'codigo',".
						"articulos_contratos.codigo_titulo_comparativo, ".
						"contratos.fecha_de_inicio, ".
						"contratos.fecha_de_termino, ".
						"contratos.duracion ".
						"FROM titulos ".
						"INNER JOIN articulos_contratos ON titulos.codigo_titulo_comparativo =articulos_contratos.codigo_titulo_comparativo ".
						"INNER JOIN contratos ON articulos_contratos.codigo_contratos = contratos.codigo_contrato  ".
						"INNER JOIN empresas ON contratos.codigo_empresa = empresas.codigo_empresa ".
						"WHERE articulos_contratos.codigo_titulo_comparativo ='".$busqueda."' order by empresas.nombre_empresa " ;


 	function limitarPalabras($cadena, $longitud, $elipsis = "..."){
		$palabras = explode(' ', $cadena);
		if (count($palabras) > $longitud)
			return implode(' ', array_slice($palabras, 0, $longitud)) . $elipsis;
		else
			return $cadena; 
	}
	$columnas= count ($cadbusca);

?>
<input name="Buscar" type="submit"  title="Comparacion" />


	<table  border="1px"  width="150%" > 
		
	
<?php
	$Empresas=mysql_query($cadbusca_empresa, $con);
	$wi=0;
	$espacio=0;
	$control=0;
	$Empresas_2=mysql_query($cadbusca_empresa, $con);
	while ($row = mysql_fetch_array($Empresas_2))
		{
		$Empresa_ori_nombre_2=$row[strip_tags('nombre')];
		$Empresa_ori_codigo_2=$row[strip_tags('codigo')];
		$codigo_titulo_comparativo_2=$row[strip_tags('codigo_titulo_comparativo')];
		$Articulos_listados_2=$titulos->listar_Articulos_Empresa($busqueda,$Empresa_ori_codigo_2);
		if(count($Articulos_listados_2)>=1){
			$espacio=$espacio+1;
		}
	}
	if ($espacio>1)
	{
		$wi=100/$espacio;
	}
	
 	$codigo_contratos="";
	while ($row = mysql_fetch_array($Empresas))
		{
		$Empresa_ori_nombre=$row[strip_tags('nombre')];
		$Empresa_ori_codigo=$row[strip_tags('codigo')];
		$Empresa_ori_fecha_de_inicio=$row[strip_tags('fecha_de_inicio')];
		$Empresa_ori_fecha_de_termino=$row[strip_tags('fecha_de_termino')];
		$Empresa_ori_duracion=$row[strip_tags('duracion')];
		$codigo_titulo_comparativo=$row[strip_tags('codigo_titulo_comparativo')];

		$Articulos_listados=$titulos->listar_Articulos_Empresa($busqueda,trim($Empresa_ori_codigo));
		if(count($Articulos_listados)>=1){
		
		  while( list( $key, $val) = each($Articulos_listados) ) {
	  			if ($Articulos_listados[$key][codigo_contratos]!=$codigo_contratos)
				{	

					print "</td><td valign='top' ";
 					if ($wi>0)
						{
							print " width='".$wi."%'";
						}

					print "><br>".$Empresa_ori_nombre."<br>";
					print "<center><hr><br>Inicio: ".$Empresa_ori_fecha_de_inicio."<br>Fin: ".$Empresa_ori_fecha_de_termino."<br><hr>Duracion: ".$Empresa_ori_duracion."</center><br>";
					$control=1;
				}					
				$codigo_articulo 	= $Articulos_listados[$key][codigo_articulo];
				$nro_articulo 		= $Articulos_listados[$key][nro_articulos];
				$titulo_comparativo = $Articulos_listados[$key][codigo_titulo_comparativo];
				$titulo_articulo 	= $Articulos_listados[$key][titulo_articulo];
				$status_articulo	=$Articulos_listados[$key][status_articulo];
				$codigo_contratos	= $Articulos_listados[$key][codigo_contratos];
				$texto_completo_articulo	=$Articulos_listados[$key][texto_completo_articulo];
				$resumen_articulo	= $Articulos_listados[$key][resumen_articulo];
				$campo_comparativo	= $Articulos_listados[$key][campo_comparativo];
				print "<hr/><hr  width='60%' color='#FFFFFF'/><br>".$titulo_articulo."<br>".$resumen_articulo."<br><hr />";
				print "<a href='#' onclick='mostrar(".$codigo_articulo."); return false' />Articulo Completo</a>
						<div id=".$codigo_articulo." style='visibility:hidden'> <font size='-2' >" .$texto_completo_articulo. " </div></font>";
				
				}
			}			
			
		} 
		if ($control==0)
		{
		print "<h1><strong>Busqueda Sin Resultados</strong></h1>";
		}
	}	

?> 
	</table>
