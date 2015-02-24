<?

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$indice = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque") . "/imagen/";

$tab = 1;


$valorCodificado="pagina=salida";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=procesarConfirmar";
$valorCodificado.="&bloque=".$esteBloque["nombre"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

$conexion = "configuracion";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$cadena_sql = $this->cadena_sql = $this->sql->cadena_sql("eliminarTemp", "123");
$resultado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");

$cadena_sql = $this->sql->cadena_sql("insertarTemp", $variable);
$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");

if ($resultado == false) {
    echo "Se presentó un error en el sistema, contacte al administrador";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("rescatarTemp", "123");
$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($resultado == null){
    echo "...Se ha presentado un error...";
    exit;
}

$datosConfirmar = array("idElemento", "idEntrada", "idSalida", "fechaRegistro", "fechaSalida", "sede", "dependencia", "funcionario", "observacion");

$totalRegistros = count($resultado);

if ($totalRegistros > 0) {

    for ($i = 0; $i < $totalRegistros; $i++) {
        if(in_array(trim($resultado[$i]["campo"]), $datosConfirmar)){
            $variable[$resultado[$i]["campo"]] = $resultado[$i]["valor"];
        }
        
    }
    
//---------------Inicio Formulario (<form>)--------------------------------
$nombreFormulario = "confirmarSalida";
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);
unset($atributos);


//-------------Mostrar Datos a Confirmar-----------------------

    foreach ($variable as $clave => $valor) {
        $esteCampo = $clave;

//-------------Control cuadroTexto-----------------------
        $atributos["tamanno"] = "";
        $atributos["estilo"] = "";
        $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
        $atributos["texto"] = $valor;
        echo $this->miFormulario->campoTexto($atributos);
        unset($atributos);
    }

//------------------Division para los botones-------------------------
    $atributos["id"] = "botones";
    $atributos["estilo"] = "marcoBotones";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

//-------------Control Boton-----------------------
$esteCampo="botonGuardar";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["tipo"]="boton";
$atributos["estilo"]="";
$atributos["verificar"]="";
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"]=$nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------

//-------------Control Boton-----------------------
$esteCampo="botonCancelar";
$atributos["verificar"]="";
$atributos["tipo"]="boton";
$atributos["id"]=$esteCampo;
$atributos["cancelar"]="true";
$atributos["tabIndex"]=$tab++;
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"]=$nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin", $atributos);
    unset($atributos);

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"]="formSaraData"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//Fin del Formulario
echo $this->miFormulario->formulario("fin");
}
?>
