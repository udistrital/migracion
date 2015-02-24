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

class sql_registroCambiarGrupoInscripcionesEstudiante extends sql
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

            case 'actualizar_grupo_espacio':

                        $cadena_sql="UPDATE acins";
                        $cadena_sql.=" SET ins_gr=".$variable['grupo'];
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