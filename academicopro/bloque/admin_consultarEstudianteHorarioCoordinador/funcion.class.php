
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");

//@ Esta clase se utiliza para el ingreso y validación de estudiante de posgrado para inscripcion por estudiante

class funcion_adminConsultarEstudianteHorarioCoordinador extends funcionGeneral {
    private $configuracion;

    //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
        include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        //$this->tema = $tema;
        $this->configuracion=$configuracion;
        $this->sql = new sql_adminConsultarEstudianteHorarioCoordinador();
        $this->log_us = new log();
        $this->validacion = new validarInscripcion();
        $this->formulario = "admin_consultarEstudianteHorarioCoordinador";

        //Conexion ORACLE
        $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");

        //Conexion General
        $this->acceso_db = $this->conectarDB($configuracion, "");
        $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

        #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
        $obj_sesion = new sesiones($configuracion);
        $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
        $this->id_accesoSesion = $this->resultadoSesion[0][0];

        //Datos de sesion
        $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->verificar = "control_vacio(" . $this->formulario . ",'codEstudiante')";
    }

#Cierre de constructor

    /*
     * Funcion que permite ingresar el codigo de estudiante a consultar
     *
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, nombreProyecto)
     */

    function consultarEstudiante() {
    ?>
    <style type="text/css" media="print">
    @media print {
    #parte1 {display:none;}
    #parte2 {display:none;}
    }
    </style>
    <div id="parte1">
        <table class='contenidotabla centrar'>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<? echo $this->formulario ?>'>
                <tr align="center">
                    <td class="centrar" colspan="4">
                        <h4>Digite el c&oacute;digo del estudiante que desea consultar</h4>
                    </td>
                </tr>
                <tr align="center">
                    <td class="centrar" colspan="4">
                        <input type="text" name="codEstudiante" size="11" maxlength="11">
                        <input type="hidden" name="opcion" value="validar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="hidden" name="codProyecto" value="<? echo (isset($_REQUEST['codProyecto'])?$_REQUEST['codProyecto']:'') ?>">
                        <input type="hidden" name="planEstudio" value="<? echo (isset($_REQUEST['planEstudio'])?$_REQUEST['planEstudio']:'') ?>">
                        <input type="hidden" name="nombreProyecto" value="<? echo (isset($_REQUEST['nombreProyecto'])?$_REQUEST['nombreProyecto']:'') ?>">
                        <input type="button" name="Consultar" value="Consultar" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario ?>'].submit()}else{false}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <hr align="center">
                    </td>
                </tr>
            </form>
        </table>
    </div>

        <?
    }

    /*
     * Función que valida si el estudiante pertenece al proyecto y plan de estudios seleccionado
     * pagina=>admin_consultarEstudianteHorarioCoordinador
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, nombreProyecto, codEstudiante)
     */

    function validarEstudiante() {

        $datosEstudiante = array('codEstudiante' => $_REQUEST['codEstudiante'],
            'planEstudio' => $_REQUEST['planEstudio']);
        if($this->nivel==4){
                $validacion = $this->validacion->validarEstudiante($datosEstudiante);
        }
        if($this->nivel==110||$this->nivel==114){
                $codEstudiante=$datosEstudiante['codEstudiante'];
                $validacion=$this->validacion->validarProyectoAsistente($codEstudiante,$this->usuario);

        }
        if (is_array($validacion)||$validacion=='ok') {

            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_consultarHorarioEstudianteCoordinador";
            $variable.="&opcion=mostrarConsulta";
            $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
            $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
            $variable.="&planEstudioGeneral=" . (isset($validacion['planEstudioEstudiante'])?$validacion['planEstudioEstudiante']:'');
            $variable.="&codProyectoEstudiante=" . (isset($validacion['codProyectoEstudiante'])?$validacion['codProyectoEstudiante']:'');
            $variable.="&planEstudioEstudiante=" . (isset($validacion['planEstudioEstudiante'])?$validacion['planEstudioEstudiante']:'');
            $variable.="&nombreProyecto=" . (isset($validacion['nombreProyecto'])?$validacion['nombreProyecto']:'');
            $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];

            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
            exit;
        } else {
            echo "<script>alert('" . $validacion . "')</script>";
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
            $variable = "pagina=admin_consultarEstudianteHorarioCoordinador";
            $variable.="&opcion=consultar";
            $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
            $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
            $variable.="&planEstudioGeneral=" . (isset($_REQUEST['planEstudioEstudiante'])?$_REQUEST['planEstudioEstudiante']:'');
            $variable.="&nombreProyecto=" . $_REQUEST['nombreProyecto'];

            include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
            $this->cripto = new encriptar();
            $variable = $this->cripto->codificar_url($variable, $this->configuracion);
            echo "<script>location.replace('" . $pagina . $variable . "')</script>";
        }
    }


}
?>
