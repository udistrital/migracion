<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarEspacioEstudianteCoorPosgrado extends sql {
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
                $cadena_sql.=" FROM acasperi";
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
                $cadena_sql.=" pen_nro_ht HT,";
                $cadena_sql.=" pen_nro_hp HP,";
                $cadena_sql.=" pen_nro_aut HAUT,";
                $cadena_sql.=" pen_sem NIVEL";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND pen_nro=".$variable['planEstudioEstudiante'];
                $cadena_sql.=" AND pen_cra_cod=".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND pen_estado like '%A%'";
                break;

            case 'adicionar_espacio_oracle':

                $cadena_sql="INSERT INTO ACINS ";
                $cadena_sql.="(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_OBS, INS_ANO, INS_PER, INS_ESTADO, INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_TOT_FALLAS, INS_SEM, INS_HOR_ALTERNATIVO) ";
                $cadena_sql.="VALUES ('".$variable['codProyectoEstudiante']."',";
                $cadena_sql.="'".$variable['codEstudiante']."',";
                $cadena_sql.="'".$variable['codEspacio']."',";
                $cadena_sql.="'".$variable['id_grupo']."',";
                $cadena_sql.="'0',";
                $cadena_sql.="'".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."',";
                $cadena_sql.="'A',";
                $cadena_sql.="'".$variable['creditos']."',";
                $cadena_sql.="'".$variable['ht']."',";
                $cadena_sql.="'".$variable['hp']."',";
                $cadena_sql.="'".$variable['haut']."',";
                $cadena_sql.="'0',";
                $cadena_sql.="'".$variable['nivel']."',";
                $cadena_sql.="'".$variable['hor_alternativo']."')";
                break;

            case 'adicionar_espacio_mysql':

                $cadena_sql="INSERT INTO ".$this->configuracion['prefijo']."horario_estudiante ";
                $cadena_sql.="VALUES ('".$variable['codEstudiante']."',";
                $cadena_sql.="'".$variable['codProyectoEstudiante']."',";
                $cadena_sql.="'".$variable['planEstudioEstudiante']."',";
                $cadena_sql.="'".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."',";
                $cadena_sql.="'".$variable['codEspacio']."',";
                $cadena_sql.="'".$variable['id_grupo']."',";
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

        }
        return $cadena_sql;
    }


}
?>