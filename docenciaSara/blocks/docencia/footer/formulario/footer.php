<?php 

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

?>


<div id="footer" align="center">
    <br/>
    <p id="info">Universidad Distrital Francisco José de Caldas. Oficina Asesora de Sistemas. 2013 </p>
</div>