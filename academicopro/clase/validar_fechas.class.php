<?php
/**
 * Clase para validar fechas de inscripcion de espacios académicos
 *
 * Esta clase se divide en diferentes funciones, ejemplo esta la funcion que valida fechas por estudiante, por grupo para coordinador
 * y la funcion que valida fechas para el perfil estudiante
 *
 * @package clases
 * @author Edwin Sanchez
 * @version 0.0.0.1
 * Fecha: 9/11/2010
 */

/**
 * @package clases
 * @subpackage validacion_fechas_adiciones 
 */
class validar_fechas {

    /**
     * Esta variable contiene los datos de configuracion del sistema (host, site, raiz, bloque)
     * @var array $configuracion Variables de configuracion del sistema
     */
    var $configuracion;
    /**
     * Función que permite validar las fechas de adiciones y cancelaciones por estudiante
     * para el perfil de coordinador.
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $cod_estudiante Código del estudiante
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechas_estudiante_coordinador($configuracion,$cod_estudiante)
    {                
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"coordinadorCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $cadena_sql=$this->cadena_sql($configuracion, 'datos_estudiante', $cod_estudiante);
        $resultado_datos=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        $cadena_sql=$this->cadena_sql($configuracion, 'preinscripcion_estudiante', $cod_estudiante);
        $resultado_preins=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        $evento='';

        if(is_array($resultado_preins))
            {
                if(trim($resultado_preins[0][0])=='S')
                    {
                        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_coordinador', $resultado_datos[0][0]);
                        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

                        if(is_array($resultado_fechas))
                            {
                                for($i=0;$i<count($resultado_fechas);$i++)
                                {
                                    if($evento=='')
                                        {
                                            switch ($resultado_fechas[$i][0]) {
                                                    case '8':
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

                                                    case '9':
                                                            $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                                            $final = $resultado_fechas[$i][3]-date('YmdHis');
                                                            if(($inicio>=0)&&($final<=0))
                                                                   {
                                                                        $evento='cancelacion';
                                                                        break;
                                                                  }else if(($inicio<=0)&&($final>=0))
                                                                   {
                                                                        $evento='cancelacion';
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
                    }else if(trim($resultado_preins[0][0])=='N')
                        {
                            $evento='consulta';
                        }
            }else
                {
                    $evento='consulta';
                }

                return $evento;
    }

    /**
     * Función que permite validar las fechas de adiciones y cancelaciones por estudiante
     * para el perfil de coordinador.
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $cod_estudiante Código del estudiante
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechas_estudiante_coordinador_posgrado($configuracion,$cod_estudiante)
    {                
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"coordinadorCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $cadena_sql=$this->cadena_sql($configuracion, 'datos_estudiante', $cod_estudiante);
        $resultado_datos=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        $evento='';
            $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_coordinador', $resultado_datos[0][0]);
            $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

                        if(is_array($resultado_fechas))
                            {
                                for($i=0;$i<count($resultado_fechas);$i++)
                                {
                                    if($evento=='')
                                        {
                                            switch ($resultado_fechas[$i][0]) {
                                                    case '8':
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

                                                    case '9':
                                                            $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                                            $final = $resultado_fechas[$i][3]-date('YmdHis');
                                                            if(($inicio>=0)&&($final<=0))
                                                                   {
                                                                        $evento='cancelacion';
                                                                        break;
                                                                  }else if(($inicio<=0)&&($final>=0))
                                                                   {
                                                                        $evento='cancelacion';
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
     * @param int $codProyecto Código del proyecto curricular del coordinador
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechas_grupo_coordinador($configuracion,$codProyecto)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"coordinadorCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $evento='';
        
        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_coordinador', $codProyecto);
        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if(is_array($resultado_fechas))
        {
            for($i=0;$i<count($resultado_fechas);$i++)
            {
                if($evento=='')
                    {
                        switch ($resultado_fechas[$i][0]) {
                                case '8':
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

                                case '9':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='cancelacion';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='cancelacion';
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
    function validar_fechas_coordinador_otros_grupos($configuracion,$codCarrera)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"coordinadorCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $evento='';

        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_coordinador_otros', $codCarrera);
        //original fechas estudiante
        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if(is_array($resultado_fechas))
        {
            for($i=0;$i<count($resultado_fechas);$i++)
            {
                if($evento=='')
                    {
                        switch ($resultado_fechas[$i][0]) {
                                case '15':
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

                                case '16':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('YmdHis');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='cancelacion';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='cancelacion';
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
     * Función que permite validar las fechas de adiciones y cancelaciones del estudiante
     * para el perfil de estudiante.
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $cod_estudiante Código del estudiante
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechas_estudiante($configuracion,$cod_estudiante)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"estudianteCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $cadena_sql=$this->cadena_sql($configuracion, 'datos_estudiante', $cod_estudiante);
        $resultado_datos=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        $cadena_sql=$this->cadena_sql($configuracion, 'preinscripcion_estudiante', $cod_estudiante);
        $resultado_preins=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        $evento='';

        if(is_array($resultado_preins))
            {
                if(trim($resultado_preins[0][0])=='S')
                    {
                        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_estudiante', $resultado_datos[0][0]);
                        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

                        if(is_array($resultado_fechas))
                            {
                                for($i=0;$i<count($resultado_fechas);$i++)
                                {
                                    if($evento=='')
                                        {
                                            switch ($resultado_fechas[$i][0]) {
                                                    case '15':
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

                                                    case '16':
                                                            $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                                            $final = $resultado_fechas[$i][3]-date('YmdHis');
                                                            if(($inicio>=0)&&($final<=0))
                                                                   {
                                                                        $evento='cancelacion';
                                                                        break;
                                                                  }else if(($inicio<=0)&&($final>=0))
                                                                   {
                                                                        $evento='cancelacion';
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
                    }else if(trim($resultado_preins[0][0])=='N')
                        {
                            $evento='consulta';
                        }
            }else
                {
                    $evento='consulta';
                }

                return $evento;
    }
    /**
     * Función que permite validar las fechas de adiciones y cancelaciones para el modulo de inscripciones del estudiante.
     * 
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $datosEstudiante: anio, periodo, codigo del proyecto
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechas_inscripciones_estudiante($configuracion,$datosEstudiante)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"estudianteCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        $cadena_sql=$this->cadena_sql($configuracion, 'preinscripcion_proyecto', $datosEstudiante);
        $resultado_preins=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        if (is_array($resultado_preins)&&$resultado_preins[0]['PROYECTO']==$datosEstudiante['codProyectoEstudiante'])
        {
            $preinscripcion='S';
        }else
            {
                $preinscripcion='N';
            }
        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_estudiante', $datosEstudiante['codProyectoEstudiante']);
        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
            $evento='';

        if ($preinscripcion=='S' && is_array($resultado_fechas))
        {
            foreach ($resultado_fechas as $fecha)
            {
                switch ($fecha['EVENTO'])
                {
                    case '15':
                        $inicio = $fecha['INICIO']-date('YmdHis');
                        $final = $fecha['FIN']-date('YmdHis');
                        if(($inicio<=0)&&($final>=0))
                        {
                            $evento='adicion';
                            break;
                        }else
                            {
                                $evento='';
                            }
                    break;

                    case '16':
                        $inicio = $fecha['INICIO']-date('YmdHis');
                        $final = $fecha['FIN']-date('YmdHis');
                        if(($inicio<=0)&&($final>=0))
                        {
                            $evento='cancelacion';
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
                if ($evento!='')break;
            }
        }
        if ($evento=='')$evento='consulta';
        $evento=array('PREINSCRIPCION'=>$preinscripcion,
                        'FECHAS'=>$resultado_fechas,
                        'EVENTO'=>$evento);
                    return $evento;
    }

    
    /**
     * Función que permite validar las fechas de preinscripciones del estudiante
     * para el perfil de estudiante.
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $codEstudiante Código del estudiante
     * @return string $evento evento que debe ejecutarse
     */
    function validar_fechasPreinscripcionProyecto($configuracion,$datosEstudiante)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"estudianteCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $cadena_sql=$this->cadena_sql($configuracion, 'cierre_semestre', $datosEstudiante);
        $resultado_preins=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
       if (is_array($resultado_preins)&&$resultado_preins[0]['PROYECTO']==$datosEstudiante['codProyectoEstudiante'])
        {
            $preinscripcion='S';
        }else
            {
                $preinscripcion='N';
            }
         $cadena_sql=$this->cadena_sql($configuracion, 'fechas_preinscripcion_proyecto', $datosEstudiante);
         $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");
        $evento='';
       if ($preinscripcion=='S' && is_array($resultado_fechas))
            {
          foreach ($resultado_fechas as $fecha)
                        {
                switch ($fecha['EVENTO'])
                {
                                    case '85':
                        $inicio = $fecha['INICIO']-date('YmdHis');
                        $final = $fecha['FIN']-date('YmdHis'); 
                                            if(($inicio<=0)&&($final>=0))
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
                if ($evento!='')break;
                        }

                }
         if ($evento=='')$evento='consulta';
        $evento=array('PREINSCRIPCION'=>$preinscripcion,
                        'FECHAS'=>$resultado_fechas,
                        'EVENTO'=>$evento);                    
                return $evento;
    }

    /**
     * Función que permite validar las fechas de adiciones y cancelaciones para un proyecto curricular para el perfil de estudiante.
     *
     * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
     * @param int $codCarrera Código del proyecto curricular
     * @return string $evento evento que debe ejecutarse
     */

   function validar_fechas_estudiantes_otros_grupos($configuracion,$codCarrera)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"estudianteCred");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $evento='';

        $cadena_sql=$this->cadena_sql($configuracion, 'fechas_activas_estudiante', $codCarrera);
        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if(is_array($resultado_fechas))
        {
            for($i=0;$i<count($resultado_fechas);$i++)
            {
                if($evento=='')
                    {

                        switch ($resultado_fechas[$i][0]) {
                                case '15':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('Ymd');
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

                                case '16':
                                        $inicio = $resultado_fechas[$i][2]-date('YmdHis');
                                        $final = $resultado_fechas[$i][3]-date('Ymd');
                                        if(($inicio>=0)&&($final<=0))
                                               {
                                                    $evento='cancelacion';
                                                    break;
                                              }else if(($inicio<=0)&&($final>=0))
                                               {
                                                    $evento='cancelacion';
                                                    break;
                                               }else
                                                   {
                                                    $evento='';
                                                   }
                                    break;

                                default:
                                                    $evento='';
                                    break;
                            }
                    }
            }
        }else
            {
                $evento='consulta';
            }

        return $evento;
    }//fin funcion validar_fechas_estudiantes_otros_grupos


    /**
     * Funcion que permite validar las fechas de novedades de nota para coordinador
     * 
     * @param type $configuracion
     * @param type $codProyecto
     * @param type $periodo
     * @return string 
     */
    function validar_fechasNovedadesCoordinador($configuracion,$codProyecto,$periodo)
    {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();
        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"coordinador");
        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");
        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");
        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        $evento='';
        $resultado_datos=array($codProyecto, $periodo);

        $cadena_sql=$this->cadena_sql($configuracion, 'fechasNovedadesNotaCoordinador', $resultado_datos);
        $resultado_fechas=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

        if(is_array($resultado_fechas))
            {
                foreach ($resultado_fechas as $key => $fecha) {
                    if($evento=='')
                        {
                            switch ($fecha[0]) {
                                    case '1':
                                            $inicio = $fecha[2]-date('YmdHis');
                                            $final = $fecha[3]-date('YmdHis');

                                            if(($inicio<=0)&&($final>=0))
                                                    {
                                                        $evento='ok';
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
            case 'datos_estudiante':
                $cadena_sql=" SELECT EST_CRA_COD, CRA_DEP_COD";
                $cadena_sql.=" FROM ACEST";
                $cadena_sql.=" INNER JOIN ACCRA ON CRA_COD=EST_CRA_COD";
                $cadena_sql.=" WHERE EST_COD='".$variable."'";
                break;

            case 'preinscripcion_estudiante':
                $cadena_sql=" SELECT Fua_Realizo_Preins(".$variable.") FROM DUAL";
                break;

            case 'preinscripcion_proyecto':
                $cadena_sql=" select ace_fec_ini INICIO,";
                $cadena_sql.=" ace_cra_cod PROYECTO";
                $cadena_sql.=" from accaleventos";
                $cadena_sql.=" where ace_cod_evento=14";
                $cadena_sql.=" and ace_anio=".$variable['ano'];
                $cadena_sql.=" and ace_periodo=".$variable['periodo'];
                $cadena_sql.=" and ace_cra_cod=".$variable['codProyectoEstudiante'];
            break;

            case 'cierre_semestre':
                $cadena_sql=" select ace_fec_ini INICIO,";
                $cadena_sql.=" ace_cra_cod PROYECTO";
                $cadena_sql.=" from accaleventos";
                $cadena_sql.=" where ace_cod_evento=73";
                $cadena_sql.=" and ace_anio=2013";//(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='P')";//.$variable['ano'];
                $cadena_sql.=" and ace_periodo=1";//(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='P')";//.$variable['periodo'];
                $cadena_sql.=" and ace_cra_cod=".$variable['codProyectoEstudiante'];
            break;

            case 'fechas_activas_coordinador':
                $cadena_sql=" SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24miss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24miss') ";
                $cadena_sql.=" FROM ACCALEVENTOS";
                $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND ACE_ESTADO='A'";
                $cadena_sql.=" AND ACE_CRA_COD='".$variable."'";
                $cadena_sql.=" AND ACE_COD_EVENTO IN (8,9)";
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'fechas_activas_coordinador_otros':
                $cadena_sql=" SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24miss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmddhh24miss') ";
                $cadena_sql.=" FROM ACCALEVENTOS";
                $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND ACE_ESTADO='A'";
                $cadena_sql.=" AND ACE_CRA_COD='".$variable."'";
                $cadena_sql.=" AND ACE_COD_EVENTO IN (15,16)";
                $cadena_sql.=" ORDER BY 1";
                break;

            case 'fechas_activas_estudiante':
                
                $cadena_sql=" SELECT DISTINCT ACE_COD_EVENTO EVENTO,";
                $cadena_sql.=" AAC_CRA_COD PROYECTO,";
                $cadena_sql.=" TO_CHAR (AAC_FECHA_INI,'YYYYMMDDHH24MISS') INICIO,";
                $cadena_sql.=" TO_CHAR (AAC_FECHA_FIN,'YYYYMMDDHH24MISS') FIN";
                $cadena_sql.=" FROM acfranjasadican";
                $cadena_sql.=" INNER JOIN ACCALEVENTOS";
                $cadena_sql.=" ON AAC_ANO=ACE_ANIO AND AAC_PERIODO=ACE_PERIODO AND AAC_CRA_COD=ACE_CRA_COD";
                $cadena_sql.=" WHERE ACE_COD_EVENTO IN (15, 16)";
                $cadena_sql.=" AND AAC_ANO=(SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND AAC_PERIODO=(SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND AAC_CRA_COD='".$variable."'";
                $cadena_sql.=" AND AAC_ESTADO LIKE '%A%'";
                $cadena_sql.=" AND ACE_ESTADO LIKE '%A%'";
                $cadena_sql.=" ORDER BY EVENTO,INICIO,FIN";                
                break;

            case 'fechas_preinscripcion_proyecto':
                $cadena_sql=" SELECT DISTINCT ACE_COD_EVENTO EVENTO,";
                //$cadena_sql.=" AFI_ANO,";
                //$cadena_sql.=" AFI_PERIODO,";
                //$cadena_sql.=" AFI_DEP_COD,";
                $cadena_sql.=" AFI_CRA_COD PROYECTO,";
                //$cadena_sql.=" AFI_COD_FRANJA,";
                $cadena_sql.=" TO_CHAR (AFI_FECHA_INI,'YYYYMMDDHH24MISS') INICIO,";
                $cadena_sql.=" TO_CHAR (AFI_FECHA_FIN,'YYYYMMDDHH24MISS') FIN";
                //$cadena_sql.=" AFI_ESTADO";
                $cadena_sql.=" FROM ACFRANJASINSDEMANDA";
                $cadena_sql.=" INNER JOIN ACCALEVENTOS ON AFI_DEP_COD=ACE_DEP_COD AND AFI_ANO=ACE_ANIO AND AFI_PERIODO=ACE_PERIODO";
                $cadena_sql.=" WHERE ACE_COD_EVENTO IN (85)";
                $cadena_sql.=" AND AFI_ANO=".$variable['ano'];
                $cadena_sql.=" AND AFI_PERIODO=".$variable['periodo'];
                $cadena_sql.=" AND AFI_CRA_COD=".$variable['codProyectoEstudiante'];
                //$cadena_sql.=" AND AFI_DEP_COD=".$variable[1];
                $cadena_sql.=" AND AFI_ESTADO LIKE '%A%'";
                $cadena_sql.=" AND ACE_ESTADO LIKE '%A%'";
                $cadena_sql.=" ORDER BY EVENTO,INICIO,FIN"; 
                break;

            case 'fechas_activas_estudiantes_otros_grupos':
                $cadena_sql=" SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhh24miss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmdd') ";
                $cadena_sql.=" FROM ACCALEVENTOS";
                $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='A')";
                $cadena_sql.=" AND ACE_ESTADO='A'";
                $cadena_sql.=" AND ACE_CRA_COD='".$variable."'";
                $cadena_sql.=" AND ACE_COD_EVENTO =15 ";
                $cadena_sql.=" ORDER BY 1";

                break;
            
            case 'fechasNovedadesNotaCoordinador':
                $cadena_sql=" SELECT ACE_COD_EVENTO EVENTO,";
                $cadena_sql.=" ACE_CRA_COD PROYECTO,";
                $cadena_sql.=" TO_CHAR (ACE_FEC_INI,'YYYYMMDDHH24MISS') INICIO,";
                $cadena_sql.=" TO_CHAR (ACE_FEC_FIN,'YYYYMMDDHH24MISS') FIN";
                $cadena_sql.=" FROM ACCALEVENTOS";
                $cadena_sql.=" WHERE ACE_COD_EVENTO IN (1)";
                $cadena_sql.=" AND ACE_ANIO=".$variable[1]['ANO'];
                $cadena_sql.=" AND ACE_PERIODO=".$variable[1]['PERIODO'];
                $cadena_sql.=" AND ACE_CRA_COD='".$variable[0]."'";
                $cadena_sql.=" AND ACE_ESTADO LIKE '%A%'";
                $cadena_sql.=" ORDER BY EVENTO,INICIO,FIN";                
                break;
        }

        return $cadena_sql;
    }
}
?>
