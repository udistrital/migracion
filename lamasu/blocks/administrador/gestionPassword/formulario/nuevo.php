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

if(isset($_REQUEST['recuperaPassword']))
{ 
    $fecha=date("m/d/Y");
    $fechaHoy=strtotime($fecha);
    $resultado=$fechaHoy-$_REQUEST['fechaHoy'];
    
    if($resultado!=0)
    {
        ?>
        <script language='javascript'>
        alert('El link al cuál está intentando acceder, está vencido, para recuperar su contraseña debe ingresar a "Ayuda","Recuperación de clave", realizar nuevamente todo el procedimiento.');
        </script>
        <?
        $ruta=$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
        $ruta.="/appserv/index.php";
        echo "<script>location.replace('".$ruta."')</script>"; 
    }
}    

$conexion = "laverna";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    echo "Este se considera un error fatal";
    exit;
}

$valorCodificado="pagina=gestionPassword";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=guardar";
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&tipo=".$_REQUEST['tipo'];
$valorCodificado.="&usuario_id=".$_REQUEST['usuario'];
if(isset($_REQUEST['recuperaPassword']))
{    
    $valorCodificado.="&nombreUsuario=".$_REQUEST['nombreUsuario'];
    $valorCodificado.="&recuperaPassword=".$_REQUEST['recuperaPassword'];
    
}    
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

$atributos["id"] = "marcoAgrupacionFechas";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = "Formulario para actualización de contraseña";
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
    $esteCampo = "informacion";
    $atributos["id"] = "mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "";
    $atributos["tipo"] = "message";
    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->cuadroMensaje($atributos);
    
    $variable['tipo']=$_REQUEST['tipo'];
    //$variable['tipo']=20;
    
    if(isset($_REQUEST['recuperaPassword']))
    {    
        echo "Nombre: ".$_REQUEST['nombre']."<p/>";
        echo "Usuario: ".$_REQUEST['nombreUsuario']."<p/>";
    }
    //Si el usuario es de soporte o administrador, y puede hcer cambio de claves, activar este código.
    /*elseif($variable['tipo']==20 ||$variable['tipo']==80)
    {
        $esteCampo="nombreUsuario";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Digite el usuario";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["anchoEtiqueta"] = 195;
        $atributos["tamanno"]="10";
        $atributos["tipo"]="text";
        $atributos["estilo"]="jqueryui";
        $atributos["validar"]="required";
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
    }*/ 
    else
    {    
        $esteCampo="actualClave";
        $atributos["id"]=$esteCampo;
        $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
        $atributos["titulo"]="Digite la contraseña actual";
        $atributos["tabIndex"]=$tab++;
        $atributos["obligatorio"]=true;
        $atributos["etiquetaObligatorio"] = true;
        $atributos["anchoEtiqueta"] = 195;
        $atributos["tamanno"]="10";
        $atributos["tipo"]="password";
        $atributos["estilo"]="jqueryui";
        $atributos["validar"]="required";
        echo $this->miFormulario->campoCuadroTexto($atributos);
        unset($atributos);
    }
    
    $esteCampo="nuevaClave";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Digite la nueva contraseña";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 195;
    $atributos["tamanno"]="10";
    $atributos["tipo"]="password";
    $atributos["estilo"]="jqueryui";
    $atributos["validar"]="required,minSize[8],maxSize[16],custom[minNumberChars],custom[minLowerAlphaChars],custom[noFirstNumber]";//
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);
            
    $esteCampo="confirmarNuevaClave";
    $atributos["id"]=$esteCampo;
    $atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"]="Confirme la nueva contraseña";
    $atributos["tabIndex"]=$tab++;
    $atributos["obligatorio"]=true;
    $atributos["etiquetaObligatorio"] = true;
    $atributos["anchoEtiqueta"] = 195;
    $atributos["tamanno"]="10";
    $atributos["tipo"]="password";
    $atributos["estilo"]="jqueryui";
    $atributos["validar"]="required,passwordEquals[nuevaClave]";
    echo $this->miFormulario->campoCuadroTexto($atributos);
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
    



//echo $this->miFormulario->division("fin");




