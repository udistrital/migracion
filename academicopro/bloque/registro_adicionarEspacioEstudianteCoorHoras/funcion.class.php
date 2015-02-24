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
class funcion_registroAdicionarEspacioEstudianteCoorHoras extends funcionGeneral {
  //Crea un objeto tema y un objeto SQL.
    private $configuracion;
    private $ano;
    private $periodo;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/administrarModulo.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/validacion/validaciones.class.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");


        $this->configuracion=$configuracion;
        $this->validacion=new validarInscripcion();
        $this->procedimientos=new procedimientos();
        $this->cripto=new encriptar();
        $this->tema=$tema;
        $this->sql=$sql;

        //Conexion ORACLE
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Datos de sesion
        $this->formulario="registro_adicionarEspacioEstudianteCoorHoras";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];

    }

    /**
     * Funcion que valida la inscripcion de un estudiante en un espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera)
     */
    function validarInscripcionEstudiante()
    {
        $back='';
        $datosInscripcion=$_REQUEST;
        $retorno['pagina']=$datosInscripcion['retornoPagina']="admin_consultarInscripcionEstudianteCoorHoras";
        $retorno['opcion']=$datosInscripcion['retornoOpcion']="mostrarConsulta";
        $datosInscripcion['retornoParametros']=array('codProyecto'=>$_REQUEST["codProyecto"],
                                                    'planEstudio'=>$_REQUEST["planEstudio"],
                                                    'codProyectoEstudiante'=>$_REQUEST["codProyectoEstudiante"],
                                                    'planEstudioEstudiante'=>$_REQUEST["planEstudioEstudiante"],
                                                    'codEstudiante'=>$_REQUEST["codEstudiante"]);
        foreach ($datosInscripcion['retornoParametros'] as $key => $value) {
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
                $this->verificarEspacioReprobado($datosInscripcion);
            }
        }else
          {
              
          }

        if (isset($_REQUEST['confirmaPlan'])&&$_REQUEST['confirmaPlan']==1)
        {
          $this->inscribirEstudiante($datosInscripcion);
        }
        else
          {
            if(isset($_REQUEST['cancelado'])&&$_REQUEST['cancelado']==1)
            {
              $this->verificarEspacioPlan($datosInscripcion);
            }
            else
              {
                if(isset($_REQUEST['requisitos'])&&$_REQUEST['requisitos']==1)
                {
                    $this->verificarCancelado($datosInscripcion);
                    $this->verificarEspacioPlan($datosInscripcion);
                }
                else
                    {
                        $this->verificarInscrito($retorno);
                        $this->verificarCruce($retorno);
                        $this->verificarSobrecupo($retorno);
                        $this->verificarRequisitos($datosInscripcion,$retorno);
                        $this->verificarCancelado($datosInscripcion);
                        $this->verificarEspacioPlan($datosInscripcion);
                    }
                    $this->inscribirEstudiante($datosInscripcion);
              }
          }

//si hay confirmación de cancelado pasa a varificar plan
//si hay confirmacion de planEstudio, pasa a registrar
        //si no hay, realiza validaciones

    }
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

    function verificarRequisitos($datosInscripcion,$retorno) {
      //verificar requisitos
      $requisitos=$this->validacion->validarRequisitos($_REQUEST);
      if($requisitos!='ok'&& is_array($requisitos))
      {
        $datosInscripcion['confirmacion']="requisitos";
        $mensaje="El estudiante no ha aprobado requisitos de ".$retorno['nombreEspacio'].":";
        foreach ($requisitos as $key => $value) {
          $mensaje.="<br>".$requisitos[$key]['NOMBRE']." Codigo:".$requisitos[$key]['REQUISITO']."  ";
        }
        $mensaje.="<br>¿Desea inscribirlo al estudiante?";
        $this->solicitarConfirmacion($mensaje,$datosInscripcion);
      }
    }

    function verificarSobrecupo($retorno) {
      //verifica cupo en el grupo
      $sobrecupo=$this->validacion->validarSobrecupo($_REQUEST);
      if($sobrecupo!='ok' && is_array($sobrecupo))
      {
        $retorno['mensaje']="El grupo presenta sobrecupo: Cupo:".$sobrecupo['cupo']." Inscritos:".$sobrecupo['inscritos']." Disponibles:".$sobrecupo['disponibles'].". No se ha realizado la inscripción";
        $this->enlaceNoAdicion($retorno);
      }
    }

    function verificarCancelado($datosInscripcion) {
      //verificar cancelado
      $cancelado=$this->validacion->validarCancelado($datosInscripcion);
      if($cancelado!='ok' && is_array($cancelado))
      {
        $datosInscripcion['opcion']="inscribir";
        $datosInscripcion['confirmacion']="cancelado";
        $mensaje="El espacio academico ha sido cancelado ".$cancelado['veces']." en el per&iacute;odo actual. ¿Desea inscribirlo nuevamente?";
        $this->solicitarConfirmacion($mensaje,$datosInscripcion);
      }
    }

    function verificarEspacioPlan($datosInscripcion) {
      //verifica si el espacio pertenece al plan de estudios del estudiante
      $planEstudio=$this->validacion->validarEspacioPlan($_REQUEST);
      if($planEstudio!='ok')
      {
        $datosInscripcion['opcion']="inscribir";
        $datosInscripcion['confirmacion']="confirmaPlan";
        $mensaje=$planEstudio[0][0];
        $mensaje.=" ¿Desea inscribirlo al estudiante?";
        $this->solicitarConfirmacion($mensaje,$datosInscripcion);
      }
    }

    /**
     * Funcion que permite verificar si un estudiante ha reprobado un espacio
     * @param <array> $datosInscripcion 
     */
    function verificarEspacioReprobado($datosInscripcion) {
      $reprobado=$this->validacion->validarReprobado($datosInscripcion);
      if ($reprobado!='ok' && is_array($reprobado))
      {
        $datosInscripcion['opcion']="inscribir";
        $datosInscripcion['confirmacion']="reprobado";
        $nombreEspacio=$this->consultarNombreEspacio();
        $mensaje="El estudiante en <b>Prueba Acad&eacute;mica</b> no ha reprobado <b>".$nombreEspacio."</b> c&oacute;digo <b>".$reprobado['espacio']."</b><br>";
        $mensaje.=" ¿Desea inscribirlo al estudiante?";
        $this->solicitarConfirmacion($mensaje,$datosInscripcion);
      }
    }

    /**
     * Funcion que realiza la inscripcion de un estudiante en un grupo de espacio academico
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEstudiante,estado_est,codEspacio,creditos,grupo,carrera,retornoPagina,retornoOpcion,
     *                           retornoParametros (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante) )
     */
    function inscribirEstudiante($datosInscripcion)
    {
        $mensaje='';
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
            $datosInscripcion['creditos']=(isset($resultado_espacio[0]['CREDITOS'])?$resultado_espacio[0]['CREDITOS']:'');
            $datosInscripcion['ht']=(isset($resultado_espacio[0]['HT'])?$resultado_espacio[0]['HT']:'');
            $datosInscripcion['hp']=(isset($resultado_espacio[0]['HP'])?$resultado_espacio[0]['HP']:'');
            $datosInscripcion['haut']=(isset($resultado_espacio[0]['HAUT'])?$resultado_espacio[0]['HAUT']:'');


            $resultado_adicionar=$this->adicionarOracle($datosInscripcion);
            if($resultado_adicionar>=1)
                {
                    $this->procedimientos->actualizarCupo($datosInscripcion);
                    $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'1',
                                              'descripcion'=>'Adiciona Espacio académico',
                                              'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],
                                              'afectado'=>$_REQUEST['codEstudiante']);
                }
                else
                    {
                        $mensaje="En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde.";
                        $this->borrarMysqlError($datosInscripcion);
                        $variablesRegistro=array('usuario'=>$this->usuario,
                                              'evento'=>'50',
                                              'descripcion'=>'Conexion Error Oracle adicionar',
                                              'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],
                                              'afectado'=>$_REQUEST['codEstudiante']);
                    }
        }
        else
        {
            $mensaje=$resultado_espacio;
            $variablesRegistro=array('usuario'=>$this->usuario,
                                  'evento'=>'50',
                                  'descripcion'=>'Conexion Error Oracle adicionar',
                                  'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],
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
     * Funcion que presenta mensaje para confirmar adicion por cancelacion de espacio
     * @param <array> $veces (VECES)
     * @param <array> $retorno (retornoPagina,retornoOpcion,retornoParametros)
     */
    function solicitarConfirmacion($mensaje,$retorno) {
          ?>
          <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
            <tr class="texto_subtitulo">
              <td  class="centrar" colspan="2">
                <?echo $mensaje?>
              </td>
            </tr>
            <tr class="texto_subtitulo">
              <td class="centrar"><?
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=".$this->formulario;
                foreach ($_REQUEST as $key => $value) {
                  $variable.="&".$key."=".$value;
                }
                $variable.="&opcion=".$retorno['opcion'];
                $variable.="&retornoPagina=".$retorno['retornoPagina'];
                $variable.="&retornoOpcion=".$retorno['retornoOpcion'];
                foreach ($retorno['retornoParametros'] as $key => $value) {
                  $variable.="&retornoParametros[".$key."]=".$value;
                }
                $variable.="&".$retorno['confirmacion']."=1";

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                ?>
                <a href="<?echo $pagina.$variable?>">
                  <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']."/clean.png";?>" width="35" height="35" border="0"><br>S&iacute;
                </a>
              </td>
              <td class="centrar"><?
                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                $variable="pagina=".$retorno['retornoPagina'];
                $variable.="&opcion=".$retorno['retornoOpcion'];
                foreach ($retorno['retornoParametros'] as $key => $value) {
                  $variable.="&".$key."=".$value;
                }

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                ?>
                <a href="<?echo $pagina.$variable?>">
                    <img src="<?echo $this->configuracion['site'].$this->configuracion['grafico']."/x.png";?>" width="35" height="35" border="0"><br>No
                </a>
              </td>
            </tr>
          </table>
          <?exit;
      
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
     * Funcion que borra el registro de MySQL por error en la conexion a Oracle al inscribir
     * @param <array> $datos
     * @return <int>
     */
    function borrarMysqlError($datos) {
        $cadena_sql=$this->sql->cadena_sql("borrar_datos_mysql_no_conexion", $datos);
        $resultado_adicionarMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }



}

?>