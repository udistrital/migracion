<?php
/**
 * Clase para validar fechas de inscripcion de espacios académicos de cursos intermedios
 *
 * Esta clase se divide en diferentes funciones, ejemplo esta la funcion que valida fechas por grupo para coordinador
 *
 * @package clases
 * @author Maritza Callejas
 * @version 0.0.0.1
 * Fecha: 29/05/2013
 */

/**
 * @package clases
 * @subpackage validar_fechas_vacacionales 
 */
class validar_fechas_vacacionales {

    /**
     * Esta variable contiene los datos de configuracion del sistema (host, site, raiz, bloque)
     * @var array $configuracion Variables de configuracion del sistema
     */
    var $configuracion;

    
    /**
     * Función que permite validar las fechas de adiciones y cancelaciones para un proyecto curricular para el perfil de coordinador.
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $codProyecto Código del proyecto curricular del coordinador
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechas_CI_grupo_coordinador($configuracion,$codProyecto)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"coordinadorCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $evento='';
        
        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_CI_coordinador', $codProyecto);
        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if(is_array($resultado_fechas))
        {
            for($i=0;$i<count($resultado_fechas);$i++)
            {
                if($evento=='')
                    {
                        switch ($resultado_fechas[$i][0]) {
                                case '42':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='adicion';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='adicion';
                                                    break;
                                               }else
                                                   {
                                                    $evento='';
                                                   }
                                    break;

                                
                                default:
                                                    $evento='consulta';
                                    break;
                            }
                    }
            }
        }else
            {
                $evento='consulta';
            }

        return $evento;
    }

        /**
     * Función que permite validar las fechas de adiciones y cancelaciones para un proyecto curricular para el perfil de coordinador.
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $codCarrera Código del proyecto curricular del coordinador
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechas_CI_coordinador_otros_grupos($configuracion,$codCarrera)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"coordinadorCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $evento='';

        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_CI_coordinador_otros', $codCarrera);
        //original fechas estudiante
        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if(is_array($resultado_fechas))
        {
            for($i=0;$i<count($resultado_fechas);$i++)
            {
                if($evento=='')
                    {
                        switch ($resultado_fechas[$i][0]) {
                                case '42':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='adicion';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='adicion';
                                                    break;
                                               }else
                                                   {
                                                    $evento='';
                                                   }
                                    break;

                                default:
                                                    $evento='consulta';
                                    break;
                            }
                    }
            }
        }else
            {
                $evento='consulta';
            }

        return $evento;
    }


    
    /**
     * Funcion que devuelve la cadena sql que debe ser ejecutada en la base de datos
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param string $tipo Cadena sql que debe ejecutarse
     * @param string $variable parametros que se envian para completar la cadena sql
     * @return string Cadena sql a ejecutar
     */
    function cadena_sql($configuracion, $tipo, $variable='')
    {
        switch ($tipo)
        {
            case 'fechas_activas_CI_coordinador':
                $cadena_sql=" SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24miss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24miss') ";
                $cadena_sql.=" FROM ACCALEVENTOS";
                $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='V')";
                $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='V')";
                $cadena_sql.=" AND ACE_ESTADO='A'";
                $cadena_sql.=" AND ACE_CRA_COD='".$variable."'";
                $cadena_sql.=" AND ACE_COD_EVENTO =42";
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'fechas_activas_CI_coordinador_otros':
                $cadena_sql=" SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24miss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24miss') ";
                $cadena_sql.=" FROM ACCALEVENTOS";
                $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='V')";
                $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='V')";
                $cadena_sql.=" AND ACE_ESTADO='A'";
                $cadena_sql.=" AND ACE_CRA_COD='".$variable."'";
                $cadena_sql.=" AND ACE_COD_EVENTO =42";
                $cadena_sql.=" ORDER BY 1";
                break;

            default :
                $cadena_sql='';      
                break;
        }

        return $cadena_sql;
    }
}
?>
