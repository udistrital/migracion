<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCancelarInscripcionEstudianteHoras extends sql {
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

            case 'cancelar_espacio_oracle':

                $cadena_sql="DELETE FROM acins";
                $cadena_sql.=" WHERE ins_cra_cod = ".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND ins_est_cod = ".$variable['codEstudiante'];
                $cadena_sql.=" AND ins_asi_cod = ".$variable['codEspacio'];
                $cadena_sql.=" AND ins_gr = ".$variable['grupo'];
                $cadena_sql.=" AND ins_ano = ".$variable['ano'];
                $cadena_sql.=" AND ins_per = ".$variable['periodo'];
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

            case 'consultar_carrera_grupo':
                $cadena_sql="SELECT cur_cra_cod CARRERA";
                $cadena_sql.=" FROM accurso";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_nro=".$variable['grupo'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" AND cur_estado LIKE '%A%'";
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