<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_consejeriasDocente extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias

  public $configuracion;


  function __construct($configuracion){

    $this->configuracion=$configuracion;

  }
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            //ORACLE
            case 'buscarProyectosConsejeriaDocente':
                $cadena_sql="SELECT";
                $cadena_sql.=" dco_cra_cod COD_PROYECTO,";
                $cadena_sql.=" cra_nombre NOMBRE_PROYECTO";
                $cadena_sql.=" FROM acdocenteconsejero";
                $cadena_sql.=" INNER JOIN accra on dco_cra_cod= cra_cod";
                $cadena_sql.=" WHERE dco_doc_nro_ident=".$variable['codDocente'];

                break;
                            
            //ORACLE
            case 'datos_docente':
                $cadena_sql="SELECT ";
                $cadena_sql.="doc_nro_iden DOCUMENTO, ";
                $cadena_sql.="doc_apellido APELLIDO, ";
                $cadena_sql.="doc_nombre NOMBRE, ";
                $cadena_sql.="doc_email CORREO ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acdocente ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="doc_nro_iden=".$variable;

                break;

            case 'buscarEstudiantesAsociados':

                $cadena_sql="SELECT eco_est_cod CODIGO, ";                
                $cadena_sql.=" est_nombre NOMBRE, ";
                $cadena_sql.=" est_estado_est ESTADO, ";
                $cadena_sql.=" estado_descripcion ESTADO_DESCRIPCION, ";
                $cadena_sql.=" est_ind_cred MODALIDAD, ";//creditos/horas
                $cadena_sql.=" fa_promedio_nota(eco_est_cod) PROMEDIO ";//creditos/horas
                $cadena_sql.=" FROM acestudianteconsejero ";
                $cadena_sql.=" INNER JOIN acest ON est_cod= eco_est_cod ";
                $cadena_sql.=" INNER JOIN acestado ON estado_cod= est_estado_est ";
                $cadena_sql.=" WHERE eco_doc_nro_ident='".$variable['codDocente']."' " ;
                $cadena_sql.=" AND eco_estado='A'" ;
                $cadena_sql.=" AND eco_cra_cod='".$variable['codProyecto']."' " ;
                $cadena_sql.=" ORDER BY eco_est_cod ASC" ;
                //echo $cadena_sql;
                break;
        
            case 'periodoActual':

                $cadena_sql="SELECT ape_ano ANO,";
                $cadena_sql.=" ape_per PERIODO";
                $cadena_sql.=" FROM acasperi";
                $cadena_sql.=" WHERE ape_estado LIKE '%A%'";
                break;
                      
        
///////////////////////////////////////////////////////////////////////////            



//            case 'consultarDatosEstudiante':
//
//                $cadena_sql="SELECT est_cod CODIGO_ESTUDIANTE, ";
//                $cadena_sql.="est_nombre NOMBRE_ESTUDIANTE, ";
//                $cadena_sql.="estado_cod ESTADO_ESTUDIANTE, ";
//                $cadena_sql.="estado_descripcion DESC_ESTADO_ESTUDIANTE, ";
//                $cadena_sql.="est_cra_cod CRA_ESTUDIANTE, ";
//                $cadena_sql.="cra_nombre CRA_NOMBRE, ";
//                $cadena_sql.="EOT_EMAIL EMAIL, ";
//                $cadena_sql.="est_telefono TELEFONO";
//                $cadena_sql.=" FROM acest ";
//                $cadena_sql.=" inner join acestado on est_estado_est= estado_cod ";
//                $cadena_sql.=" inner join accra on est_cra_cod= cra_cod";
//                $cadena_sql.=" inner join acestotr on EOT_COD= EST_COD";
//                $cadena_sql.=" WHERE est_estado like '%A%' ";
//                //$cadena_sql.=" and estado_activo like '%S%' ";
//                $cadena_sql.=" AND est_cod = '".$variable."' ";
//                //$cadena_sql.=" and est_ind_cred like '%S%' ";
//                $cadena_sql.=" ORDER BY 1 ";
//                break;
            
//            case 'datosTotalizados':
//
//                $cadena_sql="SELECT mat_est_cod,";
//                $cadena_sql.="mat_annio, ";
//                $cadena_sql.="mat_periodo, ";
//                $cadena_sql.="mat_estado_est, ";
//                $cadena_sql.="mat_cod_motivo, ";
//                $cadena_sql.="mat_motivo motivo_prueba, ";
//                $cadena_sql.="mat_nro_semestres, ";
//                $cadena_sql.="mat_prom_acumulado promedioacumulado, ";
//                $cadena_sql.="mat_prom_ponderado, ";
//                $cadena_sql.="mat_nro_materias_perdidas, ";
//                $cadena_sql.="mat_total_perdidas, ";
//                $cadena_sql.="mat_veces_prueba VECES_PRUEBA_ESTUDIANTE";
//                $cadena_sql.=" FROM sga_temp_matriculados ";
//                $cadena_sql.=" WHERE mat_est_cod = '".$variable[0]."' ";
//                $cadena_sql.=" and mat_annio = '".$variable[1]."'";
//                $cadena_sql.=" and mat_periodo = '".$variable[2]."'";
//                $cadena_sql.=" ORDER BY 1 ";
//                echo $cadena_sql;
//                exit;
//                break;



        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
