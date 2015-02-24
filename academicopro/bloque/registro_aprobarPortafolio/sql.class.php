<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registro_aprobarPortafolio extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="", $variable2="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            case "buscar_facultad":

                $cadena_sql="SELECT id_facultad, nombre_facultad ";
                $cadena_sql.="FROM sga_facultad ";
                $cadena_sql.="order by id_facultad ";
                break;

            case "parametrosPlan":
                $cadena_sql="select parametros_OB, parametros_OC,parametros_EI,parametros_EE,parametro_creditosPlan, parametros_aprobado ";
                $cadena_sql.="from sga_parametro_plan_estudio where parametro_idPlanEstudio=".$variable;//echo $busqueda;
                break;

            case "comentarioAprobar":

                $cadena_sql="INSERT INTO ".$configuracion["prefijo"]."comentario_general_planEstudio " ;
                $cadena_sql.="(comentario_idPlanEstudio, ";
                $cadena_sql.="comentario_idProyectoCurricular, ";
                $cadena_sql.="comentario_usuario, ";
                $cadena_sql.="comentario_fecha, ";
                $cadena_sql.="comentario_leidoAsesorVice, ";
                $cadena_sql.="comentario_leidoCoordinador, ";
                $cadena_sql.="comentario_descripcion) ";
                $cadena_sql.="VALUES (";
                $cadena_sql.="'".$variable[0]."', ";
                $cadena_sql.="'".$variable[1]."', ";
                $cadena_sql.="'".$variable[2]."', ";
                $cadena_sql.="'".$variable[3]."', ";
                $cadena_sql.="'1', ";
                $cadena_sql.="'0', ";
                $cadena_sql.="'".$variable[4]."')";
                break;
            
            case "aprobarEspacio":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_aprobado=1, ";
                $cadena_sql.="ofrecido_portafolio=1 ";
                $cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $cadena_sql.="AND id_espacio=".$variable;
                break;

            case "NoaprobarEspacio":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_aprobado=2 ";
                $cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $cadena_sql.="AND id_espacio=".$variable;
                break;

            case "DesaprobarEspacio":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_aprobado=0, ";
                $cadena_sql.="ofrecido_portafolio=0 ";
                $cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $cadena_sql.="AND id_espacio=".$variable;
                break;
            
            case "datosEspacio":
                $cadena_sql="SELECT espacio_nombre, espacio_nroCreditos, espacio_horasDirecto, espacio_horasCooperativo, espacio_horasAutonomo ";
                $cadena_sql.=" FROM ".$configuracion["prefijo"]."espacio_academico ";
                $cadena_sql.="WHERE id_espacio=".$variable;
                break;

            case "datosCarrera":

                $cadena_sql="SELECT id_facultad_academica, id_proyectoAcademica ";
                $cadena_sql.=" FROM sga_proyectoCurricular PC ";
                $cadena_sql.=" INNER JOIN sga_planEstudio_proyecto PEP ON PC.id_proyectoAcademica = PEP.planEstudioProyecto_idProyectoCurricular ";
                $cadena_sql.="WHERE PEP.planEstudioProyecto_idPlanEstudio =".$variable2;
                break;

            case "datosNumeroCarreras":

                $cadena_sql="SELECT count(*) ";
                $cadena_sql.=" FROM sga_proyectoCurricular PC ";
                $cadena_sql.=" INNER JOIN sga_planEstudio_proyecto PEP ON PC.id_proyectoAcademica = PEP.planEstudioProyecto_idProyectoCurricular";
                $cadena_sql.=" WHERE PEP.planEstudioProyecto_idPlanEstudio =".$variable2;
                break;

            case "datosCarreraParametros":

                $cadena_sql="select distinct cra_dep_cod, cra_emp_nro_iden, cra_estado, cra_cod, cra_nombre ";
                $cadena_sql.=" FROM accra ";
                $cadena_sql.=" inner join acpen on pen_cra_cod= cra_cod ";
                $cadena_sql.="where pen_nro=".$variable;
                break;

            case 'actualizarParametros':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."parametro_plan_estudio SET ";
                $cadena_sql.=" parametro_creditosPlan = ".$variable[1];
                $cadena_sql.=" ,parametros_OB  = ".$variable[5];
                $cadena_sql.=" ,parametros_OC = ".$variable[6];
                $cadena_sql.=" ,parametros_EI = ".$variable[7];
                $cadena_sql.=" ,parametros_EE = ".$variable[8];
                $cadena_sql.=" ,parametros_aprobado = '1'";
                $cadena_sql.=" WHERE  parametro_idPlanEstudio = ".$variable[0];
                break;

            case "cargarEspacioAcasi":
                $cadena_sql="INSERT INTO ACASI(ASI_COD, ASI_NOMBRE, ASI_DEP_COD, ASI_ESTADO, ASI_IND_CRED) " ;
                $cadena_sql.="VALUES( ";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."')";
                break;
            
            case "borrarEspacioAcasi":
                $cadena_sql="delete from acasi " ;
                $cadena_sql.="where ";
                $cadena_sql.="ASI_COD='".$variable[0]."'";
                break;

            case "buscarEspacioAcasi":
                $cadena_sql="select * from acasi " ;
                $cadena_sql.="where ";
                $cadena_sql.="ASI_COD='".$variable[0]."'";
                break;

            case "buscarEspacioAcpen":
                $cadena_sql="select * from acpen " ;
                $cadena_sql.="where ";
                $cadena_sql.="PEN_ASI_COD='".$variable[1]."'";
                $cadena_sql.=" AND PEN_NRO='".$variable[8]."'";
                break;

            case "buscarAsociacion";
                $cadena_sql="SELECT EE.id_planEstudio,";
                $cadena_sql.=" EE.id_proyectoCurricular,";
                $cadena_sql.=" EE.id_espacio,";
                $cadena_sql.=" EE.id_encabezado,";
                $cadena_sql.=" EE.id_estado,";
                $cadena_sql.=" EE.id_aprobado";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."espacioEncabezado EE";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."encabezado E on E.id_encabezado=EE.id_encabezado and E.id_planEstudio=EE.id_planEstudio";
                $cadena_sql.=" WHERE id_espacio=".$variable[8];
                $cadena_sql.=" AND EE.id_planEstudio=".$variable[0];
                $cadena_sql.=" AND EE.id_estado=1";
                $cadena_sql.=" AND E.id_estado=1";
              break;

            case 'proyectos_involucrados':

                $cadena_sql="SELECT pen_nro,";
                $cadena_sql.=" cra_nombre,";
                $cadena_sql.=" cea_nom,";
                $cadena_sql.=" asi_dep_cod,";
                $cadena_sql.=" pen_estado";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" INNER JOIN acclasificacpen ON clp_asi_cod=pen_asi_cod and clp_cra_cod=pen_cra_cod and clp_pen_nro=pen_nro";
                $cadena_sql.=" INNER JOIN geclasificaespac ON cea_cod=clp_cea_cod";
                $cadena_sql.=" INNER JOIN acasi ON asi_cod=pen_asi_cod";
                $cadena_sql.=" INNER JOIN accra ON pen_cra_cod=cra_cod";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable[8];
                $cadena_sql.=" AND pen_estado like '%A%'";
                $cadena_sql.=" ORDER BY asi_dep_cod,pen_nro";
            break;

            case "cargarEspacioAcpen":
                $cadena_sql="INSERT INTO ACPEN(PEN_CRA_COD, PEN_ASI_COD, PEN_SEM, PEN_IND_ELE, PEN_NRO_HT, PEN_NRO_HP, PEN_ESTADO, PEN_CRE, PEN_NRO,PEN_NRO_AUT) " ;
                $cadena_sql.="VALUES( ";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."',";
                $cadena_sql.="'".$variable[6]."',";
                $cadena_sql.="'".$variable[7]."',";
                $cadena_sql.="'".$variable[8]."',";
                $cadena_sql.="'".$variable[9]."')";
                break;

            case "cargarEspacioAcclasificacpen":
                $cadena_sql="INSERT INTO ACCLASIFICACPEN (CLP_CRA_COD, CLP_ASI_COD, CLP_PEN_NRO, CLP_CEA_COD, CLP_ESTADO) " ;
                $cadena_sql.="VALUES( ";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[8]."',";
                $cadena_sql.="'".$variable2."',";
                $cadena_sql.="'".$variable[6]."')";
                break;

            case "estadocargarEspacio":
                $cadena_sql="UPDATE ".$configuracion["prefijo"];
                $cadena_sql.="planEstudio_espacio ";
                $cadena_sql.="SET id_cargado=1 ";
                $cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $cadena_sql.="AND id_espacio=".$variable;
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

            case "buscar_id":
                $cadena_sql.="SELECT ";
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
                $cadena_sql.="WHERE PLAN.id_planEstudio=".$variable ;
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
                $cadena_sql="SELECT * FROM ".$configuracion["prefijo"]."comentario_espacio_planEstudio ";
                $cadena_sql.="WHERE `comentario_idEspacio`=".$variable;
                $cadena_sql.=" AND `comentario_idPlanEstudio` =".$variable2;
                $cadena_sql.=" AND `comentario_leidoAsesorVice` =0";
                break;

            case "consultarEncabezado":

                $cadena_sql="SELECT * ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="encabezado ";
                $cadena_sql.="WHERE id_planEstudio='".$variable[0]."'";
                $cadena_sql.=" AND encabezado_nivel='".$variable[1]."'";
                $cadena_sql.=" AND id_encabezado!='".$variable[2]."'";
                $cadena_sql.=" AND id_estado='1'";
                break;

            case "consultarEspaciosEncabezado":

                $cadena_sql="SELECT id_espacio, id_aprobado ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="espacioEncabezado ";
                $cadena_sql.="WHERE id_planEstudio='".$variable[1]."'";
                $cadena_sql.=" AND id_encabezado='".$variable[0]."'";
                $cadena_sql.=" AND id_estado=1";
                break;
        
            case "mensajeGeneral":

                $cadena_sql="SELECT count(*) ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="comentario_general_planEstudio ";
                $cadena_sql.="WHERE comentario_idPlanEstudio='".$variable."'";
                $cadena_sql.=" AND comentario_leidoAsesorVice='0'";
                break;

            case "mensajeEspacios":

                $cadena_sql="SELECT comentario_idEspacio ";
                $cadena_sql.="FROM ".$configuracion["prefijo"];
                $cadena_sql.="comentario_espacio_planEstudio ";
                $cadena_sql.="WHERE comentario_idPlanEstudio='".$variable."'";
                $cadena_sql.=" AND comentario_leidoAsesorVice='0'";
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
                break;

            case "modificarEspacio_notas":
                $cadena_sql="SELECT count(*) FROM ACNOT";
                $cadena_sql.=" WHERE not_asi_cod=".$variable;
                break;

            case "modificarEspacio_inscripcion":
                $cadena_sql="SELECT count(*) FROM ACINS ";
                $cadena_sql.=" WHERE ins_asi_cod=".$variable;
                break;

            case "modificarEspacio_horario":
                $cadena_sql="SELECT count(*) FROM achorario ";
                $cadena_sql.=" WHERE hor_asi_cod=".$variable;
                break;

            case 'buscarParametros':

                $cadena_sql="select distinct parametro_creditosPlan,parametros_OB,parametros_OC,parametros_EI,parametros_EE ";
                $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio ";
                $cadena_sql.=" where parametro_idPlanEstudio=".$variable;
                break;

            case 'clasificacion':

                $cadena_sql="SELECT  id_clasificacion, clasificacion_nombre ";
                $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_clasificacion ";
                //$cadena_sql.="where id_clasificacion!=5 ";
            break;

            case "bimestreActual":

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi ";
                $cadena_sql.="WHERE ape_estado like '%A%'";
            break;

        case 'actualizar_espacioAcademico':

            $cadena_sql="UPDATE ".$configuracion['prefijo']."espacio_academico SET ";
            $cadena_sql.="espacio_nombre ='".$variable[2]."'";
            $cadena_sql.=", espacio_nroCreditos='".$variable[3]."'";
            $cadena_sql.=", espacio_horasDirecto='".$variable[5]."'";
            $cadena_sql.=", espacio_horasCooperativo='".$variable[6]."'";
            $cadena_sql.=", espacio_horasAutonomo='".$variable[7]."'";
            $cadena_sql.=" WHERE id_espacio='".$variable[8]."'";
        break;

        case 'actualizar_planEstudio':

            $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
            $cadena_sql.=" id_nivel='".$variable[4]."'";
            $cadena_sql.=", id_clasificacion='".$variable[1]."'";
            $cadena_sql.=", horasDirecto='".$variable[5]."'";
            $cadena_sql.=", horasCooperativo='".$variable[6]."'";
            $cadena_sql.=", semanas='".$variable[9]."'";
            $cadena_sql.=" WHERE ";//id_planEstudio='".$variable[0]."' AND";
            $cadena_sql.=" id_espacio='".$variable[8]."'";
        break;

        case 'registroModificarEA':

             $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
             $cadena_sql.="VALUES(0, '".$variable[0]."', ";
             $cadena_sql.="'".$variable[1]."', ";
             $cadena_sql.="'19', ";
             $cadena_sql.="'Modifica Espacio Extrinseco Asesor', ";
             $cadena_sql.="'".$variable[2]."-".$variable[3].", ";
             $cadena_sql.=$variable[4].", 0, 0, ".$variable[5].", ".$variable[6]."', ";
             $cadena_sql.="'".$variable[5]."')";
             break;

            case "buscarEspacio_acpen":
                $cadena_sql="SELECT * FROM acpen ";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable[8];
                $cadena_sql.=" AND pen_nro=".$variable[0];
                break;

            case "actualizarEspacio_acpen":
                $cadena_sql="UPDATE acpen SET pen_cre='".$variable[3]."', pen_sem='".$variable[4]."', pen_nro_ht='".$variable[5]."', pen_nro_hp='".$variable[6]."', pen_nro_aut='".$variable[7]."'";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable[8];
                //$cadena_sql.=" AND pen_nro=".$variable[0];
                break;

            case "buscarEspacio_acasi":
                $cadena_sql="SELECT * FROM acasi ";
                $cadena_sql.=" WHERE asi_cod=".$variable[8];
                break;

            case "actualizarEspacio_acasi":
                $cadena_sql="UPDATE acasi SET asi_nombre='".$variable[10]."'";
                $cadena_sql.=" WHERE asi_cod='".$variable[8]."'";
                break;

            case 'buscarEspacioComunOracle':
                $cadena_sql="select * from acpen where pen_asi_cod=".$variable[8];
                $cadena_sql.=" and pen_estado like '%A%' ";
                break;

            case 'buscarEventoGestionPlanes':
                $cadena_sql=" select to_char(ace_fec_ini, 'yyyymmdd') INICIO,";
                $cadena_sql.=" to_char(ace_fec_fin, 'yyyymmdd') FIN";
                $cadena_sql.=" from accaleventos";
                $cadena_sql.=" where ace_anio=".$variable['ano'];
                $cadena_sql.=" and ace_periodo=".$variable['periodo'];
                $cadena_sql.=" and ace_cra_cod=0";
                $cadena_sql.=" and ace_cod_evento=86";
                break;
            
        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
