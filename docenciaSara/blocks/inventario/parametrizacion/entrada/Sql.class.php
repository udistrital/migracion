<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlEntrada extends sql {

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

        switch ($tipo) {

            /**
             * Clausulas específicas
             */
            case "buscarProveedor":
                $cadena_sql = "SELECT ";
                $cadena_sql.="pro_id, ";
                $cadena_sql.="pro_razon_social ";
                $cadena_sql.="FROM ";
                $cadena_sql.="proveedor ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="pro_razon_social like '%" . strtoupper(trim($variable)) . "%' ";
                break;

            case "buscarSedes":
                $cadena_sql = "SELECT ";
                $cadena_sql.="pro_id, ";
                $cadena_sql.="pro_razon_social ";
                $cadena_sql.="FROM ";
                $cadena_sql.="proveedor ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="pro_razon_social like '%" . strtoupper(trim($variable)) . "%' ";
                break;

            case "buscarOrdenadorGasto":
                $cadena_sql = "SELECT ";
                $cadena_sql.="ord_id, ";
                $cadena_sql.="ord_numero_doc ";
                $cadena_sql.="FROM ";
                $cadena_sql.="ordenador_gasto ";
                break;

            case "buscarDependenciaSupervisora":
                $cadena_sql = "SELECT ";
                $cadena_sql.="depen_id, ";
                $cadena_sql.="descripcion ";
                $cadena_sql.="FROM ";
                $cadena_sql.="dependencias ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="descripcion like '%" . strtoupper(trim($variable)) . "%' ";
                break;

            case "buscarIva": //Recordar que aqui va lo de IVA el bruto que hizo esto no se dió cuenta que era una lista desplegable...
                $cadena_sql = "SELECT ";
                $cadena_sql.="depen_id, ";
                $cadena_sql.="descripcion ";
                $cadena_sql.="FROM ";
                $cadena_sql.="dependencias ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="descripcion like '%" . strtoupper(trim($variable)) . "%' ";
                break;

            case "buscarTipoEntrada":
                $cadena_sql = "SELECT ";
                $cadena_sql.="cla_id, ";
                $cadena_sql.="cla_descripcion ";
                $cadena_sql.="FROM ";
                $cadena_sql.="clase_entrada ";
                $cadena_sql.="ORDER BY ";
                $cadena_sql.="cla_id ";
                break;


            case "insertarRegistro":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.=$prefijo . "registradoConferencia ";
                $cadena_sql.="( ";
                $cadena_sql.="`idRegistrado`, ";
                $cadena_sql.="`nombre`, ";
                $cadena_sql.="`apellido`, ";
                $cadena_sql.="`identificacion`, ";
                $cadena_sql.="`codigo`, ";
                $cadena_sql.="`correo`, ";
                $cadena_sql.="`tipo`, ";
                $cadena_sql.="`fecha` ";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="NULL, ";
                $cadena_sql.="'" . $variable['nombre'] . "', ";
                $cadena_sql.="'" . $variable['apellido'] . "', ";
                $cadena_sql.="'" . $variable['identificacion'] . "', ";
                $cadena_sql.="'" . $variable['codigo'] . "', ";
                $cadena_sql.="'" . $variable['correo'] . "', ";
                $cadena_sql.="'0', ";
                $cadena_sql.="'" . time() . "' ";
                $cadena_sql.=")";
                break;


            case "actualizarRegistro":
                $cadena_sql = "UPDATE ";
                $cadena_sql.=$prefijo . "conductor ";
                $cadena_sql.="SET ";
                $cadena_sql.="`nombre` = '" . $variable["nombre"] . "', ";
                $cadena_sql.="`apellido` = '" . $variable["apellido"] . "', ";
                $cadena_sql.="`identificacion` = '" . $variable["identificacion"] . "', ";
                $cadena_sql.="`telefono` = '" . $variable["telefono"] . "' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="`idConductor` =" . $_REQUEST["registro"] . " ";
                break;


            /**
             * Clausulas genéricas. se espera que estén en todos los formularios
             * que utilicen esta plantilla
             */
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
