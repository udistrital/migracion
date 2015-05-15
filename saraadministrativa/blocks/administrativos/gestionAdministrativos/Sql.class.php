<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlgestionAdministrativos extends sql {

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

        switch($tipo) {

        /**
         * Clausulas específicas
         */
        case "anioper":
            $cadena_sql = "SELECT ";
            $cadena_sql.="ape_ano, ape_per ";
            $cadena_sql.="FROM ";
            $cadena_sql.="acasperi ";
            $cadena_sql.="WHERE ";
            $cadena_sql.="ape_estado='A'";
            break;

        case "buscarUsuario":
            $cadena_sql = "SELECT ";
            $cadena_sql.="emp_nombre, ";
            $cadena_sql.="emp_nro_iden, ";
            $cadena_sql.="emp_cod ";
            $cadena_sql.="FROM ";
            $cadena_sql.="peemp ";
            $cadena_sql.="WHERE ";
            $cadena_sql.="emp_nro_iden='".$variable['usuario']."' ";
            break;

        case "certificadoFuncionarios":
            $cadena_sql = "SELECT unique cir_emp_nro_iden, ";
            $cadena_sql.="cir_ano, ";
            $cadena_sql.="cir_desde, ";
            $cadena_sql.="to_char(cir_desde,'yyyy') desdea, ";
            $cadena_sql.="to_char(cir_desde,'mm') desdem, ";
            $cadena_sql.="to_char(cir_desde,'dd') desded, ";
            $cadena_sql.="cir_hasta, ";
            $cadena_sql.="to_char(cir_hasta,'yyyy') hastaa, ";
            $cadena_sql.="to_char(cir_hasta,'mm') hastam, ";
            $cadena_sql.="to_char(cir_hasta,'dd') hastad, ";
            $cadena_sql.="cir_salario, ";
            $cadena_sql.="cir_cesantia, ";
            $cadena_sql.="cir_gastos_representacion, ";
            $cadena_sql.="cir_pension, ";
            $cadena_sql.="cir_otros, ";
            $cadena_sql.="cir_total_ingresos, ";
            $cadena_sql.="cir_aportes_salud, ";
            $cadena_sql.="cir_aporte_voluntario, ";
            $cadena_sql.="nvl(cir_aportes_pension,0), ";
            $cadena_sql.="cir_exentas, ";
            $cadena_sql.="cir_retencion, ";
            $cadena_sql.="cir_estado, ";
            $cadena_sql.="emp_Nombre, ";
            $cadena_sql.="CURRENT_TIMESTAMP, ";
            $cadena_sql.="to_char(CURRENT_TIMESTAMP,'yyyy') fechaa, ";
            $cadena_sql.="to_char(CURRENT_TIMESTAMP,'mm') fecham, ";
            $cadena_sql.="to_char(CURRENT_TIMESTAMP,'dd') fechad ";
            $cadena_sql.="FROM ";
            $cadena_sql.="prceringret2004, ";
            $cadena_sql.="peemp, ";
            $cadena_sql.="DUAL ";
            $cadena_sql.="WHERE ";
            $cadena_sql.="cir_emp_nro_iden = emp_nro_iden ";
            if(isset($_REQUEST['anio']))
            {	
            	$cadena_sql.="and cir_ano = ".$variable['anio']." ";
            }
            $cadena_sql.="and emp_nro_iden = '".$variable['usuario']."' ";
            $cadena_sql.="and emp_estado = 'A' ";
            $cadena_sql.="and cir_estado = 'A' ";
            $cadena_sql.="ORDER BY emp_nombre ASC ";
        break;
        case "certificadoContratistas":
            $cadena_sql = "SELECT ";
            $cadena_sql.="cir_ano, ";
            $cadena_sql.="cir_emp_nro_iden, ";
            $cadena_sql.="cir_primer_nombre, ";
            $cadena_sql.="cir_segundo_nombre, ";
            $cadena_sql.="cir_primer_apellido, ";
            $cadena_sql.="cir_segundo_apellido, ";
            $cadena_sql.="to_char(cir_desde,'dd-mm-YYYY'), ";
            $cadena_sql.="to_char(cir_hasta,'dd-mm-YYYY'), ";
            $cadena_sql.="cir_car_tc_cod, ";
            $cadena_sql.="cir_salario, ";
            $cadena_sql.="cir_cesantia, ";
            $cadena_sql.="cir_gastos_representacion, ";
            $cadena_sql.="cir_pension, ";
            $cadena_sql.="cir_otros, ";
            $cadena_sql.="cir_total_ingresos, ";
            $cadena_sql.="cir_aportes_salud, ";
            $cadena_sql.="cir_aporte_voluntario, ";
            $cadena_sql.="cir_exentas, ";
            $cadena_sql.="cir_retencion, ";
            $cadena_sql.="cir_estado, ";
            $cadena_sql.="cir_aportes_pension, ";
            $cadena_sql.="cir_honorarios, ";
            $cadena_sql.="cir_tipo, ";
            $cadena_sql.="cir_direccion, ";
            $cadena_sql.="cir_telefono ";
            $cadena_sql.="FROM ";
            $cadena_sql.="MNTPE.PRCERINGRET_OTROS ";
            $cadena_sql.="WHERE ";
            if(isset($_REQUEST['anio']))
            {
	            $cadena_sql.="cir_ano = ".$variable['anio']." ";
	            $cadena_sql.="and ";
            }
            $cadena_sql.="cir_emp_nro_iden = '".$variable['usuario']."' ";
            $cadena_sql.="and cir_estado = 'A' ";
            $cadena_sql.="ORDER BY cir_emp_nro_iden ASC ";
        break;
        case "cargo":
            $cadena_sql = "SELECT ";
            $cadena_sql.="emp_nro_iden, emp_nombre ";
            $cadena_sql.="FROM mntpe.peemp ";
            $cadena_sql.="WHERE emp_car_cod = 5 ";
            $cadena_sql.="and emp_estado_e <> 'R'";
            break;

        case "consultaUvt":
            $cadena_sql = "SELECT ";
            $cadena_sql.="rtfte_nombre, ";
            $cadena_sql.="rtfte_valor_letras, ";
            $cadena_sql.="rtfte_valor_numero, ";
            $cadena_sql.="rtfte_valor_total ";
            $cadena_sql.="FROM funcionarios.retefuente_uvt ";
            $cadena_sql.="WHERE ";
            $cadena_sql.="rtfte_anio=" . $variable['anio'] . " ";
            break;
        
        case "datosCertificacion":
            $cadena_sql = "SELECT ";
            $cadena_sql.="primer_nombre, ";
            $cadena_sql.="segundo_nombre, ";
            $cadena_sql.="primer_apellido, ";
            $cadena_sql.="segundo_apellido, ";
            $cadena_sql.="tipo_identificacion, ";
            $cadena_sql.="identificacion, ";
            $cadena_sql.="to_char(fecha_ingreso,'dd-mm-YYYY'), ";
            $cadena_sql.="to_char(fecha_retiro,'dd-mm-YYYY'), ";
            $cadena_sql.="resolucion, ";
            $cadena_sql.="to_char(fecha_resolucion,'dd-mm-YYYY'), ";
            $cadena_sql.="cargo, ";
            $cadena_sql.="dependencia, ";
            $cadena_sql.="sueldo_basico_mensual, ";
            $cadena_sql.="salario_promedio, ";
            $cadena_sql.="jefe_recursos_humanos, ";
            $cadena_sql.="mesada, ";
            $cadena_sql.="genero, ";
            $cadena_sql.="lugar_expedicion, ";
            $cadena_sql.="to_char(fecha_pago_pension,'dd-mm-YYYY') ";
            $cadena_sql.="FROM v_datos_certificacion ";
            $cadena_sql.="WHERE ";
            $cadena_sql.="identificacion = '".$variable['usuario']."' ";
            break;
        
        case "sueldoBasico":
            $cadena_sql= "SELECT ";
            $cadena_sql.="fu_salario_promedio_detalle(".$variable['codEmpleado'].",".$variable['codDetalleSalBasico'].") ";
            $cadena_sql.="FROM ";
            $cadena_sql.="dual ";
            break;
        
        case "otrosIngresos":
            $cadena_sql= "SELECT ";
            $cadena_sql.="fu_salario_promedio_detalle(".$variable['codEmpleado'].",".$variable['codDetalleOtrosIng'].") ";
            $cadena_sql.="FROM ";
            $cadena_sql.="dual ";
            break;
        
        case "doceavas":
            $cadena_sql= "SELECT ";
            $cadena_sql.="fu_salario_promedio_detalle(".$variable['codEmpleado'].",".$variable['codDetalleDoceavas'].") ";
            $cadena_sql.="FROM ";
            $cadena_sql.="dual ";
            break;
        
        case "salarioPromedio":
            $cadena_sql= "SELECT ";
            $cadena_sql.="fu_salario_promedio_detalle(".$variable['codEmpleado'].",".$variable['codDetalleSalProm'].") ";
            $cadena_sql.="FROM ";
            $cadena_sql.="dual ";
            break;
        }
        //echo $cadena_sql."<br><br>";
        return $cadena_sql;
    }

}

?>
