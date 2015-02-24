<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqldetallesCatalogo extends sql {

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
            case 'vercrearParametro':
                $cadena_sql = "SELECT * FROM " . $variable;
                break;
            
            case 'verDetallesCamposTabla':
                $cadena_sql = "SELECT tbl_columnas, ";
                $cadena_sql .= "tbl_modificable, ";
                $cadena_sql .= "tbl_consulta_detalles, ";
                $cadena_sql .= "tbl_nombre_tabla ";
                $cadena_sql .= "FROM tablas ";
                $cadena_sql .= "where tbl_nombre_tabla_bd ='" . $variable . "'";
                break;
            
            case 'obtenerConsultaEliminacion':
                $cadena_sql = "SELECT tbl_script_delete FROM tablas where tbl_nombre_tabla_bd ='" . $variable . "'";
                break;
            
            case 'obtenerLabelsCombo':
                $cadena_sql = "SELECT tbl_tabla_relacion, tbl_label_relacion FROM tablas where tbl_nombre_tabla_bd ='" . $variable . "'";
                break;
            
            case 'verLabelsTabla':
                $cadena_sql = "SELECT tbl_columnas FROM tablas where tbl_nombre_tabla_bd ='" . $variable . "'";
                break;
            
             case 'consultarValoresTablaGeneral':
                $cadena_sql = "SELECT DISTINCT * FROM tablas where tbl_nombre_tabla_bd = '" .
                        $variable . "' AND tbl_modificable = true";
                break;
            
             case 'consultarValoresCombo':
                $cadena_sql = "SELECT cmb_script FROM combo WHERE cmb_tabla = '" . $variable . "'";
                break;
            
            case 'obtenerScriptInsertarDatos':
                $cadena_sql = "SELECT tbl_script_insert FROM tablas where upper(tbl_nombre_tabla_bd) =upper('" . $variable . "')";
                break;
            
            case 'obtenerScriptActualizarDatos':
                $cadena_sql = "SELECT tbl_script_update FROM tablas where upper(tbl_nombre_tabla_bd) =upper('" . $variable . "')";
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
