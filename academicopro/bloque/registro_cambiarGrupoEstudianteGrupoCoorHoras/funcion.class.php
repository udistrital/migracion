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


class funcion_registroCambiarGrupoEstudianteGrupoCoorHoras extends funcionGeneral
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
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinadorCred");

            //Conexion General
            $this->acceso_db=$this->conectarDB($configuracion,"");
            $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

            //Datos de sesion
            $this->formulario="registro_cambiarGrupoEstudianteCoorHoras";
            $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
            $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
            $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
            $cadena_sql=$this->sql->cadena_sql("periodoActivo",'');//echo $cadena_sql;exit;
            $resultado_periodo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );
            $this->ano=$resultado_periodo[0]['ANO'];
            $this->periodo=$resultado_periodo[0]['PERIODO'];
    }

    /**
     * Funcion que verifica la existencia de estudiantes para cambio de grupo
     * @return array
     */
    function estudiantesCambio()
    {
//    foreach ($_REQUEST as $key => $value) {
//      echo $key."=>".$value."<br>";
//    }exit;
      $i=0;
      $estudiantes=array();
      $cruzados=array();
      foreach ($_REQUEST as $key => $value) {
        if (strstr($key,'codEstudiante'))
          {
            $estudiantes[$i]=array(codEspacio=>$_REQUEST['codEspacio'],
                                   codProyecto=>$_REQUEST['carrera'],
                                   grupo=>$_REQUEST['grupo'],
                                   codEstudiante=>strstr($value,'-',true));
            $valor[strstr($value,'-',true)]=$this->validacion->validarCruceHorario($estudiantes[$i]);
            //$valor[$value]=$this->validacion->validarCruce($estudiantes[$i]);
            $i++;
          }else{}
      }
      foreach ($valor as $key => $value) {
        if ($value=="ok")
          {
            $permitidos[$key]=$value;
          }
      }
//      echo "para los siguientes estudiantes no se presenta cruce de horario:<br>";
//      foreach ($valor as $key => $value) {
//        if($value=="ok"){
//        echo $key."=>".$value."<br>";}
//      }exit;
      return $permitidos;
    }

    /**
     * Funcion que verifica la existencia de estudiantes con cruce
     * @return array
     */
    function estudiantesNoCambio()
    {
//    foreach ($_REQUEST as $key => $value) {
//      echo $key."=>".$value."<br>";
//    }exit;
      $i=0;
      $estudiantes=array();
      $cruzados=array();
      foreach ($_REQUEST as $key => $value) {
        if (strstr($key,'codEstudiante'))
          {
            $estudiantes[$i]=array(codEspacio=>$_REQUEST['codEspacio'],
                                   codProyecto=>$_REQUEST['carrera'],
                                   grupo=>$_REQUEST['grupo'],
                                   codEstudiante=>strstr($value,'-',true));
            $valor[strstr($value,'-',true)]=$this->validacion->validarCruceHorario($estudiantes[$i]);
            //$valor[$value]=$this->validacion->validarCruce($estudiantes[$i]);
            $i++;
          }else{}
      }
      foreach ($valor as $key => $value) {
        if ($value!="ok")
          {
            $noCambio[$key]="Presenta cruce con ".$value['ESPACIOCRUCE'];
          }
      }
//      echo "para los siguientes estudiantes no se presenta cruce de horario:<br>";
//      foreach ($valor as $key => $value) {
//        if($value=="ok"){
//        echo $key."=>".$value."<br>";}
//      }exit;
      return $noCambio;
    }

    /**
     * Esta función registra el cambio de grupo de un estudiante de Horas
     * @param <array> $this->configuracion
     * @param <array> $_REQUEST (pagina,opcion,codProyecto;planEstudio,codEspacio,codProyectoEstudiante,planEstudioEstudiante,
     *                          codEstudiante,grupo,carrera,grupoAnterior,retorno,opcionRetorno)
     */
    function realizarCambioSeleccionados() {

        //verifica cruce
        $estudiantesCambio=$this->estudiantesCambio();
        $mensaje=$this->estudiantesNoCambio();
        //parametros de retorno
//        foreach ($_REQUEST as $key => $value) {
//          echo $key."=>".$value."<br>";
//        }
//        exit;
        $retorno['pagina']=$_REQUEST["retorno"];
        $retorno['opcion']=$_REQUEST["opcionRetorno"];
        $retorno['parametros']="&codProyecto=".$_REQUEST["codProyecto"];
        $retorno['parametros'].="&planEstudio=".$_REQUEST["planEstudio"];
        $retorno['parametros'].="&codEspacio=".$_REQUEST["codEspacio"];
        $retorno['parametros'].="&grupo=".$_REQUEST['grupoAnterior'];
        $_REQUEST['ano']=$this->ano;
        $_REQUEST['periodo']=$this->periodo;
        //retorno
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $variable="pagina=".$retorno['pagina'];
        $variable.="&opcion=".$retorno['opcion'];
        $variable.=$retorno['parametros'];
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
//        foreach ($estudiantesCambio as $key => $value) {
//          echo $key."=>".$value."<br>";
//        }exit;
        if(!is_array($estudiantesCambio))
        {if (is_array($mensaje))
          {
            $this->reporteGrupo($mensaje);
          }
          else {
            echo "No ha seleccionado estudiantes";exit;
            }
        }
        //realiza cambio en Oracle
        $i=0;
        foreach ($estudiantesCambio as $codEstudiante => $value) {
        $datosInscripcion=array(codEstudiante=>$codEstudiante,
                                planEstudio=>$_REQUEST["planEstudio"]);
        $datosEstudiante=$this->validacion->validarEstudiante($datosInscripcion);
        $_REQUEST['codEstudiante']=$codEstudiante;
        $_REQUEST['codProyectoEstudiante']=$datosEstudiante['codProyectoEstudiante'];
        $_REQUEST['planEstudioEstudiante']=$datosEstudiante['planEstudioEstudiante'];
        $mensaje[$codEstudiante]=$this->cambiarGrupoEstudiante();
          }
                //actualiza cupo a grupo nuevo
                $this->procedimiento->actualizarCupo($_REQUEST);
                //actualiza cupo a grupo anterior
                $grupo=$_REQUEST['grupo'];
                $_REQUEST['grupo']=$_REQUEST['grupoAnterior'];
                $this->procedimiento->actualizarCupo($_REQUEST);
                $_REQUEST['grupo']=$grupo;
                //$this->tablaReporte($mensaje);
                $this->reporteGrupo($mensaje);

    }

    function cambiarGrupoEstudiante() {
        $actualizo=$this->cambiarGrupoEspacioEstudianteOracle();
        if($actualizo>=1)
        {
          $variablesRegistro=array(usuario=>$this->usuario,
                                    evento=>'3',
                                    descripcion=>'Cambio grupo del Espacio Académico',
                                    registro=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].",".$_REQUEST['grupoAnterior'].",".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],
                                    afectado=>$_REQUEST['codEstudiante']);

                $this->procedimiento->registrarEvento($variablesRegistro);
                $mensaje="ok";
        }
        else
          {
              $mensaje="La base de datos O se encuentra ocupada, por favor intente mas tarde";
              $variablesRegistro=array(usuario=>$this->usuario,
                                      evento=>'50',
                                      descripcion=>'Conexion Error Oracle Cambio por Grupo',
                                      registro=>$this->ano."-".$this->periodo.", ".$_REQUEST['codEspacio'].", ".$_REQUEST['grupoAnterior'].", ".$_REQUEST['grupo'].", ".$_REQUEST['planEstudio'].", ".$_REQUEST['carrera'],
                                      afectado=>$_REQUEST['codEstudiante']);
              $this->procedimiento->registrarEvento($variablesRegistro);
          }
      return $mensaje;
    }

    /**
     * Funcion que actualiza registro de oracle por cambio de grupo
     * @return <int>
     */
    function cambiarGrupoEspacioEstudianteOracle() {
        $cadena_sql=$this->sql->cadena_sql("actualizar_grupo_espacio_oracle", $_REQUEST);//echo $cadena_sql;//exit;
        $resultado_actualizarGrupo=$this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );
        return $this->totalAfectados($this->configuracion, $this->accesoOracle);
      }

    /**
     * Funcion que genera arreglo para enviar al reporte
     * @param <array> $param (codEstudiante=>reporte)
     */
    function reporteGrupo($param) {
        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_estudiantesInscritosGrupoCoorHoras";
        $variable.="&opcion=verGrupo";
        $variable.="&codProyecto=".$_REQUEST['codProyecto'];
        $variable.="&planEstudio=".$_REQUEST['planEstudio'];
        $variable.="&codEspacio=".$_REQUEST['codEspacio'];
        $variable.="&grupo=".$_REQUEST['grupoAnterior'];
        $variable.="&grupoNuevo=".$_REQUEST['grupo'];
        $variable.="&reporte=reporte";
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
     * Funcion que genera reporte de cambio de grupo
     * @param <array> $reporte (codEstudiante=>resultado)
     */
    function reporteCambio() {
      
      if (isset($_REQUEST['reporte'])){
      foreach ($_REQUEST as $key => $value) {
        if (strstr($key,'codEstudiante'))
          {
            $reporte[strstr($key,'-',true)]=$value;
          }
       }
//       var_dump($reporte)  ;exit;
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
            REPORTE DE CAMBIO DE GRUPO
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
        <tr align="center"><td colspan="7" class='cuadro_plano centrar'><b>Cambios de grupo exitosos</b></td></tr>
            <tr class="cuadro_color">
              <td align="center">Nro</td>
              <td align="center">C&oacute;digo</td>
              <td colspan="2" align="center">Nombre</td>
              <td colspan="2" align="center">Proyecto</td>
              <td align="center">Grupo Nuevo</td>
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
          <td colspan="2"  align="center"><?$proyecto=$this->buscarDatosProyecto($estudiante[0]['PROYECTO']);echo $proyecto[0]['NOMBRE'];?></td>
          <td align="center"><?echo $_REQUEST['grupoNuevo']?></td>
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
        <tr align="center"><td colspan="7" class='cuadro_plano centrar'><font color="#F90101">Para los siguientes estudiantes <b>NO</b> se realiz&oacute; el cambio de grupo</font></td></tr>
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
          $variablesDatosEstudiante = array( codEstudiante=>$codigo );

          $cadena_sql = $this->sql->cadena_sql("buscarDatosEstudiantes", $variablesDatosEstudiante);//echo $cadena_sql;
          $arreglo_datosEstudiante = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          //var_dump($arreglo_datosEstudiante);exit;
          return $arreglo_datosEstudiante;
    }

    /**
     * Funcion que consulta los datos del proyecto al que pertenece el grupo
     * @return <array> $arreglo_proyecto (CODIGO,NOMBRE)
     */
    function buscarDatosProyecto($codProyectoEstudiante) {

          $variablesProyecto = array( codProyecto => $codProyectoEstudiante);

          $cadena_sql = $this->sql->cadena_sql("buscarDatosProyecto", $variablesProyecto);
          $arreglo_proyecto = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
          return $arreglo_proyecto;
    }

    /**
     * Funcion que genera el enlace de retorno a la pagina de consulta del grupo
     */
    function enlaceRetorno() {

    ?>
      <td colspan="7" class="centrar">
        <?
        $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
        $variable = "pagina=admin_estudiantesInscritosGrupoCoorHoras";
        $variable.="&opcion=verGrupo";
        $variable.="&codProyecto=" . $_REQUEST['codProyecto'];
        $variable.="&planEstudio=" . $_REQUEST['planEstudio'];
        $variable.="&codEspacio=" . $_REQUEST['codEspacio'];
        $variable.="&grupo=" . $_REQUEST['grupoAnterior'];

        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
        $this->cripto = new encriptar();
        $variable = $this->cripto->codificar_url($variable, $this->configuracion);
        ?>
        <a href="<?= $pagina . $variable ?>" >
          <img src="<? echo $this->configuracion['site'] . $this->configuracion['grafico'] ?>/go-first.png" width="35" height="35" border="0"><br><b>Regresar</b>
        </a>
      </td>
    <?
    }

}
//        foreach ($_REQUEST as $key => $value) {
//          echo $key."=>".$value."<br>";
//        }
//        exit;


?>