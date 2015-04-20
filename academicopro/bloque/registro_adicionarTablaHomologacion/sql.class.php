<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarTablaHomologaciones extends sql {
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

            case 'buscarEspacioAcademico':
                 $cadena_sql="SELECT DISTINCT ";
                 $cadena_sql.="ASI_NOMBRE NOM_ASIGNATURA ";
                 $cadena_sql.="FROM ACPEN, ACASI ";
                 $cadena_sql.="WHERE PEN_ESTADO='A' ";
                 $cadena_sql.="AND PEN_ASI_COD = ASI_COD ";
                 $cadena_sql.="AND PEN_ASI_COD= ".$variable['cod_espacio'];
                 if ($variable['cod_proyecto'])
                    $cadena_sql.=" AND PEN_CRA_COD= ".$variable['cod_proyecto'];
                 $cadena_sql.=" ORDER BY ASI_NOMBRE ";
                break;
            
            case 'buscarRegistroHomologacion':

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="hom_cra_cod_ppal  AS COD_CRA_PPAL, ";
                            $cadena_sql.="hom_asi_cod_ppal  AS COD_ASI_PPAL, ";
                            $cadena_sql.="hom_cra_cod_hom   AS COD_CRA_HOM, ";
                            $cadena_sql.="hom_asi_cod_hom   AS COD_ASI_HOM, ";
                            $cadena_sql.="TRIM(hom_estado)        AS ESTADO, ";
                            $cadena_sql.="hom_fecha_reg     AS FEC_REG, ";
                            $cadena_sql.="hom_tipo_hom      AS TIPO_HOMOLOGACION ";
                            $cadena_sql.="FROM  ACTABLAHOMOLOGACION  ";
                            $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable['cod_proyecto']." ";
                            $cadena_sql.="AND hom_asi_cod_ppal =".$variable['cod_padre']." ";
                            if ($variable['cod_proyecto_hom']){
                                $cadena_sql.="AND hom_cra_cod_hom =".$variable['cod_proyecto_hom']." ";
                            }
                            $cadena_sql.="AND hom_asi_cod_hom =".$variable['cod_hijo']." ";
                            $cadena_sql.="AND hom_tipo_hom =0 ";
                           
                break;

            case 'buscarPlanEstudios':
                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="pen_nro  AS PEN_NRO ";
                            $cadena_sql.="FROM  ACPEN  ";
                            $cadena_sql.="WHERE pen_asi_cod =".$variable['cod_espacio']." ";
                            if($variable['cod_proyecto']){
                                $cadena_sql.="AND pen_cra_cod =".$variable['cod_proyecto']." ";
                            }
                            $cadena_sql.="AND pen_estado ='A' ";
                            
                break;

                
            case 'buscarHomologacionesPlanEstudios':
                    $cadena_sql="SELECT DISTINCT ";
                    $cadena_sql.="hom_cra_cod_ppal   AS COD_CRA_PPAL, ";
                    $cadena_sql.="hom_asi_cod_ppal AS COD_ASI_PPAL, ";
                    $cadena_sql.="pen_nro as PE_PPAL, ";
                    $cadena_sql.="hom_cra_cod_hom AS COD_CRA_HOM, ";
                    $cadena_sql.="hom_asi_cod_hom AS COD_ASI_HOM, ";
                    $cadena_sql.="TRIM(hom_estado) AS ESTADO, ";
                    $cadena_sql.="hom_fecha_reg AS FEC_REG, ";
                    $cadena_sql.="hom_tipo_hom AS TIPO_HOMOLOGACION ";
                    $cadena_sql.="FROM  ACTABLAHOMOLOGACION , ACPEN  ";
                    $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable['cod_proyecto']." ";
                    $cadena_sql.="AND hom_asi_cod_hom =".$variable['cod_hijo']." ";
                    $cadena_sql.="AND hom_cra_cod_ppal=pen_cra_cod ";
                    $cadena_sql.="AND hom_asi_cod_ppal=pen_asi_cod ";
                    if($variable['pe_padre']){
                        $cadena_sql.="AND pen_nro IN (".$variable['pe_padre'].") ";
                        $cadena_sql.="AND TRIM(hom_estado)='A'";
                    }
                    $cadena_sql.="AND pen_estado ='A' ";
                            
                break;

            case 'adicionar_tabla_homologacion':
                    $cadena_sql="INSERT INTO ACTABLAHOMOLOGACION ";
                    $cadena_sql.="(hom_id, hom_cra_cod_ppal, hom_asi_cod_ppal, hom_cra_cod_hom, hom_asi_cod_hom, hom_estado,hom_fecha_reg, hom_tipo_hom) ";
                    $cadena_sql.="VALUES ('".$variable['identificador']."',";
                    $cadena_sql.="'".$variable['cod_proyecto']."',";
                    $cadena_sql.="'".$variable['cod_padre']."',";
                    $cadena_sql.="'".$variable['cod_proyecto_hom']."',";
                    $cadena_sql.="'".$variable['cod_hijo']."',";
                    $cadena_sql.="'A',";
                    //$cadena_sql.="to_date(current_timestamp,'dd/mm/yyyy'),";
                    $cadena_sql.="'".$variable['time']."',";
                    $cadena_sql.="'".$variable['tipo']."')"; 
                
                break;

            
            case 'buscarUltimoIndiceTablaHomologacion':
                    $cadena_sql="SELECT MAX( ";
                    $cadena_sql.="hom_id) AS ULTIMO_CODIGO ";
                    $cadena_sql.="FROM  ACTABLAHOMOLOGACION";
                            
                break;
  
            case 'buscarRegistroUnion':

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="hom_cra_cod_ppal  AS COD_CRA_PPAL, ";
                            $cadena_sql.="hom_asi_cod_ppal  AS COD_ASI_PPAL, ";
                            $cadena_sql.="hom_cra_cod_hom   AS COD_CRA_HOM, ";
                            $cadena_sql.="hom_asi_cod_hom   AS COD_ASI_HOM, ";
                            $cadena_sql.="TRIM(hom_estado)        AS ESTADO, ";
                            $cadena_sql.="hom_fecha_reg     AS FEC_REG, ";
                            $cadena_sql.="hom_tipo_hom      AS TIPO_HOMOLOGACION ";
                            $cadena_sql.="FROM  ACTABLAHOMOLOGACION  ";
                            $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable['cod_proyecto']." ";
                            $cadena_sql.="AND hom_asi_cod_ppal =".$variable['cod_padre']." ";
                            if ($variable['cod_proyecto_hom']){
                                $cadena_sql.="AND hom_cra_cod_hom =".$variable['cod_proyecto_hom']." ";
                            }
                            $cadena_sql.="AND hom_asi_cod_hom IN (".$variable['cod_hijo1'].",".$variable['cod_hijo2'].") ";
                            $cadena_sql.="AND hom_tipo_hom =1 ";
                            $cadena_sql.="ORDER BY hom_fecha_reg";
                            
                break;

            case 'adicionar_tabla_porcentajes':
                    $cadena_sql="INSERT INTO ACHOMOLOGACION_PORCENTAJES ";
                    $cadena_sql.="(HPO_ID_HOM, HPO_PORCENTAJE, HPO_REQUIERE_APROBAR, HPO_ESTADO, HPO_ANIO, HPO_PERIODO, HPO_FECHA_REG) ";
                    $cadena_sql.="VALUES ('".$variable['identificador']."',"; 
                    $cadena_sql.="'".(isset($variable['porc_hijo'])?$variable['porc_hijo']:'')."',";
                    $cadena_sql.="'".$variable['req_hijo']."',";
                    $cadena_sql.="'A',";
                    $cadena_sql.="'".$variable['annio']."',";
                    $cadena_sql.="'".$variable['periodo']."',";
                    //$cadena_sql.="to_date(current_timestamp,'dd/mm/yyyy'),";
                    $cadena_sql.="'".$variable['time']."')";
                
                break;

            case 'eliminar_tabla_homologacion':
                    $cadena_sql="DELETE FROM ACTABLAHOMOLOGACION ";
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="hom_id=".$variable['identificador'];
                    
                break;
            
            case 'eliminar_tabla_porcentaje_homologacion':
                    $cadena_sql="DELETE FROM ACHOMOLOGACION_PORCENTAJES ";
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="hpo_id_hom=".$variable['identificador'];
                    $cadena_sql.=" AND hpo_fecha_reg=".$variable['time'];
                    
                break;
            
            case 'deshabilitar_homologacion':
                
                $cadena_sql=" UPDATE ACTABLAHOMOLOGACION SET HOM_ESTADO='".$variable['estado']."'" ;
                $cadena_sql.=" WHERE ";
                $cadena_sql.="hom_asi_cod_ppal=".$variable['principal'];
                $cadena_sql.=" AND hom_asi_cod_hom=".$variable['homologo'];
                $cadena_sql.=" AND hom_tipo_hom =0 ";
                break;

            case 'buscarRegistroBifurcacion':

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="hom_cra_cod_ppal  AS COD_CRA_PPAL, ";
                            $cadena_sql.="hom_asi_cod_ppal  AS COD_ASI_PPAL, ";
                            $cadena_sql.="hom_cra_cod_hom   AS COD_CRA_HOM, ";
                            $cadena_sql.="hom_asi_cod_hom   AS COD_ASI_HOM, ";
                            $cadena_sql.="TRIM(hom_estado)        AS ESTADO, ";
                            $cadena_sql.="hom_fecha_reg     AS FEC_REG, ";
                            $cadena_sql.="hom_tipo_hom      AS TIPO_HOMOLOGACION ";
                            $cadena_sql.="FROM  ACTABLAHOMOLOGACION  ";
                            $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable['cod_proyecto']." ";
                            $cadena_sql.="AND hom_asi_cod_ppal  IN (".$variable['cod_padre1'].",".$variable['cod_padre2'].") ";
                            if ($variable['cod_proyecto_hom']){
                                $cadena_sql.="AND hom_cra_cod_hom =".$variable['cod_proyecto_hom']." ";
                            }
                            $cadena_sql.="AND hom_asi_cod_hom =".$variable['cod_hijo']." ";
                            $cadena_sql.="AND hom_tipo_hom =2 ";
                            $cadena_sql.="ORDER BY hom_fecha_reg";
                            
                break;


           case 'buscarParejaBifurcacion':

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="hom_cra_cod_ppal  AS COD_CRA_PPAL, ";
                            $cadena_sql.="hom_asi_cod_ppal  AS COD_ASI_PPAL, ";
                            $cadena_sql.="hom_cra_cod_hom   AS COD_CRA_HOM, ";
                            $cadena_sql.="hom_asi_cod_hom   AS COD_ASI_HOM, ";
                            $cadena_sql.="TRIM(hom_estado)        AS ESTADO, ";
                            $cadena_sql.="hom_fecha_reg     AS FEC_REG, ";
                            $cadena_sql.="hom_tipo_hom      AS TIPO_HOMOLOGACION ";
                            $cadena_sql.="FROM  ACTABLAHOMOLOGACION  ";
                            $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable['cod_proyecto']." ";
                            $cadena_sql.="AND hom_asi_cod_ppal =".$variable['cod_padre']." ";
                            if ($variable['cod_proyecto_hom']){
                                $cadena_sql.="AND hom_cra_cod_hom =".$variable['cod_proyecto_hom']." ";
                            }
                            $cadena_sql.="AND hom_asi_cod_hom =".$variable['cod_hijo']." ";
                            $cadena_sql.="AND hom_tipo_hom =2 ";
                            $cadena_sql.="AND TRIM(hom_estado) ='A' ";
                            
                break;

                case 'buscarParejaUnion':

                            $cadena_sql="SELECT DISTINCT ";
                            $cadena_sql.="hom_cra_cod_ppal  AS COD_CRA_PPAL, ";
                            $cadena_sql.="hom_asi_cod_ppal  AS COD_ASI_PPAL, ";
                            $cadena_sql.="hom_cra_cod_hom   AS COD_CRA_HOM, ";
                            $cadena_sql.="hom_asi_cod_hom   AS COD_ASI_HOM, ";
                            $cadena_sql.="TRIM(hom_estado)        AS ESTADO, ";
                            $cadena_sql.="hom_fecha_reg     AS FEC_REG, ";
                            $cadena_sql.="hom_tipo_hom      AS TIPO_HOMOLOGACION, ";
                            $cadena_sql.="hom_id      AS HOM_ID ";
                            $cadena_sql.="FROM  ACTABLAHOMOLOGACION  ";
                            $cadena_sql.="WHERE hom_cra_cod_ppal =".$variable['cod_proyecto']." ";
                            $cadena_sql.="AND hom_asi_cod_ppal =".$variable['cod_padre']." ";
                            if ($variable['cod_proyecto_hom']){
                                $cadena_sql.="AND hom_cra_cod_hom =".$variable['cod_proyecto_hom']." ";
                            }
                            $cadena_sql.="AND hom_asi_cod_hom =".$variable['cod_hijo']." ";
                            $cadena_sql.="AND hom_tipo_hom =1 ";
                            $cadena_sql.="AND TRIM(hom_estado) ='A' ";
                            
                break;
                
            case 'deshabilitar_homologacionUnion':
                
                $cadena_sql=" UPDATE ACTABLAHOMOLOGACION SET HOM_ESTADO='".$variable['estado']."'" ;
                $cadena_sql.=" WHERE ";
                $cadena_sql.="hom_asi_cod_ppal=".$variable['principal']; 
                $cadena_sql.=" AND hom_fecha_reg= '".$variable['fec_reg']."'"; 
                $cadena_sql.=" AND hom_cra_cod_ppal=".$variable['cod_proyecto']; 
                $cadena_sql.=" AND hom_tipo_hom =1 "; 
                break;
            
            case 'deshabilitar_homologacionBifurcacion':
                
                $cadena_sql=" UPDATE ACTABLAHOMOLOGACION SET HOM_ESTADO='".$variable['estado']."'" ;
                $cadena_sql.=" WHERE ";
                $cadena_sql.="hom_asi_cod_hom=".$variable['homologo']; 
                $cadena_sql.=" AND hom_fecha_reg='".$variable['fec_reg']."'"; 
                $cadena_sql.=" AND hom_cra_cod_ppal=".$variable['cod_proyecto']; 
                $cadena_sql.=" AND hom_tipo_hom =2 ";
                break;
            
            case 'codigoHomologado':
                
                $cadena_sql=" SELECT Hom_Asi_Cod_Hom, " ;
                $cadena_sql.=" Hom_Asi_Cod_Ppal," ;
                $cadena_sql.=" Hom_Estado" ;
                $cadena_sql.=" FROM Actablahomologacion ";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" Hom_Asi_Cod_hom= ".$variable['hijo']; 
                $cadena_sql.=" AND hom_tipo_hom=".$variable['tipo_hom']; 
                break;
                
            case 'actualizar_tabla_porcentajes':
                $cadena_sql=" UPDATE ACHOMOLOGACION_PORCENTAJES ";
                $cadena_sql.=" SET";
                $cadena_sql.=" HPO_PORCENTAJE= '".$variable['porc_hijo']."',";
                $cadena_sql.=" HPO_REQUIERE_APROBAR='".$variable['req_hijo']."'";
                $cadena_sql.=" WHERE HPO_ID_HOM='".$variable['identificador']."'";
                $cadena_sql.=" AND HPO_FECHA_REG='".$variable['time']."'";
                break;

                
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>