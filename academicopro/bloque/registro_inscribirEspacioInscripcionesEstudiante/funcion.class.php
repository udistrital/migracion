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
class funcion_registroInscribirEspacioInscripcionesEstudiante extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;
    private $datosInscripcion;
    private $datosEstudiante;
    private $espaciosCancelados;
    private $datosEspacio;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");


//        $this->administrar=new administrarModulo();
//        $this->administrar->administrarModuloSGA($configuracion, '4');

        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");
        //Conexion Distribuida - se evalua la variable $configuracion['dbdistribuida']
        //donde si es =1 la conexion la realiza a Mysql, de lo contrario la realiza a ORACLE
        if($configuracion["dbdistribuida"]==1){
                 $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }else{
                $this->accesoMyOracle = $this->accesoOracle;
        }

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_inscribirEspacioInscripcionesEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
        ?>
    <head>
        <script language="JavaScript">
            var message = "";
            function clickIE(){
                if (document.all){
                    (message);
                    return false;
                }
            }
            function clickNS(e){
                if (document.layers || (document.getElementById && !document.all)){
                    if (e.which == 2 || e.which == 3){
                        (message);
                        return false;
                    }
                }
            }
            if (document.layers){
                document.captureEvents(Event.MOUSEDOWN);
                document.onmousedown = clickNS;
            } else {
                document.onmouseup = clickNS;
                document.oncontextmenu = clickIE;
            }
            document.oncontextmenu = new Function("return false")
        </script>
    </head>
        <?

    }

    /**
     * Funcion que valida la inscripcion de un estudiante en un espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function inscribirEspacio()
    {
        if ($this->usuario!=$_REQUEST['codEstudiante'])
        {
            echo "Ha ocurrido un error. Por favor inicie sesion nuevamente ";exit;
        }
        $this->datosEstudiante=$this->consultarDatosEstudiante($this->usuario);
        $this->buscarDatosEspacio($_REQUEST['codEspacio']);
        $this->datosInscripcion=$_REQUEST;
        $this->datosInscripcion['codProyecto']=  $this->datosInscripcion['codProyectoEstudiante'];
        $this->datosInscripcion['planEstudio']=$this->datosInscripcion['planEstudioEstudiante'];
        $retorno['pagina']=$this->datosInscripcion['retornoPagina']="admin_consultarInscripcionesEstudiante";
        $retorno['opcion']=$this->datosInscripcion['retornoOpcion']="mostrarConsulta";
        $this->datosInscripcion['retornoParametros']=array('codProyectoEstudiante'=>$_REQUEST["codProyectoEstudiante"],
                                                    'planEstudioEstudiante'=>$_REQUEST["planEstudioEstudiante"],
                                                    'codEstudiante'=>$_REQUEST["codEstudiante"]);
        $back='';
         foreach ($this->datosInscripcion['retornoParametros'] as $key => $value) {
              $back.="&".$key."=".$value;
            }
            //$retorno['parametros']=$back;
        $retorno['nombreEspacio']=$this->datosInscripcion['nombreEspacio'];
        
        //$this->buscarEspaciosCancelados($retorno);
        
        $espaciosInscritos=$this->buscarEspaciosInscritos();
        if(is_array($espaciosInscritos))
        {
            $this->verificarInscrito($retorno,$espaciosInscritos);
            $horarioEstudiante=$this->buscarHorarioEstudiante($espaciosInscritos);
            $horarioGrupo=$this->buscarHorarioGrupo();
            $this->verificarCruce($retorno,$horarioEstudiante,$horarioGrupo);
        }
        $this->verificarParametros($retorno,$espaciosInscritos);
        $this->verificarCupo($retorno);
        $this->registrarInscripcion();
    }

    /*datosInscripcion
     * array (size=19)
  'pagina' => string 'registro_inscribirEspacioInscripcionesEstudiante' (length=48)
  'opcion' => string 'inscribirEspacio' (length=16)
  'codProyecto' => string '15' (length=2)
  'planEstudio' => string '202' (length=3)
  'codProyectoEstudiante' => string '15' (length=2)
  'planEstudioEstudiante' => string '202' (length=3)
  'codEstudiante' => string '20092015028' (length=11)
  'estado_est' => string 'A' (length=1)
  'codEspacio' => string '88' (length=2)
  'nombreEspacio' => string 'ECUACIONES DIFERENCIALES' (length=24)
  'creditos' => string '3' (length=1)
  'grupo' => string '29' (length=2)
  'cupo' => string '29' (length=2)
  'carrera' => string '15' (length=2)
  'grupoAnterior' => string '0' (length=1)
  'retorno' => string '' (length=0)
  'opcionRetorno' => string '' (length=0)
  'retornoPagina' => string 'admin_consultarInscripcionesEstudiante' (length=38)
  'retornoOpcion' => string 'mostrarConsulta' (length=15)
  'retornoParametros' => 
    array (size=3)
      'codProyectoEstudiante' => string '15' (length=2)
      'planEstudioEstudiante' => string '202' (length=3)
      'codEstudiante' => string '20092015028' (length=11)
     */
    
    /**
     * Funcion que valida si el espacio ya ha sido inscrito
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */

    function verificarInscrito($retorno,$espaciosInscritos) {
      //verifica si el espacio ya ha sido inscrito
      $inscrito='ok';
      foreach ($espaciosInscritos as $inscritos) {
          if($inscritos['CODIGO']==$this->datosInscripcion['codEspacio'])
          {
              $inscrito=$inscritos['GRUPO'];
              break;
          }
      }
      if($inscrito!='ok')
      {
        $retorno['mensaje']="El espacio académico ya esta inscrito en el grupo ".$inscrito." para el periodo actual. No se puede inscribir de nuevo.";
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     * Funcion que verifica si hay cruce de horario
     * @param string $retorno
     * @param array $horarioEstudiante
     * @param array $horarioGrupo 
     */
    function verificarCruce($retorno,$horarioEstudiante,$horarioGrupo) {
      //verifica si hay cruce de horario
      $cruce=$this->validacion->verificarCruceHorarios($horarioEstudiante,$horarioGrupo);
      if($cruce==1)
      {
        $retorno['mensaje']="El horario del espacio académico presenta cruce con el horario del estudiante. No se ha realizado la inscripción";
        $this->enlaceNoAdicion($retorno);
        exit;
      }
    }
/*datosEstudiante
 * array (size=1)
  0 => 
    array (size=36)
      0 => string '20092015028' (length=11)
      'CODIGO' => string '20092015028' (length=11)
      1 => string 'GARZON HERRERA LORENA ALEJANDRA' (length=31)
      'NOMBRE' => string 'GARZON HERRERA LORENA ALEJANDRA' (length=31)
      2 => string 'A' (length=1)
      'ESTADO' => string 'A' (length=1)
      3 => string 'ACTIVO' (length=6)
      'ESTADO_DESCRIPCION' => string 'ACTIVO' (length=6)
      4 => string '202' (length=3)
      'PLAN_ESTUDIO' => string '202' (length=3)
      5 => string '15' (length=2)
      'COD_CARRERA' => string '15' (length=2)
      6 => string 'INGENIERIA INDUSTRIAL' (length=21)
      'NOMBRE_CARRERA' => string 'INGENIERIA INDUSTRIAL' (length=21)
      7 => string '33' (length=2)
      'NOMBRE_FACULTAD' => string '33' (length=2)
      8 => string 'S' (length=1)
      'TIPO_ESTUDIANTE' => string 'S' (length=1)
      9 => string '1993027' (length=7)
      'ACUERDO' => string '1993027' (length=7)
      10 => string '[{"0":"8","CODIGO":"8","1":"HISTORIA Y CULTURA COLOMBIANA","NOMBRE":"HISTORIA Y CULTURA COLOMBIANA","2":"9","NIVEL":"9","3":"N","ELECTIVA":"N","4":"2","CREDITOS":"2","5":"202","PLAN":"202","6":"OC","CLASIFICACION":"OC","REQUISITOS":"1","NIVELES":"1","REPROBADO":"0"},{"0":"53","CODIGO":"53","1":"INGENIERÍA ECONÓMICA","NOMBRE":"INGENIERÍA ECONÓMICA","2":"6","NIVEL":"6","3":"N","ELECTIVA":"N","4":"2","CREDITOS":"2","5":"202","PLAN":"202","6":"OB","CLASIFICACION":"OB","REQUISITOS":"1","NIVELES":"1","REPROBA'... (length=16819)
      'ESPACIOS_POR_CURSAR' => string '[{"0":"8","CODIGO":"8","1":"HISTORIA Y CULTURA COLOMBIANA","NOMBRE":"HISTORIA Y CULTURA COLOMBIANA","2":"9","NIVEL":"9","3":"N","ELECTIVA":"N","4":"2","CREDITOS":"2","5":"202","PLAN":"202","6":"OC","CLASIFICACION":"OC","REQUISITOS":"1","NIVELES":"1","REPROBADO":"0"},{"0":"53","CODIGO":"53","1":"INGENIERÍA ECONÓMICA","NOMBRE":"INGENIERÍA ECONÓMICA","2":"6","NIVEL":"6","3":"N","ELECTIVA":"N","4":"2","CREDITOS":"2","5":"202","PLAN":"202","6":"OB","CLASIFICACION":"OB","REQUISITOS":"1","NIVELES":"1","REPROBA'... (length=16819)
      11 => string '' (length=0)
      'EQUIVALECIAS' => string '' (length=0)
      12 => string '[{"CODIGO":"131","REQUISITO":"127","NOMBRE":"PROCESOS MECÁNICOS"},{"CODIGO":"138","REQUISITO":"134","NOMBRE":"PROGRAMACIÓN LINEAL Y ENTERA"},{"CODIGO":"136","REQUISITO":"127","NOMBRE":"PROCESOS MECÁNICOS"},{"CODIGO":"157","REQUISITO":"140","NOMBRE":"MERCADOTECNIA"},{"CODIGO":"157","REQUISITO":"151","NOMBRE":"GESTIÓN E INNOVACIÓN TECNOLÓGICA"},{"CODIGO":"139","REQUISITO":"134","NOMBRE":"PROGRAMACIÓN LINEAL Y ENTERA"},{"CODIGO":"152","REQUISITO":"53","NOMBRE":"INGENIERÍA ECONÓMICA"},{"CODIGO":"155","'... (length=911)
      'REQUISITOS_NO_APROBADOS' => string '[{"CODIGO":"131","REQUISITO":"127","NOMBRE":"PROCESOS MECÁNICOS"},{"CODIGO":"138","REQUISITO":"134","NOMBRE":"PROGRAMACIÓN LINEAL Y ENTERA"},{"CODIGO":"136","REQUISITO":"127","NOMBRE":"PROCESOS MECÁNICOS"},{"CODIGO":"157","REQUISITO":"140","NOMBRE":"MERCADOTECNIA"},{"CODIGO":"157","REQUISITO":"151","NOMBRE":"GESTIÓN E INNOVACIÓN TECNOLÓGICA"},{"CODIGO":"139","REQUISITO":"134","NOMBRE":"PROGRAMACIÓN LINEAL Y ENTERA"},{"CODIGO":"152","REQUISITO":"53","NOMBRE":"INGENIERÍA ECONÓMICA"},{"CODIGO":"155","'... (length=911)
      13 => string '{"total":"167","OB":"124","OC":"17","EI":"18","EE":"8","CP":"0","maxcreditos":"18","maxespacios":"","maxniveles":""}' (length=116)
      'PARAMETROS' => string '{"total":"167","OB":"124","OC":"17","EI":"18","EE":"8","CP":"0","maxcreditos":"18","maxespacios":"","maxniveles":""}' (length=116)
      14 => string '{"total":68,"OB":57,"OC":9,"EI":2,"EE":0,"CP":0}' (length=48)
      'CREDITOS_APROBADOS' => string '{"total":68,"OB":57,"OC":9,"EI":2,"EE":0,"CP":0}' (length=48)
      15 => string '["88","105"]' (length=12)
      'CANCELADOS' => string '["88","105"]' (length=12)
      16 => string '2012' (length=4)
      'ANO' => string '2012' (length=4)
      17 => string '3' (length=1)
      'PERIODO' => string '3' (length=1)
 */
    function verificarParametros($retorno,$espaciosInscritos) {
        $parametros=json_decode($this->datosEstudiante[0]['PARAMETROS'], true);
        if (trim($this->datosEstudiante[0]['TIPO_ESTUDIANTE']) == 'S')
        {
            $creditosPeriodo=$parametros['maxcreditos'];
            $this->verificarCreditosPeriodo($retorno,$creditosPeriodo,$espaciosInscritos);
            $this->verificarCreditosClasificacion($retorno,$parametros,$espaciosInscritos);
        }else
            {
            $this->verificarEspaciosPeriodo($retorno,$parametros,$espaciosInscritos);
            }
    }
    /**
     * Funcion que verifica los requisitos de un espacio academico
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function verificarRequisitos($retorno) {
      //verificar requisitos
      $requisitos=$this->validacion->validarRequisitosCreditos($_REQUEST);
      if($requisitos!='ok'&& is_array($requisitos))
      {
        $retorno['mensaje']="El estudiante no ha aprobado requisitos de ".$retorno['nombreEspacio'].":";
        foreach ($requisitos as $key => $value) {
          $retorno['mensaje'].="\\n".$requisitos[$key]['NOMBRE']." Codigo:".$requisitos[$key]['REQUISITO']."  ";
        }
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     * Funcion que verifica el cupo en el grupo del espacio academico
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function verificarCupo($retorno) {
      //verifica cupo en el grupo
      $sobrecupo=$this->validacion->verificarSobrecupo($this->datosInscripcion);
      if($sobrecupo!='ok' && is_array($sobrecupo))
      {
        $retorno['mensaje']="El grupo presenta sobrecupo: Cupo:".$sobrecupo['cupo']." Disponibles: 0.  No se ha realizado la inscripción";
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     * Funcion que verifica si el espacio academicoha sido cancelado
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
        function verificarCancelado($retorno) {
      //verificar cancelado
      $cancelado=$this->validacion->validarCancelado($_REQUEST);
      if($cancelado!='ok' && is_array($cancelado))
      {
        $retorno['mensaje']="El espacio academico ha sido cancelado ".$cancelado['veces']." en el per&iacute;odo actual.";
        $this->enlaceNoAdicion($retorno);
        
      }
    }

    /**
     * Funcion que verifica si el espacio academico pertenece  al plan de estudios del estudiante
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
   function verificarEspacioPlan($retorno) {
      //verifica si el espacio pertenece al plan de estudios del estudiante
      $planEstudio=$this->validacion->validarEspacioPlan($_REQUEST);
      if($planEstudio!='ok')
      {
        $retorno['mensaje']="El espacio academico no pertenece al plan de estudios del estudiante.";
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     * Funcion que permite verificar si un estudiante ha reprobado un espacio
     * @param array $retorno
     */
    function verificarEspacioReprobado($retorno) {
      $reprobado=$this->validacion->validarReprobado($_REQUEST);
      if ($reprobado!='ok' && is_array($reprobado))
      {
        $retorno['mensaje']="No se puede adicionar, porque el estudiante se encuentra en <b>Prueba Acad&eacute;mica</b> y no ha reprobado el espacio acad&eacute;mico.<br>";
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     * Verifica que no se exceda el maximo de creditos por periodo
     * @param array $retorno
     * @param int $creditosPeriod
     * @param array $espaciosInscritos
     */
    function verificarCreditosPeriodo($retorno,$creditosPeriodo,$espaciosInscritos) {
        if (isset($this->datosInscripcion['creditos'])&&!is_null($this->datosInscripcion['creditos']))
        {
            $creditosEspacio=array('CREDITOS'=>$this->datosInscripcion['creditos']);
            $creditos=$this->validacion->verificarCreditosPeriodo($creditosPeriodo,$espaciosInscritos,$creditosEspacio);
            if($creditos!='ok' && $creditos==1)
            {
                $retorno['mensaje']="No se puede inscribir el espacio. Supera ".$creditosPeriodo." creditos de inscripción.";
                $this->enlaceNoAdicion($retorno);
            }
        }else
            {
                $retorno['mensaje']="El espacio no tiene creditos. No se puede inscribir.";
                $this->enlaceNoAdicion($retorno);
            }
    }

    /**
     * Funcion que verifica el numero de espacios inscritos para el periodo
     * 
     * @param string $retorno
     * @param array $parametros
     * @param array $espaciosInscritos
     */
    function verificarEspaciosPeriodo($retorno,$parametros,$espaciosInscritos) {
      $espacios=$this->validacion->validarEspaciosInscritos($parametros['maxespacios'],$espaciosInscritos);
      
      if($espacios!='ok')
      {
        $retorno['mensaje']="No se puede inscribir el espacio. Supera ".$parametros['maxespacios']." espacios de inscripción.";
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     *
     * @param <type> $datosInscripcion 
     */
    function verificarCreditosClasificacion($retorno,$parametros,$espaciosInscritos) {
        $clasificacion=0;
        if (isset($this->datosInscripcion['creditos'])&&!is_null($this->datosInscripcion['creditos']))
        {
            $espacios=json_decode($this->datosEstudiante[0]['ESPACIOS_POR_CURSAR'],true);
            $aprobados=json_decode($this->datosEstudiante[0]['CREDITOS_APROBADOS'],true);
            foreach ($espacios as $espacio)
            {
                if ($espacio['CODIGO']==$this->datosInscripcion['codEspacio'])
                {
                    $clasificacion=(isset($espacio['CLASIFICACION'])?$espacio['CLASIFICACION']:0);
                    break;
                }
            }
            if(isset($clasificacion)&&$clasificacion!==0)
            {
                $datosEspacio=array('CREDITOS'=>$this->datosInscripcion['creditos'],
                                    'CLASIFICACION'=>$clasificacion);
                $creditos=$this->validacion->verificarCreditosClasificacion($parametros,$espaciosInscritos,$datosEspacio,$aprobados);
                if($creditos!='ok' && $creditos==1)
                {
                    $retorno['mensaje']="No se puede inscribir el espacio. Supera ".$creditosPeriodo." creditos de inscripción.";
                    $this->enlaceNoAdicion($retorno);
                }
            }else
                {
                    $retorno['mensaje']="El espacio no tiene clasificación. No se puede inscribir.";
                    $this->enlaceNoAdicion($retorno);
                }
        }else
            {
                $retorno['mensaje']="El espacio no tiene creditos. No se puede inscribir.";
                $this->enlaceNoAdicion($retorno);
            }
    }

/**ncion que realiza la inscripcion de un estudiante en un grupo de espacio academico
* @param <array> $this->configuracion
* @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
*                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera,retornoPagina,retornoOpcion,
*                           retornoParametros (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante) )
*/
    
    function registrarInscripcion() {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$this->datosInscripcion['retornoPagina'];
        $variable.="&opcion=".$this->datosInscripcion['retornoOpcion'];
        foreach ($this->datosInscripcion['retornoParametros'] as $key => $value)
        {
          $variable.="&".$key."=".$value;
        }
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        $resultado_espacio=$this->datosEspacio;
        if(is_array($resultado_espacio))
        {
            $letras=array('OB','OC','EI','EE','CP');
            $numeros=array(1,2,3,4,5);
            $clasificacion=str_replace($letras,$numeros,trim(isset($resultado_espacio['CLASIFICACION'])?$resultado_espacio['CLASIFICACION']:''));
            $datos['codEstudiante']=$this->datosEstudiante[0]['CODIGO'];
            $datos['codProyectoEstudiante']=$this->datosEstudiante[0]['COD_CARRERA'];
            $datos['codEspacio']=$this->datosInscripcion['codEspacio'];
            $datos['grupo']=$this->datosInscripcion['grupo'];
            $datos['ano']=$this->ano;
            $datos['periodo']=$this->periodo;
            $datos['creditos']=(isset($resultado_espacio['CREDITOS'])?$resultado_espacio['CREDITOS']:'');
            $datos['htd']=(isset($resultado_espacio['HTD'])?$resultado_espacio['HTD']:'');
            $datos['htc']=(isset($resultado_espacio['HTC'])?$resultado_espacio['HTC']:'');
            $datos['hta']=(isset($resultado_espacio['HTA'])?$resultado_espacio['HTA']:'');
            $datos['CLASIFICACION']=$clasificacion;

            $resultado_adicionar=$this->insertarRegistroInscripcion($datos);
            if($resultado_adicionar>=1)
            {
                $mensaje="Espacio académico adicionado.";
                $this->actualizarInscripcionesSesion();
                $this->procedimientos->actualizarCupo($datos);
                $variablesRegistro=array('usuario'=>$this->usuario,
                                          'evento'=>'1',
                                          'descripcion'=>'Adiciona Espacio académico',
                                          'registro'=>$this->ano."-".$this->periodo.", ".$datos['codEspacio'].", 0, ".$datos['grupo'].", ".$this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosInscripcion['carrera'],
                                          'afectado'=>$this->datosEstudiante[0]['CODIGO']);
            }else
                {
                    $mensaje="No se puede adicionar, por favor intente mas tarde.";
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                          'evento'=>'50',
                                          'descripcion'=>'Conexion Error Oracle adicionar',
                                          'registro'=>$this->ano."-".$this->periodo.", ".$datos['codEspacio'].",0, ".$datos['grupo'].", ".$this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosEstudiante[0]['COD_CARRERA'],
                                          'afectado'=>$this->datosEstudiante[0]['CODIGO']);
                }
        }else
            {
                $mensaje="No hay datos del espacio. No se puede adicionar.";
                $variablesRegistro=array('usuario'=>$this->usuario,
                                      'evento'=>'50',
                                      'descripcion'=>'Error datos espacio adicionar',
                                      'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosEstudiante[0]['COD_CARRERA'],
                                      'afectado'=>$this->datosEstudiante[0]['CODIGO']);
            }
        $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
    }
    
    /**
     * Funcion que presenta mensaje cuando no se puede realizar la adicion por fallo en las validaciones
     * @param <array> $retorno (pagina, opcion, parametros)
     */
    function enlaceNoAdicion($retorno) {
      
        echo "<script>alert ('".$retorno['mensaje']."');</script>";
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$retorno['pagina'];
        $variable.="&opcion=".$retorno['opcion'];
        //$variable.=$retorno['parametros'];

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
    function retornar($pagina,$variable,$variablesRegistro,$mensaje=""){
        if($mensaje=="")
        {
          
        }
        else
        {
          echo "<script>alert ('".$mensaje."');</script>";
        }
        $this->procedimientos->registrarEvento($variablesRegistro);
        $this->enlaceParaRetornar($pagina, $variable);
    }

    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

    /**
     * Funcion que busca los datos de un espacio enviado
     * @param int $codEspacio
     */
    function buscarDatosEspacio($codEspacio) {
        $espaciosPorCursar=json_decode($this->datosEstudiante[0]['ESPACIOS_POR_CURSAR'],true);
        $datosEspacio=$this->encontrarDatosEspacio($espaciosPorCursar, 'CODIGO', $codEspacio);
        if (!isset($datosEspacio)||!is_array($datosEspacio))
        {
            $equivalencias=json_decode($this->datosEstudiante[0]['EQUIVALECIAS'],true);
            $datosEspacio=  $this->encontrarDatosEspacio($equivalencias, 'CODIGO_EQUIVALENCIA', $codEspacio);
        }
        $this->datosEspacio=$datosEspacio;
    }
    
    /**
     * Funcion que busca los datos de un espacio entre un arreglo enviado.
     * @param array $arregloEspacios
     * @param string $nombreCampo
     * @param int $codEspacio
     * @return array
     */
    function encontrarDatosEspacio($arregloEspacios, $nombreCampo,$codEspacio) {
        $salir=0;
        $datosEspacio='';
        foreach ($arregloEspacios as $espacio)
        {
            if($espacio[$nombreCampo]==$codEspacio)
            {
                $datosEspacio=$espacio;
                $salir=1;
            }
            if($salir==1){break;}
        }
        return $datosEspacio;
    }

    /**
     * Funcion que busca los espacios que se han cancelado
     * @param string $retorno
     */
    function buscarEspaciosCancelados($retorno) {
        $this->espaciosCancelados=json_decode($this->datosEstudiante[0]['CANCELADOS'],true);
        $codEspacio=$this->datosInscripcion['codEspacio'];
        if (is_array($this->espaciosCancelados)&&$this->espaciosCancelados!="")
        {
            foreach ($this->espaciosCancelados as $cancelados)
            {
                if($cancelados==$codEspacio)
                {
                    $retorno['mensaje']= $retorno['nombreEspacio']." ha sido cancelado en el período actual. No se puede adicionar.";
                    $this->enlaceNoAdicion($retorno);
                    exit;
                }
            }
        }
    }

    /**
     * Funcion que busca los espacios que estan inscritos para el estudiante
     * @return array
     */
    function buscarEspaciosInscritos() {
        $datosEstudiante=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                                'codProyectoEstudiante'=>  $this->datosEstudiante[0]['COD_CARRERA'],
                                'ano'=>  $this->datosEstudiante[0]['ANO'],
                                'periodo'=>  $this->datosEstudiante[0]['PERIODO']);
        $espaciosInscritos=$this->procedimientos->buscarEspaciosInscritos($datosEstudiante);
        return $espaciosInscritos;
    }
    
    /**
     * Funcion que busca el horario del estudiante de acuerdo a los espacios inscritos que le llegan
     * @param array $espaciosInscritos
     * @return array
     */
    function buscarHorarioEstudiante($espaciosInscritos) {
        $horarioEstudiante=$this->procedimientos->buscarHorario($espaciosInscritos);
        return $horarioEstudiante;
    }
    
    /**
     * Funcion que busca el horario de un grupo
     * @return array
     */
    function buscarHorarioGrupo() {
        $variables=array(array('CODIGO'=>$this->datosInscripcion['codEspacio'],
                        'GRUPO'=>$this->datosInscripcion['grupo']));
        $horarioGrupo=$this->procedimientos->buscarHorario($variables);
        return $horarioGrupo;
    }
    
    /**
     * Funcion que actualiza el registro de inscripciones de la sesion del estudiante
     */
    function actualizarInscripcionesSesion() {
        $datosEstudiante=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                                'codProyectoEstudiante'=>  $this->datosEstudiante[0]['COD_CARRERA'],
                                'ano'=>  $this->datosEstudiante[0]['ANO'],
                                'periodo'=>  $this->datosEstudiante[0]['PERIODO']);
        $espaciosInscritos=$this->procedimientos->actualizarInscritosSesion($datosEstudiante);
    }

    /**
     * Funcion que registra la inscripcion del espacio en Oracle
     * @param array $datos
     * @return int
     */
    function insertarRegistroInscripcion($datos) {
        $cadena_sql_adicionar=$this->sql->cadena_sql("inscribir_espacio",$datos);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
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
  
    
}

?>