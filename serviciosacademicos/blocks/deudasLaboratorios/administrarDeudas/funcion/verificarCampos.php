<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
	//Este se considera un error fatal
	exit;
}



if(!isset($_REQUEST['funcion'])||$_REQUEST['funcion']=="")
	$this->error=true;

if(isset($_REQUEST['metodo'])&&$_REQUEST['metodo']=="operacion"){
	if(!isset($_REQUEST['opcionConsulta'])||$_REQUEST['opcionConsulta']>3||!isset($_REQUEST['valorConsulta']))
		$this->error=true;
	if(isset($_REQUEST['valorConsulta'])&&$_REQUEST['opcionConsulta']=="nombre"&&strlen($_REQUEST['valorConsulta'])<4){
		echo json_encode($this->lenguaje->getCadena("errorUsuarioNombre"));
		$this->error=true;
	}if(isset($_REQUEST['valorConsulta'])&&$_REQUEST['opcionConsulta']=="identificacion"&&strlen($_REQUEST['valorConsulta'])<4)
		$this->error=true;
	if(isset($_REQUEST['valorConsulta'])&&$_REQUEST['opcionConsulta']=="identificacion"&&!is_numeric($_REQUEST['valorConsulta'])){
		echo json_encode($this->lenguaje->getCadena("errorUsuarioIdentificacion"));
		$this->error=true;
	}if(isset($_REQUEST['valorConsulta'])&&$_REQUEST['opcionConsulta']=="codigo"&&!is_numeric($_REQUEST['valorConsulta'])){
		echo json_encode($this->lenguaje->getCadena("errorUsuarioCodigo"));
		$this->error=true;
	}
	
}

if(isset($_REQUEST['metodo'])&&$_REQUEST['metodo']=="crear"){
	if(!isset($_REQUEST['Material'])||!isset($_REQUEST['Multa'])||!isset($_REQUEST['Periodo'])
		||!isset($_REQUEST['Anno'])||!isset($_REQUEST['listadoLaboratorios'])||!isset($_REQUEST['listadoEstados'])
		||!isset($_REQUEST['identificacionDeudor']))
		$this->error=true;

	if( strlen($_REQUEST['Material'])>=100){
		echo json_encode($this->lenguaje->getCadena("errorMaterial"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['Multa'])||$_REQUEST['Multa']<0){
		echo json_encode($this->lenguaje->getCadena("errorMulta"));
		$this->error=true;
		exit;
	}if( strlen($_REQUEST['Multa'])>9){
		
		echo json_encode($this->lenguaje->getCadena("errorMultaLongitud"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['Periodo'])||$_REQUEST['Periodo']<0||$_REQUEST['Periodo']>3){
		echo json_encode($this->lenguaje->getCadena("errorPeriodo"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['listadoLaboratorios'])){
		echo json_encode($this->lenguaje->getCadena("errorListadoLaboratorios"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['listadoEstados'])){
		echo json_encode($this->lenguaje->getCadena("errorListadoEstados"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['Anno'])	||$_REQUEST['Anno']<1995||$_REQUEST['Anno']>date("Y")){
		echo json_encode($this->lenguaje->getCadena("errorAnno"));
		$this->error=true;
		exit;
	}
}

if(isset($_REQUEST['funcion'])&&$_REQUEST['funcion']=="actualizarDeuda"){
	if(!isset($_REQUEST['Material'])||!isset($_REQUEST['Multa'])||!isset($_REQUEST['Periodo'])
	||!isset($_REQUEST['Anno'])||!isset($_REQUEST['listadoLaboratorios'])||!isset($_REQUEST['listadoEstados'])
	||!isset($_REQUEST['identificacionDeudor']))
		$this->error=true;
	
	if( strlen($_REQUEST['Material'])>=100){
		echo json_encode($this->lenguaje->getCadena("errorMaterial"));
		$this->error=true;
		exit;
	}if( strlen($_REQUEST['Multa'])>9){
		
		echo json_encode($this->lenguaje->getCadena("errorMultaLongitud"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['Multa'])||$_REQUEST['Multa']<0){
		echo json_encode($this->lenguaje->getCadena("errorMulta"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['Periodo'])||$_REQUEST['Periodo']<0||$_REQUEST['Periodo']>3){
		echo json_encode($this->lenguaje->getCadena("errorPeriodo"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['listadoLaboratorios'])){
		echo json_encode($this->lenguaje->getCadena("errorListadoLaboratorios"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['listadoEstados'])){
		echo json_encode($this->lenguaje->getCadena("errorListadoEstados"));
		$this->error=true;
		exit;
	}if(!is_numeric ($_REQUEST['Anno'])	||$_REQUEST['Anno']<1995||$_REQUEST['Anno']>date("Y")){
		echo json_encode($this->lenguaje->getCadena("errorAnno"));
		$this->error=true;
		exit;
	}

}

if(isset($_REQUEST['funcion'])&&$_REQUEST['funcion']=="filtrarDeudas"){
	if(!isset($_REQUEST['listadoProyectos'])||!isset($_REQUEST['listadoFacultades'])) {
		$this->error=true;
	}
	
}