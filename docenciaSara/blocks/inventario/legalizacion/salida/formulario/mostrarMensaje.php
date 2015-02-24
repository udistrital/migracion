

<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarCatalogo";

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

$valorCodificado="pagina=salida";
$valorCodificado.="&opcion=nuevo";
$valorCodificado.="&bloque=".$esteBloque["nombre"];
$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

echo "<div class='" . $_REQUEST["error"] . "'>" . $_REQUEST["mensaje"] . "</div>";

//---------------Inicio Formulario (<form>)--------------------------------
$nombreFormulario = "salida";
$atributos["id"]=$nombreFormulario;
$atributos["tipoFormulario"]="multipart/form-data";
$atributos["metodo"]="POST";
$atributos["nombreFormulario"]=$nombreFormulario;
$verificarFormulario="1";
echo $this->miFormulario->formulario("inicio",$atributos);
unset($atributos);

//------------------Division para los botones-------------------------
$atributos["id"]="botones";
$atributos["estilo"]="marcoBotones";
echo $this->miFormulario->division("inicio",$atributos);

//-------------Control Boton-----------------------
$esteCampo="botonVolver";
$atributos["verificar"]="";
$atributos["tipo"]="boton";
$atributos["id"]=$esteCampo;
$atributos["cancelar"]="true";
$atributos["tabIndex"]=$tab++;
$atributos["valor"]=$this->lenguaje->getCadena($esteCampo);
$atributos["nombreFormulario"]=$nombreFormulario;
echo $this->miFormulario->campoBoton($atributos);
unset($atributos);
//-------------Fin Control Boton----------------------

//------------------Fin Division para los botones-------------------------
echo $this->miFormulario->division("fin");

//-------------Control cuadroTexto con campos ocultos-----------------------
//Para pasar variables entre formularios o enviar datos para validar sesiones
$atributos["id"]="formSaraData"; //No cambiar este nombre
$atributos["tipo"]="hidden";
$atributos["obligatorio"]=false;
$atributos["etiqueta"]="";
$atributos["valor"]=$valorCodificado;
echo $this->miFormulario->campoCuadroTexto($atributos);
unset($atributos);


//Fin del Formulario
echo $this->miFormulario->formulario("fin");

?>



<!--<form name="formulario" method="post" enctype="multipart/form-data">
    <div>

        <a href='
        <?php
//        $variable = "pagina=detallesCatalogo";
//        $variable .="&opcion=" . $_REQUEST["nombreTabla"];
//        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
//        echo $variable;
        ?>
           '>

            volver</a>




       
    </div> 
</form>-->
<!--<div class="exito">Mensaje de éxito de la operación realizada</div>
<div class="alerta">Mensaje de alerta que deseamos mostrar al usuario</div>  
<div class="error">Mensaje que informa al usuario sobre el error que se ha producido</div>  -->

