<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCancelarEstudianteGrupoCoorHoras extends sql {
private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
    
  }
    function cadena_sql($opcion,$variable="")
    {

        switch($opcion)
        {
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";
                break;

            case 'buscarDatosEstudiantes':

                $cadena_sql="SELECT";
                $cadena_sql.=" est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod PROYECTO,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" est_pen_nro PLAN";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acest";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" est_cod=".$variable['codEstudiante'];
                break;


            case 'cancelar_espacio_oracle':

                $cadena_sql="DELETE FROM ACINS";
                $cadena_sql.=" WHERE INS_CRA_COD = ".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND INS_EST_COD = ".$variable['codEstudiante'];
                $cadena_sql.=" AND INS_ASI_COD = ".$variable['codEspacio'];
                $cadena_sql.=" AND INS_GR = ".$variable['grupo'];
                $cadena_sql.=" AND INS_ANO = ".$variable['ano'];
                $cadena_sql.=" AND INS_PER = ".$variable['periodo'];
                break;

            case 'buscar_espacio_mysql':

                $cadena_sql="SELECT horario_codEstudiante FROM ".$this->configuracion['prefijo']."horario_estudiante";
                $cadena_sql.=" WHERE horario_codEstudiante = ".$variable['codEstudiante'];
                $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND horario_ano = ".$variable['ano'];
                $cadena_sql.=" AND horario_periodo = ".$variable['periodo'];
                $cadena_sql.=" AND horario_idEspacio = ".$variable['codEspacio'];
                $cadena_sql.=" AND horario_grupo = ".$variable['grupo'];
                break;

            case 'cancelar_espacio_mysql':

                $cadena_sql="UPDATE ".$this->configuracion['prefijo']."horario_estudiante";
                $cadena_sql.=" SET horario_estado = 3";
                $cadena_sql.=" WHERE horario_codEstudiante = ".$variable['codEstudiante'];
                $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND horario_ano = ".$variable['ano'];
                $cadena_sql.=" AND horario_periodo = ".$variable['periodo'];
                $cadena_sql.=" AND horario_idEspacio = ".$variable['codEspacio'];
                $cadena_sql.=" AND horario_grupo = ".$variable['grupo'];
                break;

            case 'registrar_cancelar_espacio_mysql':

                $cadena_sql="INSERT INTO ".$this->configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="(".$variable['codEstudiante'].",";
                $cadena_sql.=$variable['codProyectoEstudiante'].",";
                $cadena_sql.=$variable['planEstudioEstudiante'].",";
                $cadena_sql.=$variable['ano'].",";
                $cadena_sql.=$variable['periodo'].",";
                $cadena_sql.=$variable['codEspacio'].",";
                $cadena_sql.=$variable['grupo'].",";
                $cadena_sql.="3)";
                break;

          case "buscarDatosProyecto":

                $cadena_sql="SELECT cra_cod CODIGO, ";
                $cadena_sql.=" cra_nombre NOMBRE ";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" cra_cod=".$variable['codProyecto'];
                break;

        }
        return $cadena_sql;
    }


}
?>