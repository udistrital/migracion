<?php
/**
 * SQL adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la funcion sql.class.php
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

/**
 * Clase sql_adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class sql_adminInscripcionCoordinador extends sql {

    /**
     * Funcion que crea la cadena sql
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo variable que contiene la opcion para seleccionar la cadena sql que se necesita crear
     * @param array $variable Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @param array $variable2 Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @return string Cadena sql creada
     */
    function cadena_sql($configuracion, $tipo, $variable="", $variable2="") {
        switch ($tipo) {

            case 'proyectos_curriculares':

                $cadena_sql = "SELECT DISTINCT CRA_COD, CRA_NOMBRE, PEN_NRO ";
                $cadena_sql.="FROM ACCRA ";
                $cadena_sql.="INNER JOIN ACPEN ON ACCRA.CRA_COD=ACPEN.PEN_CRA_COD ";
                //$cadena_sql.="INNER JOIN GEUSUCRA ON ACCRA.CRA_COD=GEUSUCRA.USUCRA_CRA_COD ";
                $cadena_sql.="WHERE PEN_NRO>200 ";
                $cadena_sql.=" AND CRA_EMP_NRO_IDEN=" . $variable;
                $cadena_sql.=" AND PEN_ESTADO LIKE '%A%'";
                $cadena_sql.=" ORDER BY 3";


                break;

            case 'datos_coordinador':
                $cadena_sql = "SELECT DISTINCT CRA_COD, CRA_NOMBRE, PEN_NRO";
                $cadena_sql.=" FROM ACCRA";
                //$cadena_sql.=" INNER JOIN ACCRA ON GEUSUCRA.USUCRA_CRA_COD=ACCRA.CRA_COD";
                $cadena_sql.=" INNER JOIN ACPEN ON ACCRA.CRA_COD=ACPEN.PEN_CRA_COD";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=" . $variable;
                $cadena_sql.=" AND PEN_NRO>200";
                $cadena_sql.=" AND PEN_ESTADO LIKE '%A%'";

                break;

            case "consultaEspacioPlan":
                $cadena_sql = "SELECT ESPACIO.id_espacio, ";
                $cadena_sql.="ESPACIO.espacio_nombre, ";
                $cadena_sql.="PLAN_ESPACIO.id_nivel, ";
                $cadena_sql.="espacio_nroCreditos, ";
                $cadena_sql.="horasDirecto, ";   
                $cadena_sql.="horasCooperativo, "; 
                $cadena_sql.="espacio_horasAutonomo, ";
                $cadena_sql.="CLASIFICACION.clasificacion_nombre, ";
                $cadena_sql.="CLASIFICACION.id_clasificacion, ";
                $cadena_sql.="REL_ELECTIVO.id_nombreElectivo, "; 
                $cadena_sql.="ELECTIVO.nombreElectivo, ";
                $cadena_sql.="PLAN_ESPACIO.id_aprobado, ";        
                $cadena_sql.="PLAN_ESPACIO.id_planEstudio ";    
                $cadena_sql.="FROM sga_espacio_academico AS ESPACIO ";
                $cadena_sql.="INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.="INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ";
                $cadena_sql.="ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.="LEFT OUTER JOIN sga_espacioNombreElectivo AS REL_ELECTIVO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = REL_ELECTIVO.id_espacio ";
                $cadena_sql.="LEFT OUTER JOIN sga_nombreElectivo AS ELECTIVO ";
                $cadena_sql.="ON REL_ELECTIVO.id_nombreElectivo = ELECTIVO.id_nombreElectivo ";
                $cadena_sql.="WHERE PLAN_ESPACIO.id_planEstudio=" . $variable . " ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1 ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_aprobado=1 ";
                $cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =" . $variable . " ) ";
                $cadena_sql.="ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, REL_ELECTIVO.id_nombreElectivo, ESPACIO.espacio_nombre ASC";

                break;

            case "aprobarEspacio":
                $cadena_sql = "UPDATE " . $configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_aprobado=1 ";
                $cadena_sql.="WHERE id_planEstudio=" . $variable2 . " ";
                $cadena_sql.="AND id_espacio=" . $variable;

                break;

            case "buscar_planEstudioEstudiante":
                $cadena_sql = "SELECT estudiante_idPlanEstudio, estudiante_idProyectoCurricular FROM " . $configuracion["prefijo"];
                $cadena_sql.="estudiante_creditos ";
                $cadena_sql.="WHERE estudiante_codEstudiante=" . $variable;

                break;

            case "DesaprobarEspacio":
                $cadena_sql = "UPDATE " . $configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_aprobado=0 ";
                $cadena_sql.="WHERE id_planEstudio=" . $variable2 . " ";
                $cadena_sql.="AND id_espacio=" . $variable;

                break;

            case "datosEspacio":
                $cadena_sql = "SELECT espacio_nombre, espacio_nroCreditos, espacio_horasDirecto, espacio_horasCooperativo, espacio_horasAutonomo ";
                $cadena_sql.=" FROM " . $configuracion["prefijo"] . "espacio_academico ";
                $cadena_sql.="WHERE id_espacio=" . $variable;

                break;

            case "datosCarrera":

                $cadena_sql = "select distinct cra_dep_cod, cra_emp_nro_iden, cra_estado, cra_cod ";
                $cadena_sql.=" FROM accra ";
                $cadena_sql.=" inner join acpen on pen_cra_cod= cra_cod";
                $cadena_sql.=" where pen_nro=" . $variable2;
                $cadena_sql.=" AND pen_estado like '%A%'";

                break;

            case "cargarEspacioAcasi":
                $cadena_sql = "INSERT INTO ACASI(ASI_COD, ASI_NOMBRE, ASI_DEP_COD, ASI_ESTADO, ASI_IND_CRED) ";
                $cadena_sql.="VALUES( ";
                $cadena_sql.="'" . $variable[0] . "',";
                $cadena_sql.="'" . $variable[1] . "',";
                $cadena_sql.="'" . $variable[2] . "',";
                $cadena_sql.="'" . $variable[3] . "',";
                $cadena_sql.="'" . $variable[4] . "')";

                break;

            case "borrarEspacioAcasi":
                $cadena_sql = "delete from acasi ";
                $cadena_sql.="where ";
                $cadena_sql.="ASI_COD='" . $variable[0] . "'";

                break;

            case "buscarEspacioAcasi":
                $cadena_sql = "select * from acasi ";
                $cadena_sql.="where ";
                $cadena_sql.="ASI_COD='" . $variable[0] . "'";

                break;

            case "buscarEspacioAcpen":
                $cadena_sql = "select * from acpen ";
                $cadena_sql.="where ";
                $cadena_sql.="PEN_ASI_COD='" . $variable[1] . "'";
                $cadena_sql.=" AND PEN_NRO='" . $variable[8] . "'";

                break;

            case "cargarEspacioAcpen":
                $cadena_sql = "INSERT INTO ACPEN(PEN_CRA_COD, PEN_ASI_COD, PEN_SEM, PEN_IND_ELE, PEN_NRO_HT, PEN_NRO_HP, PEN_ESTADO, PEN_CRE, PEN_NRO) ";
                $cadena_sql.="VALUES( ";
                $cadena_sql.="'" . $variable[0] . "',";
                $cadena_sql.="'" . $variable[1] . "',";
                $cadena_sql.="'" . $variable[2] . "',";
                $cadena_sql.="'" . $variable[3] . "',";
                $cadena_sql.="'" . $variable[4] . "',";
                $cadena_sql.="'" . $variable[5] . "',";
                $cadena_sql.="'" . $variable[6] . "',";
                $cadena_sql.="'" . $variable[7] . "',";
                $cadena_sql.="'" . $variable[8] . "')";

                break;

            case "estadocargarEspacio":
                $cadena_sql = "UPDATE " . $configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_cargado=1 ";
                $cadena_sql.="WHERE id_planEstudio=" . $variable2 . " ";
                $cadena_sql.="AND id_espacio=" . $variable;

                break;


            case 'registroEvento':

                $cadena_sql = "insert into " . $configuracion['prefijo'] . "log_eventos ";
                $cadena_sql.="VALUES('','" . $variable[0] . "',";
                $cadena_sql.="'" . $variable[1] . "',";
                $cadena_sql.="'" . $variable[2] . "',";
                $cadena_sql.="'" . $variable[3] . "',";
                $cadena_sql.="'" . $variable[4] . "',";
                $cadena_sql.="'" . $variable[5] . "')";

                break;

            case 'buscarIDRegistro':

                $cadena_sql = "select id_log from " . $configuracion['prefijo'] . "log_eventos ";
                $cadena_sql.="where log_usuarioProceso='" . $variable[0] . "'";
                $cadena_sql.=" and log_evento='" . $variable[2] . "'";
                $cadena_sql.=" and log_registro='" . $variable[4] . "'";
                $cadena_sql.=" and log_usuarioAfectado='" . $variable[5] . "'";

                break;

            case "listaPlanesEstudio":
                $cadena_sql = "SELECT ";
                $cadena_sql.="id_planEstudio,";
                $cadena_sql.="planEstudio_nombre,";
                $cadena_sql.="planEstudio_ano,";
                $cadena_sql.="planEstudio_periodo,";
                $cadena_sql.="planEstudio_niveles,";
                $cadena_sql.="planEstudio_fechaCreacion, ";
                $cadena_sql.="id_proyectoCurricular ";
                $cadena_sql.="FROM " . $configuracion["prefijo"];
                $cadena_sql.="planEstudio ";
                $cadena_sql.="WHERE id_estado=1";
                break;

            case "listaCarrera":
                $cadena_sql = "SELECT ";
                $cadena_sql.="id_proyectoCurricular ";
                $cadena_sql.="FROM " . $configuracion["prefijo"];
                $cadena_sql.="planEstudio ";
                $cadena_sql.="WHERE id_planEstudio='" . $variable . "'";
                break;

            case "buscar_id":
                $cadena_sql = "SELECT ";
                $cadena_sql.="id_planEstudio,";
                $cadena_sql.="planEstudio_ano, ";
                $cadena_sql.="planEstudio_periodo,";
                $cadena_sql.="planEstudio_descripcion,";
                $cadena_sql.="planEstudio_autor, ";
                $cadena_sql.="planEstudio_niveles, ";
                $cadena_sql.="planEstudio_fechaCreacion,";
                $cadena_sql.="PROYECTO.proyecto_nombre,";
                $cadena_sql.="planEstudio_nombre, ";
                $cadena_sql.="planEstudio_observaciones FROM ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="planEstudio AS PLAN ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="proyectoCurricular AS PROYECTO ";
                $cadena_sql.="ON PLAN.id_proyectoCurricular=";
                $cadena_sql.="PROYECTO.id_proyectoCurricular ";
                $cadena_sql.="WHERE PLAN.id_planEstudio=" . $variable;

                break;


            case "listaEspacios":
                $cadena_sql = "SELECT ";
                $cadena_sql.="ESPACIO.id_espacio, ";
                $cadena_sql.="ESPACIO.espacio_nombre, ";
                $cadena_sql.="CLASIF.clasificacion_abrev, ";
                $cadena_sql.="ESPACIO.espacio_nroCreditos, ";
                $cadena_sql.="PLAN_ESPACIO.horasDirecto,";
                $cadena_sql.="PLAN_ESPACIO.horasCooperativo,";
                $cadena_sql.="ESPACIO.espacio_horasAutonomo, ";
                $cadena_sql.="PLAN_ESPACIO.id_aprobado ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"] . "espacio_academico ";
                $cadena_sql.="AS ESPACIO ";
                $cadena_sql.="INNER JOIN " . $configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio AS PLAN_ESPACIO ";
                $cadena_sql.="ON ESPACIO.id_espacio=PLAN_ESPACIO.id_espacio ";
                $cadena_sql.="INNER JOIN " . $configuracion["prefijo"];
                $cadena_sql.="espacio_clasificacion AS CLASIF ON ";
                $cadena_sql.="CLASIF.id_clasificacion=PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.="WHERE id_planEstudio=" . $variable . " ";
                $cadena_sql.="ORDER BY ESPACIO.espacio_nombre";

                break;

            case "obsAprobacionEspacios":
                $cadena_sql = "UPDATE " . $configuracion["prefijo"] . "planEstudio ";
                $cadena_sql.="SET planEstudio_obsVicerrectoria='" . $variable[3] . "', ";
                $cadena_sql.="planEstudio_obsOas='" . $variable[4] . "' ";
                $cadena_sql.="WHERE id_planEstudio=" . $variable[0];

                break;

            case "comentariosNoLeidos":
                $cadena_sql = "SELECT * FROM " . $configuracion["prefijo"] . "comentario_espacio_planEstudio ";
                $cadena_sql.="WHERE `comentario_idEspacio`=" . $variable;
                $cadena_sql.=" AND `comentario_idPlanEstudio` =" . $variable2;
                $cadena_sql.=" AND `comentario_leidoAsesorVice` =0";

                break;

            case "consultarEncabezado":

                $cadena_sql = "SELECT * ";
                $cadena_sql.="FROM " . $configuracion["prefijo"];
                $cadena_sql.="encabezado ";
                $cadena_sql.="WHERE id_planEstudio='" . $variable[0] . "'";
                $cadena_sql.=" AND encabezado_nivel='" . $variable[1] . "'";
                $cadena_sql.=" AND id_encabezado!='" . $variable[2] . "'";
                break;

            case "consultarEspaciosEncabezado":

                $cadena_sql = "SELECT id_espacio ";
                $cadena_sql.="FROM " . $configuracion["prefijo"];
                $cadena_sql.="espacioEncabezado ";
                $cadena_sql.="WHERE id_planEstudio='" . $variable[1] . "'";
                $cadena_sql.=" AND id_encabezado='" . $variable[0] . "'";
                $cadena_sql.=" AND id_aprobado=1";

                break;

            case "planEstudio":

                $cadena_sql = "SELECT planEstudio_nombre ";
                $cadena_sql.="FROM " . $configuracion["prefijo"];
                $cadena_sql.="planEstudio ";
                $cadena_sql.="WHERE id_planEstudio=".$variable;

                break;

            case "datosEspacioOpcion":
                $cadena_sql = "SELECT ESPACIO.id_espacio, ";
                $cadena_sql.="ESPACIO.espacio_nombre, ";
                $cadena_sql.="PLAN_ESPACIO.id_nivel, ";
                $cadena_sql.="espacio_nroCreditos, ";
                $cadena_sql.="horasDirecto, ";  
                $cadena_sql.="horasCooperativo, "; 
                $cadena_sql.="espacio_horasAutonomo, ";
                $cadena_sql.="CLASIFICACION.clasificacion_nombre, ";
                $cadena_sql.="CLASIFICACION.id_clasificacion, ";
                $cadena_sql.="REL_ELECTIVO.id_nombreElectivo, ";
                $cadena_sql.="ELECTIVO.nombreElectivo, ";
                $cadena_sql.="PLAN_ESPACIO.id_aprobado, "; 
                $cadena_sql.="PLAN_ESPACIO.id_planEstudio ";
                $cadena_sql.="FROM sga_espacio_academico AS ESPACIO ";
                $cadena_sql.="INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.="INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ";
                $cadena_sql.="ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.="LEFT OUTER JOIN sga_espacioNombreElectivo AS REL_ELECTIVO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = REL_ELECTIVO.id_espacio ";
                $cadena_sql.="LEFT OUTER JOIN sga_nombreElectivo AS ELECTIVO ";
                $cadena_sql.="ON REL_ELECTIVO.id_nombreElectivo = ELECTIVO.id_nombreElectivo ";
                $cadena_sql.="WHERE PLAN_ESPACIO.id_espacio=" . $variable[0] . " ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_planEstudio=" . $variable[1] . " ";
                $cadena_sql.="ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, REL_ELECTIVO.id_nombreElectivo, ESPACIO.espacio_nombre ASC";

                break;
        }
        return $cadena_sql;
    }
}

?>