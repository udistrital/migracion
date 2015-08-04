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
$valorCodificado.="&opcion=ingresoCondor";
$valorCodificado.="&bloque=".$esteBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";



$tab=1;
?>
<div id="encabezado_pagina">

</div>  
<div id="login">
<?php

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);



//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);

//-------------Control Boton-----------------------
$esteCampo="botonIngresar";
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

//Campo Oculto del usuario
$atributos["id"]="usuario"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$usuario;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//Campo Oculto del modulo
$atributos["id"]="modulo"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$modulo;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);



//Fin del Formulario
echo $this->miFormulario->formulario("fin");


?>
</div>



<div id="sabio"> 
</div>

<div id="menu">
    <h3>Portales UD</h3>
    <ul>
        <li><a href="http://www.udistrital.edu.co/"target="_blank">Universidad Distrital FJC</a></li>
        <li><a href="https://condor.udistrital.edu.co/appserv/"target="_blank">C&oacute;ndor</a></li>

    </ul>
</div>


<div id="escudo"></div>

<div id="pie">
    Universidad Distrital Francisco Jos&eacute; de Caldas <br>
    Oficina Asesora de Sistemas 2013. Todos los derechos reservados.<br>
    Carrera 8 N. 40-78 Piso 1 / Teléfonos 3238400 - 3239300 Ext. 1112 -1113. ESTAMOS EN INGRESO NORMAL<br>
    
    <a href="mailto:computo@udistrital.edu.co" class="enlace">computo@udistrital.edu.co</a>
</div>