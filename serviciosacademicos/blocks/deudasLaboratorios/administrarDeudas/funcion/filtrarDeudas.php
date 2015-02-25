<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

if(isset($_REQUEST['modulo'])){
	switch($_REQUEST['modulo']){
	case "80":
		$conexion="soporteoas";
		break;	
	case "118":
		$conexion="laboratorios";
			break;
	default: 
		$conexion="estructura";
	break;
	}
}else exit;


$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
    //Este se considera un error fatal
    exit;
}

//Selecciona el tipo de consulta
if($_REQUEST['listadoProyectos']=='-1'&&$_REQUEST['listadoFacultades']=='-1') {
	$cadena_sqlD = $this->sql->cadena_sql("consultarDeudasTodas",""); 
}elseif ($_REQUEST['listadoProyectos']=='-1'&&$_REQUEST['listadoFacultades']!='-1') {
	$cadena_sqlD = $this->sql->cadena_sql("consultaDeudasFacultad", $_REQUEST['listadoFacultades']);
}elseif ($_REQUEST['listadoProyectos']!='-1'&&$_REQUEST['listadoFacultades']=='-1'){
	$cadena_sqlD = $this->sql->cadena_sql("consultaDeudasProyecto", $_REQUEST['listadoProyectos']);
}

$registrosD = $esteRecursoDB->ejecutarAcceso($cadena_sqlD,"busqueda");
if($registrosD==null){
		echo json_encode(utf8_encode($this->lenguaje->getCadena("errorConsultaFiltro")));
		exit;
}

$titulo =$this->lenguaje->getCadena("resultado");

//Mostrar Tabla

//Inicio Tabla
$cadena = '<br><table class="tablaGenerica" id="tablaEdicion" style="margin: 0 auto;"><tr>';

//encabezados
foreach ($registrosD[0] as  $att => $val){
	$string = str_replace(' ', '', $att);
	$string = preg_replace('/\s+/', ' ', $string);
	$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
	if(!is_numeric($att)) $cadena.='<td id="'.$string.'">'.$att.'</td>';
}
$cadena .= "</tr>";

//Valores Tabla

foreach ($registrosD as $valor){
	$cadena .= "<tr>";
	foreach ($valor as  $att => $val){
		$string = str_replace(' ', '', $att);
		$string = preg_replace('/\s+/', ' ', $string);
		$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
		if(!is_numeric($att))
			$cadena.='<td headers="'.$string.'">'.$val.'</td>';
		
	}
	$cadena .= "</td></tr>";
}

//fin tabla
$cadena .= "</table></form><br>";

echo $cadena;

exit;

