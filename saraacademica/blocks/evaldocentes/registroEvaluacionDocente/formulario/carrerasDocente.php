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

include_once("core/crypto/Encriptador.class.php");
$cripto=Encriptador::singleton();
$valorCodificado="action=".$esteBloque["nombre"];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&usuario=".$_REQUEST['usuario'];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$cripto->codificar($valorCodificado);
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

//------------------Division para las pestañas-------------------------
$atributos["id"]="tabs";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
unset($atributos);

//-------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------

//$items=array("tabCurso"=>$this->lenguaje->getCadena("tabCurso"), "tabDocente"=>$this->lenguaje->getCadena("tabDocente"));
$items=array("tabEstudiantes"=>$this->lenguaje->getCadena("tabEstudiantes"),"tabAutoevaluacion"=>$this->lenguaje->getCadena("tabAutoevaluacion"),"tabConsejoCurricular"=>$this->lenguaje->getCadena("tabConsejoCurricular"));
$atributos["items"]=$items;
$atributos["estilo"]="jqueryui";
$atributos["pestañas"]="true";
echo $this->miFormulario->listaNoOrdenada($atributos);

//------------------Division para la pestaña 1-------------------------
$atributos["id"]="tabEstudiantes";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
include($this->ruta."formulario/tabs/tabEstudiantes.php"); 
//-----------------Fin Division para la pestaña 1-------------------------
echo $this->miFormulario->division("fin");

//------------------Division para la pestaña 2-------------------------
$atributos["id"]="tabAutoevaluacion";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
include($this->ruta."formulario/tabs/tabAutoevaluacion.php"); 
//-----------------Fin Division para la pestaña 2-------------------------
echo $this->miFormulario->division("fin");

//------------------Division para la pestaña 3-------------------------
$atributos["id"]="tabConsejoCurricular";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
include($this->ruta."formulario/tabs/tabConsejoCurricular.php");
//-----------------Fin Division para la pestaña 3-------------------------
echo $this->miFormulario->division("fin");


//------------------Fin Division para las pestañas-------------------------
echo $this->miFormulario->division("fin");

?>
