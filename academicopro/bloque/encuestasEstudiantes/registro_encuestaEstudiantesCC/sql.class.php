<?php
/**
* SQL nombre_sql
*
* Esta clase se encarga de crear las sentencias sql del bloque
*
* @package nombrePaquete
* @subpackage nombreSubpaquete
* @author Karen palacios
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
class sql_reading extends sql
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
		  case 'rescatar_secciones':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_seccion ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" seccion_id=".$variable;
		    $cadena_sql.=" AND";
		    $cadena_sql.=" seccion_id_estado=1 ";
		    break;
		    
		  case 'rescatar_preguntas':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_pregunta,ilud_tipo_pregunta ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" pregunta_id_tipo=tippregunta_id ";
		    $cadena_sql.=" AND";
		    $cadena_sql.=" pregunta_id_estado=1 ";
		    break;
		   
		  /***************************RESPUESTAS UNICAS ***************************/
		  
		  case 'rescatar_respuesta_unica':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_respuestas_seleccion_multiple ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_pregunta=".$variable['pregunta'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
		    
		    break;
		    
		  case 'rescatar_respuestas_unicas':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_respuestas_seleccion_multiple ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
		    
		    break;		    
		    
		  case 'insertar_respuesta_unica':

		    $cadena_sql="INSERT";
		    $cadena_sql.=" INTO";
		    $cadena_sql.=" ilud_respuestas_seleccion_multiple( ";
		    $cadena_sql.=" respuesta_usuario,";
		    $cadena_sql.=" respuesta_id_pregunta,";
		    $cadena_sql.=" respuesta_id_seccion,";
		    $cadena_sql.=" respuesta_id_prueba,";
		    $cadena_sql.=" respuesta_id_proceso,";
		    $cadena_sql.=" respuesta_respuesta_usuario,";
		    $cadena_sql.=" respuesta_fecha)";
		    $cadena_sql.=" VALUES(";
		    $cadena_sql.=" {$variable['usuario']},";
		    $cadena_sql.=" {$variable['pregunta']},";
		    $cadena_sql.=" {$variable['seccion']},";
		    $cadena_sql.=" {$variable['prueba']},";
		    $cadena_sql.=" {$variable['proceso']},";
		    $cadena_sql.=" '{$variable['respuesta']}',";
		    $cadena_sql.=" '{$variable['tiempo']}'";
		    $cadena_sql.=" )";
		    
		    break;
		    
		  case 'actualizar_respuesta_unica':

		    $cadena_sql="UPDATE";
		    $cadena_sql.=" ilud_respuestas_seleccion_multiple ";
		    $cadena_sql.=" SET";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_pregunta=".$variable['pregunta'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_respuesta_usuario='".$variable['respuesta']."'";
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_fecha='".$variable['tiempo']."'";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_id=".$variable['identificador'];
		    
		    break;
		 /**********************************************************************/
		 
		 
		 
		 
		  /***************************RESPUESTAS ABIERTAS ***************************/
		  
		  case 'rescatar_respuesta_abierta':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_respuestas_abiertas ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_pregunta=".$variable['pregunta'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
		    
		    break;
		    
		  case 'rescatar_respuestas_abiertas':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_respuestas_abiertas ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
		    
		    break;		    
		    
		  case 'insertar_respuesta_abierta':

		    $cadena_sql="INSERT";
		    $cadena_sql.=" INTO";
		    $cadena_sql.=" ilud_respuestas_abiertas( ";
		    $cadena_sql.=" respuesta_usuario,";
		    $cadena_sql.=" respuesta_id_pregunta,";
		    $cadena_sql.=" respuesta_id_seccion,";
		    $cadena_sql.=" respuesta_id_prueba,";
		    $cadena_sql.=" respuesta_id_proceso,";
		    $cadena_sql.=" respuesta_respuesta_usuario,";
		    $cadena_sql.=" respuesta_fecha)";
		    $cadena_sql.=" VALUES(";
		    $cadena_sql.=" {$variable['usuario']},";
		    $cadena_sql.=" {$variable['pregunta']},";
		    $cadena_sql.=" {$variable['seccion']},";
		    $cadena_sql.=" {$variable['prueba']},";
		    $cadena_sql.=" {$variable['proceso']},";
		    $cadena_sql.=" '{$variable['respuesta']}',";
		    $cadena_sql.=" '{$variable['tiempo']}'";
		    $cadena_sql.=" )";
		    
		    break;
		    
		  case 'actualizar_respuesta_abierta':

		    $cadena_sql="UPDATE";
		    $cadena_sql.=" ilud_respuestas_abiertas ";
		    $cadena_sql.=" SET";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_pregunta=".$variable['pregunta'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_respuesta_usuario='".$variable['respuesta']."'";
		    $cadena_sql.=" ,";
		    $cadena_sql.=" respuesta_fecha='".$variable['tiempo']."'";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_id=".$variable['identificador'];
		    
		    break;
		 /**********************************************************************/
		 
		 
		 
		  case 'rescatar_opciones_preguntas':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_opciones_seleccion_multiple ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" opcion_id_estado=1 ";
		    $cadena_sql.=" ORDER BY opcion_posicion ASC";
		    break;
		    
		  case 'rescatar_preguntas_seccion':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" *";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_seccion_pregunta ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" secpreg_id_pregunta_padre=".$variable['padre'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" secpreg_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" ORDER BY secpreg_posicion ASC";
		    
		    
		    break;

		  case 'encuesta_diligenciada':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" respuesta_id,";
		    $cadena_sql.=" 'seleccion multiple' TIPO,";
		    $cadena_sql.=" respuesta_usuario,";
		    $cadena_sql.=" respuesta_id_pregunta ";
                    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_respuestas_seleccion_multiple ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
		    $cadena_sql.=" UNION ";
		    $cadena_sql.=" SELECT";
		    $cadena_sql.=" respuesta_id,";
		    $cadena_sql.=" 'abierta',";
		    $cadena_sql.=" respuesta_usuario,";
		    $cadena_sql.=" respuesta_id_pregunta ";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" ilud_respuestas_abiertas ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" respuesta_usuario=".$variable['usuario'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_seccion=".$variable['seccion'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_prueba=".$variable['prueba'];
		    $cadena_sql.=" AND";
		    $cadena_sql.=" respuesta_id_proceso=".$variable['proceso'];
                    break;


                case 'consultar_proyecto_estudiante':

		    $cadena_sql="SELECT";
		    $cadena_sql.=" est_cra_cod";
		    $cadena_sql.=" FROM";
		    $cadena_sql.=" acest ";
		    $cadena_sql.=" WHERE";
		    $cadena_sql.=" est_cod=".$variable;
		    break;
		    
    }
  //echo "<br/>{$tipo}={$cadena_sql}";
  return $cadena_sql;
   }
   
}
?>
