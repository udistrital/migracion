<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/registroEvaluacionDocente/";
//$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/evaldocentes/inicioEvaldocente/";
//$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "evaldocentes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarPeriodos", "");
$registroPeriodo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$variable['periodo'] = $registroPeriodo[0][0];

$conexion = "autoevaluadoc";
$esteRecursoDBORA = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDBORA) {

    echo "Este se considera un error fatal";
    exit;
}

$valorCodificado = "pagina=evaluacionDocente";
$valorCodificado.="&action=" . $esteBloque["nombre"];
$valorCodificado.="&opcion=consularTiposEvaluacion";
$valorCodificado.="&usuario=" . $_REQUEST['usuario'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionAsociarFormatos";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Tipos de resultados";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);


////-------------------------------Mensaje-------------------------------------
$tipo = 'message';
$mensaje = "Seleccione el período académico y tipo de resultado que desea consultar.<br>
            Los campos con * son obligatorios.";

$esteCampo = "mensaje";
$atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"] = "";
$atributos["estilo"] = "centrar";
$atributos["tipo"] = $tipo;
$atributos["mensaje"] = $mensaje;
echo $this->miFormulario->cuadroMensaje($atributos);
unset($atributos);

$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"] = $nombreFormulario;
$atributos["tipoFormulario"] = "multipart/form-data";
$atributos["metodo"] = "POST";
$atributos["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario("inicio", $atributos);
unset($atributos);


//------------------Control Lista Desplegable------------------------------
$esteCampo = "periodo";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = -1;
$atributos["evento"] = 2;
$atributos["columnas"] = "1";
$atributos["limitar"] = false;
$atributos["tamanno"] = 1;
$atributos["ancho"] = "275px";
$atributos["estilo"] = "jqueryui";
$atributos["etiquetaObligatorio"] = true;
$atributos["validar"] = "required";
$atributos["anchoEtiqueta"] = 175;
//$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Periodo académico: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("buscarPeriodos",$variable);
$atributos["baseDatos"] = "evaldocentes";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//------------------Control Lista Desplegable------------------------------
$esteCampo = "tiporesultados";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = -1;
$atributos["evento"] = 2;
$atributos["columnas"] = "1";
$atributos["limitar"] = false;
$atributos["tamanno"] = 1;
$atributos["ancho"] = "275px";
$atributos["estilo"] = "jqueryui";
$atributos["etiquetaObligatorio"] = true;
$atributos["validar"] = "required";
$atributos["anchoEtiqueta"] = 175;
$atributos["obligatorio"] = true;
$atributos["etiqueta"] = "Tipo de resultado: ";
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("tipoResultados");
$atributos["baseDatos"] = "evaldocentes";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

$atributos["id"] = "botones";
$atributos["estilo"] = "marcoBotones";
echo $this->miFormulario->division("inicio", $atributos);

$esteCampo = "botonConsultar";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["tipo"] = "boton";
$atributos["estilo"] = "";
//$atributos["estilo"]="jqueryui";
$atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
//$atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
$atributos["tipoSubmit"] = "jquery";
$atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"] = $nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);

//-------------Fin Control Boton----------------------
//-------------Control Boton-----------------------
$esteCampo="botonCancelar";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["verificar"]="";
$atributos["tipo"]="boton";
$atributos["nombreFormulario"] = $nombreFormulario;
$atributos["cancelar"]=true;
$atributos["tipoSubmit"] = "jquery";
//$atributos["onclick"]=true;
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"] = "formSaraData"; //No cambiar este nombre
$atributos["tipo"] = "hidden";
$atributos["obligatorio"] = false;
$atributos["etiqueta"] = "";
$atributos["valor"] = $valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

echo $this->miFormulario->division("fin");
//Fin del Formulario
echo $this->miFormulario->formulario("fin");
echo $this->miFormulario->marcoAGrupacion("fin");
echo $this->miFormulario->division("fin");
