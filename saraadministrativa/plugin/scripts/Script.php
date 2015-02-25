<?php

$host=$this->miConfigurador->getVariableConfiguracion("host");
$sitio=$this->miConfigurador->getVariableConfiguracion("site");
$indice=0;
$funcion[$indice++]="funciones.js";

if(isset($_REQUEST["jquery"])) {
	$funcion[$indice++]="jquery.js";
}

$funcion[$indice++]="jquery-ui/jqueryui.js";


foreach ($funcion as $nombre){
	echo "<script type='text/javascript' src='".$host.$sitio."/plugin/scripts/javascript/".$nombre."'></script>";
}

?>