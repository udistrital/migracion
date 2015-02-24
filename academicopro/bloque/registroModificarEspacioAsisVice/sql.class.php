<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroModificarEspacioAsisVice extends sql {
    function cadena_sql($configuracion,$opcion,$variable="") {

        switch($opcion) {

            case "buscarDatosEspacio":
                $cadena_sql="SELECT ESPACIO.id_espacio codEspacio,";
                $cadena_sql.=" ESPACIO.espacio_nombre nombreEspacio,";
                $cadena_sql.=" PLAN_ESPACIO.id_nivel nivel,";
                $cadena_sql.=" espacio_nroCreditos nroCreditos,";
                $cadena_sql.=" horasDirecto htd,";
                $cadena_sql.=" horasCooperativo htc,";
                $cadena_sql.=" espacio_horasAutonomo hta,";
                $cadena_sql.=" CLASIFICACION.clasificacion_nombre,";
                $cadena_sql.=" CLASIFICACION.id_clasificacion clasificacion,";
                $cadena_sql.=" PLAN_ESPACIO.id_aprobado,";
                $cadena_sql.=" PLAN_ESPACIO.id_planEstudio planEstudio,";
                $cadena_sql.=" PLAN_ESPACIO.semanas semanas";
                $cadena_sql.=" FROM sga_espacio_academico AS ESPACIO";
                $cadena_sql.=" INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO";
                $cadena_sql.=" ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion AS CLASIFICACION";
                $cadena_sql.=" ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion";
                $cadena_sql.=" WHERE PLAN_ESPACIO.id_planEstudio=".$variable['planEstudio'];
                $cadena_sql.=" AND ESPACIO.id_espacio=".$variable['codEspacio'];
                $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1";
                $cadena_sql.=" ORDER BY ESPACIO.id_espacio";

                break;


            case 'buscarEspacioComunOracle':

                $cadena_sql="select * from acpen where pen_asi_cod=".$variable[8];
                $cadena_sql.=" and pen_estado like '%A%' ";
                break;


            case 'buscarEspacioEnPlanOracle':

                $cadena_sql="SELECT pen_nro COD_PLAN,";
                $cadena_sql.=" pen_asi_cod COD";
                $cadena_sql.=" FROM acpen where pen_asi_cod=".$variable['COD'];
                $cadena_sql.=" AND pen_nro=".$variable['COD_PLAN'];
                $cadena_sql.=" and pen_estado like '%A%' ";
                break;

            
            case 'clasificacion':

                $cadena_sql=" SELECT cea_cod CODIGO_CLASIFICACION,";
                $cadena_sql.=" cea_nom NOMBRE_CLASIFICACION,";
                $cadena_sql.=" cea_abr ABREVIATURA_CLASIFICACION";
                $cadena_sql.=" FROM geclasificaespac";
                break;

            case 'actualizar_espacioAcademico':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."espacio_academico SET ";
                $cadena_sql.=" espacio_nroCreditos='".$variable['nroCreditos']."'";
                $cadena_sql.=", espacio_horasDirecto='".$variable['htd']."'";
                $cadena_sql.=", espacio_horasCooperativo='".$variable['htc']."'";
                $cadena_sql.=", espacio_horasAutonomo='".$variable['hta']."'";
                $cadena_sql.=" WHERE id_espacio='".$variable['codEspacio']."'";
                break;

            case 'actualizarCreditosEspacio':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."espacio_academico SET";
                $cadena_sql.=" espacio_nroCreditos='".$variable['nroCreditos']."'";
                $cadena_sql.=", espacio_horasDirecto='".$variable['htd']."'";
                $cadena_sql.=", espacio_horasCooperativo='".$variable['htc']."'";
                $cadena_sql.=", espacio_horasAutonomo='".$variable['hta']."'";
                $cadena_sql.=" WHERE id_espacio='".$variable['codEspacio']."'";
                break;

            case 'actualizarNombreEspacio':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."espacio_academico SET ";
                $cadena_sql.="espacio_nombre ='".$variable['nombreEspacio']."'";
                $cadena_sql.=" WHERE id_espacio='".$variable['codEspacio']."'";
                break;

            case 'modificarDatosPlanEspacio':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
                $cadena_sql.=" id_nivel='".$variable['nivel']."'";
                $cadena_sql.=", id_clasificacion='".$variable['clasificacion']."'";
                $cadena_sql.=", horasDirecto='".$variable['htd']."'";
                $cadena_sql.=", horasCooperativo='".$variable['htc']."'";
                $cadena_sql.=", semanas='".$variable['semanas']."'";
                $cadena_sql.=" WHERE id_planEstudio='".$variable['planEstudio']."'";
                $cadena_sql.=" AND id_espacio='".$variable['codEspacio']."'";
                break;

            case 'actualizarCreditosPlanEspacio':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
                $cadena_sql.=" horasDirecto='".$variable['htd']."'";
                $cadena_sql.=", horasCooperativo='".$variable['htc']."'";
                $cadena_sql.=" WHERE id_espacio='".$variable['codEspacio']."'";
                break;

            case 'modificarNivelPlanEspacio':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
                $cadena_sql.=" id_nivel='".$variable['nivel']."'";
                $cadena_sql.=" WHERE id_espacio='".$variable['codEspacio']."'";
                break;

            case 'modificarNivelEspacioAsociadoPlanEspacio':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
                $cadena_sql.=" id_nivel='".$variable['nivel']."'";
                $cadena_sql.=" WHERE id_espacio='".$variable['COD']."'";
                $cadena_sql.=" AND id_planEstudio='".$variable['COD_PLAN']."'";
                break;

            case 'modificarClasificacionPlanEspacio':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
                $cadena_sql.=" id_clasificacion='".$variable['clasificacion']."'";
                $cadena_sql.=" WHERE id_espacio='".$variable['codEspacio']."'";
                break;
            
            case "bimestreActual":

                $cadena_sql="select ape_ano ANO";
                $cadena_sql.=", ape_per PERIODO";
                $cadena_sql.=" from acasperi";
                $cadena_sql.=" where ape_estado like '%A%'";

                break;

            case 'registroModificarEA':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                $cadena_sql.="'".$variable[1]."', ";
                $cadena_sql.="'21', ";
                $cadena_sql.="'Modifica Nombre General Asesor', ";
                $cadena_sql.="'".$variable[2]."-".$variable[3].", ";
                $cadena_sql.=$variable[4].", 0, 0, ".$variable[5].", ".$variable[6]."', ";
                $cadena_sql.="'".$variable[5]."')";

                break;

            case 'consultarPlanesDeEspacio':

                $cadena_sql=" SELECT distinct PE.id_planEstudio PLAN,";
                $cadena_sql.=" PE.planEstudio_nombre 'PROYECTO o PROFUNDIZACION',";
                $cadena_sql.=" EA.espacio_nroCreditos CREDITOS,";
                $cadena_sql.=" PEE.horasDirecto HTD,";
                $cadena_sql.=" PEE.horasCooperativo HTC,";
                $cadena_sql.=" EA.espacio_horasAutonomo HTA,";
                $cadena_sql.=" clasificacion_nombre CLASIFICACION,";
                $cadena_sql.=" id_nivel NIVEL,";
                $cadena_sql.=" (CASE ";
                $cadena_sql.=" WHEN PEE.id_aprobado=1 THEN 'APROBADO' ";
                $cadena_sql.=" WHEN PEE.id_aprobado=0 THEN 'EN PROCESO' ";
                $cadena_sql.=" WHEN PEE.id_aprobado=2 THEN 'NO APROBADO' END) ESTADO,";
                $cadena_sql.=" PC.id_facultad FACULTAD,";
                $cadena_sql.=" F.nombre_facultad NOMBRE_FACULTAD,";
                $cadena_sql.=" E.estado_nombre";
                $cadena_sql.=" FROM sga_planEstudio_espacio PEE";
                $cadena_sql.=" INNER JOIN sga_espacio_academico EA ON EA.id_espacio=PEE.id_espacio";
                $cadena_sql.=" INNER JOIN sga_espacio_clasificacion EC ON PEE.id_clasificacion = EC.id_clasificacion";
                $cadena_sql.=" INNER JOIN sga_planEstudio PE ON PEE.id_planEstudio = PE.id_planEstudio";
                $cadena_sql.=" INNER JOIN sga_planEstudio_proyecto PEP ON PEE.id_planEstudio = PEP.planEstudioProyecto_idPlanEstudio";
                $cadena_sql.=" INNER JOIN sga_proyectoCurricular PC ON PEP.planEstudioProyecto_idProyectoCurricular = PC.id_proyectoAcademica";
                $cadena_sql.=" INNER JOIN sga_estado E ON PEE.id_estado=E.id_estado";
                $cadena_sql.=" INNER JOIN sga_facultad F ON PC.id_facultad=F.id_facultad";
                $cadena_sql.=" WHERE PEE.id_espacio=".$variable['codEspacio'];
                $cadena_sql.=" AND PEE.id_estado=1";
                //$cadena_sql.=" AND PEE.id_aprobado=1";
                //$cadena_sql.=" AND PEE.id_cargado=1";
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'nombre_facultad':

                $cadena_sql="select distinct  id_facultad, nombre_facultad  ";
                $cadena_sql.="from sga_facultad ";
                $cadena_sql.="where id_facultad=".$variable;
                break;

            case "modificarEspacio_notas":
                $cadena_sql="SELECT count(*) FROM ACNOT ";
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

            case "consultarEspacio":
                $cadena_sql="SELECT COUNT(*) FROM ".$configuracion['prefijo']."espacio_academico ";
                $cadena_sql.=" WHERE id_espacio=".$variable['codEspacio'];
                $cadena_sql.=" AND id_estado=1";
                break;

            case "consultarEspacioPlanEstudio":
                $cadena_sql="SELECT COUNT(*) FROM ".$configuracion['prefijo']."planEstudio_espacio ";
                $cadena_sql.=" WHERE id_espacio=".$variable['codEspacio'];
                $cadena_sql.=" AND id_planEstudio=".$variable['planEstudio'];
                $cadena_sql.=" AND id_estado=1";
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

            case "buscarEspaciosAsociadosEncabezado";
                $cadena_sql=" SELECT PEE.id_espacio COD,";
                $cadena_sql.=" EA.espacio_nombre NOMBRE,";
                $cadena_sql.=" EA.espacio_nroCreditos CREDITOS,";
                $cadena_sql.=" clasificacion_nombre CLASIFICACION,";
                $cadena_sql.=" PEE.id_nivel NIVEL,";
                $cadena_sql.=" (";
                $cadena_sql.=" CASE";
                $cadena_sql.=" WHEN PEE.id_aprobado=1";
                $cadena_sql.=" THEN 'APROBADO'";
                $cadena_sql.=" WHEN PEE.id_aprobado=0";
                $cadena_sql.=" THEN 'EN PROCESO'";
                $cadena_sql.=" WHEN PEE.id_aprobado=2";
                $cadena_sql.=" THEN 'NO APROBADO'";
                $cadena_sql.=" END) ESTADO,";
                $cadena_sql.=" EE.id_planEstudio COD_PLAN";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."espacioEncabezado EE";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."encabezado E on E.id_encabezado=EE.id_encabezado";
                $cadena_sql.=" AND E.id_planEstudio=EE.id_planEstudio";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PEE ON EE.id_espacio=PEE.id_espacio";
                $cadena_sql.=" AND EE.id_planEstudio=PEE.id_planEstudio";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON PEE.id_espacio=EA.id_espacio";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion=EC.id_clasificacion";
                $cadena_sql.=" WHERE E.id_encabezado=".$variable['id_encabezado'];
                $cadena_sql.=" AND EE.id_planEstudio=".$variable['planEstudio'];
                $cadena_sql.=" AND EE.id_estado=1";
                $cadena_sql.=" AND PEE.id_estado=1";
                $cadena_sql.=" AND E.id_estado=1";
                $cadena_sql.=" ORDER BY COD";

               break;

            case "modificarDatosEspacioAcpen":
                $cadena_sql="UPDATE acpen SET pen_cre='".$variable['nroCreditos']."'";
                $cadena_sql.=", pen_sem='".$variable['nivel']."'";
                $cadena_sql.=", pen_nro_ht='".$variable['htd']."'";
                $cadena_sql.=", pen_nro_hp='".$variable['htc']."'";
                $cadena_sql.=", pen_nro_aut='".$variable['hta']."'";
                $cadena_sql.=", pen_ind_ele='".$variable['electiva']."'";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND pen_nro=".$variable['planEstudio'];
                break;

            case "actualizarCreditosAcpen":
                $cadena_sql="UPDATE acpen SET pen_cre='".$variable['nroCreditos']."'";
                $cadena_sql.=", pen_nro_ht='".$variable['htd']."'";
                $cadena_sql.=", pen_nro_hp='".$variable['htc']."'";
                $cadena_sql.=", pen_nro_aut='".$variable['hta']."'";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                break;

            case "modificarNivelAcpen":
                $cadena_sql="UPDATE acpen SET pen_sem='".$variable['nivel']."'";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                break;

            case "modificarNivelEspacioAsociadoAcpen":
                $cadena_sql="UPDATE acpen SET pen_sem='".$variable['nivel']."'";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['COD'];
                $cadena_sql.=" AND pen_nro=".$variable['COD_PLAN'];
                break;

            case "modificarClasificacionAcpen":
                $cadena_sql="UPDATE acpen SET pen_ind_ele='".$variable['electiva']."'";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable['codEspacio'];
                break;

            case "modificarClasificacionPlan":
                $cadena_sql="UPDATE acclasificacpen SET clp_cea_cod='".$variable['clasificacion']."'";
                $cadena_sql.=" WHERE clp_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND clp_pen_nro=".$variable['planEstudio'];
                break;

            case "modificarClasificacion":
                $cadena_sql="UPDATE acclasificacpen SET clp_cea_cod='".$variable['clasificacion']."'";
                $cadena_sql.=" WHERE clp_asi_cod=".$variable['codEspacio'];
                break;

            case "buscarEspacio_acasi":
                $cadena_sql="SELECT * FROM acasi ";
                $cadena_sql.=" WHERE asi_cod=".$variable[8];
                break;

            case "actualizarNombreAcasi":
                $cadena_sql="UPDATE acasi SET asi_nombre='".$variable['nombreEspacio']."'";
                $cadena_sql.=" WHERE asi_cod='".$variable['codEspacio']."'";
                break;

            case "actualizarEspacio_acpenComun":
                $cadena_sql="UPDATE acpen SET pen_nro_ht='".$variable[5]."', pen_nro_hp='".$variable[6]."'";
                $cadena_sql.=" WHERE pen_asi_cod=".$variable[8];
                $cadena_sql.=" AND pen_nro=".$variable[0];
                break;

            
            case "actualizarEspacio_acasiComun":
                $cadena_sql="UPDATE acasi SET asi_nombre='".$variable[10]."'";
                $cadena_sql.=" WHERE asi_cod='".$variable[8]."'";
                break;

            case 'actualizar_espacioAcademicoComun':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."espacio_academico SET ";
                $cadena_sql.="espacio_nombre ='".$variable[2]."'";
                $cadena_sql.=" WHERE id_espacio='".$variable[8]."'";

                break;

            case 'actualizar_planEstudioComun':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
                $cadena_sql.=" horasDirecto='".$variable[5]."'";
                $cadena_sql.=", horasCooperativo='".$variable[6]."'";
                $cadena_sql.=" WHERE id_planEstudio='".$variable[0]."'";
                $cadena_sql.=" AND id_espacio='".$variable[8]."'";
                break;

            case 'actualizar_espacioAcademicoEncabezado':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."encabezado SET ";
                $cadena_sql.="encabezado_nombre ='".$variable[1]."'";
                $cadena_sql.=", encabezado_creditos='".$variable[2]."'";
                $cadena_sql.=", encabezado_nivel ='".$variable[3]."'";
                $cadena_sql.=", id_clasificacion ='".$variable[6]."'";
                $cadena_sql.=" WHERE id_encabezado='".$variable[0]."'";
                $cadena_sql.=" AND id_planEstudio='".$variable[4]."'";
                $cadena_sql.=" AND id_proyectoCurricular='".$variable[5]."'";
                break;

        }
        return $cadena_sql;
    }


}
?>