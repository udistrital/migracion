<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlconsultarElemento extends sql {

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
            case "consultarElementos":
                $cadena_sql = " SELECT";
                $cadena_sql.= " ele_id ELEMENTO,";
                $cadena_sql.= " ele_id_suministro ID_SUMINISTRO,";
                $cadena_sql.= " sum_nombre NOMBRE_SUMINISTRO,";
                $cadena_sql.= " ele_cantidad CANTIDAD,";
                $cadena_sql.= " ele_precio PRECIO,";
                $cadena_sql.= " ele_codigo_barras COD_BARRAS,";
                $cadena_sql.= " ele_porcentaje_iva IVA,";
                $cadena_sql.= " ele_descripcion DESCR,";
                $cadena_sql.= " ele_id_marca ID_MARCA,";
                $cadena_sql.= " mar_descripcion MARCA,";
                $cadena_sql.= " ele_num_serie SERIE,";
                $cadena_sql.= " ele_descuento DESCUENTO,";
                $cadena_sql.= " ele_id_entrada ENTRADA,";
                $cadena_sql.= " ele_id_salida SALIDA";
                $cadena_sql.= " FROM elemento";
                $cadena_sql.= " INNER JOIN suministros ON ele_id_suministro=sum_id";
                $cadena_sql.= " INNER JOIN marca ON ele_id_marca=mar_id";
                
                if ($variable!="") {
                    $cadena_sql.= " WHERE";
                    
                    if ($variable['placa']){
                        $cadena_sql.= " ele_codigo_barras =" . $variable['placa'];
                        $temp=1;
                    }
                    
                    if($temp==1 && $variable['serial']){
                        $cadena_sql.= " AND ";
                    }                        
                    
                    if ($variable['serial'])
                        $cadena_sql.= " ele_num_serie =" . $variable['serial'];
                }
                break;
        }

        return $cadena_sql;
    }

}

?>
