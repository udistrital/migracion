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
class sql_mensajesEstudiante extends sql
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
              case "buscarMensajesRecibidos":
                 
                    $cadena_sql="SELECT menrecept_cod_mensaje CODIGO,";
                    $cadena_sql.=" men_asunto ASUNTO,";
                    $cadena_sql.=" men_contenido CONTENIDO,";
                    $cadena_sql.=" menrecept_estado ESTADO_MENSAJE,";
                    $cadena_sql.=" men_tip_emisor TIPO_EMISOR,";
                    $cadena_sql.=" men_cod_emisor CODIGO_EMISOR,";
                    $cadena_sql.=" to_char(men_fecha, 'DD/month/YYYY HH24:MI','nls_date_language=spanish') FECHA";
                    $cadena_sql.=" FROM acmenreceptor";
                    $cadena_sql.=" INNER JOIN acmensaje on menrecept_cod_mensaje=men_codigo";
                    $cadena_sql.=" WHERE menrecept_cod_receptor=".$variable['codUsuario'];
                    $cadena_sql.=" ORDER BY men_fecha DESC, menrecept_cod_mensaje DESC";

                break;
                //Oracle
              case "buscarMensajesEnviados":

                    $cadena_sql="SELECT men_codigo CODIGO,";
                    $cadena_sql.=" men_asunto ASUNTO,";
                    $cadena_sql.=" men_contenido CONTENIDO,";
                    $cadena_sql.=" menrecept_tip_receptor TIPO_RECEPTOR,";
                    $cadena_sql.=" menrecept_cod_receptor CODIGO_RECEPTOR,";
                    $cadena_sql.=" to_char(men_fecha, 'DD/month/YYYY HH24:MI','nls_date_language=spanish') FECHA";
                    $cadena_sql.=" FROM acmenreceptor";
                    $cadena_sql.=" INNER JOIN acmensaje on menrecept_cod_mensaje=men_codigo";
                    $cadena_sql.=" WHERE men_cod_emisor=".$variable['codUsuario'];
                    $cadena_sql.=" ORDER BY men_fecha desc, menrecept_cod_receptor";

                    //echo $cadena_sql;exit;

                break;
                         
              //Oracle
              case "buscarNombreEstudiante":

                    $cadena_sql="SELECT est_nombre NOMBRE";
                    $cadena_sql.=" FROM acest";
                    $cadena_sql.=" WHERE est_cod=".$variable['codigo'];
                    //echo $cadena_sql;exit;

                break;

              //Oracle
              case "buscarNombreDocente":

                    $cadena_sql="SELECT doc_apellido APELLIDO,";
                    $cadena_sql.=" doc_nombre NOMBRE";
                    $cadena_sql.=" FROM acdocente";
                    $cadena_sql.=" WHERE doc_nro_iden=".$variable['documento'];

                break;
            
              //Oracle
              case 'buscarDocenteConsejero':

                $cadena_sql="SELECT";
                $cadena_sql.=" eco_doc_nro_ident CODIGO,";
                $cadena_sql.=" doc_apellido APELLIDO,";
                $cadena_sql.=" doc_nombre NOMBRE,";
                $cadena_sql.=" 30 TIPO ";
                $cadena_sql.=" FROM acestudianteconsejero";
                $cadena_sql.=" INNER JOIN acdocente ON eco_doc_nro_ident=doc_nro_iden";
                $cadena_sql.=" WHERE eco_est_cod=".$variable['codEstudiante'];
                $cadena_sql.=" AND eco_estado LIKE '%A%'";
  
                
                break;             

	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>