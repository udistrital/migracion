<?
include_once("../core/crypto/Encriptador.class.php");

$miCodificador=Encriptador::singleton();
echo $miCodificador->codificar("10.20.0.22")."<br>";
echo $miCodificador->codificar("Marquez-9138ogpjblt")."<br>";
//echo $miCodificador->decodificar("0928a675ad0d647683703c613e85e5576820af2d")."<br>";
//echo $miCodificador->decodificar("OgHG-AJMaVIyuYXZjDpFUlyWl9R320x8AGs")."<br>";


$parametro=array(
"TgOsyOSh6lJnj2eplA==",
"cgPQAU4QJ1X1tV_ekkQBqG0C",
"VQO1NOSh6lKYp08Q",
"WQP37uSh6lImtdalFInoJYQtsdFH6g==",
"XQMMzeSh6lJtm5YkmOXYS2Op",
"pAKGH3EMJ1XTOlVG8R_m8aYSEfPReoo2QiGF",
"ZAM5HOSh6lIZzSquBw==");

foreach ($parametro as $valor){
	echo $miCodificador->decodificar($valor)."<br>";
}



?>
