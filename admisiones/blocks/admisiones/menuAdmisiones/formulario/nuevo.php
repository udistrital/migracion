<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
$miSesion = Sesion::singleton();

$conexion = "admisiones";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

	echo "Este se considera un error fatal";
	exit;
}

$variable['tipoInstructivo']="instructivo";

$cadena_sql = $this->sql->cadena_sql("buscarNombreInstructivo", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//Si el usurio tiene nivel 1, tiene acceso al siguiente menú:
if($miSesion->getSesionNivel()==1)
{	
	?>
	<div class="wrap">
	    <div class="demo-container clear">
	        <div class="dcjq-vertical-mega-menu">
	            <ul id="mega-1" class="menu">
	                <li id="menu-item-0">
	                    <a href="<?
	                    $variable = "pagina=admisiones"; //pendiente la pagina para modificar parametro
	                    $variable.= "&tipo=1";
	                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
	                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                    echo $variable;
	                    ?>">
	                    <img src='<?php echo $rutaBloque . "/css/menuVertical/images/home.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Inicio</a></li> 
	                
	                <li id="menu-item-6">
	                	<a href="#">
	                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
	                		Instructivo
	            		</a>
	                    <ul>
	                    	<?php
	                    	for ($i=0; $i<=count($registro)-1; $i++)
	                    	{
	                    		echo '<li id="menu-item-52">';
	                    		echo '<a href="';
	                    		$variable = "pagina=admisiones";
	                    		$variable.= "&usuario=".$miSesion->getSesionUsuarioId();
	                    		$variable.= "&tipo=1";
	                            $variable.= "&opcion=instructivo";
	                            $variable.= "&seccion=".$registro[$i]['ins_nombre'];
	                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                            echo $variable;
	                            echo '">'.$registro[$i]['ins_nombre'].'</a>';
	                            echo '</li>';
	                    	}	
	                    	?>
	                    </ul>
	                </li>
	                <li id="menu-item-6">
	                    <a href="<?php
	                    $variable = "pagina=admisiones"; 
	                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
	                    $variable.= "&tipo=1";
	                    $variable.= "&opcion=mensajeAceptacion";
	                    $variable.= "&mensaje=aceptacion";
	                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                    echo $variable;
	                    ?>">
	                        <img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
	                        Realizar inscripción
	                    </a>
	                </li>                  
	                <li id="menu-item-6">
	                    <a href="<?php
	                    $variable = "pagina=admisiones"; 
	                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
	                    $variable.= "&tipo=1";
	                    $variable.= "&opcion=verInscripcion";
	                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                    echo $variable;
	                    ?>">
	                        <img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
	                        Ver Inscripción
	                    </a>
	                </li>
	                <li id="menu-item-6">
	                    <a href="<?php
	                    $variable = "pagina=evaluacionesExtemporaneas"; //pendiente la pagina para modificar parametro
	                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
	                    $variable.= "&tipo=88";
	                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                    echo $variable;
	                    ?>">
	                        <img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
	                        Ver Resultados
	                    </a>
	                </li>
	                     
	                <!--li id="menu-item-6">
	                	<a href="#">
	                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/security.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
	                		Seguridad
	            		</a>
	                    <ul>
	                        <li id="menu-item-52">
	                            <a href="<?
	                            /*$variable = "pagina=cambiarClaveSoporte"; //pendiente la pagina para modificar parametro                                                        
	                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
	                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                            echo $variable;*/
	                            ?>"> Clave de acceso </a>
	                        </li>                 
	                    </ul>
	                </li-->   
	                <li id="menu-item-6">
	                    <a href="<?
	                    $variable = "pagina=cerrarSesion"; //pendiente la pagina para modificar parametro
	                    $variable.= "&sesionId=" . $miSesion->getSesionId();
	                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                    echo $variable;
	                    ?>">
	                        <img src='<?php echo $rutaBloque . "/css/menuVertical/images/salir.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
	                        Cerrar sesion
	                    </a>
	                </li>
	            </ul>
	        </div>
	    </div>
	</div>
	<?
}
else 
{
    if($esteBloque['id_pagina']==6)
    {    
        ?>
        <div class="wrap">
            <div class="demo-container clear">
                <div class="dcjq-vertical-mega-menu">
                    <ul id="mega-1" class="menu">
                        <li id="menu-item-0">
                            <a href="<?php
                            $variable = "pagina=instructivo"; //pendiente la pagina para modificar parametro
                            $variable.= "&tipo=0";
                            $variable.= "&opcion=instructivo";
                            $variable.= "&seccion=Principal";
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">
                            <img src='<?php echo $rutaBloque . "/css/menuVertical/images/home.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Inicio</a></li> 

                                        <?php
                        for ($i=0; $i<=count($registro)-1; $i++)
                        {
                                echo '<li id="menu-item-6">';
                                echo '<a href="';
                                $variable = "pagina=instructivo";
                                $variable.= "&usuario=".$miSesion->getSesionUsuarioId();
                                $variable.= "&tipo=0";
                            $variable.= "&opcion=instructivo";
                            $variable.= "&seccion=".$registro[$i]['ins_nombre'];
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            echo '"><img src="'.$rutaBloque.'/css/menuVertical/images/edit.png" width="15px" style="vertical-align:text-bottom;" >'.$registro[$i]['ins_nombre'].'</a>';
                            echo '</li>';
                        }	
                        ?>               

                        <li id="menu-item-6">
                            <a href="<?php
                            $variable = "pagina=cerrarSesion"; //pendiente la pagina para modificar parametro
                            $variable.= "&sesionId=" . $miSesion->getSesionId();
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">
                                <img src='<?php echo $rutaBloque . "/css/menuVertical/images/salir.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                                Cerrar sesion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?
    }
    elseif($esteBloque['id_pagina']==7)
    {
        ?>
        <div class="wrap">
            <div class="demo-container clear">
                <div class="dcjq-vertical-mega-menu">
                    <ul id="mega-1" class="menu">
                        <li id="menu-item-0">
	                    <a href="<?php
	                    $variable = "pagina=resultados"; //pendiente la pagina para modificar parametro
	                    $variable.= "&tipo=1";
	                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
	                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                    echo $variable;
	                    ?>">
	                    <img src='<?php echo $rutaBloque . "/css/menuVertical/images/home.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Inicio</a></li>
                        <li id="menu-item-6">
	                    <a href="<?php
	                    $variable = "pagina=resultados"; 
	                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
	                    $variable.= "&tipo=1";
	                    $variable.= "&opcion=credencial";
	                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
	                    echo $variable;
	                    ?>">
	                        <img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
	                        Consultar Credencial
	                    </a>
	                </li>
                        <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Consultar listados
            		</a>
                        <ul>
                            <li id="menu-item-52">
                                <a href="<?php
                                $variable = "pagina=resultados"; //pendiente la pagina para modificar parametro
                                $variable.= "&usuario=".$miSesion->getSesionUsuarioId();
                                $variable.= "&tipo=1";
                                $variable.= "&opcion=facultades";
                                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                echo $variable;
                                ?>">Facultades </a>
                            </li>

                            <li id="menu-item-52">
                                <a href="<?php
                                $variable = "pagina=resultados"; //pendiente la pagina para modificar parametro
                                $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                                $variable.= "&tipo=1";
                                $variable.= "&opcion=IngFacTecnologica";    
                                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                echo $variable;
                                ?>">Prog. Ingenierías, Facultad tecnológica</a>
                            </li>
                            <li id="menu-item-52">
                                <a href="<?php
                                $variable = "pagina=resultados"; //pendiente la pagina para modificar parametro
                                $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                                $variable.= "&tipo=1";
                                $variable.= "&opcion=especiales";    
                                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                echo $variable;
                                ?>">Especiales</a>
                            </li>
                        </ul>
                        </li> 
                        <li id="menu-item-6">
                            <a href="<?php
                            $variable = "pagina=cerrarSesion"; //pendiente la pagina para modificar parametro
                            $variable.= "&sesionId=" . $miSesion->getSesionId();
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">
                                <img src='<?php echo $rutaBloque . "/css/menuVertical/images/salir.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                                Cerrar sesion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?
    }    
}
?>
