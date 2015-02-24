<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");

/*
 *@ Esta clase presenta los espacios academicos que se pueden inscribir a un estudiante de Horas.
 */

class funcion_adminBuscarEspacioEstudianteCoorHoras extends funcionGeneral {      //Crea un objeto tema y un objeto SQL.

  private $configuracion;
  private $ano;
  private $periodo;

    function __construct($configuracion, $sql) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/administrarModulo.class.php");
    include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");


//        $this->administrar=new administrarModulo();
//        $this->administrar->administrarModuloSGA($configuracion, '4');
    $this->configuracion = $configuracion;
    $this->validacion = new validarInscripcion();
    $this->cripto = new encriptar();
    //$this->tema = $tema;
    $this->sql = $sql;

    //Conexion ORACLE
    $this->accesoOracle = $this->conectarDB($configuracion, "coordinadorCred");

    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");
    $this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Datos de sesion
    $this->formulario = "admin_buscarGruposProyectoHoras";
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");

    $cadena_sql = $this->sql->cadena_sql("periodoActivo", ''); 
    $resultado_periodo = $this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    $this->ano = $resultado_periodo[0]['ANO'];
    $this->periodo = $resultado_periodo[0]['PERIODO'];
  }

    /**
     * Funciom que presenta los espacios que puede adicionar al estudiante
     * Utiliza los metodos mostrarEspacios, retorno
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina, opcion, codProyecto, planEstudio, codEstudiante, codProyectoEstudiante, planEstudioEstudiante, creditosInscritos, estado_est)
     */
    function consultarEspaciosPermitidos() {
    $_REQUEST['ano']=$this->ano;
    $_REQUEST['periodo']=$this->periodo;
    $resultado_planEstudio=$this->consultaEspaciosPermitidos();
    ?><table width="70%" align="center" border="0" >
        <tr class="bloquelateralcuerpo">
          <td class="centrar">
            <?
            $this->enlaceHorario();
            ?>
          </td>
        </tr>
      </table>
    <?
      if (is_array($resultado_planEstudio))
        {
          $this->mostrarEspacios($resultado_planEstudio, $_REQUEST);
        }
        else
          {
          $this->mensajeNoEspacios();
          }
        $this->retorno($_REQUEST);
    }

    /**
     * Funcion que muestra los espacios academicos que se pueden registrar al estudiante
     * listadoEspacios es una matriz de los espacios que puede inscribir al estudiante
     * @param <array> $listadoEspacios (ASICOD,NOMBRE,NIVEL,ELECTIVA,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
     */
    function mostrarEspacios($listadoEspacios, $datosGenerales) {
        $datosEquivalentes=array('codProyectoEstudiante'=>$datosGenerales['codProyectoEstudiante']);
        $equivalentes=$this->consultarEspaciosEquivalentes($datosEquivalentes);
        
      ?>
      <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
        <caption class="sigma">
          <center>
            ESPACIOS PERMITIDOS
          </center>
        </caption>
        <tr>
          <td>
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
              <tr >
                <th class="sigma centrar" width="10%"><b>C&oacute;digo Espacio</b></th>
                <th class="sigma centrar" width="40%"><b>Nombre Espacio</b></th>
                <th class="sigma centrar" width="8%"><b>Clasificaci&oacute;n</b></th>
                <th class="sigma centrar" width="15%"><b>Adicionar</b></th>
              </tr>
              <?
                for ($i = 0; $i < count($listadoEspacios); $i++)
                {
                    if ((isset($listadoEspacios[$i - 1]['NIVEL'])?$listadoEspacios[$i - 1]['NIVEL']:'') != $listadoEspacios[$i]['NIVEL'])
                    {
                    ?>
                        <tr>
                        <td class="sigma_a cuadro_plano centrar" colspan="6"><font size="2"> NIVEL <? echo $listadoEspacios[$i]['NIVEL'] ?></font></td>
                        </tr>
                    <?
                    }
                    if (trim($listadoEspacios[$i]['ELECTIVA']) == 'S')
                    {
                        $clasificacion = "<font color='#088A08'>Electivo</font>";
                    }else{
                        $clasificacion = 'Obligatorio';
                    }
                    ?>
                    <tr onmouseover="this.style.background='#FFFFAA'" onmouseout="this.style.background=''">
                        <td class='cuadro_plano centrar'><? echo $listadoEspacios[$i]['ASICOD'] ?></td>
                        <td class='cuadro_plano '><? echo $listadoEspacios[$i]['NOMBRE'] ?></td>
                        <td class='cuadro_plano centrar'><? echo $clasificacion ?></td>
                        <td class='cuadro_plano centrar'><? $this->enlaceAdicionar($listadoEspacios[$i], $datosGenerales) ?></td>
                    </tr>
                    <?
                    if (is_array($equivalentes))
                    {
                        foreach ($equivalentes as $espacio => $value)
                        {
                            if($value['ASI_COD_ANTERIOR']== $listadoEspacios[$i]['ASICOD']||(isset($value['ASI_COD_ANTERIOR2'])?$value['ASI_COD_ANTERIOR2']:'')== $listadoEspacios[$i]['ASICOD'])
                            {
                            ?>
                                <tr onmouseover="this.style.background='#F4FA58'" onmouseout="this.style.background=''">
                                    <td class='cuadro_plano derecha'><font color='#08088A'><? echo $value['ASICOD'] ?></font></td>
                                    <td class='cuadro_plano '><font color='#08088A'><? echo $value['NOMBRE']?></font> <b>Equivalente</b></td>
                                    <td class='cuadro_plano centrar'><? echo $clasificacion ?></td>
                                    <td class='cuadro_plano centrar'><? $this->enlaceAdicionar($value, $datosGenerales) ?></td>
                                </tr>
                            <?
                            }
                        }
                    }
                }
          ?>
            </table>
          </td>
        </tr>
      </table>
      <?
    }

    /**
     * Funcion que genera el enlace para regresar al horario del estudiante
     */
    function enlaceHorario() {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable=$this->variablesRetorno();
        ?>
        <a href="<?= $pagina . $variable ?>" >
          <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/vcalendar.png" width="35" height="35" border="0"><br>
          <b>Horario Estudiante</b>
        </a>
        <?
    }

    /**
     * Funcion que genera las variables para enlaces de retorno
     * @return <type>
     */
    function variablesRetorno() {

        $variable = "pagina=admin_consultarInscripcionEstudianteCoorHoras";
        $variable.="&opcion=mostrarConsulta";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
        $variable.="&codProyectoEstudiante=" . $_REQUEST['codProyectoEstudiante'];
        $variable.="&planEstudioEstudiante=" . $_REQUEST['planEstudioEstudiante'];
        $variable.="&codEstudiante=" . $_REQUEST['codEstudiante'];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        return $variable;
}

    /**
     * Funcion que muestra enlace para regresar a la pagina de consulta de inscripciones del estudainte
     * @param <array> $retorno (codProyecto,planEstudio,codEstudiante,codProyectoEstudiante,planEstudioEstudiante,estado_est,ano,periodo)
     */
    function retorno() {
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variablesPag=$this->variablesRetorno();
        ?>
        <table class="cuadro_color centrar" width="100%">
          <tr class="centrar">
            <td colspan="3">
              <a href="<?= $pagina . $variablesPag ?>" >
                <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="25" height="25" border="0"><br>
                <font size="2"><b>Regresar</b></font>
              </a>
            </td>
          </tr>
        <!--            <tr class="cuadro_plano centrar">
              <th>
                  Observaciones
              </th>
          </tr>
          <tr class="cuadro_plano">
              <td>
                  * Si la casilla de adici&oacute;n est&aacute; en <font color="#F90101">rojo</font>, significa que el grupo presenta sobrecupo
              </td>
          </tr>-->
        </table>
<?
      }

    /**
     * Funcion que muestra enlace para adicionar un espacio
     * @param <array> $datosEspacio (ASICOD,NOMBRE,NIVEL,ELECTIVA,CREDITOS)
     * @param <array> $datosGenerales (codProyecto,planEstudio,codProyectoEstudiante,planEstudioEstudiante,codEstudiante,creditosInscritos,estado_est,ano,periodo)
     */
    function enlaceAdicionar($datosEspacio, $datosGenerales) {
//        foreach ($datosGenerales as $key => $value) {
//          echo$key."=>".$value."<br>";
//        }
//        exit;
        $parametro = "=";
?>
        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
          <input type="hidden" name="codProyecto" value="<? echo $datosGenerales['codProyecto'] ?>">
          <input type="hidden" name="planEstudio" value="<? echo $datosGenerales['planEstudio'] ?>">
          <input type="hidden" name="codEstudiante" value="<? echo $datosGenerales['codEstudiante'] ?>">
          <input type="hidden" name="codProyectoEstudiante" value="<? echo $datosGenerales['codProyectoEstudiante'] ?>">
          <input type="hidden" name="planEstudioEstudiante" value="<? echo $datosGenerales['planEstudioEstudiante'] ?>">
          <input type="hidden" name="estado_est" value="<? echo $datosGenerales['estado_est'] ?>">
          <input type="hidden" name="creditos" value="<? echo (isset($datosEspacio['CREDITOS'])?$datosEspacio['CREDITOS']:'') ?>">
          <input type="hidden" name="codEspacio" value="<? echo $datosEspacio['ASICOD'] ?>">
          <input type="hidden" name="nombreEspacio" value="<? echo $datosEspacio['NOMBRE'] ?>">
          <input type="hidden" name="parametro" value="<? echo $parametro ?>">
          <input type="hidden" name="opcion" value="validar">
          <input type="hidden" name="action" value="<? echo $this->formulario ?>">
          <input type="hidden" name="destino" value="registro_adicionarEspacioEstudianteCoorHoras">
          <input type="image" name="adicion" width="20" height="20" src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/clean.png" >
        </form>
<?
      }

    /**
     * Funcion que presenta mensaje cunado no hay mensajes para inscribir
     */
    function mensajeNoEspacios() {
          ?>
            <table class='contenidotabla' align='center' width='80%' cellpadding='2' cellspacing='2'>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
              <tr>
                <th class='cuadro_plano centrar' colspan="6">No se encontraron espacios acad&eacute;micos para adicionar.</th>
              </tr>
              <tr>
                <th colspan="5">&zwnj;</th>
              </tr>
            </table>
          <?
      }

    /**
     * Funcion que permite consultar los espacios permitidos para cursar por el estudiante
     * @return <array>
     */
    function consultaEspaciosPermitidos() {
          $_REQUEST['nota']=$this->consultarNotaAprobatoria();
          $_REQUEST['aprobados']=$this->consultarEspaciosAprobados($_REQUEST['nota']);
          $_REQUEST['inscritos']=$this->consultarEspaciosInscritos();
          $cadena_sql = $this->sql->cadena_sql("espacios_plan_estudio", $_REQUEST);
          return $resultado_planEstudio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      }

    /**
     * Funcion que permite consultar los espacios aprobados por el estudiante
     * @return <string/0>
     */
    function consultarEspaciosAprobados($nota) {
          $aprobados='';
          $_REQUEST['nota']=$nota;
          $cadena_sql = $this->sql->cadena_sql("espacios_aprobados", $_REQUEST);
          $resultado_espaciosAprobados = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          if (is_array($resultado_espaciosAprobados)){
          foreach ($resultado_espaciosAprobados as $key => $value) {
            $aprobados.=$value['NOT_ASI_COD'].',';
          }
          return rtrim($aprobados, ',');
          }
          else
            {
              return 0;
            }
      }

    /**
     * Funcion que permite consultar los espacios inscritos por el estudiante en el periodo
     * @return <string/0>
     */
    function consultarEspaciosInscritos() {
          $inscritos='';
          $cadena_sql = $this->sql->cadena_sql("espacios_inscritos", $_REQUEST);
          $resultado_inscritos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          if (is_array($resultado_inscritos)){
            foreach ($resultado_inscritos as $key => $value) {
              $inscritos.=$value['INS_ASI_COD'].',';
            }
            return rtrim($inscritos, ',');
          }
          else
          {
            return 0;
          }
      }

    /**
     * Funcion que permite consultar la nota aprobatoria para el proyecto del estudiante
     * @return <int>
     */
    function consultarNotaAprobatoria() {
          $cadena_sql = $this->sql->cadena_sql("nota_aprobatoria", $_REQUEST);
          $resultado_nota = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $resultado_nota[0][0];
      }

    /**
     * Funcion que permite consultar los espacios equivalentes de los permitidos  para cursar por el estudiante
     * @return <array>
     */
    function consultarEspaciosEquivalentes($datosEquivalencia) {
          $cadena_sql = $this->sql->cadena_sql("espacios_equivalentes", $datosEquivalencia);
          return $resultado_planEstudio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
      }

  }
?>