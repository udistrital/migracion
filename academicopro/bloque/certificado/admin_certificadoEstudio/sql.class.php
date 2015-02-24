<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/06/2013
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminCertificadoEstudio extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    public $configuracion;

    function __construct($configuracion){
      $this->configuracion=$configuracion;
    }
    
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {
            
            case "datos_estudiante":
                $cadena_sql=" SELECT";
                $cadena_sql.=" est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod CARRERA,";
                $cadena_sql.=" cra_nombre PROYECTO,";
                $cadena_sql.=" est_pen_nro PENSUM,";
                $cadena_sql.=" est_nro_iden DOCUMENTO,";
                $cadena_sql.=" TRIM(est_ind_cred) IND_CRED,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" estado_activo ESTADO_ACTIVO";
                $cadena_sql.=" FROM acest";
                $cadena_sql.=" INNER JOIN acestado ON est_estado_est=estado_cod";
                $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
                $cadena_sql.=" WHERE est_cod =".$variable." ";
                
                break;
           
            case 'proyectos_curriculares':

                $cadena_sql="select distinct cra_cod, cra_nombre ";
                $cadena_sql.="from accra ";
                $cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                $cadena_sql.="where  ";
                $cadena_sql.="  USUCRA_NRO_IDEN=".$variable;
                $cadena_sql.=" order by 2";
                break;
            
             case "espacios_inscritos":
                $cadena_sql=" SELECT ";
                $cadena_sql.=" ins_est_cod  COD_ESTUDIANTE,";
                $cadena_sql.=" ins_ano      ANIO, ";
                $cadena_sql.=" ins_per      PERIODO,";
                $cadena_sql.=" ins_asi_cod  COD_ESPACIO,";
                $cadena_sql.=" ins_nro_ht   HT,";
                $cadena_sql.=" ins_nro_hp   HP,";
                $cadena_sql.=" ins_nro_aut  HAUT";
                $cadena_sql.=" FROM acins";
                $cadena_sql.=" INNER JOIN acasperi ON ape_ano=ins_ano AND ape_per=ins_per";
                $cadena_sql.=" INNER JOIN acest ON ins_est_cod=est_cod";
                $cadena_sql.=" WHERE ";
                $cadena_sql.=" ape_estado = 'A'";
                $cadena_sql.=" AND est_cod = ".$variable." ";
                break;
            
            case "cantidad_inscritas_xperiodo":
                $cadena_sql=" SELECT INS_EST_COD,";
                $cadena_sql.=" INS_ANO,";
                $cadena_sql.=" INS_PER,";
                $cadena_sql.=" PEN_SEM,";
                $cadena_sql.=" COUNT (*) ASIGNATURAS";
                $cadena_sql.=" FROM ACASPERI, ACPEN, ACINS, ACEST";
                $cadena_sql.=" WHERE APE_ESTADO = 'A'";
                $cadena_sql.=" and PEN_ASI_COD = INS_ASI_COD";
                $cadena_sql.=" AND PEN_CRA_COD = EST_CRA_COD";
                $cadena_sql.=" AND PEN_NRO = EST_PEN_NRO";
                $cadena_sql.=" AND INS_EST_COD = EST_COD";
                $cadena_sql.=" AND PEN_SEM NOT IN (0)";
                $cadena_sql.=" AND APE_ANO = INS_ANO";
                $cadena_sql.=" AND APE_PER = INS_PER";
                $cadena_sql.=" AND INS_EST_COD = ".$variable." ";
                $cadena_sql.=" GROUP BY INS_EST_COD,";
                $cadena_sql.=" INS_ANO,";
                $cadena_sql.=" INS_PER,";
                $cadena_sql.=" PEN_SEM ";
                $cadena_sql.=" union";
                $cadena_sql.=" SELECT INS_EST_COD,";
                $cadena_sql.=" INS_ANO,";
                $cadena_sql.=" INS_PER,";
                $cadena_sql.=" PEN_SEM,";
                $cadena_sql.=" COUNT (*) ASIGNATURAS";
                $cadena_sql.=" FROM ACASPERI, ACEST, ACINS, ACTABLAHOMOLOGACION, ACPEN";
                $cadena_sql.=" WHERE APE_ESTADO = 'A'";
                $cadena_sql.=" and EST_COD = ".$variable." ";
                $cadena_sql.=" and APE_ANO = INS_ANO";
                $cadena_sql.=" and APE_PER = INS_PER";
                $cadena_sql.=" and EST_COD = INS_EST_COD";
                $cadena_sql.=" and INS_ESTADO = 'A'";
                $cadena_sql.=" and INS_CRA_COD = HOM_CRA_COD_PPAL";
                $cadena_sql.=" and INS_ASI_COD = HOM_ASI_COD_HOM";
                $cadena_sql.=" and HOM_CRA_COD_PPAL = PEN_CRA_COD";
                $cadena_sql.=" and HOM_ASI_COD_PPAL = PEN_ASI_COD";
                $cadena_sql.=" and EST_PEN_NRO = PEN_NRO";
                $cadena_sql.=" and PEN_SEM not in (0)";
                $cadena_sql.=" and INS_EST_COD not in (select PEN_ASI_COD";
                $cadena_sql.=" from ACPEN";
                $cadena_sql.=" where ACEST.EST_CRA_COD = PEN_CRA_COD";
                $cadena_sql.=" and ACEST.EST_PEN_NRO = PEN_NRO";
                $cadena_sql.=" and PEN_ESTADO = 'A') ";
                $cadena_sql.=" GROUP BY INS_EST_COD,";
                $cadena_sql.=" INS_ANO,";
                $cadena_sql.=" INS_PER,";
                $cadena_sql.=" PEN_SEM";
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
            
         
          default :
              $cadena_sql='';
              break;
        }
        return $cadena_sql;

    }
}
?>