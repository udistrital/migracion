<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

if(!isset($_REQUEST['usuario']))
    {
        $variable['coordinador'] = 32768047;
    }else
        {
            $variable['coordinador'] = $_REQUEST['usuario'];
        }

$tab=1;
//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);

$conexion="coordinador";
$esteRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

/*$cadenaEjecutar = "INSERT INTO MNTAC.ACCARGAS (CAR_ID, CAR_HOR_ID, CAR_DOC_NRO, CAR_TIP_VIN, CAR_ESTADO) VALUES (null, 13908, 14204298, 4, 'A')";
$resultado=$esteRecursoDB->ejecutarAcceso($cadenaEjecutar,"accion");
var_dump($resultado);*/
?>
<div name="divDatosProyectoTabCurso" id="divDatosProyectoTabCurso">
    <?php 
    
    //-----------------Inicio de Conjunto de Controles----------------------------------------
    $esteCampo = "marcoDatosAsignaturaCurso";
    $atributos["estilo"] = "jqueryui";
    $atributos["leyenda"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->marcoAGrupacion("inicio", $atributos);
    unset($atributos);

    $cadena_sql=$this->sql->cadena_sql("periodosActivos",$variable);
    $resultado=$esteRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

    $rutaDecod = $this->miConfigurador->fabricaConexiones->crypto->decodificar($_REQUEST['data']);

    $datos = explode('&', $rutaDecod);

    //Usuario del coordinador
    $usu = explode('=',$datos[2]);
    $usuario = $usu[1];

    //-------------Control Lista Desplegable-----------------------
    $esteCampo = "periodoTabCurso";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = -1;
    $atributos["limitar"] = 1;
    $atributos["ancho"] = '150px';
    $atributos["tamanno"] = 1;
    $atributos["estilo"] = "jqueryui";
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("periodosActivos");
    $atributos["baseDatos"] = "coordinador";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);

    //-------------Control Lista Desplegable-----------------------
    $esteCampo = "proyectoTabCurso";
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["seleccion"] = -1;
    //------------ Control Asociado --------------------------------
    $atributos["evento"] = 2; //IMPORTANTE: evento=2 le indica que utilice ajax para recargar otro control
    //$atributos["ajaxControl"]="curso"; //Nombre del control asociado.
    $atributos["ajaxFunction"] = "poblarCursosTodos()"; //Función Ajax asociada
    // -------------------------------------------------------------
    $atributos["limitar"] = false;
    $atributos["tamanno"] = 1;
    $atributos["ancho"] = '350px';
    $atributos["estilo"] = "jqueryui";
    $atributos["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
    //-----De donde rescatar los datos ---------
    $atributos["cadena_sql"] = $this->sql->cadena_sql("carreras", $variable['coordinador']);
    $atributos["baseDatos"] = "coordinador";
    echo $this->miFormulario->campoCuadroLista($atributos);
    unset($atributos);
    
    ?>
    
    <div name="cursosTodos" id="cursosTodos" class="jqueryui"> 
        
    </div>     
    
    <?php
    $esteCampo='enlaceVideoGestionarCarga';
    $atributos["id"] = $esteCampo;
    $atributos["tabIndex"] = $tab++;
    $atributos["enlace"] = 'https://drive.google.com/file/d/0BzG7rdBcnWhoeUpRQU5sbGRmN1k/preview';
    $atributos["estilo"] = "jqueryui";
    $atributos["enlaceTexto"] = $this->lenguaje->getCadena($esteCampo);
    echo "<br><br><br><br><br><br><br><br>";
    echo $this->miFormulario->enlace($atributos);
    unset($atributos);
    
    //Fin de Conjunto de Controles
    echo $this->miFormulario->marcoAGrupacion("fin");
    
    ?>
</div>    
 
    <?php
  

//Fin del Formulario
echo $this->miFormulario->formulario("fin");


?>
