<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConfigurarPlanEstudioCoordinador extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="", $variable2="") {
        switch($tipo) {

            case 'proyectos_curriculares':

                $cadena_sql="select distinct cra_cod, cra_nombre ";//,pen_nro ";
                $cadena_sql.="from accra ";
                //$cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                //$cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                $cadena_sql.="where ";//pen_nro>200 ";
                $cadena_sql.=" CRA_EMP_NRO_IDEN=".$variable;
                $cadena_sql.=" ORDER BY 1";


                break;

            case 'planesCarrera':

                $cadena_sql="select distinct PEP.planEstudioProyecto_idPlanEstudio, PEP.planEstudioProyecto_idProyectoCurricular,	PE.planEstudio_nombre ";
                $cadena_sql.="from sga_planEstudio_proyecto PEP ";
                $cadena_sql.="inner join sga_planEstudio PE on PEP.planEstudioProyecto_idPlanEstudio=PE.id_planEstudio ";
                $cadena_sql.="where PEP.planEstudioProyecto_idProyectoCurricular=".$variable;


                break;

            case 'datos_coordinador':
                $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                $cadena_sql.="from accra ";
                //$cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
                $cadena_sql.="INNER JOIN ACPEN ON accra.cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.=" where cra_emp_nro_iden=".$variable;
//                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
                $cadena_sql.=" and pen_nro>200";
                $cadena_sql.=" AND PEN_ESTADO LIKE '%A%'";

            break;

// Se crea para presentar componente propedeutico 24/11/2011
            case "consultaNivelesPlan":
                $cadena_sql="SELECT DISTINCT id_nivel NIVEL";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."planEstudio_espacio";
                $cadena_sql.=" WHERE id_planEstudio=".$variable;
                $cadena_sql.=" AND id_clasificacion!=4";
                $cadena_sql.=" AND id_clasificacion!=5";
                $cadena_sql.=" AND id_estado=1";
                //$cadena_sql.=" AND id_aprobado=1";
                $cadena_sql.=" ORDER BY id_nivel";
                break;


// Se modifica nombrre (anterior es buscar_id) para presentar componente propedeutico 24/11/2011
            case "consultarDatosPlanEstudio":
                $cadena_sql="SELECT id_planEstudio COD_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_ano ANO_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_periodo PERIODO_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_descripcion DESCRIPCION_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_autor AUTOR_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_niveles NIVELES_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_fechaCreacion FECHA_PLAN_ESTUDIO,";
                $cadena_sql.=" PROYECTO.id_proyectoAcademica COD_PROYECTO,";
                $cadena_sql.=" PROYECTO.proyecto_nombre NOMBRE_PROYECTO_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_nombre NOMBRE_PLAN_ESTUDIO,";
                $cadena_sql.=" planEstudio_observaciones OBSERVACIONES_PLAN_ESTUDIO,";
                $cadena_sql.=" PROYECTO.id_proyectoCurricular ID_PROYECTO, ";
                $cadena_sql.=" planEstudio_propedeutico PLAN_PROPEDEUTICO ";
                $cadena_sql.=" FROM ".$configuracion["prefijo"]."planEstudio AS PLAN";
                $cadena_sql.=" INNER JOIN ".$configuracion["prefijo"]."proyectoCurricular AS PROYECTO";
                $cadena_sql.=" ON PLAN.id_proyectoCurricular=";
                $cadena_sql.=" PROYECTO.id_proyectoCurricular";
                $cadena_sql.=" WHERE PLAN.id_planEstudio=".$variable ;
                break;


#consulta de caracteristicas generales de espacios academicos en un plan de estudios,
//Se actualiza para presentar el componente propedeutico 24/11/2011
            case "consultaEspacioPlan":
                $cadena_sql="SELECT ESPACIO.id_espacio COD_ESPACIO,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_nivel NIVEL_ESPACIO,";
                $cadena_sql.=" espacio_nroCreditos CREDITOS_ESPACIO,";
                $cadena_sql.=" horasDirecto HTD,";        //tabla planEstudioEspacio
                $cadena_sql.=" horasCooperativo HTC,";    //tabla planEstudioEspacio
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIFICACION_ESPACIO,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion COD_CLASIFICACION_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_aprobado APROBADO_ESPACIO,";        //registro[11]
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio PLAN_ESPACIO,";      //registro[12]
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS_ESPACIO";      //registro[13]
                $cadena_sql.=" FROM sga_espacio_academico AS ESPACIO";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO";
                $cadena_sql.=" ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION";
                $cadena_sql.=" ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_planEstudio=".$variable['PLAN'];
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion!=4";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion!=5";
                $cadena_sql.=" AND PLAN_ESPACIO.id_espacio not in (".$variable['LISTA'].")";
                $cadena_sql.=" ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio";
//                exit;

                break;

            case "consultarEspaciosAsociados":
                $cadena_sql="SELECT DISTINCT id_espacio";
                $cadena_sql.=" FROM ".$configuracion["prefijo"]."espacioEncabezado";
                $cadena_sql.=" WHERE id_planEstudio =".$variable;
                $cadena_sql.=" AND id_estado=1";
                $cadena_sql.=" ORDER BY id_espacio";
                break;

// Se crea para presentar el componente propedeutico 24/11/2011
            case "consultarPropedeutico":
                $cadena_sql="SELECT ESPACIO.id_espacio COD_ESPACIO,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_nivel NIVEL_ESPACIO,";
                $cadena_sql.=" espacio_nroCreditos CREDITOS_ESPACIO,";
                $cadena_sql.=" horasDirecto HTD,";        //tabla planEstudioEspacio
                $cadena_sql.=" horasCooperativo HTC,";    //tabla planEstudioEspacio
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIFICACION_ESPACIO,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion COD_CLASIFICACION_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_aprobado APROBADO_ESPACIO,";        //registro[11]
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio PLAN_ESPACIO,";      //registro[12]
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS_ESPACIO";      //registro[13]
                $cadena_sql.=" FROM sga_espacio_academico AS ESPACIO";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO";
                $cadena_sql.=" ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION";
                $cadena_sql.=" ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_planEstudio=".$variable['PLAN'];
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion=5";
                $cadena_sql.=" AND PLAN_ESPACIO.id_espacio not in (".$variable['LISTA'].")";
                $cadena_sql.=" ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio";
                break;

//                exit;

//Se crea para presentar el componente propedeutico 24/11/2011
            case "consultarDatosEncabezadosEspacios":
                $cadena_sql="SELECT DISTINCT E.id_encabezado COD_ENCABEZADO,";
                $cadena_sql.=" E.encabezado_nombre NOMBRE_ENCABEZADO,";
                $cadena_sql.=" CLASIFICACION_ENC.clasificacion_nombre CLASIFICACION_ENCABEZADO,";
                $cadena_sql.=" E.id_clasificacion CLASIF_ENCABEZADO,";
                $cadena_sql.=" E.id_aprobado APROBADO_ENCABEZADO,";
                $cadena_sql.=" E.encabezado_creditos CREDITOS_ENCABEZADO,";
                $cadena_sql.=" E.encabezado_nivel NIVEL_ENCABEZADO,";
                $cadena_sql.=" EE.id_espacio COD_ESPACIO,";
                $cadena_sql.=" EE.id_aprobado APROBADO_ENC_ESPACIO,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_nivel NIVEL_ESPACIO,";
                $cadena_sql.=" espacio_nroCreditos CREDITOS_ESPACIO,";
                $cadena_sql.=" horasDirecto HTD,";
                $cadena_sql.=" horasCooperativo HTC,";
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIFICACION_ESPACIO,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion COD_CLASIFICACION_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_estado ESTADO_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_aprobado APROBADO_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio PLAN_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS_ESPACIO";
                $cadena_sql.=" FROM sga_encabezado AS E";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacioEncabezado AS EE ON E.id_encabezado=EE.id_encabezado AND E.id_planEstudio=EE.id_planEstudio AND E.id_proyectoCurricular=EE.id_proyectoCurricular AND EE.id_estado=1";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION_ENC ON CLASIFICACION_ENC.id_clasificacion = E.id_clasificacion";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacio_academico AS ESPACIO ON ESPACIO.id_espacio=EE.id_espacio";
                $cadena_sql.=" LEFT OUTER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio AND E.id_planEstudio=PLAN_ESPACIO.id_planEstudio";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacio_clasificacion AS CLASIFICACION ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" WHERE E.id_planEstudio=".$variable;
                $cadena_sql.=" AND E.id_estado=1";
                //$cadena_sql.=" AND (PLAN_ESPACIO.id_estado=1 or PLAN_ESPACIO.id_estado is null)";
                $cadena_sql.=" order by NIVEL_ENCABEZADO, COD_ENCABEZADO, COD_ESPACIO";
                break;

//Se crea para presentar el componente propedeutico 24/11/2011
            case "consultarDatosEncabezadosEspaciosPropedeuticos":
                $cadena_sql="SELECT DISTINCT E.id_encabezado COD_ENCABEZADO,";
                $cadena_sql.=" E.encabezado_nombre NOMBRE_ENCABEZADO,";
                $cadena_sql.=" CLASIFICACION_ENC.clasificacion_nombre CLASIFICACION_ENCABEZADO,";
                $cadena_sql.=" E.id_clasificacion CLASIF_ENCABEZADO,";
                $cadena_sql.=" E.id_aprobado APROBADO_ENCABEZADO,";
                $cadena_sql.=" E.encabezado_creditos CREDITOS_ENCABEZADO,";
                $cadena_sql.=" E.encabezado_nivel NIVEL_ENCABEZADO,";
                $cadena_sql.=" EE.id_espacio COD_ESPACIO,";
                $cadena_sql.=" EE.id_aprobado APROBADO_ENC_ESPACIO,";
                $cadena_sql.=" ESPACIO.espacio_nombre NOMBRE_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_nivel NIVEL_ESPACIO,";
                $cadena_sql.=" espacio_nroCreditos CREDITOS_ESPACIO,";
                $cadena_sql.=" horasDirecto HTD,";
                $cadena_sql.=" horasCooperativo HTC,";
                $cadena_sql.=" espacio_horasAutonomo HTA,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre CLASIFICACION_ESPACIO,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion COD_CLASIFICACION_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_aprobado APROBADO_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio PLAN_ESPACIO,";
                $cadena_sql.=" PLAN_ESPACIO.semanas SEMANAS_ESPACIO";
                $cadena_sql.=" FROM sga_encabezado AS E";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacioEncabezado AS EE ON E.id_encabezado=EE.id_encabezado AND E.id_planEstudio=EE.id_planEstudio AND E.id_proyectoCurricular=EE.id_proyectoCurricular ";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION_ENC ON CLASIFICACION_ENC.id_clasificacion = E.id_clasificacion";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacio_academico AS ESPACIO ON ESPACIO.id_espacio=EE.id_espacio";
                $cadena_sql.=" LEFT OUTER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio AND E.id_planEstudio=PLAN_ESPACIO.id_planEstudio";
                $cadena_sql.=" LEFT OUTER JOIN sga_espacio_clasificacion AS CLASIFICACION ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" WHERE E.id_planEstudio=".$variable['PLAN'];
                $cadena_sql.=" AND E.id_estado=1";
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" AND E.id_clasificacion=5";
                $cadena_sql.=" order by NIVEL_ENCABEZADO, COD_ENCABEZADO, COD_ESPACIO";
                break;

            case "aprobarEspacio":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_aprobado=1 ";
                $cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $cadena_sql.="AND id_espacio=".$variable;
                //exit;

                break;

            case "DesaprobarEspacio":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_aprobado=0 ";
                $cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $cadena_sql.="AND id_espacio=".$variable;
                //exit;

                break;
            
            case "datosEspacio":
                $cadena_sql="SELECT espacio_nombre, espacio_nroCreditos, espacio_horasDirecto, espacio_horasCooperativo, espacio_horasAutonomo ";
                $cadena_sql.=" FROM ".$configuracion["prefijo"]."espacio_academico ";
                $cadena_sql.="WHERE id_espacio=".$variable;
                //exit;

                break;

            case "datosCarrera":

                $cadena_sql="select distinct cra_dep_cod, cra_emp_nro_iden, cra_estado, cra_cod ";
                $cadena_sql.=" FROM accra ";
                $cadena_sql.=" inner join acpen on pen_cra_cod= cra_cod";
                $cadena_sql.=" where pen_nro=".$variable2;
                $cadena_sql.=" AND pen_estado like '%A%'";
                //exit;

                break;

            case "cargarEspacioAcasi":
                $cadena_sql="INSERT INTO ACASI(ASI_COD, ASI_NOMBRE, ASI_DEP_COD, ASI_ESTADO, ASI_IND_CRED) " ;
                $cadena_sql.="VALUES( ";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."')";
//                exit;

                break;
            
            case "borrarEspacioAcasi":
                $cadena_sql="delete from acasi " ;
                $cadena_sql.="where ";
                $cadena_sql.="ASI_COD='".$variable[0]."'";
//                exit;

                break;
            
            case "cargarEspacioAcpen":
                $cadena_sql="INSERT INTO ACPEN(PEN_CRA_COD, PEN_ASI_COD, PEN_SEM, PEN_IND_ELE, PEN_NRO_HT, PEN_NRO_HP, PEN_ESTADO, PEN_CRE, PEN_NRO) " ;
                $cadena_sql.="VALUES( ";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."',";
                $cadena_sql.="'".$variable[6]."',";
                $cadena_sql.="'".$variable[7]."',";
                $cadena_sql.="'".$variable[8]."')";
//                exit;

                break;

            case "estadocargarEspacio":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_cargado=1 ";
                $cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $cadena_sql.="AND id_espacio=".$variable;
                //exit;

                break;
            

            case 'registroEvento':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0,'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."')";

                break;

            case 'buscarIDRegistro':

                $cadena_sql="select id_log from ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="where log_usuarioProceso='".$variable[0]."'";
                $cadena_sql.=" and log_evento='".$variable[2]."'";
                $cadena_sql.=" and log_registro='".$variable[4]."'";
                $cadena_sql.=" and log_usuarioAfectado='".$variable[5]."'";

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

            case "listaCarrera":
                $cadena_sql="SELECT ";
                $cadena_sql.="id_proyectoCurricular ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio ";
                $cadena_sql.="WHERE id_planEstudio='".$variable."'";
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

            case "obsAprobacionEspacios":
                $cadena_sql="UPDATE ".$configuracion["prefijo"]."planEstudio ";
                $cadena_sql.="SET planEstudio_obsVicerrectoria='".$variable[3]."', ";
                $cadena_sql.="planEstudio_obsOas='".$variable[4]."' ";
                $cadena_sql.="WHERE id_planEstudio=".$variable[0];

                break;

            case "comentariosNoLeidos":
                $cadena_sql="SELECT count(*) FROM ".$configuracion["prefijo"]."comentario_espacio_planEstudio ";
                $cadena_sql.="WHERE comentario_idEspacio=".$variable['COD_ESPACIO'];
                $cadena_sql.=" AND comentario_idPlanEstudio=".$variable['PLAN_ESPACIO'];
                $cadena_sql.=" AND comentario_leidoCoordinador=0";

                break;

            case "consultarEncabezado":

                $cadena_sql="SELECT DISTINCT id_encabezado COD_ENCABEZADO,";
                $cadena_sql.=" encabezado_nombre NOMBRE_ENCABEZADO,";
                $cadena_sql.=" encabezado_descripcion DESC_ENCABEZADO,";
                $cadena_sql.=" id_planEstudio PLAN_ENCABEZADO,";
                $cadena_sql.=" id_proyectoCurricular PROYECTO_ENCABEZADO,";
                $cadena_sql.=" id_clasificacion CLASIF_ENCABEZADO,";
                $cadena_sql.=" id_estado ESTADO_ENCABEZADO,";
                $cadena_sql.=" id_aprobado APROBADO_ENCABEZADO,";
                $cadena_sql.=" id_cargado CARGADO_ENCABEZADO,";
                $cadena_sql.=" encabezado_creditos CREDITOS_ENCABEZADO,";
                $cadena_sql.=" encabezado_nivel NIVEL_ENCABEZADO";
                $cadena_sql.=" FROM ".$configuracion["prefijo"];
                $cadena_sql.="encabezado";
                $cadena_sql.=" WHERE id_planEstudio='".$variable[0]."'";
                $cadena_sql.=" AND encabezado_nivel='".$variable[1]."'";
                $cadena_sql.=" AND id_encabezado!='".$variable[2]."'";
                $cadena_sql.=" AND id_estado=1";
                //$cadena_sql.=" AND id_clasificacion!=4";
                break;

            case "consultarEspaciosEncabezado":

//                $cadena_sql="SELECT id_espacio, id_aprobado ";
//                $cadena_sql.="FROM ".$configuracion["prefijo"];
//                $cadena_sql.="espacioEncabezado ";
//                $cadena_sql.="WHERE id_planEstudio='".$variable[1]."'";
//                $cadena_sql.=" AND id_encabezado='".$variable[0]."'";
//                $cadena_sql.=" AND id_estado=1";
//                Oculta espacios electivos extrinsecos asociados a un encabezado extrinseco
                $cadena_sql="SELECT EE.id_espacio, EE.id_aprobado";
                $cadena_sql.=" FROM ".$configuracion["prefijo"]."espacioEncabezado EE";
                $cadena_sql.=" INNER JOIN ".$configuracion["prefijo"]."encabezado E ON EE.id_encabezado=E.id_encabezado";
                $cadena_sql.=" WHERE EE.id_planEstudio='".$variable[1]."'";
                $cadena_sql.=" AND EE.id_encabezado='".$variable[0]."'";
                $cadena_sql.=" AND EE.id_estado=1";
                $cadena_sql.=" AND E.id_clasificacion!=4";
                $cadena_sql.=" AND E.id_clasificacion!=5";

//                exit;
                break;

            case "datosEspacioOpcion":
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
                $cadena_sql.="WHERE PLAN_ESPACIO.id_espacio=".$variable[0]." ";
                $cadena_sql.=" AND PLAN_ESPACIO.id_planEstudio=".$variable[1]." ";
//                $cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =".$variable." ) ";
                $cadena_sql.="ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, REL_ELECTIVO.id_nombreElectivo, ESPACIO.espacio_nombre ASC";
//                exit;

                break;

            case "mensajeGeneral":

                $cadena_sql="SELECT count(*) ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="comentario_general_planEstudio ";
                $cadena_sql.="WHERE comentario_idPlanEstudio='".$variable."'";
                $cadena_sql.=" AND comentario_leidoCoordinador='0'";
                //$cadena_sql.=" AND id_estado=1";
//                exit;
                break;

            case 'buscarParametros':

                $cadena_sql="select distinct parametro_creditosPlan,parametros_OB,parametros_OC,parametros_EI,parametros_EE ";
                $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio ";
                $cadena_sql.=" where parametro_idPlanEstudio=".$variable;

                break;

        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
