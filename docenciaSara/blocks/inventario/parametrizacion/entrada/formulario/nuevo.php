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


$valorCodificado="action=".$esteBloque["nombre"];
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";

//------------------Division para las pestañas-------------------------
$atributos["id"]="tabs";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
unset($atributos);

//-------------------- Listado de Pestañas (Como lista No Ordenada) -------------------------------

$items=array(
    "tabEntrada"=>$this->lenguaje->getCadena("tabEntrada"), 
    "tabElementos"=>$this->lenguaje->getCadena("tabElementos"),
    "tabSalida"=>$this->lenguaje->getCadena("tabSalida"),
    );
$atributos["items"]=$items;
$atributos["estilo"]="jqueryui";
$atributos["pestañas"]="true";
echo $this->miFormulario->listaNoOrdenada($atributos);


//------------------Division para la pestaña 1-------------------------
$atributos["id"]="tabEntrada";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
include($this->ruta."formulario/tabs/tabEntrada.php");
//-----------------Fin Division para la pestaña 1-------------------------
echo $this->miFormulario->division("fin");

//------------------Division para la pestaña 2-------------------------
$atributos["id"]="tabElementos";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
include($this->ruta."formulario/tabs/tabElementos.php");
//-----------------Fin Division para la pestaña 2-------------------------
echo $this->miFormulario->division("fin");

//------------------Division para la pestaña 3-------------------------
$atributos["id"]="tabSalida";
$atributos["estilo"]="";
echo $this->miFormulario->division("inicio",$atributos);
include($this->ruta."formulario/tabs/tabSalida.php");
//-----------------Fin Division para la pestaña 3-------------------------
echo $this->miFormulario->division("fin");


//------------------Fin Division para las pestañas-------------------------
echo $this->miFormulario->division("fin");

?>
