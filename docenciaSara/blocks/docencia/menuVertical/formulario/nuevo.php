<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

if(isset($_REQUEST['usuario']) && $_REQUEST['usuario']!='')
    {
        $usuario = $_REQUEST['usuario'];
    }else
        {
            $rutaDecod = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['data']);

            $datos = explode('&', $rutaDecod);

            $usu = explode('=',$datos[3]);
            $usuario = $usu[1];
        }


?>
<div class="wrap">
    <div class="demo-container clear">
        <div class="dcjq-vertical-mega-menu">
            <ul id="mega-1" class="menu">
                <!--<li id="menu-item-0"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/home.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Inicio</a></li> -->
               
                <li id="menu-item-1"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/pdf.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Reportes</a>
                    <ul>
                        <li id="menu-item-2">
                            <?php
                            $variable = "pagina=estadoCuenta";
                            $variable .= "&opcion=formReporte";
                            $variable .= "&usuario=".$usuario;
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                             
                            ?>
                            <a href="<?php echo $variable;?>">Estado de Cuenta</a>
                        </li>                        
                    </ul>                    
                </li>
                <!--<li id="menu-item-1"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/pdf.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Carga Docente</a>
                    <ul>
                        <li id="menu-item-2">
                            <?php
                            $variable = "pagina=cargaDocente";
                            $variable .= "&opcion=nuevo";
                            $variable .= "&identificacion=32768047";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                             
                            ?>
                            <a href="<?php echo $variable;?>">Carga Docente</a>
                        </li>
                    </ul> 
                </li>-->
                <!--<li id="menu-item-0"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/exit.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Salir</a></li> -->
            </ul>
        </div>
    </div>
</div>
