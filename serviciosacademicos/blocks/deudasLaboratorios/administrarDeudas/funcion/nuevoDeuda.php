<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}


/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
// Buscar proveedores
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

$rutaURL = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" );
$rutaURL .="/blocks/".$_REQUEST['bloqueGrupo']."/".$_REQUEST['bloqueNombre'];

if($_REQUEST['opcionConsulta']=='codigo') $datosArray['atributo'] = 'DEU_EST_COD';
else $datosArray['atributo'] = 'DEU_DEUDOR_ID';

$datosArray['valor']=$_REQUEST['valorConsulta'];

if(isset($_REQUEST['tipo'])&&$_REQUEST['tipo']=='ESTUDIANTE'){
	$datosArray['valor'] = $_REQUEST['codigo'];
	$datosArray['atributo'] = 'DEU_EST_COD';
}

/*
echo "Codigo".$_REQUEST['codigo']."<br>";
echo "valorConsulta:".$_REQUEST['valorConsulta']."<br>";
print_r($datosArray);
echo "<br>";
*/



//consultar Deudas
$cadena_sqlD = $this->sql->cadena_sql("consultarDeudas",$datosArray);
//echo $cadena_sqlD;
$registrosD = $esteRecursoDB->ejecutarAcceso(utf8_encode($cadena_sqlD),"busqueda");
if($registrosD==null){
	echo json_encode(utf8_encode($this->lenguaje->getCadena("errorConsultaDeuda")));
} 
//else{
	
	//consulta Listado Laboratorios
	$cadena_sqlL = $this->sql->cadena_sql("consultarListadoLaboratorios",$datosArray);
	$registrosL = $esteRecursoDB->ejecutarAcceso($cadena_sqlL,"busqueda");
	//string select listado laboratorios
	$strListado ='<select name="listadoLaboratorios" id="listadoLaboratorios">';
		foreach ($registrosL as $el){
			$strListado .='<option value="'.$el[0].'">';
			$strListado .=$el[1];
			$strListado .='</option>';
		}
	$strListado .='</select>';
	
	
	
	//consulta Listado Estados de las Deudas
	
	//No hace la consulta
	//NOTA IMPORTANTE
	//No esta ejecutando el query por lo cual  se agregan manualmente los valores en la variable registrosE
	$cadena_sqlE = $this->sql->cadena_sql("consultarEstadosDeudas",$datosArray);
	$registrosE = $esteRecursoDB->ejecutarAcceso($cadena_sqlE,"busqueda");
	
	if($registrosE==null){
		$registrosE = array();
		$registrosE[1] = array(1,'ACTIVO');
		$registrosE[2] = array(2,'INACTIVO');
		$registrosE[3] = array(3,'ANULADO');
		$registrosE[4] = array(4,'ABONADO');
		$registrosE[5] = array(5,'PAGADO');
	}
	
	//string select listado Estados
	$strEstado ='<select name="listadoEstados" id="listadoEstados">';
	foreach ($registrosE as $el){
		$strEstado .='<option value="'.$el[0].'">';
		$strEstado .=$el[1];
		$strEstado .='</option>';
	}
	$strEstado .='</select>';
	
	//string select listado A�os
	$strEstado ='<select disabled name="listadoEstados" id="listadoEstados">';
	foreach ($registrosE as $el){
		$strEstado .='<option value="'.$el[0].'">';
		$strEstado .=$el[1];
		$strEstado .='</option>';
	}
	$strEstado .='</select>';
	
	//String lista A�os A�o
	$annoBase = 1995;
	$strAnno ='<select name="Anno" id="Anno">';
	for ($i=1995; $i<=date("Y");$i++){
		$strAnno .='<option value="'.$i.'">';
		$strAnno .=$i;
		$strAnno .='</option>';
	}
	$strAnno .='</select>';
	
	//String Lista Periodo
	$strPeriodo ='<select name="Periodo" id="Periodo">';
	$strPeriodo .='<option value="1" >1</option>';
	$strPeriodo .='<option value="2" >2</option>';
	$strPeriodo .='<option value="3" >3</option>';
	$strPeriodo .='</select>';
	
	
	
	//Inicio Tabla
	$cadena = '<br><form id="tablaEdicion" name="tablaEdicion"><table class="tablaGenerica" id="tablaEdicion" style="margin: 0 auto;"><tr>';
	
	//encabezados
	/*
	foreach ($registrosD[0] as  $att => $val){
		$string = str_replace(' ', '', $att);
		$string = preg_replace('/\s+/', ' ', $string);
		$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
		if(!is_numeric($att)&&$att!='id'&&$att!='idLaboratorios'&&$att!='idEstados')	$cadena.='<td id="'.$string.'">'.$att.'</td>'; 
	}
	*/
	$cadena .= "<td>".$this->lenguaje->getCadena("nombreLaboratorio")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("material")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("ano")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("periodo")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("estado")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("fechaCreacion")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("valorDeuda")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("usuarioMultador")."</td>";
	$cadena .= "<td>".$this->lenguaje->getCadena("fechaPago")."</td>";
	$cadena .= "<td></td></tr>";
	
	//valores
	foreach ($registrosD as $valor){
		if(end($registrosD)!=$valor){
		$cadena .= "<tr>";
		foreach ($valor as  $att => $val){
				$string = str_replace(' ', '', $att);
				$string = preg_replace('/\s+/', ' ', $string);
				$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
				if(!is_numeric($att)&&$att!='id'&&$att!='idLaboratorios'&&$att!='idEstados')	
					$cadena.='<td headers="'.$string.'">'.$val.'</td>';
		}
		$cadena .= '<td>';
		//Si se permite editar
		if($_REQUEST['modulo']=='118'&&!isset($_REQUEST['soloConsulta'])){
				$cadena	.='<div class="edicionMenu" id="edicionMenu">';
				$cadena .= '<div class="listaAccion"><a onclick="editarElemento(\''.$valor['id'].'\',this,\''.$valor['idLaboratorios'].'\',\''.$valor['idEstados'].'\')" style="height:20px;float:left;background-repeat: no-repeat;background-image: url(\''.$rutaURL.'/css/images/edit.png\'); " title="'.utf8_encode($this->lenguaje->getCadena("listaEditar")).'"></a></div>';
				$cadena .='</div>';
		}
		//fin edicion
		$cadena .= "</td></tr>";
		}
		
	}
	
	//fila de edicion hidden
	$cadena .= '<tr id="aClonar" style="display:none;">';
	foreach ($registrosD[0] as  $att => $val){
		$string = str_replace(' ', '', $att);
		$string = preg_replace('/\s+/', ' ', $string);
		$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
		if(!is_numeric($att)&&$att!='id'&&$att!='idLaboratorios'&&$att!='idEstados'&&$att!='Estado'
				&&$att!='Nombre Laboratorio'&&$string!="FechaCreacion"&&$string!="FechaPago"
				&&$string!="UsuarioMultador"&&$string!='Anno'&&$att!='Periodo'&&$att!='Multa'){
			$cadena.='<td><input class="campoTabla" id="'.$string.'" class="validate[required]" value="" size="10" type="text" placeholder="'.$att.'" name="'.$string.'" ></input></td>';
		}elseif ($att==='Multa'){
			$cadena.='<td><input class="campoTabla" id="'.$string.'" class="validate[required,number]" value="" size="10" type="text" placeholder="'.$att.'" name="'.$string.'" ></input></td>';
		}elseif ($att==='Estado'){
			$cadena.='<td>'.$strEstado.'</td>';
		}elseif ($string==='NombreLaboratorio'){
			$cadena.='<td>'.$strListado.'</td>';
		}elseif ($string==='Anno'){
			$cadena.='<td>'.$strAnno.'</td>';
		}elseif ($string==='Periodo'){
			$cadena.='<td>'.$strPeriodo.'</td>';
		}elseif ($string==='FechaCreacion'||$string==='FechaPago'||$string==='UsuarioMultador'){
			$cadena.='<td><input class="campoTabla" disabled id="'.$string.'" class="validate[required]" value="" size="10" type="text" placeholder="'.$att.'" name="'.$string.'" ></input></td>';
		}
	}
	
	$cadena .= '<td>';
	
	//Si se permite editar
	if($_REQUEST['modulo']=='118'&&!isset($_REQUEST['soloConsulta'])){
		$cadena	.='<div class="edicionMenu" id="edicionMenu">';
		$cadena .= '<div class="listaAccion"><a onclick="crearElemento(this)" style="height:20px;float:left;background-repeat: no-repeat;background-image: url(\''.$rutaURL.'/css/images/save.png\'); " title="'.utf8_encode($this->lenguaje->getCadena("listaGuardar")).'"></a></div>';
		$cadena .= '<div class="listaEliminar"><a onclick="$(this).closest(\'tr\').remove();" style="height:20px;height:20px;width:20px;float:left;background-repeat: no-repeat;background-image: url(\''.$rutaURL.'/css/images/delete.png\'); " title="'.utf8_encode($this->lenguaje->getCadena("listaEliminar")).'"></a></div>';
		$cadena .='</div>';
	}
	//fin edicion
	$cadena .= "</td></tr>";
	
	//$cadena .= '</tr>';
	//fin tabla
	$cadena .= "</table></form><br>";
	
	
	//Titulo Tabla
	$tituloTabla = "<h3><b>".utf8_encode($this->lenguaje->getCadena("deudasUsuario"))." :</b></h3><br>";
	
	
	
//}
//If usuario permitido

	if($_REQUEST['modulo']=='118'&&!isset($_REQUEST['soloConsulta'])){
		$add = '<div style="text-align: center;">';
		$add .= '<br><input  id="agregarFila" style="margin: 0 auto;" onclick="agregarFila()" type="button" value="'.utf8_encode($this->lenguaje->getCadena("agregar")).'"></input>';
		$add .= '</div>';
		$add .= '<form name ="infoDeudor" id="infoDeudor">';
		$add .= '<input type="hidden" name="identificacionDeudor" value="'.$_REQUEST['valorConsulta'].'"></input>';
		$add .= '<input type="hidden" name="tipoDeudor" value="'.$_REQUEST['tipoDeudor'].'"></input>';
		$add .= '<input type="hidden" name="codigo" value="'.$_REQUEST['codigo'].'"></input>';
		$add .= '</form>';
	}else $add ="<br>";
	
	echo $tituloTabla.$add.$cadena;
	