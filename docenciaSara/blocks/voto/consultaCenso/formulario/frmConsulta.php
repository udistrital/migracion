<?php 

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 * 
 *  La ruta absoluta del bloque está definida en $this->ruta
 */


$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario=$esteBloque["nombre"];

$valorCodificado="pagina=index";
$valorCodificado.="&action=".$esteBloque["nombre"];
$valorCodificado.="&opcion=consultaCenso";
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

//------------------Division para las pestañas-------------------------
$atributos["id"]="tabs";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
unset($atributos);

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

//-------------Control texto-----------------------
$esteCampo="datosUsuario";
$atributos["tamanno"]="";
$atributos["estilo"]="jqueryui";
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["texto"]="Por favor ingrese número de documento a consultar";
$atributos["columnas"]=""; //El control ocupa 47% del tamaño del formulario
echo $this->miFormulario->campoTexto($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="idUsuario";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="8";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["columnas"]=""; //El control ocupa 32% del tamaño del formulario
$atributos["validar"]="required, min[4],max[999], custom[integer], number";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//----------------------Fin Conjunto de Controles--------------------------------------


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

//------------------Fin Division para las pestañas-------------------------
echo $this->miFormulario->division("fin");

?>
