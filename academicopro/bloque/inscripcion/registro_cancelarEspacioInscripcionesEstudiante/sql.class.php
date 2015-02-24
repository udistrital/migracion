<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroCancelarEspacioInscripcionesEstudiante extends sql {
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

            case 'cancelar_espacio_oracle':

                $cadena_sql="DELETE FROM acins";
                $cadena_sql.=" WHERE ins_cra_cod = ".$variable['codProyecto'];
                $cadena_sql.=" AND ins_est_cod = ".$variable['codEstudiante'];
                $cadena_sql.=" AND ins_asi_cod = ".$variable['codEspacio'];
                $cadena_sql.=" AND ins_gr = ".$variable['id_grupo'];
                $cadena_sql.=" AND ins_ano = ".$variable['ano'];
                $cadena_sql.=" AND ins_per = ".$variable['periodo'];
                break;

            case 'registrar_cancelacion':

                $cadena_sql="INSERT into ".$this->configuracion['prefijo']."espacios_cancelados";
                $cadena_sql.=" VALUES ('".$variable['codEstudiante']."',";
                $cadena_sql.=" '".$variable['codEspacio']."',";
                $cadena_sql.="'".date('YmdHis')."',";
                $cadena_sql.="'".$variable['ano']."',";
                $cadena_sql.="'".$variable['periodo']."')";
                break;

            case 'actualizar_cancelados_tabla':

                $cadena_sql="UPDATE ".$this->configuracion['prefijo']."carga_inscripciones";
                $cadena_sql.=" SET ins_espacios_cancelados  = '".$variable['cadena']."'";
                $cadena_sql.=" WHERE ins_est_cod = ".$variable['codEstudiante'];
                $cadena_sql.=" AND ins_est_cra_cod = ".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND ins_ano = ".$variable['ano'];
                $cadena_sql.=" AND ins_periodo = ".$variable['periodo'];
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
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>