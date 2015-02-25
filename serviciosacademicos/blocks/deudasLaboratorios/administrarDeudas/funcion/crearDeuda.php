<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

header('Content-type: application/json');

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

$conexion2 = "estructura";
$esteRecursoBase = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoBase) {
	//Este se considera un error fatal
	exit;
}

$configuracion = $this->miConfigurador->configuracion;


$sqlP = $this->sql;

$rutaURL = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" );
$rutaURL .="/blocks/".$_REQUEST['bloqueGrupo']."/".$_REQUEST['bloqueNombre'];

$exitoString = utf8_encode($this->lenguaje->getCadena("resistroExito"));

$datosArray['Material']=$_REQUEST['Material'];
$datosArray['Multa']=$_REQUEST['Multa'];
$datosArray['Periodo']=$_REQUEST['Periodo'];
$datosArray['Ano']=$_REQUEST['Anno'];
$datosArray['Laboratorio']=$_REQUEST['listadoLaboratorios'];
$datosArray['Estado']=$_REQUEST['listadoEstados'];
$datosArray['identificacionDeudor']=$_REQUEST['identificacionDeudor'];
$datosArray['usuario']=$_REQUEST['usuario'];


//Consultar Nombre y codigo para estudiante
switch ($_REQUEST['tipoDeudor']){
	case "ESTUDIANTE":
		$sql_nombre = $sqlP->cadena_sql("consultarNombrePorIdentificacionEstudiante",$datosArray);
		if(isset($_REQUEST['codigo'])) $datosArray['codDeudor'] =  $_REQUEST['codigo'];
		else{
			$sql_codigo = $sqlP->cadena_sql("consultarCodigoEstudiantePorIdentificacion",$datosArray);
			$consultaCodigo = $esteRecursoDB->ejecutarAcceso($sql_codigo,"busqueda");
		}
		if(isset($consultaCodigo)) $datosArray['codDeudor'] =$consultaCodigo[0][0];
		break;
	case "DOCENTE":
		$sql_nombre = $sqlP->cadena_sql("consultarNombrePorIdentificacionDocente",$datosArray);
		$datosArray['codDeudor']="0";
		break;
	case "ADMINISTRATIVO";
		$sql_nombre = $sqlP->cadena_sql("consultarNombrePorIdentificacionAdministrativo",$datosArray);
		$datosArray['codDeudor']="0";
		break;
	default:
		break;
		
}
$consultaNombreDeudor = $esteRecursoDB->ejecutarAcceso($sql_nombre,"busqueda");
if($consultaNombreDeudor != null) $datosArray['nombreDeudor'] =$consultaNombreDeudor[0][0]; 
else echo "sin resultados";

//Id mayor deudores
$sql_id = $sqlP->cadena_sql("idMayorDeudores",$datosArray); 
$consultaId = $esteRecursoDB->ejecutarAcceso($sql_id,"busqueda");
if($consultaId != null) $datosArray['id'] =$consultaId[0][0]+1;

//Registro tabla de eventos
//Registro Insert Multa
//El registro debe ir antes de la ejecucion del insert, update, delete
$registro['deuId']=$datosArray['id'];
$registro['tipoTransaccion']=0;
$registro['valorAnterior']=0;
$valores = array(array());

$valores[0][0] = $datosArray['Material'];
$valores[0][1] = 'DEU_MATERIAL';
$valores[1][0] = $datosArray['Multa'];
$valores[1][1] = 'DEU_MULTA'; 
$valores[2][0] = $datosArray['Periodo'];
$valores[2][1] = 'DEU_PER';
$valores[3][0] = $datosArray['Ano'];
$valores[3][1] = 'DEU_ANO';
$valores[4][0] = $datosArray['Laboratorio'];
$valores[4][1] = 'DEU_CPTO_COD';
$valores[5][0] = $datosArray['Estado'];
$valores[5][1] = 'DEU_ESTADO';
$valores[6][0] = $datosArray['identificacionDeudor'];
$valores[6][1] = 'DEU_DEUDOR_ID';


//inserts
$sql_crearDeuda = $sqlP->cadena_sql("crearDeuda",$datosArray);
$crearDeuda = $esteRecursoDB->ejecutarAcceso($sql_crearDeuda,"");
$date = new DateTime(); 
$strEdicion ='<div class="edicionMenu" id="edicionMenu">';
$strEdicion .= '<div class="listaAccion"><a onclick="editarElemento(\''.$datosArray['id'].'\',this,\''.$datosArray['Laboratorio'].'\',\'1\')" style="height:20px;float:left;background-repeat: no-repeat;background-image: url(\''.$rutaURL.'/css/images/edit.png\'); " title="'.utf8_encode($this->lenguaje->getCadena("listaEditar")).'"></a></div>';
$strEdicion .='</div>'; 
//echo $sql_crearDeuda;

if($crearDeuda!=false) echo json_encode("error:".$crearDeuda);
else echo json_encode(array(true,$datosArray['id'],$date->format('d/m/y'),$strEdicion));

//Crea registros
foreach ($valores as $val){
	$registro['valorNuevo']=$val[0];
	$registro['nombreCampo']=$val[1];
	$this->crearRegistro($registro);
}

unset($registro);


