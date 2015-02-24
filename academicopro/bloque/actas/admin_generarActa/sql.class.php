<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/03/2014
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminGenerarActa extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
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
                $cadena_sql.=" estado_descripcion ESTADO_DESCRIPCION,";
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
            
            
            case 'consultar_codigo_estudiante_por_id':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nro_iden= ".$variable;
                $cadena_sql.=" AND est_estado_est= 'E'";
                $cadena_sql.=" ORDER BY CODIGO desc";

                break;
           
            case 'consultar_codigo_estudiante_por_nombre':
                $cadena_sql=" SELECT est_cod CODIGO, ";
                $cadena_sql.=" est_cra_cod COD_PROYECTO ,";
                $cadena_sql.=" cra_nombre PROYECTO,";
                $cadena_sql.=" est_nombre NOMBRE";
                $cadena_sql.=" FROM acest ";
                $cadena_sql.=" INNER JOIN accra on cra_cod=est_cra_cod";
                $cadena_sql.=" WHERE est_nombre like ".$variable." ";
                $cadena_sql.=" AND est_estado_est= 'E'";
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