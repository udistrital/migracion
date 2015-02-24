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

class sql_registroCambiarGrupoEstudianteGrupoCoorHoras extends sql
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

                        $cadena_sql="UPDATE ACINS";
                        $cadena_sql.=" SET INS_GR= ".$variable['id_grupo'];
                        $cadena_sql.=" WHERE ins_est_cod=".$variable['codEstudiante'];
                        $cadena_sql.=" AND ins_asi_cod=".$variable['codEspacio'];
                        $cadena_sql.=" AND ins_gr=".$variable['id_grupoAnterior'];
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

                    case "buscarDatosProyecto":

                        $cadena_sql="SELECT cra_cod CODIGO, ";
                        $cadena_sql.=" cra_nombre NOMBRE ";
                        $cadena_sql.=" FROM accra";
                        $cadena_sql.=" WHERE";
                        $cadena_sql.=" cra_cod=".$variable['codProyecto'];
                        break;

                    case 'buscar_espacio_mysql':

                        $cadena_sql="SELECT horario_codEstudiante FROM ".$this->configuracion['prefijo']."horario_estudiante";
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

		}
		return $cadena_sql;
	}


}
?>