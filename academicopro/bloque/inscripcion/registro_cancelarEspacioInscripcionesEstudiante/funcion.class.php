<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");

class funcion_registroCancelarEspacioInscripcionesEstudiante extends funcionGeneral {
  private $configuracion;
  private $ano;
  private $periodo;
  private $datosEstudiante;
  private $datosInscripcion;

//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql)
    {
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->validacion= new validarInscripcion();
        $this->procedimientos=new procedimientos();
        //$this->tema=$tema;
        $this->sql=$sql;

         //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"estudianteCred");

        //Conexion Distribuida - se evalua la variable $configuracion['dbdistribuida']
        //donde si es =1 la conexion la realiza a Mysql, de lo contrario la realiza a ORACLE
        if($configuracion["dbdistribuida"]==1){
            $this->accesoMyOracle = $this->conectarDB($configuracion, "estudianteMy");
        }else{
            $this->accesoMyOracle = $this->accesoOracle;
        }
        //Datos de sesion
        $this->formulario="registro_cancelarEspacioInscripcionesEstudiante";
        $this->bloque="inscripcion/registro_cancelarEspacioInscripcionesEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');//echo $cadena_sql;exit;
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
     * Esta funcion permite realizar la verificacion y confirmacion de cancelar un espacio academico
     */
    function cancelarInscripcion(){
        if ($this->usuario!=$_REQUEST['codEstudiante'])
        {
            echo "Ha ocurrido un error. Por favor inicie sesion nuevamente ";exit;
        }
        $this->datosEstudiante=$this->consultarDatosEstudiante($this->usuario);
        $this->datosInscripcion=$_REQUEST;
        $mensaje=isset($mensaje)?$mensaje:'';
        //verificar si es estudiante de primer semestre
        $this->validarPrimerSemestre();
        //verifica reprobado
        $this->buscarReprobado();
        //solicitar confirmacion
        $this->solicitarConfirmacion();
        exit;
    }

    /**
     * Funcion que verifica si el estudiante es de primer semestre para no permitir la cancelacion de espacios
     * @return string
     */
    function validarPrimerSemestre(){
        if ($this->periodo==3)
        {
          $inicioCodigoEstudiante=$this->ano.'2';
        }else
            {
                $inicioCodigoEstudiante=$this->ano.$this->periodo;
            }
        if ($inicioCodigoEstudiante==substr($this->datosEstudiante[0]['CODIGO'],'0','5'))
        {
            $nuevo='ok';
        }
        else
            {
                $nuevo='no';
            }
        if($nuevo=='ok')
        {
            $mensaje="El espacio académico ".$this->datosInscripcion['nombreEspacio']." no se puede cancelar. El estudiante es de primer semestre";
            $this->enlaceNoCancelar($mensaje);
            exit;
        }
    }

    /**
     * Funcion que verifica si un espacio ha sido reporbado para no permitir su cancelacion
     */
    function buscarReprobado() {
        $espacios=json_decode($this->datosEstudiante[0]['ESPACIOS_POR_CURSAR'], true);
        foreach ($espacios as $espacio) {
            if ($espacio['CODIGO']==$this->datosInscripcion['codEspacio']&&$espacio['REPROBADO']==1)
            {
                $mensaje="El espacio académico ".$this->datosInscripcion['nombreEspacio']." ha sido reprobado. No se puede cancelar.";
                $this->enlaceNoCancelar($mensaje);
                exit;
            }
        }
    }
    
    /**
     * Funcion que permite encriptar un enlace
     * @param string $variable
     * @param string $opcion
     * @return string 
     */
    function encriptarEnlace($variable,$opcion) {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable.="&opcion=".$opcion;
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        return $pagina.$variable;
    }    
    
    /**
     * Funcion que genera el mensaje de solicitud de confirmacion
     * 
     */
    function solicitarConfirmacion() {
        ?>
        <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
        <?
          $this->celdaMensajes();
          $this->formularioConfirmacion();
        ?>
        </table>
        <?
    }

    /**
     * Funcion que genera los mensajes de confirmacion de cancelacion
     * 
     */
    function mensajesConfirmacion() {
        ?>
        <b>Recuerde que si cancela un Espacio Acad&eacute;mico <br>&eacute;ste no se puede volver a inscribir en el presente semestre. <br>(Según Reglamento Estudiantil)</br></b>
        <br>
        <br> ¿Est&aacute; seguro que desea cancelar <b><? echo $this->datosInscripcion['nombreEspacio'] ?></b>?<?
    }

 
    /**
     * Funcion que genera la celda donde se colocan mensajes de confirmacion de cancelacion
     * 
     */
    function celdaMensajes() {
      ?>
          <tr class="texto_subtitulo">
              <td colspan="2" align="center"><?
              $this->mensajesConfirmacion();
              ?></td>
          </tr>
      <?
    }

    /**
     * Funcion que genera el formulario para confirmar o cancelar la cancelacion
     */
    function formularioConfirmacion() {
      ?>
          <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
            <tr class="texto_subtitulo">
              <td align="center">
                  <?
                    $opciones=$this->variablesCancelar();
                    $enlace=$this->encriptarEnlace($opciones,"confirmarCancelacion");
                    $array=array('tipo'=>'image','nombre'=>'aceptar','icono'=>'clean.png','texto'=>'Si','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
              <td align="center">
                  <?
                    $enlace=$this->encriptarEnlace($opciones,"cancelarConfirmacion");
                    $array=array('tipo'=>'image','nombre'=>'cancelar','icono'=>'x.png','texto'=>'No','opciones'=>$enlace);
                    $this->botonEnlace($array)
                  ?>
              </td>
            </tr>
          </form>
      <?
    }

    /**
     * Funcion que genera las variables para los enlaces de confirmacion y cancelacion
     */
    function variablesCancelar() {
        unset ($_REQUEST['pagina']);
        $variable="pagina=".$this->formulario;
        $_REQUEST['action']=$this->bloque;
        foreach ($_REQUEST as $key => $value)
        {
            $variable.="&".$key."=".$value;
        }
        return $variable;
    }

    /**
     * Funcion que genera botones de enlace
     * @param <array> $array 
     */
    function botonEnlace($array) {
      ?>
        <a href="<?echo $array['opciones']; ?>" >
            <img src="<? echo $this->configuracion["site"].$this->configuracion["grafico"];?>/<?echo $array['icono']?>" border="0" width="30" height="30"><br><?echo $array['texto']?>
        </a>
      <?
    }


    /**
     * Esta funcion realiza la cancelacion de un espacio academico.
     */
    function cancelarCreditos() {
        $this->datosInscripcion=$_REQUEST;
        $this->datosEstudiante=$this->consultarDatosEstudiante($this->usuario);        
        //verifica si el espacio esta inscrito
        $retorno['mensaje']='';
        $espaciosInscritos=$this->buscarEspaciosInscritos();
        $this->verificarInscrito($espaciosInscritos);
        $variables=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                            'codProyecto'=>$this->datosEstudiante[0]['COD_CARRERA'],
                            'codEspacio'=>$this->datosInscripcion['codEspacio'],
                            'id_grupo'=>$this->datosInscripcion['id_grupo'],
                            'ano'=>$this->ano,
                            'periodo'=>$this->periodo);
        $canceladoOracle=$this->cancelarEspacio($variables);
        //si se puede cancelar en ORACLE busca el registro en MySQL
        if($canceladoOracle==1)
        {
            $this->registrarCancelacion($variables);
            $this->actualizarInscripcionesSesion();
            $this->procedimientos->actualizarCupo($this->datosInscripcion);
            //inserta registro de evento
            $variablesRegistro=array('usuario'=>$this->usuario,
                                    'evento'=>'2',
                                    'descripcion'=>'Cancela Espacio académico',
                                    'registro'=>$this->ano."-".$this->periodo.", ".$this->datosInscripcion['codEspacio'].", ".$this->datosInscripcion['id_grupo'].", 0, ".$this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosEstudiante[0]['COD_CARRERA'],
                                    'afectado'=>  $this->datosEstudiante[0]['CODIGO']);
            $mensaje='El Espacio Académico '.$this->datosInscripcion['nombreEspacio'].' ha sido cancelado';

        }else
            {
                $mensaje='La base de datos se encuentra ocupada. El Espacio Académico '.$this->datosInscripcion['nombreEspacio'].' no ha sido cancelado';
                $variablesRegistro=array('usuario'=>$this->usuario,
                                        'evento'=>'50',
                                        'descripcion'=>'Conexion Error Oracle cancelacion',
                                        'registro'=>$this->ano."-".$this->periodo.", ".$this->datosInscripcion['codEspacio'].", 0, ".$this->datosInscripcion['id_grupo'].", ".$this->datosEstudiante[0]['PLAN_ESTUDIO'].", ".$this->datosEstudiante[0]['COD_CARRERA'],
                                        'afectado'=>$this->datosEstudiante[0]['CODIGO']);
            }
            //inserta en log de eventos
        $this->procedimientos->registrarEvento($variablesRegistro);
        $this->actualizarCanceladosTablaCarga();
        
        $this->enlaceNoCancelar($mensaje);
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
     * Funcion que valida si el espacio se encuentra inscrito
     * @param type $espaciosInscritos 
     */
    function verificarInscrito($espaciosInscritos) {
        //verifica si el espacio ya ha sido inscrito
        $inscrito='0';
        foreach ($espaciosInscritos as $inscritos)
        {
            if($inscritos['CODIGO']==$this->datosInscripcion['codEspacio'])
            {
                $inscrito='ok';
                break;
            }
        }
        if($inscrito!='ok')
        {
            $mensaje="El espacio académico no se encuentra inscrito. No se puede cancelar.";
            $this->enlaceNoCancelar($mensaje);
        }
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
     * Funcion que actualiza el registro de inscripciones de la sesion del estudiante
     */
    function actualizarCanceladosTablaCarga() {
        $cancelados=json_decode($this->datosEstudiante[0]['CANCELADOS']);
        $cancelados[]=$this->datosInscripcion['codEspacio'];
        $cadenaCancelados=json_encode($cancelados);
        $variables=array('codEstudiante'=>$this->datosEstudiante[0]['CODIGO'],
                        'codProyectoEstudiante'=>  $this->datosEstudiante[0]['COD_CARRERA'],
                        'ano'=>  $this->datosEstudiante[0]['ANO'],
                        'periodo'=>  $this->datosEstudiante[0]['PERIODO'],
                        'cadena'=>$cadenaCancelados);
        $cadena_sql=$this->sql->cadena_sql("actualizar_cancelados_tabla", $variables);
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
        
    }
    

    /**
     * Funcion que cancela espacio en Oracle
     * @return <boolean>
     */
    function cancelarEspacio($variables) {
        $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_oracle", $variables);
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

    /**
     * Funcion que registra la cancelacion en MySQL
     * @return <array>
     */
    function registrarCancelacion($variables) {
        $cadena_sql=$this->sql->cadena_sql("registrar_cancelacion", $variables);
        return $insertarRegistroCancelacion=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
    }

    
    /**
     * Funcion que permite retornar al cancelar la confirmacion
     */
    function cancelarConfirmacion() {
        $this->datosInscripcion=$_REQUEST;
        $this->enlaceNoCancelar();
    }

    /**
     * Esta funcion se utiliza para retornar cuando se cancela la cancelacion del espacio academico
     * @param <string> $mensaje Mensaje que presenta al no poder realizar la cancelacion
     */
    function enlaceNoCancelar($mensaje='')
    {
        if ($mensaje!='')
        {
            echo "<script>alert ('$mensaje');</script>";
        }
        else
            {

            }
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$this->datosInscripcion['retorno'];
        $variable.="&opcion=".$this->datosInscripcion['opcionRetorno'];
        $variable.="&codProyectoEstudiante=".  $this->datosEstudiante[0]['COD_CARRERA'];
        $variable.="&planEstudioEstudiante=".  $this->datosEstudiante[0]['PLAN_ESTUDIO'];
        $variable.="&codEstudiante=".  $this->datosEstudiante[0]['CODIGO'];
        
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
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

