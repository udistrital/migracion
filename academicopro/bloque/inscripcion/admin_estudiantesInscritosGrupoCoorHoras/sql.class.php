<?php
/**
 * SQL admin_estudiantesInscritosGrupoCoorHoras
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 * @author Fernando Torres & Milton Parra
 * @version 0.0.0.1
 * Fecha: 04/04/2011
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la funcion sql.class.php
 */
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

/**
 * Clase sql_adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class sql_estudiantesInscritosGrupoCoorHoras extends sql
{
  public $configuracion;


  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }
    /**
     * Funcion que crea la cadena sql
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo variable que contiene la opcion para seleccionar la cadena sql que se necesita crear
     * @param array $variable Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @return string Cadena sql creada
     */
   function cadena_sql($tipo,$variable="")
	{

	 switch($tipo)
	 {


              //Oracle
            case 'periodo_activo':

                    $cadena_sql="SELECT";
                    $cadena_sql.=" ape_ano ANO,";
                    $cadena_sql.=" ape_per PERIODO";
                    $cadena_sql.=" FROM";
                    $cadena_sql.=" ACASPERI ";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" ape_estado LIKE '%A%'";
                    break;

                //Oracle
            case "buscarDatosProyecto":

                    $cadena_sql="SELECT cra_cod CODIGO, ";
                    $cadena_sql.=" cra_nombre NOMBRE ";
                    $cadena_sql.=" FROM accra";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" cra_cod=".$variable['codProyecto'];
                    break;

              //Oracle
            case 'buscarEspaciosAcademicos':
                    $cadena_sql=" SELECT";
                    $cadena_sql.=" pen_asi_cod  CODIGO,";
                    $cadena_sql.=" asi_nombre   NOMBRE,";
                    $cadena_sql.=" pen_cre      CREDITOS,";
                    $cadena_sql.=" pen_nro_ht   HTD,";
                    $cadena_sql.=" pen_nro_hp   HTC,";
                    $cadena_sql.=" pen_nro_aut  HTA,";
                    $cadena_sql.=" pen_ind_ele  ELECTIVO";
                    $cadena_sql.=" FROM acpen";
                    $cadena_sql.=" INNER JOIN acasi ON pen_asi_cod= asi_cod";
                    $cadena_sql.=" WHERE pen_cra_cod=".$variable['codProyecto'];
                    $cadena_sql.=" AND pen_nro=".$variable['planEstudio'];
                    $cadena_sql.=" AND pen_asi_cod=".$variable['codEspacio'];
                    break;

          //Oracle
            case 'buscarGrupos':

                    $cadena_sql="SELECT ";
                    $cadena_sql.=" cur_id       ID_GRUPO,";
                    $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO,";
                    $cadena_sql.=" cur_nro_cupo CUPO,";
                    $cadena_sql.=" cur_nro_ins  INSCRITOS";
                    $cadena_sql.=" FROM ACCURSOS";
                    $cadena_sql.=" WHERE cur_cra_cod=".$variable['codProyecto'];
                    $cadena_sql.=" AND cur_asi_cod=".$variable['codEspacio'];
                    $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                    $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                    $cadena_sql.=" ORDER BY cur_grupo";
                    break;

                //Oracle
            case 'buscarHorarioGrupo':

                    $cadena_sql="SELECT DISTINCT";
                    $cadena_sql.=" hor_dia_nro          DIA,";
                    $cadena_sql.=" hor_hora             HORA,";
                    $cadena_sql.=" sed_id               SEDE,";
                    $cadena_sql.=" hor_sal_id_espacio   SALON";
                    $cadena_sql.=" FROM achorarios horario";
                    $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                    $cadena_sql.=" INNER JOIN gesalones salon ON horario.hor_sal_id_espacio= salon.sal_id_espacio";
                    $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                    $cadena_sql.=" WHERE cur_asi_cod=".$variable['espacio'];
                    $cadena_sql.=" AND hor_id_curso=".$variable['codGrupo'];
                    $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                    $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                    $cadena_sql.=" ORDER BY 1,2,3";             
                    break;


           //Oracle
            case 'contarInscritos':

                    $cadena_sql="SELECT COUNT(*)";
                    $cadena_sql.=" FROM ACINS";
                    $cadena_sql.=" WHERE INS_ASI_COD=".$variable['espacio'];
                    $cadena_sql.=" AND INS_GR=".$variable['codGrupo'];
                    $cadena_sql.=" AND INS_ANO=".$variable['ano'];
                    $cadena_sql.=" AND INS_PER=".$variable['periodo'];
                    $cadena_sql.=" AND INS_ESTADO='A'";
                    break;


           //Oracle
            case 'buscarEstudiantesInscritos':

                    $cadena_sql="SELECT";
                    $cadena_sql.=" ins_est_cod CODIGO";
                    $cadena_sql.=" FROM";
                    $cadena_sql.=" ACINS";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" INS_ASI_COD=".$variable['codEspacio'];
                    $cadena_sql.=" AND INS_GR=".$variable['grupo'];
                    $cadena_sql.=" AND INS_ANO=".$variable['ano'];
                    $cadena_sql.=" AND INS_PER=".$variable['periodo'];
                    $cadena_sql.=" AND INS_ESTADO='A'";
                    $cadena_sql.=" ORDER BY CODIGO";
                    break;


           //Oracle
            case 'buscarDatosEstudiantes':

                    $cadena_sql="SELECT";
                    $cadena_sql.=" est_cod          CODIGO,";
                    $cadena_sql.=" est_nombre       NOMBRE,";
                    $cadena_sql.=" est_cra_cod      PROYECTO,";
                    $cadena_sql.=" cra_abrev        PROYECTOABREV,";
                    $cadena_sql.=" est_estado_est   ESTADO,";
                    $cadena_sql.=" est_pen_nro      PLAN";
                    $cadena_sql.=" FROM";
                    $cadena_sql.=" acest";
                    $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" est_cod=".$variable['codEstudiante'];
                    break;














//***************************************




              
                case 'espacio_grupo':

                    $cadena_sql="select distinct cur_asi_cod, cur_nro, cur_cra_cod, cur_nro_cupo, cur_nro_ins, pen_sem from ACCURSOS ";
                    $cadena_sql.="inner join acpen on ACCURSOS.cur_asi_cod=acpen.pen_asi_cod ";
                    $cadena_sql.="inner join accra on ACCURSOS.cur_cra_cod=acpen.pen_cra_cod ";
                    $cadena_sql.="where cur_ape_ano=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.="and cur_ape_per=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO LIKE '%A%') ";
                    $cadena_sql.="and cur_asi_cod=".$variable[0];
                    $cadena_sql.=" and cur_nro=".$variable[1];
                    $cadena_sql.=" order by 6,1,2";


                break;


                case 'datos_espacio':

                    $cadena_sql="SELECT distinct id_nivel, PEE.`id_espacio` , `espacio_nombre` , `espacio_nroCreditos` , `espacio_horasDirecto` , `espacio_horasCooperativo` , `espacio_horasAutonomo`,id_clasificacion ";
                    $cadena_sql.=" FROM `sga_espacio_academico` EA ";
                    $cadena_sql.=" INNER JOIN sga_planEstudio_espacio PEE ON EA.id_espacio=PEE.id_espacio ";
                    $cadena_sql.=" WHERE EA.`id_espacio` = ".$variable;

                break;

                case 'grupos_del_espacio_academico':
                    
                    $cadena_sql="select distinct cur_nro from ACCURSOS ";
                    $cadena_sql.=" inner join achorarios on cur_id= hor_id_curso ";
                    $cadena_sql.=" inner join acasperi on ape_ano=cur_ape_ano and ape_per=cur_ape_per";
                    $cadena_sql.=" where cur_asi_cod=".$variable[0];
                    $cadena_sql.=" and ape_estado='A'";
                    $cadena_sql.=" and cur_cra_cod= ".$variable[3];
                    $cadena_sql.=" ORDER BY cur_nro";

                break;

                case 'horario_grupos':

                    $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                    $cadena_sql.=" FROM ACHORARIOS ";
                    $cadena_sql.=" INNER JOIN ACCURSOS ON ACHORARIO.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                    $cadena_sql.=" INNER JOIN GESALONES ON ACHORARIOS.HOR_SAL_ID_ESPACIO = GESALONES.SAL_ID_ESPACIO";
                    $cadena_sql.=" INNER JOIN ACASPERI ON APE_ANO=CUR_APE_ANO AND APE_PER=CUR_APE_PER";
                    $cadena_sql.=" LEFT OUTER JOIN GESEDE ON GESALONES.SAL_SED_ID=GESEDE.SED_ID ";
                    $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0];
                    $cadena_sql.=" AND CUR_CRA_COD=".$variable[1];
                    $cadena_sql.=" AND APE_ESTADO ='A') ";
                    $cadena_sql.=" AND HOR_NRO=".$variable[3];
                    $cadena_sql.=" ORDER BY 1,2,3";

                    break;

                case "estudiantesInscritos":

                    $cadena_sql="select est_cod, est_nombre, cra_nombre, est_ind_cred, (select cea_abr from geclasificaespac where cea_cod=ins_cea_cod) ";
                    $cadena_sql.="from acins ";
                    $cadena_sql.="inner join acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.="inner join accra on acest.est_cra_cod=accra.cra_cod ";
                    //$cadena_sql.="INNER JOIN acclasificacpen CLA ON acins.ins_asi_cod = cla.clp_asi_cod and acest.est_pen_nro= cla.clp_pen_nro ";
                    //$cadena_sql.="INNER JOIN GECLASIFICAESPAC CLAS ON clas.cea_cod= cla.clp_cea_cod ";
                    $cadena_sql.="where ins_asi_cod=".$variable[0];
                    $cadena_sql.=" and ins_gr=".$variable[1];
                    $cadena_sql.=" and ins_ano=(select ape_ano from acasperi where ape_estado like '%A%')";
                    $cadena_sql.=" and ins_per=(select ape_per from acasperi where ape_estado like '%A%')";
                    //$cadena_sql.=" and ins_est_cod=20091005054";
                    $cadena_sql.=" ORDER BY 1";
                    //echo $cadena_sql; exit;

                break;


                case 'consultaGrupo':

                    $cadena_sql="SELECT DISTINCT ins_asi_cod, ";//0
                    $cadena_sql.="ins_cra_cod, ";//1
                    $cadena_sql.="ins_gr, ";               //2
                    $cadena_sql.="ins_ano, ";                 //3
                    $cadena_sql.="ins_per, ";              //4
                    $cadena_sql.="asi_nombre,";              //5
                    $cadena_sql.="ins_est_cod, ";              //6
                    $cadena_sql.="est_pen_nro, ";              //7
                    $cadena_sql.="est_nombre, ";    //8
                    $cadena_sql.="pen_cre ";    //9
                    $cadena_sql.="FROM acins ";
                    $cadena_sql.="inner join acasi on acins.ins_asi_cod=acasi.asi_cod ";
                    $cadena_sql.="inner join acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.="inner join acpen on acins.ins_asi_cod=acpen.pen_asi_cod and acest.est_pen_nro=acpen.pen_nro ";
                    $cadena_sql.="WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" AND ins_ano=".$variable[1];
                    $cadena_sql.=" AND ins_per=".$variable[2];
                    $cadena_sql.=" ORDER BY ins_asi_cod ";

//                    echo $cadena_sql;
//                    exit;

                break;

            case 'consultaFechas':

                    $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                    $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                    $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                    $cadena_sql.="WHERE `id_usuario_afectado` =".$variable[0];
                    $cadena_sql.=" and CFE.id_cobertura_evento =".$variable[1];
                    $cadena_sql.=" AND fecha_ano=".$variable[2];
                    $cadena_sql.=" AND fecha_periodo=".$variable[3];
                    $cadena_sql.=" and id_evento between '102' AND '107'";
                    $cadena_sql.=" and fecha_estado=1";
                    $cadena_sql.=" ORDER BY id_evento";
//echo $cadena_sql;exit;
                    break;

                case 'consultaFechasGeneral':

                    $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                    $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                    $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                    $cadena_sql.=" WHERE CFE.id_cobertura_evento =".$variable[1];
                    $cadena_sql.=" AND fecha_ano=".$variable[2];
                    $cadena_sql.=" AND fecha_periodo=".$variable[3];
                    $cadena_sql.=" and id_evento between '102' AND '107'";
                    $cadena_sql.=" and fecha_estado=1";
                    $cadena_sql.=" ORDER BY id_evento";
//echo $cadena_sql;exit;
                    break;



                case 'facultad':

                    $cadena_sql="SELECT id_facultad ";
                    $cadena_sql.="FROM `sga_proyectoCurricular` ";
                    $cadena_sql.="WHERE `id_usuario_afectado` =".$variable;

                    break;

                case 'periodoActivo':

                    $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                    $cadena_sql.="WHERE ape_estado like '%A%'";
                    break;


                case 'clasificacionEspacio':

                    $cadena_sql="SELECT CL.id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".  $this->configuracion['prefijo']."planEstudio_espacio PEE ";
                    $cadena_sql.="inner join ".  $this->configuracion['prefijo']."espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion";
                    $cadena_sql.=" where id_espacio=".$variable;
                    break;

                case 'clasificacion':

                    $cadena_sql="SELECT id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".  $this->configuracion['prefijo']."espacio_clasificacion";
                    break;

                case 'horario_grupos':

                    $cadena_sql="SELECT DISTINCT  HOR_DIA_NRO, HOR_HORA, SED_ABREV, HOR_SAL_COD ";
                    $cadena_sql.=" FROM ACHORARIOS ";
                    $cadena_sql.=" INNER JOIN ACCURSOS ON ACHORARIOS.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                    $cadena_sql.=" INNER JOIN GESALONES ON ACHORARIOS.HOR_SAL_ID_ESPACIO = GESALONES.SAL_ID_ESPACIO";
                    $cadena_sql.=" INNER JOIN ACASPERI ON APE_ANO=CUR_APE_ANO AND APE_PER=CUR_APE_PER";
                    $cadena_sql.=" LEFT OUTER JOIN GESEDE ON GESALONES.SAL_SED_ID=GESEDE.SED_ID ";
                    $cadena_sql.=" WHERE CUR_ASI_COD=".$variable[0][0]; //codigo del espacio
//                    $cadena_sql.=" AND CUR_CRA_COD=".$variable[0][1];  //codigo del proyecto curricular
                    $cadena_sql.=" AND APE_ESTADO = 'A'";
                    $cadena_sql.=" AND HOR_NRO=".$variable[0][2];//numero de grupo
                    $cadena_sql.=" ORDER BY 1,2,3";//no cambiar el orden

                    //echo "cadena".$cadena_sql;
                    //exit;

                break;

                case 'consultaCreditosSemestre':

                    $cadena_sql="SELECT semestre_nroCreditosEstudiante ";
                    $cadena_sql.="FROM ".  $this->configuracion['prefijo']."semestre_creditos_estudiante ";
                    $cadena_sql.="WHERE semestre_codEstudiante=".$variable; //codigo del espacio
                break;

                case 'consultaRegistroHorario':

                    $cadena_sql="SELECT horario_codEstudiante, horario_idProyectoCurricular, horario_idPlanEstudio, horario_ano, horario_periodo, espacio_nroCreditos ";
                    $cadena_sql.=" FROM ".  $this->configuracion['prefijo']."horario_estudiante HE ";
                    $cadena_sql.="inner join ".  $this->configuracion['prefijo']."espacio_academico EA on HE.horario_idEspacio=EA.id_espacio ";
                    $cadena_sql.="WHERE horario_codEstudiante=".$variable; //codigo del estudiante
                    $cadena_sql.=" AND horario_estado!='3'"; //codigo del estudiante
                break;

                case 'grabarCreditosNuevo':

                    $cadena_sql="INSERT INTO ".  $this->configuracion['prefijo']."semestre_creditos_estudiante ";
                    $cadena_sql.=" VALUES( ";
                    $cadena_sql.="'".$variable[0]."',";
                    $cadena_sql.="'".$variable[1]."',";
                    $cadena_sql.="'".$variable[2]."',";
                    $cadena_sql.="'".$variable[3]."',";
                    $cadena_sql.="'".$variable[4]."',";
                    $cadena_sql.="'".$variable[5]."',";
                    $cadena_sql.="'0')";
                break;

                case 'periodoActivo':

                    $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                    $cadena_sql.="WHERE ape_estado like '%A%'";
                    break;

                case 'consultaFechas':

                    $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                    $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                    $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                    $cadena_sql.="WHERE `id_usuario_afectado` =".$variable[0];
                    $cadena_sql.=" and CFE.id_cobertura_evento =".$variable[1];
                    $cadena_sql.=" AND fecha_ano=".$variable[2];
                    $cadena_sql.=" AND fecha_periodo=".$variable[3];
                    $cadena_sql.=" and id_evento between '102' AND '107'";
                    $cadena_sql.=" and fecha_estado=1";
                    $cadena_sql.=" ORDER BY id_evento";
                    break;

                case 'consultaFechasGeneral':

                    $cadena_sql="SELECT id_evento,fecha_inicio, fecha_fin  ";
                    $cadena_sql.="FROM `sga_calendario_usuario_afectado` CUA ";
                    $cadena_sql.="inner join sga_calendario_fecha_evento CFE ON CUA.id_fecha_evento=CFE.id_fecha_evento ";
                    $cadena_sql.=" WHERE CFE.id_cobertura_evento =".$variable[1];
                    $cadena_sql.=" AND fecha_ano=".$variable[2];
                    $cadena_sql.=" AND fecha_periodo=".$variable[3];
                    $cadena_sql.=" and id_evento between '102' AND '107'";
                    $cadena_sql.=" and fecha_estado=1";
                    $cadena_sql.=" ORDER BY id_evento";
                    break;



                case 'facultad':

                    $cadena_sql="SELECT id_facultad ";
                    $cadena_sql.="FROM `sga_proyectoCurricular` ";
                    $cadena_sql.="WHERE `id_usuario_afectado` =".$variable;
                    
                    break;

	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>