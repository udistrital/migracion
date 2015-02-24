<?php
/**
 * SQL registronombre
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 08/09/2011
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
class sql_registroInscripcionesPrecarga extends sql
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
              case 'consultarPeriodo':

                $cadena_sql=" SELECT ape_ano ANO, ";
                $cadena_sql.=" ape_per PERIODO, ";
                $cadena_sql.=" ape_estado ESTADO ";
                $cadena_sql.=" FROM acasperi ";
                $cadena_sql.=" WHERE ape_estado= 'A'";                                              
                                                      
                break;
            
              //Oracle
              case 'consultarHorarios':

                $cadena_sql=" SELECT hor_ape_ano,";
                $cadena_sql.=" hor_ape_per,";
                $cadena_sql.=" hor_asi_cod,";
                $cadena_sql.=" hor_nro,";
                $cadena_sql.=" hor_dia_nro,";
                $cadena_sql.=" hor_hora,";
                $cadena_sql.=" hor_sed_cod,";
                $cadena_sql.=" hor_sal_cod,";
                $cadena_sql.=" hor_estado";
                $cadena_sql.=" FROM achorario";                                                
                $cadena_sql.=" WHERE  hor_ape_ano=".$variable['ANO'];                                               
                $cadena_sql.=" AND  hor_ape_per=".$variable['PERIODO'];
                        
                break;

              //Oracle
              case 'insertarHorario':

                $cadena_sql=" INSERT";
                $cadena_sql.=" INTO sga_achorario";
                $cadena_sql.=" (";
                $cadena_sql.=" hor_ape_ano,";
                $cadena_sql.=" hor_ape_per,";
                $cadena_sql.=" hor_asi_cod,";
                $cadena_sql.=" hor_nro,";
                $cadena_sql.=" hor_dia_nro,";
                $cadena_sql.=" hor_hora,";
                $cadena_sql.=" hor_sed_cod,";
                $cadena_sql.=" hor_sal_cod,";
                $cadena_sql.=" hor_estado";
                $cadena_sql.=" )";
                $cadena_sql.=" VALUES";
                $cadena_sql.=" (";
                $cadena_sql.= "'".$variable['HOR_APE_ANO']."',";
                $cadena_sql.= "'".$variable['HOR_APE_PER']."',";
                $cadena_sql.= "'".$variable['HOR_ASI_COD']."',";
                $cadena_sql.= "'".$variable['HOR_NRO']."',";
                $cadena_sql.= "'".$variable['HOR_DIA_NRO']."',";
                $cadena_sql.= "'".$variable['HOR_HORA']."',";
                $cadena_sql.= "'".$variable['HOR_SED_COD']."',";
                $cadena_sql.= "'".$variable['HOR_SAL_COD']."',";
                $cadena_sql.= "'".$variable['HOR_ESTADO']."'";
                $cadena_sql.=" )";              
                break;

                case 'consultarDatosCargados':
                $cadena_sql=" SELECT ins_fac_cod";
                $cadena_sql.=" FROM sga_carga_inscripciones";
                $cadena_sql.=" WHERE ins_fac_cod=".$variable;
                $cadena_sql.=" limit 1";
                break;
	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
