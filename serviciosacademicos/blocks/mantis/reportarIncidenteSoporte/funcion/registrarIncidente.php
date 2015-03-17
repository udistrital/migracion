<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

$conexion="mantis";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}

//Recuperar uri WSDL

$cadena_sql = $this->sql->cadena_sql("consultarWSDL",$_REQUEST['usuario']);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoRegistra");
	echo "</b></p></div>";
	exit;
}

$wsdl = $registros[0]['parametro_descripcion'];

//Recuperar uri busqueda de casos mantis

$cadena_sql = $this->sql->cadena_sql("consultarURIBusqueda",$_REQUEST['usuario']);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorNoRegistra");
	echo "</b></p></div>";
	exit;
}

$busqueda = $registros[0]['parametro_descripcion'];




//Consultar el respectivo usuario de mantis
$cadena_sql = $this->sql->cadena_sql("consultarUsuarioMantis",$_REQUEST['usuario']);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorConsulta");
	echo "</b></p></div>";
	exit;
}

$usuarioAsignar = $registros[0]['usuarios_mantis'];


$cript = Encriptador::singleton();

//Obtener Usuario y password publicador

$cadena_sql = $this->sql->cadena_sql("consultarUsuarioPublicador","");
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorConsulta");
	echo "</b></p></div>";
	exit;
}

$user = $registros[0]['usuarios_mantis'];
$password = $cript->decodificar($registros[0]['usuarios_password']);

///Crear cliente SOAP
$soap_options = array(
		'trace'       => 1,     // traces let us look at the actual SOAP messages later
		'exceptions'  => 1,
		'proxy_host'  => '10.20.4.15',
		'proxy_port'  => '3128'
);

$client = new SoapClient($wsdl ,$soap_options);


//obtiene el status cerrado
switch($_REQUEST['estado']){
	case 'abierto':
		$statusid = 60;
		break;
	default:
		try{
			$statuses =  $client->mc_enum_status($user,$password);
			$statusid = end($statuses)->id;
			
		} catch (SoapFault  $f)	{
				$statusid = 90;
		}
	break;
}

//Obtiene el di del proyecto
//Obtener lista de proyectos
try{
	$proyectos =  $client->mc_projects_get_user_accessible($user,$password) ;
	foreach ($proyectos as $prj)
			if(strtolower($prj->name)=='soporte') $idprj = $prj->id;
	
		
} catch (SoapFault  $f)	{
	$idprj = 2;
	
}
$summary =  $_REQUEST['categoria']." a ".$_REQUEST['tipoUsuario']." ".date("d/m/Y H:i");


//Registrar Incidente

//Asignar Requerimiento al usuario carlos_romero

$issueData =  new StdClass();

//Proyecto
$project = new StdClass();
$project->id = $idprj;
$issueData->project = $project;

//Status
$status = new StdClass();
$status->id = $statusid;
$issueData->status = $status;

//Resumen
$issueData->summary = $summary;

//Descripcion
$issueData->description = $_REQUEST['descripcion'];

//categoria
$issueData->category = $_REQUEST['categoria'];

//Asignado a
$handler = new StdClass();
$handler ->name = $usuarioAsignar;
$issueData->handler = $handler;

//Peticion SOAP
try{
	$requerimiento = $client->mc_issue_add($user,$password,$issueData);
} catch (SoapFault  $f)	{
		echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorCreacionIncidente");
	echo "</b></p></div>";
	exit;
	
	}
	


//Mensaje

if($requerimiento >0){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("creacionExitosa");
	echo '<a href="'.$busqueda .$requerimiento.'">'.$requerimiento.'</a>';
	echo "</b></p></div>";
	$this->registrarActividad($requerimiento);
	exit;
	
}



exit;


