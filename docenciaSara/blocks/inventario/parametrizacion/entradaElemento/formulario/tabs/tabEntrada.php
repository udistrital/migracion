<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

//

$tab=1;

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);

//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo="marcoDatosBasicos";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="factura";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="8";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"]="required";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="fechaFactura";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="10";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2"; //El control ocupa 50% del tamaño del formulario
$atributos["validar"]="required, minSize[3]";
$atributos["categoria"]="fecha"; //Para evitar un error al validar un datepicker
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="proveedor";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2";
$atributos["validar"]="required, minSize[3]";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);


//-------------Control Lista Desplegable-----------------------
$esteCampo="idOrdenadorGasto";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["seleccion"]=1;
$atributos["evento"]=2;
$atributos["limitar"]=false;
$atributos["tamanno"]=1;
//usseless: El estilo en las listas desplegables se maneja registrando el widget menu en jquery
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2";
$atributos["ancho"]="60%";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"]=$this->sql->cadena_sql("buscarOrdenadorGasto");
$atributos["baseDatos"]="gearbox";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//-------------Control Cuadro de Texto -----------------------

$esteCampo="dependenciaSupervisora";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required, minSize[3]";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//Fin de Conjunto de Controles
echo $this->miFormulario->marcoAGrupacion("fin");

//-------------Control cuadroTexto-----------------------
$esteCampo="idProveedor";
$atributos["id"]=$esteCampo;
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["valor"]="-1";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);


//-------------Control cuadroTexto-----------------------
$esteCampo="idDependenciaSupervisora";
$atributos["id"]=$esteCampo;
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["valor"]="-1";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);


//----------------------Fin Conjunto de Controles--------------------------------------


//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo="marcoTipoEntrada";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);
unset($atributos);

//-------------Control Lista Desplegable-----------------------

$esteCampo="idTipoEntrada";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["seleccion"]=1;
$atributos["evento"]=2;
$atributos["limitar"]=false;
$atributos["tamanno"]=1;
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"]=$this->sql->cadena_sql("buscarTipoEntrada");
$atributos["baseDatos"]="gearbox";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="fechaTipoEntrada";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="10";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="2"; //El control ocupa 47% del tamaño del formulario
$atributos["validar"]="required";
$atributos["categoria"]="fecha"; //Para evitar un error al validar un datepicker
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="vigencia";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="8";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]="3"; //El control ocupa 32% del tamaño del formulario
$atributos["validar"]="required, min[1],max[999], custom[integer]";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//Fin de Conjunto de Controles
echo $this->miFormulario->marcoAGrupacion("fin");

//----------------------Fin Conjunto de Controles--------------------------------------


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


//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);

//-------------Control Boton-----------------------
$esteCampo="botonAceptar";
$atributos["id"]=$esteCampo;
$atributos["tabIndex"]=$tab++;
$atributos["tipo"]="boton";
$atributos["estilo"]="";
$atributos["verificar"]=""; //Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
$atributos["tipoSubmit"]="jquery"; //Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
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


//-------------Control cuadroTexto con campos ocultos-----------------------

$atributos["id"]="grillaElementos"; 
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);


//Fin del Formulario
echo $this->miFormulario->formulario("fin");


?>