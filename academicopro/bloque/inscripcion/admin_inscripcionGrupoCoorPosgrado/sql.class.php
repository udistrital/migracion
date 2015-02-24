<?php
/**
 * SQL adminInscripcionGrupoCoordinadorPosgrado
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionGrupoCoordinadorPosgrado
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 * @author Luis Fernando Torres
 * @version 0.0.0.1
 * Fecha: 19/11/2010
 */
/**
 * Verifica si la variable global existe para poder navegar en el sistema
 *
 * @global boolean Permite navegar en el sistema
 */if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
/**
 * Incluye la funcion sql.class.php
 */
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");
/**
 * Clase sql_adminInscripcionGrupoCoordinadorPosgrado
 *
 * Esta clase se encarga de crear las sentencias sql para el bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Admin
 */
class sql_adminInscripcionGrupoCoordinadorPosgrado extends sql {

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
    function cadena_sql($tipo,$variable="") {
        switch($tipo) {


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
            case 'buscarEspaciosAcademicos':
                $cadena_sql=" SELECT";
                $cadena_sql.=" pen_asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" pen_cre CREDITOS,";
                $cadena_sql.=" pen_nro_ht HTD,";
                $cadena_sql.=" pen_nro_hp HTC,";
                $cadena_sql.=" pen_nro_aut HTA,";
                $cadena_sql.=" pen_ind_ele ELECTIVO";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" INNER JOIN acasi ON pen_asi_cod= asi_cod";
                $cadena_sql.=" WHERE pen_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND pen_nro=".$variable['planEstudio'];
                switch ($variable['opcion']) {                  
                  case 'nivel':
                      $cadena_sql.=" AND pen_sem=".$variable['nivel'];
                    break;
                  case 'codigo':
                      $cadena_sql.=" AND pen_asi_cod=".$variable['codEspacio'];
                    break;
                  case 'nombre':                    
                      $cadena_sql.=" AND asi_nombre like '%".$variable['nombreEspacio']."%'";
                    break;
                  
                  default:
                    break;
                }


                $cadena_sql.=" AND pen_estado='A'";

            break;

          //Oracle
            case 'buscarGrupos':

                $cadena_sql="SELECT ";
                $cadena_sql.=" cur_id           ID_GRUPO,";
                $cadena_sql.=" (lpad(cur_cra_cod,3,0)||'-'||cur_grupo) GRUPO,";
                $cadena_sql.=" cur_nro_cupo     CUPO,";
                $cadena_sql.=" cur_nro_ins      INSCRITOS";
                $cadena_sql.=" FROM accursos";
                $cadena_sql.=" WHERE cur_cra_cod=".$variable['codProyecto'];
                $cadena_sql.=" AND cur_asi_cod=".$variable['codEspacio'];
                $cadena_sql.=" AND cur_ape_ano=".$variable['ano'];
                $cadena_sql.=" AND cur_ape_per=".$variable['periodo'];
                $cadena_sql.=" ORDER BY cur_grupo";

            break;    


                //Oracle
            case 'buscarHorarioGrupo':

                $cadena_sql="SELECT DISTINCT";
                $cadena_sql.=" HOR_DIA_NRO          DIA,";
                $cadena_sql.=" HOR_HORA             HORA,";
                $cadena_sql.=" SED_ABREV            SEDE,";
                $cadena_sql.=" Hor_Sal_id_espacio   SALON";
                $cadena_sql.=" FROM ACHORARIOS";
                $cadena_sql.=" INNER JOIN ACCURSOS ON achorarios.HOR_ID_CURSO=ACCURSOS.CUR_ID ";
                $cadena_sql.=" INNER JOIN GESALONES ON ACHORARIOS.HOR_SAL_ID_ESPACIO = GESALONES.SAL_ID_ESPACIO";
                $cadena_sql.=" LEFT OUTER JOIN GESEDE ON GESALONES.SAL_SED_ID=GESEDE.SED_ID ";
                $cadena_sql.=" WHERE CUR_ASI_COD=".$variable['espacio'];
                $cadena_sql.=" AND HOR_ID_CURSO=".$variable['codGrupo'];
                $cadena_sql.=" AND CUR_APE_ANO=".$variable['ano'];
                $cadena_sql.=" AND CUR_APE_PER=".$variable['periodo'];
                $cadena_sql.=" ORDER BY 1,2,3";
                
                break;

            //Oracle
            case 'contarInscritos':

                $cadena_sql="SELECT COUNT(*)";
                $cadena_sql.=" FROM ACINS";
                $cadena_sql.=" WHERE INS_ASI_COD=".$variable['espacio'];
                $cadena_sql.=" AND INS_GR=".$variable['codGrupo'];
                $cadena_sql.=" AND INS_ANO=".$variable['ano'];
                $cadena_sql.=" AND INS_PER=".$variable['periodo'];
                $cadena_sql.=" AND INS_ESTADO='A'";
                //echo $cadena_sql;exit;
            break;





          
//****************************************************************************
         
              //Oracle
            case 'datos_espacio':

                $cadena_sql="SELECT pen_sem NIVEL,";
                $cadena_sql.=" pen_asi_cod CODIGO,";
                $cadena_sql.=" asi_nombre NOMBRE,";
                $cadena_sql.=" pen_cre CREDITOS,";
                $cadena_sql.=" pen_nro_ht HTD,";
                $cadena_sql.=" pen_nro_hp HTC,";
                $cadena_sql.=" pen_nro_aut HTA,";
                $cadena_sql.=" pen_ind_ele ELECTIVA";
                $cadena_sql.=" FROM acpen";
                $cadena_sql.=" INNER JOIN acasi ON pen_asi_cod= asi_cod";
                $cadena_sql.=" WHERE asi_cod=".$variable['codigoEspacio'];
                $cadena_sql.=" AND pen_nro=".$variable['planEstudio'];
                $cadena_sql.=" AND pen_cra_cod=".$variable['codProyecto'];                

                break;



            case 'Grupo_numeroInscritos':

                $cadena_sql="select distinct count(ins_est_cod) from acins ";
                $cadena_sql.="where ins_ano=".$variable['ano'];
                $cadena_sql.="and ins_per=".$variable['periodo'];
                $cadena_sql.="and ins_asi_cod=".$variable['codigoEspacio'];
                $cadena_sql.="and ins_gr=".$variable['grupo'];

                break;

            case 'datosCoordinador':

                $cadena_sql="SELECT DISTINCT ";
                $cadena_sql.="PEN_NRO, ";
                $cadena_sql.="CRA_COD ";
                $cadena_sql.="FROM ACCRA ";
                $cadena_sql.="INNER JOIN GEUSUCRA ";
                $cadena_sql.="ON ACCRA.CRA_COD = ";
                $cadena_sql.="GEUSUCRA.USUCRA_CRA_COD ";
                $cadena_sql.="INNER JOIN ACPEN ";
                $cadena_sql.="ON ACCRA.CRA_COD = ";
                $cadena_sql.="ACPEN.PEN_CRA_COD ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="GEUSUCRA.USUCRA_NRO_IDEN = ";
                $cadena_sql.=$variable." ";
                $cadena_sql.="AND PEN_NRO > 200 ";

                break;


            //Oracle
            case 'datos_coordinador':
                $cadena_sql="SELECT distinct usucra_cra_cod DOCUMENTO, ";
                $cadena_sql.="cra_nombre NOMBRE,  ";
                $cadena_sql.="pen_nro NUMERO_PENSUM ";
                $cadena_sql.="FROM geusucra ";
                $cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
                $cadena_sql.="INNER JOIN acpen ON geusucra.usucra_cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="WHERE usucra_nro_iden=".$variable;

            break;


        }
        return $cadena_sql;
    }
}
?>
