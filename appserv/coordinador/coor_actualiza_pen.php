<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_script.'msql_ano_per.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
?>
<HTML>
<HEAD>
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="../script/SoloNumero.js"></script>
</HEAD>
<BODY>
</BODY> 
<?php
ob_start();
fu_cabezote("PENSUM");
$usuario = $_SESSION["usuario_login"];

include_once(dir_script.'class_nombres.php');
$NomCra = new Nombres;

require_once('coor_lis_desp_carrera.php');

if($_REQUEST['cracod']){
   $querypen = "SELECT PEN_CRA_COD, 
		PEN_ASI_COD,
		ASI_NOMBRE,
		PEN_SEM, 
		PEN_IND_ELE, 
		PEN_NRO_HT, 
		PEN_NRO_HP,  
		PEN_CRE, 
		PEN_NRO,
		PEN_ESTADO
		FROM acpen x, acasi
		WHERE pen_cra_cod = ".$_REQUEST['cracod']."
		AND ASI_COD = PEN_ASI_COD
		AND ASI_ESTADO = 'A'
		AND EXISTS(SELECT cur_asi_cod
		FROM accursos,acasperi,achorarios
		WHERE ape_ano = cur_ape_ano 
		AND ape_per = cur_ape_per
		AND ape_estado = 'A'
		AND cur_cra_cod = x.pen_cra_cod
		AND cur_asi_cod = x.pen_asi_cod
		AND cur_id = hor_id_curso)
                ORDER BY pen_sem,pen_asi_cod,pen_nro";
	 
	 $rowpen = $conexion->ejecutarSQL($configuracion,$accesoOracle,$querypen,"busqueda");
?>	
<form name="pensum" id="pensum" method="POST" action="prog_grabar_pen.php"> 
	<div align="center"><h3>PROYECTO CURRICULAR: <? print $_REQUEST['cracod'].'-'; print $NomCra->rescataNombre($_REQUEST['cracod'],'NombreCarrera');?></h3></div>
	 <table width="95%" border="1" align="center" cellpadding="0" cellspacing="0">
	 <caption>ASIGNATURAS DEL PROYECTO, CON HORARIO PROGRAMADO EN EL PERIODO ACAD&Eacute;MICO <? print $ano.'-'.$per;?></caption>
  	 <tr class="tr">
	  <td align="center">#</td>
 	 <td align="center">C&oacute;digo</td>
 	 <td align="center">Asignatura</td>
 	 <td align="center">Sem.</td>
 	 <td align="center">Electiva</td>
 	 <td align="center">Horas T.</td>
 	 <td align="center">Horas P.</td>
 	 <td align="center">Cr&eacute;ditos</td>
 	 <td align="center">Nro.Pen.</td>
 	 <td align="center">Est.</td>
     </tr>
<?php
$i=0;
while(isset($rowpen[$i][0]))
{
	$fila=$i+1;
        print'<tr style="height:20px;" onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
	<td align="right">'.$fila.'&nbsp;</td>
	<td align="right" size="12" style="text-align:right">'.$rowpen[$i][1].'&nbsp;</td>';
	//<td align="right">
        //<input type="text" name="asicod_'.$i.'" value="'.$rowpen[$i][1].'" size="12" maxlength="12" style="text-align:right" readonly></td>
	print'<td align="left">
	<a href="#" onClick="javascript:popUpWindow(\'coor_asi_hor_pen.php?asicod='.$rowpen[$i][1].'&cracod='.$_REQUEST['cracod'].'\', \'yes\', 100, 260, 480, 250)" title="Ver Horario">'.$rowpen[$i][2].'</a></td>
        <td align="center">';
        if($rowpen[$i][3]==98){print 'CP';}else{print $rowpen[$i][3];}print'</td>
        <td align="center" size="2" style="text-align:center">'.$rowpen[$i][4].'&nbsp;</td>
        <td align="center" size="2" style="text-align:center">'.$rowpen[$i][5].'&nbsp;</td>
        <td align="center" size="2" style="text-align:center">'.$rowpen[$i][6].'&nbsp;</td>
        <td align="center">'.$rowpen[$i][7].'</td>
        <td align="center" size="2" style="text-align:center">'.$rowpen[$i][8].'&nbsp;</td>
        <td align="center">'.$rowpen[$i][9].'</td></tr>';
	/*<td align="center">
	<input type="text" name="electiva_'.$i.'" value="'.$rowpen[$i][4].'" size="2" maxlength="1" style="text-align:center" onKeypress="if(event.keyCode!=78 && event.keyCode!=83){alert(\'Digite sólo S o N\'); event.returnValue=false;}" title="Digite sólo S o N"></td>
	<td align="center">
	<input type="text" name="ht_'.$i.'" value="'.$rowpen[$i][5].'" size="2" maxlength="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>
	<td align="center">
	<input type="text" name="hp_'.$i.'" value="'.$rowpen[$i][6].'" size="2" maxlength="1" style="text-align:right" onKeypress="return SoloNumero(event)"></td>
	<td align="center">'.$rowpen[$i][7].'</td>
	<td align="center">
	<input type="text" name="pn_'.$i.'" value="'.$rowpen[$i][8].'" size="2" maxlength="1" style="text-align:right" readonly></td>
	<td align="center">'.$rowpen[$i][9].'</td></tr>*/
$i++;
}
?>
</table>
</form>
<p></p>
<? 
}
?>
</BODY>
</HTML>