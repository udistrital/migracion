<?php

$indice=0;
$funcion[$indice++]="jqueryui.js";
$funcion[$indice++]="menuVertical/jquery.hoverIntent.minified.js";
$funcion[$indice++]="menuVertical/jquery.dcverticalmegamenu.1.1.js";

$rutaBloque=$this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site");

if($esteBloque["grupo"]==""){
	$rutaBloque.="/blocks/".$esteBloque["nombre"];
}else{
	$rutaBloque.="/blocks/".$esteBloque["grupo"]."/".$esteBloque["nombre"];
}


foreach ($funcion as $clave=>$nombre){
	if(!isset($embebido[$clave])){
		echo "\n<script type='text/javascript' src='".$rutaBloque."/script/".$nombre."'>\n</script>\n";
	}else{
		echo "\n<script type='text/javascript'>";
		include($nombre);
		echo "\n</script>\n";
	}
}

// Procesar las funciones requeridas en ajax
//include("Ajax.php");

?>

