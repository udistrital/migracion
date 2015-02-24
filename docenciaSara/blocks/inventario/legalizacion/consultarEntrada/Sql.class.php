<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlconsultarEntrada extends sql {

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
            case "consultarEntradas":
                
                $cadena_sql = "SELECT ";
                $cadena_sql.="ent_id ENTRADA, ";
                $cadena_sql.="ent_fecha FECHA_ENTRADA, ";
                $cadena_sql.="ent_observaciones OBSERVACION, ";
                $cadena_sql.="pro_id ID_PROVEEDOR, ";
                $cadena_sql.="pro_nit NIT, ";
                $cadena_sql.="pro_razon_social PROVEEDOR, ";
                $cadena_sql.="cla_id CLASE_ID, ";
                $cadena_sql.="cla_descripcion CLASE, ";
                $cadena_sql.="ent_clase_fecha CLASE_FECHA, ";
                $cadena_sql.="(fact_numero || ' del ' || fact_fecha) CONCEPTO, ";
                $cadena_sql.="EST.descripcion ESTADO, ";
                $cadena_sql.="fact_numero FACTURA, ";
                $cadena_sql.="fact_fecha FACTURA_FEC, ";
                $cadena_sql.="fact_valor_neto NETO, ";
                $cadena_sql.="fact_valor_iva IVA, ";
                $cadena_sql.="fact_total_factura TOTAL, ";
                $cadena_sql.="ord_id ID_ORDENADOR, ";
                $cadena_sql.="ord_tipo ORDENADOR, ";
                $cadena_sql.="depen_id ID_DEPENDENCIA, ";
                $cadena_sql.="DEP.descripcion DEPENDENCIA ";
                $cadena_sql.="FROM ";
                $cadena_sql.="entrada ";
                $cadena_sql.="INNER JOIN factura ON ent_id = fact_id_entrada ";
                $cadena_sql.="INNER JOIN proveedor ON fact_id_proveedor = pro_id ";
                $cadena_sql.="INNER JOIN clase_entrada ON ent_id_clase = cla_id ";
                $cadena_sql.="INNER JOIN ordenador_gasto ON fact_id_ord_gasto = ord_id ";
                $cadena_sql.="INNER JOIN estado EST ON ent_estado = id_estado ";
                $cadena_sql.="INNER JOIN dependencias DEP ON ent_dependencia=depen_id ";
                
                if ($variable!="") {
                    
                    $cadena_sql.= " WHERE";
                    
                    if ($variable['entrada']){
                        $cadena_sql.= " ent_id =" . $variable['entrada'];
                        $temp++;
                    }
                    
                    if($temp>0 && $variable['fechaEntrada']){
                        $cadena_sql.= " AND ent_fecha =" . $variable['fechaEntrada'];
                    }else if($temp==0 && $variable['fechaEntrada']){
                        $cadena_sql.= " ent_fecha =" . $variable['fechaEntrada'];
                    }                        
                    
                    if($temp>0 && $variable['proveedor']){
                        $cadena_sql.= " AND pro_nit =" . $variable['proveedor'];
                    }else if($temp==0 && $variable['proveedor']){
                        $cadena_sql.= " pro_nit =" . $variable['proveedor'];
                    }
                    
                    if($temp>0 && $variable['claseEntrada']){
                        $cadena_sql.= " AND cla_id =" . $variable['claseEntrada'];
                    }else if($temp==0 && $variable['claseEntrada']){
                        $cadena_sql.= " cla_id =" . $variable['claseEntrada'];
                    }
                    
                    if($temp>0 && $variable['ordenadorGasto']){
                        $cadena_sql.= " AND ord_id =" . $variable['ordenadorGasto'];
                    }else if($temp==0 && $variable['ordenadorGasto']){
                        $cadena_sql.= " ord_id =" . $variable['ordenadorGasto'];
                    }
                }
                break;


            case 'ordenador':

                $cadena_sql = " SELECT";
                $cadena_sql.= " ord_id,";
                $cadena_sql.= " ord_tipo";
                $cadena_sql.= " FROM ordenador_gasto";
                break;

            case 'clase':

                $cadena_sql = " SELECT";
                $cadena_sql.= " cla_id,";
                $cadena_sql.= " cla_descripcion";
                $cadena_sql.= " FROM clase_entrada";
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
