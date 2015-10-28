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
                    $variable = "pagina=indexAdminAdmisiones"; //pendiente la pagina para modificar parametro
                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                    echo $variable;
                    ?>">
                    <img src='<?php echo $rutaBloque . "/css/menuVertical/images/home.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Inicio</a></li> 
                
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Habilitar Módulo
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=".$miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Habilitar Periodo </a>
                        </li>
                      
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=eventos";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Habilitar Eventos </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=medios";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Actualizar Medio </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=salmin";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Actualizar Salario Mínimo</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=localidades";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Actualizar Localidades</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=estratos";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Actualizar Estratos</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=habilitarCarreras";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Habilitar Carreras</a>
                        </li>
                    </ul>
                </li>
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Gestionar Inscripción
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=instructivo";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Instructivo </a>
                        </li>
                      
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=colillas";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Colillas </a>
                        </li>
                    
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=registrarPines";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Registrar PINES</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=registrarIcfes";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Registrar ICFES</a>
                        </li>                        
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=registarTipInscripcion";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Registrar tipos de inscripción</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=registrarTipDiscapacidad";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Registrar tipos discapacidad</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=registrarDocumentacion";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Registrar documentación</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=editarInscripcion";    
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Editar Inscripción</a>
                        </li>
                    </ul>
                </li>
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/edit.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Gestionar Formulario
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=registrarPreguntas";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Registrar preguntas </a>
                        </li>
                      
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=registrarEncabezados";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Registrar encabezados </a>
                        </li>
                    </ul>
                </li>
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/resultado.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Gestionar Resultados
            		</a>
                    <ul>
                    	<li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=calcularResultados";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Calcular resultados </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=subirPdf";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Subir PDF </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=subirPdfEspeciales";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Subir PDF especiales </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=marcarAdmitidosRangos";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Marcar admitidos - rangos</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=marcarAdmitidosCredencial";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Marcar admitidos - credencial</a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=cargarAdmitidos";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Cargar admitidos - archivo </a>
                        </li>
                    </ul>
                </li>
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/consultar.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Consultas
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=referenciaBancaria";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Referencia bancaria </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=snpAspirantes";
                            $variable.= "&tipoInscripcion=nuevos";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">SNP Aspirantes </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=snpAspirantes";
                            $variable.= "&tipoInscripcion=transferencia";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">SNP Transferencia </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=inscritosxFacultad";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Inscritos X facultad </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=consultaxcarrera";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Consulta x Carrera </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=consultaEspecialesxcarrera";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Consulta Especiales x Carrera </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=administracion"; 
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=consultaInscripciones";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Inscripciones </a>
                        </li>
                    </ul>
                </li>
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/rports.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Reportes
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=reportes"; 
                            $variable.= "&informes=admin";
                            $variable.= "&acceso=";
                            $variable.= "&tipo=33";
                            $variable.= "&opcion=reportico";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>" target="_blank">Admin Reportes </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=reportes"; 
                            $variable.= "&informes=admisiones";
                            $variable.= "&acceso=prueba";
                            $variable.= "&reporte=inscritos";
                            $variable.= "&opcion=reportico";
                            $variable.= "&tipo=33";
                            
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>" target="_blank">Inscritos por carrera </a>
                        </li>
                        <li id="menu-item-52">
                            <a href="<?php
                            $variable = "pagina=reportes"; 
                            $variable.= "&informes=admisiones";
                            $variable.= "&acceso=prueba";
                            $variable.= "&reporte=especiales";
                            $variable.= "&opcion=reportico";
                            $variable.= "&tipo=33";
                            
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>" target="_blank">Inscripciones especiales </a>
                        </li>
                    </ul>
                </li>
                <!--li id="menu-item-0">
                    <a href="<?
                    /*$variable = "pagina=administracion"; //pendiente la pagina para modificar parametro
                    $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                    $variable.= "&tipo=33";
                    $variable.= "&opcion=copiarInscripciones";
                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                    echo $variable;
                    ?>">
                    <img src='<?php echo $rutaBloque . "/css/menuVertical/images/copiar.png" ?>' width="15px" style="vertical-align:text-bottom;" > Copiar</a>
                </li> 
                <li id="menu-item-6">
                	<a href="#">
                		<img src='<?php echo $rutaBloque . "/css/menuVertical/images/security.png" ?>' width="15px" style="vertical-align:text-bottom;" > 
                		Seguridad
            		</a>
                    <ul>
                        <li id="menu-item-52">
                            <a href="<?
                            $variable = "pagina=cambiarClaveSoporte"; //pendiente la pagina para modificar parametro                                                        
                            $variable.= "&usuario=" . $miSesion->getSesionUsuarioId();
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;*/
                            ?>"> Clave de acceso </a>
                        </li>                 
                    </ul>
                </li-->   
            </ul>
        </div>
    </div>
</div>
