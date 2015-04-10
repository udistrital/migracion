<?php
/**
 * SQL cambiarEstados
 *
 * Descripción
 *
 * @package cierreSemestre
 * @subpackage cambiarEstados
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 17/04/2013
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

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

/**
 * Clase sql_registro
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 */
class sql_registroCambiarEstados extends sql
{
  public $configuracion;


  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }

    /**
     * Funcion que arma la cadena sql
     * 
     * @param string $tipo Nombre de la cadena sql
     * @param type $variable contiene pasan los parámetros que se pasan a la cadena sql
     * @return string retorna la cadena sql
     */
    function cadena_sql($tipo,$variable=""){

        switch($tipo)
        {

             //Oracle
             case 'periodo_activo':
                            //El periodo se establece a P para los cierres de los posgrados posterior a la fecha del pregrado 26/12/2013
                            //El período se cambia de P a A para el proceso de cierre de pregrado

//               $cadena_sql="SELECT";
//               $cadena_sql.=" ape_ano ANO,";
//               $cadena_sql.=" ape_per PERIODO";
//               $cadena_sql.=" FROM";
//               $cadena_sql.=" ACASPERI ";
//               $cadena_sql.=" WHERE";
//               $cadena_sql.=" trim(ape_estado)='P'";
                $cadena_sql=" SELECT DISTINCT pec_ano ANO, ";
                $cadena_sql.=" pec_periodo PERIODO, ";
                $cadena_sql.=" pec_estado ESTADO ";
                $cadena_sql.="  FROM acperiodocierre ";
                $cadena_sql.=" WHERE pec_estado IN ('A')";
                $cadena_sql.=" AND pec_cra_cod= ".$variable;
                $cadena_sql.=" ORDER BY pec_estado  ASC";               

               break;
           
            case 'consultarDatosEstudiantes':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" est_cod CODIGO";
                $cadena_sql.=", est_cra_cod CRA_CODIGO";
                $cadena_sql.=", est_valor_matricula MATRICULA";
                $cadena_sql.=", est_exento EXENTO";
                $cadena_sql.=", est_motivo_exento MOTIVO_EXENTO";
                $cadena_sql.=", est_estado_est ESTADO";
                $cadena_sql.=", est_porcentaje PORCENTAJE";
                //$cadena_sql.=", fa_promedio_nota(est_cod) PROMEDIO";
                $cadena_sql.=", est_acuerdo ACUERDO";
                $cadena_sql.=", est_pen_nro PLAN";
                $cadena_sql.=", est_ind_cred CREDITOS";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" WHERE est_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND est_estado_est in (".$variable['estados'].")";
            break;
        
             case 'consultarPromedioEstudiante':
                $cadena_sql=" SELECT ";
                $cadena_sql.="fa_promedio_nota(".$variable.") PROMEDIO";
            break;
                 
            
//Se ajusta pare tener en cuenta solo el año y periodo actual del cierre 2014/12/30          
            case 'consultarInscripciones':
                $cadena_sql=" SELECT DISTINCT ins_est_cod CODIGO";
                $cadena_sql.=" FROM acins";
                $cadena_sql.=" INNER JOIN acest ON est_cod=ins_est_cod";
                $cadena_sql.=" AND est_cra_cod=ins_cra_cod";
                $cadena_sql.=" WHERE ins_estado ='A'";
                $cadena_sql.=" AND ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                $cadena_sql.=" AND est_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND est_estado_est in (".$variable['estados'].")";
            break;
            
            case 'consultarHistoricoEstudiantes':
                $cadena_sql=" SELECT est_cod CODIGO";
                $cadena_sql.=", est_cra_cod CRA_CODIGO";
                $cadena_sql.=", est_estado ESTADO";
                $cadena_sql.=" FROM acesthis";
                $cadena_sql.=" WHERE est_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND est_ano=".$variable['ano'];
                $cadena_sql.=" AND est_per=".$variable['periodo'];
                $cadena_sql.=" AND est_reg='A'";
                $cadena_sql.=" AND est_estado not in ('C','R','P')";
                break;
            
            case 'consultarDatosProyecto':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" cra_cod CODIGO,";
                $cadena_sql.=" tra_cod TIPO,";
                $cadena_sql.=" cra_dep_cod COD_DEPENDENCIA";
                $cadena_sql.=" FROM accra";
                $cadena_sql.=" INNER JOIN actipcra";
                $cadena_sql.=" ON tra_cod=cra_tip_cra";
                $cadena_sql.=" WHERE cra_cod=".$variable['codProyecto'];

            break;
            
            case 'registrarHistoricoDatosEstudiantes':
                $cadena_sql=" INSERT INTO acesthis (";
                $cadena_sql.="EST_COD";
                $cadena_sql.=", EST_CRA_COD";
                $cadena_sql.=", EST_VALOR_MATRICULA";
                $cadena_sql.=", EST_EXENTO";
                $cadena_sql.=", EST_MOTIVO_EXENTO";
                $cadena_sql.=", EST_ESTADO";
                $cadena_sql.=", EST_ANO";
                $cadena_sql.=", EST_PER";
                $cadena_sql.=", EST_REG";
                $cadena_sql.=", EST_PORCENTAJE";
                $cadena_sql.=", EST_ACUERDO";
                $cadena_sql.=", EST_PEN_NRO";
                $cadena_sql.=", EST_IND_CRED)";
                $cadena_sql.=" VALUES (".$variable['EST_COD']."";
                $cadena_sql.=", ".$variable['EST_CRA_COD']."";
                $cadena_sql.=", ".$variable['EST_VALOR_MATRICULA']."";
                $cadena_sql.=", '".$variable['EST_EXENTO']."'";
                $cadena_sql.=", ".$variable['EST_MOTIVO_EXENTO']."";
                $cadena_sql.=", '".$variable['EST_ESTADO']."'";
                $cadena_sql.=", ".$variable['EST_ANO']."";
                $cadena_sql.=", ".$variable['EST_PER']."";
                $cadena_sql.=", 'A'";
                $cadena_sql.=", ".$variable['EST_PORCENTAJE']."";
                $cadena_sql.=", ".$variable['EST_ACUERDO']."";
                $cadena_sql.=", ".$variable['EST_PEN_NRO']."";
                $cadena_sql.=", '".$variable['EST_IND_CRED']."')";
            break;

            case 'registrarReglamentoEstudiante':
                $cadena_sql=" INSERT INTO reglamento (";
                $cadena_sql.="REG_CRA_COD";
                $cadena_sql.=", REG_EST_COD";
                $cadena_sql.=", REG_MOTIVO";
                $cadena_sql.=", REG_ESTADO";
                $cadena_sql.=", REG_ASI_3";
                $cadena_sql.=", REG_VECES";
                $cadena_sql.=", REG_ANO";
                $cadena_sql.=", REG_PER";
                $cadena_sql.=", REG_PROMEDIO";
                $cadena_sql.=", REG_NUMERO_RETIROS";
                $cadena_sql.=", REG_REGLAMENTO)";
                $cadena_sql.=" VALUES (".$variable['EST_CRA_COD']."";
                $cadena_sql.=", ".$variable['EST_COD']."";
                $cadena_sql.=", '0'";
                $cadena_sql.=", 'A'";
                $cadena_sql.=", '0'";
                $cadena_sql.=", '0'";
                $cadena_sql.=", ".$variable['EST_ANO']."";
                $cadena_sql.=", ".$variable['EST_PER']."";
                $cadena_sql.=", ".$variable['EST_PROMEDIO']."";
                $cadena_sql.=", ".$variable['EST_NUM_RETIROS']."";
                $cadena_sql.=", 'N')";
            break;

            case 'insertarEvento':
                $cadena_sql=" INSERT";
                $cadena_sql.=" INTO ACCALEVENTOS";
                $cadena_sql.=" (";
                $cadena_sql.=" ACE_COD_EVENTO,";
                $cadena_sql.=" ACE_CRA_COD,";
                $cadena_sql.=" ACE_FEC_INI,";
                $cadena_sql.=" ACE_TIP_CRA,";
                $cadena_sql.=" ACE_DEP_COD,";
                $cadena_sql.=" ACE_ANIO,";
                $cadena_sql.=" ACE_PERIODO,";
                $cadena_sql.=" ACE_ESTADO,";
                $cadena_sql.=" ACE_HABILITAR_EX";
                $cadena_sql.=" )";
                $cadena_sql.=" VALUES";
                $cadena_sql.=" (";
                $cadena_sql.=" 72,";
                $cadena_sql.=" ".$variable['codProyecto'].",";
                $cadena_sql.=" CURRENT_TIMESTAMP,";
                $cadena_sql.=" ".$variable['tipoProyecto'].",";
                $cadena_sql.=" ".$variable['codDependencia'].",";
                $cadena_sql.=" ".$variable['ano'].",";
                $cadena_sql.=" ".$variable['periodo'].",";
                $cadena_sql.=" 'A',";
                $cadena_sql.=" 'N'";
                $cadena_sql.=" )"; 

            break;
        
            case 'actualizarEvento':
                $cadena_sql=" UPDATE ACCALEVENTOS";
                $cadena_sql.=" SET ACE_FEC_FIN=CURRENT_TIMESTAMP";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ACE_COD_EVENTO=72";
                $cadena_sql.=" AND ACE_CRA_COD=".$variable['codProyecto'];
                $cadena_sql.=" AND ACE_ANIO=".$variable['ano'];
                $cadena_sql.=" AND ACE_PERIODO=".$variable['periodo'];

            break;
        
            case 'actualizarDatosEstudiante':
                $cadena_sql=" UPDATE acest";
                $cadena_sql.=" SET est_estado_est='".$variable['EST_ESTADO']."'";
                $cadena_sql.=" WHERE est_cod=".$variable['EST_COD'];
                $cadena_sql.=" AND est_cra_cod=".$variable['EST_CRA_COD'];
                break;
            
            //se Incluye para contar cancelaciones de estudiantes
            //M ilton Parra
            //17/06/2014
            case 'consultarRetirosAprobadosProyecto':
                $cadena_sql=" SELECT ars_cra_cod PROYECTO,";
                $cadena_sql.=" ars_est_cod CODIGO,";
                $cadena_sql.=" ars_estado_reg ESTADO_REG,";
                $cadena_sql.=" ars_ano ANO,";
                $cadena_sql.=" ars_periodo PERIODO,";
                $cadena_sql.=" ars_tipo_retiro TIPO_RETIRO,";
                $cadena_sql.=" arr_aprob_retiro APROBACION";
                $cadena_sql.=" FROM ac_retirosolicitud";
                $cadena_sql.=" INNER JOIN ac_retirorespuesta";
                $cadena_sql.=" ON ars_consec_solicitud=arr_consec_solicitud AND ars_est_cod=arr_est_cod AND ars_ano=arr_ano AND ars_periodo=arr_periodo";
                $cadena_sql.=" WHERE ars_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND ars_ano=".$variable['ano'];
                $cadena_sql.=" AND ars_periodo=".$variable['periodo'];
                $cadena_sql.=" AND arr_aprob_retiro='1'";

                break;
            
                case 'consultarHistoricosReglamentoRetirados':
                $cadena_sql=" SELECT reg_ano ANO,";
                $cadena_sql.=" reg_per PERIODO,";
                $cadena_sql.=" reg_cra_cod PROYECTO,";
                $cadena_sql.=" reg_est_cod CODIGO,";
                $cadena_sql.=" reg_numero_retiros NUM_RETIROS";
                $cadena_sql.=" FROM reglamento A";
                $cadena_sql.=" WHERE A.reg_est_cod =".$variable['codigo'];
                $cadena_sql.=" AND A.reg_ano::text||a.reg_per::text =";
                $cadena_sql.=" (SELECT max(B.reg_ano::text||B.reg_per::text)";
                $cadena_sql.=" FROM reglamento B";
                $cadena_sql.=" WHERE B.reg_est_cod = A.reg_est_cod";
                $cadena_sql.=" AND B.reg_ano::text||B.reg_per::text!='".$variable['anioper']."')";
                break;
       }

	return $cadena_sql;
   }
}
?>
