<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroNoAprobarEspacioAsisVice extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

    function cadena_sql($configuracion,$tipo,$variable="") {

        switch($tipo) {

            case "buscar_id":
                $this->cadena_sql.="SELECT ";
                $this->cadena_sql.="id_planEstudio,";
                $this->cadena_sql.="planEstudio_ano, ";
                $this->cadena_sql.="planEstudio_periodo,";
                $this->cadena_sql.="planEstudio_descripcion,";
                $this->cadena_sql.="planEstudio_autor, ";
                $this->cadena_sql.="planEstudio_niveles, ";
                $this->cadena_sql.="planEstudio_fechaCreacion,";
                $this->cadena_sql.="PROYECTO.proyecto_nombre,";
                $this->cadena_sql.="planEstudio_nombre, ";
                $this->cadena_sql.="planEstudio_observaciones, ";
                $this->cadena_sql.="PROYECTO.id_proyectoAcademica FROM ";
                $this->cadena_sql.=$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio AS PLAN ";
                $this->cadena_sql.="INNER JOIN ";
                $this->cadena_sql.=$configuracion["prefijo"];
                $this->cadena_sql.="proyectoCurricular AS PROYECTO ";
                $this->cadena_sql.="ON PLAN.id_proyectoCurricular=";
                $this->cadena_sql.="PROYECTO.id_proyectoCurricular ";
                $this->cadena_sql.="WHERE PLAN.id_planEstudio=".$variable ;

                break;

            case "DesaprobarEspacio":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio_espacio ";
                $this->cadena_sql.="SET id_aprobado=2 ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable[1]." ";
                $this->cadena_sql.="AND id_espacio=".$variable[0];
                
                break;

            case 'comentarioAutomaticoNoAprobo':

               $this->cadena_sql="INSERT INTO ".$configuracion['prefijo']."comentario_espacio_planEstudio ";
               $this->cadena_sql.="(comentario_idEspacio, comentario_idPlanEstudio, comentario_idProyectoCurricular, ";
               $this->cadena_sql.="comentario_usuario, comentario_fecha, comentario_leidoAsesorVice, comentario_leidoCoordinador, comentario_descripcion ) ";
               $this->cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', '".$variable[4]."',  ";
               $this->cadena_sql.="'1', '0',  'El espacio académico ".$variable[0]. " No fue aprobado.') ";

               break;


            case 'comentarioNoAprobar':
                
               $this->cadena_sql="INSERT INTO ".$configuracion['prefijo']."comentario_espacio_planEstudio ";
               $this->cadena_sql.="(comentario_idEspacio, comentario_idPlanEstudio, comentario_idProyectoCurricular, ";
               $this->cadena_sql.="comentario_usuario, comentario_fecha, comentario_leidoAsesorVice, comentario_leidoCoordinador, comentario_descripcion ) ";
               $this->cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', '".$variable[4]."',  ";
               $this->cadena_sql.="'1', '0',  '".$variable[5]."')";               

               break;
           
            case "bimestreActual":
                $this->cadena_sql="select ape_ano, ape_per " ;
                $this->cadena_sql.="from acasperi ";
                $this->cadena_sql.="where ape_estado like '%A%'";
                //                echo $this->cadena_sql;
                //                exit;

                break;
              

            case 'registroEvento':

                $this->cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $this->cadena_sql.="VALUES(0, '".$variable[0]."', ";
                $this->cadena_sql.="'".$variable[1]."', ";
                $this->cadena_sql.="'12', ";
                $this->cadena_sql.="'No aprobo Espacio Académico', ";
                $this->cadena_sql.="'".$variable[2]."-".$variable[3].",";
                $this->cadena_sql.=" ".$variable[4].", ".$variable[5].", ".$variable[6]."', ";
                $this->cadena_sql.="'".$variable[5]."')";

                break;            

           //señal
            case "datosEspacio":
                $this->cadena_sql="SELECT espacio_nombre ";
                $this->cadena_sql.=" FROM ".$configuracion["prefijo"]."espacio_academico ";
                $this->cadena_sql.="WHERE id_espacio=".$variable[0];               

                break;

            case "datosCarrera":

                $this->cadena_sql="select distinct cra_dep_cod, cra_emp_nro_iden, cra_estado, cra_cod ";
                $this->cadena_sql.=" FROM accra ";
                $this->cadena_sql.=" inner join acpen on pen_cra_cod= cra_cod ";
                $this->cadena_sql.="where pen_nro=".$variable2;
                //echo $this->cadena_sql;
                //exit;

                break;

            case "cargarEspacioAcasi":
                $this->cadena_sql="INSERT INTO ACASI(ASI_COD, ASI_NOMBRE, ASI_DEP_COD, ASI_ESTADO, ASI_IND_CRED) " ;
                $this->cadena_sql.="VALUES( ";
                $this->cadena_sql.="'".$variable[0]."',";
                $this->cadena_sql.="'".$variable[1]."',";
                $this->cadena_sql.="'".$variable[2]."',";
                $this->cadena_sql.="'".$variable[3]."',";
                $this->cadena_sql.="'".$variable[4]."')";
                //                echo $this->cadena_sql;
                //                exit;

                break;

            case "borrarEspacioAcasi":
                $this->cadena_sql="delete from acasi " ;
                $this->cadena_sql.="where ";
                $this->cadena_sql.="ASI_COD='".$variable[0]."'";
                //                echo $this->cadena_sql;
                //                exit;

                break;

            case "cargarEspacioAcpen":
                $this->cadena_sql="INSERT INTO ACPEN(PEN_CRA_COD, PEN_ASI_COD, PEN_SEM, PEN_IND_ELE, PEN_NRO_HT, PEN_NRO_HP, PEN_ESTADO, PEN_CRE, PEN_NRO) " ;
                $this->cadena_sql.="VALUES( ";
                $this->cadena_sql.="'".$variable[0]."',";
                $this->cadena_sql.="'".$variable[1]."',";
                $this->cadena_sql.="'".$variable[2]."',";
                $this->cadena_sql.="'".$variable[3]."',";
                $this->cadena_sql.="'".$variable[4]."',";
                $this->cadena_sql.="'".$variable[5]."',";
                $this->cadena_sql.="'".$variable[6]."',";
                $this->cadena_sql.="'".$variable[7]."',";
                $this->cadena_sql.="'".$variable[8]."')";
                //                echo $this->cadena_sql;
                //                exit;

                break;

            case "estadocargarEspacio":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio_espacio ";
                $this->cadena_sql.="SET id_cargado=1 ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable2." ";
                $this->cadena_sql.="AND id_espacio=".$variable;
                //echo $this->cadena_sql;
                //exit;

                break;
           

            case 'buscarIDRegistro':

                $this->cadena_sql="select id_log from ".$configuracion['prefijo']."log_eventos ";
                $this->cadena_sql.="where log_usuarioProceso='".$variable[0]."'";
                $this->cadena_sql.=" and log_evento='".$variable[2]."'";
                $this->cadena_sql.=" and log_registro='".$variable[4]."'";
                $this->cadena_sql.=" and log_usuarioAfectado='".$variable[5]."'";

                break;

            case "listaPlanesEstudio":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="id_planEstudio,";
                $this->cadena_sql.="planEstudio_nombre,";
                $this->cadena_sql.="planEstudio_ano,";
                $this->cadena_sql.="planEstudio_periodo,";
                $this->cadena_sql.="planEstudio_niveles,";
                $this->cadena_sql.="planEstudio_fechaCreacion, ";
                $this->cadena_sql.="id_proyectoCurricular ";
                $this->cadena_sql.="FROM ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio ";
                $this->cadena_sql.="WHERE id_estado=1";
                break;

            case "listaCarrera":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="id_proyectoCurricular ";
                $this->cadena_sql.="FROM ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio ";
                $this->cadena_sql.="WHERE id_planEstudio='".$variable."'";
                break;

            case "listaEspacios":
                $this->cadena_sql="SELECT ";
                $this->cadena_sql.="ESPACIO.id_espacio, ";
                $this->cadena_sql.="ESPACIO.espacio_nombre, ";
                $this->cadena_sql.="CLASIF.clasificacion_abrev, ";
                $this->cadena_sql.="ESPACIO.espacio_nroCreditos, ";
                $this->cadena_sql.="PLAN_ESPACIO.horasDirecto,";
                $this->cadena_sql.="PLAN_ESPACIO.horasCooperativo,";
                $this->cadena_sql.="ESPACIO.espacio_horasAutonomo, ";
                $this->cadena_sql.="PLAN_ESPACIO.id_aprobado ";
                $this->cadena_sql.="FROM ";
                $this->cadena_sql.=$configuracion["prefijo"]."espacio_academico ";
                $this->cadena_sql.="AS ESPACIO ";
                $this->cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio_espacio AS PLAN_ESPACIO ";
                $this->cadena_sql.="ON ESPACIO.id_espacio=PLAN_ESPACIO.id_espacio ";
                $this->cadena_sql.="INNER JOIN ".$configuracion["prefijo"];
                $this->cadena_sql.="espacio_clasificacion AS CLASIF ON ";
                $this->cadena_sql.="CLASIF.id_clasificacion=PLAN_ESPACIO.id_clasificacion ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable." ";
                $this->cadena_sql.="ORDER BY ESPACIO.espacio_nombre";
                break;

            case "obsAprobacionEspacios":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"]."planEstudio ";
                $this->cadena_sql.="SET planEstudio_obsVicerrectoria='".$variable[3]."', ";
                $this->cadena_sql.="planEstudio_obsOas='".$variable[4]."' ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable[0];

                break;

            case "comentariosNoLeidos":
                $this->cadena_sql="SELECT * FROM ".$configuracion["prefijo"]."comentario_espacio_planEstudio ";
                $this->cadena_sql.="WHERE `comentario_idEspacio`=".$variable;
                $this->cadena_sql.=" AND `comentario_idPlanEstudio` =".$variable2;
                $this->cadena_sql.=" AND `comentario_leidoAsesorVice` =0";

                break;           


        }#Cierre de switch
        return $this->cadena_sql;
}#Cierre de funcion cadena_sql
}#Cierre de clase
?>
