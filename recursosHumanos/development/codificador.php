<?php
include_once("../core/crypto/Encriptador.class.php");

$miCodificador=Encriptador::singleton();
echo $miCodificador->codificar("10.20.0.22")."<br>";
echo $miCodificador->codificar("poker")."<br>";
echo $miCodificador->decodificar("-AJglZ3BvFKf3BGUDw")."<br>--";




$parametro=array("owKQlJlE6lOONuZVsg==",
"jAD62eDABFTJQCYBggCbeL-v",
"qgJ+mJlE6lOReLU7",
"rgIce5lE6lO+JTQk4eW7t2kjFHLAqKQ=",
"sgKx0JlE6lPcVnrwhPssbg==",
"7wJCR6q_BFQnyLkH3tTeu0_ktN4pQTWL",
"uAIm35lE6lMTUre00rkx7ls="

);

foreach ($parametro as $valor){
	echo $miCodificador->decodificar($valor)."<br>";
}



?>
