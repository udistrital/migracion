<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarHorarioEstudianteCoordinador extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    private $configuracion;

    function __construct($configuracion) {
        $this->configuracion = $configuracion;
    }

    function cadena_sql($tipo, $variable = "") {
        switch ($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            case 'periodoActivo':
                $cadena_sql = "SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE ape_estado like '%A%'";
                break;

            case "consultaEstudiante":
                $cadena_sql = "SELECT est_cod CODIGO, ";
                $cadena_sql.="est_nombre NOMBRE, ";
                $cadena_sql.="est_pen_nro PLAN, ";
                $cadena_sql.="est_ind_cred TIPO, ";
                $cadena_sql.="est_cra_cod CARRERA, ";
                $cadena_sql.="cra_nombre NOMBRE_CARRERA, ";
                $cadena_sql.="est_acuerdo ACUERDO ";
                $cadena_sql.="FROM acest ";
                $cadena_sql.="INNER JOIN accra ON acest.est_cra_cod=";
                $cadena_sql.="accra.cra_cod ";
                $cadena_sql.="WHERE est_cod=" . $variable['codEstudiante'];
                break;


            case 'consultarInscripciones':

                $cadena_sql = "SELECT distinct ins_asi_cod CODIGO,";
                $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" ins_cred CREDITOS, ";
                $cadena_sql.=" ins_cea_cod CLASIFICACION, ";
                $cadena_sql.=" ins_gr CURSO ";
                $cadena_sql.=" FROM acins";
                $cadena_sql.=" INNER JOIN acasi ON asi_cod=ins_asi_cod";
                $cadena_sql.=" INNER JOIN accursos ON cur_id=ins_gr AND ins_ano=cur_ape_ano AND ins_per=cur_ape_per";
                $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
                $cadena_sql.=" AND ins_ano=" . $variable['ano'];
                $cadena_sql.=" AND ins_per=" . $variable['periodo'];
                $cadena_sql.=" AND ins_estado LIKE '%A%'";
                //$cadena_sql.=" AND ins_cra_cod=" . $variable['codProyectoEstudiante'];
                $cadena_sql.=" ORDER BY CODIGO";

                break;


            case 'horario_grupos':

                /* La modificación que se hizo con los alias permite que se cmabie la posición de los elementos en la consulta
                  siempre y cuando tengan el mismo alias */

                $cadena_sql = " SELECT DISTINCT";
                $cadena_sql.=" HOR_DIA_NRO HORA,";
                $cadena_sql.=" HOR_HORA DIA,";
                $cadena_sql.=" SED_ID COD_SEDE,";
                $cadena_sql.=" SEDE.SED_ID NOM_SEDE,";
                $cadena_sql.=" SALON.SAL_EDIFICIO ID_EDIFICIO,";
                $cadena_sql.=" EDI.EDI_NOMBRE NOM_EDIFICIO,";
                $cadena_sql.=" HOR_SAL_ID_ESPACIO ID_SALON,";
                $cadena_sql.=" SALON.SAL_NOMBRE NOM_SALON";
                $cadena_sql.=" FROM ACHORARIOS";
                $cadena_sql.=" INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ID";
                $cadena_sql.=" INNER JOIN GESALONES SALON ON HOR_SAL_ID_ESPACIO=SAL_ID_ESPACIO";
                $cadena_sql.=" INNER JOIN GESEDE SEDE ON salon.sal_sed_id=sede.sed_id";
                $cadena_sql.=" INNER JOIN GEEDIFICIO EDI ON SALON.SAL_EDIFICIO=EDI.EDI_COD";
                $cadena_sql.=" WHERE CUR_ASI_COD=" . $variable['CODIGO']; //codigo del espacio
                $cadena_sql.=" AND CUR_APE_ANO=".$variable['ANO'];
                $cadena_sql.=" AND CUR_APE_PER=".$variable['PERIODO'];
                $cadena_sql.=" AND CUR_ID=" . $variable['GRUPO']; //numero de grupo
                $cadena_sql.=" ORDER BY 1,2,3";
                break;

            case 'consultaCreditosSemestre':

                $cadena_sql = "SELECT semestre_nroCreditosEstudiante ";
                $cadena_sql.="FROM " . $this->configuracion['prefijo'] . "semestre_creditos_estudiante ";
                $cadena_sql.="WHERE semestre_codEstudiante=" . $variable; //codigo del espacio
                //echo "cadena".$cadena_sql;
                //exit;

                break;

            case 'consultaRegistroHorario':

                $cadena_sql = "SELECT horario_codEstudiante, horario_idProyectoCurricular, horario_idPlanEstudio, horario_ano, horario_periodo, espacio_nroCreditos ";
                $cadena_sql.=" FROM " . $this->configuracion['prefijo'] . "horario_estudiante HE ";
                $cadena_sql.="inner join " . $this->configuracion['prefijo'] . "espacio_academico EA on HE.horario_idEspacio=EA.id_espacio ";
                $cadena_sql.="WHERE horario_codEstudiante=" . $variable; //codigo del estudiante
                $cadena_sql.=" AND horario_estado!='3'"; //codigo del estudiante

                break;

            case 'grabarCreditosNuevo':

                $cadena_sql = "INSERT INTO " . $this->configuracion['prefijo'] . "semestre_creditos_estudiante ";
                $cadena_sql.=" VALUES( ";
                $cadena_sql.="'" . $variable[0] . "',";
                $cadena_sql.="'" . $variable[1] . "',";
                $cadena_sql.="'" . $variable[2] . "',";
                $cadena_sql.="'" . $variable[3] . "',";
                $cadena_sql.="'" . $variable[4] . "',";
                $cadena_sql.="'" . $variable[5] . "',";
                $cadena_sql.="'0')";

                //echo "cadena".$cadena_sql;
                //exit;

                break;

            case 'clasificacion':

                $cadena_sql = " SELECT id_clasificacion, ";
                $cadena_sql.=" clasificacion_abrev, ";
                $cadena_sql.=" clasificacion_nombre  ";
                $cadena_sql.=" FROM " . $this->configuracion['prefijo'] . "espacio_clasificacion";
                break;

            default:
                $cadena_sql = '';
                break;
        }#Cierre de switch
        return $cadena_sql;
    }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>
