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
class sql_mensajeEstudiante extends sql
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
              case 'buscarDocenteDestinatario':

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
            
              //Oracle
              case 'buscarCodigoMensaje':

                $cadena_sql="SELECT";
                $cadena_sql.=" mensajesequence.NEXTVAL ";
                $cadena_sql.=" FROM DUAL";

                break;
              
              //Oracle
              case 'insertarMensaje':

                $cadena_sql="INSERT";
                $cadena_sql.=" INTO acmensaje";
                $cadena_sql.=" (";
                $cadena_sql.=" men_codigo,";
                $cadena_sql.=" men_asunto,";
                $cadena_sql.=" men_contenido,";
                $cadena_sql.=" men_tip_emisor,";
                $cadena_sql.=" men_cod_emisor,";
                $cadena_sql.=" men_estado,";
                $cadena_sql.=" men_fecha";
                $cadena_sql.=" )";
                $cadena_sql.=" values";
                $cadena_sql.=" (";
                $cadena_sql.=$variable['codigoMensaje'].',';
                $cadena_sql.=" '".$variable['asunto']."',";
                $cadena_sql.=" '".$variable['contenido']."',";
                $cadena_sql.= $variable['tipoEmisor'].',';
                $cadena_sql.= $variable['codigoEmisor'].',';
                $cadena_sql.=" 'A',";
                $cadena_sql.=" SYSDATE";
                $cadena_sql.=" )";
                break;
              
              //Oracle
              case 'insertarMensajeReceptor':

                $cadena_sql="INSERT";
                $cadena_sql.=" INTO acmenreceptor";
                $cadena_sql.=" (";
                $cadena_sql.=" menrecept_cod_mensaje,";
                $cadena_sql.=" menrecept_tip_receptor,";
                $cadena_sql.=" menrecept_cod_receptor,";
                $cadena_sql.=" menrecept_estado";
                $cadena_sql.=" )";
                $cadena_sql.=" values";
                $cadena_sql.=" (";
                $cadena_sql.=$variable['codigoMensaje'].',';
                $cadena_sql.=$variable['tipoReceptor'].',';
                $cadena_sql.=$variable['codigoReceptor'].',';
                $cadena_sql.=$variable['estadoMensajeReceptor'];
                $cadena_sql.=" )";
                break;


	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>