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

echo "<div class='" . $error . "'>" . $mensaje . "</div>";
?>

<form name="formulario" method="post" enctype="multipart/form-data">
    <div>

        <a href='
        <?php
        $variable = "pagina=detallesCatalogo";
        $variable .="&opcion=" . $_REQUEST["nombreTabla"];
        $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
        echo $variable;
        ?>
           '>

            volver</a>




        <?
        /****esto funciona pero se dejo asi para que no salga la variable formSaraData ******/
//        $valorCodificado = "pagina=detallesCatalogo";
//        $valorCodificado.="&opcion=" . $_REQUEST["nombreTabla"];
//        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
//        $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
        ?> 
        <!--<input type='hidden' name='formSaraData' value='<? // echo $valorCodificado; ?>'>-->
        <!--<input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="volver" value='Volver'/>-->                        
    </div> 
</form>
<!--<div class="exito">Mensaje de éxito de la operación realizada</div>
<div class="alerta">Mensaje de alerta que deseamos mostrar al usuario</div>  
<div class="error">Mensaje que informa al usuario sobre el error que se ha producido</div>  -->

