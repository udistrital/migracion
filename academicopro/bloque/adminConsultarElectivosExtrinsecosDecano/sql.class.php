<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarElectivosExtrinsecosDecano extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
  public $configuracion;

  public function __construct($configuracion) {
    $this->configuracion=$configuracion;
  }

  function cadena_sql($tipo,$variable="", $variable2="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            case "consultarPlan":

                $cadena_sql="select est_cra_cod CARRERA,";
                $cadena_sql.=" est_pen_nro PLANESTUDIO,";
                $cadena_sql.=" cra_nombre NOMBRE_CARRERA";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=est_cra_cod";
                $cadena_sql.=" where est_cod=".$variable;
                //echo $cadena_sql;
                //exit;

                break;


            case "consultarExtrinsecosPlan":
                $cadena_sql="SELECT DISTINCT ESPACIO.id_espacio COD_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESP,";
                $cadena_sql.=" espacio_nroCreditos CRED_ESP,";
                $cadena_sql.=" horasDirecto HTD,";        //tabla planEstudioEspacio
                $cadena_sql.=" horasCooperativo HTC,";    //tabla planEstudioEspacio
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre NOMBRE_CLASIF,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion, ";
                $cadena_sql.=" PLAN_ESPACIO.id_aprobado, ";        //registro[11]
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio, ";      //registro[12]
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS";      //registro[13]
                $cadena_sql.=" FROM sga_espacio_academico AS ESPACIO ";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ";
                $cadena_sql.=" ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ";
                $cadena_sql.=" ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_planEstudio=".$variable." ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_aprobado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion=4 ";
                //$cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =".$variable." and id_estado=1 ) ";
                $cadena_sql.=" ORDER BY ESPACIO.id_espacio, ESPACIO.espacio_nombre ASC";
//                echo $cadena_sql;
//                exit;

                break;

            case "consultarExtrinsecosOtrosPlanes":
                $cadena_sql="SELECT DISTINCT ESPACIO.id_espacio COD_ESP,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESP,";
                $cadena_sql.=" espacio_nroCreditos CRED_ESP,";
                $cadena_sql.=" horasDirecto HTD,";        //tabla planEstudioEspacio
                $cadena_sql.=" horasCooperativo HTC,";    //tabla planEstudioEspacio
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre NOMBRE_CLASIF,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion, ";
                $cadena_sql.=" PLAN_ESPACIO.id_aprobado,";        //registro[11]
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio PLAN_ESTUDIOS,";      //registro[12]
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS,";      //registro[13]
                $cadena_sql.=" PROYECTO.proyecto_nombre PROYECTO,";
                $cadena_sql.=" PROYECTO.id_proyectoCurricular ID_PROYECTO,";
                $cadena_sql.=" FACULTAD.id_facultad ID_FACULTAD,";
                $cadena_sql.=" FACULTAD.nombre_facultad FACULTAD";
                $cadena_sql.=" FROM sga_espacio_academico AS ESPACIO";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO";
                $cadena_sql.=" ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION";
                $cadena_sql.=" ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" INNER JOIN sga_planEstudio PLAN ON PLAN.id_planEstudio=PLAN_ESPACIO.id_planEstudio";
                $cadena_sql.=" INNER JOIN sga_proyectoCurricular AS PROYECTO ON PROYECTO.id_proyectoCurricular=PLAN.id_proyectoCurricular";
                $cadena_sql.=" INNER JOIN sga_facultad FACULTAD ON FACULTAD.id_facultad=PROYECTO.id_facultad";
//                $cadena_sql.=" WHERE PLAN_ESPACIO.id_planEstudio!=".$variable;
//                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_aprobado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion=4 ";
                //$cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =".$variable." and id_estado=1 ) ";
                $cadena_sql.=" ORDER BY FACULTAD.id_facultad, PROYECTO.id_proyectoCurricular, ESPACIO.id_espacio, ESPACIO.espacio_nombre ASC";
//                echo $cadena_sql;
//                exit;
                break;



                


           case "listaPlanesEstudio":
                $cadena_sql="SELECT ";
                $cadena_sql.="id_planEstudio,";
                $cadena_sql.="planEstudio_nombre,";
                $cadena_sql.="planEstudio_ano,";
                $cadena_sql.="planEstudio_periodo,";
                $cadena_sql.="planEstudio_niveles,";
                $cadena_sql.="planEstudio_fechaCreacion, ";
                $cadena_sql.="id_proyectoCurricular ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio ";
                $cadena_sql.="WHERE id_estado=1";
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
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion=4 ";
                $cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =".$variable." ) ";
                $cadena_sql.="ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, REL_ELECTIVO.id_nombreElectivo, ESPACIO.espacio_nombre ASC";
//                echo $cadena_sql;
//                exit;

                break;

            case "buscar_id":
                $cadena_sql="SELECT ";
                $cadena_sql.="id_planEstudio,";
                $cadena_sql.="planEstudio_ano, ";
                $cadena_sql.="planEstudio_periodo,";
                $cadena_sql.="planEstudio_descripcion,";
                $cadena_sql.="planEstudio_autor, ";
                $cadena_sql.="planEstudio_niveles, ";
                $cadena_sql.="planEstudio_fechaCreacion,";
                $cadena_sql.="PROYECTO.proyecto_nombre,";
                $cadena_sql.="planEstudio_nombre, ";
                $cadena_sql.="planEstudio_observaciones FROM ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="planEstudio AS PLAN ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.=$configuracion["prefijo"];
                $cadena_sql.="proyectoCurricular AS PROYECTO ";
                $cadena_sql.="ON PLAN.id_proyectoCurricular=";
                $cadena_sql.="PROYECTO.id_proyectoCurricular ";
                $cadena_sql.="WHERE PLAN.id_planEstudio=".$variable;
//                echo $cadena_sql;
//                exit;
                break;


            case "listaEspacios":
                $cadena_sql="SELECT ";
                $cadena_sql.="ESPACIO.id_espacio, ";
                $cadena_sql.="ESPACIO.espacio_nombre, ";
                $cadena_sql.="CLASIF.clasificacion_abrev, ";
                $cadena_sql.="ESPACIO.espacio_nroCreditos, ";
                $cadena_sql.="PLAN_ESPACIO.horasDirecto,";
                $cadena_sql.="PLAN_ESPACIO.horasCooperativo,";
                $cadena_sql.="ESPACIO.espacio_horasAutonomo, ";
                $cadena_sql.="PLAN_ESPACIO.id_aprobado ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."espacio_academico ";
                $cadena_sql.="AS ESPACIO ";
                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio AS PLAN_ESPACIO ";
                $cadena_sql.="ON ESPACIO.id_espacio=PLAN_ESPACIO.id_espacio ";
                $cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
                $cadena_sql.="espacio_clasificacion AS CLASIF ON ";
                $cadena_sql.="CLASIF.id_clasificacion=PLAN_ESPACIO.id_clasificacion ";
                $cadena_sql.="WHERE id_planEstudio=".$variable." ";
                $cadena_sql.="ORDER BY ESPACIO.espacio_nombre";
                break;


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
