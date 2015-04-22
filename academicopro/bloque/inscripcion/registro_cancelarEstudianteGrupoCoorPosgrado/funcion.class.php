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

class funcion_registroCancelarEstudianteGrupoCoorPosgrado extends funcionGeneral {
  private $configuracion;
  private $ano;
  private $periodo;

//Crea un objeto tema y un objeto SQL.
    function __construct($configuracion, $sql)
    {
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->configuracion=$configuracion;
        $this->cripto=new encriptar();
        $this->validacion= new validarInscripcion();
        $this->procedimiento=new procedimientos();
        //$this->tema=$tema;
        $this->sql=$sql;

         //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");

            //Conexion sga
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Conexion Oracle
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");

        //Datos de sesion
        $this->formulario="registro_cancelarEstudianteGrupoCoorPosgrado";
        $this->bloque="inscripcion/registro_cancelarEstudianteGrupoCoorPosgrado";
        
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');//echo $cadena_sql;exit;
        $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
        $this->ano=$resultado_periodo[0]['ANO'];
        $this->periodo=$resultado_periodo[0]['PERIODO'];
    }

    function verificarCancelacion() {
      $numEstudiantes=$this->verificarSeleccionados();
      $this->solicitarConfirmacion($numEstudiantes);
      exit;

    }
  /**
   * Funcion que verifica si se han seleccionado estudiantes para cambio
   * @return int numero de estudaintes seleccionados
   */
  function verificarSeleccionados() {
    $i=0;
    foreach ($_REQUEST as $key => $value) {
      if (strstr($key,'codEstudiante'))
        {
          $i++;
        }
    }
      if($i<=0)
        {
          echo "<script>alert ('Debe seleccionar al menos un estudiante para cancelar inscripcion');</script>";
          $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
          $variable="pagina=admin_estudiantesInscritosGrupoCoorPosgrado";
          $variable.="&opcion=verGrupo";
          $variable.="&codProyecto=".$_REQUEST['codProyecto'];
          $variable.="&planEstudio=".$_REQUEST['planEstudio'];
          $variable.="&codEspacio=".$_REQUEST['codEspacio'];
          $variable.="&grupo=".$_REQUEST['grupo'];
          $variable.="&id_grupo=".$_REQUEST['id_grupo'];
          $variable.="&nivel=".$_REQUEST['nivel'];

          include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
          $this->cripto = new encriptar();
          $variable = $this->cripto->codificar_url($variable, $this->configuracion);
          echo "<script>location.replace('".$pagina.$variable."')</script>";
          exit;
        }
      else
        {
          return $i;
        }
  }

  
    
/*
 * Esta funcion permite realizar la verificacion y confirmacion de cancelar un espacio academico
 * @param <array> $this->configuracion
 * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
 *                            estado_est,codEspacio,nombreEspacio,creditos,grupo,retorno,opcionRetorno)
 */

    function solicitarConfirmacion($estudiantes)
    {
      $_REQUEST['ano']=$this->ano;
        $_REQUEST['periodo']=$this->periodo;
        if($estudiantes>1)
        {
          $cantidad="estudiantes";
        }
        else
        {
          $cantidad="estudiante";
        }
        $this->mensajeConfirmacion($estudiantes,$cantidad);
      //verificar cancelado

      //verificar reprobado

      //solicitar confirmacion
    }

    function mensajeConfirmacion($numEstudiantes,$cantidad) {
       
        ?>
      <table border='0' width='90%' cellpadding="2" cellspacing="2" align="center">
          <tr class="texto_subtitulo">
              <td colspan="2" align="center">
                    <br> ¿Est&aacute; seguro que desea cancelar a <b><?echo $numEstudiantes?></b> <?echo $cantidad?> del grupo <b><? echo $_REQUEST['id_grupo'] ?></b> de<br><b><? echo $_REQUEST['nombreEspacio'] ?></b> c&oacute;digo <b><? echo $_REQUEST['codEspacio'] ?></b>?
              </td>
          </tr>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<?echo $this->formulario?>'>
          <tr class="texto_subtitulo">
            <td align="center">
            <?$this->variablesFormulario()?>
              <input type="image" name="aceptar" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/clean.png" width="30" height="30"><br>Si
            </td>
            <td align="center">
              <input type="image" name="cancelar" src="<?echo $this->configuracion['site'].$this->configuracion['grafico']?>/x.png" width="30" height="30"><br>No
            </td>
          </tr>
        </form>
      </table>
      <?
    }

    function variablesFormulario() {
        unset ($_REQUEST['pagina']);
        unset ($_REQUEST['cancelar_x']);
        unset ($_REQUEST['cancelar_y']);
        $_REQUEST['opcion']="cancelarCreditos";
        $_REQUEST['action']=$this->bloque;
        foreach ($_REQUEST as $key => $value)
          {?>
            <input type="hidden" name="<?echo $key?>" value="<?echo $value?>"><?
          }
    }

   /*
    * Esta funcion realiza la cancelacion de un espacio academico.
    * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,
    *                           codEspacio,grupo,nombreEspacio,parametro,periodo,ano,retorno,opcionRetorno)
    */
    function cancelarCreditos() {
      $mensaje=array();
      $proyectos = $this->consultarProyectos($this->identificacion);
        
      //listado de estudiantes seleccionados para cancelacion
      foreach ($_REQUEST as $key => $value) {
        if (strstr($key, 'codEstudiante')) {
          $_REQUEST['codEstudiante']=$value;
          $datosEstudiante=$this->buscarDatosEstudiante($value);
          $_REQUEST['codProyectoEstudiante']=$datosEstudiante[0]['PROYECTO'];
          $_REQUEST['planEstudioEstudiante']=$datosEstudiante[0]['PLAN'];
          $proyecto_valido = $this->validarProyecto($proyectos, $_REQUEST['codProyectoEstudiante']);
          if($proyecto_valido==1){  
                  $mensaje[$_REQUEST['codEstudiante']]=$this->cancelarCreditosEstudiante();
          }else{
                $mensaje[$_REQUEST['codEstudiante']]='Proyecto curricular no valido para el usuario';
          }
        }
      }
      $datosActualizar=array('codEspacio'=>$_REQUEST['codEspacio'],'id_grupo'=>$_REQUEST['id_grupo']);
      $this->procedimiento->actualizarCupo($datosActualizar);

      $this->reporteGrupo($mensaje);
    }


    /**
     * Funcion que realiza cancelacion para un estudiante
     * @return string $resultado
     */
    function cancelarCreditosEstudiante() {
          $inscrito=$this->validacion->validarEspacioInscrito($_REQUEST);
          if(is_array($inscrito))
            {
              //cancelar en ORACLE
              $canceladoOracle=$this->cancelarEspacioEstudianteOracle();
              //si se puede cancelar en ORACLE busca el registro en MySQL
              if($canceladoOracle>=1)
              {
                $buscarInscritoMysql=$this->consultarInscritoMysql();
                //si existe el registro en MySQL lo actualiza
                if(is_array($buscarInscritoMysql))
                  {
                    $canceladoMysql=$this->actualizarRegistroEspacioEstudianteMysql();
                  }
                  else
                  {
                  //si no existe lo inserta
                    $canceladoMysql=$this->insertarRegistroCancelarEspacioEstudianteMysql();
                  }
              $variablesRegistro=array('usuario'=>$this->usuario,
                                  'evento'=>'2',
                                  'descripcion'=>'Cancela Espacio académico',
                                  'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",".$_REQUEST['id_grupo'].",".$_REQUEST['grupo'].", 0, ".$_REQUEST['planEstudio'].", ".$_REQUEST['codProyecto'],
                                  'afectado'=>$_REQUEST['codEstudiante']);
              $this->procedimiento->registrarEvento($variablesRegistro);

              $resultado='ok';
              }
              //si no se puede cancelar informa en el reporte
              else
              {
                $variablesRegistro=array('usuario'=>$this->usuario,
                                  'evento'=>'50',
                                  'descripcion'=>'Conexion Error Oracle cancelacion',
                                  'registro'=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",0, ".$_REQUEST['grupo'].",".$_REQUEST['id_grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['codProyecto'],
                                  'afectado'=>$_REQUEST['codEstudiante']);
                $this->procedimiento->registrarEvento($variablesRegistro);
                $resultado="No se ha podido cancelar";

              }
            }
            //si no esta inscrito
            else
              {
                  $resultado='El registro ha sido eliminado';
              }
       return $resultado;

    }

    /**
     * Funcion que cancela espacio en Oracle
     * @return <boolean> 
     */
    function cancelarEspacioEstudianteOracle() {
        $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_oracle", $_REQUEST);
        $resultado_cancelarOracle=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
    }

    /**
     * Funcion que consulta si el espacio esta inscrito en Mysql
     * @return <array> 
     */
    function consultarInscritoMysql() {
        $cadena_sql=$this->sql->cadena_sql("buscar_espacio_mysql", $_REQUEST);
        return $resultado_EspacioMysql=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
    }

    /**
     * Funcion que actualiza registro con estado de cancelado
     * @return <int>
     */
    function actualizarRegistroEspacioEstudianteMysql() {
      $cadena_sql=$this->sql->cadena_sql("cancelar_espacio_mysql", $_REQUEST);
      $resultado_actualizarRegistroCancelado=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
      return $this->totalAfectados($this->configuracion, $this->accesoGestion);

    }

    /**
     * Funcion que inserta registro de cancelacion en Mysql
     * @return <boolean> 
     */
    function insertarRegistroCancelarEspacioEstudianteMysql() {
      $cadena_sql=$this->sql->cadena_sql("registrar_cancelar_espacio_mysql", $_REQUEST);
      $resultado_insertarRegistroCambio=$this->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );
      return $this->totalAfectados($this->configuracion, $this->accesoGestion);
    }

    /**
     * Esta funcion se utiliza para retornar cuando se cancela la cancelacion del espacio academico
     * @param <string> $mensaje Mensaje que presenta al no poder realizar la cancelacion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,
     *                           codEspacio,grupo,retorno,opcionRetorno)
     */
    function cancelar($mensaje='')
    {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=".$_REQUEST['retorno'];
        $variable.="&opcion=".$_REQUEST['opcionRetorno'];
        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
        $variable.="&codProyectoEstudiante=".(isset($_REQUEST['codProyectoEstudiante'])?$_REQUEST['codProyectoEstudiante']:$_REQUEST['codProyecto']);
        $variable.="&planEstudioEstudiante=".(isset($_REQUEST['planEstudioEstudiante'])?$_REQUEST['planEstudioEstudiante']:'');
        $variable.="&codEstudiante=".(isset($_REQUEST['codEstudiante'])?$_REQUEST['codEstudiante']:'');
        $variable.="&codEspacio=".$_REQUEST["codEspacio"];
        $variable.="&grupo=".$_REQUEST['grupo'];
        $variable.="&id_grupo=".$_REQUEST['id_grupo'];
        $variable.="&nivel=".$_REQUEST['nivel'];
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

        echo "<script>location.replace('".$pagina.$variable."')</script>";
    }

    /**
     * Funcion que genera arreglo para enviar al reporte
     * @param <array> $param (codEstudiante=>reporte)
     */
    function reporteGrupo($param) {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_estudiantesInscritosGrupoCoorPosgrado";
        $variable.="&opcion=verGrupo";
        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
        $variable.="&codEspacio=".$_REQUEST['codEspacio'];
        $variable.="&id_grupo=".$_REQUEST['id_grupo'];
        $variable.="&grupo=".$_REQUEST['grupo'];
        $variable.="&id_grupoNuevo=".$_REQUEST['id_grupo'];
        $variable.="&grupoNuevo=".$_REQUEST['grupo'];
        $variable.="&cancelacion=reporte";
        $variable.="&nivel=".$_REQUEST['nivel'];
        foreach ($param as $key => $value) {
          $variable.="&".$key."-codEstudiante=".$value;
        }
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        echo "<script>location.replace('".$pagina.$variable."')</script>";
        exit;
    }
    
    /**
     * funcion que genera reporte de cancelacion
     * @param <array> $reporte (codEstudiante=>resultado)
     */
    function reporteCancelado() {
      
      if (isset($_REQUEST['cancelacion'])){
      foreach ($_REQUEST as $key => $value) {
        if (strstr($key,'codEstudiante'))
          {
            $reporte[strstr($key,'-',true)]=$value;
          }
       }
         $this->tablaReporte($reporte);
      }
       else
         {

         }
    }

    /**
     *  Funcion que presenta el reporte de cambio de grupo
     * @param <array> $reporte (codEstudiante=>reporte)
     */
    function tablaReporte($reporte) {
      $i=1;
        ?>
        <hr>
        <?
        $this->encabezadoReporte();
        $this->reporteNoExito($reporte,$i);
        $this->reporteExito($reporte,$i);
        ?>
        <hr><br>
        <?

    }

    /**
     * funcion que genera el encabezado para el reporte
     */
    function encabezadoReporte() {
      ?>
      <table width="100%">
        <tr>
          <caption class="sigma centrar">
            REPORTE DE CANCELACIONES
          </caption>
        </tr>
      </table>
      <?
    }

    /**
     * Funcion que genera la tabla de reporte para los cambios hechos
     * @param <array> $casosExito (codEstudiante=>reporte)
     * @param <int> $num
     */
    function reporteExito($casosExito,$num) {
      ?><table class="sigma contenidotabla"><?
        $this->encabezadoExito($casosExito);
        $this->reporteEstudiantesExito($casosExito,$num);
      ?></table><?

    }

    /**
     * Funcion que genera la tabla de reporte de los casos que no se realizó cambio
     * @param <array> $casosNoExito (codEstudiante=>reporte)
     * @param <int> $num
     */
    function reporteNoExito ($casosNoExito,$num) {
      ?><table class="sigma contenidotabla"><?
        $this->encabezadoNoExito($casosNoExito);
        $this->reporteEstudiantesNoExito($casosNoExito,$num);
      ?></table><?

    }

    /**
     * Funcion que genera el encabezado de la tabla de cambios realizados
     * @param <array> $reporte (codEstudiante=>reporte)
     */
    function encabezadoExito($reporte) {
      foreach ($reporte as $key => $value)
        {if($value=='ok')
          {
            ?>
        <tr align="center"><td colspan="7" class='cuadro_plano centrar'><b>Cancelaciones exitosas</b></td></tr>
            <tr class="cuadro_color">
              <td align="center">Nro</td>
              <td align="center">C&oacute;digo</td>
              <td colspan="2" align="center">Nombre</td>
              <td colspan="3" align="center">Proyecto</td>
            </tr>
            <?
          break;
          }
        }
    }

    /**
     * Funcion que presenta cada caso de cambio exitoso
     * @param <array> $reporte (codEstudiante=>reporte)
     * @param <int> $i
     */
    function reporteEstudiantesExito($reporte,$i) {
      foreach ($reporte as $key => $value) {
        if($value=='ok')
        {?>
        <tr><td align="center"><?echo $i?></td>
          <td align="center"><?echo $key?></td>
          <td colspan="2"><?$estudiante=$this->buscarDatosEstudiante($key);echo $estudiante[0]['NOMBRE'];?></td>
          <td colspan="3"  align="center"><?$proyecto=$this->buscarDatosProyecto($estudiante[0]['PROYECTO']);echo $proyecto[0]['NOMBRE'];?></td>
        </tr>
        <?
        $i++;
        }
      }
    }

    /**
     * Funcion que genera el encabezado de la tabla de casos no realizados
     * @param <array> $reporte (codEstudiante=>reporte)
     */
    function encabezadoNoExito($reporte) {
      foreach ($reporte as $key => $value)
        {if($value!='ok')
          {
            ?>
        <tr align="center"><td colspan="7" class='cuadro_plano centrar'><font color="#F90101">Para los siguientes estudiantes <b>NO</b> se realiz&oacute; la cancelaci&oacute;n</font></td></tr>
            <tr class="cuadro_color">
              <td align="center">Nro</td>
              <td align="center">C&oacute;digo</td>
              <td align="center">Nombre</td>
              <td colspan="2" align="center">Proyecto</td>
              <td colspan="2" align="center">Descripci&oacute;n</td>
            </tr>
            <?
          break;
          }
        }
    }

    /**
     * Funcion que presenta cada caso no realizado
     * @param <array> $reporte (codEstudiante=>reporte)
     * @param <int> $i
     */
    function reporteEstudiantesNoExito($reporte,$i) {
      foreach ($reporte as $key => $value) {
        if($value!='ok')
          {?>
          <tr><td align="center"><?echo $i?></td>
            <td align="center"><?echo $key?></td>
            <td><?$estudiante=$this->buscarDatosEstudiante($key);echo $estudiante[0]['NOMBRE'];?></td>
            <td colspan="2" align="center"><?$proyecto=$this->buscarDatosProyecto($estudiante[0]['PROYECTO']);echo $proyecto[0]['NOMBRE'];?></td>
            <td colspan="2" align="center"><?echo $value?></td>
          </tr>
          <?
          $i++;
          }
      }
    }

    /**
     * Funcion que consulta los datos del estudiante inscrito
     * @param <int> $codigo Codigo del estudiante
     * @return <array> $arreglo_datosEstudiante (CODIGO,NOMBRE,PROYECTO,ESTADO,PLAN)
     */
    function buscarDatosEstudiante($codigo) {
          $variablesDatosEstudiante = array( 'codEstudiante'=>$codigo );

          $cadena_sql = $this->sql->cadena_sql("buscarDatosEstudiantes", $variablesDatosEstudiante);
          $arreglo_datosEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_datosEstudiante;
    }

    /**
     * Funcion que consulta los datos del proyecto al que pertenece el grupo
     * @return <array> $arreglo_proyecto (CODIGO,NOMBRE)
     */
    function buscarDatosProyecto($codProyectoEstudiante) {

          $variablesProyecto = array( 'codProyecto' => $codProyectoEstudiante);

          $cadena_sql = $this->sql->cadena_sql("buscarDatosProyecto", $variablesProyecto);
          $arreglo_proyecto = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_proyecto;
    }

/**
     * Funcion para consultar los proyectos a cargo de una persona, consultando por su identificacion
     * @param type $identificacion
     * @return type 
     */
    function consultarProyectos($identificacion){
            $cadena_sql = $this->sql->cadena_sql("proyectos_curriculares", $identificacion);
            return $arreglo_proyecto = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Funcion para verificar si un codigo de proyecto se encuentra en los codigos validos para un usuario
     * @param type $proyectos
     * @param type $codProyecto
     * @return int 
     */
    function validarProyecto($proyectos, $codProyecto){
            $valido=0;
            foreach ($proyectos as $key => $proyecto) {
                if($proyecto['CRA_COD']==$codProyecto){
                $valido=1; 
                break;
                }
            }
            return $valido;
    }
    
}


?>

