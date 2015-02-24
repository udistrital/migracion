<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroAdicionarDatosPlanEstudiosHoras extends sql {
  private $configuracion;
  function  __construct($configuracion)
  {
    $this->configuracion=$configuracion;
  }
    function cadena_sql($opcion,$variable="") {

        switch($opcion) {
            
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
        
            case 'adicionar_parametro':
                    $cadena_sql="INSERT INTO acparametrosplan ";
                    $cadena_sql.="(par_cra_cod,
                                par_pen_nro,
                                par_clasificacion,
                                par_numero) ";
                    $cadena_sql.="VALUES ('".$variable['idProyecto']."',";
                    $cadena_sql.="'".$variable['idPlanEstudios']."',";
                    $cadena_sql.="'".$variable['clasificacion']."',";
                    $cadena_sql.="'".$variable['numero']."')"; 
                
                break;
            
            case 'actualizar_parametro':
                    $cadena_sql=" UPDATE acparametrosplan ";
                    $cadena_sql.=" SET par_numero= '".$variable['numero']."'";
                    $cadena_sql.=" WHERE par_cra_cod= ".$variable['idProyecto'];
                    $cadena_sql.=" AND par_pen_nro= ".$variable['idPlanEstudios'];
                    $cadena_sql.=" AND par_clasificacion= ".$variable['clasificacion'];
                    break;
  
            case 'actualizar_totalEspacios':
                    $cadena_sql=" UPDATE acplanestudio ";
                    $cadena_sql.=" SET plan_creditos= '".$variable['totalEspacios']."'";
                    $cadena_sql.=" WHERE plan_cra_cod= ".$variable['idProyecto'];
                    $cadena_sql.=" AND plan_pen_nro= ".$variable['idPlanEstudios'];
                    
                    break;
  
        }
        //echo $cadena_sql;exit;
        return $cadena_sql;
    }


}
?>