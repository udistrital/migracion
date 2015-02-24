<?php
/**
 * SQL adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 19/11/2010
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
class sql_adminMensajeContenidoDocente extends sql
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
            
              //Oracle              
              case 'buscar_nombreUsuario':

                $cadena_sql="SELECT";
                $cadena_sql.=" doc_apellido APELLIDO,";
                $cadena_sql.=" doc_nombre NOMBRE";
                $cadena_sql.=" FROM acdocente";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" doc_nro_iden=".$variable['codUsuario'];
                //echo $cadena_sql;exit;

                break;

              //Oracle              
              case 'actualizarEstadoMensaje':

                $cadena_sql="UPDATE acmenreceptor";
                $cadena_sql.=" SET menrecept_estado=2";
                $cadena_sql.=" WHERE menrecept_cod_mensaje=".$variable['codMensaje'];
                $cadena_sql.=" AND menrecept_cod_receptor=".$variable['codReceptor'];
                //echo $cadena_sql;exit;

                break;

            

	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>