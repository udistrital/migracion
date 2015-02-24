<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");


class funcion_registroCambiarGrupoInscripcionesEstudiante extends funcionGeneral
{
  private $configuracion;
  private $ano;
  private $periodo;
  private $datosEstudiante;
  private $datosInscripcion;

  //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql)
    {
            //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
            //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
            //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

            $this->configuracion=$configuracion;
            $this->cripto=new encriptar();
            $this->validacion=new validarInscripcion();
            $this->procedimientos=new procedimientos();
            //$this->tema=$tema;
            $this->sql=$sql;


            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");
            //Conexion SGA
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");
            //Conexion ORACLE
            $this->accesoOracle=$this->conectarDB($configuracion,"estudiante");
            //conexion distribuida 1 conecta a MySUDD de lo contrario conecta a ORACLE
            if ($configuracion['dbdistribuida']==1)
                {
                    $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
                }
                else
                    {
                        $this->accesoMyOracle = $this->accesoOracle;
                    }

            //Datos de sesion
            $this->formulario="registro_cambiarGrupoInscripcionesEstudiante";
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
            $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
            $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
            $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
            $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            $this->ano=$resultado_periodo[0]['ANO'];
            $this->periodo=$resultado_periodo[0]['PERIODO'];
    }

    /*
     * Esta función registra el cambio de grupo de un estudiante de posgrado
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto;planEstudio,codEspacio,codProyectoEstudiante,planEstudioEstudiante,
     *                          codEstudiante,grupo,carrera,grupoAnterior,retorno,opcionRetorno)
     */

    function cambiarDeGrupo() {
        if ($this->usuario!=$_REQUEST['codEstudiante'])
        {
            echo "Ha ocurrido un error. Por favor inicie sesion nuevamente ";exit;
        }
        $this->datosEstudiante=$this->consultarDatosEstudiante($this->usuario);
        $this->datosInscripcion=$_REQUEST;
/*
 ["pagina"]=> string(44) "registro_cambiarGrupoInscripcionesEstudiante"
 * ["opcion"]=> string(14) "cambiarDeGrupo"
 * ["retorno"]=> string(38) "admin_consultarInscripcionesEstudiante"
 * ["opcionRetorno"]=> string(15) "mostrarConsulta"
 * ["codProyecto"]=> string(2) "15"
 * ["planEstudio"]=> string(3) "202"
 * ["codEstudiante"]=> string(11) "20092015028"
 * ["codProyectoEstudiante"]=> string(0) ""
 * ["planEstudioEstudiante"]=> string(0) ""
 * ["estado_est"]=> string(1) "A"
 * ["codEspacio"]=> string(2) "20"
 * ["nombreEspacio"]=> string(36) "FÍSICA III: ONDAS Y FÍSICA MODERNA"
 * ["creditos"]=> string(1) "3"
 * ["grupo"]=> string(2) "81"
 * ["cupo"]=> string(2) "30"
 * ["carrera"]=> string(2) "20"
 * ["grupoAnterior"]=> string(2) "27" } 
 */        
        $retorno=array();
        $retorno['pagina']=$_REQUEST["retorno"];
        $retorno['opcion']=$_REQUEST["opcionRetorno"];
        $retorno['parametros']="&codProyecto=".$_REQUEST["codProyecto"];
        $retorno['parametros'].="&planEstudio=".$_REQUEST["planEstudio"];
        $retorno['parametros'].="&codProyectoEstudiante=".$_REQUEST["codProyectoEstudiante"];
        $retorno['parametros'].="&planEstudioEstudiante=".$_REQUEST["planEstudioEstudiante"];
        $retorno['parametros'].="&codEstudiante=".$_REQUEST["codEstudiante"];
        $retorno['parametros'].="&codEspacio=".$_REQUEST["codEspacio"];
        $retorno['parametros'].="&grupo=".$_REQUEST['grupoAnterior'];
        //verifica si el espacio esta inscrito
        $espaciosInscritos=$this->buscarEspaciosInscritos();
        $this->verificarInscrito($retorno,$espaciosInscritos);
        $espaciosInscritos=$this->restarEspacioActual($espaciosInscritos);
        $horarioEstudiante=$this->buscarHorarioEstudiante($espaciosInscritos);
        $horarioGrupo=$this->buscarHorarioGrupo();
        $this->verificarCruce($retorno,$horarioEstudiante,$horarioGrupo);
        $this->verificarCupo($retorno);
        //realiza cambio en Oracle
        $cambioOracle=$this->registrarCambioGrupo($retorno);

    }

    /**
     * Funcion que presenta mensaje si hay cruce de horario
     * @param <array> $retorno (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,codEspacio,grupo)
)
     */
    function enlaceNoCambio($retorno) {
        echo "<script>alert ('".$retorno['mensaje']."');</script>";
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$retorno['pagina'];
        $variable.="&opcion=".$retorno['opcion'];
        $variable.=$retorno['parametros'];

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
     * Funcion que valida si el espacio ya ha sido inscrito
     * @param <array> $retorno (pagina,opcion,parametros,nombre_espacio)
     * @param <array> $_REQUEST (pagina,opcion,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function verificarInscrito($retorno,$espaciosInscritos) {
        //verifica si el espacio ya ha sido inscrito
        $inscrito='0';
        foreach ($espaciosInscritos as $inscritos)
        {
            if($inscritos['CODIGO']==$this->datosInscripcion['codEspacio'])
            {
                $inscrito='ok';
                if ($inscritos['ID_GRUPO']!=$this->datosInscripcion['id_grupoAnterior'])
                {
                    $inscrito=$inscritos['GRUPO'];
                    break;
                }
            }
        }
        if($inscrito!='ok')
        {
            $retorno['mensaje']="El espacio académico no se encuentra inscrito en el grupo ".$this->datosInscripcion['grupoAnterior'].". No se puede cambiar de grupo.";
            $this->enlaceNoCambio($retorno);
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
        $this->enlaceNoCambio($retorno);
        exit;
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
        $retorno['mensaje']="El grupo presenta sobrecupo: Cupo: ".$sobrecupo['cupo'].". Disponibles: 0.  No se ha realizado la inscripción.";
        $this->enlaceNoCambio($retorno);
      }
    }

    /**
     * Funcion que quita el espacio actual de los inscritos
     * @param array $espaciosInscritos
     * @return array
     */
    function restarEspacioActual($espaciosInscritos) {
        foreach ($espaciosInscritos as $fila => $espacio)
        {
            if($espacio['CODIGO']==$this->datosInscripcion['codEspacio'])
            {
                unset ($espaciosInscritos[$fila]);
            }
        }
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
                        'ID_GRUPO'=>$this->datosInscripcion['id_grupo'],
                        'HOR_ALTERNATIVO'=>$this->datosInscripcion['hor_alternativo'])
            );
        $horarioGrupo=$this->procedimientos->buscarHorario($variables);
        return $horarioGrupo;
    }
    
    /**
     *  funcion que ejecuta el retorno
     * @param <string> $pagina
     * @param <string> $variable 
     */
    function enlaceParaRetornar($pagina,$variable) {
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }

    /**
     * Funcion que actualiza registro de oracle por cambio de grupo
     * @return <int>
     */
    
/*
 ["pagina"]=> string(44) "registro_cambiarGrupoInscripcionesEstudiante"
 * ["opcion"]=> string(14) "cambiarDeGrupo"
 * ["retorno"]=> string(38) "admin_consultarInscripcionesEstudiante"
 * ["opcionRetorno"]=> string(15) "mostrarConsulta"
 * ["codProyecto"]=> string(2) "15"
 * ["planEstudio"]=> string(3) "202"
 * ["codEstudiante"]=> string(11) "20092015028"
 * ["codProyectoEstudiante"]=> string(0) ""
 * ["planEstudioEstudiante"]=> string(0) ""
 * ["estado_est"]=> string(1) "A"
 * ["codEspacio"]=> string(2) "20"
 * ["nombreEspacio"]=> string(36) "FÍSICA III: ONDAS Y FÍSICA MODERNA"
 * ["creditos"]=> string(1) "3"
 * ["grupo"]=> string(2) "81"
 * ["cupo"]=> string(2) "30"
 * ["carrera"]=> string(2) "20"
 * ["grupoAnterior"]=> string(2) "27" } 
 */        
    
    function registrarCambioGrupo($retorno) {
        //retorno
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$retorno['pagina'];
        $variable.="&opcion=".$retorno['opcion'];
        $variable.=$retorno['parametros'];
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        
        $datos['codEstudiante']=$this->datosEstudiante[0]['CODIGO'];
        $datos['codEspacio']=$this->datosInscripcion['codEspacio'];
        $datos['grupoAnterior']=$this->datosInscripcion['grupoAnterior'];
        $datos['id_grupoAnterior']=$this->datosInscripcion['id_grupoAnterior'];
        $datos['id_grupo']=$this->datosInscripcion['id_grupo'];
        $datos['hor_alternativo']=$this->datosInscripcion['hor_alternativo'];
        $datos['ano']=$this->ano;
        $datos['periodo']=$this->periodo;
        $cambioGrupo=$this->actualizarGrupoEspacio($datos);
        
        if($cambioGrupo>=1)
        {
            $mensaje='';
            $this->actualizarInscripcionesSesion();
            //actualiza cupo a grupo nuevo
            $this->procedimientos->actualizarCupo($datos);
            //actualiza cupo a grupo anterior
            $datos['grupo']=$datos['grupoAnterior'];
            $this->procedimientos->actualizarCupo($datos);
            $variablesRegistro=array('usuario'=>$this->usuario,
                                      'evento'=>'3',
                                      'descripcion'=>'Cambio grupo del Espacio Académico',
                                      'registro'=>$this->ano."-".$this->periodo.", ".$datos['codEspacio'].", ".$this->datosInscripcion['id_grupoAnterior'].", ".$this->datosInscripcion['id_grupo'].", ".$this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosInscripcion['carrera'],
                                      'afectado'=>$this->datosEstudiante[0]['CODIGO']);
        }
        else
          {
              $mensaje="En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde";
              $variablesRegistro=array('usuario'=>$this->usuario,
                                        'evento'=>'50',
                                        'descripcion'=>'Conexion Error Oracle Cambio de Grupo',
                                        'registro'=>$this->ano."-".$this->periodo.", ".$datos['codEspacio'].", ".$this->datosInscripcion['id_grupoAnterior'].", ".$this->datosInscripcion['id_grupoAnterior'].", ".$this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosEstudiante[0]['COD_CARRERA']."",
                                        'afectado'=>$this->datosEstudiante[0]['CODIGO']);
          }
          $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);        
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
     * Funcion que actualiza grupo de inscripcion en la base de datos
     * @param array $datos
     * @return int 
     */
    function actualizarGrupoEspacio($datos) {
        $cadena_sql=$this->sql->cadena_sql("actualizar_grupo_espacio", $datos);
        $resultado_actualizarGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }
    
    /**
     * Funcion que consulta los datos del estudiante en tabla de carga
     * @param int $codEstudiante
     * @return array 
     */
    function consultarDatosEstudiante($codEstudiante){
        $variables =array('codEstudiante'=>$codEstudiante,
                        'ano'=>$this->ano,
                        'periodo'=>$this->periodo);
        $cadena_sql=$this->sql->cadena_sql("carga", $variables);
        return $registroCreditosGeneral=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }

}


?>