<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCambiarGrupoInscripcionCreditosEstudiante extends sql
{
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

                    case 'actualizar_grupo_espacio_oracle':

                        $cadena_sql="UPDATE acins";
                        $cadena_sql.=" SET ins_gr =".$variable['grupo'];
                        $cadena_sql.=" WHERE ins_est_cod=".$variable['codEstudiante'];
                        $cadena_sql.=" AND ins_asi_cod=".$variable['codEspacio'];
                        $cadena_sql.=" AND ins_gr=".$variable['grupoAnterior'];
                        $cadena_sql.=" AND ins_ano=".$variable['ano'];
                        $cadena_sql.=" AND ins_per=".$variable['periodo'];
                        $cadena_sql.=" AND ins_estado like '%A%'";
                        break;

                    case 'actualizar_grupo_espacio_mysql':

                        $cadena_sql="UPDATE ".$this->configuracion['prefijo']."horario_estudiante";
                        $cadena_sql.=" SET horario_grupo= ".$variable['grupo'];
                        $cadena_sql.=" WHERE horario_codEstudiante=".$variable['codEstudiante'];
                        $cadena_sql.=" AND horario_idEspacio=".$variable['codEspacio'];
                        $cadena_sql.=" AND horario_grupo=".$variable['grupoAnterior'];
                        $cadena_sql.=" AND horario_ano= ".$variable['ano'];
                        $cadena_sql.=" AND horario_periodo= ".$variable['periodo'];
                        $cadena_sql.=" AND horario_estado!=3";
                        break;

                    case 'buscar_espacio_mysql':

                        $cadena_sql="SELECT horario_codEstudiante ";
                        $cadena_sql.=" FROM ".$this->configuracion['prefijo']."horario_estudiante";
                        $cadena_sql.=" WHERE horario_codEstudiante = ".$variable['codEstudiante'];
                        $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable['codProyectoEstudiante'];
                        $cadena_sql.=" AND horario_ano = ".$variable['ano'];
                        $cadena_sql.=" AND horario_periodo = ".$variable['periodo'];
                        $cadena_sql.=" AND horario_idEspacio = ".$variable['codEspacio'];
                        $cadena_sql.=" AND horario_grupo = ".$variable['grupoAnterior'];
                        break;

                    case 'registrar_actualizar_espacio_mysql':

                        $cadena_sql="INSERT INTO ".$this->configuracion['prefijo']."horario_estudiante ";
                        $cadena_sql.="VALUES ";
                        $cadena_sql.="(".$variable['codEstudiante'].",";
                        $cadena_sql.=$variable['codProyectoEstudiante'].",";
                        $cadena_sql.=$variable['planEstudioEstudiante'].",";
                        $cadena_sql.=$variable['ano'].",";
                        $cadena_sql.=$variable['periodo'].",";
                        $cadena_sql.=$variable['codEspacio'].",";
                        $cadena_sql.=$variable['grupo'].",";
                        $cadena_sql.="4)";
                        break;

                    case 'consultar_cupo_inscritos':
                        $cadena_sql="SELECT cur_nro_ins INSCRITOS";
                        $cadena_sql.=" FROM accurso";
                        $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                        $cadena_sql.=" AND cur_nro=".$variable['grupo'];
                        $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                        $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                        $cadena_sql.=" AND cur_estado LIKE '%A%'";
                        break;

                    case 'actualiza_cupo':
                        $cadena_sql="UPDATE accurso ";
                        $cadena_sql.="SET cur_nro_ins =".$variable['nvo_inscritos'];
                        $cadena_sql.=" WHERE cur_asi_cod=".$variable['codEspacio'];
                        $cadena_sql.=" AND cur_nro=".$variable['grupo'];
                        $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                        $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                        break;


		}
		return $cadena_sql;
	}


}
?>