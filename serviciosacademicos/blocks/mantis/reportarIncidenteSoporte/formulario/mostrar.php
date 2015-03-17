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

include_once("core/builder/XML2GUI.class.php");



//Titulo
echo "<div style='text-align: center;'><h3>".$this->lenguaje->getCadena("ulTabs1")."</h3><br></div>";

$tab=1;

$xmlFile =$dom = new DOMDocument('1.0', 'UTF-8');


//se crea el elemento raiz
$root=$xmlFile->createElement('XML2GUI');

//Division Tabs
//se crea Division para los tabs
$esteCampo="tabs";
$tabs=$xmlFile->createElement('division');
$tabs->setAttribute("contenedor","si");
$tabs->setAttribute("id",$esteCampo);

//se crea Division para los tabs-1 //Actualizar elementos
$esteCampo="tabs-1";
$tabs1=$xmlFile->createElement('division');
$tabs1->setAttribute("contenedor","si");
$tabs1->setAttribute("id",$esteCampo);

//Division consulta

$esteCampo="edicion";
$consulta=$xmlFile->createElement('division');
$consulta->setAttribute("contenedor","si");
$consulta->setAttribute("id",$esteCampo);
$tabs1->appendChild($consulta);




//se crea Division para los tabs-2 //Crear elementos
$esteCampo="tabs-2";
$tabs2=$xmlFile->createElement('division');
$tabs2->setAttribute("contenedor","si");
$tabs2->setAttribute("id",$esteCampo);

//Division consulta

$esteCampo="consultas";
$consulta=$xmlFile->createElement('division');
$consulta->setAttribute("contenedor","si");
$consulta->setAttribute("id",$esteCampo);
$tabs2->appendChild($consulta);

$tabs->appendChild($tabs1);
//$tabs->appendChild($tabs2);


//se agregan elementos a raiz

$root->appendChild($tabs);
$xmlFile->appendChild($root);

//echo $xmlFile->saveXML();

$miConvertidor=new XML2GUI();
$miConvertidor->convertir($xmlFile->saveXML());


/*
//no fue posible crear con los elementos del core los tabs con ul y li
echo "<script>";
echo 'var ul = document.createElement("ul");';
echo 'var li1 = document.createElement("li");';
echo 'var a1 = document.createElement("a");';
echo 'a1.href = "#tabs-1";';
echo 'a1.innerHTML = "'.$this->lenguaje->getCadena("ulTabs1").'";';
echo 'li1.appendChild(a1);';
echo 'var li2 = document.createElement("li");';
echo 'var a2 = document.createElement("a");';
echo 'a2.href = "#tabs-2";';
echo 'a2.innerHTML = "'.$this->lenguaje->getCadena("ulTabs2").'";';
echo 'li2.appendChild(a2);';
echo 'ul.appendChild(li1);';
echo 'ul.appendChild(li2);';
echo 'var divTabs = document.getElementById("tabs");';
echo 'divTabs.insertBefore(ul, divTabs.firstChild);';
echo "</script>";
*/
exit;