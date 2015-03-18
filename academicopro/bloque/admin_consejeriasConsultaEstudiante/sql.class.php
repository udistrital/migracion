<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_consejeriasConsultaEstudiante extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

  public $configuracion;


  function __construct($configuracion){

    $this->configuracion=$configuracion;

  }
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
                        

            case 'periodoActivo':

                $cadena_sql="SELECT APE_ANO, APE_PER FROM ACASPERI";
                $cadena_sql.=" WHERE APE_ESTADO LIKE '%A%'";
                break;


            case 'consultarDatosEstudiante':

                $cadena_sql="SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_nombre NOMBRE, ";
                $cadena_sql.=" est_pen_nro PENSUM, ";
                $cadena_sql.=" est_ind_cred MODALIDAD, ";
                $cadena_sql.=" estado_cod ESTADO, ";
                $cadena_sql.=" estado_descripcion DESC_ESTADO, ";
                $cadena_sql.=" est_cra_cod CODIGO_CRA, ";
                $cadena_sql.=" cra_nombre NOMBRE_CRA, ";
                $cadena_sql.=" eot_email EMAIL, ";
                $cadena_sql.=" est_telefono TELEFONO, ";
                $cadena_sql.=" fa_promedio_nota(est_cod) PROMEDIO, " ;
                $cadena_sql.=" est_acuerdo ACUERDO, " ;
                $cadena_sql.=" tra_nivel NIVEL, " ;
                $cadena_sql.=" TO_CHAR(eot_fecha_nac, 'yyyymmdd') FEC_NACIMIENTO, ";
                $cadena_sql.=" TO_CHAR(eot_fecha_grado_secundaria, 'yyyymmdd') FEC_GRADO, ";
                $cadena_sql.=" est_nro_iden IDENTIFICACION, ";
                $cadena_sql.=" emb_valor_matricula VALOR_MATRICULA, ";
                $cadena_sql.=" TO_CHAR(eot_fecha_nac, 'yyyy-mm-dd') FEC_NACIMIENTO_MOSTRAR ";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado on est_estado_est= estado_cod ";
                $cadena_sql.=" INNER JOIN accra on est_cra_cod= cra_cod ";
                $cadena_sql.=" INNER JOIN acestotr on EOT_COD= EST_COD ";
                $cadena_sql.=" INNER JOIN actipcra ON tra_cod=cra_tip_cra ";
                $cadena_sql.=" LEFT OUTER JOIN v_acestmatbruto ON emb_est_cod=est_cod ";
                $cadena_sql.=" WHERE est_estado like '%A%' ";
                //$cadena_sql.=" and estado_activo like '%S%' ";
                $cadena_sql.=" AND est_cod = '".$variable['codEstudiante']."' ";
                break;      
            

            case 'consultarDatosDocente':
                $cadena_sql="SELECT ";
                $cadena_sql.="doc_nro_iden DOCUMENTO, ";
                $cadena_sql.="doc_apellido APELLIDO, ";
                $cadena_sql.="doc_nombre NOMBRE, ";
                $cadena_sql.="doc_email CORREO ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acdocente ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="doc_nro_iden=".$variable['codDocente'];

                break;
            

            case 'nota_aprobatoria':

                $cadena_sql="SELECT cra_nota_aprob";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" WHERE CRA_COD=".$variable['codProyectoEstudiante'];
                break;            

            

            case '---':
                $cadena_sql=" SELECT DISTINCT usucra_cra_cod, ";
                $cadena_sql.=" cra_nombre,";
                $cadena_sql.=" pen_nro ";
                $cadena_sql.=" FROM geusucra ";
                $cadena_sql.=" INNER JOIN accra ON usucra_cra_cod=cra_cod ";
                $cadena_sql.=" INNER JOIN acpen ON usucra_cra_cod=pen_cra_cod ";
                $cadena_sql.=" where usucra_nro_iden=".$variable['codDocente'];
//                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
                $cadena_sql.=" and pen_nro>200";

                break;       
            
            
            case 'consultarEspaciosCursados':

                $cadena_sql="SELECT DISTINCT not_asi_cod CODIGO, ";
                $cadena_sql.="asi_nombre NOMBRE, ";
                $cadena_sql.="not_nota NOTA, ";
                $cadena_sql.="not_cred CREDITOS,";
                $cadena_sql.="not_sem NIVEL, ";
                $cadena_sql.="not_ano ANO, ";
                $cadena_sql.="not_per PERIODO, ";
                $cadena_sql.="nob_cod CODIGO_OBSERVACION, ";
                $cadena_sql.="nob_nombre NOTA_OBSERVACIONES, ";
                $cadena_sql.="not_nro_ht HTD, ";
                $cadena_sql.="not_nro_hp HTC";
                $cadena_sql.=" FROM acnot ";
                $cadena_sql.=" INNER JOIN acasi ON acnot.not_asi_cod = acasi.asi_cod  ";
                //$cadena_sql.=" INNER JOIN acpen ON acnot.not_cra_cod = acpen.pen_cra_cod AND acnot.not_asi_cod = acpen.pen_asi_cod ";
                $cadena_sql.=" INNER JOIN acest ON est_cod=not_est_cod and not_cra_cod=est_cra_cod ";
                $cadena_sql.=" INNER JOIN acnotobs ON acnot.not_obs = acnotobs.nob_cod ";
                $cadena_sql.=" where not_est_cod = '".$variable."' ";
                //$cadena_sql.=" AND NOT_OBS != 19  ";
                //$cadena_sql.=" AND NOT_OBS != 20  ";
                $cadena_sql.=" AND not_est_reg like '%A%' ";
                $cadena_sql.=" ORDER BY not_asi_cod, not_ano DESC, not_per DESC";
                
                break;            

//********************************************************************   
            
            case 'datosTotalizados':

                $cadena_sql="SELECT mat_est_cod,";
                $cadena_sql.="mat_annio, ";
                $cadena_sql.="mat_periodo, ";
                $cadena_sql.="mat_estado_est, ";
                $cadena_sql.="mat_cod_motivo, ";
                $cadena_sql.="mat_motivo motivo_prueba, ";
                $cadena_sql.="mat_nro_semestres, ";
                $cadena_sql.="mat_prom_acumulado PROMEDIO_ACUMULADO, ";
                $cadena_sql.="mat_prom_ponderado, ";
                $cadena_sql.="mat_nro_materias_perdidas, ";
                $cadena_sql.="mat_total_perdidas, ";
                $cadena_sql.="mat_veces_prueba VECES_PRUEBA_ESTUDIANTE";
                $cadena_sql.=" FROM sga_temp_matriculados ";
                $cadena_sql.=" WHERE mat_est_cod = '".$variable[0]."' ";
                $cadena_sql.=" and mat_annio = '".$variable[1]."'";
                $cadena_sql.=" and mat_periodo = '".$variable[2]."'";
                $cadena_sql.=" ORDER BY 1 ";
                //echo $cadena_sql;exit;
                break;
         

                case 'consultaGrupo':

                    $cadena_sql=" SELECT DISTINCT ins_asi_cod CODIGO_ASIGNATURA,";
                    $cadena_sql.=" ins_cra_cod CODIGO_CARRERA_ESTUDIANTE,";
                    $cadena_sql.=" ins_gr GRUPO,";
                    $cadena_sql.=" ins_ano ANO,";
                    $cadena_sql.=" ins_per PER,";
                    $cadena_sql.=" asi_nombre NOMBRE_ASIGNATURA,";
                    $cadena_sql.=" ins_est_cod CODIGO_ESTUDIANTE,";
                    $cadena_sql.=" ins_cred CREDITOS,";
                    $cadena_sql.=" ins_cea_cod CLASIFICACION,";
                    $cadena_sql.=" cea_abr ABR_CLASIFICACION";
                    $cadena_sql.=" FROM acins";
                    $cadena_sql.=" inner join acasi on ins_asi_cod=asi_cod";
                    $cadena_sql.=" left outer join geclasificaespac On ins_cea_cod=cea_cod";
                    $cadena_sql.=" WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" AND ins_ano=".$variable[1];
                    $cadena_sql.=" AND ins_per=".$variable[2];
                    $cadena_sql.=" ORDER BY ins_asi_cod";

                break;
              
                case 'consultaGrupoPeriodoAnterior':

                    $cadena_sql=" SELECT DISTINCT ins_asi_cod CODIGO_ASIGNATURA,";
                    $cadena_sql.=" ins_cra_cod CODIGO_CARRERA_ESTUDIANTE,";
                    $cadena_sql.=" ins_gr GRUPO,";
                    $cadena_sql.=" ins_ano ANO,";
                    $cadena_sql.=" ins_per PER,";
                    $cadena_sql.=" asi_nombre NOMBRE_ASIGNATURA,";
                    $cadena_sql.=" ins_est_cod CODIGO_ESTUDIANTE,";
                    $cadena_sql.=" ins_cred CREDITOS,";
                    $cadena_sql.=" ins_cea_cod CLASIFICACION,";
                    $cadena_sql.=" cea_abr ABR_CLASIFICACION";
                    $cadena_sql.=" FROM acins";
                    $cadena_sql.=" inner join acasi on ins_asi_cod=asi_cod";
                    $cadena_sql.=" inner join acasperi on ins_ano=ape_ano AND ins_per=ape_per AND ape_estado='P'";
                    $cadena_sql.=" left outer join geclasificaespac On ins_cea_cod=cea_cod";
                    $cadena_sql.=" WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" ORDER BY ins_asi_cod";

                break;
              
             case "consultarGrupos":

                $cadena_sql="select ins_asi_cod CODIGO_ESPACIO, ";
                $cadena_sql.=" ins_gr       GRUPO, ";
                $cadena_sql.=" asi_nombre   NOMBRE_ESPACIO, ";
                $cadena_sql.=" est_pen_nro  PENSUM,";
                $cadena_sql.=" ins_ano      ANIO,";
                $cadena_sql.=" ins_per      PERIODO,";
                $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)   NRO_GRUPO ";
                $cadena_sql.=" from acins ";
                $cadena_sql.=" inner join acasi on asi_cod=ins_asi_cod ";
                $cadena_sql.=" inner join acest on ins_est_cod=est_cod ";
                $cadena_sql.=" inner join accursos on ins_gr=cur_id ";
                $cadena_sql.=" where ins_est_cod=".$variable;
                $cadena_sql.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%')";
                break;


              case "consultarPorcentajeNotasParciales":

                $cadena_sql="select cur_par1 NOTA1, ";
                $cadena_sql.="cur_par2 NOTA2, ";
                $cadena_sql.="cur_par3 NOTA3, ";
                $cadena_sql.="cur_par4 NOTA4, ";
                $cadena_sql.="cur_par5 NOTA5, ";
                $cadena_sql.="cur_par6 NOTA6, ";
                $cadena_sql.="cur_lab NOTA_LABORATORIO, ";
                $cadena_sql.="cur_exa NOTA_EXAMEN ";
                $cadena_sql.=" from accursos ";
                $cadena_sql.=" where cur_asi_cod='".$variable[0]."'";
                $cadena_sql.=" and cur_id='".$variable[1]."'";
                $cadena_sql.=" and cur_ape_ano=".$variable[4];
                $cadena_sql.=" and cur_ape_per=".$variable[5];
                break;


              case "consultarDocentesCurso":

                $cadena_sql="select distinct doc_apellido DOCENTE_APELLIDO, ";
                $cadena_sql.="doc_nombre DOCENTE_NOMBRE";
                $cadena_sql.=" from accursos ";
                $cadena_sql.=" inner join achorarios on cur_id=hor_id_curso";
                $cadena_sql.=" inner join accargas on car_hor_id=hor_id";
                $cadena_sql.=" inner join acdocente on car_doc_nro=doc_nro_iden ";
                $cadena_sql.=" where cur_asi_cod='".$variable[0]."'";
                $cadena_sql.=" and cur_id='".$variable[1]."'";
                $cadena_sql.=" and cur_ape_ano=".$variable[4];
                $cadena_sql.=" and cur_ape_per=".$variable[5];
                break;


              case "consultarNotasParciales":


                $cadena_sql="SELECT ins_nota_par1 NOTA1, ";
                $cadena_sql.="ins_nota_par2 NOTA2, ";
                $cadena_sql.="ins_nota_par3 NOTA3, ";
                $cadena_sql.="ins_nota_par4 NOTA4, ";
                $cadena_sql.="ins_nota_par5 NOTA5, ";
                $cadena_sql.="ins_nota_par6 NOTA6, ";
                $cadena_sql.="ins_nota_lab NOTA_LABORATORIO, ";
                $cadena_sql.="ins_nota_exa NOTA_EXAMEN, ";
                $cadena_sql.="ins_nota_acu ACUMULADO, ";
                $cadena_sql.="ins_tot_fallas FALLAS";
                $cadena_sql.=" FROM acins ";
                $cadena_sql.=" WHERE ins_asi_cod='".$variable[0]."'";
                $cadena_sql.=" AND ins_gr='".$variable[1]."'";
                $cadena_sql.=" AND ins_est_cod='".$variable[2]."'";
                $cadena_sql.=" AND ins_ano=".$variable[3];
                $cadena_sql.=" AND ins_per=".$variable[4];
                break;



                case 'horario_grupos':

                    $cadena_sql="SELECT DISTINCT";
                    $cadena_sql.=" horario.hor_dia_nro          DIA,";
                    $cadena_sql.=" horario.hor_hora             HORA,";
                    $cadena_sql.=" sede.sed_id                  SEDE,";
                    $cadena_sql.=" horario.hor_sal_id_espacio   ID_SALON,";
                    $cadena_sql.=" salon.sal_nombre             SALON,";
                    $cadena_sql.=" salon.sal_edificio           ID_EDIFICIO,";
                    $cadena_sql.=" edi.edi_nombre               EDIFICIO,";
                    $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo)   NRO_GRUPO, ";
                    $cadena_sql.=" curso.cur_nro_cupo           CUPO, ";
                    $cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
                    $cadena_sql.=" FROM achorarios horario";
                    $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                    $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                    $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                    $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                    $cadena_sql.=" WHERE cur_asi_cod=".$variable[0][0]; //codigo del espacio
                    $cadena_sql.=" AND cur_ape_ano=" . $variable[0][7];
                    $cadena_sql.=" AND cur_ape_per=" . $variable[0][8];
                    $cadena_sql.=" AND cur_id=" . $variable[0][2]; //numero de grupo
                    $cadena_sql.=" ORDER BY 1,2,3";

                break;


                case 'clasificacionEspacio':

                    $cadena_sql="SELECT CL.id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".$this->configuracion['prefijo']."planEstudio_espacio PEE ";
                    $cadena_sql.="inner join ".$this->configuracion['prefijo']."espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion";
                    $cadena_sql.=" where id_espacio=".$variable;
                    break;

                  


            case 'estudiantes_asociados':

                $cadena_sql="select ECO_EST_COD CODIGO_ESTUDIANTE, ";
                //$cadena_sql.="ECO_DOC_NRO_IDENT, ";
                $cadena_sql.="EST_NOMBRE NOMBRE_ESTUDIANTE, ";
                $cadena_sql.="ESTADO_DESCRIPCION DESC_ESTADO_ESTUDIANTE, ";
                $cadena_sql.="EST_IND_CRED MODALIDAD_ESTUDIANTE "; //CREDITOS, HORAS
                $cadena_sql.="from ACESTUDIANTECONSEJERO ";
                $cadena_sql.="INNER JOIN ACEST ON est_cod= eco_est_cod ";
                $cadena_sql.="INNER JOIN ACESTADO ON estado_cod= est_estado_est ";
                $cadena_sql.="where ECO_DOC_NRO_IDENT='".$variable."' " ;
                $cadena_sql.="and ECO_ESTADO='A'" ;
                $cadena_sql.="ORDER BY ECO_EST_COD ASC" ;
                //echo $cadena_sql;
                break;
            
           case 'datosEstudiante':
                $cadena_sql="select est_nombre, ESTADO_DESCRIPCION from acest ";
                $cadena_sql.=" inner join acestado on estado_cod=est_estado_est";
                $cadena_sql.=" where est_cod=".$variable;
//echo $cadena_sql;exit;
                break;


            case 'registroEvento':

                $cadena_sql="insert into ".$this->configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES('','".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."')";

                break;


            case 'consultar_reglamento_estudiante':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" REG_ANO,";
                $cadena_sql.=" REG_PER,";
                $cadena_sql.=" REG_MOTIVO,";
                $cadena_sql.=" REG_PROMEDIO,";
                $cadena_sql.=" REG_CAUSAL_EXCLUSION,";
                $cadena_sql.=" REG_REGLAMENTO,";
                $cadena_sql.=" REG_NUMERO_PRUEBAS_AC, ";
                $cadena_sql.=" REG_INDICE_REPITENCIA,";
                $cadena_sql.=" REG_INDICE_PERMANENCIA,";
                $cadena_sql.=" REG_INDICE_NIVELACION, ";
                $cadena_sql.=" REG_RENDIMIENTO_AC,";
                $cadena_sql.=" REG_INDICE_ATRASO,";
                $cadena_sql.=" REG_EDAD_INGRESO,";
                $cadena_sql.=" REG_NUM_SEMESTRES_INGRESO,";
                $cadena_sql.=" REG_INDICE_RIESGO";
                $cadena_sql.=" FROM reglamento ";
                $cadena_sql.=" WHERE REG_EST_COD= ".$variable;
                $cadena_sql.=" AND REG_ESTADO= 'A'";
                $cadena_sql.=" ORDER BY reg_ano,reg_per";

                break;

        case "consultarGruposPeriodoAnterior":

                $cadena_sql=" select ins_asi_cod CODIGO_ESPACIO, ";
                $cadena_sql.=" ins_gr       GRUPO, ";
                $cadena_sql.=" asi_nombre   NOMBRE_ESPACIO, ";
                $cadena_sql.=" est_pen_nro  PENSUM,";
                $cadena_sql.=" ins_ano      ANIO,";
                $cadena_sql.=" ins_per      PERIODO";
                $cadena_sql.=" from acins ";
                $cadena_sql.=" inner join acasi on asi_cod=ins_asi_cod ";
                $cadena_sql.=" inner join acest on ins_est_cod=est_cod ";
                $cadena_sql.=" where ins_est_cod=".$variable;
                $cadena_sql.=" and ins_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%P%')";
                $cadena_sql.=" and ins_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%P%')";
                break;

            case 'matriculas':

                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod, ";
                $cadena_sql.=" est_cra_cod, ";
                $cadena_sql.=" est_estado, ";
                $cadena_sql.=" est_ano, ";
                $cadena_sql.=" est_per  ";
                $cadena_sql.=" FROM acesthis ";
                $cadena_sql.=" WHERE est_estado in ('A','B','H') ";
                $cadena_sql.=" AND est_reg='A' ";
                $cadena_sql.=" and est_cod= ".$variable;
                $cadena_sql.=" ORDER BY est_ano,est_per";
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

            case 'consultar_deudas_estudiante':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" deu_est_cod   COD_ESTUDIANTE, ";
                $cadena_sql.=" deu_cpto_cod  COD_CONCEPTO, ";
                $cadena_sql.=" deu_material  MATERIAL, ";
                $cadena_sql.=" deu_ano       ANIO, ";
                $cadena_sql.=" deu_per       PERIODO, ";
                $cadena_sql.=" deu_estado    ESTADO, ";
                $cadena_sql.=" cpto_nombre   CONCEPTO";
                $cadena_sql.=" FROM ";
                $cadena_sql.=" acconcepto, ";
                $cadena_sql.=" acdeudores ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" deu_est_cod =".$variable." ";
                $cadena_sql.=" AND ";
                $cadena_sql.=" cpto_cod = deu_cpto_cod";
                $cadena_sql.=" AND ";
                $cadena_sql.=" deu_estado    NOT IN ('2','3','5') ";
			
                break;
            
            case 'consultar_datos_grado_estudiante':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" egr_cra_cod, ";
                $cadena_sql.=" egr_sexo,";
                $cadena_sql.=" egr_trabajo_grado, ";
                $cadena_sql.=" egr_director_trabajo,";
                $cadena_sql.=" egr_acta_sustentacion,";
                $cadena_sql.=" to_char(egr_fecha_grado,'yyyy-mm-dd') egr_fecha_grado, ";
                $cadena_sql.=" egr_acta_grado,";
                $cadena_sql.=" egr_nota,";
                $cadena_sql.=" egr_libro,";
                $cadena_sql.=" egr_folio,";
                $cadena_sql.=" egr_reg_diploma,";
                $cadena_sql.=" egr_titulo,";
                $cadena_sql.=" egr_rector, ";
                $cadena_sql.=" egr_secretario,";
                $cadena_sql.=" egr_est_cod,";
                $cadena_sql.=" egr_direccion_casa,";
                $cadena_sql.=" egr_telefono_casa,";
                $cadena_sql.=" egr_email,";
                $cadena_sql.=" egr_empresa,";
                $cadena_sql.=" egr_direccion_empresa,";
                $cadena_sql.=" egr_telefono_empresa,";
                $cadena_sql.=" egr_movil,";
                $cadena_sql.=" egr_estado,";
                $cadena_sql.=" (CASE WHEN egr_caracter_nota=2 THEN 'MERITORIO' WHEN egr_caracter_nota=3 THEN 'LAUREADO' ELSE 'N/A' END) MENCION,";
                $cadena_sql.=" egr_tipo_carga, ";
                $cadena_sql.=" egr_director_trabajo_2,";
                $cadena_sql.=" tit_nombre                 TITULO_OBTENIDO,";
                $cadena_sql.=" (sec_nombre||' '||sec_apellido)     SECRETARIO_GRADO,";
                $cadena_sql.=" (rec_nombre||' '||rec_apellido)     RECTOR_GRADO";
                $cadena_sql.=" FROM acegresado";
                $cadena_sql.=" LEFT OUTER JOIN actitulo ON tit_cod=egr_titulo AND tit_cra_cod=egr_cra_cod AND tit_sexo =egr_sexo ";
                $cadena_sql.=" LEFT OUTER JOIN acsecretario ON egr_secretario=sec_cod ";
                $cadena_sql.=" LEFT OUTER JOIN acrector ON egr_rector=rec_cod ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" egr_est_cod=".$variable['codEstudiante']." ";
                $cadena_sql.=" AND egr_cra_cod=".$variable['codProyecto']." ";
                $cadena_sql.=" AND egr_estado='A'";
                break;
           
            case 'consultar_codigo_egresado_por_id':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO";
                $cadena_sql.=" FROM ".$this->configuracion['esquema_academico']."acest ";
                $cadena_sql.=" INNER JOIN ".$this->configuracion['esquema_academico']."accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nro_iden= ".$variable;
                $cadena_sql.=" AND est_estado_est = 'E'";
                $cadena_sql.=" ORDER BY CODIGO desc";
           
                break;
            
        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
