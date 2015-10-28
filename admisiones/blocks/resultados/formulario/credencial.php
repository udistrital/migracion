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
$variable['id_evento']=6;

$cadena_sql = $this->sql->cadena_sql("consultarEventos", $variable);
$registroeventos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

if($registroeventos[0]['des_id']>0)
{
    if($cierto==1)
    {
        $variable['carreras']=9003;
        $cadena_sql = $this->sql->cadena_sql("buscarContenidoColilla", $variable);
        $registroContenido = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

        $valorCodificado="pagina=administracion";
        $valorCodificado.="&action=".$esteBloque["nombre"];
        $valorCodificado.="&opcion=guardar";
        if(isset($_REQUEST['usuario']))
        {    
            $valorCodificado.="&usuario=".$_REQUEST['usuario'];
        }
        if(isset($_REQUEST['tipo']))
        {    
            $valorCodificado.="&tipo=".$_REQUEST['tipo'];
        }
        if(isset($_REQUEST['rba_id']))
        {    
            $valorCodificado.="&rba_id=".$_REQUEST['rba_id'];
        }

        $valorCodificado="pagina=resultados";
        $valorCodificado.="&action=".$esteBloque["nombre"];
        $valorCodificado.="&opcion=consultarResultados";
        $valorCodificado.="&usuario=".$_REQUEST['usuario'];
        $valorCodificado.="&tipo=".$_REQUEST['tipo'];
        $valorCodificado.="&bloque=".$esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
        $valorCodificado.="&id_periodo=".$variable['id_periodo'];
        $valorCodificado.="&anio=".$variable['anio'];
        $valorCodificado.="&periodo=".$variable['periodo'];
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
        $mensaje = "<center><h3>CONSULTA DE RESULTADOS DEL PROCESO DE ADMISiONES PARA EL  ".$periodo." PERIODO ACADÉMICO ".$variable['anio']."</h3></center>";
        $mensaje.="<br>".$registroContenido[0]['colilla_contenido'];
        
        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);

        //------------------Division para las pestañas-------------------------
        $atributos["id"] = "tabs";
        $atributos["estilo"] = "";
        echo $this->miFormulario->division("inicio", $atributos);
        unset($atributos);

        $atributos["id"] = "marcoAgrupacionFechas";
        $atributos["estilo"] = "jqueryui";
        $atributos["leyenda"] = "Consulta de resultados por No. credencial";
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

        //-------------Control cuadroTexto-----------------------
        $esteCampo="credencial";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Porcentaje";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["anchoEtiqueta"] = 125;
        $atributos["tamanno"]="25";
        $atributos["maximoTamanno"]="125";
        $atributos["tipo"]="text";
        $atributos["estilo"]="jqueryui";
        $atributos["obligatorio"] = true;
        $atributos["validar"]="required,custom[number]";
        $atributos["categoria"]="";
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);

        $atributos["id"]="botones";
        $atributos["estilo"]="marcoBotones";
        echo $this->miFormulario->division("inicio",$atributos);   

        $esteCampo = "botonConsultar";
        $atributos["id"] = $esteCampo;
        $atributos["tabIndex"] = $tab++;
        $atributos["tipo"] = "boton";
        $atributos["estilo"] = ""; 
        $atributos["estilo"]="jqueryui";
        $atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
        //$atributos["tipoSubmit"] = ""; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
        $atributos["tipoSubmit"]="jquery";
        $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
        $atributos["nombreFormulario"] = $nombreFormulario;
        echo $this->miFormulario->campoBoton($atributos);
        unset($atributos);

        //-------------Fin Control Boton----------------------

        echo $this->miFormulario->division("fin");  

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

        echo $this->miFormulario->marcoAGrupacion("fin");
        echo $this->miFormulario->division("fin");

    }
}
else
{
    $atributos["id"]="divNoEncontroRegistro";
    $atributos["estilo"]="marcoBotones";
    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio",$atributos);

    //-------------Control Boton-----------------------
    $esteCampo = "eventoCerrado";
    $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = 'error';
    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
    echo $this->miFormulario->cuadroMensaje($atributos);
     unset($atributos); 
    //-------------Fin Control Boton----------------------

    //------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");
}    



