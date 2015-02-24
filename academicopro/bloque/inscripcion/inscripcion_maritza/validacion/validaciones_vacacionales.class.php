<?php
/*
 * Funcion que tiene todas las validaciones para el proceso de inscripciones de Posgrados
 *
 */

/**
 * Permite hacer las diferentes validaciones para la inscripción de espacios académicos
 * Cada funcion recibe unos parametros especificos
 *
 * @author Milton Parra
 * Fecha 12 de Abril de 2011
 */
if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

class validarInscripcionVacacional {

  private $configuracion;
  private $ano;
  private $periodo;
  private $usuario;

  public function __construct() {

    require_once("clase/config.class.php");
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    $this->configuracion = $configuracion;

    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

    $this->cripto = new encriptar();
    $this->funcionGeneral = new funcionGeneral();

    //Conexion General
    $this->acceso_db = $this->funcionGeneral->conectarDB($configuracion, "");

    //Conexion sga
    $this->accesoGestion = $this->funcionGeneral->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->funcionGeneral->conectarDB($configuracion, "oraclesga");

    //Datos de sesion
    $this->usuario = $this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");

    $this->identificacion = $this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->funcionGeneral->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    $cadena_sql = $this->cadena_sql("periodoActual", '');
    $resultado_periodo = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];
  }

  /**
   * Función que permite validar si el estudiante pertenece al plan de estudios.
   *
   * @param <array> $configuracion
   * @param <int> $codEstudiante
   * @param <int> $planEstudio
   * @return <array> $mensaje
   *
   */
  public function validarEstudiante($datosInscripcion) {
        if (!empty($this->usuario))
        {
    if (is_numeric($datosInscripcion['codEstudiante'])) {
      //comparar tipo de plan de estudio con el tipo de estudiante para comprobar si pertenece al plan de estudios
    $datosEstudiante = $this->datosEstudiante($datosInscripcion);
    if (is_array($datosEstudiante)) {
            $proyectosCoordinador = $this->proyectosCoordinador();
        
        $tipo = 0;
        for ($i = 0; $i < count($proyectosCoordinador); $i++) {
          if ($datosEstudiante[0]['PROYECTO_ESTUDIANTE'] == $proyectosCoordinador[$i]['PROYECTO'] && $datosEstudiante[0]['PLAN_ESTUDIANTE'] == $proyectosCoordinador[$i]['PLAN'] && trim($datosEstudiante[0]['INDICA_CREDITOS']) == trim($proyectosCoordinador[$i]['CREDITOS'])) {
            $tipo = 1;
            $valor = $i;
          }
        }
        if ($tipo == 1) {
          $mensaje = array('codProyectoEstudiante' => $datosEstudiante[0]['PROYECTO_ESTUDIANTE'],
              'planEstudioEstudiante' => $datosEstudiante[0]['PLAN_ESTUDIANTE'],
              'nombreProyecto' => $proyectosCoordinador[$valor]['NOMBRE'],
              'nivel' => $proyectosCoordinador[$valor]['CODIGONIVEL'],
              'creditos' => $proyectosCoordinador[$valor]['CREDITOS']);
        } else {
          $mensaje = "El estudiante con código " . $datosInscripcion['codEstudiante'] . " no pertenece al plan de estudios " . (isset($datosInscripcion['planEstudio'])?$datosInscripcion['planEstudio']:'') . " del proyecto curricular";
        }
      } else {
        $mensaje = "El dato ingresado no corresponde a un código válido de estudiante. Digite de nuevo el código";
      }
    } else {
      $mensaje = "El código del estudiante debe ser numerico, digite de nuevo el código";
    }
    }else
            {
                $mensaje="Imposible rescatar datos del usuario. Inicie nuevamente sesión.";
            }
    return $mensaje;
  }
  
  /**
   * Función que permite validar si el estudiante pertenece al plan de estudios.
   *
   * @param <array> $configuracion
   * @param <int> $codEstudiante
   * @param <int> $planEstudio
   * @return <array> $mensaje
   *
   */
  public function validarCodigoEstudiante($codEstudiante) {

    if (is_numeric($codEstudiante)) {

      $datosEstudiante = $this->buscarCodigoEstudiante($codEstudiante);
      if (is_array($datosEstudiante)) {
          return $datosEstudiante;
      } else {
        $mensaje = "El dato ingresado no corresponde a un código válido de estudiante. Digite de nuevo el código";
      }
    } else {
      $mensaje = "El código del estudiante debe ser numerico, digite de nuevo el código";
    }
    return $mensaje;
  }


  /**
   * Función que permite validar si el horario del nuevo espacio académico
   * presenta cruce con el horario inscrito por el estudiante.
   * Presenta mensaje de cruce indicando con que espacio.
   * codProyecto es el proyecto del Coordinador
   *
   * @param <array> $configuracion
   * @param <array> $datosHorario (codEspacio, codProyecto, grupo, codEstudiante)
   * @return <boolean>
   *
   */
  public function validarCruceHorario($datosInscripcion) {
    $horarioGrupo = $this->buscarHorarioGrupo($datosInscripcion);
    if (is_array($horarioGrupo)) {
      $nroRegistrosHorarioEstudiante = $this->buscarNumeroRegistrosHorarioEstudiante($datosInscripcion);
      if (is_array($nroRegistrosHorarioEstudiante)) {
        if ($nroRegistrosHorarioEstudiante[0][0] > 0) {
          $horarioEstudiante = $this->buscarHorarioEstudiante($datosInscripcion);
          $validacionCruce = $this->compararHorarioEstudiante_horarioGrupo($horarioGrupo, $horarioEstudiante);
          return $validacionCruce;
        } elseif ($nroRegistrosHorarioEstudiante[0][0] == 0) {
          return 'ok';
        } else {
          echo 'Fallo validar cruce en el numero de registros';
          exit;
        }
      }
    } else {
      echo 'Fallo validar cruce en el horario de grupo';
      exit;
    }
  }

  /**
   * Función que permite verificar el estado academico del estudiante para inscribir espacios
   * si es verdadero permite inscribir, para inscribir se requiere que el estado del estudiante sea A o B
   * retorno tiene las variables de la pagina a la que se retorna en caso no superar la validacion
   *
   * @param <array> $configuracion
   * @param <array> $datosInscripcion {codEstudiante}
   * @param <array> $resultadoEstadoEstudiante {CODIGO_ESTADO, NOMBRE, DESCRIPCION}
   * @return <boolean>
   */
  public function validarEstadoEstudiante($datosInscripcion) {

    if (is_null($datosInscripcion['codEstudiante'])) {
      $resultado[0][0] = "Fallo validar estado estudiante";
      return $resultado;
    } else {
      $resultadoEstadoEstudiante = $this->buscarEstadoEstudiante($datosInscripcion['codEstudiante']);
      if (trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'A' || trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'B'|| trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'V'|| trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'J') {
        return 'ok';
      } else {
        return $resultadoEstadoEstudiante;
      }
    }
  }

  /**
   * Función que permite verificar el estado academico del estudiante para preinscribir espacios por demanda
   * si es verdadero permite preinscribir, para preinscribir se requiere que el estado del estudiante sea V o J
   * retorno tiene las variables de la pagina a la que se retorna en caso no superar la validacion
   *
   * @param <array> $configuracion
   * @param <array> $datosInscripcion {codEstudiante}
   * @param <array> $resultadoEstadoEstudiante {CODIGO_ESTADO, NOMBRE, DESCRIPCION}
   * @return <boolean>
   */
  public function validarEstadoEstudiantePreinscripciones($datosInscripcion) {

    if (is_null($datosInscripcion['codEstudiante'])) {
      $resultado[0][0] = "Fallo validar estado estudiante";
      return $resultado;
    } else {
      $resultadoEstadoEstudiante = $this->buscarEstadoEstudiante($datosInscripcion['codEstudiante']);
      if (trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'A' || trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'B'|| trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'V'|| trim($resultadoEstadoEstudiante[0]['CODIGO_ESTADO']) == 'J') {
        return 'ok';
      } else {
        return $resultadoEstadoEstudiante;
      }
    }
  }

  /**
   * Funcion que permite validar si el espacio academico ya esta inscrito para el estudiante en el periodo actual.
   * @param <array> $datosInscripcion (codEstudiante,codProyectoEstudiante,codEspacio)
   * @return <array/string> (PROYECTO,GRUPO)/ok
   */
  public function validarEspacioInscrito($datosInscripcion) {

    //busca si el espacio academico ya esta inscrito, retorna arreglo,
    //si no esta inscrito el arreglo contiene el valor cero
    $resultadoEspacioInscrito = $this->buscarSiEspacioInscrito($datosInscripcion);
    if (is_array($resultadoEspacioInscrito) && $resultadoEspacioInscrito[0][0] == 0) {
        //echo "ok";//exit;
      return 'ok';
    } else {
      if (is_array($resultadoEspacioInscrito)) {
        $datosEspacioInscrito = $this->buscarDatosEspacioInscrito($datosInscripcion);
        if (is_array($datosEspacioInscrito)) {
          return $datosEspacioInscrito;
        } else {
          echo 'No se pueden rescatar los datos del espacio ya inscrito';
          return '';
          exit;
        }
      } else {
        echo 'Fallo validar "espacio ya inscrito"';
        exit;
      }
    }
  }

  /*   * Esta funcion valida si el espacio academico que se va a inscribir
   *  corresponde al plan de estudios del estudiante
   * @param <array> $this->configuracion
   * @param <array> $datosInscripcion (codEspacio, planEstudioEstudiante, codProyectoEstudiante)
   * @param <array> $retorno (pagina, opcion, parametros)
   * @return <boolean>
   */

  public function validarEspacioPlan($datosInscripcion) {

    $resultadoEspacioPlan = $this->buscarEspaciosPlanEstudiante($datosInscripcion);

    //si existe por lo menos un registro el espacio pertenece al plan de estudios del estudiante
    if (is_array($resultadoEspacioPlan) && $resultadoEspacioPlan[0][0] > 0) {
      return 'ok';
    } else {
      if (is_array($resultadoEspacioPlan)) {
        $resultado[0][0] = 'El espacio Académico no pertenece al plan de estudios del Estudiante.';
        return $resultado;
      } else {
        echo 'Fallo validar "Espacio plan de estudios estudiante"';
        exit;
      }
    }
  }

  /**
   * Funcion que permite conocer si un espacio que se quiere inscribir a un estudiante ya ha sido aprobado
   * si no esta aprobado retorna ok
   * si esta inscrito retorna codigo, ano y periodo en que aprobo el espacio academico
   *
   * @param <array> $datosAprobado (codEstudiante, codProyectoEstudiante, codEspacio) //se requiere codProyectoEstudiante para consultar nota minima aprobatoria
   * @param <array> $datosEspacioAprobado {CODIGO, ANO , PERIODO} del espacio academico
   * @return <boolean>
   */
  public function validarAprobado($datosInscripcion) {
    //busca si el espacio academico ya esta aprobado, retorna arreglo,
    //si no esta aprobado el arreglo contiene el valor cero (retorna ok)
    $resultadoEspacioAprobado = $this->buscarSiEspacioAprobado($datosInscripcion);
    if (is_array($resultadoEspacioAprobado) && $resultadoEspacioAprobado[0][0] == 0) {
      return 'ok';
    } else {
      if (is_array($resultadoEspacioAprobado)) {
        $datosEspacioAprobado = $this->buscarDatosEspacioAprobado($datosInscripcion);
        if (is_array($datosEspacioAprobado)) {
          return $datosEspacioAprobado;
        } else {
          return 'No se pueden rescatar los datos del espacio aprobado';
        }
      } else {
        return 'Fallo validar "Espacio aprobado"';
      }
    }
  }

  /**
   * Funcion que permite conocer si una nota ya se encunetra registrada para un ano y periodo
   * si no esta registrada retorna ok
   * si esta registrada retorna ano y periodo del registro
   *
   * @param <array> $datosNovedad (codEspacio,ano,periodo,nota,obs,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,tipo_est,
   *                              action,opcion,codProyecto,planEstudio,proyectosEspacio(1|Obligatorio Complementario|2|0|1|SANEAMIENTO AMBIENTAL|2|85|246|1|85),
   *                              nivel,creditos,htd,htc,hta,clasificacion,retornoPagina,retornoOpcion,
   *                              retornoParametros=(codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante))
   * @return <boolean>
   */
  public function validarNotaAnoPeriodo($datosNovedad) {
    //busca si la nota ya esta registrada para un ano y periodo, retorna arreglo,
    //si no esta registrada retorna ok
    $resultadoEspacioAprobado = $this->buscarNotaAnoPeriodo($datosNovedad);
    if (is_array($resultadoEspacioAprobado) && $resultadoEspacioAprobado[0][0] == 0) {
      return 'ok';
    } else {
      if (is_array($resultadoEspacioAprobado)) {
          $datosNota=array('CODIGO'=>$datosNovedad['codEspacio'],
                            'ANO'=>$datosNovedad['ano'],
                            'PERIODO'=>$datosNovedad['periodo']);
          return $datosNota;
      } else {
        return 'Fallo validar "Espacio aprobado"';
      }
    }
  }

  /**
   * Esta funcion permite validar si el espacio a inscribir ha sido cancelado en el periodo academico actual
   * Si ha sido canccelado retorna el numero de veces
   * @param <array> $this->configuracion
   * @param <array> $datosInscripcion (codEstudiante, codEspacio)
   * @param <array> $retorno (pagina, opcion, parametros)
   * @param <array> $continuar (pagina, opcion, parametros)
   * @return <booblean>
   */
  public function validarCancelado($datosInscripcion, $continuar="", $retorno="") {

    $resultado_cancelado = $this->consultarCancelado($datosInscripcion);
    if (is_array($resultado_cancelado)) {
      $veces = count($resultado_cancelado);
      if ($veces > 1

        )$mensaje = ' ' . $veces . ' veces ';
      else
        $mensaje=' ';
      $mensaje = array('VECES' => $mensaje);
      return $mensaje;
    }
    else {
      return 'ok';
    }
  }

  /**
   * Funcion que permite conocer si un espacio que se quiere inscribir a un estudiante ya ha sido aprobado
   * si no esta aprobado retorna ok
   * si esta inscrito retorna codigo, ano y periodo en que aprobo el espacio academico
   *
   * @param <array> $datosAprobado (codEstudiante, codProyectoEstudiante, codEspacio) //se requiere codProyectoEstudiante para consultar nota minima aprobatoria
   * @param <array> $datosEspacioAprobado {CODIGO, ANO , PERIODO} del espacio academico
   * @return <boolean>
   */
  public function validarReprobado($datosInscripcion) {
    //Permite verificar si el espacio academico aun esta reprobado para estudiantes en prueba, retorna arreglo,
    //si no esta aprobado el arreglo contiene el valor cero
    $resultadoEspacioReprobado = $this->buscarSiEspacioReprobado($datosInscripcion);
    if (is_array($resultadoEspacioReprobado) && $resultadoEspacioReprobado[0][0] > 0) {
      return 'ok';
    } else {
      if (is_array($resultadoEspacioReprobado)) {
        return $datosEspacioReprobado = array('espacio' => $datosInscripcion['codEspacio']);
      } else {
        return 'Fallo validar "Espacio reprobado"';
      }
    }
  }

  /**
   * Esta funcion permite validar si un grupo de otro proyecto presenta sobrecupo
   *
   * @param <array> $this->configuracion
   * @param <array> $datosGrupo (codEspacio, grupo)
   * @return <boolean>
   */
  public function validarSobrecupo($datosInscripcion) {
    $cupo = $this->buscarCupo($datosInscripcion);
    $carrera = $this->consultarCupo($datosInscripcion);
    $inscritos = $this->buscarInscritos($datosInscripcion);
    $cupos = $cupo - $inscritos;
    if ($cupos <= 0 && $carrera[0]['CARRERA'] != $datosInscripcion['codProyecto']) {
      $sobrecupo = array('cupo' => $cupo, 'inscritos' => $inscritos, 'disponibles' => $cupos);
      return $sobrecupo;
    } else {
      return 'ok';
    }
  }

  /**
   * Esta funcion permite validar si un grupo de otro proyecto presenta sobrecupo
   *
   * @param <array> $this->configuracion
   * @param <array> $datosGrupo (codEspacio, grupo)
   * @return <boolean>
   */
  public function verificarSobrecupo($datosInscripcion) {
    $cupo=$datosInscripcion['cupo'];
    $inscritos=$this->buscarInscritos($datosInscripcion);
    $cupos=$cupo-$inscritos;
    if ($cupos<=0) {
      $sobrecupo=array('cupo' => $cupo, 'inscritos' => $inscritos, 'disponibles' => $cupos);
      return $sobrecupo;
    } else {
      return 'ok';
    }
  }

  /**
   * Esta funcion permite validar si un grupo presenta sobrecupo
   *
   * @param <array> $this->configuracion
   * @param <array> $datosGrupo (codEspacio, grupo)
   * @return <boolean>
   */
  public function validarSobrecupoGrupo($datosInscripcion) {
    $cupo = $this->buscarCupo($datosInscripcion);
    $inscritos = $this->buscarInscritos($datosInscripcion);
    $cupos = $cupo - $inscritos;
    if ($cupos <= 0) {
      $sobrecupo = array('cupo' => $cupo, 'inscritos' => $inscritos, 'disponibles' => $cupos);
      return $sobrecupo;
    } else {
      return 'ok';
    }
  }

  /**
   * Este funcion permite validar requisitos para un espacio que se va a inscribir.
   *
   * @param <array> $this->configuracion
   * @param <array> $datosInscripcion ()
   * @param <array> $continuar(pagina, opcion, parametros)
   * @param <array> $retorno (pagina, opcion, parametros)
   * @return <boolean>
   */
  public function validarRequisitos($datosInscripcion) {
    $datosRequisitos = $datosInscripcion;
    $numero = $this->buscarCantidadRequisitosEspacio($datosInscripcion);
    if (is_array($numero)) {
      if ($numero[0][0] == 0) {
        return 'ok';
      } elseif ($numero[0][0] >= 1) {
        $requisitos = $this->buscarRequisitosEspacio($datosInscripcion);
        foreach ($requisitos as $key => $value) {
          $datosRequisitos['codEspacio'] = $requisitos[$key]['CODREQUISITO'];
          $aprobado = $this->buscarDatosEspacioAprobado($datosRequisitos);
          if ($requisitos[$key]['CODREQUISITO'] == $aprobado[0]['CODIGO']) {
            
          } else {
            $requisito[$key] = array('REQUISITO' => $requisitos[$key]['CODREQUISITO'],
                'NOMBRE' => $requisitos[$key]['NOMBREREQUISITO']);
          }
        }
        if (isset($requisito)) {
          return $requisito;
        } else {
          return 'ok';
        }
      } else {
        return "No se pueden encontrar datos de requisitos";
        exit;
      }
      unset($datosRequisitos);
    } else {
      return "No es posible consultar los requisitos";
      unset($datosRequisitos);
      exit;
    }

  }

  /**
   * Este funcion permite verificar si el estudiante va ainscribir mas espacios de los permitidos por el proyecto.
   * @param int $maximoEspaciosPeriodo
   * @param array $espaciosInscritos
   * @return string
   */
  public function validarEspaciosInscritos($maximoEspaciosPeriodo,$espaciosInscritos) {
        if (is_array($espaciosInscritos))
        {
            $totalEspacios=count($espaciosInscritos);
        }else
            {
                $totalEspacios=0;
            }
        if ($totalEspacios>=$maximoEspaciosPeriodo)
        {
            return $maximoEspaciosPeriodo;
        }else
            {
                return 'ok';
            }
  }

  /**
   * Esta funcion permite validar si el espacio a inscribir ha sido cancelado en el periodo academico actual
   * Si ha sido canccelado retorna el numero de veces
   * @param <array> $this->configuracion
   * @param <array> $datosInscripcion (codEstudiante, codEspacio)
   * @param <array> $retorno (pagina, opcion, parametros)
   * @param <array> $continuar (pagina, opcion, parametros)
   * @return <booblean>
   */
  public function validarCreditosInscritos($datosInscripcion) {
    $creditosInscritos = $this->consultarCreditosInscritos($datosInscripcion);
    $creditosMaximoPlan = $this->consultarCreditosPlan($datosInscripcion);

    if (is_array($creditosInscritos)) {
      if (is_array($creditosMaximoPlan)) {
        if ($creditosMaximoPlan[0]['MAX_PERIODO'] >= $creditosInscritos['TOTAL'] + $datosInscripcion['creditos']) {
          return 'ok';
        } else {
          return $creditosMaximoPlan;
        }
      } else {
        echo "Fallo validar Creditos Plan";
        exit;
      }
    } elseif ($creditosInscritos == 'ok') {
      return 'ok';
    } else {
      echo "Fallo validar Creditos Inscritos";
      exit;
    }
  }

  /**
   * Esta funcion permite validar si el espacio a preinscribir no supera el maximo de creditos por periodo
   *
   * @param <array> $this->configuracion
   * @param <array> $datosInscripcion (codEstudiante, codEspacio)
   * @param <array> $retorno (pagina, opcion, parametros)
   * @param <array> $continuar (pagina, opcion, parametros)
   * @return <booblean>
   */
  public function validarCreditosPreinscritos($datosInscripcion) {
    $creditosInscritos = $this->consultarCreditosPreinscritos($datosInscripcion);
    $creditosMaximoPlan = $this->consultarCreditosPlan($datosInscripcion);

    if (is_array($creditosInscritos)) {
      if (is_array($creditosMaximoPlan)) {
        if ($creditosMaximoPlan[0]['MAX_PERIODO'] >= $creditosInscritos['TOTAL'] + $datosInscripcion['creditos']) {
          return 'ok';
        } else {
          return $creditosMaximoPlan;
        }
      } else {
        echo "Fallo validar Creditos Plan";
        exit;
      }
    } elseif ($creditosInscritos == 'ok') {
      return 'ok';
    } else {
      echo "Fallo validar Creditos Inscritos";
      exit;
    }
  }

  /**
   * Esta funcion permite validar si el espacio a inscribir ha sido cancelado en el periodo academico actual
   * Si ha sido canccelado retorna el numero de veces
   * @param <array> $this->configuracion
   * @param <array> $datosInscripcion (codEstudiante, codEspacio)
   * @param <array> $retorno (pagina, opcion, parametros)
   * @param <array> $continuar (pagina, opcion, parametros)
   * @return <booblean>
   */
  public function validarCreditosPorClasificacion($datosInscripcion) {
    $clasificacionEspacio=$this->consultarClasificacionEspacio($datosInscripcion);
    if (is_array($clasificacionEspacio))
    {
      $datosInscripcion['clasificacion']=$clasificacionEspacio[0]['CODIGO'];
      $creditosAprobados=  $this->consultarAprobadosClasificacion($datosInscripcion);
      $creditosInscritos = $this->consultarCreditosInscritos($datosInscripcion);

      $creditosPlan = $this->consultarCreditosPlan($datosInscripcion);
      if (is_array($creditosInscritos)) {
        if (is_array($creditosPlan)) {
          switch (trim($clasificacionEspacio[0]['CLASIFICACION']))
          {
            case 'OB':
              if ($creditosPlan[0]['OB']>=$creditosInscritos['OB']+$creditosInscritos['CP']+$datosInscripcion['creditos']+$creditosAprobados[0]['APROBADOS']){
                $resultado='ok';
              }else{
                $resultado[0]['creditos']=$clasificacionEspacio[0]['CLASIFICACION']." (".$creditosPlan[0]['OB'].")";
              }
            break;

            case 'CP':
              if ($creditosPlan[0]['OB']>=$creditosInscritos['OB']+$creditosInscritos['CP']+$datosInscripcion['creditos']+$creditosAprobados[0]['APROBADOS']){
                $resultado='ok';
              }else{
                $resultado[0]['creditos']=$clasificacionEspacio[0]['CLASIFICACION']." (".$creditosPlan[0]['OB'].")";
              }

            break;

          default :
            $clase=trim($clasificacionEspacio[0]['CLASIFICACION']);
              if ($creditosPlan[0][$clase]>=$creditosInscritos[$clase]+$datosInscripcion['creditos']+$creditosAprobados[0]['APROBADOS']){
                $resultado='ok';
              }else{
                $resultado[0]['creditos']=$clasificacionEspacio[0]['CLASIFICACION']." (".$creditosPlan[0][$clase].")";
              }

            break;

          }
          return $resultado;

        } else {
        echo "Fallo validar Creditos Plan";
        exit;
        }
      } elseif ($creditosInscritos == 'ok') {
      return 'ok';
    } else {
      echo "Fallo validar Creditos Inscritos";
      exit;
      }
    }
    else{
      echo "Fallo consultar clasificación espacio";
      exit;
    }
  }
  /**
   * Esta funcion permite validar si el espacio a inscribir ha sido cancelado en el periodo academico actual
   * Si ha sido canccelado retorna el numero de veces
   * @param <array> $this->configuracion
   * @param <array> $datosInscripcion (codEstudiante, codEspacio)
   * @param <array> $retorno (pagina, opcion, parametros)
   * @param <array> $continuar (pagina, opcion, parametros)
   * @return <booblean>
   */
  public function validarCreditosPreinscritosPorClasificacion($datosInscripcion) {
    $clasificacionEspacio=$this->consultarClasificacionEspacio($datosInscripcion);
    if (is_array($clasificacionEspacio))
    {
      $datosInscripcion['clasificacion']=$clasificacionEspacio[0]['CODIGO'];
      $creditosAprobados=  $this->consultarAprobadosClasificacion($datosInscripcion);
      $creditosInscritos = $this->consultarCreditosPreinscritos($datosInscripcion);

      $creditosPlan = $this->consultarCreditosPlan($datosInscripcion);
      if (is_array($creditosInscritos)) {
        if (is_array($creditosPlan)) {
          switch (trim($clasificacionEspacio[0]['CLASIFICACION']))
          {
            case 'OB':
              if ($creditosPlan[0]['OB']>=$creditosInscritos['OB']+$creditosInscritos['CP']+$datosInscripcion['creditos']+$creditosAprobados[0]['APROBADOS']){
                $resultado='ok';
              }else{
                $resultado[0]['creditos']=$clasificacionEspacio[0]['CLASIFICACION']." (".$creditosPlan[0]['OB'].")";
              }
            break;

            case 'CP':
              if ($creditosPlan[0]['OB']>=$creditosInscritos['OB']+$creditosInscritos['CP']+$datosInscripcion['creditos']+$creditosAprobados[0]['APROBADOS']){
                $resultado='ok';
              }else{
                $resultado[0]['creditos']=$clasificacionEspacio[0]['CLASIFICACION']." (".$creditosPlan[0]['OB'].")";
              }

            break;

          default :
            $clase=trim($clasificacionEspacio[0]['CLASIFICACION']);
              if ($creditosPlan[0][$clase]>=$creditosInscritos[$clase]+$datosInscripcion['creditos']+$creditosAprobados[0]['APROBADOS']){
                $resultado='ok';
              }else{
                $resultado[0]['creditos']=$clasificacionEspacio[0]['CLASIFICACION']." (".$creditosPlan[0][$clase].")";
              }

            break;

          }
          return $resultado;

        } else {
        echo "Fallo validar Creditos Plan";
        exit;
        }
      } elseif ($creditosInscritos == 'ok') {
      return 'ok';
    } else {
      echo "Fallo validar Creditos Inscritos";
      exit;
      }
    }
    else{
      echo "Fallo consultar clasificación espacio";
      exit;
    }
  }
  
  /**
   * Verifica si una inscripcion excede el numero de creditos maximo para el periodo
   * @param int $creditosMaximoPerido
   * @param array $espaciosInscritos
   * @param array $datosEspacio
   * @return int
   */
  public function verificarCreditosPeriodo($creditosMaximoPerido,$espaciosInscritos,$datosEspacio) {
        $inscribir='ok';
        $creditos=0;
        if(is_array($espaciosInscritos))
        {
            foreach ($espaciosInscritos as $inscritos) {
                $creditos+=$inscritos['CREDITOS'];
            }
        }
        $creditos=$creditos+$datosEspacio['CREDITOS'];
        if($creditos>$creditosMaximoPerido)
        {
            $inscribir=1;
        }
        return $inscribir;
  }

  /**
   * Verifica si una inscripcion excede el numero de creditos maximo para el periodo
   * @param int $creditosMaximoPerido
   * @param array $espaciosInscritos
   * @param array $datosEspacio
   * @return int
   */
    public function verificarCreditosClasificacion($parametros,$espaciosInscritos,$datosEspacio,$aprobados) {
        $inscribir='ok';
        $creditos=0;
        if(is_array($espaciosInscritos))
        {
            foreach ($espaciosInscritos as $inscritos)
            {
                $letras=array('OB','OC','EI','EE','CP');
                $numeros=array(1,2,3,4,5);
                $clasificacion=str_replace($letras,$numeros,trim($datosEspacio['CLASIFICACION']));
                if ($inscritos['CLASIFICACION']==$clasificacion)
                {
                    $creditos+=$inscritos['CREDITOS'];
                }
            }
        }
        $creditos=$creditos+$datosEspacio['CREDITOS']+$aprobados[trim($datosEspacio['CLASIFICACION'])];
      if($creditos>$parametros[trim($datosEspacio['CLASIFICACION'])])
      {
          $inscribir=1;
      }
      return $inscribir;
  }

  public function validarEspacioPlanCreditos($datosInscripcion) {
    $resultadoEspacioPlan = $this->buscarEspaciosPlanEstudiante($datosInscripcion);

    //si existe por lo menos un registro el espacio pertenece al plan de estudios del estudiante
    if (is_array($resultadoEspacioPlan) && $resultadoEspacioPlan[0][0] > 0) {
      return 'ok';
    } else {
      $espacioExtrinseco = $this->buscarEspacioExtrinseco($datosInscripcion);
      //si la clasificacion del espacio es extrinseco en el proyecto
      if ($espacioExtrinseco[0]['CLASIFICACION'] == 4) {
        return 'ok';
      } else {
            //si el espacio es ofertado como extrinseco
            $espacioOfertadoExtrinseco=$this->buscarEspacioOfertadoExtrinseco($datosInscripcion);
            if (is_array($espacioOfertadoExtrinseco)&&$espacioOfertadoExtrinseco[0][0]==1)
            {
                return '4';
            }
        if (is_array($resultadoEspacioPlan)) {
          $resultado[0][0] = 'El espacio Académico no pertenece al plan de estudios del Estudiante.';
          return $resultado;
        } else {
          echo 'Fallo validar "Espacio plan de estudios estudiante"';
          exit;
        }
      }
    }
  }

  public function validarRequisitosCreditos($datosInscripcion) {
    $datosRequisitos = $datosInscripcion;
    $numero = $this->buscarCantidadRequisitosEspacioCreditos($datosInscripcion);
    if (is_array($numero)) {
      if ($numero[0][0] == 0) {
        return 'ok';
      } elseif ($numero[0][0] >= 1) {
        $requisitos = $this->buscarRequisitosEspacioCreditos($datosInscripcion);
        foreach ($requisitos as $key => $value) {
          if ($requisitos[$key]['PREVIO_APROBADO']==1){
          $datosRequisitos['codEspacio'] = $requisitos[$key]['CODREQUISITO'];
          $aprobado = $this->buscarDatosEspacioAprobado($datosRequisitos);
          if ($requisitos[$key]['CODREQUISITO'] == $aprobado[0]['CODIGO']) {
            
          } else {
            $requisito[$key] = array('REQUISITO' => $requisitos[$key]['CODREQUISITO']);
          }
        }
        else{
          $datosRequisitos['codEspacio'] = $requisitos[$key]['CODREQUISITO'];
          $aprobado = $this->buscarDatosEspacioCursado($datosRequisitos);
          if ($requisitos[$key]['CODREQUISITO'] == $aprobado[0]['CODIGO']) {

          } else {
            $requisito[$key] = array('REQUISITO' => $requisitos[$key]['CODREQUISITO']);
          }
        }
        }
        if (isset($requisito)) {
          return $requisito;
        } else {
          return 'ok';
        }
      } else {
        return "No se pueden encontrar datos de requisitos";
        exit;
      }
      unset($datosRequisitos);
    } else {
      return "No es posible consultar los requisitos";
      unset($datosRequisitos);
      exit;
    }
  }

  /**
   *
   * @param <type> $horarioGrupo
   * @param <type> $horarioEstudiante
   * @return <type>
   */
  public function compararHorarioEstudiante_horarioGrupo($horarioGrupo, $horarioEstudiante) {
      $cantidadHorario = count($horarioEstudiante);
    for ($n = 0; $n < $cantidadHorario; $n++) {
      //array_pop quita la ultima columna del arreglo
      $espacio = array_pop($horarioEstudiante[$n]);
      $espacio = array_pop($horarioEstudiante[$n]);
      $cantidadHorarioGrupo = count($horarioGrupo);
      for ($m = 0; $m < $cantidadHorarioGrupo; $m++) {
        if (($horarioGrupo[$m]) == ($horarioEstudiante[$n])) {
          //si hay cruce convierte $espacio en array
          $espacio = array('ESPACIOCRUCE' => $espacio);
          return $espacio;
        }
      }
    }
    return 'ok';
  }


  /**
   * Funcion que permite consultar los creditos (total y por clasificacion) inscritos por el estudiante
   * @param <array> $datosInscripcion
   * @return array/string
   */
  public function consultarCreditosPreinscritos($datosInscripcion) {
      $clasificacion=array();
      $numeroPreinscritos=$this->consultarNumeroEspaciosPreinscritos($datosInscripcion);
      if(is_array($numeroPreinscritos) && $numeroPreinscritos[0][0]>0){
      $creditosEstudiante = $this->consultarCreditosPreinscritosEstudiante($datosInscripcion);
      if (is_array($creditosEstudiante)) {
        $clasificaciones = $this->consultarClasificacionesEspacios();
        if (is_array($clasificaciones)) {
            foreach ($clasificaciones as $value) {
                $clasificacion['TOTAL']=0;
                $clasificacion[$value['CLASIFICACION']]=0;
            }
          foreach ($clasificaciones as $key => $value) {
            foreach ($creditosEstudiante as $clave => $valor) {
              if ($value['CODIGO'] == $valor['CLASIFICACION']) {
                $clasificacion['TOTAL']+=$valor['CREDITOS'];
                $clasificacion[$value['CLASIFICACION']]+=$valor['CREDITOS'];
              } else {
                $clasificacion['TOTAL']+=0;
                $clasificacion[$value['CLASIFICACION']]+=0;
              }
            }
          }
          return $clasificacion;
        } else {
          echo "Fallo al consultar clasificaciones";
          exit;
        }
      } else {
        echo "Fallo validar espacios incritos1";
        exit;
      }

      }else if(is_array($numeroPreinscritos) && $numeroPreinscritos[0][0]==0)
        {
          return 'ok';
        }else {
        echo "Fallo validar espacios incritos";
        exit;
        }
  }

  /**
   * Funcion que permite consultar los creditos (total y por clasificacion) inscritos por el estudiante
   * @param <array> $datosInscripcion
   * @return array/string
   */
  public function consultarTotalEspaciosPreinscritos($datosInscripcion) {
      $numeroPreinscritos=$this->consultarNumeroEspaciosPreinscritos($datosInscripcion);
      if(is_array($numeroPreinscritos) && $numeroPreinscritos[0][0]>0){
      $creditosEstudiante = $this->consultarParametrosPreinscripcion($datosInscripcion);
      if (is_array($creditosEstudiante)) {
        $clasificaciones = $this->consultarClasificacionesEspacios();
        if (is_array($clasificaciones)) {
            foreach ($clasificaciones as $value) {
                $clasificacion['TOTAL']=0;
                $clasificacion[$value['CLASIFICACION']]=0;
            }
          foreach ($clasificaciones as $key => $value) {
            foreach ($creditosEstudiante as $clave => $valor) {
              if ($value['CODIGO'] == $valor['CLASIFICACION']) {
                $clasificacion['TOTAL']+=$valor['CREDITOS'];
                $clasificacion[$value['CLASIFICACION']]+=$valor['CREDITOS'];
              } else {
                $clasificacion['TOTAL']+=0;
                $clasificacion[$value['CLASIFICACION']]+=0;
              }
            }
          }
          return $clasificacion;
        } else {
          echo "Fallo al consultar clasificaciones";
          exit;
        }
      } else {
        echo "Fallo validar espacios incritos1";
        exit;
      }

      }else if(is_array($numeroPreinscritos) && $numeroPreinscritos[0][0]==0)
        {
          return 'ok';
        }else {
        echo "Fallo validar espacios incritos";
        exit;
        }
  }






  /**
   * Funcion que permite consultar los creditos (total y por clasificacion) inscritos por el estudiante
   * @param <array> $datosInscripcion
   * @return array/string
   */
  public function consultarCreditosInscritos($datosInscripcion) {
      $clasificacion=array();
    $nroRegistrosHorarioEstudiante = $this->buscarNumeroRegistrosHorarioEstudiante($datosInscripcion);
    if ($nroRegistrosHorarioEstudiante[0][0] > 0) {
      $creditosEstudiante = $this->consultarCreditosInscritosEstudiante($datosInscripcion);
      if (is_array($creditosEstudiante)) {
        $clasificaciones = $this->consultarClasificacionesEspacios();
        if (is_array($clasificaciones)) {
            foreach ($clasificaciones as $value) {
                $clasificacion['TOTAL']=0;
                $clasificacion[$value['CLASIFICACION']]=0;
            }
          foreach ($clasificaciones as $key => $value) {
            foreach ($creditosEstudiante as $clave => $valor) {
              if ($value['CODIGO'] == $valor['CLASIFICACION']) {
                $clasificacion['TOTAL']+=$valor['CREDITOS'];
                $clasificacion[$value['CLASIFICACION']]+=$valor['CREDITOS'];
              } else {
                $clasificacion['TOTAL']+=0;
                $clasificacion[$value['CLASIFICACION']]+=0;
              }
            }
          }
          return $clasificacion;
        } else {
          echo "Fallo al consultar clasificaciones";
          exit;
        }
      } else {
        echo "Fallo validar espacios incritos";
        exit;
      }
    } elseif ($nroRegistrosHorarioEstudiante[0][0] == 0) {
      return 'ok';
    } else {
      echo 'Fallo validar creditos en el numero de registros';
      exit;
    }
  }

  
  /**
   * Funcion que verifica el cruce entre dos horarios enviados
   * @param array $horarioEstudiante DIA y HORA de cada registro de horario del estudiante
   * @param array $horarioGrupo DIA y HORA de cada registro de horario del grupo
   * @return int
   */
  function verificarCruceHorarios($horarioEstudiante,$horarioGrupo) {
      $cruce='ok';
      foreach ($horarioEstudiante as $horario1)
      {
          if(is_array($horarioGrupo))
          {
              foreach ($horarioGrupo as $horario2)
              {
                  if($horario1['DIA']==$horario2['DIA']&&$horario1['HORA']==$horario2['HORA'])
                  {
                      $cruce=1;
                  }
                  if($cruce==1)
                      break;
              }
          }else
            {
                $cruce=1;
            }
          if($cruce==1)
          break;
      }
    return $cruce;
  }
  /**
   * Esta funcion presenta el encabezado para los mensajes de confirmacion
   */
  public function encabezadoSistema() {
?>
    <table class="contenidotabla centrar">
      <tr>
        <td colspan="6" class="centrar">
          SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA<br>
          <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] . "/pequeno_universidad.png"; ?>"alt='UD' border='0'>
          <hr>
        </td>
      </tr>
    </table>
<?
  }

  public function datosEstudiante($datosEstudiante) {
    $cadena_sql = $this->cadena_sql("buscarInfoEstudiante", $datosEstudiante['codEstudiante']);
    return $resultado_datosEstudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
  
  public function buscarCodigoEstudiante($codEstudiante) {
    $cadena_sql = $this->cadena_sql("buscarCodigoEstudiante", $codEstudiante);
    return $resultado_codigoEstudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function proyectosCoordinador() {
    $cadena_sql_proyectos = $this->cadena_sql("proyectos_curriculares", $this->usuario);
    return $resultado_proyectos = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_proyectos, "busqueda");
  }

  /**
   * Funcion que obtiene el cupo de un grupo de espacio academico
   * @param <type> $datosGrupo (codEspacio,grupo)
   * @return <int>
   */
  public function buscarCupo($datosGrupo) {
    $resultado_cupo = $this->consultarCupo($datosGrupo);
    if (is_array($resultado_cupo) && is_numeric($resultado_cupo[0]['CUPO'])) {
      return $resultado_cupo[0]['CUPO'];
    } else {
      echo "No es posible obtener el cupo del grupo";
      exit;
    }
  }

  /**
   * Funcion que consulta cupo de un grupo
   * @param <type> $datosGrupo (codEspacio,grupo)
   * @return <array>
   */
  public function consultarCupo($datosGrupo) {
    $cadena_sql = $this->cadena_sql("cupo_grupo", $datosGrupo);
    return $resultado_cupo = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Este funcion permite buscar el numero de inscritos de un grupo de espacio academico
   *
   * @param <array> $this->configuracion
   * @param <array> $datosGrupo (codEspacio, grupo)
   * @return <int> cupo
   */
  public function buscarInscritos($datosGrupo) {
    $inscritos = $this->consultarInscritos($datosGrupo);
    if (is_array($inscritos) && is_numeric($inscritos[0]['INSCRITOS'])) {
      return $inscritos[0]['INSCRITOS'];
    } else {
      echo "No es posible obtener el numero de inscritos del grupo";
      exit;
    }
  }

  public function consultarInscritos($datosGrupo) {
    $cadena_sql = $this->cadena_sql("cupo_grupo_ins", $datosGrupo);
    return $resultado_inscritosGrupo = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar el estado de un estudiante
   * @param <type> $codEstudiante
   * @return <type>
   */
  public function buscarEstadoEstudiante($codEstudiante) {

    $cadena_sql = $this->cadena_sql("estado_estudiante", $codEstudiante);
    return $resultado_estudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar las veces que un espacio se encuentra inscrito para un estudiante.
   *
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarSiEspacioInscrito($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_revisaEspacioInscrito", $datosInscripcion);
    //echo "<br>validar espacio ".$cadena_sql;exit;
    return $resultado_espacioInscrito = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los datos de un espacio inscrito a un estudiante
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarDatosEspacioInscrito($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_espacioInscrito", $datosInscripcion);
    return $resultado_espacioInscrito = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que prmite consultar si un espacio ha sido aprobado por un estudiante.
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarSiEspacioAprobado($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_revisarEspacioAprobado", $datosInscripcion);
    return $resultado_espacioAprobado = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que prmite consultar si un espacio ha sido aprobado por un estudiante.
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarSiEspacioReprobado($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_revisarEspacioReprobado", $datosInscripcion);
    return $resultado_espacioReprobado = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que prmite consultar si un espacio ha sido aprobado por un estudiante.
   * @param <type> $datosNovedad (codEspacio,ano,periodo,nota,obs,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,tipo_est,
   *                              action,opcion,codProyecto,planEstudio,proyectosEspacio(1|Obligatorio Complementario|2|0|1|SANEAMIENTO AMBIENTAL|2|85|246|1|85),
   *                              nivel,creditos,htd,htc,hta,clasificacion,retornoPagina,retornoOpcion,
   *                              retornoParametros=(codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante))
   * @return <type>
   */
  public function buscarNotaAnoPeriodo($datosNovedad) {

    $cadena_sql = $this->cadena_sql("consultar_notaAnoPeriodo", $datosNovedad);
    return $resultado_registroNota = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }


  /**
   * Funcion que permite consultar la cantidad de requisitos para un espacio a inscribir
   * @param <array> $datosInscripcion
   * @return <array>
   */
  public function buscarCantidadRequisitosEspacio($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_numeroRequisitosEspacio", $datosInscripcion);
    return $resultado_numeroRequisitosEspacio = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar la cantidad de requisitos para un espacio de creditos a inscribir
   * @param <array> $datosInscripcion
   * @return <array>
   */
  public function buscarCantidadRequisitosEspacioCreditos($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_numeroRequisitosCreditos", $datosInscripcion);
    return $resultado_numeroRequisitosCreditos = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los requisitos para un espacio a inscribir
   * @param <array> $datosInscripcion
   * @return <array>
   */
  public function buscarRequisitosEspacio($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_requisitosEspacio", $datosInscripcion);
    return $resultado_requisitosEspacio = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los requisitos para un espacio a inscribir
   * @param <array> $datosInscripcion
   * @return <array>
   */
  public function buscarRequisitosEspacioCreditos($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_requisitosEspacioCreditos", $datosInscripcion);
    return $resultado_requisitosEspacio = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que busca los datos de un espacio que ha sido aprobado por un estudiante
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarDatosEspacioAprobado($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_EspacioAprobado", $datosInscripcion);
    return $resultado_espacioAprobado = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que busca los datos de un espacio que ha sido cursado por un estudiante
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarDatosEspacioCursado($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_EspacioCursado", $datosInscripcion);
    return $resultado_espacioAprobado = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que busca los datos de un espacio que ha sido rerobado por un estudiante
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarDatosEspacioReprobado($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_EspacioReprobado", $datosInscripcion);
    return $resultado_espacioReprobado = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que consulta el horario de un grupo
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarHorarioGrupo($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("horario_grupo", $datosInscripcion);
    return $resultado_horarios_nuevo = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function buscarNumeroRegistrosHorarioEstudiante($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_nroRegistrosHorarioEstudiante", $datosInscripcion);
    return $resultado_nroRegistrosHorarioEstudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que consulta el registrado para el estudiante
   * @param <type> $datosInscripcion
   * @return <type>
   */
  public function buscarHorarioEstudiante($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("horario_estudiante", $datosInscripcion);
    return $resultado_horario_estudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function consultarCancelado($datosInscripcion) {
    $cadena_sql = $this->cadena_sql("buscar_cancelado", $datosInscripcion);
    return $resultado_cancelado = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, 'busqueda');
  }

  public function buscarEspaciosPlanEstudiante($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_espaciosPlanEstudiante", $datosInscripcion);
    return $resultado_espacioPlan = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function buscarEspacioExtrinseco($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_espacioComoExtrinseco", $datosInscripcion);
    return $resultado_espacioExtrinseco = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function buscarEspacioOfertadoExtrinseco($datosInscripcion) {

    $cadena_sql = $this->cadena_sql("consultar_espacioOfertadoComoExtrinseco", $datosInscripcion);
    return $resultado_espacioOfertadoExtrinseco = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, "busqueda");
  }

  public function consultarCreditosInscritosEstudiante($datosInscripcion) {
    $cadena_sql = $this->cadena_sql("consultar_creditosInscritosEstudiante", $datosInscripcion);
    return $resultadoCreditosEstudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function consultarNumeroEspaciosInscritosEstudiante($datosInscripcion) {
    $cadena_sql = $this->cadena_sql("consultarNumeroEspaciosInscritosEstudiante", $datosInscripcion);
    return $resultadoCreditosEstudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function consultarCreditosPreinscritosEstudiante($datosInscripcion) {
    $cadena_sql = $this->cadena_sql("consultar_creditosPreinscritosEstudiante", $datosInscripcion);
    return $resultadoCreditosEstudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function consultarClasificacionesEspacios() {
    $cadena_sql = $this->cadena_sql("consultar_clasificacionesEspacios", "");
    return $resultadoCreditosEstudiante = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function consultarCreditosPlan($datosInscripcion) {
    $cadena_sql = $this->cadena_sql("buscar_parametrosPlan", $datosInscripcion);
    return $resultadoCreditosPlan = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql, 'busqueda');
  }

  public function consultarClasificacionEspacio($datosInscripcion) {
    $cadena_sql=$this->cadena_sql('buscarEspacio',$datosInscripcion);
    return $resultado_clasificacion=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  public function consultarAprobadosClasificacion($datosInscripcion){
    $cadena_sql=$this->cadena_sql('buscarAprobadosClasificacion',$datosInscripcion);
    return $resultado_clasificacion=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

  }

  /**
   * Funcion que permite consultar los espacios preinscritos que han sido cancelados
   * @param <array> $variablesInscritos (codEstudiante,ano,periodo,planEstudioEstudiante)
   * @return <array>
   */
  public function consultarEspaciosPreinscritosCancelados($variablesInscritos) {
      $cadena_sql=$this->cadena_sql("consultaPreinscripcionesCanceladasEstudiante", $variablesInscritos);
      return $resultadoCancelados = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los espacios preinscritos
   * @param <array> $variablesInscritos (codEstudiante,ano,periodo,planEstudioEstudiante)
   * @return <array>
   */
  public function consultarEspaciosPreinscritos($variablesInscritos) {
      $cadena_sql = $this->cadena_sql("consultaPreinscripcionesEstudiante", $variablesInscritos);
      return $resultadoPreinscritos = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los espacios preinscritos
   * @param <array> $variablesInscritos (codEstudiante,ano,periodo,planEstudioEstudiante)
   * @return <array>
   */
  public function consultarNumeroEspaciosPreinscritos($variablesInscritos) {
      $cadena_sql = $this->cadena_sql("consultaNumeroPreinscripcionesEstudiante", $variablesInscritos);
      return $resultadoNumeroPreinscritos = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite consultar los parametros del proyecto para horas
   * @param <array> $variablesInscritos (codEstudiante,ano,periodo,planEstudioEstudiante)
   * @return <array>
   */
  public function consultarParametrosPreinscripcion($variablesInscritos) {
      $cadena_sql = $this->cadena_sql("consultaParametrosPreinscripcionesProyecto", $variablesInscritos);
      return $resultadoNumeroPreinscritos = $this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

  /**
   * Funcion que permite validar si un estudiante es de primer semestre
   * @param <array> $datosInscripcion (codEstudiante,ano,periodo)
   * @return <varchar>
   */
  public function validarEstudiantePrimerSemestre($datosInscripcion)
    {

        if ($datosInscripcion['periodo']==3)
        {
          $inicioCodigoEstudiante=$datosInscripcion['ano'].'2';
        }else
        {
          $inicioCodigoEstudiante=$datosInscripcion['ano'].$datosInscripcion['periodo'];
        }
        if ($inicioCodigoEstudiante==substr($datosInscripcion['codEstudiante'], '0', '5'))
        {
            $primiparo = 'ok';
        }
        else{
            $primiparo = 'no';
        }
        return $primiparo;
    }//fin funcion validarEstudiantePrimerSemestre


    /**
     * Funcion para validar que la cantidad de espacios inscritos no superen el  maximo permitido
     * @param type $datosInscripcion (codEstudiante,ano,periodo)
     * @return string 
     */
    public function validarMaxCantidadEspacios($datosInscripcion) {
        $cantidad_espacios_permitidos = 2;
        if (is_null($datosInscripcion['codEstudiante'])) {
            $resultado[0][0] = "Fallo validar maximo cantidad de espacios ";
            return $resultado;
        } else {
            $resultadoCantidadEspaciosInscritos = $this->consultarNumeroEspaciosInscritosEstudiante($datosInscripcion);
            if (is_array($resultadoCantidadEspaciosInscritos) && trim($resultadoCantidadEspaciosInscritos[0][0]) < $cantidad_espacios_permitidos) {
                return 'ok';
            } else {
                return $resultadoCantidadEspaciosInscritos;
            }
        }
  }
  
 
  public function validarMaxCantidadHorasDiarias($datosInscripcion) {
        $cantidadHorasPermitidas = 6;
        if (is_null($datosInscripcion['codEstudiante'])) {
            $resultado[0][0] = "Fallo validar maximo cantidad de horas por semana ";
            return $resultado;
        } else {
            $horarioEstudiante = $this->buscarHorarioEstudiante($datosInscripcion);
            $horarioGrupo = $this->buscarHorarioGrupo($datosInscripcion);
            
            $validacionHorasDia = $this->validaSumaHorasPorDiasDeHorarios($horarioEstudiante,$horarioGrupo,$cantidadHorasPermitidas);
            echo $validacionHorasDia;
            if ($validacionHorasDia=='ok'){
                return 'ok';
            } else {
                return $resultadoCantidadEspaciosInscritos;
            }
        }
  }
  
  
  
  function validaSumaHorasPorDiasDeHorarios($horarioEstudiante,$horarioGrupo,$cantidadHorasPermitidas){
      $horas_validas=0;
      for ($i=1;$i<7;$i++){
          $horasEstudiante=0;
          $horasGrupo=0;
          if(is_array($horarioEstudiante)) { 
                foreach ($horarioEstudiante as $key => $hEstudiante) {
                        if($hEstudiante['DIA']==$i){
                            $horasEstudiante++;
                        }
                } 
          }
          if(is_array($horarioEstudiante)) { 
                foreach ($horarioGrupo as $key => $hGrupo) {
                    if($hGrupo['DIA']==$i){
                        $horasGrupo++;
                    }
                }
          }
           $total_horas_dia =   $horasEstudiante+$horasGrupo;
                      if($total_horas_dia>$cantidadHorasPermitidas){
               $horas_validas=1;
           }
      }
      if($horas_validas==1){
          return 'Horas por día no validas';
      
      }else{
          return 'ok';
      }
     
  }
  
  
  /**
   * Esta funcion construye las cadenas SQL para ejecutar
   * @param <string> $tipo
   * @param <array> $variable
   */
  public function cadena_sql($tipo, $variable) {
    switch ($tipo) {
      case 'buscarInfoEstudiante':

        $cadena_sql= "SELECT est_ind_cred INDICA_CREDITOS,";
        $cadena_sql.=" est_cra_cod PROYECTO_ESTUDIANTE,";
        $cadena_sql.=" est_pen_nro PLAN_ESTUDIANTE";
        $cadena_sql.=" FROM acest ";
        $cadena_sql.=" WHERE est_cod=" . $variable;
        break;

    case 'buscarCodigoEstudiante':

        $cadena_sql=" SELECT est_cod";
        $cadena_sql.=" FROM acest ";
        $cadena_sql.=" WHERE est_cod=" . $variable;
        break;

      case "estado_estudiante":

        $cadena_sql= "SELECT estado_cod CODIGO_ESTADO,";
        $cadena_sql.=" estado_nombre NOMBRE,";
        $cadena_sql.=" estado_descripcion DESCRIPCION";
        $cadena_sql.=" FROM acest";
        $cadena_sql.=" INNER JOIN acestado ON acest.est_estado_est=acestado.estado_cod";
        $cadena_sql.=" WHERE est_cod=" . $variable;
        break;

      case 'proyectos_curriculares':

        $cadena_sql= "SELECT DISTINCT cra_cod PROYECTO,";
        $cadena_sql.=" cra_abrev NOMBRE,";
        $cadena_sql.=" ctp_pen_nro PLAN,";
        $cadena_sql.=" tra_nivel NIVEL,";
        $cadena_sql.=" tra_cod_nivel CODIGONIVEL,";
        $cadena_sql.=" ctp_ind_cred CREDITOS";
        $cadena_sql.=" FROM ACCRA";
        $cadena_sql.=" INNER JOIN V_CRA_TIP_PEN ON CTP_CRA_COD=CRA_COD";
        $cadena_sql.=" INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD";
        $cadena_sql.=" WHERE CRA_EMP_NRO_IDEN=" . $variable;
        //$cadena_sql.=" AND (CTP_IND_CRED NOT LIKE '%N%'OR TRA_COD_NIVEL NOT IN (2,3,4))";
        //$cadena_sql.=" AND (CTP_IND_CRED NOT LIKE '%S%' OR TRA_COD_NIVEL !=1)";
        //                $cadena_sql.=" AND TRA_COD_NIVEL IN (1,2,3,4)";//para pregrado es 1. Se cambia tambien en Validaciones, proyectos del coordinador
        //                $cadena_sql.=" OR TRA_COD_NIVEL IS NULL)";
        $cadena_sql.=" ORDER BY CODIGONIVEL, PROYECTO, PLAN";

        break;

      case 'horario_grupo':

        $cadena_sql=" SELECT DISTINCT hor_dia_nro DIA,";
        $cadena_sql.=" hor_hora HORA";
        $cadena_sql.=" FROM achorario_2012 horario";
        $cadena_sql.=" INNER JOIN accurso ON horario.hor_asi_cod=accurso.cur_asi_cod AND horario.hor_nro=accurso.cur_nro AND horario.hor_ape_ano=accurso.cur_ape_ano AND horario.hor_ape_per=accurso.cur_ape_per ";
        $cadena_sql.=" INNER JOIN gesalon_2012 ON hor_sal_id_espacio=sal_id_espacio";
        $cadena_sql.=" INNER JOIN gesede ON sal_sed_id=sed_id";
        $cadena_sql.=" WHERE cur_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" AND cur_cra_cod=" . $variable['codProyecto'];
        $cadena_sql.=" AND hor_ape_ano=" . $this->ano;
        $cadena_sql.=" AND hor_ape_per=" . $this->periodo;
        $cadena_sql.=" AND hor_nro=" . $variable['grupo'];
        $cadena_sql.=" ORDER BY 1,2";

        break;  

      case 'horario_estudiante':

        $cadena_sql= "SELECT DISTINCT hor_dia_nro DIA,";
        $cadena_sql.=" hor_hora HORA,";
        $cadena_sql.=" ins_asi_cod CODIGO";
        $cadena_sql.=" FROM achorario_2012 horario";
        $cadena_sql.=" INNER JOIN acins ON horario.hor_asi_cod=acins.ins_asi_cod AND horario.hor_nro=acins.ins_gr";
        $cadena_sql.=" AND horario.hor_ape_ano=acins.ins_ano AND horario.hor_ape_per=acins.ins_per";
        $cadena_sql.=" WHERE acins.ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $this->ano;
        $cadena_sql.=" AND ins_per=" . $this->periodo;
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        $cadena_sql.=" AND ins_asi_cod!=" . $variable['codEspacio'];
        $cadena_sql.=" ORDER BY 1,2";
        break;

      case 'consultar_nroRegistrosHorarioEstudiante':

        $cadena_sql= "SELECT count(*) ";
        $cadena_sql.=" FROM achorario_2012 horario";
        $cadena_sql.=" INNER JOIN acins ON horario.hor_asi_cod=acins.ins_asi_cod AND horario.hor_nro=acins.ins_gr";
        $cadena_sql.=" AND horario.hor_ape_ano=acins.ins_ano AND horario.hor_ape_per=acins.ins_per";
        $cadena_sql.=" WHERE acins.ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $this->ano;
        $cadena_sql.=" AND ins_per=" . $this->periodo;
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        $cadena_sql.=" AND ins_asi_cod!=" . $variable['codEspacio'];
        break;

      case "consultar_revisaEspacioInscrito":

        $cadena_sql= "SELECT count(*)";
        $cadena_sql.=" FROM acins";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" AND ins_ano=" . $this->ano;
        $cadena_sql.=" AND ins_per=" . $this->periodo;
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        break;

      case "consultar_creditosInscritosEstudiante":

        $cadena_sql= "SELECT ins_cred CREDITOS,";
        $cadena_sql.=" ins_cea_cod CLASIFICACION";
        $cadena_sql.=" FROM acins";
        $cadena_sql.=" WHERE ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_ano=" . $this->ano;
        $cadena_sql.=" AND ins_per=" . $this->periodo;
        $cadena_sql.=" AND ins_estado LIKE '%A%'";
        break;

      case "consultar_creditosPreinscritosEstudiante":

        $cadena_sql= "SELECT insde_cred CREDITOS,";
        $cadena_sql.=" insde_cea_cod CLASIFICACION";
        $cadena_sql.=" FROM acinsdemanda";
        $cadena_sql.=" WHERE insde_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND insde_ano=" . $variable['ano'];
        $cadena_sql.=" AND insde_per=" . $variable['periodo'];
        $cadena_sql.=" AND insde_estado LIKE '%A%'";
        break;

      case "consultar_espacioInscrito":

        $cadena_sql= "SELECT";
        $cadena_sql.=" ins_cra_cod PROYECTO,";
        $cadena_sql.=" ins_gr GRUPO";
        $cadena_sql.=" FROM acins";
        $cadena_sql.=" INNER JOIN accurso";
        $cadena_sql.=" ON ins_asi_cod= cur_asi_cod AND ins_gr= cur_nro and ins_ano= cur_ape_ano AND ins_per= cur_ape_per";
        $cadena_sql.=" WHERE";
        $cadena_sql.=" ins_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND ins_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" AND ins_ano=" . $this->ano;
        $cadena_sql.=" AND ins_per=" . $this->periodo;
        break;

      case 'consultar_espaciosPlanEstudiante':

        $cadena_sql= "SELECT COUNT(*)";
        $cadena_sql.=" FROM acpen";
        $cadena_sql.=" where pen_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" and pen_nro= " . $variable['planEstudioEstudiante'];
        $cadena_sql.=" and pen_cra_cod= " . $variable['codProyectoEstudiante'];
        $cadena_sql.=" and pen_estado LIKE '%A%'";
        break;

      case 'consultar_espacioComoExtrinseco':

        $cadena_sql= "SELECT clp_cea_cod CLASIFICACION";
        $cadena_sql.=" FROM acclasificacpen";
        $cadena_sql.=" WHERE clp_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" AND clp_pen_nro= " . $variable['planEstudio'];
        $cadena_sql.=" AND clp_cra_cod= " . $variable['codProyecto'];
        $cadena_sql.=" AND clp_cea_cod=4";
        $cadena_sql.=" AND clp_estado LIKE '%A%'";
        break;

      case 'consultar_espacioOfertadoComoExtrinseco':

        $cadena_sql=" SELECT `ofrecido_portafolio`";
        $cadena_sql.=" FROM `" . $this->configuracion['prefijo'] . "planEstudio_espacio`";
        $cadena_sql.=" WHERE `id_planEstudio`= " . $variable['planEstudio'];
        $cadena_sql.=" AND `id_espacio`=" . $variable['codEspacio'];
        $cadena_sql.=" AND `ofrecido_portafolio` =1";
        break;

      case "periodoActual":

        $cadena_sql= "SELECT ape_ano ANO,";
        $cadena_sql.=" ape_per PERIODO";
        $cadena_sql.=" FROM acasperi";
        $cadena_sql.=" WHERE ape_estado like '%V%'";
        break;

      case "consultar_revisarEspacioAprobado":
        $cadena_sql= "SELECT count(*)";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND (not_nota>=(SELECT cra_nota_aprob FROM accra WHERE cra_cod=" . $variable['codProyectoEstudiante'] . ")";
        $cadena_sql.=" OR not_obs=19)";
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        $cadena_sql.=" AND not_asi_cod=" . $variable['codEspacio'];

        break;

      case "consultar_notaAnoPeriodo":
        $cadena_sql= "SELECT count(*)";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND not_ano =" . $variable['ano'];
        $cadena_sql.=" AND not_per =" . $variable['periodo'];
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        $cadena_sql.=" AND not_asi_cod=" . $variable['codEspacio'];

        break;

      case "consultar_revisarEspacioReprobado":
        $cadena_sql= "SELECT count(*)";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND (not_nota<(SELECT cra_nota_aprob FROM accra WHERE cra_cod=" . $variable['codProyectoEstudiante'] . ")";
        $cadena_sql.=" OR not_obs=20)";
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        $cadena_sql.=" AND not_asi_cod=" . $variable['codEspacio'];

        break;

      case "consultar_EspacioAprobado":
        $cadena_sql= "SELECT not_asi_cod CODIGO,";
        $cadena_sql.=" not_ano ANO,";
        $cadena_sql.=" not_per PERIODO";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND (not_nota>=(SELECT cra_nota_aprob FROM accra WHERE cra_cod=" . $variable['codProyectoEstudiante'] . ")";
        $cadena_sql.=" OR not_obs=19)";
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        $cadena_sql.=" AND not_asi_cod=" . $variable['codEspacio'];

        break;

      case "consultar_EspacioCursado":
        $cadena_sql= "SELECT not_asi_cod CODIGO,";
        $cadena_sql.=" not_ano ANO,";
        $cadena_sql.=" not_per PERIODO";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        $cadena_sql.=" AND not_asi_cod=" . $variable['codEspacio'];

        break;

      case "consultar_EspacioReprobado":
        $cadena_sql= "SELECT not_asi_cod CODIGO,";
        $cadena_sql.=" not_ano ANO,";
        $cadena_sql.=" not_per PERIODO";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=" . $variable['codEstudiante'];
        $cadena_sql.=" AND (not_nota<(SELECT cra_nota_aprob FROM accra WHERE cra_cod=" . $variable['codProyectoEstudiante'] . ")";
        $cadena_sql.=" OR not_obs=20)";
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        $cadena_sql.=" AND not_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" ORDER BY ";

        break;

      case "buscar_cancelado":
        $cadena_sql= "select horario_idEspacio ESPACIO,";
        $cadena_sql.=" horario_estado ESTADO";
        $cadena_sql.=" from sga_horario_estudiante";
        $cadena_sql.=" where horario_codEstudiante=" . $variable['codEstudiante'];
        $cadena_sql.=" and horario_ano=" . $this->ano;
        $cadena_sql.=" and horario_periodo=" . $this->periodo;
        $cadena_sql.=" and horario_idEspacio=" . $variable['codEspacio'];
        $cadena_sql.=" and horario_estado=3";
        break;

      case "buscar_parametrosPlan":
        $cadena_sql= "SELECT parametro_maxCreditosNivel MAX_PERIODO,";
        $cadena_sql.=" parametros_OB OB,";
        $cadena_sql.=" parametros_OC OC,";
        $cadena_sql.=" parametros_EI EI,";
        $cadena_sql.=" parametros_EE EE,";
        $cadena_sql.=" parametros_CP CP";
        $cadena_sql.=" FROM " . $this->configuracion['prefijo'] . "parametro_plan_estudio ";
        $cadena_sql.=" WHERE parametro_idPlanEstudio =" . $variable['planEstudioEstudiante'];
        break;

      case 'cupo_grupo':
        $cadena_sql= "SELECT cur_nro_cupo CUPO,";
        $cadena_sql.=" cur_cra_cod CARRERA";
        $cadena_sql.=" FROM accurso";
        $cadena_sql.=" WHERE cur_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" and cur_ape_ano=" . $this->ano;
        $cadena_sql.=" and cur_ape_per=" . $this->periodo;
        $cadena_sql.=" and cur_nro=" . $variable['grupo'];
        $cadena_sql.=" and cur_estado LIKE '%A%'";
        break;

      case 'cupo_grupo_ins':
        $cadena_sql= "SELECT count(*) INSCRITOS";
        $cadena_sql.=" FROM acins";
        $cadena_sql.=" WHERE ins_ano=" . $this->ano;
        $cadena_sql.=" AND ins_per=" . $this->periodo;
        $cadena_sql.=" AND ins_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" AND ins_gr=" . $variable['grupo'];
        break;

      case 'consultar_numeroRequisitosEspacio':
        $cadena_sql= "SELECT count(*)";
        $cadena_sql.=" FROM acreq";
        $cadena_sql.=" WHERE req_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" AND req_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" AND req_pen_nro=" . $variable['planEstudioEstudiante'];
        $cadena_sql.=" AND req_estado LIKE '%A%'";
        break;

      case 'consultar_requisitosEspacio':
        $cadena_sql= "SELECT req_asi_cod CODESPACIO,";
        $cadena_sql.=" req_cod CODREQUISITO,";
        $cadena_sql.=" asi_nombre NOMBREREQUISITO,";
        $cadena_sql.=" req_sem NIVEL,";
        $cadena_sql.=" req_ind_req REQUISITO";
        $cadena_sql.=" FROM acreq";
        $cadena_sql.=" INNER JOIN acasi ON asi_cod=req_cod";
        $cadena_sql.=" WHERE req_asi_cod=" . $variable['codEspacio'];
        $cadena_sql.=" AND req_cra_cod=" . $variable['codProyectoEstudiante'];
        $cadena_sql.=" AND req_pen_nro=" . $variable['planEstudioEstudiante'];
        $cadena_sql.=" AND req_estado LIKE '%A%'";
        break;

      case 'consultar_clasificacionesEspacios':
        $cadena_sql= "SELECT cea_cod CODIGO,";
        $cadena_sql.=" cea_abr CLASIFICACION";
        $cadena_sql.=" FROM geclasificaespac";
        break;

      case 'buscarEspacio':
        $cadena_sql="SELECT cea_cod CODIGO,";
        $cadena_sql.=" cea_abr CLASIFICACION";
        $cadena_sql.=" FROM acclasificacpen";
        $cadena_sql.=" INNER JOIN geclasificaespac ON clp_cea_cod=cea_cod";
        $cadena_sql.=" WHERE clp_asi_cod=".$variable['codEspacio'];
        $cadena_sql.=" AND clp_cra_cod=".$variable['codProyecto'];
        $cadena_sql.=" AND clp_pen_nro=".$variable['planEstudio'];
        $cadena_sql.=" AND clp_estado LIKE '%A%'";
        break;

      case 'buscarAprobadosClasificacion':
        $cadena_sql="SELECT sum(not_cred) APROBADOS";
        $cadena_sql.=" FROM acnot";
        $cadena_sql.=" WHERE not_est_cod=".$variable['codEstudiante'];
        $cadena_sql.=" AND not_cea_cod=".$variable['clasificacion'];
        $cadena_sql.=" AND not_nota>=(SELECT cra_nota_aprob FROM accra WHERE cra_cod=" . $variable['codProyectoEstudiante'] . ")";
        $cadena_sql.=" AND not_est_reg LIKE '%A%'";
        break;

      case 'consultar_requisitosEspacioCreditos':
        $cadena_sql="SELECT requisitos_previoAprobado PREVIO_APROBADO,";
        $cadena_sql.=" requisitos_idEspacioPrevio CODREQUISITO,";
        $cadena_sql.=" requisitos_idEspacioPosterior CODESPACIO,";
        $cadena_sql.=" requisitos_idPlanEstudio PLAN_ESTUDIO";
        $cadena_sql.=" FROM ".$this->configuracion['prefijo']."requisitos_espacio_plan_estudio";
        $cadena_sql.=" WHERE requisitos_idPlanEstudio='".$variable['planEstudioEstudiante']."'";
        $cadena_sql.=" AND requisitos_idEspacioPosterior='".$variable['codEspacio']."'";
        break;

      case 'consultar_numeroRequisitosCreditos':
        $cadena_sql="SELECT count(*)";
        $cadena_sql.=" FROM ".$this->configuracion['prefijo']."requisitos_espacio_plan_estudio";
        $cadena_sql.=" WHERE requisitos_idPlanEstudio='".$variable['planEstudioEstudiante']."'";
        $cadena_sql.=" AND requisitos_idEspacioPosterior='".$variable['codEspacio']."'";
        break;

    case 'consultaPreinscripcionesCanceladasEstudiante':
        $cadena_sql= "SELECT insde_est_cod COD_ESTUDIANTE,";
        $cadena_sql.= " insde_asi_cod ASI_CODIGO";
        $cadena_sql.= " FROM acinsdemanda";
        $cadena_sql.= " WHERE insde_est_cod=".$variable['codEstudiante'];
        $cadena_sql.= " AND insde_ano=".$variable['ano'];
        $cadena_sql.= " AND insde_per=".$variable['periodo'];
        $cadena_sql.= " AND insde_estado LIKE '%I%'";
        $cadena_sql.= " AND insde_cra_cod=".$variable['codProyectoEstudiante'];
        $cadena_sql.= " AND insde_asi_cod=".$variable['codEspacio'];
        break;

    case 'consultaPreinscripcionesEstudiante':
        $cadena_sql= "SELECT insde_est_cod COD_ESTUDIANTE,";
        $cadena_sql.= " insde_asi_cod ASI_CODIGO";
        $cadena_sql.= " FROM acinsdemanda";
        $cadena_sql.= " WHERE insde_est_cod=".$variable['codEstudiante'];
        $cadena_sql.= " AND insde_ano=".$variable['ano'];
        $cadena_sql.= " AND insde_per=".$variable['periodo'];
        $cadena_sql.= " AND insde_estado LIKE '%A%'";
        $cadena_sql.= " AND insde_cra_cod=".$variable['codProyectoEstudiante'];
        $cadena_sql.= " AND insde_asi_cod=".$variable['codEspacio'];
        break;

    case 'consultaNumeroPreinscripcionesEstudiante':
        $cadena_sql= "SELECT count(*)";
        $cadena_sql.= " FROM acinsdemanda";
        $cadena_sql.= " WHERE insde_est_cod=".$variable['codEstudiante'];
        $cadena_sql.= " AND insde_ano=".$variable['ano'];
        $cadena_sql.= " AND insde_per=".$variable['periodo'];
        $cadena_sql.= " AND insde_estado LIKE '%A%'";
        $cadena_sql.= " AND insde_cra_cod=".$variable['codProyectoEstudiante'];
        break;

    case 'consultarNumeroEspaciosInscritosEstudiante':
        $cadena_sql= "SELECT count(*)";
        $cadena_sql.= " FROM acins";
        $cadena_sql.= " WHERE ins_est_cod=".$variable['codEstudiante'];
        $cadena_sql.= " AND ins_ano=".$this->ano;
        $cadena_sql.= " AND ins_per=".$this->periodo;
        $cadena_sql.= " AND ins_estado LIKE '%A%'";
        $cadena_sql.= " AND ins_cra_cod=".$variable['codProyectoEstudiante'];
        break;

    
    }
    return $cadena_sql;
  }

}
?>
