<?
$enviar=$_POST['Submit'];

$id_empresa=$_GET['id'];
$codigo_empresa=$id_empresa;
$var=$empresas->getId($id_empresa);

$codigo_empresa=$empresas->codigo_empresa;
$nombre_empresa=$empresas->nombre_empresa;
$direccion_empresa=$empresas->direccion_empresa;
if($_REQUEST['id_pais']!=""){
$id_pais=$_REQUEST['id_pais'];
}else{
$id_pais=$empresas->codigo_pais;
}

if($_REQUEST['id_estado']!=""){
$id_estado=$_REQUEST['id_estado'];
}else{
$id_estado=$empresas->codigo_estado;
}

if($_REQUEST['codigo_localidad']!=""){
$codigo_localidad=$_REQUEST['codigo_localidad'];
}else{
$codigo_localidad=$empresas->codigo_localidad;
}

$telefonos_empresa=$empresas->telefonos_empresa;
$fax_empresa=$empresas->fax_empresa;
$email_empresa=$empresas->email_empresa;
$url_empresa=$empresas->url_empresa;
$rif_empresa=$empresas->rif_empresa;
$nit_empresa=$empresas->nit_empresa;
$contacto_empresa=$empresas->contacto_empresa;
$cargo_contacto=$empresas->cargo_contacto;
$telefonos_contacto=$empresas->telefonos_contacto;
$email_contacto=$empresas->email_contacto;
$id_sector=$empresas->codigo_sector;
$codigo_tipo=$empresas->codigo_tipo;
$codigo_categoria=$empresas->codigo_categoria;
$codigo_actividad=$empresas->codigo_actividad;
$cantidad_obreros_empresa=$empresas->cantidad_obreros_empresa;
$cantidad_empleados_empresa=$empresas->cantidad_empleados_empresa;
$tamano_empresa=$empresas->tamano_empresa;
$logo=$empresas->logo;

if ($enviar !=""){
$id_pais=$_POST['id_pais'];
$id_estado=$_POST['id_estado'];
$nombre_empresa=$_REQUEST['nombre'];
$direccion_empresa=$_REQUEST['direccion'];
$codigo_pais=$_REQUEST['id_pais'];
$codigo_estado=$_REQUEST['id_estado'];
$codigo_localidad=$_REQUEST['id_localidad'];
$telefonos_empresa=$_REQUEST['telefonos'];
$fax_empresa=$_REQUEST['fax'];
$email_empresa=$_REQUEST['email'];
$url_empresa=$_REQUEST['web'];
$rif_empresa=$_REQUEST['rif'];
$nit_empresa=$_REQUEST['nit'];
$contacto_empresa=$_REQUEST['persona_contacto'];
$cargo_contacto=$_REQUEST['cargo'];
$telefonos_contacto=$_REQUEST['telefono_contacto'];
$email_contacto=$_REQUEST['email_contacto'];
$codigo_sector=$_REQUEST['sector'];
$codigo_tipo=$_REQUEST['tipos'];
$codigo_categoria=$_REQUEST['categoria'];
$codigo_actividad=$_REQUEST['actividad'];
$cantidad_obreros_empresa=$_REQUEST['obreros'];
$cantidad_empleados_empresa=$_REQUEST['empleados'];
$tamano_empresa=$_REQUEST['tamanio_empresa'];




	//manejo de archivos
		$tipo1=$_FILES['imagen_1']['type'];
		$img_1=$_FILES['imagen_1']['name'];
			if($tipo1=='application/pdf'){
				$ext = 'pdf';
			}else if($tipo1=='image/jpeg'){
				$ext = 'jpg';
			}else if($tipo1=='image/png'){
				$ext = 'png';
			}
			$directorio="modulos/tablas/empresa/documentos/";
			$carpeta=$id_empresa;
			$ruta=$directorio.$carpeta;	
			mkdir("modulos/tablas/empresa/documentos/".$carpeta);
			
	//JUEGO PARA SABER QUE SI SE CARGO UN ARCHIVO ELIMINAR EL ANTERIOR Y SUBIR EL NUEVO, O DEJAR EL ANTERIOR
			$directorio="modulos/tablas/empresa/documentos/";
			$carpeta=$id_empresa;
			$ruta=$directorio.$carpeta;	
			//imagen 1
			if ($tipo1!="")
				{	
					//ELIMINAR ARCHIVO EXISTENTE
					$archivo=$_SERVER['DOCUMENT_ROOT']."/admin/modulos/tablas/empresa/documentos/".$id_empresa."/".$logo;
					$archivo_eliminar="modulos/tablas/empresa/documentos/".$id_empresa."/".$logo;
					if(file_exists($archivo))
						{
						chmod($archivo,0777);
						unlink($archivo);
						}
					//SUBIR ARCHIVO NUEVO 
					if (move_uploaded_file($_FILES['imagen_1']['tmp_name'], $ruta."/".$img_1)){ 
						chmod( $ruta."/".$img_1, 0777);
							$msg =  "<br>El archivo ha sido cargado correctamente.".$img_1; 
						}else{ 
							$msg = "Ocurrió algún error al subir el fichero. No pudo guardarse."; 
						} 	
					$sus_img_1=$empresas->update_imagen( $id_empresa,$img_1 );
				}
	


		$error=$empresas->update($nombre_empresa, $direccion_empresa, $codigo_pais, $codigo_estado, $codigo_localidad, $telefonos_empresa, $fax_empresa, $email_empresa, $url_empresa, $rif_empresa, $nit_empresa, $contacto_empresa, $cargo_contacto, $telefonos_contacto, $email_contacto, $codigo_sector, $codigo_tipo, $codigo_categoria, $codigo_actividad, $cantidad_obreros_empresa, $cantidad_empleados_empresa, $tamano_empresa , $codigo_empresa);





if ($error){
$msg="EMPRESA ACTUALIZADA";
?>
<script type="text/javascript" language="javascript">
<!--
location.href="index.php?url=tablas&tbl=Empresas&accion=listar";
-->
</script>
<?
}
}

?>