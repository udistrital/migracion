<?php
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

$miPaginactual=$this->miConfigurador->getVariableConfiguracion("pagina");

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
	
	if($_REQUEST['mensaje'] == 'error')
	{
            $tipo = 'error';
            $mensaje = "Ya existe un registro con el periodo académico que intenta guardar.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=nuevo"; 
            $valorCodificado.="&tipo=".$_REQUEST["tipo"];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	}
        if($_REQUEST['mensaje'] == 'registroExiste')
	{
            unset($variable);
            $tipo = 'error';
            $mensaje = "Ya existe un registro con los datos que está intentando guardar.";
            $boton = "regresar";
            $valorCodificado="&opcion=".$_REQUEST['opcionPagina']; 
            $valorCodificado.="&tipo=".$_REQUEST["tipo"];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'perExiste')
	{
            $tipo = 'error';
            $mensaje = "Solamente debe haber un perido en estado ".$_REQUEST['estadoNuevo'].".";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=nuevo"; 
            //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso'];
            $valorCodificado.="&tipo=".$_REQUEST["tipo"];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	}
        
        if($_REQUEST['mensaje'] == 'errorFecha')
	{
            $tipo = 'error';
            $mensaje = "La fecha inicial no puede ser mayor a la fecha final.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=eventos"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	}
        if($_REQUEST['mensaje'] == 'errorMedio')
	{
            $tipo = 'error';
            $mensaje = "Ya existe un registro con el medio que intenta guardar.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=medios"; 
            //$valorCodificado.="&nombreProceso=".$_REQUEST['proceso'];
            $valorCodificado.="&tipo=".$_REQUEST["tipo"];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
	}
	
        if($_REQUEST['mensaje'] == 'porcentaje')
        {
            $tipo = 'information';
            $mensaje = "El porcentaje debe ser mayor o igual a 0 y menor o igual a 100!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=salmin"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'formatoCampo')
        {
            $tipo = 'information';
            $mensaje = "No es un valor entero válido!!.";
            $boton = "regresar";
                     
            $valorCodificado="&opcion=".$_REQUEST['opcionPagina']; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'salarioMin')
        {
            $tipo = 'information';
            $mensaje = "Ya existe un registro de salario mínimo para el año que intenta guardar!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=salmin"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'localidades')
        {
            $tipo = 'information';
            $mensaje = "Ya existe un registro con los datos que intenta guardar!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=localidades"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'estado')
        {
            $tipo = 'information';
            $mensaje = "El estado debe ser A o I!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=salmin"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'estratos')
        {
            $tipo = 'information';
            $mensaje = "Ya existe un registro con los datos que intenta guardar!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=estratos"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        
        if($_REQUEST['mensaje'] == 'colillas')
        {
            $tipo = 'information';
            $mensaje = "Ya existe un registro con el nombre de la colilla que está intentando guardar!!.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=colillas"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'archivoPines')
        {
            $tipo = 'information';
            $mensaje = "Archivo no válido...";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=cargarAdmitidos"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'archivoAdmitidos')
        {
            $tipo = 'information';
            $mensaje = "Archivo no válido...";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=registrarPines"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'archivoPinesRepetido')
        {
            $tipo = 'information';
            $mensaje = "Por favor revise que el archivo que está intentando subir no lo haya cargado ya...";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=registrarPines"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'tipoInscripcion')
        {
            $tipo = 'information';
            $mensaje = "Ya existe un tipo de inscripción con el código o el nombre que intenta registrar.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=registarTipInscripcion"; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'campoVacio')
        {
            $tipo = 'information';
            $mensaje = "Los campos con * son obligatorios, no deben ser vacíos.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=".$_REQUEST['opcionPagina']; 
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
            $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
            $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
            $valorCodificado=$cripto->codificar($valorCodificado);
        }
        if($_REQUEST['mensaje'] == 'sinRegistro')
        {
            $tipo = 'information';
            $mensaje = "No existen registros con los datos consultados.";
            $boton = "regresar";
                        
            $valorCodificado="&opcion=".$_REQUEST['opcionPagina']; 
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