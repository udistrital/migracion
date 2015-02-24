<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
} else {

    $this->sql = new SqlEnvioCorreo();
    $conexion = "estructura";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    
    if ($noEnviados!='') {
        for ($i = 1; $i < count($noEnviados); $i++) {

            echo "<br> No se envió información a " . $noEnviados[$i]['identificacion'] . "-" . $noEnviados[$i]['nombre'] . " al correo " . $noEnviados[$i]['correo'] . ".";
            if ($i % 50 == 0 && $i!=0)
                echo "<br>---------------<br>";
        }
    }

    if ($enviados!='') {
        for ($i = 0; $i < count($enviados); $i++) {

            $datos['id'] = $enviados[$i]['identificacion'];
            $datos['estado'] = 1;

            $cadenaSql = trim($this->sql->cadena_sql("actualizarEnvio", $datos));
            $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "");

            echo "<br> Se envió información a " . $enviados[$i]['identificacion'] . "-" . $enviados[$i]['nombre'] . " al correo " . $enviados[$i]['correo'] . ".";
            if ($i % 50 == 0 && $i!=0)
                echo "<br>---------------<br>";
        }
    }
}

//$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
//$nombreFormulario = $esteBloque["nombre"];
//
//$valorCodificado = "pagina=confirmarDatos";
//$valorCodificado.="&action=" . $esteBloque["nombre"];
//$valorCodificado.="&opcion=validarDatos";
//$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
//$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
//$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
//
////-------------------------------Mensaje-------------------------------------
//$esteCampo = "msjActualizarDatos";
//$atributos["id"] = $esteCampo;
//$atributos["obligatorio"] = false;
//$atributos["estilo"] = "jqueryui";
//$atributos["etiqueta"] = "simple";
//$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
//echo $this->miFormulario->campoMensaje($atributos);
//
//$tab = 1;
//
////---------------Inicio Formulario (<form>)--------------------------------
//$atributos["id"] = $nombreFormulario;
//$atributos["tipoFormulario"] = "multipart/form-data";
//$atributos["metodo"] = "POST";
//$atributos["nombreFormulario"] = $nombreFormulario;
//$verificarFormulario = "1";
//echo $this->miFormulario->formulario("inicio", $atributos);
//
//
////-------------Control texto-----------------------
//$esteCampo = "informacionAceptado";
//$atributos["tamanno"] = "";
//$atributos["estilo"] = "jqueryui";
//$atributos["etiqueta"] = "";
//$atributos["texto"] = $this->lenguaje->getCadena($esteCampo);
//$atributos["columnas"] = ""; //El control ocupa 47% del tamaño del formulario
//echo $this->miFormulario->campoTexto($atributos);
//unset($atributos);
//
//
////-----------------Inicio de Conjunto de Controles----------------------------------------
//$esteCampo = "marcoDatosBasicos";
//$atributos["estilo"] = "jqueryui";
////$atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
//echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);
//
////-------------Control cuadroTexto-------------------------------------
//$esteCampo = "nombreU";
//$atributos["id"] = $esteCampo;
//$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
//$atributos["etiquetaObligatorio"] = false;
//$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
//$atributos["tabIndex"] = $tab++;
//$atributos["obligatorio"] = false;
//$atributos["tamanno"] = "50";
//$atributos["tipo"] = "";
//$atributos["estilo"] = "jqueryui";
//$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
//$atributos["validar"] = "OnlyLetterSp, minSize[10], maxSize[50]";
//$atributos["categoria"] = "";
//$atributos["deshabilitado"] = false;
//$atributos["valor"] = '';
//echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);
//
////-------------Control cuadroTexto-------------------------------------
//$esteCampo = "identificacionU";
//$atributos["id"] = $esteCampo;
//$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
//$atributos["etiquetaObligatorio"] = false;
//$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
//$atributos["tabIndex"] = $tab++;
//$atributos["obligatorio"] = false;
//$atributos["tamanno"] = "50";
//$atributos["tipo"] = "";
//$atributos["estilo"] = "jqueryui";
//$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
//$atributos["validar"] = "min[6], max[10], custom[integer], number, maxSize[1]";
//$atributos["categoria"] = "";
//$atributos["deshabilitado"] = false;
//$atributos["valor"] = '';
//echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);
//
////-------------Control cuadroTexto-------------------------------------
//$esteCampo = "correoU";
//$atributos["id"] = $esteCampo;
//$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
//$atributos["etiquetaObligatorio"] = false;
//$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
//$atributos["tabIndex"] = $tab++;
//$atributos["obligatorio"] = false;
//$atributos["tamanno"] = "50";
//$atributos["tipo"] = "";
//$atributos["estilo"] = "jqueryui";
//$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
//$atributos["validar"] = "custom[email], maxSize[40]";
//$atributos["categoria"] = "";
//$atributos["deshabilitado"] = false;
//$atributos["valor"] = '';
//echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);
//
//
////-------------Fin de Conjunto de Controles----------------------------
//echo $this->miFormulario->marcoAGrupacion("fin");
//
////------------------Division para los botones-------------------------
//$atributos["id"] = "botones";
//$atributos["estilo"] = "marcoBotones";
//echo $this->miFormulario->division("inicio", $atributos);
//
////-------------Control Boton-----------------------
//$esteCampo = "botonConfirmar";
//$atributos["id"] = $esteCampo;
//$atributos["tabIndex"] = $tab++;
//$atributos["tipo"] = "boton";
//$atributos["estilo"] = "";
//$atributos["verificar"] = "true"; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
//$atributos["tipoSubmit"] = "jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
//$atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
//$atributos["nombreFormulario"] = $nombreFormulario;
//echo $this->miFormulario->campoBoton($atributos);
//unset($atributos);
////-------------Fin Control Boton----------------------
//
////------------------Fin Division para los botones-------------------------
//echo $this->miFormulario->division("fin");
//
////-------------Control cuadroTexto con campos ocultos-----------------------
////Para pasar variables entre formularios o enviar datos para validar sesiones
//$atributos["id"] = "formSaraData"; //No cambiar este nombre
//$atributos["tipo"] = "hidden";
//$atributos["obligatorio"] = false;
//$atributos["etiqueta"] = "";
//$atributos["valor"] = $valorCodificado;
//echo $this->miFormulario->campoCuadroTexto($atributos);
//unset($atributos);
//
//
////Fin del Formulario
//echo $this->miFormulario->formulario("fin");
//
////------------------Fin Division para las pestañas-------------------------
//echo $this->miFormulario->division("fin");
?>