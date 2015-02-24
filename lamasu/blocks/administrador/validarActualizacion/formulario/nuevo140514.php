<?php
$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host") . "/appserv/estudiantes/";
//$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/appserv/estudiantes/";
$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");


if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$variable['fecha']=date("Y-m-d");

$variable['usuario_id']=$_REQUEST['usuario'];
$variable['tipo']=$_REQUEST['tipo'];

$cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$cuenta=count($registro);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionFechas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "";
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
    
    $tiempoCambioClave=$registro[0]['apl_tiempo_cambio_clave'];
    $fechaActual=strtotime($variable['fecha']);
    $fechaUltimaActualizacion=strtotime($registro[0]['cta_fecha_actualizacion']);
    
    //86400 es la diferencia en segundos despues de convertir las fechas a strttime y realizar la resta.
    $diferencia=($fechaActual-$fechaUltimaActualizacion)/86400;
    
    $tiempoCambioClave=$tiempoCambioClave-$diferencia;
    
    //10 es el número de días antes del vencimiento de la contraseña.
    if($tiempoCambioClave>=2 && $tiempoCambioClave<=10)
    {   
        $tipo = 'information';
        $mensaje = "Señor usuario, le recordamos que su contraseña caducará en ".$tiempoCambioClave." días, se recomienda actualizarla antes de su caducidad. <br>";
        $esteCampo = "mensaje";
        $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
        $atributos["etiqueta"] = "";
        $atributos["estilo"] = "centrar";
        $atributos["tipo"] = $tipo;
        $atributos["mensaje"] = $mensaje;
        echo $this->miFormulario->cuadroMensaje($atributos);
        unset($atributos);
    }    
    elseif($tiempoCambioClave==0 || $tiempoCambioClave==1)
    {
        $mensaje="Por favor actualice su contraseña.....";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        $valor[0]=$variable['usuario_id'];
        $valor[1]=$_REQUEST['tipo'];
        $this->funcion->redireccionar ("iraCambioClave",$valor);
    }
    elseif($tiempoCambioClave<0)
    {
        $mensaje="Su contraseña ha caducado, por favor actualicela...";
        $html="<script>alert('".$mensaje."');</script>";
        echo $html;
        $valor[0]=$variable['usuario_id'];
        $valor[1]=$_REQUEST['tipo'];
        $this->funcion->redireccionar ("iraCambioClave",$valor);
    }    
    else
    {
        //Cuando no redireccione al formulario de cambio de contraseña o aviso de vencimiento, redirecciona a la página principal de cada prerfil.
        $opcion=$_REQUEST['tipo'];
        $ruta=$registro[0]['apl_url'];
        $ruta.=$this->miConfigurador->getVariableConfiguracion("siteAppserv");
        switch ($opcion) {
        case "51":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
            echo "Redireccionando....";
            $indice = $ruta."/estudiantes/est_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        
        case "52":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
            echo "Redireccionando....";
            $indice=$ruta."/estudianteCreditos/est_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "24":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
            echo "Redireccionando....";
            $indice=$ruta."/funcionario/fun_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "30":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/docentes/doc_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "4":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/coordinador/coor_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "28":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/coordinadorcred/coorcred_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "16":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/decano/dec_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "34":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/asesor/assr_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "61":
            $tipo = 'message';
            $mensaje = "Bienvenido al Sistema de Información Académica, usted ingresó con el perfil de Asesor de Vicerrectoría.";
            $esteCampo = "mensaje";
            $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            $atributos["etiqueta"] = "";
            $atributos["estilo"] = "centrar";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = $mensaje;
            echo $this->miFormulario->cuadroMensaje($atributos);
            unset($atributos);
            break;
        case "109":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/asistenteContabilidad/aco_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "110":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/asistenteProyecto/asis_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "111":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/asistenteProyecto/asis_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "119":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/asistenteDecanatura/asis_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        case "121":
            require_once("encriptar.class.php");
            $cripto=new encriptar();
             echo "Redireccionando....";
            $indice=$ruta."/egresado/egr_pag_principal.php?";
            echo "<script>location.replace('".$indice."')</script>";      
            break;
        }
    }    
        
    echo $this->miFormulario->formulario("fin");
    echo $this->miFormulario->marcoAGrupacion("fin");
    echo $this->miFormulario->division("fin");
    




