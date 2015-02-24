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
                $cadena_sql.=" cla_tipo_usu TIPO, ";
                $cadena_sql.=" fa_promedio_nota(est_cod) PROMEDIO, " ;
                $cadena_sql.=" est_acuerdo ACUERDO, " ;
                $cadena_sql.=" tra_nivel NIVEL " ;
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado on est_estado_est= estado_cod ";
                $cadena_sql.=" INNER JOIN accra on est_cra_cod= cra_cod ";
                $cadena_sql.=" INNER JOIN geclaves on cla_codigo=est_cod ";
                $cadena_sql.=" INNER JOIN acestotr on EOT_COD= EST_COD ";
                $cadena_sql.=" INNER JOIN actipcra ON tra_cod=cra_tip_cra ";
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
                    $cadena_sql.=" ins_cea_cod CLASIFICACION";
                    $cadena_sql.=" FROM acins";
                    $cadena_sql.=" inner join acasi on ins_asi_cod=asi_cod";
                    $cadena_sql.=" WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" AND ins_ano=".$variable[1];
                    $cadena_sql.=" AND ins_per=".$variable[2];
                    $cadena_sql.=" ORDER BY ins_asi_cod";
/**
                    $cadena_sql="SELECT DISTINCT ins_asi_cod, ";
                    $cadena_sql.="ins_cra_cod CODIGO_CARRERA_ESTUDIANTE, ";
                    $cadena_sql.="ins_gr GRUPO, ";
                    $cadena_sql.="ins_ano, ";
                    $cadena_sql.="ins_per, ";
                    $cadena_sql.="asi_nombre NOMBRE_ASIGNATURA,";
                    $cadena_sql.="ins_est_cod, ";
                    $cadena_sql.="est_pen_nro NRO_PLAN_ESTUDIOS, ";
                    $cadena_sql.="est_nombre, ";
                    $cadena_sql.="pen_cre, ";
                    $cadena_sql.="cea_abr ";
                    $cadena_sql.="FROM acins ";
                    $cadena_sql.="inner join acasi on acins.ins_asi_cod=acasi.asi_cod ";
                    $cadena_sql.="inner join acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.="inner join acpen on acins.ins_asi_cod=acpen.pen_asi_cod ";
                    $cadena_sql.="left outer join geclasificaespac on cea_cod=ins_cea_cod ";
                    $cadena_sql.="WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" AND ins_ano=".$variable[1];
                    $cadena_sql.=" AND ins_per=".$variable[2];
                    //$cadena_sql.=" and pen_nro>200";
                    $cadena_sql.=" ORDER BY ins_asi_cod ";

//                    echo $cadena_sql;
//                    exit;
**/
                break;


//               #consulta de datos del estudiante
//                case "consultaEstudiante":
//                    $cadena_sql="SELECT est_cod, ";//si
//                    $cadena_sql.="est_nombre, ";//si
//                    $cadena_sql.="est_pen_nro, ";//SI
//                    $cadena_sql.="est_cra_cod, ";//SI
//                    $cadena_sql.="cra_nombre ";//SI
//                    $cadena_sql.="FROM acest ";
//                    $cadena_sql.="INNER JOIN accra ON acest.est_cra_cod=accra.cra_cod ";
//                    $cadena_sql.="WHERE est_cod=".$variable;
////                    echo $cadena_sql;
////                    exit;
//                break;

              
             case "consultarGrupos":

                $cadena_sql="select ins_asi_cod CODIGO_ESPACIO, ";
                $cadena_sql.=" ins_gr       GRUPO, ";
                $cadena_sql.=" asi_nombre   NOMBRE_ESPACIO, ";
                $cadena_sql.=" est_pen_nro  PENSUM,";
                $cadena_sql.=" ins_ano      ANIO,";
                $cadena_sql.=" ins_per      PERIODO,";
                $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo)   NRO_GRUPO ";
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
                $cadena_sql.="cur_exa NOTA_EXAMEN, ";
                $cadena_sql.="doc_apellido DOCENTE_APELLIDO, ";
                $cadena_sql.="doc_nombre DOCENTE_NOMBRE";
                $cadena_sql.=" from accurso ";
                $cadena_sql.=" inner join accarga on car_cur_asi_cod= cur_asi_cod and car_cur_nro= cur_nro AND car_ape_ano=cur_ape_ano AND car_ape_per=cur_ape_per";
                $cadena_sql.=" inner join acdocente on car_doc_nro_iden= doc_nro_iden ";
                $cadena_sql.=" where cur_asi_cod='".$variable[0]."'";
                $cadena_sql.=" and cur_nro='".$variable[1]."'";
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
//
//                $cadena_sql="SELECT DISTINCT";
//                $cadena_sql.=" HOR_DIA_NRO DIA,";
//                $cadena_sql.=" HOR_HORA HORA,";
//                $cadena_sql.=" SED_ABREV SEDE,";
//                $cadena_sql.=" Hor_Sal_id_espacio SALON";
//                $cadena_sql.=" FROM Achorario_2012";
//                $cadena_sql.=" INNER JOIN ACCURSO ON Achorario_2012.HOR_ASI_COD=ACCURSO.CUR_ASI_COD AND Achorario_2012.HOR_NRO=ACCURSO.CUR_NRO";
//                $cadena_sql.=" INNER JOIN GESEDE ON Achorario_2012.HOR_SED_COD=GESEDE.SED_COD";
//                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0][0]; //codigo del espacio
//                $cadena_sql.=" AND HOR_NRO=".$variable[0][2];//numero de grupo
//                $cadena_sql.=" AND HOR_APE_ANO=".$variable[0][11];
//                $cadena_sql.=" AND HOR_APE_PER=".$variable[0][12];
//                $cadena_sql.=" ORDER BY 1,2,3";             
//                    
                    //exit;
                    $cadena_sql="SELECT DISTINCT";
                    $cadena_sql.=" horario.hor_dia_nro          DIA,";
                    $cadena_sql.=" horario.hor_hora             HORA,";
                    $cadena_sql.=" sede.sed_id                  SEDE,";
                    $cadena_sql.=" horario.hor_sal_id_espacio   ID_SALON,";
                    $cadena_sql.=" salon.sal_nombre             SALON,";
                    $cadena_sql.=" salon.sal_edificio           ID_EDIFICIO,";
                    $cadena_sql.=" edi.edi_nombre               EDIFICIO,";
                    $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo)   NRO_GRUPO, ";
                    $cadena_sql.=" curso.cur_nro_cupo           CUPO, ";
                    $cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
                    $cadena_sql.=" FROM achorarios horario";
                    $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                    $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                    $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                    $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                    $cadena_sql.=" WHERE cur_asi_cod=".$variable[0][0]; //codigo del espacio
                    $cadena_sql.=" AND cur_ape_ano=" . $variable[0][11];
                    $cadena_sql.=" AND cur_ape_per=" . $variable[0][12];
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
                $cadena_sql.=" REG_CAUSAL_EXCLUSION";
                $cadena_sql.=" FROM reglamento ";
                $cadena_sql.=" WHERE REG_EST_COD= ".$variable;
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


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
