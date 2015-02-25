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


//url

//URL base
$url=$this->miConfigurador->getVariableConfiguracion("host");
$url.=$this->miConfigurador->getVariableConfiguracion("site");
$url.="/index.php?";


$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");

//Variables
$cadenaACodificar="pagina=".$this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar.="&procesarAjax=true";
$cadenaACodificar.="&action=index.php";
$cadenaACodificar.="&bloqueNombre=".$esteBloque["nombre"];
$cadenaACodificar.="&bloqueGrupo=".$esteBloque["grupo"];


//Codificar las variables
$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");

//Cadena codificada para subir archivos
$cadenaACodificar=$cadenaACodificar."&funcion=procesarListado";
$cadena=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar,$enlace);
$urlUploadFile = $url.$cadena;

$tab=1;

echo '   <form id="formulario"  method="post" enctype="multipart/form-data">';
echo '       <h3 style="text-align:center;">'.utf8_encode($this->lenguaje->getCadena("archivoTitulo")).'</h3>';
echo '    <p style="font-style:italic;text-align:center;">'.$this->lenguaje->getCadena("nota").'</p><br>';
echo '      <table>';
echo '           <tr><td>'.utf8_encode($this->lenguaje->getCadena("archivo")).':</td><td><input type="file" name="archivo" id="archivo"></td></tr>';
echo '           <tr><td>&nbsp;</td><td><input id="enviarListado" type="button" value="Upload"></td></tr>';
echo '      </table>';
echo '   </form>';
echo '<div id="respuesta" style="display:none;" width="50%">';
echo '</div>';
