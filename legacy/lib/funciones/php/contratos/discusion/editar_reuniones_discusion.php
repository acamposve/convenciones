<?
$enviar=$_POST['enviar'];
$codigo_bitacora = $_GET['id_bitacora'];
$codigo_reunion= $_GET['id'];

			$Fecha=$_GET['FechaReunion'];
			
			print $Fecha;
/*			$Hora=$_REQUEST['HoraReunion'];
			$Duracion=$_REQUEST['DuracionReunion'];DD
			$Detalle=$_REQUEST['DetalleReunion'];
			$Resumen=$_REQUEST['ResumenReunion'];
			$Asistentes=$_REQUEST['AsistentesReunion'];*/


		if ($enviar==1){
			$Fecha=$_REQUEST['FechaReunion'];
			$Hora=$_REQUEST['HoraReunion'];
			$Duracion=$_REQUEST['DuracionReunion'];
			$Detalle=$_REQUEST['DetalleReunion'];
			$Resumen=$_REQUEST['ResumenReunion'];
			$Asistentes=$_REQUEST['AsistentesReunion'];
//function actualizarReuniones($codigo_reunion,$fecha_reunion,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes)  {
	
			$var=$discusion->actualizarReuniones($codigo_reunion, $Fecha,$Hora,$Duracion,$Resumen,$Detalle,$Asistentes);
		
		if ($var){
		?>
		<script language="javascript" type="text/javascript">
		<!--
		location.href="index.php?url=contratos&tbl=Bitacora&accion=listar_reuniones&id=<? print $codigo_bitacora?>";
		-->
		</script>
		<?
		}
		}
?>
