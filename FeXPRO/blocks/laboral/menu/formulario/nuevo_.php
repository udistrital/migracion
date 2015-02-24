<?php

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
$miSesion = Sesion::singleton();

//Generar Certificado
$enlacegenerarReporte['enlace'] = "pagina=generarReporte";
$enlacegenerarReporte['enlace'].= "&usuario=" . $miSesion->getSesionUsuarioId();

$enlacegenerarReporte['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($enlacegenerarReporte['enlace'], $directorio);
$enlacegenerarReporte['nombre'] = "Generar Certificados";
   
?>
<div class="wrap">

<div class="demo-container">
<div class="black">  
<ul id="mega-menu-1" class="mega-menu">
	<li><a href="#">Inicio</a></li>
	
        <li><a href="#">Certificados</a>
                <ul>
                        <li><a href="<?php echo $enlacegenerarReporte['urlCodificada']?>"><?php echo $enlacegenerarReporte['nombre']?></a></li>
                </ul>
        </li>
        <?php  $pagina = $this->miConfigurador->getVariableConfiguracion("site") ?>>
      <li><a href="<?php  echo $pagina ?>">Cerrar Sesi&oacute;n</a></li>
</ul>
</div>
</div>

</div>

