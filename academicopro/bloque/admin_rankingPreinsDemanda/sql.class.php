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
class sql_adminRankingPreinsDemanda extends sql
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
          

            //oracle
            case "buscarProyectos":                
                    $cadena_sql=" SELECT cra_cod CODIGO,";
                    $cadena_sql.=" dep_cod COD_FACULTAD";
                    $cadena_sql.=" FROM accra";
                    $cadena_sql.=" INNER JOIN actipcra";
                    $cadena_sql.=" ON tra_cod=cra_tip_cra";
                    $cadena_sql.=" INNER JOIN gedep";
                    $cadena_sql.=" ON dep_cod =cra_dep_cod";
                    $cadena_sql.=" WHERE cra_estado='A'";
                    //$cadena_sql.=" and cra_dep_cod=".$variable['codFacultad'];
                    $cadena_sql.=" AND tra_nivel ='PREGRADO'";
                    $cadena_sql.=" ORDER BY dep_cod,cra_cod";
                   
                
            break;                           
        
            //oracle
            case "consultarPreinscripciones":                
      
                $cadena_sql=" SELECT insde_ano ANO,";
                $cadena_sql.=" insde_per PERIODO,";
                $cadena_sql.=" insde_cra_cod COD_PROYECTO,";
                $cadena_sql.=" cra_nombre NOMBRE_PROYECTO,";
                $cadena_sql.=" insde_asi_cod COD_ESPACIO,";
                $cadena_sql.=" asi_nombre NOMBRE_ESPACIO,";
                $cadena_sql.=" dep_cod COD_FACULTAD,";
                $cadena_sql.=" dep_nombre NOMBRE_FACULTAD";
                $cadena_sql.=" FROM acinsdemanda";
                $cadena_sql.=" inner join acasi on asi_cod=insde_asi_cod";
                $cadena_sql.=" inner join accra on cra_cod=insde_cra_cod";
                $cadena_sql.=" inner join gedep on dep_cod=cra_dep_cod";
                $cadena_sql.=" WHERE insde_ano =".$variable['ano'];
                $cadena_sql.=" AND insde_per =".$variable['periodo'];
                $cadena_sql.=" AND insde_cra_cod=".$variable['codProyecto'];
                         
                
            break;          
        
            //mysql
            case "insertarRankingPreinsdemanda":                
                
                $cadena_sql=" INSERT";
                $cadena_sql.=" INTO sga_rankingPreinsDemanda";
                $cadena_sql.=" (";
                $cadena_sql.=" rank_codFacultad,";
                $cadena_sql.=" rank_nombreFacultad,";
                $cadena_sql.=" rank_codProyecto,";
                $cadena_sql.=" rank_nombreProyecto,";
                $cadena_sql.=" rank_codEspacio,";
                $cadena_sql.=" rank_nombreEspacio,";
                $cadena_sql.=" rank_numeroPreinscritos,";
                $cadena_sql.=" rank_posicion";
                $cadena_sql.=" )";
                $cadena_sql.=" VALUES";
                $cadena_sql.=" (";
                $cadena_sql.=" '".$variable['codFacultad']."',";
                $cadena_sql.=" '".$variable['nombreFacultad']."',";
                $cadena_sql.=" '".$variable['codProyecto']."',";
                $cadena_sql.=" '".$variable['nombreProyecto']."',";
                $cadena_sql.=" '".$variable['codEspacio']."',";
                $cadena_sql.=" '".$variable['nombreEspacio']."',";
                $cadena_sql.=" '".$variable['numeroPreinscritos']."',";
                $cadena_sql.=" '".$variable['posicion']."'"; 
                $cadena_sql.=" )";      //echo $cadena_sql;exit;                              
                
            break;   
        
            //mysql
            case "consultarTablaRanking":                
                
                $cadena_sql=" SELECT";
                $cadena_sql.=" rank_codFacultad,";
                $cadena_sql.=" rank_nombreFacultad,";
                $cadena_sql.=" rank_codProyecto,";
                $cadena_sql.=" rank_nombreProyecto,";
                $cadena_sql.=" rank_codEspacio,";
                $cadena_sql.=" rank_nombreEspacio,";
                $cadena_sql.=" rank_numeroPreinscritos,";
                $cadena_sql.=" rank_posicion";                
                $cadena_sql.=" FROM sga_rankingPreinsDemanda";
 ;
                
            break;   
        
            //mysql
            case "vaciarTablaRanking":                
                
                $cadena_sql=" TRUNCATE TABLE sga_rankingPreinsDemanda";
            break; 
        
            case "vaciarTablaClasificacionEstudiantes":                
                
                $cadena_sql=" TRUNCATE TABLE sga_clasificacion_estudiantes";
            break; 
        
            case "vaciarTablaHorariosBinarios":                
                
                $cadena_sql=" TRUNCATE TABLE sga_horario_binario";
            break;          
        

	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
