<?PHP
include_once("../core/crypto/Encriptador.class.php");

$miCodificador=Encriptador::singleton();
echo $miCodificador->codificar("sjneira")."<br>";
echo $miCodificador->codificar("74333611")."<br>";
echo "pass<br>";
echo $miCodificador->codificar("aAO4GuSh6lJ5KXC1w4yW3ZQZ0K2go-g")."<br>";
echo $miCodificador->decodificar("aAO4GuSh6lJ5KXC1w4yW3ZQZ0K2go-g")."<br>";
echo $miCodificador->decodificar("OgHG-AJMaVIyuYXZjDpFUlyWl9R320x8AGs")."<br>";



/*

$parametro=array("AwLSWHOR61DhZcTqkA==",
"CwKk33OR61C9BaWCkKKdcbc=",
"DwLlY3OR61B/gbFc",
"EwLQVHOR61DfS8OI/96/gEL0l9XuWw==",
"FwJ14HOR61DhdetkyM8whQ==",
"GwKxk3OR61C90avH6Fq2nbol5g==",
"HwI+DXOR61DMHj+OOwOsk7YAZg==");

foreach ($parametro as $valor){
	echo $miCodificador->decodificar($valor)."<br>";
}
*/


?>
