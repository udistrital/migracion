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

$cript = Encriptador::singleton();

//Obtener uri WSDl

//Recuperar uri WSDL

$cadena_sql = $this->sql->cadena_sql("consultarWSDL",$_REQUEST['usuario']);
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorConsulta");
	echo "</b></p></div>";
	exit;
}

$wsdl = $registros[0]['parametro_descripcion'];


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


//Obtener lista de proyectos

try{
	$proyectos =  $client->mc_projects_get_user_accessible($user,$password) ;
	foreach ($proyectos as $prj)
			if(strtolower($prj->name)=='soporte') $idprj = $prj->id;
	
		
} catch (SoapFault  $f)	{
	$idprj = 2;
	
}
	
	

	
//Obtener categorias del proyecto e incluirlas en un select

$categorias = $client->mc_project_get_categories($user,$password,$idprj);



$selectCategorias = '<select style="width:190px" class="ui-widget ui-widget-content ui-corner-all" name="categoria">';

foreach ($categorias as $a=>$b){
	$limpio = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $b));
	
	
	//if(is_numeric(strpos($limpio,'atencin')) ){
		$selectCategorias .= "<option value='".$b."'>".$b."</option>";
	//}
}
$selectCategorias .="</select>";




$cadena_sql = $this->sql->cadena_sql("consultarTipoAtencion","");
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorConsulta");
	echo "</b></p></div>";
	exit;
}

$selectTipoUsuarioAtendido = '<select style="width:190px" class="ui-widget ui-widget-content ui-corner-all" name="tipoUsuario">';

foreach ($registros as $reg){
	
	 $selectTipoUsuarioAtendido  .= "<option value='".$reg['atencion_tipo_usuario']."'>".$reg['atencion_tipo_usuario']."</option>";
}
$selectTipoUsuarioAtendido .="</select>";



//consultar casos comunes
$cadena_sql = $this->sql->cadena_sql("consultarCasosComunes","");
$registros = $esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
if($registros==false){
	echo '<div style="text-align: center"><p><b>';
	echo $this->lenguaje->getCadena("errorConsulta");
	echo "</b></p></div>";
	exit;
}
///
$selectCasosComunes = '<select onchange=" $(\'#descripcion\').val($( \'#casoComun option:selected\' ).text())  ;" style="width:190px" class="ui-widget ui-widget-content ui-corner-all" id="casoComun" name="casoComun">';
$selectCasosComunes  .= '<option value="0" ></option>';
foreach ($registros as $reg){
	$selectCasosComunes  .= "<option value='".$reg['casos_descripcion']."'>".$reg['casos_descripcion']."</option>";
}
$selectCasosComunes .="</select>";


echo '<div id="consulta" style="text-align: center;">';
echo '<form name="formulario" id="formulario">';




echo '<table style="margin: 0px auto;text-align: right;">';

echo '<tr><td>';
echo "<b>".$this->lenguaje->getCadena("tipoRequerimiento")."</b>";
echo '</td><td>';
echo $selectCategorias ;
echo '</td></tr>';


echo '<tr><td>';
echo "<b>".$this->lenguaje->getCadena("tipoUsuario")."</b>";
echo '</td><td>';
echo $selectTipoUsuarioAtendido ;
echo '</td></tr>';

echo '<tr><td>';
echo "<b>".$this->lenguaje->getCadena("casosComunes")."</b>";
echo '</td><td>';
echo $selectCasosComunes ;
echo '</td></tr>';

echo '<tr><td>';
echo "<b>".$this->lenguaje->getCadena("descripcion")."</b>";
echo '</td><td><textarea type="text" width="80px" id="descripcion" name="descripcion" class="ui-widget ui-widget-content ui-corner-all validate[required]" title="'.utf8_encode($this->lenguaje->getCadena("ingreseValor")).'"></textarea>';
echo '</td></tr>'; 

echo '</table>';

echo "</form>";
echo '<div>';
echo '<div style="display: inline-block;">';
echo '<input type="button" width="30px" id="consultarUsuario" onclick="enviarRequerimientoCerrado()" value="'.utf8_encode($this->lenguaje->getCadena("reportarIncidenteCerrado")).'"></input>';
echo '</div>';

echo '<div style="display: inline-block;">';
echo '<input type="button" width="30px" id="consultarUsuario" onclick="enviarRequerimientoAbierto()" value="'.utf8_encode($this->lenguaje->getCadena("reportarIncidenteAbierto")).'"></input>';
echo '</div>';
echo '</div>';

echo "</div>";
echo '<div id="resultado">';
echo '<div id="resultadoUsuario">';
echo "</div>";
echo '<div id="resultadoDeudas">';
echo "</div>";
echo "</div>";
echo '<div id="listado">';
echo "</div>";



