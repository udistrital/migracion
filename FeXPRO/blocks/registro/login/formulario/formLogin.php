<?php

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
//El tiempo que se utiliza para agregar al nombre del campo se declara en ready.php


/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 *
 * La ruta absoluta del bloque está definida en $this->ruta
 */
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$nombreFormulario = sha1($esteBloque ['nombre'].$_REQUEST['tiempo']);

$valorCodificado = "action=" . $esteBloque ["nombre"];
$valorCodificado .= "&bloque=" . $esteBloque ["id_bloque"];
$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$valorCodificado .= "&tiempo=" . $_REQUEST ['tiempo'];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
$directorio = $this->miConfigurador->getVariableConfiguracion ( "rutaUrlBloque" );

$tab = 1;

// ------------------Division para los botones-------------------------
$atributos ["id"] = "fondo";
$atributos ["estilo"] = "jquery";
echo $this->miFormulario->division ( "inicio", $atributos );

// ---------------Inicio Formulario (<form>)--------------------------------
$atributos ["id"] = $nombreFormulario;
$atributos ["tipoFormulario"] = "multipart/form-data";
$atributos ["metodo"] = "POST";
$atributos ["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario ( "inicio", $atributos );

// ------------------Division para los botones-------------------------
$atributos ["id"] = "datos";
$atributos ["estilo"] = "jquery";
echo $this->miFormulario->division ( "inicio", $atributos );

// -------------Control cuadroTexto-----------------------
$esteCampo = sha1('usuario'.$_REQUEST['tiempo']);
$atributos ["id"] = $esteCampo;
$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
$atributos ["estilo"] = "simple";
$atributos ["tamanno"] = "grande";
echo $this->miFormulario->campoMensaje ( $atributos );
unset ( $atributos );


$esteCampo = sha1('usuario'.$_REQUEST['tiempo']);
$atributos ["id"] = $esteCampo;
$atributos ["anchoEtiqueta"] ='150px'; //sobreescribe el ancho predeterminado que es de 120px
$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
$atributos ["tabIndex"] = $tab ++;
$atributos ["obligatorio"] = true;
$atributos ["tamanno"] = "30";
$atributos ["tipo"] = "";
$atributos ["estilo"] = "jqueryui";
$atributos ["validar"] = "required";

echo $this->miFormulario->campoCuadroTexto ( $atributos );
unset ( $atributos );

// -------------Control cuadroTexto-----------------------
$esteCampo = sha1('clave'.$_REQUEST['tiempo']);
$atributos ["id"] = $esteCampo;
$atributos ["mensaje"] = $this->lenguaje->getCadena ( $esteCampo );
$atributos ["estilo"] = "simple";
$atributos ["tamanno"] = "grande";
echo $this->miFormulario->campoMensaje ( $atributos );
unset ( $atributos );
// -------------Control cuadroTexto-----------------------
$esteCampo = sha1('clave'.$_REQUEST['tiempo']);
$atributos ["id"] = $esteCampo;
$atributos ["titulo"] = $this->lenguaje->getCadena ( $esteCampo . "Titulo" );
$atributos ["tabIndex"] = $tab ++;
$atributos ["obligatorio"] = true;
$atributos ["tamanno"] = "30";
$atributos ["tipo"] = "password";
$atributos ["estilo"] = "jqueryui";
$atributos ["validar"] = "required";

// $atributos["valor"]="sistemasoas";

echo $this->miFormulario->campoCuadroTexto ( $atributos );
unset ( $atributos );

// Si existe algun tipo de error en el login aparece el siguiente mensaje

if (isset ( $_REQUEST ['error'] ) && $_REQUEST ['error'] == 'usuarioNoValido') {
	// ------------------Division para los botones-------------------------
	$atributos ["id"] = "error";
	$atributos ["estilo"] = "marcoBotones";
	echo $this->miFormulario->division ( "inicio", $atributos );
	
	// -------------Control texto-----------------------
	$esteCampo = "mensajeUsuarioError";
	$atributos ["tamanno"] = "";
	$atributos ["estilo"] = "errorLogin";
	$atributos ["etiqueta"] = "";
	$atributos ["mensaje"] = "Nombre de usuario o contraseña incorrectos";
	$atributos ["columnas"] = ""; // El control ocupa 47% del tamaño del formulario
	echo $this->miFormulario->campoMensaje ( $atributos );
	unset ( $atributos );
	
	// ------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division ( "fin" );
} else if (isset ( $_REQUEST ['error'] ) && $_REQUEST ['error'] == 'claveNoValida') {
	// ------------------Division para los botones-------------------------
	$atributos ["id"] = "error";
	$atributos ["estilo"] = "marcoBotones";
	echo $this->miFormulario->division ( "inicio", $atributos );
	
	// -------------Control texto-----------------------
	$esteCampo = "mensajeClaveError";
	$atributos ["tamanno"] = "";
	$atributos ["estilo"] = "errorLogin";
	$atributos ["etiqueta"] = "";
	$atributos ["mensaje"] = "Nombre de usuario o contraseña incorrectos";
	$atributos ["columnas"] = ""; // El control ocupa 47% del tamaño del formulario
	echo $this->miFormulario->campoMensaje ( $atributos );
	unset ( $atributos );
	
	// ------------------Fin Division para los botones-------------------------
	echo $this->miFormulario->division ( "fin" );
}

// ------------------Division para los botones-------------------------
$atributos ["id"] = "botones";
$atributos ["estilo"] = "marcoBotones";
echo $this->miFormulario->division ( "inicio", $atributos );

// -------------Control Boton-----------------------
$esteCampo = "botonAceptar";
$atributos ["id"] = $esteCampo;
$atributos ["tabIndex"] = $tab ++;
$atributos ["tipo"] = "boton";
$atributos ["estilo"] = "";
$atributos ["verificar"] = ""; // Se coloca true si se desea verificar el formulario antes de pasarlo al servidor.
$atributos ["tipoSubmit"] = "jquery"; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
$atributos ["nombreFormulario"] = $nombreFormulario;
echo $this->miFormulario->campoBoton ( $atributos );
unset ( $atributos );
// -------------Fin Control Boton----------------------

// ------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division ( "fin" );

echo $this->miFormulario->division ( "fin" );
// -------------Control cuadroTexto con campos ocultos-----------------------
// Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos ["id"] = "formSaraData"; // No cambiar este nombre
$atributos ["tipo"] = "hidden";
$atributos ["obligatorio"] = false;
$atributos ["etiqueta"] = "";
$atributos ["valor"] = $valorCodificado;
echo $this->miFormulario->campoCuadroTexto ( $atributos );
unset ( $atributos );

// Fin del Formulario
echo $this->miFormulario->formulario ( "fin" );

$atributos["id"]="divpie";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
//------------------Division-------------------------
$atributos["id"]="sabio";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
unset($atributos);
//------------Fin de la División -----------------------
echo $this->miFormulario->division("fin");

//------------------Division-------------------------
$atributos["id"]="escudo";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
unset($atributos);
//------------Fin de la División -----------------------
echo $this->miFormulario->division("fin");

//------------------Division-------------------------
$atributos["id"]="pie";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
unset($atributos);

//-----------------Texto-----------------------------
$esteCampo='mensajePie';
$atributos["estilo"]="";
$atributos['texto']=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);


//------------Fin de la División -----------------------
echo $this->miFormulario->division("fin");

echo $this->miFormulario->division ( "fin" );

echo $this->miFormulario->division ( "fin" );
?>