<?PHP
require_once('dir_relativo.cfg');
require_once(dir_conect.'valida_pag.php');
require_once(dir_script.'msql_ano_per.php');
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
<HEAD><TITLE>Notas Parciales</TITLE>
<META http-equiv="Page-Enter" content="revealTrans(Duration=0.6,Transition=24)">
<META http-equiv="Page-Exit" content="revealTrans(Duration=0.6,Transition=2)">
<link href="../script/estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="ValNotasPar.js"></script>
<script language="JavaScript" src="../script/ventana.js"></script>
<script language="JavaScript" src="LisLov.js"></script>
<script language="JavaScript" src="../script/SoloNumero.js"></script>
</HEAD>
<BODY>

<?php
$estado = 'A';
$docnroiden = $_SESSION['usuario_login'];

if($_REQUEST['A'] != ""){
   $_SESSION["A"] = $_REQUEST['A'];
   $_SESSION["G"] = $_REQUEST['G'];
   $_SESSION["C"] = $_REQUEST['C'];
   $_SESSION['carrera'] = $_REQUEST['C'];
   $_SESSION['nivel'] = $_REQUEST['NIVEL'];
}
require_once('valida_fec_parciales.php');
$rowfecnot=$conexion->ejecutarSQL($configuracion,$accesoOracle,$confecnot,"busqueda");
/*foreach($_POST as $clave=>$valor)
{
	echo $clave."->".$valor."<br>";
}
*/

if($_REQUEST['upd']){
  // require_once('doc_ValDoc.php');
   require_once('grabar_notas.php');
}
if($_REQUEST['notdef'])
{
   require_once('grabar_notas.php');
   $calc = "BEGIN pck_pr_notaspar.pra_calnotdef_cur(".$_REQUEST['ano'].", ".$_REQUEST['per'].", ".$_SESSION["A"].", ".$_SESSION["G"]."); END; ";
   $registro1=$conexion->ejecutarSQL($configuracion,$accesoOracle,$calc,"busqueda");
}

fu_cabezote("CAPTURA DE NOTAS PARCIALES");
$calnot = "javascript:popUpWindow('doc_fec_notaspar.php?cra=$cra', 'yes', 100, 100, 400, 230)";
echo'<div align="center"><input type="submit" name="Submit" value="Ver fechas de digitaci&oacute;n" onClick="'.$calnot.'" style="cursor:pointer"></div>';

require_once(dir_script.'msql_notaspar_doc.php');

$consulta=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cod_consulta,"busqueda");
$row = $consulta;

require_once('doc_proceso_cerrado.php');
echo'<p></p>'.$MsjCierreProceso.'<br>'.$MsjCierreSem.'
	<table border="1" width="100%" align="center">
		<tr class="tr">
			<td colspan="5" align="center">ASIGNATURA</td>
		</tr>
		<tr><td align="right">'.$consulta[0][4].'</td>
			<td><b>'.UTF8_DECODE($consulta[0][5]).'</b></td>
			<td align="center"><b>Grupo</b></td>
			<td align="center"><b>Inscritos</b></td>
			<td align="center"><b>Periodo</b></td>
			</tr>
			<tr>
			<td align="right">'.$consulta[0][0].'</td>
			<td align="left"><b>'.UTF8_DECODE($consulta[0][1]).'</b></td>
			<td align="center">'.$consulta[0][6].'</td>
			<td align="center">'.$consulta[0][30].'</td>
			<td align="center">'.$consulta[0][2].'-'.$consulta[0][3].'</td>
		</tr>
	</table><p></p>

<form name="fnotpar" id="fnotpar" method="POST" action="doc_dig_notaspar.php">
	<table border="1" width="100%" align="center">
		<tr class="tr">
			<td></td>
			<td align="center">%1</td>
			<td align="center">%2</td>
			<td align="center">%3</td>
			<td align="center">%4</td>
			<td align="center">%5</td>
			<td align="center">LAB</td>
			<td align="center">EXA</td>
			<td align="center">HAB</td>
			<td align="center">&nbsp;&nbsp;&nbsp;</td>
			<td align="center">&nbsp;&nbsp;&nbsp;</td>
			<td align="center">SUM</td>
		</tr>
	<tr>
		<td align="right" valign="middle"><b>PORCENTAJES DE NOTAS</b><img src="../img/flechasig.gif"></td>';
			//if(($fechahoy < $rowfecnot[0][1]) || ($fechahoy > $rowfecnot[0][2]) || ($rowfecnot[0][1] == " ") || ($rowfecnot[0][2] == " ") || ($fechahoy > $rowfecnot[0][17])){
				echo '<td align="center">'.$consulta[0][11].'</td>';
			//}
			//else
			//{
			//	echo '<td width="24" align="center"><input type="text" name="p1" value="'.$consulta[0][11].'" maxlength="2" size="3" style="text-align:right"'.$porpar1.'></td>';	
			//}
			//if(($fechahoy < $rowfecnot[0][3]) || ($fechahoy > $rowfecnot[0][4]) || ($rowfecnot[0][3] == " ") || ($rowfecnot[0][4] == " ") || ($fechahoy > $rowfecnot[0][17]))
			//{
				echo '<td align="center">'.$consulta[0][13].'</td>';
			//}
			//else
			//{
			//	echo '<td width="25" align="center"><input type="text" name="p2" value="'.$consulta[0][13].'" maxlength="2" size="3" style="text-align:right" '.$porpar2.'></td>';
			//}
			//if(($fechahoy < $rowfecnot[0][5]) || ($fechahoy > $rowfecnot[0][6]) || ($rowfecnot[0][5] == " ") || ($rowfecnot[0][6] == " ") || ($fechahoy > $rowfecnot[0][17]))
			//{
				echo '<td align="center">'.$consulta[0][15].'</td>';
			//}
			//else
			//{
			//	echo '<td width="25" align="center"><input type="text" name="p3" value="'.$consulta[0][15].'" maxlength="2" size="3" style="text-align:right" '.$porpar3.'></td>';
			//}
			//if(($fechahoy < $rowfecnot[0][7]) || ($fechahoy > $rowfecnot[0][8]) || ($rowfecnot[0][7] == " ") || ($rowfecnot[0][8] == " ") || ($fechahoy > $rowfecnot[0][17]))
			//{
				echo '<td align="center">'.$consulta[0][17].'</td>';
			//}
			//else
			//{
			//	echo '<td width="25" align="center"><input type="text" name="p4" value="'.$consulta[0][17].'" maxlength="2" size="2" style="text-align:right" '.$porpar4.'></td>';
			//}
			//if(($fechahoy < $rowfecnot[0][9]) || ($fechahoy > $rowfecnot[0][10]) || ($rowfecnot[0][9] == " ") || ($rowfecnot[0][10] == " ") || ($fechahoy > $rowfecnot[0][17]))
			//{
				echo '<td align="center">'.$consulta[0][19].'</td>';
			//}
			//else
			//{
			//	echo '<td width="25" align="center"><input type="text" name="p5" value="'.$consulta[0][19].'" maxlength="2" size="2" style="text-align:right" '.$porpar5.'></td>';	
			//}
			//if(($fechahoy < $rowfecnot[0][11]) || ($fechahoy > $rowfecnot[0][12]) || ($rowfecnot[0][11] == " ") || ($rowfecnot[0][12] == " ") || ($fechahoy > $rowfecnot[0][17]))
			//{
				echo '<td align="center">'.$consulta[0][25].'</td>';
			//}
			//else
			//{
			//	echo '<td width="25" align="center"><input type="text" name="pl" value="'.$consulta[0][25].'" maxlength="2" size="2" style="text-align:right" '.$porlab.'></td>';
			//}
			//if(($fechahoy < $rowfecnot[0][13]) || ($fechahoy > $rowfecnot[0][14]) || ($rowfecnot[0][13] == " ") || ($rowfecnot[0][14] == " ") || ($fechahoy > $rowfecnot[0][17]))
			//{
				echo '<td align="center">'.$consulta[0][21].'</td>';
			//}
			//else
			//{
			//	echo '<td width="25" align="center"><input type="text" name="pe" value="'.$consulta[0][21].'" maxlength="2" size="2" style="text-align:right" '.$porexa.'></td>';
			//}
			//if(($fechahoy < $rowfecnot[0][15]) || ($fechahoy > $rowfecnot[0][16]) || ($rowfecnot[0][15] == " ") || ($rowfecnot[0][16] == " ") || ($fechahoy > $rowfecnot[0][17]))
			//{
				echo '<td align="center">70</td>';
			//}
			//else
			//{
			//echo '<td width="26" align="center"><input type="text" name="ph" value="70" readonly size="2" style="text-align:right" '.$sbgc.'></td>';
		
			//}
		echo '<td width="27" align="center">&nbsp;&nbsp;&nbsp;</td>
		<td width="27" align="center">&nbsp;&nbsp;&nbsp;</td>';
		if ($consulta[0][32]<=100)
		{
			echo '<td align="center">'.$consulta[0][32].'%</td>';	
			
			
			echo '</tr>
			</table><p></p>';
			?>
			<table border="1" width="100%" cellspacing="0" cellpadding="1" align="center">
			<center>
			<span class="Estilo10"><strong>Atenci&oacute;n</strong></span>: En las notas digite siempre un n&uacute;mero entero. Ejemplo: Para 0.5 digite 5  -  Para 5,0 digite 50.  Para 3,7 digite 37.
			</center>
			<tr class="tr">
				<td width="100%" colspan="13" align="center">NOTAS PARCIALES</td>
			</tr>
			<tr bgcolor="#E4E5DB">
				<td align="center">CODIGO</td>
				<td align="center">NOMBRE</td>
				<td align="center">P1</td>
				<td align="center">P2</td>
				<td align="center">P3</td>
				<td align="center">P4</td>
				<td align="center">P5</td>
				<td align="center">LAB</td>
				<td align="center">EXA</td>
				<td align="center">HAB</td>
				<td align="center">ACU</td>
				<td align="center">OBS</td>
				<td align="center">DEF</td>
			</tr>
			<?php
			echo'<input type="Hidden" name="docnroiden" value="'.$consulta[0][0].'">';
			$ano = $consulta[0][2];
			$per = $consulta[0][3];
			
			if($fechahoy < $fecini || $fechahoy > $fecfin)
			{
				$i=0;
				while(isset($consulta[$i][0]))
				{
					echo'<tr onMouseOver="this.className=\'raton_arr\'" onMouseOut="this.className=\'raton_aba\'">
					<td align="right">'.$consulta[$i][7].'</td>
						<td>'.UTF8_DECODE($consulta[$i][8]).'</td>
						<td align="center">'.$consulta[$i][10].'</td>
						<td align="center">'.$consulta[$i][12].'</td>
						<td align="center">'.$consulta[$i][14].'</td>
						<td align="center">'.$consulta[$i][16].'</td>
						<td align="center">'.$consulta[$i][18].'</td>
						<td align="center">'.$consulta[$i][22].'</td>
						<td align="center">'.$consulta[$i][20].'</td>
						<td align="center">'.$consulta[$i][24].'</td>
						<td align="center">'.$consulta[$i][29].'</td>
						<td align="center">'.$consulta[$i][27].'</td>
						<td align="center">'.$consulta[$i][26].'</td>
					</tr>';
				$i++;
				}
			}
			else
			{
				$i=0;
				while(isset($consulta[$i][0]))
				{
					echo'<tr>
					<td align="left">'.$consulta[$i][7].'</td>
					<td>'.UTF8_DECODE($consulta[$i][8]).'</td>';
					if(($fechahoy < $rowfecnot[0][1]) || ($fechahoy > $rowfecnot[0][2]) || ($rowfecnot[0][1] == " ") || ($rowfecnot[0][2] == " ") || ($fechahoy > $rowfecnot[0][17])){
						echo '<td align="center">'.$consulta[$i][10].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="nota_1'.$i.'" name="nota_1'.$i.'" onBlur="val_nota(\'nota_1'.$i.'\')" value="'.$consulta[$i][10].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$notpar1.'></td>';
						
					}
					if(($fechahoy < $rowfecnot[0][3]) || ($fechahoy > $rowfecnot[0][4]) || ($rowfecnot[0][3] == " ") || ($rowfecnot[0][4] == " ") || ($fechahoy > $rowfecnot[0][17]))
					{
						echo '<td align="center">'.$consulta[$i][12].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="nota_2'.$i.'" name="nota_2'.$i.'" onBlur="val_nota(\'nota_2'.$i.'\')" value="'.$consulta[$i][12].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$notpar2.'></td>';
					}
					if(($fechahoy < $rowfecnot[0][5]) || ($fechahoy > $rowfecnot[0][6]) || ($rowfecnot[0][5] == " ") || ($rowfecnot[0][6] == " ") || ($fechahoy > $rowfecnot[0][17]))
					{
						echo '<td align="center">'.$consulta[$i][14].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="nota_3'.$i.'" name="nota_3'.$i.'" onBlur="val_nota(\'nota_3'.$i.'\')" value="'.$consulta[$i][14].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$notpar3.'></td>';
					}
					if(($fechahoy < $rowfecnot[0][7]) || ($fechahoy > $rowfecnot[0][8]) || ($rowfecnot[0][7] == " ") || ($rowfecnot[0][8] == " ") || ($fechahoy > $rowfecnot[0][17]))
					{
						echo '<td align="center">'.$consulta[$i][16].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="nota_4'.$i.'" name="nota_4'.$i.'" onBlur="val_nota(\'nota_4'.$i.'\')" value="'.$consulta[$i][16].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$notpar4.'></td>';
					}
					if(($fechahoy < $rowfecnot[0][9]) || ($fechahoy > $rowfecnot[0][10]) || ($rowfecnot[0][9] == " ") || ($rowfecnot[0][10] == " ") || ($fechahoy > $rowfecnot[0][17]))
					{
						echo '<td align="center">'.$consulta[$i][18].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="nota_5'.$i.'" name="nota_5'.$i.'" onBlur="val_nota(\'nota_5'.$i.'\')" value="'.$consulta[$i][18].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$notpar5.'></td>';
					}
					if(($fechahoy < $rowfecnot[0][11]) || ($fechahoy > $rowfecnot[0][12]) || ($rowfecnot[0][11] == " ") || ($rowfecnot[0][12] == " ") || ($fechahoy > $rowfecnot[0][17]))
					{
						echo '<td align="center">'.$consulta[$i][22].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="lab_'.$i.'" name="lab_'.$i.'" onBlur="val_nota(\'lab_'.$i.'\')" value="'.$consulta[$i][22].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$notlab.'></td>';
					}
					if(($fechahoy < $rowfecnot[0][13]) || ($fechahoy > $rowfecnot[0][14]) || ($rowfecnot[0][13] == " ") || ($rowfecnot[0][14] == " ") || ($fechahoy > $rowfecnot[0][17]))
					{
						echo '<td align="center">'.$consulta[$i][20].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="exa_'.$i.'" name="exa_'.$i.'" onBlur="val_nota(\'exa_'.$i.'\')" value="'.$consulta[$i][20].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$notexa.'></td>';
						
					}
					if(($fechahoy < $rowfecnot[0][15]) || ($fechahoy > $rowfecnot[0][16]) || ($rowfecnot[0][15] == " ") || ($rowfecnot[0][16] == " ") || ($fechahoy > $rowfecnot[0][17]))
					{
						echo '<td align="center">'.$consulta[$i][24].'</td>';
					}
					else
					{
						echo '<td align="center"><input type="text" id="hab_'.$i.'" name="hab_'.$i.'" onBlur="val_nota(\'hab_'.$i.'\')" value="'.$consulta[$i][24].'" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" '.$nothab.'></td>';
					}
					echo '<td align="center">'.$consulta[$i][29].'</td>
					<td align="center"><input type="text" id="obs_'.$i.'" name="obs_'.$i.'" value="'.$consulta[$i][27].'" onClick="ListaValores(\'doc_lov_obsnotas.php\', \'obs_'.$i.'\', 240, 180, 550, 350)" size="2" style="text-align:right" onKeypress="return SoloNumero(event)" title="Haga clic para ver las observaciones" '.$tipobs.'></td>
					<td align="center">'.$consulta[$i][26].'</td>
					<td align="right"><input type="hidden" name="cod_'.$i.'" size="10%" id="codigo" value="'.$consulta[$i][7].'" readonly style="text-align:right"></td>
					<td align="right"><input type="hidden" name="nivel" size="10%" id="nivel" value="'.$nivel.'" readonly style="text-align:right"></td>
					</tr>';
				$i++;
				}
			}
		}
		else
		{
			echo "La  suma de los porcentajes no debe ser superior al 100%.";
			echo"<font face='Arial' size='2' color='#FF0000'><a OnMouseOver='history.go(-1)'><center>Regresar</center></a>";
			//die('<center><h3>La  suma de los porcentajes no debe ser superior al 100%.</h3></center></font>');
		}
	?>
</table><p></p>
<table width="100%" align="center" border="1">
<tr>
  <td colspan="3">
  <lu>
  <li class="PopItemStyle">&nbsp;Antes de calcular el acumulado, las definitivas o imprimir el listado, por favor grabe las notas digitadas.</li>
  <li class="PopItemStyle">&nbsp;En caso de modificaci&oacute;n de notas o porcentajes de las mismas, no olvide grabar y recalcular el acumulado.</li>
  <li class="PopItemStyle">&nbsp;Se informa a los docentes que para poder realizar la autoevaluaci&oacute;n docentes, deben registrar la totalidad de las notas en el sistema, incluyendo la nota del ex&aacute;men, la cual es obligatoria. Para los estudiantes que no tengan calificaciones, se les debe registrar la nota con valor 0, excepto en la casilla de la habilitaci&oacute;n.</li>
  </lu>
  </td>
  </tr>
<tr>
<td width="33%" align="center"><br><? echo $btn_grabar;?></td>
<?
$print = "javascript:popUpWindow('print_doc_notaspar.php', 'yes', 0, 0, 880, 680)";
echo'<td width="33%" align="center"><BR><input type="submit" value="Imprimir p&aacute;gina" onClick="'.$print.'"></td>';
?>
<td width="33%" align="center"><br><? echo $btn_defini;?></td></tr></table>
<input name="num_regs" type="hidden" value="<?php echo $i;?>">
<input name="ano" type="hidden" value="<?php echo $ano; ?>">
<input name="per" type="hidden" value="<?php echo $per; ?>">
<input name="nivel" id="nivel" type="hidden" value="<?php echo $nivel; ?>">
</form>
</BODY>
</HTML>
