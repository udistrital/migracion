<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminConsultarReporteGruposAsisVice extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    
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
                $cadena_sql.=" ACASPERI ";
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
                $cadena_sql.=" FROM accursos";               
                $cadena_sql.=" INNER JOIN accra ON cur_cra_cod=cra_cod";               
                $cadena_sql.=" INNER JOIN gedep ON cra_dep_cod= dep_cod";               
                $cadena_sql.=" WHERE cur_ape_ano = ".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per = ".$variable['periodo'];
                $cadena_sql.=" AND cur_asi_cod = ".$variable['codEspacio'];
                break;                        
            
            //oracle
            case "buscarGruposEspacio":                
                $cadena_sql="SELECT";
                //$cadena_sql.=" cra_dep_cod FACULTAD,";
                //$cadena_sql.=" dep_nombre NOMBRE_FACULTAD,";
                $cadena_sql.=" cur_cra_cod COD_PROYECTO,";
                $cadena_sql.=" cra_nombre NOMBRE_PROYECTO,";
                //$cadena_sql.=" cur_asi_cod COD_ESPACIO,";
                //$cadena_sql.=" asi_nombre NOMBRE_ESPACIO,";
                $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO,";
                $cadena_sql.=" cur_nro_cupo CUPO,";
                $cadena_sql.=" cur_id ID_GRUPO,";
                $cadena_sql.=" (SELECT COUNT(*) FROM acins WHERE ins_asi_cod=cur_asi_cod AND ins_gr=cur_id AND ins_ano=cur_ape_ano AND ins_per=cur_ape_per) INSCRITOS";
                $cadena_sql.=" FROM accursos";
                $cadena_sql.=" INNER JOIN acasi ON cur_asi_cod=asi_cod";
                $cadena_sql.=" INNER JOIN accra ON cur_cra_cod=cra_cod";
                $cadena_sql.=" INNER JOIN gedep ON cra_dep_cod= dep_cod";
                $cadena_sql.=" WHERE cur_ape_ano = ".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per = ".$variable['periodo'];
                $cadena_sql.=" AND cur_asi_cod = ".$variable['codEspacio'];
                $cadena_sql.=" AND dep_cod = ".$variable['codFacultad'];                
                $cadena_sql.=" ORDER BY COD_PROYECTO,GRUPO";
                break;                                
            
            case "buscarDatosGrupo":
                $cadena_sql="SELECT";
                $cadena_sql.=" cur_asi_cod CODIGO,";
                $cadena_sql.=" cur_cra_cod CARRERA,";
                $cadena_sql.=" cra_nombre NOMBRE,";
                $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO,";
                $cadena_sql.=" cur_id ID_GRUPO,";
                $cadena_sql.=" cur_nro_cupo CUPO,";
                $cadena_sql.=" cur_nro_ins INSCRITOS";
                $cadena_sql.=" FROM accursos";
                $cadena_sql.=" INNER JOIN accra ON cur_cra_cod=cra_cod";
                $cadena_sql.=" WHERE cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" AND cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_id=".$variable['grupo'];
                $cadena_sql.=" AND cur_estado LIKE '%A%'";
                break;

              
            case "buscarNumeroEstudiantesInscritos":
                $cadena_sql="SELECT count(*)";
                $cadena_sql.=" FROM acins";
                $cadena_sql.=" WHERE ins_ano=".$variable['ano'];
                $cadena_sql.=" AND ins_per=".$variable['periodo'];
                $cadena_sql.=" AND ins_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND ins_gr=".$variable['grupo'];
                $cadena_sql.=" AND ins_estado LIKE '%A%'";
                break;

           case "consultarInscritosGrupo":
                $cadena_sql="SELECT ins_cra_cod CARRERA,";
                $cadena_sql.=" ins_est_cod COD_ESTUDIANTE,";
                $cadena_sql.=" est_nombre NOMBRE_ESTUDIANTE,";
                $cadena_sql.=" est_estado_est ESTADO_ESTUDIANTE,";
                $cadena_sql.=" (SELECT cra_nombre FROM accra WHERE cra_cod=ins_cra_cod) CARRERA_ESTUDIANTE";
                $cadena_sql.=" FROM acins";
                $cadena_sql.=" INNER JOIN acest on ins_est_cod=est_cod";
                $cadena_sql.=" INNER JOIN accursos ON ins_asi_cod=cur_asi_cod AND ins_gr= cur_id";
                $cadena_sql.=" AND ins_ano= cur_ape_ano AND ins_per= cur_ape_per";
                $cadena_sql.=" INNER JOIN gedep on cur_dep_cod=dep_cod";
                $cadena_sql.=" INNER JOIN accra ON cur_cra_cod=cra_cod";
                $cadena_sql.=" WHERE ins_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND ins_ano =".$variable['ano'];
                $cadena_sql.=" AND ins_per =".$variable['periodo'];
                $cadena_sql.=" AND ins_gr =".$variable['grupo'];
                $cadena_sql.=" AND cur_cra_cod =".$variable['codProyecto'];
                $cadena_sql.=" AND ins_estado ='A'";
                $cadena_sql.=" ORDER BY CUR_CRA_COD, INS_GR, INS_CRA_COD, INS_EST_COD";
                break;


            case "consultaDocenteGrupo":
                $cadena_sql=" SELECT distinct DOC_NOMBRE,";
                $cadena_sql.=" DOC_APELLIDO";
                $cadena_sql.=" FROM ACDOCENTE";
                $cadena_sql.=" INNER JOIN ACCARGAS ON DOC_NRO_IDEN=CAR_DOC_NRO";
                $cadena_sql.=" INNER JOIN achorarios ON hor_id=car_hor_id";
                $cadena_sql.=" INNER JOIN accursos ON cur_id=hor_id_curso";
                $cadena_sql.=" WHERE CUR_APE_ANO = ".$variable['ano'];
                $cadena_sql.=" AND CUR_APE_PER = ".$variable['periodo'];
                $cadena_sql.=" AND CUR_CRA_COD = ".$variable['codProyecto'];
                $cadena_sql.=" AND CUR_ASI_COD = ".$variable['codEspacio'];
                $cadena_sql.=" AND CUR_ID = ".$variable['grupo'];
                $cadena_sql.=" AND cur_estado='A'";
                $cadena_sql.=" AND hor_estado='A'";
                $cadena_sql.=" AND car_estado='A'";                
                
                
                break;


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
