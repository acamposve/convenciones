<?
$id_bitacora=$_GET['id_bitacora'];

$enviar=$_POST['enviar'];

if ($enviar==1){
$Fecha=$_REQUEST['FechaReunion'];
print $Fecha;
$Hora=$_REQUEST['HoraReunion'];
$Duracion=$_REQUEST['DuracionReunion'];
$Detalle=$_REQUEST['DetalleReunion'];
$Resumen=$_REQUEST['ResumenReunion'];
$Asistentes=$_REQUEST['AsistentesReunion'];



	$var=$discusion->agregarReunion($id_bitacora,$Fecha,$Hora,$Duracion,$Detalle,$Resumen,$Asistentes);


		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_reuniones&id=<? print $id_bitacora; ?>";
		-->
		</script>
		<?
		}
}
?>
