<?php
/**
* SQL nombre_sql
*
* Esta clase se encarga de crear las sentencias sql del bloque
*
* @package nombrePaquete
* @subpackage nombreSubpaquete
* @author Luis Fernando Torres
* @version 0.0.0.1
* Fecha: 26/02/2013
*/

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sql.class.php");

/**
* Descripción de la clase
*
* @package
* @subpackage
*/
class sql_admin_navegacion extends sql
{
  
    public $configuracion;
    
    /**
*
* @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
*/
    function __construct($configuracion){
    $this->configuracion=$configuracion;
    }
  
    /**
* Funcion que arma la cadena sql
*
* @param string $tipo Nombre de la cadena sql
* @param type $variable contiene pasan los parámetros que se pasan a la cadena sql
* @return string retorna la cadena sql
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

	      case 'datos_usuario':

		$cadena_sql=" SELECT est_cod CODIGO, ";
		$cadena_sql.=" est_nombre";
		$cadena_sql.=" est_cra_cod COD_PROYECTO";
		$cadena_sql.=" FROM acest";
		$cadena_sql.=" ORDER BY est_cod";
		
		break;


}

return $cadena_sql;
   }
   
}
?>
