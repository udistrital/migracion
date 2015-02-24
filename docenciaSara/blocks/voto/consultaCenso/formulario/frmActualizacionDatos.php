<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$datos = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['datos']);
$datos = unserialize(urldecode($datos));
$datos = $datos[0];

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$valorCodificado = "pagina=index";
$valorCodificado.="&action=" . $esteBloque["nombre"];
$valorCodificado.="&opcion=actualizarDatos";
$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado.="&idRegistro=" . $datos['censo_id_registro'];
$valorCodificado.="&votacionMensaje=" . $datos['votacion_mensaje'];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"] = "tabs";
$atributos["estilo"] = "";
echo $this->miFormulario->division("inicio", $atributos);
unset($atributos);

//-------------------------------Mensaje-------------------------------------
$esteCampo = "mensaje2";
$atributos["id"] = $esteCampo;
$atributos["obligatorio"] = false;
$atributos["estilo"] = "jqueryui";
$atributos["etiqueta"] = "simple";
$atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoMensaje($atributos);

$tab = 1;

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"] = $nombreFormulario;
$atributos["tipoFormulario"] = "multipart/form-data";
$atributos["metodo"] = "POST";
$atributos["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario("inicio", $atributos);


//-------------Control texto-----------------------
$esteCampo = "informacionAceptado";
$atributos["tamanno"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["etiqueta"] = "";
$atributos["texto"] = $this->lenguaje->getCadena($esteCampo);
$atributos["columnas"] = ""; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);


//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo = "marcoDatosBasicos";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "nombre";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "30";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required OnlyLetterSp";
$atributos["categoria"] = "";
$atributos["deshabilitado"] = true;
$atributos["valor"] = $datos['censo_nombre'];
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "tipoDocAnterior";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "8";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required";
$atributos["categoria"] = "";
$atributos["deshabilitado"] = true;
$atributos["valor"] = $datos['censo_tipo_doc_graduado'];
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "numeroDocAnterior";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "10";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required, min[4],max[10], custom[integer], number";
$atributos["categoria"] = "";
$atributos["deshabilitado"] = true;
$atributos["valor"] = $datos['censo_num_doc_graduado'];
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Fin de Conjunto de Controles----------------------------
echo $this->miFormulario->marcoAGrupacion("fin");



//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo = "marcoDatosContacto";
$atributos["estilo"] = "jqueryui";
$atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);

//-------------Control Lista Desplegable-----------------------

$esteCampo = "tipoDocNuevo";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = 1;
$atributos["evento"] = 2;
$atributos["limitar"] = false;
$atributos["tamanno"] = 1;
$atributos["columnas"] = "2";
//usseless: El estilo en las listas desplegables se maneja registrando el widget menu en jquery
$atributos["estilo"] = "jqueryui";
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("buscarTipoDocumento");
$atributos["baseDatos"] = "votocenso";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "numeroDocNuevo";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "8";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required, min[4],max[10], custom[integer], number";
$atributos["categoria"] = "";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "correoPrincipal";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "40";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required, custom[email]";
$atributos["categoria"] = "";
$atributos["valor"] = $datos['censo_correo'];
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "correoInstitucional";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "40";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required, custom[email]";
$atributos["categoria"] = "";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "telefonoFijo";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "7";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required, min[7],max[7], custom[integer], number";
$atributos["valor"] = $datos['censo_tel_fijo'];
$atributos["categoria"] = "";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "telefonoCelular";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "10";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required, min[10],max[10], custom[integer], number";
$atributos["categoria"] = "";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-------------------------------------
$esteCampo = "direccion";
$atributos["id"] = $esteCampo;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
$atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
$atributos["tabIndex"] = $tab++;
$atributos["obligatorio"] = true;
$atributos["tamanno"] = "20";
$atributos["tipo"] = "";
$atributos["estilo"] = "jqueryui";
$atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"] = "required, min[4],max[20]";
$atributos["categoria"] = "";
$atributos["valor"] = $datos['censo_direccion'];
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Fin de Conjunto de Controles----------------------------
echo $this->miFormulario->marcoAGrupacion("fin");


if (strtoupper($datos['votacion_nombre']) == "EGRESADOS" || strtoupper($datos['votacion_nombre']) == "ESTUDIANTES") {
    //-----------------Inicio de Conjunto de Controles----------------------------------------
    $esteCampo = "marcoEgresados";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);

//-------------Control cuadroTexto-------------------------------------
    $esteCampo = "codigo";
    $atributos["id"] = $esteCampo;
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
    $atributos["tabIndex"] = $tab++;
    $atributos["obligatorio"] = true;
    $atributos["tamanno"] = "11";
    $atributos["tipo"] = "";
    $atributos["estilo"] = "jqueryui";
    $atributos["columnas"] = "1"; //El control ocupa 32% del tamaño del formulario
    $atributos["validar"] = "required, min[4],max[11], custom[integer], number";
    $atributos["categoria"] = "";
    $atributos["deshabilitado"] = true;
    $atributos["valor"] = $datos['censo_cod_estudiante'];
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

//-------------Control cuadroTexto-------------------------------------
    $esteCampo = "facultad";
    $atributos["id"] = $esteCampo;
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
    $atributos["tabIndex"] = $tab++;
    $atributos["obligatorio"] = true;
    $atributos["tamanno"] = "20";
    $atributos["tipo"] = "";
    $atributos["estilo"] = "jqueryui";
    $atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
    $atributos["validar"] = "required";
    $atributos["categoria"] = "";
    $atributos["deshabilitado"] = true;
    $atributos["valor"] = $datos['censo_facultad_graduado'];
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

//-------------Control cuadroTexto-------------------------------------
    $esteCampo = "carrera";
    $atributos["id"] = $esteCampo;
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
    $atributos["tabIndex"] = $tab++;
    $atributos["obligatorio"] = true;
    $atributos["tamanno"] = "20";
    $atributos["tipo"] = "";
    $atributos["estilo"] = "jqueryui";
    $atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
    $atributos["validar"] = "required";
    $atributos["categoria"] = "";
    $atributos["deshabilitado"] = true;
    $atributos["valor"] = $datos['censo_carrera_graduado'];
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

//-------------Control cuadroTexto-------------------------------------
    $esteCampo = "anoGraduado";
    $atributos["id"] = $esteCampo;
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
    $atributos["tabIndex"] = $tab++;
    $atributos["obligatorio"] = true;
    $atributos["tamanno"] = "4";
    $atributos["tipo"] = "";
    $atributos["estilo"] = "jqueryui";
    $atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
    $atributos["validar"] = "required, min[4],max[4], custom[integer], number";
    $atributos["categoria"] = "";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

//-------------Control cuadroTexto-------------------------------------
    $esteCampo = "periodoGraduado";
    $atributos["id"] = $esteCampo;
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    $atributos["titulo"] = $this->lenguaje->getCadena($esteCampo . "Titulo");
    $atributos["tabIndex"] = $tab++;
    $atributos["obligatorio"] = true;
    $atributos["tamanno"] = "1";
    $atributos["tipo"] = "";
    $atributos["estilo"] = "jqueryui";
    $atributos["columnas"] = "2"; //El control ocupa 32% del tamaño del formulario
    $atributos["validar"] = "required, min[1],max[1], custom[integer], number";
    $atributos["categoria"] = "";
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

//-------------Fin de Conjunto de Controles----------------------------
    echo $this->miFormulario->marcoAGrupacion("fin");
//----------------------Fin Conjunto de Controles--------------------------------------
}

//------------------Division para los botones-------------------------
$atributos["id"] = "botones";
$atributos["estilo"] = "marcoBotones";
echo $this->miFormulario->division("inicio", $atributos);

//-------------Control Boton-----------------------
$esteCampo = "botonGuardar";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["tipo"] = "boton";
$atributos["estilo"] = "";
$atributos["verificar"] = "";
$atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"] = $nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------
//-------------Control Boton-----------------------
$esteCampo = "botonCancelar";
$atributos["verificar"] = "";
$atributos["tipo"] = "boton";
$atributos["id"] = $esteCampo;
$atributos["cancelar"] = "true";
$atributos["tabIndex"] = $tab++;
$atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"] = $nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------
//------------------Fin Division para los botones-------------------------
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

//------------------Fin Division para las pestañas-------------------------
echo $this->miFormulario->division("fin");
?>
