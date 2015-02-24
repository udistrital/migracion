<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarEspacioEstudianteHoras extends sql {
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
                $cadena_sql.=" pen_nro_aut HAUT";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                //$cadena_sql.=" AND pen_nro=".$variable['planEstudioEstudiante'];
                $cadena_sql.=" AND pen_cra_cod=".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND pen_estado like '%A%'";
                break;

            case 'adicionar_espacio_oracle':

                $cadena_sql="INSERT INTO acins ";
                $cadena_sql.="(ins_cra_cod, ins_est_cod, ins_asi_cod, ins_gr, ins_obs, ins_ano, ins_per, ins_estado, ins_cred, ins_nro_ht, ins_nro_hp, ins_nro_aut, ins_tot_fallas) ";
                $cadena_sql.="VALUES ('".$variable['codProyectoEstudiante']."',";
                $cadena_sql.="'".$variable['codEstudiante']."',";
                $cadena_sql.="'".$variable['codEspacio']."',";
                $cadena_sql.="'".$variable['grupo']."',";
                $cadena_sql.="'0',";
                $cadena_sql.="'".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."',";
                $cadena_sql.="'A',";
                $cadena_sql.="'".$variable['creditos']."',";
                $cadena_sql.="'".$variable['ht']."',";
                $cadena_sql.="'".$variable['hp']."',";
                $cadena_sql.="'".$variable['haut']."',";
                $cadena_sql.="'0')";
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
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

            case 'nombreEspacio':
                $cadena_sql="SELECT asi_nombre NOMBREESPACIO";
                $cadena_sql.=" FROM acasi";
                $cadena_sql.=" WHERE asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND asi_estado LIKE '%A%'";
                break;

            case 'espacios_equivalentes':

                $cadena_sql= "SELECT hom_asi_cod_ant ASI_COD_ANTERIOR,";
                $cadena_sql.= " hom_asi_cod_nue ASICOD,";
                $cadena_sql.= " hom_cra_cod_nue CRA_NUEVA,";
                $cadena_sql.= " asi_nombre NOMBRE";
                $cadena_sql.= " FROM achomcra";
                $cadena_sql.= " INNER JOIN acasi ON hom_asi_cod_nue=asi_cod";
                $cadena_sql.= " WHERE hom_cra_cod_ant=".$variable['codProyectoEstudiante'];
                //$cadena_sql.= " AND hom_cra_cod_nue=".$variable['codProyectoEstudiante'];
                $cadena_sql.= " AND hom_asi_cod_nue=".$variable['codEspacio'];
                $cadena_sql.= " AND hom_estado LIKE '%A%'";
                $cadena_sql.= " ";
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

            case 'consulta_cupos':

                $cadena_sql="SELECT cur_nro_cupo CUPO,";
                $cadena_sql.=" cur_nro_ins INSCRITOS";
                $cadena_sql.=" FROM accurso";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" and cur_cra_cod=".$variable['carrera'];
                $cadena_sql.=" and cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" and cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" and cur_nro=".$variable['grupo'];
                break;
            
            case 'actualizarMyCupos':

                $cadena_sql="UPDATE accurso";
                $cadena_sql.=" SET cur_nro_ins=".$variable['inscritos'];
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" and cur_cra_cod=".$variable['carrera'];
                $cadena_sql.=" and cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" and cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" and cur_nro=".$variable['grupo'];
                break;
            
            
        }
        return $cadena_sql;
    }


}
?>