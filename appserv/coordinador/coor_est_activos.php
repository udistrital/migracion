<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_conect.'fu_tipo_user.php');
require_once(dir_script.'fu_cabezote.php');
require_once('../administracion/class_cuenta_est.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

if(!$_REQUEST['tipo']){
    $_REQUEST['tipo']=$_SESSION['usuario_nivel'];
}

if($_REQUEST['tipo']==110){
    fu_tipo_user(110);
    $tipo=110; 
}elseif($_REQUEST['tipo']==114){
    fu_tipo_user(114);
    $tipo=114; 
}else{
fu_tipo_user(4);
    $tipo=4; 
}
?>
<html>
<head>
<title>Administraci&oacute;n de Mensajes</title>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/ventana.js"></script>
</head>
<body style="margin-top:0">
<?php
fu_cabezote("ESTUDIANTES CON ASIGNATURAS INSCRITAS");

$redir = 'coor_pag_est_nula.php';
include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
	$_SESSION['carrera'] = $_REQUEST['cracod'];
	$tot = new CuentaEst;
	$totA = $tot->CuentaEstados($_REQUEST['cracod'],'A');
	$totB = $tot->CuentaEstados($_REQUEST['cracod'],'B');
	$totC = $tot->CuentaEstados($_REQUEST['cracod'],'C');
	$totD = $tot->CuentaEstados($_REQUEST['cracod'],'D');
	$totF = $tot->CuentaEstados($_REQUEST['cracod'],'F');
	$totH = $tot->CuentaEstados($_REQUEST['cracod'],'H');
	$totI = $tot->CuentaEstados($_REQUEST['cracod'],'I');
	$totJ = $tot->CuentaEstados($_REQUEST['cracod'],'J');
	$totK = $tot->CuentaEstados($_REQUEST['cracod'],'K');
	$totL = $tot->CuentaEstados($_REQUEST['cracod'],'L');
	$totM = $tot->CuentaEstados($_REQUEST['cracod'],'M');
	$totP = $tot->CuentaEstados($_REQUEST['cracod'],'P');
	$totR = $tot->CuentaEstados($_REQUEST['cracod'],'R');
	$totS = $tot->CuentaEstados($_REQUEST['cracod'],'S');
	$totT = $tot->CuentaEstados($_REQUEST['cracod'],'T');
	$totV = $tot->CuentaEstados($_REQUEST['cracod'],'V');
	$totX = $tot->CuentaEstados($_REQUEST['cracod'],'X');
	$totZ = $tot->CuentaEstados($_REQUEST['cracod'],'Z');
	
	//require_once('capa_coor_est_activos.php');
	
	$sumtot = $totA+$totB+$totC+$totD+$totF+$totH+$totI+$totJ+$totK+$totL+$totM+$totP+$totR+$totS+$totT+$totV+$totX+$totZ;
	if($sumtot < 1){ 
	   $porA = 0; 
	   $porB = 0;
	   $porC = 0;
	   $porD = 0;
	   $porF = 0;
	   $porH = 0;
	   $porI = 0;
	   $porJ = 0;
	   $porK = 0;
	   $porL = 0;
	   $porM = 0;
	   $porP = 0;
	   $porR = 0;
	   $porS = 0;
	   $porT = 0;
	   $porV = 0; 
	   $porX = 0;
	   $porZ = 0;
	}
	else{
		$porA = sprintf("%1.2f",($totA/$sumtot)*100); 
		$porB = sprintf("%1.2f",($totB/$sumtot)*100);
		$porC = sprintf("%1.2f",($totC/$sumtot)*100);
		$porD = sprintf("%1.2f",($totD/$sumtot)*100);
		$porF = sprintf("%1.2f",($totF/$sumtot)*100);
		$porH = sprintf("%1.2f",($totH/$sumtot)*100);
		$porI = sprintf("%1.2f",($totI/$sumtot)*100);
		$porJ = sprintf("%1.2f",($totJ/$sumtot)*100);
		$porK = sprintf("%1.2f",($totK/$sumtot)*100);
		$porL = sprintf("%1.2f",($totL/$sumtot)*100);
		$porM = sprintf("%1.2f",($totM/$sumtot)*100);
		$porP = sprintf("%1.2f",($totP/$sumtot)*100);
		$porR = sprintf("%1.2f",($totR/$sumtot)*100);
		$porS = sprintf("%1.2f",($totS/$sumtot)*100);
		$porT = sprintf("%1.2f",($totT/$sumtot)*100);
		$porV = sprintf("%1.2f",($totV/$sumtot)*100);
		$porX = sprintf("%1.2f",($totX/$sumtot)*100); 
		$porZ = sprintf("%1.2f",($totZ/$sumtot)*100);
	}
	$printC = "javascript:popUpWindow('print_est_estado.php?estado=C', 'yes', 0, 0, 780, 500)";
	$printD = "javascript:popUpWindow('print_est_estado.php?estado=D', 'yes', 0, 0, 780, 500)";
	$printF = "javascript:popUpWindow('print_est_estado.php?estado=F', 'yes', 0, 0, 780, 500)";
	$printH = "javascript:popUpWindow('print_est_estado.php?estado=H', 'yes', 0, 0, 780, 500)";
	$printI = "javascript:popUpWindow('print_est_estado.php?estado=I', 'yes', 0, 0, 780, 500)";
	$printJ = "javascript:popUpWindow('print_est_estado.php?estado=J', 'yes', 0, 0, 780, 500)";
	$printK = "javascript:popUpWindow('print_est_estado.php?estado=K', 'yes', 0, 0, 780, 500)";
	$printL = "javascript:popUpWindow('print_est_estado.php?estado=L', 'yes', 0, 0, 780, 500)";
	$printM = "javascript:popUpWindow('print_est_estado.php?estado=M', 'yes', 0, 0, 780, 500)";
	$printP = "javascript:popUpWindow('print_est_estado.php?estado=P', 'yes', 0, 0, 780, 500)";
	$printR = "javascript:popUpWindow('print_est_estado.php?estado=R', 'yes', 0, 0, 780, 500)";
	$printS = "javascript:popUpWindow('print_est_estado.php?estado=S', 'yes', 0, 0, 780, 500)";
	$printT = "javascript:popUpWindow('print_est_estado.php?estado=T', 'yes', 0, 0, 780, 500)";
	$printV = "javascript:popUpWindow('print_est_estado.php?estado=V', 'yes', 0, 0, 780, 500)";
	$printX = "javascript:popUpWindow('print_est_estado.php?estado=X', 'yes', 0, 0, 780, 500)";
	$printZ = "javascript:popUpWindow('print_est_estado.php?estado=Z', 'yes', 0, 0, 780, 500)";
	$estilo = "style='cursor:pointer; color:#0000FF; font-weight:bold; border:1px solid #336699; background-color:#E8E8D0'";
	
	$ArrayEstados = array('A' => 'Activo',
						  'B' => 'Activo y prueba acad&eacute;mica',
						  'C' => 'Cancel&oacute; semestre',
						  'D' => 'Sin notas y activo',
						  'F' => 'Prueba Acad., no matriculado',
						  'H' => 'Termin&oacute; y matricul&oacute;',
						  'I' => 'Inactivo',
						  'J' => 'Prueba acad&eacute;mica y vacaciones',
						  'K' => 'Sin notas, en prueba',
						  'L' => 'Pasantia o trabajo de grado',
						  'M' => 'No oficializ&oacute; matricula',
						  'P' => 'Aplaz&oacute; semestre',
						  'R' => 'Retiro voluntario',
						  'S' => 'Sancionado',
						  'T' => 'Termin&oacute; materias',
						  'V' => 'Vacaciones',
						  'X' => 'Excluido',
						  'Z' => 'No super&oacute; prueba acad&eacute;mica');
						 
	function listar($matriz){
		echo'<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">';
		while(list($clave, $valor) = each($matriz)){
			echo'<tr><td width="10%" class="Estilo12" align="center">'.$clave.'</td>';
			echo'<td width="90%">'.$valor.'</td></tr>';
		}
		echo'</table>';
	}
	?>
	<div align="center" class="Estilo5">PROYECTO CURRICULAR: <? print $NomCra->rescataNombre($_SESSION['carrera'],"NombreCarrera"); ?></div><br>
	<div align="center" class="Estilo10">ESTADOS ACAD&Eacute;MICOS DE LOS ESTUDIANTES CON ASIGNATURAS INSCRITAS</div>
	<br>
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr class="tr">
		<td align="center">Estados</td>
		<td align="center" valign="bottom">Estudiantes Con Asignaturas Inscritas</td>
	  </tr>
	  <tr>
		<td width="237">
			<? listar($ArrayEstados); ?>
		</td>
		<td width="350" align="center" valign="bottom">
		  <table width="159" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr valign="bottom">
			  <td align="center"><? print $totA; ?><BR><img src="../img/green.png" height="<? print $porA; ?>" width="30"></td>
			  <td align="center"><? print $totB; ?><BR><img src="../img/red.png" height="<? print $porB; ?>" width="30"></td>
			  <td align="center"><? print $totC; ?><BR><img src="../img/blue.png" height="<? print $porC; ?>" width="30"></td>
			  <td align="center"><? print $totD; ?><BR><img src="../img/green.png" height="<? print $porD; ?>" width="30"></td>
			  <td align="center"><? print $totF; ?><BR><img src="../img/red.png" height="<? print $porF; ?>" width="30"></td>
			  <td align="center"><? print $totH; ?><BR><img src="../img/blue.png" height="<? print $porH; ?>" width="30"></td>
			  <td align="center"><? print $totI; ?><BR><img src="../img/green.png" height="<? print $porI; ?>" width="30"></td>
			  <td align="center"><? print $totJ; ?><BR><img src="../img/red.png" height="<? print $porJ; ?>" width="30"></td>
			  <td align="center"><? print $totK; ?><BR><img src="../img/blue.png" height="<? print $porK; ?>" width="30"></td>
			  <td align="center"><? print $totL; ?><BR><img src="../img/green.png" height="<? print $porL; ?>" width="30"></td>
			  <td align="center"><? print $totM; ?><BR><img src="../img/red.png" height="<? print $porM; ?>" width="30"></td>
			  <td align="center"><? print $totP; ?><BR><img src="../img/blue.png" height="<? print $porP; ?>" width="30"></td>
			  <td align="center"><? print $totR; ?><BR><img src="../img/green.png" height="<? print $porR; ?>" width="30"></td>
			  <td align="center"><? print $totS; ?><BR><img src="../img/red.png" height="<? print $porS; ?>" width="30"></td>
			  <td align="center"><? print $totT; ?><BR><img src="../img/blue.png" height="<? print $porT; ?>" width="30"></td>
			  <td align="center"><? print $totV; ?><BR><img src="../img/green.png" height="<? print $porV; ?>" width="30"></td>
			  <td align="center"><? print $totX; ?><BR><img src="../img/red.png" height="<? print $porX; ?>" width="30"></td>
			  <td align="center"><? print $totZ; ?><BR><img src="../img/blue.png" height="<? print $porZ; ?>" width="30"></td>
			</tr>
			<tr>
			  <td align="center"><? print'<input type=button value="A" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="B" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="C" onClick="'.$printC.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="D" onClick="'.$printD.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="F" onClick="'.$printF.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="H" onClick="'.$printH.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="I" onClick="'.$printI.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="J" onClick="'.$printJ.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="K" onClick="'.$printK.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="L" onClick="'.$printL.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="M" onClick="'.$printM.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="P" onClick="'.$printP.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="R" onClick="'.$printR.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="S" onClick="'.$printS.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="T" onClick="'.$printT.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="V" onClick="'.$printV.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="X" onClick="'.$printX.'" '.$estilo.'>'; ?></td>
			  <td align="center"><? print'<input type=button value="Z" onClick="'.$printZ.'" '.$estilo.'>'; ?></td>
			</tr>
			<tr class="Estilo3">
				<td align="center"><? print $porA; ?>%</td>
				<td align="center"><? print $porB; ?>%</td>
				<td align="center"><? print $porC; ?>%</td>
				<td align="center"><? print $porD; ?>%</td>
				<td align="center"><? print $porF; ?>%</td>
				<td align="center"><? print $porH; ?>%</td>
				<td align="center"><? print $porI; ?>%</td>
				<td align="center"><? print $porJ; ?>%</td>
				<td align="center"><? print $porK; ?>%</td>
				<td align="center"><? print $porL; ?>%</td>
				<td align="center"><? print $porM; ?>%</td>
				<td align="center"><? print $porP; ?>%</td>
				<td align="center"><? print $porR; ?>%</td>
				<td align="center"><? print $porS; ?>%</td>
				<td align="center"><? print $porT; ?>%</td>
				<td align="center"><? print $porV; ?>%</td>
				<td align="center"><? print $porX; ?>%</td>
				<td align="center"><? print $porZ; ?>%</td>
		    </tr>
	   </table></td>
	  </tr>
	</table><p></p>
	<table width="94%" border="0" align="center">
	  <tr>
		<td width="69%" align="right"><b>TOTAL DE ESTUDIANTES CON ASIGNATURAS INSCRITAS:</b></td>
		<td width="31%"><b><? print $sumtot ;?></b></td>
	  </tr>
	</table>
	<div align="justify"><br>
		  <strong>Se&ntilde;or(a) Coordinador(a):</strong> <span class="Estilo10">Tenga presente que, los estudiantes en los estados A y B, son los &uacute;nicos que deben tener asignaturas inscritas.</span>
		  <p align="center">Para borrar las inscripciones de los estudiantes en estados diferentes a A y B, haga clic en la letra bajo la gr&aacute;fica.</p>
		  <p align="center">Recuerde que al borrar estos registros ser&aacute;n eliminados de la tabla de inscripci&oacute;n de asignaturas, y las notas no ser&aacute;n procesadas.</p>	
	</div>
<?php
}
?>
</body>
</html>