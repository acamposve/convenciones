<?php
function FechaConvertirFormatoCompleto($FechaHoraInico)
 {
 	 $Fecha=explode('-',$FechaHoraInico);
     $Fecha2=explode(' ',$FechaHoraInico);
     $Anio= $Fecha[0];
	 $Mes= $Fecha[1];
	 $Dia= $Fecha2[0];
	 $Dia=explode('-',$Dia);
	 $Dia_2=$Dia[2];
	 $Hora=$Fecha2[1];
	 $DiaInicio="$Dia_2/$Mes/$Anio";
	 $Vector[0]=$DiaInicio;
	 $Vector[1]=$Hora;
	 return $Vector;
 }
 
function FechaConvertirFormato($FechaInico)
 {
 	 $Fecha=explode('-',$FechaInico);
     $Anio= $Fecha[2];
	 $Mes= $Fecha[1];
	 $Dia= $Fecha[0];
	 $DiaInicio="$Anio-$Mes-$Dia";
	 return $DiaInicio;
 } 
 
 function FechaConvertirFormato2($FechaInico)
 {
 	 $Fecha=explode('-',$FechaInico);
     $Anio= $Fecha[0];
	 $Mes= $Fecha[1];
	 $Dia= $Fecha[2];
	 $DiaInicio="$Dia-$Mes-$Anio";
	 return $DiaInicio;
 } 
?>