<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
$miSesion = Sesion::singleton();

?>
<div class="wrap">
    <div class="demo-container clear">
        <div class="dcjq-vertical-mega-menu">
            <ul id="mega-1" class="menu">
                <li id="menu-item-0">
                    <a href="<?
                    $variable = "pagina=indexEvaldocentes"; //pendiente la pagina para modificar parametro
                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                    echo $variable;
                    ?>">
                    <img src='<?php echo $rutaBloque . "/css/menuVertical/images/home.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Inicio</a></li> 
                
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Habilitar Proceso
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=habilitarEvaluacion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=".$miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Habilitar Periodo </a>
                        </li>
                      
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=habilitarEvaluacion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=eventos";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Habilitar Eventos </a>
                        </li>
                    </ul>
                </li>
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Editar Instructivo
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=editarInstructivo"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&tipoEvaluacion=1";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Estudiante </a>
                        </li>
                      
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=editarInstructivo"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&tipoEvaluacion=2";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Autoevaluación </a>
                        </li>
                    
                         <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=editarInstructivo"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&tipoEvaluacion=3";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Consejo Curricular </a>
                        </li>    
                    </ul>
                </li>
               <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Procesar Formularios
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=formatos";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Reg. Formatos </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=asociarFormatos";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Asociar Formatos </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=encabezados";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Reg. Encabezados </a>
                        </li>
                    
                         <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=preguntas";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Reg. Preguntas </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=armarFormularios"; //pendiente la pagina para modificar parametro                                                        
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Armar formularios</a>
                        </li>
                    </ul>
                </li>   
                
                <li id="menu-item-6">
                    <a href="<?
                    $variable = "pagina=evaluacionesExtemporaneas"; //pendiente la pagina para modificar parametro
                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                    $variable.= "&tipo=88";
                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                    echo $variable;
                    ?>">
                        <img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                        Ev. extempor&aacute;neas
                    </a>
                </li>
                 <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Reportes
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="intelligentia.udistrital.edu.co:8080/SpagoBI/">Ver Reportes</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=resultados";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Ver Resultados</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=resultadosCatedras";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Ver Resultados cátedras</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=tiposEvaluacion";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Docentes sin evaluación </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=listaFacultades";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Estudiantes sin evaluar</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=evaluacionDocente"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=88";
                            $variable.= "&opcion=observacionesEstudiantes";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Observaciones estudiantes</a>
                        </li>
                    </ul>
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
                </li>   
                <li id="menu-item-6">
                    <a href="<?
                    /*$variable = "pagina=cerrarSesion"; //pendiente la pagina para modificar parametro
                    $variable.= "&sesionId=" . $miSesion->getSesionId();
                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                    echo $variable;*/
                    ?>">
                        <img src='<?//php echo $rutaBloque . "/css/menuVertical/images/salir.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                        Cerrar sesion
                    </a>
                </li-->
            </ul>
        </div>
    </div>
</div>
