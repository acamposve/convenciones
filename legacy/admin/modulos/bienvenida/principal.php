<?php			
    include( '../lib/objetos/user.php');
    $login=$_SESSION["login"];
    $user->getId($login);
    $fecha	=	$user->fech_venc;
    $status	=	$user->estatus;
    $cadena=split("-",$fecha); 
    $prueba=$fecha-date(Y-m-d);
    if ($_SESSION["tipo"]!=1)
    {
        if ($cadena[0]-date("Y")==0) 
        {
            if  ($cadena[1]-date("m")==0)
            {
                if (($cadena[2]-date("d")<=7) && ($cadena[2]-date("d")>=1))
                {
                ?>
                <script language="javascript" type="text/javascript">
                    <!--
                    alert ("<? print $fecha ?> ")
                    alert ("<? print $prueba ?> ");
                    alert (" Dispone de <?php print $cadena[2]-date("d") ?> Días para que su \n Usuario sea Bloqueado \n Fecha Vencimeinto <? print $fecha?>");
                    -->
                </script>
                <?
                }
                if ($cadena[2]-date("d")<=0)
                {
                    $user->Bloqueo($login);
                ?>
                <script language="javascript" type="text/javascript">
                    <!--
                    location.href="index.php?url=logout&motivo=BLO";
                    -->
                </script>
                <?
                }
            }
            elseif( ($cadena[1]-date("m")) <0 )
            {
                if ((($cadena[1])==1) || (($cadena[1])==3) ||(($cadena[1])==7) ||(($cadena[1])==8) ||(($cadena[1])==10) || (($cadena[1])==12) )	
                {
                    $conteo	=(($cadena[2])-31);
                    $final	= $conteo - date("m");
                }
                elseif ((($cadena[1])==4) || (($cadena[1])==5) ||(($cadena[1])==6) ||(($cadena[1])==9) ||(($cadena[1])==11) || (($cadena[1])==12) )
                {
                    $conteo	=(($cadena[2])-30);
                    $final	= $conteo - date("m");
                }
                elseif (($cadena[1])==2)
                {
                    $conteo	=(($cadena[2])-28);
                    $final	= $conteo - date("m");
                }
                if ($final < 0)
                {
                    $user->Bloqueo($login);
                ?>
                <script language="javascript" type="text/javascript">
                    <!--
                    alert (" Su usuario se encuentra Vencido");
                    location.href="index.php?url=logout";
                    -->
                </script>
                <?
                }
            }
        }
    }
?>
<table id="Tabla_01" width="780" height="541" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<img src="/img/Bienvenida (1).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (2).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (3).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (4).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (5).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (6).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (7).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (8).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (9).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (10).jpg" width="64" height="54" alt=""></td>
		<td rowspan="10">
			<img src="/img/Bienvenida (11).jpg" width="142" height="541" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (12).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (13).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (14).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (15).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (16).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (17).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (18).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (19).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (20).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (21).jpg" width="64" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (22).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (23).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (24).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (25).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (26).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (27).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (28).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (29).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (30).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (31).jpg" width="64" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (32).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (33).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (34).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (35).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (36).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (37).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (38).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (39).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (40).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (41).jpg" width="64" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (42).jpg" width="64" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (43).jpg" width="64" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (44).jpg" width="63" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (45).jpg" width="64" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (46).jpg" width="64" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (47).jpg" width="64" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (48).jpg" width="64" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (49).jpg" width="63" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (50).jpg" width="64" height="55" alt=""></td>
		<td>
			<img src="/img/Bienvenida (51).jpg" width="64" height="55" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (52).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (53).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (54).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (55).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (56).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (57).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (58).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (59).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (60).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (61).jpg" width="64" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (62).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (63).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (64).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (65).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (66).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (67).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (68).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (69).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (70).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (71).jpg" width="64" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (72).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (73).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (74).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (75).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (76).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (77).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (78).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (79).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (80).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (81).jpg" width="64" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (82).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (83).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (84).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (85).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (86).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (87).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (88).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (89).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (90).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (91).jpg" width="64" height="54" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="/img/Bienvenida (92).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (93).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (94).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (95).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (96).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (97).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (98).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (99).jpg" width="63" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (100).jpg" width="64" height="54" alt=""></td>
		<td>
			<img src="/img/Bienvenida (101).jpg" width="64" height="54" alt=""></td>
	</tr>
</table>