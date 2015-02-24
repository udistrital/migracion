<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$miSesion = Sesion::singleton ();

// Registro Funcionario
$enlaceRegistroDocente ['enlace'] = "pagina=crearFuncionario";
$enlaceRegistroDocente ['enlace'] .= "&usuario=" . $miSesion->getSesionUsuarioId ();

$enlaceRegistroDocente ['urlCodificada'] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $enlaceRegistroDocente ['enlace'], $directorio );
$enlaceRegistroDocente ['nombre'] = "Administración Funcionarios";

$pagina = $this->miConfigurador->getVariableConfiguracion("site");
?>
<div class="wrap">

	<div class="demo-container">
		<div class="black">
			<ul id="mega-menu-1" class="mega-menu">
				<li><a href="#">Inicio</a></li>
				<li><a href="#">Administración</a>
                                    <ul>
                                        <li><a href="<?php echo $enlaceRegistroDocente['urlCodificada']?>"><?php echo $enlaceRegistroDocente ['nombre']?></a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php  echo $pagina ?>">Cerrar Sesi&oacute;n</a></li>
                        </ul>
			
		</div>
	</div>

</div>

