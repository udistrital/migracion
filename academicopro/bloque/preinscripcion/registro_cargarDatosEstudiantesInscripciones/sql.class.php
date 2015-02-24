<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_registroCargarDatosEstudiantesInscripciones extends sql {

    function cadena_sql($opcion, $variable = "") {

        switch ($opcion) {

            case 'periodoActivo':

                $cadena_sql = "SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperipreinsdemanda";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'consultarDatosEstudiantes':
                $cadena_sql = " SELECT est_cod CODIGO,";
                $cadena_sql.=" est_cra_cod CARRERA,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" estado_nombre DESCRIPCION,";
                $cadena_sql.=" est_pen_nro PLAN,";
                $cadena_sql.=" trim(est_ind_cred) TIPO_ESTUD,";
                $cadena_sql.=" est_acuerdo ACUERDO";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado ON est_estado_est=estado_cod";
                $cadena_sql.=" WHERE est_estado_est in ('A', 'B', 'J','V')";
                $cadena_sql.=" AND est_cra_cod =" . $variable['codProyecto'];
                $cadena_sql.=" ORDER BY CODIGO DESC";
                break;

            case 'consultarDatosEstudiante':
                $cadena_sql = " SELECT est_cod CODIGO,";
                $cadena_sql.=" est_cra_cod CARRERA,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" estado_nombre DESCRIPCION,";
                $cadena_sql.=" est_pen_nro PLAN,";
                $cadena_sql.=" trim(est_ind_cred) TIPO_ESTUD,";
                $cadena_sql.=" est_acuerdo ACUERDO";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado ON est_estado_est=estado_cod";
                $cadena_sql.=" WHERE est_estado_est in ('A', 'B', 'J','V')";
                $cadena_sql.=" AND est_cod =" . $variable['codEstudiante'];
                break;

            case 'consultarDatosCarreras':
                $cadena_sql = " SELECT cra_cod CARRERA,";
                $cadena_sql.=" cra_abrev NOMBRE_CARRERA,";
                $cadena_sql.=" cra_dep_cod FACULTAD,";
                $cadena_sql.=" dep_nombre NOMBRE_FACULTAD,";
                $cadena_sql.=" cra_nota_aprob NOTA";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" INNER JOIN gedep ON dep_cod=cra_dep_cod";
                $cadena_sql.=" INNER JOIN actipcra ON cra_tip_cra=tra_cod";
                $cadena_sql.=" WHERE cra_dep_cod=" . $variable['codFacultad'];
                $cadena_sql.=" AND tra_cod_nivel=1";
                break;

            case 'consultarDatosProyecto':
                $cadena_sql = " SELECT cra_cod CARRERA,";
                $cadena_sql.=" cra_abrev NOMBRE_CARRERA,";
                $cadena_sql.=" cra_dep_cod FACULTAD,";
                $cadena_sql.=" dep_nombre NOMBRE_FACULTAD,";
                $cadena_sql.=" cra_nota_aprob NOTA";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" INNER JOIN gedep ON dep_cod=cra_dep_cod";
                $cadena_sql.=" WHERE cra_cod=". $variable['codProyecto'];
                break;

            case 'consultarEspaciosPlanEstudio':
                $cadena_sql = "SELECT DISTINCT";
                $cadena_sql.=" pen_asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" pen_sem NIVEL,";
                $cadena_sql.=" pen_ind_ele ELECTIVA,";
                $cadena_sql.=" pen_cre CREDITOS,";
                $cadena_sql.=" pen_nro PLAN, ";
                $cadena_sql.=" pen_nro_ht HTD, ";
                $cadena_sql.=" pen_nro_hp HTC, ";
                $cadena_sql.=" pen_nro_aut HTA, ";
                $cadena_sql.=" cea_abr CLASIFICACION ";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" INNER JOIN acasi ON acpen.pen_asi_cod = acasi.asi_cod";
                $cadena_sql.=" LEFT OUTER JOIN acclasificacpen ON clp_asi_cod = pen_asi_cod AND clp_cra_cod = pen_cra_cod AND clp_pen_nro = pen_nro ";
                $cadena_sql.=" LEFT OUTER JOIN geclasificaespac ON cea_cod = clp_cea_cod ";
                $cadena_sql.=" WHERE pen_cra_cod =" . $variable['codProyecto'];
                $cadena_sql.=" AND (cea_abr IS NULL OR cea_abr NOT LIKE 'EE')";
                $cadena_sql.=" AND pen_estado LIKE '%A%'";
                break;

            case 'consultarNotasEstudiantes':
                $cadena_sql = "SELECT ";
                $cadena_sql.=" not_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" not_asi_cod CODIGO,";
                $cadena_sql.=" NVL(not_nota,0) NOTA,";
                $cadena_sql.=" not_cra_cod PROYECTO,";
                $cadena_sql.=" not_cred CREDITOS,";
                $cadena_sql.=" not_cea_cod CLASIFICACION,";
                $cadena_sql.=" not_obs OBSERVACION";
                $cadena_sql.=" FROM acnot";
                $cadena_sql.=" INNER JOIN acest ON est_cod=not_est_cod AND est_cra_cod=not_cra_cod";
                $cadena_sql.=" WHERE not_cra_cod =" . $variable['codProyecto'];
                $cadena_sql.=" AND est_estado_est IN ('A','B','V','J')";
                $cadena_sql.=" AND not_est_reg LIKE '%A%'";
                break;

            case 'consultarNotasEstudiante':
                $cadena_sql = "SELECT ";
                $cadena_sql.=" not_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" not_asi_cod CODIGO,";
                $cadena_sql.=" NVL(not_nota,0) NOTA,";
                $cadena_sql.=" not_cra_cod PROYECTO,";
                $cadena_sql.=" not_cred CREDITOS,";
                $cadena_sql.=" not_cea_cod CLASIFICACION,";
                $cadena_sql.=" not_obs OBSERVACION";
                $cadena_sql.=" FROM acnot";
                $cadena_sql.=" WHERE not_est_cod =" . $variable['codEstudiante'];
                $cadena_sql.=" AND not_est_reg LIKE '%A%'";
                break;

            case 'consultarRequisitosPlanesCreditos':
                $cadena_sql = " SELECT ";
                $cadena_sql.=" requisitos_idEspacioPosterior COD_ASIGNATURA,";
                $cadena_sql.=" requisitos_idEspacioPrevio COD_REQUISITO, ";
                $cadena_sql.=" requisitos_previoAprobado APROBADO, ";
                $cadena_sql.=" requisitos_idPlanEstudio COD_PLAN";
                $cadena_sql.=" FROM sga_requisitos_espacio_plan_estudio ";
                break;

            case 'consultarRequisitosPlanesHoras':
                $cadena_sql = " SELECT ";
                $cadena_sql.=" req_asi_cod COD_ASIGNATURA,";
                $cadena_sql.=" req_cod COD_REQUISITO, ";
                $cadena_sql.=" req_cra_cod CARRERA, ";
                $cadena_sql.=" req_asi_corre ASI_CORRE, ";
                $cadena_sql.=" NVL(Req_Pen_Nro,0) COD_PLAN";
                $cadena_sql.=" FROM acreq ";
                $cadena_sql.=" WHERE req_estado LIKE '%A%'";
                break;

            case 'consultarParametrosCreditos':
                $cadena_sql = " SELECT parametro_idPlanEstudio PLAN, ";
                $cadena_sql.=" parametro_creditosPlan TOTAL, ";
                $cadena_sql.=" parametro_maxCreditosNivel CREDITOS_NIVEL, ";
                $cadena_sql.=" parametros_OB OB, ";
                $cadena_sql.=" parametros_OC OC, ";
                $cadena_sql.=" parametros_EI EI, ";
                $cadena_sql.=" parametros_EE EE, ";
                $cadena_sql.=" parametros_CP CP ";
                $cadena_sql.=" FROM sga_parametro_plan_estudio ";
                break;

            case 'consultarParametrosHoras':
                $cadena_sql = " SELECT api_cra_cod CARRERA,";
                $cadena_sql.=" api_nro_semestres SEMESTRES,";
                $cadena_sql.=" api_maximo_asignaturas MAXIMO_ASIGNATURAS";
                $cadena_sql.=" FROM acparins ";
                $cadena_sql.=" WHERE api_tipo='C'";
                $cadena_sql.=" AND api_ape_ano =" . $variable['ano'];
                $cadena_sql.=" AND api_ape_per =" . $variable['periodo'];
                break;

            case 'consultarEspaciosCancelados':
                $cadena_sql = " SELECT can_codEstudiante COD_ESTUDIANTE, ";
                $cadena_sql.=" can_idEspacio CODIGO ";
                $cadena_sql.=" FROM sga_espacios_cancelados";
                $cadena_sql.=" WHERE can_ano = " . $variable['ano'];
                $cadena_sql.=" AND can_periodo = " . $variable['periodo'];
                break;

            case 'consultarEspaciosEquivalentes':
                $cadena_sql = "SELECT hom_cra_cod_ppal CARRERA,";
                $cadena_sql.= " hom_asi_cod_hom CODIGO,";
                $cadena_sql.= " hom_asi_cod_ppal ASI_COD_ANTERIOR,";
                $cadena_sql.= " asi_nombre NOMBRE";
                $cadena_sql.= " FROM actablahomologacion";
                $cadena_sql.= " INNER JOIN acasi ON hom_asi_cod_hom=asi_cod";
                $cadena_sql.= " WHERE hom_estado LIKE '%A%'";
                $cadena_sql.= " AND hom_tipo_hom = 0";
                break;

            case 'insertarRegistroDatosEstudiante':
                $cadena_sql = "INSERT INTO sga_carga_inscripciones_base";
                $cadena_sql.= " (ins_est_cod, ins_est_nombre, ins_est_estado, ins_estado_descripcion, ins_est_pensum,";
                $cadena_sql.= " ins_est_cra_cod, ins_cra_nombre, ins_fac_cod, ins_fac_nombre, ins_est_tipo, ins_est_acuerdo,";
                $cadena_sql.= " ins_espacios_por_cursar, ins_equivalencias, ins_requisitos_no_aprobados, ins_parametros_plan,";
                $cadena_sql.= " ins_creditos_aprobados, ins_espacios_cancelados, ins_ano, ins_periodo)";
                $cadena_sql.= " VALUES";
                $cadena_sql.= " (" . $variable['CODIGO'] . ", ";
                $cadena_sql.= "'" . $variable['NOMBRE'] . "', ";
                $cadena_sql.= "'" . $variable['ESTADO'] . "', ";
                $cadena_sql.= "'" . $variable['DESCRIPCION'] . "', ";
                $cadena_sql.= "'" . $variable['PENSUM'] . "', ";
                $cadena_sql.= "'" . $variable['PROYECTO'] . "', ";
                $cadena_sql.= "'" . $variable['NOMBRE_CARRERA'] . "', ";
                $cadena_sql.= "'" . $variable['FACULTAD'] . "', ";
                $cadena_sql.= "'" . $variable['NOMBRE_FACULTAD'] . "', ";
                $cadena_sql.= "'" . $variable['TIPO_ESTUDIANTE'] . "', ";
                $cadena_sql.= "'" . $variable['ACUERDO'] . "', ";
                $cadena_sql.= "'" . $variable['POR_CURSAR'] . "', ";
                $cadena_sql.= "'" . $variable['EQUIVALENCIAS'] . "', ";
                $cadena_sql.= "'" . $variable['REQUISITOS'] . "', ";
                $cadena_sql.= "'" . $variable['PARAMETROS_PLAN'] . "', ";
                $cadena_sql.= "'" . $variable['CREDITOS_APROBADOS'] . "', ";
                $cadena_sql.= "'" . $variable['CANCELADOS'] . "', ";
                $cadena_sql.= $variable['ANO'] . ", ";
                $cadena_sql.= $variable['PERIODO'] . ") ";
                break;

            case 'consultarDatos':
                $cadena_sql = "SELECT COUNT(*) TOTAL";
                $cadena_sql.=" FROM sga_carga_inscripciones".$variable['BASE'];
                $cadena_sql.=" WHERE " . $variable['CAMPO'] . "=" . $variable['VALOR_CAMPO'];
                break;

            case 'borrarDatos':
                $cadena_sql = "DELETE FROM sga_carga_inscripciones".$variable['BASE'];
                $cadena_sql.=" WHERE " . $variable['CAMPO'] . "=" . $variable['VALOR_CAMPO'];
                break;

            case 'cargarDatosBase':
                $cadena_sql = "INSERT INTO sga_carga_inscripciones";
                $cadena_sql.=" SELECT * FROM sga_carga_inscripciones_base";
                $cadena_sql.=" WHERE ".$variable['CAMPO']."=".$variable['VALOR_CAMPO'];
                break;
            
            case 'consultarEspaciosAcademicos':
                $cadena_sql=" SELECT asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" asi_ind_cred CREDITOS,";
                $cadena_sql.=" asi_ind_catedra CATEDRA,";
                $cadena_sql.=" asi_estado ESTADO";
                $cadena_sql.=" FROM acasi";
                $cadena_sql.=" WHERE asi_estado='A'";
                break;
            
/**
 * 
 `pro_sga`.`sga_carga_inscripciones_base`

FROM `pro_sga`.`sga_carga_inscripciones` ;
 */            
        }
        return $cadena_sql;
    }

}

?>