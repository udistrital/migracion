<?php
/**
 * SQL adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 23/06/2011
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
class sql_adminRealizarCierreSemestre extends sql
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
            case 'consultarNotasParciales':

                    $cadena_sql=" SELECT ins_cra_cod COD_PROYECTO,";
                    $cadena_sql.=" ins_est_cod COD_ESTUDIANTE,";
                    $cadena_sql.=" ins_asi_cod COD_ESPACIO,";
                    $cadena_sql.=" ins_gr GRUPO,";
                    $cadena_sql.=" ins_obs OBSERVACION,";
                    $cadena_sql.=" ins_estado ESTADO,";
                    $cadena_sql.=" ins_ano ANO,";
                    $cadena_sql.=" ins_per PERIODO,";
                    $cadena_sql.=" cur_semestre NIVEL,";
                    $cadena_sql.=" ins_nota_par1 PARCIAL1,";
                    $cadena_sql.=" ins_nota_par2 PARCIAL2,";
                    $cadena_sql.=" ins_nota_par3 PARCIAL3,";
                    $cadena_sql.=" ins_nota_par4 PARCIAL4,";
                    $cadena_sql.=" ins_nota_par5 PARCIAL5,";
                    $cadena_sql.=" ins_nota_par6 PARCIAL6,";
                    $cadena_sql.=" ins_nota_lab PARCIAL_LAB,";
                    $cadena_sql.=" ins_nota_exa PARCIAL_EX,";
                    $cadena_sql.=" ins_nota_hab PARCIAL_HAB,";
                    $cadena_sql.=" ins_cred creditos,";
                    $cadena_sql.=" ins_nro_ht htd,";
                    $cadena_sql.=" ins_nro_hp htc,";
                    $cadena_sql.=" ins_nro_aut hta,";
                    $cadena_sql.=" ins_cea_cod clasificacion,";
                    $cadena_sql.=" cur_par1 PORCENTAJE1,";
                    $cadena_sql.=" cur_par2 PORCENTAJE2,";
                    $cadena_sql.=" cur_par3 PORCENTAJE3,";
                    $cadena_sql.=" cur_par4 PORCENTAJE4,";
                    $cadena_sql.=" cur_par5 PORCENTAJE5,";
                    $cadena_sql.=" cur_par6 PORCENTAJE6,";
                    $cadena_sql.=" cur_lab PORCENTAJE_LAB,";
                    $cadena_sql.=" cur_exa PORCENTAJE_EX,";
                    $cadena_sql.=" cur_hab PORCENTAJE_HAB,";
                    $cadena_sql.=" ins_nota_acu acumulado";
                    $cadena_sql.=" FROM acins";
                    $cadena_sql.=" INNER JOIN accurso";
                    $cadena_sql.=" ON cur_ape_ano =ins_ano";
                    $cadena_sql.=" AND cur_ape_per=ins_per";
                    $cadena_sql.=" AND cur_asi_cod=ins_asi_cod";
                    $cadena_sql.=" AND cur_nro =ins_gr";
                    $cadena_sql.=" WHERE ins_ano =".$variable['ano'];
                    $cadena_sql.=" AND ins_per =".$variable['periodo'];
                    $cadena_sql.=" AND ins_cra_cod=".$variable['codProyecto'];
                    $cadena_sql.=" AND ins_estado='A'";                            
                    //este filtro se debe quitar
                    //$cadena_sql.=" AND ins_est_cod=8310575";
                    //$cadena_sql.=" ORDER BY ins_asi_cod";

            break;
         
                       
              //Oracle
            case 'actualizarNotasAcins':

                    $cadena_sql=" UPDATE acins";
                    $cadena_sql.=" SET ins_nota=".$variable['acumulado'];
                    $cadena_sql.=" WHERE ins_ano=".$variable['ano'];
                    $cadena_sql.=" AND ins_per=".$variable['periodo'];
                    $cadena_sql.=" AND ins_est_cod=".$variable['codEstudainte'];
                    $cadena_sql.=" AND ins_cra_cod=".$variable['codProyecto'];
                    $cadena_sql.=" AND ins_asi_cod=".$variable['codEspacio'];

            break;

              //Oracle
            case 'consultarEstudiantes':

                    $cadena_sql=" SELECT est_cod CODIGO,";
                    $cadena_sql.=" est_cra_cod COD_PROYECTO,";
                    $cadena_sql.=" est_nombre NOMBRE,";
                    $cadena_sql.=" EST_ESTADO_EST ESTADO,";
                    $cadena_sql.=" EST_ESTADO ESTADO_SOLO,";
                    $cadena_sql.=" EST_PEN_NRO COD_PLAN,";
                    $cadena_sql.=" EST_IND_CRED TIPO,";
                    $cadena_sql.=" est_acuerdo ACUERDO";
                    $cadena_sql.=" FROM acest";
                    $cadena_sql.=" WHERE est_cra_cod=".$variable['codProyecto'];
                    $cadena_sql.=" AND est_estado_est IN ('A', 'B')";
                    //$cadena_sql.=" AND est_cod='20041005100'";//para uno solo estudiante

            break;
            
            
            case 'consultarTablaHomologaciones':

                    $cadena_sql="SELECT DISTINCT ";
                    $cadena_sql.="hom_id  AS ID_HOM, ";
                    $cadena_sql.="hom_cra_cod_ppal  AS COD_CRA_PPAL, ";
                    $cadena_sql.="hom_asi_cod_ppal  AS COD_ASI_PPAL, ";
                    $cadena_sql.="hom_cra_cod_hom   AS COD_CRA_HOM, ";
                    $cadena_sql.="hom_asi_cod_hom   AS COD_ASI_HOM, ";
                    $cadena_sql.="hom_estado        AS ESTADO, ";
                    $cadena_sql.="hom_fecha_reg     AS FEC_REG, ";
                    $cadena_sql.="hom_tipo_hom      AS TIPO_HOMOLOGACION, ";
                    $cadena_sql.="hpo_porcentaje      AS PORCENTAJE, ";
                    $cadena_sql.="hpo_requiere_aprobar AS REQ_APROBAR, ";
                    $cadena_sql.="hpo_estado      AS ESTADO_PORCENTAJE,";
                    $cadena_sql.="hpo_anio      AS ANIO_PORCENTAJE, ";
                    $cadena_sql.="hpo_periodo      AS PERIODO_PORCENTAJE ";
                    $cadena_sql.="FROM  ACTABLAHOMOLOGACION  ";
                    $cadena_sql.="LEFT OUTER JOIN ACHOMOLOGACION_PORCENTAJES ON hom_id=hpo_id_hom AND hpo_estado='A' ";
                    $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable;
                    $cadena_sql.=" AND hom_estado='A'";
                            
            break;            

            case 'consultarNotaAprobatoria':

                        $cadena_sql="SELECT cra_nota_aprob NOTA_APROBATORIA";
                        $cadena_sql.=" FROM accra";
                        $cadena_sql.=" WHERE cra_cod=".$variable['cod_proyecto'];                                                        
            break;            


            case 'consultarEspaciosProyecto':

                    $cadena_sql="SELECT DISTINCT ";
                    $cadena_sql.="pen_cra_cod   AS COD_CRA,";
                    $cadena_sql.="pen_asi_cod   AS COD_ASI,";
                    $cadena_sql.="pen_sem       AS SEMESTRE,";
                    $cadena_sql.="pen_ind_ele   AS INDICA_ELECTIVA,";
                    $cadena_sql.="pen_nro_ht    AS H_TEORICAS,";
                    $cadena_sql.="pen_nro_hp    AS H_PRACTICAS,";
                    $cadena_sql.="pen_estado    AS ESTADO,";
                    $cadena_sql.="pen_cre       AS CREDITOS,";
                    $cadena_sql.="pen_nro       AS PENSUM,";
                    $cadena_sql.="pen_nro_aut   AS H_AUTONOMO,";
                    $cadena_sql.="pen_ind_prom  AS INDICA_PROMEDIO,";
                    $cadena_sql.="clp_cea_cod   AS CLASIFICACION ";
                    $cadena_sql.=" FROM  ACPEN  ";
                    $cadena_sql.="LEFT OUTER JOIN ACCLASIFICACPEN ON clp_cra_cod=pen_cra_cod AND clp_asi_cod=pen_asi_cod AND clp_pen_nro=pen_nro ";
                    $cadena_sql.="WHERE pen_estado ='A' ";
                    $cadena_sql.="AND pen_cra_cod =".$variable." ";

                break;            


            case 'consultarNotasInscripcionesEstudiante':

                    $cadena_sql=" select ins_cra_cod COD_PROYECTO,";
                    $cadena_sql.=" ins_est_cod COD_ESTUDIANTE,";
                    $cadena_sql.=" ins_asi_cod COD_ESPACIO,";
                    $cadena_sql.=" ins_ano ANO,";
                    $cadena_sql.=" ins_per PERIODO,";
                    $cadena_sql.=" ins_nota_acu NOTA,";
                    $cadena_sql.=" ins_gr GRUPO,";
                    $cadena_sql.=" NVL(ins_obs,0) OBSERVACION,";
                    $cadena_sql.=" ins_cred CREDITOS,";
                    $cadena_sql.=" ins_nro_ht HTD,";
                    $cadena_sql.=" ins_nro_hp HTC,";
                    $cadena_sql.=" ins_nro_aut HTA,";
                    $cadena_sql.=" ins_cea_cod CLASIFICACION";
                    $cadena_sql.=" from acins";
                    $cadena_sql.=" where ins_est_cod =".$variable['cod_estudiante'];
                    $cadena_sql.=" and ins_cra_cod =".$variable['cod_proyecto'];
                    $cadena_sql.=" and ins_estado ='A'";
                    $cadena_sql.=" order by ins_asi_cod,";
                    $cadena_sql.=" ins_nota_acu DESC"; 


            break;                

                case 'adicionar_homologacion':
                    $cadena_sql="INSERT INTO ACNOT ";
                    $cadena_sql.="(NOT_CRA_COD, NOT_EST_COD, NOT_ASI_COD, NOT_ANO, NOT_PER, NOT_SEM, NOT_NOTA, NOT_GR, NOT_OBS, NOT_FECHA, NOT_EST_REG, NOT_CRED, NOT_NRO_HT, NOT_NRO_HP, NOT_NRO_AUT, NOT_CEA_COD, NOT_ASI_COD_INS, NOT_ASI_HOMOLOGA, NOT_EST_HOMOLOGA) ";
                    $cadena_sql.="VALUES (";
                    $cadena_sql.="'".$variable['NOT_CRA_COD']."',";
                    $cadena_sql.="'".$variable['NOT_EST_COD']."',";
                    $cadena_sql.="'".$variable['NOT_ASI_COD']."',";
                    $cadena_sql.="'".$variable['NOT_ANO']."',";
                    $cadena_sql.="'".$variable['NOT_PER']."',";
                    $cadena_sql.="'".$variable['NOT_SEM']."',";
                    $cadena_sql.="'".$variable['NOT_NOTA']."',";
                    $cadena_sql.="'".$variable['NOT_GR']."',";
                    $cadena_sql.="'".$variable['NOT_OBS']."',";
                    $cadena_sql.="to_date('".$variable['NOT_FECHA']."','dd/mm/yyyy'),";
                    $cadena_sql.="'".$variable['NOT_EST_REG']."',";
                    $cadena_sql.="'".$variable['NOT_CRED']."',";
                    $cadena_sql.="'".$variable['NOT_NRO_HT']."',";
                    $cadena_sql.="'".$variable['NOT_NRO_HP']."',";
                    $cadena_sql.="'".$variable['NOT_NRO_AUT']."',";
                    $cadena_sql.="'".$variable['NOT_CEA_COD']."',";
                    $cadena_sql.="'".$variable['NOT_ASI_COD_INS']."',";
                    $cadena_sql.="'".$variable['NOT_ASI_HOMOLOGA']."',";
                    $cadena_sql.="'".$variable['NOT_EST_HOMOLOGA']."')";

                break;
            
            
                case 'consultasDatosProyecto':
                    $cadena_sql=" SELECT ";
                    $cadena_sql.=" cra_cod CODIGO,";
                    $cadena_sql.=" tra_cod TIPO,";
                    $cadena_sql.=" cra_dep_cod COD_DEPENDENCIA";
                    $cadena_sql.=" FROM accra";
                    $cadena_sql.=" INNER JOIN actipcra";
                    $cadena_sql.=" ON tra_cod=cra_tip_cra";
                    $cadena_sql.=" WHERE cra_cod=".$variable['codProyecto'];

                break;
        
            
                case 'insertarAccaleventos':
                    $cadena_sql=" INSERT";
                    $cadena_sql.=" INTO ACCALEVENTOS";
                    $cadena_sql.=" (";
                    $cadena_sql.=" ACE_COD_EVENTO,";
                    $cadena_sql.=" ACE_CRA_COD,";
                    $cadena_sql.=" ACE_FEC_INI,";
                    $cadena_sql.=" ACE_FEC_FIN,";
                    $cadena_sql.=" ACE_TIP_CRA,";
                    $cadena_sql.=" ACE_DEP_COD,";
                    $cadena_sql.=" ACE_ANIO,";
                    $cadena_sql.=" ACE_PERIODO,";
                    $cadena_sql.=" ACE_ESTADO,";
                    $cadena_sql.=" ACE_HABILITAR_EX";
                    $cadena_sql.=" )";
                    $cadena_sql.=" VALUES";
                    $cadena_sql.=" (";
                    $cadena_sql.=" 71,";
                    $cadena_sql.=" ".$variable['codProyecto'].",";
                    $cadena_sql.=" TO_DATE(sysdate),";
                    $cadena_sql.=" TO_DATE(sysdate),";
                    $cadena_sql.=" ".$variable['tipoProyecto'].",";
                    $cadena_sql.=" ".$variable['codDependencia'].",";
                    $cadena_sql.=" ".$variable['ano'].",";
                    $cadena_sql.=" ".$variable['periodo'].",";
                    $cadena_sql.=" 'A',";
                    $cadena_sql.=" 'N'";
                    $cadena_sql.=" )"; 

                break;
        
            
            
        
        
	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
