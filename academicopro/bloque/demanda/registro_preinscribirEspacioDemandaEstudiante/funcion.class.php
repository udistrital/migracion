<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//@ Clase que permite realizar la inscripcion de un espacio academico por busqueda a un estudiante
class funcion_registro_preinscribirEspacioDemandaEstudiante extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;
    private $arregloPreinscripcion;
    private $codEstudiante;
    private $datosEstudiante;
    private $espaciosPreinscritos;
    private $espaciosPorCursar;
    private $datosEspacio;
    private $parametros;
    private $aprobados;
    private $noInscritos;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;
        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

        //Conexion ORACLE
        if ($this->nivel==51){
          $this->accesoOracle = $this->conectarDB($configuracion,"estudiante");
          $this->codEstudiante=$this->rescatarValorSesion($configuracion,$this->acceso_db,"id_usuario");
        }
        elseif ($this->nivel==52){
          $this->accesoOracle = $this->conectarDB($configuracion, "estudianteCred");
          $this->codEstudiante=$this->rescatarValorSesion($configuracion,$this->acceso_db,"id_usuario");
        }

        //Datos de sesion
        $this->formulario="registro_adicionarEspacioEstudianteCoorHoras";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
        $this->arregloPreinscripcion=array();

    }

    /**
     * Funcion que valida la inscripcion de un estudiante en un espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function verificarPreinscripcion()
    {
        $_REQUEST['ano']=$this->ano;
        $_REQUEST['periodo']=$this->periodo;
        $datos=$_REQUEST;        

       if ($this->usuario!=$_REQUEST['codEstudiante'])
        {
            echo "Ha ocurrido un error. Por favor inicie sesion nuevamente ";exit;
        }

        $b=(count($_REQUEST)-9);
        $this->datosEstudiante=$this->consultarDatosEstudiante($this->usuario);
        $this->espaciosPreinscritos=$this->buscarEspaciosPreinscritos();
        $this->parametros=json_decode($this->datosEstudiante[0]['PARAMETROS'],true);
        $this->aprobados=json_decode($this->datosEstudiante[0]['CREDITOS_APROBADOS'],true);
        $a=0;
        //hacer foreach
//        $datosInscripcion['NOMBRE']=$this->consultarNombreEspacio();
//        if ($_REQUEST['clasificacion']!=4)
//            {
//                $datosInscripcion['codProyecto']=$datosInscripcion['codProyectoEstudiante'];
//                $datosInscripcion['planEstudio']=$datosInscripcion['planEstudioEstudiante'];
//            }
        

        if (trim($this->datosEstudiante[0]['ESTADO'])=='V'||trim($this->datosEstudiante[0]['ESTADO'])=='J'||trim($this->datosEstudiante[0]['ESTADO'])=='A'||trim($this->datosEstudiante[0]['ESTADO'])=='B')
        {
          if(trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE'])=='S')
          {
              for ($a=0;$a<$b;$a++) {
                $this->buscarDatosEspacio($_REQUEST['espacio'.$a]);
                $this->verificarCreditos($this->parametros['maxcreditos']);
                $this->verificarCreditosPorClasificacion();
                $this->verificarPreinscrito();
                if(isset($this->noInscritos)&&in_array($this->datosEspacio['CODIGO'],$this->noInscritos))
                {}else
                    {
                        $this->crearArregloPreinscripcionCreditos();
                    }
              }
          }
          elseif(trim($_REQUEST['tipoEstudiante'])=='N')
            {
              for ($a=0;$a<$b;$a++) {
                $this->buscarDatosEspacio($_REQUEST['espacio'.$a]);
                $this->verificarMaximoEspacios((isset($this->parametros['maxespacios'])?$this->parametros['maxespacios']:''));
                $this->verificarPreinscrito();
                if(isset($this->noInscritos)&&in_array($this->datosEspacio['CODIGO'],$this->noInscritos))
                {}else
                    {
                        $this->crearArregloPreinscripcionHoras();
                    }
              }
            }
            $this->inscribirEstudiante();
        }else
          {
            $retorno['mensaje']="El estado del estudiante no permite realizar preinscripciones (".$_REQUEST['estado_est'].")";
            $this->enlaceNoAdicion($retorno);
          }
    }

    /**
     * Funcion que valida si el espacio ya ha sido inscrito
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */

    function verificarPreinscrito() {
      //verifica si el espacio ya ha sido inscrito
        $inscrito='ok';
        if(is_array($this->espaciosPreinscritos))
        {
            foreach ($this->espaciosPreinscritos as $inscritos)
            {
                if($inscritos['ASI_CODIGO']==$this->datosEspacio['CODIGO'])
                {
                    $inscrito=$inscritos['CODIGO'];
                    break;
                }
            }
        }
        if($inscrito!='ok')
        {
            $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//            $retorno['mensaje']="El espacio académico ya esta preinscrito para el periodo actual. No se puede preinscribir de nuevo.";
//            $this->enlaceNoAdicion($retorno);
        }
    }
    
    /**
     * Funcion que verifica requsitos para estudiantes de creditos
     * @param <type> $retorno 
     */
    function verificarRequisitosCreditos($retorno) {
      //verificar requisitos
      $requisitos=$this->validacion->validarRequisitosCreditos($_REQUEST);
      if($requisitos!='ok'&& is_array($requisitos))
      {
            $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
          
//        $retorno['mensaje']="El estudiante no ha aprobado requisitos de ".$retorno['nombreEspacio'].":";
//        foreach ($requisitos as $key => $value) {
//          $requisito['codEspacio']=$requisitos[$key]['REQUISITO'];
//          $requisitos[$key]['NOMBRE']=$this->consultarNombreEspacioRequisito($requisito);
//          $retorno['mensaje'].="\\n".$requisitos[$key]['REQUISITO']." - ".$requisitos[$key]['NOMBRE'];
//        }
//        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     *
     * @param <type> $retorno 
     */
    function verificarRequisitos($retorno) {
      //verificar requisitos
      $requisitos=$this->validacion->validarRequisitos($_REQUEST);
      if($requisitos!='ok'&& is_array($requisitos))
      {
            $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
          
//        $retorno['mensaje']="El estudiante no ha aprobado requisitos de ".$retorno['nombreEspacio'].":";
//        foreach ($requisitos as $key => $value) {
//          $retorno['mensaje'].="\\n".$requisitos[$key]['NOMBRE']." Codigo:".$requisitos[$key]['REQUISITO']."  ";
//        }
//        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     *
     * @param <type> $datosInscripcion 
     */
    function verificarCreditos($creditosMaximoPerido) {
      //verifica cupo en el grupo
      $creditos=$this->validacion->verificarCreditosPeriodo($creditosMaximoPerido,$this->espaciosPreinscritos,$this->datosEspacio);
      if($creditos!='ok' && $creditos==1)
      {
            $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//        $retorno['mensaje']="No se puede preinscribir el espacio. Supera ".$creditosMaximoPerido." creditos de preinscripción.";
//        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     *
     * @param <type> $datosInscripcion 
     */
    function verificarCreditosPorClasificacion() {
      //verifica cupo en el grupo
        if (!isset($this->datosEspacio['CLASIFICACION']))
        {
            $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//            $retorno['mensaje']="Espacio sin clasificación. No se puede preinscribir.";
//            $this->enlaceNoAdicion($retorno);
        }else
            {
                $creditos=$this->validacion->verificarCreditosClasificacion($this->parametros,$this->espaciosPreinscritos,$this->datosEspacio,$this->aprobados);
                if($creditos!='ok' && $creditos==1)
                {
                   $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//                    $retorno['mensaje']="No se puede preinscribir el espacio. Supera el número de créditos de clasificación ".$this->datosEspacio['CLASIFICACION'].".";
//                    $this->enlaceNoAdicion($retorno);
                }
            }
    }

    /**
     *
     * @param <type> $datosInscripcion 
     */
    function verificarCancelado($datosInscripcion) {
      //verificar cancelado
      $cancelado=$this->validacion->consultarEspaciosPreinscritosCancelados($datosInscripcion);
      if(is_array($cancelado) && $cancelado[0]['ASI_CODIGO']==$datosInscripcion['codEspacio'])
      {
            $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//        $retorno['mensaje']="El espacio academico ha sido cancelado. No se puede preinscribir de nuevo";
//        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     *
     * @param <type> $datosInscripcion 
     */
    function verificarEspacioPreinscribir($datosInscripcion) {
      //verifica si el espacio pertenece al plan de estudios del estudiante
      $planEstudio=$this->validacion->validarEspacioPlan($_REQUEST);
      if($planEstudio!='ok')
      {
            $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//        $retorno['mensaje']=$planEstudio[0][0];
//        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     * Verifica si al inscribir un espacio no se excede el maximo de espacios a presincribir que ha registrado el coordinador
     * @param type $datosInscripcion 
     */
    function verificarMaximoEspacios($maximoEspacios) {
      //verifica cupo en el grupo
        if($maximoEspacios!='')
        {
        $numeroEspacios=count($this->espaciosPreinscritos)+1;
        if ($numeroEspacios>$maximoEspacios)
            {
                $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//                $retorno['mensaje']="No se puede preinscribir el espacio. Supera el máximo de espacios para preinscribir (".$maximoEspacios.").";
//                $this->enlaceNoAdicion($retorno);
            }
        }else
            {
                $this->noInscritos[]=  $this->datosEspacio['CODIGO'];
//                $retorno['mensaje']="No se han definido parámetros para la preinscripción. Por favor, comuníquese con el proyecto.";
//                $this->enlaceNoAdicion($retorno);
            }
    }
    
    /**
     * Funcion que busca los espacios que estan inscritos para el estudiante
     * @return array
     */
    function buscarEspaciosPreinscritos() {
        $datosEstudiante=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                                'codProyectoEstudiante'=>  $this->datosEstudiante[0]['COD_CARRERA'],
                                'ano'=>  $this->datosEstudiante[0]['ANO'],
                                'periodo'=>  $this->datosEstudiante[0]['PERIODO'],
                                'planEstudioEstudiante'=>$this->datosEstudiante[0]['PLAN_ESTUDIO']);
        $espaciosInscritos=$this->procedimientos->buscarEspaciosInscritosPreinscripcion($datosEstudiante);
        return $espaciosInscritos;
    }
    
    /**
     *
     * @param <type> $datosInscripcion
     */
    function crearArregloPreinscripcionCreditos() {
        $reprobado=$this->validarReprobado();
        if($reprobado==='ok')
        {
            $reprobado='S';
        }else
            {$reprobado='N';}
        $letras=array('OB','OC','EI','EE','CP');
        $numeros=array(1,2,3,4,5);
        $clasificacion=str_replace($letras,$numeros,trim($this->datosEspacio['CLASIFICACION']));
        $this->arregloPreinscripcion[]=array('ano'=>$this->ano,
                                            'periodo'=>  $this->periodo,
                                            'codEstudiante'=>  $this->datosEstudiante[0]['CODIGO'],
                                            'codEspacio'=>$this->datosEspacio['CODIGO'],
                                            'codProyectoEstudiante'=>$this->datosEstudiante[0]['COD_CARRERA'],
                                            'creditos'=>$this->datosEspacio['CREDITOS'],
                                            'htd'=>$this->datosEspacio['HTD'],
                                            'htc'=>$this->datosEspacio['HTC'],
                                            'hta'=>$this->datosEspacio['HTA'],
                                            'cea'=>$clasificacion,
                                            'sem'=>$this->datosEspacio['NIVEL'],
                                            'perdido'=>$reprobado,
                                            'equivalente'=>'');
    }

    /**
     *
     * @param <type> $datosInscripcion 
     */
    function crearArregloPreinscripcionHoras() {
        $reprobado=$this->validarReprobado();
        if($reprobado=='ok')
            {
            $reprobado='S';
            }
            else{$reprobado='N';}
        $this->arregloPreinscripcion[]=array('ano'=>$this->ano,
                                            'periodo'=>  $this->periodo,
                                            'codEstudiante'=>  $this->datosEstudiante[0]['CODIGO'],
                                            'codEspacio'=>$this->datosEspacio['CODIGO'],
                                            'codProyectoEstudiante'=>$this->datosEstudiante[0]['COD_CARRERA'],
                                            'creditos'=>(isset($this->datosEspacio['CREDITOS'])?$this->datosEspacio['CREDITOS']:''),
                                            'htd'=>(isset($this->datosEspacio['HTD'])?$this->datosEspacio['HTD']:''),
                                            'htc'=>(isset($this->datosEspacio['HTC'])?$this->datosEspacio['HTC']:''),
                                            'hta'=>(isset($this->datosEspacio['HTA'])?$this->datosEspacio['HTA']:''),
                                            'cea'=>'',
                                            'sem'=>(isset($this->datosEspacio['NIVEL'])?$this->datosEspacio['NIVEL']:''),
                                            'perdido'=>$reprobado,
                                            'equivalente'=>'');
        }

    /**
     * Funcion que realiza la inscripcion de un estudiante en un grupo de espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera,retornoPagina,retornoOpcion,
     *                           retornoParametros (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante) )
     */
    function inscribirEstudiante()
    {
        $mensaje='';
      $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
      $variable="pagina=admin_consultarPreinscripcionDemandaEstudiante";
      $variable.="&opcion=consultar";
        if (!is_array($this->noInscritos))
        {
            $this->noInscritos='';
        }else
            {
            foreach ($this->noInscritos as $key=>$codigo) {
                $variable.='&codigo['.$key.']='.$codigo;
            }
            }
      $variable=$this->cripto->codificar_url($variable,$this->configuracion);
      if(is_array($this->arregloPreinscripcion))
      {
      foreach ($this->arregloPreinscripcion as $preinscrito) {
        $resultado_adicionar=$this->adicionarOracle($preinscrito);
        if($resultado_adicionar>=1)
        {
          $variablesRegistro=array('usuario'=>$this->usuario,
                                    'evento'=>'52',
                                    'descripcion'=>'Preinscribe Espacio académico',
                                    'registro'=>$this->ano."-".$this->periodo.", ".  $this->datosEspacio['CODIGO'].", 0, 0, ".  $this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosEstudiante[0]['COD_CARRERA'],
                                    'afectado'=>$_REQUEST['codEstudiante']);
        $this->procedimientos->registrarEvento($variablesRegistro);
        }
        else
            {
//              $mensaje="En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde.";
//              //$this->borrarMysqlError($datosInscripcion);
//              $variablesRegistro=array('usuario'=>$this->usuario,
//                                    'evento'=>'50',
//                                    'descripcion'=>'Conexion Error Oracle adicionar',
//                                    'registro'=>$this->ano."-".$this->periodo.", ".$datosInscripcion['codEspacio'].", 0, 0, ".$datosInscripcion['planEstudioEstudiante'].", ".$datosInscripcion['codProyectoEstudiante'],
//                                    'afectado'=>$_REQUEST['codEstudiante']);
            }
    }
        $this->actualizarPreinscripcionesSesion();
    }
      $this->retornar($pagina,$variable,$mensaje);
    }

    /**
     * Funcion que presenta mensaje cuando no se puede realizar la adicion por fallo en las validaciones
     * @param <array> $retorno (pagina, opcion, parametros)
     */
    function enlaceNoAdicion($retorno) {
      
        echo "<script>alert ('".$retorno['mensaje']."');</script>";
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_consultarPreinscripcionDemandaEstudiante";
        $variable.="&opcion=consultar";
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $this->enlaceParaRetornar($pagina, $variable);
    }

    /**
     * Funcion que permite retornar a la pagina de administracion de inscipricion al realizar la adicion.
     * Cuando existe mensaje no se pudo registrar por problemas de conesxion y presenta el mensaje
     * @param <string> $pagina
     * @param <string> $variable
     * @param <array> $variablesRegistro (usuario,evento,descripcion,registro,afectado)
     * @param <string> $mensaje
     */
    function retornar($pagina,$variable,$mensaje=""){
        if($mensaje=="")
        {
          
        }
        else
        {
          echo "<script>alert ('".$mensaje."');</script>";
        }
        $this->enlaceParaRetornar($pagina, $variable);
    }

    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

    /**
    * Funcion que permite consultar el nombre del proyecto curricular
    * @param <int> $codProyecto Codigo del proyecto curricular
    * @return <string> $resultado_carrera Nombre del proyecto
    */
    function consultarNombreCarrera($codProyecto) {
        $cadena_sql = $this->sql->cadena_sql("nombre_carrera", $codProyecto);
        $resultado_carrera = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado_carrera[0]['NOMBRE'];
    }

    /**
     * Funcion que permite consultar el nombre de un espácio academico
     * @return <string>
     */
    function consultarNombreEspacio() {
        $cadena_sql=$this->sql->cadena_sql("nombreEspacio",$_REQUEST);
        $resultado_nombreEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_nombreEspacio[0]['NOMBREESPACIO'];
    }

    /**
     * Funcion que permite consultar el nombre de un espácio academico
     * @return <string>
     */
    function consultarNombreEspacioRequisito($codEspacio) {
        $cadena_sql=$this->sql->cadena_sql("nombreEspacio",$codEspacio);
        $resultado_nombreEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_nombreEspacio[0]['NOMBREESPACIO'];
    }

    /**
     * Funcion que retorna los datos de un espacio si existe
     * @param <array> $datos ()
     * @return <array/string> 
     */
    function buscarDatosEspacios($datos) {
        $datosEspacio=$this->consultarEspacioPlan($datos);
        if($datosEspacio[0][0]>=1)
          {
              return $datosEspacio=$this->consultarDatosEspacio($datos);
          }
          else
          {
                $this->noInscritos[]=$datos['codEspacio'];
              
//            return "No se pueden obtener datos del espacio";
          }
    }

    /**
     * Funcion que verifica si existen datos del espacio en el plan de estudios
     * @param <array> $datos
     * @return <array> 
     */
    function consultarEspacioPlan($datos) {
        $cadena_sql=$this->sql->cadena_sql("espacios_planEstudio",$datos);
        return $resultado_espacio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
    }

    /**
     * Funcion que consulta los datos del espacio academico en el plan de estudios
     * @param <array> $datos
     * @return <array> 
     */
    function consultarDatosEspacio($datos) {
        $cadena_sql=$this->sql->cadena_sql("datosEspacio",$datos);
        return $resultado_espacio=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
    }

    /**
     * Funcion que registra la inscripcion del espacio en Oracle
     * @param <array> $datos
     * @return <int>
     */
    function adicionarOracle($datosPreinscrito) {
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_espacio_oracle",$datosPreinscrito);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

    /**
     *Funcion que permite consultar el numero maximo de espacios a cursar.
     * @param type $datosInscripcion
     * @return type 
     */
    function consultarMaximoEspaciosACursar($datosInscripcion) {
        $cadena_sql=$this->sql->cadena_sql("buscarMaximoEspaciosACursar", $datosInscripcion);
        $resultado_maximo=  $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        return $resultado_maximo[0][0];
    }
    
    /**
     * Funcion que consulta los datos del estudiante en tabla de carga
     * @param int $codEstudiante
     * @return array 
     */
    function consultarDatosEstudiante($codEstudiante){
        $variables =array('codEstudiante'=>$codEstudiante,
                        'ano'=>  $this->ano,
                        'periodo'=>  $this->periodo);
        $cadena_sql=$this->sql->cadena_sql("carga", $variables);
        return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }
    
    function validarReprobado() {
        if ($this->datosEspacio['REPROBADO']==1)
        {
            return 'ok';
        }else
            {
            return '0';
            }
    }
    
    /**
     * Funcion que actualiza el registro de preinscripciones de la sesion del estudiante
     */
    function actualizarPreinscripcionesSesion() {
        $datosEstudiante=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                                'codProyectoEstudiante'=>  $this->datosEstudiante[0]['COD_CARRERA'],
                                'planEstudioEstudiante'=>  $this->datosEstudiante[0]['PLAN_ESTUDIO'],
                                'ano'=>  $this->datosEstudiante[0]['ANO'],
                                'periodo'=>  $this->datosEstudiante[0]['PERIODO']);
        $espaciosInscritos=$this->procedimientos->actualizarPreinscritosSesion($datosEstudiante);
    }
    
    
    /**
     * Funcion que busca los datos de un espacio enviado
     * @param int $codEspacio
     */
    function buscarDatosEspacio($codEspacio) {
        $this->espaciosPorCursar=json_decode($this->datosEstudiante[0]['ESPACIOS_POR_CURSAR'],true);
        $datosEspacio=$this->encontrarDatosEspacio($this->espaciosPorCursar, 'CODIGO', $codEspacio,1);
        if (!isset($datosEspacio)||!is_array($datosEspacio))
        {
            $equivalencias=json_decode($this->datosEstudiante[0]['EQUIVALECIAS'],true);
            $datosEspacio=  $this->encontrarDatosEspacio($equivalencias, 'CODIGO_EQUIVALENCIA', $codEspacio);
        }
        if(!is_array($datosEspacio))
        {
            $this->noInscritos[]=$codEspacio;
//            $retorno['mensaje']="El espacio academico no se puede preinscribir.";
//            $this->enlaceNoAdicion($retorno);
        }else
            {
                $this->datosEspacio=$datosEspacio;   
            }
    }
    
    /**
     * Funcion que busca los datos de un espacio entre un arreglo enviado.
     * @param array $arregloEspacios
     * @param string $nombreCampo
     * @param int $codEspacio
     * @return array
     */
    function encontrarDatosEspacio($arregloEspacios,$nombreCampo,$codEspacio,$permitidos=0) {
        $salir=0;
        $datosEspacio='';
        foreach ($arregloEspacios as $espacio)
        {
            if($espacio[$nombreCampo]==$codEspacio)
            {
                if($permitidos==1)
                {
                    if($espacio['REQUISITOS']==1&&$espacio['NIVELES']==1)
                    {
                        $datosEspacio=$espacio;
                        $salir=1;
                    }
                }else
                    {
                        $datosEspacio=$espacio;
                        $salir=1;
                    }
            }
            if($salir==1){break;}
        }
        return $datosEspacio;
    }

}

?>
