<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroHomologacionTransferenciaInterna extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {
            case 'periodoActivo':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado= 'A'";
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

             case 'consultarNotasEstudiante':

                            $cadena_sql="SELECT NOT_CRA_COD COD_CARRERA,";
                            $cadena_sql.="NOT_EST_COD COD_ESTUDIANTE, ";
                            $cadena_sql.="NOT_ASI_COD COD_ESPACIO, ";
                            $cadena_sql.="NOT_ANO ANO_NOTA, ";
                            $cadena_sql.="NOT_PER PERIODO_NOTA, ";
                            $cadena_sql.="NOT_SEM NIVEL_NOTA, ";
                            $cadena_sql.="NOT_NOTA NOTA, ";
                            $cadena_sql.="NVL(NOT_GR,0) GRUPO, ";
                            $cadena_sql.="NOT_OBS OBSERVACION, ";
                            $cadena_sql.="NOT_CRED CREDITOS, ";
                            $cadena_sql.="NOT_NRO_HT HTD, ";
                            $cadena_sql.="NOT_NRO_HP HTC, ";
                            $cadena_sql.="NOT_NRO_AUT HTA, ";
                            $cadena_sql.="NOT_CEA_COD CLASIFICACION, ";
                            $cadena_sql.="NOT_ASI_COD_INS COD_INSCRITA, ";
                            $cadena_sql.="NOT_ASI_HOMOLOGA HOMOLOGA ";
                            $cadena_sql.="FROM  ACNOT  ";
                            $cadena_sql.=" WHERE NOT_EST_COD =".$variable['cod_estudiante'];
                            $cadena_sql.=" AND NOT_CRA_COD =".$variable['cod_proyecto'];
                            $cadena_sql.=" AND NOT_EST_REG='A'";
                            $cadena_sql.=" ORDER BY NOT_ASI_COD, NOT_NOTA DESC";
                break;
           
            
            case 'consultarDatosEstudiante':

                            $cadena_sql="SELECT EST_COD AS COD_ESTUDIANTE,";
                            $cadena_sql.="EST_CRA_COD   AS CRA_COD, ";
                            $cadena_sql.="EST_NOMBRE    AS NOMBRE, ";
                            $cadena_sql.="EST_PEN_NRO   AS PENSUM, ";
                            $cadena_sql.="EST_IND_CRED   AS TIPO_EST, ";
                            $cadena_sql.="EST_ESTADO_EST   AS ESTADO ";
                            $cadena_sql.="FROM  ACEST  ";
                            $cadena_sql.=" WHERE EST_COD =".$variable['cod_estudiante'];
                            if($variable['cod_proyecto'])
                                $cadena_sql.=" AND EST_CRA_COD=".$variable['cod_proyecto'];
                            
                            
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

            
           case 'consultarDatosProyecto':

                            $cadena_sql="SELECT CRA_COD,";
                            $cadena_sql.="CRA_NOMBRE ";
                            $cadena_sql.="FROM  ACCRA  ";
                            $cadena_sql.=" WHERE CRA_COD =".$variable;
                break;

            case 'consultarNotaAprobatoria':

                            $cadena_sql="SELECT cra_nota_aprob NOTA_APROBATORIA";
                            $cadena_sql.=" FROM accra";
                            $cadena_sql.=" WHERE cra_cod=".$variable['cod_proyecto'];
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
            
            default:
               $cadena_sql='';
                break;
            
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>