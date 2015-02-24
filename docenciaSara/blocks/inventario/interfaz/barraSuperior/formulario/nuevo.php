<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$num = rand(1, 12);
$estilo="barraSeparadora_".$num;

?>
<div class="<?php echo $estilo; ?>">
    <p class="textoNombre">Nombre Apellido 
        <img class="fotoUsuario" src="<? echo $rutaBloque . "/imagenesTemp/usuario.png" ?>">
    </p>        
</div>
