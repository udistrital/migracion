<?php
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'fu_cabezote.php');
require_once(dir_conect.'fu_tipo_user.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);
fu_tipo_user(30);
?>
<HTML>
<HEAD><TITLE>Docentes</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../script/SoloNumero.js"></script>
<script language="JavaScript" src="ValNotasDef.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="LisLov.js"></script>
</HEAD>
<BODY>
<?php
$estado = 'V';
$docnroiden = $_SESSION['usuario_login'];
if(isset($_REQUEST['A'])&&$_REQUEST['A'] != "") {
   $_SESSION["A"] = $_REQUEST['A'];
   $_SESSION["G"] = $_REQUEST['G'];
   $_SESSION["cur"] = $_REQUEST['cur'];
   $_SESSION["C"] = $_REQUEST['C'];
}
$notas="";
if(isset($_REQUEST['upd'])){
   require_once('doc_ValDoc.php');
   require_once('update_notasdef_curvac.php');
}
if(isset($_REQUEST['num_regs']))
{
	$notas='<div align="center"><table border="0" width="80%" cellspacing="2" cellpadding="3">
		<tr>
			<td width="10%" align="center" colspan="6" ><font color="red">Se han registrado '.($_REQUEST['num_regs']-1).' notas.</font></td>
		</tr>
	</div><p></p>';

}
fu_cabezote("NOTAS DEFINITIVAS CURSOS DE VACACIONES");


require_once(dir_script.'msql_notasdef.php');
$consulta = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consul,"busqueda");

$asig = $consulta[0][0];
$grup = $consulta[0][2];
$calc = "javascript:popUpWindow('../generales/calc.php', 'no', 100, 100, 240, 250)";

require_once('doc_valida_fechas_curvac.php');
echo $notas;
echo'<div align="center"><table border="0" width="80%" cellspacing="2" cellpadding="3">
	<tr>
		<td width="10%" align="center" colspan="6" ><font color="red">Recuerde: En las notas digite siempre un número entero. Ejemplo: Para 0.5 digite 5 - Para 5,0 digite 50. Para 3,7 digite 37.</font></td>
	</tr>
</div><p></p>';
echo'<div align="center"><table border="0" width="80%" cellspacing="2" cellpadding="3">
	<tr>
		<td width="10%" align="center" colspan="6">'.$msg.'</td>
	</tr>
     	<tr>
     		<td width="10%" align="right"><B>Asignatura:</B></td>
		<td width="40%"><B><U>'.$consulta[0][1].'</U></B></td>
		<td width="8%" align="right"><B>Grupo:</B></td>
		<td width="5%" align="right">'.$consulta[0][2].'</td>
		<td width="8%" align="right"><B>Semestre:</B></td>
		<td width="6%" align="right">'.$consulta[0][9].'</td>
	</tr>
		   
     	<tr>
     		<td width="10%" align="right"><B>Carrera:</B></td>
		<td width="40%">'.$consulta[0][4].'</td>
		<td width="8%" align="right"><B>Cupo:</B></td>
		<td width="5%" align="right">'.$consulta[0][10].'</td>
		<td width="8%" align="right"><B>Inscritos:</B></td>
		<td width="6%" align="right">'.$consulta[0][11].'</td>
	</tr>
		   
     	<tr>
     		<td width="10%" align="right"><B>Docente:</B></td>
		<td width="40%"><B><U>'.$consulta[0][6].'</B></U></B></td>
		<td width="8%" align="right"><B>A&ntilde;o:</B></td>
		<td width="5%" align="right">'.$consulta[0][7].'</td>
		<td width="8%" align="right"><B>Per&iacute;odo:</B></td>
		<td width="6%" align="right">'.$consulta[0][8].'</td>
	</tr>
</table></div><p></p>';
?>
<table border="1" width="69%" align="center" cellspacing="0" cellpadding="0">
	<tr class="tr">
		<td width="2%" align="center">Nro.</td>
		<td width="8%" align="center">C&oacute;digo</td>
		<td width="50%" align="center">Apellidos y Nombres</td>
		<td width="3%" align="center">Nota</td>
		<td width="3%" align="center">Obs</td>
	</tr>
<?php 
echo'<form name="forma" id="forma" method="POST" action="doc_dig_curvac.php">
<input type="Hidden" name="docnroiden" value="'.$consulta[0][5].'">';
$ano = $consulta[0][7];
$per = $consulta[0][8];
$nro=1;
$i = 0;
if($fechahoy < $fecini || $fechahoy > $fecfin)
{
	while(isset($consulta[$i][0]))
	{
		echo'<tr><td width="2%" align="center">'.$nro.'</td>
		<td width="10%" align="center">'.$consulta[$i][12].'</td>
		<td width="40%" align="left">'.$consulta[$i][13].'</td>
		<td width="3%" align="right">'.$consulta[$i][14].'</td>
		<td width="3%" align="right">'.$consulta[$i][15].'</td>
		</tr>';
		$nro++;
		$i++;
	}
}
else
{
	while(isset($consulta[$i][0]))
	{
	echo'<tr>
		<td width="2%" align="right">'.$nro.'</td>
		<td width="10%" align="center">'.$consulta[$i][12].'</td>
		<td width="40%" align="left">'.$consulta[$i][13].'</td>
		<td width="3%"><input type="text" name="nota'.$i.'" size="3" id="nota" value="'.$consulta[$i][14].'" style="text-align: right" title="Digite valor (0-50)" '.$sbgc.'></td>
		<td width="3%"><input type="text" name="obs_'.$i.'" size="3" id="obs" value="'.$consulta[$i][15].'" onClick="ListaValores(\'doc_lov_obsnotas.php\', \'obs_'.$i.'\', 240, 180, 550, 350)" maxlength="2" style="text-align: right" title="Haga clic para ver lista de valores" '.$sbgc.' readonly></td>
	</tr>
	<input name="cod_'.$i.'" type="hidden" value="'.$consulta[$i][12].'">';
	$nro++;
	$i++;
    	}
}
?>
</table>
<table width="69%" align="center" cellspacing="0" cellpadding="0"><tr><td width="50%" align="center"><br><?php echo $btn_grabar; ?></td>
<?PHP
$print = "javascript:popUpWindow('print_doc_notas_curvac.php', 'yes', 0, 0, 790, 650)";
echo'<td width="50%" align="center"><br><input type="submit" value="Imprimir p&aacute;gina" onClick="'.$print.'"></td></tr></table>';
?>
<p></p>
<input name="asig" type="hidden" value="<?php echo $asig; ?> ">
<input name="grup" type="hidden" value="<?php echo $grup; ?> ">
<input name="num_regs" type="hidden" value="<?php echo $i; ?> ">
<input name="ano" type="hidden" value="<?php echo $ano; ?>">
<input name="per" type="hidden" value="<?php echo $per; ?>">
</form>
</BODY>
</HTML>
