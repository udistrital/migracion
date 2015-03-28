<?PHP

//require_once('dir_relativo.cfg');
//require_once(dir_conect.'valida_pag.php');
require_once('valida_capfec_notaspar.php');
//require_once(dir_conect.'fu_tipo_user.php');
//require_once('../calendario/calendario.php');
include_once("../clase/multiConexion.class.php");

$esta_configuracion=new config();
$configuracion=$esta_configuracion->variable("../");

$conexion=new multiConexion();
$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

fu_tipo_user(4);
$title = "title='Formato: DD/MM/AAAA' readonly";
global $raiz;

$cadena_sql="SELECT cra_nombre FROM accra WHERE cra_cod = ".$_SESSION['C']." AND cra_estado = 'A'";
$nombreCra = $conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");

$py=$nombreCra[0]['cra_nombre'];

$nombreformulario = "fecpar";

$p1i = "p1i";
$p1f = "p1f";
$p2i = "p2i";
$p2f = "p2f";
$p3i = "p3i";
$p3f = "p3f";
$p4i = "p4i";
$p4f = "p4f";
$p5i = "p5i";
$p5f = "p5f";
$labi = "labi";
$labf = "labf";
$exai = "exai";
$exaf = "exaf";
$habi = "habi";
$habf = "habf";

$FinFecNotasPar = "SELECT TO_CHAR(ACE_FEC_FIN, 'dd-Mon-yyyy')
		FROM ACCALEVENTOS,ACASPERI
		WHERE APE_ANO = ACE_ANIO
		AND APE_PER = ACE_PERIODO
		AND APE_ESTADO = 'A'
		AND ACE_CRA_COD = ".$_SESSION['C']."
		AND ACE_COD_EVENTO = 7";

$RowFFNP = $conexion->ejecutarSQL($configuracion,$accesoOracle,$FinFecNotasPar,"busqueda");
$FechaFinalNotaspar = $RowFFNP[0][0];

$QryFec = "SELECT NPF_CRA_COD,
	TO_CHAR(NPF_IPAR1,  'DD/MM/YYYY'),
	TO_CHAR(NPF_FPAR1,  'DD/MM/YYYY'),
	TO_CHAR(NPF_IPAR2,  'DD/MM/YYYY'),
	TO_CHAR(NPF_FPAR2,  'DD/MM/YYYY'),
	TO_CHAR(NPF_IPAR3,  'DD/MM/YYYY'), 
	TO_CHAR(NPF_FPAR3,  'DD/MM/YYYY'),
	TO_CHAR(NPF_IPAR4,  'DD/MM/YYYY'), 
	TO_CHAR(NPF_FPAR4,  'DD/MM/YYYY'),
	TO_CHAR(NPF_IPAR5,  'DD/MM/YYYY'), 
	TO_CHAR(NPF_FPAR5,  'DD/MM/YYYY'),
	TO_CHAR(NPF_ILAB,  'DD/MM/YYYY'), 
	TO_CHAR(NPF_FLAB,  'DD/MM/YYYY'),
	TO_CHAR(NPF_IEXA,  'DD/MM/YYYY'),  
	TO_CHAR(NPF_FEXA,  'DD/MM/YYYY'),
	TO_CHAR(NPF_IHAB,  'DD/MM/YYYY'),
	TO_CHAR(NPF_FHAB,  'DD/MM/YYYY')
	FROM acnotparfec
	WHERE NPF_CRA_COD =".$_SESSION['C']."
	AND NPF_ESTADO = 'A'";

$RowFec = $conexion->ejecutarSQL($configuracion,$accesoOracle,$QryFec,"busqueda");

if($RowFec[0][0] != "")
{
	$accion = "prog_update_notaspar.php";
}
else
{
	$accion = "prog_insert_notaspar.php";
}

print '<form action="'.$accion.'" method="post" name="fecpar" id="fecpar">
<center><span class="Estilo5">FECHAS DE DIGITACI&Oacute;N DE NOTAS PARCIALES DEL PROYECTO CURRICULAR<BR> '.$py.'</span></center>

<table border="1" align="center">
<caption>No programe fechas superiores a la fecha de cierre de digitaci&oacute;n de notas. <br>Fecha de Cierre: '.$FechaFinalNotaspar.'</caption>
<tr class="tr">
  <td align="center">Evento</td>
  <td align="center">Fec. Inicial</td>
  <td align="center">Fec. Final</td>
</tr>
<tr>
  <td><span class="Estilo5">Parcial 1</span></td>
  <td><input type="text" name="p1i" size="13" VALUE="'.$RowFec[0][1].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p1i.'\')" '.$title.'></td>
  <td><input type="text" name="p1f"  size="13" VALUE="'.$RowFec[0][2].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p1f.'\')" '.$title.'></td>
</tr>
<tr>
  <td><span class="Estilo5">Parcial 2</span></td>
  <td><input type="text" name="p2i" size="13" VALUE="'.$RowFec[0][3].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p2i.'\')" '.$title.'></td>
  <td><input type="text" name="p2f" size="13" VALUE="'.$RowFec[0][4].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p2f.'\')" '.$title.'></td>
</tr>
<tr>
  <td><span class="Estilo5">Parcial 3</span></td>
  <td><input type="text" name="p3i" size="13" VALUE="'.$RowFec[0][5].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p3i.'\')" '.$title.'></td>
  <td><input type="text" name="p3f" size="13" VALUE="'.$RowFec[0][6].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p3f.'\')" '.$title.'></td>
</tr>
<tr>
  <td><span class="Estilo5">Parcial 4</span></td>
  <td><input type="text" name="p4i" size="13" VALUE="'.$RowFec[0][7].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p4i.'\')" '.$title.'></td>
  <td><input type="text" name="p4f" size="13" VALUE="'.$RowFec[0][8].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p4f.'\')" '.$title.'></td>
</tr>
<tr>
  <td><span class="Estilo5">Parcial 5</span></td>
  <td><input type="text" name="p5i" size="13" VALUE="'.$RowFec[0][9].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p5i.'\')" '.$title.'></td>
  <td><input type="text" name="p5f" size="13" VALUE="'.$RowFec[0][10].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$p5f.'\')" '.$title.'></td>
</tr>
<tr>
  <td><span class="Estilo5">Laboratorio</span></td>
  <td><input type="text" name="labi" size="13" VALUE="'.$RowFec[0][11].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$labi.'\')" '.$title.'></td>
  <td><input type="text" name="labf" size="13" VALUE="'.$RowFec[0][12].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$labf.'\')" '.$title.'></td>
</tr>
<tr>
  <td><span class="Estilo5">Examen</span></td>
  <td><input type="text" name="exai" size="13" VALUE="'.$RowFec[0][13].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$exai.'\')" '.$title.'></td>
  <td><input type="text" name="exaf" size="13" VALUE="'.$RowFec[0][14].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$exaf.'\')" '.$title.'></td>
</tr>
<tr>
  <td><span class="Estilo5">Habilitaci&oacute;n</span></td>
  <td><input type="text" name="habi" size="13" VALUE="'.$RowFec[0][15].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$habi.'\')" '.$title.'></td>
  <td><input type="text" name="habf" size="13" VALUE="'.$RowFec[0][16].'" onclick="muestraCalendario(\''. $raiz.'\',\''. $nombreformulario .'\',\''.$habf.'\')" '.$title.'></td>
</tr>
<tr>
  <td colspan="3" align="center">
  <input type="submit" name="Submit" value="Grabar">
  <input name="cracod" type="hidden" value="'. $_SESSION['C'].'">
  <input name="fechaFin" type="hidden" value="'. $fechafin.'">    
</td>
</tr>
</table>
</form>';

?>