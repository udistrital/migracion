<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];

$conexion = "funcionario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
                                        
if (!$esteRecursoDB) {

    echo "//Este se considera un error fatal";
    exit;
}
$variable['anio']=$_REQUEST['anio'];
$variable['usuario']=$_REQUEST['usuario'];

$cadena_sql = $this->sql->cadena_sql("certificado", $variable);
$registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//echo $cadena_sql."<br>";

if(is_array($registro))
{    
    //echo "mmm".$registro[0][0];
    $contenido = "
    <page>
        <h1>Exemple d'utilisation</h1>
        <br>
        Ceci est un <b>exemple d'utilisation</b>
        de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
    </page>";
    
    $rutaClases=$this->miConfigurador->getVariableConfiguracion("raizDocumento")."/classes";
    include_once($rutaClases."/html2pdf/html2pdf.class.php");
    //require_once('clase/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','A4','fr');
    $html2pdf->WriteHTML($contenido);
    $html2pdf->Output('exemple.pdf');
}
else
{
    $atributos["id"]="divNoEncontroRegistro";
    $atributos["estilo"]="marcoBotones";
    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio",$atributos);

    //-------------Control Boton-----------------------
    $esteCampo = "noEncontroRegistro";
    $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
    $atributos["etiqueta"] = "";
    $atributos["estilo"] = "centrar";
    $atributos["tipo"] = 'error';
    $atributos["mensaje"] = $this->lenguaje->getCadena($esteCampo);;
    echo $this->miFormulario->cuadroMensaje($atributos);
     unset($atributos); 
    //-------------Fin Control Boton----------------------

    //------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");
}





