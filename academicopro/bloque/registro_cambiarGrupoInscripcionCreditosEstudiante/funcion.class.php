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
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/procedimientos.class.php");


class funcion_registroCambiarGrupoInscripcionCreditosEstudiante extends funcionGeneral
{
  private $configuracion;
  private $ano;
  private $periodo;

  //Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql)
    {
            //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
            //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
            include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

            $this->configuracion=$configuracion;
            $this->cripto=new encriptar();
            $this->validacion=new validarInscripcion();
            $this->procedimiento=new procedimientos();
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
            $this->formulario="registro_cambiarGrupoInscripcionCreditosEstudiante";
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

    function cambiarGrupo() {
        $retorno['pagina']=$_REQUEST["retorno"];
        $retorno['opcion']=$_REQUEST["opcionRetorno"];
        $retorno['parametros']="&codProyecto=".$_REQUEST["codProyecto"];
        $retorno['parametros'].="&planEstudio=".$_REQUEST["planEstudio"];
        $retorno['parametros'].="&codProyectoEstudiante=".$_REQUEST["codProyectoEstudiante"];
        $retorno['parametros'].="&planEstudioEstudiante=".$_REQUEST["planEstudioEstudiante"];
        $retorno['parametros'].="&codEstudiante=".$_REQUEST["codEstudiante"];
        $retorno['parametros'].="&codEspacio=".$_REQUEST["codEspacio"];
        $retorno['parametros'].="&grupo=".$_REQUEST['grupoAnterior'];
        $_REQUEST['ano']=$this->ano;
        $_REQUEST['periodo']=$this->periodo;
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable="pagina=".$retorno['pagina'];
        $variable.="&opcion=".$retorno['opcion'];
        $variable.=$retorno['parametros'];
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";

        //verifica si el espacio esta inscrito
        $inscrito=$this->validacion->validarEspacioInscrito($_REQUEST);
        if($inscrito=='ok')
        {
          $retorno['mensaje']="El registro ha sido eliminado. No se puede actualizar.";
          $this->enlaceNoCambio($retorno);
        }
        //verifica cruce
        $proyecto=$_REQUEST['codProyecto'];
        $_REQUEST['codProyecto']=$_REQUEST['carrera'];
        $cruce=$this->validacion->validarCruceHorario($_REQUEST);
        if ($cruce!='ok' && is_array($cruce))
        {
          $retorno['mensaje']="El horario del espacio académico presenta cruce con el horario del estudiante. No se ha realizado el cambio de grupo.";
          $this->enlaceNoCambio($retorno);
        }
        $_REQUEST['codProyecto']=$proyecto;
        //verifica cupo en el grupo
        $sobrecupo=$this->validacion->validarSobrecupo($_REQUEST);
        if($sobrecupo!='ok' && is_array($sobrecupo))
        {
          $retorno['mensaje']="El grupo presenta sobrecupo: Cupo:".$sobrecupo['cupo']." Inscritos:".$sobrecupo['inscritos']." Disponibles:".$sobrecupo['disponibles'].". No se ha realizado el cambio de grupo.";
          $this->enlaceNoCambio($retorno);
        }

        //realiza cambio en Oracle
        $cambioOracle=$this->cambiarGrupoEspacioEstudianteOracle();
        if($cambioOracle>=1)
        {
                //actualiza cupo a grupo nuevo
                $this->procedimiento->actualizarCupo($_REQUEST);
                //evalua si existen BD distribuida, para realizar el registro en MySUDD
                if($this->configuracion["dbdistribuida"]==1){
                    $cambioMyOracle=$this->cambiarGrupoEspacioEstudianteMyOracle();
                    $_REQUEST['nvo_inscritos']=$this->consultarCupoInscritos();
                    $actualizaCupo = $this->actualizarCupoMyOracle();
                }
                 //busca el registro en MySql
                $existe_espacio_mysql=$this->consultarInscritoMysql();
               if (is_array($existe_espacio_mysql))
                {   //actualiza el registro en MySql con el cambio de grupo
                    $registros_actualizados = $this->actualizarRegistroEspacioEstudianteMysql();
                }
                else
                {   //inserta el registro en MySql con el cambio de grupo
                    $registros_insertados = $this->insertarRegistroCambioGrupoEstudianteMysql();
                }
                $variablesRegistro=array('usuario'=>$this->usuario,
                                          'evento'=>'3',
                                          'descripcion'=>'Cambio grupo del Espacio Académico',
                                          'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",".$_REQUEST['grupoAnterior'].",".$_REQUEST['grupo'].", ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['carrera'],
                                          'afectado'=>$_REQUEST['codEstudiante']);

                //actualiza cupo a grupo anterior
                $grupo=$_REQUEST['grupo'];
                $_REQUEST['grupo']=$_REQUEST['grupoAnterior'];
                $this->procedimiento->actualizarCupo($_REQUEST);
                if($this->configuracion["dbdistribuida"]==1){
                    $_REQUEST['nvo_inscritos']=$this->consultarCupoInscritos();
                    $actualizaCupo = $this->actualizarCupoMyOracle();
                }
                $mensaje="Se realizó el cambio de grupo";

        }
        else
          {
              $mensaje="En este momento la base de datos O se encuentra ocupada, por favor intente mas tarde";
              $variablesRegistro=array('usuario'=>$this->usuario,
                                        'evento'=>'50',
                                        'descripcion'=>'Conexion Error Oracle Cambio de Grupo',
                                        'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", ".$_REQUEST['grupoAnterior'].", ".$_REQUEST['grupoAnterior'].", ".$_REQUEST['planEstudioEstudiante'].", ".$_REQUEST['carrera'],
                                        'afectado'=>$_REQUEST['codEstudiante']);
          }
          $this->retornar($pagina,$variable,$variablesRegistro,$mensaje);
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
        $this->procedimiento->registrarEvento($variablesRegistro);
        $this->enlaceParaRetornar($pagina, $variable);
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
    function cambiarGrupoEspacioEstudianteOracle() {
        $cadena_sql=$this->sql->cadena_sql("actualizar_grupo_espacio_oracle", $_REQUEST);
        $resultado_actualizarGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }
    
    /**
     * Funcion que actualiza registro de MyOracle por cambio de grupo
     * @return <int>
     */
    function cambiarGrupoEspacioEstudianteMyOracle() {
        $cadena_sql=$this->sql->cadena_sql("actualizar_grupo_espacio_oracle", $_REQUEST);
        $resultado_actualizarGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoMyOracle);
      }
    /**
     *  Funcion que consulta si un registro de inscripcion existe en Mysql
     * @return <array>
     */
    function consultarInscritoMysql() {
        $cadena_sql=$this->sql->cadena_sql("buscar_espacio_mysql", $_REQUEST);
        return $resultado_EspacioMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }

    /**
     * Funcion que actualiza registro de Mysql por cambio de grupo
     * @return <int>
     */
    function actualizarRegistroEspacioEstudianteMysql() {
        $cadena_sql=$this->sql->cadena_sql("actualizar_grupo_espacio_mysql", $_REQUEST);
        $resultado_actualizarGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }

    /**
     * Funcion que inserta regitro por cambio de grupo en Mysql
     * @return <int>
     */
    function insertarRegistroCambioGrupoEstudianteMysql() {
      $cadena_sql=$this->sql->cadena_sql("registrar_actualizar_espacio_mysql", $_REQUEST);
      $resultado_insertarRegistroCancelado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
      return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }

 /**
     * Funcion que consulta los inscritos
     * @return <int>
     */

    function consultarCupoInscritos() {
        $cadena_sql=$this->sql->cadena_sql("consultar_cupo_inscritos", $_REQUEST);
        $resultado_carreraGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        return $resultado_carreraGrupo[0]['INSCRITOS'];
    }

    /**
     * Funcion que actualiza el cupo en un grupo
     * @return <boolean>
     */
    function actualizarCupoMyOracle() {
        $cadena_sql=$this->sql->cadena_sql("actualiza_cupo", $_REQUEST);
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoMyOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoMyOracle);
    }

}


?>