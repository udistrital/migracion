<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

//
$variable['coordinador'] = 32768047;
$tab=1;


//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);

//-----------------Inicio de Conjunto de Controles----------------------------------------
$esteCampo="marcoDatosPeriodo";
$atributos["estilo"]="jqueryui";
$atributos["leyenda"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->marcoAGrupacion("inicio",$atributos);

$conexion="docente";
$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

$cadena_sql=$this->sql->cadena_sql("periodosActivos",$variable);
$resultado=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

$rutaDecod = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['data']);
         
$datos = explode('&', $rutaDecod);

//Usuario del coordinador
$usu = explode('=',$datos[2]);
$usuario = $usu[1];


//-------------Control cuadroTexto-----------------------
$esteCampo="identificacionFinal";
$atributos["id"]=$esteCampo;
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["valor"]="-1";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//-------------Control Lista Desplegable-----------------------
$esteCampo = "periodo";
$atributos["id"] = $esteCampo;
$atributos["tabIndex"] = $tab++;
$atributos["seleccion"] = -1;
$atributos["limitar"] = false;
$atributos["ancho"] = '150px';
$atributos["tamanno"] = 1;
$atributos["estilo"] = "jqueryui";
$atributos["conSeleccionar"] = true;
$atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
//-----De donde rescatar los datos ---------
$atributos["cadena_sql"] = $this->sql->cadena_sql("periodosActivos");
$atributos["baseDatos"] = "docente";
echo $this->miFormulario->campoCuadroLista($atributos);
unset($atributos);

//-------------Control cuadroTexto-----------------------
$esteCampo="nombreDoc";
$atributos["id"]=$esteCampo;
$atributos["etiqueta"]=$this->lenguaje->getCadena($esteCampo);
$atributos["titulo"]=$this->lenguaje->getCadena($esteCampo."Titulo");
$atributos["tabIndex"]=$tab++;
$atributos["obligatorio"]=true;
$atributos["tamanno"]="40";
$atributos["tipo"]="";
$atributos["estilo"]="jqueryui";
$atributos["validar"]="required";
$atributos["categoria"]="";
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);

//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);

?>

<input type="button" name="consultar" id="consultar" class="jqueryui" value="Consultar">
    
<?php
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin");

//Fin de Conjunto de Controles
echo $this->miFormulario->marcoAGrupacion("fin");



?>
<div name="divDatosDocente" id="divDatosDocente">
    <?php 
    
    
    
    ?>
</div>    
    
<?php

?>
<div name="divDatosProyecto" id="divDatosProyecto" style="display: none;">
    <?php 
    
    //-----------------Inicio de Conjunto de Controles----------------------------------------
    $esteCampo = "marcoDatosAsignatura";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);
    unset($atributos);


    //-------------Control Lista Desplegable-----------------------
    $esteCampo = "proyecto";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = -1;
    //------------ Control Asociado --------------------------------
    $atributos["evento"] = 2; //IMPORTANTE: evento=2 le indica que utilice ajax para recargar otro control
    //$atributos["ajaxControl"]="curso"; //Nombre del control asociado.
    $atributos["ajaxFunction"] = "poblarCurso()"; //Función Ajax asociada
    // -------------------------------------------------------------
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = '350px';
    $atributos["estilo"] = "jqueryui";
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("carreras", $variable['coordinador']);
    $atributos["baseDatos"] = "docente";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);


    //-------------Control Lista Desplegable-----------------------
    $esteCampo = "curso";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = -1;
    //------------ Control Asociado --------------------------------
    //$atributos["evento"]=2; //IMPORTANTE: evento=2 le indica que utilice ajax para recargar otro control
    //$atributos["ajaxControl"]="curso"; //Nombre del control asociado.
    //$atributos["ajaxFunction"]="poblarCurso()"; //Función Ajax asociada
    // -------------------------------------------------------------
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = '150px';
    $atributos["estilo"] = "jqueryui";
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    //-----De donde rescatar los datos ---------
    //$atributos["cadena_sql"]=$this->sql->cadena_sql("carreras",$variable['coordinador']);
    //$atributos["baseDatos"]="docente";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);


    //Fin de Conjunto de Controles
    echo $this->miFormulario->marcoAGrupacion("fin");
    
    ?>
</div>    
    
<div name="horario" id="horario" style="display: none;" class="jqueryui"> 
     <?php
         //-----------------Inicio de Conjunto de Controles----------------------------------------
    $esteCampo = "marcoDatosAsignacion";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);
    unset($atributos);
        
        ?>
    
    <div name="cuerpoAsignacion" id="cuerpoAsignacion" class="jqueryui">
        
    </div>
    <?php 
    //Fin de Conjunto de Controles
    echo $this->miFormulario->marcoAGrupacion("fin");
    ?>
    
    <?php
         //-----------------Inicio de Conjunto de Controles----------------------------------------
    $esteCampo = "marcoDatosHorario";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);
    unset($atributos);
        
        ?>
    <div name="cuerpoHorario" id="cuerpoHorario" class="jqueryui">
        
    </div>
    <?php 
    //Fin de Conjunto de Controles
    echo $this->miFormulario->marcoAGrupacion("fin");
    ?>
</div>

    <?php
    //Fin de Conjunto de Controles
    echo $this->miFormulario->marcoAGrupacion("fin");

//Fin del Formulario
echo $this->miFormulario->formulario("fin");


?>
