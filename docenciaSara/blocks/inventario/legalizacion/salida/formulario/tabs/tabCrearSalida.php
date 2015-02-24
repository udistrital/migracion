<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


$valorCodificado="pagina=salida";
$valorCodificado.="&opcion=confirmar";
$valorCodificado.="&bloque=".$esteBloque["nombre"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado.="&idEntrada=".$entrada["ent_id"];
$valorCodificado.="&idElemento=".$elemento["ele_id"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//-------------------------------Mensaje-------------------------------------
$esteCampo="mensaje1";
$atributos["id"]=$esteCampo;
$atributos["obligatorio"]=false;
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]="simple";
$atributos["mensaje"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoMensaje($atributos);

$tab=1;

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);
unset($atributos);

//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo="marcoDatosEntrada";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="idEntrada";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$entrada["ent_id"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="fechaEntrada";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$entrada["ent_fecha"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="conceptoEntrada";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$entrada["ent_concepto"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//----------------------Fin Conjunto de Controles--------------------------------------
echo $this->miFormulario->marcoAGrupacion("fin");

//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo="marcoDatosElemento";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="idElemento";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["ele_id"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="marca";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["mar_descripcion"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="tipoElemento";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["sum_nombre"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="tipoBien";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["tbi_descripcion"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="cantidad";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["ele_cantidad"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="precio";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]="$".$elemento["ele_precio"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="serie";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["ele_num_serie"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="codigoBarras";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["ele_codigo_barras"];
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="descripcionElemento";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]=$elemento["ele_descripcion"];
$atributos["columnas"]=""; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);unset($atributos);

//Fin de Conjunto de Controles
echo $this->miFormulario->marcoAGrupacion("fin");
//----------------------Fin Conjunto de Controles--------------------------------------

//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo="marcoDatosSalida";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="idSalida";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"]=true;
$atributos["tamanno"]="8";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]=""; //El control ocupa 47% del tamaño del formulario
$atributos["validar"]="required";
$atributos["categoria"]="";
$atributos["deshabilitado"]=true;
$atributos["valor"]=$idSalida;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="fechaRegistro";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["etiquetaObligatorio"]=true;
$atributos["tamanno"]="8";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
$atributos["validar"]="required";
$atributos["categoria"]="";
$atributos["deshabilitado"]=true;
$atributos["valor"]=@date("d/m/y");
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="fechaSalida";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="10";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"]="required, minSize[3]";
$atributos["categoria"]="fecha"; //Para evitar un error al validar un datepicker
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="sede";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="30";
$atributos["columnas"]="2"; //El control ocupa 32% del tamaño del formulario
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required, minSize[3]";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="dependencia";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="31";
$atributos["columnas"]="2"; //El control ocupa 32% del tamaño del formulario
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required, minSize[3]";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="funcionario";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="50";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required, minSize[3]";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTextArea-----------------------
$esteCampo="observacion";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=false;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["columnas"]=40;
$atributos["filas"]=4;
$atributos["estilo"]="jqueryui";
echo $this->miFormulario->campoTextArea($atributos);
unset($atributos);

//Fin de Conjunto de Controles
echo $this->miFormulario->marcoAGrupacion("fin");
//----------------------Fin Conjunto de Controles--------------------------------------

//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);

//-------------Control Boton-----------------------
$esteCampo="botonGuardar";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["tipo"]="boton";
$atributos["estilo"]="";
$atributos["verificar"]="";
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"]=$nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------

//-------------Control Boton-----------------------
$esteCampo="botonCancelar";
$atributos["verificar"]="";
$atributos["tipo"]="boton";
$atributos["id"]=$esteCampo;
$atributos["cancelar"]="true";
$atributos["tabIndex"]=$tab++;
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


?>