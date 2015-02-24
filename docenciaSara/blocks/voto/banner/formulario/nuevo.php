<?php 

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."formulario/superior.jpg";
echo "<img alt='' src='".$directorio."' >";
?>
