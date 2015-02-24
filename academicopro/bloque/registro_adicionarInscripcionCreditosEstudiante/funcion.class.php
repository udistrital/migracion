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
class funcion_registroAdicionarInscripcionCreditosEstudiante extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;
    private $datosInscripcion;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");


//        $this->administrar=new administrarModulo();
//        $this->administrar->administrarModuloSGA($configuracion, '4');

        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        $this->tema=$tema;
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
        $this->formulario="registro_adicionarInscripcionCreditosEstudiante";
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
    function validarInscripcionEstudiante()
    {
        $this->datosInscripcion=$_REQUEST;
        $this->datosInscripcion['codProyecto']=  $this->datosInscripcion['codProyectoEstudiante'];
        $this->datosInscripcion['planEstudio']=$this->datosInscripcion['planEstudioEstudiante'];
        $retorno['pagina']=$this->datosInscripcion['retornoPagina']="admin_consultarInscripcionCreditosEstudiante";
        $retorno['opcion']=$this->datosInscripcion['retornoOpcion']="mostrarConsulta";
        $this->datosInscripcion['retornoParametros']=array('codProyectoEstudiante'=>$_REQUEST["codProyectoEstudiante"],
                                                    'planEstudioEstudiante'=>$_REQUEST["planEstudioEstudiante"],
                                                    'codEstudiante'=>$_REQUEST["codEstudiante"]);
        $back='';
         foreach ($this->datosInscripcion['retornoParametros'] as $key => $value) {
              $back.="&".$key."=".$value;
            }
            $retorno['parametros']=$back;
        $retorno['nombreEspacio']=$this->consultarNombreEspacio();
        if (trim($_REQUEST['estado_est'])=='B'||trim($_REQUEST['estado_est'])=='J')
        {
          if(isset($_REQUEST['reprobado'])||isset($_REQUEST['confirmaPlan'])||isset($_REQUEST['cancelado']))
          {

          }else
            {
                $this->verificarEspacioReprobado($retorno);
            }
        }else
          {
              
          }

        if (isset($_REQUEST['confirmaPlan'])&&$_REQUEST['confirmaPlan']==1)
        {
          $this->inscribirEstudiante($this->datosInscripcion);
        }
        else
          {
            if(isset($_REQUEST['cancelado'])&&$_REQUEST['cancelado']==1)
            {
              $this->verificarEspacioPlan($retorno);
            }
            else
              {
                $this->verificarInscrito($retorno);
                $this->verificarCruce($retorno);
                $this->verificarRequisitos($retorno);
                $this->verificarSobrecupo($retorno);
                $this->verificarCancelado($retorno);
                $this->verificarEspacioPlan($retorno);
                $this->verificarCreditos($retorno);
                $this->verificarCreditosPorClasificacion($retorno);
              }
              $this->inscribirEstudiante($this->datosInscripcion);
          }

//si hay confirmación de cancelado pasa a varificar plan
//si hay confirmacion de planEstudio, pasa a registrar
        //si no hay, realiza validaciones

    }

    /**
     * Funcion que valida si el espacio ya ha sido inscrito
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */

    function verificarInscrito($retorno) {
      //verifica si el espacio ya ha sido inscrito
      $inscrito=$this->validacion->validarEspacioInscrito($_REQUEST);
      if($inscrito!='ok' && is_array($inscrito))
      {
        $carrera=$this->consultarNombreCarrera($inscrito[0]['PROYECTO']);
        $retorno['mensaje']="El espacio académico ya esta inscrito en el grupo ".$inscrito[0]['GRUPO']." de ".$carrera." para el periodo actual. No se puede inscribir de nuevo.";
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     * Funcion que verifica si hay cruce de horario
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function verificarCruce($retorno) {
      //verifica si hay cruce de horario
      $datos=$_REQUEST;
      $datos['codProyecto']=$datos['carrera'];
      $cruce=$this->validacion->validarCruceHorario($datos);
      if($cruce!='ok'&&is_array($cruce))
      {
        $retorno['mensaje']="El horario del espacio académico presenta cruce con el horario del estudiante. No se ha realizado la inscripción";
        $this->enlaceNoAdicion($retorno);
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
    function verificarSobrecupo($retorno) {
      //verifica cupo en el grupo
      $sobrecupo=$this->validacion->validarSobrecupo($_REQUEST);
      if($sobrecupo!='ok' && is_array($sobrecupo))
      {
        $retorno['mensaje']="El grupo presenta sobrecupo: Cupo:".$sobrecupo['cupo']." Inscritos:".$sobrecupo['inscritos']." Disponibles:".$sobrecupo['disponibles'].". No se ha realizado la inscripción";
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
     * @param <array> $datosInscripcion 
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
*
     * @param <type> $datosInscripcion 
     */
    function verificarCreditos($retorno) {
      //verifica cupo en el grupo
      $creditos=$this->validacion->validarCreditosInscritos($this->datosInscripcion);
      if($creditos!='ok' && is_array($creditos))
      {
        $retorno['mensaje']="No se puede preinscribir el espacio. Supera ".$creditos[0]['MAX_PERIODO']." creditos de inscripción.";
        $this->enlaceNoAdicion($retorno);
      }
    }

    /**
     *
     * @param <type> $datosInscripcion 
     */
    function verificarCreditosPorClasificacion($retorno) {
      //verifica cupo en el grupo
      $creditos=$this->validacion->validarCreditosPorClasificacion($this->datosInscripcion);
      if($creditos!='ok' && is_array($creditos))
      {
        $retorno['mensaje']="No se puede inscribir el espacio. Supera el número de créditos de clasificación ".$creditos[0]['creditos'];
        $this->enlaceNoAdicion($retorno);
      }
    }

/**ncion que realiza la inscripcion de un estudiante en un grupo de espacio academico
* @param <array> $this->configuracion
* @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
*                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera,retornoPagina,retornoOpcion,
*                           retornoParametros (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante) )
*/
    
    function inscribirEstudiante($datosInscripcion)
    {

        $datosInscripcion['ano']=$this->ano;
        $datosInscripcion['periodo']=$this->periodo;
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$datosInscripcion['retornoPagina'];
        $variable.="&opcion=".$datosInscripcion['retornoOpcion'];
        foreach ($datosInscripcion['retornoParametros'] as $key => $value)
        {
          $variable.="&".$key."=".$value;
        }
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        $resultado_espacio=$this->buscarDatosEspacio($datosInscripcion);
        if(is_array($resultado_espacio))
          {
            $datosInscripcion['creditos']=$resultado_espacio[0]['CREDITOS'];
            $datosInscripcion['ht']=$resultado_espacio[0]['HT'];
            $datosInscripcion['hp']=$resultado_espacio[0]['HP'];
            $datosInscripcion['haut']=$resultado_espacio[0]['HAUT'];
            $datosInscripcion['CLASIFICACION']=$resultado_espacio[0]['CLASIFICACION'];


            $resultado_adicionar=$this->adicionarOracle($datosInscripcion);
            if($resultado_adicionar>=1)
                {
                    $mensaje="Espacio académico adicionado.";
                    $this->procedimientos->actualizarCupo($datosInscripcion);
                    //evalua si existen BD distribuida, para realizar el registro en MySUDD
                    if($this->configuracion["dbdistribuida"]==1){
                        $resultado_adicionar_myoracle=$this->adicionarMyOracle($datosInscripcion);
                        $datosInscripcion['nvo_inscritos']=$this->consultarCupoInscritos($datosInscripcion);
                        $actualizaCupo = $this->actualizarCupoMyOracle($datosInscripcion);
                    }
                    //inserta el registro en MySql con la inscripcion del espacio
                    $_REQUEST['ano']=$this->ano;
                    $_REQUEST['periodo']=$this->periodo;
                    $registros_insertados = $this->adicionarEspacioMysql($_REQUEST);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'1',
                                              'descripcion'=>'Adiciona Espacio académico',
                                              'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['carrera'],
                                              'afectado'=>$_REQUEST['codEstudiante']);
                }
                else
                    {
                        $mensaje="En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde.";
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'50',
                                              'descripcion'=>'Conexion Error Oracle adicionar',
                                              'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['carrera'],
                                              'afectado'=>$_REQUEST['codEstudiante']);
                    }
        }
        else
        {
            $mensaje=$resultado_espacio;
            $variablesRegistro=array('usuario'=>$this->usuario,
                                  'evento'=>'50',
                                  'descripcion'=>'Conexion Error Oracle adicionar',
                                  'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['carrera'],
                                  'afectado'=>$_REQUEST['codEstudiante']);
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
        $variable.=$retorno['parametros'];

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
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
    * Funcion que permite consultar el nombre del proyecto curricular
    * @param <int> $codProyecto Codigo del proyecto curricular
    * @return <string> $resultado_carrera Nombre del proyecto
    */
    function consultarNombreCarrera($codProyecto) {
        $cadena_sql = $this->sql->cadena_sql("nombre_carrera", $codProyecto);
        $resultado_carrera = $this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql, "busqueda");
        return $resultado_carrera[0]['NOMBRE'];
    }

    /**
     * Funcion que permite consultar el nombre de un espácio academico
     * @return <string>
     */
    function consultarNombreEspacio() {
        $cadena_sql=$this->sql->cadena_sql("nombreEspacio",$_REQUEST);
        $resultado_nombreEspacio=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda" );
        return $resultado_nombreEspacio[0]['NOMBREESPACIO'];
    }

    /**
     * Funcion que retorna los datos de un espacio si existe
     * @param <array> $datos ()
     * @return <array/string> 
     */
    function buscarDatosEspacio($datos) {
        $datosEspacio=$this->consultarEspacioPlan($datos);
        if($datosEspacio[0][0]>=1)
          {
              return $datosEspacio=$this->consultarDatosEspacio($datos);
          }
          else
          {
            return "No se pueden obtener datos del espacio";
          }
    }

    /**
     * Funcion que verifica si existen datos del espacio en el plan de estudios
     * @param <array> $datos
     * @return <array> 
     */
    function consultarEspacioPlan($datos) {
        $cadena_sql=$this->sql->cadena_sql("espacios_planEstudio",$datos);
        return $resultado_espacio=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda" );
    }

    /**
     * Funcion que consulta los datos del espacio academico en el plan de estudios
     * @param <array> $datos
     * @return <array> 
     */
    function consultarDatosEspacio($datos) {
        $cadena_sql=$this->sql->cadena_sql("datosEspacio",$datos);
        return $resultado_espacio=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"busqueda" );
    }

    /**
     * Funcion que registra la inscripcion del espacio en MySQL
     * @param <array> $datos
     * @return <int>
     */
    function adicionarEspacioMysql($datos) {
        $cadena_sql_adicionarMysql=$this->sql->cadena_sql("adicionar_espacio_mysql",$datos);
        $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql_adicionarMysql,"");
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }

    /**
     * Funcion que registra la inscripcion del espacio en Oracle
     * @param <array> $datos
     * @return <int>
     */
    function adicionarOracle($datos) {
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_espacio_oracle",$datos);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

    /**
     * Funcion que registra la inscripcion del espacio en MyOracle
     * @param <array> $datos
     * @return <int>
     */
    function adicionarMyOracle($datos) {
        $cadena_sql_adicionar=$this->sql->cadena_sql("adicionar_espacio_oracle",$datos);
        $resultado_adicionar=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql_adicionar,"");
        return $this->totalAfectados($this->configuracion, $this->accesoMyOracle);
    }

 /**
     * Funcion que consulta los inscritos
     * @return <int>
     */

    function consultarCupoInscritos($datos) {
        $cadena_sql=$this->sql->cadena_sql("consultar_cupo_inscritos", $datos);
        $resultado_carreraGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_carreraGrupo[0]['INSCRITOS'];
    }

    /**
     * Funcion que actualiza el cupo en un grupo
     * @return <boolean>
     */
    function actualizarCupoMyOracle($datos) {
        $cadena_sql=$this->sql->cadena_sql("actualiza_cupo", $datos);
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoMyOracle);
    }

}

?>