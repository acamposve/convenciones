<?php
require('conexion.php');
$busqueda=$_POST['busqueda'];

if ($busqueda==""){
		print "<h1><strong>Busqueda Sin Parametros</strong></h1>";

}
else
{
	$cadbusca= "SELECT titulos.codigo_titulo_comparativo,". //0
				"titulos.nombre_titulo,". //1
				"titulos.descripcion_titulo, ". //2
				"articulos_contratos.codigo_articulo,". //3
				"articulos_contratos.codigo_contratos,". //4
				"articulos_contratos.nro_articulos,". //5
				"articulos_contratos.texto_completo_articulo,". //6
				"articulos_contratos.resumen_articulo,". //7
				"articulos_contratos.codigo_titulo_comparativo,". //8
				"articulos_contratos.campo_comparativo,". //9
				"articulos_contratos.titulo_articulo,". //10
				"articulos_contratos.articulo_cerrado,". //11
				"contratos.codigo_contrato, ". //12
				"contratos.pdf_auto_acta, ". //13
				"contratos.fecha_de_inicio,". //14
				"contratos.fecha_de_termino,". //15
				"contratos.duracion,". //16
				"contratos.ambito_aplicacion, ". //17
				"contratos.codigo_empresa, ". //18
				"contratos.status_publicacion, ". //19
				"empresas.codigo_empresa,". //20
				"empresas.nombre_empresa ". //21
				" FROM titulos ".
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

	<table  border="1px" width="100%" > 
		<tr >
			<td width="10%" >Empresa</td>
			<td width="7%" >Clausula </td>
			<td width="10%" >Titulo Clausula</td>
			<td >Resumen</td>
            <td width="8%" >Duracion</td>
		</tr>
<?php
	$result=mysql_query($cadbusca, $con);

	$i=1;
	while ($row = mysql_fetch_array($result)){
/*			$empresa1=($row-1)['nombre_empresa'];
			$empresa2=$row['nombre_empresa'];
				if  ($empresa1==$empresa2)
				{$empresa=$row[strip_tags('nombre_empresa')]
				}else{$empresa="";}*/
				//Se coloca en una Variable para usarlo en los DIV's
				$id_articulo=$row['codigo_articulo'];
				//Vericar Exista Resumen
				if ($row['resumen_articulo']=="") {	$resum_art="SIN TEXTO RESUMEN";	}else{$resum_art=$row['resumen_articulo'];	}
		print  "<tr>  
				<td align='left' valign='top' >".$row[strip_tags('nombre_empresa')]."</td>
				<td align='justify' valign='top' >Clausula #".$row['nro_articulos']."</td>
				<td align='justify' valign='top' >".$row['titulo_articulo']."</td>
				<td  valign='top' >".$resum_art." <br><br>
				<a href='#' onclick='mostrar(".$id_articulo."); return false' />Articulo Completo</a>
				<div id=".$id_articulo." style='visibility:hidden'> <font size='-2' >" .$row['texto_completo_articulo']. " </font></td>
				<td align='center' valign='top' >".$row['duracion']."<br/> Incio ".$row['fecha_de_inicio']."<br/> Fin ".$row['fecha_de_termino']. "</td>
			</tr></div>";
		$i++;
		
	} 
		
}
?>
	</table>
    



