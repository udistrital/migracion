<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarPreinscritosDemandaPorEspacio extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    
  public $configuracion;

  function __construct($configuracion){
    $this->configuracion=$configuracion;
  }
    
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {
            
              //Oracle
              case 'periodo_activo':

                $cadena_sql="SELECT";
                $cadena_sql.=" ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acasperipreinsdemanda";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";

                break;            

            
              //Oracle
              case 'buscarEspacios':

                $cadena_sql="SELECT";
                $cadena_sql.=" ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" ACASPERI ";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" ape_estado LIKE '%A%'";

                break;            

            //oracle
            case "buscarDatosEspacio":                
                $cadena_sql="SELECT asi_cod CODIGO,";               
                $cadena_sql.=" asi_nombre NOMBRE";               
                $cadena_sql.=" FROM acasi";               
                $cadena_sql.=" WHERE asi_cod=".$variable['codEspacio'];
                break;            
                        
            //oracle
            case "buscarFacultad":                
                $cadena_sql="SELECT DISTINCT(cra_dep_cod) CODIGO,";               
                $cadena_sql.=" dep_nombre NOMBRE";               
                $cadena_sql.=" FROM acpen";               
                $cadena_sql.=" INNER JOIN accra ON pen_cra_cod= cra_cod";               
                $cadena_sql.=" INNER JOIN gedep ON cra_dep_cod=dep_cod";               
                $cadena_sql.=" WHERE pen_asi_cod = ".$variable['codEspacio'];
                break;                        

            //oracle
            case "buscarProyectosFacultad":                
                $cadena_sql="SELECT ";               
                $cadena_sql.=" DISTINCT(cra_cod) CODIGO,";               
                $cadena_sql.=" cra_nombre NOMBRE";               
                $cadena_sql.=" FROM acpen";               
                $cadena_sql.=" INNER JOIN accra ON pen_cra_cod= cra_cod";               
                $cadena_sql.=" INNER JOIN gedep ON cra_dep_cod=dep_cod";               
                $cadena_sql.=" AND cra_dep_cod = ".$variable['codFacultad'];
                $cadena_sql.=" WHERE pen_asi_cod = ".$variable['codEspacio'];
                break;           
            
              
            case "buscarNumeroEstudiantesPreinscritos":
              $cadena_sql="SELECT count(*)";
              $cadena_sql.=" FROM acinsdemanda";
              $cadena_sql.=" WHERE insde_ano=".$variable['ano'];
              $cadena_sql.=" AND insde_per=".$variable['periodo'];
              $cadena_sql.=" AND insde_asi_cod=".$variable['codEspacio'];
              $cadena_sql.=" AND insde_cra_cod=".$variable['codProyecto'];
              $cadena_sql.=" AND insde_estado LIKE '%A%'";              
              break;
          
          
            case "buscarEstudiantesPreinscritosProyecto":
              $cadena_sql="SELECT insde_est_cod CODIGO,";
              $cadena_sql.=" est_nombre NOMBRE,";
              $cadena_sql.=" est_estado_est ESTADO";
              $cadena_sql.=" FROM acinsdemanda";
              $cadena_sql.=" INNER JOIN acest ON insde_est_cod= est_cod";
              $cadena_sql.=" WHERE insde_ano=".$variable['ano'];
              $cadena_sql.=" AND insde_per=".$variable['periodo'];
              $cadena_sql.=" AND insde_asi_cod=".$variable['codEspacio'];
              $cadena_sql.=" AND insde_cra_cod=".$variable['codProyecto'];
              $cadena_sql.=" AND insde_estado LIKE '%A%'";  
              break;
                
          
          

        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
