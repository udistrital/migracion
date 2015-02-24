<?php
/**
 * SQL adminInscripcionCoordinador
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
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
 * @subpackage Admin
 */
class sql_admin_preinscripcion extends sql {

    /**
     * Funcion que crea la cadena sql
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo variable que contiene la opcion para seleccionar la cadena sql que se necesita crear
     * @param array $variable Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @param array $variable2 Esta variable puede ser de cualquier tipo array, string, int, double y se encarga de completar la sentencia sql
     * @return string Cadena sql creada
     */
    function cadena_sql($configuracion, $tipo, $variable="") {
        switch ($tipo) {

            case 'proyectos_curriculares':

                $cadena_sql = "select distinct cra_cod, cra_nombre ";
                $cadena_sql.="from accra ";
                $cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                $cadena_sql.="where USUCRA_NRO_IDEN=".$variable;
                $cadena_sql.=" and cra_estado like 'A'";
                $cadena_sql.=" order by 1";

                break;

            case 'validarEvento':

                $cadena_sql ="select * ";
                $cadena_sql.="from accaleventos, acasperi ";
                $cadena_sql.="where ape_estado = 'A' ";
                $cadena_sql.="AND ace_anio=ape_ano ";
                $cadena_sql.="AND ace_periodo=ape_per ";
                $cadena_sql.="AND ace_cra_cod='".$variable."' ";
                $cadena_sql.="AND ACE_COD_EVENTO='14'";

                break;

            case 'periodo_academico':

                $cadena_sql = "select distinct ape_ano, ape_per ";
                $cadena_sql.="from acasperi ";
                $cadena_sql.="where ape_estado='A'";

                break;

            case 'verificarParametrosPreinscripcion':

                $cadena_sql = "select distinct api_verificar_requisito, ";
                $cadena_sql.="api_nro_semestres,  ";
                $cadena_sql.="api_consecutivos, ";
                $cadena_sql.="api_maximo_asignaturas, ";
                $cadena_sql.="api_semestres_superiores, ";
                $cadena_sql.="api_mas_asignaturas, ";
                $cadena_sql.="api_prioridad_perdidas, ";
                $cadena_sql.="api_prioridad, ";
                $cadena_sql.="api_creditosplan, ";
                $cadena_sql.="api_promediominimo, ";
                $cadena_sql.="api_maxcreditosnivel, ";
                $cadena_sql.="api_mincreditosnivel, ";
                $cadena_sql.="api_ob, ";
                $cadena_sql.="api_oc, ";
                $cadena_sql.="api_ei, ";
                $cadena_sql.="api_ee, ";
                $cadena_sql.="api_aprobado ";
                $cadena_sql.="from acparins ";
                $cadena_sql.="where api_cra_cod='".$variable[0]."'";
                $cadena_sql.=" and api_ape_ano='".$variable[1]."'";
                $cadena_sql.=" and api_ape_per='".$variable[2]."'";
                $cadena_sql.=" and api_tipo='P'";

                break;

            case 'verificarParametrosCondor':

                $cadena_sql = "select distinct api_verificar_requisito, ";
                $cadena_sql.="api_nro_semestres,  ";
                $cadena_sql.="api_consecutivos, ";
                $cadena_sql.="api_maximo_asignaturas, ";
                $cadena_sql.="api_semestres_superiores, ";
                $cadena_sql.="api_mas_asignaturas, ";
                $cadena_sql.="api_prioridad_perdidas, ";
                $cadena_sql.="api_prioridad, ";
                $cadena_sql.="api_creditosplan, ";
                $cadena_sql.="api_promediominimo, ";
                $cadena_sql.="api_maxcreditosnivel, ";
                $cadena_sql.="api_mincreditosnivel, ";
                $cadena_sql.="api_ob, ";
                $cadena_sql.="api_oc, ";
                $cadena_sql.="api_ei, ";
                $cadena_sql.="api_ee, ";
                $cadena_sql.="api_aprobado ";
                $cadena_sql.="from acparins ";
                $cadena_sql.="where api_cra_cod='".$variable[0]."'";
                $cadena_sql.=" and api_ape_ano='".$variable[1]."'";
                $cadena_sql.=" and api_ape_per='".$variable[2]."'";
                $cadena_sql.=" and api_tipo='C'";

                break;

            case 'actualizarParametrosPreinscripcion':

                $cadena_sql ="UPDATE acparins set api_verificar_requisito='".$variable[5]."', ";
                $cadena_sql.="api_nro_semestres='".$variable[6]."',  ";
                $cadena_sql.="api_consecutivos='".$variable[7]."', ";
                $cadena_sql.="api_maximo_asignaturas='".$variable[8]."', ";
                $cadena_sql.="api_semestres_superiores='".$variable[9]."', ";
                $cadena_sql.="api_mas_asignaturas='".$variable[10]."', ";
                $cadena_sql.="api_prioridad='".$variable[12]."' ";
                $cadena_sql.="where api_cra_cod='".$variable[0]."'";
                $cadena_sql.=" and api_ape_ano='".$variable[1]."'";
                $cadena_sql.=" and api_ape_per='".$variable[2]."'";
                $cadena_sql.=" and api_tipo='".$variable[14]."'";

                break;
            
            case 'insertarParametrosPreinscripcion':

                $cadena_sql ="INSERT INTO acparins VALUES (";
                $cadena_sql.="'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."',";
                $cadena_sql.="'".$variable[6]."',";
                $cadena_sql.="'".$variable[7]."',";
                $cadena_sql.="'".$variable[8]."',";
                $cadena_sql.="'".$variable[9]."',";
                $cadena_sql.="'".$variable[10]."',";
                $cadena_sql.="'".$variable[11]."',";
                $cadena_sql.="'".$variable[12]."',";
                $cadena_sql.="'".$variable[13]."',";
                $cadena_sql.="'".$variable[14]."', ";
                $cadena_sql.="'".$variable[15]."', ";
                $cadena_sql.="'".$variable[16]."', ";
                $cadena_sql.="'".$variable[17]."', ";
                $cadena_sql.="'".$variable[18]."', ";
                $cadena_sql.="'".$variable[19]."', ";
                $cadena_sql.="'".$variable[20]."', ";
                $cadena_sql.="'".$variable[21]."', ";
                $cadena_sql.="'".$variable[22]."', ";
                $cadena_sql.="'".$variable[23]."' ";
                $cadena_sql.=")";

                break;

            case 'parametrosInformePreinscripcion':
                $cadena_sql="SELECT api_cra_cod, ";
                $cadena_sql.="api_ape_ano, ";
                $cadena_sql.="api_ape_per, ";
                $cadena_sql.="api_est_ran_inf, ";
                $cadena_sql.="api_est_ran_sup, ";
                $cadena_sql.="api_verificar_requisito, ";
                $cadena_sql.="api_nro_semestres, ";
                $cadena_sql.="api_consecutivos, ";
                $cadena_sql.="api_maximo_asignaturas, ";
                $cadena_sql.="api_semestres_superiores, ";
                $cadena_sql.="api_mas_asignaturas, ";
                $cadena_sql.="api_prioridad_perdidas, ";
                $cadena_sql.="api_prioridad, api_estado ";
                $cadena_sql.="FROM acasperi, acparins ";
                $cadena_sql.="WHERE ape_estado = 'A' ";
                $cadena_sql.="AND ape_ano = api_ape_ano ";
                $cadena_sql.="AND ape_per = api_ape_per ";
                $cadena_sql.="AND api_cra_cod = '".$variable."' ";
                $cadena_sql.="and api_tipo='P'";

                break;

        }
		
		//echo "<br/>".$tipo."=".$cadena_sql;
		 
        return $cadena_sql;
    }
}

?>
