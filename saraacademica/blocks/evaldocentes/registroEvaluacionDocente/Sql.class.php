<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlregistroEvaluacionDocente extends sql {

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
            case "consultarAnioPeriodo":
                $cadena_sql = "SELECT ";
                $cadena_sql.="acasperiev_id, ";
                $cadena_sql.="acasperiev_anio||'-'||acasperiev_periodo, ";
                $cadena_sql.="acasperiev_anio, ";
                $cadena_sql.="acasperiev_periodo, ";
                $cadena_sql.="acasperiev_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="acasperiev_estado IN ('A') ";
                $cadena_sql.="ORDER BY acasperiev_id ASC ";
                //$cadena_sql.="acasperiev_estado NOT IN ('A') ";
                break;

            case "buscarAnioPeriodo":
                $cadena_sql = "SELECT ";
                $cadena_sql.="acasperiev_id, ";
                $cadena_sql.="acasperiev_anio||'-'||acasperiev_periodo, ";
                $cadena_sql.="acasperiev_anio, ";
                $cadena_sql.="acasperiev_periodo, ";
                $cadena_sql.="acasperiev_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="acasperiev_id=" . $variable['periodoId'] . " ";
                break;

            case "buscarPeriodos":
                $cadena_sql = "SELECT ";
                $cadena_sql.="acasperiev_id, ";
                $cadena_sql.="acasperiev_anio||'-'||acasperiev_periodo, ";
                $cadena_sql.="acasperiev_anio, ";
                $cadena_sql.="acasperiev_periodo, ";
                $cadena_sql.="acasperiev_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
                //$cadena_sql.="WHERE ";
                //$cadena_sql.="acasperiev_estado IN ('A') ";
                $cadena_sql.="ORDER BY acasperiev_id ASC ";
                //$cadena_sql.="acasperiev_estado NOT IN ('A') ";
                break;

            case "buscarPeriodo":
                $cadena_sql = "SELECT ";
                $cadena_sql.="acasperiev_id, ";
                $cadena_sql.="acasperiev_anio||'-'||acasperiev_periodo, ";
                $cadena_sql.="acasperiev_estado ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_acasperiev ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="acasperiev_id=" . $variable['periodoId'] . " ";
                break;

            case "consultaCarreras":
                $cadena_sql = "SELECT ";
                $cadena_sql.="distinct (cra_cod), cra_nombre, cra_emp_nro_iden ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod "; //AND cra_emp_nro_iden=doc_nro_iden";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" cra_emp_nro_iden =" . $variable['usuario'] . " ";
                //$cadena_sql.=" AND ape_estado='A'";
                $cadena_sql.=" AND ape_ano=" . $variable['anio'] . " ";
                $cadena_sql.=" AND ape_per=" . $variable['per'] . " ";
                $cadena_sql.=" AND car_estado='A'";
                $cadena_sql.=" AND hor_estado='A'";
                $cadena_sql.=" AND cur_estado='A'";
                $cadena_sql.=" AND doc_estado='A'";
                break;

            case "consultarCarga":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(cra_cod),doc_nro_iden, ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                $cadena_sql.="cra_nombre,tvi_cod,tvi_nombre,asi_ind_catedra ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.="WHERE ";
                //$cadena_sql.="ape_estado='A' ";
                $cadena_sql.=" ape_ano=" . $variable['anio'] . " ";
                $cadena_sql.=" AND ape_per=" . $variable['per'] . " ";
                $cadena_sql.="AND doc_nro_iden = " . $variable['usuario'] . " ";
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND hor_estado='A' ";
                $cadena_sql.="ORDER BY cra_cod ";
                break;

            case "consultarEventos":
                $cadena_sql = "SELECT COUNT(ace_cod_evento) "; // --Si calendario vigente..
                $cadena_sql.="FROM accaleventos ";
                $cadena_sql.="WHERE ace_cra_cod IN (" . $variable[0] . ") ";
                $cadena_sql.="AND ace_cod_evento IN " . $variable[1] . " ";
                $cadena_sql.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) >= TO_NUMBER(TO_CHAR(ace_fec_ini,'yyyymmdd')) ";
                $cadena_sql.="AND TO_NUMBER(TO_CHAR(SYSDATE,'yyyymmdd')) <= TO_NUMBER(TO_CHAR(ace_fec_fin,'yyyymmdd')) ";
                $cadena_sql.="AND ace_anio = " . $variable[2] . " ";
                $cadena_sql.="AND ace_periodo = " . $variable[3] . " ";
                break;

            case "consultarDocentes": //Consulta que hace el Coordinador
                $cadena_sql = "SELECT DISTINCT(doc_nro_iden), ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre,  ";
                $cadena_sql.="cra_cod,cra_nombre,tvi_cod,tvi_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" doc_nro_iden <>" . $variable['usuario'] . " ";
                $cadena_sql.=" AND ";
                $cadena_sql.=" cra_cod = " . $variable['carrera'] . " ";
                $cadena_sql.=" AND doc_estado = 'A'";
                //$cadena_sql.=" AND ape_estado='A'";
                $cadena_sql.=" AND ape_ano=" . $variable['anio'] . " ";
                $cadena_sql.=" AND ape_per=" . $variable['per'] . " ";
                $cadena_sql.=" AND car_estado='A' ";
                $cadena_sql.=" AND hor_estado='A' ";
                $cadena_sql.=" AND cur_estado='A' ";
                $cadena_sql.="AND asi_ind_catedra='N' ";
                $cadena_sql.=" ORDER BY doc_nombre";
                break;
            
            case "consultarDocentesConObservaciones": //Consulta que hace el Coordinador para los observaciones de estudiantes a docentes
                $cadena_sql = "SELECT DISTINCT (obs_identificacion_evaluado), ";
                $cadena_sql.="obs_observaciones ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_observaciones a  ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" tipo_id IN (".$variable['tipoEvaluacion'].") ";
                $cadena_sql.=" AND ";
                $cadena_sql.=" obs_carrera IN (".$variable['carrera'].") ";
                break;
            
            case "observacionesDocPorEst": //Consulta que hace el Coordinador para los observaciones de estudiantes a docentes
                $cadena_sql = "SELECT ";
                $cadena_sql.="obs_id, ";
                $cadena_sql.="obs_identificacion_evaluado, ";
                $cadena_sql.="a.form_id, ";
                $cadena_sql.="obs_anio, obs_periodo, ";
                $cadena_sql.="obs_carrera, obs_asignatura, obs_grupo, ";
                $cadena_sql.="obs_observaciones, ";
                $cadena_sql.="tipo_id, ";
                $cadena_sql.="obs_observaciones, ";
                $cadena_sql.="obs_anio, ";
                $cadena_sql.="obs_periodo ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_observaciones a  ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" obs_identificacion_evaluado='".$variable['docente']."' ";
                $cadena_sql.=" AND ";
                $cadena_sql.=" tipo_id IN (".$variable['tipoEvaluacion'].") ";
                $cadena_sql.=" AND ";
                $cadena_sql.=" obs_carrera IN (".$variable['carrera'].") ";
                break;
            
            case "consultaAsignaturas":
                $cadena_sql = " SELECT distinct ape_ano, ";
                $cadena_sql.=" ape_per, ";
                $cadena_sql.=" est_cod, ";
                $cadena_sql.=" asi_cod, ";
                $cadena_sql.=" asi_nombre, ";
                $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO, ";
                $cadena_sql.=" doc_nro_iden, ";
                $cadena_sql.=" (LTRIM(RTRIM(doc_apellido))||' '||LTRIM(RTRIM(doc_nombre))) doc_nombre,";
                $cadena_sql.=" cra_cod,";
                $cadena_sql.=" asi_ind_catedra,";
                $cadena_sql.=" cur_id";
                $cadena_sql.=" FROM mntac.acest";
                $cadena_sql.=" inner join mntac.accra on est_cra_cod = cra_cod";
                $cadena_sql.=" inner join mntac.acins ON est_cod = ins_est_cod";
                $cadena_sql.=" inner join mntac.acasperi ON ape_ano = ins_ano AND ape_per = ins_per";
                $cadena_sql.=" inner join mntac.acasi ON asi_cod = ins_asi_cod";
                $cadena_sql.=" inner join mntac.accursos on cur_id=ins_gr and cur_ape_ano=ins_ano and cur_ape_per=ins_per";
                $cadena_sql.=" inner join mntac.achorarios on hor_id_curso=cur_id";
                $cadena_sql.=" inner join mntac.accargas ON car_hor_id=hor_id";
                $cadena_sql.=" inner join mntac.acdocente ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" WHERE est_cod = " . $variable['usuario'] . " ";
                //$cadena_sql.=" AND ape_estado = 'A'";
                $cadena_sql.=" AND ape_ano=" . $variable['anio'] . " ";
                $cadena_sql.=" AND ape_per=" . $variable['per'] . " ";
                $cadena_sql.=" AND doc_estado = 'A' ";
                $cadena_sql.=" AND car_estado = 'A' ";
                $cadena_sql.=" AND cur_estado = 'A'";
                $cadena_sql.=" AND hor_estado = 'A'";
                break;

            case "consultaCoordinadores":
                $cadena_sql = "SELECT dep_nombre,cra_cod, cra_nombre, cra_emp_nro_iden,cra_estado,cra_dep_cod,(doc_nombre||' '||doc_apellido) AS doc";
                $cadena_sql.=" FROM mntac.accra, mntac.acdocente, mntge.gedep, mntpe.peemp";
                $cadena_sql.=" WHERE cra_estado = 'A'";
                $cadena_sql.=" AND doc_nro_iden = cra_emp_nro_iden";
                $cadena_sql.=" AND cra_dep_cod=dep_cod";
                $cadena_sql.=" AND cra_dep_cod = (SELECT MAX(dep_cod)";
                $cadena_sql.="                   FROM mntpe.peemp, mntge.gedep";
                $cadena_sql.="                   WHERE emp_nro_iden = " . $variable['usuario'] . " ";
                $cadena_sql.="                   AND dep_cod IN(23,24,32,33,101) ";
                $cadena_sql.="                   AND emp_cod = dep_emp_cod)";
                $cadena_sql.=" AND emp_nro_iden = " . $variable['usuario'] . " ";
                $cadena_sql.=" AND emp_cod = dep_emp_cod";
                $cadena_sql.=" ORDER BY cra_cod";
                break;

            case "consultaDocenteCoordinador": //Consulta que hace el Decano de los Coordinadores que son Docentes.
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(doc_nro_iden), ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                $cadena_sql.="cra_cod,tvi_cod,tvi_nombre,cra_nombre,asi_ind_catedra "; //cur_asi_cod
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.="WHERE ";
                //$cadena_sql.="ape_estado='A' ";
                $cadena_sql.=" ape_ano=".$variable[2]." ";
                $cadena_sql.=" AND ape_per=".$variable[3]." ";
                $cadena_sql.="AND doc_nro_iden IN (".$variable['docentes'] . ") ";
                $cadena_sql.="AND cra_cod IN (".$variable['carrera'].") ";
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND hor_estado='A' ";
                //$cadena_sql.="AND asi_ind_catedra='N' ";
                $cadena_sql.="ORDER BY cra_cod ";
                break;

            case "consultaDocenteCatedras":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(doc_nro_iden), ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                $cadena_sql.="cra_cod,tvi_cod,tvi_nombre,cra_nombre,asi_ind_catedra "; //cur_asi_cod
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.="WHERE ";
                //$cadena_sql.="ape_estado='A' ";
                $cadena_sql.=" ape_ano=" . $variable[2] . " ";
                $cadena_sql.=" AND ape_per=" . $variable[3] . " ";
                //$cadena_sql.="AND doc_nro_iden IN (".$variable['usuario'].") ";
                $cadena_sql.="AND cra_cod IN (" . $variable['carrera'] . ") ";
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND hor_estado='A' ";
                $cadena_sql.="AND asi_ind_catedra='S' ";
                $cadena_sql.="ORDER BY cra_cod ";
                break;

            case "consultarFormularios":
                $cadena_sql = "SELECT ";
                $cadena_sql.="a.fto_id, ";
                $cadena_sql.="a.enc_id, ";
                $cadena_sql.="a.preg_id, ";
                $cadena_sql.="enc_nombre, ";
                $cadena_sql.="preg_pregunta, ";
                $cadena_sql.="tip_preg_id, ";
                $cadena_sql.="preg_valor_pregunta, ";
                $cadena_sql.="fto_numero, ";
                $cadena_sql.="form_id ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_formulario a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_encabezado b ON b.enc_id=a.enc_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_pregunta c ON c.preg_id=a.preg_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato d ON d.fto_id=a.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.fto_id=" . $variable['formatoId'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="form_estado='A' ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.acasperiev_id =" . $variable['periodo'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="d.tipo_id =" . $variable['tipoId'] . " ";
                $cadena_sql.="ORDER BY form_id ASC";
                break;

            case "consultarPreguntasTipoPregunta":
                $cadena_sql = "SELECT ";
                $cadena_sql.="a.fto_id, ";
                $cadena_sql.="a.enc_id, ";
                $cadena_sql.="a.preg_id, ";
                $cadena_sql.="enc_nombre, ";
                $cadena_sql.="preg_pregunta, ";
                $cadena_sql.="tip_preg_id, ";
                $cadena_sql.="preg_valor_pregunta, ";
                $cadena_sql.="fto_numero, ";
                $cadena_sql.="form_id ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_formulario a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_encabezado b ON b.enc_id=a.enc_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_pregunta c ON c.preg_id=a.preg_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato d ON d.fto_id=a.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="a.fto_id=" . $variable['formatoId'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="form_estado='A' ";
                $cadena_sql.="AND ";
                $cadena_sql.="a.acasperiev_id =" . $variable['periodo'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="tip_preg_id= " . $variable['tipoPregunta'] . "";
                $cadena_sql.="ORDER BY form_id ASC";
                break;

            case "consultarAsociacion":
                $cadena_sql = "SELECT ";
                $cadena_sql.="ftvd_id, ";
                $cadena_sql.="a.fto_id, ";
                $cadena_sql.="ftvd_tvi_cod ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_fortipvindoc a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato b ON b.fto_id=a.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ftvd_tvi_cod=" . $variable['tipoVinculacion'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="ftvd_periodo=" . $variable['periodo'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="tipo_id=" . $variable['tipoId'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="ftvd_estado='A'";
                break;

            case "consultarEvaluacion":
                $cadena_sql = "SELECT ";
                $cadena_sql.="a.form_id, ";
                $cadena_sql.="resp_preg_num, ";
                $cadena_sql.="resp_identificacion_evaluado, ";
                $cadena_sql.="resp_anio, ";
                $cadena_sql.="resp_periodo, ";
                $cadena_sql.="resp_carrera, ";
                $cadena_sql.="resp_asignatura, ";
                $cadena_sql.="resp_grupo, ";
                $cadena_sql.="resp_fec_registro, ";
                $cadena_sql.="resp_respuesta, ";
                $cadena_sql.="resp_estado, ";
                $cadena_sql.="fto_numero, ";
                $cadena_sql.="preg_pregunta ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_pregunta c ON b.preg_id=c.preg_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato d ON b.fto_id=d.fto_id ";
                $cadena_sql.="WHERE ";
                if (isset($variable['formularioId'])) {
                    $cadena_sql.="a.form_id IN (" . $variable['formularioId'] . ") ";
                } else {
                    $cadena_sql.="a.form_id IN (0) ";
                }
                //$cadena_sql.="a.form_id IN (".$variable['formularioId'].") ";
                $cadena_sql.="AND ";
                if(isset($_REQUEST['grupo']))
                {
                    $cadena_sql.="resp_grupo='".$variable['grupo']."' ";
                }
                $cadena_sql.="AND ";
                if(isset($_REQUEST['asignatura']))
                {
                    $cadena_sql.="resp_asignatura=". $variable['asignatura']." ";
                }       
                $cadena_sql.="AND ";
                $cadena_sql.="resp_identificacion_evaluado='" . $variable['documentoId'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="resp_identificacion_evaluador='" . $variable['usuario'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="resp_carrera=" . $variable['carrera'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="tipo_id=" . $variable['tipoId'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="resp_estado='A'";
                break;

            case "consultarObservacion":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(obs_identificacion_evaluador), ";
                $cadena_sql.="form_id, ";
                $cadena_sql.="obs_observaciones ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_observaciones ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="obs_identificacion_evaluador='" . $variable['usuario'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="obs_asignatura='" . $variable['asignatura'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="obs_grupo='" . $variable['grupo'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="obs_identificacion_evaluado='" . $variable['documentoId'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="obs_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="obs_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="obs_carrera=" . $variable['carrera'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="obs_estado='A'";
                break;

            case "insertaEvaluacion":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="autoevaluadoc.evaldocente_respuesta (";
                $cadena_sql.="form_id, ";
                $cadena_sql.="resp_preg_num, ";
                $cadena_sql.="resp_identificacion_evaluador, ";
                $cadena_sql.="resp_identificacion_evaluado, ";
                $cadena_sql.="resp_anio, ";
                $cadena_sql.="resp_periodo, ";
                $cadena_sql.="resp_carrera, ";
                $cadena_sql.="resp_asignatura, ";
                $cadena_sql.="resp_grupo, ";
                $cadena_sql.="resp_fec_registro, ";
                $cadena_sql.="resp_respuesta, ";
                $cadena_sql.="resp_estado) ";
                $cadena_sql.="VALUES ( ";
                $cadena_sql.="" . $variable['formularioId'] . ", ";
                $cadena_sql.="" . $variable['preguntaNumero'] . ", ";
                $cadena_sql.="" . $variable['usuario'] . ", ";
                $cadena_sql.="" . $variable['documentoId'] . ", ";
                $cadena_sql.="" . $variable['anio'] . ", ";
                $cadena_sql.="" . $variable['periodo'] . ", ";
                $cadena_sql.="" . $variable['carrera'] . ", ";
                $cadena_sql.="" . $variable['asignatura'] . ", ";
                $cadena_sql.="'" . $variable['grupo'] . "', ";
                $cadena_sql.="'" . $variable['fechaHoy'] . "', ";
                $cadena_sql.="" . $variable['respuesta'] . ", ";
                $cadena_sql.="'" . $variable['estado'] . "' ";
                $cadena_sql.=")";
                break;

            case "insertaObservacion":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="autoevaluadoc.evaldocente_observaciones (";
                $cadena_sql.="form_id, ";
                $cadena_sql.="obs_identificacion_evaluador, ";
                $cadena_sql.="obs_identificacion_evaluado, ";
                $cadena_sql.="obs_anio, ";
                $cadena_sql.="obs_periodo, ";
                $cadena_sql.="obs_carrera, ";
                $cadena_sql.="obs_asignatura, ";
                $cadena_sql.="obs_grupo, ";
                $cadena_sql.="obs_fec_registro, ";
                $cadena_sql.="obs_observaciones, ";
                $cadena_sql.="obs_estado) ";
                $cadena_sql.="VALUES ( ";
                $cadena_sql.="" . $variable['formularioObsId'] . ", ";
                $cadena_sql.="" . $variable['usuario'] . ", ";
                $cadena_sql.="" . $variable['documentoId'] . ", ";
                $cadena_sql.="" . $variable['anio'] . ", ";
                $cadena_sql.="" . $variable['periodo'] . ", ";
                $cadena_sql.="" . $variable['carrera'] . ", ";
                $cadena_sql.="'" . $variable['asignatura'] . "', ";
                $cadena_sql.="'" . $variable['grupo'] . "', ";
                $cadena_sql.="'" . $variable['fechaHoy'] . "', ";
                $cadena_sql.="'" . $variable['observaciones'] . "', ";
                $cadena_sql.="'" . $variable['estado'] . "' ";
                $cadena_sql.=")";
                break;

            case "consultarTipoEvaluacion":
                $cadena_sql = "SELECT ";
                $cadena_sql.="tipo_id, ";
                $cadena_sql.="tipo_nombre, ";
                $cadena_sql.="UPPER(tipo_descripcion) ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_tipo_evaluacion ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="tipo_id IN (" . $variable['tipoId'] . ") ";
                break;

            case "tiposEvaluacion":
                $cadena_sql = "SELECT ";
                $cadena_sql.="tipo_id, ";
                $cadena_sql.="tipo_nombre, ";
                $cadena_sql.="tipo_descripcion ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_tipo_evaluacion";
                break;

            case "docentesEvaluados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(resp_identificacion_evaluado), ";
                $cadena_sql.="a.form_id, ";
                $cadena_sql.="resp_anio, resp_periodo, ";
                $cadena_sql.="resp_carrera, resp_estado, ";
                $cadena_sql.="tipo_id ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="tipo_id = " . $variable['tipoId'] . " ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . "";
                break;

            case "docentesEvaluadosCarrera":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(resp_identificacion_evaluado), ";
                $cadena_sql.="a.form_id, ";
                $cadena_sql.="resp_anio, resp_periodo, ";
                $cadena_sql.="resp_carrera, resp_estado, ";
                $cadena_sql.="tipo_id ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="tipo_id IN (" . $variable['tipoId'] . ") ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_carrera IN (" . $variable['carreras'] . ") ";
                $cadena_sql.="AND resp_identificacion_evaluador='".$variable['usuario']."' ";
                $cadena_sql.="AND resp_estado='A' ";
                break;
           
            case "docentesEvaluadosAutoevaluacion":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(resp_identificacion_evaluado), ";
                $cadena_sql.="a.form_id, ";
                $cadena_sql.="resp_anio, resp_periodo, ";
                $cadena_sql.="resp_carrera, resp_estado, ";
                $cadena_sql.="tipo_id ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="tipo_id IN (" . $variable['tipoId'] . ") ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                $cadena_sql.="AND resp_carrera IN (" . $variable['carrera'] . ") ";
                $cadena_sql.="AND resp_estado='A' ";
                break;

            case "docentesEvaluadosEstudiantes":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(resp_identificacion_evaluado), ";
                $cadena_sql.="a.form_id, ";
                $cadena_sql.="resp_anio, resp_periodo, ";
                $cadena_sql.="resp_carrera, resp_estado, ";
                $cadena_sql.="tipo_id ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="tipo_id IN (" . $variable['tipoId'] . ") ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . "";
                $cadena_sql.="AND resp_carrera =" . $variable['carrera'] . " ";
                $cadena_sql.="AND resp_asignatura =" . $variable['asignatura'] . " ";
                $cadena_sql.="AND resp_grupo ='" . $variable['grupo'] . "' ";
                $cadena_sql.="AND resp_identificacion_evaluador='" . $variable['usuario'] . "' ";
                $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['documentoId'] . "' ";
                $cadena_sql.="AND resp_estado='A' ";
                break;

            case "listaDocentes":
                $cadena_sql = "SELECT DISTINCT(cra_cod), ";
                $cadena_sql.="doc_nro_iden, ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="doc_email, ";
                $cadena_sql.="dep_cod, ";
                $cadena_sql.="dep_nombre ";
                $cadena_sql.="FROM mntac.acdocente ";
                $cadena_sql.="INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.="INNER JOIN mntac.achorarios ON car_hor_id=hor_id ";
                $cadena_sql.="INNER JOIN mntac.accursos ON hor_id_curso=cur_id ";
                $cadena_sql.="INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per ";
                $cadena_sql.="INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod ";
                $cadena_sql.="INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.="INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.="INNER JOIN mntge.gedep ON dep_cod=cra_dep_cod ";
                $cadena_sql.="WHERE ";
                $cadena_sql.=" ape_ano=" . $variable['anio'] . " ";
                $cadena_sql.=" AND ape_per=" . $variable['per'] . " ";
                if (isset($variable['vacia'])) {
                    $cadena_sql.="AND doc_nro_iden NOT IN (" . $variable['vacia'] . ") ";
                } else {
                    $cadena_sql.="AND doc_nro_iden NOT IN (0) ";
                }
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND hor_estado='A' ";
                $cadena_sql.="AND dep_cod IN (23,24,32,33,101) ";
                $cadena_sql.="AND asi_ind_catedra='" . $variable['catedra'] . "' ";
                $cadena_sql.="ORDER BY cra_cod ";
                break;

            case "estudiantesEvaluaron":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(resp_identificacion_evaluador), ";
                $cadena_sql.="form_id, ";
                $cadena_sql.="resp_anio, resp_periodo, ";
                $cadena_sql.="resp_carrera, resp_estado, ";
                $cadena_sql.="tipo_id ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato ON form_id=fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="tipo_id IN (1,4) ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . "";
                break;

            case "listaEstudiantes":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(ins_est_cod), ";
                $cadena_sql.="est_nombre, ";
                $cadena_sql.="est_estado_est, ";
                $cadena_sql.="eot_email, ";
                $cadena_sql.="eot_email_ins, ";
                $cadena_sql.="cur_cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cur_ape_ano, ";
                $cadena_sql.="cur_ape_per, ";
                $cadena_sql.="dep_cod, ";
                $cadena_sql.="dep_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.accursos,mntac.accra,mntac.acasi,mntac.acasperi,mntac.acins,mntac.acest,mntac.acestado,mntac.acestotr,mntge.gedep ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="est_cod =eot_cod ";
                $cadena_sql.="AND cur_asi_cod = asi_cod ";
                $cadena_sql.="AND cur_cra_cod = cra_cod ";
                $cadena_sql.="AND cur_ape_ano = ape_ano ";
                $cadena_sql.="AND cur_ape_per = ape_per ";
                $cadena_sql.="AND ape_ano=" . $variable['anio'] . " ";
                $cadena_sql.="AND ape_per=" . $variable['per'] . " ";
                $cadena_sql.="AND cur_asi_cod = ins_asi_cod ";
                $cadena_sql.="AND cur_id=ins_gr ";
                $cadena_sql.="AND cur_ape_ano = ins_ano ";
                $cadena_sql.="AND cur_ape_per = ins_per ";
                $cadena_sql.="AND ins_est_cod = est_cod ";
                if (isset($variable['vacia'])) {
                    $cadena_sql.="AND est_cod NOT IN (" . $variable['vacia'] . ") ";
                } else {
                    $cadena_sql.="AND est_cod NOT IN (0) ";
                }
                $cadena_sql.="AND est_estado_est = estado_cod ";
                $cadena_sql.="AND cra_dep_cod=dep_cod ";
                $cadena_sql.="AND dep_cod IN (" . $variable['facultad'] . ") ";
                $cadena_sql.="AND estado_activo='S' ";
                $cadena_sql.="ORDER BY ins_est_cod ";
                break;

            case "listaEstudiantesSinEvaluar":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(ins_est_cod), ";
                $cadena_sql.="est_nombre, ";
                $cadena_sql.="est_estado_est, ";
                $cadena_sql.="eot_email, ";
                $cadena_sql.="eot_email_ins, ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="cur_ape_ano, ";
                $cadena_sql.="cur_ape_per, ";
                $cadena_sql.="dep_cod, ";
                $cadena_sql.="dep_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.accursos,mntac.accra,mntac.acasi,mntac.acasperi,mntac.acins,mntac.acest,mntac.acestado,mntac.acestotr,mntge.gedep ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="est_cod =eot_cod ";
                $cadena_sql.="AND cur_asi_cod = asi_cod ";
                $cadena_sql.="AND ins_cra_cod = cra_cod ";
                $cadena_sql.="AND cur_ape_ano = ape_ano ";
                $cadena_sql.="AND cur_ape_per = ape_per ";
                $cadena_sql.="AND ape_ano=" . $variable['anio'] . " ";
                $cadena_sql.="AND ape_per=" . $variable['per'] . " ";
                $cadena_sql.="AND cur_asi_cod = ins_asi_cod ";
                $cadena_sql.="AND cur_id=ins_gr ";
                $cadena_sql.="AND cur_ape_ano = ins_ano ";
                $cadena_sql.="AND cur_ape_per = ins_per ";
                $cadena_sql.="AND ins_est_cod = est_cod ";
                if (isset($variable['vacia'])) {
                    $cadena_sql.="AND est_cod NOT IN (" . $variable['vacia'] . ") ";
                } else {
                    $cadena_sql.="AND est_cod NOT IN (0) ";
                }
                $cadena_sql.="AND est_estado_est = estado_cod ";
                $cadena_sql.="AND cra_dep_cod=dep_cod ";
                $cadena_sql.="AND dep_cod IN (" . $variable['facultad'] . ") ";
                $cadena_sql.="AND estado_activo='S' ";
                $cadena_sql.="ORDER BY ins_est_cod ";
                break;

            case "observacionesEstudiantes":
                $cadena_sql = "SELECT ";
                $cadena_sql.="obs_id, ";
                $cadena_sql.="obs_identificacion_evaluado, ";
                $cadena_sql.="a.form_id, ";
                $cadena_sql.="obs_anio, obs_periodo, ";
                $cadena_sql.="obs_carrera, obs_asignatura, obs_grupo, ";
                $cadena_sql.="obs_observaciones, ";
                $cadena_sql.="tipo_id ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_observaciones a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="tipo_id IN (" . $variable['tipoId'] . ") ";
                $cadena_sql.="AND obs_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND obs_periodo=" . $variable['per'] . " ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND obs_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                break;

            case "datosDocente":
                $cadena_sql = "SELECT ";
                $cadena_sql.="doc_nro_iden, ";
                $cadena_sql.="doc_nombre||' '||doc_apellido Nombre ";
                $cadena_sql.="FROM mntac.acdocente ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="doc_nro_iden=" . $variable['docente'] . " ";
                break;

            case "datosCarrera":
                $cadena_sql = "SELECT ";
                $cadena_sql.="cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="dep_cod, ";
                $cadena_sql.="dep_nombre ";
                $cadena_sql.="FROM mntac.accra, mntge.gedep ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cra_cod=" . $variable['carrera'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="cra_dep_cod=dep_cod ";
                break;

            case "datosCurso":
                $cadena_sql = "SELECT ";
                $cadena_sql.="asi_cod, ";
                $cadena_sql.="asi_nombre ";
                $cadena_sql.="FROM mntac.acasi ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="asi_cod=" . $variable['curso'] . " ";
                break;

            case "resultadosEvaluacion":
                //--Evaluación Docente por Estudiantes
                $cadena_sql = "SELECT avg(cast(resp_respuesta as int)) resp_respuesta, ";
                $cadena_sql.="resp_identificacion_evaluado, tipo_id, fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=1 ";
                $cadena_sql.="AND fto_numero=7 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='19328698' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_preg_num NOT IN (11,12) ";
                $cadena_sql.="AND resp_estado='A' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Evaluación de Docentes VE por el Consejo Curricular (HC)
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=3 ";
                $cadena_sql.="AND fto_numero=8 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_estado='A' ";
                $cadena_sql.="AND resp_respuesta <> '0' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Autoevaluación Docentes de Vinculación Especial (HC) 
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=2 ";
                $cadena_sql.="AND fto_numero=6 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='19328698' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_preg_num NOT IN (12,13) ";
                $cadena_sql.="AND resp_estado='A' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Evaluación de  Docentes VE por el Consejo Curricular (TCO)
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=3 ";
                $cadena_sql.="AND fto_numero=9 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_estado='A' ";
                $cadena_sql.="AND resp_respuesta <> '0' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Autoevaluación Docentes de Planta (T.C.)
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=2 ";
                $cadena_sql.="AND fto_numero=10 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_preg_num NOT IN (21,22) ";
                $cadena_sql.="AND resp_estado='A' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //-- Evaluación por el Consejo de Proyecto Currricular(docentes planta T.C) 
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=3 ";
                $cadena_sql.="AND fto_numero=11 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_estado='A' ";
                $cadena_sql.="AND resp_respuesta <> '0' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Autoevaluación Docentes de Vinculación Especial (TCO)	
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=2 ";
                $cadena_sql.="AND fto_numero=12 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_preg_num NOT IN (15,16) ";
                $cadena_sql.="AND resp_estado='A' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Autoevaluación Docentes de Vinculación Especial (MTO- MT)
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=2 ";
                $cadena_sql.="AND fto_numero=13 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_preg_num NOT IN (14,15) ";
                $cadena_sql.="AND resp_estado='A' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Evaluacion de Docentes VE por el Consejo Curricular (MTO- MT)
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=3 ";
                $cadena_sql.="AND fto_numero=14 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_estado='A' ";
                $cadena_sql.="AND resp_respuesta <> '0' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera";
                break;

            case "resultadosEvaluacionCatedras":
                //--Evaluacion por Estudiantes Cátedras
                $cadena_sql = "SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=4 ";
                $cadena_sql.="AND fto_numero=15 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_estado='A' ";
                
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //--Auto evaluación docentes catedras
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=5 ";
                $cadena_sql.="AND fto_numero=16 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_estado='A' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera ";
                $cadena_sql.="UNION ";
                //-- Evaluación por el Decano catedras institucionales	
                $cadena_sql.="SELECT avg(cast(resp_respuesta as int)) resp_respuesta,resp_identificacion_evaluado, ";
                $cadena_sql.="tipo_id,fto_porcentaje,resp_carrera ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=6 ";
                $cadena_sql.="AND fto_numero=17 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='51551021' ";
                $cadena_sql.="AND resp_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND resp_periodo=" . $variable['per'] . " ";
                $cadena_sql.="AND resp_estado='A' ";
                $cadena_sql.="AND resp_respuesta <> '0' ";
                if (isset($_REQUEST['periodo'])) {
                    $cadena_sql.="AND resp_identificacion_evaluado='" . $variable['usuario'] . "' ";
                }
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluado,c.tipo_id,c.fto_porcentaje,a.resp_carrera";
                break;

            case "consultaDocentes":
                $cadena_sql = "SELECT ";
                $cadena_sql.="DISTINCT(doc_nro_iden), ";
                $cadena_sql.="(LTRIM(RTRIM(doc_nombre))||' '||LTRIM(RTRIM(doc_apellido))) doc_nombre, ";
                $cadena_sql.="cra_cod,tvi_cod,tvi_nombre,cra_nombre,asi_ind_catedra "; //cur_asi_cod
                $cadena_sql.="FROM ";
                $cadena_sql.="mntac.acdocente ";
                $cadena_sql.=" INNER JOIN mntac.accargas ON car_doc_nro = doc_nro_iden ";
                $cadena_sql.=" INNER JOIN mntac.achorarios ON car_hor_id=hor_id";
                $cadena_sql.=" INNER JOIN mntac.accursos ON hor_id_curso=cur_id";
                $cadena_sql.=" INNER JOIN mntac.acasperi ON ape_ano=cur_ape_ano AND ape_per=cur_ape_per";
                $cadena_sql.=" INNER JOIN mntac.acasi ON asi_cod = cur_asi_cod";
                $cadena_sql.=" INNER JOIN mntac.actipvin ON tvi_cod = car_tip_vin ";
                $cadena_sql.=" INNER JOIN mntac.accra ON cra_cod=cur_cra_cod ";
                $cadena_sql.="WHERE ";
                //$cadena_sql.="ape_estado='A' ";
                //$cadena_sql.="ape_ano=".$variable['anio'] ." ";
                //$cadena_sql.="AND ape_per=".$variable['per'] ." ";
                $cadena_sql.="doc_nro_iden = " . $variable['docente'] . " ";
                $cadena_sql.="AND cra_cod = " . $variable['carrera'] . " ";
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND hor_estado='A' ";
                //$cadena_sql.="AND asi_ind_catedra='N' ";
                $cadena_sql.="ORDER BY cra_cod ";
                break;
            
            case "docentes":
                $cadena_sql="SELECT ";
                $cadena_sql.="doc_nro_iden, ";
                $cadena_sql.="doc_nombre, ";
                $cadena_sql.="doc_apellido ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acdocente ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="doc_nro_iden=".$variable." ";
                break;           

            case "consultaResultados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="result_identificacion, ";
                $cadena_sql.="result_valor, ";
                $cadena_sql.="result_tipo_evaluacion, ";
                $cadena_sql.="result_anio, ";
                $cadena_sql.="result_periodo, ";
                $cadena_sql.="result_carrera ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_resultados ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="result_identificacion='" . $variable['docente'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_valor='" . $variable['subtotal'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_tipo_evaluacion=" . $variable['tipoId'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_periodo=" . $variable['periodo'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_carrera=" . $variable['carrera'] . " ";
                $cadena_sql.="AND result_valor <> 0 ";
                break;

            case "insertaResultados":
                $cadena_sql = "INSERT INTO ";
                $cadena_sql.="autoevaluadoc.evaldocente_resultados (";
                $cadena_sql.="result_identificacion, ";
                $cadena_sql.="result_valor, ";
                $cadena_sql.="result_tipo_evaluacion, ";
                $cadena_sql.="result_anio, ";
                $cadena_sql.="result_periodo, ";
                $cadena_sql.="result_carrera) ";
                $cadena_sql.="VALUES ( ";
                $cadena_sql.="" . $variable['docente'] . ", ";
                $cadena_sql.="" . $variable['subtotal'] . ", ";
                $cadena_sql.="" . $variable['tipoId'] . ", ";
                $cadena_sql.="" . $variable['anio'] . ", ";
                $cadena_sql.="" . $variable['periodo'] . ", ";
                $cadena_sql.="" . $variable['carrera'] . " ";
                $cadena_sql.=")";
                break;

            case "modificaResultados":
                $cadena_sql = "UPDATE ";
                $cadena_sql.="autoevaluadoc.evaldocente_resultados ";
                $cadena_sql.="SET ";
                $cadena_sql.="result_valor='" . $variable['subtotal'] . "' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="result_identificacion='" . $variable['docente'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_tipo_evaluacion=" . $variable['tipoId'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_anio=" . $variable['anio'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_periodo=" . $variable['periodo'] . " ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_carrera=" . $variable['carrera'] . " ";
                break;

            case "sumaResultados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="SUM(result_valor) ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_resultados ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="result_identificacion='" . $variable['docente'] . "' ";
                $cadena_sql.="AND ";
                $cadena_sql.="result_carrera=" . $variable['carrera'] . " ";
                //$cadena_sql.="GROUP BY result_identificacion,result_carrera,result_tipo_evaluacion";
                break;

            case "listaFacultades":
                $cadena_sql = "SELECT ";
                $cadena_sql.="dep_cod, ";
                $cadena_sql.="dep_nombre, ";
                $cadena_sql.="cla_tipo_usu, ";
                $cadena_sql.="cla_estado ";
                $cadena_sql.="FROM geclaves,mntac.acdocente,gedep,mntpe.peemp ";
                $cadena_sql.="WHERE cla_codigo = doc_nro_iden ";
                $cadena_sql.="AND cla_tipo_usu = 16 ";
                $cadena_sql.="AND cla_estado = 'A' ";
                $cadena_sql.="AND dep_emp_cod = emp_cod ";
                $cadena_sql.="AND emp_nro_iden = cla_codigo ";
                $cadena_sql.="AND emp_estado_e != 'R' ";
                $cadena_sql.="ORDER BY 1 ";
                break;

            case "tipoResultados":
                $cadena_sql = "SELECT ";
                $cadena_sql.="tip_res_id, ";
                $cadena_sql.="tip_res_nombre ";
                $cadena_sql.="FROM ";
                $cadena_sql.="autoevaluadoc.evaldocente_tipo_resultados ";
                break;

            //Consulta el número de estudiantes que hay inscritos en la carga del docente
            case "numeroEstudiantesInscritos":
                $cadena_sql = "SELECT ";
                $cadena_sql.="distinct DOC_NRO_IDEN, ";
                $cadena_sql.="LTRIM(doc_nombre||'  '||doc_apellido) nombre, ";
                $cadena_sql.="dep_cod, ";
                $cadena_sql.="dep_nombre, ";
                $cadena_sql.="cur_cra_cod, ";
                $cadena_sql.="cra_nombre, ";
                $cadena_sql.="car_tip_vin, ";
                $cadena_sql.="tvi_nombre, ";
                $cadena_sql.="cur_asi_cod, ";
                $cadena_sql.="asi_nombre, ";
                $cadena_sql.="(lpad(cur_cra_cod,3,0)||'-'||cur_grupo), ";
                $cadena_sql.="cur_nro_ins, ";
                $cadena_sql.="tra_nivel, ";
                $cadena_sql.="cur_id ";
                $cadena_sql.="FROM mntac.accargas,mntac.acdocente,mntac.actipvin,mntac.acasi,accra a, ";
                $cadena_sql.="mntac.gedep,mntac.accursos,mntac.achorarios,mntac.acasperi b,mntac.ACTIPCRA ";
                $cadena_sql.="WHERE dep_cod = cra_dep_cod ";
                $cadena_sql.="AND car_tip_vin = tvi_cod ";
                $cadena_sql.="AND asi_cod = cur_asi_cod ";
                $cadena_sql.="AND cur_ape_ano = ape_ano ";
                $cadena_sql.="AND cur_ape_per = ape_per ";
                $cadena_sql.="AND ape_ano = ".$variable['anio'] ." ";
                $cadena_sql.="AND ape_per=".$variable['per'] ." ";
                $cadena_sql.="AND car_hor_id = hor_id ";
                $cadena_sql.="AND hor_id_curso=cur_id ";
                $cadena_sql.="AND doc_nro_iden =" . $variable['usuario'] . " ";
                $cadena_sql.="AND cra_cod = cur_cra_cod ";
                $cadena_sql.="AND doc_estado = 'A' ";
                $cadena_sql.="AND cra_estado = 'A' ";
                $cadena_sql.="AND car_doc_nro = doc_nro_iden ";
                $cadena_sql.="AND cur_estado = 'A' ";
                $cadena_sql.="AND car_estado = 'A' ";
                $cadena_sql.="AND cra_tip_cra=tra_cod ";
                $cadena_sql.="ORDER BY dep_cod, cur_cra_cod, cur_asi_cod, cur_id ASC ";
                break;

            case "numeroEstudiantesEvaluaron":
                //--Evaluación Docente por Estudiantes
                $cadena_sql="SELECT ";
                $cadena_sql.="resp_identificacion_evaluador, ";
                $cadena_sql.="resp_asignatura, ";
                $cadena_sql.="resp_grupo ";
                $cadena_sql.="FROM autoevaluadoc.evaldocente_respuesta a ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formulario b ON a.form_id=b.form_id ";
                $cadena_sql.="INNER JOIN autoevaluadoc.evaldocente_formato c ON b.fto_id=c.fto_id ";
                $cadena_sql.="WHERE tipo_id=1 ";
                $cadena_sql.="AND fto_numero=7 ";
                //$cadena_sql.="AND resp_identificacion_evaluado='19328698' ";
                $cadena_sql.="AND resp_anio=".$variable['anio'] ." ";
                $cadena_sql.="AND resp_periodo=".$variable['per'] ." ";
                $cadena_sql.="AND resp_asignatura=".$variable['asignatura'] ." ";
                $cadena_sql.="AND resp_grupo='".$variable['grupo'] ."' ";
                $cadena_sql.="AND resp_preg_num NOT IN (11,12) ";
                $cadena_sql.="AND resp_estado='A' ";
                if(isset($_REQUEST['periodo']))
                {
                    $cadena_sql.="AND resp_identificacion_evaluado='".$variable['usuario'] ."' ";
                }  
                $cadena_sql.="GROUP BY a.resp_identificacion_evaluador,resp_asignatura,resp_grupo ";
                break;
                
        }

        return $cadena_sql;
       
    }

}

?>
