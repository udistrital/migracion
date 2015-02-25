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
$textos[1]=utf8_encode($this->lenguaje->getCadena("consultar"));
$textos[2]=utf8_encode($this->lenguaje->getCadena("listaEditar"));
$textos[3]=utf8_encode($this->lenguaje->getCadena("listaActivo"));
$textos[4]=utf8_encode($this->lenguaje->getCadena("listaInactivo"));
$textos[5]=utf8_encode($this->lenguaje->getCadena("errorObtenerDeudas"));
$textos[6]=utf8_encode($this->lenguaje->getCadena("filtroTitulo"));
$textos[7]=utf8_encode($this->lenguaje->getCadena("filtroProyectos"));
$textos[8]=utf8_encode($this->lenguaje->getCadena("filtroFacultad"));
$textos[9]=utf8_encode($this->lenguaje->getCadena("filtrar"));


$cadena_sql_proyectos = $this->sql->cadena_sql("consultarProyectos",""); 
$registrosProyectos = $esteRecursoDB->ejecutarAcceso($cadena_sql_proyectos,"busqueda");
if($registrosProyectos ==null){
	$arra["resp"] =$textos[5];
	echo json_encode($arra);
	exit;
}

//Select para Proyectos

$strProyectos ='<select name="listadoProyectos" id="listadoProyectos" onchange="reseleccionar(\'listadoFacultades\');">';
$strProyectos .='<option value="-1" selected></option>';
foreach ($registrosProyectos as $el){
	if($el[0]!='999'){
		$strProyectos .='<option value="'.$el[0].'">';
		$strProyectos .=$el[1];
		$strProyectos .='</option>';
	}
}
$strProyectos .='</select>';

$cadena_sql_facultad = $this->sql->cadena_sql("consultarFacultades","");
$registrosFacultad = $esteRecursoDB->ejecutarAcceso($cadena_sql_facultad,"busqueda");
if($registrosFacultad==null){
	$arra["resp"] =$textos[5];
	echo json_encode($arra);
	exit;
}


//Select para Facultades

$strFacultades ='<select name="listadoFacultades" id="listadoFacultades" onchange="reseleccionar(\'listadoProyectos\');">';
$strFacultades .='<option value="-1" selected=true></option>'; 
foreach ($registrosFacultad as $el){
	$strFacultades .='<option value="'.$el[0].'">';
	$strFacultades .=$el[1];
	$strFacultades .='</option>';
}
$strFacultades .='</select>';


//Boton de envio de datos
echo "<div id='filtros' style='text-align: center;'>";
echo "<br><br><h3>".$textos[6]."</h3>";
echo "<form id='deudasFiltro' name='deudasFiltro'>";
echo "<h4>".$textos[7]."</h4>";
echo $strProyectos;
//echo PHP_EOL.PHP_EOL;
echo "<h4>".$textos[8]."</h4>";
echo $strFacultades;
echo "<br><input type='button' value='".$textos[9]."' id='btnFiltro' onclick='filtrarDeudas();'>";
echo "</form>";
echo "<div id='resultadosFiltro'></div></div>";
exit;




