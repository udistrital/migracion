<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroInscribirEspacioInscripcionesEstudiante extends sql {
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
                //$cadena_sql.=" AND pen_nro=".$variable['planEstudioEstudiante'];
                $cadena_sql.=" AND pen_cra_cod=".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND pen_estado like '%A%'";
                break;

            case 'datosEspacio':
                $cadena_sql="SELECT DISTINCT pen_cre CREDITOS,";
                $cadena_sql.=" pen_nro_ht HT,";
                $cadena_sql.=" pen_nro_hp HP,";
                $cadena_sql.=" pen_nro_aut HAUT,";
                $cadena_sql.=" clp_cea_cod CLASIFICACION";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" LEFT OUTER JOIN acclasificacpen ON clp_pen_nro=pen_nro and clp_cra_cod= pen_cra_cod and clp_asi_cod= pen_asi_cod ";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND pen_nro=".$variable['planEstudioEstudiante'];
                $cadena_sql.=" AND pen_cra_cod=".$variable['codProyectoEstudiante'];
                $cadena_sql.=" AND pen_estado like '%A%'";
                break;

            case 'inscribir_espacio':
                $cadena_sql="INSERT INTO acins ";
                $cadena_sql.="(ins_cra_cod, ";
                $cadena_sql.=" ins_est_cod, ";
                $cadena_sql.=" ins_asi_cod, ";
                $cadena_sql.=" ins_gr, ";
                $cadena_sql.=" ins_obs, ";
                $cadena_sql.=" ins_ano, ";
                $cadena_sql.=" ins_per, ";
                $cadena_sql.=" ins_estado, ";
                $cadena_sql.=" ins_cred, ";
                $cadena_sql.=" ins_nro_ht, ";
                $cadena_sql.=" ins_nro_hp, ";
                $cadena_sql.=" ins_nro_aut, ";
                $cadena_sql.=" ins_cea_cod, ";
                $cadena_sql.=" ins_tot_fallas, ";
                $cadena_sql.=" ins_sem, ";
                $cadena_sql.=" ins_hor_alternativo) ";
                $cadena_sql.="VALUES (".$variable['codProyectoEstudiante'].",";
                $cadena_sql.="".$variable['codEstudiante'].",";
                $cadena_sql.="".$variable['codEspacio'].",";
                $cadena_sql.="".$variable['id_grupo'].",";
                $cadena_sql.="0,";
                $cadena_sql.="".$variable['ano'].",";
                $cadena_sql.="".$variable['periodo'].",";
                $cadena_sql.="'A',";
                $cadena_sql.="".$variable['creditos'].",";
                $cadena_sql.="".$variable['htd'].",";
                $cadena_sql.="".$variable['htc'].",";
                $cadena_sql.="".$variable['hta'].",";
                $cadena_sql.="".$variable['CLASIFICACION'].",";
                $cadena_sql.="0,";
                $cadena_sql.="".$variable['nivel'].",";
                $cadena_sql.="".$variable['hor_alternativo'].")";
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
            
            case 'consultarElectivosEncabezado':
                $cadena_sql =" SELECT EE.id_espacio CODIGO,";
                $cadena_sql.=" ES.espacio_nombre NOMBRE,";
                $cadena_sql.=" EN.encabezado_nombre NOMBRE_ENC";
                $cadena_sql.=" FROM sga_espacioEncabezado EE";
                $cadena_sql.=" INNER JOIN sga_espacio_academico ES ON EE.id_espacio=ES.id_espacio";
                $cadena_sql.=" INNER JOIN sga_encabezado EN ON EE.id_encabezado=EN.id_encabezado";
                $cadena_sql.=" WHERE EE.id_encabezado=".$variable['codEncabezado']; 
                $cadena_sql.=" AND EE.id_planEstudio =".$variable['plan'];
                break;

        }
        return $cadena_sql;
    }


}
?>