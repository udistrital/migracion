<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
?>
<div class="wrap">
    <div class="demo-container clear">
        <div class="dcjq-vertical-mega-menu">
            <ul id="mega-1" class="menu">
                <li id="menu-item-0"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_020_home.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Inicio</a></li> 
                <li id="menu-item-1"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_330_blog.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Catálogo</a>
                    <ul>
                        <li id="menu-item-12">
                            <a href="<?
                            $variable = "pagina=consultarCatalogo"; //pendiente la pagina para modificar parametro                                                        
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                             echo $variable;
                            ?>"> Consultar </a>
                        </li>                        
                    </ul>
                </li>
                <li id="menu-item-2"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_134_inbox_in.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Entrada</a>
                    <ul>
                        <li id="menu-item-12"><a href="<?
                            $variable = "pagina=consultarEntrada"; //pendiente la pagina para modificar parametro                                                        
                            $variable.="&opcion=filtrar";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                             echo $variable;
                            ?>"> Consultar </a></li>
                        <li id="menu-item-13"><a href="<?
                            $variable = "pagina=entrada"; 
                            //$variable.="&opcion=consultar";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Crear</a></li>
                        <li id="menu-item-14"><a href="<?
                            $variable = "pagina=entrada"; 
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>">Cambiar Estado</a></li>
                    </ul>
                </li>
                <li id="menu-item-3"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_135_inbox_out.png" ?>' width="15px" style="vertical-align:text-bottom;" > Salida</a>
                    <ul>
                        <li id="menu-item-13"><a href="<?
                            $variable = "pagina=salida"; //pendiente la pagina para modificar parametro                                                        
                            $variable.="&opcion=nuevo";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>"> Crear </a>
                            <ul>
                            </ul></li>
                        <li id="menu-item-12"><a href="#">Consultar</a>
                            <ul>
                            </ul></li>
                        <li id="menu-item-14"><a href="#">Cambiar Estado</a>
                            <ul>
                            </ul></li>
                    </ul>
                </li>
                <li id="menu-item-7"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_259_barcode.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Elemento</a>
                    <ul>
                        <li id="menu-item-12"><a href="<?
                            $variable = "pagina=consultarElemento"; //pendiente la pagina para modificar parametro                                                        
                            $variable.="&opcion=filtrar";
                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                            echo $variable;
                            ?>"> Consultar </a></li>
                        <li id="menu-item-13"><a href="#">Crear</a></li>
                        <li id="menu-item-14"><a href="#">Cambiar Estado</a></li>
                    </ul>
                </li>
                <li id="menu-item-4"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_083_random.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Transferencia</a>  
                    <ul>
                        <li id="menu-item-12"><a href="#">Blue Adidas Jacket</a></li>
                        <li id="menu-item-13"><a href="#">White Training Jacket</a></li>
                        <li id="menu-item-14"><a href="#">Red Adidas Jacket</a></li>
                    </ul>
                </li>
                <li id="menu-item-5"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_286_fabric.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Otros procesos</a> 
                    <ul>
                        <li id="menu-item-12"><a href="#">Blue Adidas Jacket</a></li>
                        <li id="menu-item-13"><a href="#">White Training Jacket</a></li>
                        <li id="menu-item-14"><a href="#">Red Adidas Jacket</a></li>
                    </ul>
                </li>
                <li id="menu-item-6"><a href="#"><img src='<?php echo $rutaBloque . "/css/menuVertical/images/glyphicons_029_notes_2.png" ?>' width="15px" style="vertical-align:text-bottom;" >  Reportes</a>
                    <ul>
                        <li id="menu-item-52"><a href="#">Shipping</a></li>
                        <li id="menu-item-53"><a href="#">Refunds/Returns</a></li>
                        <li id="menu-item-54"><a href="#">Payment</a></li>
                        <li id="menu-item-55"><a href="#">Terms &amp; Conditions</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
