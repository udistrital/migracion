<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registro_preinscribirEspacioDemandaEstudiante extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {

            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperipreinsdemanda";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'espacios_planEstudio':
                $cadena_sql="SELECT COUNT(*)";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND pen_nro=".$variable['planEstudioEstudiante'];
                $cadena_sql.=" AND pen_cra_cod=".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND pen_estado like '%A%'";
                break;

            case 'datosEspacio':
                $cadena_sql="SELECT DISTINCT pen_cre CREDITOS,";
                $cadena_sql.=" pen_nro_ht HTD,";
                $cadena_sql.=" pen_nro_hp HTC,";
                $cadena_sql.=" pen_nro_aut HTA,";
                $cadena_sql.=" clp_cea_cod CEA";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" INNER JOIN acclasificacpen on clp_cra_cod=pen_cra_cod";
                $cadena_sql.=" AND clp_asi_cod=pen_asi_cod";
                $cadena_sql.=" AND clp_pen_nro=pen_nro";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND pen_nro=".$variable['planEstudio'];
                $cadena_sql.=" AND pen_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND pen_estado like '%A%'";
                break;

            case 'adicionar_espacio_oracle':

                $cadena_sql="INSERT INTO ACINSDEMANDA ";
                $cadena_sql.="(INSDE_ANO,INSDE_PER,INSDE_EST_COD,INSDE_ASI_COD,INSDE_CRA_COD,INSDE_CRED,INSDE_HTD,INSDE_HTC,INSDE_HTA,INSDE_CEA_COD,INSDE_SEM,INSDE_PERDIDO,INSDE_ESTADO,INSDE_EQUIVALENTE) ";
                $cadena_sql.="VALUES ('".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."',";
                $cadena_sql.="'".$variable['codEstudiante']."',";
                $cadena_sql.="'".$variable['codEspacio']."',";
                $cadena_sql.="'".$variable['codProyectoEstudiante']."',";
                $cadena_sql.="'".$variable['creditos']."',";
                $cadena_sql.="'".$variable['htd']."',";
                $cadena_sql.="'".$variable['htc']."',";
                $cadena_sql.="'".$variable['hta']."',";
                $cadena_sql.="'".$variable['cea']."',";
                $cadena_sql.="'".$variable['sem']."',";
                $cadena_sql.="'".$variable['perdido']."',";
                $cadena_sql.="'A',";
                $cadena_sql.="'".$variable['equivalente']."')";
                break;

            case 'adicionar_espacio_mysql':

                $cadena_sql="INSERT INTO ".$this->configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="VALUES ('".$variable['codEstudiante']."',";
                $cadena_sql.="'".$variable['codProyectoEstudiante']."',";
                $cadena_sql.="'".$variable['planEstudioEstudiante']."',";
                $cadena_sql.="'".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."',";
                $cadena_sql.="'".$variable['codEspacio']."',";
                $cadena_sql.="'".$variable['grupo']."',";
                $cadena_sql.="'4')";
                break;

            case 'borrar_datos_mysql_no_conexion':

                $cadena_sql="DELETE FROM ".$this->configuracion['prefijo']."horario_estudiante";
                $cadena_sql.=" WHERE horario_codEstudiante = ".$variable['codEstudiante'];
                $cadena_sql.=" AND horario_estado = 4";
                $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND horario_ano = ".$variable['ano'];
                $cadena_sql.=" AND horario_periodo = ".$variable['periodo'];
                $cadena_sql.=" AND horario_idEspacio = ".$variable['codEspacio'];
                break;

            case 'nombre_carrera':

                $cadena_sql="SELECT cra_nombre NOMBRE";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

            case 'nombreEspacio':
                $cadena_sql="SELECT asi_nombre NOMBREESPACIO";
                $cadena_sql.=" FROM acasi";
                $cadena_sql.=" WHERE asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND asi_estado LIKE '%A%'";
                break;

            case 'buscarMaximoEspaciosACursar':
                $cadena_sql="SELECT api_maximo_asignaturas MAXIMO";
                $cadena_sql.=" FROM acparins";
                $cadena_sql.=" WHERE api_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND api_ape_per=".$variable['periodo'];
                $cadena_sql.=" AND api_cra_cod=".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND api_estado LIKE '%A%'";
                $cadena_sql.=" AND api_tipo LIKE '%C%'";
                break;
            
            case 'carga':
                $cadena_sql =" SELECT ins_est_cod CODIGO,";
                $cadena_sql.=" ins_est_nombre NOMBRE,";
                $cadena_sql.=" ins_est_estado ESTADO,";
                $cadena_sql.=" ins_estado_descripcion ESTADO_DESCRIPCION,";
                $cadena_sql.=" ins_est_pensum PLAN_ESTUDIO,";
                $cadena_sql.=" ins_est_cra_cod COD_CARRERA,";
                $cadena_sql.=" ins_cra_nombre NOMBRE_CARRERA,";
                $cadena_sql.=" ins_fac_cod NOMBRE_FACULTAD,";
                $cadena_sql.=" ins_est_tipo TIPO_ESTUDIANTE,";
                $cadena_sql.=" ins_est_acuerdo ACUERDO,";
                $cadena_sql.=" ins_espacios_por_cursar ESPACIOS_POR_CURSAR,";
                $cadena_sql.=" ins_equivalencias EQUIVALECIAS,";
                $cadena_sql.=" ins_requisitos_no_aprobados REQUISITOS_NO_APROBADOS,";
                $cadena_sql.=" ins_parametros_plan PARAMETROS,";
                $cadena_sql.=" ins_creditos_aprobados CREDITOS_APROBADOS,";
                $cadena_sql.=" ins_espacios_cancelados CANCELADOS,";
                $cadena_sql.=" ins_ano ANO,";
                $cadena_sql.=" ins_periodo PERIODO";
                $cadena_sql.=" FROM sga_carga_inscripciones";
                $cadena_sql.=" WHERE ins_est_cod =".$variable['codEstudiante']; 
                $cadena_sql.=" AND ins_ano =".$variable['ano'];
                $cadena_sql.=" AND ins_periodo =".$variable['periodo'];
                //echo $cadena_sql; exit;
                break;
  ///////////////////////////////
            
                  case 'carga':
        $cadena_sql =" SELECT ins_est_cod CODIGO,";
        $cadena_sql.=" ins_est_nombre NOMBRE,";
        $cadena_sql.=" ins_est_estado ESTADO,";
        $cadena_sql.=" ins_estado_descripcion ESTADO_DESCRIPCION,";
        $cadena_sql.=" ins_est_pensum PLAN_ESTUDIO,";
        $cadena_sql.=" ins_est_cra_cod COD_CARRERA,";
        $cadena_sql.=" ins_cra_nombre NOMBRE_CARRERA,";
        $cadena_sql.=" ins_fac_cod NOMBRE_FACULTAD,";
        $cadena_sql.=" ins_est_tipo TIPO_ESTUDIANTE,";
        $cadena_sql.=" ins_est_acuerdo ACUERDO,";
        $cadena_sql.=" ins_espacios_por_cursar ESPACIOS_POR_CURSAR,";
        $cadena_sql.=" ins_equivalencias EQUIVALECIAS,";
        $cadena_sql.=" ins_requisitos_no_aprobados REQUISITOS_NO_APROBADOS,";
        $cadena_sql.=" ins_parametros_plan PARAMETROS,";
        $cadena_sql.=" ins_creditos_aprobados CREDITOS_APROBADOS,";
        $cadena_sql.=" ins_espacios_cancelados CANCELADOS,";
        $cadena_sql.=" ins_ano ANO,";
        $cadena_sql.=" ins_periodo PERIODO";
        $cadena_sql.=" FROM sga_carga_inscripciones";
        $cadena_sql.=" WHERE ins_est_cod =".$variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano =".$variable['ano'];
        $cadena_sql.=" AND ins_periodo =".$variable['periodo'];
        break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>