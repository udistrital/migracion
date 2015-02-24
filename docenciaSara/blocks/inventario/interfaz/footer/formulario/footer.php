<?php 

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

?>


<div id="footer">
    <br/>
    <img src="<?php echo $rutaBloque."css/images/ud.jpg" ?>"/>
    <p id="info">Universidad Distrital Francisco José de Caldas. Oficina Asesora de Sistemas. 2013 </p>
</div>