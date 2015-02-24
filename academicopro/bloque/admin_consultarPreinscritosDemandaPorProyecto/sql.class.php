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
class sql_adminConsultarPreinscritosDemandaPorProyecto extends sql
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

            //ORACLE proyecto curriculares asociados al usuario
            case 'buscarProyectosAsociados':

                $cadena_sql = "SELECT DISTINCT cra_cod PROYECTO,";
                $cadena_sql.=" cra_nombre NOMBRE,";
                $cadena_sql.=" max(ctp_pen_nro) PLAN,";
                $cadena_sql.=" tra_nivel NIVEL";
                $cadena_sql.=" FROM ACCRA";
                $cadena_sql.=" INNER JOIN V_CRA_TIP_PEN ON CTP_CRA_COD=CRA_COD";
                $cadena_sql.=" INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD";
                $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=".$variable['usuario'];
                //$cadena_sql.=" AND CTP_IND_CRED LIKE '%S%'";
                $cadena_sql.=" AND TRA_COD_NIVEL IN (1)";//para pregrado es 1. Se cambia tambien en Validaciones, proyectos del coordinador
                //$cadena_sql.=" OR TRA_COD_NIVEL IS NULL)";
                $cadena_sql.=" group by cra_cod, cra_nombre, tra_nivel";
                $cadena_sql.=" ORDER BY 1, 3";

            break;             
             
        
            //ORACLE
            case 'buscarNombreUsuario':
                $cadena_sql="SELECT usu_nombre NOMBRES, ";
                $cadena_sql.=" usu_apellido APELLIDOS";
                $cadena_sql.=" FROM geusuario";
                $cadena_sql.=" WHERE usu_nro_iden=".$variable['usuario'];
            break;        
             
        
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
              case 'buscarNivelesPlan':

                $cadena_sql="SELECT";
                $cadena_sql.=" DISTINCT(pen_sem) NIVEL";
                $cadena_sql.=" FROM acpen";                
                $cadena_sql.=" INNER JOIN acasi On pen_asi_cod=asi_cod";
                $cadena_sql.=" WHERE pen_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND asi_ind_cred='".$variable['modalidad']."'";
                $cadena_sql.=" AND asi_estado='A'";
                $cadena_sql.=" AND pen_estado='A'";
                $cadena_sql.=" ORDER BY pen_sem";    
                break;

            
           //Oracle
              case 'buscarEspaciosNivel':

                $cadena_sql="SELECT";
                $cadena_sql.=" DISTINCT(pen_asi_cod) CODIGO,";
                //$cadena_sql.=" pen_asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE";
                //$cadena_sql.=" pen_cre CREDITOS";
                //$cadena_sql.=" pen_nro_ht HTD,";
                //$cadena_sql.=" pen_nro_hp HTC,";
                //$cadena_sql.=" pen_nro_aut HTA,";
                //$cadena_sql.=" pen_sem NIVEL";
                $cadena_sql.=" FROM acpen";                
                $cadena_sql.=" INNER JOIN acasi On pen_asi_cod=asi_cod";
                $cadena_sql.=" WHERE pen_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND asi_ind_cred='".$variable['modalidad']."'";
                $cadena_sql.=" AND asi_estado='A'";
                $cadena_sql.=" AND pen_estado='A'";
                $cadena_sql.=" AND pen_sem=".$variable['nivel'];
                $cadena_sql.=" ORDER BY pen_asi_cod";
                //echo $cadena_sql;exit;
              
                break;
            
            //ORACLE
            case "contarPreinscritosEspacioProyecto":
              $cadena_sql="SELECT count(*)";
              $cadena_sql.=" FROM acinsdemanda";
              $cadena_sql.=" WHERE insde_ano=".$variable['ano'];
              $cadena_sql.=" AND insde_per=".$variable['periodo'];
              $cadena_sql.=" AND insde_asi_cod=".$variable['codEspacio'];
              $cadena_sql.=" AND insde_cra_cod=".$variable['codProyecto'];
              $cadena_sql.=" AND insde_estado LIKE '%A%'";              
              break;
                 
            //ORACLE
            case "buscarCodigoFacultad":
              $cadena_sql=" SELECT cra_dep_cod";            
              $cadena_sql.=" FROM accra";                      
              $cadena_sql.=" WHERE cra_cod=".$variable['codProyecto'];
              break;
                
            //ORACLE
            case "contarPreinscritosEspacioFacultad":
              $cadena_sql=" SELECT count(*) ";            
              $cadena_sql.=" FROM acinsdemanda";            
              $cadena_sql.=" INNER JOIN accra on insde_cra_cod= cra_cod";            
              $cadena_sql.=" WHERE insde_ano=".$variable['ano'];
              $cadena_sql.=" AND insde_per=".$variable['periodo'];
              $cadena_sql.=" AND insde_asi_cod=".$variable['codEspacio'];              
              $cadena_sql.=" AND cra_dep_cod=".$variable['codFacultad'];
              $cadena_sql.=" AND insde_estado LIKE '%A%'";
              break;
                


            //oracle
            case "buscarDatosEspacio":                
                $cadena_sql="SELECT asi_cod CODIGO,";               
                $cadena_sql.=" asi_nombre NOMBRE,";               
                $cadena_sql.=" asi_ind_cred MODALIDAD";               
                $cadena_sql.=" FROM acasi";               
                $cadena_sql.=" WHERE asi_cod=".$variable['codEspacio'];
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
