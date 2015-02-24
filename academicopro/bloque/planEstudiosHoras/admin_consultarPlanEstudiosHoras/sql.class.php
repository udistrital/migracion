<?php

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

class sql_adminConsultarPlanesEstudiosHoras extends sql { //@ Método que crea las sentencias sql para el modulo admin_noticias

    function __construct($configuracion) {
        $this->configuracion=$configuracion;
    }
  function cadena_sql($tipo, $variable="") {

    switch ($tipo) {

        case 'datos_planes_estudio':
                $cadena_sql=" SELECT plan_cra_cod,";
                $cadena_sql.=" plan_pen_nro,";
                $cadena_sql.=" plan_nombre,";
                $cadena_sql.=" plan_creditos";
                $cadena_sql.=" FROM acplanestudio";
                $cadena_sql.=" INNER JOIN accra ON cra_cod=plan_cra_cod ";
                $cadena_sql.=" INNER JOIN actipcra ON tra_cod=cra_tip_cra ";
                $cadena_sql.=" WHERE plan_tipo=1";
                $cadena_sql.=" AND tra_cod_nivel=1";
                $cadena_sql.=" AND plan_estado='A'";
                if($variable['idProyecto'] && $variable['idPlanEstudios']){
                        $cadena_sql.=" AND plan_cra_cod=".$variable['idProyecto'];
                        $cadena_sql.=" AND plan_pen_nro=".$variable['idPlanEstudios'];
                }
                break;

        case 'plan_estudios':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" pen_cra_cod,";
                $cadena_sql.=" plan_nombre,";
                $cadena_sql.=" pen_nro,";
                $cadena_sql.=" pen_asi_cod,";
                $cadena_sql.=" asi_nombre,";
                $cadena_sql.=" pen_sem";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" INNER JOIN acasi ON pen_asi_cod=asi_cod";
                $cadena_sql.=" INNER JOIN acplanestudio ON plan_cra_cod=pen_cra_cod AND plan_pen_nro=pen_nro ";
               
                $cadena_sql.=" WHERE pen_cra_cod=".$variable['idProyecto'];
                $cadena_sql.=" AND pen_nro=".$variable['idPlanEstudios'];
                break;
            
        
        case 'parametros_plan':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" par_cra_cod,";
                $cadena_sql.=" par_pen_nro,";
                $cadena_sql.=" par_clasificacion,";
                $cadena_sql.=" par_numero,";
                $cadena_sql.=" cea_cod,";
                $cadena_sql.=" cea_nom,";
                $cadena_sql.=" cea_abr";
                $cadena_sql.=" FROM acparametrosplan";
                $cadena_sql.=" INNER JOIN geclasificaespac ON par_clasificacion=cea_cod";
                $cadena_sql.=" WHERE cea_tipoplan_cod=1";
                $cadena_sql.=" AND par_cra_cod=".$variable['idProyecto'];
                $cadena_sql.=" AND par_pen_nro=".$variable['idPlanEstudios'];
                break;
            
        case 'datos_proyecto_curricular':
                $cadena_sql=" SELECT ";
                $cadena_sql.=" cra_cod,";
                $cadena_sql.=" cra_nombre";
                 $cadena_sql.=" FROM accra";
                $cadena_sql.=" WHERE cra_cod=".$variable;
                break;

       
            default :
                $cadena_sql="";
                break;
    }#Cierre de switch

    return $cadena_sql;
  }

#Cierre de funcion cadena_sql
}

#Cierre de clase
?>