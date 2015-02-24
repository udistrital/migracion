<?
if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}else
{

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
$miSesion = Sesion::singleton();

$nombreFormulario=$esteBloque["nombre"];

include_once("core/crypto/Encriptador.class.php");
$cripto=Encriptador::singleton();
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");

$tab=1;
//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);

	$atributos["id"]="divErrores";
	$atributos["estilo"]="marcoBotones";
        //$atributos["estiloEnLinea"]="display:none"; 
	echo $this->miFormulario->division("inicio",$atributos);
	
	if($_REQUEST['mensaje'] == 'informacion')
	{
            $tipo = 'information';
            $mensaje = "Ya existe un formulario con el número de formato que está intentando registrar!";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=formatos"; 
            //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso'];
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	}
        if($_REQUEST['mensaje'] == 'preguntas')
        {
            $tipo = 'information';
            $mensaje = "Ya existe una pregunta como la que está intentando registrar!";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=preguntas"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'campoVacioPreguntas')
        {
            $tipo = 'information';
            $mensaje = "Registre una pregunta, el campo no debe estar vacío!";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=preguntas"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'encabezados')
        {
            $tipo = 'information';
            $mensaje = "Ya existe una encabezado como el que está intentando registrar!";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=encabezados"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'campoVacioEncabezados')
        {
            $tipo = 'information';
            $mensaje = "Registre un encabezado, el campo no debe estar vacío!";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=encabezados"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'formulario')
        {
            $tipo = 'information';
            $mensaje = "La pregunta que está intentando guardar, ya está asociada al formulario!";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=armarFormulario"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'valorPreguntas')
        {
            $tipo = 'information';
            $mensaje = "El valor de la pregunta no puede ser menor a 0 (cero).";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=preguntas"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'tipoValorPreguntas')
        {
            $tipo = 'information';
            $mensaje = "El valor de la pregunta no es correcto.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=preguntas"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'valorPreguntasRadio')
        {
            $tipo = 'information';
            $mensaje = "Para preguntas tipo radio, el valor de la pregunta  no puede ser menor o igual a 0 (cero).";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=preguntas"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'camposVaciosFormulario')
        {
            $tipo = 'information';
            $mensaje = "Debe seleccionar un encabezado o una pregunta!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=armarFormulario"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'asociacion')
        {
            $tipo = 'information';
            $mensaje = "Ya existe un formato asociado al tipo de vinculación Docente que registrar!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=asociarFormatos"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'seleccionar')
        {
            $tipo = 'information';
            $mensaje = "Debe seleccionar una opción!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=asociarFormatos"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'porcentaje')
        {
            $tipo = 'information';
            $mensaje = "El porcentaje debe ser mayor o igual a 0 y menor o igual a 100!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=formatos"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'formatoCampo')
        {
            $tipo = 'information';
            $mensaje = "Los campos Formato No. y Porcentaje, aceptan solamente números!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=formatos"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
	$esteCampo = "botonContinuar";
        $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos); 
        
        //------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
        
        //------------------Division para los botones-------------------------
	$atributos["id"]="botones";
	$atributos["estilo"]="marcoBotones";
	echo $this->miFormulario->division("inicio",$atributos);
	
	//-------------Control Boton-----------------------
	$esteCampo ="botonContinuar" ;
	$atributos["id"]=$esteCampo;
	$atributos["tabIndex"]=$tab++;
	$atributos["tipo"]="boton";
	$atributos["estilo"]="jquery";
	$atributos["verificar"]="true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
	$atributos["tipoSubmit"]="jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
	$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
	$atributos["nombreFormulario"]=$nombreFormulario;
	echo $this->miFormulario->campoBoton($atributos);
	unset($atributos);
	//-------------Fin Control Boton----------------------
	
	
	//------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division("fin");
    
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