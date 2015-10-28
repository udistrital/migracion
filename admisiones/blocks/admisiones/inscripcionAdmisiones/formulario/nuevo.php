<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/administrador/adminAdmisiones/";
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

$cadena_sql = $this->sql->cadena_sql("buscarPeriodo", "");
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cierto=0;
for($i=0; $i<=count($registro)-1; $i++)
{  
    if($registro[$i]['aca_estado']=="X")
    {
        $cierto=1;
        $variable['id_periodo']=$registro[$i]['aca_id'];
        $variable['anio']=$registro[$i]['aca_anio'];
        $variable['periodo']=$registro[$i]['aca_periodo'];
    }
}
if($cierto==1)
{
    $variable['seccion']="Página inicio";
    $variable['tipoInstructivo']="inicial";
    $cadena_sql = $this->sql->cadena_sql("buscarContenidoInstructivo", $variable);
    $registroContenido = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $cadena_sql = $this->sql->cadena_sql("consultarEventos", $variable);
    @$registroEventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
    
    $valorCodificado="pagina=admisiones";
    $valorCodificado.="&opcion=mensajeAceptacion";
    $valorCodificado.="&mensaje=aceptacion";
    $valorCodificado.="&usuario=".$_REQUEST['usuario'];
    $valorCodificado.="&tipo=".$_REQUEST['tipo'];
    $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
    $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
    $valorCodificado.="&periodo=".$variable['periodo'];
    $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
    $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
    
    //------------------Division para las pestañas-------------------------
    $atributos["id"] = "tabs";
    $atributos["estilo"] = "";
    echo $this->miFormulario->division("inicio", $atributos);
    unset($atributos);

    $atributos["id"] = "marcoAgrupacionFechas";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = " ";
    echo $this->miFormulario->marcoAgrupacion("inicio", $atributos);
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
    
    
    //-------------Control Mensaje-----------------------
    if($variable['periodo']==1)
    {
        $periodo="PRIMER";
    }
    elseif($variable['periodo']==3)
    {
        $periodo="SEGUNDO";
    } 
    else
    {
        $periodo=" ";
    }
    
    
    
    $tipo = 'message';
    $mensaje = "<h1>PROCESO DE ADMISIONES ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</h1><br>";
    /*$mensaje.="<table border='1' width=100%>";
    $mensaje.="<CAPTION style='font-size:10pt;'><b>FECHAS DE INSCRIPCIÓN Y PUBLICACIÓN DE RESULTADOS</b></CAPTION>";
    $mensaje.="<thead>";
    $mensaje.=" <tr>";
    $mensaje.="     <th style='font-size:10pt;'>No. Evenvo</th>";
    $mensaje.="     <th style='font-size:10pt;'>Evento </th>";
    $mensaje.="     <th style='font-size:10pt;'>Fecha Inicial</th>";
    $mensaje.="     <th style='font-size:10pt;'>Fecha Final</th>";
    $mensaje.=" </tr>";
    $mensaje.="</thead>";
    $mensaje.="<tbody>";
    for($i=0; $i<=count($registroEventos)-1; $i++)
    {
        $mensaje.=" <tr>";
            $mensaje.=" <td align='center' style='font-size:10pt;'>";
                $mensaje.=$registroEventos[$i][1];
            $mensaje.=" </td>";
            $mensaje.=" <td style='font-size:10pt;'>";
                $mensaje.=$registroEventos[$i][2];
            $mensaje.=" </td>";
            $mensaje.=" <td align='center' style='font-size:10pt;'>";
                $mensaje.=$registroEventos[$i][3];
            $mensaje.=" </td>";
            $mensaje.=" <td align='center' style='font-size:10pt;'>";
                $mensaje.=$registroEventos[$i][4];
            $mensaje.=" </td>";
        $mensaje.=" </tr>";
    }
    $mensaje.="</tbody>";
    $mensaje.="</table>";*/
    //$valorCodificado="pagina=habilitarEvaluacion";
    
    $mensaje.=$registroContenido[0][2];
    
    $esteCampo = "mensaje";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = $tipo;
    $atributos["mensaje"] = $mensaje;
    echo $this->miFormulario->cuadroMensaje($atributos);
    unset($atributos);
    
    //---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario("inicio", $atributos);
    unset($atributos);

        
    //------------------Division para los botones-------------------------
    $atributos["id"]="botones";
    $atributos["estilo"]="marcoBotones";
    echo $this->miFormulario->division("inicio",$atributos);
   
//-------------Control Boton-----------------------
    $esteCampo = "botonContinuar";
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
    /*$esteCampo="botonCancelar";
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
    unset($atributos);*/
    //-------------Fin Control Boton----------------------

    //-------------Control cuadroTexto con campos ocultos-----------------------
    //Para pasar variables entre formularios o enviar datos para validar sesiones
        $atributos["id"] = "formSaraData"; //No cambiar este nombre
        $atributos["tipo"] = "hidden";
        $atributos["obligatorio"] = false;
        $atributos["etiqueta"] = "";
        $atributos["valor"] = $valorCodificado;
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

    //Fin del Formulario
    echo $this->miFormulario->formulario("fin");

    //-------------Fin de Conjunto de Controles----------------------------
    echo $this->miFormulario->marcoAgrupacion("fin");

    //------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");
    
}




