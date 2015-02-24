<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloque_adminPromedioProyecto extends bloque {

    public function __construct($configuracion) {
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        $this->tema=$tema;
        $this->funcion=new funcion_adminPromedioProyecto($configuracion);
        $this->sql=new sql_adminPromedioProyecto();
    }


    function html($configuracion) {
        //$this->acceso_db=$this->funcion->conectarDB($configuracion);
        // @ Crear un objeto de la clase funcion


        if(!isset($_REQUEST['opcion'])) {
            $_REQUEST['opcion']="nuevo";
        }

        switch($_REQUEST['opcion']) {

            case "verProyectos":
                $this->funcion->verProyectos($configuracion);
                break;

            case "verPlanes":
                $this->funcion->verPlanes($configuracion);
                break;

            case "seleccionar":
                $this->funcion->seleccion($configuracion);
                break;

            case "mostrar":
                $this->funcion->mostrarEstudiantes($configuracion);
                break;

            case "inconsistencias":
                $this->funcion->estudiantesProblema($configuracion);
                break;

            case "reprobados":
                $this->funcion->estudiantesReprobados($configuracion);
                break;

            case "reporte":
                $this->funcion->reporte($configuracion);
                break;

            case "registrados":
                $this->funcion->seleccion($configuracion);
                break;

            default:
                $this->funcion->nuevoRegistro($configuracion);
                break;
        }
    }


    function action($configuracion) {

        switch($_REQUEST['opcion']) {

            case "mostrar":

                unset($_REQUEST['action']);

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminPromedioProyecto";
                $variable.="&opcion=mostrar";

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                break;


            case "reprobados":

                unset($_REQUEST['action']);

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminPromedioProyecto";
                $variable.="&opcion=reprobados";

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                break;

            case "registrados":

                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminPromedioProyecto";
                $variable.="&opcion=registrados";
                $variable.="&proyecto=".$_REQUEST["proyecto"];

//var_dump($_REQUEST);exit;
                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);

                echo "<script>location.replace('".$pagina.$variable."')</script>";
                break;
        }

    }


}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminPromedioProyecto($configuracion);

if(!isset($_REQUEST['action'])) {
    $esteBloque->html($configuracion);
}
else {
    $esteBloque->action($configuracion);
}


?>