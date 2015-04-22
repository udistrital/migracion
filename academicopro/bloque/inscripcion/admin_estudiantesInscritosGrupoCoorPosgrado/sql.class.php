<?php
/**
 * SQL admin_estudiantesInscritosGrupoCoordinadorPosgrado
 *
 * Esta clase se encarga de crear las sentencias sql del bloque adminInscripcionCoordinador
 *
 * @package InscripcionCoordinadorPorGrupo
 * @subpackage Consulta
 * @author Fernando Torres & Milton Parra
 * @version 0.0.0.1
 * Fecha: 04/04/2011
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
class sql_estudiantesInscritosGrupoCoordinadorPosgrado extends sql
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
              case "buscarDatosProyecto":

                    $cadena_sql="SELECT cra_cod CODIGO, ";
                    $cadena_sql.=" cra_nombre NOMBRE ";
                    $cadena_sql.=" FROM accra";
                    $cadena_sql.=" WHERE";
                    $cadena_sql.=" cra_cod=".$variable['codProyecto'];

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
                  $cadena_sql.=" AND pen_asi_cod=".$variable['codEspacio'];

                break;

          //Oracle
            case 'buscarGrupos':

                $cadena_sql="SELECT ";
                $cadena_sql.=" cur_id           ID_GRUPO,";
                $cadena_sql.=" (lpad(cur_cra_cod::text,3,'0')||'-'||cur_grupo) GRUPO,";
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
                $cadena_sql.=" horario.hor_dia_nro          DIA,";
                $cadena_sql.=" horario.hor_hora             HORA,";
                $cadena_sql.=" sede.sed_id                  SEDE,";
                $cadena_sql.=" horario.hor_sal_id_espacio   ID_SALON,";
                $cadena_sql.=" salon.sal_nombre             SALON,";
                $cadena_sql.=" salon.sal_edificio           ID_EDIFICIO,";
                $cadena_sql.=" edi.edi_nombre               EDIFICIO,";
                $cadena_sql.=" curso.cur_nro_cupo           CUPO, ";
                $cadena_sql.=" hor_alternativa              HOR_ALTERNATIVA ";
                $cadena_sql.=" FROM achorarios horario";
                $cadena_sql.=" INNER JOIN accursos curso ON horario.hor_id_curso=curso.cur_id ";
                $cadena_sql.=" LEFT OUTER JOIN gesalones salon ON horario.hor_sal_id_espacio = salon.sal_id_espacio";
                $cadena_sql.=" LEFT OUTER JOIN gesede sede ON salon.sal_sed_id=sede.sed_id ";
                $cadena_sql.=" LEFT OUTER JOIN geedificio edi ON salon.sal_edificio=edi.edi_cod";
                $cadena_sql.=" WHERE cur_asi_cod=".$variable['espacio']; //codigo del espacio
                $cadena_sql.=" AND cur_ape_ano=" . $variable['ano'];
                $cadena_sql.=" AND cur_ape_per=" . $variable['periodo'];
                $cadena_sql.=" AND hor_id_curso=" . $variable['codGrupo']; //numero de grupo
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

            break;


           //Oracle
            case 'buscarEstudiantesInscritos':

                $cadena_sql="SELECT";
                $cadena_sql.=" ins_est_cod CODIGO";
                $cadena_sql.=" FROM";
                $cadena_sql.=" ACINS";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" INS_ASI_COD=".$variable['codEspacio'];
                $cadena_sql.=" AND INS_GR=".$variable['id_grupo'];
                $cadena_sql.=" AND INS_ANO=".$variable['ano'];
                $cadena_sql.=" AND INS_PER=".$variable['periodo'];
                $cadena_sql.=" AND INS_ESTADO='A'";
                $cadena_sql.=" ORDER BY CODIGO";

            break;


           //Oracle
            case 'buscarDatosEstudiantes':

                $cadena_sql="SELECT";
                $cadena_sql.=" est_cod CODIGO,";
                $cadena_sql.=" est_nombre NOMBRE,";
                $cadena_sql.=" est_cra_cod PROYECTO,";
                $cadena_sql.=" cra_abrev PROYECTOABREV,";
                $cadena_sql.=" est_estado_est ESTADO,";
                $cadena_sql.=" est_pen_nro PLAN";
                $cadena_sql.=" FROM";
                $cadena_sql.=" acest";
                $cadena_sql.=" INNER JOIN accra ON est_cra_cod=cra_cod";
                $cadena_sql.=" WHERE";
                $cadena_sql.=" est_cod=".$variable['codEstudiante'];

            break;




//***************************************


                case "estudiantesInscritos":

                    $cadena_sql="select est_cod, est_nombre, cra_nombre, est_ind_cred, (select cea_abr from geclasificaespac where cea_cod=ins_cea_cod) ";
                    $cadena_sql.="from acins ";
                    $cadena_sql.="inner join acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.="inner join accra on acest.est_cra_cod=accra.cra_cod ";
                    $cadena_sql.="where ins_asi_cod=".$variable[0];
                    $cadena_sql.=" and ins_gr=".$variable[1];
                    $cadena_sql.=" and ins_ano=(select ape_ano from acasperi where ape_estado like '%A%')";
                    $cadena_sql.=" and ins_per=(select ape_per from acasperi where ape_estado like '%A%')";
                    $cadena_sql.=" ORDER BY 1";

                break;


                case 'consultaGrupo':

                    $cadena_sql="SELECT DISTINCT ins_asi_cod, ";//0
                    $cadena_sql.="ins_cra_cod, ";//1
                    $cadena_sql.="ins_gr, ";               //2
                    $cadena_sql.="ins_ano, ";                 //3
                    $cadena_sql.="ins_per, ";              //4
                    $cadena_sql.="asi_nombre,";              //5
                    $cadena_sql.="ins_est_cod, ";              //6
                    $cadena_sql.="est_pen_nro, ";              //7
                    $cadena_sql.="est_nombre, ";    //8
                    $cadena_sql.="pen_cre ";    //9
                    $cadena_sql.="FROM acins ";
                    $cadena_sql.="inner join acasi on acins.ins_asi_cod=acasi.asi_cod ";
                    $cadena_sql.="inner join acest on acins.ins_est_cod=acest.est_cod ";
                    $cadena_sql.="inner join acpen on acins.ins_asi_cod=acpen.pen_asi_cod and acest.est_pen_nro=acpen.pen_nro ";
                    $cadena_sql.="WHERE ins_est_cod=".$variable[0];
                    $cadena_sql.=" AND ins_ano=".$variable[1];
                    $cadena_sql.=" AND ins_per=".$variable[2];
                    $cadena_sql.=" ORDER BY ins_asi_cod ";

//                    echo $cadena_sql;
//                    exit;

                break;


                case 'periodoActivo':

                    $cadena_sql="SELECT ape_ano, ape_per from acasperi ";
                    $cadena_sql.="WHERE ape_estado like '%A%'";
                    break;


                case 'clasificacionEspacio':

                    $cadena_sql="SELECT CL.id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".  $this->configuracion['prefijo']."planEstudio_espacio PEE ";
                    $cadena_sql.="inner join ".  $this->configuracion['prefijo']."espacio_clasificacion CL on PEE.id_clasificacion=CL.id_clasificacion";
                    $cadena_sql.=" where id_espacio=".$variable;
                    break;

                case 'clasificacion':

                    $cadena_sql="SELECT id_clasificacion,clasificacion_abrev,clasificacion_nombre from ".  $this->configuracion['prefijo']."espacio_clasificacion";
                    break;


	}#Cierre de switch

	return $cadena_sql;
   }#Cierre de funcion cadena_sql
}#Cierre de clase
?>