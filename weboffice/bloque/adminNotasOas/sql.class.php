<?php
/**
 * SQL adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package Login principal moodle
 * @subpackage Admin
 * @author Oficina Asesora de Sistemas
 * @version 0.0.0.1
 * Fecha: 18/02/2011
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
 * @subpackage Admin
 */
class sql_adminNotasOas extends sql {

    /**
     * Funcion que crea la cadena sql
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo variable que contiene la opcion para seleccionar la cadena sql que se necesita crear
     * @param array $variable Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @param array $variable2 Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @return string Cadena sql creada
     */
    function cadena_sql($configuracion, $tipo, $variable="", $variable2="") {
        switch ($tipo) {

            case "anioper":
		  $cadena_sql="SELECT ";
		  $cadena_sql.="ape_ano, ape_per ";
		  $cadena_sql.="FROM ";
		  $cadena_sql.="acasperi ";
		  $cadena_sql.="WHERE ";
		  $cadena_sql.="ape_estado='A'";	
		  break;

            case 'consultaAsignaturasMoodle':

                 $cadena_sql = "SELECT ";
                 $cadena_sql.= "distinct(grupo), ";
                 $cadena_sql.= "asignatura ";
                 $cadena_sql.= "FROM ";
                 $cadena_sql.= "v_notas_oas ";
                 $cadena_sql.= "WHERE ";
                 $cadena_sql.= "grupo<> 'SIN GRUPO'";

                break;

            case 'consultaAsignaturas':

                 $cadena_sql = "SELECT ";
                 $cadena_sql.= "distinct (asignatura) ";
                 $cadena_sql.= "FROM ";
                 $cadena_sql.= "v_notas_oas ";
                 $cadena_sql.= "WHERE ";
                 $cadena_sql.= "grupo<> 'SIN GRUPO'";

                break;

            case 'consultanotas':
                $cadena_sql = "SELECT ";
                $cadena_sql .= "asignatura ";    //0
                $cadena_sql .= ",grupo ";       //1
                $cadena_sql .= ",codigo ";      //2
                $cadena_sql .= ",nombre ";      //3
                $cadena_sql .= ",apellido ";    //4
                $cadena_sql .= ",nota ";        //5
                $cadena_sql .= ",id_nota ";        //6
                $cadena_sql .= "FROM ";
                $cadena_sql .= "v_notas_oas ";
                $cadena_sql .= "WHERE ";        
                $cadena_sql .= "asignatura='".$variable[0]."' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= "grupo='".$variable[1]."' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= "estado='".$variable[2]."' ";
                break;

            case 'consultanotasAsi':
                $cadena_sql = "SELECT ";
                $cadena_sql .= "asignatura ";    //0
                $cadena_sql .= ",grupo ";       //1
                $cadena_sql .= ",codigo ";      //2
                $cadena_sql .= ",nombre ";      //3
                $cadena_sql .= ",apellido ";    //4
                $cadena_sql .= ",nota ";        //5
                $cadena_sql .= ",id_nota ";        //6
                $cadena_sql .= "FROM ";
                $cadena_sql .= "v_notas_oas ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "asignatura='".$variable[0]."' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= "estado='".$variable[1]."' ";
                break;

            case 'consultaTodos':
                $cadena_sql = "SELECT ";
                $cadena_sql .= "asignatura ";    //0
                $cadena_sql .= ",grupo ";       //1
                $cadena_sql .= ",codigo ";      //2
                $cadena_sql .= ",nombre ";      //3
                $cadena_sql .= ",apellido ";    //4
                $cadena_sql .= ",nota ";        //5
                $cadena_sql .= "FROM ";
                $cadena_sql .= "v_notas_oas ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "asignatura='".$variable[0]."' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= "grupo='".$variable[1]."' ";
                break;

            case 'consultaTodosAsi':
                $cadena_sql = "SELECT ";
                $cadena_sql .= "asignatura ";    //0
                $cadena_sql .= ",grupo ";       //1
                $cadena_sql .= ",codigo ";      //2
                $cadena_sql .= ",nombre ";      //3
                $cadena_sql .= ",apellido ";    //4
                $cadena_sql .= ",nota ";        //5
                $cadena_sql .= "FROM ";
                $cadena_sql .= "v_notas_oas ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "asignatura='".$variable."' ";
                break;

            case 'consultaCondor':
                $cadena_sql = "SELECT ";
                $cadena_sql .= "ins_est_cod ";    //0
                $cadena_sql .= ",ins_asi_cod ";       //1
                $cadena_sql .= ",ins_gr ";      //2
                $cadena_sql .= "FROM ";
                $cadena_sql .= "acins ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "ins_asi_cod='".$variable[0]."' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= "ins_gr='".$variable[1]."' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= "ins_est_cod='".$variable[2]."' ";
                break;


            case 'consutaAsiNombre':

                 $cadena_sql = "SELECT ";
                 $cadena_sql.= "asi_nombre ";
                 $cadena_sql.= "FROM ";
                 $cadena_sql.= "acasi ";
                 $cadena_sql.= "WHERE ";
                 $cadena_sql.= "asi_cod = ".$variable." ";
                 
                break;

            case 'actualizaAcins':

                 $cadena_sql = "UPDATE ";
                 $cadena_sql.= "acins ";
                 $cadena_sql.= "SET ";
                 $cadena_sql.= "ins_nota_par3='".$variable[0]."' ";
                 $cadena_sql.= "WHERE ";
                 $cadena_sql.= "ins_asi_cod = '".$variable[1]."' ";
                 $cadena_sql.= "AND ";
                 $cadena_sql.= "ins_gr = '".$variable[2]."' ";
                 $cadena_sql.= "AND ";
                 $cadena_sql.= "ins_est_cod = '".$variable[3]."' ";

                break;

            case "actualizaEstadoVista_old":
                $cadena_sql="UPDATE ";
                $cadena_sql.= "v_notas_oas ";
                $cadena_sql.= "SET ";
                $cadena_sql.= "estado='".$variable[3]."' ";
                $cadena_sql.= "WHERE ";
                $cadena_sql.= "asignatura='".$variable[0]."' ";
                $cadena_sql.= "AND ";
                $cadena_sql.= "grupo='".$variable[1]."' ";
                $cadena_sql.= "AND ";
                $cadena_sql.= "codigo='".$variable[2]."' ";
              break;

            case "actualizaEstadoVista":
                $cadena_sql="UPDATE ";
                $cadena_sql.= "mdl_quiz_attempts ";
                $cadena_sql.= "SET ";
                $cadena_sql.= "estado='".$variable[1]."' ";
                $cadena_sql.= "WHERE ";
                $cadena_sql.= "id='".$variable[0]."' ";
                break;

           case "actualizaPorcentaje":
               $cadena_sql="UPDATE ";
               $cadena_sql.="accurso ";
               $cadena_sql.="SET ";
               $cadena_sql.="cur_par3='".$variable[2]."' ";
               $cadena_sql.="WHERE ";
               $cadena_sql.="cur_asi_cod='".$variable[0]."' ";
               $cadena_sql.="AND ";
               $cadena_sql.="cur_nro='".$variable[1]."' ";
               $cadena_sql.="AND ";
               $cadena_sql.="cur_ape_ano=2012 ";
               $cadena_sql.="AND ";
               $cadena_sql.="cur_ape_per=1 ";
               break;

            case "consultaGrupoCondor":
               $cadena_sql="SELECT ";
               $cadena_sql.="ins_gr ";
               $cadena_sql.="FROM ";
               $cadena_sql.="acins ";
               $cadena_sql.="WHERE ";
               $cadena_sql.="ins_est_cod='".$variable[0]."' ";
               $cadena_sql.="AND ";
               $cadena_sql.="ins_asi_cod='".$variable[1]."' ";
               $cadena_sql.="AND ";
               $cadena_sql.="ins_ano='".$variable[2]."' ";
               $cadena_sql.="AND ";
               $cadena_sql.="ins_per='".$variable[3]."' ";

                break;

            case "consultaPeriodo":
                $cadena_sql="SELECT ";
                $cadena_sql.="ape_ano ";
                $cadena_sql.=",ape_per ";
                $cadena_sql.="FROM ";
                $cadena_sql.="acasperi ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ape_estado like 'A'";
                break;

        }
	//echo "<br>".$cadena_sql."<br>";  
        return $cadena_sql;
    }
}

?>
