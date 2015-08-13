<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/06/2013
 0.0.0.2    Milton Parra        12/08/2015      Se adicionan consultas para registrar la generacion de cada sabana
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");
//06/11/2012 Milton Parra: Se ajustan mensajes cuando faltan datos en las notas. Se coloca mensaje cuando no presenta Promedio Acumulado
class sql_reporteSabanaDeNotas extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$conexion,$tipo,$variable="") {

        switch($tipo) {
            case "proyectos":
                $cadena_sql=" SELECT DISTINCT";
                $cadena_sql.=" cra_cod CODIGO,";
                $cadena_sql.=" cra_cod||' - '||cra_nombre NOMBRE";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."accra";
                $cadena_sql.=" WHERE cra_cod<>0 AND cra_cod<>999  ";
                $cadena_sql.=" ORDER BY cra_cod";
                break;

            case "datos_proyecto":
                $cadena_sql=" SELECT";
                $cadena_sql.=" cra_cod CODIGO,";
                $cadena_sql.=" cra_nombre NOMBRE,";
                $cadena_sql.=" cra_nota_aprob NOTA_APROBATORIA";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."accra";
                $cadena_sql.=" WHERE cra_cod =".$variable;
                break;
            
            case "datos_estudiantes":
                $cadena_sql=" SELECT";
                $cadena_sql.=" est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod CARRERA,";
                $cadena_sql.=" est_pen_nro PENSUM,";
                $cadena_sql.=" est_nro_iden DOCUMENTO,";
                $cadena_sql.=" TRIM(est_ind_cred) IND_CRED,";
                $cadena_sql.=" fa_promedio_nota(est_cod) PROMEDIO,";              
 				$cadena_sql.=" ('OAS'||ABS((est_cod+est_nro_iden*est_cra_cod-cast(TO_CHAR(current_timestamp,'yyyymmdd')as integer) +12))||CASE WHEN trim(est_ind_cred)='N' THEN 'H' WHEN trim(est_ind_cred)='S' THEN 'C' END) MARCA";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."acest";
                $cadena_sql.=" WHERE est_cod in (".$variable.")";
                break;
            
            case "notas_estudiantes":
                $cadena_sql=" SELECT nde_cra_cod PROYECTO,";
                $cadena_sql.=" nde_est_cod      COD_ESTUDIANTE,";
                $cadena_sql.=" nde_pen_nro      PENSUM,";
                $cadena_sql.=" nde_sem          SEMESTRE,";
                $cadena_sql.=" nde_asi_cod      COD_ESPACIO,";
                $cadena_sql.=" asi_nombre       ESPACIO,";
                $cadena_sql.=" nde_sem          NOT_SEM,";
                $cadena_sql.=" not_obs          OBSERVACION,";
                $cadena_sql.=" CASE WHEN not_nota=0 THEN cast((0.0) as integer) ELSE (not_nota/10) END      NOT_NOTA,";
                $cadena_sql.=" cast((CASE WHEN nde_nota=0 THEN 0.0 ELSE nde_nota END)  as integer)   NOT_NOTA_BASE,";
                $cadena_sql.=" CASE WHEN not_obs=19 THEN nob_nombre WHEN not_obs=20 THEN nob_nombre WHEN not_obs=22 THEN nob_nombre WHEN not_obs=23 THEN nob_nombre WHEN not_obs=24 THEN nob_nombre WHEN not_obs=25 THEN nob_nombre ELSE  mon_nombre END  LETRAS,";
                $cadena_sql.=" (coalesce(nde_nro_ht,0) + coalesce(nde_nro_hp,0) + coalesce(nde_nro_aut,0)) INTENSIDAD,";
                $cadena_sql.=" CASE WHEN trim(est_ind_cred)='S' THEN nde_cred ELSE null END      CREDITOS,";
                $cadena_sql.=" CASE WHEN trim(est_ind_cred)='S' THEN not_cea_cod ELSE null END      CLASIFICACION,";
                $cadena_sql.=" nde_nro_ht       HT,";
                $cadena_sql.=" nde_nro_hp       HP,";
                $cadena_sql.=" nde_nro_aut      HA";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."acmonto, ";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."acnotobs, ";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."v_acnotdef, ";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."acasi, ";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."acnot, ";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."acest";
                $cadena_sql.=" WHERE est_cod = nde_est_cod";
                $cadena_sql.=" and asi_cod = nde_asi_cod";
                $cadena_sql.=" and nde_cra_cod = not_cra_cod";
                $cadena_sql.=" and nde_est_cod = not_est_cod";
                $cadena_sql.=" and nde_asi_cod = not_asi_cod";
                $cadena_sql.=" and nde_per = ((not_ano * 10) + not_per)";
                $cadena_sql.=" and mon_cod = nde_nota";
                $cadena_sql.=" and nob_cod = nde_obs";
                $cadena_sql.=" and est_cod IN (".$variable[0].")";
                $cadena_sql.=" and nde_cra_cod =".$variable[1];
                break;
            
            case "proyecto_de_estudiante":
                $cadena_sql=" SELECT DISTINCT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO,";
                $cadena_sql.=" est_nombre NOMBRE";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."acest ";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_academico']."accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" est_cod =".$variable." ";
                break;
            
            case "secretario":
                $cadena_sql=" SELECT (trim(sec_nombre)||' '||trim(sec_apellido)) NOMBRE,";
                $cadena_sql.=" dep_nombre FACULTAD";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."gedep, ";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."accra,";
                $cadena_sql.=" ".$this->configuracion['esquema_academico']."acsecretario";
                $cadena_sql.=" WHERE dep_cod = cra_dep_cod";
                $cadena_sql.=" AND cra_cod = ".$variable;
                $cadena_sql.=" AND dep_cod = sec_dep_cod";
                $cadena_sql.=" AND current_timestamp BETWEEN sec_fecha_desde AND coalesce(sec_fecha_hasta,current_timestamp)";
                $cadena_sql.=" AND sec_estado = 'A'";
                break;
           
            case 'consultar_codigo_estudiante_por_id':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."acest ";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_academico']."accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nro_iden= ".$variable;
                $cadena_sql.=" ORDER BY CODIGO desc";

                break;
           
            case 'consultar_codigo_estudiante_por_nombre':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO,";
                $cadena_sql.=" est_nombre NOMBRE";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."acest ";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_academico']."accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nombre like ".$variable." ";
                $cadena_sql.=" ORDER BY CODIGO desc";

                break;

            case 'consultarRegistroSabana':
                $cadena_sql=" SELECT id_sabana,";
                $cadena_sql.=" sab_usuario,";
                $cadena_sql.=" sab_fecha,";
                $cadena_sql.=" sab_codigo_estudiante,";
                $cadena_sql.=" sab_doc_estudiante,";
                $cadena_sql.=" sab_nombre_estudiante,";
                $cadena_sql.=" sab_marca";
                $cadena_sql.=" FROM sga_genera_sabana_oficial";
                $cadena_sql.=" WHERE sab_codigo_estudiante='".$variable['codigo']."'";
                $cadena_sql.=" AND sab_marca='".$variable['marca']."'";
                break;
                
            case 'registrarGeneraSabanaOficial':
                $cadena_sql=" INSERT INTO sga_genera_sabana_oficial (";
                $cadena_sql.=" sab_usuario,";
                $cadena_sql.=" sab_fecha,";
                $cadena_sql.=" sab_timestamp,";
                $cadena_sql.=" sab_codigo_estudiante,";
                $cadena_sql.=" sab_doc_estudiante,";
                $cadena_sql.=" sab_nombre_estudiante,";
                $cadena_sql.=" sab_marca)";
                $cadena_sql.=" VALUES (";
                $cadena_sql.="  '".$variable['usuario']."'";
                $cadena_sql.=" ,'".$variable['fecha']."'";
                $cadena_sql.=" ,'".$variable['tiempo']."'";
                $cadena_sql.=" ,'".$variable['codigo']."'";
                $cadena_sql.=" ,'".$variable['documento']."'";
                $cadena_sql.=" ,'".$variable['nombre']."'";
                $cadena_sql.=" ,'".$variable['marca']."')";
                break;
                
      
        }



        return $cadena_sql;

    }
}
?>