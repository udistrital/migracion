<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/habilitarProcesoEvaldocente/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

//$valorCodificado="pagina=armarFormularios";
$valorCodificado="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=procesarEditarSalMin";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&id_salmin=".$_REQUEST['id_salmin'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionPreguntas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Editar salario mínimo";
echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
unset($atributos);

    //-------------Control Mensaje-----------------------
$tipo = 'message';
$mensaje = "EL SIGUIENTE FORMLUARIO LE PERMITIRÁ ACTUALIZAR EL SALARIO MINIMO, PARA EL PAGO DE INSCRIPCIÓN DE ADMISIONES.<br>
            - Digite el valor del salrio mínimo.<br>
            - Digite el porcentaje (equivalente al valor de la inscripción).<br>
            - Seleccione el estado.<br>
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
        
    //-------------Control cuadroTexto-----------------------
    $esteCampo="valorsalmin";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Valor del salario mínimo";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 125;
    $atributos["tamanno"]="25";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["validar"]="required";
    $atributos["valor"]=$_REQUEST['valor'];
    $atributos["validar"]="required,custom[integer],min[0]";
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
    
    //-------------Control cuadroTexto-----------------------
    $esteCampo="porcentajesalmin";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Porcentaje eqivalente al valor de la inscripción";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 125;
    $atributos["tamanno"]="25";
    $atributos["tipo"]="text";
    $atributos["estilo"]="jqueryui";
    $atributos["validar"]="required";
    $atributos["valor"]=$_REQUEST['porcentaje'];
    $atributos["validar"]="required,custom[number],max[100],min[0]";
    $atributos["categoria"]="";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

    $esteCampo = "estadoNuevo";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = 1;
    $atributos["evento"] = 0;
    $atributos["columnas"] = "1";
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = "125px";
    $atributos["estilo"] = "jqueryui";
    $atributos["etiquetaObligatorio"] = true;
    $atributos["validar"] = "required";
    $atributos["anchoEtiqueta"] = 125;
    $atributos["obligatorio"] = true;
    $atributos["etiqueta"] = "Estado: ";
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = array(array('A', 'Activo'),array('I', 'Inactivo'));
    $atributos["baseDatos"] = "admisiones";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    $atributos["id"]="botones";
    $atributos["estilo"]="marcoBotones";
    echo $this->miFormulario->division("inicio",$atributos);   
    
    $esteCampo = "botonGuardar";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["tipo"] = "boton";
    $atributos["estilo"] = ""; 
   //$atributos["estilo"]="jqueryui";
    $atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
    //$atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
    $atributos["tipoSubmit"]="jquery";
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
     //$atributos["tipoSubmit"] = "jquery";
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
 



