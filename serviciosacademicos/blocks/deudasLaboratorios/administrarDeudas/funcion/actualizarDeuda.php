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

$date = new DateTime();
$sqlP = $this->sql;

$rutaURL = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" );
$rutaURL .="/blocks/".$_REQUEST['bloqueGrupo']."/".$_REQUEST['bloqueNombre'];

$exitoString = utf8_encode($this->lenguaje->getCadena("resistroExito"));

$datosArray['idDeuda'] = $_REQUEST['idDeuda'];
$datosArray['Material']=$_REQUEST['Material'];
$datosArray['Multa']=$_REQUEST['Multa'];
$datosArray['Periodo']=$_REQUEST['Periodo'];
$datosArray['Ano']=$_REQUEST['Anno'];
$datosArray['Laboratorio']=$_REQUEST['listadoLaboratorios'];
$datosArray['Estado']=$_REQUEST['listadoEstados'];
$datosArray['identificacionDeudor']=$_REQUEST['identificacionDeudor'];
$datosArray['FechaCreacion']  =$_REQUEST['FechaCreacion'];

$valido = 0;

$registro = array();

//comparar Multa y actualizar en caso de ser diferente
$sql_consultaMultaID = $sqlP->cadena_sql("consultaMultaID",$datosArray);
$consultaMultaID = $esteRecursoDB->ejecutarAcceso($sql_consultaMultaID,"busqueda");
if($consultaMultaID==false){ echo json_encode($consultaMultaID);$valido++;}
else{
	if($consultaMultaID[0][0]!=$datosArray['Multa']){
		
		//Registro actualizacion Multa
		//El registro debe ir antes de la ejecucion del insert, update, delete
		$registro['deuId']=$datosArray['idDeuda'];
		$registro['nombreCampo']='DEU_MULTA';
		$registro['valorNuevo']=$datosArray['Multa'];
		$registro['tipoTransaccion']=1;
		$this->crearRegistro($registro);
		unset($registro);
		
		$sql_actualizarMulta = $sqlP->cadena_sql("actualizarMulta",$datosArray);
		$actualizarMulta = $esteRecursoDB->ejecutarAcceso($sql_actualizarMulta);
		if($actualizarMulta!=false){ echo json_encode($actualizarMulta);$valido++;}
		
	}
		
}
	
	


//Comparar Estado y actualizar

$sql_consultaEstadoID = $sqlP->cadena_sql("consultaEstadoID",$datosArray);
$consultaEstadoID = $esteRecursoDB->ejecutarAcceso($sql_consultaEstadoID,"busqueda");
if($consultaEstadoID==false){ echo json_encode($consultaEstadoID);$valido++;}
else{
	if($consultaEstadoID[0][0]!=$datosArray['Estado']){
		
		//Registro actualizacion Multa
		//El registro debe ir antes de la ejecucion del insert, update, delete
		$registro['deuId']=$datosArray['idDeuda'];
		$registro['nombreCampo']='DEU_ESTADO';
		$registro['valorNuevo']=$datosArray['Estado'];
		$registro['tipoTransaccion']=1;
		$this->crearRegistro($registro);
		unset($registro);
		
		$sql_actualizarEstado = $sqlP->cadena_sql("actualizarEstado",$datosArray);
		$actualizarEstado = $esteRecursoDB->ejecutarAcceso($sql_actualizarEstado);
		if($actualizarEstado!=false){ echo json_encode($actualizarEstado);$valido++;}
	}

}

//aCTUALIZA FECHA DE PAGO PARA ESTADOS ABONADO Y pAGADO

	if(($datosArray['Estado']==4||$datosArray['Estado']==5)&&$consultaEstadoID[0][0]!=$datosArray['Estado']){
		
		//Registro actualizacion Multa
		//El registro debe ir antes de la ejecucion del insert, update, delete
		$registro['deuId']=$datosArray['idDeuda'];
		$registro['nombreCampo']='DEU_FECHA_PAGO';
		$registro['valorNuevo']=date("d/m/y");
		$registro['tipoTransaccion']=1;
		$this->crearRegistro($registro);
		unset($registro);
		
		$sql_actualizarFechaPago = $sqlP->cadena_sql("actualizarFechaPago",$datosArray);
		$actualizarFechaPago = $esteRecursoDB->ejecutarAcceso($sql_actualizarFechaPago);
		if($actualizarFechaPago!=false){ echo json_encode($actualizarFechaPago);$valido++;}
	}






//Valida errores
if ($valido>0) {
	$errorString = utf8_encode($this->lenguaje->getCadena("errorActualizar"));
	echo json_encode($errorString);
	exit;
}


//Salida


$strEdicion ='<div class="edicionMenu" id="edicionMenu">';
$strEdicion .= '<div class="listaAccion"><a onclick="editarElemento(\''.$datosArray['idDeuda'].'\',this,\''.$datosArray['Laboratorio'].'\',\''.$datosArray['Estado'].'\')" style="height:20px;float:left;background-repeat: no-repeat;background-image: url(\''.$rutaURL.'/css/images/edit.png\'); " title="'.utf8_encode($this->lenguaje->getCadena("listaEditar")).'"></a></div>';
$strEdicion .='</div>'; 
echo json_encode(array(true,$datosArray['idDeuda'],$datosArray['FechaCreacion'],$strEdicion));

//Log Obligatorio