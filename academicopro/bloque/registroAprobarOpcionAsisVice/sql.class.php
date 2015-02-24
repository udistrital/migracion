<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAprobarOpcionAsisVice extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

    function cadena_sql($configuracion,$tipo,$variable="") {

        switch($tipo) {

            case "buscar_id":
                $this->cadena_sql="SELECT ";
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

            case 'buscarEspacio':
                
               $this->cadena_sql="SELECT id_aprobado FROM ".$configuracion['prefijo']."planEstudio_espacio ";               
               $this->cadena_sql.="WHERE id_planEstudio=".$variable[1];
               $this->cadena_sql.=" AND id_espacio=".$variable[0];
               $this->cadena_sql.=" AND id_nivel=".$variable[6];

               break;

            case 'comentarioAprobar':
                
               $this->cadena_sql="INSERT INTO ".$configuracion['prefijo']."comentario_espacio_planEstudio ";
               $this->cadena_sql.="(comentario_idEspacio, comentario_idPlanEstudio, comentario_idProyectoCurricular, ";
               $this->cadena_sql.="comentario_usuario, comentario_fecha, comentario_leidoAsesorVice, comentario_leidoCoordinador, comentario_descripcion ) ";
               $this->cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', '".$variable[4]."',  ";
               $this->cadena_sql.="'1', '0',  '".$variable[5]."')";               

               break;

            case 'comentarioAutomatico':

               $this->cadena_sql="INSERT INTO ".$configuracion['prefijo']."comentario_espacio_planEstudio ";
               $this->cadena_sql.="(comentario_idEspacio, comentario_idPlanEstudio, comentario_idProyectoCurricular, ";
               $this->cadena_sql.="comentario_usuario, comentario_fecha, comentario_leidoAsesorVice, comentario_leidoCoordinador, comentario_descripcion ) ";
               $this->cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', '".$variable[4]."',  ";
               $this->cadena_sql.="'1', '0',  'Nombre General: ".$variable[5]." ,   Espacio Académico: ".$variable[0]."  -  ".$variable[12].", Créditos: ".$variable[7]." , ";
               $this->cadena_sql.="  H.T.D: ".$variable[8].",  H.T.C: ".$variable[9].",  H.T.A: ".$variable[10].",  Clasificación: ".$variable[11]." ')";

               break;
           
            case 'comentarioAutomaticoNoAprobo':

               $this->cadena_sql="INSERT INTO ".$configuracion['prefijo']."comentario_espacio_planEstudio ";
               $this->cadena_sql.="(comentario_idEspacio, comentario_idPlanEstudio, comentario_idProyectoCurricular, ";
               $this->cadena_sql.="comentario_usuario, comentario_fecha, comentario_leidoAsesorVice, comentario_leidoCoordinador, comentario_descripcion ) ";
               $this->cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', '".$variable[4]."',  ";
               $this->cadena_sql.="'1', '0',  'La asociación del espacio académico ".$variable[12]. " No fue aprobada. Nombre General: ".$variable[5]." ,   Espacio Académico: ".$variable[0]."  -  ".$variable[12].", Créditos: ".$variable[7]." , ";
               $this->cadena_sql.="  H.T.D: ".$variable[8].",  H.T.C: ".$variable[9].",  H.T.A: ".$variable[10].",  Clasificación: ".$variable[11]." ')";

               break;

            case 'comentarioEscrito':

               $this->cadena_sql="INSERT INTO ".$configuracion['prefijo']."comentario_espacio_planEstudio ";
               $this->cadena_sql.="(comentario_idEspacio, comentario_idPlanEstudio, comentario_idProyectoCurricular, ";
               $this->cadena_sql.="comentario_usuario, comentario_fecha, comentario_leidoAsesorVice, comentario_leidoCoordinador, comentario_descripcion ) ";
               $this->cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', '".$variable[4]."',  ";
               $this->cadena_sql.="'1', '0', '".$variable[5]."')";

               break;

            case "bimestreActual":
                $this->cadena_sql="select ape_ano, ape_per " ;
                $this->cadena_sql.="from acasperi ";
                $this->cadena_sql.="where ape_estado like '%A%'";
                //                echo $this->cadena_sql;
                //                exit;

                break;
              
            case "cambiarEstadoAsociacion":
                $this->cadena_sql="UPDATE ".$configuracion['prefijo']."espacioEncabezado " ;
                $this->cadena_sql.="set id_aprobado=1 ";
                $this->cadena_sql.="where id_planEstudio=".$variable[1];
                $this->cadena_sql.=" and id_proyectoCurricular=".$variable[2];
                $this->cadena_sql.=" and id_espacio=".$variable[0];
                $this->cadena_sql.=" and id_encabezado=".$variable[13];
                //                echo $this->cadena_sql;
                //                exit;

                break;

            case "cambiarEstadoAsociacionEncabezado":
                $this->cadena_sql="UPDATE ".$configuracion['prefijo']."encabezado " ;
                $this->cadena_sql.="set id_aprobado=1 ";
                $this->cadena_sql.="where id_planEstudio=".$variable[1];
                $this->cadena_sql.=" and id_proyectoCurricular=".$variable[2];
                $this->cadena_sql.=" and id_encabezado=".$variable[0];

                break;

            case "cambiarEstadoAsociacionNoAprobado":
                $this->cadena_sql="UPDATE ".$configuracion['prefijo']."espacioEncabezado " ;
                $this->cadena_sql.="set id_aprobado=2 ";
                $this->cadena_sql.="where id_planEstudio=".$variable[1];
                $this->cadena_sql.=" and id_proyectoCurricular=".$variable[2];
                $this->cadena_sql.=" and id_espacio=".$variable[0];
                $this->cadena_sql.=" and id_encabezado=".$variable[13];
                //                echo $this->cadena_sql;
                //                exit;

                break;

            case "cambiarEstadoAsociacionNoAprobadoEncabezado":
                $this->cadena_sql="UPDATE ".$configuracion['prefijo']."encabezado " ;
                $this->cadena_sql.="set id_aprobado=2 ";
                $this->cadena_sql.="where id_planEstudio=".$variable[1];
                $this->cadena_sql.=" and id_proyectoCurricular=".$variable[2];
                $this->cadena_sql.=" and id_encabezado=".$variable[0];

                break;

            case "aprobarEspacio":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio_espacio ";
                $this->cadena_sql.="SET id_aprobado=1 ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable[1]." ";
                $this->cadena_sql.="AND id_espacio=".$variable[0];
                //echo $this->cadena_sql;
                //exit;

                break;

            case "desaprobarEspacio2":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio_espacio ";
                $this->cadena_sql.="SET id_aprobado=2 ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable[1]." ";
                $this->cadena_sql.="AND id_espacio=".$variable[0];
                //echo $this->cadena_sql;
                //exit;

                break;

            case "DesaprobarEspacio":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio_espacio ";
                $this->cadena_sql.="SET id_aprobado=0 ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable[1]." ";
                $this->cadena_sql.="AND id_espacio=".$variable[0];
                //echo $this->cadena_sql;
                //exit;

                break;

            case "datosEspacio":
                $this->cadena_sql="SELECT espacio_nombre, espacio_nroCreditos, espacio_horasDirecto, espacio_horasCooperativo, espacio_horasAutonomo ";
                $this->cadena_sql.=" FROM ".$configuracion["prefijo"]."espacio_academico ";
                $this->cadena_sql.="WHERE id_espacio=".$variable[0];
                //echo $this->cadena_sql;
                //exit;

                break;

            case "datosCarrera":

                $this->cadena_sql="SELECT id_facultad_academica, id_proyectoAcademica ";
                $this->cadena_sql.=" FROM sga_proyectoCurricular PC ";
                $this->cadena_sql.=" INNER JOIN sga_planEstudio_proyecto PEP ON PC.id_proyectoAcademica = PEP.planEstudioProyecto_idProyectoCurricular";
                $this->cadena_sql.=" WHERE PEP.planEstudioProyecto_idPlanEstudio =".$variable[1];
                //echo $this->cadena_sql;
                //exit;

                break;

            case "datosNumeroCarreras":

                $this->cadena_sql="SELECT count(*) ";
                $this->cadena_sql.=" FROM sga_proyectoCurricular PC ";
                $this->cadena_sql.=" INNER JOIN sga_planEstudio_proyecto PEP ON PC.id_proyectoAcademica = PEP.planEstudioProyecto_idProyectoCurricular";
                $this->cadena_sql.=" WHERE PEP.planEstudioProyecto_idPlanEstudio =".$variable[1];

                break;

            case "cargarEspacioAcasi":
                $this->cadena_sql="INSERT INTO ACASI(ASI_COD, ASI_NOMBRE, ASI_DEP_COD, ASI_ESTADO, ASI_IND_CRED, ASI_IND_CATEDRA) " ;
                $this->cadena_sql.="VALUES( ";
                $this->cadena_sql.="'".$variable[0]."',";
                $this->cadena_sql.="'".$variable[1]."',";
                $this->cadena_sql.="'".$variable[2]."',";
                $this->cadena_sql.="'".$variable[3]."',";
                $this->cadena_sql.="'".$variable[4]."',";
                $this->cadena_sql.="'".$variable[5]."')";
//                exit;

                break;

            case "borrarEspacioAcasi":
                $this->cadena_sql="delete from acasi " ;
                $this->cadena_sql.="where ";
                $this->cadena_sql.="ASI_COD='".$variable[0]."'";
//                echo $this->cadena_sql;
//                exit;

                break;

            case "buscarEspacioAcasi":
                $this->cadena_sql="select * from acasi " ;
                $this->cadena_sql.="where ";
                $this->cadena_sql.="ASI_COD='".$variable[0]."'";
//                echo $this->cadena_sql;
//                exit;

                break;

            case "buscarEspacioAcpen":
                $this->cadena_sql="select * from acpen " ;
                $this->cadena_sql.="where ";
                $this->cadena_sql.="PEN_ASI_COD='".$variable[1]."'";
                $this->cadena_sql.=" AND PEN_NRO='".$variable[8]."'";
//                echo $this->cadena_sql;
//                exit;

                break;

            case "cargarEspacioAcpen":
                $this->cadena_sql="INSERT INTO ACPEN(PEN_CRA_COD, PEN_ASI_COD, PEN_SEM, PEN_IND_ELE, PEN_NRO_HT, PEN_NRO_HP, PEN_ESTADO, PEN_CRE, PEN_NRO,PEN_NRO_AUT) " ;
                $this->cadena_sql.="VALUES( ";
                $this->cadena_sql.="'".$variable[0]."',";
                $this->cadena_sql.="'".$variable[1]."',";
                $this->cadena_sql.="'".$variable[2]."',";
                $this->cadena_sql.="'".$variable[3]."',";
                $this->cadena_sql.="'".$variable[4]."',";
                $this->cadena_sql.="'".$variable[5]."',";
                $this->cadena_sql.="'".$variable[6]."',";
                $this->cadena_sql.="'".$variable[7]."',";
                $this->cadena_sql.="'".$variable[8]."',";
                $this->cadena_sql.="'".$variable[9]."')";
//                echo $this->cadena_sql;
//                exit;

                break;
            case "registrarClasificacion":
                $this->cadena_sql="INSERT INTO ACCLASIFICACPEN (CLP_CRA_COD, CLP_ASI_COD, CLP_PEN_NRO, CLP_CEA_COD, CLP_ESTADO) " ;
                $this->cadena_sql.="VALUES (";
                $this->cadena_sql.="'".$variable[0]."',";
                $this->cadena_sql.="'".$variable[1]."',";
                $this->cadena_sql.="'".$variable[8]."',";
                $this->cadena_sql.="'".$variable['clasificacion']."',";
                $this->cadena_sql.="'A')";

                break;



            case "estadocargarEspacio":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"];
                $this->cadena_sql.="planEstudio_espacio ";
                $this->cadena_sql.="SET id_cargado='1' ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable[8]." ";
                $this->cadena_sql.="AND id_espacio=".$variable[1];
                //echo $this->cadena_sql;
                //exit;

                break;

            case "estadoAprobadoAsociacion":
                $this->cadena_sql="UPDATE ".$configuracion["prefijo"];
                $this->cadena_sql.="espacioEncabezado ";
                $this->cadena_sql.="SET id_aprobado='1' ";
                $this->cadena_sql.="WHERE id_planEstudio=".$variable[1]." ";
                $this->cadena_sql.="AND id_espacio=".$variable[0];
                //echo $this->cadena_sql;
                //exit;

                break;


            case 'registroEvento':

                $this->cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $this->cadena_sql.="VALUES(0,'".$variable[0]."',";
                $this->cadena_sql.="'".$variable[1]."',";
                $this->cadena_sql.="'".$variable[2]."',";
                $this->cadena_sql.="'".$variable[3]."',";
                $this->cadena_sql.="'".$variable[4]."',";
                $this->cadena_sql.="'".$variable[5]."')";

                break;
        
            case 'periodoActivo':

                $this->cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                $this->cadena_sql.="WHERE ape_estado like '%A%'";
                break;


        }#Cierre de switch
        return $this->cadena_sql;
}#Cierre de funcion cadena_sql
}#Cierre de clase
?>
