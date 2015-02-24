<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlConsultaCenso extends sql {

    var $miConfigurador;

    function __construct() {
        $this->miConfigurador = Configurador::singleton();
    }

    function cadena_sql($tipo, $variable = "") {

        /**
         * 1. Revisar las variables para evitar SQL Injection
         *
         */
        $prefijo = $this->miConfigurador->getVariableConfiguracion("prefijo");
        $idSesion = $this->miConfigurador->getVariableConfiguracion("id_sesion");
        $idSesion = "123";

        switch ($tipo) {

            case "buscarTipoDocumento":
                $cadena_sql = "SELECT DISTINCT censo_tipo_doc_graduado id,censo_tipo_doc_graduado tipo FROM censo";
                break;

            case "consultarCenso":
                $cadena_sql = "SELECT censo_id_registro, censo_tipo_doc_graduado, censo_num_doc_graduado, 
                                    censo_tipo_doc_actual, censo_num_doc_actual, censo_nombre, censo_correo, 
                                    censo_correo_ud, censo_tel_fijo, censo_tel_celular, censo_direccion, 
                                    censo_cod_estudiante, censo_facultad_graduado, censo_carrera_graduado, 
                                    censo_ano_graduado, censo_periodo_graduacion, censo_clave, censo_habilitado, 
                                    censo_estado, censo_tipo, censo_votacion, v.votacion_nombre, v.votacion_mensaje
                             FROM censo c,
                             votacion v
                             WHERE c.censo_num_doc_graduado = " . $variable . "
                             and c.censo_votacion = v.votacion_id";
                break;

            case "actualizarCenso":
                $cadena_sql = "UPDATE censo
                               SET 
                               censo_tipo_doc_graduado='" . $_REQUEST['tipoDocAnterior'] . "', 
                               censo_num_doc_graduado=" . $_REQUEST['numeroDocAnterior'] . ",
                               censo_tipo_doc_actual='" . $_REQUEST['tipoDocNuevo'] . "', 
                               censo_num_doc_actual=" . $_REQUEST['numeroDocNuevo'] . ", 
                               censo_nombre='" . $_REQUEST['nombre'] . "',
                               censo_correo='" . $_REQUEST['correoPrincipal'] . "', 
                               censo_correo_ud='" . $_REQUEST['correoInstitucional'] . "', 
                               censo_tel_fijo=" . $_REQUEST['telefonoFijo'] . ", 
                               censo_tel_celular=" . $_REQUEST['telefonoCelular'] . ",
                               censo_direccion='" . $_REQUEST['direccion'] . "',
                               censo_cod_estudiante=" . $_REQUEST['codigo'] . ", 
                               censo_facultad_graduado='" . $_REQUEST['facultad'] . "',
                               censo_carrera_graduado='" . $_REQUEST['carrera'] . "', 
                               censo_ano_graduado=" . $_REQUEST['anoGraduado'] . ", 
                               censo_periodo_graduacion=" . $_REQUEST['periodoGraduado'] . ",
                               censo_fecha_actualizacion=".date("d-M-Y  h:i:s A")."    
                               censo_clave= md5('" . $_REQUEST["clave"]."') " . "
                             WHERE censo_id_registro=" . $_REQUEST['idRegistro'];
                break;

            case "iniciarTransaccion":
                $cadena_sql = "START TRANSACTION";
                break;

            case "finalizarTransaccion":
                $cadena_sql = "COMMIT";
                break;

            case "cancelarTransaccion":
                $cadena_sql = "ROLLBACK";
                break;


            case "eliminarTemp":

                $cadena_sql = "DELETE ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$prefijo . "tempFormulario ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="id_sesion = '" . $variable . "' ";
                break;

            case "insertarTemp":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.=$prefijo . "tempFormulario ";
                $cadena_sql.="( ";
                $cadena_sql.="id_sesion, ";
                $cadena_sql.="formulario, ";
                $cadena_sql.="campo, ";
                $cadena_sql.="valor, ";
                $cadena_sql.="fecha ";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";

                foreach ($_REQUEST as $clave => $valor) {
                    $cadena_sql.="( ";
                    $cadena_sql.="'" . $idSesion . "', ";
                    $cadena_sql.="'" . $variable['formulario'] . "', ";
                    $cadena_sql.="'" . $clave . "', ";
                    $cadena_sql.="'" . $valor . "', ";
                    $cadena_sql.="'" . $variable['fecha'] . "' ";
                    $cadena_sql.="),";
                }

                $cadena_sql = substr($cadena_sql, 0, (strlen($cadena_sql) - 1));
                break;

            case "rescatarTemp":
                $cadena_sql = "SELECT ";
                $cadena_sql.="id_sesion, ";
                $cadena_sql.="formulario, ";
                $cadena_sql.="campo, ";
                $cadena_sql.="valor, ";
                $cadena_sql.="fecha ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$prefijo . "tempFormulario ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="id_sesion='" . $idSesion . "'";
                break;
        }
        return $cadena_sql;
    }

}

?>
