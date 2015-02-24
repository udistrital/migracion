<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarPlanEstudioAsisVice extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="", $variable2="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios

              case "listaPlanesEstudio":
                $cadena_sql="SELECT ";
                $cadena_sql.=" id_planEstudio PLAN,";
                $cadena_sql.=" proyecto_nombre PROYECTO_NOMBRE,";
                $cadena_sql.=" planEstudio_ano ANO,";
                $cadena_sql.=" planEstudio_periodo PERIODO,";
                $cadena_sql.=" planEstudio_niveles NIVELES,";
                $cadena_sql.=" planEstudio_fechaCreacion FECHA,";
                $cadena_sql.=" PE.id_proyectoCurricular COD_PROYECTO,";
                $cadena_sql.=" id_proyectoAcademica COD_PROYECTO_ACADEMICA,";
                $cadena_sql.=" id_facultad_academica FACULTAD";
                $cadena_sql.=" FROM ".$configuracion["prefijo"]."planEstudio PE";
                $cadena_sql.=" INNER JOIN ".$configuracion["prefijo"]."proyectoCurricular PC";
                $cadena_sql.=" ON PE.id_proyectoCurricular=PC.id_proyectoCurricular";
                $cadena_sql.=" WHERE id_estado=1";
                $cadena_sql.=" AND id_planEstudio!=4";
                $cadena_sql.=" group by PE.id_proyectoCurricular";
                $cadena_sql.=" order by 1";
                break;

            case "listaPlanes":
                $cadena_sql="SELECT ";
                $cadena_sql.=" id_planEstudio,";
                $cadena_sql.=" planEstudio_nombre,";
                $cadena_sql.=" planEstudio_ano,";
                $cadena_sql.=" planEstudio_periodo,";
                $cadena_sql.=" planEstudio_niveles,";
                $cadena_sql.=" planEstudio_fechaCreacion,";
                $cadena_sql.=" id_proyectoCurricular";
                $cadena_sql.=" FROM ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio";
                $cadena_sql.=" WHERE id_estado=1";
                $cadena_sql.=" AND id_proyectoCurricular=".$variable;
                $cadena_sql.=" order by 1";
                break;

            case "cantidadPlanes":
                $cadena_sql="SELECT count(*)";
                $cadena_sql.=" FROM ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio";
                $cadena_sql.=" WHERE id_estado=1";
                $cadena_sql.=" AND id_proyectoCurricular=".$variable;
                $cadena_sql.=" order by 1";
                break;

            case "consultaNivelesPlan":
                $cadena_sql="SELECT DISTINCT id_nivel NIVEL";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."planEstudio_espacio";
                $cadena_sql.=" WHERE id_planEstudio=".$variable;
                $cadena_sql.=" AND id_clasificacion!=4";
                $cadena_sql.=" AND id_clasificacion!=5";
                $cadena_sql.=" AND id_estado=1";
                $cadena_sql.=" AND id_aprobado=1";
                $cadena_sql.=" ORDER BY id_nivel";
                break;

            case "consultarEspaciosAsociados":
                $cadena_sql="SELECT DISTINCT id_espacio";
                $cadena_sql.=" FROM sga_espacioEncabezado";
                $cadena_sql.=" WHERE id_planEstudio=".$variable;
                $cadena_sql.=" AND id_estado=1";
                $cadena_sql.=" AND id_aprobado=1";
                $cadena_sql.=" ORDER BY id_espacio";
              break;

            case "consultarEncabezado":
                $cadena_sql="SELECT cabeza.encabezado_nivel NIVEL,";
                $cadena_sql.=" cabeza.id_encabezado ID_ENC,";
                $cadena_sql.=" cabeza.encabezado_nombre NOMBRE_ENC,";
                $cadena_sql.=" cabeza.encabezado_creditos CRED_ENC,";
                $cadena_sql.=" cabeza.id_clasificacion COD_CLASIF_ENC,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIF_ENC,";
                $cadena_sql.=" EE.id_espacio ID_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nroCreditos CRED,";
                $cadena_sql.=" PLAN_ESPACIO.horasDirecto HTD,";
                $cadena_sql.=" PLAN_ESPACIO.horasCooperativo HTC,";
                $cadena_sql.=" ESPACIO.espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIF_ESP,";
                $cadena_sql.=" PLAN_ESPACIO.id_clasificacion ID_CLASIF_ESP,";
                $cadena_sql.=" PLAN_ESPACIO.semanas SEM";
                $cadena_sql.=" FROM sga_encabezado as cabeza";
                $cadena_sql.=" LEFT JOIN sga_espacio_clasificacion AS CLASIFICACION ON CLASIFICACION.id_clasificacion = cabeza.id_clasificacion";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacioEncabezado AS EE ON cabeza.id_encabezado=EE.id_encabezado AND cabeza.id_planEstudio=EE.id_planEstudio";
                $cadena_sql.=" LEFT JOIN sga_espacio_academico AS ESPACIO ON ESPACIO.id_espacio = EE.id_espacio";
                $cadena_sql.=" LEFT JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio AND PLAN_ESPACIO.id_planEstudio=cabeza.id_planEstudio";
                $cadena_sql.=" WHERE cabeza.id_planEstudio=".$variable;
                $cadena_sql.=" AND cabeza.id_estado=1";
                $cadena_sql.=" AND cabeza.id_aprobado=1";
                $cadena_sql.=" AND (EE.id_estado=1 or EE.id_estado is null)";
                $cadena_sql.=" AND (EE.id_aprobado=1 or EE.id_aprobado is null)";
                $cadena_sql.=" AND (PLAN_ESPACIO.id_estado=1 or PLAN_ESPACIO.id_estado is null)";
                $cadena_sql.=" AND (PLAN_ESPACIO.id_aprobado=1 or PLAN_ESPACIO.id_estado is null)";
                $cadena_sql.=" ORDER BY cabeza.encabezado_nivel,cabeza.id_encabezado, EE.id_espacio";

                break;

            case "consultarEncabezadoPropedeutico":
                $cadena_sql="SELECT cabeza.encabezado_nivel NIVEL,";
                $cadena_sql.=" cabeza.id_encabezado ID_ENC,";
                $cadena_sql.=" cabeza.encabezado_nombre NOMBRE_ENC,";
                $cadena_sql.=" cabeza.encabezado_creditos CRED_ENC,";
                $cadena_sql.=" cabeza.id_clasificacion COD_CLASIF_ENC,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIF_ENC,";
                $cadena_sql.=" EE.id_espacio ID_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nroCreditos CRED,";
                $cadena_sql.=" PLAN_ESPACIO.horasDirecto HTD,";
                $cadena_sql.=" PLAN_ESPACIO.horasCooperativo HTC,";
                $cadena_sql.=" ESPACIO.espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIF_ESP,";
                $cadena_sql.=" PLAN_ESPACIO.id_clasificacion ID_CLASIF_ESP,";
                $cadena_sql.=" PLAN_ESPACIO.semanas SEM";
                $cadena_sql.=" FROM sga_encabezado as cabeza";
                $cadena_sql.=" LEFT JOIN sga_espacio_clasificacion AS CLASIFICACION ON CLASIFICACION.id_clasificacion = cabeza.id_clasificacion";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacioEncabezado AS EE ON cabeza.id_encabezado=EE.id_encabezado AND cabeza.id_planEstudio=EE.id_planEstudio";
                $cadena_sql.=" LEFT JOIN sga_espacio_academico AS ESPACIO ON ESPACIO.id_espacio = EE.id_espacio";
                $cadena_sql.=" LEFT JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio AND PLAN_ESPACIO.id_planEstudio=cabeza.id_planEstudio";
                $cadena_sql.=" WHERE cabeza.id_clasificacion=5";
                $cadena_sql.=" AND cabeza.id_planEstudio=".$variable;
                $cadena_sql.=" AND cabeza.id_estado=1";
                $cadena_sql.=" AND cabeza.id_aprobado=1";
                $cadena_sql.=" AND (EE.id_estado=1 or EE.id_estado is null)";
                $cadena_sql.=" AND (EE.id_aprobado=1 or EE.id_aprobado is null)";
                $cadena_sql.=" AND (PLAN_ESPACIO.id_estado=1 or PLAN_ESPACIO.id_estado is null)";
                $cadena_sql.=" AND (PLAN_ESPACIO.id_aprobado=1 or PLAN_ESPACIO.id_estado is null)";
                $cadena_sql.=" ORDER BY cabeza.encabezado_nivel,cabeza.id_encabezado, EE.id_espacio";
                break;

            case "consultarEspacios":
                $cadena_sql="SELECT PLAN_ESPACIO.id_nivel NIVEL,";
                $cadena_sql.=" ESPACIO.id_espacio COD_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESP,";
                $cadena_sql.=" espacio_nroCreditos CRED_ESP,";
                $cadena_sql.=" horasDirecto HTD,";
                $cadena_sql.=" horasCooperativo HTC,";
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre NOMBRE_CLASIF,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion ID_CLASIF,";
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS";
                $cadena_sql.=" FROM sga_espacio_academico AS ESPACIO";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_planEstudio=".$variable['planEstudio'];
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_aprobado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion!=4";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion!=5";
                $cadena_sql.=" AND PLAN_ESPACIO.id_espacio not in (".$variable['lista'].")";
                $cadena_sql.=" ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, ESPACIO.espacio_nombre ASC";

                break;

            case "consultarPropedeutico":
                $cadena_sql="SELECT PLAN_ESPACIO.id_nivel NIVEL,";
                $cadena_sql.=" ESPACIO.id_espacio COD_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESP,";
                $cadena_sql.=" espacio_nroCreditos CRED_ESP,";
                $cadena_sql.=" horasDirecto HTD,";
                $cadena_sql.=" horasCooperativo HTC,";
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre NOMBRE_CLASIF,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion ID_CLASIF,";
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS";
                $cadena_sql.=" FROM sga_espacio_academico AS ESPACIO";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_planEstudio=".$variable['planEstudio'];
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_aprobado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion=5";
                $cadena_sql.=" AND PLAN_ESPACIO.id_espacio not in (".$variable['lista'].")";
                $cadena_sql.=" ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, ESPACIO.espacio_nombre ASC";
                break;

            case 'buscarParametros':
                $cadena_sql="SELECT DISTINCT parametro_creditosPlan TOTAL,";
                $cadena_sql.=" parametros_OB OB,";
                $cadena_sql.=" parametros_OC OC,";
                $cadena_sql.=" parametros_EI EI,";
                $cadena_sql.=" parametros_EE EE,";
                $cadena_sql.=" parametros_CP CP";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."parametro_plan_estudio";
                $cadena_sql.=" WHERE parametro_idPlanEstudio=".$variable;
                break;

            case "consultaEspacioPlan":
                $cadena_sql="SELECT ESPACIO.id_espacio, ";
                $cadena_sql.="ESPACIO.espacio_nombre, ";
                $cadena_sql.="PLAN_ESPACIO.id_nivel, ";
                $cadena_sql.="espacio_nroCreditos, ";
                $cadena_sql.="horasDirecto, ";        //tabla planEstudioEspacio
                $cadena_sql.="horasCooperativo, ";    //tabla planEstudioEspacio
                $cadena_sql.="espacio_horasAutonomo, ";
                $cadena_sql.="CLASIFICACION.clasificacion_nombre, ";
                $cadena_sql.="CLASIFICACION.id_clasificacion, ";
                $cadena_sql.="REL_ELECTIVO.id_nombreElectivo, ";  //registro[9]
                $cadena_sql.="ELECTIVO.nombreElectivo, ";
                $cadena_sql.="PLAN_ESPACIO.id_aprobado, ";        //registro[11]
                $cadena_sql.="PLAN_ESPACIO.id_planEstudio, ";      //registro[12]
                $cadena_sql.="PLAN_ESPACIO.semanas ";      //registro[13]
                $cadena_sql.="FROM sga_espacio_academico AS ESPACIO ";
                $cadena_sql.="INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.="INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ";
                $cadena_sql.="ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.="LEFT OUTER JOIN sga_espacioNombreElectivo AS REL_ELECTIVO ";
                $cadena_sql.="ON PLAN_ESPACIO.id_espacio = REL_ELECTIVO.id_espacio ";
                $cadena_sql.="LEFT OUTER JOIN sga_nombreElectivo AS ELECTIVO ";
                $cadena_sql.="ON REL_ELECTIVO.id_nombreElectivo = ELECTIVO.id_nombreElectivo ";
                $cadena_sql.="WHERE PLAN_ESPACIO.id_planEstudio=".$variable." ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1 ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_aprobado=1 ";
                //$cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion!=4 ";
                $cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =".$variable." ) ";
                $cadena_sql.="ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, REL_ELECTIVO.id_nombreElectivo, ESPACIO.espacio_nombre ASC";
                break;

            case "buscar_id":
                $cadena_sql="SELECT ";
                $cadena_sql.="id_planEstudio PLAN,";//0
                $cadena_sql.="planEstudio_ano ANO, ";//1
                $cadena_sql.="planEstudio_periodo PERIODO, ";//2
                $cadena_sql.="planEstudio_descripcion DESCRIPCION, ";//3
                $cadena_sql.="planEstudio_autor AUTOR, ";//4
                $cadena_sql.="planEstudio_niveles NIVELES, ";//5
                $cadena_sql.="planEstudio_fechaCreacion FECHA, ";//6
                $cadena_sql.="PROYECTO.proyecto_nombre PROYECTO_NOMBRE, ";//7
                $cadena_sql.="planEstudio_nombre PLAN_NOMBRE, ";//8
                $cadena_sql.="planEstudio_observaciones PLAN_OBS, ";//9
                $cadena_sql.="PROYECTO.id_proyectoCurricular COD_PROYECTO, ";//10
                $cadena_sql.="PROYECTO.id_proyectoAcademica COD_PROYECTO_ACADEMICA, ";//11
                $cadena_sql.="planEstudio_propedeutico PLAN_PROPEDEUTICO ";//12
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="planEstudio AS PLAN ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="proyectoCurricular AS PROYECTO ";
                $cadena_sql.="ON PLAN.id_proyectoCurricular=";
                $cadena_sql.="PROYECTO.id_proyectoCurricular ";
                $cadena_sql.="WHERE PLAN.id_planEstudio=".$variable;
                //$cadena_sql.=" AND PLAN.id_aprobado!=2";
                break;

            case 'facultad':
                $cadena_sql="SELECT dep_nombre FACULTAD";
                $cadena_sql.=" FROM gedep";
                $cadena_sql.=" WHERE dep_cod=".$variable;
                break;


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
