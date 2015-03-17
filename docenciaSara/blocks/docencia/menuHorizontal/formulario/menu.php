<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
$rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
?>
<ul id="menu">
    <li>
        <a id="shHome" class="SiteHeaderHome" href="http://www.udistrital.edu.co" ></a>
    </li>
    <li><a href="#" class="drop">Servicios</a><!-- Begin Home Item -->
    
        
    
    </li><!-- End Home Item -->

    <li><a href="#" class="drop">Acad&eacute;micos</a><!-- Begin 5 columns Item -->
    
        <div class="dropdown_4columns"><!-- Begin 4 columns container -->
        
            <div class="col_4">
                <h2>Descripci&oacute;n servicios Acad&eacute;micos</h2>
            </div>
            
            <div class="col_1">
            
                <h3>Docente</h3>
                <ul>
                    <li><a href="#">Registro Notas</a></li>
                    <li><a href="#">Consejerias</a></li>
                    <li><a href="#">Listas</a></li>
                    <li><a href="#">Materias</a></li>
                    <li><a href="#">Prueba</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Administraci&oacute;n Docente</h3>
                <ul>
                    <li><a href="#">Estado de Cuenta</a></li>
                    <li><a href="#">Evaluaci&oacute;n Docente</a></li>
                    <li><a href="#">PsdTuts</a></li>
                    <li><a href="#">PhotoTuts</a></li>
                    <li><a href="#">ActiveTuts</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Other Stuff</h3>
                <ul>
                    <li><a href="#">FreelanceSwitch</a></li>
                    <li><a href="#">Creattica</a></li>
                    <li><a href="#">WorkAwesome</a></li>
                    <li><a href="#">Mac Apps</a></li>
                    <li><a href="#">Web Apps</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Misc</h3>
                <ul>
                    <li><a href="#">Design</a></li>
                    <li><a href="#">Logo</a></li>
                    <li><a href="#">Flash</a></li>
                    <li><a href="#">Illustration</a></li>
                    <li><a href="#">More...</a></li>
                </ul>   
                 
            </div>
            
        </div><!-- End 4 columns container -->
    
    </li><!-- End 5 columns Item -->

    <li><a href="#" class="drop">Administrativos</a><!-- Begin 4 columns Item -->
    
        <div class="dropdown_4columns"><!-- Begin 4 columns container -->
        
            <div class="col_4">
                <h2>This is a heading title</h2>
            </div>
            
            <div class="col_1">
            
                <h3>Some Links</h3>
                <ul>
                    <li><a href="#">ThemeForest</a></li>
                    <li><a href="#">GraphicRiver</a></li>
                    <li><a href="#">ActiveDen</a></li>
                    <li><a href="#">VideoHive</a></li>
                    <li><a href="#">3DOcean</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Useful Links</h3>
                <ul>
                    <li><a href="#">NetTuts</a></li>
                    <li><a href="#">VectorTuts</a></li>
                    <li><a href="#">PsdTuts</a></li>
                    <li><a href="#">PhotoTuts</a></li>
                    <li><a href="#">ActiveTuts</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Other Stuff</h3>
                <ul>
                    <li><a href="#">FreelanceSwitch</a></li>
                    <li><a href="#">Creattica</a></li>
                    <li><a href="#">WorkAwesome</a></li>
                    <li><a href="#">Mac Apps</a></li>
                    <li><a href="#">Web Apps</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Misc</h3>
                <ul>
                    <li><a href="#">Design</a></li>
                    <li><a href="#">Logo</a></li>
                    <li><a href="#">Flash</a></li>
                    <li><a href="#">Illustration</a></li>
                    <li><a href="#">More...</a></li>
                </ul>   
                 
            </div>
            
        </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->

    <li><a href="#" class="drop">Financieros</a><!-- Begin 4 columns Item -->
    
        <div class="dropdown_4columns"><!-- Begin 4 columns container -->
        
            <div class="col_4">
                <h2>This is a heading title</h2>
            </div>
            
            <div class="col_1">
            
                <h3>Some Links</h3>
                <ul>
                    <li><a href="#">ThemeForest</a></li>
                    <li><a href="#">GraphicRiver</a></li>
                    <li><a href="#">ActiveDen</a></li>
                    <li><a href="#">VideoHive</a></li>
                    <li><a href="#">3DOcean</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Useful Links</h3>
                <ul>
                    <li><a href="#">NetTuts</a></li>
                    <li><a href="#">VectorTuts</a></li>
                    <li><a href="#">PsdTuts</a></li>
                    <li><a href="#">PhotoTuts</a></li>
                    <li><a href="#">ActiveTuts</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Other Stuff</h3>
                <ul>
                    <li><a href="#">FreelanceSwitch</a></li>
                    <li><a href="#">Creattica</a></li>
                    <li><a href="#">WorkAwesome</a></li>
                    <li><a href="#">Mac Apps</a></li>
                    <li><a href="#">Web Apps</a></li>
                </ul>   
                 
            </div>
    
            <div class="col_1">
            
                <h3>Misc</h3>
                <ul>
                    <li><a href="#">Design</a></li>
                    <li><a href="#">Logo</a></li>
                    <li><a href="#">Flash</a></li>
                    <li><a href="#">Illustration</a></li>
                    <li><a href="#">More...</a></li>
                </ul>   
                 
            </div>
            
        </div><!-- End 4 columns container -->
    
    </li><!-- End 4 columns Item -->
	<li><a href="#" class="drop">Favoritos</a>
    
		<div class="dropdown_1column align_right">
        
                <div class="col_1">
                
                    <ul class="simple">
                        <li><a href="#">FreelanceSwitch</a></li>
                        <li><a href="#">Creattica</a></li>
                        <li><a href="#">WorkAwesome</a></li>
                        <li><a href="#">Mac Apps</a></li>
                        <li><a href="#">Web Apps</a></li>
                        <li><a href="#">NetTuts</a></li>
                        <li><a href="#">VectorTuts</a></li>
                        <li><a href="#">PsdTuts</a></li>
                        <li><a href="#">PhotoTuts</a></li>
                        <li><a href="#">ActiveTuts</a></li>
                        <li><a href="#">Design</a></li>
                        <li><a href="#">Logo</a></li>
                        <li><a href="#">Flash</a></li>
                        <li><a href="#">Illustration</a></li>
                        <li><a href="#">More...</a></li>
                    </ul>   
                     
                </div>
                
		</div>
        
	</li>

    

</ul>
