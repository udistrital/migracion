<?

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");

$nombreFormulario=$esteBloque["nombre"];

include_once("core/crypto/Encriptador.class.php");
$cripto=Encriptador::singleton();
$valorCodificado="action=".$esteBloque["nombre"];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$cripto->codificar($valorCodificado);

$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

$tab=1;
//Formulario para nuevos registros de usuario
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$atributos["verificarFormulario"]="1";

echo $this->miFormulario->marcoFormulario("inicio",$atributos);



//-------------Control cuadroTexto-----------------------
$esteCampo="nombre";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]=20;
$atributos["tipo"]="";
$atributos["estilo"]="";
$atributos["verificarFormulario"].="&& control_vacio(".$atributos["nombreFormulario"].",'".$atributos["id"]."')";
$atributos["verificarFormulario"].="&& longitud_cadena(".$atributos["nombreFormulario"].",'".$atributos["id"]."',3)";
echo $this->miFormulario->campoCuadroTexto($atributos);

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
$atributos["verificar"]="";
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoBoton($atributos);
//-------------Fin Control Boton----------------------

//-------------Control Boton-----------------------
$esteCampo="botonCancelar";
$atributos["verificar"]="";
$atributos["tipo"]="boton";
$atributos["id"]=$esteCampo;
$atributos["cancelar"]="true";
$atributos["tabIndex"]=$tab++;
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->campoBoton($atributos);
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin",$atributos);



//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"]="formSaraData"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);


//Fin del Formulario
echo $this->miFormulario->marcoFormulario("fin",$atributos);


?>