<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlSalida extends sql {

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

            /**
             * Clausulas específicas
             */
            case "buscarEntrada":
                $cadena_sql = "SELECT ent_id, ent_id FROM entrada WHERE ";
                $cadena_sql.="TO_CHAR(ent_id, '9999') like '%" . strtoupper(trim($variable)) . "%' ";
                break;

            case "buscarElemento":
                $cadena_sql = "SELECT ele_id, ele_num_serie FROM elemento WHERE ele_estado = 'C' and ";
                $cadena_sql.="ele_num_serie like '%" . strtoupper(trim($variable)) . "%' ";
                break;
            
            case "buscarSedes":
                $cadena_sql = "SELECT sed_id, sed_nombre FROM sedes WHERE ";
                $cadena_sql.="upper(sed_nombre) like upper('%" . strtoupper(trim($variable)) . "%') ";
                break;
            
            case "buscarDependencias":
                $cadena_sql = "SELECT depen_id, descripcion FROM dependencias";
                $cadena_sql.=" WHERE upper(descripcion) like upper('%" . strtoupper(trim($variable)) . "%') ";
                break;
            
            case "buscarFuncionario":
                $cadena_sql = "SELECT fun_id, fun_nombre FROM funcionarios";
                $cadena_sql.="  WHERE upper(fun_nombre) like upper('%" . strtoupper(trim($variable)) . "%') ";
                break;
            
            case "consultarEntrada":
                $cadena_sql = "SELECT ent_id, ent_fecha, ent_concepto FROM entrada WHERE ent_id = ".strtoupper(trim($variable));
                break;
            
            case "consultarElemento":
                $cadena_sql = "SELECT ele_id, sum_nombre, ele_cantidad, mar_descripcion, ele_num_serie, 
                                      ele_descripcion, ele_precio, ele_codigo_barras, tbi_descripcion 
                                 FROM elemento, marca, suministros, tipo_bien
                                 WHERE 
                                      ele_id_suministro = sum_id and
                                      ele_id_marca = mar_id and 
                                      ele_id_tipo_bien = tbi_id and
                                      ele_num_serie = '".strtoupper(trim($variable))."'";
                break;
            
            case "insertarSalida":
                $cadena_sql="INSERT INTO salida(
                                sal_id, sal_id_entrada, sal_id_dep_destino, sal_id_tipo_salida, 
                                sal_observaciones, sal_estado, sal_fecha, sal_id_sede, sal_fecha_registro, sal_funcionario)
                            VALUES (".$variable["idSalida"].","                            
                            .$variable["idEntrada"].", "
                            ."(select depen_id from dependencias where descripcion = '".$variable["dependencia"]."' limit 1)"
                            .", null," 
                            ."'".$variable['observacion']."'"
                            .", 'C'".","
                            ."'".$variable['fechaSalida']."'"
                            .", (select sed_id from sedes where sed_nombre = '".$variable['sede']."' limit 1)"
                            .", '".$variable['fechaRegistro']."'"
                            .", (select fun_id from funcionarios where fun_nombre = '".$variable['funcionario']."' limit 1));";
                $cadena_sql .= "UPDATE elemento SET ele_id_salida=".$variable["idSalida"]." WHERE ele_id=".$variable['idElemento'].";";
                break;
            
            case "obtenerIdSalida":
                $cadena_sql = "SELECT max(ele_id_salida) 
                                FROM elemento, tipo_bien 
                                WHERE ele_id_tipo_bien = tbi_id and 
                                upper(tbi_descripcion) = upper('".strtoupper(trim($variable))."')";
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
